@extends('layouts.skydash')

@section('content')
<!-- Header Section -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <div class="d-flex align-items-center">
                    <div>
                        <h3 class="font-weight-bold mb-2">Lessons Management</h3>
                        <p class="text-muted mb-0">Manage course lessons and their content</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('courses.index') }}" class="btn btn-light">
                        <i class="mdi mdi-arrow-left"></i> Back to Courses
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('lessons.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search Lessons</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search by title, description, or course...">
                    </div>
                    <div class="col-md-2">
                        <label for="module_id" class="form-label">Filter by Module</label>
                        <select class="form-control" id="module_id" name="module_id">
                            <option value="">All Modules</option>
                            @foreach($modules as $module)
                                <option value="{{ $module->id }}" {{ request('module_id') == $module->id ? 'selected' : '' }}>
                                    {{ $module->course->title }} - {{ $module->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="type" class="form-label">Filter by Type</label>
                        <select class="form-control" id="type" name="type">
                            <option value="">All Types</option>
                            @foreach($lessonTypes as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="is_free" class="form-label">Free Status</label>
                        <select class="form-control" id="is_free" name="is_free">
                            <option value="">All</option>
                            <option value="1" {{ request('is_free') == '1' ? 'selected' : '' }}>Free Only</option>
                            <option value="0" {{ request('is_free') == '0' ? 'selected' : '' }}>Paid Only</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-3">
                            <i class="mdi mdi-magnify"></i> Search
                        </button>
                        <a href="{{ route('lessons.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-refresh"></i> Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Lessons List -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-book-open text-primary"></i> All Lessons ({{ $lessons->total() }})
                    </h4>
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('instructor'))
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#selectModuleModal">
                            <i class="mdi mdi-plus"></i> Create Lesson
                        </button>
                    @endif
                </div>

                @if($lessons->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Module</th>
                                    <th>Course</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lessons as $lesson)
                                <tr>
                                    <td>
                                        <span class="badge badge-primary">{{ $lesson->order }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-1 font-weight-bold">{{ $lesson->title }}</h6>
                                            @if($lesson->description)
                                                <small class="text-muted">{{ Str::limit($lesson->description, 60) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $typeColors = [
                                                'video' => 'info',
                                                'reading' => 'success',
                                                'audio' => 'warning',
                                                'interactive' => 'primary'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $typeColors[$lesson->type] ?? 'secondary' }}">
                                            {{ ucfirst($lesson->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="font-weight-medium">{{ $lesson->module->title }}</span>
                                            <br>
                                            <small class="text-muted">Module {{ $lesson->module->order }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <a href="{{ route('courses.show', $lesson->module->course) }}" class="text-primary">
                                                {{ $lesson->module->course->title }}
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $lesson->module->course->level }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($lesson->duration_minutes)
                                            <span class="text-muted">
                                                <i class="mdi mdi-clock mr-1"></i>{{ $lesson->duration_minutes }}m
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($lesson->is_free)
                                            <span class="badge badge-success">FREE</span>
                                        @else
                                            <span class="badge badge-secondary">PAID</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('lessons.show', $lesson) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('instructor'))
                                                <a href="{{ route('lessons.edit', $lesson) }}" class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <form action="{{ route('lessons.destroy', $lesson) }}" method="POST" class="d-inline"
                                                      onsubmit="event.preventDefault(); confirmDelete(event, 'Are you sure you want to delete this lesson?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $lessons->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="mdi mdi-book-open" style="font-size: 64px; color: #e3e6f0;"></i>
                        <h5 class="mt-3 mb-2 text-muted">No lessons found</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'module_id', 'type', 'is_free']))
                                Try adjusting your search criteria
                            @else
                                No lessons have been created yet
                            @endif
                        </p>
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('instructor'))
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#selectModuleModal">
                                <i class="mdi mdi-plus"></i> Create Lesson
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Module Selection Modal -->
<div class="modal fade" id="selectModuleModal" tabindex="-1" aria-labelledby="selectModuleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectModuleModalLabel">
                    <i class="mdi mdi-folder text-primary"></i> Select Module for New Lesson
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">Choose a module to add a new lesson to:</p>
                
                @if($modules->count() > 0)
                    <div class="row">
                        @foreach($modules as $module)
                        <div class="col-md-6 mb-3">
                            <div class="card module-card h-100" style="cursor: pointer;" onclick="selectModule({{ $module->id }}, '{{ $module->title }}')">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $module->title }}</h6>
                                    <p class="card-text text-muted small">{{ Str::limit($module->description, 80) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge badge-primary">Module {{ $module->order }}</span>
                                        <small class="text-muted">{{ $module->lessons->count() }} lessons</small>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <strong>Course:</strong> {{ $module->course->title }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="mdi mdi-folder" style="font-size: 48px; color: #e3e6f0;"></i>
                        <h6 class="mt-3 mb-2 text-muted">No modules available</h6>
                        <p class="text-muted">You need to create a module first before adding lessons.</p>
                        <a href="{{ route('courses.index') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Create Module
                        </a>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .btn-group .btn {
        margin-right: 2px;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .module-card {
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
    }
    
    .module-card:hover {
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
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

function selectModule(moduleId, moduleTitle) {
    // Redirect to create lesson page for selected module
    window.location.href = `/modules/${moduleId}/lessons/create`;
}
</script>
@endpush
@endsection
