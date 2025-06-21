@extends('layouts.teacher')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-header">
                <i class="fas fa-tachometer-alt"></i>
                <div>
                    <h1>Dashboard</h1>
                    <p>Welcome back! Here's an overview of your classes and activities.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Shortcuts Frame -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Menu Shortcuts</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-4 col-md-6 mb-4">
                    <a href="{{ route('teacher.classlist') }}" class="card-link">
                        <div class="card shortcut-card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="card-title mb-0">All Students</h5>
                                    <p class="card-text text-muted mb-0">View your students</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-md-6 mb-4">
                    <a href="{{ route('teacher.subjects') }}" class="card-link">
                        <div class="card shortcut-card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-book text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="card-title mb-0">Subjects</h5>
                                    <p class="card-text text-muted mb-0">Manage your subjects</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-md-6 mb-4">
                    <a href="{{ route('teacher.grade-management') }}" class="card-link">
                        <div class="card shortcut-card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="icon-circle bg-info">
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="card-title mb-0">Grade Management</h5>
                                    <p class="card-text text-muted mb-0">Input and view grades</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-md-6 mb-4">
                    <a href="{{ route('teacher.announcements.index') }}" class="card-link">
                        <div class="card shortcut-card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-bullhorn text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="card-title mb-0">Announcements</h5>
                                    <p class="card-text text-muted mb-0">Create and view posts</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-md-6 mb-4">
                    <a href="{{ route('teacher.profile') }}" class="card-link">
                        <div class="card shortcut-card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="icon-circle bg-danger">
                                    <i class="fas fa-user-circle text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="card-title mb-0">My Profile</h5>
                                    <p class="card-text text-muted mb-0">Update your information</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/vanilla-js-calendar@1.6.0/build/vanilla-js-calendar.min.css" rel="stylesheet">
<style>
    .dashboard-header {
        background: linear-gradient(115deg, #f97316 60%, #3b82f6 60%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(249, 115, 22, 0.25);
        display: flex;
        align-items: center;
    }
    .dashboard-header i {
        font-size: 3rem;
        margin-right: 1.5rem;
        opacity: 0.8;
    }
    .dashboard-header h1 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .dashboard-header p {
        margin: 0.25rem 0 0 0;
        opacity: 0.9;
        font-size: 1.1rem;
    }
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        background-color: rgba(255, 255, 255, 0.85);
    }
    .shortcut-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .shortcut-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .card-link {
        text-decoration: none;
    }
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-circle i {
        font-size: 1.5rem;
    }
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
    .text-gray-800 {
        color: #5a5c69 !important;
    }
</style>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/vanilla-js-calendar@1.6.0/build/vanilla-js-calendar.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendar = new VanillaJsCalendar('#calendar');
    });
</script>
@endsection