@extends('layouts.registrar')

@section('title', 'Assign Student - Academic Based')

@section('content')
<div style="width: 100%; max-width: 800px; margin: 0 auto; padding: 1rem;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Assign Student - Academic Based</h4>
            <small>Assign students based on academic information with automatic subject package loading</small>
        </div>

        <div class="card-body">
            <form action="{{ route('registrar.student-assignments.store') }}" method="POST" id="assignmentForm">
                @csrf

                <!-- Academic Information Section -->
                <div class="mb-4">
                    <h5 class="text-primary mb-3">Academic Information</h5>

                    <!-- Student Selection -->
                    <div class="mb-3">
                        <label for="student_id" class="form-label fw-bold">Select Student <span class="text-danger">*</span></label>
                        <select name="student_id" id="student_id" class="form-select" required>
                            <option value="">Choose a student...</option>
                            @if(isset($students) && $students->count() > 0)
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}"
                                            data-grade="{{ $student->grade_level ?? '' }}"
                                            data-track="{{ $student->track ?? '' }}"
                                            data-strand="{{ $student->strand ?? '' }}">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                        ({{ $student->student_id }}) - {{ $student->grade_level ?? 'N/A' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Academic Year -->
                    <div class="mb-3">
                        <label for="school_year" class="form-label fw-bold">Academic Year <span class="text-danger">*</span></label>
                        <input type="text" name="school_year" id="school_year" class="form-control"
                               value="{{ $currentSchoolYear ?? '2024-2025' }}" required>
                    </div>

                    <!-- Grading Period -->
                    <div class="mb-3">
                        <label for="grading_period" class="form-label fw-bold">Grading Period <span class="text-danger">*</span></label>
                        <select name="grading_period" id="grading_period" class="form-select" required>
                            @if(isset($gradingPeriods))
                                @foreach($gradingPeriods as $period)
                                    <option value="{{ $period }}" {{ ($currentGradingPeriod ?? 'First Grading') == $period ? 'selected' : '' }}>
                                        {{ $period }}
                                    </option>
                                @endforeach
                            @else
                                <option value="First Grading" selected>First Grading</option>
                                <option value="Second Grading">Second Grading</option>
                                <option value="Third Grading">Third Grading</option>
                                <option value="Fourth Grading">Fourth Grading</option>
                            @endif
                        </select>
                    </div>

                    <!-- Grade Level -->
                    <div class="mb-3">
                        <label for="grade_level" class="form-label fw-bold">Grade Level <span class="text-danger">*</span></label>
                        <select name="grade_level" id="grade_level" class="form-select" required>
                            <option value="">Select Grade Level...</option>
                            @if(isset($gradeLevels))
                                @foreach($gradeLevels as $level)
                                    <option value="{{ $level }}">{{ $level }}</option>
                                @endforeach
                            @else
                                <option value="Grade 11">Grade 11</option>
                                <option value="Grade 12">Grade 12</option>
                            @endif
                        </select>
                    </div>

                    <!-- Track -->
                    <div class="mb-3">
                        <label for="track" class="form-label fw-bold">Track <span class="text-danger">*</span></label>
                        <select name="track" id="track" class="form-select" required>
                            <option value="">Select Track...</option>
                            @if(isset($tracks))
                                @foreach($tracks as $track)
                                    <option value="{{ $track }}">{{ $track }}</option>
                                @endforeach
                            @else
                                <option value="Academic Track">Academic Track</option>
                                <option value="TVL">TVL</option>
                            @endif
                        </select>
                    </div>

                    <!-- Strand -->
                    <div class="mb-3">
                        <label for="strand" class="form-label fw-bold">Strand <span class="text-danger">*</span></label>
                        <select name="strand" id="strand" class="form-select" required>
                            <option value="">Select Strand...</option>
                        </select>
                    </div>

                    <!-- Load Button -->
                    <div class="mb-3">
                        <button type="button" id="loadSubjectsBtn" class="btn btn-info w-100" disabled>
                            <i class="fas fa-download me-2"></i>Load Subject Package
                        </button>
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label for="notes" class="form-label fw-bold">Notes (Optional)</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3"
                                  placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Subject Package Section -->
                <div class="mb-4">
                    <h5 class="text-primary mb-3">Subject Package</h5>

                    <div id="subjectPackageContainer" style="display: none;">
                        <!-- Core Subjects -->
                        <div class="mb-3">
                            <h6 class="text-success"><i class="fas fa-star me-1"></i>Core Subjects</h6>
                            <div id="coreSubjects" class="border rounded p-3 bg-light" style="min-height: 150px;">
                                <p class="text-muted mb-0">Core subjects will appear here...</p>
                            </div>
                        </div>

                        <!-- Applied Subjects -->
                        <div class="mb-3">
                            <h6 class="text-info"><i class="fas fa-cogs me-1"></i>Applied Subjects</h6>
                            <div id="appliedSubjects" class="border rounded p-3 bg-light" style="min-height: 150px;">
                                <p class="text-muted mb-0">Applied subjects will appear here...</p>
                            </div>
                        </div>

                        <!-- Specialized Subjects -->
                        <div class="mb-3">
                            <h6 class="text-warning"><i class="fas fa-graduation-cap me-1"></i>Specialized Subjects</h6>
                            <div id="specializedSubjects" class="border rounded p-3 bg-light" style="min-height: 150px;">
                                <p class="text-muted mb-0">Specialized subjects will appear here...</p>
                            </div>
                        </div>

                        <!-- Subject Summary -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-1"></i>Subject Summary:</h6>
                            <p class="mb-1">Core: <span id="coreCount" class="fw-bold">0</span> subjects</p>
                            <p class="mb-1">Applied: <span id="appliedCount" class="fw-bold">0</span> subjects</p>
                            <p class="mb-1">Specialized: <span id="specializedCount" class="fw-bold">0</span> subjects</p>
                            <p class="mb-0"><strong>Total: <span id="totalCount" class="text-primary">0</span> subjects</strong></p>
                        </div>
                    </div>

                    <div id="noSubjectsMessage" class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        Please select academic information and click "Load Subject Package" to view available subjects.
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('registrar.student-assignments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Assignments
                        </a>
                        <button type="submit" id="saveBtn" class="btn btn-success" disabled>
                            <i class="fas fa-save me-2"></i>Save Assignment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>




@push('styles')
<style>
/* Simple centered layout that avoids sidebar overlap */
.main-content {
    padding: 1rem !important;
}

/* Ensure the form container is properly centered and sized */
div[style*="max-width: 800px"] {
    max-width: 700px !important;
}

/* Form styling */
.form-select, .form-control, textarea {
    width: 100%;
    box-sizing: border-box;
}

/* Subject package containers */
#subjectPackageContainer .border {
    min-height: 120px;
    padding: 1rem;
    margin-bottom: 1rem;
}

/* Responsive adjustments */
@media (max-width: 991px) {
    div[style*="max-width: 800px"] {
        max-width: 100% !important;
        padding: 0.5rem !important;
    }
}

@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Define strands data
    const strands = @json($strands) || {
        'Academic Track': ['STEM', 'ABM', 'HUMSS', 'GAS'],
        'TVL': ['ICT', 'Home Economics', 'Agri-Fishery Arts', 'Industrial Arts']
    };

    const trackSelect = document.getElementById('track');
    const strandSelect = document.getElementById('strand');
    const loadSubjectsBtn = document.getElementById('loadSubjectsBtn');
    const subjectPackageContainer = document.getElementById('subjectPackageContainer');
    const noSubjectsMessage = document.getElementById('noSubjectsMessage');
    const saveBtn = document.getElementById('saveBtn');

    let selectedSubjects = [];

    // Handle track selection to populate strands
    if (trackSelect && strandSelect) {
        trackSelect.addEventListener('change', function() {
            const selectedTrack = this.value;
            strandSelect.innerHTML = '<option value="">Select Strand...</option>';

            if (selectedTrack && strands[selectedTrack]) {
                strands[selectedTrack].forEach(strand => {
                    const option = document.createElement('option');
                    option.value = strand;
                    option.textContent = strand;
                    strandSelect.appendChild(option);
                });
            }

            checkFormCompletion();
        });
    }

    // Handle form field changes
    ['grade_level', 'track', 'strand', 'grading_period'].forEach(fieldId => {
        const element = document.getElementById(fieldId);
        if (element) {
            element.addEventListener('change', checkFormCompletion);
        }
    });

    function checkFormCompletion() {
        const gradeLevel = document.getElementById('grade_level')?.value || '';
        const track = document.getElementById('track')?.value || '';
        const strand = document.getElementById('strand')?.value || '';
        const gradingPeriod = document.getElementById('grading_period')?.value || '';

        if (gradeLevel && track && strand && gradingPeriod && loadSubjectsBtn) {
            loadSubjectsBtn.disabled = false;
        } else {
            if (loadSubjectsBtn) loadSubjectsBtn.disabled = true;
            hideSubjectPackage();
        }
    }

    // Load subject packages
    if (loadSubjectsBtn) {
        loadSubjectsBtn.addEventListener('click', function() {
            const gradeLevel = document.getElementById('grade_level')?.value || '';
            const track = document.getElementById('track')?.value || '';
            const strand = document.getElementById('strand')?.value || '';
            const gradingPeriod = document.getElementById('grading_period')?.value || '';

            if (!gradeLevel || !track || !strand || !gradingPeriod) {
                alert('Please fill in all required fields first.');
                return;
            }

            // Show loading state
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                alert('CSRF token not found. Please refresh the page.');
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-download me-2"></i>Load Subject Package';
                return;
            }

            fetch('{{ route("registrar.student-assignments.get-subject-packages") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    grade_level: gradeLevel,
                    track: track,
                    strand: strand,
                    grading_period: gradingPeriod
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    displaySubjectPackages(data.packages);
                    showSubjectPackage();
                } else {
                    showError('Error loading subject packages. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Network error. Please check your connection and try again.');
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-download me-2"></i>Load Subject Package';
            });
        });
    }

    function displaySubjectPackages(packages) {
        selectedSubjects = [];

        // Display core subjects
        displaySubjectCategory('coreSubjects', packages.core, 'core');
        document.getElementById('coreCount').textContent = packages.core.length;

        // Display applied subjects
        displaySubjectCategory('appliedSubjects', packages.applied, 'applied');
        document.getElementById('appliedCount').textContent = packages.applied.length;

        // Display specialized subjects
        displaySubjectCategory('specializedSubjects', packages.specialized, 'specialized');
        document.getElementById('specializedCount').textContent = packages.specialized.length;

        // Update total count
        const totalCount = packages.core.length + packages.applied.length + packages.specialized.length;
        document.getElementById('totalCount').textContent = totalCount;

        // Add all subjects to selected list by default
        [...packages.core, ...packages.applied, ...packages.specialized].forEach(subject => {
            selectedSubjects.push(subject.id);
        });

        updateSaveButton();
    }

    function displaySubjectCategory(containerId, subjects, category) {
        const container = document.getElementById(containerId);

        if (subjects.length === 0) {
            container.innerHTML = '<p class="text-muted mb-0">No subjects available</p>';
            return;
        }

        let html = '';
        subjects.forEach(subject => {
            html += `
                <div class="form-check mb-2">
                    <input class="form-check-input subject-checkbox" type="checkbox"
                           value="${subject.id}" id="subject_${subject.id}"
                           data-category="${category}" checked>
                    <label class="form-check-label" for="subject_${subject.id}">
                        <strong>${subject.name}</strong>
                        <br><small class="text-muted">${subject.code}</small>
                        ${subject.description ? '<br><small>' + subject.description + '</small>' : ''}
                    </label>
                </div>
            `;
        });

        container.innerHTML = html;

        // Add event listeners to checkboxes
        container.querySelectorAll('.subject-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    if (!selectedSubjects.includes(parseInt(this.value))) {
                        selectedSubjects.push(parseInt(this.value));
                    }
                } else {
                    selectedSubjects = selectedSubjects.filter(id => id !== parseInt(this.value));
                }
                updateSaveButton();
            });
        });
    }

    function showError(message) {
        // Create a temporary error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
        errorDiv.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Insert after the load button
        const loadBtn = document.getElementById('loadSubjectsBtn');
        loadBtn.parentNode.insertBefore(errorDiv, loadBtn.nextSibling);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 5000);
    }

    function showSubjectPackage() {
        subjectPackageContainer.style.display = 'block';
        noSubjectsMessage.style.display = 'none';
    }

    function hideSubjectPackage() {
        subjectPackageContainer.style.display = 'none';
        noSubjectsMessage.style.display = 'block';
        selectedSubjects = [];
        updateSaveButton();
    }

    function updateSaveButton() {
        if (selectedSubjects.length > 0) {
            saveBtn.disabled = false;

            // Add hidden inputs for selected subjects
            const existingInputs = document.querySelectorAll('input[name="subjects[]"]');
            existingInputs.forEach(input => input.remove());

            selectedSubjects.forEach(subjectId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'subjects[]';
                input.value = subjectId;
                document.getElementById('assignmentForm').appendChild(input);
            });
        } else {
            saveBtn.disabled = true;
        }
    }
});
</script>
@endpush
