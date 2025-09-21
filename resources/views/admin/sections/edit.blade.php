@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="h3 mb-3">Edit Section</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.sections.update', $section) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Section Name/Code</label>
                <input type="text" name="name" value="{{ old('name', $section->name) }}" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Grade Level</label>
                <select name="grade_level" class="form-select" required>
                    @foreach($gradeLevels as $g)
                        <option value="{{ $g }}" @selected(old('grade_level', $section->grade_level)===$g)>{{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Strand</label>
                <select name="track" class="form-select" required>
                    @foreach($tracks as $t)
                        <option value="{{ $t }}" @selected(old('track', $section->track)===$t)>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Adviser (optional)</label>
                <select name="adviser_id" class="form-select">
                    <option value="">— None —</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected(old('adviser_id', $section->adviser_id)==$teacher->id)>{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Maximum Capacity (optional)</label>
                <input type="number" name="max_capacity" min="1" value="{{ old('max_capacity', $section->max_capacity) }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['active','inactive','full'] as $s)
                        <option value="{{ $s }}" @selected(old('status', $section->status)===$s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <a href="{{ route('admin.sections.index') }}" class="btn btn-outline-secondary">Back</a>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection


