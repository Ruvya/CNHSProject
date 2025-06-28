@extends('Principal.layouts.app')

@section('title', 'Academics')

@section('content')
    <!-- Hero Section -->
    <div class="hero-section-small d-flex align-items-center mb-5" style="background: var(--cnhs-primary-blue); min-height: 320px; color: white;">
        <div class="container">
            <div class="hero-content text-center" data-aos="fade-up">
                <h1 class="hero-title mb-4" style="color: white; font-size: 3.2rem; font-weight: 800;"><i class="fas fa-graduation-cap me-2"></i>Academic Programs</h1>
                <p class="hero-subtitle" style="color: #e0e7ff; font-size: 1.5rem;">Excellence in Education</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Programs Section -->
        <section class="py-5">
            <div class="container">
                <h2 class="section-title text-center mb-5">🎓 Our Academic Tracks</h2>
                <div class="row">
                    <!-- STEM -->
                    <div class="col-md-4 mb-4" data-aos="fade-up">
                        <div class="academic-card stem-card h-100">
                            <div class="track-icon stem-icon mb-3"><i class="fas fa-atom"></i></div>
                            <h3 class="card-title text-primary stem-title">STEM</h3>
                            <div class="track-tagline">Innovate & Discover</div>
                            <p class="card-text">Science, Technology, Engineering, and Mathematics track prepares students for careers in:</p>
                            <ul class="list-unstyled track-list">
                                <li><i class="fas fa-check"></i>Engineering</li>
                                <li><i class="fas fa-check"></i>Medicine</li>
                                <li><i class="fas fa-check"></i>Architecture</li>
                                <li><i class="fas fa-check"></i>Information Technology</li>
                            </ul>
                            <div class="track-badge stem-badge">Science & Tech</div>
                        </div>
                    </div>

                    <!-- HUMSS -->
                    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="academic-card humss-card h-100">
                            <div class="track-icon humss-icon mb-3"><i class="fas fa-users"></i></div>
                            <h3 class="card-title text-primary humss-title">HUMSS</h3>
                            <div class="track-tagline">Lead & Inspire</div>
                            <p class="card-text">Humanities and Social Sciences track focuses on:</p>
                            <ul class="list-unstyled track-list">
                                <li><i class="fas fa-check"></i>Education</li>
                                <li><i class="fas fa-check"></i>Law</li>
                                <li><i class="fas fa-check"></i>Communication Arts</li>
                                <li><i class="fas fa-check"></i>Social Sciences</li>
                            </ul>
                            <div class="track-badge humss-badge">Humanities</div>
                        </div>
                    </div>

                    <!-- ABM -->
                    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="academic-card abm-card h-100">
                            <div class="track-icon abm-icon mb-3"><i class="fas fa-chart-line"></i></div>
                            <h3 class="card-title text-primary abm-title">ABM</h3>
                            <div class="track-tagline">Build & Succeed</div>
                            <p class="card-text">Accountancy, Business and Management track prepares for:</p>
                            <ul class="list-unstyled track-list">
                                <li><i class="fas fa-check"></i>Business Administration</li>
                                <li><i class="fas fa-check"></i>Accountancy</li>
                                <li><i class="fas fa-check"></i>Economics</li>
                                <li><i class="fas fa-check"></i>Entrepreneurship</li>
                            </ul>
                            <div class="track-badge abm-badge">Business</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Facilities Section -->
        <section class="bg-light py-5 facilities-section">
            <div class="container">
                <h2 class="section-title text-center mb-5">🏫 Our Facilities</h2>
                <div class="row">
                    <div class="col-md-6 mb-4" data-aos="fade-right">
                        <div class="facility-card library-card border-0 shadow">
                            <div class="facility-image-wrapper"><span class="facility-icon"><i class="fas fa-book"></i></span></div>
                            <h4 class="facility-title">Modern Library</h4>
                            <div class="facility-tagline">Read. Learn. Grow.</div>
                            <p class="text-muted">Our library is equipped with the latest resources and digital facilities.</p>
                            <div class="facility-features">
                                <span class="feature-tag">📚 Digital Resources</span>
                                <span class="feature-tag">💻 Study Areas</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4" data-aos="fade-left">
                        <div class="facility-card laboratory-card border-0 shadow">
                            <div class="facility-image-wrapper"><span class="facility-icon"><i class="fas fa-flask"></i></span></div>
                            <h4 class="facility-title">Science Laboratories</h4>
                            <div class="facility-tagline">Experiment. Explore. Excel.</div>
                            <p class="text-muted">State-of-the-art laboratories for hands-on learning experience.</p>
                            <div class="facility-features">
                                <span class="feature-tag">🔬 Modern Equipment</span>
                                <span class="feature-tag">🧪 Safe Environment</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Curriculum Overview Section -->
        <section class="py-5">
            <div class="container">
                <h2 class="section-title text-center mb-5" data-aos="fade-up">📝 Curriculum Overview</h2>
                <div class="row">
                    <div class="col-lg-6 mb-4" data-aos="fade-right">
                        <div class="curriculum-card">
                            <h4 class="mb-4 curriculum-header"><span class="curriculum-icon"><i class="fas fa-book"></i></span> Core Subjects</h4>
                            <div class="curriculum-item mb-3">
                                <div class="subject-title"><span class="subject-icon"><i class="fas fa-language"></i></span>Languages</div>
                                <div class="subject-tagline">Unlock Communication</div>
                                <p class="text-muted">English, Filipino, Literature</p>
                                <div class="progress-bar-wrapper"><div class="progress-bar languages-progress"></div></div>
                            </div>
                            <div class="curriculum-item mb-3">
                                <div class="subject-title"><span class="subject-icon"><i class="fas fa-calculator"></i></span>Mathematics</div>
                                <div class="subject-tagline">Numbers & Logic</div>
                                <p class="text-muted">General Mathematics, Statistics, Pre-Calculus</p>
                                <div class="progress-bar-wrapper"><div class="progress-bar math-progress"></div></div>
                            </div>
                            <div class="curriculum-item mb-3">
                                <div class="subject-title"><span class="subject-icon"><i class="fas fa-atom"></i></span>Sciences</div>
                                <div class="subject-tagline">Explore the World</div>
                                <p class="text-muted">Earth Science, Biology, Chemistry, Physics</p>
                                <div class="progress-bar-wrapper"><div class="progress-bar science-progress"></div></div>
                            </div>
                            <div class="curriculum-item">
                                <div class="subject-title"><span class="subject-icon"><i class="fas fa-globe"></i></span>Social Sciences</div>
                                <div class="subject-tagline">Society & Change</div>
                                <p class="text-muted">History, Economics, Contemporary Issues</p>
                                <div class="progress-bar-wrapper"><div class="progress-bar social-progress"></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4" data-aos="fade-left">
                        <div class="curriculum-card">
                            <h4 class="mb-4 curriculum-header"><span class="curriculum-icon"><i class="fas fa-star"></i></span> Special Programs</h4>
                            <div class="curriculum-item mb-3">
                                <div class="subject-title"><span class="subject-icon"><i class="fas fa-flask"></i></span>Research Program</div>
                                <div class="subject-tagline">Discover & Create</div>
                                <p class="text-muted">Practical Research, Capstone Projects</p>
                                <div class="progress-bar-wrapper"><div class="progress-bar research-progress"></div></div>
                            </div>
                            <div class="curriculum-item mb-3">
                                <div class="subject-title"><span class="subject-icon"><i class="fas fa-laptop-code"></i></span>Technology Integration</div>
                                <div class="subject-tagline">Digital Future</div>
                                <p class="text-muted">Computer Programming, Digital Arts</p>
                                <div class="progress-bar-wrapper"><div class="progress-bar tech-progress"></div></div>
                            </div>
                            <div class="curriculum-item mb-3">
                                <div class="subject-title"><span class="subject-icon"><i class="fas fa-futbol"></i></span>Arts and Sports</div>
                                <div class="subject-tagline">Express & Excel</div>
                                <p class="text-muted">Physical Education, Music, Visual Arts</p>
                                <div class="progress-bar-wrapper"><div class="progress-bar arts-progress"></div></div>
                            </div>
                            <div class="curriculum-item">
                                <div class="subject-title"><span class="subject-icon"><i class="fas fa-hands-helping"></i></span>Life Skills</div>
                                <div class="subject-tagline">Skills for Life</div>
                                <p class="text-muted">Values Education, Career Guidance</p>
                                <div class="progress-bar-wrapper"><div class="progress-bar life-progress"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Academic Calendar Section -->
        <section class="bg-light py-5 calendar-section">
            <div class="container">
                <h2 class="section-title text-center mb-5" data-aos="fade-up">🗓️ Academic Calendar</h2>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="calendar-card" data-aos="fade-up">
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-hover enhanced-table">
                                        <thead>
                                            <tr>
                                                <th><span class="calendar-header-icon"><i class="fas fa-clipboard-list"></i></span>Event</th>
                                                <th><span class="calendar-header-icon"><i class="fas fa-calendar-alt"></i></span>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="table-row">
                                                <td><span class="event-icon"><i class="fas fa-flag"></i></span>First Semester Begins</td>
                                                <td><span class="date-badge">August 1, 2024</span></td>
                                            </tr>
                                            <tr class="table-row">
                                                <td><span class="event-icon"><i class="fas fa-file-alt"></i></span>Midterm Examinations</td>
                                                <td><span class="date-badge">October 15-19, 2024</span></td>
                                            </tr>
                                            <tr class="table-row">
                                                <td><span class="event-icon"><i class="fas fa-gift"></i></span>Christmas Break</td>
                                                <td><span class="date-badge">December 20, 2024 - January 3, 2025</span></td>
                                            </tr>
                                            <tr class="table-row">
                                                <td><span class="event-icon"><i class="fas fa-flag-checkered"></i></span>Second Semester Begins</td>
                                                <td><span class="date-badge">January 6, 2025</span></td>
                                            </tr>
                                            <tr class="table-row">
                                                <td><span class="event-icon"><i class="fas fa-file-alt"></i></span>Final Examinations</td>
                                                <td><span class="date-badge">March 24-28, 2025</span></td>
                                            </tr>
                                            <tr class="table-row">
                                                <td><span class="event-icon"><i class="fas fa-award"></i></span>Graduation Ceremony</td>
                                                <td><span class="date-badge graduation-date">April 15, 2025</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
        --cnhs-medium-gray: #6B7280;
        --cnhs-dark-gray: #374151;
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
        font-weight: 800;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.4);
        animation: fadeInUp 1.2s ease-out;
        letter-spacing: -0.02em;
    }

    .hero-subtitle {
        color: var(--cnhs-white);
        font-size: 1.5rem;
        opacity: 0.95;
        font-weight: 400;
        animation: fadeInUp 1.2s ease-out 0.3s both;
        letter-spacing: 0.02em;
    }

    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .section-title {
        color: var(--cnhs-primary-blue);
        background: none;
        -webkit-background-clip: initial;
        -webkit-text-fill-color: initial;
        background-clip: initial;
        font-size: 2.6rem;
        font-weight: 700;
        margin-bottom: 2rem;
        position: relative;
        letter-spacing: -0.02em;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: var(--cnhs-accent-orange);
        border-radius: 2px;
    }

    .academic-card, .facility-card, .curriculum-card, .calendar-card {
        background: var(--cnhs-white);
    }

    .card-decoration, .stem-decoration, .humss-decoration, .abm-decoration, .mission-decoration, .vision-decoration {
        background: var(--cnhs-primary-blue);
    }

    .stem-icon, .humss-icon, .abm-icon {
        background: var(--cnhs-light-gray);
    }

    .stem-badge, .humss-badge, .abm-badge {
        background: var(--cnhs-primary-blue);
    }

    .facilities-section, .calendar-section {
        background: var(--cnhs-light-gray);
    }

    .feature-tag {
        background: var(--cnhs-primary-blue);
    }

    .curriculum-item {
        background: var(--cnhs-light-gray);
    }

    .progress-bar, .languages-progress, .math-progress, .science-progress, .social-progress, .research-progress, .tech-progress, .arts-progress, .life-progress {
        background: var(--cnhs-accent-orange);
    }

    .enhanced-table th {
        background: var(--cnhs-primary-blue);
    }

    .table-row:hover {
        background: #EFF6FF;
    }

    .date-badge, .graduation-date {
        background: var(--cnhs-accent-orange);
    }

    .track-icon {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 1.2rem auto;
        font-size: 2.6rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.10);
        transition: box-shadow 0.3s;
        position: relative;
        z-index: 1;
    }

    .track-icon:hover {
        animation: livelyPulse 0.7s;
        box-shadow: 0 0 0 12px #e0e7ff, 0 8px 24px #1e3a8a33;
    }

    @keyframes livelyPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.12); }
        100% { transform: scale(1); }
    }

    .card-title {
        font-size: 2rem;
        font-weight: 900;
        color: var(--cnhs-primary-blue);
        margin-bottom: 0.5rem;
        font-family: 'Montserrat', Arial, sans-serif;
        letter-spacing: -0.01em;
    }

    .track-tagline {
        font-weight: 800;
        font-size: 1.08rem;
        color: var(--cnhs-accent-orange);
        margin-bottom: 0.7rem;
        letter-spacing: 0.01em;
        text-shadow: 0 1px 2px #fff6;
    }

    .track-badge {
        display: inline-block;
        font-size: 1.05rem;
        font-weight: 800;
        padding: 0.5rem 1.3rem;
        border-radius: 18px;
        margin-top: 1.2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        letter-spacing: 0.02em;
        position: absolute;
        top: 24px;
        right: 24px;
    }

    .stem-badge { background: #FACC15; color: #7c5700; }
    .humss-badge { background: #2563eb; color: #fff; }
    .abm-badge { background: #fb923c; color: #7c3f00; }

    .track-list li {
        font-size: 1.13rem;
        font-weight: 600;
        color: var(--cnhs-dark-gray);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .track-list li i {
        font-size: 1.1rem;
        color: var(--cnhs-success);
        margin-right: 0.5rem;
        font-weight: bold;
    }

    @keyframes fadeInUpLively {
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .facility-card {
        border-width: 3px;
        border-style: solid;
        box-shadow: 0 6px 24px rgba(30,58,138,0.10);
        border-radius: 18px;
        transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
        position: relative;
        overflow: visible;
        background: var(--cnhs-white);
        padding: 2.2rem 1.5rem 2rem 1.5rem;
        text-align: left;
        min-height: 220px;
        animation: fadeInUpLively 0.8s both;
    }
    .facility-card:hover {
        transform: translateY(-12px) scale(1.04);
        box-shadow: 0 16px 40px rgba(30,58,138,0.16);
        z-index: 2;
    }
    .facility-card .facility-image-wrapper {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-bottom: 1.2rem;
        font-size: 2.2rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.10);
        position: relative;
        z-index: 1;
        background: #e0e7ff;
        overflow: hidden;
    }
    .facility-card.library-card { border-left: 8px solid var(--cnhs-primary-blue); }
    .facility-card.laboratory-card { border-left: 8px solid var(--cnhs-accent-orange); }
    .facility-card.library-card .facility-image-wrapper { background: #dbeafe; color: #2563eb; }
    .facility-card.laboratory-card .facility-image-wrapper { background: #ffedd5; color: #fb923c; }
    .facility-icon {
        font-size: 2.5rem;
        color: inherit;
        transition: transform 0.3s;
    }
    .facility-image-wrapper:hover .facility-icon {
        animation: livelyPulse 0.7s;
    }
    @keyframes livelyPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.12); }
        100% { transform: scale(1); }
    }
    .facility-title {
        color: var(--cnhs-primary-blue);
        font-weight: 900;
        margin-bottom: 0.2rem;
        font-size: 1.4rem;
        font-family: 'Montserrat', Arial, sans-serif;
    }
    .facility-tagline {
        font-weight: 800;
        font-size: 1.02rem;
        color: var(--cnhs-accent-orange);
        margin-bottom: 0.7rem;
        letter-spacing: 0.01em;
        text-shadow: 0 1px 2px #fff6;
    }
    .facility-features {
        margin-top: 1.2rem;
    }
    .feature-tag {
        display: inline-block;
        font-size: 1.01rem;
        font-weight: 700;
        padding: 0.4rem 1.1rem;
        border-radius: 8px;
        margin-right: 0.7rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        letter-spacing: 0.01em;
        background: #e0e7ff;
        color: #2563eb;
    }
    .facility-card.laboratory-card .feature-tag { background: #ffedd5; color: #fb923c; }
    @keyframes fadeInUpLively {
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.2rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .academic-card,
        .facility-card,
        .curriculum-card {
            margin-bottom: 1.5rem;
        }

        .track-badge {
            position: static;
            display: inline-block;
            margin-top: 1rem;
        }
    }

    /* Lively Curriculum Overview Section */
    .curriculum-card {
        border-width: 3px;
        border-style: solid;
        box-shadow: 0 6px 24px rgba(30,58,138,0.10);
        border-radius: 18px;
        transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
        position: relative;
        overflow: visible;
        background: var(--cnhs-white);
        padding: 2.2rem 1.5rem 2rem 1.5rem;
        min-height: 220px;
        animation: fadeInUpLively 0.8s both;
    }
    .curriculum-card:hover {
        transform: translateY(-10px) scale(1.03);
        box-shadow: 0 16px 40px rgba(30,58,138,0.16);
        z-index: 2;
    }
    .curriculum-header {
        color: var(--cnhs-primary-blue);
        font-weight: 900;
        font-size: 1.5rem;
        font-family: 'Montserrat', Arial, sans-serif;
        display: flex;
        align-items: center;
        gap: 0.7rem;
        margin-bottom: 1.2rem;
    }
    .curriculum-header .curriculum-icon {
        font-size: 1.5rem;
        background: #e0e7ff;
        color: var(--cnhs-accent-orange);
        border-radius: 50%;
        padding: 0.4rem 0.6rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .curriculum-item {
        background: #f8fafc;
        border-radius: 10px;
        margin-bottom: 1.1rem;
        padding: 1.1rem 1.2rem 0.7rem 1.2rem;
        box-shadow: 0 2px 8px rgba(30,58,138,0.06);
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        animation: fadeInUpLively 0.8s both;
    }
    .curriculum-item:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 8px 24px rgba(30,58,138,0.13);
        z-index: 2;
    }
    .curriculum-item .subject-icon {
        font-size: 1.2rem;
        margin-right: 0.7rem;
        color: var(--cnhs-accent-orange);
        vertical-align: middle;
    }
    .subject-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--cnhs-primary-blue);
        margin-bottom: 0.2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .subject-tagline {
        font-size: 0.98rem;
        color: var(--cnhs-accent-orange);
        font-weight: 700;
        margin-bottom: 0.3rem;
    }
    .progress-bar-wrapper {
        height: 7px;
        background: #E2E8F0;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 0.7rem;
        margin-bottom: 0.2rem;
        position: relative;
    }
    .progress-bar {
        height: 100%;
        border-radius: 4px;
        background: var(--cnhs-accent-orange);
        box-shadow: 0 2px 8px #ff8c0033;
        animation: livelyBar 1.2s cubic-bezier(0.23, 1, 0.32, 1);
    }
    @keyframes livelyBar {
        0% { width: 0%; }
        100% { width: 100%; }
    }
    @keyframes fadeInUpLively {
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    /* Lively Academic Calendar Section */
    .calendar-card {
        border-radius: 18px;
        box-shadow: 0 6px 24px rgba(30,58,138,0.10);
        border: 3px solid var(--cnhs-primary-blue);
        overflow: hidden;
        background: var(--cnhs-white);
        animation: fadeInUpLively 0.8s both;
    }
    .enhanced-table th {
        background: var(--cnhs-primary-blue);
        color: white;
        font-weight: 700;
        border: none;
        padding: 1rem;
        font-size: 1.1rem;
        letter-spacing: 0.03em;
    }
    .enhanced-table th .calendar-header-icon {
        font-size: 1.2rem;
        margin-right: 0.5rem;
        color: var(--cnhs-accent-orange);
        vertical-align: middle;
    }
    .table-row {
        transition: all 0.3s ease;
        position: relative;
        animation: fadeInUpLively 0.8s both;
    }
    .table-row:hover {
        background: #f3f6fb;
        box-shadow: 0 4px 16px #1e3a8a11;
        z-index: 2;
    }
    .table-row td {
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--cnhs-dark-gray);
        vertical-align: middle;
        padding: 0.9rem 1rem;
        border: none;
    }
    .table-row .event-icon {
        font-size: 1.1rem;
        margin-right: 0.6rem;
        color: var(--cnhs-accent-orange);
        vertical-align: middle;
    }
    .date-badge, .graduation-date {
        background: var(--cnhs-accent-orange);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.98rem;
        font-weight: 700;
        box-shadow: 0 2px 8px #ff8c0033;
        letter-spacing: 0.01em;
        display: inline-block;
    }
    @keyframes fadeInUpLively {
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection