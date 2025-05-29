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
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            max-width: 400px;
            margin: 40px auto;
            padding: 2.5rem 2rem;
            background: rgba(255,255,255,0.97);
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            transition: box-shadow 0.3s;
            position: relative;
        }
        .login-container:hover {
            box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.18);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2.2rem;
        }
        .login-header h1 {
            font-size: 2rem;
            color: #2d3a4a;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }
        .login-header p {
            color: #6b7280;
            font-size: 1rem;
        }
        .form-control {
            padding: 14px;
            border-radius: 7px;
            border: 1px solid #d1d5db;
            font-size: 1rem;
            background: #f9fafb;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px #6366f133;
            background: #fff;
        }
        .input-group-text {
            background: transparent;
            border: none;
            color: #6366f1;
            font-size: 1.2rem;
        }
        .input-group {
            border-radius: 7px;
            overflow: hidden;
            border: 1px solid #d1d5db;
            margin-bottom: 1rem;
            background: #f9fafb;
        }
        .btn-login {
            padding: 14px;
            border-radius: 7px;
            background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
            border: none;
            width: 100%;
            color: #fff;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px 0 #6366f122;
            transition: background 0.2s, box-shadow 0.2s;
            margin-top: 10px;
        }
        .btn-login:hover {
            background: linear-gradient(90deg, #4f46e5 0%, #2563eb 100%);
            box-shadow: 0 4px 16px 0 #6366f133;
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
            border-radius: 7px;
            cursor: pointer;
            transition: all 0.2s;
            background: #f3f4f6;
            color: #374151;
            font-weight: 500;
            font-size: 1rem;
        }
        .role-option:hover, .role-option.active {
            border-color: #6366f1;
            background: #eef2ff;
            color: #3730a3;
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
            color: #6366f1;
            text-decoration: underline;
            font-weight: 500;
        }
        .alert {
            font-size: 0.98rem;
            border-radius: 7px;
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
            color: #4b5563;
        }
        .back-link i {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <a href="{{ route('principal.index') }}" class="back-link">
                <i class='bx bx-arrow-back'></i>
                Back to Website
            </a>
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

            <form method="POST" action="{{ route('login') }}" id="loginForm" onsubmit="
                const formData = new FormData(this);
                const role = document.querySelector('input[name=role]:checked').value;
                console.log('=== FORM SUBMITTING ===', {
                    role: role,
                    action: this.action,
                    method: this.method,
                    student_id: formData.get('student_id'),
                    email: formData.get('email'),
                    password: formData.get('password') ? '***filled***' : 'empty',
                    all_form_data: Object.fromEntries(formData)
                });

                if (role === 'student') {
                    const studentIdField = document.getElementById('student-id-input');
                    console.log('Student ID field value:', studentIdField.value);
                    console.log('Student ID field visible:', studentIdField.offsetParent !== null);
                    if (!studentIdField.value) {
                        alert('Please enter your Student ID');
                        return false;
                    }
                }

                if (role === 'registrar') {
                    const emailField = document.getElementById('registrar-email-input');
                    console.log('Registrar email field value:', emailField.value);
                    console.log('Registrar email field visible:', emailField.offsetParent !== null);
                    console.log('Registrar email field disabled:', emailField.disabled);
                    console.log('Registrar email field name:', emailField.name);
                    if (!emailField.value) {
                        alert('Please enter your email address');
                        return false;
                    }
                }
                return true;
            ">
                @csrf

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

                <!-- Registrar Secret Code Field REMOVED - No longer needed -->

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

                <button type="submit" class="btn btn-primary btn-login mb-3">
                    Login
                </button>

                <div class="register-link">
                    <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
                </div>
            </form>
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
                    passwordInput.placeholder = 'Password';
                    form.action = "/admin/login";
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
                form.action = "/admin/login";

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

            // Add click event listener to login button for debugging
            const loginButton = document.querySelector('.btn-login');
            if (loginButton) {
                loginButton.addEventListener('click', function(e) {
                    console.log('Login button clicked');
                    const selectedRole = document.querySelector('input[name="role"]:checked');
                    console.log('Selected role:', selectedRole ? selectedRole.value : 'none');
                    const form = document.getElementById('loginForm');
                    console.log('Form action:', form.action);

                    // Check if required fields are filled
                    if (selectedRole && selectedRole.value === 'admin') {
                        const username = document.getElementById('username-input').value;
                        const password = document.querySelector('input[name="password"]').value;
                        console.log('Username filled:', !!username);
                        console.log('Password filled:', !!password);
                    }
                });
            }
        });
    </script>
</body>
</html>