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
    <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
    <select name="semester" id="semester" class="form-select" required>
        <option value="">Select Semester</option>
        <option value="1st Semester" {{ old('semester', $subjectAssignment->semester ?? '') == '1st Semester' ? 'selected' : '' }}>1st Semester</option>
        <option value="2nd Semester" {{ old('semester', $subjectAssignment->semester ?? '') == '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
    </select>
    <div class="form-text">
        <i class="fas fa-info-circle text-primary me-1"></i>
        <strong>Important:</strong> Ensure the semester matches the subject's designated semester for proper grade management.
    </div>
</div>
<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <input type="text" name="status" id="status" class="form-control" value="{{ old('status', $subjectAssignment->status ?? '') }}" required>
</div> 