@extends('layouts.registrar')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 text-center mb-4">
            <h1 class="h3 text-gray-800">Registrar Dashboard</h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <!-- Total Students Card -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStudents }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Subjects Card -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                My Subjects</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $mySubjects }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-edit fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary text-center">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <a href="{{ route('registrar.subjects') }}" class="btn btn-success btn-block mb-3">
                                <i class="fas fa-book"></i> Manage Subjects
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('registrar.subject-offerings.index') }}" class="btn btn-primary btn-block mb-3">
                                <i class="fas fa-calendar-alt"></i> Subject Offerings
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('registrar.profile') }}" class="btn btn-info btn-block mb-3">
                                <i class="fas fa-user-cog"></i> Update Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection