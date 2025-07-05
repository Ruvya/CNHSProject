<!-- resources/views/layouts/shared/admin-header.blade.php -->
<header class="admin-header">
    <a href="{{ route('admin.dashboard') }}" class="logo">
        <i class="fas fa-school"></i>
        <span>CNHS Admin Portal</span>
    </a>

    <div class="header-actions">
        <div class="header-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search students, teachers, subjects...">
        </div>

        <div class="dropdown">
            <button class="btn btn-link text-white dropdown-toggle" type="button" id="notificationDropdown" data-bs-toggle="dropdown">
                <i class="fas fa-bell"></i>
                <span class="badge bg-danger">3</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">Notifications</h6></li>
                <li><a class="dropdown-item" href="#">New teacher registered</a></li>
                <li><a class="dropdown-item" href="#">Email test completed</a></li>
                <li><a class="dropdown-item" href="#">System update available</a></li>
            </ul>
        </div>

        <div class="dropdown">
            <button class="btn btn-link text-white dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown">
                <div class="profile-avatar me-2">
                    {{ substr(auth()->guard('admin')->user()->name ?? 'A', 0, 1) }}
                </div>
                <span>{{ auth()->guard('admin')->user()->name ?? 'Admin' }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">Admin Account</h6></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <form id="logout-form-header" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</header>
