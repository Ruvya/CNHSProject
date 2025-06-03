@extends('layouts.registrar')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-gray-800">
                        <i class="fas fa-magic me-2"></i>Automatic Subject Assignment
                    </h1>
                    <p class="text-muted">Subjects are automatically assigned based on student's track and strand</p>
                </div>
                <div>
                    <a href="{{ route('registrar.automatic-subject-assignment.curriculum-mapping') }}" class="btn btn-info me-2">
                        <i class="fas fa-sitemap me-1"></i>Curriculum Mapping
                    </a>
                    <a href="{{ route('registrar.automatic-subject-assignment.fix-incomplete-data') }}" class="btn btn-warning">
                        <i class="fas fa-tools me-1"></i>Fix Incomplete Data
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
        <div class="col-md-3">
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
        <div class="col-md-3">
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
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Need Assignment</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $studentsNeedingAssignment->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Incomplete Data</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $studentsWithIncompleteData }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-info-circle me-1"></i>How Automatic Assignment Works
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="fas fa-user-graduate fa-3x text-primary mb-3"></i>
                        <h5>1. Student Data</h5>
                        <p class="text-muted">When a student's track, strand, and grade level are set, the system automatically identifies appropriate subjects.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="fas fa-magic fa-3x text-success mb-3"></i>
                        <h5>2. Auto Assignment</h5>
                        <p class="text-muted">Core subjects and track/strand-specific subjects are automatically assigned based on DepEd curriculum.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="fas fa-sync fa-3x text-info mb-3"></i>
                        <h5>3. Dynamic Updates</h5>
                        <p class="text-muted">If track or strand changes, subjects are automatically reassigned to match the new curriculum requirements.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Needing Assignment -->
    @if($studentsNeedingAssignment->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-exclamation-triangle me-1"></i>Students Needing Assignment ({{ $studentsNeedingAssignment->count() }})
                </h6>
                <form method="POST" action="{{ route('registrar.automatic-subject-assignment.bulk-assign') }}" style="display: inline;">
                    @csrf
                    @foreach($studentsNeedingAssignment as $student)
                        <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
                    @endforeach
                    <input type="hidden" name="school_year" value="{{ date('Y') }}-{{ date('Y') + 1 }}">
                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Assign subjects to all {{ $studentsNeedingAssignment->count() }} students?')">
                        <i class="fas fa-magic me-1"></i>Assign All
                    </button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Grade Level</th>
                                <th>Track/Strand</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($studentsNeedingAssignment as $student)
                                <tr>
                                    <td>
                                        <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                        <br><small class="text-muted">{{ $student->student_id }}</small>
                                    </td>
                                    <td>{{ $student->grade_level }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $student->track }}</span>
                                        <br><span class="badge bg-secondary mt-1">{{ $student->strand }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('registrar.automatic-subject-assignment.preview', $student->id) }}" 
                                               class="btn btn-sm btn-info" title="Preview Subjects">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form method="POST" action="{{ route('registrar.automatic-subject-assignment.assign', $student->id) }}" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="school_year" value="{{ date('Y') }}-{{ date('Y') + 1 }}">
                                                <button type="submit" class="btn btn-sm btn-success" title="Assign Subjects">
                                                    <i class="fas fa-magic"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Students Needing Reassignment -->
    @if($studentsNeedingReassignment->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-sync me-1"></i>Students Needing Reassignment ({{ $studentsNeedingReassignment->count() }})
                </h6>
                <form method="POST" action="{{ route('registrar.automatic-subject-assignment.bulk-reassign') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="school_year" value="{{ date('Y') }}-{{ date('Y') + 1 }}">
                    <button type="submit" class="btn btn-info btn-sm" onclick="return confirm('Reassign subjects for all {{ $studentsNeedingReassignment->count() }} students?')">
                        <i class="fas fa-sync me-1"></i>Reassign All
                    </button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Grade Level</th>
                                <th>Track/Strand</th>
                                <th>Current Subjects</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($studentsNeedingReassignment as $student)
                                <tr>
                                    <td>
                                        <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                        <br><small class="text-muted">{{ $student->student_id }}</small>
                                    </td>
                                    <td>{{ $student->grade_level }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $student->track }}</span>
                                        <br><span class="badge bg-secondary mt-1">{{ $student->strand }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $student->subjects->count() }} subjects</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('registrar.automatic-subject-assignment.preview', $student->id) }}" 
                                               class="btn btn-sm btn-info" title="Preview New Subjects">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form method="POST" action="{{ route('registrar.automatic-subject-assignment.assign', $student->id) }}" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="school_year" value="{{ date('Y') }}-{{ date('Y') + 1 }}">
                                                <button type="submit" class="btn btn-sm btn-warning" title="Reassign Subjects">
                                                    <i class="fas fa-sync"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- All Good Message -->
    @if($studentsNeedingAssignment->count() == 0 && $studentsNeedingReassignment->count() == 0 && $studentsWithIncompleteData == 0)
        <div class="card shadow mb-4">
            <div class="card-body text-center py-5">
                <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
                <h3 class="text-success">All Students Have Proper Subject Assignments!</h3>
                <p class="text-muted">Every student with complete track and strand information has been automatically assigned the appropriate subjects.</p>
                <a href="{{ route('registrar.students.index') }}" class="btn btn-primary">
                    <i class="fas fa-users me-1"></i>View All Students
                </a>
            </div>
        </div>
    @endif
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
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
