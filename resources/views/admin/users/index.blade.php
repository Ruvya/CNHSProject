@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<!-- Page Header -->
<div class="card mb-4" style="border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border: none;">
    <div class="card-body d-flex flex-wrap align-items-center justify-content-between" style="padding: 1.2rem 1.5rem;">
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <span style="font-size: 2rem; color: #2563eb; background: #f1f5fb; border-radius: 12px; padding: 0.7rem; margin-right: 1rem; display: flex; align-items: center;">
                <i class="fas fa-users"></i>
            </span>
            <div>
                <div style="font-size: 1.3rem; font-weight: bold; font-family: 'Poppins', sans-serif; color: #222;">User Management</div>
                <div style="font-size: 0.95rem; font-family: 'Poppins', sans-serif; color: #555; font-weight: 500;">Manage teachers and students in the CNHS system</div>
            </div>
        </div>
    </div>
</div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('email_success'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-envelope-check me-2"></i>
            <strong>Email Sent Successfully!</strong> {{ session('email_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('email_warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Email Issue:</strong> {{ session('email_warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

<!-- Statistics Cards -->
{{--
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card primary">
            <div class="stat-card-header">
                <div class="stat-card-title">Total Teachers</div>
                <div class="stat-card-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($teachers->count()) }}</div>
            <div class="stat-card-change positive">
                <i class="fas fa-users me-1"></i>
                Active faculty members
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card success">
            <div class="stat-card-header">
                <div class="stat-card-title">Total Students</div>
                <div class="stat-card-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($students->count()) }}</div>
            <div class="stat-card-change positive">
                <i class="fas fa-filter me-1"></i>
                @if($selectedGradeLevel && $selectedGradeLevel !== 'all')
                    Filtered by {{ $selectedGradeLevel }}
                @else
                    All grade levels
                @endif
            </div>
        </div>
    </div>

    @if(isset($studentsByGrade) && $studentsByGrade->count() > 0)
    <div class="col-xl-3 col-md-6">
        <div class="stat-card info">
            <div class="stat-card-header">
                <div class="stat-card-title">Grade Levels</div>
                <div class="stat-card-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $studentsByGrade->count() }}</div>
            <div class="stat-card-change positive">
                <i class="fas fa-graduation-cap me-1"></i>
                Available grades
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card warning">
            <div class="stat-card-header">
                <div class="stat-card-title">Largest Grade</div>
                <div class="stat-card-icon">
                    <i class="fas fa-trophy"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $studentsByGrade->sortByDesc('count')->first()->count ?? 0 }}</div>
            <div class="stat-card-change positive">
                <i class="fas fa-star me-1"></i>
                {{ $studentsByGrade->sortByDesc('count')->first()->grade_level ?? 'N/A' }}
            </div>
        </div>
    </div>
    @endif
</div>
--}}

    <!-- Teachers Section -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>
                    Teachers ({{ $teachers->count() }})
                </h5>
                <a href="{{ route('admin.users.teachers.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Add Teacher
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($teachers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle" style="border-radius: 14px; overflow: hidden;">
                        <thead>
                            <tr>
                                <th class="align-middle text-center">ID</th>
                                <th class="align-middle">Name</th>
                                <th class="align-middle text-center">Email</th>
                                <th class="align-middle text-center">Strand</th>
                                <th class="align-middle text-center">Contact</th>
                                <th class="align-middle text-center">Status</th>
                                <th class="align-middle text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                            <tr>
                                <td class="align-middle text-center">{{ $teacher->id }}</td>
                                <td class="align-middle">
                                    <a href="{{ route('admin.users.teachers.edit', $teacher) }}" class="text-decoration-none" style="color: #222;">
                                        <strong style="color: #222;">{{ $teacher->name }}</strong>
                                    </a>
                                </td>
                                <td class="align-middle text-center">{{ $teacher->email }}</td>
                                <td class="align-middle text-center">{{ $teacher->strand ?? '-' }}</td>
                                <td class="align-middle text-center">{{ $teacher->contact_number ?? '-' }}</td>
                                <td class="align-middle text-center">{{ ucfirst($teacher->status) }}</td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('admin.users.teachers.edit', $teacher) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.users.teachers.destroy', $teacher) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this teacher?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No teachers found</h5>
                    <p class="text-muted mb-4">There are no teachers in the system yet.</p>
                    <a href="{{ route('admin.users.teachers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add First Teacher
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Students Section -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-user-graduate me-2 text-success"></i>
                        Students ({{ $students->count() }})
                        @if($selectedGradeLevel && $selectedGradeLevel !== 'all')
                            - {{ $selectedGradeLevel }}
                        @endif
                    </h5>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <!-- Grade Level Filter -->
                    <form method="GET" action="{{ route('admin.users') }}" class="d-flex align-items-center">
                        <label for="grade_level" class="form-label me-2 mb-0 text-muted">Filter by Grade:</label>
                        <select name="grade_level" id="grade_level" class="form-select form-select-sm me-2" style="width: auto;" onchange="this.form.submit()">
                            <option value="all" {{ (!$selectedGradeLevel || $selectedGradeLevel === 'all') ? 'selected' : '' }}>
                                All Grades
                            </option>
                            @foreach($availableGradeLevels as $gradeLevel)
                                <option value="{{ $gradeLevel }}" {{ $selectedGradeLevel === $gradeLevel ? 'selected' : '' }}>
                                    {{ $gradeLevel }}
                                </option>
                            @endforeach
                        </select>
                        @if($selectedGradeLevel && $selectedGradeLevel !== 'all')
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary btn-sm me-2">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        @endif
                    </form>

                </div>
            </div>
        </div>
        <div class="card-body">
            @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle" style="border-radius: 14px; overflow: hidden;">
                        <thead>
                            <tr>
                                <th class="align-middle text-center">Student ID</th>
                                <th class="align-middle">Name</th>
                                <th class="align-middle text-center">Grade Level</th>
                                <th class="align-middle text-center">Track</th>
                                <th class="align-middle text-center">Section</th>
                                <th class="align-middle text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td class="align-middle text-center">{{ $student->student_id }}</td>
                                <td class="align-middle">
                                    <a href="{{ route('admin.users.students.show', $student) }}" class="text-decoration-none" style="color: #222;">
                                        <strong style="color: #222;">{{ $student->full_name }}</strong>
                                    </a>
                                </td>
                                <td class="align-middle text-center">{{ $student->grade_level }}</td>
                                <td class="align-middle text-center">{{ $student->track ?? '-' }}</td>
                                <td class="align-middle text-center">{{ $student->section ?? '-' }}</td>
                                <td class="align-middle text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.students.show', $student) }}" class="btn btn-info btn-sm" title="View Profile">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.users.students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this student?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                    @if($selectedGradeLevel && $selectedGradeLevel !== 'all')
                        <h5 class="text-muted">No students found in {{ $selectedGradeLevel }}</h5>
                        <p class="text-muted mb-4">There are no students enrolled in this grade level yet.</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i>View All Students
                            </a>
                        </div>
                    @else
                        <h5 class="text-muted">No students found</h5>
                        <p class="text-muted mb-4">There are no students in the system yet.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables for Teachers
    $('#teachersTable').DataTable({
        "pageLength": 10,
        "order": [[ 1, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 6 }
        ],
        "language": {
            "search": "Search teachers:",
            "lengthMenu": "Show _MENU_ teachers per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ teachers",
            "emptyTable": "No teachers available"
        }
    });

    // Initialize DataTables for Students
    $('#studentsTable').DataTable({
        "pageLength": 10,
        "order": [[ 1, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 6 }
        ],
        "language": {
            "search": "Search students:",
            "lengthMenu": "Show _MENU_ students per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ students",
            "emptyTable": "No students available"
        }
    });

    // Add loading state for grade level filter
    $('#grade_level').on('change', function() {
        const form = $(this).closest('form');
        const submitBtn = form.find('button[type="submit"]');

        // Show loading state
        $(this).prop('disabled', true);
        $('body').append('<div class="loading-overlay"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');

        // Submit form
        form.submit();
    });
});
</script>

<style>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.table th {
    background-color: var(--light-color);
    border: none;
    font-weight: 600;
    color: var(--dark-color);
}

.table td {
    border: none;
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background: rgba(37, 99, 235, 0.05);
}

.badge {
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 6px;
}
</style>
@endpush
@endsection

@push('styles')
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 0.875rem;
    font-weight: 600;
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}
</style>
@endpush
