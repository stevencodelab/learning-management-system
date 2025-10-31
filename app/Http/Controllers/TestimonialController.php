<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource (public view).
     */
    public function index(Request $request)
    {
        // Optimize: Select only needed columns and eager load with specific columns
        $query = Testimonial::with([
            'user:id,name,avatar',
            'course:id,title'
        ])
            ->select('id', 'user_id', 'course_id', 'testimonial_text', 'rating', 'is_featured', 'created_at')
            ->approved();

        // Filter by course if provided
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by featured
        if ($request->filled('featured')) {
            $query->featured();
        }

        // Search functionality - optimized with indexes
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('testimonial_text', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('course', function($q) use ($search) {
                      $q->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $testimonials = $query->latest()->paginate(12)->appends($request->query());

        // Cache courses list for filter (changes infrequently)
        $cacheKey = 'testimonials_filter_courses_' . md5('approved');
        $courses = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () {
            return Course::select('id', 'title')
                ->whereHas('testimonials', function($q) {
                    $q->approved();
                })
                ->orderBy('title')
                ->get();
        });

        return view('testimonials.index', compact('testimonials', 'courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get user's enrolled courses for selection
        $enrolledCourses = [];
        if (Auth::check()) {
            $enrolledCourses = Auth::user()->enrollments()
                ->with('course.instructor')
                ->get()
                ->pluck('course')
                ->filter()
                ->sortBy('title')
                ->values();
        }

        return view('testimonials.create', compact('enrolledCourses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'testimonial_text' => 'required|string|min:20|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to submit a testimonial.');
        }

        // Verify course enrollment if course_id is provided
        if ($request->filled('course_id')) {
            $isEnrolled = Auth::user()->enrollments()->where('course_id', $request->course_id)->exists();
            if (!$isEnrolled) {
                return back()->withErrors(['course_id' => 'You must be enrolled in this course to leave a testimonial.'])->withInput();
            }
        }

        Testimonial::create([
            'user_id' => Auth::id(),
            'course_id' => $request->course_id,
            'testimonial_text' => $request->testimonial_text,
            'rating' => $request->rating,
            'is_approved' => false, // Require admin approval
        ]);

        return redirect()->route('testimonials.index')
            ->with('success', 'Thank you for your testimonial! It will be reviewed and published soon.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimonial $testimonial)
    {
        $testimonial->load(['user', 'course']);
        
        if (!$testimonial->is_approved) {
            abort(404);
        }

        return view('testimonials.show', compact('testimonial'));
    }

    /**
     * Show the form for editing the specified resource (for own testimonial only).
     */
    public function edit(Testimonial $testimonial)
    {
        // Only allow editing own testimonial
        if (Auth::id() !== $testimonial->user_id) {
            abort(403);
        }

        $enrolledCourses = Auth::user()->enrollments()
            ->with('course.instructor')
            ->get()
            ->pluck('course')
            ->filter()
            ->sortBy('title')
            ->values();

        return view('testimonials.edit', compact('testimonial', 'enrolledCourses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        // Only allow updating own testimonial
        if (Auth::id() !== $testimonial->user_id) {
            abort(403);
        }

        $request->validate([
            'testimonial_text' => 'required|string|min:20|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        // If course changed, verify enrollment
        if ($request->filled('course_id') && $request->course_id != $testimonial->course_id) {
            $isEnrolled = Auth::user()->enrollments()->where('course_id', $request->course_id)->exists();
            if (!$isEnrolled) {
                return back()->withErrors(['course_id' => 'You must be enrolled in this course to leave a testimonial.'])->withInput();
            }
        }

        $testimonial->update([
            'course_id' => $request->course_id,
            'testimonial_text' => $request->testimonial_text,
            'rating' => $request->rating,
            'is_approved' => false, // Reset approval status on edit
        ]);

        return redirect()->route('testimonials.index')
            ->with('success', 'Testimonial updated successfully. It will be reviewed again before publication.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        // Only allow deleting own testimonial or admin
        if (Auth::id() !== $testimonial->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $testimonial->delete();

        return redirect()->route('testimonials.index')
            ->with('success', 'Testimonial deleted successfully.');
    }

    /**
     * Admin: Approve a testimonial
     */
    public function approve(Testimonial $testimonial)
    {
        if (!Auth::user()->hasAnyRole(['admin'])) {
            abort(403);
        }

        $testimonial->update(['is_approved' => true]);

        return back()->with('success', 'Testimonial approved successfully.');
    }

    /**
     * Admin: Reject a testimonial
     */
    public function reject(Testimonial $testimonial)
    {
        if (!Auth::user()->hasAnyRole(['admin'])) {
            abort(403);
        }

        $testimonial->update(['is_approved' => false]);

        return back()->with('success', 'Testimonial rejected.');
    }

    /**
     * Admin: Toggle featured status
     */
    public function toggleFeatured(Testimonial $testimonial)
    {
        if (!Auth::user()->hasAnyRole(['admin'])) {
            abort(403);
        }

        $testimonial->update(['is_featured' => !$testimonial->is_featured]);

        return back()->with('success', 'Featured status updated.');
    }

    /**
     * Admin: Manage testimonials (approve/reject/list)
     */
    public function manage(Request $request)
    {
        if (!Auth::user()->hasAnyRole(['admin'])) {
            abort(403);
        }

        $query = Testimonial::with(['user', 'course']);

        // Filter by approval status
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        } else {
            // Default: show pending first
            $query->orderBy('is_approved', 'asc');
        }

        // Filter by featured
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured == '1');
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('testimonial_text', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('course', function($q) use ($search) {
                      $q->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $testimonials = $query->latest()->paginate(15)->appends($request->query());

        // Statistics
        $stats = [
            'total' => Testimonial::count(),
            'approved' => Testimonial::where('is_approved', true)->count(),
            'pending' => Testimonial::where('is_approved', false)->count(),
            'featured' => Testimonial::where('is_featured', true)->where('is_approved', true)->count(),
        ];

        return view('testimonials.manage', compact('testimonials', 'stats'));
    }
}
