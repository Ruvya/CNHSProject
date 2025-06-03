@extends('layouts.registrar')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="page-title">
                <i class="fas fa-tachometer-alt me-3 text-primary"></i>
                Registrar Dashboard
            </h1>
            <p class="page-subtitle">
                Welcome back, {{ auth()->guard('registrar')->user()->first_name }}! Here's your system overview.
            </p>
            <div class="text-muted">
                <i class="fas fa-clock me-2"></i>
                {{ now()->format('l, F j, Y - g:i A') }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="page-actions">
                <a href="{{ route('registrar.students.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>
                    Generate Credentials
                </a>
                <a href="{{ route('registrar.subjects.create') }}" class="btn btn-outline-primary">
                    <i class="fas fa-book-plus me-2"></i>
                    Add Subject
                </a>
            </div>
        </div>
    </div>
</div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Students Card -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Total Students</div>
                        <div class="stat-card-value">{{ number_format($totalStudents) }}</div>
                        <div class="stat-card-subtitle">+{{ $recentStudentsCount ?? 0 }} this month</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Teachers Card -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-success">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Total Teachers</div>
                        <div class="stat-card-value">{{ number_format($totalTeachers) }}</div>
                        <div class="stat-card-subtitle">{{ $assignmentStats['assignment_rate'] ?? 0 }}% assigned</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Subjects Card -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-info">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Total Subjects</div>
                        <div class="stat-card-value">{{ number_format($totalSubjects) }}</div>
                        <div class="stat-card-subtitle">+{{ $recentSubjectsCount ?? 0 }} recent</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Subjects Card -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">My Subjects</div>
                        <div class="stat-card-value">{{ number_format($mySubjects) }}</div>
                        <div class="stat-card-subtitle">Created by you</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <h5 class="section-title mb-3">
                <i class="fas fa-bolt text-primary me-2"></i>
                Quick Actions
            </h5>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('registrar.students.create') }}" class="quick-action-card">
                <div class="quick-action-icon bg-primary">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="quick-action-content">
                    <h6>Generate Credentials</h6>
                    <p>Create student login accounts</p>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('registrar.subjects.create') }}" class="quick-action-card">
                <div class="quick-action-icon bg-success">
                    <i class="fas fa-book-plus"></i>
                </div>
                <div class="quick-action-content">
                    <h6>Add Subject</h6>
                    <p>Create new subject</p>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('registrar.subjects.index') }}" class="quick-action-card">
                <div class="quick-action-icon bg-info">
                    <i class="fas fa-book"></i>
                </div>
                <div class="quick-action-content">
                    <h6>Manage Subjects</h6>
                    <p>View and edit subjects</p>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('registrar.students.index') }}" class="quick-action-card">
                <div class="quick-action-icon bg-warning">
                    <i class="fas fa-users"></i>
                </div>
                <div class="quick-action-content">
                    <h6>Student Records</h6>
                    <p>Manage student accounts</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Left Column - Charts and Analytics -->
        <div class="col-lg-8">
            <!-- Analytics Charts -->
            <div class="row g-4 mb-4">
                <!-- Students by Grade Chart -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-chart-pie text-primary me-2"></i>
                                Students by Grade
                            </h6>
                        </div>
                        <div class="card-body">
                            <canvas id="studentsGradeChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Subjects by Track Chart -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-chart-bar text-success me-2"></i>
                                Subjects by Track
                            </h6>
                        </div>
                        <div class="card-body">
                            <canvas id="subjectsTrackChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Overview -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        System Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">System Statistics</h6>
                            <div class="overview-stats">
                                <div class="overview-stat-item">
                                    <div class="overview-stat-icon bg-primary">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="overview-stat-content">
                                        <div class="overview-stat-label">Total Users</div>
                                        <div class="overview-stat-value">{{ number_format($totalStudents + $totalTeachers) }} accounts</div>
                                    </div>
                                </div>
                                <div class="overview-stat-item">
                                    <div class="overview-stat-icon bg-success">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div class="overview-stat-content">
                                        <div class="overview-stat-label">Active Students</div>
                                        <div class="overview-stat-value">{{ number_format($totalStudents) }} students</div>
                                    </div>
                                </div>
                                <div class="overview-stat-item">
                                    <div class="overview-stat-icon bg-info">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <div class="overview-stat-content">
                                        <div class="overview-stat-label">Faculty Members</div>
                                        <div class="overview-stat-value">{{ number_format($totalTeachers) }} teachers</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">System Status</h6>
                            <div class="overview-stats">
                                <div class="overview-stat-item">
                                    <div class="overview-stat-icon bg-success">
                                        <i class="fas fa-database"></i>
                                    </div>
                                    <div class="overview-stat-content">
                                        <div class="overview-stat-label">Database</div>
                                        <div class="overview-stat-value">Operational</div>
                                    </div>
                                </div>
                                <div class="overview-stat-item">
                                    <div class="overview-stat-icon bg-primary">
                                        <i class="fas fa-cogs"></i>
                                    </div>
                                    <div class="overview-stat-content">
                                        <div class="overview-stat-label">Application</div>
                                        <div class="overview-stat-value">Running</div>
                                    </div>
                                </div>
                                <div class="overview-stat-item">
                                    <div class="overview-stat-icon bg-info">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div class="overview-stat-content">
                                        <div class="overview-stat-label">Security</div>
                                        <div class="overview-stat-value">Protected</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Right Column - Recent Activity -->
        <div class="col-lg-4">
            <!-- Recent Subjects -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history text-primary me-2"></i>
                        Recent Subjects
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($myRecentSubjects) && $myRecentSubjects->count() > 0)
                        <div class="recent-items">
                            @foreach($myRecentSubjects->take(5) as $subject)
                                <div class="recent-item">
                                    <div class="recent-item-content">
                                        <div class="recent-item-title">{{ $subject->name }}</div>
                                        <div class="recent-item-meta">
                                            <span class="badge bg-primary me-1">{{ $subject->code }}</span>
                                            <span class="badge bg-info">Grade {{ $subject->grade_level }}</span>
                                        </div>
                                        <div class="recent-item-date">{{ $subject->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('registrar.subjects.index') }}" class="btn btn-outline-primary btn-sm">
                                View All Subjects
                            </a>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-book fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No subjects created yet</p>
                            <a href="{{ route('registrar.subjects.create') }}" class="btn btn-primary btn-sm mt-2">
                                Create Your First Subject
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Students -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-user-graduate text-success me-2"></i>
                        Recent Students
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($recentStudents) && $recentStudents->count() > 0)
                        <div class="recent-items">
                            @foreach($recentStudents->take(5) as $student)
                                <div class="recent-item">
                                    <div class="d-flex align-items-center">
                                        <div class="student-avatar me-3">
                                            {{ substr($student->first_name ?? 'S', 0, 1) }}
                                        </div>
                                        <div class="recent-item-content flex-grow-1">
                                            <div class="recent-item-title">{{ $student->first_name }} {{ $student->last_name }}</div>
                                            <div class="recent-item-meta">
                                                @if($student->grade_level)
                                                    <span class="badge bg-info me-1">Grade {{ $student->grade_level }}</span>
                                                @endif
                                                @if($student->strand)
                                                    <span class="badge bg-secondary">{{ $student->strand }}</span>
                                                @endif
                                            </div>
                                            <div class="recent-item-date">{{ $student->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('registrar.students.index') }}" class="btn btn-outline-success btn-sm">
                                View All Students
                            </a>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-users fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No recent students</p>
                            <a href="{{ route('registrar.students.create') }}" class="btn btn-success btn-sm mt-2">
                                Generate Student Credentials
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ===== CUSTOM DASHBOARD STYLES ===== */

