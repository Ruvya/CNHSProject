@extends('layouts.registrar')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-gray-800">
                        <i class="fas fa-users me-2"></i>Bulk Subject Assignment
                    </h1>
                    <p class="text-muted">Assign subjects to multiple students at once</p>
                </div>
                <div>
                    <a href="{{ route('registrar.student-subject-assignments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('registrar.student-subject-assignments.bulk-store') }}" method="POST">
        @csrf

        <!-- Filter Section -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-filter me-1"></i>Step 1: Filter Students and Subjects
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label for="filter_grade_level" class="form-label">Grade Level</label>
                        <select name="filter_grade_level" id="filter_grade_level" class="form-select">
                            <option value="">All Grades</option>
                            @foreach($gradeLevels as $grade)
                                <option value="{{ $grade }}" {{ request('grade_level') == $grade ? 'selected' : '' }}>
                                    {{ $grade }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_track" class="form-label">Track</label>
                        <select name="filter_track" id="filter_track" class="form-select">
                            <option value="">All Tracks</option>
                            @foreach($tracks as $track)
                                <option value="{{ $track }}" {{ request('track') == $track ? 'selected' : '' }}>
                                    {{ $track }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_strand" class="form-label">Strand</label>
                        <select name="filter_strand" id="filter_strand" class="form-select">
                            <option value="">All Strands</option>
                            @foreach($strands as $strand)
                                <option value="{{ $strand }}" {{ request('strand') == $strand ? 'selected' : '' }}>
                                    {{ $strand }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_section" class="form-label">Section</label>
                        <select name="filter_section" id="filter_section" class="form-select">
                            <option value="">All Sections</option>
                            @foreach($sections as $section)
                                <option value="{{ $section }}" {{ request('section') == $section ? 'selected' : '' }}>
                                    {{ $section }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary" onclick="applyFilters()">
                            <i class="fas fa-filter me-1"></i>Apply Filters
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i>Clear Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignment Details -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-cog me-1"></i>Step 2: Assignment Details
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="school_year" class="form-label">School Year <span class="text-danger">*</span></label>
                        <select name="school_year" id="school_year" class="form-select @error('school_year') is-invalid @enderror" required>
                            <option value="">Select School Year</option>
                            <option value="2024-2025" {{ old('school_year', '2024-2025') == '2024-2025' ? 'selected' : '' }}>2024-2025</option>
                            <option value="2025-2026" {{ old('school_year') == '2025-2026' ? 'selected' : '' }}>2025-2026</option>
                        </select>
                        @error('school_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" 
                                  rows="3" placeholder="Additional notes about this bulk assignment...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Selection -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-users me-1"></i>Step 3: Select Students ({{ $students->count() }})
                </h6>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="selectAllStudents()">Select All</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllStudents()">Deselect All</button>
                </div>
            </div>
            <div class="card-body">
                @error('students')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                @if($students->count() > 0)
                    <div class="row" id="students-container">
                        @foreach($students as $student)
                            <div class="col-md-6 mb-3">
                                <div class="card border-light h-100">
                                    <div class="card-body py-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="students[]" 
                                                   value="{{ $student->id }}" id="student_{{ $student->id }}"
                                                   {{ in_array($student->id, old('students', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="student_{{ $student->id }}">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">{{ $student->first_name }} {{ $student->last_name }}</h6>
                                                        <small class="text-muted">
                                                            ID: {{ $student->student_id }} | {{ $student->grade_level }}
                                                            <br>{{ $student->track }} - {{ $student->strand }}
                                                            @if($student->section)
                                                                | Section: {{ $student->section }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                    @if($student->subjects->count() > 0)
                                                        <span class="badge bg-info">{{ $student->subjects->count() }} subjects</span>
                                                    @else
                                                        <span class="badge bg-warning">No subjects</span>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Students Found</h5>
                        <p class="text-muted">Try adjusting your filters to find students.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Subjects Selection -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-book me-1"></i>Step 4: Select Subjects ({{ $subjects->count() }})
                </h6>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="selectAllSubjects()">Select All</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllSubjects()">Deselect All</button>
                </div>
            </div>
            <div class="card-body">
                @error('subjects')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                @if($subjects->count() > 0)
                    <div class="row" id="subjects-container">
                        @foreach($subjects as $subject)
                            <div class="col-md-6 mb-3">
                                <div class="card border-light h-100">
                                    <div class="card-body py-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subjects[]" 
                                                   value="{{ $subject->id }}" id="subject_{{ $subject->id }}"
                                                   {{ in_array($subject->id, old('subjects', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="subject_{{ $subject->id }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1">{{ $subject->code }} - {{ $subject->name }}</h6>
                                                        <small class="text-muted">
                                                            {{ $subject->grade_level }} | {{ $subject->track }} - {{ $subject->strand }}
                                                            @if($subject->teacher)
                                                                <br>Teacher: {{ $subject->teacher->name }}
                                                            @else
                                                                <br><span class="text-warning">No teacher assigned</span>
                                                            @endif
                                                        </small>
                                                    </div>
                                                    <span class="badge bg-secondary">{{ $subject->students->count() }} students</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Subjects Found</h5>
                        <p class="text-muted">Try adjusting your filters to find subjects.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('registrar.student-subject-assignments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>Assign Subjects to Selected Students
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function selectAllStudents() {
    const checkboxes = document.querySelectorAll('input[name="students[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = true);
}

function deselectAllStudents() {
    const checkboxes = document.querySelectorAll('input[name="students[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = false);
}

function selectAllSubjects() {
    const checkboxes = document.querySelectorAll('input[name="subjects[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = true);
}

function deselectAllSubjects() {
    const checkboxes = document.querySelectorAll('input[name="subjects[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = false);
}

function applyFilters() {
    const gradeLevel = document.getElementById('filter_grade_level').value;
    const track = document.getElementById('filter_track').value;
    const strand = document.getElementById('filter_strand').value;
    const section = document.getElementById('filter_section').value;

    const params = new URLSearchParams();
    if (gradeLevel) params.append('grade_level', gradeLevel);
    if (track) params.append('track', track);
    if (strand) params.append('strand', strand);
    if (section) params.append('section', section);

    window.location.href = '{{ route("registrar.student-subject-assignments.bulk-create") }}?' + params.toString();
}

function clearFilters() {
    window.location.href = '{{ route("registrar.student-subject-assignments.bulk-create") }}';
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const checkedStudents = document.querySelectorAll('input[name="students[]"]:checked');
    const checkedSubjects = document.querySelectorAll('input[name="subjects[]"]:checked');
    
    if (checkedStudents.length === 0) {
        e.preventDefault();
        alert('Please select at least one student.');
        return false;
    }
    
    if (checkedSubjects.length === 0) {
        e.preventDefault();
        alert('Please select at least one subject.');
        return false;
    }
    
    const confirmMessage = `Are you sure you want to assign ${checkedSubjects.length} subject(s) to ${checkedStudents.length} student(s)?`;
    if (!confirm(confirmMessage)) {
        e.preventDefault();
        return false;
    }
});
</script>
@endsection
