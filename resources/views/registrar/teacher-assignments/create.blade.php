@extends('layouts.registrar')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-user-plus me-2 text-primary"></i>
                Assign Teacher to Subject
            </h1>
            <p class="text-muted mb-0">Assign a teacher to a subject in as few steps as possible.</p>
        </div>
        <div>
            <a href="{{ route('registrar.teacher-assignments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Assignments
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Assignment Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-form me-2"></i>Assignment Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('registrar.teacher-assignments.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="teacher_id" class="form-label">
                                <i class="fas fa-user me-1"></i>Select Teacher <span class="text-danger">*</span>
                            </label>
                            <select name="teacher_id" id="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                                <option value="">Choose a teacher...</option>
                                @foreach($teachers as $teacherOption)
                                    <option value="{{ $teacherOption->id }}">{{ $teacherOption->name }}</option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="subject_id" class="form-label">
                                <i class="fas fa-book me-1"></i>Select Subject <span class="text-danger">*</span>
                            </label>
                            <select name="subject_id" id="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                                <option value="">Choose a subject...</option>
                                @foreach($subjects as $subjectOption)
                                    <option value="{{ $subjectOption->id }}">{{ $subjectOption->name }} ({{ $subjectOption->code }})</option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Assign Teacher to Subject</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Assignment Guidelines -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 text-info">
                        <i class="fas fa-lightbulb me-2"></i>Assignment Guidelines
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-check-circle me-1"></i>Assignment Rules:</h6>
                        <ul class="mb-0">
                            <li>Teachers can be assigned to <strong>multiple subjects</strong></li>
                            <li>Assignment is immediate and simple</li>
                        </ul>
                    </div>
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-1"></i>Important Notes:</h6>
                        <ul class="mb-0">
                            <li>Check teacher's subject expertise if needed</li>
                            <li>Assignment date is automatically recorded</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Selected Teacher Info -->
            <div class="card mt-3" id="teacherInfoCard" style="display: none;">
                <div class="card-header">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-user me-2"></i>Selected Teacher
                    </h5>
                </div>
                <div class="card-body" id="teacherInfo">
                    <!-- Teacher information will be populated here -->
                </div>
            </div>

            <!-- Selected Subject Info -->
            <div class="card mt-3" id="subjectInfoCard" style="display: none;">
                <div class="card-header">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-book me-2"></i>Selected Subject
                    </h5>
                </div>
                <div class="card-body" id="subjectInfo">
                    <!-- Subject information will be populated here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const teacherSelect = document.getElementById('teacher_id');
    const subjectSelect = document.getElementById('subject_id');
    const teacherInfoCard = document.getElementById('teacherInfoCard');
    const subjectInfoCard = document.getElementById('subjectInfoCard');
    const teacherInfo = document.getElementById('teacherInfo');
    const subjectInfo = document.getElementById('subjectInfo');

    // Handle teacher selection
    teacherSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (this.value) {
            const name = selectedOption.text;

            teacherInfo.innerHTML = `
                <p><strong>Name:</strong> ${name}</p>
            `;
            teacherInfoCard.style.display = 'block';
        } else {
            teacherInfoCard.style.display = 'none';
        }
    });

    // Handle subject selection
    subjectSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (this.value) {
            const name = selectedOption.text;

            subjectInfo.innerHTML = `
                <p><strong>Subject:</strong> ${name}</p>
            `;
            subjectInfoCard.style.display = 'block';
        } else {
            subjectInfoCard.style.display = 'none';
        }
    });

    // Handle form submission with loading state
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('button[type="submit"]');
    const loadingOverlay = document.getElementById('loadingOverlay');

    form.addEventListener('submit', function(e) {
        // Validate required fields
        const teacherId = teacherSelect.value;
        const subjectId = subjectSelect.value;

        if (!teacherId) {
            e.preventDefault();
            alert('Please select a teacher.');
            teacherSelect.focus();
            return;
        }

        if (!subjectId) {
            e.preventDefault();
            alert('Please select a subject.');
            subjectSelect.focus();
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Assigning...';
        loadingOverlay.classList.remove('d-none');

        // Store assignment details in sessionStorage for potential display
        const teacherName = teacherSelect.options[teacherSelect.selectedIndex].text;
        const subjectName = subjectSelect.options[subjectSelect.selectedIndex].text;

        sessionStorage.setItem('pending_assignment', JSON.stringify({
            teacher_name: teacherName,
            subject_name: subjectName
        }));
    });

    // Trigger change events if values are pre-selected
    if (teacherSelect.value) {
        teacherSelect.dispatchEvent(new Event('change'));
    }
    if (subjectSelect.value) {
        subjectSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
