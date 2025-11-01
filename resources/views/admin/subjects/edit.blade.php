@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Subject
                    </h5>
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-light btn-sm">
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

    <form action="{{ route('admin.subjects.update', $subject) }}" method="POST" id="subjectForm">
        @csrf
        @method('PUT')

                        <!-- Basic Information Section -->
                        <div class="form-section mb-4">
                            <h6 class="section-title border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle me-2 text-primary"></i>
                                Basic Information
                            </h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
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
                                        <option value="All" {{ old('track', $subject->track) == 'All' ? 'selected' : '' }}>All</option>
                                        @foreach($tracks as $track)
                                            <option value="{{ $track->name }}" {{ old('track', $subject->track) == $track->name ? 'selected' : '' }}>
                                                {{ $track->name }}
                                            </option>
                                        @endforeach
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
                                        <option value="All" {{ old('cluster', $subject->cluster) == 'All' ? 'selected' : '' }}>All</option>
                                        <!-- Options will be populated by JavaScript based on track selection -->
                                    </select>
                                    @error('cluster')
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
                                <div class="col-md-12 mb-3">
                                    <label for="semester" class="form-label fw-semibold">
                                        Semester <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('semester') is-invalid @enderror"
                                            id="semester"
                                            name="semester"
                                            required>
                                        <option value="">Select Semester</option>
                                        <option value="1st Semester" {{ old('semester', $subject->semester) == '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                                        <option value="2nd Semester" {{ old('semester', $subject->semester) == '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                                        <option value="Both Semesters" {{ old('semester', $subject->semester) == 'Both Semesters' ? 'selected' : '' }}>Both Semesters</option>
                                    </select>
                                    @error('semester')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
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
    border-left: 4px solid #007bff;
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

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #004085);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const trackSelect = document.getElementById('track');
    const clusterSelect = document.getElementById('cluster');
    const gradeLevelSelect = document.getElementById('grade_level');
    const coreSubjectCheckbox = document.getElementById('is_core_subject');
    const electiveSubjectCheckbox = document.getElementById('is_master_subject');
    const nameInput = document.getElementById('name');

    const currentCluster = '{{ old("cluster", $subject->cluster) }}';

    // Load clusters by track name
    async function loadClustersByTrackName(trackName) {
        if (!trackName || trackName === 'All') {
            clusterSelect.innerHTML = '<option value="">Select Cluster (Optional)</option>';
            if (trackName === 'All') {
                clusterSelect.innerHTML += '<option value="All"' + (currentCluster === 'All' ? ' selected' : '') + '>All</option>';
            }
            return;
        }

        const tracks = @json($tracks);
        const track = tracks.find(t => t.name === trackName);
        
        if (!track) {
            clusterSelect.innerHTML = '<option value="">No clusters available</option>';
            return;
        }

        await loadClustersByTrackId(track.id);
    }

    // Load clusters by track ID using AJAX
    async function loadClustersByTrackId(trackId) {
        if (!trackId) {
            clusterSelect.innerHTML = '<option value="">Select Cluster (Optional)</option>';
            return;
        }

        clusterSelect.innerHTML = '<option value="">Loading clusters...</option>';
        clusterSelect.disabled = true;

        try {
            const response = await fetch(`/admin/api/clusters/by-track/${trackId}`);
            const data = await response.json();

            clusterSelect.innerHTML = '<option value="">Select Cluster (Optional)</option>';
            
            if (data.success && data.clusters && data.clusters.length > 0) {
                data.clusters.forEach(cluster => {
                    const option = document.createElement('option');
                    option.value = cluster.name;
                    option.textContent = cluster.name;
                    if (cluster.description) {
                        option.textContent += ' - ' + cluster.description;
                    }
                    if (cluster.name === currentCluster) {
                        option.selected = true;
                    }
                    clusterSelect.appendChild(option);
                });
            } else {
                clusterSelect.innerHTML = '<option value="">No clusters available for this track</option>';
            }
        } catch (error) {
            console.error('Error loading clusters:', error);
            clusterSelect.innerHTML = '<option value="">Error loading clusters</option>';
        } finally {
            clusterSelect.disabled = false;
        }
    }

    // Initialize clusters on page load
    if (trackSelect.value) {
        loadClustersByTrackName(trackSelect.value);
    }

    // Update clusters when track changes
    trackSelect.addEventListener('change', function() {
        const selectedTrack = this.value;
        loadClustersByTrackName(selectedTrack);
    });


    // Core Subject auto-assign logic
    coreSubjectCheckbox.addEventListener('change', function() {
        if (this.checked) {
            // Auto-assign default values but keep fields enabled and editable
            if (!gradeLevelSelect.value) gradeLevelSelect.value = 'Grade 11';
            if (!trackSelect.value) trackSelect.value = 'All';
            if (!clusterSelect.value) clusterSelect.value = 'All';
            // Note: All fields remain enabled and editable
            // Note: Elective Subject remains enabled and clickable
        }
        // Fields remain fully functional regardless of Core Subject selection
    });

    // Elective Subject logic - now independent of Core Subject
    electiveSubjectCheckbox.addEventListener('change', function() {
        // Elective subjects can be selected independently
        // No longer disables Core Subject checkbox
    });

    // On page load, if checked, apply logic
    if (coreSubjectCheckbox.checked) {
        // Auto-assign default values but keep fields enabled and editable
        if (!gradeLevelSelect.value) gradeLevelSelect.value = 'Grade 11';
        if (!trackSelect.value) trackSelect.value = 'All';
        if (!clusterSelect.value) clusterSelect.value = 'All';
        // Note: All fields remain enabled and editable
        // Note: Elective Subject remains enabled and clickable
    }
    // Both checkboxes remain enabled and independent
    // All curriculum fields remain fully functional

    // Initialize cluster options based on current track selection
    if (trackSelect.value) {
        trackSelect.dispatchEvent(new Event('change'));
    }

    // Form validation
    document.getElementById('subjectForm').addEventListener('submit', function(e) {
        const requiredFields = ['name'];
        let isValid = true;

        // Always validate name
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Only validate grade_level, track, and semester if Core Subject is NOT checked
        if (!coreSubjectCheckbox.checked) {
            const conditionalFields = ['grade_level', 'track', 'semester'];
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
            document.getElementById('semester').classList.remove('is-invalid');
        }

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
});
</script>
@endpush