@extends('layouts.registrar')

@section('title', 'Upload Student Data')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Upload Student Data</h1>
            <p class="text-muted">Import student records from Excel file</p>
        </div>
        <div>
            <a href="{{ route('registrar.students.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Students
            </a>
        </div>
    </div>

    <!-- Import Summary -->
    @if(session('import_summary'))
        @php $summary = session('import_summary'); @endphp
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Import Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-success">{{ $summary['success_count'] }}</h4>
                            <small class="text-muted">New Students</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-info">{{ $summary['update_count'] }}</h4>
                            <small class="text-muted">Updated Students</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-warning">{{ $summary['skip_count'] }}</h4>
                            <small class="text-muted">Skipped Records</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-danger">{{ $summary['error_count'] }}</h4>
                            <small class="text-muted">Errors</small>
                        </div>
                    </div>
                </div>

                @if(!empty($summary['errors']))
                    <div class="mt-3">
                        <h6>Errors:</h6>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($summary['errors'] as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Upload Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Upload Excel File</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('registrar.students.upload.process') }}" method="POST" enctype="multipart/form-data" id="uploadForm">

                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="excel_file" class="form-label">Select Excel File</label>
                            <input type="file"
                                   class="form-control @error('excel_file') is-invalid @enderror"
                                   id="excel_file"
                                   name="excel_file"
                                   accept=".xlsx,.xls,.csv"
                                   required>
                            @error('excel_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <strong>Supported formats:</strong> Excel (.xlsx, .xls) and CSV (.csv) files. Maximum file size: 20MB.
                                <br><small class="text-muted">Large files may take several minutes to process. Please be patient.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="grade_level" class="form-label">Grade Level <span class="text-danger">*</span></label>
                            <select class="form-control @error('grade_level') is-invalid @enderror"
                                    id="grade_level"
                                    name="grade_level"
                                    required>
                                <option value="">Select Grade Level</option>
                                <option value="Grade 11">Grade 11</option>
                                <option value="Grade 12">Grade 12</option>
                            </select>
                            @error('grade_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="track" class="form-label">Track <span class="text-danger">*</span></label>
                            <select class="form-control @error('track') is-invalid @enderror"
                                    id="track"
                                    name="track"
                                    required>
                                <option value="">Select Track</option>
                                <option value="Academic Track">Academic Track</option>
                                <option value="TVL Track">TVL Track</option>
                                <option value="Sports Track">Sports Track</option>
                                <option value="Arts and Design Track">Arts and Design Track</option>
                            </select>
                            @error('track')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" id="uploadBtn">
                                    <i class="fas fa-upload me-2"></i>Upload & Import
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Progress Bar -->
            <div id="uploadProgress" class="mt-3" style="display: none;">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                         role="progressbar"
                         style="width: 0%">
                        <span class="progress-text">Uploading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Instructions</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>File Requirements:</h6>
                    <ul>
                        <li>Supported formats: Excel (.xlsx, .xls) and CSV (.csv)</li>
                        <li>First row must contain column headers</li>
                        <li>Required column: <code>student_id</code> or <code>LRN</code></li>
                        <li>SF1-SHS format supported with all 27 columns</li>
                        <li>All profile fields will be updated from Excel data</li>
                        <li>Maximum file size: 20MB</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>Import Process & Profile Updates:</h6>
                    <ul>
                        <li><strong>Step 1:</strong> Select Grade Level and Track for all students in the file</li>
                        <li><strong>Step 2:</strong> Choose your Excel/CSV file</li>
                        <li><strong>Step 3:</strong> Click "Upload & Import" to process</li>
                        <li><strong>Student Matching:</strong> Students are matched by <code>student_id</code> or <code>LRN</code></li>
                        <li><strong>Profile Updates:</strong> ALL fields from Excel will update existing student profiles</li>
                        <li><strong>New Students:</strong> Students not found will be created automatically</li>
                        <li><strong>Academic Changes:</strong> Track/cluster changes trigger automatic subject reassignment</li>
                        <li><strong>Profile Lock:</strong> Students cannot edit their profiles after registrar upload</li>
                        <li><strong>Data Tracking:</strong> System logs all changes and update timestamps</li>
                        <li><strong>Date Format:</strong> MM/DD/YYYY (e.g., 05/15/2006) or YYYY-MM-DD</li>
                    </ul>
                </div>
            </div>

            <div class="mt-3">
                <div class="btn-group" role="group">
                    <a href="{{ route('registrar.students.template') }}" class="btn btn-outline-success">
                        <i class="fas fa-download me-2"></i>Download SF1-SHS CSV Template
                    </a>
                    <a href="{{ route('registrar.students.template', ['format' => 'excel']) }}" class="btn btn-outline-primary">
                        <i class="fas fa-file-excel me-2"></i>Download SF1-SHS Excel Template
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const uploadBtn = document.getElementById('uploadBtn');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = uploadProgress.querySelector('.progress-bar');
    const progressText = uploadProgress.querySelector('.progress-text');
    const fileInput = document.getElementById('excel_file');
    const file = fileInput.files[0];

    if (!file) {
        alert('Please select a file to upload.');
        e.preventDefault();
        return;
    }

    // Show progress bar
    uploadProgress.style.display = 'block';
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

    // Show file size info
    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
    progressText.textContent = `Processing "${file.name}" (${fileSizeMB} MB)...`;

    // Simulate progress with more realistic timing for large files
    let progress = 0;
    const estimatedTime = Math.max(5000, file.size / 1000); // Estimate based on file size
    const progressIncrement = 90 / (estimatedTime / 1000); // Progress per second

    const interval = setInterval(() => {
        progress += progressIncrement;
        if (progress > 90) progress = 90;

        progressBar.style.width = progress + '%';
        progressText.textContent = `Processing "${file.name}" (${fileSizeMB} MB)... ${Math.round(progress)}%`;
    }, 1000);

    // Set a timeout for very large files
    const timeoutDuration = Math.max(300000, file.size / 100); // At least 5 minutes, more for larger files
    const timeoutId = setTimeout(() => {
        clearInterval(interval);
        progressText.textContent = 'Upload is taking longer than expected. Please wait...';
        progressBar.classList.add('progress-bar-striped', 'progress-bar-animated');
    }, timeoutDuration);

    // Clean up intervals when page unloads or form completes
    window.addEventListener('beforeunload', () => {
        clearInterval(interval);
        clearTimeout(timeoutId);
    });
});

