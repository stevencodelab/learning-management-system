<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StudentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->checkManagePermission();
        
        $query = User::role('student')->with(['enrollments.course', 'lessonProgress', 'quizAttempts']);

        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by enrollment status
        if ($request->has('enrollment_status') && $request->enrollment_status !== '') {
            if ($request->enrollment_status === 'enrolled') {
                $query->whereHas('enrollments');
            } elseif ($request->enrollment_status === 'not_enrolled') {
                $query->whereDoesntHave('enrollments');
            }
        }

        // Filter by verification status
        if ($request->has('email_verified') && $request->email_verified !== '') {
            if ($request->email_verified === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->email_verified === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->checkManagePermission();
        
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        // Hash password
        $validated['password'] = Hash::make($validated['password']);
        
        // Create user
        $student = User::create($validated);
        
        // Assign student role
        $studentRole = Role::findByName('student');
        $student->assignRole($studentRole);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $student): View
    {
        $this->checkManagePermission();
        
        // Ensure this is a student
        if (!$student->hasRole('student')) {
            abort(404, 'Student not found.');
        }
        
        $student->load([
            'enrollments.course',
            'lessonProgress.lesson.module.course',
            'quizAttempts.quiz.lesson.module.course'
        ]);

        // Get statistics
        $stats = [
            'total_enrollments' => $student->enrollments->count(),
            'completed_courses' => $student->enrollments->where('completed_at', '!=', null)->count(),
            'total_lessons_completed' => $student->lessonProgress->where('completed_at', '!=', null)->count(),
            'total_quiz_attempts' => $student->quizAttempts->count(),
            'average_quiz_score' => $student->quizAttempts->avg('score') ?? 0,
        ];

        return view('students.show', compact('student', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $student): View
    {
        $this->checkManagePermission();
        
        // Ensure this is a student
        if (!$student->hasRole('student')) {
            abort(404, 'Student not found.');
        }
        
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentRequest $request, User $student): RedirectResponse
    {
        $this->checkManagePermission();
        
        // Ensure this is a student
        if (!$student->hasRole('student')) {
            abort(404, 'Student not found.');
        }
        
        $validated = $request->validated();
        
        // Hash password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $student->update($validated);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $student): RedirectResponse
    {
        $this->checkManagePermission();
        
        // Ensure this is a student
        if (!$student->hasRole('student')) {
            abort(404, 'Student not found.');
        }
        
        // Check if student has enrollments
        if ($student->enrollments()->count() > 0) {
            return redirect()
                ->route('students.show', $student)
                ->with('error', 'Cannot delete student with existing enrollments. Please remove enrollments first.');
        }

        // Delete student's avatar if exists
        if ($student->avatar && Storage::disk('public')->exists($student->avatar)) {
            Storage::disk('public')->delete($student->avatar);
        }

        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Check if user has permission to manage students
     */
    private function checkManagePermission(): void
    {
        if (!auth()->user()->hasAnyRole(['admin', 'instructor'])) {
            abort(403, 'You do not have permission to manage students.');
        }
    }
}
