<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Creation - Complete Solution Guide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); min-height: 100vh; }
        .solution-card { transition: transform 0.2s; }
        .solution-card:hover { transform: translateY(-2px); }
        .step-number { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-success text-white text-center py-4">
                        <h1 class="mb-0"><i class="fas fa-check-circle me-3"></i>Subject Creation - Complete Solution</h1>
                        <p class="mb-0 mt-2 opacity-75">Comprehensive guide to fix all subject creation issues</p>
                    </div>
                    <div class="card-body p-5">

                        <!-- Problem Analysis -->
                        <h2 class="mb-4"><i class="fas fa-search text-primary me-2"></i>Problem Analysis</h2>
                        
                        <div class="alert alert-info border-0 shadow-sm mb-5">
                            <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Common Subject Creation Issues</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Database Issues:</h6>
                                    <ul class="mb-3">
                                        <li>Field name mismatches (name vs subject_name)</li>
                                        <li>Missing required columns</li>
                                        <li>Unique constraint violations</li>
                                        <li>Foreign key constraint errors</li>
                                        <li>NULL value errors for required fields</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>Application Issues:</h6>
                                    <ul class="mb-3">
                                        <li>Model fillable array problems</li>
                                        <li>Controller validation errors</li>
                                        <li>Form field mapping issues</li>
                                        <li>Route configuration problems</li>
                                        <li>Middleware authentication issues</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Step-by-Step Solution -->
                        <h2 class="mb-4"><i class="fas fa-tools text-success me-2"></i>Step-by-Step Solution</h2>
                        
                        <div class="row g-4 mb-5">
                            <!-- Step 1 -->
                            <div class="col-md-6">
                                <div class="card solution-card border-primary h-100">
                                    <div class="card-header bg-primary text-white">
                                        <div class="d-flex align-items-center">
                                            <div class="step-number bg-white text-primary me-3">1</div>
                                            <h6 class="mb-0">Check Database Structure</h6>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Run this command to check your database:</strong></p>
                                        <div class="bg-light p-3 rounded">
                                            <code>php artisan tinker</code><br>
                                            <code>Schema::getColumnListing('subjects')</code>
                                        </div>
                                        <p class="mt-3"><strong>Expected columns:</strong></p>
                                        <ul class="small">
                                            <li>id, name, code, grade_level, units</li>
                                            <li>teacher_id, description, track, strand</li>
                                            <li>created_at, updated_at</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="col-md-6">
                                <div class="card solution-card border-success h-100">
                                    <div class="card-header bg-success text-white">
                                        <div class="d-flex align-items-center">
                                            <div class="step-number bg-white text-success me-3">2</div>
                                            <h6 class="mb-0">Fix Model Configuration</h6>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Update Subject model fillable array:</strong></p>
                                        <div class="bg-light p-3 rounded">
                                            <code>protected $fillable = [<br>
                                            &nbsp;&nbsp;'name', 'code', 'grade_level',<br>
                                            &nbsp;&nbsp;'units', 'teacher_id', 'description',<br>
                                            &nbsp;&nbsp;'track', 'strand'<br>
                                            ];</code>
                                        </div>
                                        <p class="mt-3"><strong>Remove any complex mutators/accessors</strong></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="col-md-6">
                                <div class="card solution-card border-warning h-100">
                                    <div class="card-header bg-warning text-dark">
                                        <div class="d-flex align-items-center">
                                            <div class="step-number bg-white text-warning me-3">3</div>
                                            <h6 class="mb-0">Verify Controller Validation</h6>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Check validation rules match database:</strong></p>
                                        <div class="bg-light p-3 rounded">
                                            <code>'name' => 'required|string|max:255',<br>
                                            'code' => 'required|string|max:50|unique:subjects',<br>
                                            'grade_level' => 'required|string',<br>
                                            'units' => 'required|integer|min:1|max:10'</code>
                                        </div>
                                        <p class="mt-3"><strong>Ensure field names match form inputs</strong></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="col-md-6">
                                <div class="card solution-card border-info h-100">
                                    <div class="card-header bg-info text-white">
                                        <div class="d-flex align-items-center">
                                            <div class="step-number bg-white text-info me-3">4</div>
                                            <h6 class="mb-0">Test Subject Creation</h6>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Test with minimal data:</strong></p>
                                        <div class="bg-light p-3 rounded">
                                            <code>Subject::create([<br>
                                            &nbsp;&nbsp;'name' => 'Test Subject',<br>
                                            &nbsp;&nbsp;'code' => 'TEST123',<br>
                                            &nbsp;&nbsp;'grade_level' => 'Grade 11',<br>
                                            &nbsp;&nbsp;'units' => 3<br>
                                            ]);</code>
                                        </div>
                                        <p class="mt-3"><strong>Check for any error messages</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Fixes -->
                        <h2 class="mb-4"><i class="fas fa-lightning-bolt text-warning me-2"></i>Quick Fixes</h2>
                        
                        <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <div class="card solution-card border-danger">
                                    <div class="card-header bg-danger text-white">
                                        <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Field Name Errors</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>If you get "Field 'name' doesn't have a default value":</strong></p>
                                        <ol class="small">
                                            <li>Check if database has 'name' column</li>
                                            <li>If not, add it or use 'subject_name'</li>
                                            <li>Update model fillable array</li>
                                            <li>Update form field names</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card solution-card border-warning">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0"><i class="fas fa-key me-2"></i>Unique Constraint Errors</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>If you get "Duplicate entry" errors:</strong></p>
                                        <ol class="small">
                                            <li>Check if subject code already exists</li>
                                            <li>Use unique subject codes</li>
                                            <li>Clear test data if needed</li>
                                            <li>Add random numbers to codes</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card solution-card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="fas fa-link me-2"></i>Foreign Key Errors</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>If you get teacher_id constraint errors:</strong></p>
                                        <ol class="small">
                                            <li>Make teacher_id nullable</li>
                                            <li>Check if teacher exists</li>
                                            <li>Use valid teacher IDs only</li>
                                            <li>Leave teacher_id empty if optional</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Manual Test Form -->
                        <h2 class="mb-4"><i class="fas fa-vial text-primary me-2"></i>Test Subject Creation</h2>
                        
                        <div class="card solution-card border-primary mb-5">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-test-tube me-2"></i>Manual Test Form</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.subjects.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="test_name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                       id="test_name" name="name" value="{{ old('name', 'Test Subject ' . rand(1, 999)) }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="test_code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                                       id="test_code" name="code" value="{{ old('code', 'TEST' . rand(100, 999)) }}" required>
                                                @error('code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="test_grade_level" class="form-label">Grade Level <span class="text-danger">*</span></label>
                                                <select class="form-select @error('grade_level') is-invalid @enderror" id="test_grade_level" name="grade_level" required>
                                                    <option value="">Select Grade Level</option>
                                                    <option value="Grade 11" {{ old('grade_level') === 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                                    <option value="Grade 12" {{ old('grade_level') === 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                                                </select>
                                                @error('grade_level')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="test_units" class="form-label">Units <span class="text-danger">*</span></label>
                                                <select class="form-select @error('units') is-invalid @enderror" id="test_units" name="units" required>
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
                                    </div>
                                    <div class="mb-3">
                                        <label for="test_description" class="form-label">Description</label>
                                        <textarea class="form-control" id="test_description" name="description" rows="2" placeholder="Optional description">{{ old('description', 'Test subject for troubleshooting') }}</textarea>
                                    </div>
                                    
                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Create Test Subject
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="generateRandomData()">
                                            <i class="fas fa-random me-2"></i>Generate Random Data
                                        </button>
                                    </div>
                                </form>
                                
                                @if($errors->any())
                                    <div class="alert alert-danger mt-4">
                                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Errors Found:</h6>
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
                            </div>
                        </div>

                        <!-- Troubleshooting Commands -->
                        <h2 class="mb-4"><i class="fas fa-terminal text-secondary me-2"></i>Troubleshooting Commands</h2>
                        
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="card solution-card border-secondary">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0"><i class="fas fa-code me-2"></i>Laravel Commands</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="bg-light p-3 rounded">
                                            <code># Clear cache<br>
                                            php artisan cache:clear<br><br>
                                            # Run migrations<br>
                                            php artisan migrate<br><br>
                                            # Check routes<br>
                                            php artisan route:list | grep subject<br><br>
                                            # Test in tinker<br>
                                            php artisan tinker</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card solution-card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="fas fa-database me-2"></i>Database Commands</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="bg-light p-3 rounded">
                                            <code># Check table structure<br>
                                            DESCRIBE subjects;<br><br>
                                            # View existing subjects<br>
                                            SELECT * FROM subjects;<br><br>
                                            # Check constraints<br>
                                            SHOW CREATE TABLE subjects;<br><br>
                                            # Clear test data<br>
                                            DELETE FROM subjects WHERE name LIKE 'Test%';</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <h2 class="mb-4"><i class="fas fa-link text-primary me-2"></i>Quick Navigation</h2>
                        
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
                                <a href="{{ route('admin.subjects') }}" class="btn btn-info btn-lg w-100">
                                    <i class="fas fa-list me-2"></i>Subjects List
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="/admin-subject-creation-diagnostic" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="fas fa-stethoscope me-2"></i>Diagnostic Tool
                                </a>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="text-center mt-5">
                            <h3 class="text-success mb-3">🎯 Subject Creation Issues - Complete Solution Guide</h3>
                            <p class="text-muted mb-4">Follow the steps above to identify and resolve any subject creation issues in your system.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function generateRandomData() {
            const subjects = ['Mathematics', 'English', 'Science', 'History', 'Physics', 'Chemistry', 'Biology', 'Literature'];
            const randomSubject = subjects[Math.floor(Math.random() * subjects.length)];
            const randomNumber = Math.floor(Math.random() * 1000);
            
            document.getElementById('test_name').value = randomSubject + ' ' + randomNumber;
            document.getElementById('test_code').value = randomSubject.substring(0, 4).toUpperCase() + randomNumber;
            document.getElementById('test_grade_level').value = Math.random() > 0.5 ? 'Grade 11' : 'Grade 12';
            document.getElementById('test_units').value = Math.floor(Math.random() * 4) + 1;
            document.getElementById('test_description').value = 'Test subject: ' + randomSubject + ' for troubleshooting purposes.';
        }
    </script>
</body>
</html>
