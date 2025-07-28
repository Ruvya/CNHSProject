@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Edit Grading Scale</h4>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.grading-scales.update', $gradingScale) }}">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label for="min_grade" class="form-label">Min Grade</label>
                        <input type="number" name="min_grade" id="min_grade" class="form-control" min="0" max="100" value="{{ old('min_grade', $gradingScale->min_grade) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label for="max_grade" class="form-label">Max Grade</label>
                        <input type="number" name="max_grade" id="max_grade" class="form-control" min="0" max="100" value="{{ old('max_grade', $gradingScale->max_grade) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" name="description" id="description" class="form-control" value="{{ old('description', $gradingScale->description) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <input type="text" name="remarks" id="remarks" class="form-control" value="{{ old('remarks', $gradingScale->remarks) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label for="order" class="form-label">Order</label>
                        <input type="number" name="order" id="order" class="form-control" min="0" value="{{ old('order', $gradingScale->order) }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.grading-scales.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection 