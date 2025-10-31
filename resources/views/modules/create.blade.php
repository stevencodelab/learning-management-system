@extends('layouts.skydash')

@section('content')
<!-- Header Section -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <div class="d-flex align-items-center">
                    <div>
                        <h3 class="font-weight-bold mb-2">Create New Module</h3>
                        <p class="text-muted mb-0">Add a new module to "{{ $course->title }}"</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('courses.show', $course) }}" class="btn btn-light">
                        <i class="icon-arrow-left"></i> Back to Course
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Course Info Card -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-gradient-primary text-white mr-3">
                        <i class="icon-book"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">{{ $course->title }}</h5>
                        <p class="text-muted mb-0">{{ $course->level }} • {{ $course->modules->count() }} existing modules</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Module Form -->
<div class="row">
    <div class="col-md-8 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="icon-plus text-primary"></i> Module Information
                </h4>

                <form action="{{ route('modules.store', $course) }}" method="POST">
                    @csrf
                    
                    <div class="form-group mb-4">
                        <label for="title" class="form-label">
                            Module Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}" 
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
                                  placeholder="Enter module description (optional)">{{ old('description') }}</textarea>
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
                               value="{{ old('order', $nextOrder) }}" 
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
                            <i class="icon-check"></i> Create Module
                        </button>
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-secondary">
                            <i class="icon-close"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4 grid-margin">
        <!-- Course Stats -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="icon-chart text-info"></i> Course Statistics
                </h5>
                
                <div class="stat-item d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="icon-folder text-primary mr-2"></i>
                        <span class="text-muted">Modules</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-primary">{{ $course->modules->count() }}</h4>
                </div>
                
                <div class="stat-item d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="icon-note text-info mr-2"></i>
                        <span class="text-muted">Total Lessons</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-info">{{ $course->lessons_count }}</h4>
                </div>
                
                <div class="stat-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="icon-clock text-success mr-2"></i>
                        <span class="text-muted">Duration</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-success">{{ $course->duration_hours }}h</h4>
                </div>
            </div>
        </div>

        <!-- Existing Modules -->
        @if($course->modules->count() > 0)
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="icon-list text-warning"></i> Existing Modules
                </h5>
                
                <div class="modules-list">
                    @foreach($course->modules->take(5) as $module)
                    <div class="module-item mb-3 p-3 border rounded">
                        <div class="d-flex align-items-center">
                            <span class="badge badge-primary mr-2">{{ $module->order }}</span>
                            <div style="flex: 1;">
                                <h6 class="mb-1 font-weight-bold">{{ $module->title }}</h6>
                                <small class="text-muted">{{ $module->lessons->count() }} lessons</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    @if($course->modules->count() > 5)
                        <p class="text-muted mb-0 small text-center">
                            + {{ $course->modules->count() - 5 }} more modules
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
    
    .module-item {
        transition: all 0.3s ease;
    }
    
    .module-item:hover {
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
</style>
@endpush
@endsection
