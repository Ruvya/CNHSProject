@extends('Principal.layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="hero-section-small d-flex align-items-center" style="background: var(--cnhs-primary-blue); min-height: 320px; color: white;">
        <div class="container">
            <div class="hero-content text-center" data-aos="fade-up">
                <h1 class="hero-title mb-4" style="color: white; font-size: 3.2rem; font-weight: 800;"><i class="fas fa-newspaper me-2"></i>News & Announcements</h1>
                <p class="hero-subtitle" style="color: #e0e7ff; font-size: 1.5rem;">Stay Updated with CNHS News and Events</p>
            </div>
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
        --cnhs-primary-blue: #1E3A8A;
        --cnhs-secondary-blue: #3B82F6;
        --cnhs-accent-orange: #FF8C00;
        --cnhs-gold: #FCD34D;
        --cnhs-white: #FFFFFF;
        --cnhs-light-gray: #F8FAFC;
        --cnhs-medium-gray: #4B5563;
        --cnhs-dark-gray: #1F2937;
        --cnhs-success: #10B981;
        --cnhs-warning: #F59E0B;
    }

    .hero-section-small {
        background: var(--cnhs-primary-blue);
        padding: 120px 0;
        margin-top: -20px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .hero-title {
        color: var(--cnhs-white);
        font-size: 3.2rem;
        font-weight: 900;
        text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.6);
        animation: fadeInUp 1.2s ease-out;
        letter-spacing: -0.02em;
    }

    .hero-subtitle {
        color: var(--cnhs-white);
        font-size: 1.5rem;
        opacity: 1;
        font-weight: 600;
        animation: fadeInUp 1.2s ease-out 0.3s both;
        letter-spacing: 0.02em;
        text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.5);
    }

    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .floating-element {
        position: absolute;
        border-radius: 50%;
        opacity: 0.08;
        animation: float 8s ease-in-out infinite;
    }

    .element-1 {
        width: 100px;
        height: 100px;
        background: var(--cnhs-accent-orange);
        top: 15%;
        right: 12%;
        animation-delay: 0s;
    }

    .element-2 {
        width: 140px;
        height: 140px;
        background: var(--cnhs-gold);
        bottom: 20%;
        left: 8%;
        animation-delay: 2.5s;
    }

    .element-3 {
        width: 80px;
        height: 80px;
        background: var(--cnhs-white);
        top: 55%;
        right: 22%;
        animation-delay: 5s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-25px) rotate(180deg); }
    }

    .section-header {
        font-size: 2.8rem;
        font-weight: 900;
        color: var(--cnhs-primary-blue);
        position: relative;
        letter-spacing: -0.02em;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        background: none;
        -webkit-background-clip: initial;
        -webkit-text-fill-color: initial;
        background-clip: initial;
    }

    .section-header::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 120px;
        height: 5px;
        background: var(--cnhs-accent-orange);
        border-radius: 3px;
    }

    .announcement-card, .event-card {
        background: var(--cnhs-white);
        border-radius: 24px;
        padding: 2.5rem;
        height: 100%;
        box-shadow: 0 12px 35px rgba(30, 58, 138, 0.12);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-align: center;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(30, 58, 138, 0.08);
    }

    .announcement-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s;
    }

    .announcement-card:hover::before {
        left: 100%;
    }

    .announcement-card:hover {
        transform: translateY(-18px) scale(1.02);
        box-shadow: 0 28px 55px rgba(30, 58, 138, 0.25);
    }

    .card-decoration, .event-decoration {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: var(--cnhs-primary-blue);
        border-radius: 24px 24px 0 0;
    }

    .event-card .card-decoration {
        background: var(--cnhs-accent-orange);
    }

    .announcement-card .icon-wrapper, .event-card .icon-wrapper {
        width: 85px;
        height: 85px;
        border-radius: 50%;
        background: var(--cnhs-light-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        position: relative;
        transition: all 0.4s ease;
        box-shadow: 0 8px 20px rgba(30, 58, 138, 0.15);
    }

    .announcement-card:hover .icon-wrapper {
        transform: rotate(360deg) scale(1.15);
        background: var(--cnhs-primary-blue);
        box-shadow: 0 12px 30px rgba(30, 58, 138, 0.3);
    }

    .announcement-card .icon-wrapper i, .event-card .icon-wrapper i {
        font-size: 2.5rem;
        color: var(--cnhs-primary-blue);
        transition: all 0.4s ease;
    }

    .announcement-card:hover .icon-wrapper i, .event-card:hover .icon-wrapper i {
        color: var(--cnhs-white);
    }

    .announcement-card h3 {
        color: var(--cnhs-dark-gray);
        font-size: 1.7rem;
        font-weight: 800;
        margin: 1.8rem 0;
        line-height: 1.4;
        transition: color 0.3s ease;
        letter-spacing: -0.01em;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
    }

    .announcement-card:hover h3 {
        color: var(--cnhs-primary-blue);
    }

    .event-card:hover h3 {
        color: var(--cnhs-accent-orange);
    }

    .announcement-card p {
        color: var(--cnhs-dark-gray);
        font-size: 1.1rem;
        line-height: 1.7;
        margin-bottom: 1.8rem;
        font-weight: 600;
    }

    .announcement-meta, .event-meta {
        color: var(--cnhs-primary-blue);
        font-size: 0.95rem;
        margin-bottom: 1.8rem;
        padding: 0.6rem 1.2rem;
        background: var(--cnhs-light-gray);
        border-radius: 25px;
        display: inline-block;
        font-weight: 700;
    }

    .announcement-meta i {
        margin-right: 8px;
        color: var(--cnhs-primary-blue);
    }

    .read-more-btn, .btn-outline-success.read-more-btn {
        border-width: 2px;
        padding: 0.8rem 2.2rem;
        border-radius: 30px;
        font-weight: 700;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.9rem;
        background: var(--cnhs-white);
        color: var(--cnhs-primary-blue);
        border: 2px solid var(--cnhs-primary-blue);
    }

    .read-more-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--cnhs-primary-blue), var(--cnhs-secondary-blue));
        transition: left 0.4s ease;
        z-index: -1;
    }

    .read-more-btn:hover::before {
        left: 0;
    }

    .read-more-btn:hover, .btn-outline-success.read-more-btn:hover {
        color: white !important;
        border-color: var(--cnhs-primary-blue);
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(30, 58, 138, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 4.5rem 2.5rem;
        background: var(--cnhs-light-gray);
        border-radius: 24px;
        border: 2px dashed #CBD5E1;
        transition: all 0.4s ease;
    }

    .empty-state:hover {
        border-color: var(--cnhs-primary-blue);
        background: #EFF6FF;
        transform: translateY(-5px);
    }

    .empty-icon, .event-empty {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: var(--cnhs-white);
        color: var(--cnhs-primary-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        transition: all 0.4s ease;
    }

    .empty-state:hover .empty-icon, .empty-state:hover .event-empty {
        background: var(--cnhs-primary-blue);
        transform: scale(1.1);
    }

    .empty-state i {
        font-size: 3.2rem;
        color: var(--cnhs-medium-gray);
        transition: color 0.4s ease;
    }

    .empty-state:hover i {
        color: var(--cnhs-white);
    }

    .empty-state h3 {
        color: var(--cnhs-dark-gray);
        margin-bottom: 0.8rem;
        font-weight: 800;
        font-size: 1.5rem;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
    }

    .empty-state p {
        color: var(--cnhs-dark-gray);
        font-size: 1.1rem;
        line-height: 1.6;
        font-weight: 600;
    }

    /* Enhanced Modal Styles */
    .enhanced-modal {
        border: none;
        border-radius: 24px;
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }

    .enhanced-modal .modal-header {
        padding: 2.5rem 2.5rem 1.5rem;
        background: var(--cnhs-primary-blue);
        color: white;
        border-radius: 24px 24px 0 0;
    }

    .event-modal .modal-header {
        background: var(--cnhs-accent-orange);
    }

    .enhanced-modal .modal-title {
        color: white;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: -0.01em;
        text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.3);
    }

    .enhanced-modal .modal-body {
        padding: 2.5rem;
        background: var(--cnhs-white);
    }

    .enhanced-modal .announcement-content {
        color: var(--cnhs-dark-gray);
        font-size: 1.1rem;
        line-height: 1.9;
        white-space: pre-line;
        font-weight: 600;
    }

    .enhanced-modal .btn-close {
        background-color: rgba(255, 255, 255, 0.15);
        padding: 0.8rem;
        border-radius: 50%;
        transition: all 0.3s ease;
        opacity: 1;
        backdrop-filter: blur(10px);
    }

    .enhanced-modal .btn-close:hover {
        background-color: rgba(255, 255, 255, 0.25);
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.4rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
        }

        .section-header {
            font-size: 2.2rem;
        }

        .announcement-card {
            margin-bottom: 2rem;
            padding: 2rem;
        }

        .modal-dialog {
            margin: 1rem;
        }

        .enhanced-modal .modal-header,
        .enhanced-modal .modal-body {
            padding: 2rem;
        }
    }

    /* Professional Loading Animation */
    @keyframes professionalPulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(30, 58, 138, 0.4); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(30, 58, 138, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(30, 58, 138, 0); }
    }

    .announcement-card:hover .icon-wrapper {
        animation: professionalPulse 2s infinite;
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

        // Add professional loading animation to buttons
        document.querySelectorAll('.read-more-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                this.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i>Loading...';
                setTimeout(() => {
                    this.innerHTML = this.classList.contains('btn-outline-success') ? 
                        '<i class="bx bx-show me-2"></i>View Event' : 
                        '<i class="bx bx-book-open me-2"></i>Read More';
                }, 1200);
            });
        });
    });
</script>
@endsection