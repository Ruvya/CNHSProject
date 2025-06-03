@extends('layouts.teacher')

@section('title', 'Subjects')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">My Subjects</h1>
        </div>
    </div>
    <div class="row g-4">
        @forelse($subjects as $subject)
            <div class="col-md-6 col-lg-4">
                <div class="card subject-card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="subject-icon me-3">
                                <i class="fas fa-book fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">{{ $subject->name }}</h5>
                                <small class="text-muted">Code: {{ $subject->code }}</small>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li><strong>Grade Level:</strong> {{ $subject->grade_level }}</li>
                            <li><strong>Track:</strong> {{ $subject->track ?? 'N/A' }}</li>
                            <li><strong>Students:</strong> {{ $subject->students_count ?? 0 }}</li>
                        </ul>
                        <div class="mt-auto">
                            <a href="{{ route('teacher.subjects.students', $subject) }}" class="btn btn-outline-primary btn-sm me-2">
                                <i class="fas fa-users"></i> View Students
                            </a>
                            <a href="{{ route('teacher.subjects.grades', $subject) }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-clipboard-list"></i> Manage Grades
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">No subjects assigned yet.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('styles')
<style>
.subject-card {
    border: none;
    border-radius: 12px;
    transition: box-shadow 0.2s;
}
.subject-card:hover {
    box-shadow: 0 0.5rem 1.5rem 0 rgba(58, 59, 69, 0.15);
}
.subject-icon {
    width: 48px;
    height: 48px;
    background: #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.card-title {
    font-size: 1.2rem;
    font-weight: 600;
}
.btn-sm {
    font-size: 0.9rem;
    padding: 0.35rem 0.75rem;
}
</style>
@endsection