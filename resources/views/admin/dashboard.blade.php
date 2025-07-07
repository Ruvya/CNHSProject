@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Admin Dashboard</h1>
    <p class="page-subtitle">Welcome back! Here's what's happening at CNHS today.</p>
    <div class="page-actions">
        <a href="{{ route('admin.credentials.generate') }}" class="btn btn-primary">
            <i class="fas fa-key me-2"></i>Generate Student Credentials
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
        <a href="{{ route('admin.users.students.index') }}" class="text-decoration-none">
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
        <a href="{{ route('admin.users.teachers.index') }}" class="text-decoration-none">
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
        <a href="{{ route('admin.subjects.index') }}" class="text-decoration-none">
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
        <a href="{{ route('admin.users.teachers.index') }}" class="text-decoration-none">
            <div class="stat-card stat-card-success">
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

    <!-- New Teachers This Month Card -->
    <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.users.teachers.create') }}" class="text-decoration-none">
            <div class="stat-card stat-card-info">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">New Teachers</div>
                        <div class="stat-card-value">
                            {{ $recentTeachersCount ?? 0 }}
                        </div>
                        <div class="stat-card-subtitle">
                            <i class="fas fa-calendar me-1"></i>
                            This month
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
                        <a href="{{ route('admin.credentials.generate') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-key me-2"></i>
                            Generate Credentials
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.users.students.index') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-user-graduate me-2"></i>
                            Manage Students
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.users.teachers.create') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Add Teacher
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-book me-2"></i>
                            View Subjects
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Section -->
<div class="row g-4 mb-4">
    <!-- Students by Grade Chart -->
    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2 text-primary"></i>
                    Students by Grade Level
                </h5>
            </div>
            <div class="card-body">
                <canvas id="studentsGradeChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Teachers -->
    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2 text-success"></i>
                    Recent Teachers
                </h5>
            </div>
            <div class="card-body">
                @if($recentTeachers && $recentTeachers->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentTeachers->take(5) as $teacher)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-success text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                                    {{ substr($teacher->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $teacher->name }}</h6>
                                    <small class="text-muted">{{ $teacher->email }}</small>
                                </div>
                            </div>
                            <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($teacher->status ?? 'active') }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No recent teachers</p>
                        <a href="{{ route('admin.users.teachers.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus me-1"></i>Add First Teacher
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Monthly Registration Trends -->
<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2 text-info"></i>
                    Monthly Registration Trends (Last 6 Months)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyRegistrationChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Users by Role Chart -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2 text-warning"></i>
                    Users by Role
                </h5>
            </div>
            <div class="card-body">
                <canvas id="usersByRoleChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Pass/Fail by School Year Chart -->
