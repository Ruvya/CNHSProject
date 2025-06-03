@extends('layouts.registrar')

@section('content')
<div class="container-fluid">
    <!-- Page Header with Clear Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-book me-2 text-primary"></i>
                Subject Management
                <span class="badge bg-success ms-2">Exclusive Access</span>
            </h1>
            <p class="text-muted mb-0">Create, edit, and manage academic subjects with DepEd curriculum structure</p>
        </div>
        <div>
            <a href="{{ route('registrar.subjects.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>Create New Subject
            </a>
        </div>
    </div>

    <!-- Role Information Notice -->
    <div class="alert alert-success border-0 mb-4" style="background: linear-gradient(135deg, #d4edda, #c3e6cb);">
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Total Subjects</div>
                        <div class="stat-card-value">{{ $totalSubjectsCount ?? 0 }}</div>
                        <div class="stat-card-subtitle">
                            <i class="fas fa-graduation-cap me-1"></i>
                            All grade levels
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-success">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Your Subjects</div>
                        <div class="stat-card-value">{{ $allSubjects->where('registrar_id', auth()->guard('registrar')->id())->count() ?? 0 }}</div>
                        <div class="stat-card-subtitle">
                            <i class="fas fa-check me-1"></i>
                            Created by you
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">With Teachers</div>
                        <div class="stat-card-value">{{ $allSubjects->where('teacher_id', '!=', null)->count() ?? 0 }}</div>
                        <div class="stat-card-subtitle">
                            <i class="fas fa-user me-1"></i>
                            Assigned teachers
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-info">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Grade Levels</div>
                        <div class="stat-card-value">{{ $availableGrades->count() ?? 2 }}</div>
                        <div class="stat-card-subtitle">
                            <i class="fas fa-graduation-cap me-1"></i>
                            Available grades
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">
                        <i class="fas fa-list me-2 text-primary"></i>
                        All Subjects
                        <span class="badge bg-primary ms-2">{{ $allSubjects->count() ?? 0 }}</span>
                    </h5>
                    <p class="text-muted mb-0 small">Manage your subjects and view all subjects in the system</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <!-- Dynamic Filtering Controls -->
                    <div class="d-flex gap-2 align-items-center">
                        <!-- Grade Level Filter -->
                        <select id="grade_level" class="form-select form-select-sm" style="width: auto;">
                            <option value="all">All Grades</option>
                            <option value="11">Grade 11</option>
                            <option value="12">Grade 12</option>
                        </select>

                        <!-- Track Filter -->
                        <select id="track" class="form-select form-select-sm" style="width: auto;">
                            <option value="all">All Tracks</option>
                            @if($availableTracks ?? false)
                                @foreach($availableTracks as $track)
                                    <option value="{{ $track }}">{{ $track }}</option>
                                @endforeach
                            @endif
                        </select>

                        <!-- Strand Filter -->
                        <select id="strand" class="form-select form-select-sm" style="width: auto;">
                            <option value="all">All Strands</option>
                            @if($availableStrands ?? false)
                                @foreach($availableStrands as $strand)
                                    <option value="{{ $strand }}">{{ $strand }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <a href="{{ route('registrar.subjects.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Add Subject
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
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

            @if($allSubjects && $allSubjects->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="subjectsTable">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">Code</th>
                                <th>Subject Name</th>
                                <th class="text-center">Grade Level</th>
                                <th>Track</th>
                                <th>Strand</th>
                                <th>Cluster/Specialization</th>
                                <th>Teacher</th>
                                <th class="text-center">Grading Period</th>
                                <th class="text-center">Created By</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allSubjects as $subject)
                            <tr class="{{ $subject->registrar_id == auth()->guard('registrar')->id() ? 'table-primary' : '' }}">
                                <td class="text-center">
                                    <span class="badge bg-primary fs-6">{{ $subject->code ?? $subject->subject_code }}</span>
                                </td>
                                <td>
                                    <div class="subject-info">
                                        <h6 class="mb-1">{{ $subject->name ?? $subject->subject_name }}</h6>
                                        @if($subject->description)
                                            <small class="text-muted">{{ Str::limit($subject->description, 50) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $subject->grade_level }}</span>
                                </td>
                                <td>
                                    @if($subject->track)
                                        <span class="badge bg-warning text-dark">{{ $subject->track }}</span>
                                    @else
                                        <span class="text-muted small">Not Set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subject->strand)
                                        <span class="badge bg-secondary">{{ $subject->strand }}</span>
                                    @else
                                        <span class="text-muted small">Not Set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subject->cluster)
                                        <span class="badge bg-info text-white mb-1">{{ $subject->cluster }}</span>
                                    @endif
                                    @if($subject->specialization)
                                        @if($subject->cluster)<br>@endif
                                        <span class="badge bg-light text-dark">{{ $subject->specialization }}</span>
                                    @endif
                                    @if(!$subject->cluster && !$subject->specialization)
                                        <span class="text-muted small">Not specified</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subject->teacher)
                                        <div class="d-flex align-items-center">
                                            <div class="teacher-avatar">
                                                {{ substr($subject->teacher->name, 0, 1) }}
                                            </div>
                                            <div class="ms-2">
                                                <div class="fw-semibold small">{{ $subject->teacher->name }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted small">Not Assigned</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $subject->grading ?? 'Not Set' }}</span>
                                </td>
                                <td class="text-center">
                                    @if($subject->registrar_id == auth()->guard('registrar')->id())
                                        <span class="badge bg-success">You</span>
                                    @elseif($subject->registrar)
                                        <span class="badge bg-secondary">{{ $subject->registrar->full_name ?? 'Other Registrar' }}</span>
                                    @else
                                        <span class="badge bg-warning">System</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <!-- View Button - Always Available -->
                                        <a href="{{ route('registrar.subjects.show', $subject) }}"
                                           class="btn btn-outline-info btn-sm"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Edit Button - Available for all registrars -->
                                        <a href="{{ route('registrar.subjects.edit', $subject) }}"
                                           class="btn btn-outline-primary btn-sm"
                                           title="Edit Subject">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Delete Button - Available for all registrars -->
                                        <form action="{{ route('registrar.subjects.destroy', $subject) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this subject: {{ $subject->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger btn-sm"
                                                    title="Delete Subject">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Show ownership info below buttons -->
                                    <div class="mt-1">
                                        @if($subject->registrar_id == auth()->guard('registrar')->id())
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> Your Subject
                                            </small>
                                        @else
                                            <small class="text-muted">
                                                <i class="fas fa-lock"></i> View Only
                                            </small>
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
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Subjects Found</h5>
                    <p class="text-muted mb-4">Start by creating your first subject with the DepEd curriculum structure.</p>
                    <a href="{{ route('registrar.subjects.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Your First Subject
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Modern Stat Cards */
.stat-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
    overflow: hidden;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.stat-card-body {
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    min-height: 100px;
}

.stat-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.stat-card-content {
    flex: 1;
    min-width: 0;
}

.stat-card-title {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.stat-card-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-card-subtitle {
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 500;
}

/* Card Color Variants */
.stat-card-primary .stat-card-icon {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
}

.stat-card-success .stat-card-icon {
    background: linear-gradient(135deg, #10b981, #059669);
}

.stat-card-warning .stat-card-icon {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.stat-card-info .stat-card-icon {
    background: linear-gradient(135deg, #06b6d4, #0891b2);
}

/* Table Improvements */
.table th {
    background-color: #343a40;
    color: white;
    font-weight: 600;
    border: none;
    font-size: 0.875rem;
    padding: 1rem 0.75rem;
}

.table-primary {
    background-color: rgba(13, 110, 253, 0.1);
}

.subject-info h6 {
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.teacher-avatar {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.75rem;
    flex-shrink: 0;
}

.btn-group .btn {
    margin-right: 2px;
}

.page-title {
    color: #1e293b;
    font-weight: 700;
}

/* Custom badge colors */
.bg-purple {
    background-color: #6f42c1 !important;
    color: white !important;
}

/* Responsive table improvements */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
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

/* Loading overlay */
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
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Document ready - jQuery loaded');
    console.log('Grade select element:', $('#grade_level').length);
    console.log('Track select element:', $('#track').length);
    console.log('Strand select element:', $('#strand').length);

    // Initialize DataTables
    $('#subjectsTable').DataTable({
        "pageLength": 15,
        "order": [[ 2, "asc" ], [ 1, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [9] }, // Actions column
            { "width": "10%", "targets": [0] }, // Code
            { "width": "20%", "targets": [1] }, // Name
            { "width": "10%", "targets": [2] }, // Grade Level
            { "width": "12%", "targets": [3] }, // Track
            { "width": "12%", "targets": [4] }, // Strand
            { "width": "12%", "targets": [5] }, // Cluster/Specialization
            { "width": "12%", "targets": [6] }, // Teacher
            { "width": "10%", "targets": [7] }, // Grading Period
            { "width": "8%", "targets": [8] }, // Created By
            { "width": "4%", "targets": [9] }  // Actions
        ],
        "language": {
            "search": "Search subjects:",
            "lengthMenu": "Show _MENU_ subjects per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ subjects",
            "emptyTable": "No subjects available"
        },
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
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
        console.log('Grade selected:', selectedGrade);

        // Reset dependent dropdowns
        trackSelect.html('<option value="all">All Tracks</option>');
        strandSelect.html('<option value="all">All Strands</option>');

        if (selectedGrade && selectedGrade !== 'all') {
            console.log('Loading tracks for grade:', selectedGrade);
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
        console.log('loadTracks called with:', gradeLevel);
        console.log('AJAX URL:', '{{ route("registrar.api.tracks-by-grade") }}');

        $.ajax({
            url: '{{ route("registrar.api.tracks-by-grade") }}',
            method: 'GET',
            data: { grade_level: gradeLevel },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(tracks) {
                console.log('Tracks received:', tracks);
                trackSelect.html('<option value="all">All Tracks</option>');
                tracks.forEach(function(track) {
                    trackSelect.append(`<option value="${track}">${track}</option>`);
                });
            },
            error: function(xhr, status, error) {
                console.error('Failed to load tracks');
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response:', xhr.responseText);
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
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
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
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
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
                                ${subject.is_core_subject ? '<span class="badge bg-danger">Core</span>' : ''}
                                ${subject.is_master_subject ? '<span class="badge bg-warning">Master</span>' : ''}
                                ${!subject.is_core_subject && !subject.is_master_subject ? '<span class="badge bg-light text-dark">Regular</span>' : ''}
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

    // Show/Hide loading overlay functions
    function showLoadingOverlay() {
        if ($('.loading-overlay').length === 0) {
            $('body').append('<div class="loading-overlay"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
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

    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add smooth animations for stat cards
    $('.stat-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
    });
});
</script>
@endpush
