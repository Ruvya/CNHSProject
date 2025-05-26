<!-- Sidebar -->
<div class="sidebar">
    <div class="profile">
        <img src="{{ asset('images/logo.png') }}" alt="Profile Picture">
        <h2>{{ Auth::user()->name }}</h2>
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

<style>
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
</style> 