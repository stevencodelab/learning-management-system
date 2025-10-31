@extends('layouts.landing')

@section('content')

<!-- Home -->
<div class="home">
    <div class="home_background_container prlx_parent">
        <div class="home_background prlx" style="background-image:url({{ asset('images/landing/teachers_background.jpg') }})"></div>
    </div>
    <div class="home_content">
        <h1>Teachers</h1>
    </div>
</div>

<!-- Teachers -->
<div class="teachers page_section">
    <div class="container">
        <!-- Search -->
        <div class="row mb-4">
            <div class="col-lg-6 mx-auto">
                <form action="{{ route('landing.teachers') }}" method="GET">
                    <div class="input-group input-group-lg">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Search teachers..." 
                               value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button type="submit" class="button button_1" style="margin: 0;">
                                <span>Search</span>
                            </button>
                        </div>
                    </div>
                    @if(request()->has('search'))
                    <div class="text-center mt-3">
                        <a href="{{ route('landing.teachers') }}" class="button button_outline">
                            <span>Clear Search</span>
                        </a>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Teachers Grid -->
        @if($teachers->count() > 0)
        <div class="row">
            @foreach($teachers as $teacher)
            <div class="col-lg-4 teacher">
                <div class="card">
                    <div class="card_img">
                        <div class="card_plus trans_200 text-center">
                            <a href="mailto:{{ $teacher->email }}">+</a>
                        </div>
                        @if($teacher->avatar)
                            <img class="card-img-top trans_200" src="{{ asset('storage/' . $teacher->avatar) }}" alt="{{ $teacher->name }}">
                        @else
                            <img class="card-img-top trans_200" src="{{ $teacher->avatar_url }}" alt="{{ $teacher->name }}" onerror="this.src='{{ asset('images/landing/teacher_1.jpg') }}'">
                        @endif
                    </div>
                    <div class="card-body text-center">
                        <div class="card-title"><a href="#">{{ $teacher->name }}</a></div>
                        <div class="card-text">
                            @if($teacher->level)
                                {{ ucfirst($teacher->level) }} Instructor
                            @else
                                Expert Instructor
                            @endif
                        </div>
                        <div class="teacher_social">
                            <ul class="menu_social">
                                <li class="menu_social_item">
                                    <a href="mailto:{{ $teacher->email }}" title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </li>
                                @if($teacher->phone)
                                <li class="menu_social_item">
                                    <a href="tel:{{ $teacher->phone }}" title="Phone">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                </li>
                                @endif
                                <li class="menu_social_item">
                                    <a href="#" title="LinkedIn">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                </li>
                                <li class="menu_social_item">
                                    <a href="#" title="Twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        @if(($teacher->available_courses ?? 0) > 0)
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-book mr-1"></i>{{ $teacher->available_courses ?? 0 }} Available Courses
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row">
            <div class="col text-center">
                <p class="lead text-muted">No teachers found. Please try different search criteria.</p>
                <a href="{{ route('landing.teachers') }}" class="button button_1 mt-3">
                    <span>View All Teachers</span>
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Milestones -->
<div class="milestones">
    <div class="milestones_background" style="background-image:url({{ asset('images/landing/milestones_background.jpg') }})"></div>

    <div class="container">
        <div class="row">
            <!-- Milestone -->
            <div class="col-lg-3 milestone_col">
                <div class="milestone text-center">
                    <div class="milestone_icon"><img src="{{ asset('images/landing/milestone_1.svg') }}" alt=""></div>
                    <div class="milestone_counter" data-end-value="{{ \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'student'); })->count() }}">0</div>
                    <div class="milestone_text">Current Students</div>
                </div>
            </div>

            <!-- Milestone -->
            <div class="col-lg-3 milestone_col">
                <div class="milestone text-center">
                    <div class="milestone_icon"><img src="{{ asset('images/landing/milestone_2.svg') }}" alt=""></div>
                    <div class="milestone_counter" data-end-value="{{ $teachers->count() }}">0</div>
                    <div class="milestone_text">Certified Teachers</div>
                </div>
            </div>

            <!-- Milestone -->
            <div class="col-lg-3 milestone_col">
                <div class="milestone text-center">
                    <div class="milestone_icon"><img src="{{ asset('images/landing/milestone_3.svg') }}" alt=""></div>
                    <div class="milestone_counter" data-end-value="{{ \App\Models\Course::where('is_published', true)->count() }}">0</div>
                    <div class="milestone_text">Approved Courses</div>
                </div>
            </div>

            <!-- Milestone -->
            <div class="col-lg-3 milestone_col">
                <div class="milestone text-center">
                    <div class="milestone_icon"><img src="{{ asset('images/landing/milestone_4.svg') }}" alt=""></div>
                    <div class="milestone_counter" data-end-value="{{ \App\Models\Enrollment::whereNotNull('completed_at')->count() }}" data-sign-before="+">0</div>
                    <div class="milestone_text">Graduate Students</div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Become -->
<div class="become">
    <div class="container">
        <div class="row row-eq-height">

            <div class="col-lg-6 order-2 order-lg-1">
                <div class="become_title">
                    <h1>Become a teacher</h1>
                </div>
                <p class="become_text">Join our community of expert instructors and share your knowledge with thousands of students worldwide. Create engaging courses, build your reputation, and earn income while making a positive impact on learners' lives. Whether you're a professional in your field or an experienced educator, we welcome you to become part of our teaching community.</p>
                <div class="become_button text-center trans_200">
                    @auth
                        @if(auth()->user()->hasRole('instructor'))
                            <a href="{{ route('dashboard') }}">Go to Dashboard</a>
                        @else
                            <a href="{{ route('register') }}">Join as Instructor</a>
                        @endif
                    @else
                        <a href="{{ route('register') }}">Join as Instructor</a>
                    @endauth
                </div>
            </div>

            <div class="col-lg-6 order-1 order-lg-2">
                <div class="become_image">
                    <img src="{{ asset('images/landing/become.jpg') }}" alt="">
                </div>
            </div>
            
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/landing/teachers_styles.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/landing/teachers_responsive.css') }}">
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
    
    .teacher .card-img-top {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }
    
    .card_plus {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        width: 60px;
        height: 60px;
        background: #ffb606;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        visibility: hidden;
        opacity: 0;
        transition: all 0.3s ease;
    }
    
    .card_plus a {
        color: white;
        font-size: 32px;
        font-weight: 300;
        text-decoration: none;
        line-height: 1;
    }
    
    .card_img {
        position: relative;
        overflow: hidden;
    }
    
    .teacher_social {
        margin-top: 20px;
    }
    
    .teacher_social .menu_social {
        justify-content: center;
    }
    
    .become_image img {
        width: 100%;
        height: auto;
        border-radius: 8px;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/landing/teachers_custom.js') }}"></script>
@endpush
@endsection

