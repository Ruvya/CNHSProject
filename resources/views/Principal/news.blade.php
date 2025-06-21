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
        <div class="hero-decoration">
            <div class="floating-element element-1"></div>
            <div class="floating-element element-2"></div>
            <div class="floating-element element-3"></div>
        </div>
    </div>

    <!-- Calendar Modal -->
    <div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content enhanced-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="calendarModalLabel">School Events Calendar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="school-calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements Grid -->
    <h2 class="mt-5 mb-4 text-center section-header">📢 Latest Announcements</h2>
    <div class="container py-5">
        <div class="row g-4">
            @forelse($announcements->where('status', 'active') as $announcement)
                <div class="col-md-4" data-aos="fade-up" data-announcement-id="{{ $announcement->id }}">
                    <div class="announcement-card">
                        <div class="card-decoration"></div>
                        <div class="icon-wrapper mb-4">
                            <i class='bx bx-news'></i>
                        </div>
                        <h3>{{ $announcement->title }}</h3>
                        <p>{{ Str::limit($announcement->content, 150) }}</p>
                        <div class="announcement-meta">
                            <span><i class='bx bx-calendar'></i> {{ $announcement->created_at->format('F d, Y') }}</span>
                        </div>
                        <button type="button" class="btn btn-outline-primary mt-3 read-more-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#announcementModal{{ $announcement->id }}">
                            <i class='bx bx-book-open me-2'></i>Read More
                        </button>
                    </div>
                </div>

                <!-- Modal for this announcement -->
                <div class="modal fade" id="announcementModal{{ $announcement->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content enhanced-modal">
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
                        <div class="empty-icon">
                            <i class='bx bx-news'></i>
                        </div>
                        <h3>No Announcements</h3>
                        <p>There are no announcements available at the moment.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <h2 class="mt-5 mb-4 text-center section-header">🗓️ Upcoming School Events</h2>
        <div class="row g-4">
            @forelse($upcomingEvents as $event)
                <div class="col-md-4" data-aos="fade-up" data-event-id="{{ $event->id }}">
                    <div class="announcement-card event-card">
                        <div class="card-decoration event-decoration"></div>
                        <div class="icon-wrapper mb-4 event-icon">
                            <i class='bx bx-calendar-event'></i>
                        </div>
                        <h3>{{ $event->title }}</h3>
                        <p>{{ Str::limit($event->description, 150) }}</p>
                        <div class="announcement-meta event-meta">
                            <span><i class='bx bx-calendar'></i> {{ \Carbon\Carbon::parse($event->start)->format('F d, Y h:i A') }}</span>
                        </div>
                        <button type="button" class="btn btn-outline-success mt-3 read-more-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#eventDetailModal{{ $event->id }}">
                            <i class='bx bx-show me-2'></i>View Event
                        </button>
                    </div>
                </div>

                <!-- Modal for event details -->
                <div class="modal fade" id="eventDetailModal{{ $event->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content enhanced-modal event-modal">
                            <div class="modal-header border-bottom-0">
                                <h5 class="modal-title">{{ $event->title }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="announcement-meta mb-4">
                                    <span><i class='bx bx-calendar'></i> {{ \Carbon\Carbon::parse($event->start)->format('F d, Y h:i A') }}</span>
                                    @if($event->end)
                                        <span> - {{ \Carbon\Carbon::parse($event->end)->format('F d, Y h:i A') }}</span>
                                    @endif
                                </div>
                                <div class="announcement-content">
                                    {!! nl2br(e($event->description)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="empty-state">
                        <div class="empty-icon event-empty">
                            <i class='bx bx-calendar-event'></i>
                        </div>
                        <h3>No Upcoming Events</h3>
                        <p>There are no upcoming school events scheduled at the moment.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@section('styles')
<style>
    :root {
        --cnhs-orange: #FF8C00;
        --cnhs-blue: #1E3A8A;
        --cnhs-light-blue: #3B82F6;
        --cnhs-yellow: #FCD34D;
        --cnhs-white: #FFFFFF;
        --cnhs-gray: #6B7280;
        --cnhs-light-gray: #F3F4F6;
    }

    .hero-section-small {
        background: linear-gradient(135deg, var(--cnhs-blue) 0%, var(--cnhs-light-blue) 50%, var(--cnhs-orange) 100%);
        padding: 100px 0;
        margin-top: -20px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .hero-section-small::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        animation: gridMove 20s linear infinite;
    }

    @keyframes gridMove {
        0% { transform: translate(0, 0); }
        100% { transform: translate(10px, 10px); }
    }

    .hero-title {
        color: var(--cnhs-white);
        font-size: 3rem;
        font-weight: 800;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 1s ease-out;
    }

    .hero-subtitle {
        color: var(--cnhs-white);
        font-size: 1.4rem;
        opacity: 0.95;
        font-weight: 300;
        animation: fadeInUp 1s ease-out 0.3s both;
    }

    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .floating-element {
        position: absolute;
        border-radius: 50%;
        opacity: 0.1;
        animation: float 6s ease-in-out infinite;
    }

    .element-1 {
        width: 80px;
        height: 80px;
        background: var(--cnhs-orange);
        top: 20%;
        right: 15%;
        animation-delay: 0s;
    }

    .element-2 {
        width: 120px;
        height: 120px;
        background: var(--cnhs-yellow);
        bottom: 25%;
        left: 10%;
        animation-delay: 2s;
    }

    .element-3 {
        width: 60px;
        height: 60px;
        background: var(--cnhs-white);
        top: 60%;
        right: 25%;
        animation-delay: 4s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .section-header {
        font-size: 2.8rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--cnhs-blue), var(--cnhs-orange));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
    }

    .section-header::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 5px;
        background: linear-gradient(135deg, var(--cnhs-orange), var(--cnhs-blue));
        border-radius: 3px;
    }

    .announcement-card {
        background: linear-gradient(145deg, var(--cnhs-white), var(--cnhs-light-gray));
        border-radius: 20px;
        padding: 2.5rem;
        height: 100%;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-align: center;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(30, 58, 138, 0.1);
    }

    .announcement-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.5s;
    }

    .announcement-card:hover::before {
        left: 100%;
    }

    .announcement-card:hover {
        transform: translateY(-15px) scale(1.02);
        box-shadow: 0 25px 50px rgba(30, 58, 138, 0.2);
    }

    .card-decoration {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(135deg, var(--cnhs-blue), var(--cnhs-orange));
        border-radius: 20px 20px 0 0;
    }

    .event-card .card-decoration {
        background: linear-gradient(135deg, var(--cnhs-orange), var(--cnhs-yellow));
    }

    .announcement-card .icon-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        position: relative;
        transition: all 0.3s ease;
    }

    .announcement-card:hover .icon-wrapper {
        transform: rotate(360deg) scale(1.1);
        background: linear-gradient(135deg, var(--cnhs-blue), var(--cnhs-light-blue));
    }

    .announcement-card .icon-wrapper i {
        font-size: 2.5rem;
        color: var(--cnhs-blue);
        transition: all 0.3s ease;
    }

    .announcement-card:hover .icon-wrapper i {
        color: var(--cnhs-white);
    }

    .event-card .icon-wrapper {
        background: linear-gradient(135deg, #FFF7ED, #FED7AA);
    }

    .event-card:hover .icon-wrapper {
        background: linear-gradient(135deg, var(--cnhs-orange), var(--cnhs-yellow));
    }

    .event-card .icon-wrapper i {
        color: var(--cnhs-orange);
    }

    .announcement-card h3 {
        color: #2d3436;
        font-size: 1.6rem;
        font-weight: 700;
        margin: 1.5rem 0;
        line-height: 1.4;
        transition: color 0.3s ease;
    }

    .announcement-card:hover h3 {
        color: #0d6efd;
    }

    .event-card:hover h3 {
        color: #198754;
    }

    .announcement-card p {
        color: #636e72;
        font-size: 1.1rem;
        line-height: 1.7;
        margin-bottom: 1.5rem;
        font-weight: 400;
    }

    .announcement-meta {
        color: #6c757d;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
        padding: 0.5rem 1rem;
        background: rgba(13, 110, 253, 0.1);
        border-radius: 20px;
        display: inline-block;
    }

    .announcement-meta i {
        margin-right: 8px;
        color: #0d6efd;
    }

    .read-more-btn {
        border-width: 2px;
        padding: 0.75rem 2rem;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .read-more-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--cnhs-blue), var(--cnhs-light-blue));
        transition: left 0.3s ease;
        z-index: -1;
    }

    .read-more-btn:hover::before {
        left: 0;
    }

    .read-more-btn:hover {
        color: #fff !important;
        border-color: transparent;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
    }

    .btn-outline-success.read-more-btn::before {
        background: linear-gradient(135deg, var(--cnhs-orange), var(--cnhs-yellow));
    }

    .btn-outline-success.read-more-btn:hover {
        box-shadow: 0 10px 20px rgba(255, 255, 255, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(145deg, #f8f9fa, #e9ecef);
        border-radius: 20px;
        border: 2px dashed #dee2e6;
        transition: all 0.3s ease;
    }

    .empty-state:hover {
        border-color: #0d6efd;
        background: linear-gradient(145deg, #e3f2fd, #f8f9fa);
    }

    .empty-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e9ecef, #dee2e6);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        transition: all 0.3s ease;
    }

    .empty-state:hover .empty-icon {
        background: linear-gradient(135deg, var(--cnhs-blue), var(--cnhs-orange));
        transform: scale(1.1);
    }

    .empty-state i {
        font-size: 3rem;
        color: #6c757d;
        transition: color 0.3s ease;
    }

    .empty-state:hover i {
        color: #fff;
    }

    .empty-state h3 {
        color: #2d3436;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .empty-state p {
        color: #636e72;
        font-size: 1.1rem;
    }

    /* Enhanced Modal Styles */
    .enhanced-modal {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .enhanced-modal .modal-header {
        padding: 2rem 2rem 1rem;
        background: linear-gradient(135deg, var(--cnhs-blue), var(--cnhs-orange));
        color: white;
        border-radius: 20px 20px 0 0;
    }

    .event-modal .modal-header {
        background: linear-gradient(135deg, var(--cnhs-orange), var(--cnhs-yellow));
    }

    .enhanced-modal .modal-title {
        color: white;
        font-size: 1.8rem;
        font-weight: 700;
    }

    .enhanced-modal .modal-body {
        padding: 2rem;
        background: linear-gradient(145deg, var(--cnhs-white), var(--cnhs-light-gray));
    }

    .enhanced-modal .announcement-content {
        color: #2d3436;
        font-size: 1.1rem;
        line-height: 1.9;
        white-space: pre-line;
    }

    .enhanced-modal .btn-close {
        background-color: rgba(255, 255, 255, 0.2);
        padding: 0.75rem;
        border-radius: 50%;
        transition: all 0.3s ease;
        opacity: 1;
    }

    .enhanced-modal .btn-close:hover {
        background-color: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.2rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .section-header {
            font-size: 2rem;
        }

        .announcement-card {
            margin-bottom: 1.5rem;
            padding: 2rem;
        }

        .modal-dialog {
            margin: 1rem;
        }

        .enhanced-modal .modal-header,
        .enhanced-modal .modal-body {
            padding: 1.5rem;
        }
    }

    /* Loading Animation */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .announcement-card:hover .icon-wrapper {
        animation: pulse 2s infinite;
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

        // Add loading animation to buttons
        document.querySelectorAll('.read-more-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                this.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i>Loading...';
                setTimeout(() => {
                    this.innerHTML = this.classList.contains('btn-outline-success') ? 
                        '<i class="bx bx-show me-2"></i>View Event' : 
                        '<i class="bx bx-book-open me-2"></i>Read More';
                }, 1000);
            });
        });
    });
</script>
@endsection