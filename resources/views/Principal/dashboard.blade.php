@extends('Principal.layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h3 text-primary">Welcome, {{ Auth::guard('principal')->user()->name }}!</h1>
                    <p class="text-muted mb-0">Principal's Dashboard</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Total Students Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 shadow h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase text-primary fw-bold text-xs mb-1">
                                TOTAL STUDENTS
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">2,500</div>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Teachers Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase text-success fw-bold text-xs mb-1">
                                TOTAL TEACHERS
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">120</div>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-chalkboard-teacher fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Classes Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-4 shadow h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase text-info fw-bold text-xs mb-1">
                                TOTAL CLASSES
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">85</div>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-school fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    <!-- Content Row -->
    <div class="row">
        <!-- Recent Announcements -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">Recent Announcements</h6>
                    <button class="btn btn-sm btn-primary">Create New</button>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Final Examination Schedule</h6>
                                <small class="text-muted">Today</small>
                            </div>
                            <p class="mb-1 text-muted">Schedule for final examinations has been released</p>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Faculty Meeting</h6>
                                <small class="text-muted">Yesterday</small>
                            </div>
                            <p class="mb-1 text-muted">Monthly faculty meeting scheduled for next week</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Upcoming Events</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">School Foundation Day</h6>
                                <small class="text-muted">June 15, 2024</small>
                            </div>
                            <p class="mb-1 text-muted">Annual celebration of school foundation</p>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Graduation Ceremony</h6>
                                <small class="text-muted">July 1, 2024</small>
                            </div>
                            <p class="mb-1 text-muted">Grade 12 graduation ceremony</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .border-start {
        border-left-width: 4px !important;
    }
    .text-gray-800 {
        color: #2d3748 !important;
    }
    .text-xs {
        font-size: 0.8rem;
    }
    .card {
        border: none;
    }
    .list-group-item {
        border-left: 0;
        border-right: 0;
    }
    .list-group-item:first-child {
        border-top: 0;
    }
    .list-group-item:last-child {
        border-bottom: 0;
    }
</style>
@endsection 