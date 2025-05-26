@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Welcome back! Here's what's happening at CNHS today.</p>
    <div class="page-actions">
        <a href="{{ route('admin.users.students.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Student
        </a>
        <a href="{{ route('admin.users.teachers.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-plus me-2"></i>Add Teacher
        </a>
    </div>
</div>

<!-- Main Statistics Cards -->
<div class="row g-3 mb-4">
    <!-- Total Students Card -->
    <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.users') }}?filter=students" class="text-decoration-none">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Total Students</div>
                        <div class="stat-card-value">
                            {{ $totalStudents ?? 0 }}
                        </div>
                        <div class="stat-card-subtitle">
                            @if(($totalStudents ?? 0) > 0)
                                <i class="fas fa-arrow-{{ $studentGrowth >= 0 ? 'up' : 'down' }} me-1"></i>
                                {{ abs($studentGrowth) }}% from last month
                            @else
                                <i class="fas fa-info-circle me-1"></i>
                                No students registered
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Total Teachers Card -->
    <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.users') }}?filter=teachers" class="text-decoration-none">
            <div class="stat-card stat-card-success">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Total Teachers</div>
                        <div class="stat-card-value">
                            {{ $totalTeachers ?? 0 }}
                        </div>
                        <div class="stat-card-subtitle">
                            @if(($totalTeachers ?? 0) > 0)
                                <i class="fas fa-arrow-{{ $teacherGrowth >= 0 ? 'up' : 'down' }} me-1"></i>
                                {{ abs($teacherGrowth) }}% from last month
                            @else
                                <i class="fas fa-info-circle me-1"></i>
                                No teachers registered
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Total Subjects Card -->
    <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.subjects') }}" class="text-decoration-none">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Total Subjects</div>
                        <div class="stat-card-value">
                            {{ $totalSubjects ?? 0 }}
                        </div>
                        <div class="stat-card-subtitle">
                            @if(($totalSubjects ?? 0) > 0)
                                <i class="fas fa-check me-1"></i>
                                Active courses
                            @else
                                <i class="fas fa-info-circle me-1"></i>
                                No subjects available
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Active Teachers Card -->
    <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.users') }}?filter=active_teachers" class="text-decoration-none">
            <div class="stat-card stat-card-info">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Active Teachers</div>
                        <div class="stat-card-value">
                            {{ $activeTeachers ?? 0 }}
                        </div>
                        <div class="stat-card-subtitle">
                            @if(($totalTeachers ?? 0) > 0)
                                <i class="fas fa-percentage me-1"></i>
                                {{ round((($activeTeachers ?? 0) / $totalTeachers) * 100, 1) }}% active
                            @else
                                <i class="fas fa-info-circle me-1"></i>
                                No active teachers
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>



<!-- Quick Actions Section -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2 text-warning"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.users.students.create') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>
                            Add Student
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.users.teachers.create') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Add Teacher
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.subjects.create') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-book-plus me-2"></i>
                            Add Subject
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.reports') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-chart-line me-2"></i>
                            View Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Overview Section -->
<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-server me-2 text-success"></i>
                    System Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">System Statistics</h6>
                        <div class="status-item">
                            <div class="status-indicator bg-primary"></div>
                            <div class="status-text">
                                <strong>Total Users</strong>
                                <small class="text-muted d-block">{{ (($totalStudents ?? 0) + ($totalTeachers ?? 0) + ($totalAdmins ?? 1)) }} registered accounts</small>
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="status-indicator bg-success"></div>
                            <div class="status-text">
                                <strong>Active Students</strong>
                                <small class="text-muted d-block">{{ $totalStudents ?? 0 }} enrolled students</small>
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="status-indicator bg-info"></div>
                            <div class="status-text">
                                <strong>Faculty Members</strong>
                                <small class="text-muted d-block">{{ $totalTeachers ?? 0 }} registered teachers</small>
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="status-indicator bg-warning"></div>
                            <div class="status-text">
                                <strong>Available Subjects</strong>
                                <small class="text-muted d-block">{{ $totalSubjects ?? 0 }} courses offered</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">System Status</h6>
                        <div class="status-item">
                            <div class="status-indicator bg-success"></div>
                            <div class="status-text">
                                <strong>Database</strong>
                                <small class="text-muted d-block">Connected and operational</small>
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="status-indicator bg-success"></div>
                            <div class="status-text">
                                <strong>Application</strong>
                                <small class="text-muted d-block">Running smoothly</small>
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="status-indicator bg-warning"></div>
                            <div class="status-text">
                                <strong>Backup Status</strong>
                                <small class="text-muted d-block">Scheduled for tonight</small>
                            </div>
                        </div>
                        @if(isset($recentActivities) && $recentActivities->count() > 0)
                        <div class="status-item">
                            <div class="status-indicator bg-info"></div>
                            <div class="status-text">
                                <strong>Recent Activity</strong>
                                <small class="text-muted d-block">{{ $recentActivities->count() }} recent actions</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Quick Actions -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2 text-warning"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('admin.users.students.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-plus me-2"></i>
                        Add New Student
                    </a>
                    <a href="{{ route('admin.users.teachers.create') }}" class="btn btn-outline-success">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        Add New Teacher
                    </a>
                    <a href="{{ route('admin.subjects.create') }}" class="btn btn-outline-warning">
                        <i class="fas fa-book-plus me-2"></i>
                        Add New Subject
                    </a>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-info">
                        <i class="fas fa-users me-2"></i>
                        Manage Users
                    </a>
                    <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-chart-line me-2"></i>
                        View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('styles')
<style>
/* Status indicators for System Overview */
.status-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding: 0.5rem 0;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.status-text {
    flex: 1;
}

.status-text strong {
    display: block;
    font-size: 0.875rem;
    color: #374151;
    margin-bottom: 0.125rem;
}

.status-text small {
    font-size: 0.75rem;
    color: #6b7280;
}

/* Enhanced Quick Actions buttons */
.btn-outline-primary:hover,
.btn-outline-success:hover,
.btn-outline-warning:hover,
.btn-outline-info:hover,
.btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
</style>
@endsection
