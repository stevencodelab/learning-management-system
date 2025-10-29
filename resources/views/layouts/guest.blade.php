<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Smart Edu - Learning Management System. Transform your learning experience with our comprehensive educational platform designed for modern learners.">

        <title>Smart Edu - Learning Management System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/landing/logo.png') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            .gradient-bg {
                background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #1e40af 100%) !important;
            }
            .gradient-bg::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #1e40af 100%);
                z-index: -1;
            }
            .tech-pattern {
                background-image: 
                    radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%),
                    radial-gradient(circle at 75% 75%, rgba(255,255,255,0.05) 0%, transparent 50%),
                    linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.02) 50%, transparent 70%);
            }
            .floating-elements {
                position: absolute;
                width: 100%;
                height: 100%;
                overflow: hidden;
            }
            .floating-circle {
                position: absolute;
                border-radius: 50%;
                background: rgba(255,255,255,0.1);
                animation: float 6s ease-in-out infinite;
                backdrop-filter: blur(1px);
                transition: all 0.3s ease;
                cursor: pointer;
            }
            .floating-circle:hover {
                background: rgba(255,255,255,0.2);
                transform: scale(1.2);
            }
            .floating-circle.error {
                background: rgba(239, 68, 68, 0.1);
            }
            .floating-circle.error:hover {
                background: rgba(239, 68, 68, 0.2);
            }
            .floating-circle:nth-child(1) {
                width: 60px;
                height: 60px;
                top: 15%;
                left: 8%;
                animation-delay: 0s;
            }
            .floating-circle:nth-child(2) {
                width: 45px;
                height: 45px;
                top: 25%;
                right: 12%;
                animation-delay: 2s;
            }
            .floating-circle:nth-child(3) {
                width: 70px;
                height: 70px;
                bottom: 15%;
                left: 15%;
                animation-delay: 4s;
            }
            .floating-circle:nth-child(4) {
                width: 35px;
                height: 35px;
                bottom: 25%;
                right: 20%;
                animation-delay: 1s;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(180deg); }
            }
            .tech-grid {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: 
                    linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px);
                background-size: 60px 60px;
                animation: grid-move 20s linear infinite;
                transition: all 0.3s ease;
            }
            .tech-grid.error {
                background-image: 
                    linear-gradient(rgba(239, 68, 68, 0.05) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(239, 68, 68, 0.05) 1px, transparent 1px);
            }
            .tech-grid:hover {
                background-image: 
                    linear-gradient(rgba(255,255,255,0.08) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px);
                background-size: 70px 70px;
            }
            .tech-grid.error:hover {
                background-image: 
                    linear-gradient(rgba(239, 68, 68, 0.08) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(239, 68, 68, 0.08) 1px, transparent 1px);
            }
            .tech-grid:hover .floating-circle {
                animation-play-state: paused;
            }
            .floating-elements:hover .floating-circle {
                animation-play-state: paused;
            }
            .tech-grid.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .floating-elements.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .tech-grid.error:hover {
                background-size: 70px 70px;
            }
            .tech-grid.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .floating-elements.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .tech-grid.error:hover {
                background-size: 70px 70px;
            }
            .tech-grid.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .floating-elements.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .tech-grid.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .floating-elements.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .tech-grid.error:hover {
                background-size: 70px 70px;
            }
            .tech-grid.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .floating-elements.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .tech-grid.error:hover {
                background-size: 70px 70px;
            }
            .tech-grid.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .floating-elements.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .tech-grid.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .floating-elements.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .tech-grid.error:hover {
                background-size: 70px 70px;
            }
            .tech-grid.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .floating-elements.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .tech-grid.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .floating-elements.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .tech-grid.error:hover {
                background-size: 70px 70px;
            }
            .tech-grid.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .floating-elements.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .tech-grid.error:hover {
                background-size: 70px 70px;
            }
            .tech-grid.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .floating-elements.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .tech-grid.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .floating-elements.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .tech-grid.error:hover {
                background-size: 70px 70px;
            }
            .tech-grid.error:hover .floating-circle {
                animation-play-state: paused;
            }
            .floating-elements.error:hover .floating-circle {
                animation-play-state: paused;
            }
            @keyframes grid-move {
                0% { transform: translate(0, 0); }
                100% { transform: translate(50px, 50px); }
            }
            .pulse-glow {
                animation: pulse-glow 3s ease-in-out infinite;
            }
            @keyframes pulse-glow {
                0%, 100% { opacity: 0.3; transform: scale(1); }
                50% { opacity: 0.6; transform: scale(1.05); }
            }
            .slide-in {
                animation: slide-in 1s ease-out;
            }
            @keyframes slide-in {
                0% { transform: translateY(30px); opacity: 0; }
                100% { transform: translateY(0); opacity: 1; }
            }
            .fade-in {
                animation: fade-in 1.5s ease-out;
            }
            @keyframes fade-in {
                0% { opacity: 0; }
                100% { opacity: 1; }
            }
            .animate-slide-down {
                animation: slide-down 0.3s ease-out;
            }
            @keyframes slide-down {
                0% { 
                    opacity: 0; 
                    transform: translateY(-10px); 
                }
                100% { 
                    opacity: 1; 
                    transform: translateY(0); 
                }
            }
            .animate-shake {
                animation: shake 0.5s ease-in-out;
            }
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
            .gold-accent {
                background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            }
            .gold-text {
                color: #fbbf24;
            }
            .blue-gradient {
                background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-blue-50">
        <div class="min-h-screen flex">
            <!-- Left Side - Branding -->
            <div class="hidden lg:flex lg:w-1/2 gradient-bg tech-pattern relative overflow-hidden items-center justify-center">
                <!-- Tech Grid Background -->
                <div class="tech-grid group {{ $errors->any() ? 'error' : '' }}"></div>
                
                <!-- Floating Elements -->
                <div class="floating-elements group {{ $errors->any() ? 'error' : '' }}">
                    <div class="floating-circle {{ $errors->any() ? 'error' : '' }}"></div>
                    <div class="floating-circle {{ $errors->any() ? 'error' : '' }}"></div>
                    <div class="floating-circle {{ $errors->any() ? 'error' : '' }}"></div>
                    <div class="floating-circle {{ $errors->any() ? 'error' : '' }}"></div>
                </div>
                
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black bg-opacity-5"></div>
                
                <!-- Main Content -->
                <div class="relative z-10 flex flex-col items-center text-white px-8 sm:px-12 w-full max-w-lg">
                    <!-- Logo -->
                    <div class="mb-8 sm:mb-10 relative slide-in">
                        <!-- Glow Effect -->
                        <div class="absolute inset-0 {{ $errors->any() ? 'bg-red-200 bg-opacity-30' : 'bg-white bg-opacity-30' }} rounded-2xl blur-xl scale-110 pulse-glow"></div>
                        <div class="relative w-20 h-20 sm:w-24 sm:h-24 {{ $errors->any() ? 'bg-red-100 bg-opacity-20 border-red-300' : 'bg-white bg-opacity-20 border-white border-opacity-30' }} rounded-2xl flex items-center justify-center backdrop-blur-sm p-2 sm:p-3 border transition-all duration-300 hover:bg-opacity-30 hover:scale-105 cursor-pointer">
                            <img src="{{ asset('images/landing/logo.png') }}" alt="Smart Edu LMS Logo" class="w-full h-full object-contain">
                        </div>
                        <!-- Decorative Elements -->
                        <div class="absolute -top-2 -right-2 w-6 h-6 {{ $errors->any() ? 'bg-red-400' : 'bg-yellow-400' }} rounded-full opacity-60 animate-pulse transition-all duration-300 hover:opacity-100 hover:scale-125"></div>
                        <div class="absolute -bottom-2 -left-2 w-5 h-5 {{ $errors->any() ? 'bg-red-300' : 'bg-blue-300' }} rounded-full opacity-40 animate-pulse transition-all duration-300 hover:opacity-100 hover:scale-125" style="animation-delay: 1s;"></div>
                    </div>
                    
                    <!-- Brand Text -->
                    <div class="text-center relative fade-in mb-8 sm:mb-10" style="animation-delay: 0.3s;">
                        <!-- Background Glow -->
                        <div class="absolute inset-0 {{ $errors->any() ? 'bg-gradient-to-r from-transparent via-red-200 to-transparent' : 'bg-gradient-to-r from-transparent via-white to-transparent' }} opacity-5 rounded-full blur-3xl"></div>
                        
                        <h1 class="text-4xl sm:text-5xl font-bold mb-4 sm:mb-6 tracking-tight relative group">
                            <span class="gold-text relative transition-all duration-300 group-hover:scale-105">
                                Smart Edu
                                <!-- Gold Accent Line -->
                                <div class="absolute -bottom-1 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-yellow-400 to-transparent transition-all duration-300 group-hover:via-yellow-300"></div>
                            </span>
                        </h1>
                        <h2 class="text-lg sm:text-xl font-medium mb-2 text-white opacity-90 relative group">
                            Learning Management System
                            <!-- Decorative Line -->
                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-32 h-1 {{ $errors->any() ? 'bg-red-300' : 'bg-white' }} bg-opacity-30 transition-all duration-300 group-hover:w-40 group-hover:bg-opacity-50"></div>
                        </h2>
                        
                        <p class="text-xl sm:text-2xl font-medium mb-4 text-white opacity-90 relative group">
                            Smart Learning Solutions
                            <!-- Decorative Line -->
                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-24 h-1 {{ $errors->any() ? 'bg-red-300' : 'bg-white' }} bg-opacity-30 transition-all duration-300 group-hover:w-32 group-hover:bg-opacity-50"></div>
                        </p>
                        
                        <p class="text-lg sm:text-xl text-white opacity-75 max-w-md leading-relaxed relative group">
                            Transform your learning experience with our comprehensive educational platform designed for modern learners.
                        </p>
                        
                        <!-- Decorative Elements -->
                        <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 w-2 h-8 bg-gradient-to-b from-transparent via-white to-transparent opacity-30 transition-all duration-300 group-hover:opacity-50 group-hover:h-10"></div>
                        <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 w-2 h-8 bg-gradient-to-t from-transparent via-white to-transparent opacity-30 transition-all duration-300 group-hover:opacity-50 group-hover:h-10"></div>
                    </div>
                    
                    <!-- Features/Stats Section -->
                    <div class="mt-6 sm:mt-8 w-full max-w-sm slide-in" style="animation-delay: 0.6s;">
                        <div class="grid grid-cols-3 gap-4 text-center {{ $errors->any() ? 'animate-shake' : '' }}">
                            <div class="relative group">
                                <div class="{{ $errors->any() ? 'bg-red-100 bg-opacity-20 border-red-300' : 'bg-white bg-opacity-10 border-white border-opacity-20' }} rounded-xl p-4 sm:p-5 backdrop-blur-sm border transition-all duration-300 group-hover:bg-opacity-20 group-hover:scale-105">
                                    <div class="text-3xl sm:text-4xl font-bold gold-text">1000+</div>
                                    <div class="text-sm sm:text-base text-white opacity-80">Students</div>
                                </div>
                                <!-- Decorative Corner -->
                                <div class="absolute -top-1 -right-1 w-4 h-4 {{ $errors->any() ? 'bg-red-400' : 'bg-yellow-400' }} rounded-full opacity-60 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            <div class="relative group">
                                <div class="{{ $errors->any() ? 'bg-red-100 bg-opacity-20 border-red-300' : 'bg-white bg-opacity-10 border-white border-opacity-20' }} rounded-xl p-4 sm:p-5 backdrop-blur-sm border transition-all duration-300 group-hover:bg-opacity-20 group-hover:scale-105">
                                    <div class="text-3xl sm:text-4xl font-bold gold-text">100+</div>
                                    <div class="text-sm sm:text-base text-white opacity-80">Courses</div>
                                </div>
                                <!-- Decorative Corner -->
                                <div class="absolute -top-1 -right-1 w-4 h-4 {{ $errors->any() ? 'bg-red-400' : 'bg-blue-300' }} rounded-full opacity-60 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            <div class="relative group">
                                <div class="{{ $errors->any() ? 'bg-red-100 bg-opacity-20 border-red-300' : 'bg-white bg-opacity-10 border-white border-opacity-20' }} rounded-xl p-4 sm:p-5 backdrop-blur-sm border transition-all duration-300 group-hover:bg-opacity-20 group-hover:scale-105">
                                    <div class="text-3xl sm:text-4xl font-bold gold-text">99%</div>
                                    <div class="text-sm sm:text-base text-white opacity-80">Success</div>
                                </div>
                                <!-- Decorative Corner -->
                                <div class="absolute -top-1 -right-1 w-4 h-4 {{ $errors->any() ? 'bg-red-400' : 'bg-green-300' }} rounded-full opacity-60 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tech Elements -->
                    <div class="absolute top-4 sm:top-6 left-4 sm:left-6 w-3 h-3 sm:w-4 sm:h-4 {{ $errors->any() ? 'bg-red-300' : 'bg-white' }} rounded-full opacity-60 animate-pulse transition-all duration-300 hover:opacity-100 hover:scale-150"></div>
                    <div class="absolute top-8 sm:top-12 right-8 sm:right-12 w-2 h-2 sm:w-3 sm:h-3 {{ $errors->any() ? 'bg-red-300' : 'bg-white' }} rounded-full opacity-40 animate-pulse transition-all duration-300 hover:opacity-100 hover:scale-150" style="animation-delay: 0.5s;"></div>
                    <div class="absolute bottom-8 sm:bottom-12 left-8 sm:left-12 w-2 h-2 sm:w-3 sm:h-3 {{ $errors->any() ? 'bg-red-300' : 'bg-white' }} rounded-full opacity-50 animate-pulse transition-all duration-300 hover:opacity-100 hover:scale-150" style="animation-delay: 1s;"></div>
                    <div class="absolute bottom-4 sm:bottom-6 right-4 sm:right-6 w-2 h-2 sm:w-3 sm:h-3 {{ $errors->any() ? 'bg-red-300' : 'bg-white' }} rounded-full opacity-30 animate-pulse transition-all duration-300 hover:opacity-100 hover:scale-150" style="animation-delay: 1.5s;"></div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 py-8 sm:py-12">
                <div class="w-full max-w-md">
                        <!-- Mobile Logo -->
                        <div class="lg:hidden text-center mb-6 sm:mb-8">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 {{ $errors->any() ? 'bg-red-100 border-red-200' : 'blue-gradient' }} rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 p-1.5 sm:p-2 transition-all duration-300">
                                <img src="{{ asset('images/landing/logo.png') }}" alt="Smart Edu LMS Logo" class="w-full h-full object-contain">
                            </div>
                            <h1 class="text-xl sm:text-2xl font-bold {{ $errors->any() ? 'text-red-700' : 'text-blue-900' }} transition-colors duration-300">
                                <span class="gold-text">Smart Edu</span>
                            </h1>
                            <h2 class="text-sm sm:text-base font-medium {{ $errors->any() ? 'text-red-600' : 'text-blue-700' }} transition-colors duration-300">
                                Learning Management System
                            </h2>
                        </div>

                    <!-- Form Container -->
                    <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 border {{ $errors->any() ? 'border-red-200 shadow-red-100' : 'border-blue-100' }} transition-all duration-300">
                        <div class="mb-6 sm:mb-8">
                            <h2 class="text-xl sm:text-2xl font-bold {{ $errors->any() ? 'text-red-700' : 'text-blue-900' }} mb-2 transition-colors duration-300">Welcome to Smart Edu</h2>
                            <p class="text-sm sm:text-base {{ $errors->any() ? 'text-red-600' : 'text-blue-600' }} transition-colors duration-300">Sign in to your account to continue your learning journey</p>
                        </div>

                {{ $slot }}
                    </div>

                        <!-- Footer -->
                        <div class="text-center mt-6 sm:mt-8">
                            <p class="text-xs sm:text-sm text-blue-500">
                                © 2024 <span class="gold-text font-semibold">Smart Edu</span> - Learning Management System. All rights reserved.
                            </p>
                        </div>
                </div>
            </div>
        </div>
    </body>
</html>
