@extends('layouts.admin')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="card mb-4" style="border-radius: 14px; box-shadow: 0 8px 25px rgba(30,58,138,0.12); border: none;">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between" style="padding: 1.2rem 1.5rem;">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <span style="font-size: 2rem; color: #2563eb; background: #f1f5fb; border-radius: 12px; padding: 0.7rem; margin-right: 1rem; display: flex; align-items: center;">
                    <i class="fas fa-plus-circle"></i>
                </span>
                <div>
                    <div style="font-size: 1.3rem; font-weight: bold; font-family: 'Poppins', sans-serif; color: #222;">Create New Track</div>
                    <div style="font-size: 0.95rem; font-family: 'Poppins', sans-serif; color: #555; font-weight: 500;">Add a new track to the system</div>
                </div>
            </div>
            <a href="{{ route('admin.tracks.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Tracks
            </a>
        </div>
    </div>

    <div class="card" style="border-radius: 12px;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.tracks.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Track Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">e.g., Academic Track, Technical-Vocational-Livelihood Track</small>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="order" class="form-label">Order</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" 
                               id="order" name="order" value="{{ old('order') }}" min="0">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Optional: Order for sorting</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.tracks.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Track
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

