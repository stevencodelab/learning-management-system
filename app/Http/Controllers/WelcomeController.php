<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Display the welcome landing page with courses and quizzes
     */
    public function index()
    {
        // Get published courses
        $courses = Course::where('is_published', true)
            ->with(['modules.lessons', 'instructor'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get all courses count for milestones
        $totalCourses = Course::where('is_published', true)->count();
        
        // Get all available quizzes from published courses
        // Using a safer approach to avoid errors if relationships don't exist
        try {
            $publishedCourseIds = Course::where('is_published', true)->pluck('id');
            
            $quizzes = Quiz::with(['lesson.module.course', 'questions'])
                ->when($publishedCourseIds->isNotEmpty(), function($query) use ($publishedCourseIds) {
                    $query->whereHas('lesson.module', function($q) use ($publishedCourseIds) {
                        $q->whereIn('course_id', $publishedCourseIds);
                    });
                })
                ->limit(6)
                ->get();
        } catch (\Exception $e) {
            // Fallback to get any quizzes if relationship fails
            $quizzes = Quiz::with(['lesson.module.course', 'questions'])
                ->limit(6)
                ->get();
        }
        
        // Get featured testimonials for landing page
        $testimonials = \App\Models\Testimonial::with(['user', 'course'])
            ->approved()
            ->featured()
            ->latest()
            ->limit(6)
            ->get();

        // Fallback to regular approved testimonials if no featured ones
        if ($testimonials->isEmpty()) {
            $testimonials = \App\Models\Testimonial::with(['user', 'course'])
                ->approved()
                ->latest()
                ->limit(6)
                ->get();
        }
        
        // Get statistics for milestones
        $totalStudents = \App\Models\User::whereHas('roles', function($query) {
            $query->where('name', 'student');
        })->count();
        
        $totalTeachers = \App\Models\User::whereHas('roles', function($query) {
            $query->where('name', 'instructor');
        })->count();

        return view('landing.index', compact(
            'courses', 
            'quizzes', 
            'testimonials',
            'totalCourses',
            'totalStudents',
            'totalTeachers'
        ));
    }

    /**
     * Display public courses listing page
     */
    public function courses(Request $request)
    {
        $query = Course::where('is_published', true)
            ->with(['modules.lessons', 'instructor']);

        // Advanced Search functionality
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('slug', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by level
        if ($request->has('level') && $request->level !== '') {
            $query->where('level', $request->level);
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

        // Filter by free courses
        if ($request->filled('price_type')) {
            if ($request->price_type === 'free') {
                $query->where('price', 0);
            } elseif ($request->price_type === 'paid') {
                $query->where('price', '>', 0);
            }
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $courses = $query->paginate(6)
            ->appends($request->query());

        return view('landing.courses', compact('courses'));
    }

    /**
     * Display public quizzes listing page
     */
    public function quizzes(Request $request)
    {
        try {
            $publishedCourseIds = Course::where('is_published', true)->pluck('id');
            
            $query = Quiz::with(['lesson.module.course', 'questions'])
                ->when($publishedCourseIds->isNotEmpty(), function($q) use ($publishedCourseIds) {
                    $q->whereHas('lesson.module', function($q2) use ($publishedCourseIds) {
                        $q2->whereIn('course_id', $publishedCourseIds);
                    });
                });

            // Advanced Search functionality
            if ($request->has('search') && $request->search !== '') {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%')
                      ->orWhere('slug', 'like', '%' . $searchTerm . '%');
                });
            }

            // Filter by course
            if ($request->filled('course_id')) {
                $query->whereHas('lesson.module', function($q) use ($request) {
                    $q->where('course_id', $request->course_id);
                });
            }

            // Filter by time limit
            if ($request->filled('time_limit')) {
                $timeLimit = (int) $request->time_limit;
                if ($timeLimit > 0) {
                    $query->where('time_limit_minutes', '<=', $timeLimit);
                }
            }

            // Filter by passing score
            if ($request->filled('passing_score_min')) {
                $passingScoreMin = (int) $request->passing_score_min;
                $query->where('passing_score', '>=', $passingScoreMin);
            }

            // Filter by questions count
            if ($request->filled('questions_min')) {
                $questionsMin = (int) $request->questions_min;
                $query->has('questions', '>=', $questionsMin);
            }

            // Sort functionality
            $sortBy = $request->get('sort_by', 'latest');
            switch ($sortBy) {
                case 'questions':
                    $query->withCount('questions')->orderBy('questions_count', 'desc');
                    break;
                case 'title':
                    $query->orderBy('title', 'asc');
                    break;
                case 'latest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $quizzes = $query->paginate(6)
                ->appends($request->query());

        } catch (\Exception $e) {
            // Fallback to get any quizzes if relationship fails
            $query = Quiz::with(['lesson.module.course', 'questions']);
            
            if ($request->has('search') && $request->search !== '') {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });
            }

            $quizzes = $query->orderBy('created_at', 'desc')
                ->paginate(6)
                ->appends($request->query());
        }

        // Get courses for filter dropdown
        try {
            $courses = Course::where('is_published', true)
                ->has('modules.lessons.quiz')
                ->orderBy('title')
                ->get();
        } catch (\Exception $e) {
            $courses = collect([]);
        }

        return view('landing.quizzes', compact('quizzes', 'courses'));
    }

    /**
     * Display public teachers listing page
     */
    public function teachers(Request $request)
    {
        $query = \App\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'instructor');
        });

        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        // Get instructors with their statistics
        $teachers = $query->orderBy('name', 'asc')->get();
        
        // Add additional data for each teacher
        $totalPublishedCourses = Course::where('is_published', true)->count();
        $teachers->each(function($teacher) use ($totalPublishedCourses) {
            // Calculate courses count if instructor has created any
            // For now, we'll use a placeholder - can be enhanced later with instructor_id in courses
            $teacher->courses_count = 0; // Placeholder, will be updated if instructor_id exists
            
            // Alternative: Count all published courses as available for all instructors
            $teacher->available_courses = $totalPublishedCourses;
        });

        return view('landing.teachers', compact('teachers'));
    }
}
