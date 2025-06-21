@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Registrar Login') }}</div>

                <div class="card-body">
                    <div class="btn-group w-100 mb-3" role="group">
                        <button type="button" class="btn btn-outline-primary {{ request()->is('login') ? 'active' : '' }}" onclick="setRole('admin')">Admin</button>
                        <button type="button" class="btn btn-outline-primary" onclick="setRole('teacher')">Teacher</button>
                        <button type="button" class="btn btn-outline-primary" onclick="setRole('student')">Student</button>
                        <button type="button" class="btn btn-outline-primary" onclick="setRole('registrar')">Registrar</button>
                    </div>
                    <input type="hidden" name="role" id="role" value="admin">

                    <form method="POST" action="{{ route('registrar.login') }}" id="loginForm">
                        @csrf
                        @if(session('step') == 'otp')
                            <div class="alert alert-info">OTP for testing: <strong>{{ session('otp') }}</strong></div>
                            <input type="hidden" name="step" value="otp">
                            <div class="row mb-3">
                                <label for="otp" class="col-md-4 col-form-label text-md-end">OTP</label>
                                <div class="col-md-6">
                                    <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror" name="otp" required autofocus>
                                    @if ($errors->has('otp'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('otp') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Verify OTP</button>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="step" value="credentials">
                            <div class="row mb-3" id="email-row">
                                <label for="email" class="col-md-4 col-form-label text-md-end">Email Address</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" required autofocus>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3" id="remember-row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function setRole(role) {
    document.getElementById('role').value = role;
    let form = document.getElementById('loginForm');
    if (role === 'admin') form.action = '/login';
    else if (role === 'teacher') form.action = '/teacher/login';
    else if (role === 'student') form.action = '/student/login';
    else if (role === 'registrar') form.action = '/registrar/login';
    // Only hide remember me for registrar
    document.getElementById('remember-row').style.display = (role === 'registrar') ? 'none' : '';
    // Do NOT hide email-row!
}
window.onload = function() {
    setRole(document.getElementById('role').value);
}
</script>
@endsection 