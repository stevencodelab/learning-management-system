<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizTakingController extends Controller
{
    /**
     * Start a quiz attempt
     */
    public function start(Quiz $quiz)
    {
        // Check if quiz is available
        if (!$quiz->isAvailable()) {
            return back()->withErrors(['error' => 'This quiz is not available at this time.']);
        }

        $user = Auth::user();

        // Check if user can attempt
        if (!$quiz->canUserAttempt($user->id)) {
            return back()->withErrors(['error' => 'You have reached the maximum number of attempts for this quiz.']);
        }

        // Check if there's an in-progress attempt
        $inProgressAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->whereNull('completed_at')
            ->first();

        if ($inProgressAttempt) {
            return redirect()->route('quiz.taking.show', ['quiz' => $quiz, 'attempt' => $inProgressAttempt]);
        }

        // Get questions for this attempt
        $questions = $quiz->getQuestionsForAttempt();

        if ($questions->isEmpty()) {
            return back()->withErrors(['error' => 'This quiz has no questions.']);
        }

        // Create new attempt
        $attemptNumber = $quiz->getUserAttemptNumber($user->id);
        
        DB::beginTransaction();
        try {
            $attempt = QuizAttempt::create([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'attempt_number' => $attemptNumber,
                'total_questions' => $questions->count(),
                'total_points' => $questions->sum('points'),
                'started_at' => now(),
                'completed_at' => null,
                'submitted' => false,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to start quiz.']);
        }

        return redirect()->route('quiz.taking.show', ['quiz' => $quiz, 'attempt' => $attempt]);
    }

    /**
     * Show quiz taking interface
     */
    public function show(Quiz $quiz, QuizAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($attempt->submitted) {
            return redirect()->route('quiz.taking.result', ['quiz' => $quiz, 'attempt' => $attempt]);
        }

        $quiz->load('questions.answers');
        
        // Get questions for this attempt
        $questions = $quiz->getQuestionsForAttempt();
        
        // Get user answers - handle multiple answers per question
        $userAnswers = [];
        foreach ($attempt->userAnswers as $userAnswer) {
            $qId = $userAnswer->quiz_question_id;
            if (!isset($userAnswers[$qId])) {
                $userAnswers[$qId] = [];
            }
            $userAnswers[$qId][] = $userAnswer->quiz_answer_id;
        }

        return view('quizzes.taking.show', compact('quiz', 'attempt', 'questions', 'userAnswers'));
    }

    /**
     * Save user answers during quiz
     */
    public function saveAnswer(Request $request, Quiz $quiz, QuizAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($attempt->submitted) {
            return response()->json(['success' => false, 'message' => 'Quiz already submitted']);
        }

        $validated = $request->validate([
            'question_id' => 'required|exists:quiz_questions,id',
            'answer_ids' => 'required|array',
            'answer_ids.*' => 'exists:quiz_answers,id',
        ]);

        DB::beginTransaction();
        try {
            // Delete existing answers for this question
            UserAnswer::where('quiz_attempt_id', $attempt->id)
                ->where('quiz_question_id', $validated['question_id'])
                ->delete();

            // Create new user answers
            foreach ($validated['answer_ids'] as $answerId) {
                UserAnswer::create([
                    'user_id' => Auth::id(),
                    'quiz_attempt_id' => $attempt->id,
                    'quiz_question_id' => $validated['question_id'],
                    'quiz_answer_id' => $answerId,
                    'is_correct' => false, // Will be calculated on submission
                ]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to save answer']);
        }
    }

    /**
     * Submit quiz
     */
    public function submit(Request $request, Quiz $quiz, QuizAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($attempt->submitted) {
            return redirect()->route('quiz.taking.result', ['quiz' => $quiz, 'attempt' => $attempt]);
        }

        DB::beginTransaction();
        try {
            // Calculate score
            $score = $this->calculateScore($attempt, $quiz);
            
            $attempt->update([
                'score' => $score['score'],
                'total_points' => $score['total_points'],
                'correct_answers' => $score['correct'],
                'incorrect_answers' => $score['incorrect'],
                'unanswered_questions' => $score['unanswered'],
                'submitted' => true,
                'completed_at' => now(),
                'answers_review' => $score['review'],
            ]);

            // Mark as passed
            $attempt->markAsPassed();
            $attempt->save();

            DB::commit();

            if ($quiz->show_results_immediately) {
                return redirect()->route('quiz.taking.result', ['quiz' => $quiz, 'attempt' => $attempt]);
            } else {
                return redirect()->route('quiz.show', $quiz)
                    ->with('success', 'Quiz submitted successfully. Your results will be available soon.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to submit quiz.']);
        }
    }

    /**
     * Show quiz results
     */
    public function result(Quiz $quiz, QuizAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $quiz->load(['questions.answers']);
        $userAnswers = $attempt->userAnswers;

        return view('quizzes.taking.result', compact('quiz', 'attempt', 'userAnswers'));
    }

    /**
     * Calculate quiz score
     */
    private function calculateScore($attempt, $quiz)
    {
        $questions = $quiz->questions;
        $userAnswers = $attempt->userAnswers;
        
        // Build answers map - handle multiple answers per question
        $answersMap = [];
        foreach ($userAnswers as $userAnswer) {
            $qId = $userAnswer->quiz_question_id;
            if (!isset($answersMap[$qId])) {
                $answersMap[$qId] = [];
            }
            $answersMap[$qId][] = $userAnswer->quiz_answer_id;
        }

        $score = 0;
        $correct = 0;
        $incorrect = 0;
        $unanswered = 0;
        $review = [];

        foreach ($questions as $question) {
            $userAnswerIds = $answersMap[$question->id] ?? [];
            
            if (empty($userAnswerIds)) {
                $unanswered++;
                $review[$question->id] = [
                    'question_id' => $question->id,
                    'user_answer' => null,
                    'is_correct' => false,
                    'earned_points' => 0,
                    'total_points' => $question->points,
                ];
                continue;
            }

            // Check if answer is correct
            $correctAnswers = $question->answers()->where('is_correct', true)->pluck('id')->toArray();
            sort($correctAnswers);
            sort($userAnswerIds);
            
            $isCorrect = $correctAnswers === $userAnswerIds;

            // Update user answers with correctness
            foreach ($userAnswers as $userAnswer) {
                if ($userAnswer->quiz_question_id == $question->id) {
                    $answer = $question->answers()->where('id', $userAnswer->quiz_answer_id)->first();
                    $userAnswer->update(['is_correct' => $answer && $answer->is_correct && $isCorrect]);
                }
            }

            if ($isCorrect) {
                $score += $question->points;
                $correct++;
                $earnedPoints = $question->points;
            } else {
                $incorrect++;
                // Apply negative marking if enabled
                if ($quiz->negative_marking) {
                    $earnedPoints = -($question->points * ($quiz->negative_mark_value ?? 0.25));
                    $score += $earnedPoints;
                } else {
                    $earnedPoints = 0;
                }
            }

            $review[$question->id] = [
                'question_id' => $question->id,
                'user_answer' => $userAnswerIds,
                'is_correct' => $isCorrect,
                'earned_points' => $earnedPoints,
                'total_points' => $question->points,
            ];
        }

        return [
            'score' => max(0, $score), // Ensure non-negative
            'total_points' => $questions->sum('points'),
            'correct' => $correct,
            'incorrect' => $incorrect,
            'unanswered' => $unanswered,
            'review' => $review,
        ];
    }

    /**
     * Get user progress
     */
    public function progress(Quiz $quiz, QuizAttempt $attempt)
    {
        $answers = $attempt->userAnswers()->count();
        $total = $attempt->total_questions;
        $percentage = $total > 0 ? ($answers / $total) * 100 : 0;

        return response()->json([
            'answered' => $answers,
            'total' => $total,
            'percentage' => round($percentage, 2),
        ]);
    }
}

