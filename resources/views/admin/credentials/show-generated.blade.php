@extends('layouts.admin')

@section('title', 'Generated Credentials')

@section('content')
<div class="page-header">
    <h1 class="page-title">Generated Student Credentials</h1>
    <p class="page-subtitle">{{ count($credentials) }} credential(s) have been successfully generated</p>
    <div class="page-actions">
        <button type="button" class="btn btn-success" onclick="copyAllCredentials()">
            <i class="fas fa-copy me-2"></i>Copy All
        </button>
        <button type="button" class="btn btn-primary" onclick="printCredentials()">
            <i class="fas fa-print me-2"></i>Print
        </button>
        <a href="{{ route('admin.credentials.generate') }}" class="btn btn-outline-primary">
            <i class="fas fa-plus me-2"></i>Generate More
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="alert alert-success border-0 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
                <div>
                    <h5 class="alert-heading mb-1">Credentials Generated Successfully!</h5>
                    <p class="mb-0">Please save or print these credentials before leaving this page. They will not be displayed again for security reasons.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @foreach($credentials as $index => $credential)
    <div class="col-lg-6 col-xl-4 mb-4">
        <div class="card credential-card">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    Student Credential #{{ $index + 1 }}
                </h6>
            </div>
            <div class="card-body">
                <div class="credential-info">
                    <div class="info-item">
                        <label class="info-label">Student ID:</label>
                        <div class="info-value">
                            <code class="credential-value">{{ $credential['student_id'] }}</code>
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                    onclick="copyToClipboard('{{ $credential['student_id'] }}', this)">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div class="info-item">
                        <label class="info-label">Password:</label>
                        <div class="info-value">
                            <code class="credential-value">{{ $credential['password'] }}</code>
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                    onclick="copyToClipboard('{{ $credential['password'] }}', this)">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    @if($credential['notes'])
                    <div class="info-item">
                        <label class="info-label">Notes:</label>
                        <div class="info-value">
                            <span class="text-muted">{{ $credential['notes'] }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-footer bg-light">
                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i>
                    Generated: {{ now()->format('M j, Y g:i A') }}
                </small>
            </div>
        </div>
    </div>
    @endforeach
</div>



<!-- Hidden div for printing -->
<div id="printableCredentials" class="d-none">
    <div class="print-header">
        <h2>CNHS Student Login Credentials</h2>
        <p>Generated on: {{ now()->format('F j, Y g:i A') }}</p>
        <hr>
    </div>

    @foreach($credentials as $index => $credential)
    <div class="print-credential">
        <h4>Student Credential #{{ $index + 1 }}</h4>
        <p><strong>Student ID:</strong> {{ $credential['student_id'] }}</p>
        <p><strong>Password:</strong> {{ $credential['password'] }}</p>
        @if($credential['notes'])
        <p><strong>Notes:</strong> {{ $credential['notes'] }}</p>
        @endif
        <hr>
    </div>
    @endforeach


</div>
@endsection

@section('styles')
<style>
.credential-card {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.credential-card:hover {
    border-color: #007bff;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
}

.credential-info {
    space-y: 1rem;
}

.info-item {
    margin-bottom: 1rem;
}

.info-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
    display: block;
    margin-bottom: 0.25rem;
}

.info-value {
    display: flex;
    align-items: center;
}

.credential-value {
    background-color: #f8f9fa;
    padding: 0.5rem;
    border-radius: 0.25rem;
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    border: 1px solid #dee2e6;
    flex: 1;
}

@media print {
    .print-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .print-credential {
        margin-bottom: 2rem;
        page-break-inside: avoid;
    }


}
</style>
@endsection

@section('scripts')
<script>
function copyToClipboard(text, button) {
    navigator.clipboard.writeText(text).then(function() {
        const originalIcon = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check text-success"></i>';
        setTimeout(() => {
            button.innerHTML = originalIcon;
        }, 1000);
    }).catch(function() {
        alert('Failed to copy to clipboard');
    });
}

function copyAllCredentials() {
    const credentials = {!! json_encode($credentials) !!};
    let text = "CNHS Student Login Credentials\n";
    text += "Generated on: " + new Date().toLocaleString() + "\n\n";

    credentials.forEach((credential, index) => {
        text += `Student Credential #${index + 1}\n`;
        text += `Student ID: ${credential.student_id}\n`;
        text += `Password: ${credential.password}\n`;
        if (credential.notes) {
            text += `Notes: ${credential.notes}\n`;
        }
        text += "\n";
    });



    navigator.clipboard.writeText(text).then(function() {
        alert('All credentials copied to clipboard!');
    }).catch(function() {
        alert('Failed to copy to clipboard');
    });
}

function printCredentials() {
    const printContent = document.getElementById('printableCredentials').innerHTML;
    const originalContent = document.body.innerHTML;

    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}
</script>
@endsection
