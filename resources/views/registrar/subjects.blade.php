@extends('layouts.registrar')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-gray-800">
                        Subject Management
                        <span class="badge badge-success ms-2">Exclusive Access</span>
                    </h1>
                    <p class="text-muted">Create, edit, and manage academic subjects with DepEd curriculum structure</p>
                </div>
                <div>
                    <a href="{{ route('registrar.subjects.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create New Subject
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Information Notice -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success border-0" style="background: linear-gradient(135deg, #d4edda, #c3e6cb);">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-user-check fa-2x text-success"></i>
                    </div>
                    <div>
                        <h6 class="alert-heading mb-1">
                            <i class="fas fa-cogs me-2"></i>Registrar Exclusive Management
                        </h6>
                        <p class="mb-0">
                            You have exclusive access to create, edit, and delete subjects. Use structured forms with
                            <strong>Grade Level (11-12)</strong>, <strong>Track</strong>, <strong>Strand</strong>,
                            <strong>Cluster</strong>, and <strong>Specialization</strong> aligned with DepEd curriculum.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Subjects</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSubjectsCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                @if($gradeFilter && $gradeFilter !== 'all')
                                    Grade {{ $gradeFilter }} Subjects
                                @else
                                    Filtered Results
                                @endif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $filteredSubjectsCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-filter fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Subjects Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>All Subjects in System</h5>
                    <span class="badge badge-light">
                        @if($gradeFilter && $gradeFilter !== 'all')
                            {{ $filteredSubjectsCount }} of {{ $totalSubjectsCount }} subjects
                        @else
                            {{ $totalSubjectsCount }} subjects
                        @endif
                    </span>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Advanced Filters -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <form method="GET" action="{{ route('registrar.subjects.index') }}" id="subjectFiltersForm">
                                <div class="row">
                                    <!-- Grade Level Filter -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="grade_level" class="form-label font-weight-bold">
                                                <i class="fas fa-graduation-cap me-2"></i>Grade Level
                                            </label>
                                            <select name="grade_level" id="grade_level" class="form-control" onchange="updateFilters()">
                                                <option value="all" {{ (!$gradeFilter || $gradeFilter === 'all') ? 'selected' : '' }}>
                                                    All Grades
                                                </option>
                                                <option value="11" {{ $gradeFilter === '11' ? 'selected' : '' }}>
                                                    Grade 11
                                                </option>
                                                <option value="12" {{ $gradeFilter === '12' ? 'selected' : '' }}>
                                                    Grade 12
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Track Filter -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="track" class="form-label font-weight-bold">
                                                <i class="fas fa-route me-2"></i>Track
                                            </label>
                                            <select name="track" id="track" class="form-control">
                                                <option value="all">All Tracks</option>
                                                @foreach($availableTracks as $track)
                                                    <option value="{{ $track }}" {{ $trackFilter === $track ? 'selected' : '' }}>
                                                        {{ $track }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Strand Filter -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="strand" class="form-label font-weight-bold">
                                                <i class="fas fa-sitemap me-2"></i>Strand
                                            </label>
                                            <select name="strand" id="strand" class="form-control">
                                                <option value="all">All Strands</option>
                                                @foreach($availableStrands as $strand)
                                                    <option value="{{ $strand }}" {{ $strandFilter === $strand ? 'selected' : '' }}>
                                                        {{ $strand }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Filter Actions -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="filter-actions">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-search me-1"></i>Apply Filters
                                                </button>
                                                <a href="{{ route('registrar.subjects.index') }}" class="btn btn-outline-secondary btn-sm ms-2">
                                                    <i class="fas fa-times me-1"></i>Clear All
                                                </a>
                                            </div>
                                            <div class="filter-info">
                                                @if($gradeFilter || $trackFilter || $strandFilter)
                                                    <small class="text-muted">
                                                        <i class="fas fa-filter me-1"></i>
                                                        Filters active:
                                                        @if($gradeFilter && $gradeFilter !== 'all')
                                                            <span class="badge badge-info">Grade {{ $gradeFilter }}</span>
                                                        @endif
                                                        @if($trackFilter && $trackFilter !== 'all')
                                                            <span class="badge badge-success">{{ $trackFilter }}</span>
                                                        @endif
                                                        @if($strandFilter && $strandFilter !== 'all')
                                                            <span class="badge badge-warning">{{ $strandFilter }}</span>
                                                        @endif
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Dynamic Subject Display Section -->
                    <div id="dynamicSubjectsSection" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h5><i class="fas fa-filter me-2"></i>Filtered Subjects</h5>
                                    <p class="mb-0">Showing subjects for: <span id="filterSummary"></span></p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="fas fa-list me-2"></i>Available Subjects (<span id="subjectCount">0</span>)</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="subjectsList" class="row">
                                            <!-- Dynamic subjects will be loaded here -->
                                        </div>

                                        <!-- File Upload Section -->
                                        <div id="fileUploadSection" style="display: none;" class="mt-4">
                                            <hr>
                                            <h6><i class="fas fa-upload me-2"></i>Upload Learning Materials</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="selectedSubject">Select Subject:</label>
                                                        <select id="selectedSubject" class="form-control">
                                                            <option value="">Choose a subject...</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="materialFile">Upload File:</label>
                                                        <input type="file" id="materialFile" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx">
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" id="uploadMaterialBtn" class="btn btn-success">
                                                <i class="fas fa-upload me-2"></i>Upload Material
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($allSubjects->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="allSubjectsTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Grade Level</th>
                                        <th>Track</th>
                                        <th>Strand</th>
                                        <th>Cluster</th>
                                        <th>Grading</th>
                                        <th>Semester</th>
                                        <th>Teacher</th>
                                        <th>Type</th>
                                        <th>Created By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allSubjects as $subject)
                                        <tr class="{{ $subject->registrar_id == auth()->guard('registrar')->id() ? 'table-primary' : '' }}">
                                            <td><strong>{{ $subject->code ?? $subject->subject_code }}</strong></td>
                                            <td>{{ $subject->name ?? $subject->subject_name }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ str_replace('Grade ', '', $subject->grade_level) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">{{ $subject->track ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">{{ $subject->strand ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $subject->cluster ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $subject->grading ?? 'All Gradings' }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $subject->semester ?? 'N/A' }}</small>
                                            </td>
                                            <td>{{ $subject->teacher->name ?? 'Not Assigned' }}</td>
                                            <td>
                                                @if($subject->is_core_subject)
                                                    <span class="badge badge-danger">Core</span>
                                                @endif
                                                @if($subject->is_master_subject)
                                                    <span class="badge badge-warning">Master</span>
                                                @endif
                                                @if(!$subject->is_core_subject && !$subject->is_master_subject)
                                                    <span class="badge badge-light">Regular</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($subject->registrar_id == auth()->guard('registrar')->id())
                                                    <span class="badge badge-primary">You</span>
                                                @elseif($subject->registrar)
                                                    <span class="badge badge-secondary">{{ $subject->registrar->full_name }}</span>
                                                @else
                                                    <span class="badge badge-warning">Admin</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('registrar.subjects.show', $subject) }}"
                                                       class="btn btn-sm btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($subject->registrar_id == auth()->guard('registrar')->id())
                                                        <a href="{{ route('registrar.subjects.edit', $subject) }}"
                                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('registrar.subjects.destroy', $subject) }}"
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this subject?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted small">View Only</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-list fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-600">No Subjects in System</h5>
                            <p class="text-gray-500">There are no subjects in the system yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.table-primary {
    background-color: rgba(78, 115, 223, 0.1);
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.btn-group .btn {
    margin-right: 2px;
}

.card {
    border-radius: 0.35rem;
}

.alert {
    border-radius: 0.35rem;
}

.table th {
    background-color: #f8f9fc;
    border-color: #e3e6f0;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    border-color: #e3e6f0;
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.075);
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables for subjects table
    $('#allSubjectsTable').DataTable({
        "pageLength": 15,
        "order": [[ 2, "asc" ], [ 3, "asc" ], [ 4, "asc" ], [ 1, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [11] }, // Actions column
            { "width": "8%", "targets": [0] }, // Subject Code
            { "width": "15%", "targets": [1] }, // Subject Name
            { "width": "8%", "targets": [2] }, // Grade Level
            { "width": "12%", "targets": [3] }, // Track
            { "width": "10%", "targets": [4] }, // Strand
            { "width": "10%", "targets": [5] }, // Cluster
            { "width": "8%", "targets": [6] }, // Units
            { "width": "10%", "targets": [7] }, // Semester
            { "width": "12%", "targets": [8] }, // Teacher
            { "width": "8%", "targets": [9] }, // Type
            { "width": "10%", "targets": [10] }, // Created By
        ],
        "language": {
            "search": "Search subjects:",
            "lengthMenu": "Show _MENU_ subjects per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ subjects",
            "infoEmpty": "No subjects found",
            "infoFiltered": "(filtered from _MAX_ total subjects)"
        },
        "responsive": true
    });

    // Dynamic filtering functionality
    const gradeSelect = $('#grade_level');
    const trackSelect = $('#track');
    const strandSelect = $('#strand');
    const dynamicSection = $('#dynamicSubjectsSection');
    const subjectsList = $('#subjectsList');
    const subjectCount = $('#subjectCount');
    const filterSummary = $('#filterSummary');
    const fileUploadSection = $('#fileUploadSection');
    const selectedSubjectSelect = $('#selectedSubject');

    // Grade level change handler
    gradeSelect.on('change', function() {
        const selectedGrade = $(this).val();

        // Reset dependent dropdowns
        trackSelect.html('<option value="all">All Tracks</option>');
        strandSelect.html('<option value="all">All Strands</option>');

        if (selectedGrade && selectedGrade !== 'all') {
            // Load tracks for selected grade
            loadTracks(selectedGrade);
        }

        // Hide dynamic section when grade changes
        dynamicSection.hide();
        fileUploadSection.hide();
    });

    // Track change handler
    trackSelect.on('change', function() {
        const selectedGrade = gradeSelect.val();
        const selectedTrack = $(this).val();

        // Reset strand dropdown
        strandSelect.html('<option value="all">All Strands</option>');

        if (selectedGrade && selectedGrade !== 'all' && selectedTrack && selectedTrack !== 'all') {
            // Load strands for selected grade and track
            loadStrands(selectedGrade, selectedTrack);
        }

        // Hide dynamic section when track changes
        dynamicSection.hide();
        fileUploadSection.hide();
    });

    // Strand change handler
    strandSelect.on('change', function() {
        const selectedGrade = gradeSelect.val();
        const selectedTrack = trackSelect.val();
        const selectedStrand = $(this).val();

        if (selectedGrade && selectedGrade !== 'all' &&
            selectedTrack && selectedTrack !== 'all' &&
            selectedStrand && selectedStrand !== 'all') {
            // Load subjects for selected combination
            loadSubjects(selectedGrade, selectedTrack, selectedStrand);
        } else {
            dynamicSection.hide();
            fileUploadSection.hide();
        }
    });

    // Load tracks based on grade level
    function loadTracks(gradeLevel) {
        $.ajax({
            url: '{{ route("registrar.api.tracks-by-grade") }}',
            method: 'GET',
            data: { grade_level: gradeLevel },
            success: function(tracks) {
                trackSelect.html('<option value="all">All Tracks</option>');
                tracks.forEach(function(track) {
                    trackSelect.append(`<option value="${track}">${track}</option>`);
                });
            },
            error: function() {
                console.error('Failed to load tracks');
            }
        });
    }

    // Load strands based on grade level and track
    function loadStrands(gradeLevel, track) {
        $.ajax({
            url: '{{ route("registrar.api.strands-by-grade-track") }}',
            method: 'GET',
            data: {
                grade_level: gradeLevel,
                track: track
            },
            success: function(strands) {
                strandSelect.html('<option value="all">All Strands</option>');
                strands.forEach(function(strand) {
                    strandSelect.append(`<option value="${strand}">${strand}</option>`);
                });
            },
            error: function() {
                console.error('Failed to load strands');
            }
        });
    }

    // Load subjects based on filters
    function loadSubjects(gradeLevel, track, strand) {
        showLoadingOverlay();

        $.ajax({
            url: '{{ route("registrar.api.subjects-by-filters") }}',
            method: 'GET',
            data: {
                grade_level: gradeLevel,
                track: track,
                strand: strand
            },
            success: function(response) {
                hideLoadingOverlay();
                displaySubjects(response.subjects, gradeLevel, track, strand);
                subjectCount.text(response.count);

                // Update filter summary
                filterSummary.text(`Grade ${gradeLevel} - ${track} - ${strand}`);

                // Show dynamic section
                dynamicSection.show();

                // Show file upload section if subjects exist
                if (response.count > 0) {
                    populateSubjectSelect(response.subjects);
                    fileUploadSection.show();
                }
            },
            error: function() {
                hideLoadingOverlay();
                console.error('Failed to load subjects');
            }
        });
    }

    // Display subjects in cards
    function displaySubjects(subjects, gradeLevel, track, strand) {
        subjectsList.empty();

        if (subjects.length === 0) {
            subjectsList.html(`
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <h5>No Subjects Found</h5>
                        <p>No subjects found for Grade ${gradeLevel} - ${track} - ${strand}</p>
                        <a href="{{ route('registrar.subjects.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create New Subject
                        </a>
                    </div>
                </div>
            `);
            return;
        }

        subjects.forEach(function(subject) {
            const subjectCard = `
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 border-primary">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <strong>${subject.code || subject.subject_code}</strong>
                            </h6>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">${subject.name || subject.subject_name}</h6>
                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>
                                    ${subject.teacher ? subject.teacher.name : 'Not Assigned'}
                                </small>
                            </p>
                            <div class="mb-2">
                                ${subject.is_core_subject ? '<span class="badge badge-danger">Core</span>' : ''}
                                ${subject.is_master_subject ? '<span class="badge badge-warning">Master</span>' : ''}
                                ${!subject.is_core_subject && !subject.is_master_subject ? '<span class="badge badge-light">Regular</span>' : ''}
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-group w-100" role="group">
                                <a href="/registrar/subjects/${subject.id}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                ${subject.registrar_id == {{ auth()->guard('registrar')->id() }} ? `
                                    <a href="/registrar/subjects/${subject.id}/edit" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            subjectsList.append(subjectCard);
        });
    }

    // Populate subject select dropdown for file upload
    function populateSubjectSelect(subjects) {
        selectedSubjectSelect.html('<option value="">Choose a subject...</option>');
        subjects.forEach(function(subject) {
            selectedSubjectSelect.append(`
                <option value="${subject.id}">
                    ${subject.code || subject.subject_code} - ${subject.name || subject.subject_name}
                </option>
            `);
        });
    }

    // Filter update function (for manual apply button)
    window.updateFilters = function() {
        const form = $('#subjectFiltersForm');
        showLoadingOverlay();
        form.submit();
    };

    // Show/Hide loading overlay functions
    function showLoadingOverlay() {
        if ($('.loading-overlay').length === 0) {
            $('body').append('<div class="loading-overlay"><div class="spinner-border text-success" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }
    }

    function hideLoadingOverlay() {
        $('.loading-overlay').remove();
    }

    // File upload handler (placeholder)
    $('#uploadMaterialBtn').on('click', function() {
        const selectedSubject = selectedSubjectSelect.val();
        const fileInput = $('#materialFile')[0];

        if (!selectedSubject) {
            alert('Please select a subject first.');
            return;
        }

        if (!fileInput.files.length) {
            alert('Please select a file to upload.');
            return;
        }

        // TODO: Implement actual file upload functionality
        alert('File upload functionality will be implemented in the next phase.');
    });

    // Add smooth animations for stat cards
    $('.card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
    });
});
</script>

