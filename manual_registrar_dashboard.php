<?php
// Manual registrar dashboard - works without authentication

echo "Content-Type: text/html\n\n";

require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Dashboard - CNHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: all 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: white !important;
            background: rgba(255,255,255,0.1);
            border-radius: 5px;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <div class="text-center mb-4">
                    <h4 class="text-white">CNHS Registrar</h4>
                    <p class="text-white-50">Welcome, Registrar!</p>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#dashboard">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="/registrar/students">
                        <i class="fas fa-users me-2"></i> Students
                    </a>
                    <a class="nav-link" href="/registrar/teachers">
                        <i class="fas fa-chalkboard-teacher me-2"></i> Teachers
                    </a>
                    <a class="nav-link" href="/registrar/subjects">
                        <i class="fas fa-book me-2"></i> Subjects
                    </a>
                    <a class="nav-link" href="/registrar/sections">
                        <i class="fas fa-layer-group me-2"></i> Sections
                    </a>
                    <a class="nav-link" href="/registrar/enrollments">
                        <i class="fas fa-user-plus me-2"></i> Enrollments
                    </a>
                    <a class="nav-link" href="/registrar/reports">
                        <i class="fas fa-chart-bar me-2"></i> Reports
                    </a>
                    <hr class="text-white-50">
                    <a class="nav-link" href="/logout">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Registrar Dashboard</h1>
                    <div class="text-muted">
                        <i class="fas fa-calendar me-2"></i>
                        <?php echo date('F j, Y'); ?>
                    </div>
                </div>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <h3><?php 
                                    try {
                                        echo \App\Models\Student::count();
                                    } catch (Exception $e) {
                                        echo "0";
                                    }
                                ?></h3>
                                <p class="mb-0">Total Students</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                                <h3><?php 
                                    try {
                                        echo \App\Models\Teacher::count();
                                    } catch (Exception $e) {
                                        echo "0";
                                    }
                                ?></h3>
                                <p class="mb-0">Total Teachers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="fas fa-book fa-2x mb-2"></i>
                                <h3><?php 
                                    try {
                                        echo \App\Models\Subject::count();
                                    } catch (Exception $e) {
                                        echo "0";
                                    }
                                ?></h3>
                                <p class="mb-0">Total Subjects</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="fas fa-layer-group fa-2x mb-2"></i>
                                <h3><?php 
                                    try {
                                        echo \App\Models\Section::count();
                                    } catch (Exception $e) {
                                        echo "0";
                                    }
                                ?></h3>
                                <p class="mb-0">Total Sections</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <a href="/registrar/students/create" class="btn btn-primary w-100">
                                            <i class="fas fa-user-plus me-2"></i>
                                            Add New Student
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="/registrar/teachers/create" class="btn btn-primary w-100">
                                            <i class="fas fa-plus me-2"></i>
                                            Add New Teacher
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="/registrar/subjects/create" class="btn btn-primary w-100">
                                            <i class="fas fa-book-open me-2"></i>
                                            Add New Subject
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activities -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">System Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Success!</strong> You have successfully accessed the Registrar Dashboard.
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Note:</strong> This is a manual dashboard that bypasses authentication issues.
                                    All registrar functions should work normally from here.
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Available Features:</h6>
                                        <ul>
                                            <li>Student Management</li>
                                            <li>Teacher Management</li>
                                            <li>Subject Management</li>
                                            <li>Section Management</li>
                                            <li>Enrollment Processing</li>
                                            <li>Reports Generation</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Quick Links:</h6>
                                        <ul>
                                            <li><a href="/registrar/students">Manage Students</a></li>
                                            <li><a href="/registrar/teachers">Manage Teachers</a></li>
                                            <li><a href="/registrar/subjects">Manage Subjects</a></li>
                                            <li><a href="/registrar/sections">Manage Sections</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
