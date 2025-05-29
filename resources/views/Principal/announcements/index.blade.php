@extends('Principal.layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h1 class="h3 text-primary mb-0">Announcements</h1>
                    <a href="{{ route('principal.announcements.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create New
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
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
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($announcements as $announcement)
                                    <tr id="announcement-row-{{ $announcement->id }}">
                                        <td>{{ $announcement->title }}</td>
                                        <td>
                                            <span class="badge bg-{{ $announcement->status === 'active' ? 'success' : 'warning' }}">
                                                {{ ucfirst($announcement->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $announcement->created_at->diffForHumans() }}</td>
                                        <td>
                                            <a href="{{ route('principal.announcements.edit', $announcement->id) }}" class="btn btn-sm btn-info me-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('principal.announcements.destroy', $announcement->id) }}" method="POST" class="d-inline delete-announcement-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger delete-btn" data-id="{{ $announcement->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No announcements found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add event listener to all delete buttons
    document.querySelectorAll('.delete-announcement-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete this announcement?')) {
                const form = this;
                const button = form.querySelector('.delete-btn');
                const announcementId = button.dataset.id;
                const row = document.getElementById('announcement-row-' + announcementId);
                
                // Immediately hide the row with animation
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
                            noAnnouncementsRow.className = 'no-announcements';
                            noAnnouncementsRow.innerHTML = `
                                <td colspan="4" class="text-center">No announcements found.</td>
                            `;
                            tbody.appendChild(noAnnouncementsRow);
                        }
                    }, 300);
                }

                // Submit the form to server
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: new FormData(form)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    
                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show';
                    alertDiv.style.opacity = '0';
                    alertDiv.innerHTML = `
                        Announcement deleted successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    
                    const cardBody = document.querySelector('.card-body');
                    cardBody.insertBefore(alertDiv, cardBody.firstChild);
                    
                    // Animate the alert
                    setTimeout(() => {
                        alertDiv.style.transition = 'opacity 0.3s ease';
                        alertDiv.style.opacity = '1';
                    }, 10);
                    
                    // Auto-dismiss the alert after 3 seconds
                    setTimeout(() => {
                        alertDiv.style.opacity = '0';
                        setTimeout(() => alertDiv.remove(), 300);
                    }, 3000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // If there was an error, revert the deletion
                    if (!document.getElementById('announcement-row-' + announcementId)) {
                        const tbody = document.querySelector('tbody');
                        tbody.insertBefore(row, tbody.firstChild);
                        row.style.opacity = '1';
                        row.style.transform = 'translateX(0)';
                    }
                    
                    // Show error message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                    alertDiv.innerHTML = `
                        Error deleting announcement. The announcement has been restored.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    
                    const cardBody = document.querySelector('.card-body');
                    cardBody.insertBefore(alertDiv, cardBody.firstChild);
                });
            }
        });
    });
});
</script>
@endsection 