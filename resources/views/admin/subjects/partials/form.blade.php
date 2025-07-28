<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $subject->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="code" class="form-label">Code</label>
    <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $subject->code ?? '') }}" required>
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