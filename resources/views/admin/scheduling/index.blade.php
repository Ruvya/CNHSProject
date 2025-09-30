@extends('layouts.admin')
@section('title', 'Class Scheduling Management')
@section('content')
<div class="container-fluid">
    <!-- Modern Angled Header Card -->
    <div class="angled-header-card mb-4">
        <div class="header-left-content">
            <span class="icon"><i class="fas fa-calendar-alt"></i></span>
            <div>
                <span class="title">Class Scheduling Management</span>
                <span class="subtitle">Manage class schedules, rooms, and time slots{{ isset($schoolYear) && isset($gradingPeriod) ? ' for ' . $schoolYear . ' - ' . $gradingPeriod : '' }}</span>
            </div>
        </div>
        <div class="header-right-content">
            <a href="{{ route('admin.scheduling.create') }}" class="angled-header-btn">
                <i class="fas fa-plus me-2"></i> Create Schedule
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_schedules'] }}</div>
                    <div class="stat-label">Total Schedules</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_teachers'] }}</div>
                    <div class="stat-label">Active Teachers</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_subjects'] }}</div>
                    <div class="stat-label">Subjects</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_sections'] }}</div>
                    <div class="stat-label">Sections</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon bg-secondary">
                    <i class="fas fa-door-open"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_rooms'] }}</div>
                    <div class="stat-label">Available Rooms</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Schedules</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.scheduling.index') }}">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label for="teacher_id" class="form-label">Teacher</label>
                        <select name="teacher_id" id="teacher_id" class="form-select">
                            <option value="">All Teachers</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ (isset($selectedTeacher) && $selectedTeacher == $teacher->id) ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="subject_id" class="form-label">Subject</label>
                        <select name="subject_id" id="subject_id" class="form-select">
                            <option value="">All Subjects</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ (isset($selectedSubject) && $selectedSubject == $subject->id) ? 'selected' : '' }}>
                                    {{ $subject->code ?? '' }} - {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="section_id" class="form-label">Section</label>
                        <select name="section_id" id="section_id" class="form-select">
                            <option value="">All Sections</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ (isset($selectedSection) && $selectedSection == $section->id) ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="room_id" class="form-label">Room</label>
                        <select name="room_id" id="room_id" class="form-select">
                            <option value="">All Rooms</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ (isset($selectedRoom) && $selectedRoom == $room->id) ? 'selected' : '' }}>
                                    {{ $room->code }} - {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="day" class="form-label">Day</label>
                        <select name="day" id="day" class="form-select">
                            <option value="">All Days</option>
                            @foreach($days as $day)
                                <option value="{{ $day }}" {{ (isset($selectedDay) && $selectedDay == $day) ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="school_year" class="form-label">School Year</label>
                        <input type="text" name="school_year" id="school_year" class="form-control" 
                               value="{{ $schoolYear ?? '' }}" placeholder="e.g., 2024-2025">
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-2">
                        <label for="grading_period" class="form-label">Grading Period</label>
                        <select name="grading_period" id="grading_period" class="form-select">
                            <option value="">All Periods</option>
                            <option value="First Grading" {{ (isset($gradingPeriod) && $gradingPeriod == 'First Grading') ? 'selected' : '' }}>First Grading</option>
                            <option value="Second Grading" {{ (isset($gradingPeriod) && $gradingPeriod == 'Second Grading') ? 'selected' : '' }}>Second Grading</option>
                            <option value="Third Grading" {{ (isset($gradingPeriod) && $gradingPeriod == 'Third Grading') ? 'selected' : '' }}>Third Grading</option>
                            <option value="Fourth Grading" {{ (isset($gradingPeriod) && $gradingPeriod == 'Fourth Grading') ? 'selected' : '' }}>Fourth Grading</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.scheduling.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Schedules Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-list me-2"></i>Class Schedules</h6>
        </div>
        <div class="card-body p-0">
            @if($schedules->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Teacher</th>
                                <th>Subject</th>
                                <th>Section</th>
                                <th>Room</th>
                                <th>School Year</th>
                                <th>Grading Period</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">{{ $schedule->day }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ date('g:i A', strtotime($schedule->start_time)) }}</strong> - 
                                        <strong>{{ date('g:i A', strtotime($schedule->end_time)) }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $schedule->teacher->name }}</div>
                                                <small class="text-muted">{{ $schedule->teacher->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-medium">{{ $schedule->subject->name }}</div>
                                            <small class="text-muted">{{ $schedule->subject->code ?? 'No Code' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $schedule->section->name }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-medium">{{ $schedule->room->name }}</div>
                                            <small class="text-muted">{{ $schedule->room->code }} ({{ ucfirst(str_replace('_', ' ', $schedule->room->type)) }})</small>
                                        </div>
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
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.scheduling.show', $schedule) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.scheduling.edit', $schedule) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.scheduling.destroy', $schedule) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this schedule?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
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
                <div class="card-footer bg-light">
                    {{ $schedules->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-calendar-times fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">No schedules found</h5>
                    <p class="text-muted">Create your first class schedule to get started.</p>
                    <a href="{{ route('admin.scheduling.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Schedule
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.stat-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 15px;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

.stat-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.avatar-sm {
    width: 32px;
    height: 32px;
}
</style>
@endsection
