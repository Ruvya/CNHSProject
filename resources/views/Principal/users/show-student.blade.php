@extends('Principal.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-graduate mr-2"></i>
                        Student Details: {{ $student->first_name }} {{ $student->last_name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('principal.users') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Users
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Student Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Personal Information</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">Student ID:</th>
                                            <td><strong>{{ $student->student_id }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Full Name:</th>
                                            <td>{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $student->email ?? 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Grade Level:</th>
                                            <td>
                                                <span class="badge badge-primary">{{ $student->grade_level }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Track:</th>
                                            <td>
                                                @if($student->track)
                                                    <span class="badge badge-info">{{ $student->track }}</span>
                                                @else
                                                    <span class="text-muted">Not assigned</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Cluster:</th>
                                            <td>{{ $student->cluster ?? 'Not assigned' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Section:</th>
                                            <td>{{ $student->section ?? 'Not assigned' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth:</th>
                                            <td>{{ $student->date_of_birth ? $student->date_of_birth->format('M d, Y') : 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Contact Number:</th>
                                            <td>{{ $student->contact_number ?? 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address:</th>
                                            <td>{{ $student->address ?? 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Login:</th>
                                            <td>
                                                @if($student->last_login_at)
                                                    {{ $student->last_login_at->format('M d, Y H:i') }}
                                                    <span class="badge badge-success ml-2">Active</span>
                                                @else
                                                    <span class="text-muted">Never logged in</span>
                                                    <span class="badge badge-secondary ml-2">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Academic Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>{{ $totalSubjects }}</h3>
                                                    <p>Enrolled Subjects</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-book"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="small-box bg-success">
                                                <div class="inner">
                                                    <h3>{{ number_format($averageGrade, 2) }}</h3>
                                                    <p>Average Grade</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-chart-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="small-box bg-warning">
                                                <div class="inner">
                                                    <h3>{{ $student->grades->count() }}</h3>
                                                    <p>Total Grades</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Enrolled Subjects -->
                                    @if($student->subjects->count() > 0)
                                    <h5>Enrolled Subjects</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Subject Code</th>
                                                    <th>Subject Name</th>
                                                    <th>Teacher</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($student->subjects as $subject)
                                                <tr>
                                                    <td><strong>{{ $subject->code }}</strong></td>
                                                    <td>{{ $subject->name }}</td>
                                                    <td>
                                                        @if($subject->teacher)
                                                            {{ $subject->teacher->name }}
                                                        @else
                                                            <span class="text-muted">Not assigned</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <p class="text-muted">No subjects enrolled.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grades Section -->
                    @if($student->grades->count() > 0)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Grade Records</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Grade</th>
                                                    <th>Grading Period</th>
                                                    <th>School Year</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($student->grades as $grade)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $grade->subject->code }}</strong><br>
                                                        <small>{{ $grade->subject->name }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-{{ $grade->grade >= 75 ? 'success' : 'danger' }}">
                                                            {{ $grade->grade }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $grade->grading_period ?? 'Not set' }}</td>
                                                    <td>{{ $grade->school_year ?? 'Not set' }}</td>
                                                    <td>{{ $grade->remarks ?? '-' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
