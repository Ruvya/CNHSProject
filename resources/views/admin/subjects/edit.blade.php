@extends('layouts.admin')

@section('title', 'Edit Subject')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-edit me-2 text-warning"></i>
                Edit Subject
            </h1>
            <p class="text-muted mb-0">Update information for {{ $subject->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.subjects') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Subjects
            </a>
            <a href="{{ route('admin.subjects.show', $subject) }}" class="btn btn-outline-info">
                <i class="fas fa-eye me-2"></i>View Details
            </a>
        </div>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-1">
                        <i class="fas fa-edit me-2 text-warning"></i>
                        Edit Subject Information
                    </h5>
                    <p class="text-muted mb-0 small">Modify the subject details below</p>
                </div>
            <div class="card-body">
                <form action="{{ route('admin.subjects.update', $subject) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Subject Name -->
                        <div class="col-md-8 mb-3">
                            <label for="name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $subject->name) }}"
                                   placeholder="e.g., Mathematics, English, Science">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subject Code -->
                        <div class="col-md-4 mb-3">
                            <label for="code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror"
                                   id="code" name="code" value="{{ old('code', $subject->code) }}"
                                   placeholder="e.g., MATH101, ENG101">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Grade Level -->
                        <div class="col-md-4 mb-3">
                            <label for="grade_level" class="form-label">Grade Level <span class="text-danger">*</span></label>
                            <select class="form-select @error('grade_level') is-invalid @enderror" id="grade_level" name="grade_level">
                                <option value="">Select Grade Level</option>
                                <option value="Grade 11" {{ old('grade_level', $subject->grade_level) === 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                <option value="Grade 12" {{ old('grade_level', $subject->grade_level) === 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                            </select>
                            @error('grade_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Grading -->
                        <div class="col-md-4 mb-3">
                            <label for="grading" class="form-label">Grading <span class="text-danger">*</span></label>
                            <select class="form-select @error('grading') is-invalid @enderror" id="grading" name="grading" required>
                                <option value="">Select Grading</option>
                                <option value="First Grading" {{ old('grading', $subject->grading ?? $subject->semester === '1st Semester' ? 'First Grading' : '') === 'First Grading' ? 'selected' : '' }}>First Grading</option>
                                <option value="Second Grading" {{ old('grading', $subject->grading ?? $subject->semester === '2nd Semester' ? 'Second Grading' : '') === 'Second Grading' ? 'selected' : '' }}>Second Grading</option>
                                <option value="Third Grading" {{ old('grading', $subject->grading) === 'Third Grading' ? 'selected' : '' }}>Third Grading</option>
                                <option value="Fourth Grading" {{ old('grading', $subject->grading) === 'Fourth Grading' ? 'selected' : '' }}>Fourth Grading</option>
                                <option value="All Gradings" {{ old('grading', $subject->grading ?? $subject->semester === 'Both Semesters' ? 'All Gradings' : '') === 'All Gradings' ? 'selected' : '' }}>All Gradings</option>
                            </select>
                            @error('grading')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Teacher -->
                        <div class="col-md-4 mb-3">
                            <label for="teacher_id" class="form-label">Assigned Teacher</label>
                            <select class="form-select @error('teacher_id') is-invalid @enderror" id="teacher_id" name="teacher_id">
                                <option value="">No Teacher Assigned</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $subject->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Track -->
                        <div class="col-md-6 mb-3">
                            <label for="track" class="form-label">Track</label>
                            <select class="form-select @error('track') is-invalid @enderror" id="track" name="track">
                                <option value="">Select Track (Optional)</option>
                                <option value="Academic Track" {{ old('track', $subject->track) === 'Academic Track' ? 'selected' : '' }}>Academic Track</option>
                                <option value="Technical-Vocational-Livelihood Track" {{ old('track', $subject->track) === 'Technical-Vocational-Livelihood Track' ? 'selected' : '' }}>Technical-Vocational-Livelihood Track</option>
                                <option value="Sports Track" {{ old('track', $subject->track) === 'Sports Track' ? 'selected' : '' }}>Sports Track</option>
                                <option value="Arts and Design Track" {{ old('track', $subject->track) === 'Arts and Design Track' ? 'selected' : '' }}>Arts and Design Track</option>
                            </select>
                            @error('track')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Strand -->
                        <div class="col-md-6 mb-3">
                            <label for="strand" class="form-label">Strand</label>
                            <select class="form-select @error('strand') is-invalid @enderror" id="strand" name="strand">
                                <option value="">Select Strand (Optional)</option>
                                <option value="HUMSS" {{ old('strand', $subject->strand) === 'HUMSS' ? 'selected' : '' }}>HUMSS (Humanities and Social Sciences)</option>
                                <option value="STEM" {{ old('strand', $subject->strand) === 'STEM' ? 'selected' : '' }}>STEM (Science, Technology, Engineering and Mathematics)</option>
                                <option value="ABM" {{ old('strand', $subject->strand) === 'ABM' ? 'selected' : '' }}>ABM (Accountancy, Business and Management)</option>
                                <option value="GAS" {{ old('strand', $subject->strand) === 'GAS' ? 'selected' : '' }}>GAS (General Academic Strand)</option>
                                <option value="TVL-ICT" {{ old('strand', $subject->strand) === 'TVL-ICT' ? 'selected' : '' }}>TVL-ICT (Information and Communications Technology)</option>
                                <option value="TVL-HE" {{ old('strand', $subject->strand) === 'TVL-HE' ? 'selected' : '' }}>TVL-HE (Home Economics)</option>
                                <option value="TVL-AFA" {{ old('strand', $subject->strand) === 'TVL-AFA' ? 'selected' : '' }}>TVL-AFA (Agri-Fishery Arts)</option>
                            </select>
                            @error('strand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4"
                                  placeholder="Enter subject description, objectives, or additional information...">{{ old('description', $subject->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Optional: Provide a brief description of the subject content and objectives.</div>
                    </div>

                    <!-- Current Information Display -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Current Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li><strong>Created:</strong> {{ $subject->created_at->format('M d, Y') }}</li>
                                    <li><strong>Last Updated:</strong> {{ $subject->updated_at->format('M d, Y') }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li><strong>Enrolled Students:</strong> {{ $subject->students()->count() }}</li>
                                    <li><strong>Status:</strong>
                                        @if($subject->teacher_id)
                                            <span class="badge bg-success">Assigned</span>
                                        @else
                                            <span class="badge bg-warning">Unassigned</span>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.subjects') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>Update Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Update strand options based on track selection
    $('#track').on('change', function() {
        const track = $(this).val();
        const strandSelect = $('#strand');
        const currentStrand = '{{ old("strand", $subject->strand) }}';

        // Clear current options
        strandSelect.html('<option value="">Select Strand (Optional)</option>');

        if (track === 'Academic Track') {
            strandSelect.append(`
                <option value="HUMSS" ${currentStrand === 'HUMSS' ? 'selected' : ''}>HUMSS (Humanities and Social Sciences)</option>
                <option value="STEM" ${currentStrand === 'STEM' ? 'selected' : ''}>STEM (Science, Technology, Engineering and Mathematics)</option>
                <option value="ABM" ${currentStrand === 'ABM' ? 'selected' : ''}>ABM (Accountancy, Business and Management)</option>
                <option value="GAS" ${currentStrand === 'GAS' ? 'selected' : ''}>GAS (General Academic Strand)</option>
            `);
        } else if (track === 'Technical-Vocational-Livelihood Track') {
            strandSelect.append(`
                <option value="TVL-ICT" ${currentStrand === 'TVL-ICT' ? 'selected' : ''}>TVL-ICT (Information and Communications Technology)</option>
                <option value="TVL-HE" ${currentStrand === 'TVL-HE' ? 'selected' : ''}>TVL-HE (Home Economics)</option>
                <option value="TVL-AFA" ${currentStrand === 'TVL-AFA' ? 'selected' : ''}>TVL-AFA (Agri-Fishery Arts)</option>
            `);
        }
    });

    // Trigger track change on page load to populate strands
    $('#track').trigger('change');
});
</script>
@endpush
