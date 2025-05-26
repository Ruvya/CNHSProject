@extends('layouts.registrar')

@section('title', 'Subject Offering Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-eye me-2 text-primary"></i>
                Subject Offering Details
            </h1>
            <p class="text-muted mb-0">View detailed information about this subject offering</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('registrar.subject-offerings.edit', $subjectOffering) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit Offering
            </a>
            <a href="{{ route('registrar.subject-offerings.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Offerings
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Subject Information -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-book me-2"></i>
                        Subject Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Subject Details</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Subject Code:</strong></td>
                                    <td>{{ $subjectOffering->subject->code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Subject Name:</strong></td>
                                    <td>{{ $subjectOffering->subject->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Units:</strong></td>
                                    <td>{{ $subjectOffering->subject->units }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>{{ $subjectOffering->subject->description ?? 'No description available' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Offering Details</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>School Year:</strong></td>
                                    <td>{{ $subjectOffering->school_year }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Grading:</strong></td>
                                    <td>{{ $subjectOffering->grading }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Grade Level:</strong></td>
                                    <td>{{ $subjectOffering->grade_level }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Track:</strong></td>
                                    <td>{{ $subjectOffering->track }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Class Schedule
                    </h5>
                </div>
                <div class="card-body">
                    @if($subjectOffering->schedules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjectOffering->schedules as $schedule)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ $schedule->day_of_week }}</span>
                                            </td>
                                            <td>{{ $schedule->time_range }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No schedule set for this offering</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Enrolled Students -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        Enrolled Students
                    </h5>
                </div>
                <div class="card-body">
                    @if($subjectOffering->students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Enrollment Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjectOffering->students as $student)
                                        <tr>
                                            <td>{{ $student->student_id }}</td>
                                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                            <td>{{ $student->pivot->enrollment_date ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-success">{{ ucfirst($student->pivot->status ?? 'enrolled') }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-user-times fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No students enrolled yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Teacher Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        Assigned Teacher
                    </h6>
                </div>
                <div class="card-body">
                    @if($subjectOffering->teacher)
                        <div class="text-center">
                            <img src="{{ $subjectOffering->teacher->profile_picture ? asset('storage/' . $subjectOffering->teacher->profile_picture) : asset('images/logo.png') }}"
                                 class="rounded-circle mb-3" width="80" height="80" alt="Teacher">
                            <h6>{{ $subjectOffering->teacher->name }}</h6>
                            <p class="text-muted mb-2">{{ $subjectOffering->teacher->email }}</p>
                            <span class="badge bg-success">{{ ucfirst($subjectOffering->teacher->status) }}</span>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-user-times fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No teacher assigned</p>
                            <a href="{{ route('registrar.subject-offerings.edit', $subjectOffering) }}" class="btn btn-sm btn-warning">
                                Assign Teacher
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Enrollment Statistics -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Enrollment Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ $subjectOffering->enrolled_students }}</h4>
                            <small class="text-muted">Enrolled</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $subjectOffering->max_students }}</h4>
                            <small class="text-muted">Capacity</small>
                        </div>
                    </div>
                    <div class="progress mt-3">
                        <div class="progress-bar" role="progressbar"
                             style="width: {{ $subjectOffering->max_students > 0 ? ($subjectOffering->enrolled_students / $subjectOffering->max_students) * 100 : 0 }}%">
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ $subjectOffering->available_slots }} slots available
                    </small>
                </div>
            </div>

            <!-- Status Information -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Status Information
                    </h6>
                </div>
                <div class="card-body">
                    <p><strong>Status:</strong>
                        @if($subjectOffering->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($subjectOffering->status === 'inactive')
                            <span class="badge bg-secondary">Inactive</span>
                        @else
                            <span class="badge bg-danger">Full</span>
                        @endif
                    </p>
                    <p><strong>Created:</strong> {{ $subjectOffering->created_at->format('M d, Y') }}</p>
                    <p><strong>Last Updated:</strong> {{ $subjectOffering->updated_at->format('M d, Y') }}</p>
                    @if($subjectOffering->notes)
                        <p><strong>Notes:</strong></p>
                        <p class="text-muted">{{ $subjectOffering->notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
