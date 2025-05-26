@extends('layouts.teacher')

@section('title', 'Edit Grade - ' . $student->first_name . ' ' . $student->last_name)

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-gray-800">Edit Grade</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('teacher.subjects') }}">Subjects</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('teacher.subjects.grades', $subject) }}">{{ $subject->name }} Grades</a></li>
                            <li class="breadcrumb-item active">Edit Grade</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('teacher.subjects.grades', $subject) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Grades
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Student and Subject Info -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img src="{{ $student->profile_picture ? asset('storage/' . $student->profile_picture) : asset('images/photo.jpg') }}"
                             class="rounded-circle me-3" width="64" height="64" alt="Profile">
                        <div>
                            <h5 class="mb-1">{{ $student->first_name }} {{ $student->last_name }}</h5>
                            <p class="text-muted mb-1">Student ID: {{ $student->student_id }}</p>
                            <p class="text-muted mb-0">Grade {{ $student->grade_level }} - Section {{ $student->section ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <h5 class="mb-1">{{ $subject->name }}</h5>
                    <p class="text-muted mb-1">Subject Code: {{ $subject->code }}</p>
                    <p class="text-muted mb-0">Units: {{ $subject->units }} | Grade Level: {{ $subject->grade_level }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grade Form -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grade Information</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('teacher.subjects.grades.save', [$subject, $student]) }}" method="POST" id="gradeForm">
                        @csrf

                        <div class="row">
                            <!-- Quarter Grades -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Quarterly Grades</h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="quarter1" class="form-label">1st Quarter</label>
                                        <input type="number"
                                               class="form-control grade-input"
                                               id="quarter1"
                                               name="quarter1"
                                               value="{{ old('quarter1', $grade->quarter1) }}"
                                               min="0" max="100" step="0.01"
                                               placeholder="Enter grade">
                                        @error('quarter1')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="quarter2" class="form-label">2nd Quarter</label>
                                        <input type="number"
                                               class="form-control grade-input"
                                               id="quarter2"
                                               name="quarter2"
                                               value="{{ old('quarter2', $grade->quarter2) }}"
                                               min="0" max="100" step="0.01"
                                               placeholder="Enter grade">
                                        @error('quarter2')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="quarter3" class="form-label">3rd Quarter</label>
                                        <input type="number"
                                               class="form-control grade-input"
                                               id="quarter3"
                                               name="quarter3"
                                               value="{{ old('quarter3', $grade->quarter3) }}"
                                               min="0" max="100" step="0.01"
                                               placeholder="Enter grade">
                                        @error('quarter3')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="quarter4" class="form-label">4th Quarter</label>
                                        <input type="number"
                                               class="form-control grade-input"
                                               id="quarter4"
                                               name="quarter4"
                                               value="{{ old('quarter4', $grade->quarter4) }}"
                                               min="0" max="100" step="0.01"
                                               placeholder="Enter grade">
                                        @error('quarter4')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Grade Summary and Remarks -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Grade Summary</h6>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <h4 class="text-primary mb-1" id="finalGradeDisplay">
                                                    {{ $grade->final_grade ? number_format($grade->final_grade, 2) : '-' }}
                                                </h4>
                                                <small class="text-muted">Final Grade</small>
                                            </div>
                                            <div class="col-6">
                                                <h4 class="mb-1" id="statusDisplay">
                                                    @if($grade->final_grade)
                                                        <span class="badge badge-{{ $grade->final_grade >= 75 ? 'success' : 'danger' }}">
                                                            {{ $grade->final_grade >= 75 ? 'Passed' : 'Failed' }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-warning">Pending</span>
                                                    @endif
                                                </h4>
                                                <small class="text-muted">Status</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label for="remarks" class="form-label">Remarks (Optional)</label>
                                    <textarea class="form-control"
                                              id="remarks"
                                              name="remarks"
                                              rows="3"
                                              placeholder="Enter any remarks or comments">{{ old('remarks', $grade->remarks) }}</textarea>
                                    @error('remarks')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.subjects.grades', $subject) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Grade
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.grade-input {
    text-align: center;
}

.grade-input:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.grade-input.is-invalid {
    border-color: #e74a3b;
}

.grade-input.is-valid {
    border-color: #1cc88a;
}

.rounded-circle {
    object-fit: cover;
}

.badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}

.card {
    border-radius: 0.35rem;
}

.alert {
    border-radius: 0.35rem;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-calculate final grade when quarter grades change
    $('.grade-input').on('input', function() {
        calculateFinalGrade();
        validateGrade(this);
    });

    // Validate grade input
    function validateGrade(input) {
        const value = parseFloat($(input).val());
        $(input).removeClass('is-invalid is-valid');

        if ($(input).val() !== '') {
            if (isNaN(value) || value < 0 || value > 100) {
                $(input).addClass('is-invalid');
            } else {
                $(input).addClass('is-valid');
            }
        }
    }

    // Calculate final grade
    function calculateFinalGrade() {
        const quarters = [];

        $('.grade-input').each(function() {
            const value = parseFloat($(this).val());
            if (!isNaN(value)) {
                quarters.push(value);
            }
        });

        let finalGrade = '-';
        let status = 'Pending';
        let statusClass = 'warning';

        if (quarters.length >= 2) {
            const average = quarters.reduce((a, b) => a + b, 0) / quarters.length;
            finalGrade = average.toFixed(2);
            status = average >= 75 ? 'Passed' : 'Failed';
            statusClass = average >= 75 ? 'success' : 'danger';
        }

        $('#finalGradeDisplay').text(finalGrade);
        $('#statusDisplay').html(`<span class="badge badge-${statusClass}">${status}</span>`);
    }

    // Form validation before submit
    $('#gradeForm').on('submit', function(e) {
        let hasErrors = false;

        $('.grade-input').each(function() {
            validateGrade(this);
            if ($(this).hasClass('is-invalid')) {
                hasErrors = true;
            }
        });

        if (hasErrors) {
            e.preventDefault();
            alert('Please fix the invalid grades before saving.');
        }
    });

    // Initialize calculations
    calculateFinalGrade();
});
</script>
@endsection
