@extends('Principal.layouts.admin')

@section('title', 'Teacher Management')

@section('content')
<!-- Page Header -->
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="page-title">
            <div class="">
                 
                </div>
                <i class="fas fa-chalkboard-teacher me-3"></i>
                Faculty Management
            </h1>
            <p class="page-subtitle">
                Manage teaching staff information, assignments, and status
            </p>
        </div>
       
    </div>
</div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Info:</strong> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Faculty Registration Information -->
    <div class="info-card mb-4">
        <div class="info-card-header">
            <h5 class="mb-0">
                <i class="fas fa-user-plus me-2"></i>
                Faculty Registration Process
            </h5>
        </div>
        <div class="info-card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="registration-steps">
                        <h6 class="text-primary mb-3">How New Faculty Members Join:</h6>
                        <div class="step-item">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <strong>Admin Creation:</strong> Teachers are created by administrators through the admin panel
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <strong>Account Assignment:</strong> Credentials are provided to teachers by the administration
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <strong>Account Creation:</strong> Complete profile with email and password
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <strong>Management:</strong> They appear here for administrative oversight
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Faculty Directory -->
    <div class="faculty-directory-card">
        <div class="faculty-directory-header">
            <h5 class="mb-0">
                <i class="fas fa-address-book me-2"></i>
                Faculty Directory
            </h5>
            <div class="faculty-stats">
                <span class="stat-item">
                    <i class="fas fa-users me-1"></i>
                    {{ count($teachers) }} Total
                </span>
                <span class="stat-item">
                    <i class="fas fa-check-circle me-1"></i>
                    {{ collect($teachers)->where('status', 'active')->count() }} Active
                </span>
            </div>
            <div class="dropdown ms-3">
                <button class="btn btn-primary dropdown-toggle" type="button" id="facultyFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-filter me-2"></i>Filter Faculty
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="facultyFilterDropdown">
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterTeachers('active');"><i class="fas fa-check-circle me-2 text-success"></i>Active Only</a></li>
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterTeachers('inactive');"><i class="fas fa-pause-circle me-2 text-secondary"></i>Inactive Only</a></li>
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterTeachers('all');"><i class="fas fa-users me-2"></i>Show All</a></li>
                </ul>
            </div>
        </div>
        <div class="faculty-directory-body">
            <div class="table-responsive">
                <table class="faculty-table">
                    <thead>
                        <tr>
                            <th>
                                <i class="fas fa-user me-2"></i>FACULTY MEMBER
                            </th>
                            <th>
                                <i class="fas fa-envelope me-2"></i>EMAIL
                            </th>
                            <th>
                                <i class="fas fa-phone me-2"></i>CONTACT NUMBER
                            </th>
                            <th>
                                <i class="fas fa-book me-2"></i>SUBJECT AREA
                            </th>
                            <th>
                                <i class="fas fa-route me-2"></i>TRACK
                            </th>
                            <th>
                                <i class="fas fa-graduation-cap me-2"></i>CLUSTER
                            </th>
                            <th>
                                <i class="fas fa-toggle-on me-2"></i>STATUS
                            </th>
                            <th>
                                <i class="fas fa-download me-2"></i>LOAD
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr id="teacher-row-{{ $teacher->id }}">
                                <td>{{ $teacher->name }}</td>
                                <td>{{ $teacher->email }}</td>
                                <td>{{ $teacher->contact_number }}</td>
                                <td>{{ $teacher->subject ?? 'N/A' }}</td>
                                <td>{{ $teacher->track ?? 'N/A' }}</td>
                                <td>{{ $teacher->cluster ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($teacher->status) }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="loadTeacher({{ $teacher->id }})">
                                        <i class="fas fa-download me-1"></i>Load
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No teachers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
@parent
<style>
    body, .page-header, .page-title, .page-subtitle, .info-card, .info-card-header, .info-card-body, .registration-steps, .step-content, .registration-visual, .visual-icon, h1, h2, h3, h4, h5, h6, p, span, div, th, td, label {
        color: #fff !important;
    }
    .info-card, .info-card-header, .info-card-body {
        background: #1E3A8A !important;
        border: none !important;
    }
    .registration-visual .visual-icon i {
        color: #fff !important;
    }
    /* CSS Variables */
    :root {
        --primary-orange: #ff6b35;
        --primary-orange-light: #ff8c5a;
        --primary-yellow: #ffd23f;
        --primary-blue: #007bff;
        --primary-blue-light: #4dabf7;
        --success-color: #28a745;
        --info-color: #17a2b8;
    }

    /* Page Header Styling */
    .page-header {
        background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-yellow) 100%);
        border-radius: 16px;
        padding: 2rem;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: var(--white);
    }

    .page-header::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        background: linear-gradient(135deg, var(--primary-blue-light) 0%, var(--primary-blue) 100%);
        clip-path: polygon(100% 0, 100% 100%, 0 100%, 20% 0);
        opacity: 0.9;
        z-index: 1;
    }

    .page-title {
        color: var(--white);
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .page-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
        margin-bottom: 0;
        font-weight: 400;
        position: relative;
        z-index: 2;
    }

    /* Info Card Styling */
    .info-card {
        background: var(--white);
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: none;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .info-card-header {
        background: linear-gradient(to right, var(--primary-blue), var(--primary-blue-light));
        color: var(--white);
        padding: 1.5rem;
        border-bottom: none;
        font-weight: 600;
    }

    .info-card-body {
        padding: 1.5rem;
    }

    /* Registration Steps */
    .registration-steps {
        padding: 1rem 0;
    }

    .step-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1rem;
        padding: 0.5rem;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .step-item:hover {
        background: var(--gray-50);
    }

    .step-number {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--primary-blue-light) 0%, var(--primary-blue) 100%);
        color: var(--white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .step-content {
        flex-grow: 1;
    }

    /* Faculty Directory */
    .faculty-directory-card {
        background: var(--white);
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: none;
        overflow: hidden;
    }

    .faculty-directory-header {
        background: linear-gradient(to right, var(--primary-blue), var(--primary-blue-light));
        color: var(--white);
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .faculty-stats {
        display: flex;
        gap: 1rem;
    }

    .stat-item {
        background: rgba(255, 255, 255, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
    }

    .faculty-directory-body {
        padding: 1.5rem;
    }

    /* Table Styling */
    .faculty-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .faculty-table th {
        background: var(--gray-50);
        color: var(--text-dark);
        font-weight: 600;
        padding: 1rem;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--gray-200);
    }

    .faculty-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--gray-200);
        vertical-align: middle;
    }

    .faculty-table tr:hover {
        background-color: var(--gray-50);
    }

    /* Badge Styling */
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge.bg-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #16a34a 100%) !important;
    }

    .badge.bg-secondary {
        background: linear-gradient(135deg,var(--primary-blue-light) 0%, var(--primary-blue) 100%) !important;
    }

    /* Button Styling */
 

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }


    .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: var(--primary-orange-light);
    }


    .btn-outline-primary:hover {
        background: var(--primary-orange-light) var(--primary-orange-light);
        border-color: transparent;
        color: var(--white);
        transform: translateY(-1px);
    }

    /* Alert Styling */
    .alert {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .alert-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #16a34a 100%);
        color: var(--white);
    }

    .alert-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: var(--white);
    }

    .alert-info {
        background: linear-gradient(135deg, var(--info-color) 0%, #2563eb 100%);
        color: var(--white);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            text-align: center;
        }

        .page-title {
            font-size: 1.5rem;
            justify-content: center;
        }

        .faculty-directory-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .faculty-stats {
            justify-content: center;
        }

        .faculty-table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    }

    /* Header Button Group */
    .header-button-group {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
        border-radius: 12px;
        padding: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        z-index: 2;
    }

    .header-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        border: none;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        color: var(--white);
    }

    .header-btn:hover {
        transform: translateY(-2px);
        color: var(--white);
    }

    .header-btn.btn-refresh {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-light) 100%);
    }

    .header-btn.btn-refresh:hover {
        background: linear-gradient(135deg, var(--primary-blue-light) 0%, var(--primary-blue) 100%);
        box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3);
    }

    .dropdown-menu {
        border: none;
        border-radius: 10px;
        padding: 0.5rem;
        min-width: 200px;
        background: #ffffff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-top: 0.5rem;
    }

    .dropdown-item {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .dropdown-item:hover {
        background-color: rgba(0, 123, 255, 0.1);
        transform: translateX(5px);
    }

    .dropdown-item i {
        width: 20px;
        text-align: center;
    }

    @media (max-width: 768px) {
        .header-button-group {
            margin-top: 1rem;
        }
    }

    /* Faculty Directory Card: gray background, black text */
    .faculty-directory-card, .faculty-directory-header, .faculty-directory-body, .faculty-table, .faculty-table th, .faculty-table td {
        background: #e5e7eb !important;
        color: #222 !important;
    }
    .faculty-directory-header h5, .faculty-directory-header .faculty-stats, .faculty-directory-header .faculty-stats .stat-item, .faculty-directory-header .faculty-stats .stat-item i {
        color: #222 !important;
    }
    .faculty-table th {
        font-weight: 700;
    }
    .faculty-table tbody tr {
        background: #f8fafc;
    }
    .faculty-table tbody tr:nth-child(even) {
        background: #e3edfa;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load teacher function
    window.loadTeacher = function(teacherId) {
        // Redirect to show page to display teacher's subjects and schedules
        window.location.href = '{{ route("principal.teachers.show", ":id") }}'.replace(':id', teacherId);
    };

    // Filter functionality
    window.filterTeachers = function(status) {
        const rows = document.querySelectorAll('tbody tr:not(.no-teachers)');
        let visibleCount = 0;

        rows.forEach(row => {
            const statusBadge = row.querySelector('.badge');
            const teacherStatus = statusBadge ? statusBadge.textContent.toLowerCase().trim() : '';

            if (status === 'all' || teacherStatus === status) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Update filter button text
        const filterBtn = document.querySelector('.header-btn');
        const filterText = status === 'all' ? 'All Teachers' :
                          status === 'active' ? 'Active Only' : 'Inactive Only';
        filterBtn.innerHTML = `<i class="fas fa-filter"></i> ${filterText}`;

        // Show/hide no results message
        const tbody = document.querySelector('tbody');
        const noTeachersRow = tbody.querySelector('.no-teachers');

        if (visibleCount === 0 && !noTeachersRow) {
            const noResultsRow = document.createElement('tr');
            noResultsRow.className = 'no-teachers';
            noResultsRow.innerHTML = `
                <td colspan="8" class="text-center">No teachers found for the selected filter.</td>
            `;
            tbody.appendChild(noResultsRow);
        } else if (visibleCount > 0 && noTeachersRow) {
            noTeachersRow.remove();
        }
    };

    // Function to show alert messages
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.style.opacity = '0';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        const container = document.querySelector('.container-fluid');
        container.insertBefore(alertDiv, container.children[1]);

        // Animate the alert
        setTimeout(() => {
            alertDiv.style.transition = 'opacity 0.3s ease';
            alertDiv.style.opacity = '1';
        }, 10);

        // Auto-dismiss the alert after 3 seconds
        setTimeout(() => {
            alertDiv.style.opacity = '0';
            setTimeout(() => alertDiv.remove(), 300);
        }, 3000);
    }
});
</script>
@endsection