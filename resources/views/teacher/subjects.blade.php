@extends('layouts.teacher')

@section('title', 'Subjects')

@section('styles')
<style>
    /* New Header Style */
    .page-header-design {
        background: linear-gradient(115deg, #f97316 60%, #3b82f6 60%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(249, 115, 22, 0.25);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header-left-content {
        display: flex;
        align-items: center;
    }
    .header-left-content i {
        font-size: 3rem;
        margin-right: 1.5rem;
        opacity: 0.8;
    }
    .header-left-content h1 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .header-left-content p {
        margin: 0.25rem 0 0 0;
        opacity: 0.9;
        font-size: 1.1rem;
    }
    .header-right-content .subject-count-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.75rem 1.25rem;
        border-radius: 50px;
        display: flex;
        align-items: center;
        font-weight: 600;
        font-size: 1rem;
        backdrop-filter: blur(10px);
    }
    .header-right-content .subject-count-badge i {
        margin-right: 0.5rem;
    }

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

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-design">
                <div class="header-left-content">
                    <i class="fas fa-book"></i>
                    <div>
                        <h1>My Subjects</h1>
                        <p>Manage and view your assigned subjects</p>
                    </div>
                </div>
                <div class="header-right-content">
                    <div class="subject-count-badge">
                        <i class="fas fa-book"></i>
                        <span>{{ $subjects->count() }} Subjects</span>
                    </div>
                </div>
            </div>
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