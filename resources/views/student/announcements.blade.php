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

    /* Announcement Modal - enhanced UI */
    .announcement-modal .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 12px 40px rgba(2, 6, 23, 0.25);
        overflow: hidden;
    }
    .announcement-modal .modal-header {
        border: none;
        padding: 1rem 1.25rem 0.5rem 1.25rem;
    }
    .announcement-modal .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
    }
    .announcement-modal .modal-body {
        padding: 0.25rem 1.25rem 1rem 1.25rem;
    }
    .announcement-modal .announcement-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }
    .announcement-modal .announcement-meta i {
        color: #2563eb;
    }
    .announcement-modal #modalAnnouncementContent {
        white-space: pre-wrap;
        line-height: 1.7;
        color: #334155;
        font-size: 1rem;
        max-height: 45vh;
        overflow-y: auto;
        padding-right: 0.25rem;
        border-top: 1px solid #e2e8f0;
        padding-top: 0.75rem;
    }
    .announcement-modal .modal-footer {
        border-top: none;
        padding: 0.75rem 1.25rem 1.25rem 1.25rem;
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
<div class="container-fluid">
    <!-- Header (match Class Scheduling style) -->
    <div class="card mb-4" style="border-radius: 14px; box-shadow: 0 8px 25px rgba(30,58,138,0.12); border: none;">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between" style="padding: 1.2rem 1.5rem;">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <span style="font-size: 2rem; color: #2563eb; background: #f1f5fb; border-radius: 12px; padding: 0.7rem; margin-right: 1rem; display: flex; align-items: center;">
                    <i class="fas fa-bullhorn"></i>
                </span>
                <div>
                    <div style="font-size: 1.3rem; font-weight: bold; font-family: 'Poppins', sans-serif; color: #222;">School Announcements</div>
                    <div style="font-size: 0.95rem; font-family: 'Poppins', sans-serif; color: #555; font-weight: 500;">Stay updated with the latest school news and events</div>
                </div>
            </div>
        </div>
    </div>

<!-- Announcements List -->
<div class="card shadow-sm">
    <div class="card-body">
        <!-- Search and Filter Section -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search by title or content..." id="announcementSearch">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="categoryFilter">
                    <option value="">All Categories</option>
                    <option value="academic">Academic</option>
                    <option value="events">Events</option>
                    <option value="general">General</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary" id="filterBtn">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="resetBtn">
                        <i class="fas fa-undo me-1"></i>Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="font-weight: 600; color: #495057;">Announcement Details</th>
                        <th style="font-weight: 600; color: #495057;">Author</th>
                        <th style="font-weight: 600; color: #495057;">Posted</th>
                        <th style="font-weight: 600; color: #495057;">Status</th>
                        <th style="font-weight: 600; color: #495057;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements ?? [] as $announcement)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td>
                            <div>
                                <div style="font-weight: 600; color: #212529; margin-bottom: 4px;">{{ $announcement->title }}</div>
                                <div style="font-size: 0.875rem; color: #6c757d;">{{ Str::limit($announcement->content, 60) }}</div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div style="font-weight: 500; color: #212529;">
                                    @if(isset($announcement->author))
                                        {{ $announcement->author->name ?? 'Unknown' }}
                                    @else
                                        School Administration
                                    @endif
                                </div>
                                <div style="font-size: 0.875rem; color: #6c757d;">
                                    @if(method_exists($announcement, 'isFromTeacher') && $announcement->isFromTeacher())
                                        Teacher
                                    @elseif(method_exists($announcement, 'isFromPrincipal') && $announcement->isFromPrincipal())
                                        Principal
                                    @else
                                        Administrator
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 500; color: #212529;">{{ $announcement->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 0.875rem; color: #6c757d;">{{ $announcement->created_at->diffForHumans() }}</div>
                        </td>
                        <td>
                            <span class="badge bg-success" style="font-size: 0.75rem; padding: 0.375rem 0.75rem;">Active</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-primary btn-sm view-announcement-btn"
                                    data-bs-toggle="modal" data-bs-target="#announcementModal"
                                    data-title="{{ htmlspecialchars($announcement->title, ENT_QUOTES) }}"
                                    data-content="{{ htmlspecialchars($announcement->content, ENT_QUOTES) }}"
                                    data-category="{{ htmlspecialchars($announcement->category ?? '', ENT_QUOTES) }}"
                                    data-author="{{ isset($announcement->author) ? htmlspecialchars($announcement->author->name ?? '', ENT_QUOTES) : '' }}"
                                    data-author-type="{{ method_exists($announcement, 'isFromTeacher') && $announcement->isFromTeacher() ? 'Teacher' : (method_exists($announcement, 'isFromPrincipal') && $announcement->isFromPrincipal() ? 'Principal' : 'School Administration') }}"
                                    data-id="{{ $announcement->id }}"
                                    style="padding: 0.375rem 0.75rem; font-size: 0.875rem;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-success btn-sm" onclick="saveAnnouncement({{ $announcement->id }})" style="padding: 0.375rem 0.75rem; font-size: 0.875rem;">
                                    <i class="fas fa-bookmark"></i>
                                </button>
                                <button class="btn btn-info btn-sm" onclick="shareAnnouncement({{ $announcement->id }})" style="padding: 0.375rem 0.75rem; font-size: 0.875rem;">
                                    <i class="fas fa-share-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4" style="color: #6c757d;">
                            <i class="fas fa-bullhorn fa-2x mb-2" style="opacity: 0.3;"></i>
                            <div>No announcements found</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Announcement Details Modal -->
<div class="modal fade announcement-modal" id="announcementModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAnnouncementTitle">Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="announcement-meta" id="modalAnnouncementAuthor">
                    <!-- author is injected here -->
                </div>
                <div id="modalAnnouncementContent"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="modalSaveBtn">
                    <i class="fas fa-bookmark me-2"></i>Save
                </button>
                <button class="btn btn-primary" id="modalShareBtn">
                    <i class="fas fa-share-alt me-2"></i>Share
                </button>
            </div>
        </div>
    </div>
    
</div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('announcementModal');
        const titleEl = document.getElementById('modalAnnouncementTitle');
        const contentEl = document.getElementById('modalAnnouncementContent');
        const authorEl = document.getElementById('modalAnnouncementAuthor');
        const saveBtn = document.getElementById('modalSaveBtn');
        const shareBtn = document.getElementById('modalShareBtn');

        // Search and filter functionality
        const searchInput = document.getElementById('announcementSearch');
        const categoryFilter = document.getElementById('categoryFilter');
        const filterBtn = document.getElementById('filterBtn');
        const resetBtn = document.getElementById('resetBtn');
        const tableRows = document.querySelectorAll('tbody tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedCategory = categoryFilter.value.toLowerCase();

            tableRows.forEach(row => {
                const title = row.querySelector('td:first-child div:first-child').textContent.toLowerCase();
                const content = row.querySelector('td:first-child div:last-child').textContent.toLowerCase();
                const author = row.querySelector('td:nth-child(2) div:first-child').textContent.toLowerCase();
                
                const matchesSearch = title.includes(searchTerm) || content.includes(searchTerm) || author.includes(searchTerm);
                const matchesCategory = !selectedCategory || selectedCategory === 'all' || 
                    (selectedCategory === 'academic' && (title.includes('academic') || content.includes('academic'))) ||
                    (selectedCategory === 'events' && (title.includes('event') || content.includes('event'))) ||
                    (selectedCategory === 'general' && (!title.includes('academic') && !title.includes('event')));

                if (matchesSearch && matchesCategory) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Event listeners
        searchInput.addEventListener('input', filterTable);
        categoryFilter.addEventListener('change', filterTable);
        filterBtn.addEventListener('click', filterTable);
        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            categoryFilter.value = '';
            tableRows.forEach(row => row.style.display = '');
        });

        // Modal functionality
        document.querySelectorAll('.view-announcement-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const title = btn.getAttribute('data-title');
                const content = btn.getAttribute('data-content');
                const author = btn.getAttribute('data-author');
                const authorType = btn.getAttribute('data-author-type');
                const id = btn.getAttribute('data-id');

                titleEl.textContent = title;
                contentEl.textContent = content;
                let authorText = '';
                if (authorType === 'Teacher') {
                    authorText = `👨‍🏫 Posted by: ${author || 'Teacher'} (Teacher)`;
                } else if (authorType === 'Principal') {
                    authorText = `🏫 Posted by: ${author || 'Principal'} (Principal)`;
                } else {
                    authorText = `📝 Posted by: ${author || 'School Administration'}`;
                }
                authorEl.innerHTML = `<small>${authorText}</small>`;

                saveBtn.onclick = function () { saveAnnouncement(id); };
                shareBtn.onclick = function () { shareAnnouncement(id); };
            });
        });
    });

    // Dummy functions for save/share
    function saveAnnouncement(id) {
        alert('Save announcement ' + id);
    }
    function shareAnnouncement(id) {
        alert('Share announcement ' + id);
    }
</script>
@endsection