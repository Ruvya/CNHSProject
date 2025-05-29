<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Creation Diagnostic Tool</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .diagnostic-card { transition: transform 0.2s; }
        .diagnostic-card:hover { transform: translateY(-2px); }
        .code-block { background: #f8f9fa; border-left: 4px solid #007bff; padding: 15px; margin: 10px 0; }
        .error-block { background: #fff5f5; border-left: 4px solid #dc3545; padding: 15px; margin: 10px 0; }
        .success-block { background: #f0fff4; border-left: 4px solid #28a745; padding: 15px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h1 class="mb-0"><i class="fas fa-stethoscope me-3"></i>Subject Creation Diagnostic Tool</h1>
                        <p class="mb-0 mt-2 opacity-75">Comprehensive analysis and troubleshooting for subject creation issues</p>
                    </div>
                    <div class="card-body p-5">

                        <!-- Database Structure Check -->
                        <h2 class="mb-4"><i class="fas fa-database text-primary me-2"></i>Database Structure Analysis</h2>

                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="card diagnostic-card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="fas fa-table me-2"></i>Current Database Columns</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="code-block">
                                            <?php
                                            try {
                                                $columns = \Illuminate\Support\Facades\Schema::getColumnListing('subjects');
                                                echo "<strong>Subjects table columns:</strong><br>";
                                                foreach ($columns as $column) {
                                                    echo "✓ $column<br>";
                                                }
                                            } catch (\Exception $e) {
                                                echo "<span class='text-danger'>Error: " . $e->getMessage() . "</span>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card diagnostic-card border-warning">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Column Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="code-block">
                                            <?php
                                            try {
                                                $structure = \Illuminate\Support\Facades\DB::select('DESCRIBE subjects');
                                                echo "<strong>Column specifications:</strong><br>";
                                                foreach ($structure as $col) {
                                                    $nullable = $col->Null === 'YES' ? 'NULL' : 'NOT NULL';
                                                    $key = $col->Key ? " ({$col->Key})" : '';
                                                    echo "<small>{$col->Field}: {$col->Type} {$nullable}{$key}</small><br>";
                                                }
                                            } catch (\Exception $e) {
                                                echo "<span class='text-danger'>Error: " . $e->getMessage() . "</span>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Model Configuration Check -->
                        <h2 class="mb-4"><i class="fas fa-cogs text-success me-2"></i>Model Configuration Analysis</h2>

                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="card diagnostic-card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-code me-2"></i>Subject Model Fillable</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="code-block">
                                            <?php
                                            try {
                                                $subject = new \App\Models\Subject();
                                                $fillable = $subject->getFillable();
                                                echo "<strong>Fillable fields:</strong><br>";
                                                foreach ($fillable as $field) {
                                                    echo "✓ $field<br>";
                                                }
                                            } catch (\Exception $e) {
                                                echo "<span class='text-danger'>Error: " . $e->getMessage() . "</span>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card diagnostic-card border-secondary">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Model Methods</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="code-block">
                                            <?php
                                            try {
                                                $subject = new \App\Models\Subject();
                                                $methods = get_class_methods($subject);
                                                $customMethods = array_filter($methods, function($method) {
                                                    return strpos($method, 'get') === 0 || strpos($method, 'set') === 0;
                                                });
                                                echo "<strong>Custom accessors/mutators:</strong><br>";
                                                foreach ($customMethods as $method) {
                                                    if (strpos($method, 'Attribute') !== false) {
                                                        echo "✓ $method<br>";
                                                    }
                                                }
                                            } catch (\Exception $e) {
                                                echo "<span class='text-danger'>Error: " . $e->getMessage() . "</span>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Test Subject Creation -->
                        <h2 class="mb-4"><i class="fas fa-vial text-warning me-2"></i>Subject Creation Test</h2>

                        <div class="row g-4 mb-5">
                            <div class="col-md-12">
                                <div class="card diagnostic-card border-warning">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0"><i class="fas fa-test-tube me-2"></i>Test Subject Creation Process</h6>
                                    </div>
                                    <div class="card-body">
                                        <form action="/test-subject-creation" method="POST" class="mb-4">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="test_name" class="form-label">Subject Name</label>
                                                        <input type="text" class="form-control" id="test_name" name="name" value="Test Subject" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="test_code" class="form-label">Subject Code</label>
                                                        <input type="text" class="form-control" id="test_code" name="code" value="TEST{{ rand(100, 999) }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="test_grade_level" class="form-label">Grade Level</label>
                                                        <select class="form-select" id="test_grade_level" name="grade_level" required>
                                                            <option value="Grade 11">Grade 11</option>
                                                            <option value="Grade 12">Grade 12</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="test_units" class="form-label">Units</label>
                                                        <select class="form-select" id="test_units" name="units" required>
                                                            <option value="3">3 units</option>
                                                            <option value="4">4 units</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="test_track" class="form-label">Track</label>
                                                        <select class="form-select" id="test_track" name="track">
                                                            <option value="">Optional</option>
                                                            <option value="Academic Track">Academic Track</option>
                                                            <option value="TVL Track">TVL Track</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-warning">
                                                <i class="fas fa-play me-2"></i>Test Subject Creation
                                            </button>
                                        </form>

                                        @if(session('test_result'))
                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-info-circle me-2"></i>Test Result:</h6>
                                                <pre>{{ session('test_result') }}</pre>
                                            </div>
                                        @endif

                                        @if(session('test_error'))
                                            <div class="alert alert-danger">
                                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Test Error:</h6>
                                                <pre>{{ session('test_error') }}</pre>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Common Issues & Solutions -->
                        <h2 class="mb-4"><i class="fas fa-tools text-danger me-2"></i>Common Issues & Solutions</h2>

                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="card diagnostic-card border-danger">
                                    <div class="card-header bg-danger text-white">
                                        <h6 class="mb-0"><i class="fas fa-bug me-2"></i>Possible Issues</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled">
                                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i><strong>Field Mismatch:</strong> Form fields don't match database columns</li>
                                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i><strong>Missing Columns:</strong> Required database columns don't exist</li>
                                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i><strong>Model Issues:</strong> Fillable array or mutators causing problems</li>
                                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i><strong>Validation Errors:</strong> Form validation failing</li>
                                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i><strong>Unique Constraints:</strong> Duplicate subject codes</li>
                                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i><strong>Foreign Key Issues:</strong> Invalid teacher_id references</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card diagnostic-card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-wrench me-2"></i>Quick Fixes</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled">
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Clear Cache:</strong> <code>php artisan cache:clear</code></li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Run Migrations:</strong> <code>php artisan migrate</code></li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Check Logs:</strong> <code>storage/logs/laravel.log</code></li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Verify Routes:</strong> <code>php artisan route:list</code></li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Test Database:</strong> Use tinker to test model</li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Check Permissions:</strong> Verify file/folder permissions</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Manual Test Form -->
                        <h2 class="mb-4"><i class="fas fa-hand-paper text-info me-2"></i>Manual Subject Creation Test</h2>

                        <div class="row g-4 mb-5">
                            <div class="col-md-12">
                                <div class="card diagnostic-card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="fas fa-edit me-2"></i>Try Creating a Subject Manually</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Instructions:</strong> Fill out this form to test subject creation. Any errors will be displayed below.
                                        </div>

                                        <form action="{{ route('admin.subjects.store') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="mb-3">
                                                        <label for="manual_name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                               id="manual_name" name="name" value="{{ old('name', 'Diagnostic Test Subject') }}" required>
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="manual_code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                                               id="manual_code" name="code" value="{{ old('code', 'DIAG' . rand(100, 999)) }}" required>
                                                        @error('code')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="manual_grade_level" class="form-label">Grade Level <span class="text-danger">*</span></label>
                                                        <select class="form-select @error('grade_level') is-invalid @enderror" id="manual_grade_level" name="grade_level" required>
                                                            <option value="">Select Grade Level</option>
                                                            <option value="Grade 11" {{ old('grade_level') === 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                                            <option value="Grade 12" {{ old('grade_level') === 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                                                        </select>
                                                        @error('grade_level')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="manual_units" class="form-label">Units <span class="text-danger">*</span></label>
                                                        <select class="form-select @error('units') is-invalid @enderror" id="manual_units" name="units" required>
                                                            <option value="">Select Units</option>
                                                            <option value="1" {{ old('units') == 1 ? 'selected' : '' }}>1 unit</option>
                                                            <option value="2" {{ old('units') == 2 ? 'selected' : '' }}>2 units</option>
                                                            <option value="3" {{ old('units') == 3 ? 'selected' : '' }}>3 units</option>
                                                            <option value="4" {{ old('units') == 4 ? 'selected' : '' }}>4 units</option>
                                                        </select>
                                                        @error('units')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="manual_track" class="form-label">Track</label>
                                                        <select class="form-select" id="manual_track" name="track">
                                                            <option value="">Select Track (Optional)</option>
                                                            <option value="Academic Track" {{ old('track') === 'Academic Track' ? 'selected' : '' }}>Academic Track</option>
                                                            <option value="TVL Track" {{ old('track') === 'TVL Track' ? 'selected' : '' }}>TVL Track</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="manual_description" class="form-label">Description</label>
                                                <textarea class="form-control" id="manual_description" name="description" rows="3" placeholder="Subject description (optional)">{{ old('description', 'This is a diagnostic test subject for troubleshooting.') }}</textarea>
                                            </div>

                                            <div class="d-flex gap-3">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-2"></i>Create Test Subject
                                                </button>
                                                <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
                                                    <i class="fas fa-list me-2"></i>View All Subjects
                                                </a>
                                            </div>
                                        </form>

                                        @if($errors->any())
                                            <div class="alert alert-danger mt-4">
                                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Validation Errors:</h6>
                                                <ul class="mb-0">
                                                    @foreach($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @if(session('success'))
                                            <div class="alert alert-success mt-4">
                                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                            </div>
                                        @endif

                                        @if(session('error'))
                                            <div class="alert alert-danger mt-4">
                                                <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <h2 class="mb-4"><i class="fas fa-link text-secondary me-2"></i>Quick Navigation</h2>

                        <div class="row g-3 mb-5">
                            <div class="col-md-3">
                                <a href="{{ route('admin.login') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i>Admin Login
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.subjects.create') }}" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-plus me-2"></i>Create Subject
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.subjects.index') }}" class="btn btn-info btn-lg w-100">
                                    <i class="fas fa-list me-2"></i>Subjects List
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="text-center mt-5">
                            <h3 class="text-primary mb-3">🔍 Subject Creation Diagnostic Complete</h3>
                            <p class="text-muted mb-4">Use the information above to identify and resolve subject creation issues.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
