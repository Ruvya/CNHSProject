@extends('layouts.teacher')

@section('title', 'Manage Grades - ' . $subject->name)

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="dashboard-header mb-4">
        <div class="header-content">
            <i class="fas fa-clipboard-check"></i>
            <div>
                <h1>Manage Grades</h1>
                <p>{{ $subject->name }}</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('teacher.subjects') }}" class="btn btn-light-blue rounded-pill">
                <i class="fas fa-arrow-left me-2"></i> Back to Subjects
            </a>
        </div>
    </div>

    <!-- Grades Management Form -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Grade Management</h6>
                    <div>
                        <button type="button" class="btn btn-info btn-sm ms-2" onclick="refreshAllGrades()" id="refreshAllBtn">
                            <i class="fas fa-sync-alt"></i> Refresh All
                        </button>
                    </div>
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

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($students->count() > 0)
                        <form id="gradesForm" action="{{ route('teacher.subjects.grades.update', $subject) }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered" id="gradesTable">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="align-middle">Student</th>
                                            <th colspan="4" class="text-center">Quarterly Grades</th>
                                            <th rowspan="2" class="align-middle">Final Grade</th>
                                            <th rowspan="2" class="align-middle">Status</th>
                                            <th rowspan="2" class="align-middle">Actions</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Q1</th>
                                            <th class="text-center">Q2</th>
                                            <th class="text-center">Q3</th>
                                            <th class="text-center">Q4</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $student)
                                            @php
                                                $grade = $student->grades->first();
                                                $finalGrade = $grade ? $grade->final_grade : null;
                                                $quarters = [];
                                                if ($grade) {
                                                    foreach (['quarter1', 'quarter2', 'quarter3', 'quarter4'] as $q) {
                                                        if (!is_null($grade->$q)) {
                                                            $quarters[] = $grade->$q;
                                                        }
                                                    }
                                                }
                                                if (count($quarters) > 0) {
                                                    $average = array_sum($quarters) / count($quarters);
                                                    $status = $average >= 75 ? 'Passed' : 'Failed';
                                                    $statusClass = $average >= 75 ? 'success' : 'danger';
                                                } else {
                                                    $status = 'Pending';
                                                    $statusClass = 'warning';
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $student->profile_picture ? asset('storage/' . $student->profile_picture) : asset('images/photo.jpg') }}"
                                                             class="rounded-circle me-2" width="32" height="32" alt="Profile">
                                                        <div>
                                                            <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                                            <br><small class="text-muted">{{ $student->student_id }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number"
                                                           class="form-control form-control-sm grade-input auto-save"
                                                           name="grades[{{ $student->id }}][quarter1]"
                                                           value="{{ $grade ? $grade->quarter1 : '' }}"
                                                           min="0" max="100" step="0.01"
                                                           data-student="{{ $student->id }}"
                                                           data-quarter="quarter1"
                                                           data-subject="{{ $subject->id }}"
                                                           placeholder="0-100">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                           class="form-control form-control-sm grade-input auto-save"
                                                           name="grades[{{ $student->id }}][quarter2]"
                                                           value="{{ $grade ? $grade->quarter2 : '' }}"
                                                           min="0" max="100" step="0.01"
                                                           data-student="{{ $student->id }}"
                                                           data-quarter="quarter2"
                                                           data-subject="{{ $subject->id }}"
                                                           placeholder="0-100">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                           class="form-control form-control-sm grade-input auto-save"
                                                           name="grades[{{ $student->id }}][quarter3]"
                                                           value="{{ $grade ? $grade->quarter3 : '' }}"
                                                           min="0" max="100" step="0.01"
                                                           data-student="{{ $student->id }}"
                                                           data-quarter="quarter3"
                                                           data-subject="{{ $subject->id }}"
                                                           placeholder="0-100">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                           class="form-control form-control-sm grade-input auto-save"
                                                           name="grades[{{ $student->id }}][quarter4]"
                                                           value="{{ $grade ? $grade->quarter4 : '' }}"
                                                           min="0" max="100" step="0.01"
                                                           data-student="{{ $student->id }}"
                                                           data-quarter="quarter4"
                                                           data-subject="{{ $subject->id }}"
                                                           placeholder="0-100">
                                                </td>
                                                <td class="text-center">
                                                    <span class="final-grade-display" data-student="{{ $student->id }}">
                                                        {{ $finalGrade ? number_format($finalGrade, 2) : '-' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="status-badge {{ $status === 'Passed' ? 'text-success' : ($status === 'Failed' ? 'text-danger' : ($status === 'Pending' ? 'text-warning' : '')) }} badge badge-{{ $statusClass }}" data-student="{{ $student->id }}">
                                                        {{ $status }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('teacher.subjects.grades.edit', [$subject, $student]) }}"
                                                       class="btn btn-sm btn-outline-primary" title="Edit Individual Grade">
                                                        Edit
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end mt-3">
                                <button type="button" class="btn btn-success btn-sm" id="saveAllBtn">
                                    <i class="fas fa-save"></i> Save All Grades
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-600">No Students Enrolled</h5>
                            <p class="text-gray-500">There are currently no students enrolled in this subject.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.dashboard-header {
    background: linear-gradient(115deg, #f97316 65%, #3b82f6 35%);
    color: white;
    padding: 1.5rem 2rem;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}
.header-content {
    display: flex;
    align-items: center;
}
.dashboard-header i {
    font-size: 2.5rem;
    margin-right: 1.5rem;
    opacity: 0.9;
}
.dashboard-header h1 {
    margin: 0;
    font-size: 2.2rem;
    font-weight: 700;
    line-height: 1.2;
}
.dashboard-header p {
    margin: 0.25rem 0 0 0;
    opacity: 0.9;
    font-size: 1rem;
}
.btn-light-blue {
    background-color: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.4);
    white-space: nowrap;
    padding: 0.4rem 1rem;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.btn-light-blue i {
    font-size: 0.7rem;
}
.btn-light-blue:hover {
    background-color: rgba(255, 255, 255, 0.3);
    color: white;
    transform: translateY(-2px);
}
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
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

.grade-input {
    width: 80px;
    text-align: center;
}

.grade-input:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.rounded-circle {
    object-fit: cover;
}

.grade-input.is-invalid {
    border-color: #e74a3b;
}

.grade-input.is-valid {
    border-color: #1cc88a;
}

.alert {
    border-radius: 0.35rem;
}

/* Auto-save grade input styles */
.grade-input.saving {
    border-color: #ffc107;
    background-color: #fff3cd;
    position: relative;
}

.grade-input.saved {
    border-color: #28a745;
    background-color: #d4edda;
    animation: savedPulse 0.5s ease-in-out;
}

.grade-input.error {
    border-color: #dc3545;
    background-color: #f8d7da;
    animation: errorShake 0.5s ease-in-out;
}

@keyframes savedPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

@keyframes errorShake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Loading indicator for saving grades */
.grade-input.saving::after {
    content: '';
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    width: 12px;
    height: 12px;
    border: 2px solid #ffc107;
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translateY(-50%) rotate(0deg); }
    100% { transform: translateY(-50%) rotate(360deg); }
}

/* Enhanced status badges */
.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Toast notification styles */
.toast-notification {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: none;
    border-radius: 0.5rem;
}

/* Improved table responsiveness */
.table-responsive {
    border-radius: 0.5rem;
    overflow: hidden;
}

/* Grade input focus enhancement */
.grade-input:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    background-color: #fff;
}

/* Student row hover effect */
#gradesTable tbody tr:hover {
    background-color: #f8f9fc;
    transition: background-color 0.2s ease;
}

