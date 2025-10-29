<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizQuestionController extends Controller
{
    /**
     * Check if user has permission to manage quiz questions
     */
    private function checkManagePermission()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'instructor'])) {
            abort(403, 'Only admins and instructors can manage quiz questions.');
        }
    }

    /**
     * Display all questions for a quiz
     */
    public function index(Quiz $quiz)
    {
        $this->checkManagePermission();
        $quiz->load(['questions.answers']);
        
        return view('quizzes.questions.index', compact('quiz'));
    }

    /**
     * Show the form for creating a new question
     */
    public function create(Quiz $quiz)
    {
        $this->checkManagePermission();
        $questionTypes = [
            'multiple_choice' => 'Multiple Choice',
            'true_false' => 'True/False',
            'fill_blank' => 'Fill in the Blank',
        ];

        return view('quizzes.questions.create', compact('quiz', 'questionTypes'));
    }

    /**
     * Store a newly created question
     */
    public function store(Request $request, Quiz $quiz)
    {
        $this->checkManagePermission();
        $validated = $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,fill_blank',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'order' => 'nullable|integer',
            'answers' => 'required|array|min:1',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Create the question
            $question = $quiz->questions()->create([
                'question_text' => $validated['question_text'],
                'type' => $validated['type'],
                'points' => $validated['points'],
                'explanation' => $validated['explanation'] ?? null,
                'difficulty' => $validated['difficulty'] ?? 'medium',
                'order' => $validated['order'] ?? ($quiz->questions()->count() + 1),
            ]);

            // Create answers
            foreach ($validated['answers'] as $answerData) {
                $question->answers()->create([
                    'answer_text' => $answerData['text'],
                    'is_correct' => $answerData['is_correct'],
                ]);
            }

            DB::commit();

            return redirect()->route('quiz.questions.index', $quiz)
                ->with('success', 'Question created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create question.'])->withInput();
        }
    }

    /**
     * Show the form for editing a question
     */
    public function edit(Quiz $quiz, QuizQuestion $question)
    {
        $this->checkManagePermission();
        $question->load('answers');
        $questionTypes = [
            'multiple_choice' => 'Multiple Choice',
            'true_false' => 'True/False',
            'fill_blank' => 'Fill in the Blank',
        ];

        return view('quizzes.questions.edit', compact('quiz', 'question', 'questionTypes'));
    }

    /**
     * Update a question
     */
    public function update(Request $request, Quiz $quiz, QuizQuestion $question)
    {
        $this->checkManagePermission();
        $validated = $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,fill_blank',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'order' => 'nullable|integer',
            'answers' => 'required|array|min:1',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Update the question
            $question->update([
                'question_text' => $validated['question_text'],
                'type' => $validated['type'],
                'points' => $validated['points'],
                'explanation' => $validated['explanation'] ?? null,
                'difficulty' => $validated['difficulty'] ?? $question->difficulty,
                'order' => $validated['order'] ?? $question->order,
            ]);

            // Delete old answers and create new ones
            $question->answers()->delete();
            
            foreach ($validated['answers'] as $answerData) {
                $question->answers()->create([
                    'answer_text' => $answerData['text'],
                    'is_correct' => $answerData['is_correct'],
                ]);
            }

            DB::commit();

            return redirect()->route('quiz.questions.index', $quiz)
                ->with('success', 'Question updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update question.'])->withInput();
        }
    }

    /**
     * Delete a question
     */
    public function destroy(Quiz $quiz, QuizQuestion $question)
    {
        $this->checkManagePermission();
        $question->delete();

        return back()->with('success', 'Question deleted successfully.');
    }

    /**
     * Reorder questions
     */
    public function reorder(Request $request, Quiz $quiz)
    {
        $this->checkManagePermission();
        $validated = $request->validate([
            'order' => 'required|array',
        ]);

        foreach ($validated['order'] as $position => $questionId) {
            QuizQuestion::where('id', $questionId)
                ->where('quiz_id', $quiz->id)
                ->update(['order' => $position + 1]);
        }

        return response()->json(['success' => true]);
    }
}

