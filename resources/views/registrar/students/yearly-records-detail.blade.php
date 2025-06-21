@extends('layouts.registrar')

@section('content')
<div class="yearly-detail-container">
    <!-- Header -->
    <div class="detail-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="detail-title">
                        <i class="fas fa-calendar-alt me-3"></i>
                        Academic Year {{ $year }}
                    </h1>
                    <p class="detail-subtitle">
                        Student records for the {{ $year }} academic year
                    </p>
                </div>
                <div class="col-md-4">
                    <div class="detail-actions">
                        <a href="{{ route('registrar.students.records') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Records
                        </a>
                        <a href="{{ route('registrar.students.records.export', $year) }}" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>
                            Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="container-fluid">
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="stats-card">
                    <div class="stats-header">
                        <h5><i class="fas fa-chart-bar me-2"></i>Year {{ $year }} Statistics</h5>
                    </div>
                    <div class="stats-body">
                        <div class="row g-4">
                            <div class="col-lg-2 col-md-3 col-sm-6">
                                <div class="stat-item">
                                    <div class="stat-value">{{ number_format($yearStats['total_students']) }}</div>
                                    <div class="stat-label">Total Students</div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6">
                                <div class="stat-item">
                                    <div class="stat-value">{{ number_format($yearStats['grade_11']) }}</div>
                                    <div class="stat-label">Grade 11</div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6">
                                <div class="stat-item">
                                    <div class="stat-value">{{ number_format($yearStats['grade_12']) }}</div>
                                    <div class="stat-label">Grade 12</div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6">
                                <div class="stat-item">
                                    <div class="stat-value">{{ number_format($yearStats['academic_track']) }}</div>
                                    <div class="stat-label">Academic Track</div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6">
                                <div class="stat-item">
                                    <div class="stat-value">{{ number_format($yearStats['tvl_track']) }}</div>
                                    <div class="stat-label">TVL Track</div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6">
                                <div class="stat-item">
                                    <div class="stat-value">{{ number_format($yearStats['male_students']) }}/{{ number_format($yearStats['female_students']) }}</div>
                                    <div class="stat-label">Male/Female</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Track Breakdown -->
        @if($trackBreakdown->count() > 0)
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="breakdown-card">
                    <div class="breakdown-header">
                        <h6><i class="fas fa-chart-pie me-2"></i>Track & Strand Breakdown</h6>
                    </div>
                    <div class="breakdown-body">
                        <div class="row g-3">
                            @foreach($trackBreakdown as $breakdown)
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="breakdown-item">
                                        <div class="breakdown-count">{{ $breakdown->count }}</div>
                                        <div class="breakdown-track">{{ $breakdown->track ?? 'No Track' }}</div>
                                        <div class="breakdown-strand">{{ $breakdown->strand ?? 'No Strand' }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Students List -->
        <div class="row">
            <div class="col-12">
                <div class="students-card">
                    <div class="students-header">
                        <h5><i class="fas fa-users me-2"></i>Students ({{ $students->total() }})</h5>
                        <div class="students-search">
                            <input type="text" id="studentSearch" class="form-control" placeholder="Search students...">
                        </div>
                    </div>
                    <div class="students-body">
                        @if($students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Grade Level</th>
                                            <th>Track</th>
                                            <th>Strand</th>
                                            <th>Gender</th>
                                            <th>Enrollment Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="studentsTableBody">
                                        @foreach($students as $student)
                                            <tr class="student-row">
                                                <td>
                                                    <span class="student-id">{{ $student->student_id }}</span>
                                                </td>
                                                <td>
                                                    <div class="student-name-cell">
                                                        <div class="student-avatar-small">
                                                            {{ substr($student->first_name ?? 'S', 0, 1) }}
                                                        </div>
                                                        <div class="student-name-info">
                                                            <div class="student-full-name">{{ $student->first_name }} {{ $student->last_name }}</div>
                                                            @if($student->email)
                                                                <div class="student-email">{{ $student->email }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($student->grade_level)
                                                        <span class="badge bg-primary">{{ $student->grade_level }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($student->track)
                                                        <span class="badge bg-info">{{ $student->track }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($student->strand)
                                                        <span class="badge bg-secondary">{{ $student->strand }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($student->gender)
                                                        <span class="gender-badge {{ strtolower($student->gender) }}">{{ $student->gender }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="enrollment-date">{{ $student->created_at->format('M d, Y') }}</span>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="{{ route('registrar.students.show', $student) }}" 
                                                           class="btn btn-sm btn-outline-primary" title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('registrar.students.edit', $student) }}" 
                                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="students-pagination">
                                {{ $students->links() }}
                            </div>
                        @else
                            <div class="empty-students">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h6>No Students Found</h6>
                                <p class="text-muted">No students were enrolled in the {{ $year }} academic year.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Yearly Detail Styles */
.yearly-detail-container {
    min-height: 100vh;
    background: #f8f9fa;
}

.detail-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
}

.detail-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.detail-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

.detail-actions .btn {
    margin-left: 0.5rem;
}

/* Statistics Card */
.stats-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.stats-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 1.5rem;
}

.stats-header h5 {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

.stats-body {
    padding: 2rem;
}

.stat-item {
    text-align: center;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.stat-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
}

/* Breakdown Card */
.breakdown-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.breakdown-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 1rem 1.5rem;
}

.breakdown-header h6 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

.breakdown-body {
    padding: 1.5rem;
}

.breakdown-item {
    text-align: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.breakdown-item:hover {
    background: #e9ecef;
    transform: translateY(-1px);
}

.breakdown-count {
    font-size: 1.5rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 0.25rem;
}

.breakdown-track {
    font-size: 0.9rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.breakdown-strand {
    font-size: 0.8rem;
    color: #6c757d;
}

/* Students Card */
.students-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.students-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.students-header h5 {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

.students-search {
    width: 300px;
}

.students-body {
    padding: 0;
}

.table {
    margin-bottom: 0;
}

.table th {
    background: #f8f9fa;
    border-top: none;
    font-weight: 600;
    color: #2c3e50;
    padding: 1rem;
}

.table td {
    padding: 1rem;
    vertical-align: middle;
}

.student-row:hover {
    background: #f8f9fa;
}

.student-id {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: #667eea;
}

.student-name-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.student-avatar-small {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
}

.student-full-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.student-email {
    font-size: 0.8rem;
    color: #6c757d;
}

.gender-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 500;
}

.gender-badge.male {
    background: #e3f2fd;
    color: #1976d2;
}

.gender-badge.female {
    background: #fce4ec;
    color: #c2185b;
}

.enrollment-date {
    font-size: 0.9rem;
    color: #6c757d;
}

.action-buttons {
    display: flex;
    gap: 0.25rem;
}

.students-pagination {
    padding: 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.empty-students {
    text-align: center;
    padding: 3rem;
    color: #6c757d;
}
</style>
@endpush

@push('scripts')
<script>
// Search functionality
document.getElementById('studentSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#studentsTableBody .student-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection
