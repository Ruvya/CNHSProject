<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal Dashboard - CNHS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Root Variables */
        :root {
            --sidebar-width: 250px;
            --primary-color: #0D47A1;
            --primary-dark: #002171;
            --accent-color: #FFD700;
        }
        
        /* Global Styles */
        body {
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        /* Layout Components */
        sidebar {
            width: var(--sidebar-width);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background-color: #012970;
            padding-top: 1rem;
            transition: all 0.3s;
            z-index: 1000;
        }

        content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            transition: all 0.3s;
        }

        /* Navbar Styles */
        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Sidebar Styles */
        .sidebar-link {
            color: #fff;
            text-decoration: none;
            padding: 0.8rem 1.5rem;
            display: block;
            transition: all 0.3s;
            opacity: 0.8;
        }

        .sidebar-link:hover {
            opacity: 1;
            background-color: rgba(255,255,255,0.1);
            color: #fff;
        }

        .sidebar-link.active {
            opacity: 1;
            background-color: rgba(255,255,255,0.2);
            border-left: 4px solid #fff;
        }

        .sidebar-link i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }

        /* Profile Section */
        .profile-section {
            padding: 1rem;
            text-align: center;
            color: #fff;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }

        .profile-section img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 0.5rem;
        }

        /* Card Styles */
        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }

        /* Hero Sections */
        .hero-section,
        .about-hero-section,
        .academics-hero-section,
        .contact-hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('../images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            min-height: 80vh;
            color: white;
        }

        .hero-box {
            background-color: rgba(13, 71, 161, 0.85);
            padding: 3rem;
            border: 3px solid var(--accent-color);
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            display: inline-block;
            margin: 0 auto;
            max-width: 90%;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin: 0;
            color: white;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            opacity: 0.9;
            color: var(--accent-color);
            margin: 0;
        }

        /* Quick Links Section */
        .quick-link-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .quick-link-card:hover {
            transform: translateY(-10px);
        }

        /* Contact Info Cards */
        .contact-info-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            height: 100%;
            transition: transform 0.3s ease;
        }

        .contact-info-card:hover {
            transform: translateY(-5px);
        }

        /* Map Container */
        .map-container {
            overflow: hidden;
            position: relative;
            height: 450px;
        }

        .map-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* Form Elements */
        .form-control {
            padding: 0.75rem;
            border-radius: 0.5rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 71, 161, 0.25);
        }

        /* Buttons */
        .btn-custom {
            background-color: var(--accent-color);
            color: var(--primary-color);
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: white;
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* Section Styles */
        .section-title {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Utility Classes */
        .border-left-primary { border-left: 4px solid #4e73df; }
        .border-left-success { border-left: 4px solid #1cc88a; }
        .border-left-info { border-left: 4px solid #36b9cc; }
        .border-left-warning { border-left: 4px solid #f6c23e; }

        /* News Section Styles */
        .news-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .news-card .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .news-card .card-body {
            padding: 1.5rem;
        }

        .news-card .card-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .news-card .card-text {
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .news-meta {
            font-size: 0.9rem;
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .news-meta i {
            color: var(--primary-color);
        }

        .news-category {
            display: inline-block;
            padding: 0.25rem 1rem;
            background: rgba(13, 71, 161, 0.1);
            color: var(--primary-color);
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .news-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: gap 0.3s ease;
        }

        .news-link:hover {
            gap: 0.75rem;
            color: var(--primary-dark);
        }

        .featured-news {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            margin-bottom: 2rem;
        }

        .featured-news img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        .featured-news-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 2rem;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: white;
        }

        .featured-news-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .featured-news-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Responsive Adjustments */
        @media (max-width: 1024px) {
            content {
                margin-left: 0;
                padding: 1rem;
            }
        }

        @media (max-width: 768px) {
            .hero-box {
                padding: 2rem;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 576px) {
            .hero-box {
                padding: 1.5rem;
            }
            
            .hero-title {
                font-size: 1.75rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="profile-section">
            <img src="{{ asset('images/principal-avatar.jpg') }}" alt="Principal" onerror="this.src='https://via.placeholder.com/80'">
            <h6 class="mb-0">Principal</h6>
            <small>Administrator</small>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('principal.dashboard') }}" class="sidebar-link {{ request()->routeIs('principal.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="" class="sidebar-link">
                <i class="fas fa-users"></i> Students
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-chalkboard-teacher"></i> Teachers
            </a>
            <a href="{{ route('principal.announcements.index') }}" class="sidebar-link {{ request()->routeIs('principal.announcements.*') ? 'active' : '' }}">
                <i class="fas fa-bullhorn"></i> Announcements
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-calendar-alt"></i> Events
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-file-alt"></i> Reports
            </a>
        </div>
    </nav>

    <!-- Page Content -->
    <div id="content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light mb-4">
            <div class="container-fluid">
                <button type="button" class="btn" id="sidebarCollapse">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="ms-auto d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger">3</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">New student registration</a></li>
                            <li><a class="dropdown-item" href="#">Teacher meeting reminder</a></li>
                            <li><a class="dropdown-item" href="#">System update</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarCollapse').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            
            if (sidebar.style.marginLeft === '-250px') {
                sidebar.style.marginLeft = '0';
                content.style.marginLeft = 'var(--sidebar-width)';
            } else {
                sidebar.style.marginLeft = '-250px';
                content.style.marginLeft = '0';
            }
        });
    </script>
</body>
</html> 