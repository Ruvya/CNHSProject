@extends('Principal.layouts.app')

@section('content')
    <!-- Announcements Hero Section -->
    <div class="hero-section-small d-flex align-items-center">
        <div class="container">
            <div class="hero-content text-center" data-aos="fade-up">
                <h1 class="hero-title mb-4">Announcements</h1>
                <p class="hero-subtitle">Stay Updated with CNHS News and Events</p>
            </div>
        </div>
    </div>

    <!-- Announcements Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Featured Announcement -->
                <div class="col-12 mb-5" data-aos="fade-up">
                    <div class="card border-0 shadow">
                        <img src="{{ asset('images/featured-announcement.jpg') }}" class="card-img-top" alt="Featured Announcement">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary">Featured</span>
                                <small class="text-muted">Posted on May 15, 2024</small>
                            </div>
                            <h3 class="card-title">School Year 2024-2025 Enrollment Now Open</h3>
                            <p class="card-text">We are pleased to announce that enrollment for the upcoming school year 2024-2025 is now open. We invite all prospective students and their parents to join our growing academic community.</p>
                            <a href="#" class="btn btn-custom">Read More</a>
                        </div>
                    </div>
                </div>

                <!-- Regular Announcements -->
                <div class="col-md-6 mb-4" data-aos="fade-up">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-success">Academic</span>
                                <small class="text-muted">Posted on May 10, 2024</small>
                            </div>
                            <h4 class="card-title">Final Examination Schedule Released</h4>
                            <p class="card-text">The schedule for final examinations has been finalized. Please check the detailed schedule to prepare accordingly.</p>
                            <a href="#" class="btn btn-link text-primary p-0">Learn More →</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-info">Event</span>
                                <small class="text-muted">Posted on May 8, 2024</small>
                            </div>
                            <h4 class="card-title">Annual Science Fair 2024</h4>
                            <p class="card-text">Join us for our annual Science Fair where students showcase their innovative projects and research findings.</p>
                            <a href="#" class="btn btn-link text-primary p-0">Learn More →</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4" data-aos="fade-up">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-warning">Sports</span>
                                <small class="text-muted">Posted on May 5, 2024</small>
                            </div>
                            <h4 class="card-title">Intramural Games Schedule</h4>
                            <p class="card-text">The annual intramural games will begin next week. Check out the complete schedule of sporting events.</p>
                            <a href="#" class="btn btn-link text-primary p-0">Learn More →</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-danger">Important</span>
                                <small class="text-muted">Posted on May 3, 2024</small>
                            </div>
                            <h4 class="card-title">Parent-Teacher Conference</h4>
                            <p class="card-text">The first quarter Parent-Teacher Conference is scheduled for next month. Save the date and time.</p>
                            <a href="#" class="btn btn-link text-primary p-0">Learn More →</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
@endsection

@section('styles')
<style>
    .card {
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .badge {
        padding: 8px 12px;
        font-weight: 500;
    }

    .btn-custom {
        background-color: var(--primary-color);
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .btn-custom:hover {
        background-color: var(--primary-dark);
        color: white;
    }
</style>
@endsection 