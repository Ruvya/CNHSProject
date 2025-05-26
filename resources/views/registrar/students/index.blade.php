@extends('layouts.registrar')

@section('title', 'Student Records Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Student Records Management</h1>
            <p class="text-muted">Comprehensive student enrollment and academic tracking system</p>
        </div>
        <div>
            <a href="{{ route('registrar.students.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Student
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

    <!-- Advanced Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-filter me-2"></i>Advanced Filters
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('registrar.students.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Name, Student ID, Email...">
                    </div>
                    <div class="col-md-2">
                        <label for="grade_level" class="form-label">Grade Level</label>
                        <select class="form-select" id="grade_level" name="grade_level">
                            <option value="">All Grades</option>
                            @foreach($gradeLevels as $grade)
                                <option value="{{ $grade }}" {{ request('grade_level') === $grade ? 'selected' : '' }}>
                                    {{ $grade }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="section" class="form-label">Section</label>
                        <select class="form-select" id="section" name="section">
                            <option value="">All Sections</option>
                            @foreach($sections as $section)
                                <option value="{{ $section }}" {{ request('section') === $section ? 'selected' : '' }}>
                                    {{ $section }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="track" class="form-label">Track</label>
                        <select class="form-select" id="track" name="track">
                            <option value="">All Tracks</option>
                            @foreach($tracks as $track)
                                <option value="{{ $track }}" {{ request('track') === $track ? 'selected' : '' }}>
                                    {{ $track }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="strand" class="form-label">Strand</label>
                        <select class="form-select" id="strand" name="strand">
                            <option value="">All Strands</option>
                            @foreach($strands as $strand)
                                <option value="{{ $strand }}" {{ request('strand') === $strand ? 'selected' : '' }}>
                                    {{ $strand }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label for="enrollment_status" class="form-label">Status</label>
                        <select class="form-select" id="enrollment_status" name="enrollment_status">
                            <option value="">All</option>
                            <option value="enrolled" {{ request('enrollment_status') === 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                            <option value="not_enrolled" {{ request('enrollment_status') === 'not_enrolled' ? 'selected' : '' }}>Not Enrolled</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Apply Filters
                        </button>
                        <a href="{{ route('registrar.students.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $students->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Enrolled Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $students->filter(function($student) { return $student->subjects->count() > 0; })->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Grade 11 Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $students->filter(function($student) { return $student->grade_level === 'Grade 11'; })->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Grade 12 Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $students->filter(function($student) { return $student->grade_level === 'Grade 12'; })->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Student Records</h6>
            <div>
                <button class="btn btn-sm btn-outline-primary" onclick="toggleBulkActions()">
                    <i class="fas fa-tasks me-1"></i>Bulk Actions
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Bulk Actions Panel (Hidden by default) -->
            <div id="bulkActionsPanel" class="alert alert-info" style="display: none;">
                <form action="{{ route('registrar.students.bulk-action') }}" method="POST" id="bulkActionForm">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="bulk_action" class="form-label">Action</label>
                            <select class="form-select" id="bulk_action" name="action" required>
                                <option value="">Select Action</option>
                                <option value="transfer_section">Transfer Section</option>
                                <option value="delete">Delete Students</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="newSectionField" style="display: none;">
                            <label for="new_section" class="form-label">New Section</label>
                            <input type="text" class="form-control" id="new_section" name="new_section" placeholder="Enter new section">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to perform this action?')">
                                <i class="fas fa-play me-1"></i>Execute
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="toggleBulkActions()">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="studentsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="30">
                                <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes()">
                            </th>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Grade Level</th>
                            <th>Section</th>
                            <th>Track/Strand</th>
                            <th>Enrollment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td>
                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="student-checkbox">
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $student->student_id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-success text-white rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('registrar.students.show', $student) }}" class="text-decoration-none">
                                            <strong class="text-primary">{{ $student->full_name }}</strong>
                                        </a>
                                        @if($student->middle_name)
                                            <br><small class="text-muted">{{ $student->middle_name }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $student->email }}</td>
                            <td>
                                <span class="badge bg-info">{{ $student->grade_level }}</span>
                            </td>
                            <td>
                                @if($student->section)
                                    <span class="badge bg-secondary">{{ $student->section }}</span>
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                @if($student->track)
                                    <span class="badge bg-warning text-dark">{{ $student->track }}</span>
                                    @if($student->strand)
                                        <br><span class="badge bg-light text-dark">{{ $student->strand }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                @if($student->subjects->count() > 0)
                                    <span class="badge bg-success">Enrolled ({{ $student->subjects->count() }} subjects)</span>
                                @else
                                    <span class="badge bg-danger">Not Enrolled</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('registrar.students.show', $student) }}" class="btn btn-info btn-sm" title="View Profile">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('registrar.students.edit', $student) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('registrar.students.enrollment', $student) }}" class="btn btn-success btn-sm" title="Manage Enrollment">
                                        <i class="fas fa-book"></i>
                                    </a>
                                    <form action="{{ route('registrar.students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this student?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">No students found</h6>
                                <p class="text-muted">Try adjusting your filters or add new students.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($students->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $students->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 0.875rem;
    font-weight: 600;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}
</style>
@endpush

@push('scripts')
<script>
function toggleBulkActions() {
    const panel = document.getElementById('bulkActionsPanel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}

function toggleAllCheckboxes() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.student-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

// Show/hide new section field based on action selection
document.getElementById('bulk_action').addEventListener('change', function() {
    const newSectionField = document.getElementById('newSectionField');
    if (this.value === 'transfer_section') {
        newSectionField.style.display = 'block';
        document.getElementById('new_section').required = true;
    } else {
        newSectionField.style.display = 'none';
        document.getElementById('new_section').required = false;
    }
});

// Collect selected student IDs for bulk actions
document.getElementById('bulkActionForm').addEventListener('submit', function(e) {
    const selectedCheckboxes = document.querySelectorAll('.student-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        e.preventDefault();
        alert('Please select at least one student.');
        return;
    }
    
    // Add hidden inputs for selected student IDs
    selectedCheckboxes.forEach(checkbox => {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'student_ids[]';
        hiddenInput.value = checkbox.value;
        this.appendChild(hiddenInput);
    });
});

// Auto-submit form when filters change
document.querySelectorAll('#filterForm select').forEach(select => {
    select.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});
</script>
@endpush
@endsection
