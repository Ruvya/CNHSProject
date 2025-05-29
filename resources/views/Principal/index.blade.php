@extends('Principal.layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <div class="hero-section d-flex align-items-center">
        <div class="container">
            <div class="hero-content text-center" data-aos="fade-up">
                <h1 class="hero-title mb-4">Welcome to CNHS Portal</h1>
                <p class="hero-subtitle">Empowering Education Through Innovation</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Add your home page content here -->
    </div>
@endsection

@section('styles')
<style>
    .hero-section {
        background: url('{{ asset("images/im.jpg") }}');
        opacity: 0.8;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 80vh;
        color: white;
    }

    .hero-title {
        font-size: 3rem;
        font-weight: 700;

    }

    .hero-subtitle {
        font-size: 1.5rem;
        opacity: 0.9;
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
        background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
    }
</style>
@endsection 