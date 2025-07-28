@extends('layouts.admin')
@section('title', 'Subject Details')
@section('content')
<div class="container">
    <h1>Subject Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $subject->name }}</h5>
            <p><strong>Code:</strong> {{ $subject->code }}</p>
            <p><strong>Grade Level:</strong> {{ $subject->grade_level }}</p>
            <p><strong>Track:</strong> {{ $subject->track }}</p>
            <p><strong>Cluster:</strong> {{ $subject->cluster }}</p>
            <p><strong>Specialization:</strong> {{ $subject->specialization }}</p>
            <p><strong>Grading:</strong> {{ $subject->grading }}</p>
            <p><strong>Teacher:</strong> {{ $subject->teacher->name ?? '' }}</p>
            <p><strong>Description:</strong> {{ $subject->description }}</p>
            <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
