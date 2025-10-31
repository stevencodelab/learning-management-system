@extends('layouts.skydash')

@section('content')
<!-- Header Section -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <div class="d-flex align-items-center">
                    <div>
                        <h3 class="font-weight-bold mb-2">Modules Management</h3>
                        <p class="text-muted mb-0">Manage course modules and their content</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('courses.index') }}" class="btn btn-light">
                        <i class="icon-arrow-left"></i> Back to Courses
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
                <form method="GET" action="{{ route('modules.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search Modules</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search by title, description, or course...">
                    </div>
                    <div class="col-md-3">
                        <label for="course_id" class="form-label">Filter by Course</label>
                        <select class="form-control" id="course_id" name="course_id">
                            <option value="">All Courses</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="icon-magnifier"></i> Search
                        </button>
                        <a href="{{ route('modules.index') }}" class="btn btn-outline-secondary">
                            <i class="icon-refresh"></i> Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modules List -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="icon-folder text-primary"></i> All Modules ({{ $modules->total() }})
                    </h4>
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('instructor'))
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#selectCourseModal">
                            <i class="icon-plus"></i> Create Module
                        </button>
                    @endif
                </div>

                @if($modules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Title</th>
                                    <th>Course</th>
                                    <th>Lessons</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modules as $module)
                                <tr>
                                    <td>
                                        <span class="badge badge-primary">{{ $module->order }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-1 font-weight-bold">{{ $module->title }}</h6>
                                            <small class="text-muted">ID: {{ $module->id }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <a href="{{ route('courses.show', $module->course) }}" class="text-primary">
                                                {{ $module->course->title }}
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $module->course->level }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $module->lessons->count() }} {{ Str::plural('Lesson', $module->lessons->count()) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($module->description)
                                            <p class="text-muted mb-0">{{ Str::limit($module->description, 80) }}</p>
                                        @else
                                            <span class="text-muted">No description</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('modules.show', $module) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('instructor'))
                                                <a href="{{ route('modules.edit', $module) }}" class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <form action="{{ route('modules.destroy', $module) }}" method="POST" class="d-inline"
                                                      onsubmit="event.preventDefault(); confirmDelete(event, 'Are you sure you want to delete this module?');">
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
                        {{ $modules->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="icon-folder" style="font-size: 64px; color: #e3e6f0;"></i>
                        <h5 class="mt-3 mb-2 text-muted">No modules found</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'course_id']))
                                Try adjusting your search criteria
                            @else
                                No modules have been created yet
                            @endif
                        </p>
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('instructor'))
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#selectCourseModal">
                                <i class="icon-plus"></i> Create Module
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Course Selection Modal -->
<div class="modal fade" id="selectCourseModal" tabindex="-1" aria-labelledby="selectCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectCourseModalLabel">
                    <i class="icon-book text-primary"></i> Select Course for New Module
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">Choose a course to add a new module to:</p>
                
                @if($courses->count() > 0)
                    <div class="row">
                        @foreach($courses as $course)
                        <div class="col-md-6 mb-3">
                            <div class="card course-card h-100" style="cursor: pointer;" onclick="selectCourse({{ $course->id }}, '{{ $course->title }}')">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $course->title }}</h6>
                                    <p class="card-text text-muted small">{{ Str::limit($course->description, 80) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge badge-{{ $course->level == 'beginner' ? 'success' : ($course->level == 'intermediate' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($course->level) }}
                                        </span>
                                        <small class="text-muted">{{ $course->modules->count() }} modules</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="icon-book" style="font-size: 48px; color: #e3e6f0;"></i>
                        <h6 class="mt-3 mb-2 text-muted">No courses available</h6>
                        <p class="text-muted">You need to create a course first before adding modules.</p>
                        <a href="{{ route('courses.create') }}" class="btn btn-primary">
                            <i class="icon-plus"></i> Create Course
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
    
    .course-card {
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
    }
    
    .course-card:hover {
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

function selectCourse(courseId, courseTitle) {
    // Redirect to create module page for selected course
    window.location.href = `/courses/${courseId}/modules/create`;
}
</script>
@endpush
@endsection
