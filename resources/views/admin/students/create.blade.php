@extends('layouts.admin')
@section('title', 'Add Student')
@section('content')
<div class="container">
    <h1>Add Student</h1>
    <form action="{{ route('admin.students.store') }}" method="POST">
        @csrf
        @include('admin.students.partials.form')
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="{{ route('admin.users.students.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 