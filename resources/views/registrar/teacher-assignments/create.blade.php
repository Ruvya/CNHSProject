@extends('layouts.registrar')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-user-plus me-2 text-primary"></i>
                Assign Teacher to Subject & Section
            </h1>
            <p class="text-muted mb-0">Assign a teacher to handle a specific subject for a section in {{ $currentSchoolYear }} - {{ $currentGradingPeriod }}</p>
        </div>
        <div>
            <a href="{{ route('registrar.teacher-assignments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Assignments
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Assignment Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-form me-2"></i>Assignment Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('registrar.teacher-assignments.store') }}">
                        @csrf

                        <!-- Teacher Selection -->
                        <div class="mb-4">
                            <label for="teacher_id" class="form-label">
                                <i class="fas fa-user me-1"></i>Select Teacher <span class="text-danger">*</span>
                            </label>
                            <select name="teacher_id" id="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                                <option value="">Choose a teacher...</option>
                                @foreach($teachers as $teacherOption)
                                    <option value="{{ $teacherOption->id }}"
                                            {{ (old('teacher_id', $teacher?->id) == $teacherOption->id) ? 'selected' : '' }}
                                            data-name="{{ $teacherOption->name }}"
                                            data-email="{{ $teacherOption->email }}"
                                            data-subject="{{ $teacherOption->subject }}"
                                            data-strand="{{ $teacherOption->strand }}">
                                        {{ $teacherOption->name }} - {{ $teacherOption->subject }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subject Selection -->
                        <div class="mb-4">
                            <label for="subject_id" class="form-label">
                                <i class="fas fa-book me-1"></i>Select Subject <span class="text-danger">*</span>
                            </label>
                            <select name="subject_id" id="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                                <option value="">Choose a subject...</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}"
                                            {{ old('subject_id') == $subject->id ? 'selected' : '' }}
                                            data-name="{{ $subject->name }}"
                                            data-code="{{ $subject->code }}"
                                            data-grade="{{ $subject->grade_level }}"
                                            data-track="{{ $subject->track }}"
                                            data-strand="{{ $subject->strand }}">
                                        {{ $subject->code }} - {{ $subject->name }} ({{ $subject->grade_level }})
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="qualificationAlert" class="alert alert-warning mt-2" style="display: none;">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <span id="qualificationMessage"></span>
                            </div>
                        </div>



                        <!-- Academic Year and Semester -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="school_year" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>School Year <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="school_year" id="school_year"
                                       class="form-control @error('school_year') is-invalid @enderror"
                                       value="{{ old('school_year', $currentSchoolYear) }}" readonly>
                                @error('school_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="grading_period" class="form-label">
                                    <i class="fas fa-calendar-alt me-1"></i>Grading Period <span class="text-danger">*</span>
                                </label>
                                <select name="grading_period" id="grading_period" class="form-select @error('grading_period') is-invalid @enderror" required>
                                    <option value="First Grading" {{ old('grading_period', $currentGradingPeriod) == 'First Grading' ? 'selected' : '' }}>First Grading</option>
                                    <option value="Second Grading" {{ old('grading_period', $currentGradingPeriod) == 'Second Grading' ? 'selected' : '' }}>Second Grading</option>
                                    <option value="Third Grading" {{ old('grading_period', $currentGradingPeriod) == 'Third Grading' ? 'selected' : '' }}>Third Grading</option>
                                    <option value="Fourth Grading" {{ old('grading_period', $currentGradingPeriod) == 'Fourth Grading' ? 'selected' : '' }}>Fourth Grading</option>
                                </select>
                                @error('grading_period')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Schedule Section -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-clock me-1"></i>Teaching Schedule (Optional)
                            </label>
                            <div id="scheduleContainer">
                                <div class="schedule-item border rounded p-3 mb-2">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Day</label>
                                            <select name="schedule[0][day]" class="form-select">
                                                <option value="">Select Day</option>
                                                <option value="Monday">Monday</option>
                                                <option value="Tuesday">Tuesday</option>
                                                <option value="Wednesday">Wednesday</option>
                                                <option value="Thursday">Thursday</option>
                                                <option value="Friday">Friday</option>
                                                <option value="Saturday">Saturday</option>
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
                                        <div class="col-md-3">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-outline-danger d-block remove-schedule" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="addSchedule" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Add Another Schedule
                            </button>
                            <div id="scheduleConflictAlert" class="alert alert-danger mt-2" style="display: none;">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <span id="scheduleConflictMessage"></span>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Notes (Optional)
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="Add any additional notes about this assignment...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('registrar.teacher-assignments.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Assign Teacher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Assignment Guidelines -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 text-info">
                        <i class="fas fa-lightbulb me-2"></i>Assignment Guidelines
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-check-circle me-1"></i>Assignment Rules:</h6>
                        <ul class="mb-0">
                            <li>Teachers can be assigned to <strong>multiple subjects</strong></li>
                            <li>No schedule conflicts allowed</li>
                            <li>Subject expertise matching recommended</li>
                            <li>Schedule validation is automatic</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-1"></i>Important Notes:</h6>
                        <ul class="mb-0">
                            <li>Check teacher's subject expertise</li>
                            <li>Verify no schedule conflicts exist</li>
                            <li>Schedule is optional but recommended</li>
                            <li>Assignment date is automatically recorded</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Selected Teacher Info -->
            <div class="card mt-3" id="teacherInfoCard" style="display: none;">
                <div class="card-header">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-user me-2"></i>Selected Teacher
                    </h5>
                </div>
                <div class="card-body" id="teacherInfo">
                    <!-- Teacher information will be populated here -->
                </div>
            </div>

            <!-- Selected Subject Info -->
            <div class="card mt-3" id="subjectInfoCard" style="display: none;">
                <div class="card-header">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-book me-2"></i>Selected Subject
                    </h5>
                </div>
                <div class="card-body" id="subjectInfo">
                    <!-- Subject information will be populated here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const teacherSelect = document.getElementById('teacher_id');
    const subjectSelect = document.getElementById('subject_id');
    const teacherInfoCard = document.getElementById('teacherInfoCard');
    const subjectInfoCard = document.getElementById('subjectInfoCard');
    const teacherInfo = document.getElementById('teacherInfo');
    const subjectInfo = document.getElementById('subjectInfo');
    const qualificationAlert = document.getElementById('qualificationAlert');
    const qualificationMessage = document.getElementById('qualificationMessage');

    let scheduleIndex = 1;

    // Handle teacher selection
    teacherSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (this.value) {
            const name = selectedOption.dataset.name;
            const email = selectedOption.dataset.email;
            const subject = selectedOption.dataset.subject;
            const strand = selectedOption.dataset.strand;

            teacherInfo.innerHTML = `
                <p><strong>Name:</strong> ${name}</p>
                <p><strong>Email:</strong> ${email}</p>
                <p><strong>Subject Expertise:</strong> ${subject || 'N/A'}</p>
                <p><strong>Strand:</strong> ${strand || 'N/A'}</p>
            `;
            teacherInfoCard.style.display = 'block';

            checkQualification();
        } else {
            teacherInfoCard.style.display = 'none';
            qualificationAlert.style.display = 'none';
        }
    });

    // Handle subject selection
    subjectSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (this.value) {
            const name = selectedOption.dataset.name;
            const code = selectedOption.dataset.code;
            const grade = selectedOption.dataset.grade;
            const track = selectedOption.dataset.track;
            const strand = selectedOption.dataset.strand;

            subjectInfo.innerHTML = `
                <p><strong>Subject:</strong> ${name}</p>
                <p><strong>Code:</strong> ${code}</p>
                <p><strong>Grade Level:</strong> ${grade}</p>
                <p><strong>Track:</strong> ${track}</p>
                <p><strong>Strand:</strong> ${strand || 'N/A'}</p>
            `;
            subjectInfoCard.style.display = 'block';

            checkQualification();
        } else {
            subjectInfoCard.style.display = 'none';
            qualificationAlert.style.display = 'none';
        }
    });

    function checkQualification() {
        if (teacherSelect.value && subjectSelect.value) {
            // This would typically make an AJAX call to check qualification
            // For now, we'll do a simple client-side check
            const teacherSubject = teacherSelect.options[teacherSelect.selectedIndex].dataset.subject;
            const subjectName = subjectSelect.options[subjectSelect.selectedIndex].dataset.name;

            if (teacherSubject && subjectName) {
                const isQualified = teacherSubject.toLowerCase().includes(subjectName.toLowerCase()) ||
                                  subjectName.toLowerCase().includes(teacherSubject.toLowerCase());

                if (!isQualified) {
                    qualificationMessage.textContent = 'Teacher may not be qualified for this subject. Please verify expertise.';
                    qualificationAlert.style.display = 'block';
                } else {
                    qualificationAlert.style.display = 'none';
                }
            }
        }
    }



    // Schedule management
    document.getElementById('addSchedule').addEventListener('click', function() {
        const container = document.getElementById('scheduleContainer');
        const newSchedule = document.createElement('div');
        newSchedule.className = 'schedule-item border rounded p-3 mb-2';
        newSchedule.innerHTML = `
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Day</label>
                    <select name="schedule[${scheduleIndex}][day]" class="form-select">
                        <option value="">Select Day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
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
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger d-block remove-schedule">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newSchedule);
        scheduleIndex++;

        // Show remove buttons for all schedule items
        document.querySelectorAll('.remove-schedule').forEach(btn => {
            btn.style.display = 'block';
        });
    });

    // Handle schedule removal
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-schedule')) {
            e.target.closest('.schedule-item').remove();

            // Hide remove button if only one schedule item remains
            const scheduleItems = document.querySelectorAll('.schedule-item');
            if (scheduleItems.length === 1) {
                scheduleItems[0].querySelector('.remove-schedule').style.display = 'none';
            }
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
@endsection
