@extends('layouts.admin')
@section('title', 'Room Details')
@section('content')
<div class="container-fluid">
    <!-- Modern Angled Header Card -->
    <div class="angled-header-card mb-4">
        <div class="header-left-content">
            <span class="icon"><i class="fas fa-door-open"></i></span>
            <div>
                <span class="title">Room Details</span>
                <span class="subtitle">{{ $room->name }} ({{ $room->code }})</span>
            </div>
        </div>
        <div class="header-right-content">
            <a href="{{ route('admin.rooms.edit', $room) }}" class="angled-header-btn me-2">
                <i class="fas fa-edit me-2"></i> Edit Room
            </a>
            <a href="{{ route('admin.rooms.index') }}" class="angled-header-btn">
                <i class="fas fa-arrow-left me-2"></i> Back to Rooms
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Room Information -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Room Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Room Name</label>
                            <div class="p-2 bg-light rounded">
                                {{ $room->name }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Room Code</label>
                            <div class="p-2 bg-light rounded">
                                <span class="badge bg-primary fs-6">{{ $room->code }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Room Type</label>
                            <div class="p-2 bg-light rounded">
                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $room->type)) }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Capacity</label>
                            <div class="p-2 bg-light rounded">
                                <strong>{{ $room->capacity }} students</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Location</label>
                            <div class="p-2 bg-light rounded">
                                {{ $room->location ?? 'Not specified' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Status</label>
                            <div class="p-2 bg-light rounded">
                                @if($room->is_available)
                                    <span class="badge bg-success fs-6">Available</span>
                                @else
                                    <span class="badge bg-danger fs-6">Unavailable</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Created</label>
                            <div class="p-2 bg-light rounded">
                                {{ $room->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Last Updated</label>
                            <div class="p-2 bg-light rounded">
                                {{ $room->updated_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                        @if($room->notes)
                        <div class="col-12">
                            <label class="form-label fw-bold">Notes</label>
                            <div class="p-2 bg-light rounded">
                                {{ $room->notes }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Equipment -->
            @if($room->equipment && count($room->equipment) > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-tools me-2"></i>Equipment</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach($room->equipment as $equipment)
                            <div class="col-md-4">
                                <span class="badge bg-light text-dark p-2 w-100">{{ $equipment }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Special Requirements -->
            @if($room->special_requirements && count($room->special_requirements) > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Special Requirements</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach($room->special_requirements as $requirement)
                            <div class="col-md-4">
                                <span class="badge bg-warning p-2 w-100">{{ $requirement }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Room
                        </a>
                        <form action="{{ route('admin.rooms.toggle-availability', $room) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn {{ $room->is_available ? 'btn-outline-warning' : 'btn-outline-success' }} w-100">
                                <i class="fas {{ $room->is_available ? 'fa-ban' : 'fa-check' }} me-2"></i>
                                {{ $room->is_available ? 'Mark as Unavailable' : 'Mark as Available' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this room?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i>Delete Room
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedules -->
    @if($room->schedules && $room->schedules->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Current Schedules</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Day</th>
                                    <th>Time</th>
                                    <th>Teacher</th>
                                    <th>Subject</th>
                                    <th>Section</th>
                                    <th>School Year</th>
                                    <th>Grading Period</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($room->schedules as $schedule)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $schedule->day }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ date('g:i A', strtotime($schedule->start_time)) }}</strong> - 
                                            <strong>{{ date('g:i A', strtotime($schedule->end_time)) }}</strong>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $schedule->teacher->name }}</div>
                                            <small class="text-muted">{{ $schedule->teacher->email }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $schedule->subject->name }}</div>
                                            <small class="text-muted">{{ $schedule->subject->code ?? 'No Code' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $schedule->section->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $schedule->school_year }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">{{ $schedule->grading_period }}</span>
                                        </td>
                                        <td>
                                            @if($schedule->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($schedule->status == 'inactive')
                                                <span class="badge bg-secondary">Inactive</span>
                                            @else
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Current Schedules</h6>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-calendar-times fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">No schedules found</h5>
                    <p class="text-muted">This room is not currently scheduled for any classes.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
