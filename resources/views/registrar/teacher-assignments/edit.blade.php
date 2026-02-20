@extends('layouts.registrar')

@section('title', 'Edit Teacher Assignment')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Teacher Assignment</h1>
        <a href="{{ route('registrar.teacher-assignments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Assignments
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Assignment Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('registrar.teacher-assignments.update', $teacherAssignment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="teacher_id" class="form-label">Teacher</label>
                    <select class="form-control" id="teacher_id" name="teacher_id">
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ $teacherAssignment->teacher_id == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="subject_id" class="form-label">Subject</label>
                    <select class="form-control" id="subject_id" name="subject_id">
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $teacherAssignment->subject_id == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="active" {{ $teacherAssignment->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $teacherAssignment->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="completed" {{ $teacherAssignment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ $teacherAssignment->notes }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Assignment</button>
            </form>
        </div>
    </div>
</div>
@endsection 