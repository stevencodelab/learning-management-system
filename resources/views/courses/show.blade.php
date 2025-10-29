@extends('layouts.skydash')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<!-- Header Section -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <div class="d-flex align-items-center">
                    <div>
                        <h3 class="font-weight-bold mb-2">{{ $course->title }}</h3>
                        <div class="d-flex align-items-center mb-2">
                            @php
                                $levelColors = [
                                    'beginner' => 'success',
                                    'intermediate' => 'warning',
                                    'advanced' => 'danger'
                                ];
                            @endphp
                            <span class="badge badge-{{ $levelColors[$course->level] ?? 'info' }} mr-2">
                                {{ ucfirst($course->level) }}
                            </span>
                            <span class="badge badge-{{ $course->is_published ? 'success' : 'warning' }} mr-2">
                                {{ $course->is_published ? 'Published' : 'Draft' }}
                            </span>
                            @if($course->price == 0)
                                <span class="badge badge-info">FREE</span>
                            @endif
                        </div>
                        <p class="text-muted mb-0">{{ Str::limit($course->description, 100) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']))
                        <a href="{{ route('courses.edit', $course) }}" class="btn btn-primary mr-2">
                            <i class="icon-pencil"></i> Edit
                        </a>
                    @endif
                    <a href="{{ route('courses.index') }}" class="btn btn-light">
                        <i class="icon-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Course Hero Section -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card" style="overflow: hidden; border: none;">
            @if($course->thumbnail)
                <img src="{{ Storage::url($course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 400px; object-fit: cover;">
            @else
                <div style="height: 400px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="icon-book" style="font-size: 120px; color: rgba(255, 255, 255, 0.3);"></i>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-md-8 grid-margin stretch-card">
        
        <!-- Course Information Card -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="icon-info text-primary"></i> About This Course
                </h4>
                
                <div class="mb-4">
                    <p class="text-muted" style="line-height: 1.8; font-size: 1rem;">{{ $course->description }}</p>
                </div>
                
                <hr class="my-4">
                
                <!-- Course Stats Grid -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="info-card p-3 border rounded">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-primary text-white mr-3">
                                    <i class="icon-grid"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0" style="font-size: 0.875rem;">Level</p>
                                    <h6 class="mb-0 font-weight-bold">{{ ucfirst($course->level) }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="info-card p-3 border rounded">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-info text-white mr-3">
                                    <i class="icon-clock"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0" style="font-size: 0.875rem;">Duration</p>
                                    <h6 class="mb-0 font-weight-bold">{{ $course->duration_hours }} Hours</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="info-card p-3 border rounded">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-success text-white mr-3">
                                    <i class="icon-folder"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0" style="font-size: 0.875rem;">Modules</p>
                                    <h6 class="mb-0 font-weight-bold">{{ $course->modules->count() }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modules Section -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="icon-folder text-primary"></i> Course Modules ({{ $course->modules->count() }})
                    </h4>
                    @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']))
                        <a href="{{ route('modules.create', $course) }}" class="btn btn-sm btn-primary">
                            <i class="icon-plus"></i> Add Module
                        </a>
                    @endif
                </div>
                
                @if($course->modules->count() > 0)
                    <div class="modules-list">
                        @foreach($course->modules as $module)
                        <div class="module-item card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div style="flex: 1;">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge badge-primary mr-2" style="min-width: 80px;">
                                                Module {{ $module->order }}
                                            </span>
                                            @if($module->lessons->count() > 0)
                                                <span class="badge badge-info">
                                                    {{ $module->lessons->count() }} {{ Str::plural('Lesson', $module->lessons->count()) }}
                                                </span>
                                            @endif
                                        </div>
                                        <h5 class="mb-2 font-weight-bold">{{ $module->title }}</h5>
                                        @if($module->description)
                                            <p class="text-muted mb-3">{{ Str::limit($module->description, 150) }}</p>
                                        @endif
                                        
                                        @if($module->lessons->count() > 0)
                                            <div class="lessons-preview">
                                                @foreach($module->lessons->take(3) as $lesson)
                                                    <div class="lesson-item mb-2 p-2 bg-light rounded">
                                                        <div class="d-flex align-items-center">
                                                            <i class="icon-{{ $lesson->type == 'video' ? 'camcorder' : ($lesson->type == 'reading' ? 'book-open' : ($lesson->type == 'audio' ? 'volume-2' : 'pencil')) }} mr-2 text-primary"></i>
                                                            <span class="font-weight-medium">{{ $lesson->title }}</span>
                                                            @if($lesson->is_free)
                                                                <span class="badge badge-success ml-2">FREE</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @if($module->lessons->count() > 3)
                                                    <p class="text-muted mb-0 small">
                                                        + {{ $module->lessons->count() - 3 }} more {{ Str::plural('lesson', $module->lessons->count() - 3) }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']))
                                        <div class="ml-3">
                                            <a href="{{ route('modules.edit', $module) }}" class="btn btn-sm btn-primary">
                                                <i class="icon-pencil"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="icon-folder" style="font-size: 64px; color: #e3e6f0;"></i>
                        <h5 class="mt-3 mb-2 text-muted">No modules yet</h5>
                        <p class="text-muted">Add modules to organize course content</p>
                    </div>
                @endif
            </div>
        </div>
        
    </div>
    
    <!-- Sidebar -->
    <div class="col-md-4 grid-margin">
        
        @if(auth()->check() && !auth()->user()->hasAnyRole(['admin', 'instructor']))
            <!-- Student Enrollment Section -->
            <div class="card enrollment-card sticky-top" style="top: 20px; border: 2px solid #667eea;">
                <div class="card-body">
                    @if($isEnrolled)
                        <div class="text-center mb-4">
                            <div class="icon-circle-lg bg-gradient-success text-white d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="icon-check-circle" style="font-size: 32px;"></i>
                            </div>
                            <h5 class="mb-1">Enrolled!</h5>
                            <p class="text-muted small mb-0">You're enrolled in this course</p>
                        </div>
                        
                        @if($enrollment)
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted"><strong>Progress</strong></span>
                                    <span class="font-weight-bold text-primary">{{ $enrollment->progress_percentage }}%</span>
                                </div>
                                <div class="progress mb-3" style="height: 24px; border-radius: 12px;">
                                    <div class="progress-bar bg-gradient-primary" role="progressbar" 
                                         style="width: {{ $enrollment->progress_percentage }}%; border-radius: 12px;">
                                    </div>
                                </div>
                                <p class="text-muted small mb-0">
                                    <i class="icon-calendar mr-1"></i>Enrolled: {{ $enrollment->enrolled_at->format('M d, Y') }}
                                </p>
                            </div>
                        @endif
                        
                        <a href="{{ $course->url }}" class="btn btn-primary btn-block btn-lg mb-2">
                            <i class="icon-control-play mr-2"></i> Continue Learning
                        </a>
                        
                        <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" 
                              onsubmit="event.preventDefault(); confirmDelete(event, 'Are you sure you want to unenroll from this course?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-block">
                                <i class="icon-close mr-1"></i> Unenroll
                            </button>
                        </form>
                    @else
                        <div class="text-center mb-4">
                            <div class="icon-circle-lg bg-gradient-primary text-white d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="icon-book-open" style="font-size: 32px;"></i>
                            </div>
                            <h5 class="mb-2">{{ Str::limit($course->title, 40) }}</h5>
                            <div class="mb-3">
                                @if($course->price > 0)
                                    <h2 class="text-primary font-weight-bold mb-0">${{ number_format($course->price, 2) }}</h2>
                                @else
                                    <span class="badge badge-success" style="font-size: 1.25rem; padding: 10px 20px;">FREE</span>
                                @endif
                            </div>
                        </div>
                        
                        @if($course->is_published)
                            <form action="{{ route('enrollments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                <button type="submit" class="btn btn-primary btn-block btn-lg mb-3">
                                    <i class="icon-plus mr-2"></i> Enroll Now
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary btn-block btn-lg mb-3" disabled>
                                <i class="icon-lock mr-2"></i> Course Not Available
                            </button>
                        @endif
                        
                        <div class="course-info-list">
                            <div class="info-item d-flex justify-content-between align-items-center py-2 border-top">
                                <span class="text-muted"><i class="icon-clock mr-2"></i>Duration</span>
                                <strong>{{ $course->duration_hours }} Hours</strong>
                            </div>
                            <div class="info-item d-flex justify-content-between align-items-center py-2 border-top">
                                <span class="text-muted"><i class="icon-grid mr-2"></i>Level</span>
                                <strong>{{ ucfirst($course->level) }}</strong>
                            </div>
                            <div class="info-item d-flex justify-content-between align-items-center py-2 border-top">
                                <span class="text-muted"><i class="icon-folder mr-2"></i>Modules</span>
                                <strong>{{ $course->modules->count() }}</strong>
                            </div>
                            <div class="info-item d-flex justify-content-between align-items-center py-2 border-top">
                                <span class="text-muted"><i class="icon-note mr-2"></i>Lessons</span>
                                <strong>{{ $course->lessons_count }}</strong>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        
        @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']))
            <!-- Admin/Instructor Actions -->
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="icon-settings"></i> Quick Actions
                    </h5>
                    
                    <div class="action-info mb-3">
                        <p class="text-muted mb-1 small">Status</p>
                        <span class="badge badge-{{ $course->is_published ? 'success' : 'warning' }}">
                            {{ $course->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    
                    <div class="action-info mb-3">
                        <p class="text-muted mb-1 small">Created</p>
                        <p class="mb-0"><strong>{{ $course->created_at->format('M d, Y') }}</strong></p>
                    </div>
                    
                    <div class="action-info mb-4">
                        <p class="text-muted mb-1 small">Last Updated</p>
                        <p class="mb-0"><strong>{{ $course->updated_at->format('M d, Y') }}</strong></p>
                    </div>
                    
                    <hr>
                    
                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-primary btn-block mb-2">
                        <i class="icon-pencil mr-2"></i> Edit Course
                    </a>
                    
                    <form action="{{ route('courses.destroy', $course) }}" method="POST" 
                          onsubmit="event.preventDefault(); confirmDelete(event, 'Are you sure you want to delete this course? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block mb-2">
                            <i class="icon-trash mr-2"></i> Delete Course
                        </button>
                    </form>
                    
                    @if(auth()->user()->hasRole('admin'))
                        <form action="{{ route('courses.toggle-published', $course) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $course->is_published ? 'warning' : 'success' }} btn-block">
                                <i class="icon-{{ $course->is_published ? 'eye-off' : 'eye' }} mr-2"></i> 
                                {{ $course->is_published ? 'Unpublish' : 'Publish' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
        
        <!-- Statistics Card -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="icon-chart text-info"></i> Statistics
                </h5>
                
                <div class="stat-item d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="icon-people text-primary mr-2"></i>
                        <span class="text-muted">Enrollments</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-primary">{{ $course->enrollments->count() }}</h4>
                </div>
                
                <div class="stat-item d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="icon-folder text-info mr-2"></i>
                        <span class="text-muted">Modules</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-info">{{ $course->modules->count() }}</h4>
                </div>
                
                <div class="stat-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="icon-note text-success mr-2"></i>
                        <span class="text-muted">Lessons</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-success">{{ $course->lessons_count }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Badge styling */
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    /* Icon circles */
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .icon-circle-lg {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Info cards */
    .info-card {
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    /* Module items */
    .module-item {
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
    }
    
    .module-item:hover {
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }
    
    .lesson-item {
        transition: all 0.2s ease;
    }
    
    .lesson-item:hover {
        background-color: #e3e6f0 !important;
    }
    
    /* Enrollment card */
    .enrollment-card {
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
    }
    
    /* Course info list */
    .course-info-list .info-item:last-child {
        border-bottom: 1px solid #e3e6f0 !important;
    }
    
    /* Progress bar */
    .progress {
        background-color: #e9ecef;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    /* Sticky sidebar */
    @media (max-width: 991px) {
        .sticky-top {
            position: relative !important;
        }
    }
    
    /* Action info spacing */
    .action-info {
        padding: 4px 0;
    }
    
    /* Stat items */
    .stat-item:last-child {
        border-bottom: none !important;
    }
</style>
@endpush
@endsection
