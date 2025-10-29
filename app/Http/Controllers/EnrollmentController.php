<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of enrollments
     * For admin/instructor: show all enrollments
     * For students: show only their own enrollments
     */
    public function index()
    {
        $user = Auth::user();
        
        // Admin and instructor can see all enrollments
        if ($user->hasAnyRole(['admin', 'instructor'])) {
            $enrollments = Enrollment::with(['course.modules.lessons', 'user'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // Students can only see their own enrollments
            $enrollments = $user->enrollments()
                ->with(['course.modules.lessons'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('enrollments.index', compact('enrollments'));
    }

    /**
     * Enroll user in a course
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = Auth::user();
        $course = Course::findOrFail($request->course_id);

        // Check if user is already enrolled
        $existingEnrollment = $user->enrollments()
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()
                ->with('error', 'You are already enrolled in this course.');
        }

        // Create enrollment
        $enrollment = $user->enrollments()->create([
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'progress_percentage' => 0,
        ]);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Successfully enrolled in the course!');
    }

    /**
     * Display the specified enrollment
     */
    public function show(Enrollment $enrollment, $slug = null)
    {
        // If slug is provided, verify it matches the enrollment slug
        if ($slug && $slug !== $enrollment->slug) {
            // Redirect to the correct URL with proper slug
            return redirect()->route('enrollments.show', ['enrollment' => $enrollment->id, 'slug' => $enrollment->slug]);
        }
        
        $user = Auth::user();
        
        // Admin and instructor can view any enrollment
        // Students can only view their own enrollment
        if (!$user->hasAnyRole(['admin', 'instructor']) && $enrollment->user_id !== $user->id) {
            abort(403, 'You do not have permission to view this enrollment.');
        }

        $enrollment->load(['course.modules.lessons', 'course.modules.lessons.quiz', 'user']);
        
        return view('enrollments.show', compact('enrollment'));
    }

    /**
     * Update enrollment progress
     */
    public function updateProgress(Request $request, Enrollment $enrollment)
    {
        // Ensure user can only update their own enrollment
        if ($enrollment->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
        ]);

        $enrollment->update([
            'progress_percentage' => $request->progress_percentage,
        ]);

        // If progress is 100%, mark as completed
        if ($request->progress_percentage == 100) {
            $enrollment->update(['completed_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Progress updated successfully.',
        ]);
    }

    /**
     * Remove enrollment
     */
    public function destroy(Enrollment $enrollment)
    {
        // Ensure user can only remove their own enrollment
        if ($enrollment->user_id !== Auth::id()) {
            abort(403);
        }

        $enrollment->delete();

        return redirect()->route('enrollments.index')
            ->with('success', 'Successfully unenrolled from the course.');
    }

    /**
     * Complete a course
     */
    public function complete(Enrollment $enrollment)
    {
        // Ensure user can only complete their own enrollment
        if ($enrollment->user_id !== Auth::id()) {
            abort(403);
        }

        $enrollment->update([
            'completed_at' => now(),
            'progress_percentage' => 100,
        ]);

        return redirect()->back()
            ->with('success', 'Congratulations! You have completed the course.');
    }
}
