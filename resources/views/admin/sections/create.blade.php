@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="h3 mb-3">Create Section</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.sections.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Section Name/Code</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="e.g., 11-STEM-A" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Grade Level</label>
                <select name="grade_level" class="form-select" required>
                    <option value="">Select grade level</option>
                    @foreach($gradeLevels as $g)
                        <option value="{{ $g }}" @selected(old('grade_level')===$g)>{{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Track <span class="text-danger">*</span></label>
                <select name="track" id="track" class="form-select" required>
                    <option value="">Select Track</option>
                    @foreach($tracks as $track)
                        <option value="{{ $track->name }}" @selected(old('track')===$track->name)>{{ $track->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Cluster</label>
                <select name="cluster" id="cluster" class="form-select">
                    <option value="">Select Cluster (Optional)</option>
                    <!-- Options will be populated by JavaScript based on track selection -->
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">School Year</label>
                <select name="school_year" class="form-select">
                    @php $defaultYear = old('school_year', optional(\App\Models\SchoolYear::active()->first())->name); @endphp
                    @foreach(($years ?? collect()) as $y)
                        <option value="{{ $y->name }}" @selected($defaultYear===$y->name)>
                            {{ $y->name }} {{ $y->status === 'active' ? '(Active)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Semester</label>
                @php $semesters = ['1st Semester','2nd Semester']; @endphp
                <select name="semester" class="form-select">
                    @foreach($semesters as $s)
                        <option value="{{ $s }}" @selected(old('semester','1st Semester')===$s)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Adviser (optional)</label>
                <select name="adviser_id" class="form-select">
                    <option value="">— None —</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected(old('adviser_id')==$teacher->id)>{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Maximum Capacity (optional)</label>
                <input type="number" name="max_capacity" min="1" value="{{ old('max_capacity', 40) }}" class="form-control">
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <a href="{{ route('admin.sections.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const trackSelect = document.getElementById('track');
    const clusterSelect = document.getElementById('cluster');
    const oldCluster = '{{ old("cluster") }}';

    // Load clusters by track name
    async function loadClustersByTrackName(trackName) {
        if (!trackName) {
            clusterSelect.innerHTML = '<option value="">Select Cluster (Optional)</option>';
            return;
        }

        // Find the track ID from the tracks data
        const tracks = @json($tracks);
        const track = tracks.find(t => t.name === trackName);
        
        if (!track) {
            clusterSelect.innerHTML = '<option value="">No clusters available</option>';
            return;
        }

        await loadClustersByTrackId(track.id);
    }

    // Load clusters by track ID using AJAX
    async function loadClustersByTrackId(trackId) {
        if (!trackId) {
            clusterSelect.innerHTML = '<option value="">Select Cluster (Optional)</option>';
            return;
        }

        clusterSelect.innerHTML = '<option value="">Loading clusters...</option>';
        clusterSelect.disabled = true;

        try {
            const response = await fetch(`/admin/api/clusters/by-track/${trackId}`);
            const data = await response.json();

            clusterSelect.innerHTML = '<option value="">Select Cluster (Optional)</option>';
            
            if (data.success && data.clusters && data.clusters.length > 0) {
                data.clusters.forEach(cluster => {
                    const option = document.createElement('option');
                    option.value = cluster.name;
                    option.textContent = cluster.name;
                    if (cluster.description) {
                        option.textContent += ' - ' + cluster.description;
                    }
                    if (cluster.name === oldCluster) {
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

    // Initialize clusters on page load
    if (trackSelect.value) {
        loadClustersByTrackName(trackSelect.value);
    }

    // Update clusters when track changes
    trackSelect.addEventListener('change', function() {
        const selectedTrack = this.value;
        loadClustersByTrackName(selectedTrack);
    });
});
</script>
@endpush

@endsection


