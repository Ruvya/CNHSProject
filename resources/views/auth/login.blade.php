<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CNHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: #f3f6fb;
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            max-width: 420px;
            margin: 24px auto;
            padding: 1.5rem 1.2rem 1.2rem 1.2rem;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(30, 58, 138, 0.10), 0 2px 8px rgba(255, 140, 0, 0.08);
            border: 2.5px solid #1E3A8A;
            position: relative;
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
            flex: 1 1 110px;
            min-width: 110px;
            max-width: 140px;
            text-align: center;
            padding: 10px 0;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s, box-shadow 0.2s;
            background: #f3f4f6;
            color: #1E3A8A;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(30,58,138,0.04);
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
    </style>
</head>
<body>
    <!-- Remove extra white background: only keep the card white -->
    <div class="d-flex justify-content-center align-items-center min-vh-100" style="background: #f3f6fb;">
        <div class="card shadow-lg border-0 position-relative" style="max-width: 420px; width: 100%; margin: 40px 0;">
            <!-- Back to Landing Page Arrow -->
            
            <div class="login-container">
            <div class="d-flex justify-content-start" style="max-width: 349px; margin: 24px auto 0 auto;">
                <a href="/" title="Back to Home" style="text-decoration: none; display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #fff; border-radius: 50%; border: 2px solid #1E3A8A; box-shadow: 0 2px 8px rgba(30,58,138,0.10); font-size: 1.3rem; color: #1E3A8A; font-weight: bold;">
                    &#8592;
                </a>
            </div>
                <!-- School Logo -->
                <div class="text-center mb-3">
                    <img src="{{ asset('images/CNHS.png') }}" alt="CNHS Logo" style="width: 90px; height: 90px; object-fit: contain; margin-bottom: 1rem;">
                </div>
                <div class="login-header">
                    <h1>Welcome to CNHS</h1>
                    <p class="text-muted">Please login to continue</p>
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
                    <!-- Section: Select Role (moved inside form) -->
                    <div class="mb-2 mt-3">
                        <label class="form-label text-uppercase fw-bold" style="color: #1E3A8A; font-size: 1.02rem; letter-spacing: 0.5px;">Select Role</label>
                    </div>
                    <div class="role-selector mb-4">
                        <label class="role-option active">
                            <input type="radio" name="role" value="admin" required checked>
                            <i class='bx bxs-user'></i>
                            <div>Admin</div>
                        </label>
                        <label class="role-option">
                            <input type="radio" name="role" value="teacher" required>
                            <i class='bx bxs-user-detail'></i>
                            <div>Teacher</div>
                        </label>
                        <label class="role-option">
                            <input type="radio" name="role" value="student" required>
                            <i class='bx bxs-user-detail'></i>
                            <div>Student</div>
                        </label>
                        <label class="role-option">
                            <input type="radio" name="role" value="registrar" required>
                            <i class='bx bxs-user-detail'></i>
                            <div>Registrar</div>
                        </label>
                        <label class="role-option">
                            <input type="radio" name="role" value="principal" required>
                            <i class='bx bxs-user-badge'></i>
                            <div>Principal</div>
                        </label>
                    </div>
                    <!-- Divider -->
                    <div style="height: 1.5px; background: #e5e7eb; margin: 1.5rem 0 1.5rem 0; border-radius: 2px;"></div>
                    <div class="mb-2">
                        <label class="form-label text-uppercase fw-bold" style="color: #1E3A8A; font-size: 1.02rem; letter-spacing: 0.5px;">Login Credentials</label>
                    </div>
                    <!-- Admin Username Field -->
                    <div class="mb-3" id="username-container">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class='bx bxs-user'></i>
                            </span>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                   name="username"
                                   id="username-input"
                                   placeholder="Admin Username"
                                   value="{{ old('username') }}"
                                   required>
                        </div>
                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <!-- Teacher Email Field -->
                    <div class="mb-3" id="email-container" style="display: none;">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class='bx bxs-envelope'></i>
                            </span>
                            <input type="email" class="form-control" name="email" id="email-input" placeholder="Teacher Email Address">
                        </div>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Registrar Email Field -->
                    <div class="mb-3" id="registrar-email-container" style="display: none;">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class='bx bxs-envelope'></i>
                            </span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   id="registrar-email-input"
                                   placeholder="Registrar Email Address"
                                   value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <!-- Student Username Field (was Student ID) -->
                    <div class="mb-3" id="student-id-container" style="display: none;">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class='bx bxs-id-card'></i>
                            </span>
                            <input type="text" class="form-control" name="student_id" id="student-id-input" placeholder="Student ID" required>
                        </div>
                        @error('student_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Principal Email Field -->
                    <div class="mb-3" id="principal-email-container" style="display: none;">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class='bx bxs-envelope'></i>
                            </span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   id="principal-email-input"
                                   placeholder="Principal Email Address"
                                   value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
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

        // Add active class to selected role and toggle input fields
        document.querySelectorAll('.role-option input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                console.log('Role changed to:', this.value);
                document.querySelectorAll('.role-option').forEach(option => {
                    option.classList.remove('active');
                });
                this.closest('.role-option').classList.add('active');

                // Toggle input fields based on role
                const usernameContainer = document.getElementById('username-container');
                const emailContainer = document.getElementById('email-container');
                const registrarEmailContainer = document.getElementById('registrar-email-container');
                const studentIdContainer = document.getElementById('student-id-container');
                const usernameInput = document.getElementById('username-input');
                const emailInput = document.getElementById('email-input');
                const registrarEmailInput = document.getElementById('registrar-email-input');
                const studentIdInput = document.getElementById('student-id-input');
                const passwordInput = document.querySelector('input[name="password"]');

                // Hide all containers first
                usernameContainer.style.display = 'none';
                emailContainer.style.display = 'none';
                registrarEmailContainer.style.display = 'none';
                studentIdContainer.style.display = 'none';
                const principalEmailContainer = document.getElementById('principal-email-container');
                principalEmailContainer.style.display = 'none';

                // Remove required attribute and disable all inputs to prevent conflicts
                usernameInput.removeAttribute('required');
                usernameInput.disabled = true;
                emailInput.removeAttribute('required');
                emailInput.disabled = true;
                registrarEmailInput.removeAttribute('required');
                registrarEmailInput.disabled = true;
                studentIdInput.removeAttribute('required');
                studentIdInput.disabled = true;
                const principalEmailInput = document.getElementById('principal-email-input');
                principalEmailInput.removeAttribute('required');
                principalEmailInput.disabled = true;

                // Remove any existing alerts
                const existingAlert = document.querySelector('.registrar-alert');
                if (existingAlert) {
                    existingAlert.remove();
                }

                // Update form action based on role
                const form = document.getElementById('loginForm');

                // Show appropriate container based on role
                if (this.value === 'admin') {
                    usernameContainer.style.display = 'block';
                    usernameInput.setAttribute('required', '');
                    usernameInput.disabled = false; // Enable the field
                    // Don't clear the username if it's already filled
                    if (!usernameInput.value) {
                        usernameInput.value = '';
                    }
                    passwordInput.placeholder = 'Admin Password';
                    form.action = "{{ route('admin.login.post') }}";
                    console.log('Admin form action set to:', form.action);
                } else if (this.value === 'teacher') {
                    emailContainer.style.display = 'block';
                    emailInput.setAttribute('required', '');
                    emailInput.disabled = false; // Enable the field
                    emailInput.value = '';
                    passwordInput.placeholder = 'Password';
                    form.action = "{{ route('login') }}";
                } else if (this.value === 'student') {
                    studentIdContainer.style.display = 'block';
                    studentIdInput.setAttribute('required', '');
                    studentIdInput.disabled = false; // Enable the field
                    studentIdInput.value = '';
                    passwordInput.placeholder = 'Password';
                    form.action = "{{ route('login') }}";
                    console.log('Student form action set to:', form.action);
                } else if (this.value === 'registrar') {
                    registrarEmailContainer.style.display = 'block';
                    registrarEmailInput.setAttribute('required', '');
                    registrarEmailInput.disabled = false; // Enable the field
                    // Don't clear the email field - let user keep their input
                    passwordInput.placeholder = 'Registrar Password';
                    form.action = "{{ route('login') }}";
                    console.log('Registrar login - form action set to:', form.action);
                    console.log('Registrar email container displayed:', registrarEmailContainer.style.display);
                    console.log('Registrar email input required:', registrarEmailInput.hasAttribute('required'));
                    console.log('Registrar email input enabled:', !registrarEmailInput.disabled);
                } else if (this.value === 'principal') {
                    // Hide regular email container and show principal-specific email container
                    emailContainer.style.display = 'none';
                    const principalEmailContainer = document.getElementById('principal-email-container');
                    principalEmailContainer.style.display = 'block';
                    const principalEmailInput = document.getElementById('principal-email-input');
                    principalEmailInput.setAttribute('required', '');
                    principalEmailInput.disabled = false; // Enable the field
                    // Clear any existing values
                    principalEmailInput.value = '';
                    form.action = "{{ route('principal.login') }}";
                }
            });
        });

        // Initialize the form when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Set up initial state for admin (default selected)
            const adminRadio = document.querySelector('input[name="role"][value="admin"]');
            if (adminRadio && adminRadio.checked) {
                const form = document.getElementById('loginForm');
                form.action = "{{ route('admin.login.post') }}";

                // Ensure admin fields are visible and others are hidden
                const usernameContainer = document.getElementById('username-container');
                const emailContainer = document.getElementById('email-container');
                const registrarEmailContainer = document.getElementById('registrar-email-container');
                const studentIdContainer = document.getElementById('student-id-container');
                const principalEmailContainer = document.getElementById('principal-email-container');

                usernameContainer.style.display = 'block';
                emailContainer.style.display = 'none';
                registrarEmailContainer.style.display = 'none';
                studentIdContainer.style.display = 'none';
                principalEmailContainer.style.display = 'none';

                // Set up initial field states for admin
                const usernameInput = document.getElementById('username-input');
                const emailInput = document.getElementById('email-input');
                const registrarEmailInput = document.getElementById('registrar-email-input');
                const studentIdInput = document.getElementById('student-id-input');
                const principalEmailInput = document.getElementById('principal-email-input');

                // Disable all fields initially except username (admin is default)
                usernameInput.disabled = false;
                emailInput.disabled = true;
                registrarEmailInput.disabled = true;
                studentIdInput.disabled = true;
                principalEmailInput.disabled = true;

                // Remove any existing alerts
                const existingAlert = document.querySelector('.registrar-alert');
                if (existingAlert) {
                    existingAlert.remove();
                }

                console.log('Initial admin setup complete. Form action:', form.action);
            }

            // Add form submission handler for debugging
            const form = document.getElementById('loginForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Form submitting...');
                    const selectedRole = document.querySelector('input[name="role"]:checked');
                    const password = document.querySelector('input[name="password"]').value;

                    if (!selectedRole) {
                        e.preventDefault();
                        alert('Please select a role');
                        return false;
                    }

                    if (!password) {
                        e.preventDefault();
                        alert('Please enter your password');
                        return false;
                    }

                    // Role-specific validation
                    if (selectedRole.value === 'registrar') {
                        const email = document.getElementById('registrar-email-input').value;
                        if (!email) {
                            e.preventDefault();
                            alert('Please enter your email address');
                            return false;
                        }
                        console.log('Registrar login attempt:', { email: email, hasPassword: !!password });
                    } else if (selectedRole.value === 'admin') {
                        const username = document.getElementById('username-input').value;
                        if (!username) {
                            e.preventDefault();
                            alert('Please enter your username');
                            return false;
                        }
                    } else if (selectedRole.value === 'teacher') {
                        const email = document.getElementById('email-input').value;
                        if (!email) {
                            e.preventDefault();
                            alert('Please enter your email address');
                            return false;
                        }
                    } else if (selectedRole.value === 'student') {
                        const studentId = document.getElementById('student-id-input').value;
                        if (!studentId) {
                            e.preventDefault();
                            alert('Please enter your student ID');
                            return false;
                        }
                    }

                    console.log('Form validation passed, submitting...');
                    return true;
                });
            }
        });
    </script>
</body>
</html>
