@extends('layouts.registrar')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-gray-800">
                        <i class="fas fa-user-graduate me-2"></i>Student Subject Assignments
                    </h1>
                    <p class="text-muted">Assign subjects to students based on their grade level, track, and strand</p>
                </div>
                <div>
                    <a href="{{ route('registrar.student-subject-assignments.bulk-create') }}" class="btn btn-success me-2">
                        <i class="fas fa-users me-1"></i>Bulk Assignment
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">Success!</h5>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle fa-2x text-danger me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">Error!</h5>
                    <p class="mb-0">{{ session('error') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
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
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">With Subjects</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $studentsWithSubjects }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Without Subjects</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $studentsWithoutSubjects }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-1"></i>Filter Students
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('registrar.student-subject-assignments.index') }}">
                <div class="row">
                    <div class="col-md-2">
                        <label for="grade_level" class="form-label">Grade Level</label>
                        <select name="grade_level" id="grade_level" class="form-select">
                            <option value="">All Grades</option>
                            @foreach($gradeLevels as $grade)
                                <option value="{{ $grade }}" {{ request('grade_level') == $grade ? 'selected' : '' }}>
                                    {{ $grade }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="track" class="form-label">Track</label>
                        <select name="track" id="track" class="form-select">
                            <option value="">All Tracks</option>
                            @foreach($tracks as $track)
                                <option value="{{ $track }}" {{ request('track') == $track ? 'selected' : '' }}>
                                    {{ $track }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="strand" class="form-label">Strand</label>
                        <select name="strand" id="strand" class="form-select">
                            <option value="">All Strands</option>
                            @foreach($strands as $strand)
                                <option value="{{ $strand }}" {{ request('strand') == $strand ? 'selected' : '' }}>
                                    {{ $strand }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="section" class="form-label">Section</label>
                        <select name="section" id="section" class="form-select">
                            <option value="">All Sections</option>
                            @foreach($sections as $section)
                                <option value="{{ $section }}" {{ request('section') == $section ? 'selected' : '' }}>
                                    {{ $section }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="assignment_status" class="form-label">Assignment Status</label>
                        <select name="assignment_status" id="assignment_status" class="form-select">
                            <option value="">All Students</option>
                            <option value="assigned" {{ request('assignment_status') == 'assigned' ? 'selected' : '' }}>With Subjects</option>
                            <option value="unassigned" {{ request('assignment_status') == 'unassigned' ? 'selected' : '' }}>Without Subjects</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Student ID, Name..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('registrar.student-subject-assignments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-1"></i>Students List
            </h6>
        </div>
        <div class="card-body">
            @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Grade Level</th>
                                <th>Track/Strand</th>
                                <th>Section</th>
                                <th>Assigned Subjects</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>{{ $student->student_id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title bg-primary rounded-circle">
                                                    {{ strtoupper(substr($student->first_name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                                <br><small class="text-muted">{{ $student->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student->grade_level }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $student->track }}</span>
                                        @if($student->strand)
                                            <br><span class="badge bg-secondary mt-1">{{ $student->strand }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $student->section ?? 'Not Assigned' }}</td>
                                    <td>
                                        @if($student->subjects->count() > 0)
                                            <span class="badge bg-success">{{ $student->subjects->count() }} subjects</span>
                                        @else
                                            <span class="badge bg-warning">No subjects</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('registrar.student-subject-assignments.create', $student->id) }}" 
                                               class="btn btn-sm btn-primary" title="Assign Subjects">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                            <a href="{{ route('registrar.students.show', $student->id) }}" 
                                               class="btn btn-sm btn-info" title="View Student">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $students->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Students Found</h5>
                    <p class="text-muted">Try adjusting your filters or search criteria.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
</style>

<script>
// Auto-hide success alerts after 5 seconds
setTimeout(function() {
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        const bsAlert = new bootstrap.Alert(successAlert);
        bsAlert.close();
    }
}, 5000);
</script>
@endsection
