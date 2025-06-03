@extends('layouts.registrar')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>
                Teacher Assignment Management
                <span class="badge bg-info ms-2">{{ $currentSchoolYear }} - {{ $currentGradingPeriod }}</span>
            </h1>
            <p class="text-muted mb-0">Assign teachers to subjects with schedule management</p>
        </div>
        <div>
            <a href="{{ route('registrar.teacher-assignments.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>Assign Teacher
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">✅ Success!</h5>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle fa-2x text-danger me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">❌ Error!</h5>
                    <p class="mb-0">{{ session('error') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x text-warning me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">⚠️ Warning!</h5>
                    <p class="mb-0">{{ session('warning') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('assignment_details'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle fa-2x text-info me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">📋 Assignment Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Teacher:</strong> {{ session('assignment_details')['teacher_name'] ?? 'N/A' }}<br>
                            <strong>Subject:</strong> {{ session('assignment_details')['subject_name'] ?? 'N/A' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Grade Level:</strong> {{ session('assignment_details')['grade_level'] ?? 'N/A' }}<br>
                            <strong>Track:</strong> {{ session('assignment_details')['track'] ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Teachers</h5>
                            <h3 class="mb-0">{{ $stats['total_teachers'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
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
                            <h5 class="card-title">Assigned Teachers</h5>
                            <h3 class="mb-0">{{ $stats['assigned_teachers'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-check fa-2x"></i>
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
                            <h5 class="card-title">Total Subjects</h5>
                            <h3 class="mb-0">{{ $stats['total_subjects'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x"></i>
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
                            <h5 class="card-title">Assigned Subjects</h5>
                            <h3 class="mb-0">{{ $stats['assigned_subjects'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book-open fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filters</h5>
        </div>
        <div class="card-body">
            <form id="filterForm" method="GET" action="{{ route('registrar.teacher-assignments.index') }}">
                <div class="row">
                    <div class="col-md-2">
                        <label for="grading_period" class="form-label">Grading Period</label>
                        <select name="grading_period" id="grading_period" class="form-select">
                            <option value="First Grading" {{ $currentGradingPeriod == 'First Grading' ? 'selected' : '' }}>First Grading</option>
                            <option value="Second Grading" {{ $currentGradingPeriod == 'Second Grading' ? 'selected' : '' }}>Second Grading</option>
                            <option value="Third Grading" {{ $currentGradingPeriod == 'Third Grading' ? 'selected' : '' }}>Third Grading</option>
                            <option value="Fourth Grading" {{ $currentGradingPeriod == 'Fourth Grading' ? 'selected' : '' }}>Fourth Grading</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="grade_level" class="form-label">Grade Level</label>
                        <select name="grade_level" id="grade_level" class="form-select">
                            <option value="">All Grades</option>
                            <option value="Grade 11" {{ $gradeLevel == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                            <option value="Grade 12" {{ $gradeLevel == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="track" class="form-label">Track</label>
                        <select name="track" id="track" class="form-select">
                            <option value="">All Tracks</option>
                            <option value="Academic Track" {{ $track == 'Academic Track' ? 'selected' : '' }}>Academic Track</option>
                            <option value="TVL" {{ $track == 'TVL' ? 'selected' : '' }}>TVL</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="teacher_id" class="form-label">Teacher</label>
                        <select name="teacher_id" id="teacher_id" class="form-select">
                            <option value="">All Teachers</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $teacherId == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control"
                               placeholder="Teacher or Subject" value="{{ $search }}">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Assignments Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Teacher Assignments</h5>
        </div>
        <div class="card-body">
            @if($assignments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Teacher</th>
                                <th>Subject</th>
                                <th>Grade/Track</th>
                                <th>Schedule</th>
                                <th>Assignment Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignments as $assignment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                @if($assignment->teacher)
                                                    <strong>{{ $assignment->teacher->name }}</strong>
                                                    <br><small class="text-muted">{{ $assignment->teacher->email }}</small>
                                                @else
                                                    <span class="text-danger">Teacher not found</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($assignment->subject)
                                            <strong>{{ $assignment->subject->name }}</strong>
                                            <br><small class="text-muted">{{ $assignment->subject->code }}</small>
                                        @else
                                            <span class="text-danger">Subject not found</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($assignment->subject)
                                            {{ $assignment->subject->grade_level }}
                                            <br><small class="text-muted">{{ $assignment->subject->track }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($assignment->schedule)
                                            <small>{{ $assignment->formatted_schedule }}</small>
                                        @else
                                            <span class="text-muted">No schedule set</span>
                                        @endif
                                    </td>
                                    <td>{{ $assignment->assignment_date->format('M d, Y') }}</td>
                                    <td>
                                        @if($assignment->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($assignment->status == 'inactive')
                                            <span class="badge bg-danger">Inactive</span>
                                        @else
                                            <span class="badge bg-secondary">Completed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('registrar.teacher-assignments.show', $assignment) }}"
                                               class="btn btn-sm btn-outline-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('registrar.teacher-assignments.edit', $assignment) }}"
                                               class="btn btn-sm btn-outline-warning" title="Edit Assignment">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('registrar.teacher-assignments.destroy', $assignment) }}"
                                                  class="d-inline" onsubmit="return confirm('Are you sure you want to remove this assignment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Assignment">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $assignments->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No teacher assignments found</h5>
                    <p class="text-muted">Start by assigning teachers to subjects for the current term.</p>
                    <a href="{{ route('registrar.teacher-assignments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Assign First Teacher
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit filter form when any filter changes
    const filterForm = document.getElementById('filterForm');
    const filterInputs = filterForm.querySelectorAll('select, input[type="text"]');

    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            filterForm.submit();
        });
    });

    // Handle search input with debounce
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterForm.submit();
            }, 500); // 500ms delay
        });
    }
});

// Auto-hide success alerts after 8 seconds
setTimeout(function() {
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        const bsAlert = new bootstrap.Alert(successAlert);
        bsAlert.close();
    }
}, 8000);

// Auto-hide info alerts after 10 seconds
setTimeout(function() {
    const infoAlert = document.querySelector('.alert-info');
    if (infoAlert) {
        const bsAlert = new bootstrap.Alert(infoAlert);
        bsAlert.close();
    }
}, 10000);

// Add smooth scroll to top when page loads with success message
if (document.querySelector('.alert-success')) {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
@endsection
