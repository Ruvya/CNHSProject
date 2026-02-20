@extends('Principal.layouts.admin')

@section('title', 'Add New Teacher')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Add New Teacher</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('principal.teachers.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="text" class="form-control @error('contact_number') is-invalid @enderror"
                                id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required>
                            @error('contact_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                            <div class="mb-3">
                            <label for="grade-level" class="form-label">Grade level</label>
                            <input type="number" class="form-control @error('grade-level') is-invalid @enderror"
                                id="grade-level" name="grade-level" value="{{ old('grade-level', $teacher->grade_level) }}" required>
                            @error('grade-level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="track" class="form-label">Track (Optional)</label>
                            <select class="form-control @error('track') is-invalid @enderror" id="track" name="track">
                                <option value="">Select Track</option>
                                <option value="Academic Track" {{ old('track') === 'Academic Track' ? 'selected' : '' }}>Academic Track</option>
                                <option value="Technical-Vocational-Livelihood Track" {{ old('track') === 'Technical-Vocational-Livelihood Track' ? 'selected' : '' }}>Technical-Vocational-Livelihood Track</option>
                                <option value="Sports Track" {{ old('track') === 'Sports Track' ? 'selected' : '' }}>Sports Track</option>
                                <option value="Arts and Design Track" {{ old('track') === 'Arts and Design Track' ? 'selected' : '' }}>Arts and Design Track</option>
                            </select>
                            @error('track')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cluster" class="form-label">Cluster (Optional)</label>
                            <select class="form-control @error('cluster') is-invalid @enderror" id="cluster" name="cluster">
                                <option value="">Select Cluster</option>
                                <!-- Options will be populated by JavaScript based on track selection -->
                            </select>
                            @error('cluster')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('principal.teachers.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Teacher</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle track/cluster relationship
    const trackSelect = document.getElementById('track');
    const clusterSelect = document.getElementById('cluster');
    const currentCluster = '{{ old("cluster") }}';

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