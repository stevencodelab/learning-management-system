<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Smart Edu - Learning Management System. Transform your learning experience with our comprehensive educational platform designed for modern learners.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Smart Edu - Learning Management System</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/landing/bootstrap4/bootstrap.min.css') }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free-5.0.1/css/fontawesome-all.css') }}">
    
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="{{ asset('plugins/OwlCarousel2-2.2.1/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/OwlCarousel2-2.2.1/owl.theme.default.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/OwlCarousel2-2.2.1/animate.css') }}">
    
    <!-- Main Styles -->
    <link rel="stylesheet" href="{{ asset('css/landing/main_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing/responsive.css') }}">
    
    @stack('styles')
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body>
    <div class="super_container">
        <!-- Header -->
        <header class="header d-flex flex-row">
            <div class="header_content d-flex flex-row align-items-center">
                <!-- Logo -->
                <div class="logo_container">
                    <div class="logo">
                        <img src="{{ asset('images/landing/logo.png') }}" alt="Smart Edu Logo">
                        <span>Smart Edu</span>
                    </div>
                </div>
                
                <!-- Main Navigation -->
                <nav class="main_nav_container">
                    <div class="main_nav">
                        <ul class="main_nav_list">
                            <li class="main_nav_item"><a href="{{ route('welcome') }}">Home</a></li>
                            <li class="main_nav_item"><a href="{{ route('landing.courses') }}">Courses</a></li>
                            <li class="main_nav_item"><a href="{{ route('landing.quizzes') }}">Quizzes</a></li>
                            <li class="main_nav_item"><a href="{{ route('landing.teachers') }}">Teachers</a></li>
                            <!-- <li class="main_nav_item"><a href="{{ route('welcome') }}#services">Services</a></li> -->
                            <li class="main_nav_item"><a href="{{ route('testimonials.index') }}">Testimonials</a></li>
                            <li class="main_nav_item"><a href="{{ route('welcome') }}#contact">Contact</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
            
            <div class="header_side d-flex flex-row justify-content-center align-items-center">
                @auth
                <a href="{{ url('/dashboard') }}" style="padding: 8px 20px; background: #1e40af; color: white; text-decoration: none; border-radius: 4px; font-weight: 500;">Dashboard</a>
            @else
                <a href="{{ route('login') }}" style="padding: 8px 20px; background: #1e40af; color: white; text-decoration: none; border-radius: 4px; font-weight: 500;">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" style="padding: 8px 20px; background: transparent; color: #1e40af; text-decoration: none; border: 1px solid #1e40af; border-radius: 4px; font-weight: 500; margin-left: 10px;">Register</a>
                @endif
            @endauth
            </div>
        </header>
        
        <!-- Main Content -->
        @yield('content')
        
        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <!-- Newsletter -->
                <div class="newsletter">
                    <div class="row">
                        <div class="col">
                            <div class="section_title text-center">
                                <h1>Subscribe to newsletter</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-center">
                            <div class="newsletter_form_container mx-auto">
                                <form action="#" method="POST">
                                    @csrf
                                    <div class="newsletter_form d-flex flex-md-row flex-column flex-xs-column align-items-center justify-content-center">
                                        <input id="newsletter_email" class="newsletter_email" type="email" name="email" placeholder="Email Address" required="required">
                                        <button id="newsletter_submit" type="submit" class="newsletter_submit_btn trans_300">Subscribe</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer Content -->
                <div class="footer_content">
                    <div class="row">
                        <!-- About -->
                        <div class="col-lg-3 footer_col">
                            <div class="logo_container">
                                <div class="logo">
                                    <img src="{{ asset('images/landing/logo.png') }}" alt="Smart Edu Logo">
                                    <span>Smart Edu</span>
                                </div>
                            </div>
                            <p class="footer_about_text">Transform your learning experience with our comprehensive educational platform designed for modern learners. Join thousands of students worldwide.</p>
                        </div>
                        
                        <!-- Menu -->
                        <div class="col-lg-3 footer_col">
                            <div class="footer_column_title">Menu</div>
                            <div class="footer_column_content">
                                <ul>
                                    <li class="footer_list_item"><a href="{{ route('welcome') }}#home">Home</a></li>
                                    <li class="footer_list_item"><a href="{{ route('landing.courses') }}">Courses</a></li>
                                    <li class="footer_list_item"><a href="{{ route('landing.quizzes') }}">Quizzes</a></li>
                                    <li class="footer_list_item"><a href="{{ route('welcome') }}#teachers">Teachers</a></li>
                                    <!-- <li class="footer_list_item"><a href="{{ route('welcome') }}#services">Services</a></li> -->
                                    <li class="footer_list_item"><a href="{{ route('welcome') }}#contact">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Useful Links -->
                        <div class="col-lg-3 footer_col">
                            <div class="footer_column_title">Useful Links</div>
                            <div class="footer_column_content">
                                <ul>
                                    <li class="footer_list_item"><a href="#testimonials">Testimonials</a></li>
                                    <li class="footer_list_item"><a href="#events">Events</a></li>
                                    <li class="footer_list_item"><a href="#">FAQ</a></li>
                                    <li class="footer_list_item"><a href="#">Community</a></li>
                                    <li class="footer_list_item"><a href="#">Support</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Contact -->
                        <div class="col-lg-3 footer_col">
                            <div class="footer_column_title">Contact</div>
                            <div class="footer_column_content">
                                <ul>
                                    <li class="footer_contact_item">
                                        <div class="footer_contact_icon">
                                            <img src="{{ asset('images/landing/placeholder.svg') }}" alt="">
                                        </div>
                                        Jakarta, Indonesia
                                    </li>
                                    <li class="footer_contact_item">
                                        <div class="footer_contact_icon">
                                            <img src="{{ asset('images/landing/smartphone.svg') }}" alt="">
                                        </div>
                                        +62 234 5678 910
                                    </li>
                                    <li class="footer_contact_item">
                                        <div class="footer_contact_icon">
                                            <img src="{{ asset('images/landing/envelope.svg') }}" alt="">
                                        </div>
                                        hello@smartedu.com
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer Bar -->
                <div class="footer_bar d-flex flex-column flex-sm-row align-items-center">
                    <div class="footer_copyright">
                        <span>© {{ date('Y') }} Smart Edu. All rights reserved.</span>
                    </div>
                    <div class="footer_social ml-sm-auto">
                        <ul class="menu_social">
                            <li class="menu_social_item"><a href="#"><i class="fab fa-pinterest"></i></a></li>
                            <li class="menu_social_item"><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                            <li class="menu_social_item"><a href="#"><i class="fab fa-instagram"></i></a></li>
                            <li class="menu_social_item"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li class="menu_social_item"><a href="#"><i class="fab fa-twitter"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('js/landing/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('css/landing/bootstrap4/popper.js') }}"></script>
    <script src="{{ asset('css/landing/bootstrap4/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/greensock/TweenMax.min.js') }}"></script>
    <script src="{{ asset('plugins/greensock/TimelineMax.min.js') }}"></script>
    <script src="{{ asset('plugins/scrollmagic/ScrollMagic.min.js') }}"></script>
    <script src="{{ asset('plugins/greensock/animation.gsap.min.js') }}"></script>
    <script src="{{ asset('plugins/greensock/ScrollToPlugin.min.js') }}"></script>
    <script src="{{ asset('plugins/easing/easing.js') }}"></script>
    <script src="{{ asset('plugins/parallax.js-2.0.0/jquery.parallax.min.js') }}"></script>
    <script src="{{ asset('plugins/progressbar/progressbar.min.js') }}"></script>
    <script src="{{ asset('plugins/scrollTo/jquery.scrollTo.min.js') }}"></script>
    <script src="{{ asset('plugins/OwlCarousel2-2.2.1/owl.carousel.js') }}"></script>
    <script src="{{ asset('js/landing/custom.js') }}"></script>
    
    @stack('scripts')
</body>
</html>

