@extends('layouts.registrar')

@section('styles')
<style>
    :root {
        --primary-blue: #2563eb;
        --primary-dark: #1d4ed8;
        --light-blue: #dbeafe;
        --success-green: #10b981;
        --warning-orange: #f59e0b;
        --info-cyan: #06b6d4;
        --border-radius: 12px;
        --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --box-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .assignment-index-container {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .page-header-modern {
        background: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--box-shadow);
        border-left: 4px solid var(--primary-blue);
    }

    .page-title-modern {
        color: var(--primary-blue);
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: #6b7280;
        font-size: 1rem;
        margin: 0;
    }

    .btn-assign-modern {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-assign-modern:hover {
        transform: translateY(-2px);
        box-shadow: var(--box-shadow-lg);
        color: white;
    }

    .stats-grid-modern {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card-modern {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--box-shadow);
        border-left: 4px solid;
        transition: transform 0.2s ease;
    }

    .stat-card-modern:hover {
        transform: translateY(-2px);
        box-shadow: var(--box-shadow-lg);
    }

    .stat-card-modern.primary {
        border-left-color: var(--primary-blue);
    }

    .stat-card-modern.success {
        border-left-color: var(--success-green);
    }

    .stat-card-modern.info {
        border-left-color: var(--info-cyan);
    }

    .stat-card-modern.warning {
        border-left-color: var(--warning-orange);
    }

    .stat-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .stat-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #6b7280;
        margin: 0;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-icon.primary {
        background: var(--primary-blue);
    }

    .stat-icon.success {
        background: var(--success-green);
    }

    .stat-icon.info {
        background: var(--info-cyan);
    }

    .stat-icon.warning {
        background: var(--warning-orange);
    }

    .filter-card-modern {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .filter-header {
        background: var(--light-blue);
        padding: 1rem 1.5rem;
        border-left: 4px solid var(--primary-blue);
    }

    .filter-title {
        color: var(--primary-blue);
        font-weight: 600;
        margin: 0;
        font-size: 1.1rem;
    }

    .table-card-modern {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
    }

    .table-header-modern {
        background: var(--light-blue);
        padding: 1rem 1.5rem;
        border-left: 4px solid var(--primary-blue);
        display: flex;
        justify-content: between;
        align-items: center;
    }

    .table-title-modern {
        color: var(--primary-blue);
        font-weight: 600;
        margin: 0;
        font-size: 1.1rem;
    }

    .badge-modern {
        border-radius: 6px;
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .btn-group-modern .btn {
        border-radius: 6px;
        margin: 0 2px;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .assignment-index-container {
            padding: 1rem 0;
        }

        .page-header-modern {
            padding: 1.5rem;
        }

        .page-title-modern {
            font-size: 1.5rem;
        }

        .stats-grid-modern {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection

@section('content')
<div class="assignment-index-container">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title-modern">
                        <i class="fas fa-users-cog me-2"></i>
                        Student Assignment Management
                        <span class="badge bg-info ms-2">{{ $currentSchoolYear }} - {{ $currentGradingPeriod }}</span>
                    </h1>
                    <p class="page-subtitle">Assign students based on academic information with automatic subject package loading</p>
                </div>
                <div>
                    <a href="{{ route('registrar.student-assignments.create') }}" class="btn-assign-modern">
                        <i class="fas fa-plus"></i>Assign Student
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid-modern">
            <div class="stat-card-modern primary">
                <div class="stat-header">
                    <div>
                        <h5 class="stat-title">Total Students</h5>
                        <div class="stat-number">{{ $stats['total_students'] }}</div>
                    </div>
                    <div class="stat-icon primary">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card-modern success">
                <div class="stat-header">
                    <div>
                        <h5 class="stat-title">Assigned Students</h5>
                        <div class="stat-number">{{ $stats['assigned_students'] }}</div>
                    </div>
                    <div class="stat-icon success">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card-modern info">
                <div class="stat-header">
                    <div>
                        <h5 class="stat-title">Total Subjects</h5>
                        <div class="stat-number">{{ $stats['total_subjects'] }}</div>
                    </div>
                    <div class="stat-icon info">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card-modern warning">
                <div class="stat-header">
                    <div>
                        <h5 class="stat-title">Unassigned Students</h5>
                        <div class="stat-number">{{ $stats['unassigned_students'] }}</div>
                    </div>
                    <div class="stat-icon warning">
                        <i class="fas fa-user-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card-modern">
            <div class="filter-header">
                <h5 class="filter-title"><i class="fas fa-filter me-2"></i>Filters</h5>
            </div>
            <div class="p-4">
                <form id="filterForm" method="GET" action="{{ route('registrar.student-assignments.index') }}">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="grading_period" class="form-label fw-semibold">Grading Period</label>
                            <select name="grading_period" id="grading_period" class="form-select">
                                <option value="First Grading" {{ $currentGradingPeriod == 'First Grading' ? 'selected' : '' }}>First Grading</option>
                                <option value="Second Grading" {{ $currentGradingPeriod == 'Second Grading' ? 'selected' : '' }}>Second Grading</option>
                                <option value="Third Grading" {{ $currentGradingPeriod == 'Third Grading' ? 'selected' : '' }}>Third Grading</option>
                                <option value="Fourth Grading" {{ $currentGradingPeriod == 'Fourth Grading' ? 'selected' : '' }}>Fourth Grading</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="grade_level" class="form-label fw-semibold">Grade Level</label>
                            <select name="grade_level" id="grade_level" class="form-select">
                                <option value="">All Grades</option>
                                <option value="Grade 11" {{ $gradeLevel == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                <option value="Grade 12" {{ $gradeLevel == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="track" class="form-label fw-semibold">Track</label>
                            <select name="track" id="track" class="form-select">
                                <option value="">All Tracks</option>
                                <option value="Academic Track" {{ $track == 'Academic Track' ? 'selected' : '' }}>Academic Track</option>
                                <option value="TVL" {{ $track == 'TVL' ? 'selected' : '' }}>TVL</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="strand" class="form-label fw-semibold">Strand</label>
                            <select name="strand" id="strand" class="form-select">
                                <option value="">All Strands</option>
                                <option value="STEM" {{ $strand == 'STEM' ? 'selected' : '' }}>STEM</option>
                                <option value="ABM" {{ $strand == 'ABM' ? 'selected' : '' }}>ABM</option>
                                <option value="HUMSS" {{ $strand == 'HUMSS' ? 'selected' : '' }}>HUMSS</option>
                                <option value="GAS" {{ $strand == 'GAS' ? 'selected' : '' }}>GAS</option>
                                <option value="ICT" {{ $strand == 'ICT' ? 'selected' : '' }}>ICT</option>
                                <option value="Home Economics" {{ $strand == 'Home Economics' ? 'selected' : '' }}>Home Economics</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search" class="form-label fw-semibold">Search Student</label>
                            <input type="text" name="search" id="search" class="form-control"
                                   placeholder="Name or Student ID" value="{{ $search }}">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Assignments Table -->
        <div class="table-card-modern">
            <div class="table-header-modern">
                <h5 class="table-title-modern"><i class="fas fa-list me-2"></i>Student Assignments</h5>
                <div>
                    <span class="text-muted">Academic-based assignments</span>
                </div>
            </div>
        <div class="card-body">
            @if($assignments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Grade Level</th>
                                <th>Track</th>
                                <th>Strand</th>
                                <th>Subjects</th>
                                <th>Assignment Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignments as $assignment)
                                <tr>
                                    <td>{{ $assignment->student->student_id }}</td>
                                    <td>
                                        <strong>{{ $assignment->student->first_name }} {{ $assignment->student->last_name }}</strong>
                                        <br><small class="text-muted">{{ $assignment->student->email }}</small>
                                    </td>
                                    <td>{{ $assignment->grade_level }}</td>
                                    <td>
                                        <span class="badge-modern bg-primary text-white">{{ $assignment->track }}</span>
                                    </td>
                                    <td>
                                        <span class="badge-modern bg-info text-white">{{ $assignment->strand }}</span>
                                    </td>
                                    <td>
                                        @if($assignment->subjects && is_array($assignment->subjects))
                                            <span class="badge-modern bg-success text-white">{{ count($assignment->subjects) }} subjects</span>
                                            <br><small class="text-muted">Click to view details</small>
                                        @else
                                            <span class="text-muted">No subjects assigned</span>
                                        @endif
                                    </td>
                                    <td>{{ $assignment->assignment_date->format('M d, Y') }}</td>
                                    <td>
                                        @if($assignment->status == 'active')
                                            <span class="badge-modern bg-success text-white">Active</span>
                                        @elseif($assignment->status == 'transferred')
                                            <span class="badge-modern bg-warning text-white">Transferred</span>
                                        @else
                                            <span class="badge-modern bg-danger text-white">Dropped</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group-modern" role="group">
                                            <a href="{{ route('registrar.student-assignments.show', $assignment) }}"
                                               class="btn btn-sm btn-outline-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('registrar.student-assignments.edit', $assignment) }}"
                                               class="btn btn-sm btn-outline-warning" title="Edit Assignment">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('registrar.student-assignments.destroy', $assignment) }}"
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
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No student assignments found</h5>
                    <p class="text-muted">Start by assigning students based on their academic information with automatic subject package loading.</p>
                    <a href="{{ route('registrar.student-assignments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Assign First Student
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Unassigned Students Section -->
    @if($unassignedStudents->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0 text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Unassigned Students ({{ $unassignedStudents->count() }})
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($unassignedStudents->take(10) as $student)
                        <div class="col-md-6 mb-2">
                            <div class="d-flex justify-content-between align-items-center border rounded p-2">
                                <div>
                                    <strong>{{ $student->student_id }}</strong> - {{ $student->full_name }}
                                    <br><small class="text-muted">{{ $student->grade_level }} - {{ $student->track }}</small>
                                </div>
                                <a href="{{ route('registrar.student-assignments.create', ['student_id' => $student->id]) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Assign
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($unassignedStudents->count() > 10)
                    <div class="text-center mt-3">
                        <small class="text-muted">Showing 10 of {{ $unassignedStudents->count() }} unassigned students</small>
                    </div>
                @endif
            </div>
        </div>
    @endif
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
</script>
@endsection
