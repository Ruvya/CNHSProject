@extends('Principal.layouts.admin')

@section('content')
<!-- School Header -->
<div class="school-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="mb-2"><strong>Welcome back, {{ Auth::guard('principal')->user()->name }}!</strong></h1>
            <p class="mb-0 opacity-75">Calingcaguing National High School - Principal Dashboard</p>
            <small class="opacity-50">{{ date('l, F j, Y') }}</small>
        </div>
        <div class="col-md-4 text-end">
            <div class="d-flex justify-content-end align-items-center">
                <div class="me-3">
                    <i class="fas fa-school fa-3x text-white"></i>
                </div>
                <div>
                    <div class="fw-bold text-white">Academic Year</div>
                    <div class="small text-white">2024-2025</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- School Statistics -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stats-icon">
                    <i class="fas fa-user-graduate text-primary fa-2x"></i>
                </div>
                <div class="text-end">
                    <div class="stats-number">{{ number_format($totalStudents ?? 0) }}</div>
                    <div class="stats-label">Enrolled Students</div>
                </div>
            </div>
            <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-primary" style="width: {{ $studentCapacityPercentage ?? 0 }}%"></div>
            </div>
            <small class="text-muted mt-2 d-block">{{ $studentCapacityPercentage ?? 0 }}% of capacity</small>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stats-icon">
                    <i class="fas fa-chalkboard-teacher text-success fa-2x"></i>
                </div>
                <div class="text-end">
                    <div class="stats-number">{{ number_format($totalTeachers ?? 0) }}</div>
                    <div class="stats-label">Teaching Staff</div>
                </div>
            </div>
            <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-success" style="width: {{ $teacherUtilizationPercentage ?? 0 }}%"></div>
            </div>
            <small class="text-muted mt-2 d-block">{{ $teacherUtilizationPercentage ?? 0 }}% actively teaching</small>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stats-icon">
                    <i class="fas fa-door-open text-info fa-2x"></i>
                </div>
                <div class="text-end">
                    <div class="stats-number">{{ number_format($activeClasses ?? 0) }}</div>
                    <div class="stats-label">Active Classes</div>
                </div>
            </div>
            <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-info" style="width: {{ $roomUtilizationPercentage ?? 0 }}%"></div>
            </div>
            <small class="text-muted mt-2 d-block">{{ $roomUtilizationPercentage ?? 0 }}% rooms occupied</small>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stats-icon">
                    <i class="fas fa-book text-warning fa-2x"></i>
                </div>
                <div class="text-end">
                    <div class="stats-number">{{ number_format($totalSubjects ?? 0) }}</div>
                    <div class="stats-label">Total Subjects</div>
                </div>
            </div>
            <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-warning" style="width: {{ ($totalSubjects ?? 0) > 0 ? min(round((($activeClasses ?? 0) / ($totalSubjects ?? 1)) * 100), 100) : 0 }}%"></div>
            </div>
            <small class="text-muted mt-2 d-block">{{ ($totalSubjects ?? 0) > 0 ? min(round((($activeClasses ?? 0) / ($totalSubjects ?? 1)) * 100), 100) : 0 }}% have assigned teachers</small>
        </div>
    </div>
</div>


