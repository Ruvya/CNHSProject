@extends('layouts.admin')
@section('title', 'Create Class Schedule')
@section('content')
<div class="container-fluid">
    <!-- Modern Angled Header Card -->
    <div class="angled-header-card mb-4">
        <div class="header-left-content">
            <span class="icon"><i class="fas fa-calendar-plus"></i></span>
            <div>
                <span class="title">Create Class Schedule</span>
                <span class="subtitle">Assign teachers to subjects, sections, and timeslots</span>
            </div>
        </div>
        <div class="header-right-content">
            <a href="{{ route('admin.scheduling.index') }}" class="angled-header-btn">
                <i class="fas fa-arrow-left me-2"></i> Back to Schedules
            </a>
        </div>
    </div>

    <!-- Schedule Form -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-plus me-2"></i>Schedule Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.scheduling.store') }}" method="POST" id="scheduleForm">
                @csrf
                
                <div class="row g-3">
                    <!-- Teacher Selection -->
                    <div class="col-md-6">
                        <label for="teacher_id" class="form-label">Teacher <span class="text-danger">*</span></label>
                        <select name="teacher_id" id="teacher_id" class="form-select" required>
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ (isset($selectedTeacher) && $selectedTeacher == $teacher->id) ? 'selected' : '' }}>
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
                                <option value="{{ $subject->id }}" {{ (isset($selectedSubject) && $selectedSubject == $subject->id) ? 'selected' : '' }}>
                                    {{ $subject->code ?? 'No Code' }} - {{ $subject->name }}
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
                                <option value="{{ $section->id }}" {{ (isset($selectedSection) && $selectedSection == $section->id) ? 'selected' : '' }}>
                                    {{ $section->name }} (Grade {{ $section->grade_level }})
                                </option>
                            @endforeach
                        </select>
                        @error('section_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    

                    <!-- Day Selection -->
                    <div class="col-md-4">
                        <label for="day" class="form-label">Day <span class="text-danger">*</span></label>
                        <select name="day" id="day" class="form-select" required>
                            <option value="">Select Day</option>
                            @foreach($days as $day)
                                <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                        @error('day')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Time Selection -->
                    <div class="col-md-4">
                        <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                        <input type="time" name="start_time" id="start_time" class="form-control" required>
                        @error('start_time')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                        <input type="time" name="end_time" id="end_time" class="form-control" required>
                        @error('end_time')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- School Year -->
                    <div class="col-md-6">
                        <label for="school_year" class="form-label">School Year <span class="text-danger">*</span></label>
                        <input type="text" name="school_year" id="school_year" class="form-control" 
                               value="{{ date('Y') . '-' . (date('Y') + 1) }}" required>
                        @error('school_year')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

					<!-- Semester -->
                    <div class="col-md-6">
						<label for="grading_period" class="form-label">Semester <span class="text-danger">*</span></label>
                        <select name="grading_period" id="grading_period" class="form-select" required>
							<option value="">Select Semester</option>
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                        </select>
                        @error('grading_period')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" 
                                  placeholder="Additional notes about this schedule..."></textarea>
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
                                <button type="submit" id="submitBtn" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Create Schedule
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
// Expose URLs for use inside verbatim JS block
window.SCHED_URLS = {
    validateConflicts: "{{ route('admin.scheduling.validate-conflicts') }}",
    index: "{{ route('admin.scheduling.index') }}",
    store: "{{ route('admin.scheduling.store') }}"
};
</script>
<script>
@verbatim
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('scheduleForm');
    const validateBtn = document.getElementById('validateBtn');
    const conflictValidation = document.getElementById('conflictValidation');
    const validationResults = document.getElementById('validationResults');
    const submitBtn = document.getElementById('submitBtn');

    // Validate schedule conflicts
    validateBtn.addEventListener('click', function() {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        // Show loading
        validateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Validating...';
        validateBtn.disabled = true;

        fetch(window.SCHED_URLS.validateConflicts, {
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
    const keyFields = ['teacher_id', 'subject_id', 'section_id', 'day', 'start_time', 'end_time'];
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

    // Intercept form submit to prevent full page reload
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Clear previous inline errors
        form.querySelectorAll('.text-danger.small').forEach(el => { el.textContent = ''; });

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        submitBtn.disabled = true;
        const originalHtml = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';

        fetch(window.SCHED_URLS.store, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(async (response) => {
            const json = await response.json().catch(() => ({}));
            if (!response.ok) {
                throw { status: response.status, body: json };
            }
            return json;
        })
        .then(result => {
            // If there are warnings from earlier validation, keep showing them
            if (result.message) {
                // Optionally show a toast/alert. For now, redirect.
            }
            if (result.redirect) {
                window.location.href = result.redirect;
            } else {
                // Fallback: reload index
                window.location.href = window.SCHED_URLS.index;
            }
        })
        .catch(err => {
            // Handle validation errors
            if (err && err.status === 422 && err.body) {
                const errors = err.body.errors || err.body;
                // Map known fields
                const fields = ['teacher_id','subject_id','section_id','day','start_time','end_time','school_year','grading_period','notes'];
                fields.forEach(field => {
                    const input = document.getElementById(field);
                    if (input) {
                        // Find the next sibling error container from Blade (@error blocks)
                        const group = input.closest('.col-md-6, .col-md-4, .col-12');
                        if (group) {
                            let errorEl = group.querySelector('.text-danger.small');
                            if (!errorEl) {
                                errorEl = document.createElement('div');
                                errorEl.className = 'text-danger small mt-1';
                                group.appendChild(errorEl);
                            }
                            if (errors[field]) {
                                errorEl.textContent = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                            }
                        }
                    }
                });

                // Show conflict messages if provided as plain array
                if (Array.isArray(err.body.errors)) {
                    conflictValidation.style.display = 'block';
                    validationResults.innerHTML = '<div class="text-danger"><strong>Conflicts detected:</strong><ul class="mb-0 mt-2">' +
                        err.body.errors.map(e => `<li>${e}</li>`).join('') + '</ul></div>';
                }
            } else {
                alert('An unexpected error occurred. Please try again.');
                console.error(err);
            }
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
        });
    });
});
@endverbatim
</script>
@endsection
