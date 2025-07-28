<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'CNHS') - Calingcaguing National High School</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Clean Formal Header Styles -->
    <style>
        :root {
            --cnhs-primary-blue: #1E3A8A;
            --cnhs-secondary-blue: #3B82F6;
            --cnhs-accent-orange: #FF8C00;
            --cnhs-gold: #FCD34D;
            --cnhs-white: #FFFFFF;
            --cnhs-light-gray: #F8FAFC;
            --cnhs-medium-gray: #6B7280;
            --cnhs-dark-gray: #374151;
            --cnhs-border-gray: #E5E7EB;
        }

        /* Clean Formal Navigation Styles */
        .enhanced-navbar {
            background: var(--cnhs-white) !important;
            padding: 1.2rem 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-bottom: 3px solid var(--cnhs-primary-blue);
            position: relative;
        }

        /* Simple bottom accent line */
        .enhanced-navbar::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--cnhs-accent-orange);
        }

        /* Clean Brand Styling */
        .enhanced-navbar-brand {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--cnhs-primary-blue) !important;
            text-decoration: none;
            transition: color 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .enhanced-navbar-brand:hover {
            color: var(--cnhs-accent-orange) !important;
        }

        /* Simple School Logo Styling */
        .school-logo-container {
            width: 100px;
            height: 100px;
            border-radius: 16px;
            background: var(--cnhs-white);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 18px rgba(255, 140, 0, 0.18), 0 2px 8px rgba(0, 0, 0, 0.10);
            border: 4px solid var(--cnhs-accent-orange);
            padding: 8px;
            margin-right: 1.5rem;
            transition: box-shadow 0.3s, border-color 0.3s;
        }

        .school-logo-container:hover {
            box-shadow: 0 8px 28px rgba(255, 140, 0, 0.28), 0 4px 16px rgba(0, 0, 0, 0.15);
            border-color: var(--cnhs-primary-blue);
        }

        .school-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 8px;
        }

        .school-logo-icon {
            font-size: 2rem;
            color: var(--cnhs-primary-blue);
        }

        /* Clean School Name and Tagline */
        .school-info {
            display: flex;
            flex-direction: column;
            line-height: 1.3;
        }

        .school-name {
            font-size: 1.9rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.02em;
            color: var(--cnhs-primary-blue);
        }

        .school-tagline {
            font-size: 0.85rem;
            font-weight: 600;
            margin: 0;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--cnhs-accent-orange);
        }

        /* Clean Navigation Links - No Icons in Header Only */
        .enhanced-nav-link {
            color: var(--cnhs-dark-gray) !important;
            font-weight: 600;
            font-size: 1rem;
            padding: 0.8rem 1.5rem !important;
            border-radius: 6px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 0 0.2rem;
            border: 2px solid transparent;
        }

        .enhanced-nav-link:hover {
            background: var(--cnhs-light-gray);
            color: var(--cnhs-primary-blue) !important;
            border-color: var(--cnhs-primary-blue);
        }

        .enhanced-nav-link.active {
            background: var(--cnhs-primary-blue);
            color: var(--cnhs-white) !important;
            border-color: var(--cnhs-primary-blue);
        }

        /* Clean Login Link Styling - No Icons in Header */
        .login-link {
            background: var(--cnhs-light-gray);
            border: 2px solid var(--cnhs-border-gray);
            color: var(--cnhs-dark-gray) !important;
            font-weight: 600;
            padding: 0.8rem 1.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .login-link:hover {
            background: var(--cnhs-primary-blue);
            border-color: var(--cnhs-primary-blue);
            color: var(--cnhs-white) !important;
        }

        /* Clean Dropdown Styling - Keep icons in dropdown items */
        .enhanced-dropdown-toggle {
            background: var(--cnhs-light-gray);
            border: 2px solid var(--cnhs-border-gray);
            color: var(--cnhs-dark-gray) !important;
            font-weight: 600;
            padding: 0.8rem 1.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .enhanced-dropdown-toggle:hover {
            background: var(--cnhs-primary-blue);
            border-color: var(--cnhs-primary-blue);
            color: var(--cnhs-white) !important;
        }

        .enhanced-dropdown-menu {
            background: var(--cnhs-white);
            border: 2px solid var(--cnhs-border-gray);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            margin-top: 0.5rem;
        }

        .enhanced-dropdown-item {
            color: var(--cnhs-dark-gray);
            font-weight: 600;
            padding: 0.8rem 1.5rem;
            transition: all 0.3s ease;
            border-radius: 6px;
            margin: 0.2rem 0.5rem;
        }

        .enhanced-dropdown-item:hover {
            background: var(--cnhs-primary-blue);
            color: var(--cnhs-white);
        }

        .enhanced-dropdown-item i {
            width: 20px;
            text-align: center;
        }

        /* Mobile Responsive */
        @media (max-width: 991px) {
            .enhanced-navbar {
                padding: 1rem 0;
            }

            .enhanced-navbar-brand {
                font-size: 1.6rem;
            }

            .school-logo-container {
                width: 70px;
                height: 70px;
                border-width: 3px;
                border-radius: 10px;
                margin-right: 1rem;
            }

            .school-logo {
                width: 50px;
                height: 50px;
                border-radius: 6px;
            }

            .school-name {
                font-size: 1.6rem;
            }

            .school-tagline {
                font-size: 0.75rem;
            }

            .enhanced-nav-link, .login-link {
                margin: 0.2rem 0;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .school-info {
                display: none;
            }

            .enhanced-navbar-brand {
                gap: 0.8rem;
            }

            .school-logo-container {
                width: 48px;
                height: 48px;
                border-width: 2px;
                border-radius: 6px;
                margin-right: 0.5rem;
            }

            .school-logo {
                width: 32px;
                height: 32px;
                border-radius: 4px;
            }
        }

        /* Clean Navbar Toggler */
        .enhanced-navbar-toggler {
            border: 2px solid var(--cnhs-primary-blue);
            border-radius: 6px;
            padding: 0.6rem;
            transition: all 0.3s ease;
        }

        .enhanced-navbar-toggler:hover {
            background: var(--cnhs-primary-blue);
        }

        .enhanced-navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2830, 58, 138, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .enhanced-navbar-toggler:hover .enhanced-navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Main content adjustment */
        .main-content {
            margin-top: 110px;
        }

        /* Logo error handling */
        .school-logo.error {
            display: none;
        }

        .school-logo-icon.show {
            display: block;
        }

        /* Clean divider */
        .formal-divider {
            height: 2px;
            background: var(--cnhs-accent-orange);
            margin: 0;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Clean Formal Navigation -->
    <nav class="navbar navbar-expand-lg enhanced-navbar">
        <div class="container">
            <!-- Clean Brand with Logo -->
            <a class="enhanced-navbar-brand" href="{{ route('principal.index') }}">
                <div class="school-logo-container">
                    <img src="{{ asset('images/logo.png') }}" alt="School Logo" class="school-logo" 
                         onerror="this.classList.add('error'); this.nextElementSibling.classList.add('show');">
                    <i class="fas fa-graduation-cap school-logo-icon" style="display: none;"></i>
                </div>
                <div class="school-info">
                    <div class="school-name">CNHS</div>
                    <div class="school-tagline">Excellence in Education</div>
                </div>
            </a>
            
            <!-- Clean Toggler -->
            <button class="navbar-toggler enhanced-navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon enhanced-navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Group navigation links and login/user menu together, aligned right -->
                <ul class="navbar-nav d-flex align-items-center ms-auto">
                    <li class="nav-item">
                        <a class="nav-link enhanced-nav-link {{ request()->routeIs('principal.index') ? 'active' : '' }}" 
                           href="{{ route('principal.index') }}">
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link enhanced-nav-link {{ request()->routeIs('principal.about') ? 'active' : '' }}" 
                           href="{{ route('principal.about') }}">
                            About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link enhanced-nav-link {{ request()->routeIs('principal.academics') ? 'active' : '' }}" 
                           href="{{ route('principal.academics') }}">
                            Academics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link enhanced-nav-link {{ request()->routeIs('principal.news') ? 'active' : '' }}" 
                           href="{{ route('principal.news') }}">
                            News
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link enhanced-nav-link {{ request()->routeIs('principal.contact') ? 'active' : '' }}" 
                           href="{{ route('principal.contact') }}">
                            Contact
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="login-link" href="{{ route('login') }}">
                            LOGIN PORTAL
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Clean Divider -->
    <div class="formal-divider"></div>
    
    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>
    
    <!-- Footer - Keep all icons here -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="school-logo-container me-3" style="width: 50px; height: 50px; background: var(--cnhs-white);">
                            <img src="{{ asset('images/logo.png') }}" alt="School Logo" class="school-logo" 
                                 onerror="this.classList.add('error'); this.nextElementSibling.classList.add('show');">
                            <i class="fas fa-graduation-cap school-logo-icon" style="display: none; font-size: 1.5rem; color: var(--cnhs-primary-blue);"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Calingcaguing National High School</h5>
                            <small class="text-muted">Excellence in Education</small>
                        </div>
                    </div>
                    <p class="mb-0">Empowering students through quality education and innovative learning approaches since 1985.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="text-uppercase fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('principal.about') }}" class="text-light text-decoration-none">About Us</a></li>
                        <li><a href="{{ route('principal.academics') }}" class="text-light text-decoration-none">Academic Programs</a></li>
                        <li><a href="{{ route('principal.news') }}" class="text-light text-decoration-none">News & Events</a></li>
                        <li><a href="{{ route('principal.contact') }}" class="text-light text-decoration-none">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="text-uppercase fw-bold mb-3">Contact Info</h6>
                    <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Calingcaguing Barugo, Philippines</p>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i>(123) 456-7890</p>
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i>info@cnhs.edu.ph</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} Calingcaguing National High School. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="social-links">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Add active class to current page nav link
            const currentPath = window.location.pathname;
            document.querySelectorAll('.enhanced-nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });

            // Logo error handling
            document.querySelectorAll('.school-logo').forEach(logo => {
                logo.addEventListener('error', function() {
                    console.log('CNHS.png logo not found, using fallback icon');
                    this.style.display = 'none';
                    const fallbackIcon = this.nextElementSibling;
                    if (fallbackIcon) {
                        fallbackIcon.style.display = 'block';
                    }
                });

                logo.addEventListener('load', function() {
                    console.log('CNHS.png logo loaded successfully');
                });
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>