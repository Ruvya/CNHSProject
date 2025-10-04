@extends('layouts.admin')
@section('title', 'Schedule Details')
@section('content')
<div class="container-fluid">
    <!-- Modern Angled Header Card -->
    <div class="angled-header-card mb-4">
        <div class="header-left-content">
            <span class="icon"><i class="fas fa-calendar-check"></i></span>
            <div>
                <span class="title">Schedule Details</span>
                <span class="subtitle">{{ $schedule->formatted_schedule }}</span>
            </div>
        </div>
        <div class="header-right-content">
            <a href="{{ route('admin.scheduling.edit', $schedule) }}" class="angled-header-btn me-2">
                <i class="fas fa-edit me-2"></i> Edit Schedule
            </a>
            <a href="{{ route('admin.scheduling.index') }}" class="angled-header-btn">
                <i class="fas fa-arrow-left me-2"></i> Back to Schedules
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Schedule Information -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Schedule Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Day</label>
                            <div class="p-2 bg-light rounded">
                                <span class="badge bg-primary fs-6">{{ $schedule->day }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Time</label>
                            <div class="p-2 bg-light rounded">
                                <strong>{{ date('g:i A', strtotime($schedule->start_time)) }}</strong> - 
                                <strong>{{ date('g:i A', strtotime($schedule->end_time)) }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">School Year</label>
                            <div class="p-2 bg-light rounded">
                                <span class="badge bg-secondary">{{ $schedule->school_year }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Grading Period</label>
                            <div class="p-2 bg-light rounded">
                                <span class="badge bg-warning">{{ $schedule->grading_period }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Status</label>
                            <div class="p-2 bg-light rounded">
                                @if($schedule->status == 'active')
                                    <span class="badge bg-success fs-6">Active</span>
                                @elseif($schedule->status == 'inactive')
                                    <span class="badge bg-secondary fs-6">Inactive</span>
                                @else
                                    <span class="badge bg-danger fs-6">Cancelled</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Created</label>
                            <div class="p-2 bg-light rounded">
                                {{ $schedule->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                        @if($schedule->notes)
                        <div class="col-12">
                            <label class="form-label fw-bold">Notes</label>
                            <div class="p-2 bg-light rounded">
                                {{ $schedule->notes }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.scheduling.edit', $schedule) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Schedule
                        </a>
                        <form action="{{ route('admin.scheduling.destroy', $schedule) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this schedule?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i>Delete Schedule
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Teacher Information -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Teacher</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-user text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $schedule->teacher->name }}</h5>
                            <p class="text-muted mb-0">{{ $schedule->teacher->email }}</p>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted">Subject Expertise</small>
                            <div class="fw-medium">{{ $schedule->teacher->subject ?? 'Not specified' }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Track</small>
                            <div class="fw-medium">{{ $schedule->teacher->track ?? 'Not specified' }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Status</small>
                            <div>
                                @if($schedule->teacher->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Contact</small>
                            <div class="fw-medium">{{ $schedule->teacher->contact_number ?? 'Not provided' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject Information -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-book me-2"></i>Subject</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg bg-info rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-book text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $schedule->subject->name }}</h5>
                            <p class="text-muted mb-0">{{ $schedule->subject->code ?? 'No Code' }}</p>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted">Grade Level</small>
                            <div class="fw-medium">{{ $schedule->subject->grade_level ?? 'Not specified' }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Track</small>
                            <div class="fw-medium">{{ $schedule->subject->track ?? 'Not specified' }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Cluster</small>
                            <div class="fw-medium">{{ $schedule->subject->cluster ?? 'Not specified' }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Semester</small>
                            <div class="fw-medium">{{ $schedule->subject->semester ?? 'Not specified' }}</div>
                        </div>
                        @if($schedule->subject->description)
                        <div class="col-12">
                            <small class="text-muted">Description</small>
                            <div class="fw-medium">{{ $schedule->subject->description }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Information -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-users me-2"></i>Section</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg bg-warning rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-users text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $schedule->section->name }}</h5>
                            <p class="text-muted mb-0">Grade {{ $schedule->section->grade_level }}</p>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted">Track</small>
                            <div class="fw-medium">{{ $schedule->section->track ?? 'Not specified' }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Strand</small>
                            <div class="fw-medium">{{ $schedule->section->strand ?? 'Not specified' }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Enrollment</small>
                            <div class="fw-medium">{{ $schedule->section->current_enrollment }}/{{ $schedule->section->max_capacity }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Status</small>
                            <div>
                                @if($schedule->section->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($schedule->section->status) }}</span>
                                @endif
                            </div>
                        </div>
                        @if($schedule->section->adviser)
                        <div class="col-12">
                            <small class="text-muted">Adviser</small>
                            <div class="fw-medium">{{ $schedule->section->adviser->name }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Information -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-door-open me-2"></i>Room</h6>
                </div>
                <div class="card-body">
                    @if($schedule->room)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-lg bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-door-open text-white fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $schedule->room->name }}</h5>
                                <p class="text-muted mb-0">{{ $schedule->room->code }} - {{ ucfirst(str_replace('_', ' ', $schedule->room->type)) }}</p>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <small class="text-muted">Capacity</small>
                                <div class="fw-medium">{{ $schedule->room->capacity }} students</div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Location</small>
                                <div class="fw-medium">{{ $schedule->room->location ?? 'Not specified' }}</div>
                            </div>
                        </div>
                    @else
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-lg bg-light rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-door-open text-muted fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 text-muted">No Room Assigned</h5>
                                <p class="text-muted mb-0">This schedule does not have a specific room assigned</p>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Room assignment is not required for this schedule. The teacher will use available classroom space.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Created By Information -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-user-cog me-2"></i>Created By</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg bg-dark rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-user-cog text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $schedule->createdBy->name ?? 'System' }}</h5>
                            <p class="text-muted mb-0">{{ $schedule->createdBy->email ?? 'system@cnhs.edu.ph' }}</p>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-12">
                            <small class="text-muted">Created</small>
                            <div class="fw-medium">{{ $schedule->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">Last Updated</small>
                            <div class="fw-medium">{{ $schedule->updated_at->format('M d, Y h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 60px;
    height: 60px;
}

.avatar-sm {
    width: 32px;
    height: 32px;
}
</style>
@endsection
