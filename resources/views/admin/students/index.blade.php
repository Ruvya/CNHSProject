@extends('layouts.admin')
@section('title', 'Students')
@section('content')
<div class="container">
    <h1>Students</h1>
    <a href="{{ route('admin.students.create') }}" class="btn btn-primary mb-3">Add Student</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Grade Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->student_id }}</td>
                    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                    <td>{{ $student->grade_level }}</td>
                    <td>
                        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.students.destroy', $student) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $students->links() }}
</div>
@endsection 