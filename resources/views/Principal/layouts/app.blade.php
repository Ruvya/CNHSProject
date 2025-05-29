<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CNHS - @yield('title', 'Welcome')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/principal.css') }}" rel="stylesheet">
    
    @section('styles')
    <style>
        /* Navbar styling with white background and blue text */
        .navbar {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            background-color: #ffffff !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #0d47a1 !important;
            font-weight: 600;
        }

        .navbar-brand span {
            font-size: 1.25rem;
            letter-spacing: 0.5px;
        }

        .navbar-brand:hover {
            color: #1565c0 !important;
        }

        .navbar-brand img {
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: scale(1.05);
        }

        .nav-link {
            color: #0d47a1 !important;
            font-weight: 500;
            position: relative;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #1565c0;
            transition: width 0.3s ease;
        }

        .nav-link:hover {
            color: #1565c0 !important;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link.active {
            color: #1565c0 !important;
            border-bottom: 2px solid #1565c0;
        }

        .btn-custom {
            background-color: #0d47a1 !important;
            color: white !important;
            font-weight: 500;
            border-radius: 4px;
            padding: 0.5rem 1.5rem !important;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #1565c0 !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Page specific styles */
        .hero-section {
            padding: 120px 0;
            background-color: #f8f9fa;
        }

        .hero-section-small {
            padding: 100px 0 60px;
            background-color: #f8f9fa;
        }

        .hero-title {
            color: #0d47a1;
            font-weight: 600;
        }

        .hero-subtitle {
            color: #666;
            font-size: 1.25rem;
        }
        
        /* Rest of your existing styles */
        @yield('additional_styles')
    </style>
    @endsection
    
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('principal.index') }}">
                <img src="{{ asset('images/CNHS.png') }}" alt="CNHS Logo" height="40">
                <span>CNHS PORTAL</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('principal.index') ? 'active' : '' }}" href="{{ route('principal.index') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('principal.about') ? 'active' : '' }}" href="{{ route('principal.about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('principal.academics') ? 'active' : '' }}" href="{{ route('principal.academics') }}">Academics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('principal.news') ? 'active' : '' }}" href="{{ route('principal.news') }}">News</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('principal.contact') ? 'active' : '' }}" href="{{ route('principal.contact') }}">Contact</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link btn btn-custom ms-2" href="/auth/login">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>About CNHS</h5>
                    <p>Calingcaguing National High School is committed to providing quality education and fostering academic excellence.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('principal.about') }}" class="text-light">About Us</a></li>
                        <li><a href="{{ route('principal.academics') }}" class="text-light">Academic Programs</a></li>
                        <li><a href="{{ route('principal.news') }}" class="text-light">News & Events</a></li>
                        <li><a href="{{ route('principal.contact') }}" class="text-light">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contact Info</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i>Calingcaguing Barugo, Philippines</li>
                        <li><i class="fas fa-phone me-2"></i>(123) 456-7890</li>
                        <li><i class="fas fa-envelope me-2"></i>info@cnhs.edu.ph</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Calingcaguing National High School. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Initialize AOS -->
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>

    @yield('scripts')
</body>
</html> 