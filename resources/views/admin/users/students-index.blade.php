@extends('layouts.admin')

@section('title', 'Student Accounts')

@section('content')
<style>
.angled-header-student {
    position: relative;
    display: flex;
    align-items: stretch;
    border-radius: 24px;
    overflow: hidden;
    min-height: 120px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
.angled-header-student .left {
    background: #fa7816;
    color: #fff;
    flex: 0 0 65%;
    display: flex;
    align-items: center;
    padding: 2rem 2.5rem;
    position: relative;
    z-index: 1;
}
.angled-header-student .right {
    background: #4286f4;
    flex: 1 1 35%;
    position: relative;
    z-index: 0;
}
.angled-header-student .left-content {
    display: flex;
    align-items: center;
    z-index: 2;
}
.angled-header-student .icon {
    font-size: 3.5rem;
    margin-right: 1.5rem;
    display: flex;
    align-items: center;
}
.angled-header-student .titles {
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.angled-header-student .main-title {
    font-size: 2rem;
    font-weight: bold;
    font-family: 'Poppins', sans-serif;
    line-height: 1.1;
}
.angled-header-student .subtitle {
    font-size: 1rem;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    margin-top: 0.25rem;
}
.angled-header-student .diagonal {
    position: absolute;
    top: 0; right: 0; bottom: 0; left: 60%;
    z-index: 1;
    width: 40%;
    height: 100%;
    pointer-events: none;
}
@media (max-width: 900px) {
    .angled-header-student .main-title { font-size: 1.5rem; }
    .angled-header-student .icon { font-size: 2rem; }
    .angled-header-student .left, .angled-header-student .right { padding: 1rem; }
}
</style>
<div class="angled-header-student mb-4">
    <div class="left">
        <div class="left-content">
            <span class="icon"><i class="fas fa-users"></i></span>
            <div class="titles">
                <span class="main-title">Student Records</span>
                <span class="subtitle">Welcome back! Here's an overview of your classes and activities.</span>
            </div>
        </div>
    </div>
    <div class="right"></div>
    <svg class="diagonal" viewBox="0 0 100 100" preserveAspectRatio="none">
        <polygon fill="#4286f4" points="70,0 100,0 100,100 30,100" />
    </svg>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <!-- Cards removed as per request -->
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
                               value="{{ request('search') }}" placeholder="Name or ID">
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
                                <th>Grade Level</th>
                                <th>Track</th>
                                <th>Cluster</th>
                                <th>Password</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>
                                    <code class="text-primary" style="font-family: 'Poppins', sans-serif;">{{ $student->student_id }}</code>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
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
                                    {{ $student->grade_level ?? '-' }}
                                </td>
                                <td>
                                    {{ $student->track ?? '-' }}
                                </td>
                                <td>
                                    {{ $student->cluster ?? '-' }}
                                </td>
                                <td>Temp_123</td>
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
