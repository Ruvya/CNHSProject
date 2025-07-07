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
                            <label for="strand" class="form-label">Strand (Optional)</label>
                            <select class="form-control @error('strand') is-invalid @enderror" id="strand" name="strand">
                                <option value="">Select Strand</option>
                                <option value="STEM" {{ old('strand') === 'STEM' ? 'selected' : '' }}>STEM</option>
                                <option value="ABM" {{ old('strand') === 'ABM' ? 'selected' : '' }}>ABM</option>
                                <option value="HUMSS" {{ old('strand') === 'HUMSS' ? 'selected' : '' }}>HUMSS</option>
                                <option value="GAS" {{ old('strand') === 'GAS' ? 'selected' : '' }}>GAS</option>
                                <option value="TVL-HE" {{ old('strand') === 'TVL-HE' ? 'selected' : '' }}>TVL - Home Economics</option>
                                <option value="TVL-ICT" {{ old('strand') === 'TVL-ICT' ? 'selected' : '' }}>TVL - ICT</option>
                                <option value="TVL-IA" {{ old('strand') === 'TVL-IA' ? 'selected' : '' }}>TVL - Industrial Arts</option>
                                <option value="TVL-AFA" {{ old('strand') === 'TVL-AFA' ? 'selected' : '' }}>TVL - Agri-Fishery Arts</option>
                            </select>
                            @error('strand')
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