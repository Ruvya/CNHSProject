@extends('layouts.registrar')

@push('styles')
<style>
/* Clean Page Header */
.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 12px;
}
.dashboard-title {
    font-size: 2rem;
    font-weight: 700;
}
.dashboard-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
}

/* Clean Statistics Cards */
.clean-stat-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
    border-top: 4px solid;
}
.clean-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}
.students-card { border-color: #4facfe; }
.subjects-card { border-color: #43e97b; }
.teachers-card { border-color: #fa709a; }
.records-card { border-color: #a8edea; }
.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin-bottom: 1rem;
    float: right;
}
.students-card .stat-icon { background: linear-gradient(135deg, #4facfe, #00f2fe); }
.subjects-card .stat-icon { background: linear-gradient(135deg, #43e97b, #38f9d7); }
.teachers-card .stat-icon { background: linear-gradient(135deg, #fa709a, #fee140); }
.records-card .stat-icon { background: linear-gradient(135deg, #a8edea, #fed6e3); }
.stat-content h3 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}
.stat-content p {
    font-size: 1rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 0.5rem;
}
.stat-change {
    font-size: 0.85rem;
    color: #28a745;
    font-weight: 500;
}

/* Action Group Cards */
.action-group-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    height: 100%;
}
.action-group-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.action-group-header i { font-size: 1.5rem; color: #667eea; }
.action-group-header h6 { font-size: 1.1rem; font-weight: 600; color: #2c3e50; margin: 0; }
.action-group-body { padding: 1rem; }
.action-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    text-decoration: none;
    color: #2c3e50;
    transition: all 0.3s ease;
    margin-bottom: 0.5rem;
}
.action-item:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    text-decoration: none;
    transform: translateX(5px);
}
.action-item i { font-size: 1.1rem; width: 20px; text-align: center; }
.action-item span { font-weight: 500; }

/* Analytics Card */
.analytics-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin-bottom: 2rem;
}
.analytics-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 1.5rem;
}
.analytics-header h5 { font-size: 1.3rem; font-weight: 600; color: #2c3e50; margin: 0; }
.analytics-body { padding: 2rem; }
.chart-container {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    position: relative;
    height: 300px;
    width: 100%;
}
.chart-container h6 {
    font-size: 1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
    text-align: center;
}
.chart-container canvas {
    max-height: 250px !important;
    width: 100% !important;
}
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container-fluid">
            <h1 class="dashboard-title">Registrar Dashboard</h1>
            <p class="dashboard-subtitle">Welcome back, {{ auth()->guard('registrar')->user()->first_name }}! Here's your school's overview.</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Students Card -->
        <div class="col-lg-3 col-md-6">
            <div class="clean-stat-card students-card">
                <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                <div class="stat-content">
                    <h3>{{ number_format($totalStudents ?? 0) }}</h3>
                    <p>Total Students</p>
                    <span class="stat-change">+{{ $recentStudentsCount ?? 0 }} this month</span>
                </div>
            </div>
        </div>
        <!-- Subjects Card -->
        <div class="col-lg-3 col-md-6">
            <div class="clean-stat-card subjects-card">
                <div class="stat-icon"><i class="fas fa-book"></i></div>
                <div class="stat-content">
                    <h3>{{ number_format($totalSubjects ?? 0) }}</h3>
                    <p>Total Subjects</p>
                    <span class="stat-change">{{ number_format($mySubjects ?? 0) }} created by you</span>
                </div>
            </div>
        </div>
        <!-- Teachers Card -->
        <div class="col-lg-3 col-md-6">
            <div class="clean-stat-card teachers-card">
                <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="stat-content">
                    <h3>{{ number_format($totalTeachers ?? 0) }}</h3>
                    <p>Faculty Members</p>
                    <span class="stat-change">{{ $assignmentStats['assignment_rate'] ?? 0 }}% assigned</span>
                </div>
            </div>
        </div>
        <!-- Yearly Records Card -->
        <div class="col-lg-3 col-md-6">
            <div class="clean-stat-card records-card">
                <div class="stat-icon"><i class="fas fa-archive"></i></div>
                <div class="stat-content">
                    <h3>{{ date('Y') }}</h3>
                    <p>Current Year</p>
                    <span class="stat-change">
                        <a href="{{ route('registrar.students.records') }}" class="text-primary">View Records</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="action-group-card">
                <div class="action-group-header"><i class="fas fa-bolt"></i><h6>Quick Actions</h6></div>
                <div class="action-group-body">
                    <a href="{{ route('registrar.students.upload') }}" class="action-item"><i class="fas fa-upload"></i><span>Upload Students</span></a>
                    <a href="{{ route('registrar.students.create') }}" class="action-item"><i class="fas fa-user-plus"></i><span>Generate Credentials</span></a>
                    <a href="{{ route('registrar.teacher-assignments.index') }}" class="action-item"><i class="fas fa-user-tie"></i><span>Assign Teachers</span></a>
                    <a href="{{ route('registrar.student-subject-assignments.index') }}" class="action-item"><i class="fas fa-user-graduate"></i><span>Assign Subjects to Students</span></a>
                </div>
            </div>
        </div>

        <!-- Analytics Overview -->
        <div class="col-lg-8">
            <div class="analytics-card">
                <div class="analytics-header"><h5><i class="fas fa-chart-line me-2"></i>Enrollment Analytics</h5></div>
                <div class="analytics-body">
                    <div class="chart-container">
                        <h6>Students by Grade Level</h6>
                        <canvas id="studentsGradeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Students by Grade Chart
    const studentsGradeCanvas = document.getElementById('studentsGradeChart');
    if (studentsGradeCanvas) {
        const studentsGradeCtx = studentsGradeCanvas.getContext('2d');
        const studentsGradeData = @json($studentsByGradeLevel ?? []);
        
        new Chart(studentsGradeCtx, {
            type: 'bar',
            data: {
                labels: studentsGradeData.map(item => item.grade_level),
                datasets: [{
                    label: 'Number of Students',
                    data: studentsGradeData.map(item => item.count),
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(67, 233, 123, 0.8)',
                        'rgba(250, 112, 154, 0.8)',
                        'rgba(168, 237, 234, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgba(102, 126, 234, 1)',
                        'rgba(67, 233, 123, 1)',
                        'rgba(250, 112, 154, 1)',
                        'rgba(168, 237, 234, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: 20,
                        right: 20,
                        bottom: 20,
                        left: 20
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            color: '#6c757d'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            color: '#6c757d'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(102, 126, 234, 1)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Students: ' + context.parsed.y;
                            }
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }
});
</script>
@endpush
