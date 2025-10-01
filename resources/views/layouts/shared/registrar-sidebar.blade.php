<!-- resources/views/layouts/shared/registrar-sidebar.blade.php -->
<div class="sidebar-content">
    <div class="profile">
        @if(auth()->guard('registrar')->check() && auth()->guard('registrar')->user())
            @php
                $registrar = auth()->guard('registrar')->user();
                $profilePicture = (isset($registrar->profile_picture) && $registrar->profile_picture)
                    ? asset('storage/' . $registrar->profile_picture)
                    : asset('images/cnhs.png');

                // Try different name properties with fallbacks
                $displayName = 'Registrar';
                if (isset($registrar->first_name) && $registrar->first_name) {
                    $displayName = $registrar->first_name;
                } elseif (isset($registrar->name) && $registrar->name) {
                    $displayName = $registrar->name;
                } elseif (method_exists($registrar, 'getDisplayNameAttribute')) {
                    $displayName = $registrar->display_name;
                }
            @endphp
            <img src="{{ $profilePicture }}" alt="Profile Picture" onerror="this.src='{{ asset('images/cnhs.png') }}'">
            <h2>{{ $displayName }}</h2>
        @else
            <img src="{{ asset('images/cnhs.png') }}" alt="Profile Picture">
            <h2>Registrar</h2>
        @endif
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
            <a href="{{ route('registrar.years.index') }}" class="{{ request()->routeIs('registrar.years*') ? 'active' : '' }}">
                <i class="fas fa-archive"></i>
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
            <a href="{{ route('registrar.subject-management.index') }}" class="{{ request()->routeIs('registrar.subject-management*') ? 'active' : '' }}">
                <i class="fas fa-book"></i>
                <span>Subject Management</span>
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