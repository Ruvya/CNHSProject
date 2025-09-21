<!-- resources/views/layouts/shared/admin-sidebar.blade.php -->
<div class="sidebar-content">
    <div class="profile">
        <img src="{{ auth()->guard('admin')->user()->profile_picture ?? asset('images/cnhs.png') }}" alt="Profile Picture">
        <h2>{{ auth()->guard('admin')->user()->name ?? 'Administrator' }}</h2>
        <p>Admin</p>
    </div>
    <ul class="menu">
        <li>
            <a href="{{ route('registrar.dashboard') }}" class="{{ request()->routeIs('registrar.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.school-years.index') }}" class="{{ request()->routeIs('admin.school-years*') ? 'active' : '' }}">
                <i class="fas fa-calendar"></i>
                <span>School Years</span>
            </a>
        </li>
        <li>
            <a href="{{ route('registrar.students.index') }}" class="{{ request()->routeIs('registrar.students*') && !request()->routeIs('registrar.students.yearly-records*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i>
                <span>Student Records</span>
            </a>
        </li>
        <li>
            <a href="{{ route('registrar.yearly-records.index') }}" class="{{ request()->routeIs('registrar.yearly-records*') || request()->routeIs('registrar.students.yearly-records*') || request()->routeIs('registrar.teachers.yearly-records*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Yearly Records</span>
            </a>
        </li>
        <li>
            <a href="{{ route('registrar.subjects.index') }}" class="{{ request()->routeIs('registrar.subjects*') ? 'active' : '' }}">
                <i class="fas fa-book"></i>
                <span>Subjects</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.sections.index') }}" class="{{ request()->routeIs('admin.sections*') ? 'active' : '' }}">
                <i class="fas fa-layer-group"></i>
                <span>Sections</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.subject-assignments.index') }}" class="{{ request()->routeIs('admin.subject-assignments*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard"></i>
                <span>Subject Assignments</span>
            </a>
        </li>
        <li>
            <a href="{{ route('registrar.subject-assignments.index') }}" class="{{ request()->routeIs('registrar.subject-assignments*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Subject Assignment</span>
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
            <a href="{{ route('registrar.grading-scales.index') }}" class="{{ request()->routeIs('admin.grading-scales*') ? 'active' : '' }}">
                <i class="fas fa-percentage me-2"></i> Grading Scales
            </a>
        </li>
        <li>
            <a href="{{ route('registrar.profile') }}" class="{{ request()->routeIs('registrar.profile') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
        </li>
    </ul>
    
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>
