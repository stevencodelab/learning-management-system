@extends('layouts.skydash')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Welcome {{ auth()->user()->name }}</h3>
                <h6 class="font-weight-normal mb-0">Manage your courses and track your students' progress. You have <span class="text-primary">active enrollments!</span></h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                        <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i class="mdi mdi-calendar"></i> {{ now()->format('M d, Y') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                            <a class="dropdown-item" href="#">Today</a>
                            <a class="dropdown-item" href="#">Last 7 days</a>
                            <a class="dropdown-item" href="#">Last 30 days</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card tale-bg">
            <div class="card-people mt-auto">
                <img src="{{ asset('skydash/images/dashboard/people.svg') }}" alt="people">
                <div class="weather-info">
                    <div class="d-flex">
                        <div>
                            <h2 class="mb-0 font-weight-normal"><i class="mdi mdi-school me-2"></i>{{ $totalStudents ?? 0 }}</h2>
                        </div>
                        <div class="ms-2">
                            <h4 class="location font-weight-normal">Total Students</h4>
                            <h6 class="font-weight-normal">Active Learners</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin transparent">
        <div class="row">
            <div class="col-md-6 mb-4 stretch-card transparent">
                <div class="card card-tale interactive-card">
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-4">Total Courses</p>
                                <p class="fs-30 mb-2">{{ $totalCourses ?? 0 }}</p>
                                <p>{{ $publishedCourses ?? 0 }} published</p>
                            </div>
                            <div class="card-icon-circle">
                                <i class="mdi mdi-school"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4 stretch-card transparent">
                <div class="card card-dark-blue interactive-card">
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-4">Total Enrollments</p>
                                <p class="fs-30 mb-2">{{ $totalEnrollments ?? 0 }}</p>
                                <p>{{ $activeEnrollments ?? 0 }} active</p>
                            </div>
                            <div class="card-icon-circle">
                                <i class="mdi mdi-account-group"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4 stretch-card transparent">
                <div class="card card-light-blue interactive-card">
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-4">Total Modules</p>
                                <p class="fs-30 mb-2">{{ $totalModules ?? 0 }}</p>
                                <p>Across courses</p>
                            </div>
                            <div class="card-icon-circle">
                                <i class="mdi mdi-file-document"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4 stretch-card transparent">
                <div class="card card-light-danger interactive-card">
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-4">Total Quizzes</p>
                                <p class="fs-30 mb-2">{{ $totalQuizzes ?? 0 }}</p>
                                <p>Available</p>
                            </div>
                            <div class="card-icon-circle">
                                <i class="mdi mdi-clipboard-text"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Charts Section -->
