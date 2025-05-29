@extends('Principal.layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="hero-section-small d-flex align-items-center mb-5">
        <div class="container">
            <div class="hero-content text-center" data-aos="fade-up">
                <h1 class="hero-title mb-4">School Announcements</h1>
                <p class="hero-subtitle">Stay Updated with CNHS</p>
            </div>
        </div>
    </div>

    <!-- Announcements Grid -->
    <div class="container py-5">
        <div class="row g-4">
            @forelse($announcements->where('status', 'active') as $announcement)
                <div class="col-md-4" data-aos="fade-up" data-announcement-id="{{ $announcement->id }}">
                    <div class="announcement-card">
                        <div class="icon-wrapper mb-4">
                            <i class='bx bx-news'></i>
                        </div>
                        <h3>{{ $announcement->title }}</h3>
                        <p>{{ Str::limit($announcement->content, 150) }}</p>
                        <div class="announcement-meta">
                            <span><i class='bx bx-calendar'></i> {{ $announcement->created_at->format('F d, Y') }}</span>
                        </div>
                        <button type="button" class="btn btn-outline-primary mt-3" 
                                data-bs-toggle="modal" 
                                data-bs-target="#announcementModal{{ $announcement->id }}">
                            Read More
                        </button>
                    </div>
                </div>

                <!-- Modal for this announcement -->
                <div class="modal fade" id="announcementModal{{ $announcement->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header border-bottom-0">
                                <h5 class="modal-title">{{ $announcement->title }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="announcement-meta mb-4">
                                    <span><i class='bx bx-calendar'></i> {{ $announcement->created_at->format('F d, Y') }}</span>
                                </div>
                                <div class="announcement-content">
                                    {!! nl2br(e($announcement->content)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="empty-state">
                        <i class='bx bx-news'></i>
                        <h3>No Announcements</h3>
                        <p>There are no announcements available at the moment.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@section('styles')
<style>
    .hero-section-small {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset("images/school-bg.jpg") }}');
        background-size: cover;
        background-position: center;
        padding: 80px 0;
        margin-top: -20px;
    }

    .hero-title {
        color: #fff;
        font-size: 2.5rem;
        font-weight: 700;
    }

    .hero-subtitle {
        color: #fff;
        font-size: 1.2rem;
        opacity: 0.9;
    }

    .announcement-card {
        background: #fff;
        border-radius: 15px;
        padding: 2rem;
        height: 100%;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .announcement-card:hover {
        transform: translateY(-5px);
    }

    .announcement-card .icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: #f0f4ff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .announcement-card .icon-wrapper i {
        font-size: 2rem;
        color: #0d6efd;
    }

    .announcement-card h3 {
        color: #2d3436;
        font-size: 1.5rem;
        font-weight: 600;
        margin: 1rem 0;
        line-height: 1.4;
    }

    .announcement-card p {
        color: #636e72;
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .announcement-meta {
        color: #888;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .announcement-meta i {
        margin-right: 5px;
    }

    .btn-outline-primary {
        border-width: 2px;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: #fff;
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: #f8f9fa;
        border-radius: 15px;
    }

    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: #2d3436;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #636e72;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .announcement-card {
            margin-bottom: 1rem;
        }

        .modal-dialog {
            margin: 1rem;
        }
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        padding: 1.5rem 1.5rem 1rem;
        background: #fff;
        border-radius: 15px 15px 0 0;
    }

    .modal-title {
        color: #2d3436;
        font-size: 1.5rem;
        font-weight: 600;
        line-height: 1.4;
    }

    .modal-body {
        padding: 1.5rem;
        background: #fff;
        border-radius: 0 0 15px 15px;
    }

    .announcement-content {
        color: #2d3436;
        font-size: 1rem;
        line-height: 1.8;
        white-space: pre-line;
    }

    .btn-close {
        background-color: #f8f9fa;
        padding: 0.5rem;
        border-radius: 50%;
        transition: background-color 0.3s ease;
    }

    .btn-close:hover {
        background-color: #e9ecef;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all modals
        var modals = document.querySelectorAll('.modal');
        modals.forEach(function(modal) {
            new bootstrap.Modal(modal);
        });
    });
</script>
@endsection 