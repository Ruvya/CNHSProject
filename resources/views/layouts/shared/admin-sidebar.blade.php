<!-- resources/views/layouts/shared/admin-sidebar.blade.php -->
<div class="sidebar-content">
    <div class="profile">
        <img src="{{ auth()->guard('admin')->user()->profile_picture ?? asset('images/cnhs.png') }}" alt="Profile Picture">
        <h2>{{ auth()->guard('admin')->user()->name ?? 'Administrator' }}</h2>
        <p>Admin</p>
    </div>
    <ul class="menu">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.credentials.generate') }}" class="{{ request()->routeIs('admin.credentials*') ? 'active' : '' }}">
                <i class="fas fa-key"></i>
                <span>Student Credentials</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.users.students.index') }}" class="{{ request()->routeIs('admin.users.students*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i>
                <span>Student Management</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.users.teachers.index') }}" class="{{ request()->routeIs('admin.users.teachers*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Teacher Management</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.users.teachers.create') }}" class="{{ request()->routeIs('admin.users.teachers.create') ? 'active' : '' }}">
                <i class="fas fa-user-plus"></i>
                <span>Add Teacher</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.subjects.index') }}" class="{{ request()->routeIs('admin.subjects*') ? 'active' : '' }}">
                <i class="fas fa-book"></i>
                <span>Subjects</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-danger">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
    
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>
