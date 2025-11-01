@extends('Principal.layouts.admin')

@section('title', 'Teacher Details - Subjects & Schedules')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('principal.teachers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Teachers
        </a>
    </div>

    <!-- Teacher Information Card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-user me-2"></i>Teacher Information
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="info-item">
                        <label class="info-label">Name:</label>
                        <span class="info-value">{{ $teacher->name }}</span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="info-item">
                        <label class="info-label">Email:</label>
                        <span class="info-value">{{ $teacher->email }}</span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="info-item">
                        <label class="info-label">Subject Area:</label>
                        <span class="info-value">{{ $teacher->subject ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="info-item">
                        <label class="info-label">Track:</label>
                        <span class="info-value">{{ $teacher->track ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="info-item">
                        <label class="info-label">Cluster:</label>
                        <span class="info-value">{{ $teacher->cluster ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="info-item">
                        <label class="info-label">Status:</label>
                        <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($teacher->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects & Schedules Card -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-calendar-alt me-2"></i>Subjects & Schedules
            </h5>
        </div>
        <div class="card-body">
            @if(count($groupedSchedules) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Subject</th>
                                <th>Schedule</th>
                                <th>Time</th>
                                <th>Section</th>
                                <th>Grade Level</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupedSchedules as $schedule)
                                @php
                                    // Format days as M-W-F format
                                    $dayAbbreviations = [
                                        'Monday' => 'M',
                                        'Tuesday' => 'T',
                                        'Wednesday' => 'W',
                                        'Thursday' => 'Th',
                                        'Friday' => 'F',
                                        'Saturday' => 'Sa',
                                        'Sunday' => 'Su'
                                    ];
                                    $formattedDays = [];
                                    foreach ($schedule['days'] as $day) {
                                        $formattedDays[] = $dayAbbreviations[$day] ?? substr($day, 0, 1);
                                    }
                                    $scheduleDays = implode('-', $formattedDays);

                                    // Format time as 12:00-1:00 PM (shared AM/PM)
                                    $startTime = date('g:i', strtotime($schedule['start_time']));
                                    $endTime = date('g:i A', strtotime($schedule['end_time']));
                                    $timeRange = $startTime . '-' . $endTime;

                                    // Get grade level from section or subject
                                    $gradeLevel = $schedule['section']->grade_level ?? $schedule['subject']->grade_level ?? 'N/A';
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $schedule['subject']->name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $scheduleDays }}</td>
                                    <td>{{ $timeRange }}</td>
                                    <td>{{ $schedule['section']->name ?? 'N/A' }}</td>
                                    <td>{{ $gradeLevel }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No active schedules found for this teacher.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
@parent
<style>
    .info-item {
        display: flex;
        align-items: center;
    }

    .info-label {
        font-weight: 600;
        margin-right: 10px;
        min-width: 120px;
        color: #495057;
    }

    .info-value {
        color: #212529;
        font-size: 1rem;
    }

    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-radius: 8px;
    }

    .card-header {
        border-radius: 8px 8px 0 0 !important;
        padding: 1rem 1.5rem;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .table td {
        vertical-align: middle;
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>
@endsection

