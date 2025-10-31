<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Optimized version of WelcomeController with caching and query optimization
 */
class OptimizedWelcomeController extends Controller
{
    /**
     * Display welcome page with optimized queries and caching
     */
    public function index()
    {
        // Cache expensive statistics queries
        $totalCourses = Cache::remember('stats.total_courses', 3600, function () {
            return Course::where('is_published', true)->count();
        });

        // Cache featured courses (most viewed/popular)
        $courses = Cache::remember('homepage.featured_courses', 1800, function () {
            return Course::with(['instructor:id,name,avatar', 'modules:id,course_id'])
                ->where('is_published', true)
                ->select('id', 'title', 'description', 'thumbnail', 'level', 'price', 'instructor_id', 'duration_hours')
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        });

        // Cache featured quizzes
        $quizzes = Cache::remember('homepage.featured_quizzes', 1800, function () {
            $publishedCourseIds = Course::where('is_published', true)->pluck('id');
            
            return Quiz::with(['lesson.module:id,course_id', 'questions:id,quiz_id'])
                ->whereHas('lesson.module', function($q) use ($publishedCourseIds) {
                    $q->whereIn('course_id', $publishedCourseIds);
                })
                ->select('id', 'lesson_id', 'title', 'description', 'passing_score', 'time_limit_minutes')
                ->limit(6)
                ->get();
        });

        // Cache featured testimonials
        $testimonials = Cache::remember('homepage.featured_testimonials', 3600, function () {
            return Testimonial::with(['user:id,name,avatar', 'course:id,title'])
                ->select('id', 'user_id', 'course_id', 'testimonial_text', 'rating')
                ->approved()
                ->featured()
                ->latest()
                ->limit(6)
                ->get();
        });

        // Fallback if no featured testimonials
        if ($testimonials->isEmpty()) {
            $testimonials = Cache::remember('homepage.testimonials', 3600, function () {
                return Testimonial::with(['user:id,name,avatar', 'course:id,title'])
                    ->select('id', 'user_id', 'course_id', 'testimonial_text', 'rating')
                    ->approved()
                    ->latest()
                    ->limit(6)
                    ->get();
            });
        }

        // Cache statistics
        $stats = Cache::remember('homepage.stats', 3600, function () {
            return [
                'totalStudents' => User::whereHas('roles', function($query) {
                    $query->where('name', 'student');
                })->count(),
                'totalTeachers' => User::whereHas('roles', function($query) {
                    $query->where('name', 'instructor');
                })->count(),
            ];
        });

        return view('landing.index', compact(
            'courses',
            'quizzes',
            'testimonials',
            'totalCourses',
            'stats'
        ));
    }
}

