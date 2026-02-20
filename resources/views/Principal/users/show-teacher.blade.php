@extends('Principal.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>
                        Teacher Details: {{ $teacher->name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('principal.users') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Users
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Teacher Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Personal Information</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">Full Name:</th>
                                            <td><strong>{{ $teacher->name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $teacher->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Contact Number:</th>
                                            <td>{{ $teacher->contact_number ?? 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address:</th>
                                            <td>{{ $teacher->address ?? 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Track:</th>
                                            <td>
                                                @if($teacher->track)
                                                    <span class="badge badge-info">{{ $teacher->track }}</span>
                                                @else
                                                    <span class="text-muted">Not assigned</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Cluster:</th>
                                            <td>{{ $teacher->cluster ?? 'Not assigned' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                <span class="badge badge-{{ $teacher->status == 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($teacher->status ?? 'active') }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Date Joined:</th>
                                            <td>{{ $teacher->created_at ? $teacher->created_at->format('M d, Y') : 'Not available' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Login:</th>
                                            <td>
                                                @if($teacher->last_login_at)
                                                    {{ $teacher->last_login_at->format('M d, Y H:i') }}
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

                        <!-- Teaching Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Teaching Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>{{ $totalAssignments }}</h3>
                                                    <p>Subject Assignments</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-tasks"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small-box bg-success">
                                                <div class="inner">
                                                    <h3>{{ $totalStudents }}</h3>
                                                    <p>Total Students</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-users"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Current Subject Assignments -->
                                    @if($teacher->teacherAssignments->where('status', 'active')->count() > 0)
                                    <h5>Current Subject Assignments</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Subject Code</th>
                                                    <th>Subject Name</th>
                                                    <th>Grade Level</th>
                                                    <th>School Year</th>
                                                    <th>Grading Period</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($teacher->teacherAssignments->where('status', 'active') as $assignment)
                                                <tr>
                                                    <td><strong>{{ $assignment->subject->code }}</strong></td>
                                                    <td>{{ $assignment->subject->name }}</td>
                                                    <td>
                                                        <span class="badge badge-primary">{{ $assignment->subject->grade_level }}</span>
                                                    </td>
                                                    <td>{{ $assignment->school_year }}</td>
                                                    <td>{{ $assignment->grading_period }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <p class="text-muted">No current subject assignments.</p>
                                    @endif

                                    <!-- Direct Subject Assignments (Legacy) -->
                                    @if($teacher->subjects->count() > 0)
                                    <h5 class="mt-3">Direct Subject Assignments</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Subject Code</th>
                                                    <th>Subject Name</th>
                                                    <th>Grade Level</th>
                                                    <th>Students Enrolled</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($teacher->subjects as $subject)
                                                <tr>
                                                    <td><strong>{{ $subject->code }}</strong></td>
                                                    <td>{{ $subject->name }}</td>
                                                    <td>
                                                        <span class="badge badge-primary">{{ $subject->grade_level }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-secondary">{{ $subject->students->count() }}</span>
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

                    <!-- Assignment History -->
                    @if($teacher->teacherAssignments->count() > 0)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Assignment History</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Grade Level</th>
                                                    <th>School Year</th>
                                                    <th>Grading Period</th>
                                                    <th>Assignment Date</th>
                                                    <th>Status</th>
                                                    <th>Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($teacher->teacherAssignments->sortByDesc('assignment_date') as $assignment)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $assignment->subject->code }}</strong><br>
                                                        <small>{{ $assignment->subject->name }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-primary">{{ $assignment->subject->grade_level }}</span>
                                                    </td>
                                                    <td>{{ $assignment->school_year }}</td>
                                                    <td>{{ $assignment->grading_period }}</td>
                                                    <td>{{ $assignment->assignment_date->format('M d, Y') }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $assignment->status == 'active' ? 'success' : 'secondary' }}">
                                                            {{ ucfirst($assignment->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $assignment->notes ?? '-' }}</td>
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
