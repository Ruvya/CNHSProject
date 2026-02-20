@extends('layouts.admin')

@section('title', 'Edit Teacher')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="card mb-4" style="border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border: none;">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between" style="padding: 1.2rem 1.5rem;">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <span style="font-size: 2rem; color: #2563eb; background: #f1f5fb; border-radius: 12px; padding: 0.7rem; margin-right: 1rem; display: flex; align-items: center;">
                    <i class="fas fa-user-edit"></i>
                </span>
                <div>
                    <div style="font-size: 1.3rem; font-weight: bold; font-family: 'Poppins', sans-serif; color: #222;">Edit Teacher</div>
                    <div style="font-size: 0.95rem; font-family: 'Poppins', sans-serif; color: #555; font-weight: 500;">Update teacher information for {{ $teacher->name }}</div>
                </div>
            </div>
            <div class="mb-2 mb-md-0">
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to User Management
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
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
                                        @foreach($tracks as $track)
                                            <option value="{{ $track->name }}" {{ old('track', $teacher->track) == $track->name ? 'selected' : '' }} data-track-id="{{ $track->id }}">
                                                {{ $track->name }}
                                            </option>
                                        @endforeach
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
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle track/cluster relationship
    const trackSelect = document.getElementById('track');
    const clusterSelect = document.getElementById('cluster');
    const currentCluster = '{{ old("cluster", $teacher->cluster) }}';
    const currentTrack = '{{ old("track", $teacher->track) }}';

    // Load clusters by track name
    async function loadClustersByTrackName(trackName) {
        if (!trackName) {
            clusterSelect.innerHTML = '<option value="">Select Cluster</option>';
            return;
        }

        // Find the track ID from the option's data attribute or tracks JSON
        const selectedOption = trackSelect.querySelector(`option[value="${trackName}"]`);
        const trackId = selectedOption ? selectedOption.getAttribute('data-track-id') : null;
        
        if (!trackId) {
            // Fallback: find from tracks JSON
            const tracks = @json($tracks);
            const track = tracks.find(t => t.name === trackName);
            if (track) {
                await loadClustersByTrackId(track.id);
            } else {
                clusterSelect.innerHTML = '<option value="">No clusters available</option>';
            }
            return;
        }

        await loadClustersByTrackId(trackId);
    }

    // Load clusters by track ID using AJAX
    async function loadClustersByTrackId(trackId) {
        if (!trackId) {
            clusterSelect.innerHTML = '<option value="">Select Cluster</option>';
            return;
        }

        clusterSelect.innerHTML = '<option value="">Loading clusters...</option>';
        clusterSelect.disabled = true;

        try {
            const response = await fetch(`/admin/api/clusters/by-track/${trackId}`);
            const data = await response.json();

            clusterSelect.innerHTML = '<option value="">Select Cluster</option>';
            
            if (data.success && data.clusters && data.clusters.length > 0) {
                data.clusters.forEach(cluster => {
                    const option = document.createElement('option');
                    option.value = cluster.name;
                    option.textContent = cluster.name;
                    if (cluster.description) {
                        option.textContent += ' - ' + cluster.description;
                    }
                    if (cluster.name === currentCluster) {
                        option.selected = true;
                    }
                    clusterSelect.appendChild(option);
                });
            } else {
                clusterSelect.innerHTML = '<option value="">No clusters available for this track</option>';
            }
        } catch (error) {
            console.error('Error loading clusters:', error);
            clusterSelect.innerHTML = '<option value="">Error loading clusters</option>';
        } finally {
            clusterSelect.disabled = false;
        }
    }

    // Initialize clusters on page load if track is already selected
    if (currentTrack) {
        loadClustersByTrackName(currentTrack);
    }

    trackSelect.addEventListener('change', function() {
        const selectedTrack = this.value;
        loadClustersByTrackName(selectedTrack);
    });
});
</script>
@endpush
