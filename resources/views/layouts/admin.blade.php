<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - CNHS</title>

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
            width: calc(100% - 280px); /* Adjust for sidebar width */
            z-index: 1000;
            height: 70px;
            margin-left: 280px; /* Aligns with main content */
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
            min-width: 20px;
            text-align: center;
        }

        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 400px;
            max-height: 500px;
            overflow-y: auto;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            display: none;
        }

        .notification-header {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8f9fa;
            border-radius: 8px 8px 0 0;
        }

        .notification-header h6 {
            margin: 0;
            color: #333;
            font-weight: 600;
        }

        .notification-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.2s;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .notification-content {
            color: #666;
            font-size: 13px;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .notification-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #999;
        }

        .notification-author {
            font-weight: 500;
            color: #007bff;
        }

        .notification-time {
            color: #999;
        }

        .new-announcement {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
        }

        .new-announcement:hover {
            background-color: #fde68a;
        }

        .no-notifications {
            padding: 30px;
            text-align: center;
            color: #666;
        }

        .no-notifications i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 15px;
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

        /* Content Cards */
        .card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .alert {
            border-radius: 8px;
            border: 1px solid transparent;
        }

        .alert-success {
            background-color: #d1fae5;
            border-color: #10b981;
            color: #065f46;
        }

        .alert-danger {
            background-color: #fee2e2;
            border-color: #ef4444;
            color: #991b1b;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background-color: #012970; /* Solid blue */
            color: white;
            padding: 25px 20px;
            position: fixed;
            height: 100vh;
            padding-top: 90px; /* Space for fixed header */
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
            padding: 90px 2rem 2rem; /* Adjusted padding for fixed header */
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
                padding-top: 90px; /* Ensure padding on mobile as well */
                background-color: #f1f5f9;
            }

            .header-right .user-name {
                display: none;
            }
        }

    </style>
    @yield('styles')
</head>

<body>
    <!-- Header - Admin Style -->
    <header class="header">
        <div class="header-left">
            <img src="{{ asset('images/logo.png') }}" alt="School Logo" class="logo">
            <h1>CNHS Admin Portal</h1>
        </div>
        <div class="header-right">
            {{-- Removed notification bell icon and dropdown --}}
            <div class="dropdown user-profile">
                @if(Auth::guard('admin')->check())
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::guard('admin')->user()->profile_picture ? asset('storage/' . Auth::guard('admin')->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::guard('admin')->user()->name) . '&background=4e73df&color=ffffff' }}" alt="Profile" class="header-profile-pic">
                        <span class="d-none d-sm-inline mx-1 user-name">{{ Auth::guard('admin')->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.profile.index') }}">
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

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile">
            <div class="profile-image-container">
                <img src="{{ Auth::guard('admin')->user()->profile_picture ? asset('storage/' . Auth::guard('admin')->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::guard('admin')->user()->name) . '&size=90&background=4e73df&color=ffffff&bold=true' }}" alt="Profile Picture">
                <div class="online-indicator"></div>
            </div>
            <h2>{{ Auth::guard('admin')->user()->name }}</h2>
            <p class="role">Administrator</p>
            <span class="online-status">ONLINE</span>
        </div>
        <ul class="menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.students.index') }}" class="{{ request()->routeIs('admin.users.students*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate"></i> Student Records
                </a>
            </li>
            <li>
                <a href="{{ route('admin.subjects.index') }}" class="{{ request()->routeIs('admin.subjects*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i> Subjects
                </a>
            </li>
            <li>
                <a href="{{ route('admin.school-years.index') }}" class="{{ request()->routeIs('admin.school-years*') ? 'active' : '' }}">
                    <i class="fas fa-calendar"></i> School Years
                </a>
            </li>
            <li>
                <a href="{{ route('admin.sections.index') }}" class="{{ request()->routeIs('admin.sections*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i> Sections
                </a>
            </li>
            <li>
                <a href="{{ route('admin.subject-assignments.index') }}" class="{{ request()->routeIs('admin.subject-assignments*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i> Subject Assignment
                </a>
            </li>
            <li>
                <a href="{{ route('admin.scheduling.index') }}" class="{{ request()->routeIs('admin.scheduling*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Class Scheduling
                </a>
            </li>
            {{-- Room Management removed per system update request --}}
            <li>
                <a href="{{ route('admin.users') }}" class="{{ (request()->routeIs('admin.users') || request()->routeIs('admin.users.index')) ? 'active' : '' }}">
                    <i class="fas fa-users"></i> User Management
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-start">
                    <i class="fas fa-check-circle me-3 mt-1"></i>
                    <div class="flex-grow-1">
                        <strong>Success!</strong>
                        <div class="mt-1" style="white-space: pre-line;">{{ session('success') }}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-start">
                    <i class="fas fa-exclamation-circle me-3 mt-1"></i>
                    <div class="flex-grow-1">
                        <strong>Error!</strong>
                        <div class="mt-1" style="white-space: pre-line;">{{ session('error') }}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-start">
                    <i class="fas fa-exclamation-triangle me-3 mt-1"></i>
                    <div class="flex-grow-1">
                        <strong>Warning!</strong>
                        <div class="mt-1" style="white-space: pre-line;">{{ session('warning') }}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-start">
                    <i class="fas fa-info-circle me-3 mt-1"></i>
                    <div class="flex-grow-1">
                        <strong>Info!</strong>
                        <div class="mt-1" style="white-space: pre-line;">{{ session('info') }}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        $(document).ready(function() {
            // Toggle notification dropdown
            $('#notificationBell').click(function(e) {
                e.stopPropagation();
                const dropdown = $('#notificationDropdown');
                dropdown.toggle();
            });
            
            // Close dropdown when clicking outside
            $(document).click(function(e) {
                if (!$(e.target).closest('#notificationBell').length) {
                    $('#notificationDropdown').hide();
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>