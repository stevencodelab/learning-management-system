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
    
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('skydash/css/style.css') }}">
    
    <link rel="shortcut icon" href="{{ asset('images/landing/logo.png') }}" />
    
    <style>
        @media (max-width: 991px) {
            .sidebar {
                display: none !important;
            }
            
            .main-panel {
                width: 100% !important;
            }
        }
        
        .bottom-nav {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 40px);
            max-width: 500px;
            background: linear-gradient(135deg, 
                rgba(102, 126, 234, 0.4) 0%, 
                rgba(118, 75, 162, 0.35) 50%, 
                rgba(102, 126, 234, 0.4) 100%);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            box-shadow: 
                0 8px 32px 0 rgba(31, 38, 135, 0.6),
                0 0 0 1px rgba(255, 255, 255, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.5),
                0 0 0 2px rgba(102, 126, 234, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            z-index: 1000;
            display: none;
            padding: 12px 10px calc(12px + env(safe-area-inset-bottom));
            border-radius: 24px;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: auto;
            transition: transform 0.42s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.32s ease;
            will-change: transform, opacity;
            contain: layout paint;
        }

        /* Hide/show animation (scroll-aware) */
        .bottom-nav.hidden {
            transform: translate(-50%, 120%);
            opacity: 0;
            pointer-events: none;
        }
        
        /* Scrolling state - Enhanced background */
        .bottom-nav.scrolling {
            background: linear-gradient(135deg, 
                rgba(102, 126, 234, 0.6) 0%, 
                rgba(118, 75, 162, 0.55) 50%, 
                rgba(102, 126, 234, 0.6) 100%);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            box-shadow: 
                0 12px 40px 0 rgba(31, 38, 135, 0.8),
                0 0 0 1px rgba(255, 255, 255, 0.6),
                inset 0 1px 0 rgba(255, 255, 255, 0.7),
                0 0 0 2px rgba(102, 126, 234, 0.4),
                0 0 20px rgba(102, 126, 234, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.5);;
            transform: translateX(-50%) scale(1.02);
        }
        
        /* Touch interaction state */
        .bottom-nav.touching {
            background: linear-gradient(135deg, 
                rgba(102, 126, 234, 0.7) 0%, 
                rgba(118, 75, 162, 0.65) 50%, 
                rgba(102, 126, 234, 0.7) 100%);
            backdrop-filter: blur(35px);
            -webkit-backdrop-filter: blur(35px);
            box-shadow: 
                0 16px 50px 0 rgba(31, 38, 135, 0.9),
                0 0 0 1px rgba(255, 255, 255, 0.8),
                inset 0 1px 0 rgba(255, 255, 255, 0.9),
                0 0 0 2px rgba(102, 126, 234, 0.6),
                0 0 30px rgba(102, 126, 234, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.7);
            transform: translateX(-50%) scale(1.05);
        }
        
        /* In-view state for visible items */
        .bottom-nav-item.in-view {
            opacity: 1;
        }
        
        .bottom-nav-item:not(.in-view) {
            opacity: 0.7;
            transform: scale(0.98);
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
            flex-direction: row;
            gap: 8px;
            align-items: center;
            width: max-content;
            min-width: 100%;
            transition: transform 0.3s ease;
            padding: 0 10px;
            scroll-snap-type: x proximity;
            position: relative;
        }
        
        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1), 
                        opacity 0.3s ease, 
                        transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            padding: 10px 12px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            min-width: 70px;
            min-height: 60px;
            flex-shrink: 0;
            gap: 4px;
            scroll-snap-align: center;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }

        /* Focus ring for accessibility */
        .bottom-nav-item:focus-visible {
            outline: 2px solid rgba(102, 126, 234, 0.9);
            outline-offset: 2px;
        }

        /* Subtle ripple feedback */
        .bottom-nav-item::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(rgba(255,255,255,0.35), rgba(255,255,255,0) 70%);
            transform: translate(-50%, -50%);
            opacity: 0;
            transition: width 0.35s ease, height 0.35s ease, opacity 0.45s ease;
            pointer-events: none;
            border-radius: 50%;
        }

        .bottom-nav-item:active::after {
            width: 160px;
            height: 160px;
            opacity: 1;
        }
        
        .bottom-nav-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }
        
        /* Enhanced menu items during scrolling */
        .bottom-nav.scrolling .bottom-nav-item {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.15);
        }
        
        .bottom-nav.touching .bottom-nav-item {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }
        
        .bottom-nav-item i {
            font-size: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 2;
        }
        
        .bottom-nav-item span {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.2px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 2;
            text-align: center;
        }
        
        /* Active State - Enhanced glass effect */
        .bottom-nav-item.active {
            color: #667eea;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 
                0 6px 25px rgba(102, 126, 234, 0.4),
                0 0 0 2px rgba(255, 255, 255, 0.8),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(102, 126, 234, 0.3);
            transform: translateY(-2px);
        }
        
        .bottom-nav-item.active i {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transform: scale(1.15);
        }
        
        .bottom-nav-item.active span {
            color: #667eea;
            font-weight: 700;
            transform: scale(1.05);
        }
        
        /* Hover State */
        .bottom-nav-item:hover:not(.active) {
            color: rgba(255, 255, 255, 0.95);
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }
        
        .bottom-nav-item:hover:not(.active) i {
            transform: scale(1.1);
        }
        
        .bottom-nav-item:hover:not(.active) span {
            transform: scale(1.02);
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

        /* Gliding active indicator (bar under the active item) */
        .bottom-nav-items .active-indicator {
            position: absolute;
            bottom: 4px;
            height: 3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 3px;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.45);
            transition: transform 0.55s cubic-bezier(0.34, 1.56, 0.64, 1), 
                        width 0.45s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.3s ease;
            will-change: transform, width, opacity;
            pointer-events: none;
            opacity: 0;
        }
        
        .bottom-nav-items .active-indicator.visible {
            opacity: 1;
            animation: indicatorPulse 2s ease-in-out infinite;
        }
        
        @keyframes indicatorPulse {
            0%, 100% {
                box-shadow: 0 2px 10px rgba(102, 126, 234, 0.45);
            }
            50% {
                box-shadow: 0 2px 15px rgba(102, 126, 234, 0.7), 0 0 8px rgba(102, 126, 234, 0.4);
            }
        }
        
        /* Custom horizontal scrollbar */
        .bottom-nav::-webkit-scrollbar {
            height: 4px;
        }
        
        .bottom-nav::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
        }
        
        .bottom-nav::-webkit-scrollbar-thumb {
            background: rgba(102, 126, 234, 0.6);
            border-radius: 2px;
        }
        
        .bottom-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(102, 126, 234, 0.8);
        }
        
        /* Hide scrollbar on mobile */
        @media (max-width: 768px) {
            .bottom-nav::-webkit-scrollbar {
                display: none;
            }
        }
        
        /* Enhanced visibility for white backgrounds */
        .bottom-nav::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(0, 0, 0, 0.15) 0%, 
                rgba(0, 0, 0, 0.1) 50%, 
                rgba(0, 0, 0, 0.15) 100%);
            border-radius: 24px;
            pointer-events: none;
            z-index: -1;
        }
        
        .bottom-nav.expanded::before {
            background: linear-gradient(135deg, 
                rgba(0, 0, 0, 0.2) 0%, 
                rgba(0, 0, 0, 0.15) 50%, 
                rgba(0, 0, 0, 0.2) 100%);
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .bottom-nav {
                background: linear-gradient(135deg, 
                    rgba(0, 0, 0, 0.4) 0%, 
                    rgba(0, 0, 0, 0.3) 50%, 
                    rgba(0, 0, 0, 0.4) 100%);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .bottom-nav.expanded {
                background: linear-gradient(135deg, 
                    rgba(0, 0, 0, 0.5) 0%, 
                    rgba(0, 0, 0, 0.4) 50%, 
                    rgba(0, 0, 0, 0.5) 100%);
            }
            
            .bottom-nav::before {
                background: linear-gradient(135deg, 
                    rgba(255, 255, 255, 0.05) 0%, 
                    rgba(255, 255, 255, 0.02) 50%, 
                    rgba(255, 255, 255, 0.05) 100%);
            }
        }

        /* Respect reduced motion preferences */
        @media (prefers-reduced-motion: reduce) {
            .bottom-nav, .bottom-nav-items, .bottom-nav-item, .bottom-nav .active-indicator {
                transition: none !important;
            }
            .bottom-nav {
                scroll-behavior: auto;
            }
            .bottom-nav-item::after { display: none; }
            .active-indicator {
                animation: none !important;
            }
        }
        
        /* Active click feedback */
        .bottom-nav-item:active {
            transform: scale(0.92) translateY(-1px);
            transition: transform 0.1s ease;
        }
        
        .bottom-nav-item.active:active {
            transform: scale(0.95) translateY(-2px);
        }
        
        /* Responsive adjustments */
        @media (max-width: 576px) {
            .bottom-nav {
                width: calc(100% - 30px);
                bottom: max(15px, env(safe-area-inset-bottom));
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
        
        /* Avatar Styling */
        .navbar-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .navbar-avatar:hover {
            border-color: rgba(255, 255, 255, 0.5);
            transform: scale(1.05);
        }
        
        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Brand Logo Text Styling */
        .brand-logo-text {
            font-size: 20px;
            font-weight: 700;
            color: #667eea;
            margin-left: 8px;
            letter-spacing: 0.5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            white-space: nowrap;
            transition: all 0.3s ease;
        }
        
        .brand-logo:hover .brand-logo-text {
            transform: scale(1.05);
        }
        
        .brand-logo-mini .brand-logo-text {
            display: none;
        }
        
        @media (max-width: 991px) {
            .brand-logo-text {
                font-size: 16px;
                margin-left: 6px;
            }
            
            .brand-logo {
                margin-right: 1rem !important;
            }
        }
        
        @media (max-width: 768px) {
            .brand-logo-text {
                font-size: 14px;
                margin-left: 4px;
            }
        }
        
        @media (max-width: 576px) {
            .brand-logo-text {
                font-size: 12px;
                margin-left: 3px;
            }
            
            .brand-logo img {
                max-height: 32px !important;
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
                <a class="navbar-brand brand-logo me-5 d-flex align-items-center" href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/landing/logo.png') }}" class="me-2" alt="logo" style="max-height: 40px;" />
                    <span class="brand-logo-text">SMART EDU</span>
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
                            <div class="profile-image">
                                <img src="{{ auth()->user()->avatar_url }}" 
                                     alt="Profile" 
                                     class="navbar-avatar">
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
                        <i class="mdi mdi-view-dashboard menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                
                @if(auth()->user()->hasRole('admin'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(['courses.index', 'courses.create', 'courses.edit', 'courses.show']) && !request()->routeIs('testimonials.*') && !request()->routeIs('quizzes.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#courses-menu" aria-expanded="{{ request()->routeIs(['courses.index', 'courses.create', 'courses.edit', 'courses.show']) && !request()->routeIs('testimonials.*') && !request()->routeIs('quizzes.*') ? 'true' : 'false' }}" aria-controls="courses-menu">
                            <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                            <span class="menu-title">Courses</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs(['courses.index', 'courses.create', 'courses.edit', 'courses.show']) && !request()->routeIs('testimonials.*') && !request()->routeIs('quizzes.*') ? 'show' : '' }}" id="courses-menu">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"><a class="nav-link {{ request()->routeIs('courses.index') ? 'active' : '' }}" href="{{ route('courses.index') }}">All Courses</a></li>
                                <li class="nav-item"><a class="nav-link {{ request()->routeIs('courses.create') ? 'active' : '' }}" href="{{ route('courses.create') }}">Create Course</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(['modules.index', 'modules.create', 'modules.edit', 'modules.show']) ? 'active' : '' }}" href="{{ route('modules.index') }}">
                            <i class="mdi mdi-folder menu-icon"></i>
                            <span class="menu-title">Modules</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(['lessons.index', 'lessons.create', 'lessons.edit', 'lessons.show']) ? 'active' : '' }}" href="{{ route('lessons.index') }}">
                            <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                            <span class="menu-title">Lessons</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(['enrollments.index', 'enrollments.create', 'enrollments.edit', 'enrollments.show']) ? 'active' : '' }}" href="{{ route('enrollments.index') }}">
                            <i class="mdi mdi-account-group menu-icon"></i>
                            <span class="menu-title">Enrollments</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(['students.index', 'students.create', 'students.edit', 'students.show']) ? 'active' : '' }}" href="{{ route('students.index') }}">
                            <i class="mdi mdi-school menu-icon"></i>
                            <span class="menu-title">Students</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->routeIs(['quizzes.index', 'quizzes.create', 'quizzes.edit', 'quizzes.show', 'quiz.questions.*', 'quiz.taking.*']) && !request()->routeIs('testimonials.*') && !request()->routeIs('courses.*')) ? 'active' : '' }}" href="{{ route('quizzes.index') }}">
                            <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                            <span class="menu-title">Quizzes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(['testimonials.manage', 'testimonials.create', 'testimonials.edit', 'testimonials.approve', 'testimonials.reject', 'testimonials.toggle-featured']) ? 'active' : '' }}" href="{{ route('testimonials.manage') }}">
                            <i class="mdi mdi-comment-multiple-outline menu-icon"></i>
                            <span class="menu-title">Testimonials</span>
                        </a>
                    </li>
                @endif
                
                @if(auth()->user()->hasRole('instructor'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(['courses.index', 'courses.create', 'courses.edit', 'courses.show']) ? 'active' : '' }}" href="{{ route('courses.index') }}">
                            <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                            <span class="menu-title">My Courses</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(['modules.index', 'modules.create', 'modules.edit', 'modules.show']) ? 'active' : '' }}" href="{{ route('modules.index') }}">
                            <i class="mdi mdi-folder menu-icon"></i>
                            <span class="menu-title">Modules</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(['lessons.index', 'lessons.create', 'lessons.edit', 'lessons.show']) ? 'active' : '' }}" href="{{ route('lessons.index') }}">
                            <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                            <span class="menu-title">Lessons</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(['students.index', 'students.create', 'students.edit', 'students.show']) ? 'active' : '' }}" href="{{ route('students.index') }}">
                            <i class="mdi mdi-school menu-icon"></i>
                            <span class="menu-title">Students</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(['quizzes.index', 'quizzes.create', 'quizzes.edit', 'quizzes.show']) ? 'active' : '' }}" href="{{ route('quizzes.index') }}">
                            <i class="mdi mdi-help-circle menu-icon"></i>
                            <span class="menu-title">Quizzes</span>
                        </a>
                    </li>
                @endif
                
                @if(auth()->user()->hasRole('student'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(['courses.index', 'courses.create', 'courses.edit', 'courses.show']) ? 'active' : '' }}" href="{{ route('courses.index') }}">
                            <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                            <span class="menu-title">Browse Courses</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('enrollments.index') }}">
                            <i class="mdi mdi-account-group menu-icon"></i>
                            <span class="menu-title">My Enrollments</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(['quizzes.index', 'quizzes.create', 'quizzes.edit', 'quizzes.show']) ? 'active' : '' }}" href="{{ route('quizzes.index') }}">
                            <i class="mdi mdi-help-circle menu-icon"></i>
                            <span class="menu-title">Quizzes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(['testimonials.index', 'testimonials.create', 'testimonials.edit']) ? 'active' : '' }}" href="{{ route('testimonials.create') }}">
                            <i class="mdi mdi-comment-multiple-outline menu-icon"></i>
                            <span class="menu-title">Testimonials</span>
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
    
    <!-- Chart.js responsive plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-responsive"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('skydash/js/template.js') }}"></script>
    
    @stack('scripts')
    
    <!-- SweetAlert2 Global Scripts -->
    <script>
        // Handle Laravel session messages with SweetAlert
        @if(session('success'))
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 1500
            });
        @endif

        @if(session('error'))
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        @if(session('info'))
            Swal.fire({
                position: "top-end",
                icon: "info",
                title: "{{ session('info') }}",
                showConfirmButton: false,
                timer: 1500
            });
        @endif

        // Global function for delete confirmation
        function confirmDelete(event, message = "You won't be able to revert this!") {
            event.preventDefault();
            const form = event.target.closest('form');
            
            Swal.fire({
                title: "Are you sure?",
                text: message,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // If form exists, submit it
                    if (form) {
                        form.submit();
                    } else {
                        // Otherwise, redirect to the href
                        window.location.href = event.target.href || event.target.closest('a').href;
                    }
                }
            });
        }

        // Global function for save confirmation
        function confirmSave(form) {
            Swal.fire({
                title: "Do you want to save the changes?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Save",
                denyButtonText: `Don't save`
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                } else if (result.isDenied) {
                    Swal.fire("Changes are not saved", "", "info");
                }
            });
        }
    </script>
    
    <script>
        // Bottom Navigation Horizontal Slide Functionality - Enhanced
        let bottomNav = null;
        let isScrolling = false;
        let lastScrollY = 0;
        let initialViewportH = window.innerHeight;
        let keyboardLikelyOpen = false;
        let scrollHideRaf = null;
        let showDebounceTimer = null;
        let indicatorEl = null;
        
        function initSlideNavigation() {
            bottomNav = document.getElementById('bottomNav');
            
            if (!bottomNav) return;
            
            // Enable smooth native scrolling
            bottomNav.style.scrollBehavior = 'auto'; // Use 'auto' for native smoothness
            bottomNav.style.overflowX = 'auto';
            bottomNav.style.overflowY = 'hidden';
            bottomNav.style.webkitOverflowScrolling = 'touch';
            
            // Track scrolling state - combined handler
            bottomNav.addEventListener('scroll', function() {
                bottomNav.classList.add('scrolling');
                clearTimeout(bottomNav.scrollTimeout);
                bottomNav.scrollTimeout = setTimeout(() => {
                    bottomNav.classList.remove('scrolling');
                    updateActiveIndicator();
                }, 150);
            }, { passive: true });

            // Window scroll: hide on scroll down, show on scroll up - Enhanced
            window.addEventListener('scroll', throttle(handleWindowScroll, 16), { passive: true });

            // Keyboard-aware (mobile): hide when viewport height shrinks significantly
            window.addEventListener('resize', handleResize, { passive: true });

            // Enhanced keyboard navigation between items
            bottomNav.addEventListener('keydown', handleKeyNav);

            // Haptic/light vibration on item click with better timing
            bottomNav.addEventListener('click', handleHapticClick, { capture: true });

            // Center active item on load with delay
            setTimeout(centerActiveItem, 100);

            // Create and position active indicator
            createActiveIndicator();
            updateActiveIndicator();

            // Add Intersection Observer for better performance
            setupIntersectionObserver();
        }
        
        // Throttle function for performance
        function throttle(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            }
        }
        
        // Debounce function for performance
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function handleWindowScroll() {
            const currentY = window.scrollY || window.pageYOffset;
            const delta = currentY - lastScrollY;
            const threshold = 10; // Slightly increased for better detection
            
            if (scrollHideRaf) cancelAnimationFrame(scrollHideRaf);
            
            scrollHideRaf = requestAnimationFrame(() => {
                if (Math.abs(delta) > threshold) {
                    if (delta > 0 && currentY > 100 && !keyboardLikelyOpen) {
                        // Scrolling down - hide nav
                        bottomNav.classList.add('hidden');
                    } else if (delta < -threshold) {
                        // Scrolling up - show nav
                        if (showDebounceTimer) clearTimeout(showDebounceTimer);
                        showDebounceTimer = setTimeout(() => {
                            bottomNav.classList.remove('hidden');
                        }, 120);
                    }
                }
            });
            
            lastScrollY = currentY;
        }

        function handleResize() {
            // If innerHeight reduced notably, assume keyboard open (mobile); hide bar
            const reduction = initialViewportH - window.innerHeight;
            keyboardLikelyOpen = reduction > 150; // heuristic
            
            if (keyboardLikelyOpen) {
                bottomNav.classList.add('hidden');
            } else {
                bottomNav.classList.remove('hidden');
            }
            
            // Update initial viewport height
            initialViewportH = window.innerHeight;
        }

        function handleKeyNav(e) {
            const isHorizontal = ['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(e.key);
            if (!isHorizontal) return;
            
            const items = Array.from(bottomNav.querySelectorAll('.bottom-nav-item'));
            const active = document.activeElement;
            const idx = items.indexOf(active);
            let nextIdx = idx;
            
            if (e.key === 'ArrowRight') {
                nextIdx = Math.min(items.length - 1, idx + 1);
            } else if (e.key === 'ArrowLeft') {
                nextIdx = Math.max(0, idx - 1);
            } else if (e.key === 'Home') {
                nextIdx = 0;
            } else if (e.key === 'End') {
                nextIdx = items.length - 1;
            }
            
            if (nextIdx !== -1 && items[nextIdx]) {
                items[nextIdx].focus();
                items[nextIdx].scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                e.preventDefault();
            }
        }

        function handleHapticClick(e) {
            try {
                if (typeof navigator !== 'undefined' && navigator.vibrate) {
                    navigator.vibrate([10, 5, 10]);
                }
            } catch (_) { /* ignore */ }
        }
        
        function centerActiveItem() {
            try {
                const active = document.querySelector('#bottomNav .bottom-nav-item.active');
                if (active) {
                    setTimeout(() => {
                    active.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                    }, 200);
                }
            } catch (_) { /* ignore */ }
        }

        function createActiveIndicator() {
            const container = document.getElementById('bottomNavItems');
            if (!container || indicatorEl) return;
            indicatorEl = document.createElement('div');
            indicatorEl.className = 'active-indicator';
            container.appendChild(indicatorEl);
        }

        function updateActiveIndicator() {
            const container = document.getElementById('bottomNavItems');
            if (!container) return;
            
            const active = container.querySelector('.bottom-nav-item.active');
            if (!active) {
                if (indicatorEl) {
                    indicatorEl.style.width = '0px';
                    indicatorEl.classList.remove('visible');
                }
                return;
            }
            
            if (!indicatorEl) createActiveIndicator();

            const containerRect = container.getBoundingClientRect();
            const activeRect = active.getBoundingClientRect();
            const width = Math.max(28, Math.min(activeRect.width - 20, 60));
            const centerX = activeRect.left + activeRect.width / 2;
            const left = centerX - containerRect.left - width / 2 + container.scrollLeft;

            indicatorEl.style.width = width + 'px';
            indicatorEl.style.transform = `translateX(${left}px)`;
            
            // Add visible class for smooth fade-in
            requestAnimationFrame(() => {
                indicatorEl.classList.add('visible');
            });
        }
        
        function setupIntersectionObserver() {
            const items = bottomNav.querySelectorAll('.bottom-nav-item');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in-view');
                    } else {
                        entry.target.classList.remove('in-view');
                    }
                });
            }, {
                root: bottomNav,
                rootMargin: '-20%',
                threshold: 0.5
            });
            
            items.forEach(item => observer.observe(item));
        }
        
        // Initialize slide navigation when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSlideNavigation);
        } else {
            initSlideNavigation();
        }
        
        // Add CSS for horizontal scrolling
        const style = document.createElement('style');
        style.textContent = `
            .bottom-nav {
                scroll-behavior: smooth;
            }
            
            .bottom-nav::-webkit-scrollbar {
                height: 4px;
            }
            
            .bottom-nav::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 2px;
            }
            
            .bottom-nav::-webkit-scrollbar-thumb {
                background: rgba(102, 126, 234, 0.6);
                border-radius: 2px;
            }
            
            .bottom-nav::-webkit-scrollbar-thumb:hover {
                background: rgba(102, 126, 234, 0.8);
            }
            
            @media (max-width: 768px) {
                .bottom-nav::-webkit-scrollbar {
                    display: none;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
    
    <!-- Bottom Navigation for Mobile -->
    <nav class="bottom-nav" id="bottomNav" role="navigation" aria-label="Primary">
        <div class="bottom-nav-items" id="bottomNavItems">
            @php
                $menuItems = [];
                
                // Always include Dashboard
                $menuItems[] = [
                    'url' => route('dashboard'),
                    'icon' => 'mdi mdi-view-dashboard',
                    'label' => 'Dashboard',
                    'active' => request()->routeIs('dashboard'),
                    'priority' => 1
                ];
                
                // Role-specific menu items
                if(auth()->user()->hasRole('admin')) {
                    $menuItems = array_merge($menuItems, [
                        [
                            'url' => route('courses.index'),
                            'icon' => 'mdi mdi-book-open-page-variant',
                            'label' => 'Courses',
                            'active' => (request()->routeIs(['courses.index', 'courses.create', 'courses.edit', 'courses.show']) && !request()->routeIs('testimonials.*') && !request()->routeIs('quizzes.*')),
                            'priority' => 2
                        ],
                        [
                            'url' => route('students.index'),
                            'icon' => 'mdi mdi-school',
                            'label' => 'Students',
                            'active' => request()->routeIs(['students.index', 'students.create', 'students.edit', 'students.show']),
                            'priority' => 3
                        ],
                        [
                            'url' => route('enrollments.index'),
                            'icon' => 'mdi mdi-account-group',
                            'label' => 'Enrollments',
                            'active' => request()->routeIs(['enrollments.index', 'enrollments.create', 'enrollments.edit', 'enrollments.show']),
                            'priority' => 4
                        ],
                        [
                            'url' => route('modules.index'),
                            'icon' => 'mdi mdi-folder',
                            'label' => 'Modules',
                            'active' => request()->routeIs(['modules.index', 'modules.create', 'modules.edit', 'modules.show']),
                            'priority' => 5
                        ],
                        [
                            'url' => route('lessons.index'),
                            'icon' => 'mdi mdi-book-open-page-variant',
                            'label' => 'Lessons',
                            'active' => request()->routeIs(['lessons.index', 'lessons.create', 'lessons.edit', 'lessons.show']),
                            'priority' => 6
                        ],
                        [
                            'url' => route('quizzes.index'),
                            'icon' => 'mdi mdi-help-circle',
                            'label' => 'Quizzes',
                            'active' => request()->routeIs(['quizzes.index', 'quizzes.create', 'quizzes.edit', 'quizzes.show', 'quiz.questions.*', 'quiz.taking.*']) && !request()->routeIs('testimonials.*'),
                            'priority' => 7
                        ],
                        [
                            'url' => route('testimonials.manage'),
                            'icon' => 'mdi mdi-comment-multiple-outline',
                            'label' => 'Testimonials',
                            'active' => request()->routeIs(['testimonials.manage', 'testimonials.create', 'testimonials.edit', 'testimonials.approve', 'testimonials.reject', 'testimonials.toggle-featured']),
                            'priority' => 8
                        ]
                    ]);
                } elseif(auth()->user()->hasRole('instructor')) {
                    $menuItems = array_merge($menuItems, [
                        [
                            'url' => route('courses.index'),
                            'icon' => 'mdi mdi-book-open-page-variant',
                            'label' => 'Courses',
                            'active' => (request()->routeIs(['courses.index', 'courses.create', 'courses.edit', 'courses.show']) && !request()->routeIs('testimonials.*') && !request()->routeIs('quizzes.*')),
                            'priority' => 2
                        ],
                        [
                            'url' => route('students.index'),
                            'icon' => 'mdi mdi-school',
                            'label' => 'Students',
                            'active' => request()->routeIs(['students.index', 'students.create', 'students.edit', 'students.show']),
                            'priority' => 3
                        ],
                        [
                            'url' => route('modules.index'),
                            'icon' => 'mdi mdi-folder',
                            'label' => 'Modules',
                            'active' => request()->routeIs(['modules.index', 'modules.create', 'modules.edit', 'modules.show']),
                            'priority' => 4
                        ],
                        [
                            'url' => route('lessons.index'),
                            'icon' => 'mdi mdi-book-open-page-variant',
                            'label' => 'Lessons',
                            'active' => request()->routeIs(['lessons.index', 'lessons.create', 'lessons.edit', 'lessons.show']),
                            'priority' => 5
                        ],
                        [
                            'url' => route('quizzes.index'),
                            'icon' => 'mdi mdi-help-circle',
                            'label' => 'Quizzes',
                            'active' => request()->routeIs(['quizzes.index', 'quizzes.create', 'quizzes.edit', 'quizzes.show', 'quiz.questions.*', 'quiz.taking.*']) && !request()->routeIs('testimonials.*'),
                            'priority' => 6
                        ]
                    ]);
                } elseif(auth()->user()->hasRole('student')) {
                    $menuItems = array_merge($menuItems, [
                        [
                            'url' => route('courses.index'),
                            'icon' => 'mdi mdi-book-open-page-variant',
                            'label' => 'Browse',
                            'active' => request()->routeIs(['courses.index', 'courses.create', 'courses.edit', 'courses.show']),
                            'priority' => 2
                        ],
                        [
                            'url' => route('enrollments.index'),
                            'icon' => 'mdi mdi-account-group',
                            'label' => 'My Courses',
                            'active' => request()->routeIs(['enrollments.index', 'enrollments.create', 'enrollments.edit', 'enrollments.show']),
                            'priority' => 3
                        ],
                        [
                            'url' => route('quizzes.index'),
                            'icon' => 'mdi mdi-help-circle',
                            'label' => 'Quizzes',
                            'active' => (request()->routeIs(['quizzes.index', 'quizzes.create', 'quizzes.edit', 'quizzes.show', 'quiz.questions.*', 'quiz.taking.*']) && !request()->routeIs('testimonials.*') && !request()->routeIs('courses.*')),
                            'priority' => 4
                        ],
                        [
                            'url' => route('testimonials.create'),
                            'icon' => 'mdi mdi-comment-multiple-outline',
                            'label' => 'Testimonials',
                            'active' => (request()->routeIs(['testimonials.create', 'testimonials.edit', 'testimonials.store', 'testimonials.update', 'testimonials.destroy']) && !request()->routeIs('quizzes.*') && !request()->routeIs('courses.*')),
                            'priority' => 5
                        ]
                    ]);
            }
                
                // Always include Profile at the end
                $menuItems[] = [
                    'url' => route('profile.edit'),
                    'icon' => 'mdi mdi-account-settings',
                    'label' => 'Profile',
                    'active' => request()->routeIs(['profile.edit', 'profile.show']),
                    'priority' => 99
                ];
                
                // Sort by priority
                usort($menuItems, function($a, $b) {
                    return $a['priority'] <=> $b['priority'];
                });
                
                $totalItems = count($menuItems);
                $showAllButton = $totalItems > 4;
            @endphp
            
            @foreach($menuItems as $item)
                <a href="{{ $item['url'] }}" class="bottom-nav-item {{ $item['active'] ? 'active' : '' }}" aria-current="{{ $item['active'] ? 'page' : 'false' }}" aria-label="{{ $item['label'] }}">
                    <i class="{{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>
</body>
</html>

