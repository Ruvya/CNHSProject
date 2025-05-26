@extends('layouts.registrar')

@section('title', 'Subject Offerings Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                Subject Offerings Management
            </h1>
            <p class="text-muted mb-0">Manage subject offerings, schedules, and teacher assignments</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('registrar.subject-offerings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create New Offering
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Offerings</h6>
                            <h3 class="mb-0">{{ $stats['total_offerings'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-list-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Active Offerings</h6>
                            <h3 class="mb-0">{{ $stats['active_offerings'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Enrolled</h6>
                            <h3 class="mb-0">{{ $stats['total_enrolled'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Capacity</h6>
                            <h3 class="mb-0">{{ $stats['total_capacity'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chair fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-filter me-2"></i>Filter Offerings
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('registrar.subject-offerings.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="school_year" class="form-label">School Year</label>
                        <select name="school_year" id="school_year" class="form-select">
                            @foreach($availableSchoolYears as $year)
                                <option value="{{ $year }}" {{ $schoolYear === $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="grading" class="form-label">Grading</label>
                        <select name="grading" id="grading" class="form-select">
                            <option value="">All Gradings</option>
                            @foreach($availableGradings as $gradingOption)
                                <option value="{{ $gradingOption }}" {{ $grading === $gradingOption ? 'selected' : '' }}>
                                    {{ $gradingOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="grade_level" class="form-label">Grade Level</label>
                        <select name="grade_level" id="grade_level" class="form-select">
                            <option value="">All Grade Levels</option>
                            @foreach($availableGradeLevels as $grade)
                                <option value="{{ $grade }}" {{ $gradeLevel === $grade ? 'selected' : '' }}>
                                    {{ $grade }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('registrar.subject-offerings.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Offerings Table -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-table me-2"></i>Subject Offerings
                @if($offerings->count() > 0)
                    <span class="badge bg-primary ms-2">{{ $offerings->count() }} offerings</span>
                @endif
            </h6>
        </div>
        <div class="card-body">
            @if($offerings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Subject</th>
                                <th>Track</th>
                                <th>Teacher</th>
                                <th>Schedule</th>
                                <th>Enrollment</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offerings as $offering)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $offering->subject->code }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $offering->subject->name }}</small>
                                            <br>
                                            <span class="badge bg-info">{{ $offering->grade_level }}</span>
                                            <span class="badge bg-secondary">{{ $offering->grading }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $offering->track }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $offering->school_year }}</small>
                                    </td>
                                    <td>
                                        @if($offering->teacher)
                                            <span class="text-success">
                                                <i class="fas fa-user-check me-1"></i>
                                                {{ $offering->teacher->name }}
                                            </span>
                                        @else
                                            <span class="text-warning">
                                                <i class="fas fa-user-times me-1"></i>
                                                Not Assigned
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($offering->schedules->count() > 0)
                                            @foreach($offering->schedules as $schedule)
                                                <div class="mb-1">
                                                    <small class="badge bg-light text-dark">
                                                        {{ $schedule->day_of_week }} {{ $schedule->time_range }}
                                                    </small>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-muted">No schedule set</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                @php
                                                    $percentage = $offering->max_students > 0 ? ($offering->enrolled_students / $offering->max_students) * 100 : 0;
                                                @endphp
                                                <div class="progress-bar
                                                    @if($percentage >= 90) bg-danger
                                                    @elseif($percentage >= 70) bg-warning
                                                    @else bg-success
                                                    @endif"
                                                    style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <small class="text-muted">
                                                {{ $offering->enrolled_students }}/{{ $offering->max_students }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($offering->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($offering->status === 'inactive')
                                            <span class="badge bg-secondary">Inactive</span>
                                        @else
                                            <span class="badge bg-danger">Full</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('registrar.subject-offerings.show', $offering) }}"
                                               class="btn btn-outline-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('registrar.subject-offerings.edit', $offering) }}"
                                               class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger"
                                                    onclick="confirmDelete({{ $offering->id }})" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Subject Offerings Found</h5>
                    <p class="text-muted">Start by creating your first subject offering for the current school year.</p>
                    <a href="{{ route('registrar.subject-offerings.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create First Offering
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this subject offering? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(offeringId) {
    const form = document.getElementById('deleteForm');
    form.action = `/registrar/subject-offerings/${offeringId}`;

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush
