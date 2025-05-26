@extends('layouts.registrar')

@section('title', 'Edit Subject Offering')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-edit me-2 text-warning"></i>
                Edit Subject Offering
            </h1>
            <p class="text-muted mb-0">Update subject offering details and schedule</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('registrar.subject-offerings.show', $subjectOffering) }}" class="btn btn-outline-info">
                <i class="fas fa-eye me-2"></i>View Details
            </a>
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
                        <i class="fas fa-calendar-edit me-2 text-warning"></i>
                        Update Subject Offering
                    </h5>
                    <p class="text-muted mb-0 small">Modify the subject offering configuration and schedule</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('registrar.subject-offerings.update', $subjectOffering) }}" method="POST" id="offeringForm">
                        @csrf
                        @method('PUT')

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
                                                    {{ old('subject_id', $subjectOffering->subject_id) == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->code }} - {{ $subject->name }}
                                                ({{ $subject->grade_level }}, {{ $subject->semester }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Select the master subject to offer</div>
                                </div>
                            </div>
                        </div>

                        <!-- Offering Configuration Section -->
                        <div class="form-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-cogs me-2 text-success"></i>
                                Offering Configuration
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="school_year" class="form-label fw-semibold">
                                        School Year <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('school_year') is-invalid @enderror"
                                            id="school_year"
                                            name="school_year"
                                            required>
                                        <option value="">Select School Year</option>
                                        @foreach($schoolYears as $year)
                                            <option value="{{ $year }}" {{ old('school_year', $subjectOffering->school_year) == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('school_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="grading" class="form-label fw-semibold">
                                        Grading <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('grading') is-invalid @enderror"
                                            id="grading"
                                            name="grading"
                                            required>
                                        <option value="">Select Grading</option>
                                        @foreach($gradings as $grading)
                                            <option value="{{ $grading }}" {{ old('grading', $subjectOffering->grading) == $grading ? 'selected' : '' }}>
                                                {{ $grading }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('grading')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="grade_level" class="form-label fw-semibold">
                                        Grade Level <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('grade_level') is-invalid @enderror"
                                            id="grade_level"
                                            name="grade_level"
                                            required>
                                        <option value="">Select Grade Level</option>
                                        @foreach($gradeLevels as $grade)
                                            <option value="{{ $grade }}" {{ old('grade_level', $subjectOffering->grade_level) == $grade ? 'selected' : '' }}>
                                                {{ $grade }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('grade_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="track" class="form-label fw-semibold">
                                        Track <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('track') is-invalid @enderror"
                                            id="track"
                                            name="track"
                                            required>
                                        <option value="">Select Track</option>
                                        @foreach($tracks as $track)
                                            <option value="{{ $track }}" {{ old('track', $subjectOffering->track) == $track ? 'selected' : '' }}>
                                                {{ $track }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('track')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Select the academic track/strand</div>
                                </div>
                            </div>
                        </div>

                        <!-- Teacher Assignment Section -->
                        <div class="form-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-chalkboard-teacher me-2 text-info"></i>
                                Teacher Assignment
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="teacher_id" class="form-label fw-semibold">
                                        Assigned Teacher
                                    </label>
                                    <select class="form-select @error('teacher_id') is-invalid @enderror"
                                            id="teacher_id"
                                            name="teacher_id">
                                        <option value="">No Teacher Assigned</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $subjectOffering->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }} ({{ $teacher->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Select a teacher to assign to this offering</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="max_students" class="form-label fw-semibold">
                                        Maximum Students <span class="text-danger">*</span>
                                    </label>
                                    <input type="number"
                                           class="form-control @error('max_students') is-invalid @enderror"
                                           id="max_students"
                                           name="max_students"
                                           value="{{ old('max_students', $subjectOffering->max_students) }}"
                                           min="1"
                                           max="100"
                                           required>
                                    @error('max_students')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Maximum number of students for this offering</div>
                                </div>
                            </div>
                        </div>

                        <!-- Status and Notes Section -->
                        <div class="form-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-info-circle me-2 text-secondary"></i>
                                Status and Notes
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label fw-semibold">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status"
                                            name="status"
                                            required>
                                        <option value="active" {{ old('status', $subjectOffering->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $subjectOffering->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="full" {{ old('status', $subjectOffering->status) == 'full' ? 'selected' : '' }}>Full</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="notes" class="form-label fw-semibold">
                                        Notes
                                    </label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                              id="notes"
                                              name="notes"
                                              rows="3"
                                              placeholder="Additional notes about this offering">{{ old('notes', $subjectOffering->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional notes or special instructions</div>
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
                                @foreach($subjectOffering->schedules as $index => $schedule)
                                    <div class="schedule-item border rounded p-3 mb-3">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-semibold">Day of Week <span class="text-danger">*</span></label>
                                                <select class="form-select" name="schedules[{{ $index }}][day_of_week]" required>
                                                    <option value="">Select Day</option>
                                                    <option value="Monday" {{ $schedule->day_of_week == 'Monday' ? 'selected' : '' }}>Monday</option>
                                                    <option value="Tuesday" {{ $schedule->day_of_week == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                                    <option value="Wednesday" {{ $schedule->day_of_week == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                                    <option value="Thursday" {{ $schedule->day_of_week == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                                    <option value="Friday" {{ $schedule->day_of_week == 'Friday' ? 'selected' : '' }}>Friday</option>
                                                    <option value="Saturday" {{ $schedule->day_of_week == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label fw-semibold">Start Time <span class="text-danger">*</span></label>
                                                <input type="time" class="form-control" name="schedules[{{ $index }}][start_time]"
                                                       value="{{ $schedule->start_time ? date('H:i', strtotime($schedule->start_time)) : '' }}" required>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label fw-semibold">End Time <span class="text-danger">*</span></label>
                                                <input type="time" class="form-control" name="schedules[{{ $index }}][end_time]"
                                                       value="{{ $schedule->end_time ? date('H:i', strtotime($schedule->end_time)) : '' }}" required>
                                            </div>
                                            <div class="col-md-2 mb-3 d-flex align-items-end">
                                                @if($index > 0)
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-schedule">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-schedule">
                                <i class="fas fa-plus me-2"></i>Add Another Schedule
                            </button>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('registrar.subject-offerings.show', $subjectOffering) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Update Offering
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
    let scheduleIndex = {{ $subjectOffering->schedules->count() }};

    // Add new schedule
    $('#add-schedule').on('click', function() {
        const scheduleHtml = `
            <div class="schedule-item border rounded p-3 mb-3">
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
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Start Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="schedules[${scheduleIndex}][start_time]" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">End Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="schedules[${scheduleIndex}][end_time]" required>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-schedule">
                            <i class="fas fa-trash"></i>
                        </button>
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
@endpush
