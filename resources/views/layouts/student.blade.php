<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CNHS Student Portal">
    <meta name="theme-color" content="#4CAF50">
    <link rel="icon" href="{{ asset('images/CNHS.png') }}" type="image/png">
    <title>@yield('title', 'Student Dashboard') - CNHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/student-styles.css') }}" rel="stylesheet">
    @include('layouts.shared.dashboard-styles')
    <style>
        /* General Styles */
        :root {
        --primary-orange: #ff6b35;
        --primary-orange-light: #ff8c5a;
        --primary-yellow: #ffd23f;
        --primary-blue: #007bff;
        --primary-blue-light: #4dabf7;
        --success-color: #28a745;
        --info-color: #17a2b8;
        --white: #ffffff;
        --gray-50: #f8f9fa;
        --gray-200: #e9ecef;
        --gray-400: #6c757d;
        --text-dark: #212529;
    }
        span{
            color: #0d47a1 !important;
            font-weight: 600;
            font-size: 1.25rem;
            letter-spacing: 0.5px;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
            color: #333;
        }

        /* Container */
        .container {
            display: flex;
            
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
            width: 80%;
            z-index: 1000;
            height: 70px;
            margin-left: 20%;
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
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color:blue;
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
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: #012970 !important;
            color: #fff;
            padding-top: 1rem;
            z-index: 1000;
            transition: all 0.3s ease;
            background: b#012970 !important;
        }

        .profile {
            text-align: center;
            padding: 2rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 1rem;
            border: 3px solid rgba(255, 255, 255, 0.2);
        }

        .name {
            font-size: 18px;
            margin-bottom: 5px;
            color: white;
        }

        .role {
            font-size: 14px;
            color: #bbb;
        }

        .menu {
            list-style: none;
            padding: 0;
        }

        .menu li {
            margin-bottom: 10px;
        }

        .menu a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .menu a:hover, .menu a.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        .menu i {
            width: 20px;
            margin-right: 0.75rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: calc(70px + 2rem) 2rem 2rem;
            min-height: 100vh;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
          

            .main-content {
                margin-left: 0;
            }

            .header-right .user-name {
                display: none;
            }

        }

        /* Student-specific overrides */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: #334155;
            line-height: 1.6;
        }

        @yield('styles')
    </style>
</head>
<body class="student-theme">
    <!-- Header -->
    <header class="header">
    <div class="header-left">
    <a class="navbar-brand" href="{{ route('principal.index') }}">
                <img src="{{ asset('images/CNHS.png') }}" alt="CNHS Logo" height="40">
                <span>CNHS PORTAL</span>
            </a>
        </div>
        <div class="header-right">
            <div class="notification-bell">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </div>
            <div class="dropdown user-profile">
                @if(Auth::guard('student')->check())
                    <a href="#" class="dropdown-toggle d-flex align-items-center" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration:none;">
                        @php
                            $student = Auth::guard('student')->user();
                        @endphp
                        <img src="{{ $student && $student->profile_picture ? asset('storage/' . $student->profile_picture) : asset('images/photo.jpg') }}" class="header-profile-pic" alt="Profile Picture">
                        <span class="user-name ms-2">{{ $student ? $student->first_name . ' ' . $student->last_name : 'Guest' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item" type="submit">Logout</button>
                            </form>
                        </li>
                    </ul>
                @else
                    <a href="{{ route('login') }}" class="dropdown-toggle d-flex align-items-center" style="text-decoration:none;">
                        <img src="{{ asset('images/photo.jpg') }}" class="header-profile-pic" alt="Profile Picture">
                        <span class="user-name ms-2">Guest</span>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile">
            @if(Auth::guard('student')->check())
                @php
                    $student = Auth::guard('student')->user();
                @endphp
                <img src="{{ $student && $student->profile_picture ? asset('storage/' . $student->profile_picture) : asset('images/logo.png') }}" alt="Profile Picture" class="profile-pic">
                <h2>{{ $student ? $student->first_name . ' ' . $student->last_name : 'Guest' }}</h2>
                <p>Student</p>
            @else
                <img src="{{ asset('images/logo.png') }}" alt="Profile Picture" class="profile-pic">
                <h2>Guest</h2>
                <p>Not Logged In</p>
            @endif
        </div>
        <ul class="menu">
            <li>
                <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('student.announcements') }}" class="{{ request()->routeIs('student.announcements') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i> Announcements
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