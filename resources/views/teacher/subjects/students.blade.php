@extends('layouts.teacher')

@section('title', 'Students - ' . $subject->name)

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-gray-800">{{ $subject->name }} - Students</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('teacher.subjects') }}">Subjects</a></li>
                            <li class="breadcrumb-item active">{{ $subject->name }} Students</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('teacher.subjects') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Back to Subjects
                    </a>
                    <a href="{{ route('teacher.subjects.grades', $subject) }}" class="btn btn-success">
                        <i class="fas fa-clipboard-list"></i> Manage Grades
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Grade Management Card (from grades.blade.php) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm p-4">
                <form id="filterForm" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="subject" class="form-label">Subject</label>
                        <select id="subject" class="form-select">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subjectOption)
                                <option value="{{ $subjectOption->id }}">{{ $subjectOption->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="gradeLevel" class="form-label">Grade Level</label>
                        <select id="gradeLevel" class="form-select">
                            <option value="">Select Grade Level</option>
                            <option value="11">Grade 11</option>
                            <option value="12">Grade 12</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="section" class="form-label">Section</label>
                        <select id="section" class="form-select">
                            <option value="">Select Section</option>
                            <option value="A">Section A</option>
                            <option value="B">Section B</option>
                            <option value="C">Section C</option>
                            <option value="D">Section D</option>
                            <option value="E">Section E</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary w-100" id="viewGradesBtn">
                            <i class="fas fa-search"></i> View Grades
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Subject Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="text-primary font-weight-bold">Subject Code</h6>
                            <p class="mb-0">{{ $subject->code }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-primary font-weight-bold">Grade Level</h6>
                            <p class="mb-0">Grade {{ $subject->grade_level }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-primary font-weight-bold">Total Students</h6>
                            <p class="mb-0">{{ $students->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Enrolled Students</h6>
                    <span class="badge badge-primary">{{ $students->count() }} Students</span>
                </div>
                <div class="card-body">
                    @if($students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="studentsTable">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Grade Level</th>
                                        <th>Section</th>
                                        <th>Actions</th>
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
                                            <td>{{ $student->student_id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $student->profile_picture ? asset('storage/' . $student->profile_picture) : asset('images/photo.jpg') }}" 
                                                         class="rounded-circle me-2" width="32" height="32" alt="Profile">
                                                    <div>
                                                        <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                                        @if($student->middle_name)
                                                            <br><small class="text-muted">{{ $student->middle_name }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Grade {{ $student->grade_level }}</td>
                                            <td>{{ $student->section ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('teacher.subjects.grades.edit', [$subject, $student]) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="Edit Grade">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('teacher.students.show', $student) }}" 
                                                   class="btn btn-sm btn-outline-info" title="View Profile">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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

.table-grades th, .table-grades td {
    vertical-align: middle;
}
.table-grades th {
    background: #f8f9fa;
}
.card-header {
    border-bottom: 1px solid #e3e6f0;
}
.grade-input {
    width: 80px;
    text-align: center;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#studentsTable').DataTable({
        "pageLength": 25,
        "order": [[ 1, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [6] }
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

$('#viewGradesBtn').on('click', function(e) {
    e.preventDefault();
    const subjectId = $('#subject').val();
    const gradeLevel = $('#gradeLevel').val();
    const section = $('#section').val();
    if (!subjectId || !gradeLevel || !section) {
        $('#gradesList').html('<div class="alert alert-warning">Please select all filters.</div>');
        return;
    }
    $('#gradesList').html('<div class="text-center text-muted">Loading grades...</div>');
    fetch(`/teacher/get-grades?subjectId=${subjectId}&gradeLevel=${gradeLevel}&section=${section}`)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                $('#gradesList').html('<div class="alert alert-info">No grades found for the selected filters.</div>');
                return;
            }
            let table = `<div class="table-responsive"><table class="table table-bordered table-grades"><thead><tr><th>#</th><th>Name</th><th>Student ID</th><th>Prelim</th><th>Midterm</th><th>Final</th><th>Average</th><th>Status</th><th>Action</th></tr></thead><tbody>`;
            data.forEach((student, idx) => {
                table += `<tr>
                    <td>${idx+1}</td>
                    <td>${student.name}</td>
                    <td>${student.student_id}</td>
                    <td><input type="number" class="form-control grade-input" value="${student.prelim ?? ''}" data-id="${student.id}" data-type="prelim"></td>
                    <td><input type="number" class="form-control grade-input" value="${student.midterm ?? ''}" data-id="${student.id}" data-type="midterm"></td>
                    <td><input type="number" class="form-control grade-input" value="${student.final ?? ''}" data-id="${student.id}" data-type="final"></td>
                    <td>${student.average ?? '-'}</td>
                    <td>${student.status ?? '-'}</td>
                    <td><button class="btn btn-success btn-sm save-grade-btn" data-id="${student.id}"><i class="fas fa-save"></i> Save</button></td>
                </tr>`;
            });
            table += '</tbody></table></div>';
            $('#gradesList').html(table);
        });
});

// Save grade (AJAX example, you may need to adjust for your backend)
$(document).on('click', '.save-grade-btn', function() {
    const row = $(this).closest('tr');
    const studentId = $(this).data('id');
    const subjectId = $('#subject').val();
    const prelim = row.find('input[data-type="prelim"]').val();
    const midterm = row.find('input[data-type="midterm"]').val();
    const final = row.find('input[data-type="final"]').val();
    $.ajax({
        url: '{{ route('teacher.save-grade') }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            student_id: studentId,
            subject_id: subjectId,
            prelim: prelim,
            midterm: midterm,
            final: final
        },
        success: function(response) {
            alert('Grade saved successfully!');
        },
        error: function() {
            alert('Error saving grade.');
        }
    });
});
</script>
@endsection
