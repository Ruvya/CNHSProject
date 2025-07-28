@extends('Principal.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>
                        Subject Assignment Management
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $stats['total_assignments'] }}</h3>
                                    <p>Total Assignments</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-tasks"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $stats['unique_teachers'] }}</h3>
                                    <p>Active Teachers</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $stats['unique_subjects'] }}</h3>
                                    <p>Assigned Subjects</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-book"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $stats['unassigned_subjects'] }}</h3>
                                    <p>Unassigned Subjects</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <form method="GET" action="{{ route('principal.subject-assignments.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Teacher</label>
                                            <select name="teacher_id" class="form-control">
                                                <option value="">All Teachers</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}" 
                                                        {{ $selectedTeacher == $teacher->id ? 'selected' : '' }}>
                                                        {{ $teacher->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Subject</label>
                                            <select name="subject_id" class="form-control">
                                                <option value="">All Subjects</option>
                                                @foreach($subjects as $subject)
                                                    <option value="{{ $subject->id }}" 
                                                        {{ $selectedSubject == $subject->id ? 'selected' : '' }}>
                                                        {{ $subject->code }} - {{ $subject->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Grade Level</label>
                                            <select name="grade_level" class="form-control">
                                                <option value="">All Grades</option>
                                                <option value="Grade 11" {{ $selectedGradeLevel == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                                <option value="Grade 12" {{ $selectedGradeLevel == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Track</label>
                                            <select name="track" class="form-control">
                                                <option value="">All Tracks</option>
                                                <option value="Academic" {{ $selectedTrack == 'Academic' ? 'selected' : '' }}>Academic</option>
                                                <option value="TVL" {{ $selectedTrack == 'TVL' ? 'selected' : '' }}>TVL</option>
                                                <option value="Sports" {{ $selectedTrack == 'Sports' ? 'selected' : '' }}>Sports</option>
                                                <option value="Arts and Design" {{ $selectedTrack == 'Arts and Design' ? 'selected' : '' }}>Arts and Design</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-search"></i> Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Assignments Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Teacher</th>
                                    <th>Subject</th>
                                    <th>Grade Level</th>
                                    <th>Track</th>
                                    <th>School Year</th>
                                    <th>Grading Period</th>
                                    <th>Assignment Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $assignment)
                                <tr>
                                    <td>
                                        <strong>{{ $assignment->teacher->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $assignment->teacher->email }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $assignment->subject->code }}</strong>
                                        <br>
                                        {{ $assignment->subject->name }}
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $assignment->subject->grade_level }}</span>
                                    </td>
                                    <td>
                                        @if($assignment->subject->track)
                                            <span class="badge badge-info">{{ $assignment->subject->track }}</span>
                                        @else
                                            <span class="text-muted">All Tracks</span>
                                        @endif
                                    </td>
                                    <td>{{ $assignment->school_year }}</td>
                                    <td>{{ $assignment->grading_period }}</td>
                                    <td>{{ $assignment->assignment_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $assignment->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($assignment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('principal.subject-assignments.show', $assignment) }}" 
                                           class="btn btn-sm btn-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <p class="text-muted">No subject assignments found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $assignments->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
