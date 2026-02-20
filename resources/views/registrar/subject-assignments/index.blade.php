@extends('layouts.registrar')

@section('content')
<div class="container-fluid">
    <!-- Modern Angled Header Card -->
    <div class="angled-header-card mb-4">
        <div class="header-left-content">
            <span class="icon"><i class="fas fa-chalkboard-teacher"></i></span>
            <div>
                <span class="title">Subject Assignment Management</span>
                <span class="subtitle">Assign subjects to teachers for {{ $schoolYear }} - {{ $gradingPeriod }}</span>
            </div>
        </div>
        <div class="header-right-content">
            <a href="{{ route('registrar.subject-assignments.create') }}" class="angled-header-btn">
                <i class="fas fa-plus me-2"></i> Assign Subject to Teacher
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-gradient rounded-3 p-3">
                                <i class="fas fa-tasks text-white fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Total Assignments</h6>
                            <h4 class="mb-0 text-primary">{{ number_format($stats['total_assignments']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-gradient rounded-3 p-3">
                                <i class="fas fa-chalkboard-teacher text-white fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Assigned Teachers</h6>
                            <h4 class="mb-0 text-success">{{ $stats['assigned_teachers'] }}/{{ $stats['total_teachers'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-gradient rounded-3 p-3">
                                <i class="fas fa-book text-white fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Assigned Subjects</h6>
                            <h4 class="mb-0 text-info">{{ $stats['assigned_subjects'] }}/{{ $stats['total_subjects'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-gradient rounded-3 p-3">
                                <i class="fas fa-percentage text-white fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Assignment Rate</h6>
                            <h4 class="mb-0 text-warning">
                                {{ $stats['total_teachers'] > 0 ? round(($stats['assigned_teachers'] / $stats['total_teachers']) * 100) : 0 }}%
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Assignments</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('registrar.subject-assignments.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="teacher_id" class="form-label">Teacher</label>
                        <select name="teacher_id" id="teacher_id" class="form-select">
                            <option value="">All Teachers</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $selectedTeacher == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="subject_id" class="form-label">Subject</label>
                        <select name="subject_id" id="subject_id" class="form-select">
                            <option value="">All Subjects</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $selectedSubject == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->code }} - {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="school_year" class="form-label">School Year</label>
                        <select name="school_year" id="school_year" class="form-select">
                            <option value="2024-2025" {{ $schoolYear == '2024-2025' ? 'selected' : '' }}>2024-2025</option>
                            <option value="2025-2026" {{ $schoolYear == '2025-2026' ? 'selected' : '' }}>2025-2026</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="grading_period" class="form-label">Grading Period</label>
                        <select name="grading_period" id="grading_period" class="form-select">
                            <option value="First Grading" {{ $gradingPeriod == 'First Grading' ? 'selected' : '' }}>First Grading</option>
                            <option value="Second Grading" {{ $gradingPeriod == 'Second Grading' ? 'selected' : '' }}>Second Grading</option>
                            <option value="Third Grading" {{ $gradingPeriod == 'Third Grading' ? 'selected' : '' }}>Third Grading</option>
                            <option value="Fourth Grading" {{ $gradingPeriod == 'Fourth Grading' ? 'selected' : '' }}>Fourth Grading</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-search me-1"></i>Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Assignments Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h6 class="mb-0"><i class="fas fa-list me-2"></i>Current Assignments</h6>
        </div>
        <div class="card-body p-0">
            @if($assignments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Teacher</th>
                                <th>Subject</th>
                                <th>Grade Level</th>
                                <th>Track/Strand</th>
                                <th>Schedule</th>
                                <th>Assigned Date</th>
                                <th>Assigned By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignments as $assignment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $assignment->teacher->name }}</h6>
                                                <small class="text-muted">{{ $assignment->teacher->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $assignment->subject->name }}</h6>
                                            <small class="text-muted">{{ $assignment->subject->code }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $assignment->subject->grade_level }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="badge bg-info">{{ $assignment->subject->track }}</span>
                                            @if($assignment->subject->strand)
                                                <br><small class="text-muted">{{ $assignment->subject->strand }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($assignment->schedule)
                                            <small class="text-muted">{{ $assignment->formatted_schedule }}</small>
                                        @else
                                            <span class="text-muted">No schedule set</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $assignment->assignment_date->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $assignment->assignedBy->full_name ?? $assignment->assignedBy->name }}</small>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('registrar.subject-assignments.destroy', $assignment) }}" 
                                              onsubmit="return confirm('Are you sure you want to remove this assignment?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Assignment">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="card-footer bg-white">
                    {{ $assignments->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No assignments found</h5>
                    <p class="text-muted">Start by assigning subjects to teachers for the current academic period.</p>
                    <a href="{{ route('registrar.subject-assignments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create First Assignment
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
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
.header-right-content {
    display: flex;
    align-items: center;
}
.angled-header-btn {
    background: rgba(255,255,255,0.18);
    color: #fff;
    font-weight: 700;
    font-size: 1.1rem;
    border-radius: 2rem;
    padding: 0.7rem 1.7rem;
    display: flex;
    align-items: center;
    gap: 0.7rem;
    box-shadow: 0 2px 8px rgba(56,135,250,0.10);
    border: 1px solid rgba(255,255,255,0.25);
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
}
.angled-header-btn:hover {
    background: rgba(255,255,255,0.28);
    color: #fff;
    text-decoration: none;
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
    .header-right-content {
        margin-top: 1rem;
        width: 100%;
        justify-content: flex-start;
    }
}
.avatar-sm {
    width: 32px;
    height: 32px;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #2c3e50;
}

.card {
    border-radius: 10px;
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
}
</style>
@endsection
