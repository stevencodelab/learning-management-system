@extends('layouts.skydash')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="page-header-content">
                    <div class="student-profile-header">
                        <div class="student-avatar-section">
                            <div class="student-avatar-container">
                                <img src="{{ $student->avatar_url }}" 
                                     alt="{{ $student->name }}" 
                                     class="student-avatar-image">
                                <div class="avatar-status-indicator {{ $student->email_verified_at ? 'verified' : 'unverified' }}"></div>
                            </div>
                        </div>
                        <div class="student-info-section">
                            <h1 class="student-name">{{ $student->name }}</h1>
                            <div class="student-badges">
                                <span class="badge badge-primary">Student #{{ $student->id }}</span>
                                @if($student->level)
                                    @php
                                        $levelColors = [
                                            'beginner' => 'success',
                                            'intermediate' => 'warning',
                                            'advanced' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge badge-{{ $levelColors[$student->level] ?? 'secondary' }}">{{ ucfirst($student->level) }}</span>
                                @endif
                                @if($student->email_verified_at)
                                    <span class="badge badge-success">Verified</span>
                                @else
                                    <span class="badge badge-warning">Unverified</span>
                                @endif
                            </div>
                            <div class="student-meta">
                                <span class="meta-item">
                                    <i class="mdi mdi-email"></i> {{ $student->email }}
                                </span>
                                <span class="meta-item">
                                    <i class="mdi mdi-calendar"></i> Joined {{ $student->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="page-header-actions">
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('instructor'))
                        <a href="{{ route('students.edit', $student) }}" class="btn btn-primary btn-lg">
                            <i class="mdi mdi-pencil"></i> Edit Student
                        </a>
                    @endif
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="mdi mdi-arrow-left"></i> Back to Students
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Overview -->
<div class="statistics-overview">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card stat-card-primary">
                    <div class="stat-card-icon">
                        <i class="mdi mdi-book"></i>
                    </div>
                    <div class="stat-card-content">
                        <h3 class="stat-number">{{ $stats['total_enrollments'] }}</h3>
                        <p class="stat-label">Total Enrollments</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card stat-card-success">
                    <div class="stat-card-icon">
                        <i class="mdi mdi-check-circle"></i>
                    </div>
                    <div class="stat-card-content">
                        <h3 class="stat-number">{{ $stats['completed_courses'] }}</h3>
                        <p class="stat-label">Completed Courses</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card stat-card-info">
                    <div class="stat-card-icon">
                        <i class="mdi mdi-play-circle"></i>
                    </div>
                    <div class="stat-card-content">
                        <h3 class="stat-number">{{ $stats['total_lessons_completed'] }}</h3>
                        <p class="stat-label">Lessons Completed</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card stat-card-warning">
                    <div class="stat-card-icon">
                        <i class="mdi mdi-help-circle"></i>
                    </div>
                    <div class="stat-card-content">
                        <h3 class="stat-number">{{ number_format($stats['average_quiz_score'], 1) }}%</h3>
                        <p class="stat-label">Avg Quiz Score</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <!-- Left Column - Main Content -->
            <div class="col-lg-8">
                
                <!-- Course Enrollments Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="mdi mdi-book-open"></i>
                            Course Enrollments
                        </h2>
                        <div class="section-actions">
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="mdi mdi-plus"></i> Browse Courses
                            </a>
                        </div>
                    </div>
                    
                    <div class="section-content">
                
                        @if($student->enrollments->count() > 0)
                            <div class="enrollments-table">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Course</th>
                                                <th>Enrolled</th>
                                                <th>Progress</th>
                                                <th>Status</th>
                                                <th width="80">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($student->enrollments as $enrollment)
                                            <tr>
                                                <td>
                                                    <div class="course-info">
                                                        <h6 class="course-title">{{ $enrollment->course->title }}</h6>
                                                        <span class="course-level">{{ ucfirst($enrollment->course->level) }} level</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="enrollment-date">{{ $enrollment->enrolled_at->format('M d, Y') }}</span>
                                                </td>
                                                <td>
                                                    <div class="progress-container">
                                                        <div class="progress">
                                                            <div class="progress-bar" 
                                                                 role="progressbar" 
                                                                 style="width: {{ $enrollment->progress_percentage }}%"
                                                                 aria-valuenow="{{ $enrollment->progress_percentage }}" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <span class="progress-text">{{ $enrollment->progress_percentage }}%</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($enrollment->completed_at)
                                                        <span class="status-badge status-completed">Completed</span>
                                                    @elseif($enrollment->progress_percentage > 0)
                                                        <span class="status-badge status-progress">In Progress</span>
                                                    @else
                                                        <span class="status-badge status-not-started">Not Started</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('enrollments.show', $enrollment) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="View Details">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="mdi mdi-book-open"></i>
                                </div>
                                <h3 class="empty-state-title">No Course Enrollments</h3>
                                <p class="empty-state-description">This student hasn't enrolled in any courses yet.</p>
                                <div class="empty-state-actions">
                                    <a href="{{ route('courses.index') }}" class="btn btn-primary">
                                        <i class="mdi mdi-book mr-2"></i> Browse Available Courses
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
                <!-- Recent Quiz Attempts Section -->
                @if($student->quizAttempts->count() > 0)
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="mdi mdi-help-circle"></i>
                            Recent Quiz Attempts
                        </h2>
                        @if($student->quizAttempts->count() > 5)
                            <div class="section-actions">
                                <span class="text-muted small">Showing 5 of {{ $student->quizAttempts->count() }} attempts</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="section-content">
                        <div class="quiz-attempts-table">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Quiz</th>
                                            <th>Course</th>
                                            <th>Score</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->quizAttempts->take(5) as $attempt)
                                        <tr>
                                            <td>
                                                <div class="quiz-info">
                                                    <h6 class="quiz-title">{{ $attempt->quiz->title }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="course-name">{{ $attempt->quiz->lesson->module->course->title }}</span>
                                            </td>
                                            <td>
                                                <span class="score-badge score-{{ $attempt->score >= 70 ? 'excellent' : ($attempt->score >= 50 ? 'good' : 'poor') }}">
                                                    {{ $attempt->score }}%
                                                </span>
                                            </td>
                                            <td>
                                                @if($attempt->completed_at)
                                                    <span class="status-badge status-completed">Completed</span>
                                                @else
                                                    <span class="status-badge status-progress">In Progress</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="attempt-date">{{ $attempt->created_at->format('M d, Y') }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Right Column - Sidebar -->
            <div class="col-lg-4">
        
                <!-- Student Information Card -->
                <div class="sidebar-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="mdi mdi-account"></i>
                            Student Information
                        </h3>
                    </div>
                    <div class="card-content">
                        <div class="info-grid">
                            <div class="info-item">
                                <label class="info-label">Full Name</label>
                                <p class="info-value">{{ $student->name }}</p>
                            </div>
                            
                            <div class="info-item">
                                <label class="info-label">Email Address</label>
                                <p class="info-value">{{ $student->email }}</p>
                            </div>
                            
                            @if($student->phone)
                            <div class="info-item">
                                <label class="info-label">Phone Number</label>
                                <p class="info-value">{{ $student->phone }}</p>
                            </div>
                            @endif
                            
                            @if($student->level)
                            <div class="info-item">
                                <label class="info-label">Student Level</label>
                                <div class="info-value">
                                    @php
                                        $levelColors = [
                                            'beginner' => 'success',
                                            'intermediate' => 'warning',
                                            'advanced' => 'danger'
                                        ];
                                    @endphp
                                    <span class="level-badge level-{{ $student->level }}">
                                        {{ ucfirst($student->level) }}
                                    </span>
                                </div>
                            </div>
                            @endif
                            
                            <div class="info-item">
                                <label class="info-label">Member Since</label>
                                <p class="info-value">{{ $student->created_at->format('M d, Y') }}</p>
                            </div>
                            
                            <div class="info-item">
                                <label class="info-label">Last Updated</label>
                                <p class="info-value">{{ $student->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
        
                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('instructor'))
                    <!-- Admin Actions Card -->
                    <div class="sidebar-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="mdi mdi-cog"></i>
                                Quick Actions
                            </h3>
                        </div>
                        <div class="card-content">
                            <div class="admin-info">
                                <div class="admin-info-item">
                                    <label class="admin-label">Student ID</label>
                                    <p class="admin-value">#{{ $student->id }}</p>
                                </div>
                                
                                <div class="admin-info-item">
                                    <label class="admin-label">Account Status</label>
                                    <div class="admin-value">
                                        @if($student->enrollments->count() > 0)
                                            <span class="status-badge status-active">Active</span>
                                        @else
                                            <span class="status-badge status-inactive">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="admin-info-item">
                                    <label class="admin-label">Email Status</label>
                                    <div class="admin-value">
                                        @if($student->email_verified_at)
                                            <span class="status-badge status-verified">Verified</span>
                                        @else
                                            <span class="status-badge status-unverified">Unverified</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="admin-actions">
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-primary btn-block">
                                    <i class="mdi mdi-pencil mr-2"></i> Edit Student
                                </a>
                                
                                <form action="{{ route('students.destroy', $student) }}" method="POST" 
                                      onsubmit="event.preventDefault(); confirmDelete(event, 'Are you sure you want to delete this student? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-block" {{ $student->enrollments->count() > 0 ? 'disabled' : '' }}>
                                        <i class="mdi mdi-delete mr-2"></i> Delete Student
                                    </button>
                                </form>
                                
                                @if($student->enrollments->count() > 0)
                                    <div class="admin-warning">
                                        <i class="mdi mdi-warning"></i> Cannot delete student with existing enrollments
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
        
                <!-- Learning Progress Card -->
                <div class="sidebar-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="mdi mdi-chart"></i>
                            Learning Progress
                        </h3>
                    </div>
                    <div class="card-content">
                        <div class="progress-items">
                            <div class="progress-item">
                                <div class="progress-header">
                                    <span class="progress-label">Course Completion</span>
                                    <span class="progress-value">{{ $stats['completed_courses'] }}/{{ $stats['total_enrollments'] }}</span>
                                </div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: {{ $stats['total_enrollments'] > 0 ? ($stats['completed_courses'] / $stats['total_enrollments']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            
                            <div class="progress-item">
                                <div class="progress-header">
                                    <span class="progress-label">Quiz Performance</span>
                                    <span class="progress-value">{{ number_format($stats['average_quiz_score'], 1) }}%</span>
                                </div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: {{ $stats['average_quiz_score'] }}%"></div>
                                </div>
                            </div>
                            
                            <div class="progress-item">
                                <div class="progress-header">
                                    <span class="progress-label">Total Attempts</span>
                                    <span class="progress-value">{{ $stats['total_quiz_attempts'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Page Header Styles */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 0 0 20px 20px;
    }
    
    .student-profile-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    
    .student-avatar-container {
        position: relative;
    }
    
    .student-avatar-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }
    
    .avatar-status-indicator {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 3px solid white;
    }
    
    .avatar-status-indicator.verified {
        background: #28a745;
    }
    
    .avatar-status-indicator.unverified {
        background: #ffc107;
    }
    
    .student-name {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: white;
    }
    
    .student-badges {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    
    .student-badges .badge {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
    }
    
    .student-meta {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        opacity: 0.9;
    }
    
    .page-header-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        align-items: center;
        height: 100%;
    }
    
    /* Statistics Overview */
    .statistics-overview {
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: none;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }
    
    .stat-card-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        flex-shrink: 0;
    }
    
    .stat-card-primary .stat-card-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .stat-card-success .stat-card-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .stat-card-info .stat-card-icon {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    .stat-card-warning .stat-card-icon {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        color: #2c3e50;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
        margin: 0;
        font-weight: 500;
    }
    
    /* Main Content */
    .main-content {
        margin-bottom: 2rem;
    }
    
    .content-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        overflow: hidden;
    }
    
    .section-header {
        background: #f8f9fa;
        padding: 1.5rem;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: between;
        align-items: center;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: #667eea;
    }
    
    .section-actions {
        margin-left: auto;
    }
    
    .section-content {
        padding: 1.5rem;
    }
    
    /* Table Styles */
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
        background: #f8f9fa;
        padding: 1rem 0.75rem;
    }
    
    .table td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
        border-top: 1px solid #e9ecef;
    }
    
    .course-title {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.25rem;
    }
    
    .course-level {
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .enrollment-date, .attempt-date, .course-name {
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .progress-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .progress {
        flex: 1;
        height: 8px;
        border-radius: 10px;
        background: #e9ecef;
    }
    
    .progress-bar {
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .progress-text {
        font-size: 0.875rem;
        font-weight: 600;
        color: #495057;
        min-width: 40px;
    }
    
    /* Status Badges */
    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-completed {
        background: #d4edda;
        color: #155724;
    }
    
    .status-progress {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .status-not-started {
        background: #f8d7da;
        color: #721c24;
    }
    
    .status-active {
        background: #d4edda;
        color: #155724;
    }
    
    .status-inactive {
        background: #f8d7da;
        color: #721c24;
    }
    
    .status-verified {
        background: #d4edda;
        color: #155724;
    }
    
    .status-unverified {
        background: #fff3cd;
        color: #856404;
    }
    
    /* Score Badges */
    .score-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .score-excellent {
        background: #d4edda;
        color: #155724;
    }
    
    .score-good {
        background: #fff3cd;
        color: #856404;
    }
    
    .score-poor {
        background: #f8d7da;
        color: #721c24;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    
    .empty-state-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    
    .empty-state-description {
        color: #6c757d;
        margin-bottom: 2rem;
    }
    
    /* Sidebar */
    .sidebar-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    
    .card-header {
        background: #f8f9fa;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .card-title i {
        color: #667eea;
    }
    
    .card-content {
        padding: 1.5rem;
    }
    
    /* Info Grid */
    .info-grid {
        display: grid;
        gap: 1rem;
    }
    
    .info-item {
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .info-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 0.25rem;
        display: block;
    }
    
    .info-value {
        font-size: 0.95rem;
        color: #2c3e50;
        font-weight: 500;
        margin: 0;
    }
    
    .level-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .level-beginner {
        background: #d4edda;
        color: #155724;
    }
    
    .level-intermediate {
        background: #fff3cd;
        color: #856404;
    }
    
    .level-advanced {
        background: #f8d7da;
        color: #721c24;
    }
    
    /* Admin Info */
    .admin-info {
        margin-bottom: 1.5rem;
    }
    
    .admin-info-item {
        margin-bottom: 1rem;
    }
    
    .admin-info-item:last-child {
        margin-bottom: 0;
    }
    
    .admin-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 0.25rem;
        display: block;
    }
    
    .admin-value {
        font-size: 0.95rem;
        color: #2c3e50;
        font-weight: 500;
        margin: 0;
    }
    
    .admin-actions {
        display: grid;
        gap: 0.75rem;
    }
    
    .admin-warning {
        font-size: 0.75rem;
        color: #856404;
        background: #fff3cd;
        padding: 0.5rem;
        border-radius: 8px;
        text-align: center;
    }
    
    /* Progress Items */
    .progress-items {
        display: grid;
        gap: 1.5rem;
    }
    
    .progress-item {
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .progress-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .progress-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    .progress-value {
        font-size: 0.875rem;
        color: #2c3e50;
        font-weight: 600;
    }
    
    .progress-bar-container {
        height: 8px;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-bar-container .progress-bar {
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        transition: width 0.3s ease;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .student-profile-header {
            flex-direction: column;
            text-align: center;
        }
        
        .student-meta {
            justify-content: center;
        }
        
        .page-header-actions {
            justify-content: center;
            margin-top: 1rem;
        }
        
        .stat-card {
            flex-direction: column;
            text-align: center;
        }
        
        .section-header {
            flex-direction: column;
            gap: 1rem;
        }
        
        .section-actions {
            margin-left: 0;
        }
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
