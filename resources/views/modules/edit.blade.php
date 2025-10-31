@extends('layouts.skydash')

@section('content')
<!-- Header Section -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <div class="d-flex align-items-center">
                    <div>
                        <h3 class="font-weight-bold mb-2">Edit Module</h3>
                        <p class="text-muted mb-0">Update "{{ $module->title }}" in "{{ $module->course->title }}"</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('modules.show', $module) }}" class="btn btn-light mr-2">
                        <i class="icon-eye"></i> View Module
                    </a>
                    <a href="{{ route('courses.show', $module->course) }}" class="btn btn-outline-secondary">
                        <i class="icon-arrow-left"></i> Back to Course
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Module Info Card -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-gradient-primary text-white mr-3">
                        <i class="icon-folder"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">{{ $module->title }}</h5>
                        <p class="text-muted mb-0">
                            Module {{ $module->order }} • {{ $module->lessons->count() }} lessons • 
                            Course: {{ $module->course->title }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Module Form -->
<div class="row">
    <div class="col-md-8 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="icon-pencil text-primary"></i> Module Information
                </h4>

                <form action="{{ route('modules.update', $module) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group mb-4">
                        <label for="title" class="form-label">
                            Module Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $module->title) }}" 
                               placeholder="Enter module title"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  placeholder="Enter module description (optional)">{{ old('description', $module->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="order" class="form-label">
                            Order <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control @error('order') is-invalid @enderror" 
                               id="order" 
                               name="order" 
                               value="{{ old('order', $module->order) }}" 
                               min="1"
                               required>
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            The order determines the sequence of modules in the course
                        </small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="icon-check"></i> Update Module
                        </button>
                        <a href="{{ route('modules.show', $module) }}" class="btn btn-secondary">
                            <i class="icon-close"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4 grid-margin">
        <!-- Module Stats -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="icon-chart text-info"></i> Module Statistics
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
                        <i class="icon-calendar text-info mr-2"></i>
                        <span class="text-muted">Created</span>
                    </div>
                    <h6 class="mb-0 font-weight-bold text-info">{{ $module->created_at->format('M d, Y') }}</h6>
                </div>
                
                <div class="stat-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="icon-clock text-success mr-2"></i>
                        <span class="text-muted">Last Updated</span>
                    </div>
                    <h6 class="mb-0 font-weight-bold text-success">{{ $module->updated_at->format('M d, Y') }}</h6>
                </div>
            </div>
        </div>

        <!-- Course Info -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="icon-book text-warning"></i> Course Information
                </h5>
                
                <div class="course-info">
                    <h6 class="mb-2">{{ $module->course->title }}</h6>
                    <p class="text-muted mb-2">{{ $module->course->level }} level</p>
                    <p class="text-muted mb-3">{{ Str::limit($module->course->description, 100) }}</p>
                    
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

        <!-- Danger Zone -->
        <div class="card mt-3 border-danger">
            <div class="card-body">
                <h5 class="card-title mb-4 text-danger">
                    <i class="icon-trash"></i> Danger Zone
                </h5>
                
                <p class="text-muted mb-3 small">
                    Deleting this module will permanently remove it and all its lessons. This action cannot be undone.
                </p>
                
                @if($module->lessons->count() > 0)
                    <div class="alert alert-warning mb-3">
                        <i class="icon-warning"></i>
                        <strong>Warning:</strong> This module contains {{ $module->lessons->count() }} lessons. 
                        You must delete all lessons before deleting this module.
                    </div>
                @endif
                
                <form action="{{ route('modules.destroy', $module) }}" method="POST" 
                      onsubmit="event.preventDefault(); confirmDelete(event, 'Are you sure you want to delete this module? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="btn btn-danger btn-block" 
                            {{ $module->lessons->count() > 0 ? 'disabled' : '' }}>
                        <i class="icon-trash mr-2"></i> Delete Module
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .stat-item:last-child {
        border-bottom: none !important;
    }
    
    .course-stats {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }
    
    .card.border-danger {
        border-color: #dc3545 !important;
    }
    
    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeaa7;
        color: #856404;
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
