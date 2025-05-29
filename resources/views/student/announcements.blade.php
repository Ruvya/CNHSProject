@extends('layouts.student')

@section('title', 'Announcements')

@section('styles')
<style>
    /* Override main-content from layout */
    .main-content {
        padding: 2rem !important;
        background: #ffffff !important;
        min-height: calc(100vh - 80px) !important;
        position: relative;
        overflow-x: hidden;
        margin-left: 250px !important; /* Account for sidebar */
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
    .announcements-header {
        background: #ffffff;
        border-radius: 25px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow:
            0 4px 20px rgba(37, 99, 235, 0.08),
            0 1px 3px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 2;
        border: 1px solid rgba(37, 99, 235, 0.1);
    }

    .header-title {
        text-align: center;
        margin-bottom: 2rem;
    }

    .header-title h1 {
        font-size: 2.8rem;
        font-weight: 800;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .subtitle {
        font-size: 1.2rem;
        color: #6b7280;
        font-weight: 500;
        opacity: 0.8;
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
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border-radius: 25px 25px 0 0;
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
<div class="announcements-header">
    <div class="header-title">
        <h1>📢 Class Announcements</h1>
        <p class="subtitle">Stay updated with the latest news and important updates from your school</p>
    </div>
    <div class="filter-options">
        <select id="announcementType" class="filter-select">
            <option value="all">🔍 All Announcements</option>
            <option value="important">🚨 Important</option>
            <option value="class">📚 Class</option>
            <option value="school">🏫 School</option>
        </select>
    </div>
</div>

@if($announcements && $announcements->count() > 0)
    <div class="announcements-container">
        @foreach($announcements as $announcement)
        <div class="announcement-card {{ $announcement->type ?? 'general' }}">
            @if(isset($announcement->priority) && $announcement->priority === 'high')
                <div class="priority-badge high">🚨 Urgent</div>
            @elseif(isset($announcement->priority) && $announcement->priority === 'medium')
                <div class="priority-badge medium">⚠️ Important</div>
            @endif

            <div class="card-header">
                <i class="fas {{ $announcement->icon ?? 'fa-bullhorn' }}"></i>
                <h3>{{ $announcement->title }}</h3>
                <span class="date">📅 {{ $announcement->created_at->format('F d, Y') }}</span>
            </div>
            <div class="card-content">
                <p>{{ $announcement->content }}</p>
                @if(isset($announcement->author))
                    <div class="announcement-author">
                        <small>📝 Posted by: {{ $announcement->author->name ?? 'School Administration' }}</small>
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
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="empty-announcements">
        <i class="fas fa-bullhorn"></i>
        <h3>📭 No Announcements Yet</h3>
        <p>There are currently no announcements to display. Check back later for updates from your teachers and school administration. You'll be notified when new announcements are posted!</p>
        <div style="margin-top: 2rem;">
            <button onclick="window.location.reload()" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; border: none; padding: 0.8rem 2rem; border-radius: 50px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);">
                🔄 Refresh Page
            </button>
        </div>
    </div>
@endif

<script>
    // Filter announcements based on type
    document.getElementById('announcementType').addEventListener('change', function() {
        const type = this.value;
        const cards = document.querySelectorAll('.announcement-card');
        const container = document.querySelector('.announcements-container');
        let visibleCount = 0;

        cards.forEach(card => {
            if (type === 'all' || card.classList.contains(type)) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show/hide empty state based on visible cards
        const emptyState = document.querySelector('.empty-announcements');
        if (visibleCount === 0 && cards.length > 0) {
            if (!emptyState) {
                const emptyDiv = document.createElement('div');
                emptyDiv.className = 'empty-announcements';
                emptyDiv.innerHTML = `
                    <i class="fas fa-search"></i>
                    <h3>No Announcements Found</h3>
                    <p>No announcements match the selected filter. Try selecting a different category.</p>
                `;
                container.parentNode.insertBefore(emptyDiv, container.nextSibling);
            }
            if (container) container.style.display = 'none';
        } else {
            if (emptyState && emptyState.querySelector('.fas.fa-search')) {
                emptyState.remove();
            }
            if (container) container.style.display = 'grid';
        }
    });

    // Save announcement function
    function saveAnnouncement(announcementId) {
        // Add your save logic here
        console.log('Saving announcement:', announcementId);

        // Show feedback to user
        const button = event.target.closest('.save-btn');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i> Saved';
        button.style.backgroundColor = '#28a745';
        button.style.color = 'white';

        setTimeout(() => {
            button.innerHTML = originalText;
            button.style.backgroundColor = '';
            button.style.color = '';
        }, 2000);
    }

    // Share announcement function
    function shareAnnouncement(announcementId) {
        // Add your share logic here
        console.log('Sharing announcement:', announcementId);

        // Simple share functionality
        if (navigator.share) {
            const card = event.target.closest('.announcement-card');
            const title = card.querySelector('h3').textContent;
            const content = card.querySelector('.card-content p').textContent;

            navigator.share({
                title: title,
                text: content,
                url: window.location.href
            });
        } else {
            // Fallback: copy to clipboard
            const card = event.target.closest('.announcement-card');
            const title = card.querySelector('h3').textContent;
            const content = card.querySelector('.card-content p').textContent;
            const textToCopy = `${title}\n\n${content}`;

            navigator.clipboard.writeText(textToCopy).then(() => {
                const button = event.target.closest('.share-btn');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i> Copied';
                button.style.backgroundColor = '#17a2b8';
                button.style.color = 'white';

                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.style.backgroundColor = '';
                    button.style.color = '';
                }, 2000);
            });
        }
    }

    // Add smooth scroll animation for better UX
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.announcement-card');

        // Add staggered animation for cards
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';

            setTimeout(() => {
                card.style.transition = 'opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), transform 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 150);
        });

        // Add hover effects for better interactivity
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.zIndex = '10';
            });

            card.addEventListener('mouseleave', function() {
                this.style.zIndex = '1';
            });
        });

        // Add notification for new announcements (if any)
        if (cards.length > 0) {
            setTimeout(() => {
                const notification = document.createElement('div');
                notification.innerHTML = `
                    <div style="position: fixed; top: 100px; right: 20px; background: linear-gradient(135deg, #10b981, #22c55e); color: white; padding: 1rem 1.5rem; border-radius: 15px; box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3); z-index: 1000; font-weight: 600; animation: slideIn 0.5s ease;">
                        ✅ ${cards.length} announcement${cards.length > 1 ? 's' : ''} loaded successfully!
                    </div>
                `;
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.5s ease';
                    setTimeout(() => notification.remove(), 500);
                }, 3000);
            }, 1000);
        }
    });

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
</script>
@endsection