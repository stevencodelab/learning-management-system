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

<!-- Charts -->
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title">Enrollment Overview</p>
                <p class="font-weight-500">The total number of enrollments within the date range</p>
                <div id="sales-chart-legend" class="chartjs-legend mt-4 mb-2"></div>
                <canvas id="sales-chart"></canvas>
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
    // Sales Chart
    if ($("#sales-chart").length) {
        var ctx = document.getElementById('sales-chart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Enrollments',
                    data: [12, 19, 15, 25, 22, 30, 28, 35, 32, 40, 38, 45],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection

