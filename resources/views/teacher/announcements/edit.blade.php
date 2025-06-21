@extends('layouts.teacher')

@section('title', 'Edit Announcement')

@section('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .page-header h1 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: 700;
    }

    .page-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
        font-size: 1.1rem;
    }

    .form-container {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-control.is-invalid {
        border-color: #e53e3e;
        background: #fed7d7;
    }

    .invalid-feedback {
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }

    textarea.form-control {
        min-height: 150px;
        resize: vertical;
        font-family: inherit;
    }

    .status-selector {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .status-option {
        flex: 1;
        position: relative;
    }

    .status-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        margin: 0;
        cursor: pointer;
    }

    .status-option label {
        display: block;
        padding: 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .status-option input[type="radio"]:checked + label {
        border-color: #667eea;
        background: #667eea;
        color: white;
    }

    .status-option label .status-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .status-option label .status-desc {
        font-size: 0.875rem;
        opacity: 0.8;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e2e8f0;
    }

    .btn {
        padding: 0.8rem 2rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-secondary {
        background: #e2e8f0;
        color: #4a5568;
    }

    .btn-secondary:hover {
        background: #cbd5e0;
        color: #2d3748;
        text-decoration: none;
    }

    .announcement-info {
        background: #f0f4f8;
        border-left: 4px solid #667eea;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .announcement-info h4 {
        margin: 0 0 0.5rem 0;
        color: #2d3748;
        font-size: 1.1rem;
    }

    .announcement-info p {
        margin: 0;
        color: #4a5568;
        font-size: 0.9rem;
    }

    .character-count {
        text-align: right;
        font-size: 0.875rem;
        color: #718096;
        margin-top: 0.25rem;
    }

    .character-count.warning {
        color: #d69e2e;
    }

    .character-count.danger {
        color: #e53e3e;
    }

    .preview-section {
        background: #f8fafc;
        border: 2px dashed #cbd5e0;
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 1rem;
    }

    .preview-title {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .preview-content {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        border-left: 4px solid #667eea;
    }

    .preview-content h4 {
        margin: 0 0 0.5rem 0;
        color: #2d3748;
    }

    .preview-content p {
        margin: 0;
        color: #4a5568;
        line-height: 1.6;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1>✏️ Edit Announcement</h1>
    <p>Update your announcement details</p>
</div>

<div class="form-container">
    <div class="announcement-info">
        <h4>📋 Announcement Details</h4>
        <p>
            <strong>Created:</strong> {{ $announcement->created_at->format('F d, Y \a\t g:i A') }} |
            <strong>Status:</strong> 
            <span style="color: {{ $announcement->status === 'active' ? '#28a745' : '#ffc107' }};">
                {{ ucfirst($announcement->status) }}
            </span>
            @if($announcement->published_at)
                | <strong>Published:</strong> {{ $announcement->published_at->format('F d, Y \a\t g:i A') }}
            @endif
        </p>
    </div>

    <form method="POST" action="{{ route('teacher.announcements.update', $announcement) }}" id="announcementForm">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="title" class="form-label">
                <i class="fas fa-heading"></i> Announcement Title
            </label>
            <input type="text" 
                   class="form-control @error('title') is-invalid @enderror" 
                   id="title" 
                   name="title" 
                   value="{{ old('title', $announcement->title) }}" 
                   placeholder="Enter a clear and descriptive title..."
                   maxlength="255"
                   required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="character-count" id="titleCount">0/255 characters</div>
        </div>

        <div class="form-group">
            <label for="content" class="form-label">
                <i class="fas fa-align-left"></i> Announcement Content
            </label>
            <textarea class="form-control @error('content') is-invalid @enderror" 
                      id="content" 
                      name="content" 
                      placeholder="Write your announcement here. Be clear and provide all necessary details..."
                      rows="8"
                      required>{{ old('content', $announcement->content) }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="character-count" id="contentCount">0 characters</div>
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-toggle-on"></i> Publication Status
            </label>
            <div class="status-selector">
                <div class="status-option">
                    <input type="radio" id="status_draft" name="status" value="draft" 
                           {{ old('status', $announcement->status) === 'draft' ? 'checked' : '' }}>
                    <label for="status_draft">
                        <div class="status-title">📝 Save as Draft</div>
                        <div class="status-desc">Save for later editing</div>
                    </label>
                </div>
                <div class="status-option">
                    <input type="radio" id="status_active" name="status" value="active" 
                           {{ old('status', $announcement->status) === 'active' ? 'checked' : '' }}>
                    <label for="status_active">
                        <div class="status-title">📢 Publish Now</div>
                        <div class="status-desc">Make visible to students</div>
                    </label>
                </div>
            </div>
        </div>

        <div class="preview-section" id="previewSection">
            <div class="preview-title">
                <i class="fas fa-eye"></i> Preview
            </div>
            <div class="preview-content">
                <h4 id="previewTitle">{{ $announcement->title }}</h4>
                <p id="previewContent">{{ $announcement->content }}</p>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('teacher.announcements.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Announcement
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const contentInput = document.getElementById('content');
        const titleCount = document.getElementById('titleCount');
        const contentCount = document.getElementById('contentCount');
        const previewTitle = document.getElementById('previewTitle');
        const previewContent = document.getElementById('previewContent');

        // Character counting
        function updateTitleCount() {
            const length = titleInput.value.length;
            titleCount.textContent = `${length}/255 characters`;
            
            if (length > 200) {
                titleCount.className = 'character-count warning';
            } else if (length > 240) {
                titleCount.className = 'character-count danger';
            } else {
                titleCount.className = 'character-count';
            }
        }

        function updateContentCount() {
            const length = contentInput.value.length;
            contentCount.textContent = `${length} characters`;
        }

        function updatePreview() {
            const title = titleInput.value.trim();
            const content = contentInput.value.trim();
            
            previewTitle.textContent = title || 'Announcement Title';
            previewContent.textContent = content || 'Announcement content will appear here...';
        }

        // Event listeners
        titleInput.addEventListener('input', function() {
            updateTitleCount();
            updatePreview();
        });

        contentInput.addEventListener('input', function() {
            updateContentCount();
            updatePreview();
        });

        // Initial counts and preview
        updateTitleCount();
        updateContentCount();
        updatePreview();

        // Form validation
        document.getElementById('announcementForm').addEventListener('submit', function(e) {
            const title = titleInput.value.trim();
            const content = contentInput.value.trim();
            
            if (!title) {
                e.preventDefault();
                titleInput.focus();
                alert('Please enter a title for your announcement.');
                return;
            }
            
            if (!content) {
                e.preventDefault();
                contentInput.focus();
                alert('Please enter content for your announcement.');
                return;
            }
        });
    });
</script>
@endsection
