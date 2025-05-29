@extends('layouts.student')

@section('title', 'Dashboard')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    /* Modern Dashboard Styles - Clean White Background */
    .main-content {
       
        padding: 2rem !important;
        background: #ffffff !important;
        min-height: calc(100vh - 80px) !important;
        position: relative;
        overflow-x: hidden;
        margin-left: 250px !important;
    }

    /* Subtle background pattern */
    .main-content::before {
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

    /* Welcome Section */
    .welcome-section {
        margin-top: 3%;
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

    .welcome-text h1 {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .date-time {
        font-size: 1.1rem;
        color: #6b7280;
        font-weight: 500;
    }

    /* Quick Stats */
    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
        position: relative;
        z-index: 2;
    }

    .stat-card {
        background: #ffffff;
        padding: 2rem;
        border-radius: 20px;
        box-shadow:
            0 4px 20px rgba(37, 99, 235, 0.08),
            0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(37, 99, 235, 0.1);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border-radius: 20px 20px 0 0;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow:
            0 8px 30px rgba(37, 99, 235, 0.15),
            0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .stat-card i {
        font-size: 2.5rem;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        padding: 1rem;
        background-color: rgba(37, 99, 235, 0.1);
        border-radius: 15px;
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-info h3 {
        font-size: 0.95rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-info p {
        font-size: 1.8rem;
        font-weight: 700;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
    }

    /* Dashboard Grid */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
        position: relative;
        z-index: 2;
    }

    .grid-item {
        background: #ffffff;
        padding: 2rem;
        border-radius: 20px;
        box-shadow:
            0 4px 20px rgba(37, 99, 235, 0.08),
            0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(37, 99, 235, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .grid-item:hover {
        transform: translateY(-5px);
        box-shadow:
            0 8px 30px rgba(37, 99, 235, 0.15),
            0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .grid-item h2 {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .grid-item h2 i {
        font-size: 1.2rem;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Specific Component Styles */
    .subjects-list, .announcements-list, .activities-list, .grades-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        max-height: 300px;
        overflow-y: auto;
    }

    .subject-item, .announcement-item, .activity-item, .grade-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 15px;
        border: 1px solid rgba(37, 99, 235, 0.08);
        transition: all 0.3s ease;
    }

    .subject-item:hover, .announcement-item:hover, .activity-item:hover, .grade-item:hover {
        background: #f1f5f9;
        transform: translateX(5px);
        border-color: rgba(37, 99, 235, 0.15);
    }

    .item-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
        flex-shrink: 0;
    }

    .subject-icon { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
    .announcement-icon { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .activity-icon { background: linear-gradient(135deg, #60a5fa, #3b82f6); }
    .grade-icon { background: linear-gradient(135deg, #1d4ed8, #1e40af); }

    .item-content {
        flex: 1;
        min-width: 0;
    }

    .item-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .item-subtitle {
        font-size: 0.85rem;
        color: #6b7280;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .item-meta {
        font-size: 0.75rem;
        color: #9ca3af;
        text-align: right;
        flex-shrink: 0;
    }

    /* Chart Container */
    .chart-container {
        height: 300px;
        position: relative;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        color: #374151;
    }

    .empty-state p {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .main-content {
            margin-left: 0 !important;
            padding: 1rem !important;
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .quick-stats {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .welcome-text h1 {
            font-size: 2rem;
        }

        .stat-card {
            padding: 1.5rem;
        }

        .stat-card i {
            width: 60px;
            height: 60px;
            font-size: 2rem;
        }

        .grid-item {
            padding: 1.5rem;
        }

        .quick-stats {
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }
    }

    @media (max-width: 480px) {
        .main-content {
            padding: 0.5rem !important;
        }

        .welcome-section {
            padding: 1.5rem;
        }

        .welcome-text h1 {
            font-size: 1.5rem;
        }

        .quick-stats {
            grid-template-columns: 1fr;
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Animations */
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

    .stat-card, .grid-item {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }
</style>
@endsection

@section('content')
<!-- Welcome Section -->
<div class="welcome-section">
    <div class="welcome-text">
        <h1><span id="greeting">Good morning</span>, {{ $student ? $student->first_name . ' ' . $student->last_name : 'Guest' }}!</h1>
        <p class="date-time" id="currentDateTime">Loading...</p>
    </div>
</div>

<!-- Quick Stats -->
<div class="quick-stats">
    <div class="stat-card">
        <i class="fas fa-graduation-cap"></i>
        <div class="stat-info">
            <h3>Current Grade</h3>
            <p>{{ $student ? $student->grade_level : 'N/A' }}</p>
        </div>
    </div>
    <div class="stat-card">
        <i class="fas fa-book"></i>
        <div class="stat-info">
            <h3>Enrolled Subjects</h3>
            <p>{{ $totalSubjects }} Active</p>
        </div>
    </div>
    <div class="stat-card">
        <i class="fas fa-chart-line"></i>
        <div class="stat-info">
            <h3>Average Grade</h3>
            <p>{{ $gradeStats['general_average'] ? number_format($gradeStats['general_average'], 1) : 'N/A' }}</p>
        </div>
    </div>
    <div class="stat-card">
        <i class="fas fa-star"></i>
        <div class="stat-info">
            <h3>Highest Grade</h3>
            <p>{{ $gradeStats['highest'] ? number_format($gradeStats['highest'], 1) : 'N/A' }}</p>
        </div>
    </div>
</div>

<!-- Dashboard Grid -->
<div class="dashboard-grid">
    <!-- Enrolled Subjects -->
    <div class="grid-item">
        <h2><i class="fas fa-book"></i> My Subjects</h2>
        @if($enrolledSubjects->count() > 0)
            <div class="subjects-list">
                @foreach($enrolledSubjects->take(5) as $subject)
                <div class="subject-item">
                    <div class="item-icon subject-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="item-content">
                        <div class="item-title">{{ $subject->name }}</div>
                        <div class="item-subtitle">{{ $subject->teacher ? $subject->teacher->name : 'No teacher assigned' }}</div>
                    </div>
                    <div class="item-meta">
                        {{ $subject->track }} - {{ $subject->strand }}
                    </div>
                </div>
                @endforeach
            </div>
            @if($enrolledSubjects->count() > 5)
                <div style="text-align: center; margin-top: 1rem;">
                    <a href="{{ route('student.subjects') }}" style="color: #2563eb; text-decoration: none; font-weight: 600;">
                        View All Subjects ({{ $enrolledSubjects->count() }})
                    </a>
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-book"></i>
                <h3>No Subjects Enrolled</h3>
                <p>You haven't been enrolled in any subjects yet. Contact your registrar for assistance.</p>
            </div>
        @endif
    </div>

    <!-- Recent Announcements -->
    <div class="grid-item">
        <h2><i class="fas fa-bullhorn"></i> Recent Announcements</h2>
        @if($recentAnnouncements->count() > 0)
            <div class="announcements-list">
                @foreach($recentAnnouncements as $announcement)
                <div class="announcement-item">
                    <div class="item-icon announcement-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div class="item-content">
                        <div class="item-title">{{ $announcement->title }}</div>
                        <div class="item-subtitle">{{ Str::limit($announcement->content, 60) }}</div>
                    </div>
                    <div class="item-meta">
                        {{ $announcement->created_at->diffForHumans() }}
                    </div>
                </div>
                @endforeach
            </div>
            <div style="text-align: center; margin-top: 1rem;">
                <a href="{{ route('student.announcements') }}" style="color: #2563eb; text-decoration: none; font-weight: 600;">
                    View All Announcements
                </a>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-bullhorn"></i>
                <h3>No Announcements</h3>
                <p>No recent announcements available. Check back later for updates.</p>
            </div>
        @endif
    </div>




    <!-- Quick Actions -->
    <div class="grid-item">
        <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 1rem;">
            <a href="{{ route('student.subjects') }}" style="display: flex; flex-direction: column; align-items: center; padding: 1rem; background: rgba(37, 99, 235, 0.1); border-radius: 15px; text-decoration: none; color: #2563eb; transition: all 0.3s ease;">
                <i class="fas fa-book" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                <span style="font-size: 0.9rem; font-weight: 600;">My Subjects</span>
            </a>
            <a href="{{ route('student.grades') }}" style="display: flex; flex-direction: column; align-items: center; padding: 1rem; background: rgba(37, 99, 235, 0.1); border-radius: 15px; text-decoration: none; color: #2563eb; transition: all 0.3s ease;">
                <i class="fas fa-star" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                <span style="font-size: 0.9rem; font-weight: 600;">View Grades</span>
            </a>
            <a href="{{ route('student.profile') }}" style="display: flex; flex-direction: column; align-items: center; padding: 1rem; background: rgba(37, 99, 235, 0.1); border-radius: 15px; text-decoration: none; color: #2563eb; transition: all 0.3s ease;">
                <i class="fas fa-user" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                <span style="font-size: 0.9rem; font-weight: 600;">My Profile</span>
            </a>
            <a href="{{ route('student.announcements') }}" style="display: flex; flex-direction: column; align-items: center; padding: 1rem; background: rgba(37, 99, 235, 0.1); border-radius: 15px; text-decoration: none; color: #2563eb; transition: all 0.3s ease;">
                <i class="fas fa-bullhorn" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                <span style="font-size: 0.9rem; font-weight: 600;">Announcements</span>
            </a>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Pass PHP data to JavaScript
    const studentGrades = @json($grades->pluck('final_grade')->filter()->values() ?? []);
    const subjectNames = @json($grades->pluck('subject.name')->values() ?? []);

    // Update date and time
    function updateDateTime() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        document.getElementById('currentDateTime').textContent = now.toLocaleDateString('en-US', options);
    }

    // Update greeting based on time
    function updateGreeting() {
        const hour = new Date().getHours();
        let greeting = 'Good morning';
        if (hour >= 12 && hour < 18) {
            greeting = 'Good afternoon';
        } else if (hour >= 18) {
            greeting = 'Good evening';
        }
        document.getElementById('greeting').textContent = greeting;
    }

    // Initialize Chart.js for grades
    function initializeChart() {
        const ctx = document.getElementById('gradesChart');
        if (!ctx || studentGrades.length === 0) return;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: subjectNames.slice(0, 6),
                datasets: [{
                    label: 'Grades',
                    data: studentGrades.slice(0, 6),
                    backgroundColor: [
                        'rgba(37, 99, 235, 0.8)',
                        'rgba(29, 78, 216, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(96, 165, 250, 0.8)',
                        'rgba(147, 197, 253, 0.8)',
                        'rgba(191, 219, 254, 0.8)'
                    ],
                    borderColor: [
                        '#2563eb',
                        '#1d4ed8',
                        '#3b82f6',
                        '#60a5fa',
                        '#93c5fd',
                        '#bfdbfe'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    // Add hover effects to quick action buttons
    function addHoverEffects() {
        const actionButtons = document.querySelectorAll('[href*="student."]');
        actionButtons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px)';
                this.style.boxShadow = '0 8px 25px rgba(37, 99, 235, 0.3)';
            });

            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    }

    // Initialize everything when page loads
    document.addEventListener('DOMContentLoaded', function() {
        updateDateTime();
        updateGreeting();
        initializeChart();
        addHoverEffects();

        // Update time every minute
        setInterval(updateDateTime, 60000);

        // Add staggered animation to cards
        const cards = document.querySelectorAll('.stat-card, .grid-item');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';

            setTimeout(() => {
                card.style.transition = 'all 0.6s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endsection