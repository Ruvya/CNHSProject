@extends('layouts.admin')

@section('title', 'Generate Student Credentials')

@section('content')
<div class="page-header">
    <h1 class="page-title">Generate Student Credentials</h1>
    <p class="page-subtitle">Create unique login credentials for students that can be used for first-time login</p>
    <div class="page-actions">
        <a href="{{ route('admin.credentials.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-list me-2"></i>View All Credentials
        </a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
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
                                <div class="form-text">Add a note to help identify these credentials</div>
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

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2 text-info"></i>
                    How It Works
                </h5>
            </div>
            <div class="card-body">
                <div class="step-list">
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <strong>Generate Credentials</strong>
                            <p class="text-muted mb-0">System creates unique Student IDs and secure passwords</p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <strong>Share with Students</strong>
                            <p class="text-muted mb-0">Provide the generated credentials to students</p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <strong>First Login</strong>
                            <p class="text-muted mb-0">Students use credentials to log in for the first time</p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <strong>Complete Profile</strong>
                            <p class="text-muted mb-0">Students can then update their profile information</p>
                        </div>
                    </div>
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

@section('styles')
<style>
.step-list {
    position: relative;
}

.step-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    position: relative;
}

.step-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 15px;
    top: 35px;
    width: 2px;
    height: calc(100% + 0.5rem);
    background-color: #e9ecef;
}

.step-number {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.875rem;
    margin-right: 1rem;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}

.step-content {
    flex: 1;
    padding-top: 0.25rem;
}

.step-content strong {
    display: block;
    margin-bottom: 0.25rem;
    color: #495057;
}

.step-content p {
    font-size: 0.875rem;
    line-height: 1.4;
}
</style>
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
