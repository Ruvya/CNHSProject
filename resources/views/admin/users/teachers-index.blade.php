@extends('layouts.admin')

@section('title', 'Teacher Management')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Teacher Management</h1>
    <p class="page-subtitle">Manage teacher accounts and information</p>
    <div class="page-actions">
        <a href="{{ route('admin.users.teachers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Teacher
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card primary">
            <div class="stat-card-header">
                <div class="stat-card-title">Total Teachers</div>
                <div class="stat-card-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($teachers->count()) }}</div>
            <div class="stat-card-change positive">
                <i class="fas fa-users me-1"></i>
                Active faculty members
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card success">
            <div class="stat-card-header">
                <div class="stat-card-title">Active Teachers</div>
                <div class="stat-card-icon">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($teachers->where('status', 'active')->count()) }}</div>
            <div class="stat-card-change positive">
                <i class="fas fa-check me-1"></i>
                Currently active
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card warning">
            <div class="stat-card-header">
                <div class="stat-card-title">Inactive Teachers</div>
                <div class="stat-card-icon">
                    <i class="fas fa-user-times"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($teachers->where('status', 'inactive')->count()) }}</div>
            <div class="stat-card-change">
                <i class="fas fa-pause me-1"></i>
                Currently inactive
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card info">
            <div class="stat-card-header">
                <div class="stat-card-title">Recent Additions</div>
                <div class="stat-card-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($teachers->where('created_at', '>=', now()->subDays(30))->count()) }}</div>
            <div class="stat-card-change positive">
                <i class="fas fa-calendar me-1"></i>
                Last 30 days
            </div>
        </div>
    </div>
</div>

<!-- Teachers Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>
                Teachers ({{ $teachers->count() }})
            </h5>
            <a href="{{ route('admin.users.teachers.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>Add Teacher
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($teachers->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" id="teachersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Track</th>
                            <th>Cluster</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        {{ substr($teacher->name, 0, 1) }}
                                    </div>
                                    <strong>{{ $teacher->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $teacher->email }}</td>
                            <td>{{ $teacher->track ?? '-' }}</td>
                            <td>{{ $teacher->cluster ?? '-' }}</td>
                            <td>{{ $teacher->contact_number ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $teacher->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($teacher->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.teachers.edit', $teacher) }}" 
                                       class="btn btn-warning btn-sm" title="Edit Teacher">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.users.teachers.destroy', $teacher) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this teacher?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Teacher">
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
        @else
            <div class="text-center py-5">
                <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No teachers found</h5>
                <p class="text-muted mb-4">There are no teachers in the system yet.</p>
                <a href="{{ route('admin.users.teachers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add First Teacher
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables for Teachers
    $('#teachersTable').DataTable({
        "pageLength": 10,
        "order": [[ 1, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 7 }
        ],
        "language": {
            "search": "Search teachers:",
            "lengthMenu": "Show _MENU_ teachers per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ teachers",
            "emptyTable": "No teachers available"
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.stat-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-left: 4px solid;
    margin-bottom: 1rem;
}

.stat-card.primary { border-left-color: #007bff; }
.stat-card.success { border-left-color: #28a745; }
.stat-card.warning { border-left-color: #ffc107; }
.stat-card.info { border-left-color: #17a2b8; }

.stat-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.stat-card-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
}

.stat-card-icon {
    font-size: 1.5rem;
    color: #6c757d;
}

.stat-card-value {
    font-size: 2rem;
    font-weight: 700;
    color: #495057;
    margin-bottom: 0.5rem;
}

.stat-card-change {
    font-size: 0.875rem;
    color: #6c757d;
}

.stat-card-change.positive {
    color: #28a745;
}

.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 0.875rem;
    font-weight: 600;
}
</style>
@endpush
