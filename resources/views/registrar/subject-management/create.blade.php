@extends('layouts.registrar')

@section('title', 'Create Subject')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Create New Subject</h1>
                    <p class="text-muted">Create a new subject and optionally assign a teacher</p>
                </div>
                <div>
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
                    <form action="{{ route('registrar.subject-management.store') }}" method="POST">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">Basic Information</h6>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code') }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
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
                                    <option value="Grade 11" {{ old('grade_level') == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                    <option value="Grade 12" {{ old('grade_level') == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
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
                                    <option value="Academic" {{ old('track') == 'Academic' ? 'selected' : '' }}>Academic</option>
                                    <option value="TVL" {{ old('track') == 'TVL' ? 'selected' : '' }}>TVL</option>
                                    <option value="Sports" {{ old('track') == 'Sports' ? 'selected' : '' }}>Sports</option>
                                    <option value="Arts & Design" {{ old('track') == 'Arts & Design' ? 'selected' : '' }}>Arts & Design</option>
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
                                    <option value="HUMSS" {{ old('cluster') == 'HUMSS' ? 'selected' : '' }}>HUMSS</option>
                                    <option value="STEM" {{ old('cluster') == 'STEM' ? 'selected' : '' }}>STEM</option>
                                    <option value="ABM" {{ old('cluster') == 'ABM' ? 'selected' : '' }}>ABM</option>
                                    <option value="GAS" {{ old('cluster') == 'GAS' ? 'selected' : '' }}>GAS</option>
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
                                    <option value="1st Semester" {{ old('semester') == '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                                    <option value="2nd Semester" {{ old('semester') == '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                                    <option value="Both Semesters" {{ old('semester') == 'Both Semesters' ? 'selected' : '' }}>Both Semesters</option>
                                </select>
                                @error('semester')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="specialization" class="form-label">Specialization</label>
                                <input type="text" class="form-control @error('specialization') is-invalid @enderror" 
                                       id="specialization" name="specialization" value="{{ old('specialization') }}">
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
                                           value="1" {{ old('is_core_subject') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_core_subject">
                                        Core Subject (Required for all students)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_master_subject" name="is_master_subject" 
                                           value="1" {{ old('is_master_subject') ? 'checked' : '' }}>
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
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="schedule_days_{{ strtolower($day) }}" 
                                                       name="schedule_days[]" value="{{ $day }}" 
                                                       {{ in_array($day, old('schedule_days', [])) ? 'checked' : '' }}>
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
                                       id="start_time" name="start_time" value="{{ old('start_time') }}">
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="end_time" class="form-label">End Time</label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                       id="end_time" name="end_time" value="{{ old('end_time') }}">
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="room" class="form-label">Room</label>
                                <input type="text" class="form-control @error('room') is-invalid @enderror" 
                                       id="room" name="room" value="{{ old('room') }}">
                                @error('room')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="schedule_notes" class="form-label">Schedule Notes</label>
                                <input type="text" class="form-control @error('schedule_notes') is-invalid @enderror" 
                                       id="schedule_notes" name="schedule_notes" value="{{ old('schedule_notes') }}">
                                @error('schedule_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Teacher Assignment -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">Teacher Assignment (Optional)</h6>
                            </div>
                            <div class="col-12">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="assign_teacher" name="assign_teacher" 
                                           value="1" {{ old('assign_teacher') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="assign_teacher">
                                        Assign a teacher to this subject
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6" id="teacher-selection" style="display: none;">
                                <label for="assignment_teacher_id" class="form-label">Select Teacher</label>
                                <select class="form-select @error('assignment_teacher_id') is-invalid @enderror" 
                                        id="assignment_teacher_id" name="assignment_teacher_id">
                                    <option value="">Choose a teacher...</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('assignment_teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assignment_teacher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Assignment Schedule -->
                        <div class="row mb-4" id="assignment-schedule" style="display: none;">
                            <div class="col-12">
                                <label class="form-label">Assignment Schedule (Optional)</label>
                                <div id="schedule-container">
                                    <div class="schedule-item row mb-2">
                                        <div class="col-md-4">
                                            <select class="form-select" name="assignment_schedule[0][day]">
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
                                            <input type="time" class="form-control" name="assignment_schedule[0][start_time]" placeholder="Start Time">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="time" class="form-control" name="assignment_schedule[0][end_time]" placeholder="End Time">
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
                        </div>

                        <div class="row mb-4" id="assignment-notes" style="display: none;">
                            <div class="col-12">
                                <label for="assignment_notes" class="form-label">Assignment Notes</label>
                                <textarea class="form-control" id="assignment_notes" name="assignment_notes" rows="3" placeholder="Additional notes for the assignment...">{{ old('assignment_notes') }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-4" id="send-email" style="display: none;">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="send_email" name="send_email" value="1" {{ old('send_email') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="send_email">
                                        Send email notification to teacher
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('registrar.subject-management.index') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create Subject
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Help & Tips</h5>
                </div>
                <div class="card-body">
                    <h6>Subject Creation Tips:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i> Use clear, descriptive subject names</li>
                        <li><i class="fas fa-check text-success me-2"></i> Subject codes should be unique and meaningful</li>
                        <li><i class="fas fa-check text-success me-2"></i> Core subjects are required for all students</li>
                        <li><i class="fas fa-check text-success me-2"></i> You can assign a teacher immediately or later</li>
                    </ul>
                    
                    <h6 class="mt-3">Current School Year:</h6>
                    <p class="text-muted">{{ $currentSchoolYear }} - {{ $currentSemester }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const assignTeacherCheckbox = document.getElementById('assign_teacher');
    const teacherSelection = document.getElementById('teacher-selection');
    const assignmentSchedule = document.getElementById('assignment-schedule');
    const assignmentNotes = document.getElementById('assignment-notes');
    const sendEmail = document.getElementById('send-email');
    
    // Toggle teacher assignment fields
    assignTeacherCheckbox.addEventListener('change', function() {
        if (this.checked) {
            teacherSelection.style.display = 'block';
            assignmentSchedule.style.display = 'block';
            assignmentNotes.style.display = 'block';
            sendEmail.style.display = 'block';
        } else {
            teacherSelection.style.display = 'none';
            assignmentSchedule.style.display = 'none';
            assignmentNotes.style.display = 'none';
            sendEmail.style.display = 'none';
        }
    });
    
    // Initialize display based on old values
    if (assignTeacherCheckbox.checked) {
        teacherSelection.style.display = 'block';
        assignmentSchedule.style.display = 'block';
        assignmentNotes.style.display = 'block';
        sendEmail.style.display = 'block';
    }
    
    // Schedule management
    let scheduleIndex = 1;
    
    document.getElementById('add-schedule').addEventListener('click', function() {
        const container = document.getElementById('schedule-container');
        const newItem = document.createElement('div');
        newItem.className = 'schedule-item row mb-2';
        newItem.innerHTML = `
            <div class="col-md-4">
                <select class="form-select" name="assignment_schedule[${scheduleIndex}][day]">
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
                <input type="time" class="form-control" name="assignment_schedule[${scheduleIndex}][start_time]" placeholder="Start Time">
            </div>
            <div class="col-md-3">
                <input type="time" class="form-control" name="assignment_schedule[${scheduleIndex}][end_time]" placeholder="End Time">
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
