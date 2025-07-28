<div class="mb-3">
    <label for="teacher_id" class="form-label">Teacher</label>
    <input type="number" name="teacher_id" id="teacher_id" class="form-control" value="{{ old('teacher_id', $subjectAssignment->teacher_id ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="subject_id" class="form-label">Subject</label>
    <input type="number" name="subject_id" id="subject_id" class="form-control" value="{{ old('subject_id', $subjectAssignment->subject_id ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="school_year" class="form-label">School Year</label>
    <input type="text" name="school_year" id="school_year" class="form-control" value="{{ old('school_year', $subjectAssignment->school_year ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="grading_period" class="form-label">Grading Period</label>
    <input type="text" name="grading_period" id="grading_period" class="form-control" value="{{ old('grading_period', $subjectAssignment->grading_period ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <input type="text" name="status" id="status" class="form-control" value="{{ old('status', $subjectAssignment->status ?? '') }}" required>
</div> 