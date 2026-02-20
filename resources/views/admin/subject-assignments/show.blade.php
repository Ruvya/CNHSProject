@extends('layouts.admin')
@section('title', 'Subject Assignment Details')
@section('content')
<div class="container">
    <h1>Subject Assignment Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Assignment #{{ $subjectAssignment->id }}</h5>
            <p><strong>Teacher:</strong> {{ $subjectAssignment->teacher->name ?? '' }}</p>
            <p><strong>Subject:</strong> {{ $subjectAssignment->subject->name ?? '' }}</p>
            <p><strong>School Year:</strong> {{ $subjectAssignment->school_year }}</p>
            <p><strong>Grading Period:</strong> {{ $subjectAssignment->grading_period }}</p>
            <p><strong>Status:</strong> {{ $subjectAssignment->status }}</p>
            <a href="{{ route('admin.subject-assignments.edit', $subjectAssignment) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('admin.subject-assignments.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection 