@extends('layouts.registrar')

@section('title', 'Subject Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ $subject->name }}</h1>
                    <p class="text-muted">{{ $subject->code }} • {{ $subject->grade_level }}</p>
                </div>
                <div>
                    <a href="{{ route('registrar.subject-management.edit', $subject) }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-edit"></i> Edit Subject
                    </a>
                    <a href="{{ route('registrar.subject-management.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Subjects
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Subject Information -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Subject Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Basic Details</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Subject Code:</strong></td>
                                    <td>{{ $subject->code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Subject Name:</strong></td>
                                    <td>{{ $subject->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Grade Level:</strong></td>
                                    <td><span class="badge bg-info">{{ $subject->grade_level }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Track:</strong></td>
                                    <td>{{ $subject->track ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Cluster/Strand:</strong></td>
                                    <td>{{ $subject->cluster ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Semester:</strong></td>
                                    <td>{{ $subject->semester ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Subject Type</h6>
                            <div class="mb-2">
                                @if($subject->is_core_subject)
                                    <span class="badge bg-primary me-2">Core Subject</span>
                                @endif
                                @if($subject->is_master_subject)
                                    <span class="badge bg-success me-2">Master Subject</span>
                                @endif
                            </div>
                            
                            @if($subject->description)
                                <h6>Description</h6>
                                <p class="text-muted">{{ $subject->description }}</p>
                            @endif
                            
                            @if($subject->specialization)
                                <h6>Specialization</h6>
                                <p class="text-muted">{{ $subject->specialization }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule Information -->
            @if($subject->schedule_days || $subject->start_time || $subject->end_time || $subject->room)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Schedule Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($subject->schedule_days)
                        <div class="col-md-6">
                            <h6>Schedule Days</h6>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach(explode(',', $subject->schedule_days) as $day)
                                    <span class="badge bg-secondary">{{ trim($day) }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        @if($subject->start_time || $subject->end_time)
                        <div class="col-md-6">
                            <h6>Time</h6>
                            <p class="mb-0">
                                @if($subject->start_time && $subject->end_time)
                                    {{ date('g:i A', strtotime($subject->start_time)) }} - {{ date('g:i A', strtotime($subject->end_time)) }}
                                @elseif($subject->start_time)
                                    From {{ date('g:i A', strtotime($subject->start_time)) }}
                                @elseif($subject->end_time)
                                    Until {{ date('g:i A', strtotime($subject->end_time)) }}
                                @endif
                            </p>
                        </div>
                        @endif
                        
                        @if($subject->room)
                        <div class="col-md-6">
                            <h6>Room</h6>
                            <p class="mb-0">{{ $subject->room }}</p>
                        </div>
                        @endif
                        
                        @if($subject->schedule_notes)
                        <div class="col-md-12">
                            <h6>Schedule Notes</h6>
                            <p class="text-muted">{{ $subject->schedule_notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Current Teacher Assignments -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Current Teacher Assignments ({{ $currentSchoolYear }} - {{ $currentSemester }})</h5>
                    @if($currentAssignments->isEmpty())
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignTeacherModal">
                            <i class="fas fa-user-plus"></i> Assign Teacher
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if($currentAssignments->count() > 0)
                        @foreach($currentAssignments as $assignment)
                            <div class="row align-items-center mb-3">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-circle fa-2x text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-0">{{ $assignment->teacher->name }}</h6>
                                            <p class="text-muted mb-0">{{ $assignment->teacher->email }}</p>
                                            @if($assignment->schedule)
                                                <small class="text-info">
                                                    <i class="fas fa-clock"></i> 
                                                    @foreach($assignment->schedule as $schedule)
                                                        {{ $schedule['day'] }} {{ $schedule['start_time'] }}-{{ $schedule['end_time'] }}
                                                        @if(!$loop->last), @endif
                                                    @endforeach
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-info btn-sm" 
                                                data-bs-toggle="modal" data-bs-target="#assignmentDetailsModal{{ $assignment->id }}">
                                            <i class="fas fa-info"></i> Details
                                        </button>
                                        <form action="{{ route('registrar.subject-management.remove-assignment', $assignment) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to remove this teacher assignment?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-user-minus"></i> Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-times fa-3x text-muted mb-3"></i>
                            <h6>No teacher assigned</h6>
                            <p class="text-muted">This subject doesn't have a teacher assigned for the current period.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignTeacherModal">
                                <i class="fas fa-user-plus"></i> Assign Teacher
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Student Enrollment -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Enrolled Students</h5>
                </div>
                <div class="card-body">
                    @if($subject->students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Grade Level</th>
                                        <th>Track</th>
                                        <th>Cluster</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subject->students as $student)
                                        <tr>
                                            <td>{{ $student->student_id }}</td>
                                            <td>{{ $student->name }}</td>
                                            <td><span class="badge bg-info">{{ $student->grade_level }}</span></td>
                                            <td>{{ $student->track ?? 'N/A' }}</td>
                                            <td>{{ $student->cluster ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h6>No students enrolled</h6>
                            <p class="text-muted">No students are currently enrolled in this subject.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('registrar.subject-management.edit', $subject) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit Subject
                        </a>
                        @if($currentAssignments->isEmpty())
                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#assignTeacherModal">
                                <i class="fas fa-user-plus"></i> Assign Teacher
                            </button>
                        @endif
                        <a href="{{ route('registrar.student-subject-assignments.index') }}?subject_id={{ $subject->id }}" class="btn btn-outline-info">
                            <i class="fas fa-users"></i> Manage Students
                        </a>
                    </div>
                </div>
            </div>

            <!-- Subject Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ $subject->students->count() }}</h4>
                            <small class="text-muted">Enrolled Students</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $currentAssignments->count() }}</h4>
                            <small class="text-muted">Assigned Teachers</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subject Metadata -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Metadata</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td>{{ $subject->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Updated:</strong></td>
                            <td>{{ $subject->updated_at->format('M d, Y') }}</td>
                        </tr>
                        @if($subject->registrar)
                            <tr>
                                <td><strong>Created by:</strong></td>
                                <td>{{ $subject->registrar->name }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Teacher Modal -->
<div class="modal fade" id="assignTeacherModal" tabindex="-1">
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

<!-- Assignment Details Modals -->
@foreach($currentAssignments as $assignment)
    <div class="modal fade" id="assignmentDetailsModal{{ $assignment->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assignment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Teacher:</strong></td>
                            <td>{{ $assignment->teacher->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $assignment->teacher->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>School Year:</strong></td>
                            <td>{{ $assignment->school_year }}</td>
                        </tr>
                        <tr>
                            <td><strong>Semester:</strong></td>
                            <td>{{ $assignment->semester }}</td>
                        </tr>
                        <tr>
                            <td><strong>Assignment Date:</strong></td>
                            <td>{{ $assignment->assignment_date->format('M d, Y') }}</td>
                        </tr>
                        @if($assignment->schedule)
                            <tr>
                                <td><strong>Schedule:</strong></td>
                                <td>
                                    @foreach($assignment->schedule as $schedule)
                                        {{ $schedule['day'] }} {{ $schedule['start_time'] }}-{{ $schedule['end_time'] }}
                                        @if(!$loop->last)<br>@endif
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                        @if($assignment->notes)
                            <tr>
                                <td><strong>Notes:</strong></td>
                                <td>{{ $assignment->notes }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
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
