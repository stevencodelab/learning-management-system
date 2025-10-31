@extends('layouts.skydash')

@section('content')
<!-- Header Section -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <div class="d-flex align-items-center">
                    <div>
                        <h3 class="font-weight-bold mb-2">Edit Student</h3>
                        <p class="text-muted mb-0">Update "{{ $student->name }}" information</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('students.show', $student) }}" class="btn btn-light mr-2">
                        <i class="mdi mdi-eye"></i> View Student
                    </a>
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                        <i class="mdi mdi-arrow-left"></i> Back to Students
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Student Info Card -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="student-avatar-large mr-3">
                        <img src="{{ $student->avatar_url }}" 
                             alt="Avatar" 
                             class="student-avatar-image">
                    </div>
                    <div>
                        <h5 class="mb-1">{{ $student->name }}</h5>
                        <p class="text-muted mb-0">
                            Student ID: #{{ $student->id }} • 
                            {{ $student->enrollments->count() }} enrollments • 
                            Joined {{ $student->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Student Form -->
<div class="row">
    <div class="col-md-8 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="mdi mdi-account-edit text-primary"></i> Student Information
                </h4>

                <form action="{{ route('students.update', $student) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group mb-4">
                        <label for="name" class="form-label">
                            Full Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $student->name) }}" 
                               placeholder="Enter student's full name"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="email" class="form-label">
                            Email Address <span class="text-danger">*</span>
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $student->email) }}" 
                               placeholder="Enter student's email address"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            This will be used for login and notifications
                        </small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $student->phone) }}" 
                               placeholder="Enter student's phone number">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="level" class="form-label">Student Level</label>
                        <select class="form-control @error('level') is-invalid @enderror" 
                                id="level" 
                                name="level">
                            <option value="">Select student level (optional)</option>
                            <option value="beginner" {{ old('level', $student->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ old('level', $student->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced" {{ old('level', $student->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                        @error('level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            This helps categorize students by their skill level
                        </small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Enter new password (leave blank to keep current)">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Leave blank to keep the current password
                        </small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" 
                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Confirm the new password">
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary mr-3">
                            <i class="mdi mdi-check"></i> Update Student
                        </button>
                        <a href="{{ route('students.show', $student) }}" class="btn btn-secondary">
                            <i class="mdi mdi-close"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4 grid-margin">
        <!-- Student Stats -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="mdi mdi-chart text-info"></i> Student Statistics
                </h5>
                
                <div class="stat-item d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="mdi mdi-book text-primary mr-2"></i>
                        <span class="text-muted">Enrollments</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-primary">{{ $student->enrollments->count() }}</h4>
                </div>
                
                <div class="stat-item d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="mdi mdi-check-circle text-success mr-2"></i>
                        <span class="text-muted">Completed</span>
                    </div>
                    <h4 class="mb-0 font-weight-bold text-success">{{ $student->enrollments->where('completed_at', '!=', null)->count() }}</h4>
                </div>
                
                <div class="stat-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-calendar text-info mr-2"></i>
                        <span class="text-muted">Member Since</span>
                    </div>
                    <h6 class="mb-0 font-weight-bold text-info">{{ $student->created_at->format('M Y') }}</h6>
                </div>
            </div>
        </div>

        <!-- Account Status -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="mdi mdi-account-check text-warning"></i> Account Status
                </h5>
                
                <div class="status-list">
                    <div class="status-item d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Email Status:</span>
                        @if($student->email_verified_at)
                            <span class="badge badge-success">Verified</span>
                        @else
                            <span class="badge badge-warning">Unverified</span>
                        @endif
                    </div>
                    
                    <div class="status-item d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Account Status:</span>
                        @if($student->enrollments->count() > 0)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </div>
                    
                    <div class="status-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Last Login:</span>
                        <small class="text-muted">{{ $student->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="card mt-3 border-danger">
            <div class="card-body">
                <h5 class="card-title mb-4 text-danger">
                    <i class="mdi mdi-delete"></i> Danger Zone
                </h5>
                
                <p class="text-muted mb-3 small">
                    Deleting this student will permanently remove their account and all associated data. This action cannot be undone.
                </p>
                
                @if($student->enrollments->count() > 0)
                    <div class="alert alert-warning mb-3">
                        <i class="mdi mdi-warning"></i>
                        <strong>Warning:</strong> This student has {{ $student->enrollments->count() }} enrollment(s). 
                        You must remove all enrollments before deleting this student.
                    </div>
                @endif
                
                <form action="{{ route('students.destroy', $student) }}" method="POST" 
                      onsubmit="event.preventDefault(); confirmDelete(event, 'Are you sure you want to delete this student? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="btn btn-danger btn-block" 
                            {{ $student->enrollments->count() > 0 ? 'disabled' : '' }}>
                        <i class="mdi mdi-delete mr-2"></i> Delete Student
                    </button>
                </form>
                
                @if($student->enrollments->count() > 0)
                    <small class="text-muted mt-2 d-block">
                        <i class="mdi mdi-warning"></i> Cannot delete student with existing enrollments
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .student-avatar-image {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e3e6f0;
    }
    
    .student-avatar-large {
        position: relative;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .stat-item:last-child {
        border-bottom: none !important;
    }
    
    .card.border-danger {
        border-color: #dc3545 !important;
    }
    
    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeaa7;
        color: #856404;
    }
    
    .badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .status-item {
        padding: 4px 0;
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
