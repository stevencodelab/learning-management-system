<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Course::with(['modules.lessons', 'instructor']);
        
        // Filter by instructor if user is instructor (not admin)
        if (auth()->user()->hasRole('instructor') && !auth()->user()->hasRole('admin')) {
            $query->where('instructor_id', auth()->id());
        }

        // Advanced Search - full-text search across multiple fields
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('slug', 'like', '%' . $searchTerm . '%')
                  ->orWhere('level', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by level
        if ($request->has('level') && $request->level !== '') {
            $query->where('level', $request->level);
        }

        // Filter by published status
        if ($request->filled('published')) {
            $query->where('is_published', $request->published == '1' ? 1 : 0);
        }

        // Filter by price range
        if ($request->filled('price_min')) {
            $priceMin = (float) $request->price_min;
            $query->where('price', '>=', $priceMin);
        }

        if ($request->filled('price_max')) {
            $priceMax = (float) $request->price_max;
            $query->where('price', '<=', $priceMax);
        }

        $courses = $query->paginate(12)->appends($request->query());

        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkManagePermission();
        
        // Get all instructors for assignment
        $instructors = \App\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'instructor');
        })->orderBy('name')->get();
        
        return view('courses.create', compact('instructors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkManagePermission();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'instructor_id' => 'nullable|exists:users,id',
        ]);

        $data = $request->all();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Store the image directly
            $path = $image->storeAs('course-thumbnails', $filename, 'public');
            
            $data['thumbnail'] = $path;
        }

        // Auto-assign instructor if user is instructor and no instructor_id provided
        if (auth()->user()->hasRole('instructor') && !auth()->user()->hasRole('admin')) {
            if (empty($data['instructor_id'])) {
                $data['instructor_id'] = auth()->id();
            }
        }

        Course::create($data);

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the public detail page for course
     */
    public function detail(Course $course, $slug = null)
    {
        // Load course with all relationships
        $course->load(['modules.lessons', 'modules.lessons.quiz', 'enrollments', 'instructor']);
        
        return view('courses.detail', compact('course'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course, $slug = null)
    {
        // If slug is provided, verify it matches the course slug
        if ($slug && $slug !== $course->slug) {
            // Redirect to the correct URL with proper slug
            return redirect()->route('courses.show', ['course' => $course->id, 'slug' => $course->slug]);
        }
        
        $course->load(['modules.lessons', 'modules.lessons.quiz', 'enrollments', 'instructor']);
        
        // Check if user is enrolled
        $isEnrolled = false;
        $enrollment = null;
        
        if (auth()->check()) {
            $enrollment = auth()->user()->enrollments()
                ->where('course_id', $course->id)
                ->first();
            $isEnrolled = $enrollment !== null;
        }
        
        return view('courses.show', compact('course', 'isEnrolled', 'enrollment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $this->checkManagePermission();
        
        // Get all instructors for assignment
        $instructors = \App\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'instructor');
        })->orderBy('name')->get();
        
        return view('courses.edit', compact('course', 'instructors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $this->checkManagePermission();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'instructor_id' => 'nullable|exists:users,id',
        ]);

        $data = $request->all();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            $image = $request->file('thumbnail');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Store the image directly
            $path = $image->storeAs('course-thumbnails', $filename, 'public');
            
            $data['thumbnail'] = $path;
        }

        // Auto-assign instructor if user is instructor and no instructor_id provided
        if (auth()->user()->hasRole('instructor') && !auth()->user()->hasRole('admin')) {
            if (empty($data['instructor_id'])) {
                $data['instructor_id'] = auth()->id();
            }
        }

        $course->update($data);

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $this->checkManagePermission();
        
        // Delete thumbnail
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    /**
     * Toggle published status
     */
    public function togglePublished(Course $course)
    {
        $this->checkManagePermission();
        
        $course->update(['is_published' => !$course->is_published]);

        return redirect()->back()
            ->with('success', 'Course status updated successfully.');
    }

    /**
     * Check if user has permission to manage courses
     */
    private function checkManagePermission()
    {
        if (!auth()->check() || !auth()->user()->hasAnyRole(['admin', 'instructor'])) {
            abort(403, 'Only admins and instructors can manage courses.');
        }
    }
}
