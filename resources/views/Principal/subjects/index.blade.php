@extends('Principal.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-book mr-2"></i>
                        Subject Management
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalSubjects }}</h3>
                                    <p>Total Subjects</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-book"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $assignedSubjects }}</h3>
                                    <p>Assigned Subjects</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $coreSubjects }}</h3>
                                    <p>Core Subjects</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $masterSubjects }}</h3>
                                    <p>Master Subjects</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subjects Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Grade Level</th>
                                    <th>Track</th>
                                    <th>Assigned Teacher</th>
                                    <th>Students Enrolled</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subjects as $subject)
                                <tr>
                                    <td>
                                        <strong>{{ $subject->code }}</strong>
                                    </td>
                                    <td>{{ $subject->name }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $subject->grade_level }}</span>
                                    </td>
                                    <td>
                                        @if($subject->track)
                                            <span class="badge badge-info">{{ $subject->track }}</span>
                                        @else
                                            <span class="text-muted">All Tracks</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subject->teacherAssignments->count() > 0)
                                            @foreach($subject->teacherAssignments as $assignment)
                                                <span class="badge badge-success">{{ $assignment->teacher->name }}</span><br>
                                            @endforeach
                                        @else
                                            <span class="text-danger">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $subject->students_count }}</span>
                                    </td>
                                    <td>
                                        @if($subject->is_core_subject)
                                            <span class="badge badge-warning">Core</span>
                                        @endif
                                        @if($subject->is_master_subject)
                                            <span class="badge badge-danger">Master</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('principal.subjects.show', $subject) }}" 
                                           class="btn btn-sm btn-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <p class="text-muted">No subjects found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $subjects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject Distribution Charts -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Subjects by Grade Level</h3>
                </div>
                <div class="card-body">
                    @if($subjectsByGrade->count() > 0)
                        @foreach($subjectsByGrade as $grade => $count)
                        <div class="progress-group">
                            {{ $grade }}
                            <span class="float-right"><b>{{ $count }}</b>/{{ $totalSubjects }}</span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary" 
                                     style="width: {{ ($count / $totalSubjects) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No grade level data available.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Subjects by Track</h3>
                </div>
                <div class="card-body">
                    @if($subjectsByTrack->count() > 0)
                        @foreach($subjectsByTrack as $track => $count)
                        <div class="progress-group">
                            {{ $track }}
                            <span class="float-right"><b>{{ $count }}</b>/{{ $totalSubjects }}</span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success" 
                                     style="width: {{ ($count / $totalSubjects) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No track data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
