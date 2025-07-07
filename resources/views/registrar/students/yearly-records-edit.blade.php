@extends('layouts.registrar')

@section('title', 'Edit Yearly Record - ' . $student->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Yearly Record for {{ $student->first_name }} {{ $student->last_name }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('registrar.students.yearly-records.update', [$student, $record]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="school_year">School Year</label>
                            <input type="text" name="school_year" id="school_year" class="form-control" value="{{ old('school_year', $record->school_year) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="grade_level">Grade Level</label>
                            <input type="text" name="grade_level" id="grade_level" class="form-control" value="{{ old('grade_level', $record->grade_level) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="section">Section</label>
                            <input type="text" name="section" id="section" class="form-control" value="{{ old('section', $record->section) }}">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="enrolled" {{ $record->status == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                <option value="promoted" {{ $record->status == 'promoted' ? 'selected' : '' }}>Promoted</option>
                                <option value="retained" {{ $record->status == 'retained' ? 'selected' : '' }}>Retained</option>
                                <option value="graduated" {{ $record->status == 'graduated' ? 'selected' : '' }}>Graduated</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Record</button>
                        <a href="{{ route('registrar.students.yearly-records.index', $student) }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 