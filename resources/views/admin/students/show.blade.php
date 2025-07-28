@extends('layouts.admin')
@section('title', 'Student Details')
@section('content')
<div class="container">
    <h1>Student Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $student->first_name }} {{ $student->last_name }}</h5>
            <p><strong>Student ID:</strong> {{ $student->student_id }}</p>
            <p><strong>Email:</strong> {{ $student->email }}</p>
            <p><strong>Grade Level:</strong> {{ $student->grade_level }}</p>
            <p><strong>Gender:</strong> {{ $student->gender }}</p>
            <p><strong>Contact Number:</strong> {{ $student->contact_number }}</p>
            <p><strong>Address:</strong> {{ $student->address }}</p>
            <p><strong>Parent Name:</strong> {{ $student->parent_name }}</p>
            <p><strong>Parent Contact:</strong> {{ $student->parent_contact }}</p>
            <p><strong>Track:</strong> {{ $student->track }}</p>
            <p><strong>Strand:</strong> {{ $student->strand }}</p>
            <p><strong>Section:</strong> {{ $student->section }}</p>
            <p><strong>LRN:</strong> {{ $student->lrn }}</p>
            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('admin.users.students.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection 