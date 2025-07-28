<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Dashboard') - CNHS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('layouts.shared.dashboard-styles')
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            position: relative;
            background-color: #f1f5f9;
            line-height: 1.6;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("{{ asset('images/cnhs-bg.png') }}");
            background-repeat: repeat;
            opacity: 0.1;
            z-index: -1;
        }
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: calc(100% - 280px);
            z-index: 1000;
            height: 70px;
            margin-left: 280px;
            border-bottom: 1px solid #e2e8f0;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        .header h1 {
            font-size: 1.5rem;
            color: #012970;
            margin: 0;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        .notification-bell {
            position: relative;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: background-color 0.2s;
        }
        .notification-bell:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }
        .notification-bell i {
            font-size: 1.2rem;
            color: #666;
            transition: color 0.2s;
        }
        .notification-bell:hover i {
            color: #007bff;
        }
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 0.2rem 0.5rem;
            font-size: 0.75rem;
        }
        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .header-profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .user-name {
            color: #012970;
            font-weight: 500;
        }
        /* Sidebar */
        .sidebar {
            width: 280px;
            background-color: #012970;
            color: white;
            padding: 25px 20px;
            position: fixed;
            height: 100vh;
            padding-top: 90px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            overflow-x: auto;
            min-width: 280px;
            max-width: 100vw;
        }
        .profile {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }
        .profile-image-container {
            position: relative;
            display: inline-block;
            margin-bottom: 15px;
        }
        .profile img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.2);
            object-fit: cover;
        }
        .online-indicator {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 20px;
            height: 20px;
            background-color: #10b981;
            border: 3px solid white;
            border-radius: 50%;
        }
        .profile h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
            color: white;
        }
        .profile .role {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 12px;
        }
        .online-status {
            display: inline-block;
            background-color: #10b981;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .menu li {
            margin-bottom: 10px;
        }
        .menu a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
            white-space: nowrap;
        }
        .menu a:hover, .menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            font-size: 16px;
        }
        .logout-btn {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            color: white;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .logout-btn i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            font-size: 16px;
        }
        /* Main Content */
        .main-content {
            flex-grow: 1;
            margin-left: 280px;
            padding: 90px 2rem 2rem;
            background-color: #f1f5f9;
            min-height: 100vh;
        }
        /* Responsive Design */
        @media (max-width: 768px) {
            .header {
                width: 100%;
                margin-left: 0;
            }
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding-top: 20px;
            }
            .main-content {
                margin-left: 0;
                padding-top: 90px;
                background-color: #f1f5f9;
            }
            .header-right .user-name {
                display: none;
            }
        }
        @yield('styles')
    </style>
</head>
<body class="student-theme">
    <!-- Header - Student Style (Admin Design) -->
    <header class="header">
        <div class="header-left">
            <img src="{{ asset('images/logo.png') }}" alt="School Logo" class="logo">
            <h1>CNHS Student Portal</h1>
        </div>
        <div class="header-right">
            <div class="notification-bell">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </div>
            <div class="dropdown user-profile">
                @if(Auth::guard('student')->check())
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::guard('student')->user()->profile_picture ? asset('storage/' . Auth::guard('student')->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::guard('student')->user()->first_name . ' ' . Auth::guard('student')->user()->last_name) . '&background=4e73df&color=ffffff' }}" alt="Profile" class="header-profile-pic">
                        <span class="d-none d-sm-inline mx-1 user-name">{{ Auth::guard('student')->user()->first_name . ' ' . Auth::guard('student')->user()->last_name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                        <li>
                            <a class="dropdown-item" href="{{ route('student.profile') }}">
                                <i class="fas fa-user-circle me-2"></i>Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                @endif
            </div>
        </div>
    </header>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <!-- Sidebar - Student Style (Admin Design) -->
    <div class="sidebar">
        <div class="profile">
            <div class="profile-image-container">
                <img src="{{ Auth::guard('student')->user()->profile_picture ? asset('storage/' . Auth::guard('student')->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::guard('student')->user()->first_name . ' ' . Auth::guard('student')->user()->last_name) . '&size=90&background=4e73df&color=ffffff&bold=true' }}" alt="Profile Picture">
                <div class="online-indicator"></div>
            </div>
            <h2>{{ Auth::guard('student')->user()->first_name . ' ' . Auth::guard('student')->user()->last_name }}</h2>
            <p class="role">Student</p>
            <span class="online-status">ONLINE</span>
        </div>
        <ul class="menu">
            <li>
                <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('student.subjects') }}" class="{{ request()->routeIs('student.subjects*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i> My Subjects
                </a>
            </li>
            <li>
                <a href="{{ route('student.profile') }}" class="{{ request()->routeIs('student.profile') ? 'active' : '' }}">
                    <i class="fas fa-user"></i> Profile
                </a>
            </li>
            <li>
                <a href="{{ route('student.grades') }}" class="{{ request()->routeIs('student.grades') ? 'active' : '' }}">
                    <i class="fas fa-star"></i> Grades
                </a>
            </li>
        </ul>
    </div>
    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>