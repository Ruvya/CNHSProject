<!-- resources/views/layouts/shared/registrar-sidebar-safe.blade.php -->
<div class="sidebar-content">
    <div class="profile">
        <img src="{{ asset('images/cnhs.png') }}" alt="Profile Picture">
        <h2>CNHS Registrar</h2>
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
            <a href="{{ route('registrar.automatic-subject-assignment.index') }}" class="{{ request()->routeIs('registrar.automatic-subject-assignment*') ? 'active' : '' }}">
                <i class="fas fa-cogs"></i>
                <span>Auto-Assign Subjects</span>
            </a>
        </li>
        <li>
            <a href="{{ route('registrar.student-subject-assignments.index') }}" class="{{ request()->routeIs('registrar.student-subject-assignments*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i>
                <span>Assign Subjects to Students</span>
            </a>
        </li>
    </ul>
</div>
