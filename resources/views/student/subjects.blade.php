@extends('layouts.student')

@section('title', 'My Subjects')

@section('styles')
<style>
    /* Modern Dashboard Styles */
    .main-content {
        padding: 2rem;
        background: #f8fafc;
        min-height: calc(100vh - 80px);
        position: relative;
        overflow-x: hidden;
        margin-left: 250px;
    }
   
 


    /* Page Header */
    .page-header {
        background: linear-gradient(to right, #FFA726, #FF7043);
        border-radius: 16px;
        padding: 2rem;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        
    }
    .page-header::before{
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        clip-path: polygon(100% 0, 100% 100%, 0 100%, 20% 0);
        opacity: 0.9;
        z-index: 1;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: white;
    }

    .page-header p {
        opacity: 0.9;
        margin-bottom: 0;
        color: white;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .stats-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(37, 99, 235, 0.1);
        margin-bottom: 1rem;
    }

    .stats-icon i {
        font-size: 1.5rem;
        color: #2563eb;
    }

    .stats-number {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e3a8a;
        margin-bottom: 0.25rem;
    }

    .stats-label {
        color: #6b7280;
        font-size: 0.875rem;
    }

    /* Subjects Table */
    .subjects-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e3a8a;
        margin: 0;
    }

    .form-select {
        border-color: #e5e7eb;
        border-radius: 8px;
        padding: 0.5rem 2.5rem 0.5rem 1rem;
        font-size: 0.875rem;
        max-width: 200px;
    }

    .table {
        width: 100%;
        margin-bottom: 0;
    }

    .table th {
        background: #f8fafc;
        color: #4b5563;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem 1.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .table td {
        padding: 1rem 1.5rem;
        color: #4b5563;
        vertical-align: middle;
        border-bottom: 1px solid #e5e7eb;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .badge-active {
        background-color: #ecfdf5;
        color: #059669;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        background-color: #f3f4f6;
        color: #4b5563;
        border: none;
    }

    .btn-action:hover {
        background-color: #e5e7eb;
        color: #1e3a8a;
    }

    .btn-action i {
        font-size: 1rem;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e3a8a;
    }

    .modal-body {
        padding: 1.5rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: #9ca3af;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: #374151;
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #6b7280;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      

        .page-header {
            padding: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .card-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .form-select {
            max-width: 100%;
        }

        .table-responsive {
            margin: 0 -1rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1>My Subjects</h1>
            <p>View and manage your enrolled subjects for the current semester</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="d-flex justify-content-end align-items-center">
                <div class="me-3">
                    <i class="fas fa-book fa-3x opacity-25"></i>
                </div>
                <div>
                    <div class="fw-bold text-white">Total</div>
                    <div class="opacity-75">{{ $totalSubjects ?? 0 }} Subjects</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Overview -->
<div class="stats-grid">
    <div class="stats-card">
        <div class="stats-icon">
            <i class="fas fa-book"></i>
        </div>
        <div class="stats-number">{{ $totalSubjects ?? 0 }}</div>
        <div class="stats-label">Total Subjects</div>
    </div>

    <div class="stats-card">
        <div class="stats-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stats-number">{{ $totalHours ?? 0 }}</div>
        <div class="stats-label">Weekly Hours</div>
    </div>

    <div class="stats-card">
        <div class="stats-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="stats-number">{{ $gradeLevel ?? '12' }}</div>
        <div class="stats-label">Grade Level</div>
    </div>

    <div class="stats-card">
        <div class="stats-icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stats-number">{{ number_format($averageGrade ?? 0, 2) }}</div>
        <div class="stats-label">Average Grade</div>
    </div>
</div>

<!-- Subjects List -->
<div class="subjects-card">
    <div class="card-header">
        <h2>
            <i class="fas fa-book-open me-2"></i>
            Enrolled Subjects
        </h2>
        <form method="GET" action="{{ route('student.subjects') }}">
            <select class="form-select" name="semester" onchange="this.form.submit()">
                @if(isset($semesterOptions))
                    @foreach($semesterOptions as $value => $label)
                        <option value="{{ $value }}" {{ (isset($selectedSemester) && $selectedSemester === $value) ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                @else
                    <option value="">Select Semester</option>
                    <option value="1st Semester" {{ (isset($selectedSemester) && $selectedSemester === '1st Semester') ? 'selected' : '' }}>1st Semester</option>
                    <option value="2nd Semester" {{ (isset($selectedSemester) && $selectedSemester === '2nd Semester') ? 'selected' : '' }}>2nd Semester</option>
                @endif
            </select>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Teacher</th>
                    <th>Schedule</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects ?? [] as $subject)
                <tr>
                    <td>{{ $subject->code }}</td>
                    <td>{{ $subject->name }}</td>
                    <td>{{ $subject->current_teacher->name ?? 'TBA' }}</td>
                    <td>{{ $subject->schedule ?? 'TBA' }}</td>
                    <td>
                        <span class="badge badge-active">Enrolled</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="fas fa-book"></i>
                            <h3>No Subjects Found</h3>
                            <p>You are not enrolled in any subjects for this semester.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Subject Details Modal -->
<div class="modal fade" id="subjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subject Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Subject details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation for stats cards
        const cards = document.querySelectorAll('.stats-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.4s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endsection