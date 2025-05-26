@extends('layouts.registrar')

@section('title', 'Add New Student')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Add New Student</h1>
            <p class="text-muted">Create a comprehensive student profile with academic information</p>
        </div>
        <div>
            <a href="{{ route('registrar.students.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Students
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Please correct the following errors:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <form action="{{ route('registrar.students.store') }}" method="POST">
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
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label">Student ID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('student_id') is-invalid @enderror" 
                                       id="student_id" name="student_id" value="{{ old('student_id') }}" 
                                       placeholder="e.g., CNHS-2024-001" required>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lrn" class="form-label">LRN (Learner Reference Number)</label>
                                <input type="text" class="form-control @error('lrn') is-invalid @enderror" 
                                       id="lrn" name="lrn" value="{{ old('lrn') }}" 
                                       placeholder="12-digit LRN">
                                @error('lrn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control @error('middle_name') is-invalid @enderror" 
                                       id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                                @error('middle_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" class="form-control @error('contact_number') is-invalid @enderror" 
                                       id="contact_number" name="contact_number" value="{{ old('contact_number') }}" 
                                       placeholder="e.g., 09123456789">
                                @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" 
                                      placeholder="Complete home address">{{ old('address') }}</textarea>
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
                            <div class="col-md-4 mb-3">
                                <label for="grade_level" class="form-label">Grade Level <span class="text-danger">*</span></label>
                                <select class="form-select @error('grade_level') is-invalid @enderror" id="grade_level" name="grade_level" required>
                                    <option value="">Select Grade Level</option>
                                    <option value="Grade 11" {{ old('grade_level') === 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                    <option value="Grade 12" {{ old('grade_level') === 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                                </select>
                                @error('grade_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="section" class="form-label">Section</label>
                                <input type="text" class="form-control @error('section') is-invalid @enderror" 
                                       id="section" name="section" value="{{ old('section') }}" 
                                       placeholder="e.g., Einstein, Newton, Darwin">
                                @error('section')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="track" class="form-label">Academic Track</label>
                                <select class="form-select @error('track') is-invalid @enderror" id="track" name="track">
                                    <option value="">Select Track (Optional)</option>
                                    <option value="Academic Track" {{ old('track') === 'Academic Track' ? 'selected' : '' }}>Academic Track</option>
                                    <option value="Technical-Vocational-Livelihood Track" {{ old('track') === 'Technical-Vocational-Livelihood Track' ? 'selected' : '' }}>Technical-Vocational-Livelihood Track</option>
                                    <option value="Sports Track" {{ old('track') === 'Sports Track' ? 'selected' : '' }}>Sports Track</option>
                                    <option value="Arts and Design Track" {{ old('track') === 'Arts and Design Track' ? 'selected' : '' }}>Arts and Design Track</option>
                                </select>
                                @error('track')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="strand" class="form-label">Strand</label>
                                <select class="form-select @error('strand') is-invalid @enderror" id="strand" name="strand">
                                    <option value="">Select Strand (Optional)</option>
                                    <option value="HUMSS" {{ old('strand') === 'HUMSS' ? 'selected' : '' }}>HUMSS (Humanities and Social Sciences)</option>
                                    <option value="STEM" {{ old('strand') === 'STEM' ? 'selected' : '' }}>STEM (Science, Technology, Engineering and Mathematics)</option>
                                    <option value="ABM" {{ old('strand') === 'ABM' ? 'selected' : '' }}>ABM (Accountancy, Business and Management)</option>
                                    <option value="GAS" {{ old('strand') === 'GAS' ? 'selected' : '' }}>GAS (General Academic Strand)</option>
                                    <option value="TVL-ICT" {{ old('strand') === 'TVL-ICT' ? 'selected' : '' }}>TVL-ICT (Information and Communications Technology)</option>
                                    <option value="TVL-HE" {{ old('strand') === 'TVL-HE' ? 'selected' : '' }}>TVL-HE (Home Economics)</option>
                                    <option value="TVL-AFA" {{ old('strand') === 'TVL-AFA' ? 'selected' : '' }}>TVL-AFA (Agri-Fishery Arts)</option>
                                </select>
                                @error('strand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-key me-2 text-warning"></i>
                            Account Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required
                                       placeholder="student@cnhs.edu.ph">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required
                                       placeholder="Minimum 8 characters">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parent/Guardian Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2 text-info"></i>
                            Parent/Guardian Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="parent_name" class="form-label">Parent/Guardian Name</label>
                                <input type="text" class="form-control @error('parent_name') is-invalid @enderror" 
                                       id="parent_name" name="parent_name" value="{{ old('parent_name') }}" 
                                       placeholder="Full name of parent or guardian">
                                @error('parent_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="parent_contact" class="form-label">Parent/Guardian Contact</label>
                                <input type="text" class="form-control @error('parent_contact') is-invalid @enderror" 
                                       id="parent_contact" name="parent_contact" value="{{ old('parent_contact') }}" 
                                       placeholder="e.g., 09123456789">
                                @error('parent_contact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('registrar.students.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Student Profile
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Update strand options based on track selection
    $('#track').on('change', function() {
        const track = $(this).val();
        const strandSelect = $('#strand');
        const currentStrand = '{{ old("strand") }}';
        
        // Clear current options
        strandSelect.html('<option value="">Select Strand (Optional)</option>');
        
        if (track === 'Academic Track') {
            strandSelect.append(`
                <option value="HUMSS" ${currentStrand === 'HUMSS' ? 'selected' : ''}>HUMSS (Humanities and Social Sciences)</option>
                <option value="STEM" ${currentStrand === 'STEM' ? 'selected' : ''}>STEM (Science, Technology, Engineering and Mathematics)</option>
                <option value="ABM" ${currentStrand === 'ABM' ? 'selected' : ''}>ABM (Accountancy, Business and Management)</option>
                <option value="GAS" ${currentStrand === 'GAS' ? 'selected' : ''}>GAS (General Academic Strand)</option>
            `);
        } else if (track === 'Technical-Vocational-Livelihood Track') {
            strandSelect.append(`
                <option value="TVL-ICT" ${currentStrand === 'TVL-ICT' ? 'selected' : ''}>TVL-ICT (Information and Communications Technology)</option>
                <option value="TVL-HE" ${currentStrand === 'TVL-HE' ? 'selected' : ''}>TVL-HE (Home Economics)</option>
                <option value="TVL-AFA" ${currentStrand === 'TVL-AFA' ? 'selected' : ''}>TVL-AFA (Agri-Fishery Arts)</option>
            `);
        }
    });
    
    // Trigger track change on page load to populate strands
    $('#track').trigger('change');
    
    // Auto-generate email based on name
    $('#first_name, #last_name').on('blur', function() {
        const firstName = $('#first_name').val().toLowerCase().replace(/\s+/g, '');
        const lastName = $('#last_name').val().toLowerCase().replace(/\s+/g, '');
        
        if (firstName && lastName && !$('#email').val()) {
            $('#email').val(`${firstName}.${lastName}@cnhs.edu.ph`);
        }
    });
});
</script>
@endpush
@endsection
