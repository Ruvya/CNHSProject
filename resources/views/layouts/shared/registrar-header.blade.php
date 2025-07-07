<!-- resources/views/layouts/shared/registrar-header.blade.php -->
<header class="registrar-header">
    <a href="{{ route('registrar.dashboard') }}" class="logo">
        <i class="fas fa-school"></i>
        <span>CNHS Portal</span>
    </a>

    <div class="header-actions">
        <div class="header-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search for students, subjects...">
        </div>

        <div class="dropdown">
            @if(auth()->guard('registrar')->check() && auth()->guard('registrar')->user())
                @php
                    $registrar = auth()->guard('registrar')->user();
                    $firstName = $registrar->first_name ?? $registrar->name ?? 'Registrar';
                    $lastName = $registrar->last_name ?? '';
                    $fullName = trim($firstName . ' ' . $lastName);
                    $initial = strtoupper(substr($firstName, 0, 1));
                @endphp
                <a href="#" class="header-profile" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-avatar">
                        {{ $initial }}
                    </div>
                    <div class="d-none d-md-block">
                        <div style="font-weight: 600;">{{ $fullName }}</div>
                        <div style="font-size: 0.875rem; opacity: 0.8;">Registrar</div>
                    </div>
                    <i class="fas fa-chevron-down ms-2" style="font-size: 0.8rem;"></i>
                </a>
            @else
                <a href="#" class="header-profile" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-avatar">
                        R
                    </div>
                    <div class="d-none d-md-block">
                        <div style="font-weight: 600;">Registrar</div>
                        <div style="font-size: 0.875rem; opacity: 0.8;">Registrar</div>
                    </div>
                    <i class="fas fa-chevron-down ms-2" style="font-size: 0.8rem;"></i>
                </a>
            @endif
            <ul class="dropdown-menu dropdown-menu-end" style="border-radius: var(--border-radius); box-shadow: var(--box-shadow-lg); border: none; padding: 0.5rem 0;">
                <li><a class="dropdown-item" href="{{ route('registrar.profile') }}"><i class="fas fa-user-circle me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-cogs me-2"></i>Settings</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-life-ring me-2"></i>Support</a></li>
                <li><hr class="dropdown-divider" style="border-color: #e2e8f0;"></li>
                <li>
                    <form method="POST" action="{{ route('registrar.logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header> 