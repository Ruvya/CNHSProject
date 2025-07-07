@extends('layouts.admin')

@section('title', 'Student Accounts')

@section('content')
<div class="page-header">
    <h1 class="page-title">Student Accounts</h1>
    <p class="page-subtitle">Manage student accounts created through credential login</p>
    <div class="page-actions">
        <a href="{{ route('admin.credentials.generate') }}" class="btn btn-primary">
            <i class="fas fa-key me-2"></i>Generate New Credentials
        </a>
        <a href="{{ route('admin.credentials.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-list me-2"></i>Manage Credentials
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card stat-card-primary">
            <div class="stat-card-body">
                <div class="stat-card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-title">Total Students</div>
                    <div class="stat-card-value">{{ $totalStudents }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-card-warning">
            <div class="stat-card-body">
                <div class="stat-card-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-title">Temporary Accounts</div>
                    <div class="stat-card-value">{{ $temporaryAccounts }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-card-success">
            <div class="stat-card-body">
                <div class="stat-card-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-title">Completed Profiles</div>
                    <div class="stat-card-value">{{ $completedProfiles }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-card-info">
            <div class="stat-card-body">
                <div class="stat-card-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-title">Completion Rate</div>
                    <div class="stat-card-value">{{ $totalStudents > 0 ? round(($completedProfiles / $totalStudents) * 100) : 0 }}%</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.users.students.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               value="{{ request('search') }}" placeholder="Name, ID, or email">
                    </div>
                    <div class="col-md-3">
                        <label for="grade_level" class="form-label">Grade Level</label>
                        <select name="grade_level" id="grade_level" class="form-select">
                            <option value="">All Grade Levels</option>
                            @foreach($availableGradeLevels as $grade)
                                <option value="{{ $grade }}" {{ request('grade_level') === $grade ? 'selected' : '' }}>
                                    {{ $grade }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="account_type" class="form-label">Account Status</label>
                        <select name="account_type" id="account_type" class="form-select">
                            <option value="">All Accounts</option>
                            <option value="temporary" {{ request('account_type') === 'temporary' ? 'selected' : '' }}>
                                Temporary (Incomplete)
                            </option>
                            <option value="completed" {{ request('account_type') === 'completed' ? 'selected' : '' }}>
                                Completed Profiles
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                        <a href="{{ route('admin.users.students.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Students Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    Student Accounts ({{ $students->total() }})
                </h5>
            </div>
            <div class="card-body p-0">
                @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Grade Level</th>
                                <th>Account Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>
                                    <code class="text-primary">{{ $student->student_id }}</code>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center">
                                            {{ substr($student->first_name ?? 'S', 0, 1) }}{{ substr($student->last_name ?? 'T', 0, 1) }}
                                        </div>
                                        <div>
                                            <strong>{{ $student->full_name ?? $student->student_id }}</strong>
                                            @if($student->is_temporary_account)
                                                <br><small class="text-warning">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    Profile incomplete
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($student->email && !str_contains($student->email, '@temp.cnhs.edu.ph'))
                                        {{ $student->email }}
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->grade_level)
                                        <span class="badge bg-info">{{ $student->grade_level }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->profile_completed)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>
                                            Complete
                                        </span>
                                    @elseif($student->is_temporary_account)
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>
                                            Incomplete
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Unknown</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $student->created_at->format('M j, Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.students.show', $student) }}" 
                                           class="btn btn-sm btn-outline-info" title="View Profile">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.users.students.destroy', $student) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this student account?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="card-footer">
                    {{ $students->appends(request()->query())->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No student accounts found</h5>
                    <p class="text-muted">Students will appear here after they log in using generated credentials.</p>
                    <a href="{{ route('admin.credentials.generate') }}" class="btn btn-primary">
                        <i class="fas fa-key me-2"></i>Generate Student Credentials
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
