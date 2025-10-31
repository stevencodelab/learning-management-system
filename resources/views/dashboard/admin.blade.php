@extends('layouts.skydash')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Admin Dashboard</h3>
                <h6 class="font-weight-normal mb-0">Welcome {{ auth()->user()->name }}! Monitor your platform's performance and analytics.</h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                        <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i class="mdi mdi-calendar"></i> {{ now()->format('M d, Y') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                            <a class="dropdown-item" href="#" onclick="updateCharts('today')">Today</a>
                            <a class="dropdown-item" href="#" onclick="updateCharts('week')">Last 7 days</a>
                            <a class="dropdown-item" href="#" onclick="updateCharts('month')">Last 30 days</a>
                            <a class="dropdown-item" href="#" onclick="updateCharts('year')">This year</a>
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
                    <h4 class="card-title mb-0">Enrollment Trends</h4>
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
                <p class="font-weight-500 mb-3">Student enrollment patterns over time</p>
                <div class="chart-container">
                    <canvas id="enrollmentTrendsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Course Level Distribution -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Course Distribution</h4>
                <p class="font-weight-500 mb-3">Courses by difficulty level</p>
                <div class="chart-container-small">
                    <canvas id="courseLevelChart"></canvas>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Beginner</span>
                        <span class="font-weight-bold">{{ $coursesByLevel['beginner'] ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Intermediate</span>
                        <span class="font-weight-bold">{{ $coursesByLevel['intermediate'] ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Advanced</span>
                        <span class="font-weight-bold">{{ $coursesByLevel['advanced'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Metrics -->
<div class="row">
    <!-- Student Activity Heatmap -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Student Activity</h4>
                <p class="font-weight-500 mb-3">Daily login patterns</p>
                <div class="chart-container-small">
                    <canvas id="activityHeatmapChart"></canvas>
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

<!-- Recent Activity Table -->
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Recent Platform Activity</h4>
                <p class="font-weight-500 mb-0">Latest enrollments and course updates</p>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>User</th>
                                <th>Course</th>
                                <th>Type</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivity ?? [] as $activity)
                            <tr>
                                <td>{{ $activity['description'] ?? 'Course enrollment' }}</td>
                                <td>{{ $activity['user_name'] ?? 'Student' }}</td>
                                <td>{{ $activity['course_title'] ?? 'Course Title' }}</td>
                                <td>
                                    <label class="badge {{ $activity['type'] === 'enrollment' ? 'badge-success' : 'badge-info' }}">
                                        {{ ucfirst($activity['type'] ?? 'enrollment') }}
                                    </label>
                                </td>
                                <td>{{ $activity['created_at'] ?? now()->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No recent activity</td>
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

    /* Responsive adjustments */
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
</style>
@endpush

@push('scripts')
<script>
    // Chart.js configuration
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#6c757d';
    
    let enrollmentTrendsChart, courseLevelChart, activityHeatmapChart, completionRatesChart;
    
    // Initialize all charts
    document.addEventListener('DOMContentLoaded', function() {
        initEnrollmentTrendsChart();
        initCourseLevelChart();
        initActivityHeatmapChart();
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
                    label: 'Completed Courses',
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
    
    // Course Level Distribution Chart
    function initCourseLevelChart() {
        const ctx = document.getElementById('courseLevelChart').getContext('2d');
        courseLevelChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Beginner', 'Intermediate', 'Advanced'],
                datasets: [{
                    data: [{{ $coursesByLevel['beginner'] ?? 0 }}, {{ $coursesByLevel['intermediate'] ?? 0 }}, {{ $coursesByLevel['advanced'] ?? 0 }}],
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(118, 75, 162, 0.8)',
                        'rgba(255, 99, 132, 0.8)'
                    ],
                    borderColor: [
                        '#667eea',
                        '#764ba2',
                        '#ff6384'
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
    
    // Activity Heatmap Chart
    function initActivityHeatmapChart() {
        const ctx = document.getElementById('activityHeatmapChart').getContext('2d');
        activityHeatmapChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Active Users',
                    data: [120, 150, 180, 200, 160, 80, 60],
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
    
    function updateCharts(period) {
        // Update all charts based on selected period
        console.log('Updating charts for period:', period);
        // Add your data fetching logic here
    }
</script>
@endpush
@endsection
