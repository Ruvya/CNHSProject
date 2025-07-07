@extends('Principal.layouts.app')

@section('content')
    <!-- About Hero Section -->
    <div class="hero-section-small d-flex align-items-center" style="background: var(--cnhs-primary-blue); min-height: 320px; color: white;">
        <div class="container">
            <div class="hero-content text-center" data-aos="fade-up">
                <h1 class="hero-title mb-4" style="color: white; font-size: 3.2rem; font-weight: 800;"><i class="fas fa-school me-2"></i>About CNHS</h1>
                <p class="hero-subtitle" style="color: #e0e7ff; font-size: 1.5rem;">Discover Our History and Vision</p>
            </div>
        </div>
        <div class="hero-decoration">
            <div class="floating-element element-1"></div>
            <div class="floating-element element-2"></div>
            <div class="floating-element element-3"></div>
        </div>
    </div>

    <!-- History Section -->
    <section class="py-5 history-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6" data-aos="fade-right">
                    <div class="history-content">
                        <h2 class="section-title">📚 Our History</h2>
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h4 class="timeline-year">1964</h4>
                                <p class="timeline-text">Founded in 1964, Calingcaguing National High School has been a beacon of academic excellence in the region for over five decades. What started as a small community school has grown into one of the most prestigious educational institutions in Camarines Norte.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h4 class="timeline-year">Present</h4>
                                <p class="timeline-text">Throughout the years, CNHS has consistently produced graduates who have gone on to become leaders in various fields, contributing significantly to the nation's development.</p>
                            </div>
                        </div>
                        <div class="history-stats">
                            <div class="history-stat">
                                <span class="stat-number">60+</span>
                                <span class="stat-label">Years of Excellence</span>
                            </div>
                            <div class="history-stat">
                                <span class="stat-number">10,000+</span>
                                <span class="stat-label">Alumni</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <div class="history-image-wrapper">
                        <img src="{{ asset('images/cal.jpg') }}" alt="CNHS History" class="img-fluid history-image">
                        <div class="history-overlay">
                            <div class="overlay-badge">
                                <i class="fas fa-school"></i>
                                <span>Since 1964</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="mission-vision-section py-5 lively-mission-vision-bg">
        <div class="container">
            <h2 class="text-center mb-5 section-header lively-mv-title" data-aos="fade-up">
                <span class="lively-mv-icon"><i class="fas fa-bullseye"></i></span> Our Mission & Vision
            </h2>
            <div class="row">
                <div class="col-md-6 mb-4" data-aos="fade-up">
                    <div class="mission-card lively-mv-card h-100">
                        <div class="card-decoration mission-decoration lively-mv-decoration"></div>
                        <div class="card-icon mission-icon lively-mv-icon-bg">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <div class="card-body p-4">
                            <h3 class="card-title mb-2">Our Mission</h3>
                            <div class="lively-mv-tagline">Empowering Learners</div>
                            <p class="card-text">To provide quality education that develops well-rounded individuals equipped with knowledge, skills, and values necessary to meet the challenges of a rapidly changing world while maintaining their cultural identity and social responsibility.</p>
                            <div class="mission-highlights">
                                <span class="highlight-tag">Quality Education</span>
                                <span class="highlight-tag">Holistic Development</span>
                                <span class="highlight-tag">Cultural Identity</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="vision-card lively-mv-card h-100">
                        <div class="card-decoration vision-decoration lively-mv-decoration"></div>
                        <div class="card-icon vision-icon lively-mv-icon-bg-vision">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="card-body p-4">
                            <h3 class="card-title mb-2">Our Vision</h3>
                            <div class="lively-mv-tagline">Inspiring Leaders</div>
                            <p class="card-text">To be a leading educational institution that nurtures future leaders and innovators who are globally competitive, socially responsible, and deeply rooted in Filipino values.</p>
                            <div class="vision-highlights">
                                <span class="highlight-tag">Global Competitiveness</span>
                                <span class="highlight-tag">Innovation</span>
                                <span class="highlight-tag">Filipino Values</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="py-5 values-section">
        <div class="container">
            <h2 class="text-center mb-5 section-header" data-aos="fade-up">⭐ Our Core Values</h2>
            <div class="row">
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="value-card excellence-card">
                        <div class="value-icon-wrapper"><i class="fas fa-star"></i></div>
                        <h4 class="value-title">Excellence</h4>
                        <div class="value-tagline">Strive for the Best</div>
                        <p class="value-description">Striving for the highest standards in everything we do</p>
                        <div class="value-badge">Top Quality</div>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="value-card integrity-card">
                        <div class="value-icon-wrapper"><i class="fas fa-heart"></i></div>
                        <h4 class="value-title">Integrity</h4>
                        <div class="value-tagline">Honesty & Ethics</div>
                        <p class="value-description">Upholding honesty and moral principles</p>
                        <div class="value-badge">Honest</div>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="value-card service-card">
                        <div class="value-icon-wrapper"><i class="fas fa-hands-helping"></i></div>
                        <h4 class="value-title">Service</h4>
                        <div class="value-tagline">Community First</div>
                        <p class="value-description">Dedicated to serving our community</p>
                        <div class="value-badge">Community</div>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="value-card unity-card">
                        <div class="value-icon-wrapper"><i class="fas fa-users"></i></div>
                        <h4 class="value-title">Unity</h4>
                        <div class="value-tagline">Together as One</div>
                        <p class="value-description">Working together towards common goals</p>
                        <div class="value-badge">Together</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Section -->
    <section class="leadership-section py-5">
        <div class="container">
            <h2 class="text-center mb-5 section-header" data-aos="fade-up">👥 School Leadership</h2>
            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="leadership-card principal-card">
                        <div class="leader-icon"><i class="fas fa-graduation-cap"></i></div>
                        <div class="leader-image-wrapper">
                            <img src="{{ asset('images/principal.jpg') }}" class="leader-image" alt="School Principal">
                        </div>
                        <div class="leader-info">
                            <h5 class="leader-name">Dr. Juan Dela Cruz</h5>
                            <p class="leader-position">School Principal</p>
                            <div class="leader-tagline">Guiding Excellence</div>
                            <div class="leader-badge">Leader</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="leadership-card academic-card">
                        <div class="leader-icon"><i class="fas fa-book"></i></div>
                        <div class="leader-image-wrapper">
                            <img src="{{ asset('images/vice-principal.jpg') }}" class="leader-image" alt="Vice Principal">
                        </div>
                        <div class="leader-info">
                            <h5 class="leader-name">Ms. Maria Santos</h5>
                            <p class="leader-position">Vice Principal for Academics</p>
                            <div class="leader-tagline">Academic Vision</div>
                            <div class="leader-badge">Academic</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="leadership-card admin-card">
                        <div class="leader-icon"><i class="fas fa-cogs"></i></div>
                        <div class="leader-image-wrapper">
                            <img src="{{ asset('images/admin-head.jpg') }}" class="leader-image" alt="Administrative Head">
                        </div>
                        <div class="leader-info">
                            <h5 class="leader-name">Mr. Pedro Reyes</h5>
                            <p class="leader-position">Administrative Head</p>
                            <div class="leader-tagline">Operational Excellence</div>
                            <div class="leader-badge">Admin</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
        left: 0;
        width: 80px;
        height: 4px;
        background: var(--cnhs-accent-orange);
        border-radius: 2px;
    }

    .section-header {
        color: var(--cnhs-primary-blue);
        background: none;
        -webkit-background-clip: initial;
        -webkit-text-fill-color: initial;
        background-clip: initial;
        font-size: 2.8rem;
        font-weight: 800;
        position: relative;
        letter-spacing: -0.02em;
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

    .history-section, .mission-vision-section, .leadership-section {
        background: var(--cnhs-light-gray);
        margin: 3rem 0;
        border-radius: 30px;
        padding: 4rem 2rem;
    }

    .history-content, .mission-card, .vision-card, .leadership-card, .value-card {
        background: var(--cnhs-white);
        padding: 3rem;
        border-radius: 24px;
        box-shadow: 0 12px 35px rgba(30, 58, 138, 0.12);
        position: relative;
    }

    .history-content::before {
        background: var(--cnhs-primary-blue);
    }

    .timeline-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 2.5rem;
        position: relative;
    }

    .timeline-marker {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--cnhs-primary-blue);
        margin-right: 2rem;
        margin-top: 0.5rem;
        flex-shrink: 0;
        position: relative;
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    .timeline-marker::after {
        content: '';
        position: absolute;
        top: 24px;
        left: 50%;
        transform: translateX(-50%);
        width: 3px;
        height: 80px;
        background: var(--cnhs-primary-blue);
        border-radius: 2px;
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-year {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--cnhs-primary-blue);
        margin-bottom: 1rem;
        letter-spacing: -0.01em;
    }

    .timeline-text {
        font-size: 1.05rem;
        color: var(--cnhs-medium-gray);
        line-height: 1.7;
        font-weight: 500;
    }

    .history-stats {
        display: flex;
        justify-content: space-around;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 2px solid var(--cnhs-light-gray);
    }

    .history-stat {
        text-align: center;
        padding: 1.5rem;
        background: var(--cnhs-light-gray);
        border-radius: 18px;
        transition: all 0.3s ease;
    }

    .history-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(30, 58, 138, 0.15);
    }

    .stat-number {
        display: block;
        font-size: 2rem;
        font-weight: 700;
        color: var(--cnhs-primary-blue);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 1rem;
        color: var(--cnhs-medium-gray);
        font-weight: 500;
    }

    .history-image-wrapper {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(30, 58, 138, 0.12);
    }

    .history-image {
        width: 100%;
        height: auto;
        transition: transform 0.4s ease;
    }

    .history-image-wrapper:hover .history-image {
        transform: scale(1.05);
    }

    .history-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(30, 58, 138, 0.8);
        opacity: 0;
        transition: opacity 0.4s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .history-image-wrapper:hover .history-overlay {
        opacity: 1;
    }

    .overlay-badge {
        background: rgba(30, 58, 138, 0.15);
        padding: 1.5rem 2rem;
        border-radius: 18px;
        text-align: center;
        color: var(--cnhs-primary-blue);
        backdrop-filter: blur(10px);
    }

    .overlay-badge i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: block;
    }

    .overlay-badge span {
        font-size: 1.2rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .mission-card, .vision-card {
        background: var(--cnhs-white);
        border-radius: 24px;
        box-shadow: 0 12px 35px rgba(30, 58, 138, 0.12);
        position: relative;
        transition: all 0.4s ease;
        overflow: hidden;
    }

    .mission-card:hover, .vision-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(30, 58, 138, 0.18);
    }

    .card-decoration {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        border-radius: 24px 24px 0 0;
    }

    .mission-decoration {
        background: var(--cnhs-primary-blue);
    }

    .vision-decoration {
        background: var(--cnhs-primary-blue);
    }

    .card-icon {
        position: absolute;
        top: 20px;
        left: 20px;
        width: 40px;
        height: 40px;
        background: var(--cnhs-light-gray);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-icon i {
        font-size: 2rem;
        color: var(--cnhs-primary-blue);
    }

    .mission-highlights, .vision-highlights {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }

    .highlight-tag {
        font-size: 0.9rem;
        color: var(--cnhs-primary-blue);
    }

    .leadership-card {
        background: var(--cnhs-white);
        padding: 2.5rem 1.5rem 2rem 1.5rem;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(30,58,138,0.10);
        position: relative;
        border-left: 8px solid var(--cnhs-primary-blue);
        transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
        min-height: 260px;
        animation: fadeInUpLively 0.8s both;
        z-index: 1;
    }
    .leadership-card:hover {
        transform: translateY(-10px) scale(1.03);
        box-shadow: 0 16px 40px rgba(30,58,138,0.16);
        z-index: 2;
    }
    .leadership-card .leader-image-wrapper {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem auto;
        border-radius: 50%;
        overflow: hidden;
        background: #e0e7ff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 10px rgba(30,58,138,0.10);
        position: relative;
    }
    .leadership-card.principal-card { border-left: 8px solid var(--cnhs-accent-orange); }
    .leadership-card.academic-card { border-left: 8px solid var(--cnhs-primary-blue); }
    .leadership-card.admin-card { border-left: 8px solid var(--cnhs-warning); }
    .leader-badge {
        display: inline-block;
        font-size: 1.05rem;
        font-weight: 800;
        padding: 0.4rem 1.1rem;
        border-radius: 8px;
        margin-top: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        letter-spacing: 0.02em;
    }
    .leadership-card.principal-card .leader-badge { background: var(--cnhs-accent-orange); color: #fff; }
    .leadership-card.academic-card .leader-badge { background: var(--cnhs-primary-blue); color: #fff; }
    .leadership-card.admin-card .leader-badge { background: var(--cnhs-warning); color: #fff; }
    .leader-name {
        font-size: 1.3rem;
        font-weight: 900;
        color: var(--cnhs-primary-blue);
        margin-bottom: 0.2rem;
        font-family: 'Montserrat', Arial, sans-serif;
    }
    .leader-position {
        font-size: 1.08rem;
        color: var(--cnhs-medium-gray);
        font-weight: 700;
        margin-bottom: 0.3rem;
    }
    .leader-tagline {
        font-size: 1.02rem;
        color: var(--cnhs-accent-orange);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .leader-icon {
        position: absolute;
        top: -18px;
        left: -18px;
        width: 38px;
        height: 38px;
        background: var(--cnhs-white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        font-size: 1.3rem;
        color: var(--cnhs-accent-orange);
        z-index: 2;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.4rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
        }

        .section-title, .section-header {
            font-size: 2.2rem;
            text-align: center;
        }

        .section-title::after {
            left: 50%;
            transform: translateX(-50%);
        }

        .timeline-item {
            flex-direction: column;
            text-align: center;
        }

        .timeline-marker {
            margin: 0 auto 1rem;
        }

        .timeline-marker::after {
            display: none;
        }

        .history-stats {
            flex-direction: column;
            gap: 1rem;
        }

        .mission-highlights, .vision-highlights {
            flex-direction: column;
            align-items: center;
        }

        .value-card {
            margin-bottom: 2rem;
        }

        .value-badge {
            position: static;
            display: inline-block;
            margin-top: 1rem;
        }

        .history-content {
            padding: 2rem;
        }
    }

    /* Professional animations */
    @keyframes professionalPulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(30, 58, 138, 0.4); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(30, 58, 138, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(30, 58, 138, 0); }
    }

    .timeline-marker {
        animation: professionalPulse 3s infinite;
    }

    .lively-mission-vision-bg {
        background: #f3f6fb;
        border-radius: 30px;
        position: relative;
        overflow: hidden;
    }
    .lively-mission-vision-bg::before,
    .lively-mission-vision-bg::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        opacity: 0.10;
        z-index: 0;
    }
    .lively-mission-vision-bg::before {
        width: 120px;
        height: 120px;
        background: var(--cnhs-primary-blue);
        top: 10%;
        left: 5%;
    }
    .lively-mission-vision-bg::after {
        width: 90px;
        height: 90px;
        background: var(--cnhs-accent-orange);
        bottom: 8%;
        right: 7%;
    }
    .lively-mv-title {
        position: relative;
        font-size: 2.8rem;
        font-weight: 800;
        color: var(--cnhs-primary-blue);
        margin-bottom: 2.5rem;
    }
    .lively-mv-title::after {
        content: '';
        display: block;
        margin: 0.7rem auto 0 auto;
        width: 120px;
        height: 5px;
        background: var(--cnhs-primary-blue);
        border-radius: 3px;
    }
    .lively-mv-icon {
        font-size: 2.2rem;
        color: var(--cnhs-accent-orange);
        vertical-align: middle;
        margin-right: 0.5rem;
    }
    .lively-mv-card {
        border: 2px solid var(--cnhs-primary-blue);
        box-shadow: 0 8px 32px rgba(30, 58, 138, 0.10), 0 0 0 8px #e0e7ff;
        border-radius: 24px;
        transition: transform 0.25s, box-shadow 0.25s;
        position: relative;
        overflow: visible;
        background: var(--cnhs-white);
        animation: fadeInUpLively 0.8s both;
        z-index: 1;
    }
    .lively-mv-card:hover {
        transform: translateY(-12px) scale(1.04);
        box-shadow: 0 16px 40px rgba(30, 58, 138, 0.16), 0 0 0 12px #e0e7ff;
        z-index: 2;
    }
    .lively-mv-icon-bg {
        width: 64px;
        height: 64px;
        background: var(--cnhs-primary-blue);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 2.5rem;
        margin-bottom: 1.2rem;
        box-shadow: 0 0 0 8px #e0e7ff, 0 4px 16px #1e3a8a22;
        position: absolute;
        top: 24px;
        left: 24px;
        transition: box-shadow 0.3s;
    }
    .lively-mv-icon-bg-vision {
        width: 64px;
        height: 64px;
        background: var(--cnhs-accent-orange);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 2.5rem;
        margin-bottom: 1.2rem;
        box-shadow: 0 0 0 8px #ffe6cc, 0 4px 16px #ff8c0022;
        position: absolute;
        top: 24px;
        left: 24px;
        transition: box-shadow 0.3s;
    }
    .lively-mv-icon-bg:hover, .lively-mv-icon-bg-vision:hover {
        animation: livelyPulse 0.7s;
        box-shadow: 0 0 0 14px #e0e7ff, 0 8px 24px #1e3a8a33;
    }
    @keyframes livelyPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.12); }
        100% { transform: scale(1); }
    }
    .lively-mv-tagline {
        font-weight: 800;
        font-size: 1.15rem;
        color: var(--cnhs-accent-orange);
        margin-bottom: 0.7rem;
        letter-spacing: 0.01em;
        text-shadow: 0 1px 2px #fff6;
    }
    .highlight-tag {
        position: relative;
        font-size: 0.98rem;
        color: var(--cnhs-primary-blue);
        font-weight: 700;
        margin-right: 1.2rem;
        padding-left: 1.3em;
    }
    .highlight-tag::before {
        content: '';
        display: inline-block;
        width: 0.7em;
        height: 0.7em;
        background: var(--cnhs-accent-orange);
        border-radius: 2px;
        position: absolute;
        left: 0;
        top: 0.18em;
    }
    @keyframes fadeInUpLively {
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection