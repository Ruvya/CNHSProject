@extends('layouts.registrar')

@section('title', 'Edit Subject')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Edit Subject</h1>
                    <p class="text-muted">{{ $subject->name }} ({{ $subject->code }})</p>
                </div>
                <div>
                    <a href="{{ route('registrar.subject-management.show', $subject) }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-eye"></i> View Subject
                    </a>
                    <a href="{{ route('registrar.subject-management.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Subjects
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Subject Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('registrar.subject-management.update', $subject) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">Basic Information</h6>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $subject->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code', $subject->code) }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3">{{ old('description', $subject->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Academic Classification -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">Academic Classification</h6>
                            </div>
                            <div class="col-md-4">
                                <label for="grade_level" class="form-label">Grade Level <span class="text-danger">*</span></label>
                                <select class="form-select @error('grade_level') is-invalid @enderror" 
                                        id="grade_level" name="grade_level" required>
                                    <option value="">Select Grade Level</option>
                                    <option value="Grade 11" {{ old('grade_level', $subject->grade_level) == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                    <option value="Grade 12" {{ old('grade_level', $subject->grade_level) == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                                </select>
                                @error('grade_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="track" class="form-label">Track <span class="text-danger">*</span></label>
                                <select class="form-select @error('track') is-invalid @enderror" 
                                        id="track" name="track" required>
                                    <option value="">Select Track</option>
                                    <option value="Academic" {{ old('track', $subject->track) == 'Academic' ? 'selected' : '' }}>Academic</option>
                                    <option value="TVL" {{ old('track', $subject->track) == 'TVL' ? 'selected' : '' }}>TVL</option>
                                    <option value="Sports" {{ old('track', $subject->track) == 'Sports' ? 'selected' : '' }}>Sports</option>
                                    <option value="Arts & Design" {{ old('track', $subject->track) == 'Arts & Design' ? 'selected' : '' }}>Arts & Design</option>
                                </select>
                                @error('track')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="cluster" class="form-label">Cluster/Strand</label>
                                <select class="form-select @error('cluster') is-invalid @enderror" 
                                        id="cluster" name="cluster">
                                    <option value="">Select Cluster</option>
                                    <option value="HUMSS" {{ old('cluster', $subject->cluster) == 'HUMSS' ? 'selected' : '' }}>HUMSS</option>
                                    <option value="STEM" {{ old('cluster', $subject->cluster) == 'STEM' ? 'selected' : '' }}>STEM</option>
                                    <option value="ABM" {{ old('cluster', $subject->cluster) == 'ABM' ? 'selected' : '' }}>ABM</option>
                                    <option value="GAS" {{ old('cluster', $subject->cluster) == 'GAS' ? 'selected' : '' }}>GAS</option>
                                </select>
                                @error('cluster')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                                <select class="form-select @error('semester') is-invalid @enderror" 
                                        id="semester" name="semester" required>
                                    <option value="">Select Semester</option>
                                    <option value="1st Semester" {{ old('semester', $subject->semester) == '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                                    <option value="2nd Semester" {{ old('semester', $subject->semester) == '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                                    <option value="Both Semesters" {{ old('semester', $subject->semester) == 'Both Semesters' ? 'selected' : '' }}>Both Semesters</option>
                                </select>
                                @error('semester')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="specialization" class="form-label">Specialization</label>
                                <input type="text" class="form-control @error('specialization') is-invalid @enderror" 
                                       id="specialization" name="specialization" value="{{ old('specialization', $subject->specialization) }}">
                                @error('specialization')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Subject Type -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">Subject Type</h6>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_core_subject" name="is_core_subject" 
                                           value="1" {{ old('is_core_subject', $subject->is_core_subject) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_core_subject">
                                        Core Subject (Required for all students)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_master_subject" name="is_master_subject" 
                                           value="1" {{ old('is_master_subject', $subject->is_master_subject) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_master_subject">
                                        Master Subject (Template for other subjects)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">Schedule Information</h6>
                            </div>
                            <div class="col-md-6">
                                <label for="schedule_days" class="form-label">Schedule Days</label>
                                <div class="row">
                                    @php
                                        $selectedDays = old('schedule_days', $subject->schedule_days ? explode(',', $subject->schedule_days) : []);
                                    @endphp
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="schedule_days_{{ strtolower($day) }}" 
                                                       name="schedule_days[]" value="{{ $day }}" 
                                                       {{ in_array($day, $selectedDays) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="schedule_days_{{ strtolower($day) }}">
                                                    {{ $day }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="start_time" class="form-label">Start Time</label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                       id="start_time" name="start_time" value="{{ old('start_time', $subject->start_time) }}">
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="end_time" class="form-label">End Time</label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                       id="end_time" name="end_time" value="{{ old('end_time', $subject->end_time) }}">
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="room" class="form-label">Room</label>
                                <input type="text" class="form-control @error('room') is-invalid @enderror" 
                                       id="room" name="room" value="{{ old('room', $subject->room) }}">
                                @error('room')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="schedule_notes" class="form-label">Schedule Notes</label>
                                <input type="text" class="form-control @error('schedule_notes') is-invalid @enderror" 
                                       id="schedule_notes" name="schedule_notes" value="{{ old('schedule_notes', $subject->schedule_notes) }}">
                                @error('schedule_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('registrar.subject-management.show', $subject) }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Subject
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Current Assignments -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Current Teacher Assignments</h5>
                </div>
                <div class="card-body">
                    @if($currentAssignments->count() > 0)
                        @foreach($currentAssignments as $assignment)
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-user-circle fa-2x text-primary me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $assignment->teacher->name }}</h6>
                                    <small class="text-muted">{{ $assignment->teacher->email }}</small>
                                    @if($assignment->schedule)
                                        <br><small class="text-info">
                                            @foreach($assignment->schedule as $schedule)
                                                {{ $schedule['day'] }} {{ $schedule['start_time'] }}-{{ $schedule['end_time'] }}
                                                @if(!$loop->last), @endif
                                            @endforeach
                                        </small>
                                    @endif
                                </div>
                                <div>
                                    <form action="{{ route('registrar.subject-management.remove-assignment', $assignment) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to remove this teacher assignment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-user-minus"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-user-times fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No teacher assigned</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('registrar.subject-management.show', $subject) }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye"></i> View Subject
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
            <div class="card">
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
                            @foreach($teachers as $teacher)
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
