@extends('layouts.landing')

@section('content')
@php
use Illuminate\Support\Str;
use Carbon\Carbon;
@endphp

<!-- Home -->
<div class="home">
    <div class="home_background_container prlx_parent">
        <div class="home_background prlx" style="background-image:url({{ asset('images/landing/news_background.jpg') }})"></div>
    </div>
    <div class="home_content">
        <h1>The Quizzes</h1>
    </div>
</div>

<!-- News -->
<div class="news">
    <div class="container">
        <div class="row">
            
            <!-- Quizzes Column -->
            <div class="col-lg-8">
                
                <!-- Advanced Search -->
                <div class="mb-4">
                    <div class="card" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0" style="color: #1e40af; font-weight: 600;">
                                    <i class="fas fa-filter mr-2"></i>Advanced Search
                                </h5>
                                <button type="button" class="btn btn-link p-0" data-toggle="collapse" data-target="#advancedSearch" aria-expanded="{{ request()->hasAny(['time_limit', 'passing_score_min', 'questions_min', 'sort_by']) ? 'true' : 'false' }}">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                            
                            <form action="{{ route('landing.quizzes') }}" method="GET" id="searchForm">
                                <!-- Basic Search -->
                                <div class="row mb-3">
                                    <div class="col-md-8 mb-2">
                                        <input type="text" 
                                               name="search" 
                                               class="form-control" 
                                               placeholder="Search quizzes by title or description..." 
                                               value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <select name="course_id" class="form-control">
                                            <option value="">All Courses</option>
                                            @foreach($courses ?? [] as $course)
                                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                                {{ $course->title }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Advanced Filters (Collapsible) -->
                                <div class="collapse {{ request()->hasAny(['time_limit', 'passing_score_min', 'questions_min', 'sort_by']) ? 'show' : '' }}" id="advancedSearch">
                                    <div class="row mb-3">
                                        <div class="col-md-3 mb-2">
                                            <label class="small text-muted">Time Limit (min)</label>
                                            <select name="time_limit" class="form-control">
                                                <option value="">Any Time</option>
                                                <option value="5" {{ request('time_limit') == '5' ? 'selected' : '' }}>≤ 5 minutes</option>
                                                <option value="10" {{ request('time_limit') == '10' ? 'selected' : '' }}>≤ 10 minutes</option>
                                                <option value="15" {{ request('time_limit') == '15' ? 'selected' : '' }}>≤ 15 minutes</option>
                                                <option value="30" {{ request('time_limit') == '30' ? 'selected' : '' }}>≤ 30 minutes</option>
                                                <option value="60" {{ request('time_limit') == '60' ? 'selected' : '' }}>≤ 60 minutes</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="small text-muted">Min Passing Score (%)</label>
                                            <input type="number" 
                                                   name="passing_score_min" 
                                                   class="form-control" 
                                                   placeholder="0" 
                                                   min="0" 
                                                   max="100"
                                                   value="{{ request('passing_score_min') }}">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="small text-muted">Min Questions</label>
                                            <input type="number" 
                                                   name="questions_min" 
                                                   class="form-control" 
                                                   placeholder="0" 
                                                   min="0"
                                                   value="{{ request('questions_min') }}">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="small text-muted">Sort By</label>
                                            <select name="sort_by" class="form-control">
                                                <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Latest</option>
                                                <option value="questions" {{ request('sort_by') == 'questions' ? 'selected' : '' }}>Most Questions</option>
                                                <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Title (A-Z)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="button button_1 mr-2">
                                            <span>Search</span>
                                        </button>
                                        @if(request()->hasAny(['search', 'course_id', 'time_limit', 'passing_score_min', 'questions_min', 'sort_by']))
                                        <a href="{{ route('landing.quizzes') }}" class="button button_outline">
                                            <span>Clear Filters</span>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Quizzes Posts -->
                @if($quizzes->count() > 0)
                <div class="news_posts">
                    @foreach($quizzes as $quiz)
                    <div class="news_post">
                        <div class="news_post_image">
                            @if(optional($quiz->lesson)->module->course && optional($quiz->lesson->module->course)->thumbnail)
                                <img src="{{ asset('storage/' . $quiz->lesson->module->course->thumbnail) }}" alt="{{ $quiz->title }}">
                            @else
                                <img src="{{ asset('images/landing/news_1.jpg') }}" alt="{{ $quiz->title }}">
                            @endif
                        </div>
                        <div class="news_post_top d-flex flex-column flex-sm-row">
                            <div class="news_post_date_container">
                                <div class="news_post_date d-flex flex-column align-items-center justify-content-center">
                                    <div>{{ $quiz->created_at->format('d') }}</div>
                                    <div>{{ strtolower($quiz->created_at->format('M')) }}</div>
                                </div>
                            </div>
                            <div class="news_post_title_container">
                                <div class="news_post_title">
                                    <a href="{{ route('quizzes.detail', $quiz) }}">{{ $quiz->title }}</a>
                                </div>
                                <div class="news_post_meta">
                                    <span class="news_post_author">
                                        @if(optional($quiz->lesson)->module->course)
                                            <a href="#">{{ $quiz->lesson->module->course->title }}</a>
                                        @else
                                            <a href="#">Smart Edu</a>
                                        @endif
                                    </span>
                                    <span>|</span>
                                    <span class="news_post_comments">
                                        <a href="#">{{ $quiz->questions_count ?? 0 }} Questions</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="news_post_text">
                            <p>{{ Str::limit($quiz->description ?? 'Test your knowledge with this comprehensive quiz', 150) }}</p>
                            <div class="mb-2">
                                @if($quiz->passing_score)
                                <span class="badge badge-success mr-2">{{ $quiz->passing_score }}% to Pass</span>
                                @endif
                                @if($quiz->time_limit_minutes)
                                <span class="badge badge-info mr-2">{{ $quiz->time_limit_minutes }} Min</span>
                                @endif
                            </div>
                        </div>
                        <div class="news_post_button text-center trans_200">
                            @auth
                                <a href="{{ route('quizzes.show', $quiz) }}">Take Quiz</a>
                            @else
                                <a href="{{ route('quizzes.detail', $quiz) }}">View Details</a>
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Page Nav / Pagination -->
                @if($quizzes->hasPages())
                <div class="news_page_nav">
                    <ul>
                        @foreach(range(1, min($quizzes->lastPage(), 10)) as $page)
                            @if($page == $quizzes->currentPage())
                                <li class="active text-center trans_200"><a href="#">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a></li>
                            @else
                                <li class="text-center trans_200"><a href="{{ $quizzes->url($page) }}">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a></li>
                            @endif
                        @endforeach
                        @if($quizzes->lastPage() > 10)
                            <li class="text-center trans_200"><a href="{{ $quizzes->url($quizzes->lastPage()) }}">...</a></li>
                        @endif
                    </ul>
                </div>
                @endif
                @else
                <div class="news_posts">
                    <div class="news_post">
                        <div class="news_post_text text-center">
                            <p class="lead text-muted">No quizzes found. Please try different search criteria.</p>
                            <div class="news_post_button text-center trans_200">
                                <a href="{{ route('landing.quizzes') }}">View All Quizzes</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                <div class="sidebar">

                    <!-- Archives -->
                    <div class="sidebar_section">
                        <div class="sidebar_section_title">
                            <h3>Quiz Categories</h3>
                        </div>
                        <ul class="sidebar_list">
                            @php
                                $courses = \App\Models\Course::where('is_published', true)
                                    ->has('modules.lessons.quiz')
                                    ->take(5)
                                    ->get();
                            @endphp
                            @if($courses->count() > 0)
                                @foreach($courses as $course)
                                <li class="sidebar_list_item">
                                    <a href="{{ route('landing.quizzes') }}?course={{ $course->id }}">{{ $course->title }}</a>
                                </li>
                                @endforeach
                            @else
                                <li class="sidebar_list_item"><a href="#">General Quizzes</a></li>
                                <li class="sidebar_list_item"><a href="#">Course Quizzes</a></li>
                                <li class="sidebar_list_item"><a href="#">Practice Tests</a></li>
                            @endif
                        </ul>
                    </div>

                    <!-- Latest Posts -->
                    <div class="sidebar_section">
                        <div class="sidebar_section_title">
                            <h3>Latest quizzes</h3>
                        </div>
                        
                        <div class="latest_posts">
                            @php
                                $latestQuizzes = \App\Models\Quiz::with(['lesson.module.course'])
                                    ->orderBy('created_at', 'desc')
                                    ->take(3)
                                    ->get();
                            @endphp
                            @foreach($latestQuizzes as $latestQuiz)
                            <div class="latest_post">
                                <div class="latest_post_image">
                                    @if(optional($latestQuiz->lesson)->module->course && optional($latestQuiz->lesson->module->course)->thumbnail)
                                        <img src="{{ asset('storage/' . $latestQuiz->lesson->module->course->thumbnail) }}" alt="{{ $latestQuiz->title }}">
                                    @else
                                        <img src="{{ asset('images/landing/latest_1.jpg') }}" alt="{{ $latestQuiz->title }}">
                                    @endif
                                </div>
                                <div class="latest_post_title">
                                    <a href="{{ route('quizzes.detail', $latestQuiz) }}">{{ $latestQuiz->title }}</a>
                                </div>
                                <div class="latest_post_meta">
                                    <span class="latest_post_author">
                                        @if(optional($latestQuiz->lesson)->module->course)
                                            <a href="#">{{ $latestQuiz->lesson->module->course->title }}</a>
                                        @else
                                            <a href="#">Smart Edu</a>
                                        @endif
                                    </span>
                                    <span>|</span>
                                    <span class="latest_post_comments">
                                        <a href="#">{{ $latestQuiz->questions_count ?? 0 }} Questions</a>
                                    </span>
                                </div>
                            </div>
                            @endforeach
                            
                            @if($latestQuizzes->count() == 0)
                            <div class="latest_post">
                                <div class="latest_post_title">
                                    <a href="#">No quizzes available</a>
                                </div>
                            </div>
                            @endif
                        </div>
                            
                    </div>

                    <!-- Tags -->
                    <div class="sidebar_section">
                        <div class="sidebar_section_title">
                            <h3>Tags</h3>
                        </div>
                        <div class="tags d-flex flex-row flex-wrap">
                            <div class="tag"><a href="#">Quiz</a></div>
                            <div class="tag"><a href="#">Test</a></div>
                            <div class="tag"><a href="#">Practice</a></div>
                            <div class="tag"><a href="#">Exam</a></div>
                            <div class="tag"><a href="#">Assessment</a></div>
                            <div class="tag"><a href="#">Learning</a></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/landing/news_styles.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/landing/news_responsive.css') }}">
<style>
    .button_outline {
        background: transparent;
        border: 2px solid #ffb606;
        height: 48px;
        padding-left: 38px;
        padding-right: 38px;
        border-radius: 0px;
        text-align: center;
        cursor: pointer;
        display: inline-block;
        transition: all 200ms ease;
        text-decoration: none;
    }
    
    .button_outline span {
        font-size: 14px;
        font-weight: 700;
        color: #ffb606;
        text-transform: uppercase;
        line-height: 48px;
        white-space: nowrap;
        text-decoration: none;
    }
    
    .button_outline:hover {
        background: #ffb606;
    }
    
    .button_outline:hover span {
        color: #FFFFFF;
    }
    
    .input-group-append .button {
        height: 48px;
        border-radius: 0;
        display: flex;
        align-items: center;
    }
    
    .input-group-append .button span {
        line-height: 48px;
    }
    
    .news_page_nav {
        margin-top: 40px;
    }
    
    .news_page_nav ul {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .news_page_nav ul li {
        margin: 0 5px;
    }
    
    .news_page_nav ul li a {
        display: block;
        width: 50px;
        height: 50px;
        line-height: 50px;
        text-align: center;
        color: #1e40af;
        text-decoration: none;
        border: 1px solid #1e40af;
        transition: all 0.3s ease;
    }
    
    .news_page_nav ul li.active a,
    .news_page_nav ul li:hover a {
        background-color: #1e40af;
        color: white;
    }
    
    /* Advanced Search Card Styling */
    #advancedSearch {
        border-top: 1px solid #e9ecef;
        padding-top: 1rem;
        margin-top: 1rem;
    }
    
    .card {
        border: 1px solid #e9ecef;
        background: white;
    }
    
    label.small {
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
        display: block;
    }
    
    .btn-link {
        color: #1e40af;
        text-decoration: none;
    }
    
    .btn-link:hover {
        color: #3b82f6;
    }
    
    .btn-link i {
        transition: transform 0.3s ease;
    }
    
    .btn-link[aria-expanded="true"] i {
        transform: rotate(180deg);
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/landing/news_custom.js') }}"></script>
@endpush
@endsection
