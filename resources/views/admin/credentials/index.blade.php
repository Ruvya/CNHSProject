@extends('layouts.admin')

@section('title', 'Manage Student Credentials')

@section('content')
<div class="page-header">
    <h1 class="page-title">Manage Student Credentials</h1>
    <p class="page-subtitle">View and manage all generated student login credentials</p>
    <div class="page-actions">
        <a href="{{ route('admin.credentials.generate') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Generate New Credentials
        </a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.credentials.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Credentials</option>
                            <option value="unused" {{ request('status') === 'unused' ? 'selected' : '' }}>Unused</option>
                            <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>Used</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="admin_id" class="form-label">Created By</label>
                        <select name="admin_id" id="admin_id" class="form-select">
                            <option value="">All Admins</option>
                            <!-- Add admin options here if needed -->
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        <a href="{{ route('admin.credentials.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card stat-card-primary">
            <div class="stat-card-body">
                <div class="stat-card-icon">
                    <i class="fas fa-key"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-title">Total Generated</div>
                    <div class="stat-card-value">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-card-success">
            <div class="stat-card-body">
                <div class="stat-card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-title">Used</div>
                    <div class="stat-card-value">{{ $stats['used'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-card-warning">
            <div class="stat-card-body">
                <div class="stat-card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-title">Unused</div>
                    <div class="stat-card-value">{{ $stats['unused'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-card-info">
            <div class="stat-card-body">
                <div class="stat-card-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-title">Today</div>
                    <div class="stat-card-value">{{ $stats['today'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Credentials Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Generated Credentials
                </h5>
                @if($stats['unused'] > 0)
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="bulkDelete()">
                    <i class="fas fa-trash me-2"></i>Delete Unused
                </button>
                @endif
            </div>
            <div class="card-body p-0">
                @if($credentials->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>Student ID</th>
                                <th>Password</th>
                                <th>Status</th>
                                <th>Notes</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th>Used By</th>
                                <th>Used At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($credentials as $credential)
                            <tr>
                                <td>
                                    @if(!$credential->is_used)
                                    <input type="checkbox" name="credential_ids[]" value="{{ $credential->id }}" class="form-check-input credential-checkbox">
                                    @endif
                                </td>
                                <td>
                                    <code class="text-primary">{{ $credential->student_id }}</code>
                                </td>
                                <td>
                                    @if(!$credential->is_used)
                                        <div class="password-field">
                                            <code class="text-success">{{ $credential->password }}</code>
                                            <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                                    onclick="copyToClipboard('{{ $credential->password }}', this)">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-muted">Hidden (Used)</span>
                                    @endif
                                </td>
                                <td>
                                    @if($credential->is_used)
                                        <span class="badge bg-success">Used</span>
                                    @else
                                        <span class="badge bg-warning">Unused</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted">{{ $credential->notes ?: 'No notes' }}</span>
                                </td>
                                <td>
                                    {{ $credential->createdByAdmin->name ?? 'Unknown' }}
                                </td>
                                <td>
                                    <small class="text-muted">{{ $credential->created_at->format('M j, Y g:i A') }}</small>
                                </td>
                                <td>
                                    @if($credential->usedByStudent)
                                        {{ $credential->usedByStudent->full_name }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($credential->used_at)
                                        <small class="text-muted">{{ $credential->used_at->format('M j, Y g:i A') }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$credential->is_used)
                                    <form action="{{ route('admin.credentials.destroy', $credential) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this credential?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer">
                    {{ $credentials->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-key fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No credentials generated yet</h5>
                    <p class="text-muted">Start by generating some student login credentials.</p>
                    <a href="{{ route('admin.credentials.generate') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Generate Credentials
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Form -->
<form id="bulkDeleteForm" action="{{ route('admin.credentials.bulk-delete') }}" method="POST" style="display: none;">
    @csrf
    <div id="bulkDeleteInputs"></div>
</form>
@endsection

@section('scripts')
<script>
// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.credential-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Bulk delete functionality
function bulkDelete() {
    const selectedCheckboxes = document.querySelectorAll('.credential-checkbox:checked');

    if (selectedCheckboxes.length === 0) {
        alert('Please select credentials to delete.');
        return;
    }

    if (!confirm(`Are you sure you want to delete ${selectedCheckboxes.length} unused credential(s)?`)) {
        return;
    }

    const form = document.getElementById('bulkDeleteForm');
    const inputsContainer = document.getElementById('bulkDeleteInputs');
    inputsContainer.innerHTML = '';

    selectedCheckboxes.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'credential_ids[]';
        input.value = checkbox.value;
        inputsContainer.appendChild(input);
    });

    form.submit();
}

// Copy to clipboard functionality
function copyToClipboard(text, button) {
    navigator.clipboard.writeText(text).then(function() {
        // Change button icon temporarily
        const icon = button.querySelector('i');
        const originalClass = icon.className;
        icon.className = 'fas fa-check text-success';

        setTimeout(function() {
            icon.className = originalClass;
        }, 2000);
    }).catch(function(err) {
        console.error('Failed to copy: ', err);
        alert('Failed to copy to clipboard');
    });
}
</script>
@endsection

@section('styles')
<style>
.password-field {
    display: flex;
    align-items: center;
    gap: 8px;
}

.password-field code {
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
    padding: 4px 8px;
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    color: #198754;
    font-weight: 600;
}

.password-field .btn {
    padding: 2px 6px;
    font-size: 0.75em;
}

.table td {
    vertical-align: middle;
}

.credential-value {
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
    font-weight: 600;
}
</style>
@endsection
