@extends('layouts.skydash')

@section('content')
<div class="row">
    <!-- Welcome Banner -->
    <div class="col-md-12 grid-margin">
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-white mb-2">Welcome back, {{ auth()->user()->name }}!</h2>
                        <p class="text-white mb-0" style="opacity: 0.9;">Continue your learning journey with Smart Edu LMS</p>
                    </div>
                    <div>
                        <i class="icon-graduation" style="font-size: 64px; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Progress Card -->
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="icon-chart text-primary"></i> Overall Progress
                </h4>
                <div class="d-flex align-items-center">
                    <div style="flex: 0 0 150px;">
                        <h1 class="mb-0 text-primary" style="font-size: 3.5rem; font-weight: 700;">
                            {{ $overallProgress ?? 0 }}%
                        </h1>
                        <p class="text-muted mb-0">Course Completion</p>
                    </div>
                    <div style="flex: 1; margin-left: 2rem;">
                        <div style="background: #e5e7eb; height: 20px; border-radius: 9999px; overflow: hidden;">
                            <div style="background: linear-gradient(90deg, #667eea, #764ba2); height: 100%; width: {{ $overallProgress ?? 0 }}%; transition: width 0.3s ease;"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">{{ $completedLessonsCount ?? 0 }} Lessons Completed</small>
                            <small class="text-muted">{{ $totalLessons ?? 0 }} Total Lessons</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards Row 1 -->
<div class="row">
    <div class="col-lg-3 col-md-6 grid-margin stretch-card">
        <div class="card card-tale interactive-card">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-4">Enrolled Courses</p>
                        <p class="fs-30 mb-2">{{ $totalEnrolledCourses ?? 0 }}</p>
                        <p>{{ $activeCourses ?? 0 }} active</p>
                    </div>
                    <div class="card-icon-circle">
                        <i class="icon-book"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 grid-margin stretch-card">
        <div class="card card-dark-blue interactive-card">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-4">Completed Lessons</p>
                        <p class="fs-30 mb-2">{{ $completedLessonsCount ?? 0 }}</p>
                        <p>{{ $inProgressLessons ?? 0 }} in progress</p>
                    </div>
                    <div class="card-icon-circle">
                        <i class="icon-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 grid-margin stretch-card">
        <div class="card card-light-blue interactive-card">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-4">Quiz Attempts</p>
                        <p class="fs-30 mb-2">{{ $totalQuizAttempts ?? 0 }}</p>
                        <p>{{ $passedQuizzes ?? 0 }} passed</p>
                    </div>
                    <div class="card-icon-circle">
                    <i class="mdi mdi-clipboard-text"></i>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 grid-margin stretch-card">
        <div class="card card-light-danger interactive-card">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-4">Average Score</p>
                        <p class="fs-30 mb-2">{{ number_format($averageScore ?? 0, 1) }}%</p>
                        <p>Quiz performance</p>
                    </div>
                    <div class="card-icon-circle">
                        <i class="icon-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <!-- Course Progress Chart -->
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="icon-chart-bar text-primary"></i> Course Progress
                </h4>
                <canvas id="courseProgressChart" height="60"></canvas>
            </div>
        </div>
    </div>

    <!-- Weekly Activity Chart -->
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="icon-calendar text-success"></i> Weekly Activity
                </h4>
                <canvas id="weeklyProgressChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Quiz Performance Chart -->
@if(isset($quizPerformance) && $quizPerformance->count() > 0)
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="icon-trending-up text-warning"></i> Quiz Performance Over Time
                </h4>
                <canvas id="quizPerformanceChart" height="50"></canvas>
            </div>
        </div>
    </div>
</div>
@endif

