@extends('layouts.registrar')

@section('title', 'Create Teacher Yearly Record - ' . $teacher->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Yearly Record for {{ $teacher->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('registrar.teachers.yearly-records.index', $teacher) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Records
                        </a>
                    </div>
                </div>
                <form action="{{ route('registrar.teachers.yearly-records.store', $teacher) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="school_year" class="form-label">School Year <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('school_year') is-invalid @enderror" 
                                           id="school_year" name="school_year" value="{{ old('school_year', $currentSchoolYear) }}" 
                                           placeholder="e.g., 2023-2024" required>
                                    @error('school_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                           id="department" name="department" value="{{ old('department', $teacher->department) }}" 
                                           placeholder="e.g., Mathematics, Science">
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="position" class="form-label">Position</label>
                                    <select class="form-select @error('position') is-invalid @enderror" id="position" name="position">
                                        <option value="">Select Position</option>
                                        <option value="Teacher" {{ old('position') === 'Teacher' ? 'selected' : '' }}>Teacher</option>
                                        <option value="Head Teacher" {{ old('position') === 'Head Teacher' ? 'selected' : '' }}>Head Teacher</option>
                                        <option value="Department Head" {{ old('position') === 'Department Head' ? 'selected' : '' }}>Department Head</option>
                                        <option value="Subject Coordinator" {{ old('position') === 'Subject Coordinator' ? 'selected' : '' }}>Subject Coordinator</option>
                                        <option value="Guidance Counselor" {{ old('position') === 'Guidance Counselor' ? 'selected' : '' }}>Guidance Counselor</option>
                                    </select>
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="advisory_section" class="form-label">Advisory Section</label>
                                    <input type="text" class="form-control @error('advisory_section') is-invalid @enderror" 
                                           id="advisory_section" name="advisory_section" value="{{ old('advisory_section') }}" 
                                           placeholder="e.g., 11-STEM-A">
                                    @error('advisory_section')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="subjects_taught" class="form-label">Subjects Taught</label>
                                    <select class="form-select @error('subjects_taught') is-invalid @enderror" 
                                            id="subjects_taught" name="subjects_taught[]" multiple>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->name }}" 
                                                {{ in_array($subject->name, old('subjects_taught', [])) ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Hold Ctrl/Cmd to select multiple subjects</div>
                                    @error('subjects_taught')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="grade_levels_handled" class="form-label">Grade Levels Handled</label>
                                    <select class="form-select @error('grade_levels_handled') is-invalid @enderror" 
                                            id="grade_levels_handled" name="grade_levels_handled[]" multiple>
                                        <option value="Grade 11" {{ in_array('Grade 11', old('grade_levels_handled', [])) ? 'selected' : '' }}>Grade 11</option>
                                        <option value="Grade 12" {{ in_array('Grade 12', old('grade_levels_handled', [])) ? 'selected' : '' }}>Grade 12</option>
                                    </select>
                                    <div class="form-text">Hold Ctrl/Cmd to select multiple grade levels</div>
                                    @error('grade_levels_handled')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="total_students" class="form-label">Total Students</label>
                                    <input type="number" class="form-control @error('total_students') is-invalid @enderror" 
                                           id="total_students" name="total_students" value="{{ old('total_students', 0) }}" 
                                           min="0" placeholder="0">
                                    @error('total_students')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="teaching_load" class="form-label">Teaching Load (Hours)</label>
                                    <input type="number" class="form-control @error('teaching_load') is-invalid @enderror" 
                                           id="teaching_load" name="teaching_load" value="{{ old('teaching_load', 0) }}" 
                                           min="0" max="999.99" step="0.01" placeholder="0.00">
                                    @error('teaching_load')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="employment_status" class="form-label">Employment Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('employment_status') is-invalid @enderror" 
                                            id="employment_status" name="employment_status" required>
                                        <option value="">Select Status</option>
                                        <option value="regular" {{ old('employment_status') === 'regular' ? 'selected' : '' }}>Regular</option>
                                        <option value="substitute" {{ old('employment_status') === 'substitute' ? 'selected' : '' }}>Substitute</option>
                                        <option value="part-time" {{ old('employment_status') === 'part-time' ? 'selected' : '' }}>Part-time</option>
                                    </select>
                                    @error('employment_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="transferred" {{ old('status') === 'transferred' ? 'selected' : '' }}>Transferred</option>
                                        <option value="resigned" {{ old('status') === 'resigned' ? 'selected' : '' }}>Resigned</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Additional notes for this school year...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Create Record
                        </button>
                        <a href="{{ route('registrar.teachers.yearly-records.index', $teacher) }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
