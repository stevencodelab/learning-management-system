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
        <h1>Quiz Details</h1>
    </div>
</div>

<!-- News -->
<div class="news">
    <div class="container">
        <div class="row">
            
            <!-- Quiz Post Column -->
            <div class="col-lg-8">
                
                <div class="news_post_container">
                    <!-- Quiz Post -->
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
                                    <a href="#">{{ $quiz->title }}</a>
                                </div>
                                <div class="news_post_meta">
                                    <span class="news_post_author">
                                        @if(optional($quiz->lesson)->module->course)
                                            <a href="{{ route('courses.detail', $quiz->lesson->module->course) }}">{{ $quiz->lesson->module->course->title }}</a>
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
                        
                        @if($quiz->description)
                        <div class="news_post_text">
                            <p>{!! nl2br(e($quiz->description)) !!}</p>
                        </div>
                        @endif

                        <!-- Quiz Instructions -->
                        @if($quiz->instructions)
                        <div class="news_post_quote">
                            <p class="news_post_quote_text"><span>I</span>{{ Str::limit($quiz->instructions, 200) }}</p>
                        </div>
                        @endif

                        <!-- Quiz Rules -->
                        <div class="news_post_text" style="margin-top: 40px;">
                            <h4 class="mb-3" style="color: #1e40af; font-weight: 600;">Quiz Rules & Requirements</h4>
                            
                            <ul style="line-height: 2; color: #555; padding-left: 20px;">
                                <li class="mb-2"><strong>Total Questions:</strong> {{ $quiz->questions_count }} questions</li>
                                
                                @if($quiz->passing_score)
                                <li class="mb-2"><strong>Passing Score:</strong> {{ $quiz->passing_score }}% is required to pass</li>
                                @endif
                                
                                @if($quiz->time_limit_minutes)
                                <li class="mb-2"><strong>Time Limit:</strong> You have {{ $quiz->time_limit_minutes }} minutes to complete</li>
                                @endif
                                
                                @if(!$quiz->allow_navigation)
                                <li class="mb-2"><strong>Navigation:</strong> You cannot go back to previous questions</li>
                                @endif
                                
                                @if($quiz->shuffle_questions)
                                <li class="mb-2"><strong>Question Order:</strong> Questions will appear in random order</li>
                                @endif
                                
                                @if($quiz->shuffle_answers)
                                <li class="mb-2"><strong>Answer Order:</strong> Answer options will be shuffled randomly</li>
                                @endif
                                
                                @if($quiz->negative_marking)
                                <li class="mb-2"><strong>Negative Marking:</strong> Incorrect answers deduct {{ $quiz->negative_mark_value ?? 1 }} point(s)</li>
                                @endif
                                
                                @if($quiz->require_all_questions)
                                <li class="mb-2"><strong>Mandatory:</strong> All questions must be answered before submission</li>
                                @endif
                                
                                @if($quiz->allow_multiple_attempts)
                                <li class="mb-2"><strong>Multiple Attempts:</strong> You can take this quiz multiple times
                                    @if($quiz->max_attempts) (Max {{ $quiz->max_attempts }}) @endif
                                </li>
                                @else
                                <li class="mb-2"><strong>Single Attempt:</strong> You can only take this quiz once</li>
                                @endif
                            </ul>
                        </div>

                        <!-- Pass/Fail Messages -->
                        @if($quiz->pass_message || $quiz->fail_message)
                        <div class="news_post_text" style="margin-top: 40px;">
                            @if($quiz->pass_message)
                            <div class="mb-3 p-3" style="background: #d4edda; border-left: 4px solid #28a745; border-radius: 4px;">
                                <strong class="text-success">
                                    <i class="fas fa-check-circle mr-2"></i>Pass Message
                                </strong>
                                <p class="mb-0 mt-2">{{ $quiz->pass_message }}</p>
                            </div>
                            @endif
                            
                            @if($quiz->fail_message)
                            <div class="mb-3 p-3" style="background: #f8d7da; border-left: 4px solid #dc3545; border-radius: 4px;">
                                <strong class="text-danger">
                                    <i class="fas fa-times-circle mr-2"></i>Fail Message
                                </strong>
                                <p class="mb-0 mt-2">{{ $quiz->fail_message }}</p>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Result Visibility -->
                        <div class="news_post_text" style="margin-top: 40px;">
                            <h4 class="mb-3" style="color: #1e40af; font-weight: 600;">Result Visibility</h4>
                            
                            @if($quiz->show_results_immediately)
                            <p class="mb-2">
                                <i class="fas fa-check text-success mr-2"></i>
                                <strong>Results:</strong> You will see your score immediately after submission
                            </p>
                            @endif
                            
                            @if($quiz->show_correct_answers)
                            <p class="mb-0">
                                <i class="fas fa-check text-success mr-2"></i>
                                <strong>Answers:</strong> Correct answers will be shown after you complete the quiz
                            </p>
                            @else
                            <p class="mb-0 text-muted">
                                <i class="fas fa-times text-danger mr-2"></i>
                                <strong>Answers:</strong> Correct answers will not be shown
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                <div class="sidebar">

                    <!-- Ready to Start -->
                    <div class="sidebar_section">
                        <div class="sidebar_section_title">
                            <h3>Ready to Start?</h3>
                        </div>
                        
                        @if($quiz->time_limit_minutes)
                        <p class="text-muted mb-4" style="text-align: center;">
                            <i class="fas fa-clock mr-2" style="color: #ffb606;"></i>
                            You'll have {{ $quiz->time_limit_minutes }} minutes
                        </p>
                        @endif
                        
                        @auth
                            @php
                                $userAttempts = $quiz->attempts()->where('user_id', auth()->id())->count();
                                $canAttempt = $quiz->canUserAttempt(auth()->id());
                            @endphp
                            
                            @if($canAttempt)
                                @if($userAttempts > 0)
                                    <div class="alert alert-info mb-4" style="font-size: 0.9rem; padding: 12px;">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Attempt {{ $userAttempts + 1 }} of {{ $quiz->max_attempts ?? 'Unlimited' }}
                                    </div>
                                @endif
                                <div class="button button_1 mb-3" style="width: 100%;">
                                    <a href="{{ route('quizzes.show', $quiz) }}">Start Quiz</a>
                                </div>
                            @else
                                <div class="alert alert-warning mb-4" style="font-size: 0.9rem; padding: 12px;">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    @if($quiz->max_attempts)
                                        Max attempts reached
                                    @else
                                        Quiz already completed
                                    @endif
                                </div>
                                <div class="button button_outline mb-3" style="width: 100%;">
                                    <a href="{{ route('quizzes.show', $quiz) }}">View Results</a>
                                </div>
                            @endif
                        @else
                            <div class="button button_1 mb-3" style="width: 100%;">
                                <a href="{{ route('login') }}">Login to Take Quiz</a>
                            </div>
                            <div class="button button_outline mb-3" style="width: 100%;">
                                <a href="{{ route('register') }}">Create Account</a>
                            </div>
                        @endauth
                    </div>

                    <!-- Related Course -->
                    @if(optional($quiz->lesson)->module->course)
                    <div class="sidebar_section">
                        <div class="sidebar_section_title">
                            <h3>Related Course</h3>
                        </div>
                        <div class="latest_post">
                            @if($quiz->lesson->module->course->thumbnail)
                            <div class="latest_post_image">
                                <img src="{{ asset('storage/' . $quiz->lesson->module->course->thumbnail) }}" alt="{{ $quiz->lesson->module->course->title }}">
                            </div>
                            @endif
                            <div class="latest_post_title">
                                <a href="{{ route('courses.detail', $quiz->lesson->module->course) }}">{{ $quiz->lesson->module->course->title }}</a>
                            </div>
                            <div class="latest_post_meta">
                                <span class="latest_post_author">
                                    <a href="{{ route('courses.detail', $quiz->lesson->module->course) }}">View Course</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Quiz Stats -->
                    <div class="sidebar_section">
                        <div class="sidebar_section_title">
                            <h3>Quiz Stats</h3>
                        </div>
                        <ul class="sidebar_list">
                            <li class="sidebar_list_item">
                                <strong>Questions:</strong> {{ $quiz->questions_count }}
                            </li>
                            @if($quiz->passing_score)
                            <li class="sidebar_list_item">
                                <strong>Pass Score:</strong> {{ $quiz->passing_score }}%
                            </li>
                            @endif
                            @if($quiz->time_limit_minutes)
                            <li class="sidebar_list_item">
                                <strong>Time Limit:</strong> {{ $quiz->time_limit_minutes }} min
                            </li>
                            @endif
                            <li class="sidebar_list_item">
                                <strong>Attempts:</strong> {{ $quiz->attempts()->count() }}
                            </li>
                        </ul>
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
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/landing/news_post_styles.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/landing/news_post_responsive.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/landing/news_post_custom.js') }}"></script>
@endpush
@endsection
