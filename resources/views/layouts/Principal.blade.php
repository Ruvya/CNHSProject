<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - CNHS Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Root Variables */
        :root {
            --primary-color: #012970;
            --secondary-color: #0d47a1;
            --accent-color: #4154f1;
            --text-color: #012970;
            --background-color: #f6f9ff;
        }
        
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--background-color);
        }

        /* Header Styles */
        .header {
            background: #fff;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: fixed;
            width: calc(100% - 280px);
            top: 0;
            right: 0;
            z-index: 100;
            height: 70px;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-brand img {
            height: 40px;
            width: auto;
        }

        .portal-text {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            letter-spacing: 1px;
            text-transform: uppercase;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1;
            text-decoration: none;
        }

        .portal-text span {
            color: var(--primary-color);
            font-weight: 700;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: var(--primary-color);
            color: #fff;
            padding-top: 1rem;
            z-index: 1000;
            transition: all 0.3s ease;
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

        .profile h2 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: #fff;
        }

        .profile p {
            font-size: 0.9rem;
            opacity: 0.8;
            margin: 0;
        }

        .menu {
            list-style: none;
            padding: 1rem 0;
        }

        .menu li {
            padding: 0 1rem;
            margin-bottom: 0.5rem;
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

        .menu a:hover,
        .menu a.active {
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
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .header {
                width: 100%;
            }

            .main-content {
                margin-left: 0;
            }
        }

        /* Custom Styles */
        .btn-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .card-custom {
            background: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* Additional Styles */
        @yield('styles')
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-brand">
            <img src="{{ asset('images/CNHS.png') }}" alt="CNHS Logo">
            <a href="{{ route('principal.dashboard') }}" class="portal-text">
                CNHS PORTAL
            </a>
        </div>
        <div class="header-actions">
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ Auth::guard('principal')->user()->profile_picture ? asset('storage/' . Auth::guard('principal')->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::guard('principal')->user()->name) . '&size=32&background=007bff&color=ffffff&bold=true' }}" alt="{{ Auth::guard('principal')->user()->name }}" class="rounded-circle" width="32" height="32">
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="{{ route('principal.profile') }}">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile">
            <img src="{{ Auth::guard('principal')->user()->profile_picture ? asset('storage/' . Auth::guard('principal')->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::guard('principal')->user()->name) . '&size=100&background=007bff&color=ffffff&bold=true' }}" alt="{{ Auth::guard('principal')->user()->name }}" class="profile-pic">
            <h2>{{ Auth::user()->name ?? 'Principal' }}</h2>
            <p>School Principal</p>
        </div>

        <ul class="menu">
            <li>
                <a href="{{ route('principal.dashboard') }}" class="{{ request()->routeIs('principal.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('principal.announcements.index') }}" class="{{ request()->routeIs('principal.announcements.*') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i> Announcements
                </a>
            </li>
            <li>
                <a href="{{ route('principal.teachers.index') }}" class="{{ request()->routeIs('principal.teachers.*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i> Teachers
                </a>
            </li>
            <li>
                <a href="{{ route('principal.profile') }}" class="{{ request()->routeIs('principal.profile') ? 'active' : '' }}">
                    <i class="fas fa-user"></i> Profile
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        @if(isset($recentAnnouncements) && $recentAnnouncements->count() > 0)
            @foreach($recentAnnouncements->where('status', 'active') as $announcement)
                ...
            @endforeach
        @else
            <div>No announcements yet</div>
        @endif
        <pre>{{ print_r($recentAnnouncements, true) }}</pre>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html> 