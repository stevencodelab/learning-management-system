@extends('layouts.skydash')

@section('content')
<!-- Header Section -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <div class="d-flex align-items-center">
                    <div>
                        <h3 class="font-weight-bold mb-2">Profile Settings</h3>
                        <p class="text-muted mb-0">Manage your account settings and profile information</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('dashboard') }}" class="btn btn-light">
                        <i class="mdi mdi-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Profile Information Card -->
<div class="row">
    <div class="col-md-8 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="mdi mdi-account text-primary"></i> Profile Information
                </h4>
                <p class="text-muted mb-4">Update your account's profile information and email address.</p>

                <!-- Avatar Section -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="avatar-section text-center">
                            <div class="avatar-container mb-3">
                                <img src="{{ $user->avatar_url }}" 
                                     alt="Avatar" 
                                     class="avatar-image"
                                     id="avatar-preview">
                            </div>
                            <div class="avatar-actions">
                                <button type="button" 
                                        class="btn btn-sm btn-primary mb-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#avatarModal">
                                    <i class="mdi mdi-camera mr-1"></i> Change Avatar
                                </button>
                                @if($user->avatar)
                                    <form action="{{ route('profile.avatar.delete') }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to remove your avatar?')">
                                            <i class="mdi mdi-delete mr-1"></i> Remove
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="avatar-info">
                            <h6 class="mb-2">Profile Picture</h6>
                            <p class="text-muted small mb-2">
                                Upload a profile picture to personalize your account. 
                                Supported formats: JPG, PNG, GIF (max 2MB)
                            </p>
                            @if($user->avatar)
                                <div class="alert alert-success small">
                                    <i class="mdi mdi-check-circle mr-1"></i>
                                    You have a custom avatar uploaded.
                                </div>
                            @else
                                <div class="alert alert-info small">
                                    <i class="mdi mdi-information mr-1"></i>
                                    Using generated avatar with your initials.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="form-group mb-4">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required 
                               autofocus 
                               autocomplete="name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required 
                               autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="mt-2">
                                <p class="text-sm text-warning">
                                    <i class="mdi mdi-alert-circle mr-1"></i>
                                    Your email address is unverified.
                                </p>
                                <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning">
                                        <i class="mdi mdi-email mr-1"></i> Re-send verification email
                                    </button>
                                </form>

                                @if (session('status') === 'verification-link-sent')
                                    <div class="alert alert-success mt-2">
                                        <i class="mdi mdi-check-circle mr-1"></i>
                                        A new verification link has been sent to your email address.
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary mr-3">
                            <i class="mdi mdi-check"></i> Save Changes
                        </button>

                        @if (session('status') === 'profile-updated')
                            <span class="text-success ml-3">
                                <i class="mdi mdi-check-circle mr-1"></i> Saved successfully!
                            </span>
                        @elseif (session('status') === 'avatar-updated')
                            <span class="text-success ml-3">
                                <i class="mdi mdi-check-circle mr-1"></i> Avatar updated successfully!
                            </span>
                        @elseif (session('status') === 'avatar-deleted')
                            <span class="text-info ml-3">
                                <i class="mdi mdi-information mr-1"></i> Avatar removed successfully!
                            </span>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4 grid-margin">
        <!-- User Info Card -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="mdi mdi-account-circle text-info"></i> Account Information
                </h5>
                
                <div class="user-info">
                    <div class="d-flex align-items-center mb-3">
                        <div class="sidebar-avatar mr-3">
                            <img src="{{ $user->avatar_url }}" 
                                 alt="Avatar" 
                                 class="sidebar-avatar-image">
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $user->name }}</h6>
                            <small class="text-muted">{{ $user->email }}</small>
                </div>
            </div>

                    <div class="account-stats">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Role:</span>
                            <strong>{{ ucfirst($user->roles->first()->name ?? 'User') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Member since:</span>
                            <strong>{{ $user->created_at->format('M Y') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Email status:</span>
                            @if($user->hasVerifiedEmail())
                                <span class="badge badge-success">Verified</span>
                            @else
                                <span class="badge badge-warning">Unverified</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Password Card -->
<div class="row">
    <div class="col-md-8 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="mdi mdi-lock text-primary"></i> Update Password
                </h4>
                <p class="text-muted mb-4">Ensure your account is using a long, random password to stay secure.</p>

                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="form-group mb-4">
                        <label for="update_password_current_password" class="form-label">Current Password</label>
                        <input type="password" 
                               class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                               id="update_password_current_password" 
                               name="current_password" 
                               autocomplete="current-password">
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="update_password_password" class="form-label">New Password</label>
                        <input type="password" 
                               class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                               id="update_password_password" 
                               name="password" 
                               autocomplete="new-password">
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="update_password_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" 
                               class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                               id="update_password_password_confirmation" 
                               name="password_confirmation" 
                               autocomplete="new-password">
                        @error('password_confirmation', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary mr-3">
                            <i class="mdi mdi-check"></i> Update Password
                        </button>

                        @if (session('status') === 'password-updated')
                            <span class="text-success ml-3">
                                <i class="mdi mdi-check-circle mr-1"></i> Password updated successfully!
                            </span>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Card -->
<div class="row">
    <div class="col-md-8 grid-margin">
        <div class="card border-danger">
            <div class="card-body">
                <h4 class="card-title mb-4 text-danger">
                    <i class="mdi mdi-delete text-danger"></i> Delete Account
                </h4>
                <p class="text-muted mb-4">
                    Once your account is deleted, all of its resources and data will be permanently deleted. 
                    Before deleting your account, please download any data or information that you wish to retain.
                </p>

                <button type="button" 
                        class="btn btn-danger" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteAccountModal">
                    <i class="mdi mdi-delete mr-2"></i> Delete Account
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="mdi mdi-delete text-danger"></i> Delete Account
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                
                <div class="modal-body">
                    <p class="mb-3">
                        Are you sure you want to delete your account? Once your account is deleted, 
                        all of its resources and data will be permanently deleted.
                    </p>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" 
                               class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Enter your password to confirm">
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close mr-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="mdi mdi-delete mr-1"></i> Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Avatar Upload Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarModalLabel">
                    <i class="mdi mdi-camera text-primary"></i> Upload Avatar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="avatar" class="form-label">Choose Avatar Image</label>
                        <input type="file" 
                               class="form-control @error('avatar') is-invalid @enderror" 
                               id="avatar" 
                               name="avatar" 
                               accept="image/*"
                               onchange="previewAvatar(this)">
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Supported formats: JPG, PNG, GIF. Maximum size: 2MB
                        </small>
                    </div>
                    
                    <div class="avatar-preview-container text-center">
                        <img id="avatar-preview-modal" 
                             src="{{ $user->avatar_url }}" 
                             alt="Avatar Preview" 
                             class="avatar-preview-image">
                        <p class="text-muted small mt-2">Preview</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close mr-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-upload mr-1"></i> Upload Avatar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Avatar Styling */
    .avatar-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #e3e6f0;
        transition: all 0.3s ease;
    }
    
    .avatar-image:hover {
        border-color: #667eea;
        transform: scale(1.05);
    }
    
    .sidebar-avatar-image {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e3e6f0;
    }
    
    .avatar-preview-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e3e6f0;
    }
    
    .avatar-container {
        position: relative;
        display: inline-block;
    }
    
    .avatar-section {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e3e6f0;
    }
    
    .avatar-info {
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    .avatar-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .avatar-preview-container {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e3e6f0;
    }
    
    /* Legacy avatar circle for fallback */
    .avatar-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: bold;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .account-stats {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }
    
    .card.border-danger {
        border-color: #dc3545 !important;
    }
    
    .badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-success {
        background-color: #28a745;
        color: white;
    }
    
    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }
    
    /* File input styling */
    .form-control[type="file"] {
        padding: 0.375rem 0.75rem;
    }
    
    .form-control[type="file"]::-webkit-file-upload-button {
        background-color: #667eea;
        color: white;
        border: none;
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        margin-right: 0.5rem;
        cursor: pointer;
    }
    
    .form-control[type="file"]::-webkit-file-upload-button:hover {
        background-color: #5a6fd8;
    }
</style>
@endpush

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            // Update preview in modal
            document.getElementById('avatar-preview-modal').src = e.target.result;
            
            // Update main avatar preview
            document.getElementById('avatar-preview').src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Auto-refresh page after successful avatar upload
@if(session('status') === 'avatar-updated' || session('status') === 'avatar-deleted')
    setTimeout(function() {
        location.reload();
    }, 2000);
@endif
</script>
@endpush
@endsection
