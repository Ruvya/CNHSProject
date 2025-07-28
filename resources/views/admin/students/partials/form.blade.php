<div class="mb-3">
    <label for="student_id" class="form-label">Student ID</label>
    <input type="text" name="student_id" id="student_id" class="form-control" value="{{ old('student_id', $student->student_id ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="first_name" class="form-label">First Name</label>
    <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name', $student->first_name ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="last_name" class="form-label">Last Name</label>
    <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name', $student->last_name ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $student->email ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" name="password" id="password" class="form-control" {{ isset($student) ? '' : 'required' }}>
    @if(isset($student))
        <small class="form-text text-muted">Leave blank to keep current password.</small>
    @endif
</div>
<div class="mb-3">
    <label for="grade_level" class="form-label">Grade Level</label>
    <input type="text" name="grade_level" id="grade_level" class="form-control" value="{{ old('grade_level', $student->grade_level ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="gender" class="form-label">Gender</label>
    <select name="gender" id="gender" class="form-control" required>
        <option value="">Select Gender</option>
        <option value="Male" {{ old('gender', $student->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
        <option value="Female" {{ old('gender', $student->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
    </select>
</div>
<div class="mb-3">
    <label for="contact_number" class="form-label">Contact Number</label>
    <input type="text" name="contact_number" id="contact_number" class="form-control" value="{{ old('contact_number', $student->contact_number ?? '') }}">
</div>
<div class="mb-3">
    <label for="address" class="form-label">Address</label>
    <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $student->address ?? '') }}">
</div>
<div class="mb-3">
    <label for="parent_name" class="form-label">Parent Name</label>
    <input type="text" name="parent_name" id="parent_name" class="form-control" value="{{ old('parent_name', $student->parent_name ?? '') }}">
</div>
<div class="mb-3">
    <label for="parent_contact" class="form-label">Parent Contact</label>
    <input type="text" name="parent_contact" id="parent_contact" class="form-control" value="{{ old('parent_contact', $student->parent_contact ?? '') }}">
</div>
<div class="mb-3">
    <label for="track" class="form-label">Track</label>
    <input type="text" name="track" id="track" class="form-control" value="{{ old('track', $student->track ?? '') }}">
</div>
<div class="mb-3">
    <label for="strand" class="form-label">Strand</label>
    <input type="text" name="strand" id="strand" class="form-control" value="{{ old('strand', $student->strand ?? '') }}">
</div>
<div class="mb-3">
    <label for="section" class="form-label">Section</label>
    <input type="text" name="section" id="section" class="form-control" value="{{ old('section', $student->section ?? '') }}">
</div>
<div class="mb-3">
    <label for="lrn" class="form-label">LRN</label>
    <input type="text" name="lrn" id="lrn" class="form-control" value="{{ old('lrn', $student->lrn ?? '') }}">
</div> 