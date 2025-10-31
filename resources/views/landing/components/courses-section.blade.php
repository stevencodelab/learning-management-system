<!-- Popular Courses Section -->
@php
use Illuminate\Support\Str;
@endphp

<div class="popular page_section" id="courses">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="section_title text-center">
                    <h1>Available Courses</h1>
                    <p class="lead">Discover and enroll in our comprehensive learning courses</p>
                </div>
            </div>
        </div>
        
        @if($courses->count() > 0)
        <div class="row course_boxes course-cards-compact">
            @foreach($courses as $course)
            <div class="col-lg-4 col-md-6 course_box mb-3">
                <div class="card h-100 course-card-compact">
                    <img class="card-img-top course-image-compact" 
                         src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : asset('images/landing/course_1.jpg') }}" 
                         alt="{{ $course->title }}">
                    <div class="card-body text-center course-body-compact">
                        <div class="card-title course-title-compact">
                            <a href="{{ route('courses.show', $course) }}">{{ Str::limit($course->title, 50) }}</a>
                        </div>
                        <div class="card-text course-description-compact">{{ Str::limit($course->description, 80) }}</div>
                        <div class="course-badges-compact">
                            <span class="badge badge-primary badge-sm">{{ ucfirst($course->level) }}</span>
                            <span class="badge badge-info badge-sm">{{ $course->modules->count() }} Modules</span>
                        </div>
                    </div>
                    <div class="price_box d-flex flex-row align-items-center price-box-compact">
                        <div class="course_author_image course-author-compact">
                            @if($course->instructor && $course->instructor->avatar)
                                <img src="{{ asset('storage/' . $course->instructor->avatar) }}" alt="{{ $course->instructor->name }}">
                            @elseif($course->instructor)
                                <img src="{{ $course->instructor->avatar_url }}" alt="{{ $course->instructor->name }}" onerror="this.src='{{ asset('images/landing/author.jpg') }}'">
                            @else
                                <img src="{{ asset('images/landing/author.jpg') }}" alt="Instructor">
                            @endif
                        </div>
                        <div class="course_author_name course-author-name-compact">
                            @if($course->instructor)
                                {{ Str::limit($course->instructor->name, 15) }}
                            @else
                                Smart Edu
                            @endif
                        </div>
                        <div class="course_price d-flex flex-column align-items-center justify-content-center course-price-compact">
                            <span>{{ $course->price == 0 ? 'Free' : '$' . number_format($course->price, 0) }}</span>
                        </div>
                    </div>
                    <div class="card-footer course-footer-compact">
                        @auth
                            @php
                                $enrolled = auth()->user()->courses()->where('course_id', $course->id)->exists();
                            @endphp
                            @if($enrolled)
                                <a href="{{ route('courses.show', $course) }}" class="btn btn-success btn-xs w-100">
                                    Continue Learning
                                </a>
                            @else
                                <a href="{{ route('courses.show', $course) }}" class="btn btn-primary btn-xs w-100">
                                    Enroll Now
                                </a>
                            @endif
                        @else
                            <a href="{{ route('courses.detail', $course) }}" class="btn btn-outline-info btn-xs w-100 mb-1">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-primary btn-xs w-100">
                                Login to Enroll
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if(method_exists($courses, 'hasPages') && $courses->hasPages())
        <div class="row mt-4">
            <div class="col">
                <div class="d-flex justify-content-center">
                    {{ $courses->links() }}
                </div>
            </div>
        </div>
        @else
        <div class="row mt-3">
            <div class="col text-center">
                <a href="{{ route('landing.courses') }}" class="button button_1">
                    <span>View All Courses</span>
                </a>
            </div>
        </div>
        @endif
        @else
        <div class="row">
            <div class="col text-center">
                <p class="lead text-muted">No courses available at the moment. Please check back later.</p>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .course-cards-compact {
        display: flex;
        flex-wrap: wrap;
    }
    
    .course-cards-compact > [class*='col-'] {
        display: flex;
    }
    
    .course-card-compact {
        display: flex;
        flex-direction: column;
        width: 100%;
        transition: all 0.3s ease;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }
    
    .course-card-compact:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.12);
    }
    
    .course-image-compact {
        height: 140px;
        object-fit: cover;
    }
    
    .course-body-compact {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 1rem;
    }
    
    .course-title-compact {
        margin-bottom: 0.5rem;
        min-height: 2.5rem;
    }
    
    .course-title-compact a {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e40af;
        text-decoration: none;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .course-title-compact a:hover {
        color: #3b82f6;
        text-decoration: none;
    }
    
    .course-description-compact {
        font-size: 0.8rem;
        color: #6c757d;
        line-height: 1.4;
        min-height: 2.5rem;
        margin-bottom: 0.75rem;
    }
    
    .course-badges-compact {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        justify-content: center;
        margin-bottom: 0.5rem;
    }
    
    .course-badges-compact .badge-sm {
        font-size: 0.65rem;
        padding: 0.25em 0.5em;
        font-weight: 500;
    }
    
    .price-box-compact {
        padding: 0.5rem 1rem;
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
        border-bottom: 1px solid #e9ecef;
    }
    
    .course-author-compact {
        width: 30px;
        height: 30px;
        margin-right: 0.5rem;
    }
    
    .course-author-compact img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .course-author-name-compact {
        font-size: 0.8rem;
        color: #6c757d;
        flex: 1;
    }
    
    .course-price-compact {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1e40af;
    }
    
    .course-footer-compact {
        padding: 0.75rem 1rem;
        background-color: #ffffff;
    }
    
    .course-footer-compact .btn-xs {
        font-size: 0.75rem;
        padding: 0.35rem 0.75rem;
        line-height: 1.4;
    }
    
    @media (min-width: 992px) {
        .course-cards-compact {
            align-items: stretch;
        }
    }
    
    .pagination {
        justify-content: center;
    }
    
    .pagination .page-link {
        color: #1e40af;
        border-color: #1e40af;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #1e40af;
        border-color: #1e40af;
    }
</style>
@endpush
