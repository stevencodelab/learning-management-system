@extends('layouts.skydash')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Courses Management</h3>
                <h6 class="font-weight-normal mb-0">Manage and organize all your courses here</h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('courses.create') }}" class="btn btn-primary">
                        <i class="icon-plus"></i> Create New Course
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Search -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Advanced Search</h4>
                <form action="{{ route('courses.index') }}" method="GET" id="searchForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search">Search</label>
                                <input type="text" class="form-control" name="search" id="search" 
                                       placeholder="Search courses..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="level">Level</label>
                                <select class="form-control" name="level" id="level">
                                    <option value="">All Levels</option>
                                    <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="published">Status</label>
                                <select class="form-control" name="published" id="published">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('published') == '1' ? 'selected' : '' }}>Published</option>
                                    <option value="0" {{ request('published') == '0' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="price_min">Min Price</label>
                                <input type="number" step="0.01" class="form-control" name="price_min" 
                                       id="price_min" placeholder="0.00" value="{{ request('price_min') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="price_max">Max Price</label>
                                <input type="number" step="0.01" class="form-control" name="price_max" 
                                       id="price_max" placeholder="9999.99" value="{{ request('price_max') }}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="icon-magnifier"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button type="reset" class="btn btn-secondary btn-sm" onclick="window.location.href='{{ route('courses.index') }}'">
                                <i class="icon-refresh"></i> Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Courses Grid -->
<div class="row">
    @forelse($courses as $course)
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card course-card" style="height: 100%; display: flex; flex-direction: column;">
            <div class="card-body" style="display: flex; flex-direction: column; flex: 1;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="badge {{ $course->is_published ? 'badge-success' : 'badge-warning' }}">
                        {{ $course->is_published ? 'Published' : 'Draft' }}
                    </div>
                    <div class="badge badge-info">{{ ucfirst($course->level) }}</div>
                </div>
                
                @if($course->thumbnail)
                <img src="{{ Storage::url($course->thumbnail) }}" class="img-fluid rounded mb-3" alt="{{ $course->title }}" style="width: 100%; height: 180px; object-fit: cover;">
                @else
                <div class="bg-light rounded mb-3" style="width: 100%; height: 180px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="icon-book" style="font-size: 64px; color: rgba(255, 255, 255, 0.5);"></i>
                </div>
                @endif
                
                <h5 class="font-weight-bold mb-2" style="min-height: 48px; line-height: 1.4;">{{ $course->title }}</h5>
                <p class="text-muted mb-3" style="min-height: 60px; font-size: 13px; line-height: 1.6;">{{ Str::limit($course->description, 100) }}</p>
                
                <div class="d-flex align-items-center justify-content-between text-muted mb-3" style="font-size: 13px;">
                    <span><i class="icon-clock"></i> {{ $course->duration_hours }}h</span>
                    <span><i class="icon-people"></i> {{ $course->modules->count() }} Modules</span>
                    <span><i class="ti-wallet"></i> ${{ number_format($course->price, 2) }}</span>
                </div>
                
                <div class="d-flex gap-2 mt-auto">
                    <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-info flex-fill">
                        <i class="icon-eye"></i> View
                    </a>
                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-primary flex-fill">
                        <i class="icon-pencil"></i> Edit
                    </a>
                    <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline flex-fill" onsubmit="return confirm('Are you sure you want to delete this course?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger w-100">
                            <i class="icon-trash"></i> Delete
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
                <i class="icon-book" style="font-size: 64px; color: #ccc;"></i>
                <h4 class="mt-3">No courses found</h4>
                <p class="text-muted">Start by creating your first course!</p>
                <a href="{{ route('courses.create') }}" class="btn btn-primary mt-3">
                    <i class="icon-plus"></i> Create Course
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($courses->hasPages())
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-center">
            {{ $courses->links() }}
        </div>
    </div>
</div>
@endif

@push('styles')
<style>
    #searchForm .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 5px;
    }
    
    #searchForm .form-control {
        border-radius: 8px;
    }
    .course-card {
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }
    
    .course-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        border-color: #667eea;
    }
    
    .course-card .card-body {
        padding: 1.25rem;
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
    
    /* Ensure uniform card heights */
    .course-card .card-body {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .course-card .btn-group {
        margin-top: auto;
    }
    
    /* Responsive adjustments */
    @media (max-width: 991px) {
        .col-md-6 {
            margin-bottom: 20px;
        }
    }
    
    @media (max-width: 768px) {
        .course-card:hover {
            transform: translateY(-5px);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Live search with debounce
    let searchTimeout;
    const searchInput = document.getElementById('search');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                // Optional: Auto-submit after 1 second of no typing
                // Uncomment to enable:
                // document.getElementById('searchForm').submit();
            }, 1000);
        });
    }
    
    // Enter key submit
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('searchForm').submit();
            }
        });
    }
</script>
@endpush
@endsection
