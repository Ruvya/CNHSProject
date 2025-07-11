@extends('layouts.registrar')

@section('title', 'Yearly Records Overview')

@push('styles')
<style>
.angled-header-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(115deg, #f97316 60%, #3b82f6 60%);
    color: white;
    padding: 2.2rem 2.5rem 2.2rem 2.5rem;
    border-radius: 16px;
    margin-bottom: 2.5rem;
    box-shadow: 0 10px 30px rgba(249, 115, 22, 0.18);
    position: relative;
    overflow: hidden;
    min-height: 120px;
}
.header-left-content {
    display: flex;
    align-items: center;
}
.header-left-content .icon {
    font-size: 2.8rem;
    margin-right: 1.5rem;
    opacity: 0.92;
}
.header-left-content .title {
    font-size: 2.2rem;
    font-weight: 800;
    display: block;
    margin-bottom: 0.2rem;
    line-height: 1.1;
}
.header-left-content .subtitle {
    font-size: 1.1rem;
    font-weight: 500;
    opacity: 0.95;
    display: block;
}
.header-right-content {
    display: flex;
    align-items: center;
}
.angled-header-btn {
    background: rgba(255,255,255,0.18);
    color: #fff;
    font-weight: 700;
    font-size: 1.1rem;
    border-radius: 2rem;
    padding: 0.7rem 1.7rem;
    display: flex;
    align-items: center;
    gap: 0.7rem;
    box-shadow: 0 2px 8px rgba(56,135,250,0.10);
    border: 1px solid rgba(255,255,255,0.25);
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
}
.angled-header-btn:hover {
    background: rgba(255,255,255,0.28);
    color: #fff;
    text-decoration: none;
}
@media (max-width: 768px) {
    .angled-header-card {
        flex-direction: column;
        align-items: flex-start;
        padding: 1.2rem 1rem;
        min-height: 100px;
    }
    .header-left-content {
        flex-direction: column;
        align-items: flex-start;
    }
    .header-left-content .icon {
        margin-bottom: 0.7rem;
        margin-right: 0;
    }
    .header-right-content {
        margin-top: 1rem;
        width: 100%;
        justify-content: flex-start;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Modern Angled Header Card -->
    <div class="angled-header-card mb-4">
        <div class="header-left-content">
            <span class="icon"><i class="fas fa-calendar-alt"></i></span>
            <div>
                <span class="title">Yearly Records Management</span>
                <span class="subtitle">Manage student and teacher records by academic year</span>
            </div>
        </div>
        <div class="header-right-content">
            <button type="button" class="angled-header-btn" data-bs-toggle="modal" data-bs-target="#createNewYearModal">
                <i class="fas fa-plus me-2"></i> Create New School Year
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Current Year Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $currentYearStats['total_students'] }}</h4>
                            <p class="mb-0">Students ({{ $currentSchoolYear }})</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-graduate fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $currentYearStats['total_teachers'] }}</h4>
                            <p class="mb-0">Teachers ({{ $currentSchoolYear }})</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chalkboard-teacher fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $currentYearStats['active_students'] }}</h4>
                            <p class="mb-0">Active Students</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $currentYearStats['active_teachers'] }}</h4>
                            <p class="mb-0">Active Teachers</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- School Years List -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>School Years
                    </h5>
                </div>
                <div class="card-body">
                    @if($allYears->count() > 0)
                        <div class="list-group">
                            @foreach($allYears as $year)
                                <a href="{{ route('registrar.yearly-records.show', $year) }}" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">School Year {{ $year }}</h6>
                                        <small class="text-muted">
                                            @if($year === $currentSchoolYear)
                                                <span class="badge bg-success">Current Year</span>
                                            @endif
                                        </small>
                                    </div>
                                    <div>
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No yearly records found. Create your first school year to get started.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Recent Student Records -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Recent Student Records</h6>
                </div>
                <div class="card-body">
                    @if($recentStudentRecords->count() > 0)
                        @foreach($recentStudentRecords as $record)
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <small class="fw-bold">
                                        @if($record->student)
                                            {{ $record->student->name }}
                                        @else
                                            Sample Student ({{ $record->grade_level }} - {{ $record->section }})
                                        @endif
                                    </small><br>
                                    <small class="text-muted">{{ $record->grade_level }} - {{ $record->status }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <small class="text-muted">No recent student records</small>
                    @endif
                </div>
            </div>

            <!-- Recent Teacher Records -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Recent Teacher Records</h6>
                </div>
                <div class="card-body">
                    @if($recentTeacherRecords->count() > 0)
                        @foreach($recentTeacherRecords as $record)
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <small class="fw-bold">
                                        @if($record->teacher)
                                            {{ $record->teacher->name }}
                                        @else
                                            Sample Teacher ({{ $record->department }})
                                        @endif
                                    </small><br>
                                    <small class="text-muted">{{ $record->department }} - {{ $record->position }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <small class="text-muted">No recent teacher records</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create New Year Modal -->
<div class="modal fade" id="createNewYearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('registrar.yearly-records.create-new-year') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create New School Year</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="school_year" class="form-label">School Year</label>
                        <input type="text" class="form-control" id="school_year" name="school_year" 
                               placeholder="e.g., 2024-2025" pattern="\d{4}-\d{4}" required>
                        <div class="form-text">Format: YYYY-YYYY (e.g., 2024-2025)</div>
                    </div>
                    <div class="mb-3">
                        <label for="copy_from_year" class="form-label">Copy from Previous Year (Optional)</label>
                        <select class="form-select" id="copy_from_year" name="copy_from_year">
                            <option value="">Create fresh records</option>
                            @foreach($allYears as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">If selected, students will be promoted and teachers will be carried over</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create School Year</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate school year based on current year
    const schoolYearInput = document.getElementById('school_year');
    const currentYear = new Date().getFullYear();
    const nextYear = currentYear + 1;
    schoolYearInput.placeholder = `${currentYear}-${nextYear}`;
});
</script>
@endsection
