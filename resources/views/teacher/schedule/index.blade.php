@extends('layouts.teacher')

@section('title', 'My Schedule')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-header">
                <i class="fas fa-calendar-alt"></i>
                <div>
                    <h1>My Schedule</h1>
                    <p>View your classes by semester and school year.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('teacher.schedule') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">School Year</label>
                    <input type="text" name="school_year" value="{{ $schoolYear }}" class="form-control" placeholder="e.g. {{ $schoolYearDefault }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-control">
                        @foreach($semesterOptions as $opt)
                            <option value="{{ $opt }}" {{ $semester === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Day</label>
                    <select name="day" class="form-control">
                        <option value="">All Days</option>
                        @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $d)
                            <option value="{{ $d }}" {{ $day === $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Apply</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Schedule for {{ $semester }} - {{ $schoolYear }}</h6>
        </div>
        <div class="card-body">
            @if($schedules->isEmpty())
                <div class="alert alert-info mb-0">
                    No schedules found for the selected filters.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="white-space:nowrap;">Day</th>
                                <th>Time</th>
                                <th>Subject</th>
                                <th>Section</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $sched)
                                <tr>
                                    <td style="white-space:nowrap;">{{ $sched->day }}</td>
                                    <td style="white-space:nowrap;">{{ date('g:i A', strtotime($sched->start_time)) }} - {{ date('g:i A', strtotime($sched->end_time)) }}</td>
                                    <td>
                                        @if($sched->subject)
                                            <strong>{{ $sched->subject->code }}</strong><br>
                                            <small class="text-muted">{{ $sched->subject->name }}</small>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $sched->section ? $sched->section->name : '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


