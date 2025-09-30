@extends('layouts.registrar')

@section('title', 'Subject Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Subject Management</h1>
                    <p class="text-muted">Manage subjects and their teacher assignments</p>
                </div>
                <div>
                    <a href="{{ route('registrar.subject-management.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Subject
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['total_subjects'] }}</h4>
                            <p class="mb-0">Total Subjects</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['assigned_subjects'] }}</h4>
                            <p class="mb-0">Assigned Subjects</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['unassigned_subjects'] }}</h4>
                            <p class="mb-0">Unassigned Subjects</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-times fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['active_assignments'] }}</h4>
                            <p class="mb-0">Active Assignments</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-tasks fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('registrar.subject-management.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Search subjects...">
                            </div>
                            <div class="col-md-2">
                                <label for="grade_level" class="form-label">Grade Level</label>
                                <select class="form-select" id="grade_level" name="grade_level">
                                    <option value="">All Grades</option>
                                    <option value="11" {{ request('grade_level') == '11' ? 'selected' : '' }}>Grade 11</option>
                                    <option value="12" {{ request('grade_level') == '12' ? 'selected' : '' }}>Grade 12</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="track" class="form-label">Track</label>
                                <select class="form-select" id="track" name="track">
                                    <option value="">All Tracks</option>
                                    @foreach($tracks as $track)
                                        <option value="{{ $track }}" {{ request('track') == $track ? 'selected' : '' }}>
                                            {{ $track }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="assignment_status" class="form-label">Assignment Status</label>
                                <select class="form-select" id="assignment_status" name="assignment_status">
                                    <option value="">All</option>
                                    <option value="assigned" {{ request('assignment_status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                    <option value="unassigned" {{ request('assignment_status') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('registrar.subject-management.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Subjects ({{ $subjects->total() }} total)</h5>
                </div>
                <div class="card-body">
                    @if($subjects->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Grade Level</th>
                                        <th>Track</th>
                                        <th>Cluster</th>
                                        <th>Current Teacher</th>
                                        <th>Assignment Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjects as $subject)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $subject->code }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $subject->name }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $subject->grade_level }}</span>
                                            </td>
                                            <td>{{ $subject->track ?? 'N/A' }}</td>
                                            <td>{{ $subject->cluster ?? 'N/A' }}</td>
                                            <td>
                                                @if($subject->teacher)
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-user-circle me-2"></i>
                                                        <span>{{ $subject->teacher->name }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted">No teacher assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $hasActiveAssignment = $subject->teacherAssignments()
                                                        ->where('school_year', $currentSchoolYear)
                                                        ->where('semester', $currentSemester)
                                                        ->where('status', 'active')
                                                        ->exists();
                                                @endphp
                                                @if($hasActiveAssignment)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Assigned
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-exclamation"></i> Unassigned
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('registrar.subject-management.show', $subject) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('registrar.subject-management.edit', $subject) }}" 
                                                       class="btn btn-sm btn-outline-secondary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if(!$hasActiveAssignment)
                                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                                data-bs-toggle="modal" data-bs-target="#assignTeacherModal{{ $subject->id }}" title="Assign Teacher">
                                                            <i class="fas fa-user-plus"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $subjects->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <h5>No subjects found</h5>
                            <p class="text-muted">Create your first subject to get started.</p>
                            <a href="{{ route('registrar.subject-management.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Subject
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Assign Teacher Modals -->
@foreach($subjects as $subject)
    @if(!$subject->teacherAssignments()->where('school_year', $currentSchoolYear)->where('semester', $currentSemester)->where('status', 'active')->exists())
        <div class="modal fade" id="assignTeacherModal{{ $subject->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('registrar.subject-management.assign-teacher', $subject) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Assign Teacher to {{ $subject->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="teacher_id" class="form-label">Select Teacher</label>
                                <select class="form-select" id="teacher_id" name="teacher_id" required>
                                    <option value="">Choose a teacher...</option>
                                    @foreach(\App\Models\Teacher::where('status', 'active')->orderBy('name')->get() as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Schedule (Optional)</label>
                                <div id="schedule-container">
                                    <div class="schedule-item row mb-2">
                                        <div class="col-md-4">
                                            <select class="form-select" name="schedule[0][day]">
                                                <option value="">Select Day</option>
                                                <option value="Monday">Monday</option>
                                                <option value="Tuesday">Tuesday</option>
                                                <option value="Wednesday">Wednesday</option>
                                                <option value="Thursday">Thursday</option>
                                                <option value="Friday">Friday</option>
                                                <option value="Saturday">Saturday</option>
                                                <option value="Sunday">Sunday</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="time" class="form-control" name="schedule[0][start_time]" placeholder="Start Time">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="time" class="form-control" name="schedule[0][end_time]" placeholder="End Time">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-schedule">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="add-schedule">
                                    <i class="fas fa-plus"></i> Add Schedule
                                </button>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Additional notes..."></textarea>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="send_email" name="send_email" value="1">
                                <label class="form-check-label" for="send_email">
                                    Send email notification to teacher
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Assign Teacher</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Schedule management
    let scheduleIndex = 1;
    
    document.getElementById('add-schedule').addEventListener('click', function() {
        const container = document.getElementById('schedule-container');
        const newItem = document.createElement('div');
        newItem.className = 'schedule-item row mb-2';
        newItem.innerHTML = `
            <div class="col-md-4">
                <select class="form-select" name="schedule[${scheduleIndex}][day]">
                    <option value="">Select Day</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="time" class="form-control" name="schedule[${scheduleIndex}][start_time]" placeholder="Start Time">
            </div>
            <div class="col-md-3">
                <input type="time" class="form-control" name="schedule[${scheduleIndex}][end_time]" placeholder="End Time">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger btn-sm remove-schedule">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newItem);
        scheduleIndex++;
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-schedule')) {
            e.target.closest('.schedule-item').remove();
        }
    });
});
</script>
@endsection
