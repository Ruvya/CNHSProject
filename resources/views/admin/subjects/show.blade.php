@extends('layouts.admin')

@section('title', 'Subject Details')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">{{ $subject->name }}</h1>
    <p class="page-subtitle">Subject details and enrolled students</p>
    <div class="page-actions">
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Subjects
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Subject Information -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-book me-2 text-primary"></i>
                    Subject Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Subject Code:</strong></td>
                                <td><span class="badge bg-primary">{{ $subject->code }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Subject Name:</strong></td>
                                <td>{{ $subject->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Grade Level:</strong></td>
                                <td><span class="badge bg-info">{{ $subject->grade_level }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Units:</strong></td>
                                <td><span class="badge bg-success">{{ $subject->units }} {{ Str::plural('unit', $subject->units) }}</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Track:</strong></td>
                                <td>
                                    @if($subject->track)
                                        <span class="badge bg-warning text-dark">{{ $subject->track }}</span>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Strand:</strong></td>
                                <td>
                                    @if($subject->strand)
                                        <span class="badge bg-secondary">{{ $subject->strand }}</span>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $subject->created_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ $subject->updated_at->format('M d, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($subject->description)
                <div class="mt-3">
                    <h6>Description:</h6>
                    <p class="text-muted">{{ $subject->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Enrolled Students -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2 text-success"></i>
                    Enrolled Students ({{ $enrolledStudents->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($enrolledStudents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover" id="studentsTable">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Grade Level</th>
                                    <th>Track</th>
                                    <th>Section</th>
                                    <th>Current Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($enrolledStudents as $student)
                                <tr>
                                    <td>{{ $student->student_id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success text-white rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <strong>{{ $student->full_name }}</strong>
                                                <br><small class="text-muted">{{ $student->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student->grade_level }}</td>
                                    <td>{{ $student->track ?? '-' }}</td>
                                    <td>{{ $student->section ?? '-' }}</td>
                                    <td>
                                        @php
                                            $grade = $student->grades->where('subject_id', $subject->id)->first();
                                        @endphp
                                        @if($grade)
                                            <span class="badge bg-{{ $grade->grade >= 75 ? 'success' : 'danger' }}">
                                                {{ $grade->grade }}
                                            </span>
                                        @else
                                            <span class="text-muted">No grade</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No students enrolled</h6>
                        <p class="text-muted">This subject doesn't have any enrolled students yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Teacher Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chalkboard-teacher me-2 text-warning"></i>
                    Assigned Teacher
                </h5>
            </div>
            <div class="card-body">
                @if($subject->teacher)
                    <div class="text-center">
                        <div class="avatar-lg bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                            {{ substr($subject->teacher->name, 0, 2) }}
                        </div>
                        <h6>{{ $subject->teacher->name }}</h6>
                        <p class="text-muted mb-2">{{ $subject->teacher->email }}</p>
                        @if($subject->teacher->contact_number)
                            <p class="text-muted mb-2">
                                <i class="fas fa-phone me-1"></i>{{ $subject->teacher->contact_number }}
                            </p>
                        @endif
                        @if($subject->teacher->strand)
                            <span class="badge bg-info">{{ $subject->teacher->strand }}</span>
                        @endif
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No Teacher Assigned</h6>
                        <p class="text-muted mb-3">This subject needs a teacher assignment.</p>
                        <p class="text-muted small">Teacher assignments can be managed by the Registrar.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2 text-info"></i>
                    Quick Stats
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary">{{ $enrolledStudents->count() }}</h4>
                            <small class="text-muted">Enrolled Students</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $subject->units }}</h4>
                        <small class="text-muted">Credit Units</small>
                    </div>
                </div>

                @if($enrolledStudents->count() > 0)
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            @php
                                $passedStudents = $enrolledStudents->filter(function($student) use ($subject) {
                                    $grade = $student->grades->where('subject_id', $subject->id)->first();
                                    return $grade && $grade->grade >= 75;
                                })->count();
                            @endphp
                            <h5 class="text-success">{{ $passedStudents }}</h5>
                            <small class="text-muted">Passing</small>
                        </div>
                    </div>
                    <div class="col-6">
                        @php
                            $failedStudents = $enrolledStudents->filter(function($student) use ($subject) {
                                $grade = $student->grades->where('subject_id', $subject->id)->first();
                                return $grade && $grade->grade < 75;
                            })->count();
                        @endphp
                        <h5 class="text-danger">{{ $failedStudents }}</h5>
                        <small class="text-muted">Failing</small>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables for Students
    $('#studentsTable').DataTable({
        "pageLength": 10,
        "order": [[ 1, "asc" ]],
        "language": {
            "search": "Search students:",
            "lengthMenu": "Show _MENU_ students per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ students",
            "emptyTable": "No students enrolled"
        }
    });
});
</script>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 0.875rem;
    font-weight: 600;
}

.avatar-lg {
    width: 64px;
    height: 64px;
    font-size: 1.5rem;
    font-weight: 600;
}
</style>
@endpush
