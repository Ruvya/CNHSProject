@extends('layouts.registrar')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-plus-circle me-2 text-primary"></i>
                Assign Subject to Teacher
            </h1>
            <p class="text-muted mb-0">Create a new subject assignment for a teacher</p>
        </div>
        <div>
            <a href="{{ route('registrar.subject-assignments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Assignments
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Assignment Form -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-form me-2"></i>Assignment Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('registrar.subject-assignments.store') }}" id="assignmentForm">
                        @csrf
                        
                        <!-- Teacher Selection -->
                        <div class="mb-4">
                            <label for="teacher_id" class="form-label fw-bold">
                                <i class="fas fa-user me-1"></i>Select Teacher <span class="text-danger">*</span>
                            </label>
                            <select name="teacher_id" id="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                                <option value="">Choose a teacher...</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" 
                                            data-email="{{ $teacher->email }}"
                                            data-subject="{{ $teacher->subject }}"
                                            data-strand="{{ $teacher->strand }}"
                                            {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subject Selection -->
                        <div class="mb-4">
                            <label for="subject_id" class="form-label fw-bold">
                                <i class="fas fa-book me-1"></i>Select Subject <span class="text-danger">*</span>
                            </label>
                            <select name="subject_id" id="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                                <option value="">Choose a subject...</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}"
                                            data-code="{{ $subject->code }}"
                                            data-grade="{{ $subject->grade_level }}"
                                            data-track="{{ $subject->track }}"
                                            data-strand="{{ $subject->strand }}"
                                            data-description="{{ $subject->description }}"
                                            {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->code }} - {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Academic Period - REQUIRED FIELDS -->
                        <div class="card border-warning mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Required: Academic Period</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="school_year" class="form-label fw-bold">
                                            <i class="fas fa-calendar me-1"></i>School Year <span class="text-danger">*</span>
                                        </label>
                                        <select name="school_year" id="school_year" class="form-select @error('school_year') is-invalid @enderror" required>
                                            <option value="2024-2025" {{ old('school_year', $currentSchoolYear ?? '2024-2025') == '2024-2025' ? 'selected' : '' }}>2024-2025</option>
                                            <option value="2025-2026" {{ old('school_year', $currentSchoolYear ?? '2024-2025') == '2025-2026' ? 'selected' : '' }}>2025-2026</option>
                                        </select>
                                        @error('school_year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="grading_period" class="form-label fw-bold">
                                            <i class="fas fa-clock me-1"></i>Grading Period <span class="text-danger">*</span>
                                        </label>
                                        <select name="grading_period" id="grading_period" class="form-select @error('grading_period') is-invalid @enderror" required>
                                            <option value="First Grading" {{ old('grading_period', $currentGradingPeriod ?? 'First Grading') == 'First Grading' ? 'selected' : '' }}>First Grading</option>
                                            <option value="Second Grading" {{ old('grading_period', $currentGradingPeriod ?? 'First Grading') == 'Second Grading' ? 'selected' : '' }}>Second Grading</option>
                                            <option value="Third Grading" {{ old('grading_period', $currentGradingPeriod ?? 'First Grading') == 'Third Grading' ? 'selected' : '' }}>Third Grading</option>
                                            <option value="Fourth Grading" {{ old('grading_period', $currentGradingPeriod ?? 'First Grading') == 'Fourth Grading' ? 'selected' : '' }}>Fourth Grading</option>
                                        </select>
                                        @error('grading_period')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule (Optional) -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-schedule me-1"></i>Teaching Schedule (Optional)
                            </label>
                            <div id="scheduleContainer">
                                <div class="schedule-slot border rounded p-3 mb-2">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label">Day</label>
                                            <select name="schedule[0][day]" class="form-select">
                                                <option value="">Select Day</option>
                                                <option value="Monday">Monday</option>
                                                <option value="Tuesday">Tuesday</option>
                                                <option value="Wednesday">Wednesday</option>
                                                <option value="Thursday">Thursday</option>
                                                <option value="Friday">Friday</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Start Time</label>
                                            <input type="time" name="schedule[0][start_time]" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">End Time</label>
                                            <input type="time" name="schedule[0][end_time]" class="form-control">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-schedule" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="addSchedule" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Add Another Time Slot
                            </button>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold">
                                <i class="fas fa-sticky-note me-1"></i>Notes (Optional)
                            </label>
                            <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" 
                                      placeholder="Add any additional notes about this assignment...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Notification -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="send_email" id="send_email" value="1" {{ old('send_email') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="send_email">
                                    <i class="fas fa-envelope me-1"></i>Send email notification to teacher
                                </label>
                                <div class="form-text">The teacher will receive an email with assignment details and login instructions.</div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('registrar.subject-assignments.index') }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-check me-1"></i>Assign Subject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Teacher Info Card -->
            <div class="card shadow-sm mb-4" id="teacherInfoCard" style="display: none;">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Teacher Information</h6>
                </div>
                <div class="card-body" id="teacherInfo">
                    <!-- Teacher details will be populated here -->
                </div>
            </div>

            <!-- Subject Info Card -->
            <div class="card shadow-sm mb-4" id="subjectInfoCard" style="display: none;">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-book me-2"></i>Subject Information</h6>
                </div>
                <div class="card-body" id="subjectInfo">
                    <!-- Subject details will be populated here -->
                </div>
            </div>

            <!-- Assignment Guidelines -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Assignment Guidelines</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Ensure teacher qualifications match subject requirements</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Check for schedule conflicts before assigning</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Consider teacher's current workload</li>
                        <li class="mb-0"><i class="fas fa-check text-success me-2"></i>Send email notification to inform teacher</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center" style="z-index: 9999;">
    <div class="text-center text-white">
        <div class="spinner-border mb-3" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h5>Creating Assignment...</h5>
        <p>Please wait while we process the assignment.</p>
    </div>
</div>

<style>
.page-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #2c3e50;
}

