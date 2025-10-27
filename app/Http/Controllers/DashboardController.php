<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\QuizAttempt;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard for students
     */
    public function student()
    {
        $user = Auth::user();
        
        // Get enrolled courses
        $enrolledCourses = $user->courses()
            ->with(['modules.lessons'])
            ->get();

        // Get recent lesson progress
        $recentProgress = $user->lessonProgress()
            ->with('lesson.module.course')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Get quiz attempts
        $recentQuizAttempts = $user->quizAttempts()
            ->with('quiz.lesson.module.course')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Calculate overall progress
        $totalLessons = 0;
        $completedLessons = 0;
        
        foreach ($enrolledCourses as $course) {
            foreach ($course->modules as $module) {
                $totalLessons += $module->lessons->count();
                foreach ($module->lessons as $lesson) {
                    $progress = $user->lessonProgress()
                        ->where('lesson_id', $lesson->id)
                        ->where('is_completed', true)
                        ->first();
                    if ($progress) {
                        $completedLessons++;
                    }
                }
            }
        }

        $overallProgress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0;

        return view('dashboard.student', compact(
            'enrolledCourses',
            'recentProgress',
            'recentQuizAttempts',
            'overallProgress'
        ));
    }

    /**
     * Display the dashboard for instructors/admins
     */
    public function instructor()
    {
        // Get course statistics
        $totalCourses = Course::count();
        $publishedCourses = Course::where('is_published', true)->count();
        $totalEnrollments = Enrollment::count();
        $activeEnrollments = Enrollment::whereNull('completed_at')->count();
        
        // Get modules and quizzes statistics
        $totalModules = Module::count();
        $totalQuizzes = Quiz::count();
        $totalStudents = User::whereHas('roles', function($query) {
            $query->where('name', 'student');
        })->count();

        // Get recent courses
        $recentCourses = Course::with('modules.lessons')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get enrollment statistics by level
        $enrollmentsByLevel = Course::selectRaw('level, COUNT(enrollments.id) as enrollment_count')
            ->leftJoin('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->groupBy('level')
            ->get();

        return view('dashboard.instructor', compact(
            'totalCourses',
            'publishedCourses',
            'totalEnrollments',
            'activeEnrollments',
            'totalModules',
            'totalQuizzes',
            'totalStudents',
            'recentCourses',
            'enrollmentsByLevel'
        ));
    }

    /**
     * Display the main dashboard based on user role
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin') || $user->hasRole('instructor')) {
            return $this->instructor();
        }
        
        return $this->student();
    }
}
