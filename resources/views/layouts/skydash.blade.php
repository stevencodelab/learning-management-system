<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $title ?? 'Dashboard' }} - Smart Edu LMS</title>
    
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('skydash/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('skydash/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('skydash/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('skydash/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('skydash/vendors/mdi/css/materialdesignicons.min.css') }}">
    
    <!-- Plugin css for this page -->
    <!-- DataTables CSS removed - not needed for current dashboard -->
    
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('skydash/css/style.css') }}">
    
    <link rel="shortcut icon" href="{{ asset('images/landing/logo.png') }}" />
    
    <style>
        /* Mobile Sidebar Hide */
        @media (max-width: 991px) {
            .sidebar {
                display: none !important;
            }
            
            .main-panel {
                width: 100% !important;
            }
        }
        
        /* Bottom Navigation - Glassmorphism Style */
        .bottom-nav {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 40px);
            max-width: 500px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.18);
            z-index: 1000;
            display: none;
            padding: 10px 8px;
            transition: all 0.3s ease;
        }
        
        @media (max-width: 991px) {
            .bottom-nav {
                display: flex;
            }
            
            .content-wrapper {
                padding-bottom: 100px;
            }
        }
        
        .bottom-nav-items {
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 100%;
            gap: 4px;
        }
        
        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            flex: 1;
            padding: 8px 12px;
            border-radius: 16px;
            gap: 4px;
        }
        
        .bottom-nav-item i {
            font-size: 20px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }
        
        .bottom-nav-item span {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.2px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }
        
        /* Active State - Glass effect with glow */
        .bottom-nav-item.active {
            color: #667eea;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3),
                        0 0 0 2px rgba(255, 255, 255, 0.5);
        }
        
        .bottom-nav-item.active i {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transform: scale(1.1);
        }
        
        .bottom-nav-item.active span {
            color: #667eea;
            font-weight: 700;
        }
        
        /* Hover State */
        .bottom-nav-item:hover:not(.active) {
            color: rgba(255, 255, 255, 0.95);
            background: rgba(255, 255, 255, 0.15);
        }
        
        .bottom-nav-item:hover:not(.active) i {
            transform: scale(1.05);
        }
        
        /* Active indicator dot */
        .bottom-nav-item.active::before {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(102, 126, 234, 0.6);
        }
        
        /* Responsive adjustments */
        @media (max-width: 576px) {
            .bottom-nav {
                width: calc(100% - 30px);
                bottom: 15px;
            }
            
            .bottom-nav-item span {
                font-size: 9px;
            }
            
            .bottom-nav-item i {
                font-size: 18px;
            }
            
            .bottom-nav-item {
                padding: 8px 8px;
            }
        }
        
        @media (max-width: 400px) {
            .bottom-nav-item span {
                font-size: 8px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-scroller">
        <!-- Navbar -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <a class="navbar-brand brand-logo me-5" href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/landing/logo.png') }}" class="me-2" alt="logo" style="max-height: 40px;" />
                </a>
                <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/landing/logo.png') }}" alt="logo" style="max-height: 30px;" />
                </a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler align-self-center d-none d-lg-block" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
                <ul class="navbar-nav mr-lg-2">
                    <li class="nav-item nav-search d-none d-lg-block">
                        <div class="input-group">
                            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                <span class="input-group-text" id="search">
                                    <i class="icon-search"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="navbar-search-input" placeholder="Search" aria-label="search" aria-describedby="search">
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                            <i class="icon-bell mx-0"></i>
                            <span class="count"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                            <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                        </div>
                    </li>
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                            <div class="profile-image" style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="ti-settings text-primary"></i> Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left;">
                                    <i class="ti-power-off text-primary"></i> Logout
                                </button>
                            </form>
                        </div>
                    </li>
                    <li class="nav-item nav-settings d-none d-lg-flex">
                        <a class="nav-link" href="#">
                            <i class="icon-ellipsis"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- page-body-wrapper -->
        <div class="container-fluid page-body-wrapper">
            <!-- Sidebar -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="icon-grid menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                
                @if(auth()->user()->hasRole('admin'))
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#courses-menu" aria-expanded="false" aria-controls="courses-menu">
                            <i class="icon-book menu-icon"></i>
                            <span class="menu-title">Courses</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="courses-menu">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"><a class="nav-link {{ request()->routeIs('courses.index') ? 'active' : '' }}" href="{{ route('courses.index') }}">All Courses</a></li>
                                <li class="nav-item"><a class="nav-link {{ request()->routeIs('courses.create') ? 'active' : '' }}" href="{{ route('courses.create') }}">Create Course</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('enrollments.*') ? 'active' : '' }}" href="{{ route('enrollments.index') }}">
                            <i class="ti-id-badge menu-icon"></i>
                            <span class="menu-title">Enrollments</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('quizzes.*') ? 'active' : '' }}" href="{{ route('quizzes.index') }}">
                            <i class="icon-book menu-icon"></i>
                            <span class="menu-title">Quizzes</span>
                        </a>
                    </li>
                @endif
                
                @if(auth()->user()->hasRole('instructor'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" href="{{ route('courses.index') }}">
                            <i class="icon-book menu-icon"></i>
                            <span class="menu-title">My Courses</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="icon-users menu-icon"></i>
                            <span class="menu-title">Students</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('quizzes.*') ? 'active' : '' }}" href="{{ route('quizzes.index') }}">
                            <i class="icon-note menu-icon"></i>
                            <span class="menu-title">Quizzes</span>
                        </a>
                    </li>
                @endif
                
                @if(auth()->user()->hasRole('student'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" href="{{ route('courses.index') }}">
                            <i class="icon-book menu-icon"></i>
                            <span class="menu-title">Browse Courses</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('enrollments.index') }}">
                            <i class="ti-id-badge menu-icon"></i>
                            <span class="menu-title">My Enrollments</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('quizzes.*') ? 'active' : '' }}" href="{{ route('quizzes.index') }}">
                            <i class="icon-book menu-icon"></i>
                            <span class="menu-title">Quizzes</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        
        <!-- Main Panel -->
        <div class="main-panel">
            <div class="content-wrapper">
                @yield('content')
            </div>
            <!-- Footer -->
            <footer class="footer">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © <a href="https://smartedu.com" target="_blank">Smart Edu LMS</a> 2024</span>
                </div>
            </footer>
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->

