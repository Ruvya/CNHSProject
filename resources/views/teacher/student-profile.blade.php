@extends('layouts.teacher')

@section('title', 'Student Profile')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.classlist') }}">Class List</a></li>
                            <li class="breadcrumb-item active">Student Profile</li>
                        </ol>
                    </nav>
                    <h2 class="mb-1">{{ $student->full_name }}</h2>
                    <p class="text-muted">Student ID: {{ $student->student_id }}</p>
                </div>
                <div>
                    <a href="{{ route('teacher.classlist') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Class List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Student Information -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>Student Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-lg bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center">
                            {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                        </div>
                        <h5 class="mt-2 mb-0">{{ $student->full_name }}</h5>
                        <p class="text-muted">{{ $student->student_id }}</p>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-item">
                            <label class="info-label">Email:</label>
                            <span class="info-value">{{ $student->email }}</span>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Grade Level:</label>
                            <span class="badge bg-info">Grade {{ $student->grade_level }}</span>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Section:</label>
                            <span class="badge bg-secondary">Section {{ $student->section }}</span>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Contact:</label>
                            <span class="info-value">{{ $student->contact_number ?? 'Not provided' }}</span>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Address:</label>
                            <span class="info-value">{{ $student->address ?? 'Not provided' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subjects and Grades -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-book me-2"></i>Subjects & Grades
                    </h5>
                </div>
                <div class="card-body">
                    @if($student->subjects->where('teacher_id', $teacher->id)->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Subjects Assigned</h5>
                            <p class="text-muted">This student is not enrolled in any of your subjects.</p>
                        </div>
                    @else
                        <div class="row">
                            @foreach($student->subjects->where('teacher_id', $teacher->id) as $subject)
                                @php
                                    $grade = $student->grades->where('subject_id', $subject->id)->first();
                                @endphp
                                <div class="col-md-6 mb-4">
                                    <div class="subject-card">
                                        <div class="subject-header">
                                            <h6 class="subject-name">{{ $subject->name }}</h6>
                                            <span class="subject-code">{{ $subject->code }}</span>
                                        </div>
                                        
                                        @if($grade)
                                            <div class="grades-section">
                                                <div class="grade-row">
                                                    <span class="grade-label">Quarter 1:</span>
                                                    <span class="grade-value">{{ $grade->quarter1 ?? '-' }}</span>
                                                </div>
                                                <div class="grade-row">
                                                    <span class="grade-label">Quarter 2:</span>
                                                    <span class="grade-value">{{ $grade->quarter2 ?? '-' }}</span>
                                                </div>
                                                <div class="grade-row">
                                                    <span class="grade-label">Quarter 3:</span>
                                                    <span class="grade-value">{{ $grade->quarter3 ?? '-' }}</span>
                                                </div>
                                                <div class="grade-row">
                                                    <span class="grade-label">Quarter 4:</span>
                                                    <span class="grade-value">{{ $grade->quarter4 ?? '-' }}</span>
                                                </div>
                                                <div class="grade-row final-grade">
                                                    <span class="grade-label">Final Grade:</span>
                                                    <span class="grade-value">
                                                        @if($grade->final_grade)
                                                            <span class="badge {{ $grade->final_grade >= 75 ? 'bg-success' : 'bg-warning' }}">
                                                                {{ number_format($grade->final_grade, 1) }}
                                                            </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </div>
                                                @if($grade->remarks)
                                                    <div class="grade-remarks">
                                                        <small class="text-muted">{{ $grade->remarks }}</small>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="no-grades">
                                                <p class="text-muted mb-0">No grades recorded yet</p>
                                            </div>
                                        @endif
                                        
                                        <div class="subject-actions">
                                            <a href="{{ route('teacher.grades.edit', [$student->id, $subject->id]) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit me-1"></i>Edit Grades
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
    font-size: 1.5rem;
    font-weight: 600;
}

.info-group {
    space-y: 0.75rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #6c757d;
    margin: 0;
}

.info-value {
    color: #495057;
}

.subject-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    height: 100%;
    background: #f8f9fa;
}

.subject-header {
    margin-bottom: 1rem;
}

.subject-name {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.25rem;
}

.subject-code {
    font-size: 0.875rem;
    color: #6c757d;
}

.grades-section {
    margin-bottom: 1rem;
}

.grade-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.25rem 0;
}

.grade-row.final-grade {
    border-top: 1px solid #dee2e6;
    padding-top: 0.5rem;
    margin-top: 0.5rem;
    font-weight: 600;
}

.grade-label {
    font-size: 0.875rem;
    color: #6c757d;
}

.grade-value {
    font-weight: 500;
}

.grade-remarks {
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid #dee2e6;
}

.no-grades {
    text-align: center;
    padding: 2rem 0;
}

.subject-actions {
    margin-top: 1rem;
    text-align: center;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
</style>
@endsection
