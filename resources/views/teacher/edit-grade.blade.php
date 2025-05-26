@extends('layouts.teacher')

@section('title', 'Edit Grade')

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
                            <li class="breadcrumb-item"><a href="{{ route('teacher.students.show', $student->id) }}">{{ $student->full_name }}</a></li>
                            <li class="breadcrumb-item active">Edit Grade</li>
                        </ol>
                    </nav>
                    <h2 class="mb-1">Edit Grade</h2>
                    <p class="text-muted">{{ $student->full_name }} - {{ $subject->name }}</p>
                </div>
                <div>
                    <a href="{{ route('teacher.students.show', $student->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star me-2"></i>Grade Management
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Student and Subject Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-card">
                                <h6 class="info-title">Student Information</h6>
                                <div class="info-content">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $student->full_name }}</div>
                                            <small class="text-muted">{{ $student->student_id }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card">
                                <h6 class="info-title">Subject Information</h6>
                                <div class="info-content">
                                    <div class="fw-bold">{{ $subject->name }}</div>
                                    <small class="text-muted">{{ $subject->code }} - Grade {{ $subject->grade_level }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grade Form -->
                    <form id="gradeForm">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quarter1" class="form-label">Quarter 1</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="quarter1" 
                                           name="quarter1" 
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           value="{{ $grade->quarter1 ?? '' }}"
                                           placeholder="Enter grade (0-100)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quarter2" class="form-label">Quarter 2</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="quarter2" 
                                           name="quarter2" 
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           value="{{ $grade->quarter2 ?? '' }}"
                                           placeholder="Enter grade (0-100)">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quarter3" class="form-label">Quarter 3</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="quarter3" 
                                           name="quarter3" 
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           value="{{ $grade->quarter3 ?? '' }}"
                                           placeholder="Enter grade (0-100)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quarter4" class="form-label">Quarter 4</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="quarter4" 
                                           name="quarter4" 
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           value="{{ $grade->quarter4 ?? '' }}"
                                           placeholder="Enter grade (0-100)">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="finalGrade" class="form-label">Final Grade</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="finalGrade" 
                                           readonly 
                                           placeholder="Calculated automatically"
                                           value="{{ $grade->final_grade ? number_format($grade->final_grade, 2) : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="remarks" 
                                           name="remarks" 
                                           value="{{ $grade->remarks ?? '' }}"
                                           placeholder="Optional remarks">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.students.show', $student->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save Grade
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 0.75rem;
    font-weight: 600;
}

.info-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    height: 100%;
}

.info-title {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.info-content {
    color: #6c757d;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
}

#finalGrade {
    background-color: #e9ecef;
    font-weight: 600;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quarterInputs = ['quarter1', 'quarter2', 'quarter3', 'quarter4'];
    const finalGradeInput = document.getElementById('finalGrade');
    
    // Calculate final grade when quarter grades change
    function calculateFinalGrade() {
        const grades = quarterInputs.map(id => {
            const value = document.getElementById(id).value;
            return value ? parseFloat(value) : null;
        }).filter(grade => grade !== null);
        
        if (grades.length > 0) {
            const average = grades.reduce((sum, grade) => sum + grade, 0) / grades.length;
            finalGradeInput.value = average.toFixed(2);
        } else {
            finalGradeInput.value = '';
        }
    }
    
    // Add event listeners to quarter inputs
    quarterInputs.forEach(id => {
        document.getElementById(id).addEventListener('input', calculateFinalGrade);
    });
    
    // Handle form submission
    document.getElementById('gradeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
        
        fetch('{{ route("teacher.save-grade") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show';
                alert.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.card-body').insertBefore(alert, document.querySelector('form'));
                
                // Update final grade if provided
                if (data.final_grade) {
                    finalGradeInput.value = parseFloat(data.final_grade).toFixed(2);
                }
            } else {
                throw new Error(data.message || 'Failed to save grade');
            }
        })
        .catch(error => {
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show';
            alert.innerHTML = `
                <i class="fas fa-exclamation-circle me-2"></i>${error.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.card-body').insertBefore(alert, document.querySelector('form'));
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
});
</script>
@endsection