// File validation
document.getElementById('excel_file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                             'application/vnd.ms-excel',
                             'text/csv'];
        const allowedExtensions = ['.xlsx', '.xls', '.csv'];
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();

        // Check file type and extension
        if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
            alert('Please select a valid Excel (.xlsx, .xls) or CSV (.csv) file.');
            e.target.value = '';
            return;
        }

        // Increased file size limit to 20MB
        if (file.size > 20 * 1024 * 1024) { // 20MB
            alert('File size must be less than 20MB. For larger files, please split them into smaller chunks.');
            e.target.value = '';
            return;
        }

        // Show file info
        const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
        const fileInfo = document.createElement('div');
        fileInfo.className = 'mt-2 text-info';
        fileInfo.innerHTML = `<i class="fas fa-info-circle"></i> Selected: ${file.name} (${fileSizeMB} MB)`;

        // Remove any existing file info
        const existingInfo = e.target.parentNode.querySelector('.text-info');
        if (existingInfo) {
            existingInfo.remove();
        }

        e.target.parentNode.appendChild(fileInfo);

        // Show warning for large files
        if (file.size > 5 * 1024 * 1024) { // 5MB
            const warning = document.createElement('div');
            warning.className = 'mt-1 text-warning small';
            warning.innerHTML = `<i class="fas fa-exclamation-triangle"></i> Large file detected. Processing may take several minutes.`;
            e.target.parentNode.appendChild(warning);
        }
    }
});
</script>
@endpush
@endsection
