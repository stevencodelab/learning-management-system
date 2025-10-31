@extends('layouts.skydash')

@section('content')
<!-- Header Section -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <div class="d-flex align-items-center">
                    <div>
                        <h3 class="font-weight-bold mb-2">Create New Lesson</h3>
                        <p class="text-muted mb-0">Add a new lesson to "{{ $module->title }}"</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('modules.show', $module) }}" class="btn btn-light">
                        <i class="mdi mdi-arrow-left"></i> Back to Module
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
                        <i class="mdi mdi-folder"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">{{ $module->title }}</h5>
                        <p class="text-muted mb-0">{{ $module->course->title }} • {{ $module->lessons->count() }} existing lessons</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Lesson Form -->
<div class="row">
    <div class="col-md-8 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="mdi mdi-plus text-primary"></i> Lesson Information
                </h4>

                <form action="{{ route('lessons.store', $module) }}" method="POST">
                    @csrf
                    
                    <div class="form-group mb-4">
                        <label for="title" class="form-label">
                            Lesson Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}" 
                               placeholder="Enter lesson title"
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
                                  rows="3" 
                                  placeholder="Enter lesson description (optional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="type" class="form-label">
                            Lesson Type <span class="text-danger">*</span>
                        </label>
                        <select class="form-control @error('type') is-invalid @enderror" 
                                id="type" 
                                name="type" 
                                required>
                            <option value="">Select lesson type</option>
                            @foreach($lessonTypes as $type)
                                <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="content_url" class="form-label">Content URL</label>
                        <input type="url" 
                               class="form-control @error('content_url') is-invalid @enderror" 
                               id="content_url" 
                               name="content_url" 
                               value="{{ old('content_url') }}" 
                               placeholder="https://example.com/video">
                        @error('content_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            URL to video, audio, or external content
                        </small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="content_text" class="form-label">Content Text</label>
                        <textarea class="form-control @error('content_text') is-invalid @enderror" 
                                  id="content_text" 
                                  name="content_text" 
                                  rows="6" 
                                  placeholder="Enter lesson content text (optional)">{{ old('content_text') }}</textarea>
                        @error('content_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Text content for reading lessons or additional information
                        </small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="duration_minutes" class="form-label">Duration (minutes)</label>
                                <input type="number" 
                                       class="form-control @error('duration_minutes') is-invalid @enderror" 
                                       id="duration_minutes" 
                                       name="duration_minutes" 
                                       value="{{ old('duration_minutes') }}" 
                                       min="1"
                                       max="999"
                                       placeholder="30">
                                @error('duration_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="order" class="form-label">
                                    Order <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('order') is-invalid @enderror" 
                                       id="order" 
                                       name="order" 
                                       value="{{ old('order', $nextOrder) }}" 
                                       min="1"
                                       max="999"
                                       required>
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <div class="form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="is_free" 
                                   name="is_free" 
                                   value="1" 
                                   {{ old('is_free') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_free">
                                This lesson is free
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            Free lessons can be accessed without enrollment
                        </small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary mr-3">
                            <i class="mdi mdi-check"></i> Create Lesson
                        </button>
                        <a href="{{ route('modules.show', $module) }}" class="btn btn-secondary">
                            <i class="mdi mdi-close"></i> Cancel
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
                    <i class="mdi mdi-chart text-info"></i> Module Statistics
                </h5>
                
                <div class="stat-item d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="mdi mdi-book-open text-primary mr-2"></i>
                        <span class="text-muted">Lessons</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-primary">{{ $module->lessons->count() }}</h4>
                </div>
                
                <div class="stat-item d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="mdi mdi-folder text-info mr-2"></i>
                        <span class="text-muted">Order</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-info">{{ $module->order }}</h4>
                </div>
                
                <div class="stat-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-calendar text-success mr-2"></i>
                        <span class="text-muted">Created</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-success">{{ $module->created_at->format('M d, Y') }}</h4>
                </div>
            </div>
        </div>

        <!-- Course Info -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="mdi mdi-book text-warning"></i> Course Information
                </h5>
                
                <div class="course-info">
                    <h6 class="mb-2">{{ $module->course->title }}</h6>
                    <p class="text-muted mb-2">{{ $module->course->level }} level</p>
                    <p class="text-muted mb-3">{{ Str::limit($module->course->description, 100) }}</p>
                    
                    <a href="{{ route('courses.show', $module->course) }}" class="btn btn-outline-primary btn-block mb-3">
                        <i class="mdi mdi-eye mr-2"></i> View Course
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

        <!-- Existing Lessons -->
        @if($module->lessons->count() > 0)
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="mdi mdi-list text-warning"></i> Existing Lessons
                </h5>
                
                <div class="lessons-list">
                    @foreach($module->lessons->take(5) as $lesson)
                    <div class="lesson-item mb-3 p-3 border rounded">
                        <div class="d-flex align-items-center">
                            <span class="badge badge-primary mr-2">{{ $lesson->order }}</span>
                            <div style="flex: 1;">
                                <h6 class="mb-1 font-weight-bold">{{ $lesson->title }}</h6>
                                <small class="text-muted">{{ ucfirst($lesson->type) }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    @if($module->lessons->count() > 5)
                        <p class="text-muted mb-0 small text-center">
                            + {{ $module->lessons->count() - 5 }} more lessons
                        </p>
                    @endif
                </div>
            </div>
        </div>
        @endif
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
    
    .lesson-item {
        transition: all 0.3s ease;
    }
    
    .lesson-item:hover {
        background-color: #f8f9fa;
        border-color: #667eea !important;
    }
    
    .stat-item:last-child {
        border-bottom: none !important;
    }
    
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .course-stats {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }
</style>
@endpush
@endsection
