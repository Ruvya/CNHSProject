@extends('layouts.student')

@section('content')
<div class="container">
    <h2 class="mb-3">My Section</h2>

    @if(!$student->section)
        <div class="alert alert-warning">Your section is not set yet. Please contact the registrar.</div>
    @else
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div><strong>Section:</strong> {{ $student->section }}</div>
                        <div><strong>Grade Level:</strong> {{ $section->grade_level ?? ($student->grade_level ?? '—') }}</div>
                        <div><strong>School Year:</strong> {{ $section->school_year ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div><strong>Adviser:</strong> {{ optional(optional($section)->adviser)->name ?? '—' }}</div>
                        <div><strong>Track/Strand:</strong> {{ optional($section)->track ?? ($student->track ?? '—') }} {{ optional($section)->strand ? ' / '. $section->strand : '' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Classmates</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Student ID</th>
                            <th>Gender</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classmates as $i => $classmate)
                            <tr @if($classmate->id === $student->id) class="table-active" @endif>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $classmate->first_name }} {{ $classmate->last_name }}</td>
                                <td>{{ $classmate->student_id }}</td>
                                <td>{{ $classmate->gender ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No classmates found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection


