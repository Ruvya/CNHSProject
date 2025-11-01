@extends('Principal.layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Clean Hero Section -->
    <div class="hero-section d-flex align-items-center">
        <div class="container">
            <div class="hero-content text-center" data-aos="fade-up">
                <h1 class="hero-title mb-4"><i class="fas fa-school me-2"></i>Welcome to Calingcaguing National High School</h1>
                <p class="hero-subtitle">Dedicated to Academic Excellence and Student Success</p>
                <div class="hero-buttons mt-4">
                    <a href="{{ route('principal.about') }}" class="btn btn-hero-primary me-3"><i class="fas fa-info-circle me-1"></i>About Us</a>
                    <a href="{{ route('principal.contact') }}" class="btn btn-hero-secondary"><i class="fas fa-envelope me-1"></i>Contact</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Welcome Section -->
        <section class="mb-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <div class="welcome-content">
                        <h2 class="section-title"><i class="fas fa-door-open me-2"></i>Welcome to CNHS</h2>
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
                    <div class="image-wrapper" style="max-width: 420px; margin: 0 auto;">
                        <img src="{{ asset('images/cal.jpg') }}" alt="School Building" class="img-fluid rounded shadow welcome-image" style="width: 100%; max-width: 420px; height: 260px; object-fit: cover;">
                        <div class="image-overlay">
                            <div class="overlay-content">
                                <span>Excellence in Education</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Core Values Section -->
        <section class="mb-5 core-values-section">
            <div class="container">
                <h2 class="text-center mb-5 section-header" data-aos="fade-up"><i class="fas fa-gem me-2"></i>Our Core Values</h2>
                <p class="text-center mb-5 values-subtitle" data-aos="fade-up" data-aos-delay="100">
                    The fundamental principles that guide our educational mission and shape our school community
                </p>
                
                <div class="row g-4">
                    <!-- Excellence Value -->
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="value-card excellence-value h-100" data-aos data-aos-delay="100">
                            <div class="value-icon-lively"><i class="fas fa-star"></i></div>
                            <div class="value-content">
                                <h4 class="value-title"><i class="fas fa-star text-warning me-2"></i>Excellence</h4>
                                <div class="value-tagline">Strive for the Best</div>
                                <p class="value-description">
                                    We strive for the highest standards in all aspects of education, encouraging students to reach their full potential and achieve academic distinction.
                                </p>
                                <div class="value-highlight excellence-highlight">
                                    <span>Academic Achievement</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Integrity Value -->
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="value-card integrity-value h-100" data-aos data-aos-delay="200">
                            <div class="value-icon-lively"><i class="fas fa-balance-scale"></i></div>
                            <div class="value-content">
                                <h4 class="value-title"><i class="fas fa-balance-scale text-primary me-2"></i>Integrity</h4>
                                <div class="value-tagline">Honesty & Ethics</div>
                                <p class="value-description">
                                    We foster honesty, transparency, and ethical behavior in all our interactions, building trust and respect within our school community.
                                </p>
                                <div class="value-highlight integrity-highlight">
                                    <span>Moral Character</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Innovation Value -->
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="value-card innovation-value h-100" data-aos data-aos-delay="300">
                            <div class="value-icon-lively"><i class="fas fa-lightbulb"></i></div>
                            <div class="value-content">
                                <h4 class="value-title"><i class="fas fa-lightbulb text-warning me-2"></i>Innovation</h4>
                                <div class="value-tagline">Creative Solutions</div>
                                <p class="value-description">
                                    We embrace modern teaching methods and technology to create dynamic learning experiences that prepare students for the future.
                                </p>
                                <div class="value-highlight innovation-highlight">
                                    <span>Future-Ready Learning</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Compassion Value -->
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                        <div class="value-card compassion-value h-100" data-aos data-aos-delay="400">
                            <div class="value-icon-lively"><i class="fas fa-heart"></i></div>
                            <div class="value-content">
                                <h4 class="value-title"><i class="fas fa-heart text-danger me-2"></i>Compassion</h4>
                                <div class="value-tagline">Empathy & Care</div>
                                <p class="value-description">
                                    We cultivate empathy, kindness, and understanding, creating a supportive environment where every student feels valued and cared for.
                                </p>
                                <div class="value-highlight compassion-highlight">
                                    <span>Caring Community</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Responsibility Value -->
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="500">
                        <div class="value-card responsibility-value h-100" data-aos data-aos-delay="500">
                            <div class="value-icon-lively"><i class="fas fa-user-shield"></i></div>
                            <div class="value-content">
                                <h4 class="value-title"><i class="fas fa-user-shield text-success me-2"></i>Responsibility</h4>
                                <div class="value-tagline">Accountability</div>
                                <p class="value-description">
                                    We instill accountability and civic duty, empowering students to become responsible citizens who contribute positively to society.
                                </p>
                                <div class="value-highlight responsibility-highlight">
                                    <span>Civic Leadership</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unity Value -->
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="600">
                        <div class="value-card unity-value h-100" data-aos data-aos-delay="600">
                            <div class="value-icon-lively"><i class="fas fa-users"></i></div>
                            <div class="value-content">
                                <h4 class="value-title"><i class="fas fa-users text-info me-2"></i>Unity</h4>
                                <div class="value-tagline">Together as One</div>
                                <p class="value-description">
                                    We celebrate diversity and promote collaboration, building a harmonious community where everyone works together toward common goals.
                                </p>
                                <div class="value-highlight unity-highlight">
                                    <span>Collaborative Spirit</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Key Features Section -->
        <section class="mb-5 features-section">
            <h2 class="text-center mb-5 section-header" data-aos="fade-up"><i class="fas fa-question-circle me-2"></i>Why Choose CNHS?</h2>
            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card academic-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-book fa-2x text-primary mb-3"></i>
                            <h5 class="card-title">Academic Excellence</h5>
                            <p class="card-text">Our school consistently achieves high passing rates in national examinations, with many students receiving academic distinctions.</p>
                            <div class="feature-badge academic-badge">Top Performer</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card faculty-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-chalkboard-teacher fa-2x text-success mb-3"></i>
                            <h5 class="card-title">Qualified Faculty</h5>
                            <p class="card-text">Our teachers are highly qualified professionals dedicated to providing quality education and mentorship to students.</p>
                            <div class="feature-badge faculty-badge">Expert Teachers</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card facilities-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-building fa-2x text-warning mb-3"></i>
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
                <h2 class="text-center mb-5 stats-header"><i class="fas fa-chart-bar me-2"></i>CNHS By The Numbers</h2>
                <div class="row text-center">
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="stat-item students-stat">
                            <i class="fas fa-user-graduate fa-2x mb-2"></i>
                            <h2 class="counter">1,200+</h2>
                            <p class="stat-description">Active Students</p>
                            <div class="stat-progress">
                                <div class="progress-bar students-progress"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="stat-item faculty-stat">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h2 class="counter">60+</h2>
                            <p class="stat-description">Faculty Members</p>
                            <div class="stat-progress">
                                <div class="progress-bar faculty-progress"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="stat-item graduation-stat">
                            <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                            <h2 class="counter">95%</h2>
                            <p class="stat-description">Graduation Rate</p>
                            <div class="stat-progress">
                                <div class="progress-bar graduation-progress"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item excellence-stat">
                            <i class="fas fa-award fa-2x mb-2"></i>
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
    <!-- News & Events Section -->
    <section class="container py-5">
        <h2 class="text-center mb-5 section-header" data-aos="fade-up"><i class="fas fa-newspaper me-2"></i>News & Events</h2>
        <div class="row g-4">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="welcome-content h-100">
                    <h4 class="mb-3"><i class="fas fa-bullhorn me-2 text-primary"></i>Latest Announcements</h4>
                    <ul class="list-unstyled mb-0">
                        @forelse(($recentAnnouncements ?? collect())->take(3) as $a)
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-circle text-primary me-2" style="font-size: 0.5rem; margin-top: 9px;"></i>
                                    <div>
                                        <div class="fw-bold">{{ $a->title }}</div>
                                        <small class="text-muted">{{ $a->created_at->format('M d, Y') }}</small>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="text-muted">No announcements yet.</li>
                        @endforelse
                    </ul>
                    <div class="mt-3">
                        <a href="{{ route('principal.news') }}" class="btn btn-hero-secondary"><i class="fas fa-arrow-right me-1"></i>View all</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="welcome-content h-100">
                    <h4 class="mb-3"><i class="fas fa-calendar-alt me-2 text-success"></i>Upcoming Events</h4>
                    <ul class="list-unstyled mb-0">
                        @forelse(($upcomingEvents ?? collect())->take(3) as $e)
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-circle text-success me-2" style="font-size: 0.5rem; margin-top: 9px;"></i>
                                    <div>
                                        <div class="fw-bold">{{ $e->title }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($e->start)->format('M d, Y g:i A') }}</small>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="text-muted">No upcoming events.</li>
                        @endforelse
                    </ul>
                    <div class="mt-3">
                        <a href="{{ route('principal.news') }}" class="btn btn-hero-secondary"><i class="fas fa-arrow-right me-1"></i>View all</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section py-5 text-white text-center" style="background: var(--cnhs-accent-orange);">
        <div class="container">
            <div class="cta-content">
                <h2 class="mb-4 cta-title" data-aos="fade-up"><i class="fas fa-user-plus me-2"></i>Ready to Join Our Community?</h2>
                <p class="mb-4 cta-subtitle" data-aos="fade-up" data-aos-delay="100">Discover the opportunities waiting for you at Calingcaguing National High School.</p>
                <div class="cta-buttons" data-aos="fade-up" data-aos-delay="200">
                    <a href="{{ route('principal.contact') }}" class="btn btn-cta-primary me-3"><i class="fas fa-phone-alt me-1"></i>Contact Us Today</a>
                    <a href="{{ route('principal.academics') }}" class="btn btn-cta-secondary"><i class="fas fa-book-open me-1"></i>Explore Programs</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('styles')
