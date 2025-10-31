@extends('layouts.landing')

@section('content')
@php
use Illuminate\Support\Str;
@endphp

<!-- Home -->
<div class="home">
    <div class="home_background_container prlx_parent">
        <div class="home_background prlx" style="background-image:url({{ asset('images/landing/courses_background.jpg') }})"></div>
    </div>
    <div class="home_content">
        <h1>Courses</h1>
    </div>
</div>

<!-- Popular -->
<div class="popular page_section">
    <div class="container">
        <!-- Advanced Search and Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0" style="color: #1e40af; font-weight: 600;">
                                <i class="fas fa-filter mr-2"></i>Advanced Search
                            </h5>
                            <button type="button" class="btn btn-link p-0" data-toggle="collapse" data-target="#advancedSearch" aria-expanded="{{ request()->hasAny(['search', 'level', 'price_min', 'price_max', 'price_type', 'sort_by']) ? 'true' : 'false' }}">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        
                        <form action="{{ route('landing.courses') }}" method="GET" id="searchForm">
                            <!-- Basic Search -->
                            <div class="row mb-3">
                                <div class="col-md-8 mb-2">
                                    <input type="text" 
                                           name="search" 
                                           class="form-control form-control-lg" 
                                           placeholder="Search courses by title or description..." 
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <select name="level" class="form-control form-control-lg">
                                        <option value="">All Levels</option>
                                        <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                        <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                        <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Advanced Filters (Collapsible) -->
                            <div class="collapse {{ request()->hasAny(['price_min', 'price_max', 'price_type', 'sort_by']) ? 'show' : '' }}" id="advancedSearch">
                                <div class="row mb-3">
                                    <div class="col-md-3 mb-2">
                                        <label class="small text-muted">Price Type</label>
                                        <select name="price_type" class="form-control">
                                            <option value="">All Prices</option>
                                            <option value="free" {{ request('price_type') == 'free' ? 'selected' : '' }}>Free</option>
                                            <option value="paid" {{ request('price_type') == 'paid' ? 'selected' : '' }}>Paid</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="small text-muted">Min Price ($)</label>
                                        <input type="number" 
                                               name="price_min" 
                                               class="form-control" 
                                               placeholder="0" 
                                               min="0" 
                                               step="0.01"
                                               value="{{ request('price_min') }}">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="small text-muted">Max Price ($)</label>
                                        <input type="number" 
                                               name="price_max" 
                                               class="form-control" 
                                               placeholder="999" 
                                               min="0" 
                                               step="0.01"
                                               value="{{ request('price_max') }}">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="small text-muted">Sort By</label>
                                        <select name="sort_by" class="form-control">
                                            <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Latest</option>
                                            <option value="price_low" {{ request('sort_by') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                            <option value="price_high" {{ request('sort_by') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
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
                                    @if(request()->hasAny(['search', 'level', 'price_min', 'price_max', 'price_type', 'sort_by']))
                                    <a href="{{ route('landing.courses') }}" class="button button_outline">
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

        <div class="row">
            <div class="col">
                <div class="section_title text-center">
                    <h1>Popular Courses</h1>
                </div>
            </div>
        </div>

        <!-- Courses Grid -->
        @if($courses->count() > 0)
        <div class="row course_boxes">
            @foreach($courses as $course)
            <div class="col-lg-4 course_box">
                <div class="card">
                    <img class="card-img-top" 
                         src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : asset('images/landing/course_1.jpg') }}" 
                         alt="{{ $course->title }}">
                    <div class="card-body text-center">
                        <div class="card-title">
                            <a href="{{ route('courses.detail', $course) }}">{{ $course->title }}</a>
                        </div>
                        <div class="card-text">{{ Str::limit($course->description, 100) }}</div>
                    </div>
                    <div class="price_box d-flex flex-row align-items-center">
                        <div class="course_author_image">
                            @if($course->instructor && $course->instructor->avatar)
                                <img src="{{ asset('storage/' . $course->instructor->avatar) }}" alt="{{ $course->instructor->name }}">
                            @elseif($course->instructor)
                                <img src="{{ $course->instructor->avatar_url }}" alt="{{ $course->instructor->name }}" onerror="this.src='{{ asset('images/landing/author.jpg') }}'">
                            @else
                                <img src="{{ asset('images/landing/author.jpg') }}" alt="Instructor">
                            @endif
                        </div>
                        <div class="course_author_name">
                            @if($course->instructor)
                                {{ Str::limit($course->instructor->name, 20) }}, <span>Instructor</span>
                            @else
                                Smart Edu, <span>Author</span>
                            @endif
                        </div>
                        <div class="course_price d-flex flex-column align-items-center justify-content-center">
                            <span>{{ $course->price == 0 ? 'Free' : '$' . number_format($course->price, 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-5">
            <div class="col">
                <div class="d-flex justify-content-center">
                    {{ $courses->links() }}
                </div>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col text-center">
                <p class="lead text-muted">No courses found. Please try different search criteria.</p>
                <a href="{{ route('landing.courses') }}" class="button button_1 mt-3">
                    <span>View All Courses</span>
                </a>
            </div>
        </div>
        @endif
    </div>		
</div>

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/landing/courses_styles.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/landing/courses_responsive.css') }}">
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
    
    /* Advanced Search Card Styling */
    #advancedSearch {
        border-top: 1px solid #e9ecef;
        padding-top: 1rem;
        margin-top: 1rem;
    }
    
    .card {
        border: 1px solid #e9ecef;
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
<script src="{{ asset('js/landing/courses_custom.js') }}"></script>
@endpush
@endsection

