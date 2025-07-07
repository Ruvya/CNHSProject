@extends('layouts.registrar')

@section('title', 'Add Yearly Record - ' . $student->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New Yearly Record for {{ $student->first_name }} {{ $student->last_name }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('registrar.students.yearly-records.store', $student) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="school_year">School Year (e.g., 2023-2024)</label>
                            <input type="text" name="school_year" id="school_year" class="form-control" value="{{ old('school_year') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="grade_level">Grade Level</label>
                            <input type="text" name="grade_level" id="grade_level" class="form-control" value="{{ old('grade_level') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="section">Section</label>
                            <input type="text" name="section" id="section" class="form-control" value="{{ old('section') }}">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="enrolled">Enrolled</option>
                                <option value="promoted">Promoted</option>
                                <option value="retained">Retained</option>
                                <option value="graduated">Graduated</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">Save Record</button>
                        <a href="{{ route('registrar.students.yearly-records.index', $student) }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 