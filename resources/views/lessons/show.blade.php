@extends('layouts.skydash')

@section('content')
<!-- Header Section -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <div class="d-flex align-items-center">
                    <div>
                        <h3 class="font-weight-bold mb-2">{{ $lesson->title }}</h3>
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-primary mr-2">Lesson {{ $lesson->order }}</span>
                            @php
                                $typeColors = [
                                    'video' => 'info',
                                    'reading' => 'success',
                                    'audio' => 'warning',
                                    'interactive' => 'primary'
                                ];
                            @endphp
                            <span class="badge badge-{{ $typeColors[$lesson->type] ?? 'secondary' }} mr-2">{{ ucfirst($lesson->type) }}</span>
                            @if($lesson->is_free)
                                <span class="badge badge-success">FREE</span>
                            @else
                                <span class="badge badge-secondary">PAID</span>
                            @endif
                        </div>
                        <p class="text-muted mb-0">Module: {{ $lesson->module->title }} • Course: {{ $lesson->module->course->title }}</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('instructor'))
                        <a href="{{ route('lessons.edit', $lesson) }}" class="btn btn-primary mr-2">
                            <i class="mdi mdi-pencil"></i> Edit Lesson
                        </a>
                    @endif
                    <a href="{{ route('modules.show', $lesson->module) }}" class="btn btn-light">
                        <i class="mdi mdi-arrow-left"></i> Back to Module
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-md-8 grid-margin stretch-card">
        
        <!-- Lesson Information Card -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="mdi mdi-information text-primary"></i> Lesson Overview
                </h4>
                
                @if($lesson->description)
                    <div class="mb-4">
                        <p class="text-muted" style="line-height: 1.8; font-size: 1rem;">{{ $lesson->description }}</p>
                    </div>
                @else
                    <div class="mb-4">
                        <p class="text-muted font-italic">No description provided for this lesson.</p>
                    </div>
                @endif
                
                <hr class="my-4">
                
                <!-- Lesson Stats Grid -->
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="info-card p-3 border rounded">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-primary text-white mr-3">
                                    <i class="mdi mdi-sort-numeric-variant"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0" style="font-size: 0.875rem;">Order</p>
                                    <h6 class="mb-0 font-weight-bold">{{ $lesson->order }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="info-card p-3 border rounded">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-info text-white mr-3">
                                    <i class="mdi mdi-clock"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0" style="font-size: 0.875rem;">Duration</p>
                                    <h6 class="mb-0 font-weight-bold">{{ $lesson->duration_minutes ? $lesson->duration_minutes . 'm' : '-' }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="info-card p-3 border rounded">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-success text-white mr-3">
                                    <i class="mdi mdi-tag"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0" style="font-size: 0.875rem;">Type</p>
                                    <h6 class="mb-0 font-weight-bold">{{ ucfirst($lesson->type) }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="info-card p-3 border rounded">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-warning text-white mr-3">
                                    <i class="mdi mdi-calendar"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0" style="font-size: 0.875rem;">Created</p>
                                    <h6 class="mb-0 font-weight-bold">{{ $lesson->created_at->format('M d, Y') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lesson Content -->
        @if($lesson->content_url || $lesson->content_text)
        <div class="card mt-3">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="mdi mdi-play-circle text-primary"></i> Lesson Content
                </h4>
                
                @if($lesson->content_url)
                    <div class="mb-4">
                        <h6 class="mb-2">External Content</h6>
                        <a href="{{ $lesson->content_url }}" target="_blank" class="btn btn-primary">
                            <i class="mdi mdi-open-in-new mr-2"></i> Open Content
                        </a>
                        <small class="text-muted d-block mt-2">{{ $lesson->content_url }}</small>
                    </div>
                @endif
                
                @if($lesson->content_text)
                    <div class="mb-4">
                        <h6 class="mb-2">Text Content</h6>
                        <div class="content-text p-3 bg-light rounded">
                            {!! nl2br(e($lesson->content_text)) !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Quiz Section -->
        @if($lesson->quiz)
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-help-circle text-primary"></i> Associated Quiz
                    </h4>
                    <a href="{{ route('quizzes.show', $lesson->quiz) }}" class="btn btn-sm btn-primary">
                        <i class="mdi mdi-eye"></i> View Quiz
                    </a>
                </div>
                
                <div class="quiz-info">
                    <h6 class="mb-2">{{ $lesson->quiz->title }}</h6>
                    <p class="text-muted mb-3">{{ Str::limit($lesson->quiz->description, 100) }}</p>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <span class="badge badge-info">
                                <i class="mdi mdi-help mr-1"></i>{{ $lesson->quiz->questions->count() }} Questions
                            </span>
                        </div>
                        <div class="col-md-4">
                            <span class="badge badge-success">
                                <i class="mdi mdi-check mr-1"></i>{{ $lesson->quiz->passing_score }}% Pass
                            </span>
                        </div>
                        <div class="col-md-4">
                            <span class="badge badge-warning">
                                <i class="mdi mdi-clock mr-1"></i>{{ $lesson->quiz->time_limit_minutes ? $lesson->quiz->time_limit_minutes . 'm' : '∞' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
    </div>
    
    <!-- Sidebar -->
    <div class="col-md-4 grid-margin">
        
        <!-- Module Information -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="mdi mdi-folder text-info"></i> Module Information
                </h5>
                
                <div class="module-info">
                    <h6 class="mb-2">{{ $lesson->module->title }}</h6>
                    <p class="text-muted mb-2">Module {{ $lesson->module->order }}</p>
                    <p class="text-muted mb-3">{{ Str::limit($lesson->module->description, 100) }}</p>
                    
                    <a href="{{ route('modules.show', $lesson->module) }}" class="btn btn-outline-primary btn-block mb-3">
                        <i class="mdi mdi-eye mr-2"></i> View Module
                    </a>
                    
                    <div class="module-stats">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Lessons:</span>
                            <strong>{{ $lesson->module->lessons->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Course:</span>
                            <strong>{{ $lesson->module->course->title }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Level:</span>
                            <strong>{{ $lesson->module->course->level }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('instructor'))
            <!-- Admin Actions -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="mdi mdi-cog"></i> Quick Actions
                    </h5>
                    
                    <div class="action-info mb-3">
                        <p class="text-muted mb-1 small">Lesson ID</p>
                        <p class="mb-0"><strong>#{{ $lesson->id }}</strong></p>
                    </div>
                    
                    <div class="action-info mb-3">
                        <p class="text-muted mb-1 small">Created</p>
                        <p class="mb-0"><strong>{{ $lesson->created_at->format('M d, Y') }}</strong></p>
                    </div>
                    
                    <div class="action-info mb-4">
                        <p class="text-muted mb-1 small">Last Updated</p>
                        <p class="mb-0"><strong>{{ $lesson->updated_at->format('M d, Y') }}</strong></p>
                    </div>
                    
                    <hr>
                    
                    <a href="{{ route('lessons.edit', $lesson) }}" class="btn btn-primary btn-block mb-2">
                        <i class="mdi mdi-pencil mr-2"></i> Edit Lesson
                    </a>
                    
                    @if(!$lesson->quiz)
                        <a href="{{ route('quizzes.create', $lesson) }}" class="btn btn-success btn-block mb-2">
                            <i class="mdi mdi-plus mr-2"></i> Add Quiz
                        </a>
                    @endif
                    
                    <form action="{{ route('lessons.destroy', $lesson) }}" method="POST" 
                          onsubmit="event.preventDefault(); confirmDelete(event, 'Are you sure you want to delete this lesson? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block" {{ $lesson->quiz ? 'disabled' : '' }}>
                            <i class="mdi mdi-delete mr-2"></i> Delete Lesson
                        </button>
                    </form>
                    
                    @if($lesson->quiz)
                        <small class="text-muted mt-2 d-block">
                            <i class="mdi mdi-warning"></i> Cannot delete lesson with existing quiz
                        </small>
                    @endif
                </div>
            </div>
        @endif
        
        <!-- Lesson Statistics -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="mdi mdi-chart text-success"></i> Lesson Statistics
                </h5>
                
                <div class="stat-item d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="mdi mdi-clock text-primary mr-2"></i>
                        <span class="text-muted">Duration</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-primary">{{ $lesson->duration_minutes ? $lesson->duration_minutes . 'm' : '-' }}</h4>
                </div>
                
                <div class="stat-item d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="mdi mdi-tag text-info mr-2"></i>
                        <span class="text-muted">Type</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-info">{{ ucfirst($lesson->type) }}</h4>
                </div>
                
                <div class="stat-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-calendar text-success mr-2"></i>
                        <span class="text-muted">Age</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-success">{{ $lesson->created_at->diffForHumans() }}</h4>
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
    
    /* Module stats */
    .module-stats {
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
    
    /* Content text */
    .content-text {
        line-height: 1.6;
        font-size: 0.95rem;
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
    
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
