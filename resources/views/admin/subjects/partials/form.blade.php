<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $subject->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="code" class="form-label">Code (Optional)</label>
    <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $subject->code ?? '') }}">
</div>
<div class="mb-3">
    <label for="grade_level" class="form-label">Grade Level</label>
    <input type="text" name="grade_level" id="grade_level" class="form-control" value="{{ old('grade_level', $subject->grade_level ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="track" class="form-label">Track</label>
    <input type="text" name="track" id="track" class="form-control" value="{{ old('track', $subject->track ?? '') }}">
</div>
<div class="mb-3">
    <label for="cluster" class="form-label">Cluster</label>
    <input type="text" name="cluster" id="cluster" class="form-control" value="{{ old('cluster', $subject->cluster ?? '') }}">
</div>
<div class="mb-3">
    <label for="specialization" class="form-label">Specialization</label>
    <input type="text" name="specialization" id="specialization" class="form-control" value="{{ old('specialization', $subject->specialization ?? '') }}">
</div>
<div class="mb-3">
    <label for="grading" class="form-label">Grading</label>
    <input type="text" name="grading" id="grading" class="form-control" value="{{ old('grading', $subject->grading ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="teacher_id" class="form-label">Teacher</label>
    <input type="number" name="teacher_id" id="teacher_id" class="form-control" value="{{ old('teacher_id', $subject->teacher_id ?? '') }}">
</div>
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" id="description" class="form-control">{{ old('description', $subject->description ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label for="is_core_subject" class="form-label">Is Core Subject</label>
    <input type="checkbox" name="is_core_subject" id="is_core_subject" value="1" {{ old('is_core_subject', $subject->is_core_subject ?? false) ? 'checked' : '' }}>
</div>
<div class="mb-3">
    <label for="is_master_subject" class="form-label">Is Master Subject</label>
    <input type="checkbox" name="is_master_subject" id="is_master_subject" value="1" {{ old('is_master_subject', $subject->is_master_subject ?? false) ? 'checked' : '' }}>
</div>

<!-- Schedule Section -->
<div class="mb-4">
    <h5 class="border-bottom pb-2 mb-3">Class Schedule</h5>
    
    <div class="mb-3">
        <label class="form-label">Days of the Week</label>
        <div class="row">
            @php
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $oldDays = old('schedule_days', $subject->schedule_days_array ?? []);
            @endphp
            @foreach($days as $day)
                <div class="col-md-3 col-sm-4 col-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               name="schedule_days[]" 
                               value="{{ $day }}" 
                               id="day_{{ strtolower($day) }}"
                               {{ in_array($day, $oldDays) ? 'checked' : '' }}>
                        <label class="form-check-label" for="day_{{ strtolower($day) }}">
                            {{ $day }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="start_time" class="form-label">Start Time</label>
            <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time', $subject->start_time ?? '') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label for="end_time" class="form-label">End Time</label>
            <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time', $subject->end_time ?? '') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label for="room" class="form-label">Room/Venue</label>
            <input type="text" name="room" id="room" class="form-control" value="{{ old('room', $subject->room ?? '') }}" placeholder="e.g., Room 101, Computer Lab">
        </div>
    </div>
    
    <div class="mb-3">
        <label for="schedule_notes" class="form-label">Schedule Notes</label>
        <textarea name="schedule_notes" id="schedule_notes" class="form-control" rows="2" placeholder="Additional schedule information">{{ old('schedule_notes', $subject->schedule_notes ?? '') }}</textarea>
    </div>
</div> 