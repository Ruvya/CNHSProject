@extends('Principal.layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <div class="hero-section d-flex align-items-center">
        <div class="container">
            <div class="hero-content text-center" data-aos="fade-up">
                <h1 class="hero-title mb-4">Welcome to Calingcaguing National High School</h1>
                <p class="hero-subtitle">Empowering Education Through Innovation</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Welcome Section -->
        <section class="mb-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <h2 class="section-title">Welcome to CNHS</h2>
                    <p>Calingcaguing National High School (CNHS) is a premier educational institution committed to academic excellence and holistic development of students. Established in 1985, we have been serving the community for over 35 years.</p>
                    <p>Our mission is to provide quality education that prepares students for higher learning and equips them with the skills needed for the 21st century.</p>
                    <a href="{{ route('principal.about') }}" class="btn btn-primary">Learn More About Us</a>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <img src="{{ asset('images/cal.jpg') }}" alt="School Building" class="img-fluid rounded shadow">
                </div>
            </div>
        </section>

        <!-- Key Features Section -->
        <section class="mb-5">
            <h2 class="text-center mb-4" data-aos="fade-up">Why Choose CNHS?</h2>
            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-graduation-cap fa-3x text-primary"></i>
                            </div>
                            <h5 class="card-title">Academic Excellence</h5>
                            <p class="card-text">Our school consistently achieves high passing rates in national examinations, with many students receiving academic distinctions.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-chalkboard-teacher fa-3x text-primary"></i>
                            </div>
                            <h5 class="card-title">Qualified Faculty</h5>
                            <p class="card-text">Our teachers are highly qualified professionals dedicated to providing quality education and mentorship to students.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-laptop fa-3x text-primary"></i>
                            </div>
                            <h5 class="card-title">Modern Facilities</h5>
                            <p class="card-text">We provide modern classrooms, computer laboratories, science labs, and a library equipped with the latest resources.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Announcements Preview -->
        <section class="mb-5">
            <div class="row">
                <div class="col-lg-8 mb-4 mb-lg-0" data-aos="fade-up">
                    <h2 class="section-title">Latest Announcements</h2>
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Enrollment for School Year 2024-2025 Now Open</h5>
                            <p class="card-text text-muted mb-2"><small><i class="far fa-calendar-alt me-1"></i> June 1, 2024</small></p>
                            <p class="card-text">We are now accepting applications for the upcoming school year. Please visit our registrar's office for more information.</p>
                            <a href="#" class="btn btn-sm btn-outline-primary">Read More</a>
                        </div>
                    </div>
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">National Achievement Test Results</h5>
                            <p class="card-text text-muted mb-2"><small><i class="far fa-calendar-alt me-1"></i> May 15, 2024</small></p>
                            <p class="card-text">Congratulations to our students for achieving an average score of 92% in the recent National Achievement Test!</p>
                            <a href="#" class="btn btn-sm btn-outline-primary">Read More</a>
                        </div>
                    </div>
                    <a href="{{ route('principal.news') }}" class="btn btn-primary">View All Announcements</a>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <h2 class="section-title">Upcoming Events</h2>
                    <div class="list-group border-0 shadow-sm">
                        <div class="list-group-item border-0 mb-2">
                            <h6 class="mb-1">Foundation Day Celebration</h6>
                            <p class="text-muted mb-0"><small><i class="far fa-calendar-alt me-1"></i> July 15, 2024</small></p>
                        </div>
                        <div class="list-group-item border-0 mb-2">
                            <h6 class="mb-1">Science Fair</h6>
                            <p class="text-muted mb-0"><small><i class="far fa-calendar-alt me-1"></i> August 5-7, 2024</small></p>
                        </div>
                        <div class="list-group-item border-0 mb-2">
                            <h6 class="mb-1">Parent-Teacher Conference</h6>
                            <p class="text-muted mb-0"><small><i class="far fa-calendar-alt me-1"></i> August 20, 2024</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics Section -->
        <section class="py-5 bg-light rounded" data-aos="fade-up">
            <div class="container">
                <h2 class="text-center mb-5">CNHS By The Numbers</h2>
                <div class="row text-center">
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="stat-item">
                            <h2 class="counter text-primary">1,200+</h2>
                            <p class="text-muted">Students</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="stat-item">
                            <h2 class="counter text-primary">60+</h2>
                            <p class="text-muted">Faculty Members</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="stat-item">
                            <h2 class="counter text-primary">95%</h2>
                            <p class="text-muted">Graduation Rate</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <h2 class="counter text-primary">35+</h2>
                            <p class="text-muted">Years of Excellence</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Call to Action Section -->
    <section class="cta-section py-5 text-white text-center">
        <div class="container">
            <h2 class="mb-4" data-aos="fade-up">Ready to Join Our Community?</h2>
            <p class="mb-4" data-aos="fade-up" data-aos-delay="100">Discover the opportunities waiting for you at Calingcaguing National High School.</p>
            <a href="{{ route('principal.contact') }}" class="btn btn-light btn-lg" data-aos="fade-up" data-aos-delay="200">Contact Us Today</a>
        </div>
    </section>
@endsection

@section('styles')
<style>
    .hero-section {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset("images/im.jpg") }}');
        background-size: cover;
        background-position: center;
        padding: 80px 0;
        margin-top: -20px;
        min-height: 60vh;
    }

    .hero-title {
        font-size: 3rem;
        font-weight: 700;
        color: #fff;
    }

    .hero-subtitle {
        font-size: 1.5rem;
        opacity: 0.9;
        color: #fff;
    }

    .section-title {
        position: relative;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .section-title:after {
        content: '';
        display: block;
        width: 50px;
        height: 3px;
        background: var(--primary-color, #0d6efd);
        margin-top: 10px;
    }

    .feature-icon {
        color: var(--primary-color, #0d6efd);
    }

    .quick-link-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .quick-link-card:hover {
        transform: translateY(-10px);
    }

    .cta-section {
        background: linear-gradient(45deg, #0d6efd, #0a58ca);
    }

    .counter {
        font-size: 2.5rem;
        font-weight: 700;
    }

    .stat-item {
        padding: 20px;
    }
</style>
@endsection

