@extends('layouts.admin')
@section('title', 'Add New Room')
@section('content')
<div class="container-fluid">
    <!-- Modern Angled Header Card -->
    <div class="angled-header-card mb-4">
        <div class="header-left-content">
            <span class="icon"><i class="fas fa-plus-circle"></i></span>
            <div>
                <span class="title">Add New Room</span>
                <span class="subtitle">Create a new classroom, laboratory, or facility</span>
            </div>
        </div>
        <div class="header-right-content">
            <a href="{{ route('admin.rooms.index') }}" class="angled-header-btn">
                <i class="fas fa-arrow-left me-2"></i> Back to Rooms
            </a>
        </div>
    </div>

    <!-- Room Form -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-plus me-2"></i>Room Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.rooms.store') }}" method="POST">
                @csrf
                
                <div class="row g-3">
                    <!-- Room Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Room Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" 
                               value="{{ old('name') }}" required placeholder="e.g., Computer Laboratory 1">
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Room Code -->
                    <div class="col-md-6">
                        <label for="code" class="form-label">Room Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="code" class="form-control" 
                               value="{{ old('code') }}" required placeholder="e.g., CL-001">
                        @error('code')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Room Type -->
                    <div class="col-md-6">
                        <label for="type" class="form-label">Room Type <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="">Select Room Type</option>
                            @foreach($roomTypes as $type)
                                <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Capacity -->
                    <div class="col-md-6">
                        <label for="capacity" class="form-label">Capacity <span class="text-danger">*</span></label>
                        <input type="number" name="capacity" id="capacity" class="form-control" 
                               value="{{ old('capacity', 30) }}" required min="1" max="1000">
                        @error('capacity')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="col-md-6">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-control" 
                               value="{{ old('location') }}" placeholder="e.g., 2nd Floor, Building A">
                        @error('location')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Availability -->
                    <div class="col-md-6">
                        <label for="is_available" class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_available" id="is_available" 
                                   value="1" {{ old('is_available', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available">
                                Available for scheduling
                            </label>
                        </div>
                        @error('is_available')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Equipment -->
                    <div class="col-12">
                        <label for="equipment" class="form-label">Equipment</label>
                        <div class="row g-2" id="equipment-container">
                            <div class="col-md-4">
                                <input type="text" name="equipment[]" class="form-control" 
                                       placeholder="e.g., Projector" value="{{ old('equipment.0') }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="equipment[]" class="form-control" 
                                       placeholder="e.g., Whiteboard" value="{{ old('equipment.1') }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="equipment[]" class="form-control" 
                                       placeholder="e.g., Air Conditioning" value="{{ old('equipment.2') }}">
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-equipment">
                            <i class="fas fa-plus me-1"></i>Add Equipment
                        </button>
                        @error('equipment')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Special Requirements -->
                    <div class="col-12">
                        <label for="special_requirements" class="form-label">Special Requirements</label>
                        <div class="row g-2" id="requirements-container">
                            <div class="col-md-4">
                                <input type="text" name="special_requirements[]" class="form-control" 
                                       placeholder="e.g., Computer Lab" value="{{ old('special_requirements.0') }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="special_requirements[]" class="form-control" 
                                       placeholder="e.g., Science Lab" value="{{ old('special_requirements.1') }}">
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-requirement">
                            <i class="fas fa-plus me-1"></i>Add Requirement
                        </button>
                        @error('special_requirements')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" 
                                  placeholder="Additional notes about this room...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Room
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add equipment field
    document.getElementById('add-equipment').addEventListener('click', function() {
        const container = document.getElementById('equipment-container');
        const newField = document.createElement('div');
        newField.className = 'col-md-4';
        newField.innerHTML = `
            <div class="input-group">
                <input type="text" name="equipment[]" class="form-control" placeholder="Equipment name">
                <button type="button" class="btn btn-outline-danger remove-field">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.appendChild(newField);
    });

    // Add requirement field
    document.getElementById('add-requirement').addEventListener('click', function() {
        const container = document.getElementById('requirements-container');
        const newField = document.createElement('div');
        newField.className = 'col-md-4';
        newField.innerHTML = `
            <div class="input-group">
                <input type="text" name="special_requirements[]" class="form-control" placeholder="Requirement">
                <button type="button" class="btn btn-outline-danger remove-field">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.appendChild(newField);
    });

    // Remove field functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-field') || e.target.closest('.remove-field')) {
            e.target.closest('.col-md-4').remove();
        }
    });
});
</script>
@endsection
