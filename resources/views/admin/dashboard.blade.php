@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Semester System Notice -->
@include('admin.partials.semester-notice')

<!-- Modern Angled Dashboard Header -->
<div class="angled-header-card mb-4">
    <div class="header-left-content">
        <span class="icon"><i class="fas fa-tachometer-alt"></i></span>
        <div>
            <span class="title">Dashboard</span>
            <span class="subtitle">Welcome back! Here's an overview of the analytics.</span>
        </div>
    </div>
</div>
<!-- Page Header -->
{{--
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
--}}

@include('admin.partials.semester-notice')

<!-- Main Statistics Cards -->
<div class="stat-cards-row mb-3">
    <!-- Total Students Card -->
    <div>
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
    </div>

    <!-- Total Teachers Card -->
    <div>
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
    </div>

    <!-- Total Subjects Card -->
    <div>
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
    </div>

    <!-- Active Teachers Card -->
    <div>
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
    </div>

    <!-- Grading Analytics Card -->
    <div>
        <div class="stat-card stat-card-info">
            <div class="stat-card-body">
                <div class="stat-card-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-title">Grading Analytics</div>
                    <div class="stat-card-value">
                        {{ $gradingScaleAnalytics['passing_percentage'] ?? 0 }}%
                    </div>
                    <div class="stat-card-subtitle">
                        @if(($gradingScaleAnalytics['total_grades'] ?? 0) > 0)
                            <i class="fas fa-graduation-cap me-1"></i>
                            {{ $gradingScaleAnalytics['total_grades'] }} total grades
                        @else
                            <i class="fas fa-info-circle me-1"></i>
                            No grades recorded
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Quick Actions Section -->
{{--
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
--}}

<!-- System Status Section -->

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

    <!-- Users by Role Chart -->
    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2 text-warning"></i>
                    Users by Role
                </h5>
            </div>
            <div class="card-body">
                <canvas id="usersByRoleChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>



<!-- Grading Scale Analytics Chart and System Overview -->
<div class="row g-4 mb-4 equal-height-cards">
    <!-- Grade Scale Distribution by Section -->
    <div class="col-xl-9 col-lg-9">
        <div class="card h-100">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar me-2 text-info"></i>
                            Grading Scale Distribution by Section
                        </h5>
                        <small class="text-muted">Performance overview across all sections</small>
                    </div>
                    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#gradingFilters" aria-expanded="false">
                        <i class="fas fa-filter me-1"></i>Filters
                    </button>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="collapse" id="gradingFilters">
                <div class="card-body border-bottom bg-light">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="trackFilter" class="form-label small fw-bold">Track</label>
                            <select class="form-select form-select-sm" id="trackFilter" onchange="updateGradingChart()">
                                <option value="">All Tracks</option>
                                <option value="Academic Track">Academic Track</option>
                                <option value="TVL Track">TVL Track</option>
                                <option value="Sports Track">Sports Track</option>
                                <option value="Arts and Design Track">Arts and Design Track</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="clusterFilter" class="form-label small fw-bold">Cluster</label>
                            <select class="form-select form-select-sm" id="clusterFilter" onchange="updateGradingChart()">
                                <option value="">All Clusters</option>
                                <option value="STEM">STEM</option>
                                <option value="HUMSS">HUMSS</option>
                                <option value="ABM">ABM</option>
                                <option value="GAS">GAS</option>
                                <option value="ICT">ICT</option>
                                <option value="HE">Home Economics</option>
                                <option value="IA">Industrial Arts</option>
                                <option value="Agri-Fish">Agri-Fishery</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="sectionFilter" class="form-label small fw-bold">Section</label>
                            <select class="form-select form-select-sm" id="sectionFilter" onchange="updateGradingChart()">
                                <option value="">All Sections</option>
                                <!-- Sections will be populated dynamically -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="subjectFilter" class="form-label small fw-bold">Subject</label>
                            <select class="form-select form-select-sm" id="subjectFilter" onchange="updateGradingChart()">
                                <option value="">All Subjects</option>
                                <!-- Subjects will be populated dynamically -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="quarterFilter" class="form-label small fw-bold">Quarter</label>
                            <select class="form-select form-select-sm" id="quarterFilter" onchange="updateGradingChart()">
                                <option value="">All Quarters</option>
                                <option value="1">1st Quarter</option>
                                <option value="2">2nd Quarter</option>
                                <option value="3">3rd Quarter</option>
                                <option value="4">4th Quarter</option>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button class="btn btn-outline-danger btn-sm w-100" onclick="resetGradingFilters()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body py-2 px-3">
                <!-- Loading indicator -->
                <div id="gradingChartLoading" class="text-center py-3" style="display: none;">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted small">Loading chart data...</p>
                </div>

                <!-- Chart container -->
                <div id="gradingChartContainer">
                    <canvas id="gradingScaleBySectionChart"></canvas>

                    <!-- Grading Scale Legend -->
                    <div class="mt-2 p-2 bg-light rounded border">
                        <!-- Compact horizontal legend -->
                        <div class="d-flex flex-wrap justify-content-center align-items-center gap-2" style="font-size: 0.7rem;">
                            <span class="fw-bold text-muted me-2">Scale:</span>

                            <div class="d-flex align-items-center">
                                <div class="rounded me-1" style="width: 8px; height: 8px; background-color: #10b981;"></div>
                                <span class="fw-bold">90-100</span>
                                <span class="text-muted ms-1">Outstanding</span>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="rounded me-1" style="width: 8px; height: 8px; background-color: #3b82f6;"></div>
                                <span class="fw-bold">85-89</span>
                                <span class="text-muted ms-1">V.Satisfactory</span>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="rounded me-1" style="width: 8px; height: 8px; background-color: #06b6d4;"></div>
                                <span class="fw-bold">80-84</span>
                                <span class="text-muted ms-1">Satisfactory</span>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="rounded me-1" style="width: 8px; height: 8px; background-color: #f59e0b;"></div>
                                <span class="fw-bold">75-79</span>
                                <span class="text-muted ms-1">F.Satisfactory</span>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="rounded me-1" style="width: 8px; height: 8px; background-color: #ef4444;"></div>
                                <span class="fw-bold text-danger">Below 75</span>
                                <span class="text-danger ms-1">Failed</span>
                            </div>

                            <span class="text-muted ms-3">|</span>
                            <span class="text-success fw-bold ms-1">Pass: 75+</span>
                        </div>
                    </div>
                </div>

                <!-- No data message -->
                <div id="gradingChartNoData" class="text-center py-3" style="display: none;">
                    <i class="fas fa-chart-bar text-muted mb-2" style="font-size: 2rem;"></i>
                    <h6 class="text-muted">No Data Available</h6>
                    <p class="text-muted small">No grade data found for the selected filters.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview -->
    <div class="col-xl-3 col-lg-3">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-server me-2 text-success"></i>
                    System Overview
                </h5>
            </div>
            <div class="card-body">
                <h5 class="text-muted mb-4 fw-bold">System Statistics</h5>
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
        </div>
    </div>
