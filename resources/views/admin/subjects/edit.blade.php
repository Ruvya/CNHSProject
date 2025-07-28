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
            gradeLevelSelect.value = 'Grade 11';
            gradeLevelSelect.style.backgroundColor = '#e9ecef';
            gradeLevelSelect.style.pointerEvents = 'none';
            trackSelect.value = 'All';
            trackSelect.style.backgroundColor = '#e9ecef';
            trackSelect.style.pointerEvents = 'none';
            clusterSelect.value = 'All';
            clusterSelect.style.backgroundColor = '#e9ecef';
            clusterSelect.style.pointerEvents = 'none';
            gradingSelect.value = 'All Gradings';
            gradingSelect.style.backgroundColor = '#e9ecef';
            gradingSelect.style.pointerEvents = 'none';
            // Disable and uncheck Elective Subject
            electiveSubjectCheckbox.checked = false;
            electiveSubjectCheckbox.setAttribute('disabled', 'disabled');
        } else {
            gradeLevelSelect.style.backgroundColor = '';
            gradeLevelSelect.style.pointerEvents = '';
            trackSelect.style.backgroundColor = '';
            trackSelect.style.pointerEvents = '';
            clusterSelect.style.backgroundColor = '';
            clusterSelect.style.pointerEvents = '';
            gradingSelect.style.backgroundColor = '';
            gradingSelect.style.pointerEvents = '';
            // Enable Elective Subject
            electiveSubjectCheckbox.removeAttribute('disabled');
        }
    });

    // Elective Subject logic
    electiveSubjectCheckbox.addEventListener('change', function() {
        if (this.checked) {
            // Disable and uncheck Core Subject
            coreSubjectCheckbox.checked = false;
            coreSubjectCheckbox.setAttribute('disabled', 'disabled');
        } else {
            // Enable Core Subject
            coreSubjectCheckbox.removeAttribute('disabled');
        }
    });

    // On page load, if checked, apply logic
    if (coreSubjectCheckbox.checked) {
        gradeLevelSelect.value = 'Grade 11';
        gradeLevelSelect.style.backgroundColor = '#e9ecef';
        gradeLevelSelect.style.pointerEvents = 'none';
        trackSelect.value = 'All';
        trackSelect.style.backgroundColor = '#e9ecef';
        trackSelect.style.pointerEvents = 'none';
        clusterSelect.value = 'All';
        clusterSelect.style.backgroundColor = '#e9ecef';
        clusterSelect.style.pointerEvents = 'none';
        gradingSelect.value = 'All Gradings';
        gradingSelect.style.backgroundColor = '#e9ecef';
        gradingSelect.style.pointerEvents = 'none';
        // Disable and uncheck Elective Subject
        electiveSubjectCheckbox.checked = false;
        electiveSubjectCheckbox.setAttribute('disabled', 'disabled');
    } else if (electiveSubjectCheckbox.checked) {
        // Disable and uncheck Core Subject
        coreSubjectCheckbox.checked = false;
        coreSubjectCheckbox.setAttribute('disabled', 'disabled');
    } else {
        electiveSubjectCheckbox.removeAttribute('disabled');
        coreSubjectCheckbox.removeAttribute('disabled');
    }

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