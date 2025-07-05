@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-envelope"></i> Email Management System</h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Email Type Tabs -->
                    <ul class="nav nav-tabs" id="emailTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                                <i class="fas fa-envelope"></i> General Email
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="welcome-tab" data-bs-toggle="tab" data-bs-target="#welcome" type="button" role="tab">
                                <i class="fas fa-user-plus"></i> Welcome Email
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="notification-tab" data-bs-toggle="tab" data-bs-target="#notification" type="button" role="tab">
                                <i class="fas fa-bell"></i> Notification Email
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="bulk-tab" data-bs-toggle="tab" data-bs-target="#bulk" type="button" role="tab">
                                <i class="fas fa-users"></i> Bulk Email
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="test-tab" data-bs-toggle="tab" data-bs-target="#test" type="button" role="tab">
                                <i class="fas fa-cog"></i> Test Configuration
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="emailTabContent">
                        <!-- General Email Tab -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <form method="POST" action="{{ route('mail.send.general') }}" class="mt-4">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="to_email" class="form-label">Recipient Email</label>
                                            <input type="email" class="form-control @error('to_email') is-invalid @enderror" 
                                                   id="to_email" name="to_email" value="{{ old('to_email') }}" required>
                                            @error('to_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sender_name" class="form-label">Sender Name</label>
                                            <input type="text" class="form-control" id="sender_name" name="sender_name" 
                                                   value="{{ old('sender_name', 'CNHS System') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                           id="subject" name="subject" value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Send Email
                                </button>
                            </form>
                        </div>

                        <!-- Welcome Email Tab -->
                        <div class="tab-pane fade" id="welcome" role="tabpanel">
                            <form method="POST" action="{{ route('mail.send.welcome') }}" class="mt-4">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="welcome_to_email" class="form-label">Recipient Email</label>
                                            <input type="email" class="form-control @error('to_email') is-invalid @enderror" 
                                                   id="welcome_to_email" name="to_email" value="{{ old('to_email') }}" required>
                                            @error('to_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="user_name" class="form-label">User Name</label>
                                            <input type="text" class="form-control @error('user_name') is-invalid @enderror" 
                                                   id="user_name" name="user_name" value="{{ old('user_name') }}" required>
                                            @error('user_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="user_type" class="form-label">User Type</label>
                                    <select class="form-select @error('user_type') is-invalid @enderror" 
                                            id="user_type" name="user_type" required>
                                        <option value="">Select User Type</option>
                                        <option value="student" {{ old('user_type') == 'student' ? 'selected' : '' }}>Student</option>
                                        <option value="teacher" {{ old('user_type') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                        <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="registrar" {{ old('user_type') == 'registrar' ? 'selected' : '' }}>Registrar</option>
                                        <option value="principal" {{ old('user_type') == 'principal' ? 'selected' : '' }}>Principal</option>
                                    </select>
                                    @error('user_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-user-plus"></i> Send Welcome Email
                                </button>
                            </form>
                        </div>

                        <!-- Notification Email Tab -->
                        <div class="tab-pane fade" id="notification" role="tabpanel">
                            <form method="POST" action="{{ route('mail.send.notification') }}" class="mt-4">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="notification_to_email" class="form-label">Recipient Email</label>
                                            <input type="email" class="form-control @error('to_email') is-invalid @enderror" 
                                                   id="notification_to_email" name="to_email" value="{{ old('to_email') }}" required>
                                            @error('to_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="notification_type" class="form-label">Notification Type</label>
                                            <input type="text" class="form-control @error('notification_type') is-invalid @enderror" 
                                                   id="notification_type" name="notification_type" value="{{ old('notification_type') }}" 
                                                   placeholder="e.g., Grade Update, System Maintenance" required>
                                            @error('notification_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="notification_message" class="form-label">Notification Message</label>
                                    <textarea class="form-control @error('notification_message') is-invalid @enderror" 
                                              id="notification_message" name="notification_message" rows="5" required>{{ old('notification_message') }}</textarea>
                                    @error('notification_message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="action_url" class="form-label">Action URL (Optional)</label>
                                    <input type="url" class="form-control @error('action_url') is-invalid @enderror" 
                                           id="action_url" name="action_url" value="{{ old('action_url') }}" 
                                           placeholder="https://example.com/action">
                                    @error('action_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-bell"></i> Send Notification
                                </button>
                            </form>
                        </div>

                        <!-- Bulk Email Tab -->
                        <div class="tab-pane fade" id="bulk" role="tabpanel">
                            <form method="POST" action="{{ route('mail.send.bulk') }}" class="mt-4">
                                @csrf
                                <div class="mb-3">
                                    <label for="email_list" class="form-label">Email List</label>
                                    <textarea class="form-control @error('email_list') is-invalid @enderror" 
                                              id="email_list" name="email_list" rows="4" required 
                                              placeholder="Enter email addresses separated by commas or new lines">{{ old('email_list') }}</textarea>
                                    @error('email_list')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Enter multiple email addresses separated by commas or new lines.</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="bulk_subject" class="form-label">Subject</label>
                                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                                   id="bulk_subject" name="subject" value="{{ old('subject') }}" required>
                                            @error('subject')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="bulk_sender_name" class="form-label">Sender Name</label>
                                            <input type="text" class="form-control" id="bulk_sender_name" name="sender_name" 
                                                   value="{{ old('sender_name', 'CNHS System') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="bulk_message" class="form-label">Message</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="bulk_message" name="message" rows="6" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-users"></i> Send Bulk Email
                                </button>
                            </form>
                        </div>

                        <!-- Test Configuration Tab -->
                        <div class="tab-pane fade" id="test" role="tabpanel">
                            <div class="mt-4">
                                <h5>Test Email Configuration</h5>
                                <p>Click the button below to send a test email to verify that your SMTP configuration is working correctly.</p>
                                
                                <div class="alert alert-info">
                                    <h6>Current Configuration:</h6>
                                    <ul class="mb-0">
                                        <li><strong>SMTP Host:</strong> {{ config('mail.mailers.smtp.host') }}</li>
                                        <li><strong>SMTP Port:</strong> {{ config('mail.mailers.smtp.port') }}</li>
                                        <li><strong>From Address:</strong> {{ config('mail.from.address') }}</li>
                                        <li><strong>From Name:</strong> {{ config('mail.from.name') }}</li>
                                    </ul>
                                </div>
                                
                                <form method="POST" action="{{ route('mail.test.config') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fas fa-cog"></i> Test Email Configuration
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.nav-tabs .nav-link {
    color: #495057;
}
.nav-tabs .nav-link.active {
    color: #007bff;
    font-weight: bold;
}
.tab-content {
    border: 1px solid #dee2e6;
    border-top: none;
    padding: 20px;
    background-color: #f8f9fa;
}
</style>
@endsection
