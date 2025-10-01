@extends('layouts.admin')
@section('title', 'Edit Subject')
@section('content')
<div class="container">
    <h1>Edit Subject</h1>
    <form action="{{ route('admin.subjects.update', $subject) }}" method="POST" id="subjectForm">
        @csrf
        @method('PUT')
        @include('admin.subjects.partials.form')
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const coreSubjectCheckbox = document.getElementById('is_core_subject');
    const electiveSubjectCheckbox = document.getElementById('is_master_subject');
    const gradeLevelSelect = document.getElementById('grade_level');
    const trackSelect = document.getElementById('track');
    const clusterSelect = document.getElementById('cluster');
    const gradingSelect = document.getElementById('grading');

    // Core Subject auto-assign logic
    coreSubjectCheckbox.addEventListener('change', function() {
        if (this.checked) {
            // Auto-assign default values but keep fields enabled and editable
            if (!gradeLevelSelect.value) gradeLevelSelect.value = 'Grade 11';
            if (!trackSelect.value) trackSelect.value = 'All';
            if (!clusterSelect.value) clusterSelect.value = 'All';
            if (!gradingSelect.value) gradingSelect.value = 'All Gradings';
            // Note: All fields remain enabled and editable
            // Note: Elective Subject remains enabled and clickable
        }
        // Fields remain fully functional regardless of Core Subject selection
    });

    // Elective Subject logic - now independent of Core Subject
    electiveSubjectCheckbox.addEventListener('change', function() {
        // Elective subjects can be selected independently
        // No longer disables Core Subject checkbox
    });

    // On page load, if checked, apply logic
    if (coreSubjectCheckbox.checked) {
        // Auto-assign default values but keep fields enabled and editable
        if (!gradeLevelSelect.value) gradeLevelSelect.value = 'Grade 11';
        if (!trackSelect.value) trackSelect.value = 'All';
        if (!clusterSelect.value) clusterSelect.value = 'All';
        if (!gradingSelect.value) gradingSelect.value = 'All Gradings';
        // Note: All fields remain enabled and editable
        // Note: Elective Subject remains enabled and clickable
    }
    // Both checkboxes remain enabled and independent
    // All curriculum fields remain fully functional

    // Form validation
    document.getElementById('subjectForm').addEventListener('submit', function(e) {
        const requiredFields = ['name', 'code'];
        let isValid = true;

        // Always validate name and code
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Only validate grade_level, track, and grading if Core Subject is NOT checked
        if (!coreSubjectCheckbox.checked) {
            const conditionalFields = ['grade_level', 'track', 'grading'];
            conditionalFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
        } else {
            // If Core Subject is checked, ensure disabled fields have their values
            // and remove any invalid styling
            document.getElementById('grade_level').classList.remove('is-invalid');
            document.getElementById('track').classList.remove('is-invalid');
            document.getElementById('grading').classList.remove('is-invalid');
        }

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
});
</script>
@endpush