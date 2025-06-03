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
    </div>

    <!-- History Section -->@extends('Principal.layouts.app')

@section('styles')
    <style>
        .hero-section-small {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset("images/im.jpg") }}');
            background-size: cover;
            background-position: center;
            padding: 80px 0;
            margin-top: -20px;
            color: white;
        }
            .hero-title {
        color: #fff;
        font-size: 2.5rem;
        font-weight: 700;
    }

    .hero-subtitle {
        color: #fff;
        font-size: 1.2rem;
        opacity: 0.9;
    }
    </style>
@endsection

@section('content')
    <!-- About Hero Section -->
    <div class="hero-section-small d-flex align-items-center">
        <div class="container">
            <div class="hero-content text-center" data-aos="fade-up">
                <h1 class="hero-title mb-4">About CNHS</h1>
                <p class="hero-subtitle">Discover Our History and Vision</p>
            </div>
        </div>
    </div>

    <!-- History Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6" data-aos="fade-right">
                    <h2 class="section-title">Our History</h2>
                    <p class="text-muted">Founded in 1964, Calingcaguing National High School has been a beacon of academic excellence in the region for over five decades. What started as a small community school has grown into one of the most prestigious educational institutions in Camarines Norte.</p>
                    <p class="text-muted">Throughout the years, CNHS has consistently produced graduates who have gone on to become leaders in various fields, contributing significantly to the nation's development.</p>
               
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4" data-aos="fade-up">
                    <div class="card h-100 border-0 shadow">
                        <div class="card-body p-4">
                            <h3 class="card-title text-primary mb-4">Our Mission</h3>
                            <p class="card-text">To provide quality education that develops well-rounded individuals equipped with knowledge, skills, and values necessary to meet the challenges of a rapidly changing world while maintaining their cultural identity and social responsibility.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow">
                        <div class="card-body p-4">
                            <h3 class="card-title text-primary mb-4">Our Vision</h3>
                            <p class="card-text">To be a leading educational institution that nurtures future leaders and innovators who are globally competitive, socially responsible, and deeply rooted in Filipino values.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Our Core Values</h2>
            <div class="row">
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-center">
                        <i class="fas fa-star fa-3x text-primary mb-3"></i>
                        <h4>Excellence</h4>
                        <p class="text-muted">Striving for the highest standards in everything we do</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-center">
                        <i class="fas fa-heart fa-3x text-primary mb-3"></i>
                        <h4>Integrity</h4>
                        <p class="text-muted">Upholding honesty and moral principles</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-center">
                        <i class="fas fa-hands-helping fa-3x text-primary mb-3"></i>
                        <h4>Service</h4>
                        <p class="text-muted">Dedicated to serving our community</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-center">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h4>Unity</h4>
                        <p class="text-muted">Working together towards common goals</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Section -->
    <section class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">School Leadership</h2>
            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow">
                        <img src="{{ asset('images/principal.jpg') }}" class="card-img-top" alt="School Principal">
                        <div class="card-body text-center">
                            <h5 class="card-title">Dr. Juan Dela Cruz</h5>
                            <p class="text-muted">School Principal</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow">
                        <img src="{{ asset('images/vice-principal.jpg') }}" class="card-img-top" alt="Vice Principal">
                        <div class="card-body text-center">
                            <h5 class="card-title">Ms. Maria Santos</h5>
                            <p class="text-muted">Vice Principal for Academics</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow">
                        <img src="{{ asset('images/admin-head.jpg') }}" class="card-img-top" alt="Administrative Head">
                        <div class="card-body text-center">
                            <h5 class="card-title">Mr. Pedro Reyes</h5>
                            <p class="text-muted">Administrative Head</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6" data-aos="fade-right">
                    <h2 class="section-title">Our History</h2>
                    <p class="text-muted">Founded in 1964, Calingcaguing National High School has been a beacon of academic excellence in the region for over five decades. What started as a small community school has grown into one of the most prestigious educational institutions in Camarines Norte.</p>
                    <p class="text-muted">Throughout the years, CNHS has consistently produced graduates who have gone on to become leaders in various fields, contributing significantly to the nation's development.</p>
               
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4" data-aos="fade-up">
                    <div class="card h-100 border-0 shadow">
                        <div class="card-body p-4">
                            <h3 class="card-title text-primary mb-4">Our Mission</h3>
                            <p class="card-text">To provide quality education that develops well-rounded individuals equipped with knowledge, skills, and values necessary to meet the challenges of a rapidly changing world while maintaining their cultural identity and social responsibility.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow">
                        <div class="card-body p-4">
                            <h3 class="card-title text-primary mb-4">Our Vision</h3>
                            <p class="card-text">To be a leading educational institution that nurtures future leaders and innovators who are globally competitive, socially responsible, and deeply rooted in Filipino values.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Our Core Values</h2>
            <div class="row">
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-center">
                        <i class="fas fa-star fa-3x text-primary mb-3"></i>
                        <h4>Excellence</h4>
                        <p class="text-muted">Striving for the highest standards in everything we do</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-center">
                        <i class="fas fa-heart fa-3x text-primary mb-3"></i>
                        <h4>Integrity</h4>
                        <p class="text-muted">Upholding honesty and moral principles</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-center">
                        <i class="fas fa-hands-helping fa-3x text-primary mb-3"></i>
                        <h4>Service</h4>
                        <p class="text-muted">Dedicated to serving our community</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-center">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h4>Unity</h4>
                        <p class="text-muted">Working together towards common goals</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Section -->
    <section class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">School Leadership</h2>
            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow">
                        <img src="{{ asset('images/principal.jpg') }}" class="card-img-top" alt="School Principal">
                        <div class="card-body text-center">
                            <h5 class="card-title">Dr. Juan Dela Cruz</h5>
                            <p class="text-muted">School Principal</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow">
                        <img src="{{ asset('images/vice-principal.jpg') }}" class="card-img-top" alt="Vice Principal">
                        <div class="card-body text-center">
                            <h5 class="card-title">Ms. Maria Santos</h5>
                            <p class="text-muted">Vice Principal for Academics</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow">
                        <img src="{{ asset('images/admin-head.jpg') }}" class="card-img-top" alt="Administrative Head">
                        <div class="card-body text-center">
                            <h5 class="card-title">Mr. Pedro Reyes</h5>
                            <p class="text-muted">Administrative Head</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 