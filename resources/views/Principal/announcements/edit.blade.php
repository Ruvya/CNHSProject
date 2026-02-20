@extends('Principal.layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="announcements-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <div class="school-logo">
                        <img src="{{ asset('images/CNHS.png') }}" alt="CNHS Logo" class="logo-img">
                    </div>
                    <div>
                        <h1 class="page-title mb-1">
                            <i class="fas fa-edit me-2"></i>
                            Edit Announcement
                        </h1>
                        <p class="page-subtitle mb-0">
                            Update your announcement content and settings
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="header-button-group">
                    <a href="{{ route('principal.announcements.index') }}" class="header-btn btn-secondary btn-sm shadow">
                        <i class="fas fa-arrow-left me-2"></i><span class="d-none d-md-inline">Back</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="announcements-card">
        <div class="announcements-card-header">
            <h5 class="mb-0">
                <i class="fas fa-edit me-2"></i>
                Edit Announcement Details
            </h5>
        </div>
        <div class="announcements-card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('principal.announcements.update', $announcement->id) }}" method="POST" class="enhanced-form">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <label for="title" class="form-label enhanced-label">
                                <i class="fas fa-heading me-2"></i>Announcement Title
                            </label>
                            <input type="text" 
                                   class="form-control enhanced-input @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $announcement->title) }}" 
                                   placeholder="Enter announcement title..."
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label enhanced-label">
                                <i class="fas fa-align-left me-2"></i>Announcement Content
                            </label>
                            <textarea class="form-control enhanced-textarea @error('content') is-invalid @enderror" 
                                      id="content" 
                                      name="content" 
                                      rows="8" 
                                      placeholder="Write your announcement content here..."
                                      required>{{ old('content', $announcement->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                You can use basic HTML formatting for better presentation.
                            </small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-sidebar">
                            <div class="mb-4">
                                <label for="status" class="form-label enhanced-label">
                                    <i class="fas fa-toggle-on me-2"></i>Publication Status
                                </label>
                                <select class="form-select enhanced-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="draft" {{ old('status', $announcement->status) === 'draft' ? 'selected' : '' }}>
                                        <i class="fas fa-edit"></i> Save as Draft
                                    </option>
                                    <option value="active" {{ old('status', $announcement->status) === 'active' ? 'selected' : '' }}>
                                        <i class="fas fa-eye"></i> Publish Immediately
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="announcement-info">
                                <h6 class="info-title">
                                    <i class="fas fa-info-circle me-2"></i>Announcement Info
                                </h6>
                                <div class="info-content">
                                    <div class="info-item">
                                        <span class="info-label">Created:</span>
                                        <span class="info-value">{{ $announcement->created_at->format('M d, Y \a\t g:i A') }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Last Updated:</span>
                                        <span class="info-value">{{ $announcement->updated_at->format('M d, Y \a\t g:i A') }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Current Status:</span>
                                        <span class="badge bg-{{ $announcement->status === 'active' ? 'success' : 'warning' }}">
                                            {{ ucfirst($announcement->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="preview-card">
                                <h6 class="preview-title">
                                    <i class="fas fa-eye me-2"></i>Live Preview
                                </h6>
                                <div class="preview-content">
                                    <div id="title-preview" class="preview-title-text">{{ $announcement->title }}</div>
                                    <div id="content-preview" class="preview-content-text">{{ $announcement->content }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('principal.announcements.index') }}" class="btn btn-secondary enhanced-btn">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary enhanced-btn">
                        <i class="fas fa-save me-2"></i>Update Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Enhanced Form Styles */
    .enhanced-form {
        background: #ffffff;
        border-radius: 12px;
        padding: 0;
    }

    .enhanced-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .enhanced-input,
    .enhanced-textarea,
    .enhanced-select {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .enhanced-input:focus,
    .enhanced-textarea:focus,
    .enhanced-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        outline: none;
    }

    .enhanced-textarea {
        resize: vertical;
        min-height: 200px;
    }

    .form-sidebar {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
    }

    .announcement-info {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .info-title {
        color: #374151;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .info-content {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 500;
        color: #6b7280;
        font-size: 0.85rem;
    }

    .info-value {
        font-weight: 600;
        color: #374151;
        font-size: 0.85rem;
    }

    .preview-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .preview-title {
        color: #374151;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .preview-title-text {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .preview-content-text {
        color: #6b7280;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }

    .enhanced-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }

    .enhanced-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-primary.enhanced-btn {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: #ffffff;
    }

    .btn-secondary.enhanced-btn {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: #ffffff;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .form-sidebar {
            margin-top: 1.5rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .enhanced-btn {
            width: 100%;
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Live preview functionality
    const titleInput = document.getElementById('title');
    const contentInput = document.getElementById('content');
    const titlePreview = document.getElementById('title-preview');
    const contentPreview = document.getElementById('content-preview');

    function updatePreview() {
        titlePreview.textContent = titleInput.value || 'Your announcement title will appear here';
        contentPreview.textContent = contentInput.value || 'Your announcement content will appear here';
    }

    titleInput.addEventListener('input', updatePreview);
    contentInput.addEventListener('input', updatePreview);

    // Initialize preview with current values
    updatePreview();
});
</script>
@endsection 