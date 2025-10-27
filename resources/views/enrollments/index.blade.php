@extends('layouts.skydash')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">My Enrollments</h3>
                <h6 class="font-weight-normal mb-0">Track your learning progress</h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('courses.index') }}" class="btn btn-primary">
                        <i class="icon-plus"></i> Browse Courses
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enrollments List -->
<div class="row">
    @forelse($enrollments as $enrollment)
    <div class="col-md-6 mb-4">
        <div class="card enrollment-card" style="height: 100%;">
            <div class="card-body" style="display: flex; flex-direction: column;">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="badge {{ $enrollment->completed_at ? 'badge-success' : ($enrollment->progress_percentage > 0 ? 'badge-info' : 'badge-warning') }}">
                        @if($enrollment->completed_at)
                            Completed
                        @elseif($enrollment->progress_percentage > 0)
                            In Progress
                        @else
                            Not Started
                        @endif
                    </div>
                    <div class="badge badge-dark">{{ ucfirst($enrollment->course->level) }}</div>
                </div>
                
                @if($enrollment->course->thumbnail)
                <img src="{{ Storage::url($enrollment->course->thumbnail) }}" class="img-fluid rounded mb-3" alt="{{ $enrollment->course->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                @else
                <div class="bg-light rounded mb-3" style="width: 100%; height: 200px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="icon-book" style="font-size: 64px; color: rgba(255, 255, 255, 0.3);"></i>
                </div>
                @endif
                
                <h5 class="font-weight-bold mb-2">{{ $enrollment->course->title }}</h5>
                <p class="text-muted mb-3" style="min-height: 40px;">{{ Str::limit($enrollment->course->description, 80) }}</p>
                
                <!-- Progress Bar -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">Progress</small>
                        <small class="text-muted font-weight-bold">{{ $enrollment->progress_percentage }}%</small>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-primary" role="progressbar" 
                             style="width: {{ $enrollment->progress_percentage }}%" 
                             aria-valuenow="{{ $enrollment->progress_percentage }}" 
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="d-flex align-items-center justify-content-between text-muted mb-3" style="font-size: 13px;">
                    <span><i class="icon-people"></i> {{ $enrollment->course->modules->count() }} Modules</span>
                    <span><i class="icon-clock"></i> {{ $enrollment->course->duration_hours }}h</span>
                    <span><i class="icon-calendar"></i> {{ $enrollment->enrolled_at->format('M d, Y') }}</span>
                </div>
                
                <!-- Actions -->
                <div class="d-flex gap-2 mt-auto">
                    <a href="{{ route('enrollments.show', $enrollment) }}" class="btn btn-sm btn-info flex-fill">
                        <i class="icon-eye"></i> View Details
                    </a>
                    <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-sm btn-primary flex-fill">
                        <i class="icon-book"></i> Continue
                    </a>
                    <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" class="flex-fill" onsubmit="return confirm('Are you sure you want to unenroll from this course?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger w-100">
                            <i class="icon-close"></i> Unenroll
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-md-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="icon-notebook" style="font-size: 64px; color: #ccc;"></i>
                <h4 class="mt-3">No enrollments yet</h4>
                <p class="text-muted">Start your learning journey by enrolling in a course!</p>
                <a href="{{ route('courses.index') }}" class="btn btn-primary mt-3">
                    <i class="icon-plus"></i> Browse Courses
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($enrollments->hasPages())
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-center">
            {{ $enrollments->links() }}
        </div>
    </div>
</div>
@endif

@push('styles')
<style>
    .enrollment-card {
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }
    
    .enrollment-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        border-color: #667eea;
    }
    
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
    
    .badge-info {
        background-color: #17a2b8;
        color: white;
    }
    
    .badge-warning {
        background-color: #ffc107;
        color: #333;
    }
    
    .badge-dark {
        background-color: #343a40;
        color: white;
    }
    
    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
    }
    
    .progress-bar {
        border-radius: 10px;
        transition: width 0.6s ease;
    }
    
    .gap-2 {
        gap: 8px;
    }
</style>
@endpush
@endsection
