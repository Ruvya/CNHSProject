@extends('layouts.admin')
@section('title', 'Edit Student')
@section('content')
<div class="container">
    <h1>Edit Student</h1>
    <form action="{{ route('admin.students.update', $student) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.students.partials.form')
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.users.students.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 