<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CNHS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            font-family: 'Poppins', Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: url('{{ asset('images/bg.png') }}') no-repeat center center;
            background-size: cover;
            opacity: 0.4;
            z-index: 0;
            pointer-events: none;
        }
        .login-container, .card, .d-flex {
            position: relative;
            z-index: 1;
        }
        .login-container {
            max-width: 640px;
            margin: 24px auto;
            padding: 1.5rem 1.2rem 1.2rem 1.2rem;
            background: rgba(255,255,255,0.85); /* semi-transparent */
            border-radius: 18px;
            box-shadow: none;
            border: 2.5px solid #2563eb; /* vibrant blue */
            position: relative;
            backdrop-filter: blur(4px); /* frosted glass effect */
        }
        .card {
            background: transparent !important;
            box-shadow: none !important;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2.2rem;
        }
        .login-header h1 {
            font-size: 2.3rem;
            color: #1E3A8A;
            font-weight: 900;
            margin-bottom: 0.5rem;
            letter-spacing: -0.01em;
            position: relative;
        }
        .login-header h1::after {
            content: '';
            display: block;
            margin: 0.7rem auto 0 auto;
            width: 80px;
            height: 5px;
            background: #FF8C00;
            border-radius: 3px;
        }
        .login-header p {
            color: #6b7280;
            font-size: 1.08rem;
        }
        .form-control {
            padding: 14px;
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            font-size: 1rem;
            background: #f9fafb;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            border-color: #1E3A8A;
            box-shadow: 0 0 0 2px #1E3A8A22;
            background: #fff;
        }
        .input-group-text {
            background: transparent;
            border: none;
            color: #1E3A8A;
            font-size: 1.2rem;
        }
        .input-group {
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid #e5e7eb;
            margin-bottom: 1rem;
            background: #f9fafb;
        }
        .btn-login {
            padding: 14px;
            border-radius: 10px;
            background: #FF8C00;
            border: none;
            width: 100%;
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px 0 #ff8c0022;
            transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-login i {
            font-size: 1.2rem;
        }
        .btn-login:hover {
            background: #1E3A8A;
            color: #fff;
            box-shadow: 0 4px 16px 0 #1e3a8a22;
            transform: translateY(-2px) scale(1.03);
        }
        .role-selector {
            display: flex;
            gap: 12px;
            margin-bottom: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .role-option {
            flex: 1 1 auto;
            width: 100px;
            min-width: 0;
            max-width: 100px;
            text-align: center;
            padding: 4px 0 4px 0;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s, box-shadow 0.2s;
            background: #f3f4f6;
            color: #1E3A8A;
            font-weight: 600;
            font-size: 0.82rem;
            box-shadow: 0 2px 8px rgba(30,58,138,0.04);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .role-option:hover, .role-option.active {
            border-color: #FF8C00;
            background: #fff7ed;
            color: #FF8C00;
            box-shadow: 0 4px 16px #ff8c0033;
            transform: scale(1.04);
        }
        .role-option input[type="radio"] {
            display: none;
        }
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 1rem;
        }
        .register-link a {
            color: #1E3A8A;
            text-decoration: underline;
            font-weight: 600;
        }
        .alert {
            font-size: 0.98rem;
            border-radius: 10px;
        }

        .back-link {
            position: absolute;
            top: 1rem;
            left: 1rem;
            color: #6b7280;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: #1E3A8A;
        }
        .back-link i {
            font-size: 1.2rem;
        }
        @media (max-width: 500px) {
            .login-container {
                padding: 1.2rem 0.5rem;
                max-width: 98vw;
            }
            .login-header h1 {
                font-size: 1.3rem;
            }
        }
        .login-container .role-selector {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 1.5rem;
            justify-content: flex-start;
            align-items: stretch;
            width: 100%;
        }
        .login-container .role-option {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            min-width: 0;
            max-width: 100%;
            padding: 10px 18px;
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            background: #F3F4F6;
            color: #1E293B;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s, color 0.2s;
            box-shadow: none;
            margin-bottom: 0;
            justify-content: flex-start;
        }
        .login-container .role-option.active {
            border-color: #4F46E5;
            background: #fff;
            color: #4F46E5;
            box-shadow: 0 0 0 2px #4F46E522;
        }
        .login-container .role-option.active i {
            color: #4F46E5;
        }
        .login-container .role-option i {
            font-size: 1.4rem;
            color: #1E293B;
            transition: color 0.2s;
        }
        .login-container .role-option span {
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.01em;
        }
        .login-container .role-option input[type="radio"] {
            display: none;
        }
        .login-container .input-group {
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #E5E7EB;
            margin-bottom: 0px;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-shadow: none;
        }
        .login-container .input-group:focus-within {
            border-color: #4F46E5;
            box-shadow: 0 0 0 2px #4F46E522;
        }
        .login-container .input-group-text {
            background: transparent;
            border: none;
            color: #4F46E5;
            font-size: 1.3rem;
            transition: color 0.2s;
        }
        .login-container .form-control {
            border: none;
            background: #fff;
            font-size: 1rem;
            color: #1E293B;
            font-weight: 500;
            padding: 14px 16px;
            border-radius: 0;
            box-shadow: none;
            transition: color 0.2s;
        }
        .login-container .form-control:focus {
            outline: none;
            box-shadow: none;
            color: #4F46E5;
        }
        .login-container .input-group .input-group-text:last-child {
            cursor: pointer;
        }
        .login-container .btn-login {
            width: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #6366F1 0%, #38BDF8 100%);
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            border: none;
            box-shadow: none;
            padding: 14px 0;
            margin-top: 10px;
            margin-bottom: 0;
            text-transform: none;
            letter-spacing: 0.5px;
            transition: background 0.2s, transform 0.2s;
            display: block;
        }
        .login-container .btn-login:hover {
            background: linear-gradient(90deg, #4F46E5 0%, #38BDF8 100%);
            color: #fff;
            transform: translateY(-2px) scale(1.03);
        }
    </style>
</head>
<body>
    <!-- Remove extra white background: only keep the card white -->
    <div class="d-flex min-vh-300" style="justify-content: center; align-items: center; gap: 50px;">
        <!-- Hero Section on the left -->
        <div class="d-none d-md-flex flex-column justify-content-center align-items-center flex-grow-1" style="height: 50vh;">
            <!-- Replace this with your hero content (image, text, etc.) -->
            <div style="text-align: center; color:rgb(0, 0, 0);">
                <h2 style="font-size: 3rem; font-weight: 800; margin-bottom: 1.2rem;">Welcome to CNHS Portal</h2>
                <p style="font-size: 1.5rem; max-width: 480px; margin: 0 auto;">Empowering students, teachers, and staff with seamless access to school resources and information.</p>
                <!-- You can add an image or illustration here -->
            </div>
        </div>
        <!-- Login card on the right -->
        <div class="card shadow-lg border-0 position-relative" style="max-width: 640px; width: 100%; transform: scale(0.75); transform-origin: center center;">
            <div class="login-container">
            <div class="d-flex justify-content-start mb-2">
                <a href="/" class="btn btn-outline-primary" style="border-radius: 999px; font-weight: 600; padding: 6px 22px; font-size: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class='bx bx-arrow-back'></i> Back to Website
                </a>
            </div>
                <!-- School Logo -->
                <div class="text-center mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="School Logo" style="width: 120px; height: 120px; object-fit: contain; margin-bottom: 1rem;">
                </div>
                <div class="login-header">
                    <!-- <h1>Welcome to CNHS</h1> -->
                    <p style="color: #1E293B; font-size: 1.35rem; font-weight: 700;">Please login to continue</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mb-3">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                        <div class="mt-2">
                            <a href="{{ route('registrar.login') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-sign-in-alt me-1"></i>Go to Registrar Login
                            </a>
                        </div>
                    </div>
                @endif

                @if(session('message'))
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('message') }}
                    </div>
                @endif

                <!-- Section: Login Credentials -->
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    <div class="p-3 d-flex flex-column justify-content-center">
                        <label class="form-label text-uppercase fw-bold mb-2 mt-2 w-100" style="color: #1E3A8A; font-size: 1.02rem; letter-spacing: 0.5px; text-align:left;">Login</label>

                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class='bx bxs-user'></i>
                                </span>
                                <input type="text" class="form-control" name="identifier" id="identifier-input" placeholder="Username / Email / Student ID" value="{{ old('identifier') }}" required>
                            </div>
                            @error('identifier')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class='bx bxs-lock-alt'></i>
                                </span>
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                                <span class="input-group-text" style="cursor: pointer" onclick="togglePassword(this)">
                                    <i class='bx bxs-show'></i>
                                </span>
                            </div>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-login mb-2 mt-2">
                            <i class='bx bx-log-in'></i> Login
                        </button>

                        <div class="register-link" style="font-size: 0.98rem; margin-top: 0.7rem;">
                            <p>Need an account? Contact your administrator for account creation.</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(element) {
            const passwordInput = element.parentElement.querySelector('input[type="password"]');
            const icon = element.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bxs-show');
                icon.classList.add('bxs-hide');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bxs-hide');
                icon.classList.add('bxs-show');
            }
        }

        // Minimal client-side validation for unified form
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            if (!form) return;
            form.addEventListener('submit', function(e) {
                const identifier = document.getElementById('identifier-input').value.trim();
                const password = document.querySelector('input[name="password"]').value.trim();
                if (!identifier) {
                    e.preventDefault();
                    alert('Please enter your username, email or student ID');
                    return false;
                }
                if (!password) {
                    e.preventDefault();
                    alert('Please enter your password');
                    return false;
                }
                return true;
            });
        });
    </script>
</body>
</html>
