@extends('layouts.admin')

@section('title', 'Student Login Credentials')

@section('content')
<div class="page-header">
    <h1 class="page-title">Student Login Credentials</h1>
    <p class="page-subtitle">View and manage all generated student login credentials. Students use their Student ID with password "Temp_123" for first-time login.</p>
    <div class="page-actions">
        <button type="button" class="btn btn-info me-2" onclick="showLoginInstructions()">
            <i class="fas fa-info-circle me-2"></i>Login Instructions
        </button>
        <a href="{{ route('admin.credentials.generate') }}" class="btn btn-primary me-2">
            <i class="fas fa-key me-2"></i>Generate New Credentials
        </a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>

<!-- Login Instructions Modal -->
<div class="modal fade" id="loginInstructionsModal" tabindex="-1" aria-labelledby="loginInstructionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="loginInstructionsModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Student Login Instructions
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-key me-2"></i>Default Login Credentials</h6>
                    <p class="mb-2">All students use the following default credentials for their first login:</p>
                    <ul class="mb-0">
                        <li><strong>Username:</strong> Their assigned Student ID</li>
                        <li><strong>Password:</strong> <code class="bg-light px-2 py-1 rounded">Temp_123</code></li>
                    </ul>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-user-graduate me-2"></i>For New Students:</h6>
                        <ol>
                            <li>Go to the student login page</li>
                            <li>Enter their Student ID as username</li>
                            <li>Enter <code>Temp_123</code> as password</li>
                            <li>Complete their profile setup</li>
                        </ol>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-upload me-2"></i>For CSV Uploaded Students:</h6>
                        <ol>
                            <li>Students uploaded via CSV automatically get <code>Temp_123</code> password</li>
                            <li>They can login immediately with their Student ID</li>
                            <li>Profile information is pre-filled from CSV data</li>
                            <li>Students can update their password after login</li>
                        </ol>
                    </div>
                </div>

                <div class="alert alert-warning mt-3">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Important Notes:</h6>
                    <ul class="mb-0">
                        <li>The password <code>Temp_123</code> is case-sensitive</li>
                        <li>Students should change their password after first login</li>
                        <li>Unused credentials can be deleted from this page</li>
                        <li>Used credentials are automatically marked and cannot be deleted</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.credentials.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Credentials</option>
                            <option value="unused" {{ request('status') === 'unused' ? 'selected' : '' }}>Unused</option>
                            <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>Used</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="source" class="form-label">Source</label>
                        <select name="source" id="source" class="form-select">
                            <option value="">All Sources</option>
                            <option value="manual" {{ request('source') === 'manual' ? 'selected' : '' }}>Manual Generation</option>
                            <option value="csv_upload" {{ request('source') === 'csv_upload' ? 'selected' : '' }}>CSV Upload</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="admin_id" class="form-label">Created By</label>
                        <select name="admin_id" id="admin_id" class="form-select">
                            <option value="">All Creators</option>
                            <!-- Add admin options here if needed -->
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
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
    <!-- Credential summary cards removed as per request -->
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
                                <th>Student ID</th>
                                <th>Password <small class="text-muted">(Default: Temp_123)</small></th>
                                <th>Status</th>
                                <th>Source</th>
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
                                    <code class="text-primary fw-bold">{{ $credential->student_id }}</code>
                                </td>
                                <td>
                                    @if(!$credential->is_used)
                                        <div class="password-field">
                                            @if($credential->password === 'Temp_123')
                                                <code class="text-success fw-bold bg-light px-2 py-1 rounded">{{ $credential->password }}</code>
                                                <span class="badge bg-info ms-2">Default</span>
                                            @else
                                                <code class="text-success">{{ $credential->password }}</code>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                                    onclick="copyToClipboard('{{ $credential->password }}', this)"
                                                    title="Copy password">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    @else
                                        @if($credential->password === 'Temp_123')
                                            <span class="text-muted">Temp_123 (Used)</span>
                                        @else
                                            <span class="text-muted">Hidden (Used)</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($credential->is_used)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Used
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>Unused
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($credential->source === 'manual')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-hand-paper me-1"></i>Manual
                                        </span>
                                    @elseif($credential->source === 'csv_upload')
                                        <span class="badge bg-info">
                                            <i class="fas fa-file-csv me-1"></i>CSV Upload
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Unknown</span>
                                    @endif
                                </td>
                                <td>
                                    @if($credential->createdByAdmin)
                                        <span class="text-primary">{{ $credential->createdByAdmin->name }}</span>
                                        <small class="text-muted d-block">Admin</small>
                                    @elseif($credential->createdByRegistrar)
                                        <span class="text-info">{{ $credential->createdByRegistrar->name ?? $credential->createdByRegistrar->first_name . ' ' . $credential->createdByRegistrar->last_name }}</span>
                                        <small class="text-muted d-block">Registrar</small>
                                    @else
                                        <span class="text-muted">Unknown</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $credential->created_at->format('M j, Y g:i A') }}</small>
                                </td>
                                <td>
                                    @if($credential->usedByStudent)
                                        <span class="text-success">{{ $credential->usedByStudent->full_name }}</span>
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
                                                onclick="return confirm('Are you sure you want to delete this credential?')"
                                                title="Delete unused credential">
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
// Show login instructions modal
function showLoginInstructions() {
    const modal = new bootstrap.Modal(document.getElementById('loginInstructionsModal'));
    modal.show();
}

// Select all functionality
document.getElementById('selectAll')?.addEventListener('change', function() {
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

        // Show tooltip
        button.setAttribute('title', 'Copied!');

        setTimeout(function() {
            icon.className = originalClass;
            button.setAttribute('title', 'Copy password');
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
