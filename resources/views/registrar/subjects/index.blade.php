@extends('layouts.registrar')

@section('content')
<div class="container-fluid">
    <!-- Page Header with Clear Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-book me-2 text-primary"></i>
                Subject Management
                <span class="badge bg-success ms-2">Exclusive Access</span>
            </h1>
            <p class="text-muted mb-0">Create, edit, and manage academic subjects with DepEd curriculum structure</p>
        </div>
        <div>
            <a href="{{ route('registrar.subjects.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>Create New Subject
            </a>
        </div>
    </div>

    <!-- Role Information Notice -->
    <div class="alert alert-success border-0 mb-4" style="background: linear-gradient(135deg, #d4edda, #c3e6cb);">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <i class="fas fa-user-check fa-2x text-success"></i>
            </div>
            <div>
                <h6 class="alert-heading mb-1">
                    <i class="fas fa-cogs me-2"></i>Registrar Exclusive Management
                </h6>
                <p class="mb-0">
                    You have exclusive access to create, edit, and delete subjects. Use structured forms with
                    <strong>Grade Level (11-12)</strong>, <strong>Track</strong>, <strong>Strand</strong>,
                    <strong>Cluster</strong>, and <strong>Specialization</strong> aligned with DepEd curriculum.
                </p>
            </div>
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
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Total Subjects</div>
                        <div class="stat-card-value">{{ $totalSubjectsCount ?? 0 }}</div>
                        <div class="stat-card-subtitle">
                            <i class="fas fa-graduation-cap me-1"></i>
                            All grade levels
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-success">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Your Subjects</div>
                        <div class="stat-card-value">{{ $allSubjects->where('registrar_id', auth()->guard('registrar')->id())->count() ?? 0 }}</div>
                        <div class="stat-card-subtitle">
                            <i class="fas fa-check me-1"></i>
                            Created by you
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">With Teachers</div>
                        <div class="stat-card-value">{{ $allSubjects->where('teacher_id', '!=', null)->count() ?? 0 }}</div>
                        <div class="stat-card-subtitle">
                            <i class="fas fa-user me-1"></i>
                            Assigned teachers
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-info">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Grade Levels</div>
                        <div class="stat-card-value">{{ $availableGrades->count() ?? 2 }}</div>
                        <div class="stat-card-subtitle">
                            <i class="fas fa-graduation-cap me-1"></i>
                            Available grades
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">
                        <i class="fas fa-list me-2 text-primary"></i>
                        All Subjects
                        <span class="badge bg-primary ms-2">{{ $allSubjects->count() ?? 0 }}</span>
                    </h5>
                    <p class="text-muted mb-0 small">Manage your subjects and view all subjects in the system</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <!-- Grade Level Filter -->
                    <form method="GET" action="{{ route('registrar.subjects.index') }}" class="d-flex align-items-center">
                        <select name="grade_level" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                            <option value="all" {{ (!$gradeFilter || $gradeFilter === 'all') ? 'selected' : '' }}>
                                All Grades
                            </option>
                            @if($availableGrades ?? false)
                                @foreach($availableGrades as $grade)
                                    <option value="{{ $grade }}" {{ $gradeFilter === $grade ? 'selected' : '' }}>
                                        {{ $grade }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </form>

                    <a href="{{ route('registrar.subjects.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Add Subject
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if($allSubjects && $allSubjects->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="subjectsTable">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">Code</th>
                                <th>Subject Name</th>
                                <th class="text-center">Grade Level</th>
                                <th>Track</th>
                                <th>Strand</th>
                                <th>Cluster/Specialization</th>
                                <th>Teacher</th>
                                <th class="text-center">Grading Period</th>
                                <th class="text-center">Created By</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allSubjects as $subject)
                            <tr class="{{ $subject->registrar_id == auth()->guard('registrar')->id() ? 'table-primary' : '' }}">
                                <td class="text-center">
                                    <span class="badge bg-primary fs-6">{{ $subject->code ?? $subject->subject_code }}</span>
                                </td>
                                <td>
                                    <div class="subject-info">
                                        <h6 class="mb-1">{{ $subject->name ?? $subject->subject_name }}</h6>
                                        @if($subject->description)
                                            <small class="text-muted">{{ Str::limit($subject->description, 50) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $subject->grade_level }}</span>
                                </td>
                                <td>
                                    @if($subject->track)
                                        <span class="badge bg-warning text-dark">{{ $subject->track }}</span>
                                    @else
                                        <span class="text-muted small">Not Set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subject->strand)
                                        <span class="badge bg-secondary">{{ $subject->strand }}</span>
                                    @else
                                        <span class="text-muted small">Not Set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subject->cluster)
                                        <span class="badge bg-info text-white mb-1">{{ $subject->cluster }}</span>
                                    @endif
                                    @if($subject->specialization)
                                        @if($subject->cluster)<br>@endif
                                        <span class="badge bg-light text-dark">{{ $subject->specialization }}</span>
                                    @endif
                                    @if(!$subject->cluster && !$subject->specialization)
                                        <span class="text-muted small">Not specified</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subject->teacher)
                                        <div class="d-flex align-items-center">
                                            <div class="teacher-avatar">
                                                {{ substr($subject->teacher->name, 0, 1) }}
                                            </div>
                                            <div class="ms-2">
                                                <div class="fw-semibold small">{{ $subject->teacher->name }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted small">Not Assigned</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $subject->grading ?? 'Not Set' }}</span>
                                </td>
                                <td class="text-center">
                                    @if($subject->registrar_id == auth()->guard('registrar')->id())
                                        <span class="badge bg-success">You</span>
                                    @elseif($subject->registrar)
                                        <span class="badge bg-secondary">{{ $subject->registrar->full_name ?? 'Other Registrar' }}</span>
                                    @else
                                        <span class="badge bg-warning">System</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('registrar.subjects.show', $subject) }}"
                                           class="btn btn-outline-info btn-sm"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($subject->registrar_id == auth()->guard('registrar')->id())
                                            <a href="{{ route('registrar.subjects.edit', $subject) }}"
                                               class="btn btn-outline-primary btn-sm"
                                               title="Edit Subject">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('registrar.subjects.destroy', $subject) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this subject?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-outline-danger btn-sm"
                                                        title="Delete Subject">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">View Only</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Subjects Found</h5>
                    <p class="text-muted mb-4">Start by creating your first subject with the DepEd curriculum structure.</p>
                    <a href="{{ route('registrar.subjects.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Your First Subject
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Modern Stat Cards */
.stat-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
    overflow: hidden;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.stat-card-body {
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    min-height: 100px;
}

