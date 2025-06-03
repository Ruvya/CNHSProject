@extends('layouts.registrar')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-gray-800">
                        <i class="fas fa-eye me-2"></i>Subject Assignment Preview
                    </h1>
                    <p class="text-muted">Preview subjects that will be assigned to {{ $student->first_name }} {{ $student->last_name }}</p>
                </div>
                <div>
                    <a href="{{ route('registrar.automatic-subject-assignment.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Information -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-user me-1"></i>Student Information
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Student ID:</strong></td>
                            <td>{{ $student->student_id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Name:</strong></td>
                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $student->email }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Grade Level:</strong></td>
                            <td><span class="badge bg-primary">{{ $student->grade_level }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Track:</strong></td>
                            <td><span class="badge bg-info">{{ $student->track }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Strand:</strong></td>
                            <td><span class="badge bg-secondary">{{ $student->strand }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Validation Errors -->
    @if(!empty($validationErrors))
        <div class="alert alert-danger" role="alert">
            <h5 class="alert-heading">
                <i class="fas fa-exclamation-triangle me-2"></i>Assignment Issues
            </h5>
            <ul class="mb-0">
                @foreach($validationErrors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Assignment Summary -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-chart-pie me-1"></i>Assignment Summary
            </h6>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <h4 class="text-primary">{{ $preview['core_subjects']->count() }}</h4>
                        <p class="text-muted mb-0">Core Subjects</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <h4 class="text-info">{{ $preview['track_subjects']->count() }}</h4>
                        <p class="text-muted mb-0">Track Subjects</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <h4 class="text-secondary">{{ $preview['strand_subjects']->count() }}</h4>
                        <p class="text-muted mb-0">Strand Subjects</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3 bg-light">
                        <h4 class="text-success">{{ $preview['total_count'] }}</h4>
                        <p class="text-muted mb-0">Total Subjects</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Subjects -->
    @if($preview['core_subjects']->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-star me-1"></i>Core Subjects ({{ $preview['core_subjects']->count() }})
                    <small class="text-muted">- Required for all students</small>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($preview['core_subjects'] as $subject)
                        <div class="col-md-6 mb-3">
                            <div class="card border-primary h-100">
                                <div class="card-body py-2">
                                    <h6 class="card-title mb-1">{{ $subject->code }} - {{ $subject->name }}</h6>
                                    <small class="text-muted">
                                        @if($subject->teacher)
                                            Teacher: {{ $subject->teacher->name }}
                                        @else
                                            <span class="text-warning">No teacher assigned</span>
                                        @endif
                                        <br>Grading: {{ $subject->grading }}
                                        @if($subject->description)
                                            <br>{{ Str::limit($subject->description, 50) }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Track Subjects -->
    @if($preview['track_subjects']->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-road me-1"></i>Track Subjects ({{ $preview['track_subjects']->count() }})
                    <small class="text-muted">- Specific to {{ $student->track }}</small>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($preview['track_subjects'] as $subject)
                        <div class="col-md-6 mb-3">
                            <div class="card border-info h-100">
                                <div class="card-body py-2">
                                    <h6 class="card-title mb-1">{{ $subject->code }} - {{ $subject->name }}</h6>
                                    <small class="text-muted">
                                        @if($subject->teacher)
                                            Teacher: {{ $subject->teacher->name }}
                                        @else
                                            <span class="text-warning">No teacher assigned</span>
                                        @endif
                                        <br>Cluster: {{ $subject->cluster ?? 'Not specified' }}
                                        <br>Grading: {{ $subject->grading }}
                                        @if($subject->description)
                                            <br>{{ Str::limit($subject->description, 50) }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Strand Subjects -->
    @if($preview['strand_subjects']->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-secondary">
                    <i class="fas fa-graduation-cap me-1"></i>Strand Subjects ({{ $preview['strand_subjects']->count() }})
                    <small class="text-muted">- Specific to {{ $student->strand }}</small>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($preview['strand_subjects'] as $subject)
                        <div class="col-md-6 mb-3">
                            <div class="card border-secondary h-100">
                                <div class="card-body py-2">
                                    <h6 class="card-title mb-1">{{ $subject->code }} - {{ $subject->name }}</h6>
                                    <small class="text-muted">
                                        @if($subject->teacher)
                                            Teacher: {{ $subject->teacher->name }}
                                        @else
                                            <span class="text-warning">No teacher assigned</span>
                                        @endif
                                        <br>Specialization: {{ $subject->specialization ?? 'General' }}
                                        <br>Grading: {{ $subject->grading }}
                                        @if($subject->description)
                                            <br>{{ Str::limit($subject->description, 50) }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- No Subjects Message -->
    @if($preview['total_count'] == 0)
        <div class="card shadow mb-4">
            <div class="card-body text-center py-5">
                <i class="fas fa-exclamation-triangle fa-5x text-warning mb-4"></i>
                <h3 class="text-warning">No Subjects Available</h3>
                <p class="text-muted">No subjects are available for this student's track and strand combination.</p>
                <p class="text-muted">Please check if subjects have been created for <strong>{{ $student->track }} - {{ $student->strand }}</strong> in <strong>{{ $student->grade_level }}</strong>.</p>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    @if(empty($validationErrors) && $preview['total_count'] > 0)
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('registrar.automatic-subject-assignment.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Cancel
                    </a>
                    <form method="POST" action="{{ route('registrar.automatic-subject-assignment.assign', $student->id) }}" style="display: inline;">
                        @csrf
                        <input type="hidden" name="school_year" value="{{ date('Y') }}-{{ date('Y') + 1 }}">
                        <button type="submit" class="btn btn-success" onclick="return confirm('Assign {{ $preview['total_count'] }} subjects to {{ $student->first_name }} {{ $student->last_name }}?')">
                            <i class="fas fa-magic me-1"></i>Assign {{ $preview['total_count'] }} Subjects
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