/* Section Titles */
.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0;
}

/* Quick Action Cards */
.quick-action-card {
    display: block;
    background: white;
    border: none;
    border-radius: 12px;
    padding: 1.5rem;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    height: 100%;
}

.quick-action-card:hover {
    text-decoration: none;
    color: inherit;
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.quick-action-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    margin-bottom: 1rem;
}

.quick-action-content h6 {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.quick-action-content p {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0;
}

/* Overview Stats */
.overview-stats {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.overview-stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.overview-stat-item:last-child {
    border-bottom: none;
}

.overview-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.overview-stat-content {
    flex: 1;
}

.overview-stat-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.overview-stat-value {
    font-size: 0.8rem;
    color: #6c757d;
}

/* Recent Items */
.recent-items {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.recent-item {
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.recent-item:hover {
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transform: translateY(-1px);
}

.recent-item-content {
    flex: 1;
}

.recent-item-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

.recent-item-meta {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
}

.recent-item-date {
    font-size: 0.75rem;
    color: #6c757d;
}

/* Student Avatar */
.student-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
    flex-shrink: 0;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem 1rem;
}

.empty-state i {
    display: block;
    margin-bottom: 1rem;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Students by Grade Chart
    const studentsGradeCtx = document.getElementById('studentsGradeChart').getContext('2d');
    const studentsGradeData = @json($studentsByGrade);

    new Chart(studentsGradeCtx, {
        type: 'doughnut',
        data: {
            labels: studentsGradeData.map(item => item.grade_level || 'Not Set'),
            datasets: [{
                data: studentsGradeData.map(item => item.count),
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#ffc107',
                    '#dc3545',
                    '#6f42c1'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // Subjects by Track Chart
    const subjectsTrackCtx = document.getElementById('subjectsTrackChart').getContext('2d');
    const subjectsTrackData = @json($subjectsByTrack);

    new Chart(subjectsTrackCtx, {
        type: 'bar',
        data: {
            labels: subjectsTrackData.map(item => item.track || 'Not Set'),
            datasets: [{
                label: 'Subjects',
                data: subjectsTrackData.map(item => item.count),
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#ffc107',
                    '#dc3545'
                ],
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
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush