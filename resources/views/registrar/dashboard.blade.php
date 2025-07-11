@extends('layouts.registrar')

@push('styles')
<style>
:root {
    --cnhs-primary-blue: #1E3A8A;
    --cnhs-secondary-blue: #3B82F6;
    --cnhs-accent-orange: #FF8C00;
    --cnhs-gold: #FCD34D;
    --cnhs-white: #FFFFFF;
    --cnhs-light-gray: #F8FAFC;
    --cnhs-medium-gray: #6B7280;
    --cnhs-dark-gray: #374151;
    --cnhs-success: #10B981;
    --cnhs-warning: #F59E0B;
    --cnhs-danger: #EF4444;
    --cnhs-info: #06B6D4;
    --cnhs-gradient-primary: linear-gradient(135deg, var(--cnhs-primary-blue) 0%, var(--cnhs-secondary-blue) 100%);
    --cnhs-gradient-orange: linear-gradient(135deg, var(--cnhs-accent-orange) 0%, var(--cnhs-gold) 100%);
    --cnhs-shadow-md: 0 8px 25px rgba(30, 58, 138, 0.12);
    --cnhs-shadow-lg: 0 15px 35px rgba(30, 58, 138, 0.15);
}

.dashboard-header {
    background: var(--cnhs-gradient-orange);
    color: var(--cnhs-white);
    padding: 2.5rem 0 2rem 0;
    margin-bottom: 2.5rem;
    border-radius: 24px;
    box-shadow: var(--cnhs-shadow-lg);
    position: relative;
    overflow: hidden;
}
.dashboard-title {
    font-size: 2.3rem;
    font-weight: 900;
    letter-spacing: -0.01em;
    margin-bottom: 0.5rem;
    color: var(--cnhs-white);
    text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
}
.dashboard-subtitle {
    font-size: 1.15rem;
    color: var(--cnhs-white);
    font-weight: 500;
    opacity: 0.95;
}

.clean-stat-card {
    background: linear-gradient(145deg, var(--cnhs-white), var(--cnhs-light-gray));
    border-radius: 20px;
    padding: 2rem;
    box-shadow: var(--cnhs-shadow-md);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    border: none;
    margin-bottom: 1rem;
}
.clean-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--cnhs-gradient-primary);
    border-radius: 20px 20px 0 0;
}
.clean-stat-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: var(--cnhs-shadow-lg);
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
    font-size: 2.2rem;
    color: var(--cnhs-white);
    margin-bottom: 1rem;
    float: right;
    box-shadow: 0 4px 16px rgba(30,58,138,0.10);
}
.students-card .stat-icon { background: var(--cnhs-gradient-primary); }
.subjects-card .stat-icon { background: var(--cnhs-gradient-orange); }
.teachers-card .stat-icon { background: linear-gradient(135deg, #fa709a, #fee140); }
.records-card .stat-icon { background: linear-gradient(135deg, #a8edea, #fed6e3); }
.stat-content h3 {
    font-size: 2.5rem;
    font-weight: 800;
    background: var(--cnhs-gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.2;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}
.stat-content p {
    font-size: 1rem;
    font-weight: 600;
    color: var(--cnhs-dark-gray);
    margin-bottom: 0.5rem;
}
.stat-change {
    font-size: 0.95rem;
    color: var(--cnhs-success);
    font-weight: 500;
}

.action-group-card {
    background: linear-gradient(145deg, var(--cnhs-white), var(--cnhs-light-gray));
    border-radius: 20px;
    box-shadow: var(--cnhs-shadow-md);
    overflow: hidden;
    height: 100%;
    border: none;
    margin-bottom: 1rem;
}
.action-group-header {
    background: var(--cnhs-gradient-primary);
    color: var(--cnhs-white);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.action-group-header i { font-size: 1.5rem; color: var(--cnhs-gold); }
.action-group-header h6 { font-size: 1.1rem; font-weight: 700; color: var(--cnhs-white); margin: 0; }
.action-group-body { padding: 1.2rem; }
.action-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.85rem 1.2rem;
    border-radius: 12px;
    text-decoration: none;
    color: var(--cnhs-dark-gray);
    transition: all 0.3s ease;
    margin-bottom: 0.5rem;
    font-weight: 600;
    background: var(--cnhs-white);
    box-shadow: 0 2px 8px rgba(30,58,138,0.04);
}
.action-item:hover {
    background: var(--cnhs-gradient-orange);
    color: var(--cnhs-white);
    text-decoration: none;
    transform: translateX(5px) scale(1.03);
    box-shadow: 0 4px 16px #ff8c0033;
}
.action-item i { font-size: 1.2rem; width: 24px; text-align: center; }
.action-item span { font-weight: 600; }

.analytics-card {
    background: linear-gradient(145deg, var(--cnhs-white), var(--cnhs-light-gray));
    border-radius: 20px;
    box-shadow: var(--cnhs-shadow-md);
    overflow: hidden;
    margin-bottom: 2rem;
    border: none;
}
.analytics-header {
    background: var(--cnhs-gradient-primary);
    color: var(--cnhs-white);
    padding: 1.5rem;
}
.analytics-header h5 { font-size: 1.3rem; font-weight: 700; color: var(--cnhs-white); margin: 0; }
.analytics-body { padding: 2rem; }
.chart-container {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    position: relative;
    height: 300px;
    width: 100%;
    box-shadow: 0 2px 8px rgba(30,58,138,0.04);
}
.chart-container h6 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--cnhs-dark-gray);
    margin-bottom: 1rem;
    text-align: center;
}
.chart-container canvas {
    max-height: 250px !important;
    width: 100% !important;
}
.angled-header-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
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
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Modern Angled Header Card -->
    <div class="angled-header-card mb-4">
        <div class="header-left-content">
            <span class="icon"><i class="fas fa-clipboard-check"></i></span>
            <div>
                <span class="title">Registrar Dashboard</span>
                <span class="subtitle">Welcome back, {{ auth()->guard('registrar')->user()->first_name }}! Here's your school's overview.</span>
            </div>
        </div>
        <!-- Optionally, you can add a right-aligned button or badge here -->
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
                        <a href="{{ route('registrar.yearly-records.index') }}" class="text-primary">View Records</a>
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
                    <a href="{{ route('registrar.yearly-records.index') }}" class="action-item"><i class="fas fa-calendar-alt"></i><span>Yearly Records</span></a>
                    <a href="{{ route('registrar.students.upload') }}" class="action-item"><i class="fas fa-upload"></i><span>Upload Students</span></a>
                    <a href="{{ route('registrar.students.create') }}" class="action-item"><i class="fas fa-user-plus"></i><span>Generate Credentials</span></a>
                    <a href="{{ route('registrar.subject-assignments.index') }}" class="action-item"><i class="fas fa-user-tie"></i><span>Assign Subjects to Teachers</span></a>
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
