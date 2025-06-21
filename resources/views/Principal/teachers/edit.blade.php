@extends('Principal.layouts.admin')

@section('title', 'Edit Teacher')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Teacher</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('principal.teachers.update', $teacher) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $teacher->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email', $teacher->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="text" class="form-control @error('contact_number') is-invalid @enderror"
                                id="contact_number" name="contact_number" value="{{ old('contact_number', $teacher->contact_number) }}" required>
                            @error('contact_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                id="address" name="address" rows="3" required>{{ old('address', $teacher->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                id="subject" name="subject" value="{{ old('subject', $teacher->subject) }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="strand" class="form-label">Strand (Optional)</label>
                            <select class="form-control @error('strand') is-invalid @enderror" id="strand" name="strand">
                                <option value="">Select Strand</option>
                                <option value="STEM" {{ old('strand', $teacher->strand) === 'STEM' ? 'selected' : '' }}>STEM</option>
                                <option value="ABM" {{ old('strand', $teacher->strand) === 'ABM' ? 'selected' : '' }}>ABM</option>
                                <option value="HUMSS" {{ old('strand', $teacher->strand) === 'HUMSS' ? 'selected' : '' }}>HUMSS</option>
                                <option value="GAS" {{ old('strand', $teacher->strand) === 'GAS' ? 'selected' : '' }}>GAS</option>
                                <option value="TVL-HE" {{ old('strand', $teacher->strand) === 'TVL-HE' ? 'selected' : '' }}>TVL - Home Economics</option>
                                <option value="TVL-ICT" {{ old('strand', $teacher->strand) === 'TVL-ICT' ? 'selected' : '' }}>TVL - ICT</option>
                                <option value="TVL-IA" {{ old('strand', $teacher->strand) === 'TVL-IA' ? 'selected' : '' }}>TVL - Industrial Arts</option>
                                <option value="TVL-AFA" {{ old('strand', $teacher->strand) === 'TVL-AFA' ? 'selected' : '' }}>TVL - Agri-Fishery Arts</option>
                            </select>
                            @error('strand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $teacher->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $teacher->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>



                        <div class="d-flex justify-content-between">
                            <a href="{{ route('principal.teachers.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="updateBtn">
                                <span class="btn-text">Update Teacher</span>
                                <span class="btn-loading d-none">
                                    <i class="fas fa-spinner fa-spin"></i> Updating...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const updateBtn = document.getElementById('updateBtn');
    const btnText = updateBtn.querySelector('.btn-text');
    const btnLoading = updateBtn.querySelector('.btn-loading');

    // Handle form submission
    form.addEventListener('submit', function(e) {
        // Show loading state
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
        updateBtn.disabled = true;

        // Store original form data for comparison
        const formData = new FormData(form);
        const originalData = {};
        for (let [key, value] of formData.entries()) {
            originalData[key] = value;
        }

        // Add a small delay to show the loading state
        setTimeout(() => {
            // The form will submit normally after this
        }, 100);
    });

    // Handle form validation errors (restore button state)
    @if($errors->any())
        btnText.classList.remove('d-none');
        btnLoading.classList.add('d-none');
        updateBtn.disabled = false;
    @endif

    // Add real-time validation feedback
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            // Remove invalid class when user starts typing
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
                const feedback = this.parentNode.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.style.display = 'none';
                }
            }
        });
    });

    // Show success message if redirected back with success
    @if(session('success'))
        showAlert('success', '{{ session('success') }}');
    @endif

    @if(session('error'))
        showAlert('danger', '{{ session('error') }}');
    @endif

    // Function to show alert messages
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.style.opacity = '0';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        const container = document.querySelector('.container-fluid');
        container.insertBefore(alertDiv, container.children[0]);

        // Animate the alert
        setTimeout(() => {
            alertDiv.style.transition = 'opacity 0.3s ease';
            alertDiv.style.opacity = '1';
        }, 10);

        // Auto-dismiss the alert after 5 seconds
        setTimeout(() => {
            alertDiv.style.opacity = '0';
            setTimeout(() => alertDiv.remove(), 300);
        }, 5000);
    }
});
</script>
@endsection