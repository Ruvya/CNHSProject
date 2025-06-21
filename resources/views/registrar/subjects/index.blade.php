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
                    <div class="stat-card-icon"><i class="fas fa-book"></i></div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Total Subjects</div>
                        <div class="stat-card-value">{{ $totalSubjectsCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-success">
                <div class="stat-card-body">
                    <div class="stat-card-icon"><i class="fas fa-user-check"></i></div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Your Subjects</div>
                        <div class="stat-card-value">{{ $yourSubjectsCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-body">
                    <div class="stat-card-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">With Teachers</div>
                        <div class="stat-card-value">{{ $withTeachersCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-info">
                <div class="stat-card-body">
                    <div class="stat-card-icon"><i class="fas fa-layer-group"></i></div>
                    <div class="stat-card-content">
                        <div class="stat-card-title">Grade Levels</div>
                        <div class="stat-card-value">{{ $gradeLevelsCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
            <form action="{{ route('registrar.subjects.index') }}" method="GET" class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or code..." value="{{ request('search') }}">
                    <select name="grade_level" class="form-select" style="width: auto;">
                        <option value="">All Grades</option>
                        <option value="11" {{ request('grade_level') == '11' ? 'selected' : '' }}>Grade 11</option>
                        <option value="12" {{ request('grade_level') == '12' ? 'selected' : '' }}>Grade 12</option>
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('registrar.subjects.index') }}" class="btn btn-secondary"><i class="fas fa-sync"></i> Reset</a>
                </div>
                <h5 class="mb-0 text-muted">
                    <i class="fas fa-list me-2"></i>
                    Displaying {{ $subjects->firstItem() }}-{{ $subjects->lastItem() }} of {{ $subjects->total() }} subjects
                </h5>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Grade Level</th>
                            <th>Track</th>
                            <th>Strand</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td>{{ $subject->name }}</td>
                                <td>{{ $subject->code }}</td>
                                <td>{{ $subject->grade_level }}</td>
                                <td>{{ $subject->track ?? 'N/A' }}</td>
                                <td>{{ $subject->strand ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('registrar.subjects.edit', $subject) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('registrar.subjects.destroy', $subject) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                    <p class="mb-0">No subjects found matching your criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $subjects->appends(request()->query())->links() }}
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
