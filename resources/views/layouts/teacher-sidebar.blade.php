<!-- Sidebar -->
<div class="sidebar">
    <div class="profile">
        <div class="profile-image-container">
            <img src="{{ asset('images/logo.png') }}" alt="Profile Picture">
            <div class="online-indicator"></div>
        </div>
        <h2>{{ Auth::user()->name }}</h2>
        <p class="role">Teacher</p>
        <span class="online-status">ONLINE</span>
    </div>
    <ul class="menu">
        <li>
            <a href="{{ route('teacher.dashboard') }}" class="{{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('teacher.classlist') }}" class="{{ request()->routeIs('teacher.classlist') ? 'active' : '' }}">
                <i class="fas fa-users"></i> My Students
            </a>
        </li>
        <li>
            <a href="{{ route('teacher.grades.index') }}" class="{{ request()->routeIs('teacher.grades.index') ? 'active' : '' }}">
                <i class="fas fa-graduation-cap"></i> Gradebook
            </a>
        </li>
        <li>
            <a href="{{ route('teacher.grade-management') }}" class="{{ request()->routeIs('teacher.grade-management') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Grade Management
            </a>
        </li>
        <li>
            <a href="{{ route('teacher.subjects') }}" class="{{ request()->routeIs('teacher.subjects') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher"></i> My Subjects
            </a>
        </li>
        <li>
            <a href="{{ route('teacher.profile') }}" class="{{ request()->routeIs('teacher.profile') ? 'active' : '' }}">
                <i class="fas fa-user-circle"></i> My Profile
            </a>
        </li>
        <li>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn">
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
        background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 50%, #2563eb 100%);
        color: white;
        padding: 25px 20px;
        position: fixed;
        height: 100vh;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
    }

    .profile {
        text-align: center;
        margin-bottom: 40px;
        padding-bottom: 25px;
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
        border: 3px solid rgba(255, 255, 255, 0.3);
        object-fit: cover;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.2),
                    0 0 40px rgba(255, 255, 255, 0.1),
                    0 4px 15px rgba(0, 0, 0, 0.2);
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
        margin-bottom: 8px;
    }

    .menu a {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 12px 15px;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-weight: 500;
        position: relative;
    }

    .menu a:hover {
        background-color: rgba(255, 255, 255, 0.15);
        transform: translateX(5px);
    }

    .menu a.active {
        background-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .menu i {
        margin-right: 12px;
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
        padding: 12px 15px;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-weight: 500;
        display: flex;
        align-items: center;
    }

    .logout-btn:hover {
        background-color: rgba(255, 255, 255, 0.15);
        transform: translateX(5px);
    }

    .logout-btn i {
        margin-right: 12px;
        width: 20px;
        text-align: center;
        font-size: 16px;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }
    }
</style>