<div class="row">
    <!-- Enrollment Trends Chart -->
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Student Enrollment Trends</h4>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="enrollmentChartDropdown" data-bs-toggle="dropdown">
                            <i class="mdi mdi-calendar"></i> Last 12 months
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" onclick="updateEnrollmentChart('6months')">Last 6 months</a>
                            <a class="dropdown-item" href="#" onclick="updateEnrollmentChart('12months')">Last 12 months</a>
                            <a class="dropdown-item" href="#" onclick="updateEnrollmentChart('year')">This year</a>
                        </div>
                    </div>
                </div>
                <p class="font-weight-500 mb-3">Track your course enrollment patterns and student engagement</p>
                <div class="chart-container">
                    <canvas id="enrollmentTrendsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Course Performance -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Course Performance</h4>
                <p class="font-weight-500 mb-3">Your courses by completion rate</p>
                <div class="chart-container-small">
                    <canvas id="coursePerformanceChart"></canvas>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">High Performance</span>
                        <span class="font-weight-bold text-success">{{ $highPerformanceCourses ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Medium Performance</span>
                        <span class="font-weight-bold text-warning">{{ $mediumPerformanceCourses ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Needs Attention</span>
                        <span class="font-weight-bold text-danger">{{ $lowPerformanceCourses ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Student Engagement Metrics -->
<div class="row">
    <!-- Student Activity -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Student Activity</h4>
                <p class="font-weight-500 mb-3">Weekly student engagement</p>
                <div class="chart-container-small">
                    <canvas id="studentActivityChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Course Completion Rates -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Course Completion</h4>
                <p class="font-weight-500 mb-3">Completion rates by course</p>
                <div class="chart-container-small">
                    <canvas id="completionRatesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Courses -->
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title">Recent Courses</p>
                <p class="font-weight-500 mb-0">Your latest course updates</p>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Level</th>
                                <th>Modules</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCourses ?? [] as $course)
                            <tr>
                                <td>{{ $course->title ?? 'Course Title' }}</td>
                                <td>{{ $course->level ?? 'N/A' }}</td>
                                <td>{{ $course->modules->count() ?? 0 }}</td>
                                <td>
                                    <label class="badge {{ $course->is_published ? 'badge-success' : 'badge-warning' }}">
                                        {{ $course->is_published ? 'Published' : 'Draft' }}
                                    </label>
                                </td>
                                <td>{{ $course->created_at ?? now()->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No courses yet. <a href="{{ route('courses.create') }}">Create your first course</a></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Interactive Cards Hover Effect */
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

    /* Icon Circle Styling */
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
    }

    .interactive-card:hover .fs-30 {
        transform: scale(1.05);
        font-weight: 700;
    }

    /* Chart container styling */
    .card canvas {
        border-radius: 8px;
        max-height: 400px;
    }
    
    /* Responsive chart containers */
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    .chart-container-small {
        position: relative;
        height: 250px;
        width: 100%;
    }
    
    /* Mobile chart adjustments */
    @media (max-width: 768px) {
        .chart-container {
            height: 250px;
        }
        
        .chart-container-small {
            height: 200px;
        }
        
        .card canvas {
            max-height: 250px;
        }
        
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
    
    @media (max-width: 576px) {
        .chart-container {
            height: 200px;
        }
        
        .chart-container-small {
            height: 180px;
        }
        
        .card canvas {
            max-height: 200px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Chart.js configuration
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#6c757d';
    
    let enrollmentTrendsChart, coursePerformanceChart, studentActivityChart, completionRatesChart;
    
    // Initialize all charts
    document.addEventListener('DOMContentLoaded', function() {
        initEnrollmentTrendsChart();
        initCoursePerformanceChart();
        initStudentActivityChart();
        initCompletionRatesChart();
    });
    
    // Enrollment Trends Chart
    function initEnrollmentTrendsChart() {
        const ctx = document.getElementById('enrollmentTrendsChart').getContext('2d');
        enrollmentTrendsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'New Enrollments',
                    data: [12, 19, 15, 25, 22, 30, 28, 35, 32, 40, 38, 45],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }, {
                    label: 'Course Completions',
                    data: [8, 15, 12, 20, 18, 25, 22, 28, 26, 32, 30, 35],
                    borderColor: '#764ba2',
                    backgroundColor: 'rgba(118, 75, 162, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#764ba2',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#667eea',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6c757d'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            color: '#6c757d'
                        }
                    }
                },
                elements: {
                    line: {
                        borderWidth: 3
                    }
                }
            }
        });
    }
    
    // Course Performance Chart
    function initCoursePerformanceChart() {
        const ctx = document.getElementById('coursePerformanceChart').getContext('2d');
        coursePerformanceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['High Performance', 'Medium Performance', 'Needs Attention'],
                datasets: [{
                    data: [{{ $highPerformanceCourses ?? 0 }}, {{ $mediumPerformanceCourses ?? 0 }}, {{ $lowPerformanceCourses ?? 0 }}],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        '#28a745',
                        '#ffc107',
                        '#dc3545'
                    ],
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                },
                cutout: '60%'
            }
        });
    }
    
    // Student Activity Chart
    function initStudentActivityChart() {
        const ctx = document.getElementById('studentActivityChart').getContext('2d');
        studentActivityChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Active Students',
                    data: [45, 52, 48, 61, 55, 25, 18],
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(118, 75, 162, 0.6)',
                        'rgba(118, 75, 162, 0.6)'
                    ],
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6c757d'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            color: '#6c757d'
                        }
                    }
                }
            }
        });
    }
    
    // Course Completion Rates Chart
    function initCompletionRatesChart() {
        const ctx = document.getElementById('completionRatesChart').getContext('2d');
        completionRatesChart = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Course A', 'Course B', 'Course C', 'Course D', 'Course E'],
                datasets: [{
                    label: 'Completion Rate (%)',
                    data: [85, 92, 78, 88, 95],
                    backgroundColor: 'rgba(102, 126, 234, 0.2)',
                    borderColor: '#667eea',
                    borderWidth: 2,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 20,
                            color: '#6c757d'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        pointLabels: {
                            color: '#6c757d',
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Chart update functions
    function updateEnrollmentChart(period) {
        // Simulate data update based on period
        const periods = {
            '6months': ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            '12months': ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'year': ['Q1', 'Q2', 'Q3', 'Q4']
        };
        
        enrollmentTrendsChart.data.labels = periods[period] || periods['12months'];
        enrollmentTrendsChart.update();
    }
</script>
@endpush
@endsection

