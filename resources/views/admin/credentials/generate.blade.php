@extends('layouts.admin')

@section('title', 'Generate Student Credentials')

@section('content')
<div class="page-header">
    <h1 class="page-title">Generate Student Credentials</h1>
    <p class="page-subtitle">Create unique login credentials for students. All generated credentials use the default password: <code class="bg-light px-2 py-1 rounded">Temp_123</code></p>
    <div class="page-actions">
        <a href="{{ route('admin.credentials.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-list me-2"></i>View All Credentials
        </a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>

<!-- Information Alert -->
<div class="alert alert-info">
    <h6><i class="fas fa-info-circle me-2"></i>Default Password Information</h6>
    <p class="mb-2">All generated student credentials will use the standard temporary password:</p>
    <ul class="mb-2">
        <li><strong>Password:</strong> <code class="bg-light px-2 py-1 rounded">Temp_123</code> (case-sensitive)</li>
        <li>Students use their assigned Student ID as the username</li>
        <li>Students can change their password after first login</li>
    </ul>
    <small class="text-muted">This ensures consistency and makes it easy for students to access their accounts.</small>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-key me-2 text-primary"></i>
                    Credential Generation
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.credentials.store') }}" method="POST" id="generateForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Number of Credentials</label>
                                <input type="number"
                                       class="form-control @error('quantity') is-invalid @enderror"
                                       id="quantity"
                                       name="quantity"
                                       value="{{ old('quantity', 1) }}"
                                       min="1"
                                       max="50"
                                       required>
                                <div class="form-text">Generate between 1 and 50 credentials at once</div>

                                <!-- Quick quantity buttons -->
                                <div class="mt-2">
                                    <small class="text-muted d-block mb-1">Quick select:</small>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-secondary" onclick="setQuantity(5)">5</button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="setQuantity(10)">10</button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="setQuantity(20)">20</button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="setQuantity(30)">30</button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="setQuantity(50)">50</button>
                                    </div>
                                </div>

                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes (Optional)</label>
                                <input type="text"
                                       class="form-control @error('notes') is-invalid @enderror"
                                       id="notes"
                                       name="notes"
                                       value="{{ old('notes') }}"
                                       maxlength="500"
                                       placeholder="e.g., Grade 11 batch, New students">

                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary" id="generateBtn">
                            <i class="fas fa-cog me-2"></i>
                            Generate Credentials
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-shield-alt me-2 text-success"></i>
                    Security Features
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Unique Student IDs with year prefix
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Strong 12-character passwords
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Passwords include uppercase, lowercase, numbers, and symbols
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        One-time use credentials
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        Audit trail of credential usage
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection



@section('scripts')
<script>
document.getElementById('generateForm').addEventListener('submit', function() {
    const btn = document.getElementById('generateBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';
});

function setQuantity(quantity) {
    document.getElementById('quantity').value = quantity;

    // Add visual feedback
    const buttons = document.querySelectorAll('.btn-group .btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
}
</script>
@endsection
