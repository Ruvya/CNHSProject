@extends('layouts.teacher')

@section('title', 'Manage Grades - ' . $subject->name)

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-gray-800">{{ $subject->name }} - Manage Grades</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('teacher.subjects') }}">Subjects</a></li>
                            <li class="breadcrumb-item active">{{ $subject->name }} Grades</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('teacher.subjects') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Back to Subjects
                    </a>
                    <a href="{{ route('teacher.subjects.students', $subject) }}" class="btn btn-info">
                        <i class="fas fa-users"></i> View Students
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStudents }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Students with Grades</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $studentsWithGrades }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Class Average</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $averageGrade ? number_format($averageGrade, 2) : 'N/A' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Grades</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStudents - $studentsWithGrades }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grades Management Form -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Grade Management</h6>
                    <div>
                        <button type="button" class="btn btn-success btn-sm" onclick="saveAllGrades()">
                            <i class="fas fa-save"></i> Save All Grades
                        </button>
                    </div>
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

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($students->count() > 0)
                        <form id="gradesForm" action="{{ route('teacher.subjects.grades.update', $subject) }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered" id="gradesTable">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="align-middle">Student</th>
                                            <th colspan="4" class="text-center">Quarterly Grades</th>
                                            <th rowspan="2" class="align-middle">Final Grade</th>
                                            <th rowspan="2" class="align-middle">Status</th>
                                            <th rowspan="2" class="align-middle">Actions</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Q1</th>
                                            <th class="text-center">Q2</th>
                                            <th class="text-center">Q3</th>
                                            <th class="text-center">Q4</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $student)
                                            @php
                                                $grade = $student->grades->first();
                                                $finalGrade = $grade ? $grade->final_grade : null;
                                                $status = $finalGrade ? ($finalGrade >= 75 ? 'Passed' : 'Failed') : 'Pending';
                                                $statusClass = $finalGrade ? ($finalGrade >= 75 ? 'success' : 'danger') : 'warning';
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $student->profile_picture ? asset('storage/' . $student->profile_picture) : asset('images/photo.jpg') }}"
                                                             class="rounded-circle me-2" width="32" height="32" alt="Profile">
                                                        <div>
                                                            <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                                            <br><small class="text-muted">{{ $student->student_id }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number"
                                                           class="form-control form-control-sm grade-input"
                                                           name="grades[{{ $student->id }}][quarter1]"
                                                           value="{{ $grade ? $grade->quarter1 : '' }}"
                                                           min="0" max="100" step="0.01"
                                                           data-student="{{ $student->id }}"
                                                           data-quarter="1">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                           class="form-control form-control-sm grade-input"
                                                           name="grades[{{ $student->id }}][quarter2]"
                                                           value="{{ $grade ? $grade->quarter2 : '' }}"
                                                           min="0" max="100" step="0.01"
                                                           data-student="{{ $student->id }}"
                                                           data-quarter="2">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                           class="form-control form-control-sm grade-input"
                                                           name="grades[{{ $student->id }}][quarter3]"
                                                           value="{{ $grade ? $grade->quarter3 : '' }}"
                                                           min="0" max="100" step="0.01"
                                                           data-student="{{ $student->id }}"
                                                           data-quarter="3">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                           class="form-control form-control-sm grade-input"
                                                           name="grades[{{ $student->id }}][quarter4]"
                                                           value="{{ $grade ? $grade->quarter4 : '' }}"
                                                           min="0" max="100" step="0.01"
                                                           data-student="{{ $student->id }}"
                                                           data-quarter="4">
                                                </td>
                                                <td class="text-center">
                                                    <span class="final-grade-display" data-student="{{ $student->id }}">
                                                        {{ $finalGrade ? number_format($finalGrade, 2) : '-' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-{{ $statusClass }} status-badge" data-student="{{ $student->id }}">
                                                        {{ $status }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('teacher.subjects.grades.edit', [$subject, $student]) }}"
                                                       class="btn btn-sm btn-outline-primary" title="Edit Individual Grade">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-600">No Students Enrolled</h5>
                            <p class="text-gray-500">There are currently no students enrolled in this subject.</p>
                        </div>
                    @endif
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

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.table th {
    background-color: #f8f9fc;
    border-color: #e3e6f0;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    border-color: #e3e6f0;
    vertical-align: middle;
}

.grade-input {
    width: 80px;
    text-align: center;
}

.grade-input:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.rounded-circle {
    object-fit: cover;
}

.grade-input.is-invalid {
    border-color: #e74a3b;
}

.grade-input.is-valid {
    border-color: #1cc88a;
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
        const studentId = $(this).data('student');
        calculateFinalGrade(studentId);
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

    // Calculate final grade for a student
    function calculateFinalGrade(studentId) {
        const quarters = [];
        for (let i = 1; i <= 4; i++) {
            const value = parseFloat($(`input[data-student="${studentId}"][data-quarter="${i}"]`).val());
            if (!isNaN(value)) {
                quarters.push(value);
            }
        }

        let finalGrade = '-';
        let status = 'Pending';
        let statusClass = 'warning';

        if (quarters.length >= 2) {
            const average = quarters.reduce((a, b) => a + b, 0) / quarters.length;
            finalGrade = average.toFixed(2);
            status = average >= 75 ? 'Passed' : 'Failed';
            statusClass = average >= 75 ? 'success' : 'danger';
        }

        $(`.final-grade-display[data-student="${studentId}"]`).text(finalGrade);
        $(`.status-badge[data-student="${studentId}"]`)
            .removeClass('badge-success badge-danger badge-warning')
            .addClass(`badge-${statusClass}`)
            .text(status);
    }

    // Save all grades
    window.saveAllGrades = function() {
        // Validate all inputs first
        let hasErrors = false;
        $('.grade-input').each(function() {
            validateGrade(this);
            if ($(this).hasClass('is-invalid')) {
                hasErrors = true;
            }
        });

        if (hasErrors) {
            alert('Please fix the invalid grades before saving.');
            return;
        }

        // Show loading state
        const saveBtn = $('button[onclick="saveAllGrades()"]');
        const originalText = saveBtn.html();
        saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        // Submit the form
        $('#gradesForm').submit();
    };

    // Initialize DataTable
    $('#gradesTable').DataTable({
        "pageLength": 25,
        "order": [[ 0, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [1, 2, 3, 4, 5, 6, 7] }
        ],
        "language": {
            "search": "Search students:",
            "lengthMenu": "Show _MENU_ students per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ students",
            "infoEmpty": "No students found",
            "infoFiltered": "(filtered from _MAX_ total students)"
        }
    });
});
</script>
@endsection
