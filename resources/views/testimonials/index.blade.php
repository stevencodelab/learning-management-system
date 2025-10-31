@extends('layouts.landing')

@section('content')

<!-- Home -->
<div class="home">
    <div class="home_background_container prlx_parent">
        <div class="home_background prlx" style="background-image:url({{ asset('images/landing/testimonials_background.jpg') }})"></div>
    </div>
    <div class="home_content">
        <h1>Testimonials</h1>
    </div>
</div>

<!-- Testimonials -->
<div class="elements">
    <div class="container">
        <!-- Title -->
        <div class="row">
            <div class="col">
                <div class="elements_title text-center">
                    <h1>What Our Students Say</h1>
                    <p class="text-muted mt-3">Read testimonials from students who have experienced our courses</p>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto">
                <div class="card" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div class="card-body">
                        <form action="{{ route('testimonials.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input type="text" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="Search testimonials..." 
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <select name="course_id" class="form-control">
                                        <option value="">All Courses</option>
                                        @foreach($courses ?? [] as $course)
                                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <select name="featured" class="form-control">
                                        <option value="">All Testimonials</option>
                                        <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured Only</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit" class="button button_1 mr-2">
                                        <span>Search</span>
                                    </button>
                                    @if(request()->hasAny(['search', 'course_id', 'featured']))
                                    <a href="{{ route('testimonials.index') }}" class="button button_outline">
                                        <span>Clear Filters</span>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col text-center">
                @auth
                @if(auth()->user()->hasRole('student'))
                <a href="{{ route('testimonials.create') }}" class="button button_1">
                    <span>Share Your Experience</span>
                </a>
                @endif
                @else
                <a href="{{ route('login') }}" class="button button_1">
                    <span>Login to Share Your Experience</span>
                </a>
                @endauth
            </div>
        </div>

        <!-- Testimonials Grid -->
        @if($testimonials->count() > 0)
        <div class="row icon_boxes_container">
            @foreach($testimonials as $testimonial)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="icon_box text-left d-flex flex-column align-items-start justify-content-start h-100" 
                     style="background: #f8f9fa; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: 100%;">
                    <!-- Rating Stars -->
                    @if($testimonial->rating)
                    <div class="mb-3" style="color: #ffb606;">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $testimonial->rating)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                        <span class="ml-2 text-muted">({{ $testimonial->rating }}/5)</span>
                    </div>
                    @endif

                    <!-- Testimonial Text -->
                    <div class="mb-3" style="flex-grow: 1;">
                        <p style="font-style: italic; color: #555; line-height: 1.8;">
                            "{{ Str::limit($testimonial->testimonial_text, 200) }}"
                        </p>
                    </div>

                    <!-- User Info -->
                    <div class="d-flex align-items-center w-100" style="border-top: 1px solid #e9ecef; padding-top: 15px;">
                        <div class="mr-3">
                            @if($testimonial->user->avatar)
                                <img src="{{ asset('storage/' . $testimonial->user->avatar) }}" 
                                     alt="{{ $testimonial->user->name }}" 
                                     style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                            @else
                                <img src="{{ $testimonial->user->avatar_url }}" 
                                     alt="{{ $testimonial->user->name }}" 
                                     style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;"
                                     onerror="this.src='{{ asset('images/landing/testimonials_user.jpg') }}'">
                            @endif
                        </div>
                        <div style="flex: 1;">
                            <h6 class="mb-1" style="color: #1e40af; font-weight: 600;">{{ $testimonial->user->name }}</h6>
                            @if($testimonial->course)
                            <p class="text-muted mb-0" style="font-size: 0.875rem;">
                                <i class="fas fa-book mr-1"></i>{{ Str::limit($testimonial->course->title, 30) }}
                            </p>
                            @else
                            <p class="text-muted mb-0" style="font-size: 0.875rem;">Student</p>
                            @endif
                        </div>
                        @if($testimonial->is_featured)
                        <div>
                            <span class="badge badge-warning" style="font-size: 0.75rem;">
                                <i class="fas fa-star"></i> Featured
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Date -->
                    <div class="mt-2 w-100">
                        <small class="text-muted">
                            <i class="far fa-calendar mr-1"></i>{{ $testimonial->created_at->format('M d, Y') }}
                        </small>
                    </div>

                    <!-- Action Buttons (for own testimonial) -->
                    @auth
                    @if(auth()->id() == $testimonial->user_id)
                    <div class="mt-3 w-100" style="border-top: 1px solid #e9ecef; padding-top: 10px;">
                        <a href="{{ route('testimonials.edit', $testimonial) }}" class="btn btn-sm btn-outline-primary mr-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('testimonials.destroy', $testimonial) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                    @endif
                    @endauth
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-5">
            <div class="col">
                <div class="d-flex justify-content-center">
                    {{ $testimonials->links() }}
                </div>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col text-center py-5">
                <i class="fas fa-comments" style="font-size: 64px; color: #ccc;"></i>
                <h4 class="mt-3">No testimonials found</h4>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'course_id', 'featured']))
                        Try adjusting your search criteria.
                    @else
                        No testimonials have been published yet.
                    @endif
                </p>
                @auth
                @if(auth()->user()->hasRole('student'))
                <a href="{{ route('testimonials.create') }}" class="button button_1 mt-3">
                    <span>Be the First to Share</span>
                </a>
                @endif
                @endauth
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
                    <div class="milestone_counter" data-end-value="{{ \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'instructor'); })->count() }}">0</div>
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
                    <div class="milestone_counter" data-end-value="{{ \App\Models\Testimonial::where('is_approved', true)->count() }}" data-sign-before="+">0</div>
                    <div class="milestone_text">Testimonials</div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/landing/elements_styles.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/landing/elements_responsive.css') }}">
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

    .icon_box {
        transition: all 0.3s ease;
    }

    .icon_box:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/landing/elements_custom.js') }}"></script>
@endpush
@endsection

