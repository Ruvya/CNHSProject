@extends('layouts.admin')

@section('title', 'Email Configuration Test')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Email Configuration Test</h1>
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('email_success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('email_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('email_warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('email_warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Configuration Status -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog"></i> SMTP Configuration Status
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge badge-{{ $configStatus['is_configured'] ? 'success' : 'danger' }} badge-lg">
                            {{ $configStatus['is_configured'] ? '✅ Configured' : '❌ Incomplete' }}
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Setting</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($configStatus['configurations'] as $key => $value)
                                <tr>
                                    <td><strong>{{ $key }}</strong></td>
                                    <td>
                                        @if($value)
                                            <span class="text-success">{{ $value }}</span>
                                        @else
                                            <span class="text-danger">❌ Not Set</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(!$configStatus['is_configured'])
                        <div class="alert alert-warning mt-3">
                            <h6><i class="fas fa-exclamation-triangle"></i> Configuration Required</h6>
                            <p class="mb-0">Please configure the missing SMTP settings in your .env file before testing email functionality.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Test Email -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-envelope-open-text"></i> Test Email Sending
                    </h6>
                </div>
                <div class="card-body">
                    @if($configStatus['is_configured'])
                        <form action="{{ route('admin.users.test-email') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="test_email" class="form-label">Test Email Address</label>
                                <input type="email" 
                                       class="form-control @error('test_email') is-invalid @enderror" 
                                       id="test_email" 
                                       name="test_email" 
                                       value="{{ old('test_email', config('mail.from.address')) }}" 
                                       required>
                                @error('test_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    A test teacher credentials email will be sent to this address.
                                </small>
                            </div>

                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-paper-plane"></i> Send Test Email
                            </button>
                        </form>

                        <div class="mt-4">
                            <h6 class="text-primary">What happens when you test:</h6>
                            <ul class="small">
                                <li>A sample CNHS teacher credentials email will be sent</li>
                                <li>The email will contain dummy login information</li>
                                <li>This verifies your SMTP configuration is working</li>
                                <li>Check your inbox (and spam folder) for the test email</li>
                                <li>The email will display with Calingcaging National High School branding</li>
                                <li>Test the password change flow described in the email</li>
                            </ul>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-times-circle"></i> Cannot Test Email</h6>
                            <p class="mb-0">Email configuration is incomplete. Please configure all required SMTP settings first.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Help Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-question-circle"></i> SMTP Configuration Help
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Gmail Configuration</h6>
                            <ul class="small">
                                <li><strong>MAIL_HOST:</strong> smtp.gmail.com</li>
                                <li><strong>MAIL_PORT:</strong> 587</li>
                                <li><strong>MAIL_ENCRYPTION:</strong> tls</li>
                                <li><strong>MAIL_USERNAME:</strong> your-email@gmail.com</li>
                                <li><strong>MAIL_PASSWORD:</strong> your-app-password</li>
                            </ul>
                            <p class="small text-muted">
                                <strong>Note:</strong> For Gmail, you need to use an App Password, not your regular password.
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Outlook/Hotmail Configuration</h6>
                            <ul class="small">
                                <li><strong>MAIL_HOST:</strong> smtp-mail.outlook.com</li>
                                <li><strong>MAIL_PORT:</strong> 587</li>
                                <li><strong>MAIL_ENCRYPTION:</strong> tls</li>
                                <li><strong>MAIL_USERNAME:</strong> your-email@outlook.com</li>
                                <li><strong>MAIL_PASSWORD:</strong> your-password</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <h6 class="text-primary">Troubleshooting Tips</h6>
                        <ul class="small">
                            <li>Make sure your email provider allows SMTP access</li>
                            <li>For Gmail, enable 2-factor authentication and use App Passwords</li>
                            <li>Check that your firewall allows outbound connections on the SMTP port</li>
                            <li>Verify that the email credentials are correct</li>
                            <li>Some hosting providers block outbound SMTP connections</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