<div class="row g-4 mb-4">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2 text-danger"></i>
                    Pass/Fail by School Year
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    <select id="filterGradeLevel" class="form-select form-select-sm" style="min-width: 140px;">
                        <option value="">All Grade Levels</option>
                        @foreach(\App\Models\Student::distinct()->pluck('grade_level')->filter() as $grade)
                            <option value="{{ $grade }}">{{ $grade }}</option>
                        @endforeach
                    </select>
                    <select id="filterSection" class="form-select form-select-sm" style="min-width: 140px;">
                        <option value="">All Sections</option>
                        @foreach(\App\Models\Student::distinct()->pluck('section')->filter() as $section)
                            <option value="{{ $section }}">{{ $section }}</option>
                        @endforeach
                    </select>
                    <select id="filterSchoolYear" class="form-select form-select-sm" style="min-width: 140px;">
                        <option value="">All School Years</option>
                        @php
                            try {
                                $schoolYears = \App\Models\StudentYearlyRecord::distinct()->pluck('school_year')->sort()->reverse();
                            } catch (\Exception $e) {
                                $schoolYears = collect([date('Y') . '-' . (date('Y') + 1)]);
                            }
                        @endphp
                        @foreach($schoolYears as $sy)
                            <option value="{{ $sy }}">{{ $sy }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body">
                <canvas id="passFailChart" height="300"></canvas>
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
                    <a href="{{ route('admin.credentials.generate') }}" class="btn btn-primary">
                        <i class="fas fa-key me-2"></i>
                        Generate Student Credentials
                        <small class="d-block text-muted">Create login credentials for students</small>
                    </a>
                    <a href="{{ route('admin.users.students.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-users me-2"></i>
                        View Student Accounts
                        <small class="d-block text-muted">Manage existing student accounts</small>
                    </a>
                    <a href="{{ route('admin.credentials.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>
                        Manage Credentials
                        <small class="d-block text-muted">View and manage generated credentials</small>
                    </a>
                    <a href="{{ route('admin.users.teachers.create') }}" class="btn btn-outline-success">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        Add New Teacher
                    </a>
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-warning">
                        <i class="fas fa-book me-2"></i>
                        View Subjects
                    </a>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-info">
                        <i class="fas fa-users me-2"></i>
                        Manage Users
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

/* Chart containers */
.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
}
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js default configuration
    Chart.defaults.font.family = 'Inter, sans-serif';
    Chart.defaults.color = '#6b7280';
    Chart.defaults.plugins.legend.display = true;
    Chart.defaults.plugins.legend.position = 'bottom';

    // Students by Grade Level Chart
    const studentsGradeCtx = document.getElementById('studentsGradeChart').getContext('2d');
    const studentsGradeData = @json($studentsByGrade);

    new Chart(studentsGradeCtx, {
        type: 'bar',
        data: {
            labels: studentsGradeData.map(item => item.grade_level || 'Not Set'),
            datasets: [{
                label: 'Number of Students',
                data: studentsGradeData.map(item => item.count),
                backgroundColor: [
                    '#3b82f6', // Blue
                    '#10b981', // Green
                    '#f59e0b', // Yellow
                    '#ef4444', // Red
                    '#8b5cf6', // Purple
                    '#06b6d4'  // Cyan
                ],
                borderColor: [
                    '#2563eb',
                    '#059669',
                    '#d97706',
                    '#dc2626',
                    '#7c3aed',
                    '#0891b2'
                ],
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#374151',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: '#6b7280'
                    },
                    grid: {
                        color: '#f3f4f6'
                    }
                },
                x: {
                    ticks: {
                        color: '#6b7280'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Students by Track Chart
    const studentsTrackCtx = document.getElementById('studentsTrackChart').getContext('2d');
    const studentsTrackData = @json($studentsByTrack);

    new Chart(studentsTrackCtx, {
        type: 'bar',
        data: {
            labels: studentsTrackData.map(item => item.track || 'Not Set'),
            datasets: [{
                label: 'Number of Students',
                data: studentsTrackData.map(item => item.count),
                backgroundColor: [
                    '#10b981', // Green
                    '#3b82f6', // Blue
                    '#f59e0b', // Yellow
                    '#ef4444', // Red
                    '#8b5cf6'  // Purple
                ],
                borderColor: [
                    '#059669',
                    '#2563eb',
                    '#d97706',
                    '#dc2626',
                    '#7c3aed'
                ],
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#374151',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: '#6b7280'
                    },
                    grid: {
                        color: '#f3f4f6'
                    }
                },
                x: {
                    ticks: {
                        color: '#6b7280'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Monthly Registration Trends Chart
    const monthlyRegistrationCtx = document.getElementById('monthlyRegistrationChart').getContext('2d');
    const monthlyRegistrationData = @json($monthlyRegistrations);

    new Chart(monthlyRegistrationCtx, {
        type: 'bar',
        data: {
            labels: monthlyRegistrationData.map(item => item.month),
            datasets: [
                {
                    label: 'Students',
                    data: monthlyRegistrationData.map(item => item.students),
                    backgroundColor: '#3b82f6',
                    borderColor: '#2563eb',
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false
                },
                {
                    label: 'Teachers',
                    data: monthlyRegistrationData.map(item => item.teachers),
                    backgroundColor: '#10b981',
                    borderColor: '#059669',
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#374151',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: '#6b7280'
                    },
                    grid: {
                        color: '#f3f4f6'
                    }
                },
                x: {
                    ticks: {
                        color: '#6b7280'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Users by Role Chart (Doughnut)
    const usersByRoleCtx = document.getElementById('usersByRoleChart').getContext('2d');
    const usersByRoleData = @json($usersByRole);

    new Chart(usersByRoleCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(usersByRoleData),
            datasets: [{
                data: Object.values(usersByRoleData),
                backgroundColor: [
                    '#ef4444', // Red for Admin
                    '#10b981', // Green for Teacher
                    '#3b82f6'  // Blue for Student
                ],
                borderColor: [
                    '#dc2626',
                    '#059669',
                    '#2563eb'
                ],
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const dataset = data.datasets[0];
                                    const value = dataset.data[i];
                                    return {
                                        text: `${label}: ${value}`,
                                        fillStyle: dataset.backgroundColor[i],
                                        strokeStyle: dataset.borderColor[i],
                                        lineWidth: dataset.borderWidth,
                                        pointStyle: 'circle',
                                        hidden: false,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#374151',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return `${context.label}: ${context.parsed} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });

    // Pass/Fail by School Year Chart
    let passFailChart;
    const passFailCtx = document.getElementById('passFailChart').getContext('2d');
    const filterGradeLevel = document.getElementById('filterGradeLevel');
    const filterSection = document.getElementById('filterSection');
    const filterSchoolYear = document.getElementById('filterSchoolYear');

    function fetchPassFailData() {
        const params = new URLSearchParams();
        if (filterGradeLevel.value) params.append('grade_level', filterGradeLevel.value);
        if (filterSection.value) params.append('section', filterSection.value);
        if (filterSchoolYear.value) params.append('school_year', filterSchoolYear.value);
        fetch(`/admin/dashboard/pass-fail-stats?${params.toString()}`)
            .then(res => res.json())
            .then(res => {
                const stats = res.data || {};
                const years = Object.keys(stats);
                const passed = years.map(y => stats[y].passed);
                const failed = years.map(y => stats[y].failed);
                renderPassFailChart(years, passed, failed);
            });
    }

    function renderPassFailChart(labels, passedData, failedData) {
        if (passFailChart) passFailChart.destroy();
        passFailChart = new Chart(passFailCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Passed',
                        data: passedData,
                        backgroundColor: '#10b981', // Green
                        borderColor: '#059669',
                        borderWidth: 2,
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Failed',
                        data: failedData,
                        backgroundColor: '#ef4444', // Red
                        borderColor: '#dc2626',
                        borderWidth: 2,
                        borderRadius: 6,
                        borderSkipped: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'bottom' },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#374151',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#6b7280' },
                        grid: { color: '#f3f4f6' }
                    },
                    x: {
                        ticks: { color: '#6b7280' },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Fetch data on load and when filters change
    [filterGradeLevel, filterSection, filterSchoolYear].forEach(el => {
        el.addEventListener('change', fetchPassFailData);
    });
    fetchPassFailData();
});
</script>
@endpush
