<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - CNHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #334155;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Header */
        .admin-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            box-shadow: var(--box-shadow);
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 2rem;
        }

        .admin-header .logo {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .admin-header .logo i {
            font-size: 2rem;
        }

        .header-actions {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-search {
            position: relative;
            display: none;
        }

        .header-search input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            color: white;
            width: 300px;
        }

        .header-search input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .header-search i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
        }

        .header-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: background-color 0.2s;
        }

        .header-profile:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 250px;
            background-color: #012970;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
            top: 0;
            left: 0;
            overflow-y: auto;
            z-index: 999;
            transition: transform 0.3s ease;
        }

        /* Header */
        .admin-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            box-shadow: var(--box-shadow);
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 2rem;
        }

        .admin-header .logo {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .admin-header .logo i {
            font-size: 2rem;
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
            padding-top: 80px; /* Adjust for header height */
            transition: margin-left 0.3s ease;
        }

        .sidebar-content {
            padding-top: var(--header-height);
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
            color: white;
        }

        .profile p {
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
        .admin-main {
            margin-left: 250px;
            margin-top: var(--header-height);
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
            background: #ffffff;
            position: relative;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--secondary-color);
            font-size: 1rem;
        }

        .page-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
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

        /* Hover effects for clickable cards */
        a .stat-card:hover .stat-card-icon {
            transform: scale(1.1);
        }

        a .stat-card:hover .stat-card-value {
            color: var(--primary-color);
        }

        /* Statistics Grid Improvements */
        .row.g-3 {
            margin-bottom: 1.5rem;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .header-search {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block;
            }

            .admin-sidebar {
                width: 100%;
                height: auto;
                position: relative;
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
                padding: 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .stat-card-body {
                padding: 0.75rem;
                min-height: 85px;
                gap: 0.75rem;
            }

            .stat-card-icon {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }

            .stat-card-value {
                font-size: 1.5rem;
            }

            .stat-card-title {
                font-size: 0.7rem;
            }

            .stat-card-subtitle {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 576px) {
            .stat-card-body {
                flex-direction: column;
                text-align: center;
                padding: 0.75rem;
                min-height: 110px;
                gap: 0.5rem;
            }

            .stat-card-value {
                font-size: 1.25rem;
            }

            .stat-card-title {
                font-size: 0.65rem;
            }

            .stat-card-subtitle {
                font-size: 0.65rem;
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

        /* Additional Styles for Activity Timeline */
        .activity-timeline {
            position: relative;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .activity-item:last-child {
            margin-bottom: 0;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--dark-color);
        }

        .activity-description {
            font-size: 0.875rem;
            color: var(--secondary-color);
            margin-bottom: 0.25rem;
        }

        .activity-time {
            font-size: 0.75rem;
            color: var(--secondary-color);
        }

        /* Status Indicators */
        .status-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .status-item:last-child {
            margin-bottom: 0;
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .status-text {
            flex: 1;
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

        /* Quick Actions Buttons */
        .btn-outline-primary:hover,
        .btn-outline-success:hover,
        .btn-outline-warning:hover,
        .btn-outline-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
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

        /* Progress Bar Enhancements */
        .progress {
            background: #e2e8f0;
            border-radius: 4px;
        }

        .progress-bar {
            border-radius: 4px;
        }

        /* Badge Enhancements */
        .badge {
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
        }

        /* Ensure clean backgrounds for all admin content */
        .container, .container-fluid, .row, .col, [class*="col-"] {
            background: transparent !important;
        }

        .table {
            background: #ffffff;
        }

        .modal-content {
            background: #ffffff;
        }

        /* Remove any potential background images from all elements */
        * {
            background-image: none !important;
        }

        /* Exception: Keep necessary background images for icons and UI elements */
        .fas, .far, .fab, .fal, .fad, [class*="fa-"] {
            background-image: initial !important;
        }

        @yield('styles')
    </style>
</head>
<body class="admin-theme">
    <!-- Header -->
    <header class="admin-header">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>

        <a href="{{ route('admin.dashboard') }}" class="logo">
            <i class="fas fa-graduation-cap"></i>
            <span>CNHS Admin</span>
        </a>

        <div class="header-actions">
            <div class="header-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search...">
            </div>

            <div class="dropdown">
                <a href="#" class="header-profile" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-avatar">
                        A
                    </div>
                    <div class="d-none d-md-block">
                        <div style="font-weight: 600;">Administrator</div>
                        <div style="font-size: 0.875rem; opacity: 0.8;">admin@cnhs.edu</div>
                    </div>
                    <i class="fas fa-chevron-down ms-2"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <div class="admin-sidebar" id="adminSidebar">
        @include('layouts.shared.admin-sidebar')
    </div>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        @include('layouts.shared.admin-header')

        <main class="main-content-area py-4">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const adminSidebar = document.getElementById('adminSidebar');

            if (sidebarToggle && adminSidebar) {
                sidebarToggle.addEventListener('click', function() {
                    adminSidebar.classList.toggle('show');
                });

                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(event) {
                    if (window.innerWidth <= 768) {
                        if (!adminSidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                            adminSidebar.classList.remove('show');
                        }
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>