.stat-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.stat-card-content {
    flex: 1;
    min-width: 0;
}

.stat-card-title {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.stat-card-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-card-subtitle {
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 500;
}

/* Card Color Variants */
.stat-card-primary .stat-card-icon {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
}

.stat-card-success .stat-card-icon {
    background: linear-gradient(135deg, #10b981, #059669);
}

.stat-card-warning .stat-card-icon {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.stat-card-info .stat-card-icon {
    background: linear-gradient(135deg, #06b6d4, #0891b2);
}

/* Table Improvements */
.table th {
    background-color: #343a40;
    color: white;
    font-weight: 600;
    border: none;
    font-size: 0.875rem;
    padding: 1rem 0.75rem;
}

.table-primary {
    background-color: rgba(13, 110, 253, 0.1);
}

.subject-info h6 {
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.teacher-avatar {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.75rem;
    flex-shrink: 0;
}

.btn-group .btn {
    margin-right: 2px;
}

.page-title {
    color: #1e293b;
    font-weight: 700;
}

/* Custom badge colors */
.bg-purple {
    background-color: #6f42c1 !important;
    color: white !important;
}

/* Responsive table improvements */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables
    $('#subjectsTable').DataTable({
        "pageLength": 15,
        "order": [[ 2, "asc" ], [ 1, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [9] }, // Actions column
            { "width": "10%", "targets": [0] }, // Code
            { "width": "20%", "targets": [1] }, // Name
            { "width": "10%", "targets": [2] }, // Grade Level
            { "width": "12%", "targets": [3] }, // Track
            { "width": "12%", "targets": [4] }, // Strand
            { "width": "12%", "targets": [5] }, // Cluster/Specialization
            { "width": "12%", "targets": [6] }, // Teacher
            { "width": "10%", "targets": [7] }, // Grading Period
            { "width": "8%", "targets": [8] }, // Created By
            { "width": "4%", "targets": [9] }  // Actions
        ],
        "language": {
            "search": "Search subjects:",
            "lengthMenu": "Show _MENU_ subjects per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ subjects",
            "emptyTable": "No subjects available"
        },
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        "responsive": true
    });

    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add smooth animations for stat cards
    $('.stat-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
    });
});
</script>
@endpush
