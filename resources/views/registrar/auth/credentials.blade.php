<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Login Credentials - CNHS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .credential-card { transition: transform 0.2s; }
        .credential-card:hover { transform: translateY(-5px); }
        .copy-btn { transition: all 0.2s; }
        .copy-btn:hover { background-color: #0d6efd !important; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-danger text-white text-center py-4">
                        <h1 class="mb-0"><i class="fas fa-key me-3"></i>Registrar Login Credentials</h1>
                        <p class="mb-0 mt-2 opacity-75">Complete login information for CNHS Registrar System</p>
                    </div>
                    <div class="card-body p-5">
                        
                        <!-- Success Alert -->
                        <div class="alert alert-success border-0 shadow-sm mb-5">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                                <div>
                                    <h4 class="alert-heading mb-1">✅ Registrar Database Fixed!</h4>
                                    <p class="mb-0">The missing <code>registrar_secret</code> column has been added and the registrar account has been properly configured.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Login Credentials -->
                        <h2 class="mb-4"><i class="fas fa-sign-in-alt text-danger me-2"></i>Registrar Login Credentials</h2>
                        
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="card credential-card border-0 shadow-sm h-100">
                                    <div class="card-body text-center">
                                        <div class="bg-primary text-white rounded-circle p-3 d-inline-flex mb-3">
                                            <i class="fas fa-envelope fa-2x"></i>
                                        </div>
                                        <h5 class="mb-3">Email Address</h5>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center fw-bold" 
                                                   value="registrar@cnhs.edu.ph" readonly id="email">
                                            <button class="btn btn-outline-primary copy-btn" type="button" 
                                                    onclick="copyToClipboard('email')" title="Copy to clipboard">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card credential-card border-0 shadow-sm h-100">
                                    <div class="card-body text-center">
                                        <div class="bg-success text-white rounded-circle p-3 d-inline-flex mb-3">
                                            <i class="fas fa-lock fa-2x"></i>
                                        </div>
                                        <h5 class="mb-3">Password</h5>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center fw-bold" 
                                                   value="password123" readonly id="password">
                                            <button class="btn btn-outline-success copy-btn" type="button" 
                                                    onclick="copyToClipboard('password')" title="Copy to clipboard">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Registrar Secret -->
                        <div class="row justify-content-center mb-5">
                            <div class="col-md-6">
                                <div class="card credential-card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="bg-warning text-dark rounded-circle p-3 d-inline-flex mb-3">
                                            <i class="fas fa-key fa-2x"></i>
                                        </div>
                                        <h5 class="mb-3">Registrar Secret</h5>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center fw-bold" 
                                                   value="letmein" readonly id="secret">
                                            <button class="btn btn-outline-warning copy-btn" type="button" 
                                                    onclick="copyToClipboard('secret')" title="Copy to clipboard">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted mt-2 d-block">Required for registrar authentication</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Login Steps -->
                        <h2 class="mb-4"><i class="fas fa-list-ol text-info me-2"></i>Step-by-Step Login Process</h2>
                        
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="fas fa-mouse-pointer me-2"></i>Step 1: Navigate to Login</h6>
                                    </div>
                                    <div class="card-body">
                                        <ol class="mb-0">
                                            <li>Go to: <code>http://localhost:8000/registrar/login</code></li>
                                            <li>Or click the "Login as Registrar" button below</li>
                                            <li>You'll see the registrar login form</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-keyboard me-2"></i>Step 2: Enter Credentials</h6>
                                    </div>
                                    <div class="card-body">
                                        <ol class="mb-0">
                                            <li>Email: <code>registrar@cnhs.edu.ph</code></li>
                                            <li>Password: <code>password123</code></li>
                                            <li>Registrar Secret: <code>letmein</code></li>
                                            <li>Click "Login" button</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- OTP Information -->
                        <div class="alert alert-warning border-0 shadow-sm mb-5">
                            <h5 class="alert-heading"><i class="fas fa-mobile-alt me-2"></i>OTP Verification</h5>
                            <hr>
                            <p class="mb-2">After entering your credentials, you'll be prompted for OTP verification:</p>
                            <ul class="mb-0">
                                <li><strong>OTP Code:</strong> <code>123456</code> (default test OTP)</li>
                                <li>The OTP will be "sent" to your email (simulated in development)</li>
                                <li>Enter the OTP code to complete the login process</li>
                                <li>You'll then be redirected to the registrar dashboard</li>
                            </ul>
                        </div>

                        <!-- Quick Access -->
                        <h2 class="mb-4"><i class="fas fa-rocket text-primary me-2"></i>Quick Access Links</h2>
                        
                        <div class="row g-3 mb-5">
                            <div class="col-md-4">
                                <a href="http://localhost:8000/registrar/login" class="btn btn-danger btn-lg w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login as Registrar
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="http://localhost:8000/registrar/students" class="btn btn-outline-danger btn-lg w-100">
                                    <i class="fas fa-user-graduate me-2"></i>Student Records
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="http://localhost:8000/registrar/dashboard" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a>
                            </div>
                        </div>

                        <!-- System Features -->
                        <h2 class="mb-4"><i class="fas fa-features text-success me-2"></i>Available Features After Login</h2>
                        
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="text-success mb-3">Student Records Management</h6>
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-plus me-2 text-success"></i>Add new student profiles</li>
                                            <li><i class="fas fa-edit me-2 text-warning"></i>Edit existing student records</li>
                                            <li><i class="fas fa-eye me-2 text-info"></i>View detailed student profiles</li>
                                            <li><i class="fas fa-filter me-2 text-primary"></i>Advanced filtering and search</li>
                                            <li><i class="fas fa-tasks me-2 text-secondary"></i>Bulk operations</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="text-primary mb-3">Enrollment Management</h6>
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-book me-2 text-success"></i>Subject enrollment control</li>
                                            <li><i class="fas fa-toggle-on me-2 text-primary"></i>Activate/deactivate enrollments</li>
                                            <li><i class="fas fa-chart-bar me-2 text-info"></i>Academic performance tracking</li>
                                            <li><i class="fas fa-sync-alt me-2 text-warning"></i>Re-enrollment support</li>
                                            <li><i class="fas fa-cogs me-2 text-secondary"></i>Curriculum adaptability</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Troubleshooting -->
                        <div class="alert alert-info border-0 shadow-sm">
                            <h5 class="alert-heading"><i class="fas fa-tools me-2"></i>Troubleshooting</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>If login still fails:</h6>
                                    <ul class="mb-3">
                                        <li>Clear browser cache and cookies</li>
                                        <li>Try incognito/private browsing mode</li>
                                        <li>Check that the server is running on port 8000</li>
                                        <li>Verify database connection is working</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>Database was fixed:</h6>
                                    <ul class="mb-3">
                                        <li>✅ Added missing <code>registrar_secret</code> column</li>
                                        <li>✅ Updated registrar account with correct credentials</li>
                                        <li>✅ Password changed to <code>password123</code></li>
                                        <li>✅ Secret set to <code>letmein</code></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="text-center mt-5">
                            <h3 class="text-danger mb-3">🎯 Registrar System Ready!</h3>
                            <p class="text-muted mb-4">Use the credentials above to access the comprehensive student records and enrollment management system.</p>
                            
                            <div class="d-flex justify-content-center gap-3 flex-wrap">
                                <a href="http://localhost:8000/registrar/login" class="btn btn-danger btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Start Using Registrar System
                                </a>
                                <a href="http://localhost:8000/registrar-student-management-summary" class="btn btn-outline-danger btn-lg">
                                    <i class="fas fa-info-circle me-2"></i>View System Features
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            element.setSelectionRange(0, 99999); // For mobile devices
            
            navigator.clipboard.writeText(element.value).then(function() {
                // Show success feedback
                const button = element.nextElementSibling;
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i>';
                button.classList.remove('btn-outline-primary', 'btn-outline-success', 'btn-outline-warning');
                button.classList.add('btn-success');
                
                setTimeout(function() {
                    button.innerHTML = originalHTML;
                    button.classList.remove('btn-success');
                    if (elementId === 'email') {
                        button.classList.add('btn-outline-primary');
                    } else if (elementId === 'password') {
                        button.classList.add('btn-outline-success');
                    } else {
                        button.classList.add('btn-outline-warning');
                    }
                }, 2000);
            });
        }
    </script>
</body>
</html>
