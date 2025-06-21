@extends('Principal.layouts.app')

@section('title', 'Academics')

@section('content')
    <!-- Hero Section -->
    <div class="hero-section-small d-flex align-items-center mb-5">
        <div class="container">
            <div class="hero-content text-center" data-aos="fade-up">
                <h1 class="hero-title mb-4">Academic Programs</h1>
                <p class="hero-subtitle">Excellence in Education</p>
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
                        <div class="card academic-card stem-card h-100 border-0 shadow">
                            <div class="card-decoration stem-decoration"></div>
                            <div class="card-body p-4">
                                <div class="track-icon stem-icon mb-3">
                                    <i class="fas fa-atom"></i>
                                </div>
                                <h3 class="card-title text-primary stem-title">STEM</h3>
                                <p class="card-text">Science, Technology, Engineering, and Mathematics track prepares students for careers in:</p>
                                <ul class="list-unstyled track-list">
                                    <li><i class="fas fa-check text-success me-2"></i>Engineering</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Medicine</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Architecture</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Information Technology</li>
                                </ul>
                                <div class="track-badge stem-badge">Science & Tech</div>
                            </div>
                        </div>
                    </div>

                    <!-- HUMSS -->
                    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="card academic-card humss-card h-100 border-0 shadow">
                            <div class="card-decoration humss-decoration"></div>
                            <div class="card-body p-4">
                                <div class="track-icon humss-icon mb-3">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3 class="card-title text-primary humss-title">HUMSS</h3>
                                <p class="card-text">Humanities and Social Sciences track focuses on:</p>
                                <ul class="list-unstyled track-list">
                                    <li><i class="fas fa-check text-success me-2"></i>Education</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Law</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Communication Arts</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Social Sciences</li>
                                </ul>
                                <div class="track-badge humss-badge">Humanities</div>
                            </div>
                        </div>
                    </div>

                    <!-- ABM -->
                    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="card academic-card abm-card h-100 border-0 shadow">
                            <div class="card-decoration abm-decoration"></div>
                            <div class="card-body p-4">
                                <div class="track-icon abm-icon mb-3">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h3 class="card-title text-primary abm-title">ABM</h3>
                                <p class="card-text">Accountancy, Business and Management track prepares for:</p>
                                <ul class="list-unstyled track-list">
                                    <li><i class="fas fa-check text-success me-2"></i>Business Administration</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Accountancy</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Economics</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Entrepreneurship</li>
                                </ul>
                                <div class="track-badge abm-badge">Business</div>
                            </div>
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
                        <div class="card facility-card border-0 shadow">
                            <div class="facility-image-wrapper">
                                <img src="{{ asset('images/library.jpg') }}" class="card-img-top facility-img" alt="Library">
                                <div class="facility-overlay">
                                    <div class="facility-icon">
                                        <i class="fas fa-book"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h4 class="facility-title">Modern Library</h4>
                                <p class="text-muted">Our library is equipped with the latest resources and digital facilities.</p>
                                <div class="facility-features">
                                    <span class="feature-tag">📚 Digital Resources</span>
                                    <span class="feature-tag">💻 Study Areas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4" data-aos="fade-left">
                        <div class="card facility-card border-0 shadow">
                            <div class="facility-image-wrapper">
                                <img src="{{ asset('images/laboratory.jpg') }}" class="card-img-top facility-img" alt="Science Laboratory">
                                <div class="facility-overlay">
                                    <div class="facility-icon">
                                        <i class="fas fa-flask"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h4 class="facility-title">Science Laboratories</h4>
                                <p class="text-muted">State-of-the-art laboratories for hands-on learning experience.</p>
                                <div class="facility-features">
                                    <span class="feature-tag">🔬 Modern Equipment</span>
                                    <span class="feature-tag">🧪 Safe Environment</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Curriculum Overview Section -->
        <section class="py-5">
            <div class="container">
                <h2 class="section-title text-center mb-5" data-aos="fade-up">📋 Curriculum Overview</h2>
                <div class="row">
                    <div class="col-lg-6 mb-4" data-aos="fade-right">
                        <div class="card curriculum-card border-0 shadow">
                            <div class="card-body p-4">
                                <h4 class="mb-4 curriculum-header">📚 Core Subjects</h4>
                                <div class="curriculum-item mb-3">
                                    <h5 class="text-primary subject-title">Languages</h5>
                                    <p class="text-muted">English, Filipino, Literature</p>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar languages-progress"></div>
                                    </div>
                                </div>
                                <div class="curriculum-item mb-3">
                                    <h5 class="text-primary subject-title">Mathematics</h5>
                                    <p class="text-muted">General Mathematics, Statistics, Pre-Calculus</p>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar math-progress"></div>
                                    </div>
                                </div>
                                <div class="curriculum-item mb-3">
                                    <h5 class="text-primary subject-title">Sciences</h5>
                                    <p class="text-muted">Earth Science, Biology, Chemistry, Physics</p>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar science-progress"></div>
                                    </div>
                                </div>
                                <div class="curriculum-item">
                                    <h5 class="text-primary subject-title">Social Sciences</h5>
                                    <p class="text-muted">History, Economics, Contemporary Issues</p>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar social-progress"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4" data-aos="fade-left">
                        <div class="card curriculum-card border-0 shadow">
                            <div class="card-body p-4">
                                <h4 class="mb-4 curriculum-header">⭐ Special Programs</h4>
                                <div class="curriculum-item mb-3">
                                    <h5 class="text-primary subject-title">Research Program</h5>
                                    <p class="text-muted">Practical Research, Capstone Projects</p>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar research-progress"></div>
                                    </div>
                                </div>
                                <div class="curriculum-item mb-3">
                                    <h5 class="text-primary subject-title">Technology Integration</h5>
                                    <p class="text-muted">Computer Programming, Digital Arts</p>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar tech-progress"></div>
                                    </div>
                                </div>
                                <div class="curriculum-item mb-3">
                                    <h5 class="text-primary subject-title">Arts and Sports</h5>
                                    <p class="text-muted">Physical Education, Music, Visual Arts</p>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar arts-progress"></div>
                                    </div>
                                </div>
                                <div class="curriculum-item">
                                    <h5 class="text-primary subject-title">Life Skills</h5>
                                    <p class="text-muted">Values Education, Career Guidance</p>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar life-progress"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Academic Calendar Section -->
        <section class="bg-light py-5 calendar-section">
            <div class="container">
                <h2 class="section-title text-center mb-5" data-aos="fade-up">📅 Academic Calendar</h2>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card calendar-card border-0 shadow" data-aos="fade-up">
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-hover enhanced-table">
                                        <thead>
                                            <tr>
                                                <th>📋 Event</th>
                                                <th>📅 Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="table-row">
                                                <td>First Semester Begins</td>
                                                <td><span class="date-badge">August 1, 2024</span></td>
                                            </tr>
                                            <tr class="table-row">
                                                <td>Midterm Examinations</td>
                                                <td><span class="date-badge">October 15-19, 2024</span></td>
                                            </tr>
                                            <tr class="table-row">
                                                <td>Christmas Break</td>
                                                <td><span class="date-badge">December 20, 2024 - January 3, 2025</span></td>
                                            </tr>
                                            <tr class="table-row">
                                                <td>Second Semester Begins</td>
                                                <td><span class="date-badge">January 6, 2025</span></td>
                                            </tr>
                                            <tr class="table-row">
                                                <td>Final Examinations</td>
                                                <td><span class="date-badge">March 24-28, 2025</span></td>
                                            </tr>
                                            <tr class="table-row">
                                                <td>Graduation Ceremony</td>
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
    .hero-section-small {
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.9), rgba(111, 66, 193, 0.8)), url('{{ asset("images/im.jpg") }}');
        background-size: cover;
        background-position: center;
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
        background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .hero-title {
        color: #fff;
        font-size: 3rem;
        font-weight: 800;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 1s ease-out;
    }

    .hero-subtitle {
        color: #fff;
        font-size: 1.4rem;
        opacity: 0.95;
        font-weight: 300;
        animation: fadeInUp 1s ease-out 0.3s both;
    }

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

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #0d6efd, #6f42c1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 2rem;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(135deg, #0d6efd, #6f42c1);
        border-radius: 2px;
    }

    .academic-card {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        background: linear-gradient(145deg, #ffffff, #f8f9ff);
    }

    .academic-card:hover {
        transform: translateY(-15px) scale(1.02);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    .card-decoration {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        border-radius: 20px 20px 0 0;
    }

    .stem-decoration {
        background: linear-gradient(135deg, #e74c3c, #f39c12);
    }

    .humss-decoration {
        background: linear-gradient(135deg, #9b59b6, #e91e63);
    }

    .abm-decoration {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
    }

    .track-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        transition: all 0.3s ease;
        font-size: 2rem;
    }

    .stem-icon {
        background: linear-gradient(135deg, #ffebee, #ffcdd2);
        color: #e74c3c;
    }

    .humss-icon {
        background: linear-gradient(135deg, #f3e5f5, #e1bee7);
        color: #9b59b6;
    }

    .abm-icon {
        background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
        color: #27ae60;
    }

    .academic-card:hover .track-icon {
        transform: rotate(360deg) scale(1.2);
    }

    .stem-card:hover .track-icon {
        background: linear-gradient(135deg, #e74c3c, #f39c12);
        color: white;
    }

    .humss-card:hover .track-icon {
        background: linear-gradient(135deg, #9b59b6, #e91e63);
        color: white;
    }

    .abm-card:hover .track-icon {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
    }

    .track-list li {
        padding: 0.5rem 0;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .track-list li:hover {
        transform: translateX(10px);
        color: #0d6efd;
    }

    .track-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: white;
    }

    .stem-badge {
        background: linear-gradient(135deg, #e74c3c, #f39c12);
    }

    .humss-badge {
        background: linear-gradient(135deg, #9b59b6, #e91e63);
    }

    .abm-badge {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
    }

    .facilities-section {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 30px;
        margin: 2rem 0;
    }

    .facility-card {
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s ease;
        background: linear-gradient(145deg, #ffffff, #f8f9ff);
    }

    .facility-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .facility-image-wrapper {
        position: relative;
        overflow: hidden;
    }

    .facility-img {
        height: 250px;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .facility-card:hover .facility-img {
        transform: scale(1.1);
    }

    .facility-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.8), rgba(111, 66, 193, 0.8));
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .facility-card:hover .facility-overlay {
        opacity: 1;
    }

    .facility-icon {
        font-size: 3rem;
        color: white;
        animation: bounceIn 0.6s ease;
    }

    @keyframes bounceIn {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    .facility-title {
        color: #2d3436;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .facility-features {
        margin-top: 1rem;
    }

    .feature-tag {
        display: inline-block;
        background: linear-gradient(135deg, #0d6efd, #6f42c1);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        margin: 0.2rem;
        font-weight: 500;
    }

    .curriculum-card {
        border-radius: 20px;
        background: linear-gradient(145deg, #ffffff, #f8f9ff);
        transition: all 0.3s ease;
    }

    .curriculum-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .curriculum-header {
        color: #2d3436;
        font-weight: 700;
        border-bottom: 3px solid #0d6efd;
        padding-bottom: 0.5rem;
    }

    .curriculum-item {
        padding: 1rem;
        border-radius: 15px;
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.05), rgba(111, 66, 193, 0.05));
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .curriculum-item:hover {
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.1), rgba(111, 66, 193, 0.1));
        transform: translateX(10px);
    }

    .subject-title {
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .progress-bar-wrapper {
        height: 6px;
        background: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .progress-bar {
        height: 100%;
        border-radius: 3px;
        animation: progressAnimation 2s ease-in-out;
    }

    @keyframes progressAnimation {
        0% { width: 0%; }
        100% { width: var(--progress-width); }
    }

    .languages-progress {
        --progress-width: 85%;
        background: linear-gradient(135deg, #e74c3c, #f39c12);
        width: 85%;
    }

    .math-progress {
        --progress-width: 90%;
        background: linear-gradient(135deg, #3498db, #2980b9);
        width: 90%;
    }

    .science-progress {
        --progress-width: 88%;
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        width: 88%;
    }

    .social-progress {
        --progress-width: 82%;
        background: linear-gradient(135deg, #9b59b6, #8e44ad);
        width: 82%;
    }

    .research-progress {
        --progress-width: 75%;
        background: linear-gradient(135deg, #e67e22, #d35400);
        width: 75%;
    }

    .tech-progress {
        --progress-width: 80%;
        background: linear-gradient(135deg, #1abc9c, #16a085);
        width: 80%;
    }

    .arts-progress {
        --progress-width: 85%;
        background: linear-gradient(135deg, #e91e63, #ad1457);
        width: 85%;
    }

    .life-progress {
        --progress-width: 90%;
        background: linear-gradient(135deg, #ff5722, #d84315);
        width: 90%;
    }

    .calendar-section {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 30px;
        margin: 2rem 0;
    }

    .calendar-card {
        border-radius: 20px;
        background: linear-gradient(145deg, #ffffff, #f8f9ff);
    }

    .enhanced-table {
        margin-bottom: 0;
        border-radius: 15px;
        overflow: hidden;
    }

    .enhanced-table th {
        background: linear-gradient(135deg, #0d6efd, #6f42c1);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem;
    }

    .table-row {
        transition: all 0.3s ease;
    }

    .table-row:hover {
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.1), rgba(111, 66, 193, 0.1));
        transform: scale(1.02);
    }

    .date-badge {
        background: linear-gradient(135deg, #0d6efd, #6f42c1);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .graduation-date {
        background: linear-gradient(135deg, #28a745, #20c997);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
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
</style>
@endsection