<!-- Calendar Modal -->
<div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="calendarModalLabel">School Events Calendar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="text-center py-2 text-muted small">Click any date to create a new event.</div>
                <div id="school-calendar" style="height:80vh;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="row">
    <!-- Recent School Announcements -->
    <div class="col-lg-8 mb-4">
        <div class="card enhanced-announcements-card">
            <div class="card-header enhanced-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 card-title-enhanced">
                    <i class="fas fa-bullhorn me-2"></i>
                    School Announcements
                </h5>
                <a href="{{ route('principal.announcements.create') }}" class="btn btn-enhanced-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>New Announcement
                </a>
            </div>
            <div class="card-body enhanced-card-body p-0">
                <div class="list-group list-group-flush enhanced-list-group">
                    @if(isset($recentAnnouncements) && $recentAnnouncements->count() > 0)
                        @foreach($recentAnnouncements->where('status', 'active') as $announcement)
                            <div class="list-group-item enhanced-list-item p-3 border-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 announcement-title-enhanced">{{ $announcement->title ?? 'Untitled' }}</h6>
                                        <p class="mb-2 announcement-content-enhanced">{{ Str::limit($announcement->content ?? '', 120) }}</p>
                                        <small class="announcement-meta-enhanced">
                                            <i class="fas fa-calendar me-1"></i>
                                            @if($announcement->created_at)
                                                {{ $announcement->created_at->diffForHumans() }}
                                            @else
                                                Unknown date
                                            @endif
                                        </small>
                                    </div>
                                    <span class="badge enhanced-badge bg-{{ ($announcement->status ?? 'draft') === 'active' ? 'success' : 'warning' }} ms-3">
                                        {{ ucfirst($announcement->status ?? 'draft') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="list-group-item enhanced-empty-state p-3 border-0 text-center">
                            <div class="text-muted">
                                <i class="fas fa-bullhorn fa-2x mb-2 opacity-50"></i>
                                <p class="mb-0">No announcements yet</p>
                                <small>Create your first announcement to get started</small>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer enhanced-card-footer bg-light text-center">
                    <a href="{{ route('principal.announcements.index') }}" class="btn btn-enhanced-outline-primary btn-sm">
                        View All Announcements
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Calendar -->
    <div class="col-lg-4 mb-4">
        <!-- Quick Actions -->
        <div class="card enhanced-quick-actions-card mb-4">
            <div class="card-header enhanced-card-header">
                <h5 class="mb-0 card-title-enhanced">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body enhanced-card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('principal.announcements.create') }}" class="btn btn-enhanced-outline-primary enhanced-action-btn">
                        <i class="fas fa-bullhorn me-2"></i>Create Announcement
                    </a>
                    <a href="{{ route('principal.teachers.index') }}" class="btn btn-enhanced-outline-success enhanced-action-btn">
                        <i class="fas fa-users me-2"></i>Manage Teachers
                    </a>
                    <a href="#" class="btn btn-enhanced-outline-warning enhanced-action-btn">
                        <i class="fas fa-calendar-alt me-2"></i>School Calendar
                    </a>
                </div>
            </div>
        </div>

        <!-- Upcoming School Events -->
        <div class="card enhanced-events-card">
            <div class="card-header enhanced-card-header">
                <h5 class="mb-0 card-title-enhanced">
                    <i class="fas fa-calendar-check me-2"></i>
                    Upcoming Events
                </h5>
            </div>
            <div class="card-body enhanced-card-body p-0">
                <div id="upcoming-events-container" class="enhanced-events-container">
                    @include('Principal._upcoming_events_list', ['upcomingEvents' => $upcomingEvents])
                </div>
                <div class="text-center py-3 enhanced-calendar-action">
                    <button type="button" class="btn btn-enhanced-primary btn-lg enhanced-calendar-btn" data-bs-toggle="modal" data-bs-target="#calendarModal">
                        <i class="fas fa-calendar-alt me-2"></i>View Full Calendar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="eventForm">
        <div class="modal-header">
          <h5 class="modal-title" id="eventModalLabel">Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="eventId">
          <div class="mb-3">
            <label for="eventTitle" class="form-label">Title</label>
            <input type="text" class="form-control" id="eventTitle" required>
          </div>
          <div class="mb-3">
            <label for="eventDescription" class="form-label">Description</label>
            <textarea class="form-control" id="eventDescription"></textarea>
          </div>
          <div class="mb-3">
            <label for="eventStart" class="form-label">Start</label>
            <input type="datetime-local" class="form-control" id="eventStart" required>
          </div>
          <div class="mb-3">
            <label for="eventEnd" class="form-label">End</label>
            <input type="datetime-local" class="form-control" id="eventEnd">
          </div>
          <div class="mb-3">
            <label for="eventColor" class="form-label">Color</label>
            <input type="color" class="form-control form-control-color" id="eventColor" value="#563d7c" title="Choose your color">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" id="deleteEventButton" style="display:none;">Delete</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="saveEventButton">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('styles')