<!-- plugins:js -->
    <script src="{{ asset('skydash/vendors/js/vendor.bundle.base.js') }}"></script>
    
    <!-- Plugin js for this page -->
    <script src="{{ asset('skydash/vendors/chart.js/chart.umd.js') }}"></script>
    
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('skydash/js/template.js') }}"></script>
    
    @stack('scripts')
    
    <!-- Bottom Navigation for Mobile -->
    <nav class="bottom-nav">
        <div class="bottom-nav-items">
            <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="icon-grid"></i>
                <span>Dashboard</span>
            </a>
            
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('courses.index') }}" class="bottom-nav-item {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                    <i class="icon-book"></i>
                    <span>Courses</span>
                </a>
                <a href="{{ route('enrollments.index') }}" class="bottom-nav-item {{ request()->routeIs('enrollments.*') ? 'active' : '' }}">
                    <i class="ti-id-badge"></i>
                    <span>Enrollments</span>
                </a>
                <a href="{{ route('quizzes.index') }}" class="bottom-nav-item {{ request()->routeIs('quizzes.*') ? 'active' : '' }}">
                    <i class="icon-book"></i>
                    <span>Quizzes</span>
                </a>
            @endif
            
            @if(auth()->user()->hasRole('instructor'))
                <a href="{{ route('courses.index') }}" class="bottom-nav-item {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                    <i class="icon-book"></i>
                    <span>Courses</span>
                </a>
                <a href="{{ route('quizzes.index') }}" class="bottom-nav-item {{ request()->routeIs('quizzes.*') ? 'active' : '' }}">
                    <i class="icon-book"></i>
                    <span>Quizzes</span>
                </a>
                <a href="#" class="bottom-nav-item">
                    <i class="icon-users"></i>
                    <span>Students</span>
                </a>
            @endif
            
            @if(auth()->user()->hasRole('student'))
                <a href="{{ route('courses.index') }}" class="bottom-nav-item {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                    <i class="icon-book"></i>
                    <span>Browse</span>
                </a>
                <a href="{{ route('enrollments.index') }}" class="bottom-nav-item {{ request()->routeIs('enrollments.*') ? 'active' : '' }}">
                    <i class="icon-docs"></i>
                    <span>My Courses</span>
                </a>
                <a href="{{ route('quizzes.index') }}" class="bottom-nav-item {{ request()->routeIs('quizzes.*') ? 'active' : '' }}">
                    <i class="icon-note"></i>
                    <span>Quizzes</span>
                </a>
            @endif
            
            <a href="{{ route('profile.edit') }}" class="bottom-nav-item">
                <i class="ti-settings"></i>
                <span>Profile</span>
            </a>
        </div>
    </nav>
</body>
</html>

