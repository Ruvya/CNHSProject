@extends('layouts.student')

@section('title', 'Subject Details')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subjects') }}">My Subjects</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $subject->name ?? $subject->subject_name }}</li>
                </ol>
            </nav>

            <!-- Subject Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="mb-1">{{ $subject->name ?? $subject->subject_name }}</h3>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-code me-2"></i>{{ $subject->code ?? $subject->subject_code }}
                                @if($isAssigned)
                                    <span class="badge bg-success ms-2">
                                        <i class="fas fa-check me-1"></i>Assigned to You
                                    </span>
                                @else
                                    <span class="badge bg-warning ms-2">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Not Assigned
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="subject-icon-large">
                                <i class="fas fa-book fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Subject Information -->
                <div class="col-md-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2 text-primary"></i>Subject Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="form-label text-muted">Subject Name</label>
                                        <p class="fw-bold">{{ $subject->name ?? $subject->subject_name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="form-label text-muted">Subject Code</label>
                                        <p class="fw-bold">{{ $subject->code ?? $subject->subject_code }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="form-label text-muted">Grade Level</label>
                                        <p><span class="badge bg-primary">{{ $subject->grade_level }}</span></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="form-label text-muted">Units</label>
                                        <p><span class="badge bg-info">{{ $subject->units ?? 'N/A' }} Units</span></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="form-label text-muted">Track</label>
                                        <p><span class="badge bg-secondary">{{ $subject->track ?? 'N/A' }}</span></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="form-label text-muted">Strand</label>
                                        <p><span class="badge bg-secondary">{{ $subject->strand ?? 'N/A' }}</span></p>
                                    </div>
                                </div>
                                @if($subject->description)
                                    <div class="col-12">
                                        <div class="info-item">
                                            <label class="form-label text-muted">Description</label>
                                            <p class="text-justify">{{ $subject->description }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Teacher Information -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-chalkboard-teacher me-2 text-success"></i>Teacher Information
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($subject->teacher)
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <div class="teacher-avatar">
                                            <i class="fas fa-user-tie fa-3x text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <h5 class="mb-1">{{ $subject->teacher->name }}</h5>
                                        <p class="text-muted mb-2">Subject Teacher</p>
                                        @if($subject->teacher->email)
                                            <p class="mb-1">
                                                <i class="fas fa-envelope me-2"></i>
                                                <a href="mailto:{{ $subject->teacher->email }}">{{ $subject->teacher->email }}</a>
                                            </p>
                                        @endif
                                        @if($subject->teacher->contact_number)
                                            <p class="mb-0">
                                                <i class="fas fa-phone me-2"></i>{{ $subject->teacher->contact_number }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-user-times fa-3x text-warning mb-3"></i>
                                    <h5 class="text-warning">No Teacher Assigned</h5>
                                    <p class="text-muted">A teacher will be assigned to this subject soon.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Student's Grade Information -->
                    @if($isAssigned && $studentGrade)
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-star me-2 text-warning"></i>Your Grade
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @if($studentGrade->quarter1)
                                        <div class="col-md-3">
                                            <div class="grade-item text-center">
                                                <label class="form-label text-muted">Quarter 1</label>
                                                <h4 class="text-primary">{{ $studentGrade->quarter1 }}</h4>
                                            </div>
                                        </div>
                                    @endif
                                    @if($studentGrade->quarter2)
                                        <div class="col-md-3">
                                            <div class="grade-item text-center">
                                                <label class="form-label text-muted">Quarter 2</label>
                                                <h4 class="text-primary">{{ $studentGrade->quarter2 }}</h4>
                                            </div>
                                        </div>
                                    @endif
                                    @if($studentGrade->quarter3)
                                        <div class="col-md-3">
                                            <div class="grade-item text-center">
                                                <label class="form-label text-muted">Quarter 3</label>
                                                <h4 class="text-primary">{{ $studentGrade->quarter3 }}</h4>
                                            </div>
                                        </div>
                                    @endif
                                    @if($studentGrade->quarter4)
                                        <div class="col-md-3">
                                            <div class="grade-item text-center">
                                                <label class="form-label text-muted">Quarter 4</label>
                                                <h4 class="text-primary">{{ $studentGrade->quarter4 }}</h4>
                                            </div>
                                        </div>
                                    @endif
                                    @if($studentGrade->final_grade)
                                        <div class="col-12">
                                            <div class="final-grade text-center mt-3 p-3 bg-light rounded">
                                                <label class="form-label text-muted">Final Grade</label>
                                                <h2 class="text-{{ $studentGrade->final_grade >= 75 ? 'success' : 'danger' }}">
                                                    {{ $studentGrade->final_grade }}
                                                </h2>
                                                <span class="badge bg-{{ $studentGrade->final_grade >= 75 ? 'success' : 'danger' }}">
                                                    {{ $studentGrade->final_grade >= 75 ? 'Passed' : 'Failed' }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Quick Stats -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-chart-bar me-2 text-info"></i>Quick Stats
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="stat-item mb-3">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Class Size</span>
                                    <strong>{{ $classSize }} students</strong>
                                </div>
                            </div>
                            <div class="stat-item mb-3">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Units</span>
                                    <strong>{{ $subject->units ?? 'N/A' }}</strong>
                                </div>
                            </div>
                            <div class="stat-item mb-3">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Assignment Status</span>
                                    <span class="badge bg-{{ $isAssigned ? 'success' : 'warning' }}">
                                        {{ $isAssigned ? 'Assigned' : 'Not Assigned' }}
                                    </span>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Teacher Status</span>
                                    <span class="badge bg-{{ $subject->teacher ? 'success' : 'warning' }}">
                                        {{ $subject->teacher ? 'Assigned' : 'TBA' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-cogs me-2 text-secondary"></i>Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('student.subjects') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to My Subjects
                                </a>
                                @if($isAssigned)
                                    <a href="{{ route('student.grades') }}" class="btn btn-outline-success">
                                        <i class="fas fa-star me-2"></i>View All Grades
                                    </a>
                                @endif
                                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-home me-2"></i>Dashboard
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    @if($subject->teacher)
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-address-book me-2 text-primary"></i>Contact Teacher
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="small text-muted mb-3">Need help with this subject? Contact your teacher:</p>
                                @if($subject->teacher->email)
                                    <div class="d-grid gap-2">
                                        <a href="mailto:{{ $subject->teacher->email }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-envelope me-2"></i>Send Email
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Admin Color Variables */
:root {
    --primary-color: #2563eb;
    --primary-dark: #1d4ed8;
    --secondary-color: #64748b;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #06b6d4;
    --dark-color: #1e293b;
    --light-color: #f8fafc;
    --border-radius: 12px;
    --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --box-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.info-item {
    margin-bottom: 1rem;
}

.info-item label {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--secondary-color);
}

.teacher-avatar {
    text-align: center;
    padding: 1rem;
    background: rgba(16, 185, 129, 0.1);
    border-radius: var(--border-radius);
}

.grade-item {
    padding: 1rem;
    background: var(--light-color);
    border-radius: var(--border-radius);
}

.stat-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.stat-item:last-child {
    border-bottom: none;
}

.subject-icon-large {
    opacity: 0.7;
}

.card {
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}

.final-grade {
    border: 2px solid #e2e8f0;
}
</style>
@endsection
