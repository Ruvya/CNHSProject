@extends('layouts.student')

@section('title', 'Grades')

@section('styles')
    <style>
        /* Override main-content from layout to fix positioning */
        .main-content {
            padding: 2rem !important;
            background: #ffffff !important;
            min-height: calc(100vh - 80px) !important;
            position: relative;
            overflow-x: hidden;
            margin-left: 250px !important;
            

        }

        .grades-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            margin-top: 3%;
            background-image: linear-gradient(to right, #FFA726, #FF7043);
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(255, 112, 67, 0.3);
            padding: 1.5rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .grades-header::before{
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

        .header-title h1 {
            font-size: 2.8rem;
            color: white;
            margin-bottom: 0.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.4);
            z-index: 2;
            position: relative;
        }

        .subtitle {
            color: white;
            font-size: 1.3rem;
            opacity: 0.9;
            margin-bottom: 0;
            z-index: 2;
            position: relative;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
            z-index: 2;
            position: relative;
        }

        .filter-options {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filter-select {
            padding: 8px 15px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 0.9rem;
            cursor: pointer;
            min-width: 150px;
            transition: all 0.3s ease;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%20viewBox%3D%220%200%20292.4%20292.4%22%3E%3Cpath%20fill%3D%22white%22%20d%3D%22M287%2C197.35L146.2%2C56.65c-3.7-3.7-9.8-3.7-13.5%2C0L5.3%2C197.35c-3.7%2C3.7-3.7%2C9.8%2C0%2C13.5l13.5%2C13.5c3.7%2C3.7%2C9.8%2C3.7%2C13.5%2C0l100.8-100.8L259.9%2C224.45c3.7%2C3.7%2C9.8%2C3.7%2C13.5%2C0l13.5-13.5C290.7%2C207.15%2C290.7%2C201.05%2C287%2C197.35z%22%2F%3E%3C%2Fsvg%3E');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 12px;
            padding-right: 30px;
        }

        .filter-select:hover {
            border-color: rgba(255, 255, 255, 0.8);
            background-color: rgba(255, 255, 255, 0.3);
        }

        .filter-select:focus {
            outline: none;
            border-color: white;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
            background-color: rgba(255, 255, 255, 0.3);
        }

        .filter-select option {
            background-color: #3b82f6;
            color: white;
        }

        .btn-print {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            background: white;
            color: #2563eb;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            font-weight: 600;
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            background-color: #f0f0f0;
        }

        /* Quick Stats */
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.15);
        }

        /* Loading Animation */
        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Fade in animation */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.6s ease-out forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Stagger animation delays */
        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        .chart-container { animation-delay: 0.5s; }
        .grades-table-container { animation-delay: 0.6s; }
        .grading-scale-section { animation-delay: 0.7s; }

        .stat-card i {
            font-size: 2rem;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-info h3 {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .stat-info p {
            font-size: 1.5rem;
            font-weight: 600;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }

        /* Grades Table Container */
        .grades-table-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.08);
            border: 1px solid rgba(37, 99, 235, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
            overflow: hidden;
        }
        .grades-table {
            table-layout: fixed;
            width: 100%;
        }
        .grades-table th:nth-child(1), .grades-table td:nth-child(1) { width: 32%; }
        .grades-table th:nth-child(2), .grades-table td:nth-child(2) { width: 18%; }
        .grades-table th:nth-child(3), .grades-table td:nth-child(3) { width: 8%; }
        .grades-table th:nth-child(4), .grades-table td:nth-child(4) { width: 8%; }
        .grades-table th:nth-child(5), .grades-table td:nth-child(5) { width: 8%; }
        .grades-table th:nth-child(6), .grades-table td:nth-child(6) { width: 8%; }
        .grades-table th:nth-child(7), .grades-table td:nth-child(7) { width: 10%; }
        .grades-table th:nth-child(8), .grades-table td:nth-child(8) { width: 8%; }

        .table-header {
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .table-header h3 {
            font-size: 1.3rem;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
            margin: 0;
        }

        .table-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-outline-primary {
            border: 1px solid #2563eb;
            color: #2563eb;
            background: transparent;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-outline-primary:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-outline-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-outline-primary .fa-sync-alt {
            transition: transform 0.3s ease;
        }

        .btn-outline-primary:hover .fa-sync-alt {
            transform: rotate(180deg);
        }

        /* Toast notification styles */
        .toast-notification {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .toast-notification .btn-close {
            font-size: 0.75rem;
        }

        /* Grade update animations */
        .grade-updated {
            animation: gradeUpdate 0.6s ease-in-out;
        }

        @keyframes gradeUpdate {
            0% { background-color: #dbeafe; }
            50% { background-color: #bfdbfe; }
            100% { background-color: transparent; }
        }

        .enrollment-notice {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border: 1px solid #3b82f6;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
            color: #1e40af;
        }

        .enrollment-notice i {
            font-size: 1.2rem;
            color: #3b82f6;
        }

        .table-wrapper {
            overflow-x: auto;
            margin-left: -2rem;
            margin-right: -2rem;
            margin-bottom: -2rem;
        }

        .grades-table {
            width: 100%;
            border-collapse: collapse;
        }

        .grades-table th,
        .grades-table td {
            border: 1px solid rgba(37, 99, 235, 0.1);
            padding: 1rem 0.75rem;
            text-align: center;
            font-size: 0.95rem;
        }

        .grades-table th {
            background: linear-gradient(135deg, #f8faff, #f1f5f9);
            color: #2563eb;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .grades-table th:first-child {
            width: 35%;
            text-align: left;
        }

        .grades-table td:first-child {
            text-align: left;
            padding-left: 1.5rem;
            color: #2563eb;
            font-weight: 500;
        }

        .grade-cell {
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .grade-cell.pending {
            color: #6b7280;
            font-style: italic;
            background-color: #f9fafb;
        }

        .grades-table tbody tr:hover {
            background-color: rgba(37, 99, 235, 0.02);
        }

        .grades-table tbody tr:nth-child(even) {
            background-color: rgba(37, 99, 235, 0.01);
        }

        /* Subject Info Styling */
        .subject-info {
            text-align: left;
        }

        .subject-code {
            color: #6b7280;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .enrollment-status {
            color: #ef4444;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .teacher-cell {
            color: #374151;
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* Not enrolled row styling */
        .grades-table tbody tr.not-enrolled {
            background-color: rgba(226, 232, 240, 0.3);
            opacity: 0.8;
        }

        .grades-table tbody tr.not-enrolled:hover {
            background-color: rgba(226, 232, 240, 0.5);
        }

        /* Adjust first column width for subject info */
        .grades-table th:first-child {
            width: 25%;
        }

        .grades-table th:nth-child(2) {
            width: 15%;
        }

        .badge {
            display: inline-block;
            padding: 0.4rem 1.2rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge.pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge.passed {
            background-color: #d4edda;
            color: #155724;
        }

        .badge.failed {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge.not-enrolled {
            background-color: #e2e8f0;
            color: #475569;
        }

        .total-row {
            background: linear-gradient(135deg, #f8faff, #f1f5f9);
            border-top: 2px solid #2563eb;
        }

        .total-row td {
            font-weight: 700;
            color: #2563eb;
            font-size: 1rem;
            padding: 1.2rem 0.75rem;
        }

        /* Chart Container */
        .chart-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.08);
            border: 1px solid rgba(37, 99, 235, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
            height: 400px;
            position: relative;
        }

        .chart-header {
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .chart-header h3 {
            font-size: 1.3rem;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
            margin: 0;
        }

        /* Grading Scale Section */
        .grading-scale-section {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.08);
            border: 1px solid rgba(37, 99, 235, 0.1);
            padding: 2rem;
            margin-top: 2rem;
            overflow: hidden;
        }

        .scale-header {
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .scale-header h2 {
            font-size: 1.3rem;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
            margin: 0;
        }

        .scale-table {
            width: 100%;
            border-collapse: collapse;
        }

        .scale-table th,
        .scale-table td {
            border: 1px solid #e6e9f0;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }

        .scale-table th {
            background-color: #f8faff;
            color: #2563eb;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            text-align: center;
        }

        .scale-table td {
            text-align: center;
        }

        .scale-table td:nth-child(2) {
            text-align: left;
            padding-left: 1.5rem;
        }

        .grade-range {
            color: #2563eb;
            font-weight: 600;
        }

        .grade-description {
            color: #444444;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .main-content {
                margin-left: 0 !important;
                padding: 1rem !important;
            }

            .quick-stats {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
            }
        }

        @media (max-width: 768px) {
            .grades-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .header-actions {
                flex-direction: column;
                width: 100%;
                gap: 10px;
            }

            .filter-options {
                flex-direction: column;
                width: 100%;
                gap: 10px;
            }

            .filter-select {
                width: 100%;
                min-width: unset;
            }

            .btn-print {
                width: 100%;
                justify-content: center;
            }

            .quick-stats {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stat-card {
                padding: 1rem;
                gap: 1rem;
            }

            .chart-container {
                height: 300px;
                padding: 1rem;
            }

            .grades-table-container,
            .grading-scale-section {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .grades-table th,
            .grades-table td {
                padding: 0.5rem 0.25rem;
                font-size: 0.85rem;
            }

            .grades-table th:first-child,
            .grades-table td:first-child {
                padding-left: 0.5rem;
            }

            /* Hide teacher column on mobile */
            .grades-table th:nth-child(2),
            .grades-table td:nth-child(2) {
                display: none;
            }

            .subject-code,
            .enrollment-status {
                font-size: 0.75rem;
            }

            .badge {
                padding: 0.3rem 0.8rem;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .header-title h1 {
                font-size: 1.5rem;
            }

            .stat-card {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }

            .stat-card i {
                font-size: 1.5rem;
            }

            .stat-info p {
                font-size: 1.2rem;
            }

            .chart-container {
                height: 250px;
                padding: 0.75rem;
            }
        }
    </style>
@endsection

@section('content')
<div class="grades-header">
    <div class="header-title">
        <h1>Academic Grades</h1>
        <p class="subtitle">Track your academic performance and progress</p>
    </div>
    <div class="header-actions">
        <div class="filter-options">
            <select id="gradeLevel" class="filter-select">
                <option value="">Select Grade Level</option>
                <option value="11">Grade 11</option>
                <option value="12">Grade 12</option>
            </select>
            <select id="schoolYear" class="filter-select">
                <option value="">Select School Year</option>
                <option value="2023-2024">2023-2024</option>
                <option value="2022-2023">2022-2023</option>
                <option value="2021-2022">2021-2022</option>
            </select>
        </div>
        <!-- Print Grade Sheet button removed -->
    </div>
</div>

{{-- Removed quick stats cards --}}



<div class="grades-table-container fade-in">
    	<div class="table-header">
		<div class="table-header-left" style="display:flex; flex-direction:column; gap:8px; align-items:flex-start;">
			<h3>Grade Report</h3>
			<select id="semesterFilter" class="form-select" style="max-width: 220px;">
				<option value="first">First Semester</option>
				<option value="second">Second Semester</option>
				<option value="all">All</option>
			</select>
		</div>
		<div class="table-actions">
			<small class="text-muted ms-2" id="lastUpdated">
				Last updated: {{ now()->format('M d, Y h:i A') }}
			</small>
		</div>
		@if($enrolledSubjects < $totalSubjects)
			<div class="enrollment-notice">
				<i class="fas fa-info-circle"></i>
				<span>You are enrolled in {{ $enrolledSubjects }} out of {{ $totalSubjects }} available subjects. Contact your registrar to enroll in additional subjects.</span>
			</div>
		@endif
	</div>
    <div class="table-wrapper">
        <table class="grades-table">
        <thead>
            <tr>
                <th>SUBJECTS</th>
                <th>TEACHER</th>
                <th>Q1</th>
                <th>Q2</th>
                <th>Q3</th>
                <th>Q4</th>
                <th>FINAL GRADE</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @if($grades->count() > 0)
                @foreach($grades as $grade)
            @php
                $rawSemester = $grade->subject->semester ?? '';
                $semLower = strtolower($rawSemester);
                $dataSemester = 'first';
                if (strpos($semLower, '2') !== false || strpos($semLower, 'second') !== false) {
                    $dataSemester = 'second';
                } elseif (strpos($semLower, 'both') !== false) {
                    $dataSemester = 'both';
                }
            @endphp
            <tr class="{{ !$grade->is_enrolled ? 'not-enrolled' : '' }}" data-subject-id="{{ $grade->subject->id }}" data-semester="{{ $dataSemester }}">
                <td>
                    <div class="subject-info">
                        <strong>{{ $grade->subject->name ?? '-' }}</strong>
                        @if($grade->subject->code)
                            <br><small class="subject-code">{{ $grade->subject->code }}</small>
                        @endif
                        @if(!$grade->is_enrolled)
                            <br><small class="enrollment-status">Not Enrolled</small>
                        @endif
                    </div>
                </td>
                <td class="teacher-cell">
                    {{ $grade->subject->current_teacher->name ?? 'No teacher assigned' }}
                </td>
                <td class="grade-cell {{ $grade->quarter1 ? '' : 'pending' }}">
                    <span class="q1-grade">{{ $grade->quarter1 ?? '-' }}</span>
                </td>
                <td class="grade-cell {{ $grade->quarter2 ? '' : 'pending' }}">
                    <span class="q2-grade">{{ $grade->quarter2 ?? '-' }}</span>
                </td>
                <td class="grade-cell {{ $grade->quarter3 ? '' : 'pending' }}">
                    <span class="q3-grade">{{ $grade->quarter3 ?? '-' }}</span>
                </td>
                <td class="grade-cell {{ $grade->quarter4 ? '' : 'pending' }}">
                    <span class="q4-grade">{{ $grade->quarter4 ?? '-' }}</span>
                </td>
                				<td class="grade-cell {{ ($grade->final_grade && ($grade->quarter1 || $grade->quarter2 || $grade->quarter3 || $grade->quarter4)) ? '' : 'pending' }}">
					<span class="final-grade">{{ ($grade->quarter1 || $grade->quarter2 || $grade->quarter3 || $grade->quarter4) ? ($grade->final_grade ?? '-') : '-' }}</span>
				</td>
                <td>
                    @if(!$grade->is_enrolled)
                        <span class="badge not-enrolled status-badge">Not Enrolled</span>
                    @elseif($grade->final_grade)
                        <span class="badge {{ $grade->final_grade >= 75 ? 'passed' : 'failed' }} status-badge">
                            {{ $grade->final_grade >= 75 ? 'Passed' : 'Failed' }}
                        </span>
                    @else
                        <span class="badge pending status-badge">Pending</span>
                    @endif
                </td>
            </tr>
                @endforeach
                <tr class="total-row">
                <td colspan="6"><strong>GENERAL AVERAGE FOR THE SEMESTER</strong></td>
                <td><strong>{{ $generalAverage ?? '-' }}</strong></td>
                <td>
                    @if(isset($generalAverage))
                        <span class="badge {{ $generalAverage >= 75 ? 'passed' : 'failed' }}">
                            {{ $generalAverage >= 75 ? 'Passed' : 'Failed' }}
                        </span>
                    @else
                        <span class="badge pending">Pending</span>
                    @endif
                </td>
                </tr>
            @else
                <tr>
                    <td colspan="8" class="text-center" style="padding: 3rem; color: #6b7280;">
                        <i class="fas fa-book" style="font-size: 3rem; margin-bottom: 1rem; color: #d1d5db;"></i>
                        <br>
                        <strong>No subjects available</strong>
                        <br>
                        <small>Contact your registrar to get enrolled in subjects for your grade level.</small>
                    </td>
                </tr>
            @endif
        </tbody>
        </table>
    </div>
</div>

<!-- Grading Scale Section -->
<div class="grading-scale-section fade-in" id="gradingScale">
    <div class="scale-header">
        <h2>Grading Scale</h2>
    </div>
        <table class="scale-table">
            <thead>
                <tr>
                    <th>Range</th>
                    <th>Description</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="grade-range">90-100</td>
                    <td class="grade-description">Outstanding</td>
                    <td>Passed</td>
                </tr>
                <tr>
                    <td class="grade-range">85-89</td>
                    <td class="grade-description">Very Satisfactory</td>
                    <td>Passed</td>
                </tr>
                <tr>
                    <td class="grade-range">80-84</td>
                    <td class="grade-description">Satisfactory</td>
                    <td>Passed</td>
                </tr>
                <tr>
                    <td class="grade-range">75-79</td>
                    <td class="grade-description">Fairly Satisfactory</td>
                    <td>Passed</td>
                </tr>
                <tr>
                    <td class="grade-range">Below 75</td>
                    <td class="grade-description">Did Not Meet Expectations</td>
                    <td>Failed</td>
                </tr>
            </tbody>
        </table>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
        function generateGradeSheet() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Get the table element
            const table = document.querySelector('.grades-table');

            // Convert table to canvas
            html2canvas(table).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const imgWidth = 210; // A4 width in mm
                const pageHeight = 295; // A4 height in mm
                const imgHeight = canvas.height * imgWidth / canvas.width;
                let heightLeft = imgHeight;
                let position = 0;

                // Add the image to the PDF
                doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                // Add new pages if needed
                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    doc.addPage();
                    doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }

                // Save the PDF
                doc.save('grade-sheet.pdf');
            });
        }

        // Function to refresh grades in real-time
        function refreshGrades() {
            const refreshBtn = document.getElementById('refreshBtn');
            const originalText = refreshBtn.innerHTML;

            // Show loading state
            refreshBtn.disabled = true;
            refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';

            fetch('{{ route("student.grades.refresh") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateGradeTable(data.grades);
                        document.getElementById('lastUpdated').textContent = 'Last updated: ' + data.last_refresh;
                        showToast('success', 'Grades refreshed successfully!');
                    } else {
                        showToast('error', 'Failed to refresh grades');
                    }
                })
                .catch(error => {
                    console.error('Error refreshing grades:', error);
                    showToast('error', 'Error refreshing grades');
                })
                .finally(() => {
                    // Restore button state
                    refreshBtn.disabled = false;
                    refreshBtn.innerHTML = originalText;
                });
        }

        // Function to update the grade table with new data
        function updateGradeTable(grades) {
            grades.forEach(grade => {
                const subjectRow = document.querySelector(`tr[data-subject-id="${grade.subject_id}"]`);
                if (subjectRow) {
                    // Update quarter grades
                    const q1Cell = subjectRow.querySelector('.q1-grade');
                    const q2Cell = subjectRow.querySelector('.q2-grade');
                    const q3Cell = subjectRow.querySelector('.q3-grade');
                    const q4Cell = subjectRow.querySelector('.q4-grade');
                    const finalCell = subjectRow.querySelector('.final-grade');
                    const statusCell = subjectRow.querySelector('.status-badge');

                    if (q1Cell) q1Cell.textContent = grade.quarter1 || '-';
                    if (q2Cell) q2Cell.textContent = grade.quarter2 || '-';
                    if (q3Cell) q3Cell.textContent = grade.quarter3 || '-';
                    if (q4Cell) q4Cell.textContent = grade.quarter4 || '-';

                    // Compute final grade based on current semester selection
                    const semesterSelect = document.getElementById('semesterFilter');
                    const showFirst = !semesterSelect || semesterSelect.value === 'first';
                    const values = [];
                    if (showFirst) {
                        const v1 = parseFloat((q1Cell ? q1Cell.textContent : '').trim());
                        const v2 = parseFloat((q2Cell ? q2Cell.textContent : '').trim());
                        if (!isNaN(v1)) values.push(v1);
                        if (!isNaN(v2)) values.push(v2);
                    } else {
                        const v3 = parseFloat((q3Cell ? q3Cell.textContent : '').trim());
                        const v4 = parseFloat((q4Cell ? q4Cell.textContent : '').trim());
                        if (!isNaN(v3)) values.push(v3);
                        if (!isNaN(v4)) values.push(v4);
                    }
                    if (finalCell) {
                        if (values.length === 0) {
                            finalCell.textContent = '-';
                            subjectRow.querySelector('.final-grade')?.parentElement?.classList.add('pending');
                        } else {
                            const avg = (values.reduce((a, b) => a + b, 0) / values.length).toFixed(2);
                            finalCell.textContent = avg;
                            finalCell.parentElement?.classList.remove('pending');
                        }
                    }

                    if (statusCell) {
                        statusCell.className = `badge ${grade.status_color}`;
                        statusCell.textContent = grade.status;
                    }
                }
            });
        }

        // Toast notification function
        function showToast(type, message) {
            // Remove existing toasts
            document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());

            const toastClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

            const toast = document.createElement('div');
            toast.className = `toast-notification alert ${toastClass} alert-dismissible fade show`;
            toast.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.innerHTML = `
                <i class="fas ${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            `;

            document.body.appendChild(toast);

            // Auto-remove after 3 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 3000);
        }

        // Function to load grades based on selected filters
        function loadGrades() {
            const gradeLevel = document.getElementById('gradeLevel');
            const schoolYear = document.getElementById('schoolYear');

            if (gradeLevel && schoolYear) {
                if (!gradeLevel.value || !schoolYear.value) {
                    alert('Please select both Grade Level and School Year');
                    return;
                }

                // Here you would typically make an AJAX call to fetch grades for the selected year and grade level
                console.log('Loading grades for:', {
                    gradeLevel: gradeLevel.value,
                    schoolYear: schoolYear.value
                });
            }
        }

        // Add event listeners for filter changes if elements exist
        document.addEventListener('DOMContentLoaded', function() {
            const gradeLevel = document.getElementById('gradeLevel');
            const schoolYear = document.getElementById('schoolYear');
            const semesterFilter = document.getElementById('semesterFilter');

            if (gradeLevel) gradeLevel.addEventListener('change', loadGrades);
            if (schoolYear) schoolYear.addEventListener('change', loadGrades);

            function toggleSemesterColumns(semester) {
                const table = document.querySelector('.grades-table');
                if (!table) return;
                const showFirst = semester === 'first';
                table.querySelectorAll('tr').forEach(function(row) {
                    const cells = row.children;
                    // 0: SUBJECTS, 1: TEACHER, 2: Q1, 3: Q2, 4: Q3, 5: Q4, 6: FINAL, 7: STATUS
                    if (cells[2]) cells[2].style.display = showFirst ? '' : 'none';
                    if (cells[3]) cells[3].style.display = showFirst ? '' : 'none';
                    if (cells[4]) cells[4].style.display = showFirst ? 'none' : '';
                    if (cells[5]) cells[5].style.display = showFirst ? 'none' : '';

                    // Also filter rows by data-semester (first/second/both)
                    const rowSem = (row.getAttribute('data-semester') || '').toLowerCase();
                    if (rowSem) {
                        if (semester === 'first') {
                            row.style.display = (rowSem === 'first' || rowSem === 'both') ? '' : 'none';
                        } else if (semester === 'second') {
                            row.style.display = (rowSem === 'second' || rowSem === 'both') ? '' : 'none';
                        } else {
                            row.style.display = '';
                        }
                    }

                    // Determine if any visible quarter cell has a grade
                    const firstSemCells = [cells[2], cells[3]];
                    const secondSemCells = [cells[4], cells[5]];
                    const activeCells = showFirst ? firstSemCells : secondSemCells;
                    const hasVisibleGrade = activeCells.some(function(c) {
                        if (!c) return false;
                        const span = c.querySelector('span');
                        const val = span ? span.textContent.trim() : '';
                        return c.style.display !== 'none' && val !== '-' && val !== '';
                    });

                    // Compute semester final grade from the visible quarters
                    const finalCell = cells[6];
                    const finalSpan = finalCell ? finalCell.querySelector('.final-grade') : null;
                    if (finalSpan) {
                        // Parse numeric values from the relevant quarter cells
                        const values = activeCells.map(function(c) {
                            if (!c) return null;
                            const span = c.querySelector('span');
                            const txt = span ? span.textContent.trim() : '';
                            const num = parseFloat(txt);
                            return isNaN(num) ? null : num;
                        }).filter(v => v !== null);

                        if (values.length === 0) {
                            finalSpan.textContent = '-';
                            finalCell.classList.add('pending');
                        } else {
                            const avg = (values.reduce((a, b) => a + b, 0) / values.length).toFixed(2);
                            finalSpan.textContent = avg;
                            finalCell.classList.remove('pending');
                        }
                    }

                    // Update status cell based on visible grades (skip not-enrolled rows)
                    const statusCell = cells[7];
                    const statusBadge = statusCell ? statusCell.querySelector('.status-badge') : null;
                    const isNotEnrolled = row.classList.contains('not-enrolled');
                    if (statusBadge && !isNotEnrolled) {
                        if (!row.dataset.originalStatusHtml) {
                            row.dataset.originalStatusHtml = statusCell.innerHTML;
                        }
                        if (!hasVisibleGrade) {
                            statusCell.innerHTML = '<span class="badge pending status-badge">Pending</span>';
                        } else {
                            statusCell.innerHTML = row.dataset.originalStatusHtml;
                        }
                    }
                });
            }

            if (semesterFilter) {
                semesterFilter.addEventListener('change', function(e) {
                    toggleSemesterColumns(e.target.value);
                });
                // Initialize on load with current selection or default to first semester
                toggleSemesterColumns(semesterFilter.value || 'first');
            }

            
        });

        const quarterGrades = @json($quarterGrades);
        const generalAverage = {{ $generalAverage ?? 0 }};
        const data = [
            quarterGrades.first,
            quarterGrades.second,
            quarterGrades.third,
            quarterGrades.fourth
        ];
        const labels = ['Q1', 'Q2', 'Q3', 'Q4'];

    // Initialize chart only if canvas exists
    const chartCanvas = document.getElementById('academicPerformanceChart');
    if (chartCanvas) {
        new Chart(chartCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Quarterly Average',
                        data: data,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    },
                    {
                        label: 'General Average',
                        data: [generalAverage, generalAverage, generalAverage, generalAverage],
                        borderColor: '#1d4ed8',
                        backgroundColor: 'rgba(29, 78, 216, 0.05)',
                        borderDash: [8, 4],
                        fill: false,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#1f2937',
                        bodyColor: '#374151',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toFixed(1) + '%';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(37, 99, 235, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                weight: '600'
                            }
                        }
                    },
                    y: {
                        beginAtZero: false,
                        min: 70,
                        max: 100,
                        grid: {
                            color: 'rgba(37, 99, 235, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                weight: '600'
                            },
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endpush