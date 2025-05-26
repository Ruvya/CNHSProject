@extends('layouts.admin')

@section('title', 'Add New Student')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Student</h1>
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to User Management
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Student Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.students.store') }}" method="POST">
                        @csrf
                        
                        <!-- Basic Information -->
                        <h6 class="text-primary mb-3">Personal Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="student_id" class="form-label">Student ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('student_id') is-invalid @enderror" 
                                           id="student_id" name="student_id" value="{{ old('student_id') }}" required>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control @error('middle_name') is-invalid @enderror" 
                                           id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                                    @error('middle_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control @error('contact_number') is-invalid @enderror" 
                                           id="contact_number" name="contact_number" value="{{ old('contact_number') }}">
                                    @error('contact_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <hr>
                        <h6 class="text-primary mb-3">Academic Information</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="grade_level" class="form-label">Grade Level <span class="text-danger">*</span></label>
                                    <select class="form-control @error('grade_level') is-invalid @enderror" id="grade_level" name="grade_level" required>
                                        <option value="">Select Grade Level</option>
                                        <option value="Grade 11" {{ old('grade_level') == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                        <option value="Grade 12" {{ old('grade_level') == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                                    </select>
                                    @error('grade_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="track" class="form-label">Track</label>
                                    <select class="form-control @error('track') is-invalid @enderror" id="track" name="track">
                                        <option value="">Select Track</option>
                                        <option value="Academic Track" {{ old('track') == 'Academic Track' ? 'selected' : '' }}>Academic Track</option>
                                        <option value="TVL Track" {{ old('track') == 'TVL Track' ? 'selected' : '' }}>TVL Track</option>
                                        <option value="Sports Track" {{ old('track') == 'Sports Track' ? 'selected' : '' }}>Sports Track</option>
                                        <option value="Arts and Design Track" {{ old('track') == 'Arts and Design Track' ? 'selected' : '' }}>Arts and Design Track</option>
                                    </select>
                                    @error('track')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="strand" class="form-label">Strand</label>
                                    <select class="form-control @error('strand') is-invalid @enderror" id="strand" name="strand">
                                        <option value="">Select Strand</option>
                                        <option value="STEM" {{ old('strand') == 'STEM' ? 'selected' : '' }}>STEM</option>
                                        <option value="HUMSS" {{ old('strand') == 'HUMSS' ? 'selected' : '' }}>HUMSS</option>
                                        <option value="ABM" {{ old('strand') == 'ABM' ? 'selected' : '' }}>ABM</option>
                                        <option value="GAS" {{ old('strand') == 'GAS' ? 'selected' : '' }}>GAS</option>
                                    </select>
                                    @error('strand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section" class="form-label">Section</label>
                                    <input type="text" class="form-control @error('section') is-invalid @enderror" 
                                           id="section" name="section" value="{{ old('section') }}">
                                    @error('section')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Parent/Guardian Information -->
                        <hr>
                        <h6 class="text-primary mb-3">Parent/Guardian Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="parent_name" class="form-label">Parent/Guardian Name</label>
                                    <input type="text" class="form-control @error('parent_name') is-invalid @enderror" 
                                           id="parent_name" name="parent_name" value="{{ old('parent_name') }}">
                                    @error('parent_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="parent_contact" class="form-label">Parent/Guardian Contact</label>
                                    <input type="text" class="form-control @error('parent_contact') is-invalid @enderror" 
                                           id="parent_contact" name="parent_contact" value="{{ old('parent_contact') }}">
                                    @error('parent_contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Create Student
                                    </button>
                                    <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Help & Guidelines</h6>
                </div>
                <div class="card-body">
                    <h6 class="text-success">Required Fields</h6>
                    <ul class="small">
                        <li>Student ID</li>
                        <li>First Name & Last Name</li>
                        <li>Email Address</li>
                        <li>Password (minimum 8 characters)</li>
                        <li>Gender</li>
                        <li>Grade Level</li>
                    </ul>

                    <h6 class="text-success mt-3">Student ID Format</h6>
                    <p class="small">Use a unique identifier for each student (e.g., 2024-001, STU-001, etc.)</p>

                    <h6 class="text-success mt-3">Academic Tracks</h6>
                    <ul class="small">
                        <li><strong>Academic:</strong> STEM, HUMSS, ABM, GAS</li>
                        <li><strong>TVL:</strong> Technical-Vocational-Livelihood</li>
                        <li><strong>Sports:</strong> Sports Track</li>
                        <li><strong>Arts:</strong> Arts and Design Track</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
