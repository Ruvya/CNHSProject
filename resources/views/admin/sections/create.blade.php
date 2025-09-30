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
                <label class="form-label">Cluster</label>
                <select name="track" class="form-select" required>
                    <option value="">Select cluster</option>
                    @foreach($tracks as $t)
                        <option value="{{ $t }}" @selected(old('track')===$t)>{{ $t }}</option>
                    @endforeach
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
                <label class="form-label">Grading Period</label>
                @php $periods = ['First Grading','Second Grading','Third Grading','Fourth Grading']; @endphp
                <select name="grading_period" class="form-select">
                    @foreach($periods as $p)
                        <option value="{{ $p }}" @selected(old('grading_period','First Grading')===$p)>{{ $p }}</option>
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
@endsection


