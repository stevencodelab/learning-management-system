<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Module;
use App\Http\Requests\LessonRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Lesson::with(['module.course']);

        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('module', function($moduleQuery) use ($searchTerm) {
                      $moduleQuery->where('title', 'like', '%' . $searchTerm . '%')
                                  ->orWhereHas('course', function($courseQuery) use ($searchTerm) {
                                      $courseQuery->where('title', 'like', '%' . $searchTerm . '%');
                                  });
                  });
            });
        }

        // Filter by module
        if ($request->has('module_id') && $request->module_id !== '') {
            $query->where('module_id', $request->module_id);
        }

        // Filter by type
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        // Filter by free status
        if ($request->filled('is_free')) {
            $query->where('is_free', $request->is_free == '1' ? 1 : 0);
        }

        $lessons = $query->ordered()->paginate(15)->appends($request->query());
        $modules = Module::with('course')->get();
        $lessonTypes = ['video', 'reading', 'audio', 'interactive'];

        return view('lessons.index', compact('lessons', 'modules', 'lessonTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Module $module): View
    {
        $this->checkManagePermission();
        
        // Get the next order number for this module
        $nextOrder = $module->lessons()->max('order') + 1;
        
        $lessonTypes = ['video', 'reading', 'audio', 'interactive'];
        
        return view('lessons.create', compact('module', 'nextOrder', 'lessonTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LessonRequest $request, Module $module): RedirectResponse
    {
        $validated = $request->validated();
        $validated['module_id'] = $module->id;

        Lesson::create($validated);

        return redirect()
            ->route('modules.show', $module)
            ->with('success', 'Lesson created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson): View
    {
        $lesson->load(['module.course', 'quiz', 'lessonProgress' => function($query) {
            $query->where('user_id', auth()->id());
        }]);

        return view('lessons.show', compact('lesson'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson): View
    {
        $this->checkManagePermission();
        
        $lesson->load('module.course');
        $lessonTypes = ['video', 'reading', 'audio', 'interactive'];
        
        return view('lessons.edit', compact('lesson', 'lessonTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LessonRequest $request, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validated();
        $lesson->update($validated);

        return redirect()
            ->route('lessons.show', $lesson)
            ->with('success', 'Lesson updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson): RedirectResponse
    {
        $this->checkManagePermission();
        
        $module = $lesson->module;
        
        // Check if lesson has quiz
        if ($lesson->quiz) {
            return redirect()
                ->route('lessons.show', $lesson)
                ->with('error', 'Cannot delete lesson with existing quiz. Please delete the quiz first.');
        }

        $lesson->delete();

        return redirect()
            ->route('modules.show', $module)
            ->with('success', 'Lesson deleted successfully.');
    }

    /**
     * Check if user has permission to manage lessons
     */
    private function checkManagePermission(): void
    {
        if (!auth()->user()->hasAnyRole(['admin', 'instructor'])) {
            abort(403, 'You do not have permission to manage lessons.');
        }
    }
}
