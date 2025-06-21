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
        <div class="hero-decoration">
            <div class="floating-element element-1"></div>
            <div class="floating-element element-2"></div>
            <div class="floating-element element-3"></div>
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

    .contact-info {
        background: linear-gradient(145deg, var(--cnhs-white), var(--cnhs-light-gray));
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .contact-info::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(135deg, var(--cnhs-orange), var(--cnhs-blue));
        border-radius: 20px 20px 0 0;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        padding: 1.5rem;
        background: var(--cnhs-white);
        border-radius: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .contact-item:hover {
        transform: translateX(10px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .contact-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1.5rem;
        font-size: 1.5rem;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .address-icon {
        background: linear-gradient(135deg, #FFF7ED, #FED7AA);
        color: var(--cnhs-orange);
    }

    .phone-icon {
        background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
        color: var(--cnhs-blue);
    }

    .email-icon {
        background: linear-gradient(135deg, #F0FDF4, #BBF7D0);
        color: #16A34A;
    }

    .time-icon {
        background: linear-gradient(135deg, #FEF2F2, #FECACA);
        color: #DC2626;
    }

    .contact-item:hover .contact-icon-wrapper {
        transform: rotate(360deg) scale(1.1);
    }

    .contact-item:hover .address-icon {
        background: linear-gradient(135deg, var(--cnhs-orange), #FF6B00);
        color: white;
    }

    .contact-item:hover .phone-icon {
        background: linear-gradient(135deg, var(--cnhs-blue), var(--cnhs-light-blue));
        color: white;
    }

    .contact-item:hover .email-icon {
        background: linear-gradient(135deg, #16A34A, #15803D);
        color: white;
    }

    .contact-item:hover .time-icon {
        background: linear-gradient(135deg, #DC2626, #B91C1C);
        color: white;
    }

    .contact-label {
        color: var(--cnhs-blue);
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }

    .contact-text {
        color: var(--cnhs-gray);
        line-height: 1.6;
        font-size: 1rem;
        margin: 0;
    }

    .schedule-item {
        display: inline-block;
        padding: 0.2rem 0.8rem;
        background: rgba(59, 130, 246, 0.1);
        border-radius: 10px;
        margin: 0.2rem 0;
        font-size: 0.9rem;
    }

    .schedule-item.closed {
        background: rgba(220, 38, 38, 0.1);
        color: #DC2626;
    }

    .contact-form-card {
        background: linear-gradient(145deg, var(--cnhs-white), var(--cnhs-light-gray));
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }

    .card-decoration {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(135deg, var(--cnhs-blue), var(--cnhs-orange));
        border-radius: 20px 20px 0 0;
    }

    .form-header {
        color: var(--cnhs-blue);
        font-weight: 700;
        font-size: 1.8rem;
        text-align: center;
        margin-bottom: 2rem;
    }

    .form-label {
        color: var(--cnhs-blue);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--cnhs-gray);
        font-size: 1rem;
        z-index: 2;
    }

    .textarea-icon {
        top: 20px;
        transform: none;
    }

    .form-control {
        border-radius: 15px;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid #E5E7EB;
        background: var(--cnhs-white);
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: var(--cnhs-orange);
        box-shadow: 0 0 0 0.2rem rgba(255, 140, 0, 0.25);
        background: var(--cnhs-white);
    }

    .form-control:focus + .input-icon {
        color: var(--cnhs-orange);
    }

    .btn-custom {
        background: linear-gradient(135deg, var(--cnhs-orange), #FF6B00);
        border: none;
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        width: 100%;
        position: relative;
        overflow: hidden;
    }

    .btn-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--cnhs-blue), var(--cnhs-light-blue));
        transition: left 0.3s ease;
        z-index: -1;
    }

    .btn-custom:hover::before {
        left: 0;
    }

    .btn-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(255, 140, 0, 0.3);
        color: white;
    }

    .map-section {
        background: linear-gradient(135deg, var(--cnhs-light-gray), #E5E7EB);
        margin: 3rem 0;
        border-radius: 30px;
        padding: 4rem 2rem;
    }

    .map-card {
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        transition: all 0.3s ease;
    }

    .map-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .map-card iframe {
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .map-overlay {
        position: absolute;
        top: 20px;
        left: 20px;
        background: linear-gradient(135deg, var(--cnhs-orange), #FF6B00);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3);
        z-index: 10;
    }

    .map-info h5 {
        margin: 0 0 0.5rem 0;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .map-info p {
        margin: 0;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.2rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .section-title {
            font-size: 2rem;
            text-align: center;
        }

        .section-title::after {
            left: 50%;
            transform: translateX(-50%);
        }

        .contact-item {
            flex-direction: column;
            text-align: center;
        }

        .contact-icon-wrapper {
            margin: 0 auto 1rem;
        }

        .contact-item:hover {
            transform: translateY(-5px);
        }

        .form-control {
            padding: 1rem;
        }

        .input-icon {
            display: none;
        }

        .map-overlay {
            position: static;
            margin-bottom: 1rem;
            text-align: center;
        }
    }

    /* Animation for form validation */
    .form-control.is-invalid {
        border-color: #DC2626;
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    .form-control.is-valid {
        border-color: #16A34A;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation and animation
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
            // Simulate form submission
            const submitBtn = form.querySelector('.btn-custom');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
            submitBtn.disabled = true;
            
            setTimeout(() => {
                submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Message Sent!';
                submitBtn.style.background = 'linear-gradient(135deg, #16A34A, #15803D)';
                
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    submitBtn.style.background = '';
                    form.reset();
                    inputs.forEach(input => {
                        input.classList.remove('is-valid', 'is-invalid');
                    });
                }, 2000);
            }, 2000);
        }
    });
});
</script>
@endsection