@parent
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<style>
    /* Enhanced Color Scheme Variables */
    :root {
        --cnhs-primary-blue: #1E3A8A;
        --cnhs-secondary-blue: #3B82F6;
        --cnhs-accent-orange: #FF8C00;
        --cnhs-gold: #FCD34D;
        --cnhs-white: #FFFFFF;
        --cnhs-light-gray: #F8FAFC;
        --cnhs-medium-gray: #6B7280;
        --cnhs-dark-gray: #374151;
        --cnhs-success: #10B981;
        --cnhs-warning: #F59E0B;
        --cnhs-danger: #EF4444;
        --cnhs-info: #06B6D4;
        
        /* Enhanced gradients */
        --cnhs-gradient-primary: linear-gradient(135deg, var(--cnhs-primary-blue) 0%, var(--cnhs-secondary-blue) 100%);
        --cnhs-gradient-orange: linear-gradient(135deg, var(--cnhs-accent-orange) 0%, var(--cnhs-gold) 100%);
        --cnhs-gradient-success: linear-gradient(135deg, var(--cnhs-success) 0%, #059669 100%);
        --cnhs-gradient-warning: linear-gradient(135deg, var(--cnhs-warning) 0%, #D97706 100%);
        
        /* Enhanced shadows */
        --cnhs-shadow-sm: 0 2px 8px rgba(30, 58, 138, 0.08);
        --cnhs-shadow-md: 0 8px 25px rgba(30, 58, 138, 0.12);
        --cnhs-shadow-lg: 0 15px 35px rgba(30, 58, 138, 0.15);
        --cnhs-shadow-xl: 0 25px 50px rgba(30, 58, 138, 0.20);
    }

    /* Enhanced Stats Cards */
    .stats-card {
        background: linear-gradient(145deg, var(--cnhs-white), var(--cnhs-light-gray));
        border: 1px solid rgba(30, 58, 138, 0.08);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--cnhs-shadow-md);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--cnhs-gradient-primary);
        border-radius: 20px 20px 0 0;
    }

    .stats-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: var(--cnhs-shadow-xl);
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 800;
        background: var(--cnhs-gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.2;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }

    .stats-label {
        color: var(--cnhs-dark-gray);
        font-weight: 600;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .stats-icon {
        opacity: 0.8;
        font-size: 2.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .stats-card:hover .stats-icon {
        opacity: 1;
        transform: scale(1.1);
    }

    /* Enhanced School Header */
    .school-header {
        background: var(--cnhs-gradient-orange);
        border-radius: 24px;
        padding: 3rem;
        border: none;
        box-shadow: var(--cnhs-shadow-lg);
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        color: var(--cnhs-white);
    }

    .school-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        animation: gridMove 30s linear infinite;
    }

    @keyframes gridMove {
        0% { transform: translate(0, 0); }
        100% { transform: translate(20px, 20px); }
    }

    .school-header::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        background: var(--cnhs-gradient-primary);
        clip-path: polygon(100% 0, 100% 100%, 0 100%, 25% 0);
        opacity: 0.9;
        z-index: 1;
    }

    .school-header h1 {
        font-size: 2.2rem;
        margin-bottom: 1rem;
        font-weight: 800;
        color: var(--cnhs-white);
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
        position: relative;
        z-index: 2;
    }

    .school-header p {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        color: var(--cnhs-white);
        font-weight: 500;
        position: relative;
        z-index: 2;
    }

    .school-header small {
        position: relative;
        z-index: 2;
        font-weight: 400;
    }

    /* Enhanced Card Styles */
    .enhanced-announcements-card,
    .enhanced-quick-actions-card,
    .enhanced-events-card {
        background: linear-gradient(145deg, var(--cnhs-white), var(--cnhs-light-gray));
        border: none;
        border-radius: 24px;
        box-shadow: var(--cnhs-shadow-md);
        transition: all 0.4s ease;
        overflow: hidden;
        position: relative;
    }

    .enhanced-announcements-card:hover,
    .enhanced-quick-actions-card:hover,
    .enhanced-events-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--cnhs-shadow-lg);
    }

    .enhanced-card-header {
        background: var(--cnhs-gradient-primary);
        color: var(--cnhs-white);
        padding: 2rem;
        border-bottom: none;
        position: relative;
        overflow: hidden;
    }

    .enhanced-card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="50" r="1.5" fill="rgba(255,255,255,0.08)"/></svg>');
        background-size: 50px 50px;
        animation: patternMove 20s linear infinite;
    }

    @keyframes patternMove {
        0% { transform: translate(0, 0); }
        100% { transform: translate(50px, 50px); }
    }

    .card-title-enhanced {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        position: relative;
        z-index: 2;
    }

    .enhanced-card-body {
        padding: 2rem;
        background: var(--cnhs-white);
    }

    /* Enhanced List Items */
    .enhanced-list-item {
        transition: all 0.3s ease;
        border-radius: 12px;
        margin: 0.5rem;
        background: var(--cnhs-white);
        border: 1px solid rgba(30, 58, 138, 0.05);
    }

    .enhanced-list-item:hover {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.02), rgba(255, 140, 0, 0.02));
        transform: translateX(8px);
        box-shadow: var(--cnhs-shadow-sm);
    }

    .announcement-title-enhanced {
        color: var(--cnhs-primary-blue);
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 0.8rem;
    }

    .announcement-content-enhanced {
        color: var(--cnhs-dark-gray);
        font-size: 1rem;
        line-height: 1.6;
        font-weight: 500;
    }

    .announcement-meta-enhanced {
        color: var(--cnhs-medium-gray);
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Enhanced Badges */
    .enhanced-badge {
        padding: 0.6rem 1.2rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: var(--cnhs-shadow-sm);
    }

    /* Enhanced Buttons */
    .btn-enhanced-primary {
        background: var(--cnhs-gradient-primary);
        border: none;
        color: var(--cnhs-white);
        padding: 0.8rem 1.8rem;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: var(--cnhs-shadow-sm);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-enhanced-primary:hover {
        transform: translateY(-3px);
        box-shadow: var(--cnhs-shadow-md);
        color: var(--cnhs-white);
    }

    .btn-enhanced-outline-primary {
        color: var(--cnhs-primary-blue);
        border: 2px solid var(--cnhs-primary-blue);
        background: transparent;
        padding: 0.8rem 1.8rem;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-enhanced-outline-primary:hover {
        background: var(--cnhs-gradient-primary);
        border-color: transparent;
        color: var(--cnhs-white);
        transform: translateY(-3px);
        box-shadow: var(--cnhs-shadow-md);
    }

    .btn-enhanced-outline-success {
        color: var(--cnhs-success);
        border: 2px solid var(--cnhs-success);
        background: transparent;
        padding: 0.8rem 1.8rem;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-enhanced-outline-success:hover {
        background: var(--cnhs-gradient-success);
        border-color: transparent;
        color: var(--cnhs-white);
        transform: translateY(-3px);
        box-shadow: var(--cnhs-shadow-md);
    }

    .btn-enhanced-outline-warning {
        color: var(--cnhs-warning);
        border: 2px solid var(--cnhs-warning);
        background: transparent;
        padding: 0.8rem 1.8rem;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-enhanced-outline-warning:hover {
        background: var(--cnhs-gradient-warning);
        border-color: transparent;
        color: var(--cnhs-white);
        transform: translateY(-3px);
        box-shadow: var(--cnhs-shadow-md);
    }

    .enhanced-action-btn {
        padding: 1.2rem 2rem;
        font-size: 1rem;
        border-radius: 15px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .enhanced-action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .enhanced-action-btn:hover::before {
        left: 100%;
    }

    /* Enhanced Calendar Button */
    .enhanced-calendar-btn {
        background: var(--cnhs-gradient-orange);
        border: none;
        color: var(--cnhs-white);
        padding: 1.2rem 2.5rem;
        border-radius: 30px;
        font-weight: 700;
        transition: all 0.4s ease;
        box-shadow: var(--cnhs-shadow-md);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        position: relative;
        overflow: hidden;
    }

    .enhanced-calendar-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s ease;
    }

    .enhanced-calendar-btn:hover::before {
        left: 100%;
    }

    .enhanced-calendar-btn:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: var(--cnhs-shadow-xl);
        color: var(--cnhs-white);
    }

    /* Enhanced Card Footer */
    .enhanced-card-footer {
        background: linear-gradient(135deg, var(--cnhs-light-gray), #E2E8F0);
        border-top: 1px solid rgba(30, 58, 138, 0.1);
        padding: 1.5rem 2rem;
    }

    /* Enhanced Empty State */
    .enhanced-empty-state {
        padding: 3rem 2rem;
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.02), rgba(255, 140, 0, 0.02));
        border-radius: 15px;
        margin: 1rem;
    }

    /* Enhanced Events Container */
    .enhanced-events-container {
        max-height: 400px;
        overflow-y: auto;
    }

    .enhanced-events-container::-webkit-scrollbar {
        width: 6px;
    }

    .enhanced-events-container::-webkit-scrollbar-track {
        background: var(--cnhs-light-gray);
        border-radius: 3px;
    }

    .enhanced-events-container::-webkit-scrollbar-thumb {
        background: var(--cnhs-gradient-primary);
        border-radius: 3px;
    }

    .enhanced-events-container::-webkit-scrollbar-thumb:hover {
        background: var(--cnhs-primary-blue);
    }

    /* Enhanced Calendar Action */
    .enhanced-calendar-action {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.02), rgba(255, 140, 0, 0.02));
        border-top: 1px solid rgba(30, 58, 138, 0.1);
    }

    /* Enhanced Progress Bars */
    .progress {
        height: 6px;
        border-radius: 10px;
        background: rgba(30, 58, 138, 0.1);
        overflow: hidden;
    }

    .progress-bar {
        border-radius: 10px;
        background: var(--cnhs-gradient-primary);
        transition: width 0.6s ease;
    }

    .progress-bar.bg-success {
        background: var(--cnhs-gradient-success);
    }

    .progress-bar.bg-warning {
        background: var(--cnhs-gradient-warning);
    }

    .progress-bar.bg-info {
        background: linear-gradient(135deg, var(--cnhs-info), #0891B2);
    }

    /* Enhanced Calendar Styles */
    #school-calendar {
        width: 100%;
        max-width: 900px;
        height: 80vh !important;
        min-height: 500px;
        background: var(--cnhs-white);
        border-radius: 15px;
        box-shadow: var(--cnhs-shadow-md);
        margin: 0 auto;
        border: 1px solid rgba(30, 58, 138, 0.1);
        padding: 1rem;
    }

    .fc-toolbar-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--cnhs-primary-blue);
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
    }

    .fc-daygrid-event {
        background: transparent !important;
        color: var(--cnhs-dark-gray) !important;
        border: none !important;
        border-radius: 8px !important;
        font-weight: 500;
        padding: 2px 6px;
        margin-bottom: 2px;
        box-shadow: var(--cnhs-shadow-sm);
        transition: all 0.3s ease;
    }

    .fc-daygrid-event:hover {
        transform: scale(1.05);
        box-shadow: var(--cnhs-shadow-md);
    }

    .fc-event-title {
        font-size: 0.85rem;
        line-height: 1.3;
        background: var(--cnhs-gradient-primary) !important;
        color: var(--cnhs-white) !important;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 600;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }

    .fc .fc-button-primary {
        background: var(--cnhs-gradient-primary);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        color: var(--cnhs-white);
        padding: 0.6rem 1.2rem;
        transition: all 0.3s ease;
    }

    .fc .fc-button-primary:hover {
        background: var(--cnhs-primary-blue);
        transform: translateY(-2px);
        box-shadow: var(--cnhs-shadow-md);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .school-header {
            text-align: center;
            padding: 2rem;
        }

        .school-header h1 {
            font-size: 1.8rem;
        }

        .stats-card {
            margin-bottom: 1.5rem;
            padding: 1.5rem;
        }

        .stats-number {
            font-size: 2rem;
        }

        .enhanced-card-header,
        .enhanced-card-body {
            padding: 1.5rem;
        }

        .enhanced-action-btn {
            padding: 1rem 1.5rem;
        }

        .enhanced-calendar-btn {
            padding: 1rem 2rem;
            font-size: 0.9rem;
        }
    }

    /* Animation for page load */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stats-card,
    .enhanced-announcements-card,
    .enhanced-quick-actions-card,
    .enhanced-events-card {
        animation: fadeInUp 0.6s ease-out;
    }

    .stats-card:nth-child(1) { animation-delay: 0.1s; }
    .stats-card:nth-child(2) { animation-delay: 0.2s; }
    .stats-card:nth-child(3) { animation-delay: 0.3s; }
    .stats-card:nth-child(4) { animation-delay: 0.4s; }
