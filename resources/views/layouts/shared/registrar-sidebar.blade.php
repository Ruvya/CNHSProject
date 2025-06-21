<!-- resources/views/layouts/shared/registrar-sidebar.blade.php -->
<div class="sidebar-content">
    <div class="profile">
        <img src="{{ auth()->guard('registrar')->user()->profile_picture ? asset('storage/' . auth()->guard('registrar')->user()->profile_picture) : asset('images/cnhs.png') }}" alt="Profile Picture">
        <h2>{{ auth()->guard('registrar')->user()->first_name }}</h2>
        <p>Registrar</p>
    </div>
    <ul class="menu">
        <li>
            <a href="{{ route('registrar.dashboard') }}" class="{{ request()->routeIs('registrar.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('registrar.students.index') }}" class="{{ request()->routeIs('registrar.students*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i>
                <span>Student Records</span>
            </a>
        </li>
        <li>
            <a href="{{ route('registrar.subjects.index') }}" class="{{ request()->routeIs('registrar.subjects*') ? 'active' : '' }}">
                <i class="fas fa-book"></i>
                <span>Subjects</span>
            </a>
        </li>
        <li>
            <a href="{{ route('registrar.teacher-assignments.index') }}" class="{{ request()->routeIs('registrar.teacher-assignments*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Teacher Assignment</span>
            </a>
        </li>
        <li>
            <a href="{{ route('registrar.automatic-subject-assignment.index') }}" class="{{ request()->routeIs('registrar.automatic-subject-assignment*') ? 'active' : '' }}">
                <i class="fas fa-cogs"></i>
                <span>Auto-Assign Subjects</span>
            </a>
        </li>
        <li>
            <a href="{{ route('registrar.student-subject-assignments.index') }}" class="{{ request()->routeIs('registrar.student-subject-assignments*') ? 'active' : '' }}">
                <i class="fas fa-edit"></i>
                <span>Manual-Assign Subjects</span>
            </a>
        </li>
        <li>
            <a href="{{ route('registrar.profile') }}" class="{{ request()->routeIs('registrar.profile') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
        </li>
    </ul>
</div> 