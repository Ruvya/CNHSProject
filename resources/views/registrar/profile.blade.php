@extends('layouts.registrar')

@section('title', 'Profile Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Profile Settings</h1>
            <p class="text-muted">Manage your registrar account information and preferences</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Please correct the following errors:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Information -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2 text-primary"></i>
                        Profile Information
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <img src="{{ auth()->guard('registrar')->user() && auth()->guard('registrar')->user()->profile_picture ? asset('storage/' . auth()->guard('registrar')->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->guard('registrar')->user() ? auth()->guard('registrar')->user()->full_name : 'Registrar') . '&background=6366f1&color=ffffff&size=150' }}"
                             class="rounded-circle border border-3 border-primary"
                             width="150" height="150" alt="Profile Picture" id="profilePreview">
                        <div class="position-absolute bottom-0 end-0">
                            <label for="profile_picture" class="btn btn-primary btn-sm rounded-circle" style="width: 40px; height: 40px;" title="Change Profile Picture">
                                <i class="fas fa-camera"></i>
                            </label>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ auth()->guard('registrar')->user() ? auth()->guard('registrar')->user()->full_name : 'Registrar' }}</h4>
                    <p class="text-muted mb-2">{{ auth()->guard('registrar')->user() ? auth()->guard('registrar')->user()->email : 'N/A' }}</p>
                    <span class="badge bg-success">Active Registrar</span>

                    <hr class="my-3">

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h5 class="text-primary">{{ \App\Models\Student::count() }}</h5>
                                <small class="text-muted">Total Students</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="text-success">{{ \App\Models\Subject::count() }}</h5>
                            <small class="text-muted">Total Subjects</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Account Information
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <strong>Account Created:</strong><br>
                            <small class="text-muted">{{ auth()->guard('registrar')->user() ? auth()->guard('registrar')->user()->created_at->format('M d, Y \a\t g:i A') : 'N/A' }}</small>
                        </li>
                        <li class="mb-2">
                            <strong>Last Updated:</strong><br>
                            <small class="text-muted">{{ auth()->guard('registrar')->user() ? auth()->guard('registrar')->user()->updated_at->format('M d, Y \a\t g:i A') : 'N/A' }}</small>
                        </li>
                        <li class="mb-2">
                            <strong>Account Status:</strong><br>
                            <span class="badge bg-success">Active</span>
                        </li>
                        <li>
                            <strong>Role:</strong><br>
                            <span class="badge bg-primary">Registrar</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Profile Update Form -->
        <div class="col-xl-8">
            <form method="POST" action="{{ route('registrar.update-profile') }}" enctype="multipart/form-data" id="profileForm">
                @csrf

                <!-- Personal Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-edit me-2 text-warning"></i>
                            Personal Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                       id="first_name" name="first_name"
                                       value="{{ old('first_name', auth()->guard('registrar')->user() ? auth()->guard('registrar')->user()->first_name : '') }}"
                                       required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                       id="last_name" name="last_name"
                                       value="{{ old('last_name', auth()->guard('registrar')->user() ? auth()->guard('registrar')->user()->last_name : '') }}"
                                       required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email"
                                   value="{{ old('email', auth()->guard('registrar')->user() ? auth()->guard('registrar')->user()->email : '') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hidden file input for profile picture -->
                        <input type="file" id="profile_picture" name="profile_picture"
                               accept="image/*" style="display: none;" onchange="previewImage(this)">

                        <div class="mb-3">
                            <label class="form-label">Profile Picture</label>
                            <div class="d-flex align-items-center">
                                <img src="{{ auth()->guard('registrar')->user() && auth()->guard('registrar')->user()->profile_picture ? asset('storage/' . auth()->guard('registrar')->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->guard('registrar')->user() ? auth()->guard('registrar')->user()->full_name : 'Registrar') . '&background=6366f1&color=ffffff&size=60' }}"
                                     class="rounded-circle me-3" width="60" height="60" alt="Current Profile" id="currentProfilePreview">
                                <div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('profile_picture').click()">
                                        <i class="fas fa-upload me-1"></i>Choose New Picture
                                    </button>
                                    @if(auth()->guard('registrar')->user() && auth()->guard('registrar')->user()->profile_picture)
                                        <button type="button" class="btn btn-outline-danger btn-sm ms-2" onclick="removeProfilePicture()">
                                            <i class="fas fa-trash me-1"></i>Remove
                                        </button>
                                    @endif
                                    <div class="form-text">Recommended: Square image, max 2MB</div>
                                </div>
                            </div>
                            @error('profile_picture')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-shield-alt me-2 text-danger"></i>
                            Security Settings
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Password Change:</strong> Leave password fields empty if you don't want to change your password.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password" name="current_password"
                                       placeholder="Enter current password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password"
                                       placeholder="Enter new password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control"
                                       id="password_confirmation" name="password_confirmation"
                                       placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    All changes will be saved immediately upon submission.
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo me-2"></i>Reset Changes
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.profile-upload-btn {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endpush

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('profilePreview').src = e.target.result;
            document.getElementById('currentProfilePreview').src = e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function removeProfilePicture() {
    if (confirm('Are you sure you want to remove your profile picture?')) {
        // Create a hidden input to indicate profile picture removal
        const removeInput = document.createElement('input');
        removeInput.type = 'hidden';
        removeInput.name = 'remove_profile_picture';
        removeInput.value = '1';
        document.getElementById('profileForm').appendChild(removeInput);

        // Update preview images to default
        const defaultImage = 'https://ui-avatars.com/api/?name={{ urlencode(auth()->guard('registrar')->user() ? auth()->guard('registrar')->user()->full_name : 'Registrar') }}&background=6366f1&color=ffffff&size=150';
        document.getElementById('profilePreview').src = defaultImage;
        document.getElementById('currentProfilePreview').src = defaultImage.replace('150', '60');

        // Clear file input
        document.getElementById('profile_picture').value = '';
    }
}

function resetForm() {
    if (confirm('Are you sure you want to reset all changes?')) {
        document.getElementById('profileForm').reset();

        // Reset profile picture previews
        const originalImage = '{{ auth()->guard('registrar')->user() && auth()->guard('registrar')->user()->profile_picture ? asset('storage/' . auth()->guard('registrar')->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->guard('registrar')->user() ? auth()->guard('registrar')->user()->full_name : 'Registrar') . '&background=6366f1&color=ffffff&size=150' }}';
        document.getElementById('profilePreview').src = originalImage;
        document.getElementById('currentProfilePreview').src = originalImage.replace('150', '60');

        // Remove any hidden inputs for profile picture removal
        const removeInputs = document.querySelectorAll('input[name="remove_profile_picture"]');
        removeInputs.forEach(input => input.remove());
    }
}

// Auto-save draft functionality (optional)
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    const inputs = form.querySelectorAll('input[type="text"], input[type="email"]');

    inputs.forEach(input => {
        input.addEventListener('input', function() {
            // You can implement auto-save draft functionality here if needed
        });
    });
});
</script>
@endpush
@endsection