</div>


@endsection

@section('styles')
@parent
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
    font-size: 1rem;
    color: #374151;
    margin-bottom: 0.25rem;
    font-weight: 600;
}

.status-text small {
    font-size: 0.9rem;
    color: #6b7280;
    font-weight: 500;
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

/* Equal height cards */
.equal-height-cards {
    display: flex;
    flex-wrap: wrap;
}

.equal-height-cards .card {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.equal-height-cards .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.angled-header-card {
    display: flex;
    align-items: center;
    background: linear-gradient(115deg, #f97316 60%, #3b82f6 60%);
    color: white;
    padding: 2.2rem 2.5rem 2.2rem 2.5rem;
    border-radius: 16px;
    margin-bottom: 2.5rem;
    box-shadow: 0 10px 30px rgba(249, 115, 22, 0.18);
    position: relative;
    overflow: hidden;
    min-height: 120px;
}
.header-left-content {
    display: flex;
    align-items: center;
}
.header-left-content .icon {
    font-size: 2.8rem;
    margin-right: 1.5rem;
    opacity: 0.92;
}
.header-left-content .title {
    font-size: 2.2rem;
    font-weight: 800;
    display: block;
    margin-bottom: 0.2rem;
    line-height: 1.1;
}
.header-left-content .subtitle {
    font-size: 1.1rem;
    font-weight: 500;
    opacity: 0.95;
    display: block;
}
@media (max-width: 768px) {
    .angled-header-card {
        flex-direction: column;
        align-items: flex-start;
        padding: 1.2rem 1rem;
        min-height: 100px;
    }
    .header-left-content {
        flex-direction: column;
        align-items: flex-start;
    }
    .header-left-content .icon {
        margin-bottom: 0.7rem;
        margin-right: 0;
    }
}

/* Consistent Stat Card Sizes */
.stat-card {
    min-width: 175px;
    max-width: 190px;
    height: 250px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin: 0 0 1rem 0;
    border-radius: 20px;
    box-shadow: 0 4px 16px rgba(30, 64, 175, 0.08);
    background: #fff;
    transition: box-shadow 0.7s cubic-bezier(0.23, 1, 0.32, 1), transform 0.7s cubic-bezier(0.23, 1, 0.32, 1);
}
.stat-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 8px 32px rgba(30, 64, 175, 0.13);
    z-index: 2;
}
.stat-card .stat-card-body {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
}
.stat-card .stat-card-icon {
    font-size: 2.4rem;
    margin-bottom: 1rem;
    color: #2563eb;
}
.stat-card .stat-card-title {
    font-size: 1.05rem;
    font-weight: 700;
    letter-spacing: 0.7px;
    color: #374151;
    margin-bottom: 0.4rem;
    text-align: center;
}
.stat-card .stat-card-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: #2563eb;
    margin-bottom: 0.4rem;
    text-align: center;
}
.stat-card .stat-card-subtitle {
    font-size: 0.95rem;
    color: #6b7280;
    text-align: center;
    margin-top: 0.15rem;
}
@media (max-width: 1200px) {
    .stat-card {
        min-width: 140px;
        max-width: 160px;
        height: 200px;
        padding: 1rem 0.7rem 1rem 0.7rem;
    }
}
@media (max-width: 768px) {
    .stat-card {
        min-width: 100%;
        max-width: 100%;
        height: auto;
        margin-bottom: 1.2rem;
    }
}

.stat-cards-row {
    display: flex;
    flex-wrap: nowrap;
    gap: 0.3rem;
    justify-content: flex-start;
    width: 100%;
}
.stat-cards-row > div {
    flex: 1 1 0;
    min-width: 0;
}
.stat-card {
    width: 100%;
    min-width: 0;
    max-width: none;
    /* keep previous height, padding, etc. */
}
@media (max-width: 1200px) {
    .stat-cards-row {
        flex-wrap: wrap;
        gap: 0.7rem;
    }
    .stat-cards-row > div {
        flex: 1 1 45%;
        min-width: 220px;
        margin-bottom: 1rem;
    }
}
@media (max-width: 768px) {
    .stat-cards-row {
        flex-direction: column;
        gap: 1rem;
    }
    .stat-cards-row > div {
        flex: 1 1 100%;
        min-width: 0;
    }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if Chart.js is available
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded!');
        return;
    }

    // Get data from Laravel
    const studentsGradeData = @json($studentsByGrade);
    const usersByRoleData = @json($usersByRole);

    // Students by Grade Level Chart
    const studentsGradeCtx = document.getElementById('studentsGradeChart');
    if (studentsGradeCtx && studentsGradeData.length > 0) {
        new Chart(studentsGradeCtx, {
            type: 'bar',
            data: {
                labels: studentsGradeData.map(item => item.grade_level),
                datasets: [{
                    label: 'Students',
                    data: studentsGradeData.map(item => item.count),
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }



    // Users by Role Chart
    const roleCtx = document.getElementById('usersByRoleChart');
    if (roleCtx && usersByRoleData) {
        new Chart(roleCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(usersByRoleData),
                datasets: [{
                    data: Object.values(usersByRoleData),
                    backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Initialize grading chart
    initializeGradingChart();

    // Load filter options
    loadFilterOptions();
});

// Global variables for grading chart
let gradingChart = null;
let allGradingData = @json($gradingScaleBySection ?? []);

// Initialize the grading scale chart
function initializeGradingChart() {
    const gradingScaleBySectionCtx = document.getElementById('gradingScaleBySectionChart');

    if (gradingScaleBySectionCtx && allGradingData && allGradingData.sections && allGradingData.sections.length > 0) {
        gradingChart = new Chart(gradingScaleBySectionCtx, {
            type: 'bar',
            data: {
                labels: allGradingData.sections,
                datasets: [
                    {
                        label: 'Outstanding (90-100) - Passed',
                        data: allGradingData.outstanding,
                        backgroundColor: '#10b981',
                        borderColor: '#059669',
                        borderWidth: 1
                    },
                    {
                        label: 'Very Satisfactory (85-89) - Passed',
                        data: allGradingData.very_satisfactory,
                        backgroundColor: '#3b82f6',
                        borderColor: '#2563eb',
                        borderWidth: 1
                    },
                    {
                        label: 'Satisfactory (80-84) - Passed',
                        data: allGradingData.satisfactory,
                        backgroundColor: '#06b6d4',
                        borderColor: '#0891b2',
                        borderWidth: 1
                    },
                    {
                        label: 'Fairly Satisfactory (75-79) - Passed',
                        data: allGradingData.fairly_satisfactory,
                        backgroundColor: '#f59e0b',
                        borderColor: '#d97706',
                        borderWidth: 1
                    },
                    {
                        label: 'Did Not Meet Expectations (Below 75) - Failed',
                        data: allGradingData.failed,
                        backgroundColor: '#ef4444',
                        borderColor: '#dc2626',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2.5,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            title: function(context) {
                                return `Section: ${context[0].label}`;
                            },
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw} students`;
                            },
                            footer: function(tooltipItems) {
                                const sectionName = tooltipItems[0].label;
                                const sectionData = allGradingData.section_data ? allGradingData.section_data[sectionName] : null;
                                if (sectionData) {
                                    return `Total students: ${sectionData.total}`;
                                }
                                return '';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Sections'
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Students'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                }
            }
        });
    } else {
        showNoDataMessage();
    }
}

// Load filter options from server
function loadFilterOptions() {
    // Load sections
    fetch('/admin/api/sections')
        .then(response => response.json())
        .then(data => {
            const sectionSelect = document.getElementById('sectionFilter');
            sectionSelect.innerHTML = '<option value="">All Sections</option>';
            data.forEach(section => {
                sectionSelect.innerHTML += `<option value="${section}">${section}</option>`;
            });
        })
        .catch(error => console.error('Error loading sections:', error));

    // Load subjects
    fetch('/admin/api/subjects')
        .then(response => response.json())
        .then(data => {
            const subjectSelect = document.getElementById('subjectFilter');
            subjectSelect.innerHTML = '<option value="">All Subjects</option>';
            data.forEach(subject => {
                subjectSelect.innerHTML += `<option value="${subject.id}">${subject.name}</option>`;
            });
        })
        .catch(error => console.error('Error loading subjects:', error));
}

// Update chart based on filters
function updateGradingChart() {
    const track = document.getElementById('trackFilter').value;
    const cluster = document.getElementById('clusterFilter').value;
    const section = document.getElementById('sectionFilter').value;
    const subject = document.getElementById('subjectFilter').value;
    const quarter = document.getElementById('quarterFilter').value;

    // Show loading
    showLoading();

    // Build query parameters
    const params = new URLSearchParams();
    if (track) params.append('track', track);
    if (cluster) params.append('cluster', cluster);
    if (section) params.append('section', section);
    if (subject) params.append('subject', subject);
    if (quarter) params.append('quarter', quarter);

    // Fetch filtered data
    fetch(`/admin/api/grading-scale-data?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.sections && data.sections.length > 0) {
                updateChartData(data);
                showChart();
            } else {
                showNoDataMessage();
            }
        })
        .catch(error => {
            console.error('Error fetching grading data:', error);
            hideLoading();
            showNoDataMessage();
        });
}

// Update chart with new data
function updateChartData(data) {
    if (gradingChart) {
        gradingChart.data.labels = data.sections;
        gradingChart.data.datasets[0].data = data.outstanding;
        gradingChart.data.datasets[1].data = data.very_satisfactory;
        gradingChart.data.datasets[2].data = data.satisfactory;
        gradingChart.data.datasets[3].data = data.fairly_satisfactory;
        gradingChart.data.datasets[4].data = data.failed;
        gradingChart.update();
    }
}

// Reset all filters
function resetGradingFilters() {
    document.getElementById('trackFilter').value = '';
    document.getElementById('clusterFilter').value = '';
    document.getElementById('sectionFilter').value = '';
    document.getElementById('subjectFilter').value = '';
    document.getElementById('quarterFilter').value = '';

    // Reset to original data
    if (gradingChart && allGradingData.sections && allGradingData.sections.length > 0) {
        updateChartData(allGradingData);
        showChart();
    } else {
        showNoDataMessage();
    }
}

// UI helper functions
function showLoading() {
    document.getElementById('gradingChartLoading').style.display = 'block';
    document.getElementById('gradingChartContainer').style.display = 'none';
    document.getElementById('gradingChartNoData').style.display = 'none';
}

function hideLoading() {
    document.getElementById('gradingChartLoading').style.display = 'none';
}

function showChart() {
    document.getElementById('gradingChartContainer').style.display = 'block';
    document.getElementById('gradingChartNoData').style.display = 'none';
}

function showNoDataMessage() {
    document.getElementById('gradingChartContainer').style.display = 'none';
    document.getElementById('gradingChartNoData').style.display = 'block';
}
</script>
@endpush
