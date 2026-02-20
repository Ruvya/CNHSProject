@extends('layouts.teacher')

@section('title', 'Profile')

@section('styles')
<style>
    /* New Header Style */
    .page-header-design {
        background: linear-gradient(115deg, #f97316 60%, #3b82f6 60%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(249, 115, 22, 0.25);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header-left-content {
        display: flex;
        align-items: center;
    }
    .header-left-content i {
        font-size: 3rem;
        margin-right: 1.5rem;
        opacity: 0.8;
    }
    .header-left-content h1 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .header-left-content p {
        margin: 0.25rem 0 0 0;
        opacity: 0.9;
        font-size: 1.1rem;
    }
    .header-right-content .profile-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.75rem 1.25rem;
        border-radius: 50px;
        display: flex;
        align-items: center;
        font-weight: 600;
        font-size: 1rem;
        backdrop-filter: blur(10px);
    }
    .header-right-content .profile-badge i {
        margin-right: 0.5rem;
    }

    .card-header {
        border-bottom: 1px solid #e3e6f0;
    }
    .fw-bold {
        font-weight: 600;
    }
    .profile-image {
        object-fit: cover;
        transition: all 0.3s ease;
        width: 100px !important;
        height: 100px !important;
        max-width: 100px;
        max-height: 100px;
    }
    .profile-image:hover {
        transform: scale(1.05);
    }
    .position-relative:hover .position-absolute {
        opacity: 1;
    }
    .position-absolute {
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
    .profile-picture-card-body {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }
    .profile-picture-card .card-body {
        min-height: unset !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-design">
                <div class="header-left-content">
                    <i class="fas fa-user-circle"></i>
                    <div>
                        <h1>My Profile</h1>
                        <p>Manage your personal information and account settings</p>
                    </div>
                </div>
                <div class="header-right-content">
                    <div class="profile-badge">
                        <i class="fas fa-user"></i>
                        <span>{{ ucfirst($teacher->status ?? 'Active') }} Status</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Picture Section -->
        <div class="col-xl-4">
            <div class="card mb-4 profile-picture-card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>Profile Picture
                    </h5>
                </div>
                <div class="card-body text-center profile-picture-card-body">
                    <div class="position-relative d-inline-block mb-2">
                        <img src="{{ $teacher->profile_picture ? asset('storage/' . $teacher->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($teacher->name) . '&background=4e73df&color=ffffff&size=120' }}"
                             class="rounded-circle border border-3 border-primary profile-image"
                             width="120" height="120" alt="Profile Picture" id="profilePreview">
                        <div class="position-absolute bottom-0 end-0">
                            <button type="button" class="btn btn-primary btn-sm rounded-circle" onclick="document.getElementById('profilePictureInput').click()">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                    </div>
                    <h5 class="mb-1">{{ $teacher->name }}</h5>
                    <p class="text-muted mb-2">{{ $teacher->email }}</p>

                    <!-- Hidden file input -->
                    <input type="file" id="profilePictureInput" name="profile_picture" accept="image/jpeg,image/png,image/jpg" style="display: none;" onchange="previewAndUploadImage(this)">

                    @if($teacher->profile_picture)
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeProfilePicture()">
                            <i class="fas fa-trash me-1"></i>Remove Picture
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Full Name:</label>
                            <div class="p-2 bg-light rounded">{{ $teacher->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email:</label>
                            <div class="p-2 bg-light rounded">{{ $teacher->email }}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Contact Number:</label>
                            <div class="p-2 bg-light rounded">{{ $teacher->contact_number ?? 'Not provided' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Address:</label>
                            <div class="p-2 bg-light rounded">{{ $teacher->address ?? 'Not provided' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Remove the Academic Information card entirely -->
        </div>
        <div class="col-xl-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Profile Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.update-profile') }}" enctype="multipart/form-data" id="profileForm">
                        @csrf

                        <!-- Basic Information -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $teacher->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $teacher->email) }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" class="form-control @error('contact_number') is-invalid @enderror"
                                       id="contact_number" name="contact_number" value="{{ old('contact_number', $teacher->contact_number) }}">
                                @error('contact_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                       id="address" name="address" value="{{ old('address', $teacher->address) }}">
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Profile Picture Upload -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="profile_picture_form" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control @error('profile_picture') is-invalid @enderror"
                                       id="profile_picture_form" name="profile_picture" accept="image/jpeg,image/png,image/jpg"
                                       onchange="previewImageInForm(this)">
                                <div class="form-text">Accepted formats: JPG, PNG. Maximum size: 2MB.</div>
                                @error('profile_picture')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <!-- Image Preview -->
                                <div id="imagePreviewContainer" class="mt-2" style="display: none;">
                                    <img id="imagePreview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                </div>
                            </div>
                        </div>

                        <!-- Password Change Section -->
                        <hr>
                        <h6 class="mb-3">Change Password (Optional)</h6>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password" name="current_password">
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control"
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 mb-0">Uploading image...</p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Preview and upload image via camera button
function previewAndUploadImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Validate file type
        if (!file.type.match('image/jpeg') && !file.type.match('image/png') && !file.type.match('image/jpg')) {
            alert('Please select a valid image file (JPG, PNG).');
            return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB.');
            return;
        }

        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePreview').src = e.target.result;
        };
        reader.readAsDataURL(file);

        // Upload image via AJAX
        uploadProfilePicture(file);
    }
}

// Preview image in form
function previewImageInForm(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Validate file type
        if (!file.type.match('image/jpeg') && !file.type.match('image/png') && !file.type.match('image/jpg')) {
            alert('Please select a valid image file (JPG, PNG).');
            input.value = '';
            return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreviewContainer').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('imagePreviewContainer').style.display = 'none';
    }
}

// Upload profile picture via AJAX
function uploadProfilePicture(file) {
    const formData = new FormData();
    formData.append('profile_picture', file);
    formData.append('_token', '{{ csrf_token() }}');

    // Show loading modal
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    loadingModal.show();

    fetch('{{ route("teacher.profile.upload") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        loadingModal.hide();
        if (data.success) {
            // Show success message
            showAlert('success', data.message);
            // Update profile image
            document.getElementById('profilePreview').src = data.image_url;
            // Reload page to show remove button if it wasn't there before
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('danger', 'Failed to upload image. Please try again.');
        }
    })
    .catch(error => {
        loadingModal.hide();
        console.error('Error:', error);
        showAlert('danger', 'An error occurred while uploading the image.');
    });
}

// Remove profile picture
function removeProfilePicture() {
    if (confirm('Are you sure you want to remove your profile picture?')) {
        // Create a hidden input to indicate profile picture removal
        const removeInput = document.createElement('input');
        removeInput.type = 'hidden';
        removeInput.name = 'remove_profile_picture';
        removeInput.value = '1';
        document.getElementById('profileForm').appendChild(removeInput);

        // Submit the form
        document.getElementById('profileForm').submit();
    }
}

// Show alert messages
function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    // Insert alert at the top of the container
    const container = document.querySelector('.container-fluid');
    const firstChild = container.children[1]; // After the title row
    firstChild.insertAdjacentHTML('afterend', alertHtml);

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}
</script>
@endsection