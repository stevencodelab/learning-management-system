@extends('layouts.dashboard')

@section('content')
<div>
    <!-- Welcome Banner -->
    <div class="card mb-6" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); color: white; border: none;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Welcome back, {{ auth()->user()->name }}!</h1>
                <p style="font-size: 1.125rem; opacity: 0.9;">Continue your learning journey with Smart Edu.</p>
            </div>
            <div>
                <img src="{{ asset('build/images/logo/logo.png') }}" alt="Logo" style="width: 80px; height: 80px; opacity: 0.8;">
            </div>
        </div>
    </div>
    
    <!-- Progress Overview -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">Overall Progress</h3>
        </div>
        <div style="display: flex; align-items: center; gap: 2rem;">
            <div style="flex: 1;">
                <div style="font-size: 3rem; font-weight: 700; color: #3b82f6; margin-bottom: 0.5rem;">
                    {{ $overallProgress ?? 0 }}%
                </div>
                <div style="color: #6b7280; font-size: 1.125rem;">Course Completion</div>
            </div>
            <div style="flex: 2;">
                <div style="background: #e5e7eb; height: 16px; border-radius: 9999px; overflow: hidden;">
                    <div style="background: linear-gradient(90deg, #3b82f6, #2563eb); height: 100%; width: {{ $overallProgress ?? 0 }}%; transition: width 0.3s ease;"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row g-4 mb-6">
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 28px; height: 28px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="stat-value">{{ $enrolledCourses->count() ?? 0 }}</div>
                <div class="stat-label">Enrolled Courses</div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 28px; height: 28px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="stat-value">{{ $recentProgress->where('is_completed', true)->count() ?? 0 }}</div>
                <div class="stat-label">Completed Lessons</div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 28px; height: 28px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div class="stat-value">{{ $recentQuizAttempts->count() ?? 0 }}</div>
                <div class="stat-label">Quiz Attempts</div>
            </div>
        </div>
    </div>
    
    <!-- My Courses -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">My Courses</h3>
            <a href="{{ route('courses.index') }}" style="color: #3b82f6; text-decoration: none;">Browse all courses →</a>
        </div>
        <div class="row g-4">
            @forelse($enrolledCourses ?? [] as $course)
            <div class="col-lg-4 col-md-6">
                <div class="card" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                        <span style="font-size: 3rem; font-weight: 700; color: white;">{{ substr($course->title, 0, 1) }}</span>
                    </div>
                    <h4 style="font-size: 1.25rem; font-weight: 600; color: #1e40af; margin-bottom: 0.5rem;">{{ $course->title }}</h4>
                    <p style="color: #6b7280; margin-bottom: 1rem;">{{ $course->description ?? 'No description available.' }}</p>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.875rem; color: #6b7280;">{{ $course->modules->count() ?? 0 }} Modules</span>
                        <a href="{{ route('courses.show', $course) }}" style="color: #3b82f6; font-weight: 600; text-decoration: none;">Continue →</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="p-5 text-center">
                    <p style="font-size: 1.125rem; color: #6b7280; margin-bottom: 1rem;">You haven't enrolled in any courses yet.</p>
                    <a href="{{ route('courses.index') }}" style="display: inline-block; padding: 0.75rem 2rem; background: #3b82f6; color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600;">Browse Courses</a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Progress</h3>
                </div>
                <div class="list-group">
                    @forelse($recentProgress ?? [] as $progress)
                    <div class="d-flex align-items-center p-3 border-bottom" style="text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
                        <div style="width: 40px; height: 40px; background: #dbeafe; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                            <span style="color: #3b82f6; font-weight: 700;">{{ substr($progress->lesson->title ?? 'L', 0, 1) }}</span>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #1f2937;">{{ $progress->lesson->title ?? 'Lesson' }}</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">
                                {{ $progress->is_completed ? 'Completed' : 'In Progress' }}
                            </div>
                        </div>
                        <div style="color: {{ $progress->is_completed ? '#10b981' : '#f59e0b' }}; font-weight: 700;">
                            {{ $progress->is_completed ? '✓' : '●' }}
                        </div>
                    </div>
                    @empty
                    <div class="p-3 text-center text-muted">No progress yet</div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Quiz Results</h3>
                </div>
                <div class="list-group">
                    @forelse($recentQuizAttempts ?? [] as $attempt)
                    <div class="d-flex align-items-center p-3 border-bottom" style="text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
                        <div style="width: 40px; height: 40px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                            <span style="color: #f59e0b; font-weight: 700;">Q</span>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #1f2937;">{{ $attempt->quiz->title ?? 'Quiz' }}</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">Score: {{ $attempt->score ?? 0 }}%</div>
                        </div>
                        <div style="color: #10b981; font-weight: 700;">{{ $attempt->is_passed ? 'Pass' : 'Fail' }}</div>
                    </div>
                    @empty
                    <div class="p-3 text-center text-muted">No quiz attempts yet</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add CSS for grid -->
<style>
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: -0.75rem;
    }
    .col-lg-3, .col-lg-4, .col-lg-6, .col-md-6 {
        padding: 0.75rem;
    }
    .col-lg-3 { flex: 0 0 25%; max-width: 25%; }
    .col-lg-4 { flex: 0 0 33.333%; max-width: 33.333%; }
    .col-lg-6 { flex: 0 0 50%; max-width: 50%; }
    .col-md-6 { flex: 0 0 50%; max-width: 50%; }
    .mb-6 { margin-bottom: 2rem; }
    .g-4 > * { margin: 0.75rem; }
    .border-bottom { border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem; margin-bottom: 1rem; }
    .border-bottom:last-child { border-bottom: none; margin-bottom: 0; }
    @media (max-width: 991px) {
        .col-lg-3, .col-lg-4, .col-lg-6 { flex: 0 0 100%; max-width: 100%; }
    }
</style>
@endsection

