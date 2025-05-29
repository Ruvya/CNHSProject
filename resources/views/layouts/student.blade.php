<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CNHS Student Portal">
    <meta name="theme-color" content="#4CAF50">
    <link rel="icon" href="{{ asset('images/log.png') }}" type="image/png">
    <title>@yield('title') - CNHS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General Styles */
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
            width: 100%;
            z-index: 1000;
            height: 70px;
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
            width: 250px;
            background-color: #012970;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
            padding-top: 90px;
            overflow-y: auto;
        }

        .profile {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
            object-fit: cover;
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
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .menu a:hover, .menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            margin-left: 250px;
            padding: 90px 2rem 2rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding-top: 20px;
            }

            .main-content {
                margin-left: 0;
            }

            .header-right .user-name {
                display: none;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <img src="{{ asset('images/log.png') }}" alt="CNHS Logo" class="logo">
            <h1>CNHS Student Portal</h1>
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