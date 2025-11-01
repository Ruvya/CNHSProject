@extends('layouts.admin')
@section('title', 'Edit Class Schedule')
@section('content')
<div class="container-fluid">
    <!-- Header (match User Management style) -->
    <div class="card mb-4" style="border-radius: 14px; box-shadow: 0 8px 25px rgba(30,58,138,0.12); border: none;">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between" style="padding: 1.2rem 1.5rem;">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <span style="font-size: 2rem; color: #2563eb; background: #f1f5fb; border-radius: 12px; padding: 0.7rem; margin-right: 1rem; display: flex; align-items: center;">
                    <i class="fas fa-calendar-edit"></i>
                </span>
                <div>
                    <div style="font-size: 1.3rem; font-weight: bold; font-family: 'Poppins', sans-serif; color: #222;">Edit Class Schedule</div>
                    <div style="font-size: 0.95rem; font-family: 'Poppins', sans-serif; color: #555; font-weight: 500;">Update schedule information and resolve conflicts</div>
                </div>
            </div>
            <div class="mb-2 mb-md-0">
                <a href="{{ route('admin.scheduling.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Schedules
                </a>
            </div>
        </div>
    </div>

    <!-- Schedule Form -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-edit me-2"></i>Schedule Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.scheduling.update', $schedule) }}" method="POST" id="scheduleForm">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <!-- Teacher Selection -->
                    <div class="col-md-6">
                        <label for="teacher_id" class="form-label">Teacher <span class="text-danger">*</span></label>
                        <select name="teacher_id" id="teacher_id" class="form-select" required>
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $schedule->teacher_id == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }} ({{ $teacher->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Subject Selection -->
                    <div class="col-md-6">
                        <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
                        <select name="subject_id" id="subject_id" class="form-select" required>
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $schedule->subject_id == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Section Selection -->
                    <div class="col-md-6">
                        <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                        <select name="section_id" id="section_id" class="form-select" required>
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ $schedule->section_id == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }} (Grade {{ $section->grade_level }})
                                </option>
                            @endforeach
                        </select>
                        @error('section_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Room removed -->

                    <!-- Day Selection -->
                    <div class="col-md-4">
                        <label for="day" class="form-label">Day <span class="text-danger">*</span></label>
                        <select name="day" id="day" class="form-select" required>
                            <option value="">Select Day</option>
                            @foreach($days as $day)
                                <option value="{{ $day }}" {{ $schedule->day == $day ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>
                        @error('day')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Time Selection -->
                    <div class="col-md-4">
                        <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                        <input type="time" name="start_time" id="start_time" class="form-control" 
                               value="{{ date('H:i', strtotime($schedule->start_time)) }}" required>
                        @error('start_time')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                        <input type="time" name="end_time" id="end_time" class="form-control" 
                               value="{{ date('H:i', strtotime($schedule->end_time)) }}" required>
                        @error('end_time')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- School Year -->
                    <div class="col-md-6">
                        <label for="school_year" class="form-label">School Year <span class="text-danger">*</span></label>
                        <input type="text" name="school_year" id="school_year" class="form-control" 
                               value="{{ $schedule->school_year }}" required>
                        @error('school_year')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Semester -->
                    <div class="col-md-6">
                        <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                        <select name="semester" id="semester" class="form-select" required>
                            <option value="">Select Semester</option>
                            <option value="1st Semester" {{ $schedule->semester == '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                            <option value="2nd Semester" {{ $schedule->semester == '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                        </select>
                        @error('semester')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ $schedule->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $schedule->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="cancelled" {{ $schedule->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" 
                                  placeholder="Additional notes about this schedule...">{{ $schedule->notes }}</textarea>
                        @error('notes')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Conflict Validation -->
                <div id="conflictValidation" class="mt-4" style="display: none;">
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Schedule Validation</h6>
                        <div id="validationResults"></div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.scheduling.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <div>
                                <button type="button" id="validateBtn" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-check-circle me-2"></i>Validate Schedule
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Schedule
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('scheduleForm');
    const validateBtn = document.getElementById('validateBtn');
    const conflictValidation = document.getElementById('conflictValidation');
    const validationResults = document.getElementById('validationResults');

    // Validate schedule conflicts
    validateBtn.addEventListener('click', function() {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        data.exclude_schedule_id = {{ $schedule->id }};

        // Show loading
        validateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Validating...';
        validateBtn.disabled = true;

        fetch('{{ route("admin.scheduling.validate-conflicts") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            conflictValidation.style.display = 'block';
            
            let html = '';
            if (result.valid) {
                html = '<div class="text-success"><i class="fas fa-check-circle me-2"></i>No conflicts detected! Schedule is valid.</div>';
            } else {
                html = '<div class="text-danger"><strong>Conflicts detected:</strong><ul class="mb-0 mt-2">';
                result.errors.forEach(error => {
                    html += `<li>${error}</li>`;
                });
                html += '</ul></div>';
            }

            if (result.warnings && result.warnings.length > 0) {
                html += '<div class="text-warning mt-2"><strong>Warnings:</strong><ul class="mb-0 mt-2">';
                result.warnings.forEach(warning => {
                    html += `<li>${warning}</li>`;
                });
                html += '</ul></div>';
            }

            validationResults.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            validationResults.innerHTML = '<div class="text-danger">Error validating schedule. Please try again.</div>';
        })
        .finally(() => {
            validateBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Validate Schedule';
            validateBtn.disabled = false;
        });
    });

    // Auto-validate when key fields change
    const keyFields = ['teacher_id', 'subject_id', 'section_id', 'room_id', 'day', 'start_time', 'end_time'];
    keyFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('change', function() {
                if (conflictValidation.style.display === 'block') {
                    validateBtn.click();
                }
            });
        }
    });

    // Ensure end time is after start time
    document.getElementById('start_time').addEventListener('change', function() {
        const startTime = this.value;
        const endTimeInput = document.getElementById('end_time');
        
        if (startTime && endTimeInput.value && endTimeInput.value <= startTime) {
            endTimeInput.value = '';
        }
    });
});
</script>
@endsection
