@extends('Principal.layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="announcements-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <div>
                        <h1 class="page-title mb-1">
                            <i class="fas fa-bullhorn me-2"></i>
                            School Announcements
                        </h1>
                        <p class="page-subtitle mb-0">
                            Manage and publish important school communications
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="header-button-group">
                    <a href="{{ route('principal.announcements.create') }}" class="header-btn btn-create btn-sm shadow">
                        <i class="fas fa-plus-circle me-2"></i><span class="d-none d-md-inline">Create</span>
                    </a>
                    <button type="button" class="header-btn btn-refresh btn-sm shadow" onclick="forceClearCache()" title="Force refresh data">
                        <i class="fas fa-sync-alt me-2"></i><span class="d-none d-md-inline">Refresh</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements Management -->
    <div class="announcements-card">
        <div class="announcements-card-header">
            <h5 class="mb-0">
                <i class="fas fa-list-alt me-2"></i>
                Announcement Management
            </h5>
            <div class="announcement-stats">
                <span class="stat-badge stat-total">
                    <i class="fas fa-file-alt me-1"></i>
                    {{ count($announcements) }} Total
                </span>
                <span class="stat-badge stat-active">
                    <i class="fas fa-eye me-1"></i>
                    {{ collect($announcements)->where('status', 'active')->count() }} Active
                </span>
                <span class="stat-badge stat-draft">
                    <i class="fas fa-edit me-1"></i>
                    {{ collect($announcements)->where('status', 'draft')->count() }} Draft
                </span>
            </div>

            <!-- Filter Controls -->
            <div class="filter-controls mt-3">
                <div class="d-flex gap-2 align-items-center">
                    <label for="statusFilter" class="form-label mb-0 text-white">
                        <i class="fas fa-filter me-1"></i>Filter by Status:
                    </label>
                    <select id="statusFilter" class="form-select form-select-sm" style="width: auto;" onchange="filterAnnouncements()">
                        <option value="all">All Announcements</option>
                        <option value="active">Active Only</option>
                        <option value="draft">Draft Only</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="announcements-card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="announcements-table">
                    <thead>
                        <tr>
                            <th class="announcement-title">
                                <i class="fas fa-heading me-2"></i>TITLE
                            </th>
                            <th class="announcement-status">
                                <i class="fas fa-toggle-on me-2"></i>STATUS
                            </th>
                            <th class="announcement-date">
                                <i class="fas fa-calendar me-2"></i>DATE
                            </th>
                            <th class="announcement-actions">
                                <i class="fas fa-cogs me-2"></i>ACTIONS
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $announcement)
                            <tr id="announcement-row-{{ $announcement->id }}">
                                <td class="announcement-title">
                                    <div class="title-text">{{ $announcement->title }}</div>
                                </td>
                                <td class="announcement-status">
                                    <span class="badge bg-{{ $announcement->status === 'active' ? 'success' : 'warning' }}" id="status-badge-{{ $announcement->id }}">
                                        {{ ucfirst($announcement->status) }}
                                    </span>
                                </td>
                                <td class="announcement-date">
                                    <i class="fas fa-clock me-1 text-muted"></i>
                                    {{ $announcement->created_at->diffForHumans() }}
                                </td>
                                <td class="announcement-actions">
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-{{ $announcement->status === 'active' ? 'warning' : 'success' }} toggle-status-btn"
                                                data-id="{{ $announcement->id }}"
                                                data-current-status="{{ $announcement->status }}"
                                                title="{{ $announcement->status === 'active' ? 'Move to Draft' : 'Publish' }}">
                                            <i class="fas fa-{{ $announcement->status === 'active' ? 'eye-slash' : 'eye' }}"></i>
                                            <span class="button-text">{{ $announcement->status === 'active' ? 'Draft' : 'Publish' }}</span>
                                        </button>
                                        <a href="{{ route('principal.announcements.edit', $announcement->id) }}" 
                                           class="btn btn-sm btn-info"
                                           title="Edit Announcement">
                                            <i class="fas fa-edit"></i>
                                            <span class="button-text">Edit</span>
                                        </a>
                                        <form action="{{ route('principal.announcements.destroy', $announcement->id) }}" 
                                              method="POST" 
                                              class="d-inline delete-announcement-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger delete-btn" 
                                                    data-id="{{ $announcement->id }}"
                                                    title="Delete Announcement">
                                                <i class="fas fa-trash"></i>
                                                <span class="button-text">Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-bullhorn fa-2x mb-3 text-muted"></i>
                                        <p class="mb-1 text-muted">No announcements found</p>
                                        <small class="text-muted">Create your first announcement to get started</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* CSS Variables */
    :root {
        --primary-orange: #ff6b35;
        --primary-orange-light: #ff8c5a;
        --primary-yellow: #ffd23f;
        --primary-blue: #007bff;
        --primary-blue-light: #4dabf7;
        --success-color: #28a745;
        --info-color: #17a2b8;
        --white: #ffffff;
        --gray-50: #f8f9fa;
        --gray-200: #e9ecef;
        --gray-400: #6c757d;
        --text-dark: #212529;
    }

    /* Header Button Group */
    .header-button-group {
        display: flex;
        gap: 7%;
        justify-content: flex-end;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
        border-radius: 12px;
        padding: 3%;
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        z-index: 2;
        width: 58%;
        margin-left: 30%;
    }

    .header-btn.btn-create {
        background: linear-gradient(135deg, var(--success-color) 0%, #2ed573 100%);
    }

    .header-btn.btn-create:hover {
        background: linear-gradient(135deg, #2ed573 0%, var(--success-color) 100%);
        box-shadow: 0 6px 12px rgba(46, 213, 115, 0.3);
    }
    .header-btn.btn-refresh {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-light) 100%);
    }

    .header-btn.btn-refresh:hover {
        background: linear-gradient(135deg, var(--primary-blue-light) 0%, var(--primary-blue) 100%);
        box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3);
    }

    .header-btn.btn-sm {
        font-size: 0.95rem;
        padding: 0.5rem 1.1rem;
        border-radius: 8px;
        min-width: 90px;
        font-weight: 600;
        letter-spacing: 0.01em;
    }
    .header-btn.shadow {
        box-shadow: 0 6px 18px rgba(0,0,0,0.10) !important;
    }


    /* Announcements Page Header */
    .announcements-header {
        background: #1E3A8A;
        border-radius: 16px;
        padding: 2rem;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: var(--white);
    }

    .announcements-header::after {
        display: none;
    }

    .page-title {
        color: var(--white);
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .page-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
        margin-bottom: 0;
        font-weight: 400;
        position: relative;
        z-index: 2;
    }

    .school-logo {
        position: relative;
        z-index: 2;
    }

    .logo-img {
        width: 60px;
        height: 60px;
        object-fit: contain;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    }

    /* Announcements Card */
    .announcements-card {
        background: var(--white);
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: none;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .announcements-card-header {
        background: #1E3A8A;
        color: var(--white);
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .announcements-card-body {
        padding: 1.5rem;
    }

    /* Stats Badges */
    .announcement-stats {
        display: flex;
        gap: 1rem;
    }

    .stat-badge {
        background: rgba(255, 255, 255, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        backdrop-filter: blur(4px);
    }

    /* Table Styling */
    .announcements-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .announcements-table th {
        background: var(--gray-50);
        color: var(--text-dark);
        font-weight: 600;
        padding: 1rem;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--gray-200);
    }

    .announcements-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--gray-200);
        vertical-align: middle;
    }

    .announcements-table tr:hover {
        background-color: var(--gray-50);
    }

    /* Column Widths */
    .announcement-title {
        width: 40%;
    }

    .announcement-status {
        width: 15%;
    }

    .announcement-date {
        width: 20%;
    }

    .announcement-actions {
        width: 25%;
    }

    /* Button Styling */
    .btn-create {
        background: linear-gradient(135deg, var(--success-color) 0%, #2ed573 100%);
        color: var(--white);
        border: none;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        background: linear-gradient(135deg, #2ed573 0%, var(--success-color) 100%);
        color: var(--white);
    }

    .btn-outline-warning {
        background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-light) 100%);
        color: var(--white);
        border: none;
        padding: 0.75rem 1.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }

    .btn-outline-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(255, 107, 53, 0.3);
        background: linear-gradient(135deg, var(--primary-orange-light) 0%, var(--primary-orange) 100%);
        color: var(--white);
    }

    .btn-outline-primary {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-light) 100%);
        color: var(--white);
        border: none;
        padding: 0.75rem 1.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }

    .btn-outline-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3);
        background: linear-gradient(135deg, var(--primary-blue-light) 0%, var(--primary-blue) 100%);
        color: var(--white);
    }

    /* Button Group Container */
    .d-flex.gap-2 {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 0.75rem;
        margin: 0;
        display: inline-flex !important;
        gap: 0.75rem !important;
    }

    .btn-outline-info {
        color: var(--info-color);
        border: 2px solid var(--info-color);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        font-size: 0.875rem;
    }

    .btn-outline-info:hover {
        background: linear-gradient(135deg, var(--info-color) 0%, #2563eb 100%);
        border-color: transparent;
        color: var(--white);
        transform: translateY(-1px);
    }

    /* Filter Controls */
    .filter-controls {
      
        border-radius: 8px;
        padding: 1rem;
        backdrop-filter: blur(10px);
    }

    .filter-controls .form-label {
        font-weight: 500;
        color: var(--white);
    }

    .filter-controls .form-select {
        background-color: rgba(255, 255, 255, 0.9);
     
        color: var(--text-dark);
        font-size: 0.875rem;
        min-width: 100px;
    }

    .filter-controls .form-select:focus {
        background-color: var(--white);
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .filter-controls .btn-outline-light {
        border-color: rgba(255, 255, 255, 0.5);
        color: var(--white);
        font-size: 0.875rem;
    }

    .filter-controls .btn-outline-light:hover {
     
        border-color: var(--white);
        color: var(--white);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .action-buttons .btn {
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .action-buttons .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .action-buttons .btn-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #16a34a 100%);
    }

    .action-buttons .btn-warning {
        background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-light) 100%);
        color: var(--white);
    }

    .action-buttons .btn-info {
        background: linear-gradient(135deg, var(--info-color) 0%, #2563eb 100%);
        color: var(--white);
    }

    .action-buttons .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    /* Badge Styling */
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge.bg-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #16a34a 100%) !important;
    }

    .badge.bg-warning {
        background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-light) 100%) !important;
        color: var(--white);
    }

    /* Empty State */
    .empty-state {
        padding: 3rem 1.5rem;
        text-align: center;
    }

    .empty-state i {
        color: var(--gray-400);
        margin-bottom: 1rem;
    }

    /* Alert Styling */
    .alert {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .alert-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #16a34a 100%);
        color: var(--white);
    }

    .alert-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: var(--white);
    }

    /* School Logo */
    .school-logo {
        width: 60px;
        height: 60px;
        background: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 8px;
        margin-right: 1rem;
    }

    .logo-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .header-btn.btn-create.btn-sm {
        background: #22c55e;
        color: #fff;
        border: none;
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.15);
        border-radius: 10px;
        transition: background 0.15s, box-shadow 0.15s, transform 0.15s;
    }
    .header-btn.btn-create.btn-sm:hover {
        background: #16a34a;
        color: #fff;
        box-shadow: 0 4px 16px rgba(34, 197, 94, 0.25);
        transform: translateY(-2px) scale(1.03);
    }
    .header-btn.btn-refresh.btn-sm {
        background: #2563eb;
        color: #fff;
        border: none;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.15);
        border-radius: 10px;
        transition: background 0.15s, box-shadow 0.15s, transform 0.15s;
    }
    .header-btn.btn-refresh.btn-sm:hover {
        background: #1d4ed8;
        color: #fff;
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.25);
        transform: translateY(-2px) scale(1.03);
    }