/* Final grade display enhancement */
.final-grade-display {
    font-weight: 600;
    font-size: 1.1em;
    color: #2c3e50;
}

/* Improved button styles */
.btn-sm {
    padding: 0.25rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.25rem;
    transition: all 0.2s ease;
}

.btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Align DataTables search box to the left in a flex row */
.dataTables_filter {
    text-align: left !important;
    flex: 1;
    margin-bottom: 0;
}
.dataTables_filter label {
    width: 50%;
    display: flex;
    align-items: left;
    gap: 0.5rem;
    margin-bottom: 0;
}
.dataTables_filter input[type="search"] {
    margin-left: 0 !important;
    flex: 1;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let saveTimeout;

    // Auto-save functionality with debouncing
    $('.auto-save').on('input', function() {
        const $input = $(this);
        const studentId = $input.data('student');
        const quarter = $input.data('quarter');
        const subjectId = $input.data('subject');
        const grade = $input.val();

        // console.log('Input detected:', {studentId, quarter, subjectId, grade}); // Debug

        // Clear previous timeout
        clearTimeout(saveTimeout);

        // Validate input first
        validateGrade(this);

        // Calculate final grade immediately for visual feedback
        calculateFinalGrade(studentId);

        // Auto-save after 1 second of no typing
        if (grade !== '' && grade !== null) {
            saveTimeout = setTimeout(function() {
                // console.log('About to save grade:', {studentId, subjectId, quarter, grade}); // Debug
                saveQuarterGrade(studentId, subjectId, quarter, grade, $input);
            }, 1000);
        }
    });

    // Manual grade input validation and calculation
    $('.grade-input').on('input', function() {
        const studentId = $(this).data('student');
        calculateFinalGrade(studentId);
        validateGrade(this);
    });

    // Save individual quarter grade via AJAX
    function saveQuarterGrade(studentId, subjectId, quarter, grade, $input) {
        console.log('saveQuarterGrade called with:', {studentId, subjectId, quarter, grade}); // Debug

        // Show saving indicator
        $input.addClass('saving');

        const requestData = {
            _token: '{{ csrf_token() }}',
            student_id: studentId,
            subject_id: subjectId,
            quarter: quarter,
            grade: grade
        };

        console.log('Sending AJAX request:', requestData); // Debug

        $.ajax({
            url: '{{ route("teacher.save-quarter-grade") }}',
            method: 'POST',
            data: requestData,
            success: function(response) {
                console.log('AJAX Success Response:', response); // Debug

                if (response.success) {
                    $input.removeClass('saving').addClass('saved');

                    // Update final grade and status
                    $(`.final-grade-display[data-student="${studentId}"]`).text(
                        response.data.final_grade ? response.data.final_grade : '-'
                    );

                    $(`.status-badge[data-student="${studentId}"]`)
                        .removeClass('badge-success badge-danger badge-warning')
                        .addClass(`badge-${response.data.status_color}`)
                        .text(response.data.status);

                    // Show success feedback briefly
                    setTimeout(function() {
                        $input.removeClass('saved');
                    }, 2000);

                    // Show toast notification
                    showToast('success', 'Grade saved successfully!');
                } else {
                    console.log('Success response but success=false:', response); // Debug
                    $input.removeClass('saving').addClass('error');
                    showToast('error', response.message || 'Failed to save grade');
                }
            },
            error: function(xhr) {
                console.log('AJAX Error Response:', xhr); // Debug
                console.log('Status:', xhr.status); // Debug
                console.log('Response Text:', xhr.responseText); // Debug

                $input.removeClass('saving').addClass('error');
                let errorMessage = 'Failed to save grade';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join(', ');
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error occurred. Check console for details.';
                } else if (xhr.status === 404) {
                    errorMessage = 'Route not found. Check if the route exists.';
                } else if (xhr.status === 422) {
                    errorMessage = 'Validation error. Check the data being sent.';
                }

                showToast('error', errorMessage);
                console.error('Full error details:', xhr); // Debug

                // Remove error class after 3 seconds
                setTimeout(function() {
                    $input.removeClass('error');
                }, 3000);
            }
        });
    }

    // Validate grade input
    function validateGrade(input) {
        const value = parseFloat($(input).val());
        $(input).removeClass('is-invalid is-valid');

        if ($(input).val() !== '') {
            if (isNaN(value) || value < 0 || value > 100) {
                $(input).addClass('is-invalid');
                return false;
            } else {
                $(input).addClass('is-valid');
                return true;
            }
        }
        return true;
    }

    // Calculate final grade for a student
    function calculateFinalGrade(studentId) {
        const quarters = [];
        ['quarter1', 'quarter2', 'quarter3', 'quarter4'].forEach(quarter => {
            const value = parseFloat($(`input[data-student="${studentId}"][data-quarter="${quarter}"]`).val());
            if (!isNaN(value)) {
                quarters.push(value);
            }
        });

        let finalGrade = '-';
        let status = 'Incomplete';
        let statusClass = 'warning';

        if (quarters.length > 0) {
            const average = quarters.reduce((a, b) => a + b, 0) / quarters.length;
            finalGrade = average.toFixed(2);
            status = average >= 75 ? 'Passed' : 'Failed';
            statusClass = average >= 75 ? 'success' : 'danger';
        }

        $(`.final-grade-display[data-student="${studentId}"]`).text(finalGrade);
        $(`.status-badge[data-student="${studentId}"]`)
            .removeClass('badge-success badge-danger badge-warning')
            .addClass(`badge-${statusClass}`)
            .text(status);
    }

    // Toast notification function
    function showToast(type, message) {
        console.log('showToast called:', type, message); // Debug

        // Remove existing toasts
        $('.toast-notification').remove();

        const toastClass = type === 'success' ? 'alert-success' : (type === 'warning' ? 'alert-warning' : 'alert-danger');
        const iconClass = type === 'success' ? 'fa-check-circle' : (type === 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle');

        const toast = $(`
            <div class="toast-notification alert ${toastClass} alert-dismissible fade show" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <i class="fas ${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" onclick="$(this).parent().remove()"></button>
            </div>
        `);

        $('body').append(toast);
        console.log('Toast added to body'); // Debug

        // Auto-remove after 5 seconds (increased for debugging)
        setTimeout(function() {
            if (toast.length) {
                toast.fadeOut(function() {
                    $(this).remove();
                });
            }
        }, 5000);
    }

    // Simple test for Save All Grades button
    $('#saveAllBtn').on('click', function() {
        alert('Button clicked! This is working.');

        // Show loading state
        const saveBtn = $(this);
        const originalText = saveBtn.html();
        saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        // Collect all grade data
        const gradesData = {};

        // Collect grades from all inputs
        $('.grade-input').each(function() {
            const studentId = $(this).data('student');
            const quarter = $(this).data('quarter');
            const value = $(this).val();

            if (studentId && quarter && value) {
                if (!gradesData[studentId]) {
                    gradesData[studentId] = {};
                }
                gradesData[studentId][quarter] = value;
            }
        });

        console.log('Grades to save:', gradesData);

        // Check if we have any data to save
        if (Object.keys(gradesData).length === 0) {
            alert('No grades to save. Please enter some grades first.');
            saveBtn.prop('disabled', false).html(originalText);
            return;
        }

        // Submit via AJAX
        $.ajax({
            url: '{{ route("teacher.subjects.grades.update", $subject) }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                grades: gradesData
            },
            success: function(response) {
                console.log('Success:', response);
                alert('Grades saved successfully!');
                window.location.reload();
            },
            error: function(xhr) {
                console.log('Error:', xhr);
                alert('Error saving grades: ' + (xhr.responseJSON?.message || 'Unknown error'));
            },
            complete: function() {
                saveBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Refresh all grades
    window.refreshAllGrades = function() {
        const refreshBtn = $('#refreshAllBtn');
        const originalText = refreshBtn.html();

        refreshBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Refreshing...');

        // Reload the page to get fresh data
        setTimeout(function() {
            window.location.reload();
        }, 500);
    };

    // Initialize DataTable
    $('#gradesTable').DataTable({
        "pageLength": 25,
        "order": [[ 0, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [1, 2, 3, 4, 5, 6, 7] }
        ],
        "language": {
            "search": "Search students:",
            "infoEmpty": "",
            "infoFiltered": ""
        },
        "lengthChange": false,
        "dom": '<"d-flex justify-content-between align-items-center mb-2"f>t',
        "paging": false,
        "info": false
    });

    // Initialize tooltips for better UX
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endsection
