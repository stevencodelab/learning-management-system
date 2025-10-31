@extends('layouts.skydash')

@section('content')
<!-- Header Section -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <div class="d-flex align-items-center">
                    <div>
                        <h3 class="font-weight-bold mb-2">{{ $module->title }}</h3>
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-primary mr-2">Module {{ $module->order }}</span>
                            <span class="badge badge-info mr-2">{{ $module->lessons->count() }} {{ Str::plural('Lesson', $module->lessons->count()) }}</span>
                            <span class="badge badge-secondary">{{ $module->course->level }}</span>
                        </div>
                        <p class="text-muted mb-0">Course: {{ $module->course->title }}</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    @if(auth()->user()->hasAnyRole(['admin', 'instructor']))
                        <a href="{{ route('modules.edit', $module) }}" class="btn btn-primary mr-2">
                            <i class="icon-pencil"></i> Edit Module
                        </a>
                    @endif
                    <a href="{{ route('courses.show', $module->course) }}" class="btn btn-light">
                        <i class="icon-arrow-left"></i> Back to Course
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-md-8 grid-margin stretch-card">
        
        <!-- Module Information Card -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="icon-info text-primary"></i> Module Overview
                </h4>
                
                @if($module->description)
                    <div class="mb-4">
                        <p class="text-muted" style="line-height: 1.8; font-size: 1rem;">{{ $module->description }}</p>
                    </div>
                @else
                    <div class="mb-4">
                        <p class="text-muted font-italic">No description provided for this module.</p>
                    </div>
                @endif
                
                <hr class="my-4">
                
                <!-- Module Stats Grid -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="info-card p-3 border rounded">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-primary text-white mr-3">
                                    <i class="icon-folder"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0" style="font-size: 0.875rem;">Order</p>
                                    <h6 class="mb-0 font-weight-bold">{{ $module->order }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="info-card p-3 border rounded">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-info text-white mr-3">
                                    <i class="icon-note"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0" style="font-size: 0.875rem;">Lessons</p>
                                    <h6 class="mb-0 font-weight-bold">{{ $module->lessons->count() }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="info-card p-3 border rounded">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-success text-white mr-3">
                                    <i class="icon-calendar"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0" style="font-size: 0.875rem;">Created</p>
                                    <h6 class="mb-0 font-weight-bold">{{ $module->created_at->format('M d, Y') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lessons Section -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="icon-note text-primary"></i> Module Lessons ({{ $module->lessons->count() }})
                    </h4>
                    @if(auth()->user()->hasAnyRole(['admin', 'instructor']))
                        <a href="{{ route('lessons.create', $module) }}" class="btn btn-sm btn-primary">
                            <i class="icon-plus"></i> Add Lesson
                        </a>
                    @endif
                </div>
                
                @if($module->lessons->count() > 0)
                    <div class="lessons-list">
                        @foreach($module->lessons as $lesson)
                        <div class="lesson-item card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div style="flex: 1;">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge badge-primary mr-2" style="min-width: 80px;">
                                                Lesson {{ $lesson->order }}
                                            </span>
                                            <span class="badge badge-{{ $lesson->type == 'video' ? 'info' : ($lesson->type == 'reading' ? 'success' : ($lesson->type == 'audio' ? 'warning' : 'secondary')) }}">
                                                {{ ucfirst($lesson->type) }}
                                            </span>
                                            @if($lesson->is_free)
                                                <span class="badge badge-success ml-2">FREE</span>
                                            @endif
                                        </div>
                                        <h5 class="mb-2 font-weight-bold">{{ $lesson->title }}</h5>
                                        @if($lesson->description)
                                            <p class="text-muted mb-3">{{ Str::limit($lesson->description, 150) }}</p>
                                        @endif
                                        
                                        @if($lesson->duration_minutes)
                                            <div class="lesson-meta">
                                                <small class="text-muted">
                                                    <i class="icon-clock mr-1"></i>{{ $lesson->duration_minutes }} minutes
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                    @if(auth()->user()->hasAnyRole(['admin', 'instructor']))
                                        <div class="ml-3">
                                            <a href="{{ route('lessons.edit', $lesson) }}" class="btn btn-sm btn-primary">
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
                        <i class="icon-note" style="font-size: 64px; color: #e3e6f0;"></i>
                        <h5 class="mt-3 mb-2 text-muted">No lessons yet</h5>
                        <p class="text-muted">Add lessons to organize module content</p>
                        @if(auth()->user()->hasAnyRole(['admin', 'instructor']))
                            <a href="{{ route('lessons.create', $module) }}" class="btn btn-primary">
                                <i class="icon-plus"></i> Add First Lesson
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        
    </div>
    
    <!-- Sidebar -->
    <div class="col-md-4 grid-margin">
        
        <!-- Course Information -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="icon-book text-info"></i> Course Information
                </h5>
                
                <div class="course-info">
                    <h6 class="mb-2">{{ $module->course->title }}</h6>
                    <p class="text-muted mb-2">{{ $module->course->level }} level</p>
                    <p class="text-muted mb-3">{{ Str::limit($module->course->description, 100) }}</p>
                    
                    <a href="{{ route('courses.show', $module->course) }}" class="btn btn-outline-primary btn-block mb-3">
                        <i class="icon-eye mr-2"></i> View Course
                    </a>
                    
                    <div class="course-stats">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Modules:</span>
                            <strong>{{ $module->course->modules->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Total Lessons:</span>
                            <strong>{{ $module->course->lessons_count }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Duration:</span>
                            <strong>{{ $module->course->duration_hours }}h</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @if(auth()->user()->hasAnyRole(['admin', 'instructor']))
            <!-- Admin Actions -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="icon-settings"></i> Quick Actions
                    </h5>
                    
                    <div class="action-info mb-3">
                        <p class="text-muted mb-1 small">Module ID</p>
                        <p class="mb-0"><strong>#{{ $module->id }}</strong></p>
                    </div>
                    
                    <div class="action-info mb-3">
                        <p class="text-muted mb-1 small">Created</p>
                        <p class="mb-0"><strong>{{ $module->created_at->format('M d, Y') }}</strong></p>
                    </div>
                    
                    <div class="action-info mb-4">
                        <p class="text-muted mb-1 small">Last Updated</p>
                        <p class="mb-0"><strong>{{ $module->updated_at->format('M d, Y') }}</strong></p>
                    </div>
                    
                    <hr>
                    
                    <a href="{{ route('modules.edit', $module) }}" class="btn btn-primary btn-block mb-2">
                        <i class="icon-pencil mr-2"></i> Edit Module
                    </a>
                    
                    <a href="{{ route('lessons.create', $module) }}" class="btn btn-success btn-block mb-2">
                        <i class="icon-plus mr-2"></i> Add Lesson
                    </a>
                    
                    <form action="{{ route('modules.destroy', $module) }}" method="POST" 
                          onsubmit="event.preventDefault(); confirmDelete(event, 'Are you sure you want to delete this module? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block" {{ $module->lessons->count() > 0 ? 'disabled' : '' }}>
                            <i class="icon-trash mr-2"></i> Delete Module
                        </button>
                    </form>
                    
                    @if($module->lessons->count() > 0)
                        <small class="text-muted mt-2 d-block">
                            <i class="icon-warning"></i> Cannot delete module with existing lessons
                        </small>
                    @endif
                </div>
            </div>
        @endif
        
        <!-- Module Statistics -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="icon-chart text-success"></i> Module Statistics
                </h5>
                
                <div class="stat-item d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="icon-note text-primary mr-2"></i>
                        <span class="text-muted">Lessons</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-primary">{{ $module->lessons->count() }}</h4>
                </div>
                
                <div class="stat-item d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="icon-folder text-info mr-2"></i>
                        <span class="text-muted">Order</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-info">{{ $module->order }}</h4>
                </div>
                
                <div class="stat-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="icon-calendar text-success mr-2"></i>
                        <span class="text-muted">Age</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-success">{{ $module->created_at->diffForHumans() }}</h4>
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
    
    /* Info cards */
    .info-card {
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    /* Lesson items */
    .lesson-item {
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
    }
    
    .lesson-item:hover {
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }
    
    /* Course stats */
    .course-stats {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }
    
    /* Action info spacing */
    .action-info {
        padding: 4px 0;
    }
    
    /* Stat items */
    .stat-item:last-child {
        border-bottom: none !important;
    }
    
    /* Gradients */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
</style>
@endpush

@push('scripts')
<script>
function confirmDelete(event, message) {
    if (confirm(message)) {
        event.target.closest('form').submit();
    }
}
</script>
@endpush
@endsection
