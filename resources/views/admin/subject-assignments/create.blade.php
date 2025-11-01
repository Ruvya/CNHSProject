@extends('layouts.admin')
@section('title', 'Assign Subject to Teacher')
@section('content')
<div class="container-fluid">
    <!-- Success Notification -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <!-- Header (match User Management style) -->
    <div class="card mb-4" style="border-radius: 14px; box-shadow: 0 8px 25px rgba(30,58,138,0.12); border: none;">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between" style="padding: 1.2rem 1.5rem;">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <span style="font-size: 2rem; color: #2563eb; background: #f1f5fb; border-radius: 12px; padding: 0.7rem; margin-right: 1rem; display: flex; align-items: center;">
                    <i class="fas fa-plus-circle"></i>
                </span>
                <div>
                    <div style="font-size: 1.3rem; font-weight: bold; font-family: 'Poppins', sans-serif; color: #222;">Assign Subject to Teacher</div>
                    <div style="font-size: 0.95rem; font-family: 'Poppins', sans-serif; color: #555; font-weight: 500;">Create a new subject assignment for a teacher</div>
                </div>
            </div>
            <div class="mb-2 mb-md-0">
                <a href="{{ route('admin.subject-assignments.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Assignments
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- Assignment Form -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-form me-2"></i>Assignment Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.subject-assignments.store') }}" id="assignmentForm">
                        @csrf
                        <!-- Teacher Selection -->
                        <div class="mb-4">
                            <label for="teacher_id" class="form-label fw-bold">
                                <i class="fas fa-user me-1"></i>Select Teacher <span class="text-danger">*</span>
                            </label>
                            <select name="teacher_id" id="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                                <option value="">Choose a teacher...</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
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
                                        {{ (old('subject_id', $selectedSubjectId ?? '') == $subject->id) ? 'selected' : '' }}>
                                        {{ $subject->code ?? '' }} - {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- School Year and Semester -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="school_year" class="form-label fw-bold">
                                    <i class="fas fa-calendar me-1"></i>School Year <span class="text-danger">*</span>
                                </label>
                                <select name="school_year" id="school_year" class="form-select @error('school_year') is-invalid @enderror" required>
                                    <option value="">Select School Year...</option>
                                    @php
                                        $schoolYears = \App\Models\SchoolYear::orderByDesc('start_year')->get();
                                        $defaultYear = old('school_year', optional(\App\Models\SchoolYear::active()->first())->name ?? '');
                                    @endphp
                                    @foreach($schoolYears as $sy)
                                        <option value="{{ $sy->name }}" {{ $defaultYear == $sy->name ? 'selected' : '' }}>
                                            {{ $sy->name }} {{ $sy->status === 'active' ? '(Active)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('school_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="semester" class="form-label fw-bold">
                                    <i class="fas fa-calendar-alt me-1"></i>Semester <span class="text-danger">*</span>
                                </label>
                                <select name="semester" id="semester" class="form-select @error('semester') is-invalid @enderror" required>
                                    <option value="">Select Semester...</option>
                                    <option value="1st Semester" {{ old('semester', '1st Semester') == '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                                    <option value="2nd Semester" {{ old('semester') == '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                                    <option value="Both Semesters" {{ old('semester') == 'Both Semesters' ? 'selected' : '' }}>Both Semesters</option>
                                </select>
                                @error('semester')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Assignment Status -->
                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">
                                <i class="fas fa-toggle-on me-1"></i>Assignment Status <span class="text-danger">*</span>
                            </label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Active assignments are immediately effective</div>
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
                            <a href="{{ route('admin.subject-assignments.index') }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="button" class="btn btn-primary" id="openConfirmModal" data-bs-toggle="modal" data-bs-target="#confirmModal">
                                <i class="fas fa-check me-1"></i>Assign Subject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="confirmModalLabel">Apply Changes?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Review</button>
        <button type="submit" form="assignmentForm" class="btn btn-primary">Yes</button>
      </div>
    </div>
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

.validation-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
}

.validation-feedback.valid {
    color: #198754;
}

.validation-feedback.invalid {
    color: #dc3545;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('assignmentForm');
    const teacherSelect = document.getElementById('teacher_id');
    const subjectSelect = document.getElementById('subject_id');
    const schoolYearSelect = document.getElementById('school_year');
    const semesterSelect = document.getElementById('semester');
    const statusSelect = document.getElementById('status');
    const submitBtn = document.getElementById('openConfirmModal');

    // Real-time validation feedback
    function showValidationFeedback(element, isValid, message) {
        // Remove existing feedback
        const existingFeedback = element.parentNode.querySelector('.validation-feedback');
        if (existingFeedback) {
            existingFeedback.remove();
        }

        // Add new feedback
        const feedback = document.createElement('div');
        feedback.className = `validation-feedback ${isValid ? 'valid' : 'invalid'}`;
        feedback.innerHTML = `<i class="fas fa-${isValid ? 'check' : 'times'} me-1"></i>${message}`;
        element.parentNode.appendChild(feedback);

        // Update element styling
        element.classList.remove('is-valid', 'is-invalid');
        element.classList.add(isValid ? 'is-valid' : 'is-invalid');
    }

    // Validate teacher selection
    teacherSelect.addEventListener('change', function() {
        if (this.value) {
            const teacherName = this.options[this.selectedIndex].text;
            showValidationFeedback(this, true, `Teacher "${teacherName}" selected`);
        } else {
            showValidationFeedback(this, false, 'Please select a teacher');
        }
        validateForm();
    });

    // Validate subject selection
    subjectSelect.addEventListener('change', function() {
        if (this.value) {
            const subjectName = this.options[this.selectedIndex].text;
            showValidationFeedback(this, true, `Subject "${subjectName}" selected`);
        } else {
            showValidationFeedback(this, false, 'Please select a subject');
        }
        validateForm();
    });

    // Validate school year selection
    schoolYearSelect.addEventListener('change', function() {
        if (this.value) {
            const schoolYear = this.options[this.selectedIndex].text;
            showValidationFeedback(this, true, `School Year "${schoolYear}" selected`);
        } else {
            showValidationFeedback(this, false, 'Please select a school year');
        }
        validateForm();
    });

    // Validate semester selection
    semesterSelect.addEventListener('change', function() {
        if (this.value) {
            const semester = this.options[this.selectedIndex].text;
            showValidationFeedback(this, true, `Semester "${semester}" selected`);
        } else {
            showValidationFeedback(this, false, 'Please select a semester');
        }
        validateForm();
    });

    // Validate status selection
    statusSelect.addEventListener('change', function() {
        if (this.value) {
            const statusName = this.options[this.selectedIndex].text;
            showValidationFeedback(this, true, `Status "${statusName}" selected`);
        } else {
            showValidationFeedback(this, false, 'Please select a status');
        }
        validateForm();
    });

    // Form validation
    function validateForm() {
        const isValid = teacherSelect.value && subjectSelect.value && schoolYearSelect.value && semesterSelect.value && statusSelect.value;
        submitBtn.disabled = !isValid;

        if (isValid) {
            submitBtn.innerHTML = '<i class="fas fa-check me-1"></i>Assign Subject';
            submitBtn.className = 'btn btn-primary';
        } else {
            submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Please Complete Required Fields';
            submitBtn.className = 'btn btn-secondary';
        }
    }

    // Initial validation
    validateForm();
});
</script>
@endsection