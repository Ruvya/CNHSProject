@extends('Principal.layouts.app')

@section('title', 'Contact Us')

@section('content')
    <!-- Hero Section -->
    <div class="hero-section-small d-flex align-items-center mb-5">
        <div class="container">
            <div class="hero-content text-center" data-aos="fade-up">
                <h1 class="hero-title mb-4">Contact Us</h1>
                <p class="hero-subtitle">Get in Touch with CNHS</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Contact Information Section -->
        <section class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 mb-4" data-aos="fade-right">
                        <h2 class="section-title">Contact Information</h2>
                        <div class="contact-info">
                            <div class="mb-4">
                                <h5><i class="fas fa-map-marker-alt text-primary me-2"></i>Address</h5>
                                <p class="text-muted">Calingcaguing National High School<br>Calingcaguing Barugo, Philippines</p>
                            </div>
                            <div class="mb-4">
                                <h5><i class="fas fa-phone text-primary me-2"></i>Phone</h5>
                                <p class="text-muted">(123) 456-7890</p>
                            </div>
                            <div class="mb-4">
                                <h5><i class="fas fa-envelope text-primary me-2"></i>Email</h5>
                                <p class="text-muted">info@cnhs.edu.ph</p>
                            </div>
                            <div class="mb-4">
                                <h5><i class="fas fa-clock text-primary me-2"></i>Office Hours</h5>
                                <p class="text-muted">
                                    Monday - Friday: 7:00 AM - 5:00 PM<br>
                                    Saturday: 8:00 AM - 12:00 PM<br>
                                    Sunday: Closed
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4" data-aos="fade-left">
                        <div class="card border-0 shadow">
                            <div class="card-body p-4">
                                <h4 class="card-title mb-4">Send us a Message</h4>
                                <form>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="subject" class="form-label">Subject</label>
                                        <input type="text" class="form-control" id="subject" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea class="form-control" id="message" rows="5" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-custom">Send Message</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map Section -->
        <section class="bg-light py-5">
            <div class="container">
                <h2 class="section-title text-center mb-4">Our Location</h2>
                <div class="row">
                    <div class="col-12" data-aos="zoom-in">
                        <div class="card border-0 shadow">
                            <div class="card-body p-0">
                                <!-- Replace with actual Google Maps embed code -->
                                <div class="ratio ratio-16x9">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3857.8231764539307!2d122.95340731484253!3d14.1436899900000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b1e4f4e4b0a9%3A0x6c3f9d9d9d9d9d9d!2sCamarines%20Norte%20High%20School!5e0!3m2!1sen!2sph!4v1621234567890!5m2!1sen!2sph" 
                                        style="border:0;" allowfullscreen="" loading="lazy"></iframe>
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
    .contact-form .form-control {
        border-radius: 10px;
        padding: 0.8rem;
        border: 1px solid #e0e0e0;
    }

    .contact-form .form-control:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.2rem rgba(41, 98, 255, 0.25);
    }

    .map-container {
        position: relative;
        padding-bottom: 75%;
        height: 0;
        overflow: hidden;
    }

    .map-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100% !important;
        height: 100% !important;
    }
</style>
@endsection 