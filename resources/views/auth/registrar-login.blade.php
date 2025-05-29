@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Registrar Login</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('step') == 'otp')
                        <form method="POST" action="{{ route('registrar.login') }}">
                            @csrf
                            <input type="hidden" name="step" value="otp">

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Please enter the OTP sent to your email.
                            </div>

                            <div class="mb-3">
                                <label for="otp" class="form-label">OTP Code</label>
                                <input type="text" class="form-control @error('otp') is-invalid @enderror"
                                       id="otp" name="otp" required autofocus>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Verify OTP
                                </button>
                            </div>
                        </form>
                    @else
                        <form method="POST" action="{{ route('registrar.login') }}">
                            @csrf
                            <input type="hidden" name="step" value="credentials">

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>
                            </div>
                        </form>
                    @endif

                    <div class="mt-3 text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>Back to Main Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection