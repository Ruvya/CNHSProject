@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Section: {{ $section->name }}</h1>
        <a href="{{ route('admin.sections.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header"><strong>Details</strong></div>
                <div class="card-body">
                    <div class="mb-2"><strong>Grade Level:</strong> {{ $section->grade_level }}</div>
                    <div class="mb-2"><strong>Track/Strand:</strong> {{ $section->strand ?: $section->track }}</div>
                    <div class="mb-2"><strong>Adviser:</strong> {{ optional($section->adviser)->name ?: '—' }}</div>
                    <div class="mb-2"><strong>Capacity:</strong> {{ $section->current_enrollment }} / {{ $section->max_capacity }}</div>
                    <div class="mb-2"><strong>Status:</strong>
                        <span class="badge {{ $section->status === 'active' ? 'bg-success' : ($section->status === 'full' ? 'bg-warning text-dark' : 'bg-secondary') }}">{{ ucfirst($section->status) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Students</strong>
                    <span class="text-muted">{{ $section->students->count() }} students</span>
                </div>
                <div class="card-body">
                    @if($section->students->isEmpty())
                        <div class="alert alert-info">No students assigned to this section yet.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Grade</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($section->students as $student)
                                        <tr>
                                            <td>{{ $student->student_id }}</td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->grade_level }}</td>
                                            <td class="text-end">
                                                <form method="POST" action="{{ route('admin.sections.remove-student', [$section, $student]) }}" onsubmit="return confirm('Remove this student from the section?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header"><strong>Assign Students</strong></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.sections.assign-students', $section) }}">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label">Eligible Students (same grade and strand/track)</label>
                        <select class="form-select" name="student_ids[]" multiple size="10">
                            @foreach($eligibleStudents as $s)
                                <option value="{{ $s->id }}">{{ $s->student_id }} — {{ $s->name }} @if($s->section) ({{ $s->section }}) @endif</option>
                            @endforeach
                        </select>
                        <div class="form-text">Hold Ctrl/Cmd to select multiple. Already assigned students will be moved to this section.</div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Assign Selected</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


