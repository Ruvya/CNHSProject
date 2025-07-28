@extends('layouts.teacher')

@section('title', 'My Announcements')

@section('styles')
<style>
    /* New Header Style */
    .page-header-design {
        background: linear-gradient(115deg, #f97316 60%, #3b82f6 60%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(249, 115, 22, 0.25);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header-left-content {
        display: flex;
        align-items: center;
    }
    .header-left-content i {
        font-size: 3rem;
        margin-right: 1.5rem;
        opacity: 0.8;
    }
    .header-left-content h1 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .header-left-content p {
        margin: 0.25rem 0 0 0;
        opacity: 0.9;
        font-size: 1.1rem;
    }
    .header-right-content .announcement-count-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.75rem 1.25rem;
        border-radius: 50px;
        display: flex;
        align-items: center;
        font-weight: 600;
        font-size: 1rem;
        backdrop-filter: blur(10px);
    }
    .header-right-content .announcement-count-badge i {
        margin-right: 0.5rem;
    }

    .create-btn {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        margin-bottom: 2rem;
    }

    .create-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(40, 167, 69, 0.4);
        color: white;
        text-decoration: none;
    }

    .announcements-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .announcement-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-left: 5px solid #667eea;
    }

    .announcement-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .announcement-card.draft {
        border-left-color: #ffc107;
        opacity: 0.8;
    }

    .announcement-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .announcement-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2d3748;
        margin: 0;
        flex: 1;
    }

    .announcement-status {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-active {
        background: #d4edda;
        color: #155724;
    }

    .status-draft {
        background: #fff3cd;
        color: #856404;
    }

    .announcement-content {
        color: #4a5568;
        line-height: 1.6;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .announcement-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        color: #718096;
    }

    .announcement-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .btn-edit {
        background: #e3f2fd;
        color: #1976d2;
    }

    .btn-edit:hover {
        background: #bbdefb;
        color: #1565c0;
        text-decoration: none;
    }

    .btn-delete {
        background: #ffebee;
        color: #d32f2f;
    }

    .btn-delete:hover {
        background: #ffcdd2;
        color: #c62828;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: #4a5568;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #718096;
        margin-bottom: 2rem;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        border: none;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-design">
                <div class="header-left-content">
                    <i class="fas fa-bullhorn"></i>
                    <div>
                        <h1>My Announcements</h1>
                        <p>Create and manage announcements for your students</p>
                    </div>
                </div>
                <div class="header-right-content">
                    <div class="announcement-count-badge">
                        <i class="fas fa-bullhorn"></i>
                        <span>{{ $announcements ? $announcements->count() : 0 }} Announcements</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('teacher.announcements.create') }}" class="create-btn">
        <i class="fas fa-plus"></i>
        Create New Announcement
    </a>

    @if($announcements && $announcements->count() > 0)
        <div class="announcements-grid">
            @foreach($announcements as $announcement)
            <div class="announcement-card {{ $announcement->status === 'draft' ? 'draft' : '' }}">
                <div class="announcement-header">
                    <h3 class="announcement-title">{{ $announcement->title }}</h3>
                    <span class="announcement-status status-{{ $announcement->status }}">
                        {{ $announcement->status }}
                    </span>
                </div>
                
                <div class="announcement-content">
                    {{ Str::limit($announcement->content, 150) }}
                </div>
                
                <div class="announcement-meta">
                    <span>📅 {{ $announcement->created_at->format('M d, Y') }}</span>
                    @if($announcement->published_at)
                        <span>🕒 Published {{ optional($announcement->published_at instanceof \Illuminate\Support\Carbon ? $announcement->published_at : \Illuminate\Support\Carbon::parse($announcement->published_at))->format('M d, Y') }}</span>
                    @endif
                </div>
                
                <div class="announcement-actions">
                    <a href="{{ route('teacher.announcements.edit', $announcement) }}" class="btn-action btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('teacher.announcements.destroy', $announcement) }}" 
                          style="display: inline;" 
                          onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-bullhorn"></i>
            <h3>No Announcements Yet</h3>
            <p>You haven't created any announcements yet. Create your first announcement to communicate with your students!</p>
            <a href="{{ route('teacher.announcements.create') }}" class="create-btn">
                <i class="fas fa-plus"></i>
                Create Your First Announcement
            </a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Auto-hide success messages after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
@endsection
