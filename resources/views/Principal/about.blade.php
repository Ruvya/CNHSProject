@extends('Principal.layouts.app')

@section('content')
    <!-- About Hero Section -->
    <div class="hero-section-small d-flex align-items-center">
        <div class="container">
            <div class="hero-content text-center" data-aos="fade-up">
                <h1 class="hero-title mb-4">About CNHS</h1>
                <p class="hero-subtitle">Discover Our History and Vision</p>
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
    <section class="mission-vision-section py-5">
        <div class="container">
            <h2 class="text-center mb-5 section-header" data-aos="fade-up">🎯 Our Mission & Vision</h2>
            <div class="row">
                <div class="col-md-6 mb-4" data-aos="fade-up">
                    <div class="mission-card h-100">
                        <div class="card-decoration mission-decoration"></div>
                        <div class="card-icon mission-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <div class="card-body p-4">
                            <h3 class="card-title mb-4">Our Mission</h3>
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
                    <div class="vision-card h-100">
                        <div class="card-decoration vision-decoration"></div>
                        <div class="card-icon vision-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="card-body p-4">
                            <h3 class="card-title mb-4">Our Vision</h3>
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
                        <div class="value-icon-wrapper">
                            <i class="fas fa-star"></i>
                        </div>
                        <h4 class="value-title">Excellence</h4>
                        <p class="value-description">Striving for the highest standards in everything we do</p>
                        <div class="value-badge excellence-badge">Top Quality</div>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="value-card integrity-card">
                        <div class="value-icon-wrapper">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4 class="value-title">Integrity</h4>
                        <p class="value-description">Upholding honesty and moral principles</p>
                        <div class="value-badge integrity-badge">Honest</div>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="value-card service-card">
                        <div class="value-icon-wrapper">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h4 class="value-title">Service</h4>
                        <p class="value-description">Dedicated to serving our community</p>
                        <div class="value-badge service-badge">Community</div>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="value-card unity-card">
                        <div class="value-icon-wrapper">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="value-title">Unity</h4>
                        <p class="value-description">Working together towards common goals</p>
                        <div class="value-badge unity-badge">Together</div>
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
                    <div class="leadership-card">
                        <div class="leader-image-wrapper">
                            <img src="{{ asset('images/principal.jpg') }}" class="leader-image" alt="School Principal">
                            <div class="leader-overlay">
                                <div class="leader-social">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                        </div>
                        <div class="leader-info">
                            <h5 class="leader-name">Dr. Juan Dela Cruz</h5>
                            <p class="leader-position">School Principal</p>
                            <div class="leader-badge principal-badge">Leader</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="leadership-card">
                        <div class="leader-image-wrapper">
                            <img src="{{ asset('images/vice-principal.jpg') }}" class="leader-image" alt="Vice Principal">
                            <div class="leader-overlay">
                                <div class="leader-social">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                        </div>
                        <div class="leader-info">
                            <h5 class="leader-name">Ms. Maria Santos</h5>
                            <p class="leader-position">Vice Principal for Academics</p>
                            <div class="leader-badge academic-badge">Academic</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="leadership-card">
                        <div class="leader-image-wrapper">
                            <img src="{{ asset('images/admin-head.jpg') }}" class="leader-image" alt="Administrative Head">
                            <div class="leader-overlay">
                                <div class="leader-social">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                        </div>
                        <div class="leader-info">
                            <h5 class="leader-name">Mr. Pedro Reyes</h5>
                            <p class="leader-position">Administrative Head</p>
                            <div class="leader-badge admin-badge">Admin</div>
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

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--cnhs-blue), var(--cnhs-orange));
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
        left: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(135deg, var(--cnhs-orange), var(--cnhs-blue));
        border-radius: 2px;
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

    .history-section {
        background: linear-gradient(135deg, var(--cnhs-light-gray), #E5E7EB);
        margin: 2rem 0;
        border-radius: 30px;
        padding: 4rem 2rem;
    }

    .history-content {
        background: var(--cnhs-white);
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .history-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(135deg, var(--cnhs-orange), var(--cnhs-blue));
        border-radius: 20px 20px 0 0;
    }

    .timeline-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 2rem;
        position: relative;
    }

    .timeline-marker {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--cnhs-orange), var(--cnhs-blue));
        margin-right: 1.5rem;
        margin-top: 0.5rem;
        flex-shrink: 0;
        position: relative;
    }

    .timeline-marker::after {
        content: '';
        position: absolute;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 2px;
        height: 60px;
        background: linear-gradient(135deg, var(--cnhs-orange), var(--cnhs-blue));
        border-radius: 2px;
    }

    .timeline-content {
        margin-left: 25px;
    }

    .timeline-year {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--cnhs-blue);
    }

    .timeline-text {
        font-size: 1rem;
        color: var(--cnhs-gray);
    }

    .history-stats {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }

    .history-stat {
        text-align: center;
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--cnhs-blue);
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--cnhs-gray);
    }

    .mission-vision-section {
        background: linear-gradient(135deg, var(--cnhs-light-gray), #E5E7EB);
        margin: 2rem 0;
        border-radius: 30px;
        padding: 4rem 2rem;
    }

    .mission-card {
        background: var(--cnhs-white);
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .mission-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(135deg, var(--cnhs-orange), var(--cnhs-blue));
        border-radius: 20px 20px 0 0;
    }

    .mission-icon {
        position: absolute;
        top: 20px;
        left: 20px;
        width: 40px;
        height: 40px;
        background: var(--cnhs-orange);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mission-icon i {
        font-size: 2rem;
        color: var(--cnhs-white);
    }

    .mission-highlights {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }

    .highlight-tag {
        font-size: 0.9rem;
        color: var(--cnhs-blue);
    }

    .vision-vision-section {
        background: linear-gradient(135deg, var(--cnhs-light-gray), #E5E7EB);
        margin: 2rem 0;
        border-radius: 30px;
        padding: 4rem 2rem;
    }

    .vision-card {
        background: var(--cnhs-white);
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .vision-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(135deg, var(--cnhs-orange), var(--cnhs-blue));
        border-radius: 20px 20px 0 0;
    }

    .vision-icon {
        position: absolute;
        top: 20px;
        left: 20px;
        width: 40px;
        height: 40px;
        background: var(--cnhs-blue);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .vision-icon i {
        font-size: 2rem;
        color: var(--cnhs-white);
    }

    .vision-highlights {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }

    .value-card {
        background: var(--cnhs-white);
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .value-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(135deg, var(--cnhs-orange), var(--cnhs-blue));
        border-radius: 20px 20px 0 0;
    }

    .value-icon-wrapper {
        position: absolute;
        top: 20px;
        left: 20px;
        width: 40px;
        height: 40px;
        background: var(--cnhs-orange);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .value-icon-wrapper i {
        font-size: 2rem;
        color: var(--cnhs-white);
    }

    .value-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--cnhs-blue);
    }

    .value-description {
        font-size: 1rem;
        color: var(--cnhs-gray);
    }

    .value-badge {
        font-size: 0.9rem;
        color: var(--cnhs-blue);
    }

    .leadership-section {
        background: linear-gradient(135deg, var(--cnhs-light-gray), #E5E7EB);
        margin: 2rem 0;
        border-radius: 30px;
        padding: 4rem 2rem;
    }

    .leadership-card {
        background: var(--cnhs-white);
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .leadership-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(135deg, var(--cnhs-orange), var(--cnhs-blue));
        border-radius: 20px 20px 0 0;
    }

    .leader-image-wrapper {
        position: relative;
    }

    .leader-image {
        width: 100%;
        height: auto;
        border-radius: 20px;
    }

    .leader-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .leader-social {
        font-size: 2rem;
        color: var(--cnhs-white);
    }

    .leader-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--cnhs-blue);
    }

    .leader-position {
        font-size: 1rem;
        color: var(--cnhs-gray);
    }

    .leader-badge {
        font-size: 0.9rem;
        color: var(--cnhs-blue);
    }

    .principal-badge {
        background: var(--cnhs-orange);
    }

    .academic-badge {
        background: var(--cnhs-blue);
    }

    .admin-badge {
        background: var(--cnhs-yellow);
    }
</style>
@endsection