<style>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

/* Filter styling */
.filter-actions .btn {
    margin-right: 5px;
}

.filter-info .badge {
    margin-left: 3px;
}

.form-group label {
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.form-control {
    border-radius: 6px;
    border: 1px solid #d1d3e2;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
}

/* Enhanced filter section */
#subjectFiltersForm {
    background: #f8f9fc;
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #e3e6f0;
    margin-bottom: 20px;
}

.filter-actions {
    margin-top: 10px;
}

.filter-info {
    margin-top: 10px;
}

/* Dynamic subjects section styling */
#dynamicSubjectsSection {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.card.border-primary {
    border-width: 2px !important;
}

.card.h-100 {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card.h-100:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

#fileUploadSection {
    background: #f8f9fc;
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #e3e6f0;
}

#subjectsList .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
}

#subjectsList .card-header h6 {
    color: white !important;
}

.alert-info {
    background: linear-gradient(135deg, #d1ecf1, #bee5eb);
    border-color: #b6d4da;
}

/* Loading states */
.form-control:disabled {
    background-color: #f8f9fa;
    opacity: 0.7;
}

/* Subject card animations */
#subjectsList .card {
    animation: slideInUp 0.3s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* File upload styling */
#materialFile {
    border: 2px dashed #dee2e6;
    padding: 10px;
    border-radius: 8px;
    transition: border-color 0.3s ease;
}

#materialFile:hover {
    border-color: #007bff;
}

#uploadMaterialBtn {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

#uploadMaterialBtn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}
</style>
@endsection