@extends('layouts.registrar')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-gray-800">
                        <i class="fas fa-user-plus me-2"></i>Assign Subjects to Student
                    </h1>
                    <p class="text-muted">Select subjects to assign to {{ $student->first_name }} {{ $student->last_name }}</p>
                </div>
                <div>
                    <a href="{{ route('registrar.student-subject-assignments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Information Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-user me-1"></i>Student Information
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Student ID:</strong></td>
                            <td>{{ $student->student_id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Name:</strong></td>
                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $student->email }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Grade Level:</strong></td>
                            <td><span class="badge bg-primary">{{ $student->grade_level }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Track:</strong></td>
                            <td><span class="badge bg-info">{{ $student->track }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Strand:</strong></td>
                            <td><span class="badge bg-secondary">{{ $student->strand }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Currently Assigned Subjects -->
    @if($student->subjects->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-check-circle me-1"></i>Currently Assigned Subjects ({{ $student->subjects->count() }})
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($student->subjects as $subject)
                        <div class="col-md-6 mb-3">
                            <div class="card border-success">
                                <div class="card-body py-2">
                                    <h6 class="card-title mb-1">{{ $subject->code }} - {{ $subject->name }}</h6>
                                    <small class="text-muted">
                                        {{ $subject->track }} - {{ $subject->strand }}
                                        @if($subject->teacher)
                                            | Teacher: {{ $subject->teacher->name }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Subject Assignment Form -->
    <form action="{{ route('registrar.student-subject-assignments.store', $student->id) }}" method="POST">
        @csrf
        
        <!-- Assignment Details -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-cog me-1"></i>Assignment Details
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
                                  rows="3" placeholder="Additional notes about this assignment...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Subjects -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-book me-1"></i>Available Subjects
                </h6>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">Select All</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">Deselect All</button>
                </div>
            </div>
            <div class="card-body">
                @error('subjects')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <!-- Recommended Subjects (Based on Student's Track/Strand) -->
                @if($availableSubjects->count() > 0)
                    <h6 class="text-success mb-3">
                        <i class="fas fa-star me-1"></i>Recommended Subjects ({{ $availableSubjects->count() }})
                        <small class="text-muted">- Based on student's grade level, track, and strand</small>
                    </h6>
                    <div class="row mb-4">
                        @foreach($availableSubjects as $subject)
                            <div class="col-md-6 mb-3">
                                <div class="card border-success h-100">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subjects[]" 
                                                   value="{{ $subject->id }}" id="subject_{{ $subject->id }}"
                                                   {{ in_array($subject->id, $assignedSubjectIds) ? 'checked' : '' }}
                                                   {{ in_array($subject->id, old('subjects', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="subject_{{ $subject->id }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1">{{ $subject->code }} - {{ $subject->name }}</h6>
                                                        <small class="text-muted">
                                                            {{ $subject->track }} - {{ $subject->strand }}
                                                            @if($subject->teacher)
                                                                <br>Teacher: {{ $subject->teacher->name }}
                                                            @else
                                                                <br><span class="text-warning">No teacher assigned</span>
                                                            @endif
                                                            <br>Students: {{ $subject->students->count() }}
                                                        </small>
                                                    </div>
                                                    @if(in_array($subject->id, $assignedSubjectIds))
                                                        <span class="badge bg-success">Currently Assigned</span>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- All Other Subjects -->
                @php
                    $otherSubjects = $allSubjects->whereNotIn('id', $availableSubjects->pluck('id'));
                @endphp

                @if($otherSubjects->count() > 0)
                    <h6 class="text-info mb-3">
                        <i class="fas fa-list me-1"></i>All Other Subjects ({{ $otherSubjects->count() }})
                        <small class="text-muted">- Manual selection if needed</small>
                    </h6>
                    <div class="row">
                        @foreach($otherSubjects as $subject)
                            <div class="col-md-6 mb-3">
                                <div class="card border-light h-100">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subjects[]" 
                                                   value="{{ $subject->id }}" id="subject_{{ $subject->id }}"
                                                   {{ in_array($subject->id, $assignedSubjectIds) ? 'checked' : '' }}
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
                                                            <br>Students: {{ $subject->students->count() }}
                                                        </small>
                                                    </div>
                                                    @if(in_array($subject->id, $assignedSubjectIds))
                                                        <span class="badge bg-success">Currently Assigned</span>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($availableSubjects->count() == 0 && $otherSubjects->count() == 0)
                    <div class="text-center py-5">
                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Subjects Available</h5>
                        <p class="text-muted">There are no subjects available for assignment.</p>
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
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Assign Selected Subjects
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function selectAll() {
    const checkboxes = document.querySelectorAll('input[name="subjects[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = true);
}

function deselectAll() {
    const checkboxes = document.querySelectorAll('input[name="subjects[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = false);
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const checkedBoxes = document.querySelectorAll('input[name="subjects[]"]:checked');
    if (checkedBoxes.length === 0) {
        e.preventDefault();
        alert('Please select at least one subject to assign.');
        return false;
    }
});
</script>
@endsection
