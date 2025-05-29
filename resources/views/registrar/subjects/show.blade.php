@extends('layouts.registrar')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-dark fw-bold">
                        <i class="fas fa-book-open text-primary me-2"></i>
                        Subject Details
                    </h1>
                    <p class="text-muted mb-0">Complete information about {{ $subject->name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('registrar.subjects.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Subjects
                    </a>
                    @if($subject->registrar_id == auth()->guard('registrar')->id())
                        <a href="{{ route('registrar.subjects.edit', $subject) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i>Edit Subject
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Subject Overview Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                <div class="subject-icon me-3">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div>
                                    <h2 class="h4 mb-1 text-dark fw-bold">{{ $subject->name }}</h2>
                                    <p class="text-muted mb-0">{{ $subject->description ?: 'No description provided' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="subject-code-display">
                                <span class="badge bg-primary fs-5 px-3 py-2">{{ $subject->code }}</span>
                                <small class="d-block text-muted mt-1">Subject Code</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="row g-4">
                <!-- Left Column - Academic Information -->
                <div class="col-lg-8">
                    <!-- Academic Configuration Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light border-0 py-3">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-graduation-cap text-primary me-2"></i>
                                Academic Information
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-card-header">
                                            <i class="fas fa-layer-group text-info"></i>
                                            <span>Grade Level</span>
                                        </div>
                                        <div class="info-card-value">
                                            <span class="badge bg-info fs-6">{{ $subject->grade_level }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-card-header">
                                            <i class="fas fa-calendar-check text-success"></i>
                                            <span>Grading Period</span>
                                        </div>
                                        <div class="info-card-value">
                                            <span class="badge bg-success fs-6">{{ $subject->grading ?? 'Not specified' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DepEd Curriculum Structure Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light border-0 py-3">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-sitemap text-success me-2"></i>
                                DepEd Curriculum Structure
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-card-header">
                                            <i class="fas fa-route text-warning"></i>
                                            <span>Track</span>
                                        </div>
                                        <div class="info-card-value">
                                            <span class="badge bg-warning text-dark fs-6">{{ $subject->track ?: 'Not specified' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-card-header">
                                            <i class="fas fa-code-branch text-secondary"></i>
                                            <span>Strand</span>
                                        </div>
                                        <div class="info-card-value">
                                            <span class="badge bg-secondary fs-6">{{ $subject->strand ?: 'Not specified' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-card-header">
                                            <i class="fas fa-layer-group text-info"></i>
                                            <span>Cluster</span>
                                        </div>
                                        <div class="info-card-value">
                                            <span class="text-dark">{{ $subject->cluster ?: 'Not specified' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-card-header">
                                            <i class="fas fa-star text-purple"></i>
                                            <span>Specialization</span>
                                        </div>
                                        <div class="info-card-value">
                                            <span class="text-dark">{{ $subject->specialization ?: 'Not specified' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Additional Information -->
                <div class="col-lg-4">
                    <!-- Teacher Assignment Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light border-0 py-3">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chalkboard-teacher text-primary me-2"></i>
                                Teacher Assignment
                            </h5>
                        </div>
                        <div class="card-body p-4 text-center">
                            @if($subject->teacher)
                                <div class="teacher-profile">
                                    <div class="teacher-avatar-large mx-auto mb-3">
                                        {{ substr($subject->teacher->name, 0, 2) }}
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">{{ $subject->teacher->name }}</h6>
                                    <span class="badge bg-success">Assigned</span>
                                </div>
                            @else
                                <div class="teacher-profile">
                                    <div class="teacher-avatar-large mx-auto mb-3 bg-light text-muted">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <h6 class="text-muted mb-1">No Teacher Assigned</h6>
                                    <span class="badge bg-warning">Pending Assignment</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Subject Classification Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light border-0 py-3">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-tags text-warning me-2"></i>
                                Classification
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="classification-item mb-3">
                                <label class="classification-label">Subject Type</label>
                                <div class="classification-value">
                                    @if($subject->is_core_subject)
                                        <span class="badge bg-danger fs-6">Core Subject</span>
                                    @elseif($subject->is_master_subject)
                                        <span class="badge bg-warning fs-6">Master Subject</span>
                                    @else
                                        <span class="badge bg-light text-dark fs-6">Regular Subject</span>
                                    @endif
                                </div>
                            </div>
                            <div class="classification-item">
                                <label class="classification-label">Created By</label>
                                <div class="classification-value">
                                    @if($subject->registrar_id == auth()->guard('registrar')->id())
                                        <span class="badge bg-primary fs-6">You</span>
                                    @elseif($subject->registrar)
                                        <span class="badge bg-secondary fs-6">{{ $subject->registrar->full_name }}</span>
                                    @else
                                        <span class="badge bg-info fs-6">System</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Record Information Card -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light border-0 py-3">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clock text-secondary me-2"></i>
                                Record Information
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="record-item mb-3">
                                <label class="record-label">Created</label>
                                <div class="record-value">{{ $subject->created_at->format('M j, Y') }}</div>
                                <small class="text-muted">{{ $subject->created_at->format('g:i A') }}</small>
                            </div>
                            <div class="record-item">
                                <label class="record-label">Last Updated</label>
                                <div class="record-value">{{ $subject->updated_at->format('M j, Y') }}</div>
                                <small class="text-muted">{{ $subject->updated_at->format('g:i A') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($subject->registrar_id == auth()->guard('registrar')->id())
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Subject Management</h6>
                                        <small class="text-muted">You can edit or delete this subject since you created it.</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('registrar.subjects.edit', $subject) }}" class="btn btn-primary">
                                            <i class="fas fa-edit me-1"></i>Edit Subject
                                        </a>
                                        <form action="{{ route('registrar.subjects.destroy', $subject) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this subject? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fas fa-trash me-1"></i>Delete Subject
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-info border-0 shadow-sm">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <span>You can only edit subjects that you created. This subject was created by another registrar.</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Modern Card Design */
.card {
    border-radius: 12px;
    border: none;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    border-bottom: 1px solid #dee2e6;
}

.card-title {
    font-weight: 600;
    color: #495057;
}

/* Subject Icon */
.subject-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.subject-code-display {
    text-align: center;
}

/* Info Cards */
.info-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    height: 100%;
    transition: all 0.3s ease;
}

.info-card:hover {
    background: #e9ecef;
    transform: translateY(-1px);
}

.info-card-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-card-value {
    font-size: 1rem;
    font-weight: 500;
    color: #212529;
}

/* Teacher Profile */
.teacher-avatar-large {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #28a745, #20c997);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: 700;
    text-transform: uppercase;
}

.teacher-profile h6 {
    font-size: 1.1rem;
}

/* Classification Items */
.classification-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
}

.classification-item:last-child {
    border-bottom: none;
}

.classification-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
    display: block;
}

.classification-value {
    font-size: 1rem;
}

/* Record Items */
.record-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
}

.record-item:last-child {
    border-bottom: none;
}

.record-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
    display: block;
}

.record-value {
    font-size: 1rem;
    font-weight: 500;
    color: #212529;
    margin-bottom: 0.25rem;
}

/* Custom Colors */
.text-purple {
    color: #6f42c1 !important;
}

.bg-purple {
    background-color: #6f42c1 !important;
    color: white !important;
}

/* Badges */
.badge {
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
}

/* Buttons */
.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Alert */
.alert {
    border-radius: 8px;
    border: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .subject-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }

    .teacher-avatar-large {
        width: 60px;
        height: 60px;
        font-size: 1.25rem;
    }

    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }

    .btn {
        width: 100%;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.5s ease-out;
}

.card:nth-child(2) {
    animation-delay: 0.1s;
}

.card:nth-child(3) {
    animation-delay: 0.2s;
}
</style>
@endpush
