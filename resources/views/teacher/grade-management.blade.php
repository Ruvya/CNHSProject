@extends('layouts.teacher')

@section('title', 'Grade Management')

@section('styles')
<style>
    /* New Header Style */
    .page-header-design {
        background: linear-gradient(115deg, #f97316 60%, #3b82f6 60%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(249, 115, 22, 0.25);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header-left-content {
        display: flex;
        align-items: center;
    }
    .header-left-content i {
        font-size: 3rem;
        margin-right: 1.5rem;
        opacity: 0.8;
    }
    .header-left-content h1 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .header-left-content p {
        margin: 0.25rem 0 0 0;
        opacity: 0.9;
        font-size: 1.1rem;
    }
    .header-right-content .stats-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.75rem 1.25rem;
        border-radius: 50px;
        display: flex;
        align-items: center;
        font-weight: 600;
        font-size: 1rem;
        backdrop-filter: blur(10px);
    }
    .header-right-content .stats-badge i {
        margin-right: 0.5rem;
    }

    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: white;
        font-size: 1.5rem;
    }

    .stats-content h3 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: #2c3e50;
    }

    .stats-content p {
        color: #6c757d;
        margin-bottom: 0;
        font-weight: 500;
    }

    .subject-info strong {
        color: #2c3e50;
    }

    .grade-average {
        font-weight: 600;
        font-size: 1.1rem;
    }

    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 10px;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        border-radius: 10px 10px 0 0 !important;
    }

    .btn-group .btn {
        margin-right: 0.25rem;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-design">
                <div class="header-left-content">
                    <i class="fas fa-chart-line"></i>
                    <div>
                        <h1>Grade Management</h1>
                        <p>Monitor and manage grading progress across all your subjects</p>
                    </div>
                </div>
                <div class="header-right-content">
                    <div class="stats-badge">
                        <i class="fas fa-percentage"></i>
                        <span>{{ collect($gradeStats)->avg('completion_percentage') ? round(collect($gradeStats)->avg('completion_percentage'), 1) : 0 }}% Avg Completion</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-primary">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ count($subjects) }}</h3>
                    <p>Total Subjects</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ collect($gradeStats)->sum('graded_students') }}</h3>
                    <p>Graded Students</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ collect($gradeStats)->sum('pending_grades') }}</h3>
                    <p>Pending Grades</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-info">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ collect($gradeStats)->avg('completion_percentage') ? round(collect($gradeStats)->avg('completion_percentage'), 1) : 0 }}%</h3>
                    <p>Avg Completion</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject Grade Statistics -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-chart-bar me-2"></i>
                Subject Grading Progress
            </h5>
        </div>
        <div class="card-body">
            @if(count($gradeStats) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Subject Code</th>
                                <th>Total Students</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gradeStats as $stat)
                                <tr class="clickable-row" data-href="{{ route('teacher.subjects.grades', $stat['subject']->id) }}">
                                    <td>
                                        <div class="subject-info">
                                            <strong>{{ $stat['subject']->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $stat['subject']->code ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $stat['total_students'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Subjects Assigned</h5>
                    <p class="text-muted">You don't have any subjects assigned yet. Contact your administrator to get subjects assigned.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <!-- Grading Tips card removed -->
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.clickable-row').css('cursor', 'pointer').hover(
            function() { $(this).addClass('table-active'); },
            function() { $(this).removeClass('table-active'); }
        ).click(function() {
            window.location = $(this).data('href');
        });
    });
</script>
@endsection

