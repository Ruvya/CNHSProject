@extends('layouts.registrar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Subject: {{ $subject->name }}
                    </h5>
                    <a href="{{ route('registrar.subjects.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to Subjects
                    </a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Please correct the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('registrar.subjects.update', $subject) }}" method="POST" id="subjectForm">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information Section -->
                        <div class="form-section mb-4">
                            <h6 class="section-title border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle me-2 text-primary"></i>
                                Basic Information
                            </h6>
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        Subject Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $subject->name) }}"
                                           placeholder="e.g., General Mathematics"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="code" class="form-label fw-semibold">
                                        Subject Code <span class="text-warning">(Editable)</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('code') is-invalid @enderror"
                                           id="code"
                                           name="code"
                                           value="{{ old('code', $subject->code) }}"
                                           placeholder="e.g., GENMATH"
                                           style="text-transform: uppercase;"
                                           required>
                                    <div class="form-text text-warning">
                                        <i class="fas fa-edit me-1"></i>You can modify the subject code if needed
                                    </div>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label fw-semibold">
                                        Description
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description"
                                              name="description"
                                              rows="3"
                                              placeholder="Brief description of the subject content and objectives">{{ old('description', $subject->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- DepEd Curriculum Structure Section -->
                        <div class="form-section mb-4">
                            <h6 class="section-title border-bottom pb-2 mb-3">
                                <i class="fas fa-graduation-cap me-2 text-success"></i>
                                DepEd Curriculum Structure
                            </h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="grade_level" class="form-label fw-semibold">
                                        Grade Level <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('grade_level') is-invalid @enderror"
                                            id="grade_level"
                                            name="grade_level"
                                            required>
                                        <option value="">Select Grade Level</option>
                                        <option value="Grade 11" {{ old('grade_level', $subject->grade_level) == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                        <option value="Grade 12" {{ old('grade_level', $subject->grade_level) == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                                    </select>
                                    @error('grade_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="track" class="form-label fw-semibold">
                                        Track <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('track') is-invalid @enderror"
                                            id="track"
                                            name="track"
                                            required>
                                        <option value="">Select Track</option>
                                        <option value="Academic Track" {{ old('track', $subject->track) == 'Academic Track' ? 'selected' : '' }}>Academic Track</option>
                                        <option value="TVL Track" {{ old('track', $subject->track) == 'TVL Track' ? 'selected' : '' }}>TVL Track</option>
                                        <option value="Sports Track" {{ old('track', $subject->track) == 'Sports Track' ? 'selected' : '' }}>Sports Track</option>
                                        <option value="Arts and Design Track" {{ old('track', $subject->track) == 'Arts and Design Track' ? 'selected' : '' }}>Arts and Design Track</option>
                                    </select>
                                    @error('track')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="cluster" class="form-label fw-semibold">
                                        Cluster
                                    </label>
                                    <select class="form-select @error('cluster') is-invalid @enderror"
                                            id="cluster"
                                            name="cluster">
                                        <option value="">Select Cluster (Optional)</option>
                                        <!-- Options will be populated by JavaScript based on track selection -->
                                    </select>
                                    @error('cluster')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="specialization" class="form-label fw-semibold">
                                        Specialization
                                    </label>
                                    <input type="text"
                                           class="form-control @error('specialization') is-invalid @enderror"
                                           id="specialization"
                                           name="specialization"
                                           value="{{ old('specialization', $subject->specialization) }}"
                                           placeholder="e.g., Computer Programming, Cookery">
                                    @error('specialization')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Academic Configuration Section -->
                        <div class="form-section mb-4">
                            <h6 class="section-title border-bottom pb-2 mb-3">
                                <i class="fas fa-calendar-alt me-2 text-info"></i>
                                Academic Configuration
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="grading" class="form-label fw-semibold">
                                        Grading Period <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('grading') is-invalid @enderror"
                                            id="grading"
                                            name="grading"
                                            required>
                                        <option value="">Select Grading Period</option>
                                        <option value="First Grading" {{ old('grading', $subject->grading) == 'First Grading' ? 'selected' : '' }}>First Grading</option>
                                        <option value="Second Grading" {{ old('grading', $subject->grading) == 'Second Grading' ? 'selected' : '' }}>Second Grading</option>
                                        <option value="Third Grading" {{ old('grading', $subject->grading) == 'Third Grading' ? 'selected' : '' }}>Third Grading</option>
                                        <option value="Fourth Grading" {{ old('grading', $subject->grading) == 'Fourth Grading' ? 'selected' : '' }}>Fourth Grading</option>
                                        <option value="All Gradings" {{ old('grading', $subject->grading) == 'All Gradings' ? 'selected' : '' }}>All Gradings</option>
                                    </select>
                                    @error('grading')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="teacher_id" class="form-label fw-semibold">
                                        Assigned Teacher
                                    </label>
                                    <select class="form-select @error('teacher_id') is-invalid @enderror"
                                            id="teacher_id"
                                            name="teacher_id">
                                        <option value="">Select Teacher (Optional)</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $subject->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">You can assign a teacher now or later</div>
                                </div>
                            </div>
                        </div>

                        <!-- Subject Classification Section -->
                        <div class="form-section mb-4">
                            <h6 class="section-title border-bottom pb-2 mb-3">
                                <i class="fas fa-tags me-2 text-warning"></i>
                                Subject Classification
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="is_core_subject"
                                               name="is_core_subject"
                                               value="1"
                                               {{ old('is_core_subject', $subject->is_core_subject) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_core_subject">
                                            Core Subject
                                        </label>
                                        <div class="form-text">Check if this is a core/required subject</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="is_master_subject"
                                               name="is_master_subject"
                                               value="1"
                                               {{ old('is_master_subject', $subject->is_master_subject) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_master_subject">
                                            Elective Subject
                                        </label>
                                        <div class="form-text">Check if this is a master subject template</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('registrar.subjects.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-1"></i>Update Subject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0.5rem;
    border-left: 4px solid #ffc107;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 1rem;
}

.form-label.fw-semibold {
    color: #495057;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
}

.btn-warning {
    background: linear-gradient(45deg, #ffc107, #e0a800);
    border: none;
    color: #212529;
}

.btn-warning:hover {
    background: linear-gradient(45deg, #e0a800, #d39e00);
    color: #212529;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // DepEd Curriculum Data Structure (same as create form)
    const curriculumData = {
        'Academic Track': {
            'STEM': {
                clusters: ['Mathematics and Science', 'Engineering', 'Medical and Health Sciences'],
                specializations: ['Pre-Engineering', 'Pre-Medicine', 'Computer Science', 'Applied Physics']
            },
            'ABM': {
                clusters: ['Business and Entrepreneurship', 'Accounting and Finance'],
                specializations: ['Business Management', 'Accounting', 'Marketing', 'Entrepreneurship']
            },
            'HUMSS': {
                clusters: ['Social Sciences', 'Humanities', 'Communication Arts'],
                specializations: ['Political Science', 'Psychology', 'Literature', 'Communication']
            },
            'GAS': {
                clusters: ['General Academic Strand'],
                specializations: ['General Academic Subjects']
            }
        },
        'TVL Track': {
            'ICT': {
                clusters: ['Computer Programming', 'Computer Systems Servicing', 'Animation'],
                specializations: ['Web Development', 'Mobile App Development', 'Network Administration']
            },
            'HE': {
                clusters: ['Cookery', 'Food and Beverage Services', 'Housekeeping'],
                specializations: ['Culinary Arts', 'Hotel Management', 'Tourism Services']
            },
            'IA': {
                clusters: ['Electrical Installation', 'Electronics', 'Welding'],
                specializations: ['Electrical Technology', 'Electronics Technology', 'Mechanical Technology']
            },
            'AFA': {
                clusters: ['Agri-Fishery Arts', 'Animal Production', 'Crop Production'],
                specializations: ['Agriculture', 'Fishery', 'Livestock Production']
            }
        },
        'Sports Track': {
            'Sports': {
                clusters: ['Sports Science', 'Physical Education'],
                specializations: ['Athletic Training', 'Sports Management', 'Physical Therapy']
            }
        },
        'Arts and Design Track': {
            'Arts and Design': {
                clusters: ['Visual Arts', 'Performing Arts', 'Media Arts'],
                specializations: ['Fine Arts', 'Music', 'Theater Arts', 'Digital Arts']
            }
        }
    };

    const trackSelect = document.getElementById('track');
    const clusterSelect = document.getElementById('cluster');
    const codeInput = document.getElementById('code');
    const coreSubjectCheckbox = document.getElementById('is_core_subject');
    const electiveSubjectCheckbox = document.getElementById('is_master_subject');
    const gradeLevelSelect = document.getElementById('grade_level');
    const gradingSelect = document.getElementById('grading');

    // Current values from the subject
    const currentTrack = '{{ old("track", $subject->track) }}';
    const currentCluster = '{{ old("cluster", $subject->cluster) }}';

    // Initialize form with current values
    function initializeForm() {
        if (currentTrack) {
            populateClusters(currentTrack);
            if (currentCluster) {
                clusterSelect.value = currentCluster;
            }
        }
    }

    // Populate clusters based on track
    function populateClusters(selectedTrack) {
        clusterSelect.innerHTML = '<option value="">Select Cluster (Optional)</option>';
        if (selectedTrack && curriculumData[selectedTrack]) {
            const clusters = curriculumData[selectedTrack].clusters; // Assuming clusters are directly under the track
            clusters.forEach(cluster => {
                const option = document.createElement('option');
                option.value = cluster;
                option.textContent = cluster;
                clusterSelect.appendChild(option);
            });
        }
    }

    // Event listeners
    trackSelect.addEventListener('change', function() {
        const selectedTrack = this.value;
        populateClusters(selectedTrack);
    });

    // Ensure code is always uppercase
    codeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Auto-map semester to grading period
    const semesterSelect = document.getElementById('semester');

    semesterSelect.addEventListener('change', function() {
        const semester = this.value;
        if (semester === '1st Semester') {
            gradingSelect.value = 'First Grading';
        } else if (semester === '2nd Semester') {
            gradingSelect.value = 'Second Grading';
        } else if (semester === 'Both Semesters') {
            gradingSelect.value = 'All Gradings';
        }
    });

    // Core Subject auto-assign logic
    coreSubjectCheckbox.addEventListener('change', function() {
        if (this.checked) {
            gradeLevelSelect.value = 'Grade 11';
            gradeLevelSelect.style.backgroundColor = '#e9ecef';
            gradeLevelSelect.style.pointerEvents = 'none';
            trackSelect.value = 'All';
            trackSelect.style.backgroundColor = '#e9ecef';
            trackSelect.style.pointerEvents = 'none';
            clusterSelect.value = 'All';
            clusterSelect.style.backgroundColor = '#e9ecef';
            clusterSelect.style.pointerEvents = 'none';
            gradingSelect.value = 'All Gradings';
            gradingSelect.style.backgroundColor = '#e9ecef';
            gradingSelect.style.pointerEvents = 'none';
            // Disable and uncheck Elective Subject
            electiveSubjectCheckbox.checked = false;
            electiveSubjectCheckbox.setAttribute('disabled', 'disabled');
        } else {
            gradeLevelSelect.style.backgroundColor = '';
            gradeLevelSelect.style.pointerEvents = '';
            trackSelect.style.backgroundColor = '';
            trackSelect.style.pointerEvents = '';
            clusterSelect.style.backgroundColor = '';
            clusterSelect.style.pointerEvents = '';
            gradingSelect.style.backgroundColor = '';
            gradingSelect.style.pointerEvents = '';
            // Enable Elective Subject
            electiveSubjectCheckbox.removeAttribute('disabled');
        }
    });

    // Elective Subject logic
    electiveSubjectCheckbox.addEventListener('change', function() {
        if (this.checked) {
            // Disable and uncheck Core Subject
            coreSubjectCheckbox.checked = false;
            coreSubjectCheckbox.setAttribute('disabled', 'disabled');
        } else {
            // Enable Core Subject
            coreSubjectCheckbox.removeAttribute('disabled');
        }
    });

    // On page load, if checked, apply logic
    if (coreSubjectCheckbox.checked) {
        gradeLevelSelect.value = 'Grade 11';
        gradeLevelSelect.style.backgroundColor = '#e9ecef';
        gradeLevelSelect.style.pointerEvents = 'none';
        trackSelect.value = 'All';
        trackSelect.style.backgroundColor = '#e9ecef';
        trackSelect.style.pointerEvents = 'none';
        clusterSelect.value = 'All';
        clusterSelect.style.backgroundColor = '#e9ecef';
        clusterSelect.style.pointerEvents = 'none';
        gradingSelect.value = 'All Gradings';
        gradingSelect.style.backgroundColor = '#e9ecef';
        gradingSelect.style.pointerEvents = 'none';
        // Disable and uncheck Elective Subject
        electiveSubjectCheckbox.checked = false;
        electiveSubjectCheckbox.setAttribute('disabled', 'disabled');
    } else if (electiveSubjectCheckbox.checked) {
        // Disable and uncheck Core Subject
        coreSubjectCheckbox.checked = false;
        coreSubjectCheckbox.setAttribute('disabled', 'disabled');
    } else {
        electiveSubjectCheckbox.removeAttribute('disabled');
        coreSubjectCheckbox.removeAttribute('disabled');
    }

    // Form validation
    document.getElementById('subjectForm').addEventListener('submit', function(e) {
        const requiredFields = ['name', 'code'];
        let isValid = true;

        // Always validate name and code
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Only validate grade_level, track, and grading if Core Subject is NOT checked
        if (!coreSubjectCheckbox.checked) {
            const conditionalFields = ['grade_level', 'track', 'grading'];
            conditionalFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
        } else {
            // If Core Subject is checked, ensure disabled fields have their values
            // and remove any invalid styling
            document.getElementById('grade_level').classList.remove('is-invalid');
            document.getElementById('track').classList.remove('is-invalid');
            document.getElementById('grading').classList.remove('is-invalid');
        }

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });

    // Initialize the form
    initializeForm();
});
</script>
@endpush