@parent
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
        --cnhs-purple: #8B5CF6;
        --cnhs-pink: #EC4899;
    }

    /* Clean Hero Section */
    .hero-section {
        background: var(--cnhs-primary-blue);
        padding: 120px 0;
        margin-top: -20px;
        min-height: 70vh;
        position: relative;
        color: white;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--cnhs-white);
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.4);
        margin-bottom: 1.5rem;
        letter-spacing: -0.02em;
    }

    .hero-subtitle {
        font-size: 1.6rem;
        color: var(--cnhs-white);
        font-weight: 400;
        margin-bottom: 2rem;
        letter-spacing: 0.02em;
    }

    .btn-hero-primary {
        background: var(--cnhs-accent-orange);
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-hero-primary:hover {
        background: #e67e00;
        color: white;
    }

    .btn-hero-secondary {
        background: transparent;
        border: 2px solid var(--cnhs-white);
        color: var(--cnhs-white);
        padding: 1rem 2rem;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-hero-secondary:hover {
        background: var(--cnhs-white);
        color: var(--cnhs-primary-blue);
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--cnhs-primary-blue);
        margin-bottom: 2rem;
        position: relative;
        letter-spacing: -0.02em;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 60px;
        height: 4px;
        background: var(--cnhs-accent-orange);
        border-radius: 2px;
    }

    .section-header {
        font-size: 2.8rem;
        font-weight: 800;
        color: var(--cnhs-primary-blue);
        position: relative;
        letter-spacing: -0.02em;
    }

    .section-header::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 5px;
        background: var(--cnhs-accent-orange);
        border-radius: 3px;
    }

    .welcome-content {
        padding: 2rem;
        background: var(--cnhs-white);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-left: 4px solid var(--cnhs-accent-orange);
    }

    .welcome-text {
        font-size: 1.1rem;
        line-height: 1.8;
        color: var(--cnhs-medium-gray);
        margin-bottom: 1.5rem;
        font-weight: 500;
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
        background: var(--cnhs-primary-blue);
        border-radius: 8px;
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
        background: var(--cnhs-accent-orange);
        border: none;
        color: white;
        padding: 0.8rem 2rem;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-cnhs-primary:hover {
        background: #e67e00;
        color: white;
    }

    .image-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
    }

    .welcome-image {
        transition: transform 0.3s ease;
        border-radius: 12px;
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
        background: rgba(30, 58, 138, 0.8);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }

    .image-wrapper:hover .image-overlay {
        opacity: 1;
    }

    .overlay-content {
        text-align: center;
        color: white;
    }

    .overlay-content span {
        font-size: 1.2rem;
        font-weight: 600;
    }

    /* Clean Core Values Section */
    .core-values-section, .features-section {
        background: var(--cnhs-light-gray);
        padding: 5rem 2rem;
        border-radius: 12px;
        margin: 4rem 0;
    }

    .values-subtitle {
        font-size: 1.2rem;
        color: var(--cnhs-medium-gray);
        font-weight: 400;
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .value-card, .feature-card {
        background: var(--cnhs-white);
        border-radius: 12px;
        padding: 2.5rem 2rem;
        transition: all 0.4s ease;
        border: 1px solid #E5E7EB;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        height: 100%;
        border-left: 4px solid var(--cnhs-primary-blue);
    }

    .value-card:hover, .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .value-content {
        text-align: center;
    }

    .value-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--cnhs-primary-blue);
        margin-bottom: 1.5rem;
    }

    .value-description {
        font-size: 1rem;
        line-height: 1.7;
        color: var(--cnhs-medium-gray);
        margin-bottom: 2rem;
        font-weight: 500;
    }

    .value-highlight {
        display: inline-block;
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        color: white;
        font-size: 0.95rem;
        font-weight: 700;
        margin-top: 1.2rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.10);
        transition: box-shadow 0.3s, transform 0.3s, background 0.3s;
        position: relative;
        opacity: 0.95;
    }
    .value-card.excellence-value .value-highlight {
        background: linear-gradient(90deg, #fde047 0%, #facc15 100%);
        color: #7c5700;
        text-shadow: 0 1px 2px #fff6;
    }
    .value-card.integrity-value .value-highlight {
        background: linear-gradient(90deg, #60a5fa 0%, #2563eb 100%);
        color: #fff;
    }
    .value-card.innovation-value .value-highlight {
        background: linear-gradient(90deg, #fdba74 0%, #fb923c 100%);
        color: #7c3f00;
    }
    .value-card.compassion-value .value-highlight {
        background: linear-gradient(90deg, #fca5a5 0%, #ef4444 100%);
        color: #fff;
    }
    .value-card.responsibility-value .value-highlight {
        background: linear-gradient(90deg, #6ee7b7 0%, #10b981 100%);
        color: #065f46;
    }
    .value-card.unity-value .value-highlight {
        background: linear-gradient(90deg, #c4b5fd 0%, #a78bfa 100%);
        color: #3b0764;
    }
    .value-card:hover .value-highlight {
        box-shadow: 0 4px 24px rgba(0,0,0,0.18);
        transform: scale(1.06);
        opacity: 1;
    }
    @keyframes livelyHighlightIn {
        0% { opacity: 0; transform: translateY(20px) scale(0.95); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    .value-highlight {
        animation: livelyHighlightIn 0.7s cubic-bezier(0.23, 1, 0.32, 1) both;
    }

    .value-icon-lively {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 1rem auto;
        font-size: 2.2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .value-card.excellence-value .value-icon-lively { background: #fef9c3; color: #FACC15; }
    .value-card.integrity-value .value-icon-lively { background: #dbeafe; color: #2563eb; }
    .value-card.innovation-value .value-icon-lively { background: #ffedd5; color: #fb923c; }
    .value-card.compassion-value .value-icon-lively { background: #fee2e2; color: #ef4444; }
    .value-card.responsibility-value .value-icon-lively { background: #d1fae5; color: #10b981; }
    .value-card.unity-value .value-icon-lively { background: #ede9fe; color: #a78bfa; }

    .value-tagline {
        font-weight: 700;
        font-size: 1.08rem;
        color: #374151;
        margin-bottom: 0.5rem;
        letter-spacing: 0.01em;
    }

    @keyframes fadeInUpLively {
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .value-card[data-aos] {
        opacity: 0;
        animation: fadeInUpLively 0.8s forwards;
    }
    .value-card[data-aos][data-aos-delay="100"] { animation-delay: 0.1s; }
    .value-card[data-aos][data-aos-delay="200"] { animation-delay: 0.2s; }
    .value-card[data-aos][data-aos-delay="300"] { animation-delay: 0.3s; }
    .value-card[data-aos][data-aos-delay="400"] { animation-delay: 0.4s; }
    .value-card[data-aos][data-aos-delay="500"] { animation-delay: 0.5s; }
    .value-card[data-aos][data-aos-delay="600"] { animation-delay: 0.6s; }

    .stats-section {
        background: var(--cnhs-primary-blue);
        color: white;
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
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        background: rgba(255, 255, 255, 0.2);
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
        background: #E2E8F0;
        border-radius: 2px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        border-radius: 2px;
        background: var(--cnhs-accent-orange);
    }

    .students-progress {
        background: var(--cnhs-accent-orange);
        width: 90%;
    }

    .faculty-progress {
        background: var(--cnhs-gold);
        width: 85%;
    }

    .graduation-progress {
        background: var(--cnhs-success);
        width: 95%;
    }

    .excellence-progress {
        background: var(--cnhs-purple);
        width: 100%;
    }

    .cta-section {
        background: var(--cnhs-accent-orange) !important;
    }

    .cta-title {
        font-size: 3rem;
        font-weight: 800;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .cta-subtitle {
        font-size: 1.3rem;
        font-weight: 300;
    }

    .btn-cta-primary {
        background: var(--cnhs-white);
        color: var(--cnhs-primary-blue);
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 6px;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-cta-primary:hover {
        background: #f0f0f0;
        color: var(--cnhs-primary-blue);
    }

    .btn-cta-secondary {
        background: transparent;
        border: 2px solid var(--cnhs-white);
        color: var(--cnhs-white);
        padding: 1rem 2.5rem;
        border-radius: 6px;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-cta-secondary:hover {
        background: var(--cnhs-white);
        color: var(--cnhs-accent-orange);
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

        .core-values-section {
            padding: 3rem 1rem;
        }

        .value-card {
            padding: 2rem 1.5rem;
            margin-bottom: 2rem;
        }
    }

    /* Add lively styles for Core Values section */
    .value-card {
        border-width: 2px;
        border-style: solid;
        box-shadow: 0 4px 16px rgba(0,0,0,0.07);
        transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
        position: relative;
        overflow: visible;
    }
    .value-card:hover {
        transform: translateY(-10px) scale(1.03);
        box-shadow: 0 12px 32px rgba(0,0,0,0.13);
        z-index: 2;
    }
    .value-card.excellence-value { border-color: #FACC15; }
    .value-card.integrity-value { border-color: #2563eb; }
    .value-card.innovation-value { border-color: #fb923c; }
    .value-card.compassion-value { border-color: #ef4444; }
    .value-card.responsibility-value { border-color: #10b981; }
    .value-card.unity-value { border-color: #a78bfa; }

    .value-icon-lively {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 1rem auto;
        font-size: 2.2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .value-card.excellence-value .value-icon-lively { background: #fef9c3; color: #FACC15; }
    .value-card.integrity-value .value-icon-lively { background: #dbeafe; color: #2563eb; }
    .value-card.innovation-value .value-icon-lively { background: #ffedd5; color: #fb923c; }
    .value-card.compassion-value .value-icon-lively { background: #fee2e2; color: #ef4444; }
    .value-card.responsibility-value .value-icon-lively { background: #d1fae5; color: #10b981; }
    .value-card.unity-value .value-icon-lively { background: #ede9fe; color: #a78bfa; }

    .value-tagline {
        font-weight: 700;
        font-size: 1.08rem;
        color: #374151;
        margin-bottom: 0.5rem;
        letter-spacing: 0.01em;
    }

    @keyframes fadeInUpLively {
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .value-card[data-aos] {
        opacity: 0;
        animation: fadeInUpLively 0.8s forwards;
    }
    .value-card[data-aos][data-aos-delay="100"] { animation-delay: 0.1s; }
    .value-card[data-aos][data-aos-delay="200"] { animation-delay: 0.2s; }
    .value-card[data-aos][data-aos-delay="300"] { animation-delay: 0.3s; }
    .value-card[data-aos][data-aos-delay="400"] { animation-delay: 0.4s; }
    .value-card[data-aos][data-aos-delay="500"] { animation-delay: 0.5s; }
    .value-card[data-aos][data-aos-delay="600"] { animation-delay: 0.6s; }

    @media (max-width: 991px) {
        .image-wrapper {
            max-width: 100% !important;
        }
        .welcome-image {
            max-width: 100% !important;
            height: 180px !important;
        }
    }
    @media (max-width: 576px) {
        .welcome-image {
            height: 120px !important;
        }
    }
</style>
@endsection

@section('scripts')
@parent
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
});
</script>
@endsection