</style>
@endsection

@section('scripts')
<script>
    // Function to show alerts
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Remove any existing alerts
        document.querySelectorAll('.alert').forEach(alert => alert.remove());
        
        // Insert the new alert at the beginning of the announcements-card-body
        const cardBody = document.querySelector('.announcements-card-body');
        cardBody.insertBefore(alertDiv, cardBody.firstChild);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => alertDiv.remove(), 5000);
    }

    // Handle status toggle
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.toggle-status-btn');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const announcementId = this.dataset.id;
                const currentStatus = this.dataset.currentStatus;
                const toggleButton = this;
                
                // Disable button and show loading state
                toggleButton.disabled = true;
                const originalContent = toggleButton.innerHTML;
                toggleButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                fetch(`{{ url('principal/announcements') }}/${announcementId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update status badge
                        const badge = document.getElementById(`status-badge-${announcementId}`);
                        badge.className = `badge bg-${data.badge_class}`;
                        badge.textContent = data.badge_text;
                        
                        // Update toggle button
                        toggleButton.className = `btn btn-sm btn-${data.status === 'active' ? 'warning' : 'success'} me-2 toggle-status-btn`;
                        toggleButton.dataset.currentStatus = data.status;
                        toggleButton.innerHTML = `
                            <i class="fas fa-${data.status === 'active' ? 'eye-slash' : 'eye'}"></i>
                            ${data.status === 'active' ? 'Draft' : 'Publish'}
                        `;
                        
                        showAlert('success', data.message);
                    } else {
                        toggleButton.innerHTML = originalContent;
                        showAlert('danger', data.message || 'Error updating status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toggleButton.innerHTML = originalContent;
                    showAlert('danger', 'An error occurred while updating the status');
                })
                .finally(() => {
                    toggleButton.disabled = false;
                });
            });
        });

        // Enhanced delete functionality with better error handling
        document.querySelectorAll('.delete-announcement-form').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (!confirm('Are you sure you want to delete this announcement? This action cannot be undone.')) {
                    return;
                }

                const form = this;
                const button = form.querySelector('.delete-btn');
                const announcementId = button.dataset.id;
                const row = document.getElementById('announcement-row-' + announcementId);

                // Disable the button and show loading state
                button.disabled = true;
                const originalContent = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                try {
                    // Single deletion request
                    const response = await fetch(form.action, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to delete announcement');
                    }

                    // Successfully deleted - animate row removal
                    if (row) {
                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(-20px)';

                        // Remove the row after animation
                        setTimeout(() => {
                            row.remove();

                            // Check if there are no more announcements
                            const remainingRows = document.querySelectorAll('tbody tr:not(.no-announcements)');
                            if (remainingRows.length === 0) {
                                const tbody = document.querySelector('tbody');
                                const noAnnouncementsRow = document.createElement('tr');
                                noAnnouncementsRow.innerHTML = `
                                    <td colspan="4" class="text-center">No announcements found.</td>
                                `;
                                tbody.appendChild(noAnnouncementsRow);
                            }
                        }, 300);

                        showAlert('success', data.message || 'Announcement has been successfully deleted.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('danger', error.message || 'Failed to delete announcement. Please try again.');
                    button.disabled = false;
                    button.innerHTML = originalContent;
                }
            });
        });

        // Force clear cache function with enhanced feedback
        window.forceClearCache = function() {
            console.log('🔄 Force Clear Cache button clicked');
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;

            // Show loading state
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Refreshing...';

            fetch('{{ route("principal.announcements.force-clear-cache") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 'Cache cleared successfully! Refreshing page...');
                    
                    // Force reload from server
                    setTimeout(() => {
                        window.location.href = window.location.href + '?t=' + Date.now();
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Failed to clear cache');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Failed to clear cache. Please try again.');
                button.disabled = false;
                button.innerHTML = originalContent;
            });
        };

        // Check for cached deletions on page load
        const lastDeletionTime = localStorage.getItem('last_deletion_time');
        if (lastDeletionTime) {
            const timeSinceLastDeletion = Date.now() - parseInt(lastDeletionTime);
            if (timeSinceLastDeletion < 5000) { // Within last 5 seconds
                forceClearCache();
            }
        }

        // Check if an announcement was just created
    });

    @if(session('announcement_created'))
    <script>
        showAlert('success', 'Announcement created successfully! It will now appear in your dashboard.');
    </script>
    @endif

    // Clean up soft-deleted announcements function
    function cleanupSoftDeleted() {
        console.log('🧹 Cleanup Soft Deleted button clicked');
        if (!confirm('This will permanently delete all soft-deleted announcements. Are you sure?')) {
            return;
        }

        const button = event.target.closest('button');
        const originalContent = button.innerHTML;

        // Show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Cleaning...';

        fetch('{{ route("principal.announcements.cleanup-soft-deleted") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);

                // Refresh the page after cleanup
                setTimeout(() => {
                    window.location.reload(true);
                }, 1500);
            } else {
                showAlert('danger', 'Error during cleanup: ' + data.message);
                button.disabled = false;
                button.innerHTML = originalContent;
            }
        })
        .catch(error => {
            console.error('Error during cleanup:', error);
            showAlert('danger', 'Error during cleanup. Please try again.');
            button.disabled = false;
            button.innerHTML = originalContent;
        });
    }

    // Debug deletion functionality
    function debugDeletion() {
        const announcements = document.querySelectorAll('.delete-announcement-form');
        const deleteButtons = document.querySelectorAll('.delete-btn');

        let debugInfo = `🔍 Deletion Debug Information:\n\n`;
        debugInfo += `📊 Page Statistics:\n`;
        debugInfo += `   - Total announcement forms: ${announcements.length}\n`;
        debugInfo += `   - Total delete buttons: ${deleteButtons.length}\n`;
        debugInfo += `   - CSRF token present: ${document.querySelector('meta[name="csrf-token"]') ? 'YES' : 'NO'}\n\n`;

        debugInfo += `🛣️ Route Information:\n`;
        if (announcements.length > 0) {
            const firstForm = announcements[0];
            debugInfo += `   - Form action: ${firstForm.action}\n`;
            debugInfo += `   - Form method: ${firstForm.method}\n`;
            debugInfo += `   - Has @method('DELETE'): ${firstForm.querySelector('input[name="_method"][value="DELETE"]') ? 'YES' : 'NO'}\n`;
        }

        debugInfo += `\n🔧 JavaScript Events:\n`;
        debugInfo += `   - Event listeners attached: ${announcements.length > 0 ? 'YES' : 'NO'}\n`;

        debugInfo += `\n📝 Recent Console Errors:\n`;
        debugInfo += `   Check browser console (F12) for any JavaScript errors\n`;

        debugInfo += `\n💡 Troubleshooting Tips:\n`;
        debugInfo += `   1. Check browser console for errors\n`;
        debugInfo += `   2. Verify CSRF token is present\n`;
        debugInfo += `   3. Ensure you're logged in as principal\n`;
        debugInfo += `   4. Try the Force Refresh button\n`;
        debugInfo += `   5. Check server logs for backend errors\n`;

        alert(debugInfo);

        // Also log to console for detailed inspection
        console.log('🔍 Deletion Debug Information:', {
            announcementForms: announcements,
            deleteButtons: deleteButtons,
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.content,
            firstFormAction: announcements[0]?.action,
            eventListeners: 'Check Elements tab for event listeners'
        });
    }

    // Test JavaScript function
    function testJavaScript() {
        console.log('🧪 JavaScript Test Function Called');

        // Test basic functionality
        const tests = {
            'jQuery Available': typeof $ !== 'undefined',
            'CSRF Token Present': document.querySelector('meta[name="csrf-token"]') !== null,
            'Force Clear Cache Function': typeof forceClearCache === 'function',
            'Cleanup Function': typeof cleanupSoftDeleted === 'function',
            'Create Route': '{{ route("principal.announcements.create") }}',
            'Force Clear Route': '{{ route("principal.announcements.force-clear-cache") }}',
            'Cleanup Route': '{{ route("principal.announcements.cleanup-soft-deleted") }}'
        };

        let message = '🧪 JavaScript Test Results:\n\n';
        for (const [test, result] of Object.entries(tests)) {
            const status = result ? '✅' : '❌';
            message += `${status} ${test}: ${result}\n`;
        }

        message += '\n📝 Check browser console for detailed logs';

        alert(message);
        console.log('🧪 Test Results:', tests);

        // Test a simple alert
        showAlert('info', 'JavaScript is working! Check console for details.');

        // Test the backend endpoint
        fetch('{{ route("principal.announcements.test-endpoint") }}')
        .then(response => response.json())
        .then(data => {
            console.log('🔗 Backend Test Result:', data);
            if (data.success) {
                showAlert('success', 'Backend connection working: ' + data.message);
            }
        })
        .catch(error => {
            console.error('🔗 Backend Test Failed:', error);
            showAlert('danger', 'Backend connection failed: ' + error.message);
        });
    }

    // Filter announcements by status
    function filterAnnouncements() {
        const filterValue = document.getElementById('statusFilter').value;
        const allRows = document.querySelectorAll('tr[id^="announcement-row-"]');

        console.log('🔍 Filtering announcements by:', filterValue);

        allRows.forEach(row => {
            const statusBadge = row.querySelector('.badge');
            if (!statusBadge) return;

            const status = statusBadge.textContent.toLowerCase().trim();

            if (filterValue === 'all') {
                row.style.display = '';
            } else if (filterValue === 'active' && status === 'active') {
                row.style.display = '';
            } else if (filterValue === 'draft' && status === 'draft') {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Update visible count
        updateVisibleCount();
    }

    // Reset filter
    function resetFilter() {
        document.getElementById('statusFilter').value = 'all';
        filterAnnouncements();
        showAlert('info', 'Filter reset - showing all announcements');
    }

    // Update visible count after filtering
    function updateVisibleCount() {
        const allRows = document.querySelectorAll('tr[id^="announcement-row-"]');
        const visibleRows = Array.from(allRows).filter(row => row.style.display !== 'none');

        console.log(`📊 Showing ${visibleRows.length} of ${allRows.length} announcements`);
    }
</script>
@endsection