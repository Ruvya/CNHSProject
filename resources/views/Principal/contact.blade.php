@extends('Principal.layouts.app')

@section('title', 'Contact Us')

@section('content')
    <!-- Hero Section -->
    <div class="hero-section-small d-flex align-items-center" style="background: var(--cnhs-primary-blue); min-height: 320px; color: white;">
        <div class="container">
            <div class="hero-content text-center" data-aos="fade-up">
                <h1 class="hero-title mb-4" style="color: white; font-size: 3.2rem; font-weight: 800;"><i class="fas fa-envelope me-2"></i>Contact Us</h1>
                <p class="hero-subtitle" style="color: #e0e7ff; font-size: 1.5rem;">Get in Touch with CNHS</p>
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
                        <h2 class="section-title">📞 Contact Information</h2>
                        <div class="contact-info">
                            <div class="contact-item mb-4">
                                <div class="contact-icon-wrapper address-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="contact-details">
                                    <h5 class="contact-label">Address</h5>
                                    <p class="contact-text">Calingcaguing National High School<br>Calingcaguing Barugo, Philippines</p>
                                </div>
                            </div>
                            <div class="contact-item mb-4">
                                <div class="contact-icon-wrapper phone-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="contact-details">
                                    <h5 class="contact-label">Phone</h5>
                                    <p class="contact-text">(123) 456-7890</p>
                                </div>
                            </div>
                            <div class="contact-item mb-4">
                                <div class="contact-icon-wrapper email-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-details">
                                    <h5 class="contact-label">Email</h5>
                                    <p class="contact-text">info@cnhs.edu.ph</p>
                                </div>
                            </div>
                            <div class="contact-item mb-4">
                                <div class="contact-icon-wrapper time-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="contact-details">
                                    <h5 class="contact-label">Office Hours</h5>
                                    <p class="contact-text">
                                        <span class="schedule-item">Monday - Friday: 7:00 AM - 5:00 PM</span><br>
                                        <span class="schedule-item">Saturday: 8:00 AM - 12:00 PM</span><br>
                                        <span class="schedule-item closed">Sunday: Closed</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4" data-aos="fade-left">
                        <div class="card contact-form-card border-0 shadow">
                            <div class="card-decoration"></div>
                            <div class="card-body p-4">
                                <h4 class="card-title mb-4 form-header">💌 Send us a Message</h4>
                                <form class="contact-form">
                                    <div class="mb-3 form-group">
                                        <label for="name" class="form-label">Full Name</label>
                                        <div class="input-wrapper">
                                            <i class="fas fa-user input-icon"></i>
                                            <input type="text" class="form-control" id="name" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 form-group">
                                        <label for="email" class="form-label">Email Address</label>
                                        <div class="input-wrapper">
                                            <i class="fas fa-envelope input-icon"></i>
                                            <input type="email" class="form-control" id="email" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 form-group">
                                        <label for="subject" class="form-label">Subject</label>
                                        <div class="input-wrapper">
                                            <i class="fas fa-tag input-icon"></i>
                                            <input type="text" class="form-control" id="subject" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 form-group">
                                        <label for="message" class="form-label">Message</label>
                                        <div class="input-wrapper">
                                            <i class="fas fa-comment input-icon textarea-icon"></i>
                                            <textarea class="form-control" id="message" rows="5" required></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-custom">
                                        <i class="fas fa-paper-plane me-2"></i>Send Message
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map Section -->
        <section class="map-section py-5">
            <div class="container">
                <h2 class="section-title text-center mb-4">🗺️ Our Location</h2>
                <div class="row">
                    <div class="col-12" data-aos="zoom-in">
                        <div class="card map-card border-0 shadow">
                            <div class="card-body p-0">
                                <div class="ratio ratio-16x9">
                                   <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3912.3790275064916!2d124.76404897504977!3d11.306982388875943!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33080e870432b9d7%3A0xddb0cfff1054bb70!2sCalingcaguing%20National%20High%20School!5e0!3m2!1sen!2sph!4v1748875720490!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                            <div class="map-overlay">
                                <div class="map-info">
                                    <h5>Visit Our Campus</h5>
                                    <p>Calingcaguing Barugo, Philippines</p>
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

    .contact-info {
        background: var(--cnhs-white);
        padding: 3rem;
        border-radius: 24px;
        box-shadow: 0 12px 35px rgba(30, 58, 138, 0.12);
        position: relative;
        overflow: hidden;
    }

    .contact-info::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(135deg, var(--cnhs-accent-orange), var(--cnhs-primary-blue));
        border-radius: 24px 24px 0 0;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        padding: 2rem;
        background: var(--cnhs-white);
        border-radius: 18px;
        transition: all 0.4s ease;
        box-shadow: 0 6px 20px rgba(30, 58, 138, 0.08);
        border: 1px solid rgba(30, 58, 138, 0.05);
    }

    .contact-item:hover {
        transform: translateX(12px);
        box-shadow: 0 12px 30px rgba(30, 58, 138, 0.15);
    }

    .contact-icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 2rem;
        font-size: 1.6rem;
        transition: all 0.4s ease;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(30, 58, 138, 0.15);
        background: var(--cnhs-light-gray);
        color: var(--cnhs-primary-blue);
    }

    .address-icon {
        background: var(--cnhs-light-gray);
        color: var(--cnhs-primary-blue);
    }

    .phone-icon {
        background: var(--cnhs-light-gray);
        color: var(--cnhs-primary-blue);
    }

    .email-icon {
        background: var(--cnhs-light-gray);
        color: var(--cnhs-primary-blue);
    }

    .time-icon {
        background: var(--cnhs-light-gray);
        color: var(--cnhs-primary-blue);
    }

    .contact-item:hover .contact-icon-wrapper {
        transform: rotate(360deg) scale(1.15);
    }

    .contact-item:hover .address-icon {
        background: var(--cnhs-accent-orange);
        color: white;
    }

    .contact-item:hover .phone-icon {
        background: var(--cnhs-primary-blue);
        color: white;
    }

    .contact-item:hover .email-icon {
        background: var(--cnhs-success);
        color: white;
    }

    .contact-item:hover .time-icon {
        background: var(--cnhs-warning);
        color: white;
    }

    .contact-label {
        color: var(--cnhs-primary-blue);
        font-weight: 700;
        font-size: 1.3rem;
        margin-bottom: 0.8rem;
        letter-spacing: -0.01em;
    }

    .contact-text {
        color: var(--cnhs-medium-gray);
        line-height: 1.7;
        font-size: 1.05rem;
        margin: 0;
        font-weight: 500;
    }

    .schedule-item {
        display: inline-block;
        padding: 0.3rem 1rem;
        background: rgba(59, 130, 246, 0.08);
        border-radius: 12px;
        margin: 0.3rem 0;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .schedule-item.closed {
        background: rgba(220, 38, 38, 0.08);
        color: #DC2626;
    }

    .contact-form-card {
        background: var(--cnhs-white);
        border-radius: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(30, 58, 138, 0.12);
    }

    .card-decoration {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: var(--cnhs-primary-blue);
        border-radius: 24px 24px 0 0;
    }

    .form-header {
        color: var(--cnhs-primary-blue);
        font-weight: 700;
        font-size: 2rem;
        text-align: center;
        margin-bottom: 2.5rem;
        letter-spacing: -0.01em;
    }

    .form-label {
        color: var(--cnhs-primary-blue);
        font-weight: 600;
        margin-bottom: 0.8rem;
        font-size: 1.05rem;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--cnhs-medium-gray);
        font-size: 1.1rem;
        z-index: 2;
    }

    .textarea-icon {
        top: 22px;
        transform: none;
    }

    .form-control {
        border-radius: 18px;
        padding: 1.2rem 1.2rem 1.2rem 3.5rem;
        border: 2px solid #E5E7EB;
        background: var(--cnhs-white);
        transition: all 0.4s ease;
        font-size: 1.05rem;
        font-weight: 500;
    }

    .form-control:focus {
        border-color: var(--cnhs-accent-orange);
        box-shadow: 0 0 0 0.25rem rgba(255, 140, 0, 0.15);
        background: var(--cnhs-white);
    }

    .form-control:focus + .input-icon {
        color: var(--cnhs-accent-orange);
    }

    .btn-custom {
        background: var(--cnhs-accent-orange);
        border: none;
        color: white;
        padding: 1.2rem 3rem;
        border-radius: 30px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.4s ease;
        width: 100%;
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--cnhs-primary-blue), var(--cnhs-secondary-blue));
        transition: left 0.4s ease;
        z-index: -1;
    }

    .btn-custom:hover::before {
        left: 0;
    }

    .btn-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(255, 140, 0, 0.3);
        color: white;
        background: var(--cnhs-primary-blue);
    }

    .map-section {
        background: var(--cnhs-light-gray);
        margin: 4rem 0;
        border-radius: 30px;
        padding: 4rem 2rem;
    }

    .map-card {
        border-radius: 24px;
        overflow: hidden;
        position: relative;
        transition: all 0.4s ease;
        box-shadow: 0 12px 35px rgba(30, 58, 138, 0.12);
    }

    .map-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px rgba(30, 58, 138, 0.2);
    }

    .map-card iframe {
        border-radius: 24px;
        transition: all 0.4s ease;
    }

    .map-overlay {
        position: absolute;
        top: 25px;
        left: 25px;
        background: var(--cnhs-accent-orange);
        color: white;
        padding: 1.2rem 2rem;
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(255, 140, 0, 0.3);
        z-index: 10;
        backdrop-filter: blur(10px);
    }

    .map-info h5 {
        margin: 0 0 0.8rem 0;
        font-weight: 700;
        font-size: 1.2rem;
        letter-spacing: -0.01em;
    }

    .map-info p {
        margin: 0;
        font-size: 1rem;
        opacity: 0.95;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.4rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
        }

        .section-title {
            font-size: 2.2rem;
            text-align: center;
        }

        .section-title::after {
            left: 50%;
            transform: translateX(-50%);
        }

        .contact-item {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem;
        }

        .contact-icon-wrapper {
            margin: 0 auto 1.5rem;
        }

        .contact-item:hover {
            transform: translateY(-8px);
        }

        .form-control {
            padding: 1rem;
        }

        .input-icon {
            display: none;
        }

        .map-overlay {
            position: static;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .contact-info {
            padding: 2rem;
        }
    }

    /* Professional form validation animations */
    .form-control.is-invalid {
        border-color: #DC2626;
        animation: professionalShake 0.6s ease-in-out;
    }

    @keyframes professionalShake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-8px); }
        75% { transform: translateX(8px); }
    }

    .form-control.is-valid {
        border-color: var(--cnhs-success);
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.15);
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Professional form validation and animation
    const form = document.querySelector('.contact-form');
    const inputs = form.querySelectorAll('.form-control');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() !== '') {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            } else {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });
        
        input.addEventListener('focus', function() {
            this.classList.remove('is-invalid', 'is-valid');
        });
    });
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        inputs.forEach(input => {
            if (input.value.trim() === '') {
                input.classList.add('is-invalid');
                isValid = false;
            }
        });
        
        if (isValid) {
            // Professional form submission simulation
            const submitBtn = form.querySelector('.btn-custom');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending Message...';
            submitBtn.disabled = true;
            
            setTimeout(() => {
                submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Message Sent Successfully!';
                submitBtn.style.background = 'linear-gradient(135deg, var(--cnhs-success), #059669)';
                
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    submitBtn.style.background = '';
                    form.reset();
                    inputs.forEach(input => {
                        input.classList.remove('is-valid', 'is-invalid');
                    });
                }, 2500);
            }, 2000);
        }
    });
});
</script>
@endsection