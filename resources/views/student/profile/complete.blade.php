@extends('layouts.student')

@section('title', 'Complete Your Profile')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Welcome Header -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-user-circle fa-4x text-primary"></i>
                    </div>
                    <h1 class="h2 mb-3">Welcome to CNHS!</h1>
                    <p class="lead text-muted mb-4">
                        Please complete your profile information to get started. This will help us provide you with a better experience.
                    </p>
                    <div class="alert alert-info d-inline-block">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Your Student ID:</strong> {{ $student->student_id }}
                    </div>
                </div>
            </div>

            <!-- Profile Completion Form -->
            <form action="{{ route('student.profile.complete.store') }}" method="POST">
                @csrf

                <!-- Personal Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2 text-primary"></i>
                            Personal Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('first_name') is-invalid @enderror"
                                           id="first_name"
                                           name="first_name"
                                           value="{{ old('first_name', $student->first_name) }}"
                                           required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text"
                                           class="form-control @error('middle_name') is-invalid @enderror"
                                           id="middle_name"
                                           name="middle_name"
                                           value="{{ old('middle_name', $student->middle_name) }}">
                                    @error('middle_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('last_name') is-invalid @enderror"
                                           id="last_name"
                                           name="last_name"
                                           value="{{ old('last_name', $student->last_name) }}"
                                           required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $student->email) }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select class="form-select @error('gender') is-invalid @enderror"
                                            id="gender"
                                            name="gender"
                                            required>
                                        <option value="">Select Gender</option>
                                        @foreach($dropdownOptions['genders'] as $value => $label)
                                            <option value="{{ $value }}" {{ old('gender', $student->gender) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="text"
                                           class="form-control @error('contact_number') is-invalid @enderror"
                                           id="contact_number"
                                           name="contact_number"
                                           value="{{ old('contact_number', $student->contact_number) }}">
                                    @error('contact_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address"
                                      name="address"
                                      rows="2">{{ old('address', $student->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-graduation-cap me-2 text-success"></i>
                            Academic Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="grade_level" class="form-label">Grade Level <span class="text-danger">*</span></label>
                                    <select class="form-select @error('grade_level') is-invalid @enderror"
                                            id="grade_level"
                                            name="grade_level"
                                            required>
                                        <option value="">Select Grade Level</option>
                                        @foreach($dropdownOptions['grade_levels'] as $value => $label)
                                            <option value="{{ $value }}" {{ old('grade_level', $student->grade_level) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('grade_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="track" class="form-label">Track</label>
                                    <select class="form-select @error('track') is-invalid @enderror"
                                            id="track"
                                            name="track">
                                        <option value="">Select Track</option>
                                        @foreach($dropdownOptions['tracks'] as $value => $label)
                                            <option value="{{ $value }}" {{ old('track', $student->track) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('track')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="strand" class="form-label">Strand</label>
                                    <select class="form-select @error('strand') is-invalid @enderror"
                                            id="strand"
                                            name="strand">
                                        <option value="">Select Strand</option>
                                        @foreach($dropdownOptions['strands'] as $value => $label)
                                            <option value="{{ $value }}" {{ old('strand', $student->strand) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('strand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="section" class="form-label">Section</label>
                                    <select class="form-select @error('section') is-invalid @enderror"
                                            id="section"
                                            name="section">
                                        <option value="">Select Section</option>
                                        @foreach($dropdownOptions['sections'] as $value => $label)
                                            <option value="{{ $value }}" {{ old('section', $student->section) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="advisor" class="form-label">Adviser</label>
                                    <select class="form-select @error('advisor') is-invalid @enderror"
                                            id="advisor"
                                            name="advisor">
                                        <option value="">Select Adviser</option>
                                        @foreach($dropdownOptions['teachers'] as $value => $label)
                                            <option value="{{ $value }}" {{ old('advisor', $student->advisor) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('advisor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="lrn" class="form-label">LRN (Learner Reference Number)</label>
                                    <input type="text"
                                           class="form-control @error('lrn') is-invalid @enderror"
                                           id="lrn"
                                           name="lrn"
                                           value="{{ old('lrn', $student->lrn) }}"
                                           placeholder="12-digit LRN">
                                    @error('lrn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parent/Guardian Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2 text-warning"></i>
                            Parent/Guardian Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="parent_name" class="form-label">Parent/Guardian Name</label>
                                    <input type="text"
                                           class="form-control @error('parent_name') is-invalid @enderror"
                                           id="parent_name"
                                           name="parent_name"
                                           value="{{ old('parent_name', $student->parent_name) }}">
                                    @error('parent_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="parent_contact" class="form-label">Parent/Guardian Contact</label>
                                    <input type="text"
                                           class="form-control @error('parent_contact') is-invalid @enderror"
                                           id="parent_contact"
                                           name="parent_contact"
                                           value="{{ old('parent_contact', $student->parent_contact) }}">
                                    @error('parent_contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security (Optional) -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-lock me-2 text-danger"></i>
                            Security (Optional)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            You can change your password now or keep the current one and change it later from your profile.
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           minlength="8">
                                    <div class="form-text">Leave blank to keep current password</div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           minlength="8">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-check me-2"></i>
                            Complete Profile
                        </button>
                        <p class="text-muted mt-3 mb-0">
                            <small>You can update this information later from your profile page.</small>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.text-danger {
    color: #dc3545 !important;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    font-weight: 600;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}
</style>
@endsection
