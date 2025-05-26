@extends('layouts.teacher')

@section('title', 'Grades Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Grades Management</h1>
            <div class="card shadow-sm p-4">
                <form id="filterForm" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="subject" class="form-label">Subject</label>
                        <select id="subject" class="form-select">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
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
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Student Grades</h5>
                </div>
                <div class="card-body">
                    <div id="gradesList">
                        <p class="text-center text-muted">Select subject, grade level, and section to view grades.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
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