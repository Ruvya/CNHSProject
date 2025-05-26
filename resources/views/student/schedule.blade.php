@extends('layouts.student')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">My Class Schedule</h4>
                </div>

                <div class="card-body">
                    @if($subjects->isEmpty())
                        <div class="alert alert-info">
                            No schedule information available for your subjects.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Monday</th>
                                        <th>Tuesday</th>
                                        <th>Wednesday</th>
                                        <th>Thursday</th>
                                        <th>Friday</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $timeSlots = [
                                            '7:00 AM - 8:00 AM',
                                            '8:00 AM - 9:00 AM',
                                            '9:00 AM - 10:00 AM',
                                            '10:00 AM - 11:00 AM',
                                            '11:00 AM - 12:00 PM',
                                            '1:00 PM - 2:00 PM',
                                            '2:00 PM - 3:00 PM',
                                            '3:00 PM - 4:00 PM',
                                            '4:00 PM - 5:00 PM'
                                        ];
                                    @endphp

                                    @foreach($timeSlots as $timeSlot)
                                        <tr>
                                            <td>{{ $timeSlot }}</td>
                                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                                                <td>
                                                    @php
                                                        $subject = $subjects->first(function($subject) use ($timeSlot, $day) {
                                                            return str_contains($subject->schedule, $day) && str_contains($subject->schedule, $timeSlot);
                                                        });
                                                    @endphp
                                                    @if($subject)
                                                        <div class="schedule-item">
                                                            <strong>{{ $subject->code }}</strong><br>
                                                            {{ $subject->name }}<br>
                                                            <small>{{ $subject->teacher->name ?? 'TBA' }}</small>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.schedule-item {
    padding: 5px;
    border-radius: 4px;
    background-color: #f8f9fa;
    margin-bottom: 5px;
}
</style>
@endsection 