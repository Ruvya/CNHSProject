@extends('layouts.registrar')

@section('title', 'Create Subject Offering')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-plus me-2 text-primary"></i>
                Create Subject Offering
            </h1>
            <p class="text-muted mb-0">Offer a master subject for a specific school year, semester, and section</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('registrar.subject-offerings.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Offerings
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-1">
                        <i class="fas fa-calendar-plus me-2 text-primary"></i>
                        Subject Offering Details
                    </h5>
                    <p class="text-muted mb-0 small">Configure the subject offering and schedule</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('registrar.subject-offerings.store') }}" method="POST" id="offeringForm">
                        @csrf

                        <!-- Subject Selection Section -->
                        <div class="form-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-book me-2 text-primary"></i>
                                Subject Selection
                            </h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="subject_id" class="form-label fw-semibold">
                                        Master Subject <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('subject_id') is-invalid @enderror"
                                            id="subject_id"
                                            name="subject_id"
                                            required>
                                        <option value="">Select a Master Subject</option>
                                        @foreach($masterSubjects as $subject)
                                            <option value="{{ $subject->id }}"
                                                    data-code="{{ $subject->code }}"
                                                    data-units="{{ $subject->units }}"
                                                    data-grade="{{ $subject->grade_level }}"
                                                    data-semester="{{ $subject->semester }}"
                                                    {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->code }} - {{ $subject->name }}
                                                ({{ $subject->grade_level }}, {{ $subject->semester }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Choose from admin-created master subjects</div>
                                </div>
                            </div>
                        </div>

                        <!-- Offering Details Section -->
                        <div class="form-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-info-circle me-2 text-success"></i>
                                Offering Details
                            </h6>
                            <div class="row">
                                <!-- School Year -->
                                <div class="col-md-3 mb-3">
                                    <label for="school_year" class="form-label fw-semibold">
                                        School Year <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('school_year') is-invalid @enderror"
                                            id="school_year"
                                            name="school_year"
                                            required>
                                        @foreach($schoolYears as $year)
                                            <option value="{{ $year }}" {{ old('school_year', $schoolYears[1]) === $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('school_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Grading -->
                                <div class="col-md-3 mb-3">
                                    <label for="grading" class="form-label fw-semibold">
                                        Grading <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('grading') is-invalid @enderror"
                                            id="grading"
                                            name="grading"
                                            required>
                                        <option value="">Select Grading</option>
                                        @foreach($gradings as $grading)
                                            <option value="{{ $grading }}" {{ old('grading') === $grading ? 'selected' : '' }}>
                                                {{ $grading }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('grading')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Grade Level -->
                                <div class="col-md-3 mb-3">
                                    <label for="grade_level" class="form-label fw-semibold">
                                        Grade Level <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('grade_level') is-invalid @enderror"
                                            id="grade_level"
                                            name="grade_level"
                                            required>
                                        <option value="">Select Grade Level</option>
                                        @foreach($gradeLevels as $grade)
                                            <option value="{{ $grade }}" {{ old('grade_level') === $grade ? 'selected' : '' }}>
                                                {{ $grade }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('grade_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Track -->
                                <div class="col-md-3 mb-3">
                                    <label for="track" class="form-label fw-semibold">
                                        Track <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('track') is-invalid @enderror"
                                            id="track"
                                            name="track"
                                            required>
                                        <option value="">Select Track</option>
                                        @foreach($tracks as $track)
                                            <option value="{{ $track }}" {{ old('track') === $track ? 'selected' : '' }}>
                                                {{ $track }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('track')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Academic track/strand</div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Teacher Assignment -->
                                <div class="col-md-8 mb-3">
                                    <label for="teacher_id" class="form-label fw-semibold">
                                        Assigned Teacher
                                    </label>
                                    <select class="form-select @error('teacher_id') is-invalid @enderror"
                                            id="teacher_id"
                                            name="teacher_id">
                                        <option value="">No Teacher Assigned</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Teacher who will handle this offering</div>
                                </div>

                                <!-- Max Students -->
                                <div class="col-md-4 mb-3">
                                    <label for="max_students" class="form-label fw-semibold">
                                        Max Students <span class="text-danger">*</span>
                                    </label>
                                    <input type="number"
                                           class="form-control @error('max_students') is-invalid @enderror"
                                           id="max_students"
                                           name="max_students"
                                           value="{{ old('max_students', 40) }}"
                                           min="1"
                                           max="100"
                                           required>
                                    @error('max_students')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Class capacity</div>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Section -->
                        <div class="form-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-clock me-2 text-warning"></i>
                                Class Schedule
                            </h6>
                            <div id="schedules-container">
                                <div class="schedule-item border rounded p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-semibold">Day of Week <span class="text-danger">*</span></label>
                                            <select class="form-select" name="schedules[0][day_of_week]" required>
                                                <option value="">Select Day</option>
                                                <option value="Monday">Monday</option>
                                                <option value="Tuesday">Tuesday</option>
                                                <option value="Wednesday">Wednesday</option>
                                                <option value="Thursday">Thursday</option>
                                                <option value="Friday">Friday</option>
                                                <option value="Saturday">Saturday</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-semibold">Start Time <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control" name="schedules[0][start_time]" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-semibold">End Time <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control" name="schedules[0][end_time]" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-schedule">
                                <i class="fas fa-plus me-1"></i>Add Another Schedule
                            </button>
                        </div>

                        <!-- Notes Section -->
                        <div class="form-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-sticky-note me-2 text-info"></i>
                                Additional Notes (Optional)
                            </h6>
                            <div class="mb-3">
                                <label for="notes" class="form-label fw-semibold">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes"
                                          name="notes"
                                          rows="3"
                                          placeholder="Any additional information about this offering...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('registrar.subject-offerings.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Offering
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let scheduleIndex = 1;

    // Add new schedule
    $('#add-schedule').on('click', function() {
        const scheduleHtml = `
            <div class="schedule-item border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Schedule ${scheduleIndex + 1}</h6>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-schedule">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Day of Week <span class="text-danger">*</span></label>
                        <select class="form-select" name="schedules[${scheduleIndex}][day_of_week]" required>
                            <option value="">Select Day</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Start Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="schedules[${scheduleIndex}][start_time]" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">End Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="schedules[${scheduleIndex}][end_time]" required>
                    </div>
                </div>
            </div>
        `;

        $('#schedules-container').append(scheduleHtml);
        scheduleIndex++;
    });

    // Remove schedule
    $(document).on('click', '.remove-schedule', function() {
        $(this).closest('.schedule-item').remove();
    });

    // Auto-populate fields based on selected subject
    $('#subject_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            const gradeLevel = selectedOption.data('grade');
            const semester = selectedOption.data('semester');

            if (gradeLevel) {
                $('#grade_level').val(gradeLevel);
            }
            // Map semester to grading
            if (semester && semester !== 'Both Semesters') {
                if (semester === '1st Semester') {
                    $('#grading').val('First Grading');
                } else if (semester === '2nd Semester') {
                    $('#grading').val('Second Grading');
                }
            }
        }
    });
});
</script>

<style>
/* Form Section Styling */
.form-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    border-left: 4px solid #2563eb;
}

.section-title {
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.schedule-item {
    background: white;
    border: 1px solid #e5e7eb !important;
}

.form-label {
    color: #374151;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 0.75rem;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.card {
    border: none;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border-radius: 12px;
}
</style>
@endpush
