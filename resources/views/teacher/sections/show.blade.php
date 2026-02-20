@extends('layouts.teacher')

@section('content')
<div class="container">
    <div class="card mb-4 border-0" style="background: #ffffff; color: #000;">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="me-3" style="width: 46px; height: 46px; background: rgba(255,255,255,.15); border-radius: 10px; display: grid; place-items: center;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h3 class="mb-0" style="font-weight: 700;">Section: {{ $section->name }}</h3>
                    <small class="opacity-75">School Year {{ $section->school_year }}</small>
                </div>
            </div>
            <a href="{{ route('teacher.sections.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Sections
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted">Grade Level</div>
                    <div class="fs-5 fw-semibold">{{ $section->grade_level }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted">Track / Strand</div>
                    <div class="fs-5 fw-semibold">{{ $section->track }} {{ $section->strand ? ' / '.$section->strand : '' }}</div>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted">Students</div>
                    <div class="fs-5 fw-semibold">{{ $students->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Students</span>
            <div class="text-muted small">Sorted by Last Name</div>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:70px">#</th>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Gender</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $i => $student)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="fw-semibold">{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td>{{ $student->student_id }}</td>
                            <td>{{ $student->gender ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">No students found in this section.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


