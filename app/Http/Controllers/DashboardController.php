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

        // Calculate statistics
        $totalEnrolledCourses = $enrolledCourses->count();
        $completedCourses = $user->enrollments()->whereNotNull('completed_at')->count();
        $activeCourses = $user->enrollments()->whereNull('completed_at')->count();
        $totalLessons = $enrolledCourses->sum(function($course) {
            return $course->modules->sum(function($module) {
                return $module->lessons->count();
            });
        });
        
        $completedLessons = 0;
        foreach ($enrolledCourses as $course) {
            foreach ($course->modules as $module) {
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
        $completedLessonsCount = $user->lessonProgress()->where('is_completed', true)->count();
        $inProgressLessons = $user->lessonProgress()->where('is_completed', false)->count();
        
        // Quiz statistics
        $totalQuizAttempts = $user->quizAttempts()->count();
        
        // Calculate passed/failed quizzes - use is_passed if available, otherwise calculate
        $submittedAttempts = $user->quizAttempts()->where('submitted', true)->with('quiz')->get();
        
        $passedQuizzes = 0;
        $failedQuizzes = 0;
        
        foreach ($submittedAttempts as $attempt) {
            if (isset($attempt->is_passed)) {
                // Use is_passed column if available
                if ($attempt->is_passed) {
                    $passedQuizzes++;
                } else {
                    $failedQuizzes++;
                }
            } elseif ($attempt->quiz) {
                // Fallback: calculate based on percentage
                $percentage = $attempt->score_percentage;
                if ($percentage >= $attempt->quiz->passing_score) {
                    $passedQuizzes++;
                } else {
                    $failedQuizzes++;
                }
            }
        }
        
        // Calculate average score
        $averageScore = 0;
        if ($submittedAttempts->count() > 0) {
            $totalPercentage = $submittedAttempts->sum(function($attempt) {
                return $attempt->score_percentage ?? 0;
            });
            $averageScore = $totalPercentage / $submittedAttempts->count();
        }
        
        // Progress by course for chart
        $courseProgress = $enrolledCourses->map(function($course) use ($user) {
            $totalCourseLessons = $course->modules->sum(function($module) {
                return $module->lessons->count();
            });
            
            $completedCourseLessons = 0;
            foreach ($course->modules as $module) {
                foreach ($module->lessons as $lesson) {
                    $progress = $user->lessonProgress()
                        ->where('lesson_id', $lesson->id)
                        ->where('is_completed', true)
                        ->first();
                    if ($progress) {
                        $completedCourseLessons++;
                    }
                }
            }
            
            return [
                'course' => $course->title,
                'progress' => $totalCourseLessons > 0 ? round(($completedCourseLessons / $totalCourseLessons) * 100, 1) : 0,
                'completed' => $completedCourseLessons,
                'total' => $totalCourseLessons,
            ];
        })->values();

        // Weekly progress data for chart
        $weeklyProgress = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $completedOnDate = $user->lessonProgress()
                ->where('is_completed', true)
                ->whereDate('updated_at', $date->toDateString())
                ->count();
            $weeklyProgress[] = [
                'date' => $date->format('D'),
                'completed' => $completedOnDate,
            ];
        }

        // Quiz performance over time
        $quizPerformance = $user->quizAttempts()
            ->where('submitted', true)
            ->orderBy('completed_at', 'asc')
            ->get()
            ->map(function($attempt) {
                return [
                    'score' => $attempt->score_percentage ?? 0,
                    'date' => $attempt->completed_at ? $attempt->completed_at->format('M d') : 'N/A',
                ];
            })
            ->take(10)
            ->values();

        return view('dashboard.student', compact(
            'enrolledCourses',
            'recentProgress',
            'recentQuizAttempts',
            'overallProgress',
            'totalEnrolledCourses',
            'completedCourses',
            'activeCourses',
            'totalLessons',
            'completedLessonsCount',
            'inProgressLessons',
            'totalQuizAttempts',
            'passedQuizzes',
            'failedQuizzes',
            'averageScore',
            'courseProgress',
            'weeklyProgress',
            'quizPerformance'
        ));
    }

    /**
     * Display the dashboard for instructors
     */
    public function instructor()
    {
        $user = Auth::user();
        
        // Get course statistics for instructor's courses only
        $query = Course::query();
        if ($user->hasRole('instructor') && !$user->hasRole('admin')) {
            $query->where('instructor_id', $user->id);
        }
        
        $totalCourses = $query->count();
        $publishedCourses = $query->where('is_published', true)->count();
        
        // Get enrollments for all courses (or instructor's courses if instructor_id exists)
        $courseIds = $query->pluck('id');
        $totalEnrollments = Enrollment::whereIn('course_id', $courseIds)->count();
        $activeEnrollments = Enrollment::whereIn('course_id', $courseIds)->whereNull('completed_at')->count();
        
        // Get modules and quizzes statistics
        $totalModules = Module::whereIn('course_id', $courseIds)->count();
        
        // Get quizzes count - need to join through lessons and modules
        $totalQuizzes = Quiz::whereHas('lesson.module', function($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds);
        })->count();
        
        $totalStudents = User::whereHas('roles', function($query) {
            $query->where('name', 'student');
        })->count();

        // Get recent courses
        $recentCourses = Course::with('modules.lessons')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get enrollment statistics by level
        $enrollmentsByLevel = Course::selectRaw('courses.level, COUNT(enrollments.id) as enrollment_count')
            ->leftJoin('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->groupBy('courses.level')
            ->get();

        // Calculate course performance metrics
        $baseQuery = Course::query();
        $highPerformanceCourses = $baseQuery->whereHas('enrollments', function($q) {
            $q->whereRaw('(SELECT COUNT(*) FROM enrollments e2 WHERE e2.course_id = enrollments.course_id AND e2.completed_at IS NOT NULL) / (SELECT COUNT(*) FROM enrollments e3 WHERE e3.course_id = enrollments.course_id) >= 0.8');
        })->count();
        
        $mediumPerformanceCourses = Course::whereHas('enrollments', function($q) {
            $q->whereRaw('(SELECT COUNT(*) FROM enrollments e2 WHERE e2.course_id = enrollments.course_id AND e2.completed_at IS NOT NULL) / (SELECT COUNT(*) FROM enrollments e3 WHERE e3.course_id = enrollments.course_id) BETWEEN 0.5 AND 0.79');
        })->count();
        
        $lowPerformanceCourses = Course::whereHas('enrollments', function($q) {
            $q->whereRaw('(SELECT COUNT(*) FROM enrollments e2 WHERE e2.course_id = enrollments.course_id AND e2.completed_at IS NOT NULL) / (SELECT COUNT(*) FROM enrollments e3 WHERE e3.course_id = enrollments.course_id) < 0.5');
        })->count();

        return view('dashboard.instructor', compact(
            'totalCourses',
            'publishedCourses',
            'totalEnrollments',
            'activeEnrollments',
            'totalModules',
            'totalQuizzes',
            'totalStudents',
            'recentCourses',
            'enrollmentsByLevel',
            'highPerformanceCourses',
            'mediumPerformanceCourses',
            'lowPerformanceCourses'
        ));
    }

    /**
     * Display the dashboard for admins
     */
    public function admin()
    {
        // Get platform-wide statistics
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

        // Get courses by level
        $coursesByLevel = Course::selectRaw('level, COUNT(*) as count')
            ->groupBy('level')
            ->pluck('count', 'level')
            ->toArray();

        // Get recent platform activity
        $recentActivity = collect();
        
        // Recent enrollments
        $recentEnrollments = Enrollment::with(['user', 'course'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($enrollment) {
                return [
                    'description' => 'Enrolled in course',
                    'user_name' => $enrollment->user->name,
                    'course_title' => $enrollment->course->title,
                    'type' => 'enrollment',
                    'created_at' => $enrollment->created_at->format('M d, Y')
                ];
            });
        
        $recentActivity = $recentActivity->merge($recentEnrollments);

        return view('dashboard.admin', compact(
            'totalCourses',
            'publishedCourses',
            'totalEnrollments',
            'activeEnrollments',
            'totalModules',
            'totalQuizzes',
            'totalStudents',
            'coursesByLevel',
            'recentActivity'
        ));
    }

    /**
     * Display the main dashboard based on user role
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            return $this->admin();
        } elseif ($user->hasRole('instructor')) {
            return $this->instructor();
        }
        
        return $this->student();
    }
}
