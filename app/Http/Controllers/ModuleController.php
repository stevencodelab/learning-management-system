<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Course;
use App\Http\Requests\ModuleRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Module::with(['course', 'lessons']);

        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('course', function($courseQuery) use ($searchTerm) {
                      $courseQuery->where('title', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Filter by course
        if ($request->has('course_id') && $request->course_id !== '') {
            $query->where('course_id', $request->course_id);
        }

        $modules = $query->ordered()->paginate(15)->appends($request->query());
        $courses = Course::select('id', 'title')->get();

        return view('modules.index', compact('modules', 'courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course): View
    {
        $this->checkManagePermission();
        
        // Get the next order number for this course
        $nextOrder = $course->modules()->max('order') + 1;
        
        return view('modules.create', compact('course', 'nextOrder'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ModuleRequest $request, Course $course): RedirectResponse
    {
        $validated = $request->validated();
        $validated['course_id'] = $course->id;

        Module::create($validated);

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Module created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module): View
    {
        $module->load(['course', 'lessons' => function($query) {
            $query->orderBy('order');
        }]);

        return view('modules.show', compact('module'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module): View
    {
        $this->checkManagePermission();
        
        $module->load('course');
        
        return view('modules.edit', compact('module'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ModuleRequest $request, Module $module): RedirectResponse
    {
        $validated = $request->validated();
        $module->update($validated);

        return redirect()
            ->route('modules.show', $module)
            ->with('success', 'Module updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module): RedirectResponse
    {
        $this->checkManagePermission();
        
        $course = $module->course;
        
        // Check if module has lessons
        if ($module->lessons()->count() > 0) {
            return redirect()
                ->route('modules.show', $module)
                ->with('error', 'Cannot delete module with existing lessons. Please delete all lessons first.');
        }

        $module->delete();

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Module deleted successfully.');
    }

    /**
     * Check if user has permission to manage modules
     */
    private function checkManagePermission(): void
    {
        if (!auth()->user()->hasAnyRole(['admin', 'instructor'])) {
            abort(403, 'You do not have permission to manage modules.');
        }
    }
}