<!-- My Courses -->
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="icon-book text-primary"></i> My Courses
                    </h4>
                    <a href="{{ route('courses.index') }}" class="btn btn-sm btn-outline-primary">
                        Browse All <i class="icon-arrow-right"></i>
                    </a>
                </div>
                
                <div class="row">
                    @forelse($enrolledCourses ?? [] as $course)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card course-card h-100" style="cursor: pointer; transition: all 0.3s;" 
                             onclick="window.location.href='{{ $course->url }}'"
                             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.1)'" 
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 150px; display: flex; align-items: center; justify-content: center;">
                                @if($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span style="font-size: 3rem; font-weight: 700; color: white; opacity: 0.8;">
                                        {{ substr($course->title, 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title mb-2" style="font-size: 1.125rem; font-weight: 600;">
                                    {{ Str::limit($course->title, 40) }}
                                </h5>
                                <p class="text-muted mb-3" style="font-size: 0.875rem;">
                                    {{ Str::limit($course->description ?? 'No description available.', 80) }}
                                </p>
                                
                                @php
                                    $courseProg = $courseProgress->firstWhere('course', $course->title);
                                @endphp
                                @if($courseProg)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="text-muted">Progress</small>
                                        <small class="text-muted font-weight-bold">{{ $courseProg['progress'] }}%</small>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: {{ $courseProg['progress'] }}%"></div>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge badge-info">
                                        <i class="icon-folder mr-1"></i>{{ $course->modules->count() ?? 0 }} Modules
                                    </span>
                                    <a href="{{ $course->url }}" class="btn btn-sm btn-primary">
                                        Continue <i class="icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="icon-book-open" style="font-size: 64px; color: #e3e6f0;"></i>
                            <h5 class="mt-3 mb-2">No enrolled courses yet</h5>
                            <p class="text-muted mb-4">Start your learning journey by enrolling in courses</p>
                            <a href="{{ route('courses.index') }}" class="btn btn-primary">
                                <i class="icon-magnifier mr-2"></i>Browse Courses
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <!-- Recent Progress -->
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card modern-activity-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="icon-clock text-success mr-2"></i> Recent Progress
                    </h4>
                    <span class="badge badge-light">{{ $recentProgress->count() ?? 0 }} items</span>
                </div>
                <div class="modern-list">
                    @forelse($recentProgress ?? [] as $progress)
                    <div class="modern-list-item {{ $progress->is_completed ? 'completed' : 'in-progress' }}">
                        <div class="modern-list-content">
                            <div class="modern-list-icon">
                                @if($progress->is_completed)
                                    <div class="icon-wrapper icon-wrapper-success">
                                        <i class="icon-check"></i>
                                    </div>
                                @else
                                    <div class="icon-wrapper icon-wrapper-primary">
                                        <i class="icon-clock"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="modern-list-details">
                                <h6 class="modern-list-title">{{ Str::limit($progress->lesson->title ?? 'Lesson', 40) }}</h6>
                                <p class="modern-list-subtitle">
                                    <i class="icon-graduation mr-1"></i>
                                    {{ Str::limit($progress->lesson->module->course->title ?? 'Course', 35) }}
                                </p>
                                <div class="modern-list-meta">
                                    <span class="meta-time">
                                        <i class="icon-calendar mr-1"></i>
                                        {{ $progress->updated_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            <div class="modern-list-status">
                                @if($progress->is_completed)
                                    <span class="status-badge status-badge-success">
                                        <i class="icon-check-circle mr-1"></i> Done
                                    </span>
                                @else
                                    <span class="status-badge status-badge-progress">
                                        <i class="icon-refresh mr-1"></i> Learning
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="icon-clock"></i>
                        </div>
                        <h5 class="empty-state-title">No progress yet</h5>
                        <p class="empty-state-text">Start learning to see your progress here</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Quiz Results -->
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card modern-activity-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="icon-question text-warning mr-2"></i> Recent Quiz Results
                    </h4>
                    <span class="badge badge-light">{{ $recentQuizAttempts->count() ?? 0 }} attempts</span>
                </div>
                <div class="modern-list">
                    @forelse($recentQuizAttempts ?? [] as $attempt)
                    <div class="modern-list-item quiz-item {{ ($attempt->is_passed ?? false) ? 'passed' : 'failed' }}">
                        <div class="modern-list-content">
                            <div class="modern-list-icon">
                                @if($attempt->is_passed ?? false)
                                    <div class="icon-wrapper icon-wrapper-success">
                                        <i class="icon-star"></i>
                                    </div>
                                @else
                                    <div class="icon-wrapper icon-wrapper-danger">
                                        <i class="icon-close"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="modern-list-details">
                                <h6 class="modern-list-title">{{ Str::limit($attempt->quiz->title ?? 'Quiz', 40) }}</h6>
                                <div class="score-display">
                                    <span class="score-value">{{ $attempt->score ?? 0 }}</span>
                                    <span class="score-separator">/</span>
                                    <span class="score-total">{{ $attempt->total_points ?? 'N/A' }}</span>
                                    @if($attempt->total_points > 0)
                                        <span class="score-percentage">
                                            ({{ round(($attempt->score / $attempt->total_points) * 100) }}%)
                                        </span>
                                    @endif
                                </div>
                                <div class="modern-list-meta">
                                    <span class="meta-time">
                                        <i class="icon-calendar mr-1"></i>
                                        {{ $attempt->completed_at ? $attempt->completed_at->diffForHumans() : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                            <div class="modern-list-status">
                                @if($attempt->is_passed ?? false)
                                    <span class="status-badge status-badge-success">
                                        <i class="icon-check-circle mr-1"></i> Passed
                                    </span>
                                @else
                                    <span class="status-badge status-badge-danger">
                                        <i class="icon-close-circle mr-1"></i> Failed
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="icon-question"></i>
                        </div>
                        <h5 class="empty-state-title">No quiz attempts yet</h5>
                        <p class="empty-state-text">Take quizzes to see your results here</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .interactive-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .interactive-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1;
    }

    .interactive-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .interactive-card:hover::before {
        opacity: 1;
    }

    .card-icon-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .card-icon-circle i {
        font-size: 28px;
        color: rgba(255, 255, 255, 0.95);
        transition: all 0.3s ease;
    }

    .interactive-card:hover .card-icon-circle {
        background: rgba(255, 255, 255, 0.35);
        transform: scale(1.1) rotate(5deg);
    }

    .interactive-card:hover .card-icon-circle i {
        transform: scale(1.1);
    }

    /* Stagger Animation Delay for Multiple Cards */
    .interactive-card:nth-child(1) {
        animation-delay: 0.05s;
    }

    .interactive-card:nth-child(2) {
        animation-delay: 0.1s;
    }

    .interactive-card:nth-child(3) {
        animation-delay: 0.15s;
    }

    .interactive-card:nth-child(4) {
        animation-delay: 0.2s;
    }

    /* Pulse Effect for Numbers */
    .fs-30 {
        transition: all 0.3s ease;
        font-size: 30px;
        font-weight: 600;
    }

    .interactive-card:hover .fs-30 {
        transform: scale(1.05);
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .card-icon-circle {
            width: 50px;
            height: 50px;
        }

        .card-icon-circle i {
            font-size: 24px;
        }

        .interactive-card:hover {
            transform: translateY(-5px);
        }
    }

    .modern-activity-card {
        border-radius: 16px;
        border: 1px solid #e3e6f0;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .modern-activity-card:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .modern-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .modern-list-item {
        background: #ffffff;
        border: 1px solid #e3e6f0;
        border-radius: 12px;
        padding: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .modern-list-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: currentColor;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modern-list-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        border-color: currentColor;
    }

    .modern-list-item:hover::before {
        opacity: 1;
    }

    .modern-list-item.completed {
        color: #06beb6;
    }

    .modern-list-item.in-progress {
        color: #667eea;
    }

    .modern-list-item.passed {
        color: #10b981;
    }

    .modern-list-item.failed {
        color: #f5576c;
    }

    .modern-list-content {
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .modern-list-icon {
        flex-shrink: 0;
    }

    .icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        transition: all 0.3s ease;
    }

    .icon-wrapper-success {
        background: rgba(6, 190, 182, 0.1);
        color: #06beb6;
    }

    .icon-wrapper-primary {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
    }

    .icon-wrapper-danger {
        background: rgba(245, 87, 108, 0.1);
        color: #f5576c;
    }

    .modern-list-item:hover .icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }

    .modern-list-details {
        flex: 1;
        min-width: 0;
    }

    .modern-list-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 6px;
        line-height: 1.4;
    }

    .modern-list-subtitle {
        font-size: 0.85rem;
        color: #718096;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }

    .modern-list-meta {
        display: flex;
        gap: 16px;
        margin-top: 8px;
    }

    .meta-time {
        font-size: 0.75rem;
        color: #a0aec0;
        display: flex;
        align-items: center;
    }

    .modern-list-status {
        flex-shrink: 0;
        display: flex;
        align-items: center;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }

    .status-badge-success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .status-badge-progress {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
    }

    .status-badge-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    /* Score Display */
    .score-display {
        display: flex;
        align-items: baseline;
        gap: 4px;
        margin-bottom: 8px;
    }

    .score-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a202c;
    }

    .score-separator {
        font-size: 0.9rem;
        color: #a0aec0;
        font-weight: 500;
    }

    .score-total {
        font-size: 0.9rem;
        color: #718096;
        font-weight: 500;
    }

    .score-percentage {
        font-size: 0.85rem;
        color: #4a5568;
        font-weight: 600;
        margin-left: 4px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: #a0aec0;
    }

    .empty-state-icon {
        font-size: 64px;
        opacity: 0.3;
        margin-bottom: 16px;
    }

    .empty-state-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #718096;
        margin-bottom: 8px;
    }

    .empty-state-text {
        font-size: 0.9rem;
        color: #a0aec0;
        margin: 0;
    }

    /* Icon circles for other components */
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .icon-circle-sm {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    /* Course cards */
    .course-card {
        border: 1px solid #e3e6f0;
    }

    .course-card .card-header {
        border: none;
        padding: 0;
    }

    /* Gradient backgrounds */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%);
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    /* List group styling */
    .list-group-item {
        border: 1px solid #e3e6f0;
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        background-color: rgba(102, 126, 234, 0.05);
        transform: translateX(5px);
    }

    /* Badge styling */
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    /* Progress bar */
    .progress {
        height: 8px;
        border-radius: 10px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Course Progress Chart
const courseProgressCtx = document.getElementById('courseProgressChart');
if (courseProgressCtx) {
    const courseProgressData = @json($courseProgress ?? []);
    
    new Chart(courseProgressCtx, {
        type: 'bar',
        data: {
            labels: courseProgressData.map(item => item.course.length > 20 ? item.course.substring(0, 20) + '...' : item.course),
            datasets: [{
                label: 'Progress (%)',
                data: courseProgressData.map(item => item.progress),
                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                borderColor: 'rgba(102, 126, 234, 1)',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

// Weekly Progress Chart
const weeklyProgressCtx = document.getElementById('weeklyProgressChart');
if (weeklyProgressCtx) {
    const weeklyProgressData = @json($weeklyProgress ?? []);
    
    new Chart(weeklyProgressCtx, {
        type: 'line',
        data: {
            labels: weeklyProgressData.map(item => item.date),
            datasets: [{
                label: 'Lessons Completed',
                data: weeklyProgressData.map(item => item.completed),
                borderColor: 'rgba(6, 190, 182, 1)',
                backgroundColor: 'rgba(6, 190, 182, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

// Quiz Performance Chart
const quizPerformanceCtx = document.getElementById('quizPerformanceChart');
if (quizPerformanceCtx) {
    const quizPerformanceData = @json($quizPerformance ?? []);
    
    if (quizPerformanceData.length > 0) {
        new Chart(quizPerformanceCtx, {
            type: 'line',
            data: {
                labels: quizPerformanceData.map(item => item.date),
                datasets: [{
                    label: 'Quiz Score (%)',
                    data: quizPerformanceData.map(item => item.score),
                    borderColor: 'rgba(245, 87, 108, 1)',
                    backgroundColor: 'rgba(245, 87, 108, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    }
}
</script>
@endpush
@endsection
