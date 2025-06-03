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
                <h2 class="section-title text-center mb-5">Our Academic Tracks</h2>
                <div class="row">
                    <!-- STEM -->
                    <div class="col-md-4 mb-4" data-aos="fade-up">
                        <div class="card h-100 border-0 shadow">
                            <div class="card-body p-4">
                                <h3 class="card-title text-primary">STEM</h3>
                                <p class="card-text">Science, Technology, Engineering, and Mathematics track prepares students for careers in:</p>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Engineering</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Medicine</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Architecture</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Information Technology</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- HUMSS -->
                    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="card h-100 border-0 shadow">
                            <div class="card-body p-4">
                                <h3 class="card-title text-primary">HUMSS</h3>
                                <p class="card-text">Humanities and Social Sciences track focuses on:</p>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Education</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Law</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Communication Arts</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Social Sciences</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- ABM -->
                    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="card h-100 border-0 shadow">
                            <div class="card-body p-4">
                                <h3 class="card-title text-primary">ABM</h3>
                                <p class="card-text">Accountancy, Business and Management track prepares for:</p>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Business Administration</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Accountancy</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Economics</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Entrepreneurship</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Facilities Section -->
        <section class="bg-light py-5">
            <div class="container">
                <h2 class="section-title text-center mb-5">Our Facilities</h2>
                <div class="row">
                    <div class="col-md-6 mb-4" data-aos="fade-right">
                        <div class="card border-0 shadow">
                            <img src="{{ asset('images/library.jpg') }}" class="card-img-top" alt="Library">
                            <div class="card-body">
                                <h4>Modern Library</h4>
                                <p class="text-muted">Our library is equipped with the latest resources and digital facilities.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4" data-aos="fade-left">
                        <div class="card border-0 shadow">
                            <img src="{{ asset('images/laboratory.jpg') }}" class="card-img-top" alt="Science Laboratory">
                            <div class="card-body">
                                <h4>Science Laboratories</h4>
                                <p class="text-muted">State-of-the-art laboratories for hands-on learning experience.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Curriculum Overview Section -->
        <section class="py-5">
            <div class="container">
                <h2 class="section-title text-center mb-5" data-aos="fade-up">Curriculum Overview</h2>
                <div class="row">
                    <div class="col-lg-6 mb-4" data-aos="fade-right">
                        <div class="card border-0 shadow">
                            <div class="card-body p-4">
                                <h4 class="mb-4">Core Subjects</h4>
                                <div class="curriculum-item mb-3">
                                    <h5 class="text-primary">Languages</h5>
                                    <p class="text-muted">English, Filipino, Literature</p>
                                </div>
                                <div class="curriculum-item mb-3">
                                    <h5 class="text-primary">Mathematics</h5>
                                    <p class="text-muted">General Mathematics, Statistics, Pre-Calculus</p>
                                </div>
                                <div class="curriculum-item mb-3">
                                    <h5 class="text-primary">Sciences</h5>
                                    <p class="text-muted">Earth Science, Biology, Chemistry, Physics</p>
                                </div>
                                <div class="curriculum-item">
                                    <h5 class="text-primary">Social Sciences</h5>
                                    <p class="text-muted">History, Economics, Contemporary Issues</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4" data-aos="fade-left">
                        <div class="card border-0 shadow">
                            <div class="card-body p-4">
                                <h4 class="mb-4">Special Programs</h4>
                                <div class="curriculum-item mb-3">
                                    <h5 class="text-primary">Research Program</h5>
                                    <p class="text-muted">Practical Research, Capstone Projects</p>
                                </div>
                                <div class="curriculum-item mb-3">
                                    <h5 class="text-primary">Technology Integration</h5>
                                    <p class="text-muted">Computer Programming, Digital Arts</p>
                                </div>
                                <div class="curriculum-item mb-3">
                                    <h5 class="text-primary">Arts and Sports</h5>
                                    <p class="text-muted">Physical Education, Music, Visual Arts</p>
                                </div>
                                <div class="curriculum-item">
                                    <h5 class="text-primary">Life Skills</h5>
                                    <p class="text-muted">Values Education, Career Guidance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Academic Calendar Section -->
        <section class="bg-light py-5">
            <div class="container">
                <h2 class="section-title text-center mb-5" data-aos="fade-up">Academic Calendar</h2>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow" data-aos="fade-up">
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Event</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>First Semester Begins</td>
                                                <td>August 1, 2024</td>
                                            </tr>
                                            <tr>
                                                <td>Midterm Examinations</td>
                                                <td>October 15-19, 2024</td>
                                            </tr>
                                            <tr>
                                                <td>Christmas Break</td>
                                                <td>December 20, 2024 - January 3, 2025</td>
                                            </tr>
                                            <tr>
                                                <td>Second Semester Begins</td>
                                                <td>January 6, 2025</td>
                                            </tr>
                                            <tr>
                                                <td>Final Examinations</td>
                                                <td>March 24-28, 2025</td>
                                            </tr>
                                            <tr>
                                                <td>Graduation Ceremony</td>
                                                <td>April 15, 2025</td>
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
    .program-icon {
        width: 80px;
        height: 80px;
        line-height: 80px;
        border-radius: 50%;
        background: rgba(26, 35, 126, 0.1);
        margin: 0 auto;
    }

    .facility-img {
        height: 250px;
        object-fit: cover;
    }

    .curriculum-item {
        padding: 15px;
        border-radius: 10px;
        background: rgba(26, 35, 126, 0.05);
        margin-bottom: 15px;
    }

    .curriculum-item h5 {
        margin-bottom: 5px;
    }

    .curriculum-item p {
        margin-bottom: 0;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        background: var(--primary-color);
        color: white;
    }

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