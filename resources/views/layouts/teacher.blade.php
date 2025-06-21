<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teacher Dashboard') - CNHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('layouts.shared.dashboard-styles')
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: #334155;
            line-height: 1.6;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #012970;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
        }

        .profile {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .profile h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .profile p {
            font-size: 14px;
            color: #bbb;
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
        }

        .menu a:hover, .menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
        }

        @yield('styles')
    </style>
</head>
<body class="teacher-theme">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile">
            <img src="{{ asset('images/logo.png') }}" alt="Profile Picture">
            <h2>{{ Auth::guard('teacher')->user()->name }}</h2>
            <p>Teacher</p>
        </div>
        <ul class="menu">
            <li>
                <a href="{{ route('teacher.dashboard') }}" class="{{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.classlist') }}" class="{{ request()->routeIs('teacher.classlist') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Class List
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.subjects') }}" class="{{ request()->routeIs('teacher.subjects') ? 'active' : '' }}">
                    <i class="fas fa-book"></i> Subjects
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.grades') }}" class="{{ request()->routeIs('teacher.grades') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Grades
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.profile') }}" class="{{ request()->routeIs('teacher.profile') ? 'active' : '' }}">
                    <i class="fas fa-user"></i> Profile
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" style="background: none; border: none; width: 100%; text-align: left; color: white; padding: 10px; cursor: pointer;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    @yield('scripts')
</body>
</html>