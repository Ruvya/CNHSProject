@extends('Principal.layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <div class="hero-section d-flex align-items-center">
        <div class="container">
            <div class="hero-content text-center" data-aos="fade-up">
                <h1 class="hero-title mb-4">Welcome to Calingcaguing National High School</h1>
                <p class="hero-subtitle">Empowering Education Through Innovation</p>
                <div class="hero-buttons mt-4">
                    <a href="{{ route('principal.about') }}" class="btn btn-hero-primary me-3">Discover Our Story</a>
                    <a href="{{ route('principal.contact') }}" class="btn btn-hero-secondary">Get In Touch</a>
                </div>
            </div>
        </div>
        <div class="hero-decoration">
            <div class="floating-shape shape-1"></div>
            <div class="floating-shape shape-2"></div>
            <div class="floating-shape shape-3"></div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Welcome Section -->
        <section class="mb-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <div class="welcome-content">
                        <h2 class="section-title">🏫 Welcome to CNHS</h2>
                        <p class="welcome-text">Calingcaguing National High School (CNHS) is a premier educational institution committed to academic excellence and holistic development of students. Established in 1985, we have been serving the community for over 35 years.</p>
                        <p class="welcome-text">Our mission is to provide quality education that prepares students for higher learning and equips them with the skills needed for the 21st century.</p>
                        <div class="welcome-stats">
                            <div class="stat-mini">
                                <span class="stat-number">35+</span>
                                <span class="stat-label">Years</span>
                            </div>
                            <div class="stat-mini">
                                <span class="stat-number">1200+</span>
                                <span class="stat-label">Students</span>
                            </div>
                            <div class="stat-mini">
                                <span class="stat-number">95%</span>
                                <span class="stat-label">Success Rate</span>
                            </div>
                        </div>
                        <a href="{{ route('principal.about') }}" class="btn btn-cnhs-primary">Learn More About Us</a>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="image-wrapper">
                        <img src="{{ asset('images/cal.jpg') }}" alt="School Building" class="img-fluid rounded shadow welcome-image">
                        <div class="image-overlay">
                            <div class="overlay-content">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Excellence in Education</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Key Features Section -->
        <section class="mb-5 features-section">
            <h2 class="text-center mb-5 section-header" data-aos="fade-up">🌟 Why Choose CNHS?</h2>
            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card academic-card h-100">
                        <div class="feature-decoration academic-decoration"></div>
                        <div class="card-body text-center">
                            <div class="feature-icon-wrapper academic-icon mb-3">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h5 class="card-title">Academic Excellence</h5>
                            <p class="card-text">Our school consistently achieves high passing rates in national examinations, with many students receiving academic distinctions.</p>
                            <div class="feature-badge academic-badge">Top Performer</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card faculty-card h-100">
                        <div class="feature-decoration faculty-decoration"></div>
                        <div class="card-body text-center">
                            <div class="feature-icon-wrapper faculty-icon mb-3">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <h5 class="card-title">Qualified Faculty</h5>
                            <p class="card-text">Our teachers are highly qualified professionals dedicated to providing quality education and mentorship to students.</p>
                            <div class="feature-badge faculty-badge">Expert Teachers</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card facilities-card h-100">
                        <div class="feature-decoration facilities-decoration"></div>
                        <div class="card-body text-center">
                            <div class="feature-icon-wrapper facilities-icon mb-3">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <h5 class="card-title">Modern Facilities</h5>
                            <p class="card-text">We provide modern classrooms, computer laboratories, science labs, and a library equipped with the latest resources.</p>
                            <div class="feature-badge facilities-badge">State-of-Art</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics Section -->
        <section class="py-5 stats-section rounded" data-aos="fade-up">
            <div class="container">
                <h2 class="text-center mb-5 stats-header">📊 CNHS By The Numbers</h2>
                <div class="row text-center">
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="stat-item students-stat">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h2 class="counter">1,200+</h2>
                            <p class="stat-description">Active Students</p>
                            <div class="stat-progress">
                                <div class="progress-bar students-progress"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="stat-item faculty-stat">
                            <div class="stat-icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <h2 class="counter">60+</h2>
                            <p class="stat-description">Faculty Members</p>
                            <div class="stat-progress">
                                <div class="progress-bar faculty-progress"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="stat-item graduation-stat">
                            <div class="stat-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h2 class="counter">95%</h2>
                            <p class="stat-description">Graduation Rate</p>
                            <div class="stat-progress">
                                <div class="progress-bar graduation-progress"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item excellence-stat">
                            <div class="stat-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h2 class="counter">35+</h2>
                            <p class="stat-description">Years of Excellence</p>
                            <div class="stat-progress">
                                <div class="progress-bar excellence-progress"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Call to Action Section -->
    <section class="cta-section py-5 text-white text-center">
        <div class="container">
            <div class="cta-content">
                <h2 class="mb-4 cta-title" data-aos="fade-up">Ready to Join Our Community?</h2>
                <p class="mb-4 cta-subtitle" data-aos="fade-up" data-aos-delay="100">Discover the opportunities waiting for you at Calingcaguing National High School.</p>
                <div class="cta-buttons" data-aos="fade-up" data-aos-delay="200">
                    <a href="{{ route('principal.contact') }}" class="btn btn-cta-primary me-3">Contact Us Today</a>
                    <a href="{{ route('principal.academics') }}" class="btn btn-cta-secondary">Explore Programs</a>
                </div>
            </div>
        </div>
        <div class="cta-decoration">
            <div class="cta-shape cta-shape-1"></div>
            <div class="cta-shape cta-shape-2"></div>
        </div>
    </section>
@endsection

@section('styles')
@parent
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
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

    .hero-section {
        background: linear-gradient(135deg, var(--cnhs-blue) 0%, var(--cnhs-light-blue) 50%, var(--cnhs-orange) 100%);
        padding: 120px 0;
        margin-top: -20px;
        min-height: 70vh;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
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
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--cnhs-white);
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        animation: heroTitleAnimation 1.5s ease-out;
        margin-bottom: 1.5rem;
    }

    .hero-subtitle {
        font-size: 1.6rem;
        opacity: 0.95;
        color: var(--cnhs-white);
        font-weight: 300;
        animation: heroSubtitleAnimation 1.5s ease-out 0.3s both;
        margin-bottom: 2rem;
    }

    @keyframes heroTitleAnimation {
        0% { opacity: 0; transform: translateY(50px) scale(0.8); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes heroSubtitleAnimation {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .hero-buttons {
        animation: heroButtonsAnimation 1.5s ease-out 0.6s both;
    }

    @keyframes heroButtonsAnimation {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .btn-hero-primary {
        background: linear-gradient(135deg, var(--cnhs-orange), #FF6B00);
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3);
    }

    .btn-hero-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(255, 140, 0, 0.4);
        color: white;
    }

    .btn-hero-secondary {
        background: transparent;
        border: 2px solid var(--cnhs-white);
        color: var(--cnhs-white);
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-hero-secondary:hover {
        background: var(--cnhs-white);
        color: var(--cnhs-blue);
        transform: translateY(-3px);
    }

    .floating-shape {
        position: absolute;
        border-radius: 50%;
        opacity: 0.1;
        animation: float 6s ease-in-out infinite;
    }

    .shape-1 {
        width: 100px;
        height: 100px;
        background: var(--cnhs-orange);
        top: 20%;
        right: 10%;
        animation-delay: 0s;
    }

    .shape-2 {
        width: 150px;
        height: 150px;
        background: var(--cnhs-yellow);
        bottom: 20%;
        left: 5%;
        animation-delay: 2s;
    }

    .shape-3 {
        width: 80px;
        height: 80px;
        background: var(--cnhs-white);
        top: 60%;
        right: 20%;
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

    .welcome-content {
        padding: 2rem;
        background: linear-gradient(145deg, var(--cnhs-white), var(--cnhs-light-gray));
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .welcome-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(135deg, var(--cnhs-orange), var(--cnhs-blue));
    }

    .welcome-text {
        font-size: 1.1rem;
        line-height: 1.8;
        color: var(--cnhs-gray);
        margin-bottom: 1.5rem;
    }

    .welcome-stats {
        display: flex;
        gap: 2rem;
        margin: 2rem 0;
        flex-wrap: wrap;
    }

    .stat-mini {
        text-align: center;
        padding: 1rem;
        background: linear-gradient(135deg, var(--cnhs-light-blue), var(--cnhs-blue));
        border-radius: 15px;
        color: white;
        min-width: 80px;
    }

    .stat-number {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .btn-cnhs-primary {
        background: linear-gradient(135deg, var(--cnhs-orange), #FF6B00);
        border: none;
        color: white;
        padding: 0.8rem 2rem;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-cnhs-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 140, 0, 0.3);
        color: white;
    }

    .image-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
    }

    .welcome-image {
        transition: transform 0.3s ease;
        border-radius: 20px;
    }

    .image-wrapper:hover .welcome-image {
        transform: scale(1.05);
    }

    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.8), rgba(255, 140, 0, 0.8));
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
    }

    .image-wrapper:hover .image-overlay {
        opacity: 1;
    }

    .overlay-content {
        text-align: center;
        color: white;
    }

    .overlay-content i {
        font-size: 3rem;
        margin-bottom: 1rem;
        display: block;
    }

    .overlay-content span {
        font-size: 1.2rem;
        font-weight: 600;
    }

    .features-section {
        background: linear-gradient(135deg, var(--cnhs-light-gray), #E5E7EB);
        padding: 4rem 2rem;
        border-radius: 30px;
        margin: 3rem 0;
    }

    .feature-card {
        background: linear-gradient(145deg, var(--cnhs-white), #F9FAFB);
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .feature-card:hover {
        transform: translateY(-15px) scale(1.02);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    .feature-decoration {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        border-radius: 20px 20px 0 0;
    }

    .academic-decoration {
        background: linear-gradient(135deg, var(--cnhs-orange), #FF6B00);
    }

    .faculty-decoration {
        background: linear-gradient(135deg, var(--cnhs-blue), var(--cnhs-light-blue));
    }

    .facilities-decoration {
        background: linear-gradient(135deg, var(--cnhs-yellow), var(--cnhs-orange));
    }

    .feature-icon-wrapper {
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

    .academic-icon {
        background: linear-gradient(135deg, #FFF7ED, #FED7AA);
        color: var(--cnhs-orange);
    }

    .faculty-icon {
        background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
        color: var(--cnhs-blue);
    }

    .facilities-icon {
        background: linear-gradient(135deg, #FFFBEB, #FEF3C7);
        color: #D97706;
    }

    .feature-card:hover .feature-icon-wrapper {
        transform: rotate(360deg) scale(1.2);
    }

    .academic-card:hover .academic-icon {
        background: linear-gradient(135deg, var(--cnhs-orange), #FF6B00);
        color: white;
    }

    .faculty-card:hover .faculty-icon {
        background: linear-gradient(135deg, var(--cnhs-blue), var(--cnhs-light-blue));
        color: white;
    }

    .facilities-card:hover .facilities-icon {
        background: linear-gradient(135deg, var(--cnhs-yellow), var(--cnhs-orange));
        color: white;
    }

    .feature-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: white;
    }

    .academic-badge {
        background: linear-gradient(135deg, var(--cnhs-orange), #FF6B00);
    }

    .faculty-badge {
        background: linear-gradient(135deg, var(--cnhs-blue), var(--cnhs-light-blue));
    }

    .facilities-badge {
        background: linear-gradient(135deg, var(--cnhs-yellow), var(--cnhs-orange));
    }

    .stats-section {
        background: linear-gradient(135deg, var(--cnhs-blue), var(--cnhs-light-blue));
        color: white;
        position: relative;
        overflow: hidden;
    }

    .stats-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
        background-size: 50px 50px;
        animation: dotMove 15s linear infinite;
    }

    @keyframes dotMove {
        0% { transform: translate(0, 0); }
        100% { transform: translate(50px, 50px); }
    }

    .stats-header {
        color: white;
        font-size: 2.5rem;
        font-weight: 800;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .stat-item {
        padding: 2rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-item:hover {
        transform: translateY(-10px);
        background: rgba(255, 255, 255, 0.2);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: var(--cnhs-orange);
    }

    .counter {
        font-size: 3rem;
        font-weight: 800;
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        margin-bottom: 0.5rem;
    }

    .stat-description {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }

    .stat-progress {
        height: 4px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 2px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        border-radius: 2px;
        animation: progressAnimation 2s ease-in-out;
    }

    .students-progress {
        background: linear-gradient(135deg, var(--cnhs-orange), #FF6B00);
        width: 90%;
    }

    .faculty-progress {
        background: linear-gradient(135deg, var(--cnhs-yellow), var(--cnhs-orange));
        width: 85%;
    }

    .graduation-progress {
        background: linear-gradient(135deg, #10B981, #059669);
        width: 95%;
    }

    .excellence-progress {
        background: linear-gradient(135deg, #8B5CF6, #7C3AED);
        width: 100%;
    }

    @keyframes progressAnimation {
        0% { width: 0%; }
        100% { width: var(--progress-width, 100%); }
    }

    .cta-section {
        background: linear-gradient(135deg, var(--cnhs-orange) 0%, #FF6B00 50%, var(--cnhs-blue) 100%);
        position: relative;
        overflow: hidden;
    }

    .cta-title {
        font-size: 3rem;
        font-weight: 800;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .cta-subtitle {
        font-size: 1.3rem;
        opacity: 0.95;
        font-weight: 300;
    }

    .btn-cta-primary {
        background: var(--cnhs-white);
        color: var(--cnhs-blue);
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
    }

    .btn-cta-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.4);
        color: var(--cnhs-blue);
    }

    .btn-cta-secondary {
        background: transparent;
        border: 2px solid var(--cnhs-white);
        color: var(--cnhs-white);
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-cta-secondary:hover {
        background: var(--cnhs-white);
        color: var(--cnhs-orange);
        transform: translateY(-3px);
    }

    .cta-shape {
        position: absolute;
        border-radius: 50%;
        opacity: 0.1;
        animation: ctaFloat 8s ease-in-out infinite;
    }

    .cta-shape-1 {
        width: 200px;
        height: 200px;
        background: var(--cnhs-white);
        top: 10%;
        right: 5%;
        animation-delay: 0s;
    }

    .cta-shape-2 {
        width: 150px;
        height: 150px;
        background: var(--cnhs-yellow);
        bottom: 10%;
        left: 10%;
        animation-delay: 4s;
    }

    @keyframes ctaFloat {
        0%, 100% { transform: translateY(0px) scale(1); }
        50% { transform: translateY(-30px) scale(1.1); }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .section-header {
            font-size: 2.2rem;
        }

        .welcome-stats {
            justify-content: center;
        }

        .hero-buttons .btn {
            display: block;
            margin: 0.5rem 0;
        }

        .cta-title {
            font-size: 2.2rem;
        }

        .cta-buttons .btn {
            display: block;
            margin: 0.5rem 0;
        }
    }

    /* Calendar Styles */
    #index-school-calendar {
        max-width: 900px;
        margin: 0 auto;
        background: var(--cnhs-white);
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.07);
        padding: 20px;
    }

    .fc-toolbar-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--cnhs-blue);
    }

    .fc-daygrid-event {
        background: linear-gradient(90deg, var(--cnhs-blue) 60%, var(--cnhs-light-blue) 100%) !important;
        color: var(--cnhs-white) !important;
        border: none !important;
        border-radius: 6px !important;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(30, 58, 138, 0.08);
    }

    .fc-daygrid-event-dot {
        border-color: var(--cnhs-blue) !important;
    }

    .fc .fc-button-primary {
        background: var(--cnhs-blue);
        border: none;
        border-radius: 6px;
        font-weight: 500;
    }

    .fc .fc-button-primary:hover {
        background: var(--cnhs-light-blue);
    }
</style>
@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Counter Animation
    const counters = document.querySelectorAll('.counter');
    const animateCounter = (counter) => {
        const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
        const increment = target / 100;
        let current = 0;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.ceil(current) + (counter.textContent.includes('%') ? '%' : '+');
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = counter.textContent;
            }
        };
        updateCounter();
    };

    // Intersection Observer for counter animation
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target.querySelector('.counter');
                if (counter) {
                    animateCounter(counter);
                }
            }
        });
    });

    document.querySelectorAll('.stat-item').forEach(item => {
        observer.observe(item);
    });

    // Calendar initialization
    var calendarEl = document.getElementById('index-school-calendar');
    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: {
                url: '{{ route('principal.events.index') }}',
                method: 'GET',
                failure: function() {
                    alert('There was an error while fetching events!');
                }
            },
            eventColor: '#1E3A8A',
            nowIndicator: true,
            selectable: false,
            editable: false,
            eventDisplay: 'block',
        });
        calendar.render();
    }
});
</script>
@endsection