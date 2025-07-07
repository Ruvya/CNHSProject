@extends('layouts.registrar')

@section('title', 'Teacher Yearly Records - ' . $teacher->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yearly Records for {{ $teacher->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('registrar.teachers.yearly-records.create', $teacher) }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Add New Record
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($records->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>School Year</th>
                                        <th>Department</th>
                                        <th>Position</th>
                                        <th>Subjects</th>
                                        <th>Grade Levels</th>
                                        <th>Students</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($records as $record)
                                        <tr>
                                            <td>
                                                <strong>{{ $record->school_year }}</strong>
                                                @if($record->school_year === $teacher->getCurrentSchoolYear())
                                                    <span class="badge bg-success ms-2">Current</span>
                                                @endif
                                            </td>
                                            <td>{{ $record->department ?? 'Not Set' }}</td>
                                            <td>{{ $record->position ?? 'Teacher' }}</td>
                                            <td>
                                                <small>{{ $record->formatted_subjects }}</small>
                                            </td>
                                            <td>
                                                <small>{{ $record->formatted_grade_levels }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $record->total_students }}</span>
                                                @if($record->advisory_section)
                                                    <br><small class="text-muted">Adviser: {{ $record->advisory_section }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $record->status === 'active' ? 'success' : ($record->status === 'inactive' ? 'secondary' : 'warning') }}">
                                                    {{ ucfirst($record->status) }}
                                                </span>
                                                <br><small class="text-muted">{{ ucfirst($record->employment_status) }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('registrar.teachers.yearly-records.edit', [$teacher, $record]) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="confirmDelete({{ $record->id }})" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                
                                                <!-- Hidden delete form -->
                                                <form id="delete-form-{{ $record->id }}" 
                                                      action="{{ route('registrar.teachers.yearly-records.destroy', [$teacher, $record]) }}" 
                                                      method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Yearly Records Found</h5>
                            <p class="text-muted">This teacher doesn't have any yearly records yet.</p>
                            <a href="{{ route('registrar.teachers.yearly-records.create', $teacher) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create First Record
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Teacher Information Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Teacher Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $teacher->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $teacher->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Contact:</strong></td>
                                    <td>{{ $teacher->contact_number ?? 'Not provided' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Primary Subject:</strong></td>
                                    <td>{{ $teacher->subject ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Strand:</strong></td>
                                    <td>{{ $teacher->strand ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($teacher->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(recordId) {
    if (confirm('Are you sure you want to delete this yearly record? This action cannot be undone.')) {
        document.getElementById('delete-form-' + recordId).submit();
    }
}
</script>
@endsection
