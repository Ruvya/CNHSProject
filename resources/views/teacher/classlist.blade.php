@extends('layouts.teacher')

@section('title', 'Class List')

@section('styles')
<style>
    /* New Header Style */
    .page-header-design {
        background: linear-gradient(115deg, #f97316 60%, #3b82f6 60%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(249, 115, 22, 0.25);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header-left-content {
        display: flex;
        align-items: center;
    }
    .header-left-content i {
        font-size: 3rem;
        margin-right: 1.5rem;
        opacity: 0.8;
    }
    .header-left-content h1 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .header-left-content p {
        margin: 0.25rem 0 0 0;
        opacity: 0.9;
        font-size: 1.1rem;
    }
    .header-right-content .student-count-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.75rem 1.25rem;
        border-radius: 50px;
        display: flex;
        align-items: center;
        font-weight: 600;
        font-size: 1rem;
        backdrop-filter: blur(10px);
    }
    .header-right-content .student-count-badge i {
        margin-right: 0.5rem;
    }

    /* Existing styles from the file */
    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
    }

    .btn-group .dropdown-toggle::after {
        margin-left: 0.255em;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.05);
        transition: background-color 0.2s ease;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
             <div class="page-header-design">
                <div class="header-left-content">
                    <i class="fas fa-users"></i>
                    <div>
                        <h1>Class List</h1>
                        <p>Manage your students and their grades</p>
                    </div>
                </div>
                <div class="header-right-content">
                    <div class="student-count-badge">
                        <i class="fas fa-user-graduate"></i>
                        <span>{{ $students->count() }} Students</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter me-2"></i>Filter Students
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('teacher.classlist') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="subject_id" class="form-label">Subject</label>
                                <select class="form-select" id="subject_id" name="subject_id">
                                    <option value="">All Subjects</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}"
                                            {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }} ({{ $subject->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="grade_level" class="form-label">Grade Level</label>
                                <select class="form-select" id="grade_level" name="grade_level">
                                    <option value="">All Grades</option>
                                    @foreach($gradeLevels as $grade)
                                        <option value="{{ $grade }}"
                                            {{ request('grade_level') == $grade ? 'selected' : '' }}>
                                            Grade {{ $grade }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="section" class="form-label">Section</label>
                                <select class="form-select" id="section" name="section">
                                    <option value="">All Sections</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section }}"
                                            {{ request('section') == $section ? 'selected' : '' }}>
                                            Section {{ $section }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-1"></i>Filter
                                </button>
                                <a href="{{ route('teacher.classlist') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users me-2"></i>Students
                            @if(request()->hasAny(['subject_id', 'grade_level', 'section']))
                                <small class="text-muted">(Filtered Results)</small>
                            @endif
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-sort me-1"></i>Sort
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                                <li><h6 class="dropdown-header">Sort By</h6></li>
                                <li><a class="dropdown-item sort-option" href="#" data-sort="name" data-direction="asc">Name (A-Z)</a></li>
                                <li><a class="dropdown-item sort-option" href="#" data-sort="name" data-direction="desc">Name (Z-A)</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item sort-option" href="#" data-sort="grade_level" data-direction="asc">Grade Level (Low-High)</a></li>
                                <li><a class="dropdown-item sort-option" href="#" data-sort="grade_level" data-direction="desc">Grade Level (High-Low)</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item sort-option" href="#" data-sort="student_id" data-direction="asc">Student ID (Ascending)</a></li>
                                <li><a class="dropdown-item sort-option" href="#" data-sort="student_id" data-direction="desc">Student ID (Descending)</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($students->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Students Found</h5>
                            <p class="text-muted">
                                @if(request()->hasAny(['subject_id', 'grade_level', 'section']))
                                    Try adjusting your filters to see more students.
                                @else
                                    No students are enrolled in your subjects yet.
                                @endif
                            </p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover" id="studentsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Student ID <i class="fas fa-sort sort-icon" data-sort="student_id"></i></th>
                                        <th>Name <i class="fas fa-sort sort-icon" data-sort="name"></i></th>
                                        <th>Track</th>
                                        <th>Cluster</th>
                                        <th>Grade Level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        @php
                                            $enrolledSubjects = $student->subjects->where('teacher_id', $teacher->id);
                                        @endphp
                                        <tr style="cursor: pointer;" onclick="window.location='{{ route('teacher.students.show', $student->id) }}'">
                                            <td>
                                                <span class="fw-bold">{{ $student->student_id }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $student->full_name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $student->track ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $student->cluster ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $student->grade_level ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.sort-option').click(function(e) {
        e.preventDefault();
        const sortBy = $(this).data('sort');
        const direction = $(this).data('direction');
        
        const $tbody = $('#studentsTable tbody');
        const rows = $tbody.find('tr').toArray();
        
        rows.sort((a, b) => {
            let aVal, bVal;
            
            if (sortBy === 'student_id') {
                aVal = $(a).find('td:first').text().trim();
                bVal = $(b).find('td:first').text().trim();
            } else if (sortBy === 'name') {
                aVal = $(a).find('td:nth-child(2) .fw-bold').text().trim();
                bVal = $(b).find('td:nth-child(2) .fw-bold').text().trim();
            } else if (sortBy === 'grade_level') {
                aVal = $(a).find('td:nth-child(3) .badge:first').text().trim();
                bVal = $(b).find('td:nth-child(3) .badge:first').text().trim();
            }
            
            if (direction === 'asc') {
                return aVal.localeCompare(bVal);
            } else {
                return bVal.localeCompare(aVal);
            }
        });
        
        $tbody.append(rows);
    });
});

// Fetch subjects based on grade level
$('#grade_level').on('change', function() {
    const gradeLevel = $(this).val();
    $('#subject_id').html('<option value="">Loading...</option>');
    if (gradeLevel) {
        fetch(`/teacher/get-subjects?gradeLevel=${gradeLevel}`)
            .then(res => res.json())
            .then(data => {
                let options = '<option value="">Select Subject</option>';
                data.forEach(subject => {
                    options += `<option value="${subject.id}">${subject.name}</option>`;
                });
                $('#subject_id').html(options);
            });
    } else {
        $('#subject_id').html('<option value="">Select Subject</option>');
    }
});

// Fetch students based on filters
$('#viewStudentsBtn').on('click', function(e) {
    e.preventDefault();
    const gradeLevel = $('#grade_level').val();
    const subjectId = $('#subject_id').val();
    const section = $('#section').val();
    if (!gradeLevel || !subjectId || !section) {
        $('#studentList').html('<div class="alert alert-warning">Please select all filters.</div>');
        return;
    }
    $('#studentList').html('<div class="text-center text-muted">Loading students...</div>');
    fetch(`/teacher/get-students?gradeLevel=${gradeLevel}&subjectId=${subjectId}&section=${section}`)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                $('#studentList').html('<div class="alert alert-info">No students found for the selected filters.</div>');
                return;
            }
            let table = `<div class="table-responsive"><table class="table table-bordered table-students"><thead><tr><th>#</th><th>Name</th><th>Student ID</th><th>Section</th><th>Gender</th></tr></thead><tbody>`;
            data.forEach((student, idx) => {
                table += `<tr><td>${idx+1}</td><td>${student.name}</td><td>${student.student_id}</td><td>${student.section}</td><td>${student.gender}</td></tr>`;
            });
            table += '</tbody></table></div>';
            $('#studentList').html(table);
        });
});
</script>
@endsection