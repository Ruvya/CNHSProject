@extends('layouts.student')

@section('title', 'Announcements')

@section('styles')
<style>
    /* Override main-content from layout */
    .main-content {
        padding: 2rem;
        background: #f8fafc;
        min-height: calc(100vh - 80px);
        position: relative;
        overflow-x: hidden;
        margin-left: 250px; /* Account for sidebar */
    }

    /* Background decorative elements */
    .main-content::before {
        margin-top: 3%;
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(circle at 20% 80%, rgba(37, 99, 235, 0.02) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(37, 99, 235, 0.03) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(37, 99, 235, 0.01) 0%, transparent 50%);
        pointer-events: none;
        z-index: 1;
        
    }

    /* Header section */
    .content-header{
        background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-yellow) 100%);
        border-radius: 16px;
        padding: 2rem;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: var(--white);
        
    }
  .content-header::before{
    content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        background: linear-gradient(135deg, var(--primary-blue-light) 0%, var(--primary-blue) 100%);
        clip-path: polygon(100% 0, 100% 100%, 0 100%, 20% 0);
        opacity: 0.9;
        z-index: 1;
  }

    .header-title {
        text-align: left;   
        margin-bottom: 2rem;
        font-size: 30px;
    }



    .subtitle {
        font-size: 1.2rem;
        color: #6b7280;
        font-weight: 500;
        opacity: 0.8;
        margin-top: -4%;
    }

    /* Filter section */
    .filter-options {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .filter-select {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
        min-width: 200px;
    }

    .filter-select:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(37, 99, 235, 0.4);
    }

    .filter-select:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3);
    }

    /* Announcements container */
    .announcements-container {
        position: relative;
        z-index: 2;
    }

    /* Announcement cards */
    .announcement-card {
        background: #ffffff;
        border-radius: 25px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow:
            0 4px 20px rgba(37, 99, 235, 0.08),
            0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(37, 99, 235, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .announcement-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        background: linear-gradient(135deg, var(--primary-blue-light) 0%, var(--primary-blue) 100%);
        clip-path: polygon(100% 0, 100% 100%, 0 100%, 20% 0);
        opacity: 0.9;
        z-index: 1;
    }

    .announcement-card:hover {
        transform: translateY(-8px);
        box-shadow:
            0 8px 30px rgba(37, 99, 235, 0.15),
            0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Priority badges */
    .priority-badge {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .priority-badge.high {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }

    .priority-badge.medium {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }

    /* Card header */
    .card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .card-header i {
        font-size: 1.8rem;
        color: #2563eb;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .card-header h3 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        flex: 1;
        min-width: 200px;
    }

    .card-header .date {
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        color: #6b7280;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        white-space: nowrap;
    }

    /* Card content */
    .card-content {
        margin-bottom: 2rem;
    }

    .card-content p {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #4b5563;
        margin-bottom: 1rem;
    }

    .announcement-author {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .announcement-author small {
        color: #6b7280;
        font-weight: 500;
    }

    /* Card actions */
    .card-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .save-btn,
    .share-btn {
        padding: 0.8rem 1.5rem;
        border: none;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 120px;
        justify-content: center;
    }

    .save-btn {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .save-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }

    .share-btn {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }

    .share-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    }

    /* Empty state */
    .empty-announcements {
        text-align: center;
        padding: 4rem 2rem;
        background: #ffffff;
        border-radius: 25px;
        box-shadow:
            0 4px 20px rgba(37, 99, 235, 0.08),
            0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(37, 99, 235, 0.1);
        position: relative;
        z-index: 2;
    }

    .empty-announcements i {
        font-size: 4rem;
        color: #9ca3af;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .empty-announcements h3 {
        font-size: 2rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 1rem;
    }

    .empty-announcements p {
        font-size: 1.1rem;
        color: #6b7280;
        line-height: 1.6;
        max-width: 600px;
        margin: 0 auto 2rem;
    }

    /* Responsive design */
    @media (max-width: 1024px) {
        .main-content {
            margin-left: 0 !important;
            padding: 1rem !important;
        }
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 1rem !important;
        }

        .announcements-header {
            padding: 2rem;
            border-radius: 20px;
        }

        .header-title h1 {
            font-size: 2.2rem;
        }

        .subtitle {
            font-size: 1rem;
        }

        .filter-select {
            min-width: 100%;
            padding: 0.8rem 1.5rem;
        }

        .announcement-card {
            padding: 2rem;
            margin-bottom: 1.5rem;
            border-radius: 20px;
        }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .card-header h3 {
            font-size: 1.4rem;
            min-width: auto;
        }

        .card-actions {
            flex-direction: column;
        }

        .save-btn,
        .share-btn {
            width: 100%;
        }

        .empty-announcements {
            padding: 3rem 1.5rem;
            border-radius: 20px;
        }

        .empty-announcements h3 {
            font-size: 1.6rem;
        }

        .empty-announcements i {
            font-size: 3rem;
        }
    }

    @media (max-width: 480px) {
        .main-content {
            padding: 0.5rem !important;
        }

        .announcements-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .header-title h1 {
            font-size: 1.8rem;
        }

        .subtitle {
            font-size: 0.9rem;
        }

        .announcement-card {
            padding: 1.5rem;
        }

        .card-header h3 {
            font-size: 1.2rem;
        }

        .card-content p {
            font-size: 0.95rem;
        }

        .save-btn,
        .share-btn {
            padding: 0.7rem 1.2rem;
            font-size: 0.9rem;
        }
    }

</style>
@endsection

@section('content')
<!-- Header Section -->
<div class="content-header">
    <h1>School Announcements</h1>
    <p>Stay updated with the latest school news and events</p>
</div>

<!-- Announcements List -->
<div class="table-container">
    <div class="table-header">
        <h2 class="table-title">All Announcements</h2>
        <select class="filter-dropdown">
            <option value="all">All Categories</option>
            <option value="academic">Academic</option>
            <option value="events">Events</option>
            <option value="general">General</option>
        </select>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Posted</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements ?? [] as $announcement)
                <tr>
                    <td>{{ $announcement->title }}</td>
                    <td>{{ $announcement->category }}</td>
                    <td>{{ $announcement->created_at->diffForHumans() }}</td>
                    <td>
                        <span class="badge badge-active">Active</span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <button class="btn-table-action btn-draft">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No announcements found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Announcement Details Modal -->
<div class="modal fade" id="announcementModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Announcement Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="card-content">
                <p>{{ $announcement->content }}</p>
                @if(isset($announcement->author))
                    <div class="announcement-author">
                        <small>
                            @if($announcement->isFromTeacher())
                                👨‍🏫 Posted by: {{ $announcement->author->name ?? 'Teacher' }} (Teacher)
                            @elseif($announcement->isFromPrincipal())
                                🏫 Posted by: {{ $announcement->author->name ?? 'Principal' }} (Principal)
                            @else
                                📝 Posted by: {{ $announcement->author->name ?? 'School Administration' }}
                            @endif
                        </small>
                    </div>
                @endif
            </div>
            <div class="card-actions">
                <button class="save-btn" onclick="saveAnnouncement({{ $announcement->id }})">
                    <i class="fas fa-bookmark"></i> Save
                </button>
                <button class="share-btn" onclick="shareAnnouncement({{ $announcement->id }})">
                    <i class="fas fa-share-alt"></i> Share
                </button>

            <div class="modal-body">
                <!-- Announcement content will be loaded here -->

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add any JavaScript for handling announcements here
</script>
@endsection