@extends('layouts.skydash')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">{{ $course->title }}</h3>
                <h6 class="font-weight-normal mb-0">{{ $course->description }}</h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-primary mr-2">
                        <i class="icon-pencil"></i> Edit
                    </a>
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                        <i class="icon-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Course Details -->
<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Course Details</h4>
                
                <div class="mb-4">
                    @if($course->thumbnail)
                    <img src="{{ Storage::url($course->thumbnail) }}" class="img-fluid rounded mb-3" alt="{{ $course->title }}">
                    @else
                    <div class="bg-light rounded mb-3" style="width: 100%; height: 300px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="icon-book" style="font-size: 120px; color: rgba(255, 255, 255, 0.3);"></i>
                    </div>
                    @endif
                </div>
                
                <div class="mb-4">
                    <h5>Description</h5>
                    <p class="text-muted">{{ $course->description }}</p>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="icon-grid mr-3" style="font-size: 24px; color: #667eea;"></i>
                            <div>
                                <p class="mb-0 text-muted">Level</p>
                                <h6 class="mb-0 font-weight-bold">{{ ucfirst($course->level) }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="icon-clock mr-3" style="font-size: 24px; color: #667eea;"></i>
                            <div>
                                <p class="mb-0 text-muted">Duration</p>
                                <h6 class="mb-0 font-weight-bold">{{ $course->duration_hours }} Hours</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="ti-wallet mr-3" style="font-size: 24px; color: #667eea;"></i>
                            <div>
                                <p class="mb-0 text-muted">Price</p>
                                <h6 class="mb-0 font-weight-bold">${{ number_format($course->price, 2) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h5>Modules ({{ $course->modules->count() }})</h5>
                    @forelse($course->modules as $module)
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-0">{{ $module->title }}</h6>
                                    <small class="text-muted">{{ $module->lessons->count() }} lessons</small>
                                </div>
                                <span class="badge badge-info">Module {{ $module->order }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No modules yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Quick Actions</h4>
                
                <div class="mb-4">
                    <p class="text-muted mb-2">Status</p>
                    <span class="badge {{ $course->is_published ? 'badge-success' : 'badge-warning' }}">
                        {{ $course->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
                
                <div class="mb-4">
                    <p class="text-muted mb-2">Created</p>
                    <p class="font-weight-bold">{{ $course->created_at->format('M d, Y') }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-muted mb-2">Last Updated</p>
                    <p class="font-weight-bold">{{ $course->updated_at->format('M d, Y') }}</p>
                </div>
                
                <hr>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-primary flex-fill">
                        <i class="icon-pencil"></i> Edit
                    </a>
                    <form action="{{ route('courses.destroy', $course) }}" method="POST" class="flex-fill" onsubmit="return confirm('Are you sure you want to delete this course?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="icon-trash"></i> Delete
                        </button>
                    </form>
                </div>
                
                @if(auth()->user()->hasRole('admin'))
                <form action="{{ route('courses.toggle-published', $course) }}" method="POST" class="mt-3">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-{{ $course->is_published ? 'warning' : 'success' }} w-100">
                        <i class="icon-{{ $course->is_published ? 'eye-off' : 'eye' }}"></i> 
                        {{ $course->is_published ? 'Unpublish' : 'Publish' }}
                    </button>
                </form>
                @endif
            </div>
        </div>
        
        <!-- Enrollment Stats -->
        <div class="card mt-3">
            <div class="card-body">
                <h4 class="card-title mb-4">Statistics</h4>
                
                <div class="mb-3">
                    <p class="text-muted mb-1">Total Enrollments</p>
                    <h4 class="font-weight-bold">{{ $course->enrollments->count() }}</h4>
                </div>
                
                <div class="mb-3">
                    <p class="text-muted mb-1">Total Modules</p>
                    <h4 class="font-weight-bold">{{ $course->modules->count() }}</h4>
                </div>
                
                <div class="mb-3">
                    <p class="text-muted mb-1">Total Lessons</p>
                    <h4 class="font-weight-bold">{{ $course->lessons_count }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-success {
        background-color: #28a745;
        color: white;
    }
    
    .badge-warning {
        background-color: #ffc107;
        color: #333;
    }
    
    .badge-info {
        background-color: #17a2b8;
        color: white;
    }
    
    .gap-2 {
        gap: 8px;
    }
</style>
@endpush
@endsection
