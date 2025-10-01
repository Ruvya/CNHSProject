@extends('layouts.student')

@section('title', 'Dashboard')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
 
    /* Modern Dashboard Styles - Clean White Background */
    .main-content {
        padding: 2rem !important;
        background: #ffffff !important;
        position: relative;
        overflow-x: hidden;
        margin: 0 auto !important;
        max-width: 1200px !important;
        width: 90% !important;
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

    /* Welcome Section - Orange to Blue Diagonal Banner */
    .welcome-section {
        background: linear-gradient(135deg, #ffa62b 0%, #ff7b2c 100%);
        border-radius: 20px;
        padding: 1.75rem 2rem;
        border: none;
        box-shadow: 0 10px 24px rgba(16, 24, 40, 0.08);
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
        color: #ffffff;
    }

    /* Blue Right Panel with Diagonal Edge */
    .welcome-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 65%;
        background: linear-gradient(135deg, #3961e7 0%, #2f50d1 100%);
        clip-path: polygon(22% 0, 100% 0, 100% 100%, 0 100%);
        opacity: 1;
        z-index: 1;
    }

 

    .welcome-text h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 0.25rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
   
    .welcome-text h1 span#greeting {
        opacity: 0.95;
        color: #ffffff !important;
        font-size: 40px;
    }
   
  

    /* Quick Stats */
    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 2;
    }

    .stat-card {
        background: #ffffff;
        padding: 1rem;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.1);
        border: 1px solid rgba(37, 99, 235, 0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border-radius: 12px 12px 0 0;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    }

    .stat-card i {
        font-size: 1.75rem;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        padding: 0.75rem;
        background-color: rgba(37, 99, 235, 0.1);
        border-radius: 10px;
        width: 32px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-info h3 {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-info p {
        font-size: 1.1rem;
        font-weight: 600;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
    }

    /* Dashboard Grid */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
        position: relative;
        z-index: 2;
    }

    .grid-item {
        background: #ffffff;
        padding: 1.25rem;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.1);
        border: 1px solid rgba(37, 99, 235, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .grid-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    }

    .grid-item h2 {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .grid-item h2 i {
        font-size: 1rem;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* List Items */
    .subjects-list, .announcements-list, .activities-list, .grades-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        max-height: 280px;
        overflow-y: auto;
    }

    .subject-item, .announcement-item, .activity-item, .grade-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid rgba(37, 99, 235, 0.08);
        transition: all 0.3s ease;
    }

    .subject-item:hover, .announcement-item:hover, .activity-item:hover, .grade-item:hover {
        background: #f1f5f9;
        transform: translateX(4px);
        border-color: rgba(37, 99, 235, 0.15);
    }

    .item-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
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
        font-size: 0.9rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.15rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .item-subtitle {
        font-size: 0.8rem;
        color: #6b7280;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .item-meta {
        font-size: 0.7rem;
        color: #9ca3af;
        text-align: right;
        flex-shrink: 0;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 1.5rem;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 2rem;
        margin-bottom: 0.75rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 1rem;
        margin-bottom: 0.25rem;
        color: #374151;
    }

    .empty-state p {
        font-size: 0.85rem;
        opacity: 0.8;
    }

    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 0.75rem;
    }

    .quick-action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0.75rem;
        background: rgba(37, 99, 235, 0.1);
        border-radius: 10px;
        text-decoration: none;
        color: #2563eb;
        transition: all 0.3s ease;
    }

    .quick-action-btn i {
        font-size: 1.25rem;
        margin-bottom: 0.35rem;
    }

    .quick-action-btn span {
        font-size: 0.8rem;
        font-weight: 600;
        text-align: center;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .main-content {
            width: 95% !important;
            padding: 1rem !important;
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .quick-stats {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .welcome-text h1 {
            font-size: 1.75rem;
        }

        .stat-card {
            padding: 1rem;
        }

        .stat-card i {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }

        .grid-item {
            padding: 1rem;
        }

        .quick-stats {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.75rem;
        }
    }

    @media (max-width: 480px) {
        .main-content {
            padding: 0.5rem !important;
        }

        .welcome-section {
            padding: 1rem;
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
</style>
@endsection

@section('content')


<!-- Welcome Section -->
<div class="welcome-section d-flex align-items-center flex-wrap" style="position: relative; min-height: 140px;">
    <div class="welcome-text flex-grow-1" style="z-index:2;">
        <h1 class="mb-1" style="font-size:2.2rem; font-weight:700; color:#fff; text-shadow:0 2px 8px rgba(0,0,0,0.10);">
            <span id="greeting" style="font-size:1.3em; font-weight:700; color:#fff;">Good morning</span>
            <span style="font-weight:700; color:#fff;">{{ optional($student)->first_name . ' ' . optional($student)->last_name ?? 'Guest' }}!</span>
        </h1>
        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
            <span class="text-white-50 ms-2" id="currentDateTime" style="font-size:0.98rem;"></span>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="quick-stats">
    <div class="stat-card">
        <i class="fas fa-graduation-cap"></i>
        <div class="stat-info">
            <h3>Current Grade</h3>
            <p>{{ optional($student)->grade_level ?? 'N/A' }}</p>
        </div>
    </div>
    <div class="stat-card">
        <i class="fas fa-book"></i>
        <div class="stat-info">
            <h3>Enrolled Subjects</h3>
            <p>{{ $totalSubjects ?? 0 }} Active</p>
        </div>
    </div>
    <div class="stat-card">
        <i class="fas fa-chart-line"></i>
        <div class="stat-info">
            <h3>Average Grade</h3>
            <p>{{ isset($gradeStats['general_average']) ? number_format($gradeStats['general_average'], 1) : 'N/A' }}</p>
        </div>
    </div>
    <div class="stat-card">
        <i class="fas fa-star"></i>
        <div class="stat-info">
            <h3>Highest Grade</h3>
            <p>{{ isset($gradeStats['highest']) ? number_format($gradeStats['highest'], 1) : 'N/A' }}</p>
        </div>
    </div>
</div>

<!-- Dashboard Grid -->
<div class="dashboard-grid">
    <!-- Enrolled Subjects -->
    <div class="grid-item">
        <h2><i class="fas fa-book"></i> My Subjects</h2>
        @if(isset($enrolledSubjects) && $enrolledSubjects->count() > 0)
            <div class="subjects-list">
                @foreach($enrolledSubjects->take(5) as $subject)
                <div class="subject-item">
                    <div class="item-icon subject-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="item-content">
                        <div class="item-title">{{ $subject->name ?? 'Untitled Subject' }}</div>
                        <div class="item-subtitle">{{ optional($subject->current_teacher)->name ?? 'No teacher assigned' }}</div>
                    </div>
                    <div class="item-meta">
                        {{ $subject->track ?? 'No Track' }} - {{ $subject->strand ?? 'No Strand' }}
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
        @if(isset($recentAnnouncements) && $recentAnnouncements->count() > 0)
            <div class="announcements-list">
                @foreach($recentAnnouncements as $announcement)
                <div class="announcement-item">
                    <div class="item-icon announcement-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div class="item-content">
                        <div class="item-title">{{ $announcement->title ?? 'Untitled Announcement' }}</div>
                        <div class="item-subtitle">{{ Str::limit($announcement->content ?? 'No content available', 60) }}</div>
                    </div>
                    <div class="item-meta">
                        {{ optional($announcement->created_at)->diffForHumans() ?? 'Recently' }}
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
    // Pass PHP data to JavaScript with null checks
    const studentGrades = @json(optional($grades)->pluck('final_grade')->filter()->values() ?? []);
    const subjectNames = @json(optional($grades)->pluck('subject.name')->values() ?? []);

    // Update date and time
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true };
        const formattedDateTime = now.toLocaleDateString('en-US', options);
        document.getElementById('currentDateTime').innerText = formattedDateTime;
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