.schedule-slot {
    background-color: #f8f9fa;
}

.card {
    border-radius: 10px;
}
</style>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const teacherSelect = document.getElementById('teacher_id');
    const subjectSelect = document.getElementById('subject_id');
    const teacherInfoCard = document.getElementById('teacherInfoCard');
    const subjectInfoCard = document.getElementById('subjectInfoCard');
    const teacherInfo = document.getElementById('teacherInfo');
    const subjectInfo = document.getElementById('subjectInfo');
    const addScheduleBtn = document.getElementById('addSchedule');
    const scheduleContainer = document.getElementById('scheduleContainer');
    let scheduleIndex = 1;

    // Handle teacher selection
    teacherSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (this.value) {
            const name = selectedOption.text;
            const email = selectedOption.dataset.email;
            const subject = selectedOption.dataset.subject;
            const strand = selectedOption.dataset.strand;

            teacherInfo.innerHTML = `
                <p><strong>Name:</strong> ${name}</p>
                <p><strong>Email:</strong> ${email}</p>
                ${subject ? `<p><strong>Subject Expertise:</strong> ${subject}</p>` : ''}
                ${strand ? `<p><strong>Strand:</strong> ${strand}</p>` : ''}
            `;
            teacherInfoCard.style.display = 'block';
        } else {
            teacherInfoCard.style.display = 'none';
        }
    });

    // Handle subject selection
    subjectSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (this.value) {
            const name = selectedOption.text;
            const code = selectedOption.dataset.code;
            const grade = selectedOption.dataset.grade;
            const track = selectedOption.dataset.track;
            const strand = selectedOption.dataset.strand;
            const description = selectedOption.dataset.description;

            subjectInfo.innerHTML = `
                <p><strong>Subject:</strong> ${name}</p>
                <p><strong>Code:</strong> ${code}</p>
                <p><strong>Grade Level:</strong> ${grade}</p>
                <p><strong>Track:</strong> ${track}</p>
                ${strand ? `<p><strong>Strand:</strong> ${strand}</p>` : ''}
                ${description ? `<p><strong>Description:</strong> ${description}</p>` : ''}
            `;
            subjectInfoCard.style.display = 'block';
        } else {
            subjectInfoCard.style.display = 'none';
        }
    });

    // Handle adding schedule slots
    addScheduleBtn.addEventListener('click', function() {
        const newSlot = document.createElement('div');
        newSlot.className = 'schedule-slot border rounded p-3 mb-2';
        newSlot.innerHTML = `
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label">Day</label>
                    <select name="schedule[${scheduleIndex}][day]" class="form-select">
                        <option value="">Select Day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Start Time</label>
                    <input type="time" name="schedule[${scheduleIndex}][start_time]" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Time</label>
                    <input type="time" name="schedule[${scheduleIndex}][end_time]" class="form-control">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-schedule">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        scheduleContainer.appendChild(newSlot);
        scheduleIndex++;

        // Show remove buttons for all slots except the first one
        updateRemoveButtons();
    });

    // Handle removing schedule slots
    scheduleContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-schedule')) {
            e.target.closest('.schedule-slot').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const slots = scheduleContainer.querySelectorAll('.schedule-slot');
        slots.forEach((slot, index) => {
            const removeBtn = slot.querySelector('.remove-schedule');
            if (slots.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }

    // Handle form submission
    const form = document.getElementById('assignmentForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingOverlay = document.getElementById('loadingOverlay');

    form.addEventListener('submit', function(e) {
        // Validate required fields
        const teacherId = teacherSelect.value;
        const subjectId = subjectSelect.value;

        if (!teacherId) {
            e.preventDefault();
            alert('Please select a teacher.');
            teacherSelect.focus();
            return;
        }

        if (!subjectId) {
            e.preventDefault();
            alert('Please select a subject.');
            subjectSelect.focus();
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Assigning...';
        loadingOverlay.classList.remove('d-none');
    });

    // Set default values for school year and grading period if not already set
    const schoolYearSelect = document.getElementById('school_year');
    const gradingPeriodSelect = document.getElementById('grading_period');

    // Force set default values
    if (!schoolYearSelect.value || schoolYearSelect.value === '') {
        schoolYearSelect.value = '2024-2025'; // Default to current school year
        console.log('Set default school year: 2024-2025');
    }

    if (!gradingPeriodSelect.value || gradingPeriodSelect.value === '') {
        gradingPeriodSelect.value = 'First Grading'; // Default to first grading
        console.log('Set default grading period: First Grading');
    }

    // Add visual indicators that these fields are required
    schoolYearSelect.style.borderColor = '#28a745';
    gradingPeriodSelect.style.borderColor = '#28a745';

    // Add change listeners to validate
    schoolYearSelect.addEventListener('change', function() {
        if (this.value) {
            this.style.borderColor = '#28a745';
        } else {
            this.style.borderColor = '#dc3545';
        }
    });

    gradingPeriodSelect.addEventListener('change', function() {
        if (this.value) {
            this.style.borderColor = '#28a745';
        } else {
            this.style.borderColor = '#dc3545';
        }
    });

    // Trigger change events if values are pre-selected
    if (teacherSelect.value) {
        teacherSelect.dispatchEvent(new Event('change'));
    }
    if (subjectSelect.value) {
        subjectSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush

@endsection
