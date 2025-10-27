<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }} - {{ $title ?? 'Dashboard' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
        }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            position: fixed;
            top: 0;
            left: 0;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar.collapsed {
            width: 80px;
        }
        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-logo {
            display: flex;
            align-items: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
        }
        .sidebar-logo img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
        .sidebar-menu {
            padding: 1rem 0;
        }
        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            margin: 0.25rem 0;
        }
        .menu-item:hover, .menu-item.active {
            background: rgba(255,255,255,0.1);
            border-left-color: #ffb606;
            color: white;
        }
        .menu-item svg {
            width: 20px;
            height: 20px;
            margin-right: 12px;
        }
        .menu-text {
            flex: 1;
        }
        .sidebar-collapse {
            width: 80px;
        }
        .sidebar-collapse .menu-text,
        .sidebar-collapse .sidebar-header .text {
            display: none;
        }
        .sidebar-collapse .sidebar-logo {
            justify-content: center;
        }
        
        .main-content {
            margin-left: 260px;
            padding: 2rem;
            transition: all 0.3s ease;
        }
        .main-content.sidebar-collapsed {
            margin-left: 80px;
        }
        
        .top-nav {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .nav-toggle {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .nav-toggle:hover {
            background: #2563eb;
        }
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e40af;
        }
        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e40af;
            margin: 0.5rem 0;
        }
        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="sidebar-logo">
                <img src="{{ asset('build/images/logo/logo.png') }}" alt="Logo">
                <span class="text">Smart Edu</span>
            </a>
        </div>
        
        <nav class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="menu-text">Dashboard</span>
            </a>
            
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('courses.index') }}" class="menu-item {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="menu-text">Courses</span>
                </a>
                <a href="{{ route('enrollments.index') }}" class="menu-item {{ request()->routeIs('enrollments.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="menu-text">Enrollments</span>
                </a>
            @endif
            
            @if(auth()->user()->hasRole('instructor'))
                <a href="{{ route('courses.index') }}" class="menu-item {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="menu-text">My Courses</span>
                </a>
                <a href="{{ route('enrollments.index') }}" class="menu-item {{ request()->routeIs('enrollments.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="menu-text">Students</span>
                </a>
            @endif
            
            @if(auth()->user()->hasRole('student'))
                <a href="{{ route('courses.index') }}" class="menu-item {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="menu-text">Browse Courses</span>
                </a>
                <a href="{{ route('enrollments.index') }}" class="menu-item {{ request()->routeIs('enrollments.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="menu-text">My Enrollments</span>
                </a>
            @endif
            
            <div style="margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="menu-item" style="width: 100%; background: none; border: none; cursor: pointer;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="menu-text">Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navigation -->
        <div class="top-nav">
            <button class="nav-toggle" onclick="toggleSidebar()">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <div class="user-menu">
                <div>
                    <div style="font-weight: 600; color: #1e40af;">{{ auth()->user()->name }}</div>
                    <div style="font-size: 0.875rem; color: #6b7280;">{{ auth()->user()->roles->first()->name ?? 'User' }}</div>
                </div>
                <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:text-blue-800">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </a>
            </div>
        </div>
        
        <!-- Page Content -->
        <div style="padding: 2rem 0;">
            @yield('content')
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('sidebar-collapse');
            mainContent.classList.toggle('sidebar-collapsed');
        }
        
        // Mobile sidebar toggle
        if (window.innerWidth <= 768) {
            document.querySelector('.nav-toggle').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('show');
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>

