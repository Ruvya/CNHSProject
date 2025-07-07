@extends('layouts.registrar')

@section('title', 'Student Yearly Records - ' . $student->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yearly Records for {{ $student->first_name }} {{ $student->last_name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('registrar.students.yearly-records.create', $student) }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Add New Record
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>School Year</th>
                                <th>Grade Level</th>
                                <th>Section</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $record)
                                <tr>
                                    <td>{{ $record->school_year }}</td>
                                    <td>{{ $record->grade_level }}</td>
                                    <td>{{ $record->section ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($record->status) }}</td>
                                    <td>
                                        <a href="{{ route('registrar.students.yearly-records.edit', [$student, $record]) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('registrar.students.yearly-records.destroy', [$student, $record]) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No yearly records found for this student.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
