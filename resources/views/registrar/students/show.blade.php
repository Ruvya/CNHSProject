@extends('layouts.registrar')

@section('title', 'Student Profile')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ $student->full_name }}</h1>
            <p class="text-muted">Student ID: {{ $student->student_id }} • {{ $student->grade_level }}</p>
            @if(isset($schoolYear))
                <div class="mt-2">
                    <form method="GET" action="{{ route('registrar.students.show', $student) }}" class="row g-2 align-items-center">
                        <div class="col-auto">
                            <label class="form-label mb-0"><strong>School Year:</strong></label>
                        </div>
                        <div class="col-auto">
                            <select name="school_year" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Active</option>
                                @foreach(($availableSchoolYears ?? collect()) as $sy)
                                    <option value="{{ $sy }}" {{ ($schoolYear ?? '') === $sy ? 'selected' : '' }}>{{ $sy }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($schoolYear)
                            <div class="col-auto">
                                <span class="badge bg-secondary">Viewing {{ $schoolYear }}</span>
                            </div>
                        @endif
                    </form>
                </div>
            @endif
        </div>
        <div>
            <a href="{{ route('registrar.students.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Students
            </a>
            <a href="{{ route('registrar.students.edit', $student) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit Profile
            </a>
            <a href="{{ route('registrar.students.enrollment', $student) }}" class="btn btn-success">
                <i class="fas fa-book me-2"></i>Manage Enrollment
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main Content -->
        <div class="col-xl-8">
            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2 text-primary"></i>
                        Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Full Name:</strong></td>
                                    <td>{{ $student->full_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>First Name:</strong></td>
                                    <td>{{ $student->first_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Middle Name:</strong></td>
                                    <td>{{ $student->middle_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Name:</strong></td>
                                    <td>{{ $student->last_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Gender:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $student->gender === 'Male' ? 'primary' : 'pink' }}">
                                            {{ $student->gender }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Date of Birth:</strong></td>
                                    <td>{{ $student->date_of_birth ? $student->date_of_birth->format('F j, Y') : 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Place of Birth:</strong></td>
                                    <td>{{ $student->place_of_birth ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nationality:</strong></td>
                                    <td>{{ $student->nationality ?? 'Not provided' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Religion:</strong></td>
                                    <td>{{ $student->religion ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Civil Status:</strong></td>
                                    <td>
                                        @if($student->civil_status)
                                            <span class="badge bg-info">{{ $student->civil_status }}</span>
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>
                                        <a href="mailto:{{ $student->email }}" class="text-decoration-none">
                                            {{ $student->email }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Contact Number:</strong></td>
                                    <td>
                                        @if($student->contact_number)
                                            <a href="tel:{{ $student->contact_number }}" class="text-decoration-none">
                                                {{ $student->contact_number }}
                                            </a>
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td>{{ $student->address ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>LRN:</strong></td>
                                    <td>{{ $student->lrn ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Registered:</strong></td>
                                    <td>{{ $student->created_at->format('M d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-graduation-cap me-2 text-success"></i>
                        Academic Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Student ID:</strong></td>
                                    <td><span class="badge bg-primary">{{ $student->student_id }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Grade Level:</strong></td>
                                    <td><span class="badge bg-info">{{ $student->grade_level }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Section:</strong></td>
                                    <td>
                                        @if($student->section)
                                            <span class="badge bg-secondary">{{ $student->section }}</span>
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Track:</strong></td>
                                    <td>
                                        @if($student->track)
                                            <span class="badge bg-warning text-dark">{{ $student->track }}</span>
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Cluster:</strong></td>
                                    <td>
                                        @if($student->cluster)
                                            <span class="badge bg-light text-dark">{{ $student->cluster }}</span>
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Enrollment Status:</strong></td>
                                    <td>
                                        @if($totalSubjects > 0)
                                            <span class="badge bg-success">Enrolled ({{ $totalSubjects }} subjects)</span>
                                        @else
                                            <span class="badge bg-danger">Not Enrolled</span>
                                        @endif
                                    </td>
                                </tr>
                                @if(isset($yearlyRecord))
                                <tr>
                                    <td><strong>Yearly Record:</strong></td>
                                    <td>
                                        <span class="badge bg-info">{{ $yearlyRecord->school_year }}</span>
                                        <span class="badge bg-secondary">{{ $yearlyRecord->grade_level }}</span>
                                        @if($yearlyRecord->section)
                                            <span class="badge bg-success">{{ $yearlyRecord->section }}</span>
                                        @endif
                                        @if($yearlyRecord->status)
                                            <span class="badge bg-{{ $yearlyRecord->status === 'enrolled' ? 'success' : ($yearlyRecord->status === 'graduated' ? 'primary' : 'secondary') }}">{{ ucfirst($yearlyRecord->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Grades for Selected School Year -->
            @if(($student->subjects->count() > 0) || (isset($gradesBySubject) && $gradesBySubject->count() > 0))
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2 text-primary"></i>
                        Grades @if($schoolYear) <small class="text-muted">{{ $schoolYear }}</small>@endif
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Q1</th>
                                    <th>Q2</th>
                                    <th>Q3</th>
                                    <th>Q4</th>
                                    <th>Final</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->subjects as $subject)
                                @php
                                    $gradeModel = isset($gradesBySubject) ? $gradesBySubject->get($subject->id) : null;
                                    $pivotGrade = isset($subject->pivot) ? $subject->pivot->grade : null;
                                    $final = $gradeModel->final_grade ?? $pivotGrade ?? null;
                                @endphp
                                <tr>
                                    <td><span class="badge bg-primary">{{ $subject->code ?? $subject->subject_code }}</span></td>
                                    <td><strong>{{ $subject->name ?? $subject->subject_name }}</strong></td>
                                    <td>{{ $gradeModel->quarter1 ?? '—' }}</td>
                                    <td>{{ $gradeModel->quarter2 ?? '—' }}</td>
                                    <td>{{ $gradeModel->quarter3 ?? '—' }}</td>
                                    <td>{{ $gradeModel->quarter4 ?? '—' }}</td>
                                    <td>
                                        @if($final !== null)
                                            <span class="badge bg-{{ $final >= 75 ? 'success' : 'danger' }}">{{ number_format($final, 1) }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $gradeModel->remarks ?? ($subject->pivot->remarks ?? '—') }}</td>
                                    <td>
                                        @if($final !== null)
                                            <span class="badge bg-{{ $final >= 75 ? 'success' : 'danger' }}">{{ $final >= 75 ? 'Passed' : 'Failed' }}</span>
                                        @else
                                            <span class="badge bg-secondary">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Parent/Guardian Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2 text-info"></i>
                        Parent/Guardian Information
                    </h5>
                </div>
                <div class="card-body">
                    @if($student->parent_name || $student->parent_contact)
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Parent/Guardian Name:</strong></td>
                                        <td>{{ $student->parent_name ?? 'Not provided' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Contact Number:</strong></td>
                                        <td>
                                            @if($student->parent_contact)
                                                <a href="tel:{{ $student->parent_contact }}" class="text-decoration-none">
                                                    {{ $student->parent_contact }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-users fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No parent/guardian information provided.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Enrolled Subjects -->
            @if($student->subjects->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-book-open me-2 text-warning"></i>
                        Enrolled Subjects
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Grade Level</th>
                                    <th>Units</th>
                                    <th>Current Grade</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->subjects as $subject)
                                @php
                                    // Try to get grade from pivot table first, then from grades table
                                    $gradeValue = null;
                                    if (isset($subject->pivot) && $subject->pivot->grade) {
                                        $gradeValue = $subject->pivot->grade;
                                    } else {
                                        $grade = $student->grades->where('subject_id', $subject->id)->first();
                                        if ($grade) {
                                            $gradeValue = $grade->final_grade ?? $grade->quarter4 ?? $grade->quarter3 ?? $grade->quarter2 ?? $grade->quarter1;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td><span class="badge bg-primary">{{ $subject->code ?? $subject->subject_code }}</span></td>
                                    <td>
                                        <strong>{{ $subject->name ?? $subject->subject_name }}</strong>
                                        @if($subject->description)
                                            <br><small class="text-muted">{{ Str::limit($subject->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-info">{{ $subject->grade_level }}</span></td>
                                    <td><span class="badge bg-secondary">{{ $subject->units ?? 1 }}</span></td>
                                    <td>
                                        @if($gradeValue)
                                            <span class="badge bg-{{ $gradeValue >= 75 ? 'success' : 'danger' }} fs-6">
                                                {{ number_format($gradeValue, 1) }}
                                            </span>
                                        @else
                                            <span class="text-muted">No grade</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($gradeValue)
                                            @if($gradeValue >= 75)
                                                <span class="badge bg-success">Passed</span>
                                            @else
                                                <span class="badge bg-danger">Failed</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-xl-4">
            <!-- Student Avatar and Quick Info -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="avatar-xl bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                    </div>
                    <h4>{{ $student->full_name }}</h4>
                    <p class="text-muted mb-2">{{ $student->student_id }}</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge bg-info">{{ $student->grade_level }}</span>
                        @if($student->section)
                            <span class="badge bg-success">{{ $student->section }}</span>
                        @endif
                    </div>
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $student->email }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-envelope me-2"></i>Send Email
                        </a>
                        @if($student->contact_number)
                            <a href="tel:{{ $student->contact_number }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-phone me-2"></i>Call Student
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Academic Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-success"></i>
                        Academic Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary">{{ $totalSubjects }}</h4>
                                <small class="text-muted">Total Subjects</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $passedSubjects }}</h4>
                            <small class="text-muted">Passed</small>
                        </div>
                    </div>

                    @if($averageGrade)
                    <hr>
                    <div class="row text-center">
                        <div class="col-12">
                            <h5 class="text-info">{{ number_format($averageGrade, 1) }}</h5>
                            <small class="text-muted">Average Grade</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Enrollment Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2 text-warning"></i>
                        Enrollment Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('registrar.students.enrollment', $student) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-book me-2"></i>Manage Subjects
                        </a>
                        <form action="{{ route('registrar.students.toggle-enrollment', $student) }}" method="POST" class="d-inline">
                            @csrf
                            @if($totalSubjects > 0)
                                <button type="submit" class="btn btn-warning btn-sm w-100"
                                        onclick="return confirm('Are you sure you want to deactivate this student\'s enrollment?')">
                                    <i class="fas fa-pause me-2"></i>Deactivate Enrollment
                                </button>
                            @else
                                <button type="submit" class="btn btn-info btn-sm w-100" disabled>
                                    <i class="fas fa-play me-2"></i>Activate Enrollment
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2 text-secondary"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('registrar.students.edit', $student) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </a>
                        <button class="btn btn-info btn-sm" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print Profile
                        </button>
                        <form action="{{ route('registrar.students.destroy', $student) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this student? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                <i class="fas fa-trash me-2"></i>Delete Student
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar-xl {
    width: 80px;
    height: 80px;
    font-size: 2rem;
    font-weight: 600;
}

.bg-pink {
    background-color: #e91e63 !important;
}

@media print {
    .btn, .card:last-child {
        display: none !important;
    }

    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}
</style>
@endpush
@endsection
