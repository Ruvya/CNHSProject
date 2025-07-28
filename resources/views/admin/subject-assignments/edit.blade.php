@extends('layouts.admin')
@section('title', 'Edit Subject Assignment')
@section('content')
<div class="container">
    <h1>Edit Subject Assignment</h1>
    <form action="{{ route('admin.subject-assignments.update', $subjectAssignment) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.subject-assignments.partials.form')
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.subject-assignments.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 