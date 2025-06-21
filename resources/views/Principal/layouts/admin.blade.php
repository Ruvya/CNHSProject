<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CNHS Principal - @yield('title', 'Dashboard')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/principal-sections.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 70px;
            --primary-color: #0d47a1;
            --primary-light: #1565c0;
            --primary-dark: #002171;
        }
      


        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,.8);
            padding: 0.875rem 1.5rem;
            border-radius: 8px;
            margin: 0.25rem 1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,.1);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,.2);
            font-weight: 600;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
            font-size: 1.1rem;
        }

        /* Main Content Area */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
           
            transition: all 0.3s ease;
        }
  
        /* Navbar Styles */
        .navbar {
            height: var(--header-height);
            background-color: white;
            box-shadow: 0 2px 15px rgba(0,0,0,.04);
            padding: 0.5rem 1.5rem;
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            z-index: 999;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar-brand img {
            height: 40px;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: scale(1.05);
        }

        /* Principal Profile Section */
        .principal-profile-section {
            padding: 2rem 1.5rem;
            text-align: center;
            background: rgba(255,255,255,0.05);
            margin: 1rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .profile-picture-sidebar {
            width: 90px;
            height: 90px;
            border: 3px solid rgba(255,255,255,0.2);
            border-radius: 50%;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .profile-picture-sidebar:hover {
            transform: scale(1.05);
            border-color: rgba(255,255,255,0.4);
        }

        /* Content Area */
        .content {
            padding: calc(var(--header-height) + 1.5rem) 1.5rem 1.5rem;
        }

        /* Dropdown Styles */
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 8px;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            padding: 0.5rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f3f4f6;
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 16px;
            text-align: center;
        }

        /* Responsive Adjustments */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .navbar {
                left: 0;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <div class="container-fluid p-0">
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Principal Profile Section -->
            <div class="principal-profile-section">
                <div class="position-relative d-inline-block mb-3">
                    <img src="{{ Auth::guard('principal')->user()->profile_picture ? asset('storage/' . Auth::guard('principal')->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::guard('principal')->user()->name) . '&size=90&background=ffffff&color=007bff&bold=true' }}" 
                         alt="{{ Auth::guard('principal')->user()->name }}" 
                         class="profile-picture-sidebar shadow">
                    
                    <!-- Online Status Badge -->
                    <span class="position-absolute bottom-0 end-0 translate-middle p-2 bg-success border border-2 border-white rounded-circle">
                        <span class="visually-hidden">Online</span>
                    </span>
                </div>
                
                <h6 class="mb-1 text-white fw-bold">{{ Auth::guard('principal')->user()->name }}</h6>
                <p class="mb-2 text-white-50 small">{{ Auth::guard('principal')->user()->position ?? 'Principal' }}</p>
                <span class="badge bg-success bg-opacity-25 text-white px-2 py-1">
                    <i class="fas fa-circle me-1" style="font-size: 6px;"></i>
                    Online
                </span>
            </div>
            
            <!-- Navigation Menu -->
            <ul class="nav flex-column mt-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('principal.dashboard') ? 'active' : '' }}" href="{{ route('principal.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('principal.announcements.*') ? 'active' : '' }}" href="{{ route('principal.announcements.index') }}">
                        <i class="fas fa-bullhorn"></i> Announcements
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('principal.teachers.*') ? 'active' : '' }}" href="{{ route('principal.teachers.index') }}">
                        <i class="fas fa-chalkboard-teacher"></i> Teachers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('principal.profile') ? 'active' : '' }}" href="{{ route('principal.profile') }}">
                        <i class="fas fa-user-edit"></i> My Profile
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid px-0">
                    <button class="btn btn-link text-dark d-lg-none me-3" type="button" onclick="document.querySelector('.sidebar').classList.toggle('show')">
                        <i class="fas fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="{{ route('principal.dashboard') }}">
                        <img src="{{ asset('images/CNHS.png') }}" alt="CNHS Logo" class="me-2">
                        <span class="h5 mb-0 text-primary fw-bold">CNHS Portal</span>
                    </a>
                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-link dropdown-toggle text-dark text-decoration-none" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <img src="{{ Auth::guard('principal')->user()->profile_picture ? asset('storage/' . Auth::guard('principal')->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::guard('principal')->user()->name) . '&size=32&background=007bff&color=ffffff&bold=true' }}" 
                                     alt="{{ Auth::guard('principal')->user()->name }}"
                                     class="rounded-circle me-2"
                                     width="32"
                                     height="32">
                                <span class="fw-medium">{{ Auth::guard('principal')->user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('principal.profile') }}">
                                    <i class="fas fa-user"></i> My Profile
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('principal.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>