</style>
@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
var calendar;
// Define modal and input variables in a scope accessible to FullCalendar callbacks
var eventModal;
var eventForm;
var deleteEventBtn;
var eventIdInput;
var eventTitleInput;
var eventDescriptionInput;
var eventStartInput;
var eventEndInput;
var eventColorInput;

function resetModal() {
    if (eventIdInput) eventIdInput.value = '';
    if (eventTitleInput) eventTitleInput.value = '';
    if (eventDescriptionInput) eventDescriptionInput.value = '';
    if (eventStartInput) eventStartInput.value = '';
    if (eventEndInput) eventEndInput.value = '';
    if (eventColorInput) eventColorInput.value = '#563d7c';
    if (deleteEventBtn) deleteEventBtn.style.display = 'none';
}

function initOrRefreshCalendar() {
    var calendarEl = document.getElementById('school-calendar');
    if (!calendar) {
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            titleFormat: { year: 'numeric', month: 'long' },
            themeSystem: 'standard',
            events: {
                url: '{{ route('principal.events.index') }}',
                method: 'GET',
                failure: function() {
                    console.error('Error fetching events!');
                    alert('There was an error while fetching events!');
                },
                eventDataTransform: function(eventData) {
                    if (eventData.color) {
                        eventData.backgroundColor = eventData.color;
                        eventData.borderColor = eventData.color;
                    }
                    return eventData;
                }
            },
            nowIndicator: true,
            selectable: true,
            editable: false,
            dateClick: function(info) {
                console.log('Date clicked:', info.dateStr);
                console.log('eventModal object:', eventModal);
                if (eventModal && typeof eventModal.show === 'function') {
                    resetModal();
                    if (eventStartInput) eventStartInput.value = info.dateStr + 'T08:00';
                    if (eventEndInput) eventEndInput.value = info.dateStr + 'T17:00';
                    eventModal.show();
                } else {
                    console.error('eventModal is not properly initialized or show method is missing.', eventModal);
                    alert('Calendar is not ready to create events. Please check console for details.');
                }
            },
            eventClick: function(info) {
                console.log('Event clicked:', info.event.id);
                console.log('eventModal object:', eventModal);
                if (eventModal && typeof eventModal.show === 'function') {
                    var event = info.event;
                    resetModal();
                    if (eventIdInput) eventIdInput.value = event.id;
                    if (eventTitleInput) eventTitleInput.value = event.title;
                    if (eventDescriptionInput) eventDescriptionInput.value = event.extendedProps.description || '';
                    if (eventStartInput) eventStartInput.value = event.start ? event.start.toISOString().slice(0,16) : '';
                    if (eventEndInput) eventEndInput.value = event.end ? event.end.toISOString().slice(0,16) : '';
              
                    if (deleteEventBtn) deleteEventBtn.style.display = 'inline-block';
                    eventModal.show();
                } else {
                    console.error('eventModal is not properly initialized or show method is missing.', eventModal);
                    alert('Calendar is not ready to edit events. Please check console for details.');
                }
            }
        });
        calendar.render();
    } else {
        calendar.updateSize();
        calendar.render();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap modal objects and input references once DOM is loaded
    eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
    eventForm = document.getElementById('eventForm');
    deleteEventBtn = document.getElementById('deleteEventButton');
    eventIdInput = document.getElementById('eventId');
    eventTitleInput = document.getElementById('eventTitle');
    eventDescriptionInput = document.getElementById('eventDescription');
    eventStartInput = document.getElementById('eventStart');
    eventEndInput = document.getElementById('eventEnd');
    eventColorInput = document.getElementById('eventColor');

    var calendarModal = document.getElementById('calendarModal'); // This is the main FullCalendar display modal

    if (calendarModal) {
        calendarModal.addEventListener('shown.bs.modal', function () {
            initOrRefreshCalendar();
        });
    }

    if (eventForm) {
        eventForm.onsubmit = function(e) {
            e.preventDefault();
            var id = eventIdInput.value;
            var data = {
                title: eventTitleInput.value,
                description: eventDescriptionInput.value,
                start: eventStartInput.value,
                end: eventEndInput.value,
                color: eventColorInput.value
            };
            var url, method;
            if (id) {
                url = `/principal/events/${id}`;
                method = 'PUT';
            } else {
                url = `{{ route('principal.events.store') }}`;
                method = 'POST';
            }
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    // If response is not OK (e.g., 4xx or 5xx status)
                    return response.json().then(err => {
                        throw new Error(err.message || 'Server error occurred.');
                    });
                }
                return response.json();
            })
            .then(event => {
                eventModal.hide();
                calendar.refetchEvents();
                // Refresh the Upcoming Events list after saving an event
                fetch('{{ route('principal.dashboard.upcoming_events_html') }}')
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('upcoming-events-container').innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error refreshing upcoming events after save:', error);
                    });
            })
            .catch(error => {
                console.error('Error saving event:', error);
                alert('Error saving event: ' + error.message || 'An unknown error occurred.');
            });
        };
    }

    if (deleteEventBtn) {
        deleteEventBtn.onclick = function() {
            var id = eventIdInput.value;
            if (!id) return;
            if (!confirm('Are you sure you want to delete this event?')) return;
            fetch(`/principal/events/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Server error occurred during deletion.');
                    });
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    eventModal.hide();
                    calendar.refetchEvents();
                    // Refresh the Upcoming Events list after deleting an event
                    fetch('{{ route('principal.dashboard.upcoming_events_html') }}')
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById('upcoming-events-container').innerHTML = html;
                        })
                        .catch(error => {
                            console.error('Error refreshing upcoming events after delete:', error);
                        });
                } else {
                    alert('Error deleting event: ' + (result.message || 'Unknown error.'));
                }
            })
            .catch(error => {
                console.error('Error deleting event:', error);
                alert('Error deleting event: ' + error.message || 'An unknown error occurred.');
            });
        };
    }

    // AJAX setup for CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content')); // Log CSRF token

    // Handle Save Event button click
    document.getElementById('saveEventButton').addEventListener('click', function() {
        // ... existing code ...
    });
});

// FullCalendar Initialization for Modal
var calendarModalEl = document.getElementById('school-calendar');
var calendarModal = new FullCalendar.Calendar(calendarModalEl, {
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    initialView: 'dayGridMonth',
    events: '/principal/events',
    editable: true,
    selectable: true,
    dayMaxEvents: true,
    eventDidMount: function(info) {
        // Optional: Add tooltip or custom styling
    },
    eventClick: function(info) {
        var event = info.event;

        // Populate modal for viewing (read-only)
        document.getElementById('displayEventTitle').innerText = event.title;
        document.getElementById('displayEventDescription').innerText = event.extendedProps.description || 'N/A';
        document.getElementById('displayEventStart').innerText = moment(event.start).format('YYYY-MM-DD HH:mm');
        document.getElementById('displayEventEnd').innerText = event.end ? moment(event.end).format('YYYY-MM-DD HH:mm') : 'N/A';
        document.getElementById('displayEventColor').style.backgroundColor = event.backgroundColor;

        $('#eventModal').modal('show');
    }
});

// Render the calendar when the modal is shown
$('#calendarModal').on('shown.bs.modal', function() {
    calendarModal.render();
});
</script>
@endsection