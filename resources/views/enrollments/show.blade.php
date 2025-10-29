@extends('layouts.skydash')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">{{ $enrollment->course->title }}</h3>
                @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']) && isset($enrollment->user))
                    <h6 class="font-weight-normal mb-2">
                        <i class="icon-user mr-1"></i> Student: <strong>{{ $enrollment->user->name }}</strong>
                    </h6>
                @endif
                <h6 class="font-weight-normal mb-0">{{ Str::limit($enrollment->course->description, 100) }}</h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('enrollments.index') }}" class="btn btn-secondary">
                        <i class="icon-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enrollment Details -->
<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Course Content</h4>
                
                <!-- Progress Overview -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5>Overall Progress</h5>
                        <h5 class="text-primary">{{ $enrollment->progress_percentage }}%</h5>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" 
                             role="progressbar" 
                             style="width: {{ $enrollment->progress_percentage }}%" 
                             aria-valuenow="{{ $enrollment->progress_percentage }}" 
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
                
                <!-- Modules List -->
                <h5 class="mb-3">Modules ({{ $enrollment->course->modules->count() }})</h5>
                @forelse($enrollment->course->modules as $module)
                <div class="card mb-3 module-item">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-fill">
                                <h6 class="mb-1">
                                    <i class="icon-layers text-primary"></i>
                                    {{ $module->title }}
                                </h6>
                                <small class="text-muted">{{ $module->lessons->count() }} lessons</small>
                            </div>
                            <span class="badge badge-secondary">Module {{ $module->order }}</span>
                        </div>
                        
                        @if($module->lessons->isNotEmpty())
                        <div class="mt-3">
                            <div class="accordion" id="lessons{{ $module->id }}">
                                @foreach($module->lessons as $lesson)
                                <div class="card lesson-item mb-2">
                                    <div class="card-header" id="heading{{ $lesson->id }}">
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-link text-left w-100" type="button" data-toggle="collapse" 
                                                    data-target="#collapse{{ $lesson->id }}" aria-expanded="false" 
                                                    aria-controls="collapse{{ $lesson->id }}">
                                                <i class="icon-list text-success"></i>
                                                <span class="ml-2">{{ $lesson->title }}</span>
                                                @if($enrollment->progress_percentage >= ($loop->iteration / $enrollment->course->modules->sum(function($m) { return $m->lessons->count(); }) * 100))
                                                    <i class="icon-check ml-auto text-success"></i>
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                    <div id="collapse{{ $lesson->id }}" class="collapse" 
                                         aria-labelledby="heading{{ $lesson->id }}" data-parent="#lessons{{ $module->id }}">
                                        <div class="card-body">
                                            <p class="text-muted mb-2">{{ $lesson->description ?? 'No description available.' }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="icon-clock"></i> {{ $lesson->duration_minutes ?? 'N/A' }} min
                                                </small>
                                                @if($lesson->type)
                                                <span class="badge badge-primary">{{ ucfirst($lesson->type) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-muted">No modules available yet.</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Enrollment Info</h4>
                
                @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']) && isset($enrollment->user))
                <div class="mb-4">
                    <p class="text-muted mb-2">Student</p>
                    <p class="font-weight-bold">{{ $enrollment->user->name }}</p>
                    <p class="text-muted small mb-0">{{ $enrollment->user->email }}</p>
                </div>
                @endif
                
                <div class="mb-4">
                    <p class="text-muted mb-2">Status</p>
                    <span class="badge {{ $enrollment->completed_at ? 'badge-success' : ($enrollment->progress_percentage > 0 ? 'badge-info' : 'badge-warning') }}">
                        @if($enrollment->completed_at)
                            Completed
                        @elseif($enrollment->progress_percentage > 0)
                            In Progress
                        @else
                            Not Started
                        @endif
                    </span>
                </div>
                
                <div class="mb-4">
                    <p class="text-muted mb-2">Enrolled Date</p>
                    <p class="font-weight-bold">{{ $enrollment->enrolled_at->format('M d, Y') }}</p>
                </div>
                
                @if($enrollment->completed_at)
                <div class="mb-4">
                    <p class="text-muted mb-2">Completed Date</p>
                    <p class="font-weight-bold">{{ $enrollment->completed_at->format('M d, Y') }}</p>
                </div>
                @endif
                
                <div class="mb-4">
                    <p class="text-muted mb-2">Course Level</p>
                    <span class="badge badge-info">{{ ucfirst($enrollment->course->level) }}</span>
                </div>
                
                <div class="mb-4">
                    <p class="text-muted mb-2">Duration</p>
                    <p class="font-weight-bold">{{ $enrollment->course->duration_hours }} hours</p>
                </div>
                
                <hr>
                
                <div class="mb-4">
                    <div class="card bg-gradient-primary text-white">
                        <div class="card-body text-center">
                            <h4>{{ $enrollment->progress_percentage }}%</h4>
                            <p class="mb-0">Complete</p>
                        </div>
                    </div>
                </div>
                
                @if(!auth()->check() || !auth()->user()->hasAnyRole(['admin', 'instructor']))
                    @if(!$enrollment->completed_at && $enrollment->progress_percentage < 100)
                    <form action="{{ route('enrollments.complete', $enrollment) }}" method="POST" class="mb-3">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Mark this course as completed?');">
                            <i class="icon-check"></i> Mark as Complete
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-primary w-100">
                        <i class="icon-book"></i> Continue Learning
                    </a>
                @else
                    <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-primary w-100">
                        <i class="icon-book"></i> View Course
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .module-item {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    
    .module-item:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .lesson-item {
        transition: all 0.2s ease;
    }
    
    .lesson-item:hover {
        background-color: #f8f9fa;
    }
    
    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
    
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endpush
@endsection
