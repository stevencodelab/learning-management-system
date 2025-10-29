<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuizController extends Controller
{
    /**
     * Check if user has permission to manage quizzes
     */
    private function checkManagePermission()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'instructor'])) {
            abort(403, 'Only admins and instructors can manage quizzes.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quizzes = Quiz::with(['lesson.module.course'])
            ->when(request('search'), function ($query) {
                $search = request('search');
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->when(request('lesson_id'), function ($query) {
                $query->where('lesson_id', request('lesson_id'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get all lessons for admin/instructor to create quiz
        $lessons = null;
        if (auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor'])) {
            $lessons = Lesson::with(['module.course'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('quizzes.index', compact('quizzes', 'lessons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Lesson $lesson)
    {
        $this->checkManagePermission();
        return view('quizzes.create', compact('lesson'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Lesson $lesson)
    {
        $this->checkManagePermission();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|numeric|min:0|max:100',
            'time_limit_minutes' => 'nullable|numeric|min:1',
            'instructions' => 'nullable|string',
            'allow_multiple_attempts' => 'boolean',
            'max_attempts' => 'nullable|integer|min:1',
            'shuffle_questions' => 'boolean',
            'shuffle_answers' => 'boolean',
            'show_correct_answers' => 'boolean',
            'show_results_immediately' => 'boolean',
            'questions_per_page' => 'nullable|integer|min:1',
            'allow_navigation' => 'boolean',
            'negative_marking' => 'boolean',
            'negative_mark_value' => 'nullable|numeric|min:0|max:1',
            'status' => 'required|in:draft,published,archived',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'random_question_count' => 'nullable|integer|min:1',
            'require_all_questions' => 'boolean',
            'pass_message' => 'nullable|string',
            'fail_message' => 'nullable|string',
        ]);

        // Set default values for checkboxes if not present
        $validated['allow_multiple_attempts'] = $validated['allow_multiple_attempts'] ?? true;
        $validated['shuffle_questions'] = $validated['shuffle_questions'] ?? false;
        $validated['shuffle_answers'] = $validated['shuffle_answers'] ?? false;
        $validated['show_correct_answers'] = $validated['show_correct_answers'] ?? false;
        $validated['show_results_immediately'] = $validated['show_results_immediately'] ?? true;
        $validated['allow_navigation'] = $validated['allow_navigation'] ?? true;
        $validated['negative_marking'] = $validated['negative_marking'] ?? false;
        $validated['require_all_questions'] = $validated['require_all_questions'] ?? false;
        
        $validated['lesson_id'] = $lesson->id;
        
        Quiz::create($validated);

        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz, $slug = null)
    {
        // If slug is provided, verify it matches the quiz slug
        if ($slug && $slug !== $quiz->slug) {
            // Redirect to the correct URL with proper slug
            return redirect()->route('quizzes.show', ['quiz' => $quiz->id, 'slug' => $quiz->slug]);
        }
        
        $quiz->load(['lesson.module.course', 'questions', 'attempts.user']);
        
        // Load user attempts if student
        $userAttempts = null;
        $bestAttempt = null;
        
        if (auth()->check() && !auth()->user()->hasAnyRole(['admin', 'instructor'])) {
            $userAttempts = auth()->user()->quizAttempts()
                ->where('quiz_id', $quiz->id)
                ->orderBy('created_at', 'desc')
                ->get();
                
            $bestAttempt = auth()->user()->quizAttempts()
                ->where('quiz_id', $quiz->id)
                ->where('submitted', true)
                ->orderBy('score', 'desc')
                ->first();
        }

        return view('quizzes.show', compact('quiz', 'userAttempts', 'bestAttempt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        $this->checkManagePermission();
        $quiz->load('lesson.module.course');
        
        return view('quizzes.edit', compact('quiz'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $this->checkManagePermission();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|numeric|min:0|max:100',
            'time_limit_minutes' => 'nullable|numeric|min:1',
            'instructions' => 'nullable|string',
            'allow_multiple_attempts' => 'boolean',
            'max_attempts' => 'nullable|integer|min:1',
            'shuffle_questions' => 'boolean',
            'shuffle_answers' => 'boolean',
            'show_correct_answers' => 'boolean',
            'show_results_immediately' => 'boolean',
            'questions_per_page' => 'nullable|integer|min:1',
            'allow_navigation' => 'boolean',
            'negative_marking' => 'boolean',
            'negative_mark_value' => 'nullable|numeric|min:0|max:1',
            'status' => 'required|in:draft,published,archived',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'random_question_count' => 'nullable|integer|min:1',
            'require_all_questions' => 'boolean',
            'pass_message' => 'nullable|string',
            'fail_message' => 'nullable|string',
        ]);

        // Ensure boolean fields are set properly (checkboxes)
        $validated['allow_multiple_attempts'] = isset($validated['allow_multiple_attempts']);
        $validated['shuffle_questions'] = isset($validated['shuffle_questions']);
        $validated['shuffle_answers'] = isset($validated['shuffle_answers']);
        $validated['show_correct_answers'] = isset($validated['show_correct_answers']);
        $validated['show_results_immediately'] = isset($validated['show_results_immediately']);
        $validated['allow_navigation'] = isset($validated['allow_navigation']);
        $validated['negative_marking'] = isset($validated['negative_marking']);
        $validated['require_all_questions'] = isset($validated['require_all_questions']);

        $quiz->update($validated);

        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $this->checkManagePermission();
        $quiz->delete();

        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz deleted successfully.');
    }
}
