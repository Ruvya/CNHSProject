@extends('layouts.admin')

@section('title', 'Subjects Overview')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-book me-2 text-primary"></i>
                Subjects Overview
                <span class="badge bg-info ms-2">View Only</span>
            </h1>
            <p class="text-muted mb-0">View academic subjects and their assigned teachers</p>
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
                        <div class="stat-card-value">{{ $totalSubjects }}</div>
                        <div class="stat-card-subtitle">
                            @if($selectedGradeLevel && $selectedGradeLevel !== 'all')
                                <i class="fas fa-filter me-1"></i>
                                Filtered by {{ $selectedGradeLevel }}
                            @else
                                <i class="fas fa-graduation-cap me-1"></i>
                                All grade levels
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-success">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Assigned Subjects</div>
                        <div class="stat-card-value">{{ $subjects->where('teacher_id', '!=', null)->count() }}</div>
                        <div class="stat-card-subtitle">
                            <i class="fas fa-check me-1"></i>
                            With teachers
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Unassigned</div>
                        <div class="stat-card-value">{{ $subjects->where('teacher_id', null)->count() }}</div>
                        <div class="stat-card-subtitle">
                            <i class="fas fa-exclamation me-1"></i>
                            Need teachers
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
                        <div class="stat-card-value">{{ $subjectsByGrade->count() }}</div>
                        <div class="stat-card-subtitle">
                            <i class="fas fa-graduation-cap me-1"></i>
                            Available grades
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Subjects Section -->
<div class="card">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Subjects List
                    <span class="badge bg-primary ms-2">{{ $subjects->count() }}</span>
                    @if($selectedGradeLevel && $selectedGradeLevel !== 'all')
                        <span class="badge bg-info ms-1">{{ $selectedGradeLevel }}</span>
                    @endif
                </h5>
                <p class="text-muted mb-0 small">View subjects and their assigned teachers</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <!-- Grade Level Filter -->
                <form method="GET" action="{{ route('admin.subjects.index') }}" class="d-flex align-items-center" id="gradeFilterForm">
                    <select name="grade_level" id="grade_level" class="form-select form-select-sm" style="width: auto;">
                        <option value="all" {{ (!$selectedGradeLevel || $selectedGradeLevel === 'all') ? 'selected' : '' }}>
                            All Grades
                        </option>
                        @foreach($availableGradeLevels as $gradeLevel)
                            <option value="{{ $gradeLevel }}" {{ $selectedGradeLevel === $gradeLevel ? 'selected' : '' }}>
                                {{ $gradeLevel }}
                            </option>
                        @endforeach
                    </select>
                    @if($selectedGradeLevel && $selectedGradeLevel !== 'all')
                        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary btn-sm ms-2" id="clearFilter">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($subjects->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="subjectsTable">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">Code</th>
                            <th>Subject Name</th>
                            <th class="text-center">Grade</th>
                            <th class="text-center">Grading</th>
                            <th>Assigned Teacher</th>
                            <th class="text-center">Track/Strand</th>
                            <th class="text-center">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                        <tr class="subject-row">
                            <td class="text-center">
                                <span class="badge bg-primary fs-6">{{ $subject->code }}</span>
                            </td>
                            <td>
                                <div class="subject-info">
                                    <h6 class="mb-1">{{ $subject->name }}</h6>
                                    @if($subject->description)
                                        <small class="text-muted">{{ Str::limit($subject->description, 60) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $subject->grade_level }}</span>
                            </td>
                            <td class="text-center">
                                @if($subject->grading)
                                    <span class="badge bg-success">{{ $subject->grading }}</span>
                                @elseif($subject->semester)
                                    <span class="badge bg-warning">
                                        @if($subject->semester === '1st Semester')
                                            First Grading
                                        @elseif($subject->semester === '2nd Semester')
                                            Second Grading
                                        @elseif($subject->semester === 'Both Semesters')
                                            All Gradings
                                        @else
                                            {{ $subject->semester }}
                                        @endif
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Not Set</span>
                                @endif
                            </td>
                            <td>
                                @if($subject->teacher)
                                    <div class="d-flex align-items-center">
                                        <div class="teacher-avatar">
                                            {{ substr($subject->teacher->name, 0, 1) }}
                                        </div>
                                        <div class="ms-2">
                                            <div class="fw-semibold">{{ $subject->teacher->name }}</div>
                                            <small class="text-muted">{{ $subject->teacher->email }}</small>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-muted">
                                        <i class="fas fa-user-slash me-1"></i>
                                        <span>Not Assigned</span>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($subject->track || $subject->strand)
                                    <div class="track-strand-info">
                                        @if($subject->track)
                                            <span class="badge bg-warning text-dark mb-1">{{ $subject->track }}</span>
                                        @endif
                                        @if($subject->strand)
                                            <br><span class="badge bg-secondary">{{ $subject->strand }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">General</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.subjects.show', $subject) }}"
                                   class="btn btn-outline-info btn-sm"
                                   title="View Details"
                                   data-bs-toggle="tooltip">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                @if($selectedGradeLevel && $selectedGradeLevel !== 'all')
                    <h5 class="text-muted">No subjects found for {{ $selectedGradeLevel }}</h5>
                    <p class="text-muted mb-4">There are no subjects assigned to this grade level yet.</p>
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>View All Subjects
                    </a>
                @else
                    <h5 class="text-muted">No subjects found</h5>
                    <p class="text-muted mb-4">There are no subjects in the system yet.</p>
                    <p class="text-muted">Subjects can be created by the Registrar.</p>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables for Subjects
    $('#subjectsTable').DataTable({
        "pageLength": 15,
        "order": [[ 1, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 6 }
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
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // AJAX grade level filter
    $('#grade_level').on('change', function() {
        const selectedGrade = $(this).val();
        const currentUrl = new URL(window.location.href);

        // Show loading state
        $(this).prop('disabled', true);
        $('body').append('<div class="loading-overlay"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');

        // Update URL parameters
        if (selectedGrade === 'all') {
            currentUrl.searchParams.delete('grade_level');
        } else {
            currentUrl.searchParams.set('grade_level', selectedGrade);
        }

        // Use AJAX to fetch filtered content
        $.ajax({
            url: currentUrl.toString(),
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                // Update the page content
                const newContent = $(response).find('.card').last();
                $('.card').last().replaceWith(newContent);

                // Update the URL without page reload
                window.history.pushState({}, '', currentUrl.toString());

                // Reinitialize DataTables
                if ($.fn.DataTable.isDataTable('#subjectsTable')) {
                    $('#subjectsTable').DataTable().destroy();
                }

                if ($('#subjectsTable').length) {
                    $('#subjectsTable').DataTable({
                        "pageLength": 15,
                        "order": [[ 1, "asc" ]],
                        "columnDefs": [
                            { "orderable": false, "targets": 6 }
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
                }

                // Reinitialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            },
            error: function() {
                // Fallback to form submission if AJAX fails
                $('#gradeFilterForm').submit();
            },
            complete: function() {
                // Remove loading state
                $('#grade_level').prop('disabled', false);
                $('.loading-overlay').remove();
            }
        });
    });

    // Add smooth animations for stat cards
    $('.stat-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
        $(this).addClass('animate__animated animate__fadeInUp');
    });

    // Enhanced search functionality
    $('#searchSubjects').on('keyup', function() {
        const table = $('#subjectsTable').DataTable();
        table.search(this.value).draw();
    });
});
</script>

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

.subject-row {
    transition: all 0.2s ease;
}

.subject-row:hover {
    background-color: rgba(0, 123, 255, 0.05);
    transform: translateX(2px);
}

.subject-info h6 {
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.teacher-avatar {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.track-strand-info {
    line-height: 1.2;
}

.btn-group .btn {
    border-radius: 6px;
    margin: 0 1px;
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

/* Card styling */
.card {
    border: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border-radius: 12px;
}

.card-header {
    border-bottom: 1px solid #e9ecef;
    background: white;
    padding: 1.25rem;
}

.card-body {
    padding: 1.25rem;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .stat-card-body {
        padding: 0.75rem;
        min-height: 85px;
        gap: 0.75rem;
    }

    .stat-card-icon {
        width: 40px;
        height: 40px;
        font-size: 1.1rem;
    }

    .stat-card-value {
        font-size: 1.5rem;
    }

    .teacher-avatar {
        width: 32px;
        height: 32px;
        font-size: 0.75rem;
    }
}
</style>
@endpush
