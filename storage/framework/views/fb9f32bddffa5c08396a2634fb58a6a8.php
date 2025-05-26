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
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <h1>Welcome to CNHS</h1>
                <p class="text-muted">Please login to continue</p>
            </div>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger mb-3">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('info')): ?>
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i><?php echo e(session('info')); ?>

                    <div class="mt-2">
                        <a href="<?php echo e(route('registrar.login')); ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-sign-in-alt me-1"></i>Go to Registrar Login
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('message')): ?>
                <div class="alert alert-warning mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i><?php echo e(session('message')); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="/admin/login" id="loginForm" onsubmit="console.log('=== FORM SUBMITTING ===', {role: document.querySelector('input[name=role]:checked').value, action: this.action, method: this.method}); return true;">
                <?php echo csrf_field(); ?>

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
                </div>

                <!-- Admin Username Field -->
                <div class="mb-3" id="username-container">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class='bx bxs-user'></i>
                        </span>
                        <input type="text" class="form-control <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               name="username"
                               id="username-input"
                               placeholder="Admin Username"
                               value="<?php echo e(old('username')); ?>"
                               required>
                    </div>
                    <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback" role="alert">
                            <strong><?php echo e($message); ?></strong>
                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Teacher Email Field -->
                <div class="mb-3" id="email-container" style="display: none;">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class='bx bxs-envelope'></i>
                        </span>
                        <input type="email" class="form-control" name="email" id="email-input" placeholder="Email Address">
                    </div>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Student Username Field (was Student ID) -->
                <div class="mb-3" id="student-id-container" style="display: none;">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class='bx bxs-id-card'></i>
                        </span>
                        <input type="text" class="form-control" name="student_id" id="student-id-input" placeholder="Student ID" required>
                    </div>
                    <?php $__errorArgs = ['student_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3" id="registrar-code-container" style="display: none;">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class='bx bxs-key'></i>
                        </span>
                        <input type="text" class="form-control <?php $__errorArgs = ['registrar_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               name="registrar_secret" id="registrar-code-input"
                               placeholder="Registrar Secret Code" value="<?php echo e(old('registrar_secret')); ?>">
                    </div>
                    <?php $__errorArgs = ['registrar_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block">
                            <strong><?php echo e($message); ?></strong>
                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <button type="submit" class="btn btn-primary btn-login mb-3">
                    Login
                </button>

                <div class="register-link">
                    <p>Don't have an account? <a href="<?php echo e(route('register')); ?>">Register</a></p>
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
                const studentIdContainer = document.getElementById('student-id-container');
                const registrarCodeContainer = document.getElementById('registrar-code-container');
                const usernameInput = document.getElementById('username-input');
                const emailInput = document.getElementById('email-input');
                const studentIdInput = document.getElementById('student-id-input');
                const registrarCodeInput = document.getElementById('registrar-code-input');
                const passwordInput = document.querySelector('input[name="password"]');

                // Hide all containers first
                usernameContainer.style.display = 'none';
                emailContainer.style.display = 'none';
                studentIdContainer.style.display = 'none';
                registrarCodeContainer.style.display = 'none';

                // Remove required attribute from all inputs
                usernameInput.removeAttribute('required');
                emailInput.removeAttribute('required');
                studentIdInput.removeAttribute('required');
                registrarCodeInput.removeAttribute('required');

                // Update form action based on role
                const form = document.getElementById('loginForm');

                // Show appropriate container based on role
                if (this.value === 'admin') {
                    usernameContainer.style.display = 'block';
                    usernameInput.setAttribute('required', '');
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
                    emailInput.value = '';
                    passwordInput.placeholder = 'Password';
                    form.action = "<?php echo e(route('login')); ?>";
                } else if (this.value === 'student') {
                    studentIdContainer.style.display = 'block';
                    studentIdInput.setAttribute('required', '');
                    studentIdInput.value = '';
                    passwordInput.placeholder = 'Password';
                    form.action = "<?php echo e(route('login')); ?>";
                } else if (this.value === 'registrar') {
                    emailContainer.style.display = 'block';
                    registrarCodeContainer.style.display = 'block';
                    emailInput.setAttribute('required', '');
                    registrarCodeInput.setAttribute('required', '');
                    emailInput.value = '';
                    registrarCodeInput.value = '';
                    passwordInput.placeholder = 'Registrar Password';
                    form.action = "<?php echo e(route('login')); ?>";

                    // Show a message about using dedicated registrar login
                    const existingAlert = document.querySelector('.registrar-alert');
                    if (existingAlert) {
                        existingAlert.remove();
                    }

                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-info registrar-alert mt-3';
                    alertDiv.innerHTML = `
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Registrar Login:</strong> For better security, please use the dedicated registrar login system.
                        <a href="<?php echo e(route('registrar.login')); ?>" class="btn btn-sm btn-primary ms-2">
                            <i class="fas fa-sign-in-alt me-1"></i>Go to Registrar Login
                        </a>
                    `;

                    const form = document.getElementById('loginForm');
                    form.parentNode.insertBefore(alertDiv, form.nextSibling);
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

                // Ensure admin fields are visible
                const usernameContainer = document.getElementById('username-container');
                const emailContainer = document.getElementById('email-container');
                const studentIdContainer = document.getElementById('student-id-container');
                const registrarCodeContainer = document.getElementById('registrar-code-container');

                usernameContainer.style.display = 'block';
                emailContainer.style.display = 'none';
                studentIdContainer.style.display = 'none';
                registrarCodeContainer.style.display = 'none';

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
</html><?php /**PATH H:\Test - Copy\resources\views/auth/login.blade.php ENDPATH**/ ?>