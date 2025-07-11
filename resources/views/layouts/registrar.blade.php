<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Registrar Dashboard') - CNHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('layouts.shared.dashboard-styles')
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --sidebar-width: 280px;
            --header-height: 70px;
            --border-radius: 12px;
            --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --box-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f1f5f9;
            color: #334155;
            line-height: 1.6;
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

        /* Mobile Sidebar Toggle */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: background-color 0.2s;
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            margin-left: 280px;
            padding: 90px 2rem 2rem; /* Adjusted padding for fixed header */
            background-color: #f1f5f9;
            min-height: 100vh;
        }

        /* Pagination Styles */
        .pagination {
            margin-top: 2rem;
            justify-content: center;
        }

        .pagination .page-link {
            border-radius: 8px !important;
            margin: 0 4px;
            color: var(--primary-color);
            border-color: #dee2e6;
            font-weight: 500;
        }

        .pagination .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
        }

        .pagination .page-link svg {
            width: 1rem;
            height: 1rem;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: none;
            overflow: hidden;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Modern Compact Stat Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
            overflow: hidden;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #e2e8f0;
        }

        .stat-card-body {
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            min-height: 100px;
        }

        .stat-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            flex-shrink: 0;
        }

        .stat-card-content {
            flex: 1;
            min-width: 0;
        }

        .stat-card-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .stat-card-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .stat-card-subtitle {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
        }

        /* Card Color Variants */
        .stat-card-primary .stat-card-icon {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        .stat-card-success .stat-card-icon {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .stat-card-warning .stat-card-icon {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .stat-card-info .stat-card-icon {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
        }

        .stat-card-secondary .stat-card-icon {
            background: linear-gradient(135deg, #64748b, #475569);
        }

        /* Enhanced Button Styles */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.625rem 1.25rem;
            transition: all 0.2s;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Enhanced Table Styles */
        .table {
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .table th {
            background: var(--light-color);
            border: none;
            font-weight: 600;
            color: var(--dark-color);
            padding: 1rem;
        }

        .table td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background: rgba(37, 99, 235, 0.05);
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

        /* Utilities */
        .text-primary { color: var(--primary-color) !important; }
        .text-success { color: var(--success-color) !important; }
        .text-warning { color: var(--warning-color) !important; }
        .text-danger { color: var(--danger-color) !important; }
        .text-info { color: var(--info-color) !important; }

        .bg-primary { background-color: var(--primary-color) !important; }
        .bg-success { background-color: var(--success-color) !important; }
        .bg-warning { background-color: var(--warning-color) !important; }
        .bg-danger { background-color: var(--danger-color) !important; }
        .bg-info { background-color: var(--info-color) !important; }


    </style>
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <img src="{{ asset('images/CNHS.png') }}" alt="CNHS Logo" class="logo">
            <h1>CNHS Registrar Portal</h1>
        </div>
        <div class="header-right">
            <div class="dropdown user-profile">
                @if(Auth::guard('registrar')->check())
                    @php
                        $registrar = Auth::guard('registrar')->user();
                        $firstName = $registrar->first_name ?? $registrar->name ?? 'Registrar';
                        $lastName = $registrar->last_name ?? '';
                        $fullName = trim($firstName . ' ' . $lastName);
                    @endphp
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::guard('registrar')->user()->profile_picture ? asset('storage/' . Auth::guard('registrar')->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($fullName) . '&background=4e73df&color=ffffff' }}" alt="Profile" class="header-profile-pic">
                        <span class="d-none d-sm-inline mx-1 user-name">{{ $fullName }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                        <li>
                            <a class="dropdown-item" href="{{ route('registrar.profile') }}">
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
                <img src="{{ asset('images/logo.png') }}" alt="Profile Picture">
                <div class="online-indicator"></div>
            </div>
            <h2>{{ Auth::guard('registrar')->check() ? (Auth::guard('registrar')->user()->first_name ?? Auth::guard('registrar')->user()->name ?? 'Registrar') : 'Registrar' }}</h2>
            <p class="role">Registrar</p>
            <span class="online-status">ONLINE</span>
        </div>
        <ul class="menu">
            <li>
                <a href="{{ route('registrar.dashboard') }}" class="{{ request()->routeIs('registrar.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('registrar.students.index') }}" class="{{ request()->routeIs('registrar.students*') && !request()->routeIs('registrar.students.yearly-records*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate"></i> Student Records
                </a>
            </li>
            <li>
                <a href="{{ route('registrar.yearly-records.index') }}" class="{{ request()->routeIs('registrar.yearly-records*') || request()->routeIs('registrar.students.yearly-records*') || request()->routeIs('registrar.teachers.yearly-records*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Yearly Records
                </a>
            </li>
            <li>
                <a href="{{ route('registrar.subjects.index') }}" class="{{ request()->routeIs('registrar.subjects*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i> Subjects
                </a>
            </li>
            <li>
                <a href="{{ route('registrar.subject-assignments.index') }}" class="{{ request()->routeIs('registrar.subject-assignments*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i> Subject Assignment
                </a>
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

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    @stack('scripts')
</body>
</html>