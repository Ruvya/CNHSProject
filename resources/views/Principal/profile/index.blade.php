@extends('Principal.layouts.admin')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Profile Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: #e3edfa;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            <div class="profile-picture-container position-relative d-inline-block mb-3">
                                <img src="{{ $principal->profile_picture ? asset('storage/' . $principal->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($principal->name) . '&size=150&background=007bff&color=ffffff&bold=true' }}"
                                     alt="{{ $principal->name }}"
                                     class="rounded-circle border border-3 border-primary shadow"
                                     style="width: 150px; height: 150px; object-fit: cover;"
                                     id="profilePicturePreview">

                                <!-- Online Status Badge -->
                                <span class="position-absolute bottom-0 end-0 translate-middle p-2 bg-success border border-light rounded-circle">
                                    <span class="visually-hidden">Online</span>
                                </span>

                                <!-- Upload/Remove buttons -->
                                <div class="profile-picture-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center rounded-circle"
                                     style="background: rgba(0,0,0,0.7); opacity: 0; transition: opacity 0.3s;">
                                    <div class="btn-group-vertical">
                                        <button type="button" class="btn btn-sm btn-light mb-1" onclick="document.getElementById('profilePictureInput').click()" title="Upload Picture">
                                            <i class="fas fa-camera"></i>
                                        </button>
                                        @if($principal->profile_picture)
                                            <button type="button" class="btn btn-sm btn-danger mb-1" onclick="removeProfilePicture()" id="removeProfilePictureBtn" title="Remove Picture">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>

                            <!-- Principal Name and Status -->
                            <div class="text-center">
                                <h4 class="mb-1 text-primary">{{ $principal->name }}</h4>
                                <p class="text-muted mb-1">{{ $principal->position ?? 'Principal' }}</p>
                                <span class="badge bg-success">
                                    <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                                    Online
                                </span>
                            </div>

                            <!-- Hidden file input -->
                            <input type="file" id="profilePictureInput" accept="image/*" style="display: none;" onchange="uploadProfilePicture(this)">
                        </div>

                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="text-primary mb-3">
                                        <i class="fas fa-id-badge me-2"></i>
                                        Profile Overview
                                    </h3>
                                </div>
                            </div>

                            @if($principal->bio)
                                <div class="card border-0 mb-4" style="background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%); border-left: 4px solid #3b82f6 !important;">
                                    <div class="card-body">
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-quote-left me-2"></i>Professional Summary
                                        </h6>
                                        <p class="mb-0 text-dark" style="font-style: italic; line-height: 1.7;">{{ $principal->bio }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h6 class="card-title text-primary">
                                                <i class="fas fa-address-card me-2"></i>Contact Information
                                            </h6>
                                            @if($principal->email)
                                                <p class="mb-2">
                                                    <i class="fas fa-envelope text-muted me-2"></i>
                                                    <small class="text-muted">Email:</small><br>
                                                    <strong>{{ $principal->email }}</strong>
                                                </p>
                                            @endif
                                            @if($principal->contact_number)
                                                <p class="mb-2">
                                                    <i class="fas fa-phone text-muted me-2"></i>
                                                    <small class="text-muted">Phone:</small><br>
                                                    <strong>{{ $principal->contact_number }}</strong>
                                                </p>
                                            @endif
                                            @if($principal->address)
                                                <p class="mb-0">
                                                    <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                                    <small class="text-muted">Address:</small><br>
                                                    <strong>{{ $principal->address }}</strong>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h6 class="card-title text-primary">
                                                <i class="fas fa-user-graduate me-2"></i>Professional Details
                                            </h6>
                                            @if($principal->education)
                                                <p class="mb-2">
                                                    <i class="fas fa-graduation-cap text-muted me-2"></i>
                                                    <small class="text-muted">Education:</small><br>
                                                    <strong>{{ $principal->education }}</strong>
                                                </p>
                                            @endif
                                            @if($principal->years_of_experience)
                                                <p class="mb-2">
                                                    <i class="fas fa-briefcase text-muted me-2"></i>
                                                    <small class="text-muted">Experience:</small><br>
                                                    <strong>{{ $principal->formatted_experience ?? $principal->years_of_experience . ' years' }}</strong>
                                                </p>
                                            @endif
                                            @if($principal->date_of_birth)
                                                <p class="mb-0">
                                                    <i class="fas fa-birthday-cake text-muted me-2"></i>
                                                    <small class="text-muted">Age:</small><br>
                                                    <strong>{{ $principal->age ?? 'N/A' }} years old</strong>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Media Links -->
                            @if($principal->facebook || $principal->linkedin || $principal->twitter)
                                <div class="mt-4">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-center">
                                            <h6 class="card-title text-primary mb-3">
                                                <i class="fas fa-share-alt me-2"></i>Connect With Me
                                            </h6>
                                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                                @if($principal->facebook)
                                                    <a href="{{ $principal->facebook }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                        <i class="fab fa-facebook me-1"></i> Facebook
                                                    </a>
                                                @endif
                                                @if($principal->linkedin)
                                                    <a href="{{ $principal->linkedin }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                        <i class="fab fa-linkedin me-1"></i> LinkedIn
                                                    </a>
                                                @endif
                                                @if($principal->twitter)
                                                    <a href="{{ $principal->twitter }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                        <i class="fab fa-twitter me-1"></i> Twitter
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Edit Profile Button -->
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <button class="btn btn-edit-profile" type="button" data-bs-toggle="collapse" data-bs-target="#editProfileForm" aria-expanded="false" aria-controls="editProfileForm">
                                <i class="fas fa-edit me-2"></i>Edit Profile Information
                            </button>
                            <p class="text-muted mt-2 mb-0">
                                <small><i class="fas fa-info-circle me-1"></i>Click to update your personal and professional information</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Edit Form (Collapsible) -->
    <div class="row">
        <div class="col-12">
            <div class="collapse" id="editProfileForm">
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Profile Information</h5>
                            <button class="btn btn-sm btn-outline-light" type="button" data-bs-toggle="collapse" data-bs-target="#editProfileForm" aria-expanded="true" aria-controls="editProfileForm">
                                <i class="fas fa-times"></i> Close
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                    <form action="{{ route('principal.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-user me-2"></i>Basic Information
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                    id="name" name="name" value="{{ old('name', $principal->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    id="email" name="email" value="{{ old('email', $principal->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" class="form-control @error('contact_number') is-invalid @enderror" 
                                    id="contact_number" name="contact_number" value="{{ old('contact_number', $principal->contact_number) }}">
                                @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">Position</label>
                                <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                    id="position" name="position" value="{{ old('position', $principal->position ?? 'Principal') }}">
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                    id="address" name="address" rows="2">{{ old('address', $principal->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="bio" class="form-label">Biography</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                    id="bio" name="bio" rows="3" placeholder="Tell us about yourself...">{{ old('bio', $principal->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-briefcase me-2"></i>Professional Information
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="education" class="form-label">Education</label>
                                <input type="text" class="form-control @error('education') is-invalid @enderror" 
                                    id="education" name="education" value="{{ old('education', $principal->education) }}"
                                    placeholder="e.g., Master's in Educational Administration">
                                @error('education')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="years_of_experience" class="form-label">Years of Experience</label>
                                <input type="number" class="form-control @error('years_of_experience') is-invalid @enderror" 
                                    id="years_of_experience" name="years_of_experience" 
                                    value="{{ old('years_of_experience', $principal->years_of_experience) }}" min="0" max="50">
                                @error('years_of_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-id-card me-2"></i>Personal Information
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                    id="date_of_birth" name="date_of_birth" 
                                    value="{{ old('date_of_birth', $principal->date_of_birth ? $principal->date_of_birth->format('Y-m-d') : '') }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender', $principal->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $principal->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $principal->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-phone-alt me-2"></i>Emergency Contact
                                </h6>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="emergency_contact_name" class="form-label">Contact Name</label>
                                <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                    id="emergency_contact_name" name="emergency_contact_name" 
                                    value="{{ old('emergency_contact_name', $principal->emergency_contact_name) }}">
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="emergency_contact_number" class="form-label">Contact Number</label>
                                <input type="text" class="form-control @error('emergency_contact_number') is-invalid @enderror" 
                                    id="emergency_contact_number" name="emergency_contact_number" 
                                    value="{{ old('emergency_contact_number', $principal->emergency_contact_number) }}">
                                @error('emergency_contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="emergency_contact_relationship" class="form-label">Relationship</label>
                                <input type="text" class="form-control @error('emergency_contact_relationship') is-invalid @enderror" 
                                    id="emergency_contact_relationship" name="emergency_contact_relationship" 
                                    value="{{ old('emergency_contact_relationship', $principal->emergency_contact_relationship) }}"
                                    placeholder="e.g., Spouse, Sibling">
                                @error('emergency_contact_relationship')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-share-alt me-2"></i>Social Media Links
                                </h6>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="facebook" class="form-label">Facebook</label>
                                <input type="url" class="form-control @error('facebook') is-invalid @enderror" 
                                    id="facebook" name="facebook" value="{{ old('facebook', $principal->facebook) }}"
                                    placeholder="https://facebook.com/yourprofile">
                                @error('facebook')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="linkedin" class="form-label">LinkedIn</label>
                                <input type="url" class="form-control @error('linkedin') is-invalid @enderror" 
                                    id="linkedin" name="linkedin" value="{{ old('linkedin', $principal->linkedin) }}"
                                    placeholder="https://linkedin.com/in/yourprofile">
                                @error('linkedin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="twitter" class="form-label">Twitter</label>
                                <input type="url" class="form-control @error('twitter') is-invalid @enderror" 
                                    id="twitter" name="twitter" value="{{ old('twitter', $principal->twitter) }}"
                                    placeholder="https://twitter.com/yourhandle">
                                @error('twitter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Change -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-lock me-2"></i>Change Password
                                </h6>
                                <p class="text-muted small">Leave password fields blank if you don't want to change your password.</p>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                    id="current_password" name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" 
                                    id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#editProfileForm" aria-expanded="true" aria-controls="editProfileForm">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="updateBtn">
                                        <span class="btn-text">
                                            <i class="fas fa-save me-2"></i>Update Profile
                                        </span>
                                        <span class="btn-loading d-none">
                                            <i class="fas fa-spinner fa-spin me-2"></i>Updating...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Profile Page Specific Styles */
    .profile-picture-container {
        position: relative;
        display: inline-block;
    }

    .profile-picture-overlay {
        background: rgba(44, 90, 160, 0.8) !important;
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .profile-picture-container:hover .profile-picture-overlay {
        opacity: 1;
    }

    .profile-section-card {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e0e6ed;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .profile-info-card {
        background: #ffffff;
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .form-section {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e0e6ed;
        margin-bottom: 20px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .form-section-header {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #e0e6ed;
        font-weight: 500;
        color: #495057;
    }

    .form-section-body {
        padding: 20px;
    }

    .form-control {
        border: 1px solid #d1d5db;
        border-radius: 4px;
        padding: 10px 12px;
        transition: border-color 0.2s ease;
        background-color: #ffffff;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #2c5aa0;
        box-shadow: 0 0 0 2px rgba(44, 90, 160, 0.2);
        background-color: #ffffff;
        outline: none;
    }

    .form-label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .section-title {
        color: #2c5aa0;
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .section-title i {
        margin-right: 8px;
        font-size: 16px;
    }

    .info-item {
        background: #ffffff;
        border-radius: 4px;
        padding: 12px;
        margin-bottom: 8px;
        border-left: 3px solid #2c5aa0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0e6ed;
    }

    .info-item:hover {
        background-color: #f8f9fa;
    }

    .info-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 400;
        text-transform: none;
        letter-spacing: normal;
        margin-bottom: 0.125rem;
    }

    .info-value {
        color: #1e293b;
        font-weight: 500;
        font-size: 14px;
    }

    .social-links-card {
        background-color: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 0;
    }

    .btn-social {
        border-radius: 3px;
        padding: 0.375rem 0.75rem;
        font-weight: 400;
        transition: background-color 0.2s ease;
        border: 1px solid transparent;
        font-size: 14px;
    }

    .profile-stats {
        background-color: #1e40af;
        color: white;
        border-radius: 0;
        padding: 1.25rem;
        text-align: center;
        margin-bottom: 1.25rem;
    }

    .profile-stats h3 {
        margin-bottom: 0.5rem;
        font-weight: 600;
        font-family: 'Times New Roman', Times, serif;
    }

    .profile-stats p {
        opacity: 0.9;
        margin-bottom: 0;
        font-size: 14px;
    }

    .status-badge {
        background-color: #10b981;
        color: white;
        padding: 0.375rem 0.75rem;
        border-radius: 3px;
        font-weight: 500;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        box-shadow: none;
    }

    .status-badge i {
        margin-right: 0.375rem;
        font-size: 10px;
    }

    /* Form styling improvements */
    .form-floating > .form-control {
        height: calc(3.5rem + 2px);
        line-height: 1.25;
    }

    .form-floating > label {
        padding: 1rem;
        color: #64748b;
        font-weight: 500;
    }

    .btn-update {
        background-color: #2c5aa0;
        border: 1px solid #2c5aa0;
        border-radius: 4px;
        padding: 10px 20px;
        font-weight: 500;
        color: white;
        transition: background-color 0.2s ease;
    }

    .btn-update:hover {
        background-color: #1e3a8a;
        border-color: #1e3a8a;
        color: white;
    }

    /* Additional button styles */
    .btn-primary {
        background-color: #2c5aa0;
        border-color: #2c5aa0;
        border-radius: 4px;
        padding: 8px 16px;
    }

    .btn-primary:hover {
        background-color: #1e3a8a;
        border-color: #1e3a8a;
    }

    .btn-secondary {
        background-color: #6b7280;
        border-color: #6b7280;
        border-radius: 4px;
        padding: 8px 16px;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
        border-color: #4b5563;
    }

    /* Edit Profile Button */
    .btn-edit-profile {
        background: linear-gradient(135deg, #2c5aa0 0%, #3b82f6 100%);
        border: none;
        border-radius: 8px;
        padding: 12px 30px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(44, 90, 160, 0.3);
        text-transform: none;
        font-size: 16px;
    }

    .btn-edit-profile:hover {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(44, 90, 160, 0.4);
        color: white;
    }

    /* Success highlight animation */
    .profile-updated {
        animation: successPulse 2s ease-in-out;
    }

    @keyframes successPulse {
        0% {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        50% {
            box-shadow: 0 0 20px rgba(40, 167, 69, 0.4);
            transform: scale(1.02);
        }
        100% {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
    }

    /* Collapsible form styling */
    .collapse {
        transition: all 0.3s ease;
    }

    .collapsing {
        transition: height 0.3s ease;
    }

    /* Form card animation */
    #editProfileForm .card {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .profile-section-card {
            margin-bottom: 1.5rem;
        }

        .form-section-body {
            padding: 1rem;
        }

        .profile-stats {
            margin-bottom: 1rem;
        }

        .btn-edit-profile {
            padding: 10px 20px;
            font-size: 14px;
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if there's a success message and close edit form
    const successAlert = document.getElementById('successAlert');
    const editProfileForm = document.getElementById('editProfileForm');

    if (successAlert && editProfileForm) {
        // Close the edit form if it's open
        const bsCollapse = new bootstrap.Collapse(editProfileForm, {
            hide: true
        });

        // Add success animation to profile overview card
        const profileCard = document.querySelector('.card.border-0.shadow-sm');
        if (profileCard) {
            profileCard.classList.add('profile-updated');

            // Remove animation class after animation completes
            setTimeout(() => {
                profileCard.classList.remove('profile-updated');
            }, 2000);
        }

        // Scroll to the top of the profile overview
        setTimeout(() => {
            document.querySelector('.container-fluid').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }, 300);

        // Show additional success feedback
        setTimeout(() => {
            showAlert('success', 'Profile updated successfully! Your changes have been saved.');
        }, 500);

        // Auto-dismiss success alert after 5 seconds
        setTimeout(() => {
            if (successAlert) {
                const alert = new bootstrap.Alert(successAlert);
                alert.close();
            }
        }, 5000);
    }

    // Profile picture hover effect
    const profileContainer = document.querySelector('.profile-picture-container');
    const overlay = document.querySelector('.profile-picture-overlay');

    if (profileContainer && overlay) {
        profileContainer.addEventListener('mouseenter', function() {
            overlay.style.opacity = '1';
        });

        profileContainer.addEventListener('mouseleave', function() {
            overlay.style.opacity = '0';
        });
    }

    // Edit Profile Form Toggle
    const editButton = document.querySelector('[data-bs-target="#editProfileForm"]');

    if (editProfileForm && editButton) {
        editProfileForm.addEventListener('shown.bs.collapse', function() {
            // Scroll to form when opened
            editProfileForm.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

            // Update button text
            const buttonText = editButton.querySelector('i').nextSibling;
            if (buttonText) {
                buttonText.textContent = ' Close Edit Form';
                editButton.querySelector('i').className = 'fas fa-times me-2';
            }
        });

        editProfileForm.addEventListener('hidden.bs.collapse', function() {
            // Reset button text
            const buttonText = editButton.querySelector('i').nextSibling;
            if (buttonText) {
                buttonText.textContent = ' Edit Profile Information';
                editButton.querySelector('i').className = 'fas fa-edit me-2';
            }

            // Scroll back to profile overview when form is closed
            setTimeout(() => {
                document.querySelector('.container-fluid').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 100);
        });
    }

    // Form submission handling
    const form = document.getElementById('profileForm');
    const updateBtn = document.getElementById('updateBtn');

    if (form && updateBtn) {
        const btnText = updateBtn.querySelector('.btn-text');
        const btnLoading = updateBtn.querySelector('.btn-loading');

        form.addEventListener('submit', function(e) {
            // Show loading state
            if (btnText && btnLoading) {
                btnText.classList.add('d-none');
                btnLoading.classList.remove('d-none');
                updateBtn.disabled = true;
            }

            // Show loading message
            showAlert('info', 'Updating profile information...');
        });

        // Reset form state if there was an error (form didn't redirect)
        setTimeout(() => {
            if (btnText && btnLoading && updateBtn.disabled) {
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                updateBtn.disabled = false;
            }
        }, 3000);
    }

    // Real-time validation feedback
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
                const feedback = this.parentNode.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.style.display = 'none';
                }
            }
        });
    });

    // Password confirmation validation
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    function validatePasswordMatch() {
        if (password.value && passwordConfirmation.value) {
            if (password.value !== passwordConfirmation.value) {
                passwordConfirmation.setCustomValidity('Passwords do not match');
                passwordConfirmation.classList.add('is-invalid');
            } else {
                passwordConfirmation.setCustomValidity('');
                passwordConfirmation.classList.remove('is-invalid');
            }
        }
    }

    password.addEventListener('input', validatePasswordMatch);
    passwordConfirmation.addEventListener('input', validatePasswordMatch);
});

// Profile picture upload function
function uploadProfilePicture(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            showAlert('danger', 'File size must be less than 2MB');
            return;
        }

        // Validate file type
        if (!file.type.match('image.*')) {
            showAlert('danger', 'Please select a valid image file');
            return;
        }

        // Show preview immediately for better user experience
        const reader = new FileReader();
        reader.onload = function(e) {
            // Update the profile picture preview on the profile page
            document.getElementById('profilePicturePreview').src = e.target.result;

            // Update the profile picture in the sidebar navigation
            const sidebarProfilePicture = document.querySelector('.profile-picture-sidebar');
            if (sidebarProfilePicture) {
                sidebarProfilePicture.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);

        const formData = new FormData();
        formData.append('profile_picture', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        // Show loading
        showAlert('info', 'Uploading profile picture...');

        fetch('{{ route("principal.profile.upload-picture") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the profile picture preview on the profile page
                document.getElementById('profilePicturePreview').src = data.profile_picture_url;

                // Update the profile picture in the sidebar navigation
                const sidebarProfilePicture = document.querySelector('.profile-picture-sidebar');
                if (sidebarProfilePicture) {
                    sidebarProfilePicture.src = data.profile_picture_url;
                }

                // Update any other profile pictures on the page
                const allProfilePictures = document.querySelectorAll('img[alt="{{ $principal->name }}"]');
                allProfilePictures.forEach(img => {
                    if (img.src.includes('ui-avatars.com') || img.src.includes('storage/')) {
                        img.src = data.profile_picture_url;
                    }
                });

                showAlert('success', data.message);

                // Show/hide remove button based on whether there's now a profile picture
                const removeButton = document.getElementById('removeProfilePictureBtn');
                if (removeButton) {
                    removeButton.style.display = 'inline-block';
                }

                // No need to refresh the page since we're updating the images directly
                // setTimeout(() => {
                //     window.location.reload();
                // }, 1000);
            } else {
                showAlert('danger', data.message || 'Failed to upload profile picture');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred while uploading the profile picture');
        });
    }
}

// Remove profile picture function
function removeProfilePicture() {
    if (confirm('Are you sure you want to remove your profile picture?')) {
        fetch('{{ route("principal.profile.remove-picture") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Set placeholder image with name for profile page (150px)
                const principalName = '{{ $principal->name }}';
                const placeholderUrl150 = `https://ui-avatars.com/api/?name=${encodeURIComponent(principalName)}&size=150&background=007bff&color=ffffff&bold=true`;
                const placeholderUrl80 = `https://ui-avatars.com/api/?name=${encodeURIComponent(principalName)}&size=80&background=ffffff&color=007bff&bold=true`;

                // Update the profile picture preview on the profile page
                document.getElementById('profilePicturePreview').src = placeholderUrl150;

                // Update the profile picture in the sidebar navigation (80px)
                const sidebarProfilePicture = document.querySelector('.profile-picture-sidebar');
                if (sidebarProfilePicture) {
                    sidebarProfilePicture.src = placeholderUrl80;
                }

                // Update any other profile pictures on the page
                const allProfilePictures = document.querySelectorAll('img[alt="{{ $principal->name }}"]');
                allProfilePictures.forEach(img => {
                    if (img.classList.contains('profile-picture-sidebar')) {
                        img.src = placeholderUrl80; // Sidebar uses 80px
                    } else {
                        img.src = placeholderUrl150; // Profile page uses 150px
                    }
                });

                showAlert('success', data.message);

                // Hide remove button since there's no profile picture now
                const removeButton = document.getElementById('removeProfilePictureBtn');
                if (removeButton) {
                    removeButton.style.display = 'none';
                }

                // No need to refresh the page since we're updating the images directly
                // setTimeout(() => {
                //     window.location.reload();
                // }, 1000);
            } else {
                showAlert('danger', data.message || 'Failed to remove profile picture');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred while removing the profile picture');
        });
    }
}

// Alert function
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.style.opacity = '0';
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
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

    // Auto-dismiss the alert after 4 seconds
    setTimeout(() => {
        alertDiv.style.opacity = '0';
        setTimeout(() => alertDiv.remove(), 300);
    }, 4000);
}

// Debug profile picture function
function debugProfilePicture() {
    fetch('{{ route("principal.profile.debug-picture") }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        let debugInfo = `🔍 Profile Picture Debug Information:\n\n`;
        debugInfo += `👤 Principal: ${data.principal_name} (ID: ${data.principal_id})\n\n`;
        debugInfo += `📁 Database Info:\n`;
        debugInfo += `   - Profile picture path: ${data.profile_picture_path || 'NULL'}\n`;
        debugInfo += `   - Profile picture URL: ${data.profile_picture_url || 'NULL'}\n\n`;
        debugInfo += `💾 File System:\n`;
        debugInfo += `   - File exists in storage: ${data.file_exists ? 'YES' : 'NO'}\n`;
        debugInfo += `   - Storage path: ${data.storage_path || 'NULL'}\n`;
        debugInfo += `   - Public path: ${data.public_path || 'NULL'}\n\n`;
        debugInfo += `🖼️ Current Display:\n`;
        debugInfo += `   - Profile page image: ${document.getElementById('profilePicturePreview').src}\n`;

        const sidebarImg = document.querySelector('.profile-picture-sidebar');
        debugInfo += `   - Sidebar image: ${sidebarImg ? sidebarImg.src : 'NOT FOUND'}\n\n`;

        debugInfo += `💡 Troubleshooting:\n`;
        debugInfo += `   1. Check if storage link exists: php artisan storage:link\n`;
        debugInfo += `   2. Verify file permissions on storage folder\n`;
        debugInfo += `   3. Check if image file is corrupted\n`;
        debugInfo += `   4. Try uploading a new image\n`;

        alert(debugInfo);

        // Also log to console for detailed inspection
        console.log('🔍 Profile Picture Debug Data:', data);
    })
    .catch(error => {
        console.error('Debug error:', error);
        alert('Error getting debug information: ' + error.message);
    });
}
</script>
@endsection
