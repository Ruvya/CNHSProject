    @extends('layouts.admin')

@section('title', 'Student Profile')

@section('content')
<!-- Page Header -->
<div class="card mb-4 shadow-sm border-0 bg-light position-relative">
    <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between py-4 px-4">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-xl bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; font-size: 2.5rem; font-weight: 700;">
                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
            </div>
            <div>
                <h1 class="mb-1 fw-bold" style="font-size: 2.2rem; letter-spacing: -1px;">{{ $student->full_name }}</h1>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <span class="badge bg-primary fs-6"><i class="fas fa-id-card me-1"></i> {{ $student->student_id }}</span>
                    <span class="badge bg-info text-dark fs-6"><i class="fas fa-layer-group me-1"></i> {{ $student->grade_level }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column align-items-end gap-2 mt-3 mt-md-0">
            <a href="{{ route('admin.users.students.index') }}" class="btn btn-outline-secondary px-3 mb-1">
                <i class="fas fa-arrow-left me-2"></i>Back to Student Accounts
            </a>
            @if($student->is_temporary_account)
                <span class="badge bg-warning fs-6 align-self-end">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Profile Incomplete - Student needs to complete profile
                </span>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <!-- Main Content -->
    <div class="col-xl-12">
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
                                    @php
                                        $genderValue = is_string($student->gender) ? strtolower(trim($student->gender)) : null;
                                    @endphp
                                    @if($genderValue === 'male' || $genderValue === 'm')
                                        <span class=>Male</span>
                                    @elseif($genderValue === 'female' || $genderValue === 'f')
                                        <span class=>Female</span>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
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
                                <td><strong>Registered:</strong></td>
                                <td>{{ $student->created_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ $student->updated_at->format('M d, Y') }}</td>
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
                                <td><span class=>{{ $student->student_id }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Grade Level:</strong></td>
                                <td><span class=>{{ $student->grade_level }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Track:</strong></td>
                                <td>
                                    @if($student->track)
                                        <span class=>{{ $student->track }}</span>
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
                                <td><strong>Cluster:</strong></td>
                                <td>
                                    @if($student->cluster)
                                        <span class=>{{ $student->cluster }}</span>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Section:</strong></td>
                                <td>
                                    @if($student->section)
                                        <span class=>{{ $student->section }}</span>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

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

        <!-- Academic Performance -->
        @if($student->subjects->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2 text-warning"></i>
                    Academic Performance
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
        {{-- Removed the card with avatar, name, student ID, grade, section, and Send Email/Call Student buttons as requested --}}

        <!-- Recent Grades -->
        @if($recentGrades->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2 text-warning"></i>
                    Recent Grades
                </h5>
            </div>
            <div class="card-body">
                @foreach($recentGrades as $recentGrade)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>{{ $recentGrade->subject->name ?? $recentGrade->subject->subject_name ?? 'Unknown Subject' }}</strong>
                        <br><small class="text-muted">{{ $recentGrade->created_at ? $recentGrade->created_at->format('M d, Y') : 'No date' }}</small>
                    </div>
                    <span class="badge bg-{{ $recentGrade->grade >= 75 ? 'success' : 'danger' }} fs-6">
                        {{ number_format($recentGrade->grade, 1) }}
                    </span>
                </div>
                @if(!$loop->last)<hr class="my-2">@endif
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Password Reset Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">
                    <i class="fas fa-key me-2"></i>Reset Student Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.students.reset-password', $student) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Student:</strong> {{ $student->full_name }} ({{ $student->student_id }})
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                        <div class="form-text">Password must be at least 8 characters long.</div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-key me-2"></i>Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

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
    .page-actions,
    .btn,
    .card:last-child {
        display: none !important;
    }

    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}
</style>
@endpush
