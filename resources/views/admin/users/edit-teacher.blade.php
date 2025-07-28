@extends('layouts.admin')

@section('title', 'Edit Teacher')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Teacher: {{ $teacher->name }}</h1>
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to User Management
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Teacher Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.teachers.update', $teacher) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $teacher->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $teacher->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control @error('contact_number') is-invalid @enderror" 
                                           id="contact_number" name="contact_number" value="{{ old('contact_number', $teacher->contact_number) }}">
                                    @error('contact_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Password (Optional for Edit) -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Leave password fields empty to keep the current password.
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="track" class="form-label">Track</label>
                                    <select class="form-control @error('track') is-invalid @enderror" id="track" name="track">
                                        <option value="">Select Track</option>
                                        <option value="Academic Track" {{ old('track', $teacher->track) == 'Academic Track' ? 'selected' : '' }}>Academic Track</option>
                                        <option value="Technical-Vocational-Livelihood Track" {{ old('track', $teacher->track) == 'Technical-Vocational-Livelihood Track' ? 'selected' : '' }}>Technical-Vocational-Livelihood Track</option>
                                        <option value="Sports Track" {{ old('track', $teacher->track) == 'Sports Track' ? 'selected' : '' }}>Sports Track</option>
                                        <option value="Arts and Design Track" {{ old('track', $teacher->track) == 'Arts and Design Track' ? 'selected' : '' }}>Arts and Design Track</option>
                                    </select>
                                    @error('track')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="cluster" class="form-label">Cluster</label>
                                    <select class="form-control @error('cluster') is-invalid @enderror" id="cluster" name="cluster">
                                        <option value="">Select Cluster</option>
                                        <!-- Options will be populated by JavaScript based on track selection -->
                                    </select>
                                    @error('cluster')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="active" {{ old('status', $teacher->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $teacher->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
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
                                              id="address" name="address" rows="3">{{ old('address', $teacher->address) }}</textarea>
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
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Teacher
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

        <!-- Info Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Teacher Details</h6>
                </div>
                <div class="card-body">
                    <p><strong>Teacher ID:</strong> {{ $teacher->id }}</p>
                    <p><strong>Created:</strong> {{ $teacher->created_at->format('M d, Y') }}</p>
                    <p><strong>Last Updated:</strong> {{ $teacher->updated_at->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Important Notes</h6>
                </div>
                <div class="card-body">
                    <ul class="small">
                        <li>Leave password fields empty to keep current password</li>
                        <li>Email must be unique across all teachers</li>
                        <li>Inactive teachers cannot log in to the system</li>
                        <li>Changes take effect immediately</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle track/cluster relationship
    const trackSelect = document.getElementById('track');
    const clusterSelect = document.getElementById('cluster');
    const currentCluster = '{{ old("cluster", $teacher->cluster) }}';

    trackSelect.addEventListener('change', function() {
        const track = this.value;

        // Clear current options
        clusterSelect.innerHTML = '<option value="">Select Cluster</option>';

        if (track === 'Academic Track') {
            clusterSelect.innerHTML += `
                <option value="HUMSS" ${currentCluster === 'HUMSS' ? 'selected' : ''}>HUMSS (Humanities and Social Sciences)</option>
                <option value="STEM" ${currentCluster === 'STEM' ? 'selected' : ''}>STEM (Science, Technology, Engineering and Mathematics)</option>
                <option value="ABM" ${currentCluster === 'ABM' ? 'selected' : ''}>ABM (Accountancy, Business and Management)</option>
                <option value="GAS" ${currentCluster === 'GAS' ? 'selected' : ''}>GAS (General Academic Strand)</option>
            `;
        } else if (track === 'Technical-Vocational-Livelihood Track') {
            clusterSelect.innerHTML += `
                <option value="TVL-ICT" ${currentCluster === 'TVL-ICT' ? 'selected' : ''}>TVL-ICT (Information and Communications Technology)</option>
                <option value="TVL-HE" ${currentCluster === 'TVL-HE' ? 'selected' : ''}>TVL-HE (Home Economics)</option>
                <option value="TVL-AFA" ${currentCluster === 'TVL-AFA' ? 'selected' : ''}>TVL-AFA (Agri-Fishery Arts)</option>
                <option value="TVL-IA" ${currentCluster === 'TVL-IA' ? 'selected' : ''}>TVL-IA (Industrial Arts)</option>
            `;
        } else if (track === 'Sports Track') {
            clusterSelect.innerHTML += `
                <option value="Sports" ${currentCluster === 'Sports' ? 'selected' : ''}>Sports</option>
            `;
        } else if (track === 'Arts and Design Track') {
            clusterSelect.innerHTML += `
                <option value="Arts and Design" ${currentCluster === 'Arts and Design' ? 'selected' : ''}>Arts and Design</option>
            `;
        }
    });

    // Trigger track change on page load to populate clusters
    trackSelect.dispatchEvent(new Event('change'));
});
</script>
@endpush
