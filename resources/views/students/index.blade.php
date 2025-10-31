@extends('layouts.skydash')

@section('content')
<!-- Header Section -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <div class="d-flex align-items-center">
                    <div>
                        <h3 class="font-weight-bold mb-2">Students Management</h3>
                        <p class="text-muted mb-0">Manage student accounts and their learning progress</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('instructor'))
                        <a href="{{ route('students.create') }}" class="btn btn-primary mr-2">
                            <i class="mdi mdi-plus"></i> Add Student
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="btn btn-light">
                        <i class="mdi mdi-arrow-left"></i> Back to Dashboard
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
                <form method="GET" action="{{ route('students.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search Students</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search by name, email, or phone...">
                    </div>
                    <div class="col-md-3">
                        <label for="enrollment_status" class="form-label">Enrollment Status</label>
                        <select class="form-control" id="enrollment_status" name="enrollment_status">
                            <option value="">All Students</option>
                            <option value="enrolled" {{ request('enrollment_status') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                            <option value="not_enrolled" {{ request('enrollment_status') == 'not_enrolled' ? 'selected' : '' }}>Not Enrolled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="email_verified" class="form-label">Email Status</label>
                        <select class="form-control" id="email_verified" name="email_verified">
                            <option value="">All</option>
                            <option value="verified" {{ request('email_verified') == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="unverified" {{ request('email_verified') == 'unverified' ? 'selected' : '' }}>Unverified</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-3">
                            <i class="mdi mdi-magnify"></i> Search
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-refresh"></i> Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Students List -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-account-group text-primary"></i> All Students ({{ $students->total() }})
                    </h4>
                </div>

                @if($students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Level</th>
                                    <th>Enrollments</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td>
                                        <div class="student-avatar">
                                            <img src="{{ $student->avatar_url }}" 
                                                 alt="Avatar" 
                                                 class="table-avatar">
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-1 font-weight-bold">{{ $student->name }}</h6>
                                            <small class="text-muted">ID: #{{ $student->id }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="text-primary">{{ $student->email }}</span>
                                            @if($student->email_verified_at)
                                                <br><small class="text-success"><i class="mdi mdi-check-circle"></i> Verified</small>
                                            @else
                                                <br><small class="text-warning"><i class="mdi mdi-alert-circle"></i> Unverified</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($student->phone)
                                            <span class="text-muted">{{ $student->phone }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($student->level)
                                            @php
                                                $levelColors = [
                                                    'beginner' => 'success',
                                                    'intermediate' => 'warning',
                                                    'advanced' => 'danger'
                                                ];
                                            @endphp
                                            <span class="badge badge-{{ $levelColors[$student->level] ?? 'secondary' }}">
                                                {{ ucfirst($student->level) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="badge badge-info">{{ $student->enrollments->count() }}</span>
                                            @if($student->enrollments->count() > 0)
                                                <br><small class="text-muted">{{ $student->enrollments->where('completed_at', '!=', null)->count() }} completed</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($student->enrollments->count() > 0)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $student->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('instructor'))
                                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline"
                                                      onsubmit="event.preventDefault(); confirmDelete(event, 'Are you sure you want to delete this student?');">
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
                        {{ $students->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="mdi mdi-account-group" style="font-size: 64px; color: #e3e6f0;"></i>
                        <h5 class="mt-3 mb-2 text-muted">No students found</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'enrollment_status', 'email_verified']))
                                Try adjusting your search criteria
                            @else
                                No students have been registered yet
                            @endif
                        </p>
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('instructor'))
                            <a href="{{ route('students.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> Add First Student
                            </a>
                        @endif
                    </div>
                @endif
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
    
    .table-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e3e6f0;
    }
    
    .student-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
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
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
