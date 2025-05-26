<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Management Module - Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
        }
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .role-section {
            padding: 3rem 0;
        }
        .role-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            height: 100%;
        }
        .admin-card {
            border-left: 5px solid #dc3545;
        }
        .registrar-card {
            border-left: 5px solid #28a745;
        }
        .demo-btn {
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .demo-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4">
                        <i class="fas fa-graduation-cap me-3"></i>
                        Subject Management Module
                    </h1>
                    <p class="lead mb-4">
                        A comprehensive solution for managing academic subjects with distinct roles for Administrators and Registrars. 
                        Create master subjects, offer them for specific terms, assign teachers, and manage schedules seamlessly.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('admin.login') }}" class="demo-btn btn btn-light btn-lg">
                            <i class="fas fa-user-shield me-2"></i>Admin Demo
                        </a>
                        <a href="{{ route('registrar.login') }}" class="demo-btn btn btn-outline-light btn-lg">
                            <i class="fas fa-user-edit me-2"></i>Registrar Demo
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fas fa-chalkboard-teacher fa-10x opacity-75"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Overview -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Key Features</h2>
                <p class="text-muted">Everything you need for comprehensive subject management</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-book fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Master Subject Creation</h5>
                            <p class="card-text">Admins create comprehensive master subjects with all academic details including semester, department, and track information.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-alt fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Subject Offerings</h5>
                            <p class="card-text">Registrars offer master subjects for specific school years, semesters, and sections with teacher assignments.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                            <h5 class="card-title">Schedule Management</h5>
                            <p class="card-text">Complete scheduling system with day, time, and room assignments for each subject offering.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Role-Based Access -->
    <section class="role-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Role-Based Management</h2>
                <p class="text-muted">Different capabilities for different user roles</p>
            </div>
            <div class="row g-4">
                <!-- Admin Role -->
                <div class="col-lg-6">
                    <div class="role-card admin-card">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-user-shield fa-2x text-danger me-3"></i>
                            <h3 class="mb-0 text-danger">Admin Role</h3>
                        </div>
                        <h5 class="mb-3">Master Subject Management</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Create master subjects with comprehensive details</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Set subject code, name, description, and units</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Define grade level, semester, and department</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Assign to specific tracks and strands</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Edit or delete existing master subjects</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Organize subjects by departments</li>
                        </ul>
                        <div class="mt-4">
                            <a href="{{ route('admin.login') }}" class="btn btn-danger demo-btn">
                                <i class="fas fa-sign-in-alt me-2"></i>Access Admin Panel
                            </a>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <strong>Credentials:</strong> Username: admin | Password: admin123
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Registrar Role -->
                <div class="col-lg-6">
                    <div class="role-card registrar-card">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-user-edit fa-2x text-success me-3"></i>
                            <h3 class="mb-0 text-success">Registrar Role</h3>
                        </div>
                        <h5 class="mb-3">Subject Offering & Assignment</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>View all admin-created master subjects</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Offer subjects for specific school years</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Set grade level and section assignments</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Assign teachers to subject offerings</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Create detailed class schedules</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Manage enrollment capacity and status</li>
                        </ul>
                        <div class="mt-4">
                            <a href="{{ route('registrar.login') }}" class="btn btn-success demo-btn">
                                <i class="fas fa-sign-in-alt me-2"></i>Access Registrar Panel
                            </a>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <strong>Credentials:</strong> Email: registrar@cnhs.edu.ph | Password: password123 | Secret: letmein
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Implementation Details -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Implementation Highlights</h2>
                <p class="text-muted">Technical features and capabilities</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-database text-primary me-2"></i>
                                Database Structure
                            </h5>
                            <ul class="list-unstyled">
                                <li><strong>subjects</strong> - Master subjects with enhanced fields</li>
                                <li><strong>subject_offerings</strong> - Registrar-created offerings</li>
                                <li><strong>subject_schedules</strong> - Detailed scheduling system</li>
                                <li><strong>Relationships</strong> - Proper foreign key constraints</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-shield-alt text-success me-2"></i>
                                Security & Validation
                            </h5>
                            <ul class="list-unstyled">
                                <li><strong>Role-based access</strong> - Proper authorization</li>
                                <li><strong>Data validation</strong> - Comprehensive form validation</li>
                                <li><strong>Unique constraints</strong> - Prevent duplicate offerings</li>
                                <li><strong>Secure operations</strong> - Protected CRUD operations</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Access -->
    <section class="py-5">
        <div class="container">
            <div class="text-center">
                <h2 class="fw-bold mb-4">Quick Access Links</h2>
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="{{ route('admin.subjects') }}" class="btn btn-outline-primary demo-btn">
                                <i class="fas fa-book me-2"></i>Admin Subjects
                            </a>
                            <a href="{{ route('registrar.subject-offerings.index') }}" class="btn btn-outline-success demo-btn">
                                <i class="fas fa-calendar-alt me-2"></i>Subject Offerings
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-info demo-btn">
                                <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                            </a>
                            <a href="{{ route('registrar.dashboard') }}" class="btn btn-outline-warning demo-btn">
                                <i class="fas fa-chart-bar me-2"></i>Registrar Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
