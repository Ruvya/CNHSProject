@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-circle me-2"></i>My Profile
        </h1>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Information Card -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-edit me-2"></i>Profile Information
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $admin->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                           id="username" name="username" value="{{ old('username', $admin->username) }}" required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                           id="first_name" name="first_name" value="{{ old('first_name', $admin->first_name) }}">
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                           id="last_name" name="last_name" value="{{ old('last_name', $admin->last_name) }}">
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $admin->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', $admin->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                              id="address" name="address" rows="3">{{ old('address', $admin->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Profile Picture -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="profile_picture" class="form-label">Profile Picture</label>
                                    <input type="file" class="form-control @error('profile_picture') is-invalid @enderror"
                                           id="profile_picture" name="profile_picture" accept="image/*">
                                    @error('profile_picture')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Accepted formats: JPEG, PNG, JPG, GIF. Maximum size: 2MB.
                                    </small>
                                    @if($admin->profile_picture)
                                        <div class="mt-2">
                                            <label class="form-check-label">
                                                <input type="checkbox" name="remove_profile_picture" value="1" class="form-check-input">
                                                Remove current profile picture
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Password Change Section -->
                        <hr class="my-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-lock me-2"></i>Change Password (Optional)
                        </h6>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                           id="current_password" name="current_password">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control"
                                           id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Picture Preview Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-image me-2"></i>Profile Picture
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="profile-picture-container mb-3">
                        @if($admin->profile_picture)
                            <img src="{{ asset('storage/' . $admin->profile_picture) }}"
                                 alt="Profile Picture"
                                 class="rounded-circle img-fluid"
                                 style="width: 150px; height: 150px; object-fit: cover;"
                                 id="profilePicturePreview">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->name) }}&size=150&background=007bff&color=ffffff&bold=true"
                                 alt="Profile Picture"
                                 class="rounded-circle img-fluid"
                                 style="width: 150px; height: 150px; object-fit: cover;"
                                 id="profilePicturePreview">
                        @endif
                    </div>

                    <h5 class="mb-1">{{ $admin->name }}</h5>
                    <p class="text-muted mb-3">{{ $admin->email }}</p>

                    <!-- Quick Upload Buttons -->
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('quickUploadInput').click()">
                            <i class="fas fa-camera me-1"></i>Change Picture
                        </button>
                        @if($admin->profile_picture)
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeProfilePicture()">
                                <i class="fas fa-trash me-1"></i>Remove Picture
                            </button>
                        @endif
                    </div>

                    <!-- Hidden file input for quick upload -->
                    <input type="file" id="quickUploadInput" name="profile_picture" accept="image/*" style="display: none;" onchange="quickUploadImage(this)">
                </div>
            </div>

            <!-- Account Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle me-2"></i>Account Information
                    </h6>
                </div>
                <div class="card-body">
                    <p><strong>User ID:</strong> {{ $admin->id }}</p>
                    <p><strong>Role:</strong> Administrator</p>
                    <p><strong>Account Created:</strong> {{ $admin->created_at->format('M d, Y') }}</p>
                    <p><strong>Last Updated:</strong> {{ $admin->updated_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function quickUploadImage(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('profile_picture', input.files[0]);
        formData.append('_token', '{{ csrf_token() }}');

        // Show loading state
        const preview = document.getElementById('profilePicturePreview');
        const originalSrc = preview.src;

        fetch('{{ route("admin.profile.upload-picture") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the profile picture preview
                preview.src = data.profile_picture_url;

                // Update any other profile pictures on the page
                const allProfilePictures = document.querySelectorAll('img[alt="Profile Picture"]');
                allProfilePictures.forEach(img => {
                    if (img !== preview) {
                        img.src = data.profile_picture_url;
                    }
                });

                // Show success message
                showAlert('success', data.message);

                // Refresh the page to show remove button if it wasn't there before
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showAlert('error', data.message || 'Failed to upload profile picture');
                preview.src = originalSrc;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while uploading the profile picture');
            preview.src = originalSrc;
        });
    }
}

function removeProfilePicture() {
    if (confirm('Are you sure you want to remove your profile picture?')) {
        fetch('{{ route("admin.profile.remove-picture") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the profile picture preview to default avatar
                const preview = document.getElementById('profilePicturePreview');
                preview.src = 'https://ui-avatars.com/api/?name={{ urlencode($admin->name) }}&size=150&background=007bff&color=ffffff&bold=true';

                showAlert('success', data.message);

                // Refresh the page to hide remove button
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showAlert('error', data.message || 'Failed to remove profile picture');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while removing the profile picture');
        });
    }
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    const container = document.querySelector('.container-fluid');
    const firstChild = container.children[1]; // Insert after the header
    container.insertBefore(alertDiv, firstChild);

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Preview image when selected in the main form
document.getElementById('profile_picture').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePicturePreview').src = e.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endpush