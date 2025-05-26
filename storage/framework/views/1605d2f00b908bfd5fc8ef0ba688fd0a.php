

<?php $__env->startSection('title', 'Grades'); ?>

<?php $__env->startSection('styles'); ?>
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
        }

        .header-title h1 {
            font-size: 1.8rem;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .subtitle {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .filter-options {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filter-select {
            padding: 8px 15px;
            border: 1px solid rgba(37, 99, 235, 0.2);
            border-radius: 8px;
            background-color: white;
            color: #2563eb;
            font-size: 0.9rem;
            cursor: pointer;
            min-width: 150px;
            transition: all 0.3s ease;
        }

        .filter-select:hover {
            border-color: #2563eb;
        }

        .filter-select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .filter-select option {
            padding: 8px;
        }

        .btn-print {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
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

        .table-header {
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .table-header h3 {
            font-size: 1.3rem;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
            margin: 0 0 1rem 0;
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
        <button class="btn-print" onclick="generateGradeSheet()">
            <i class="fas fa-print"></i> Print Grade Sheet
        </button>
    </div>
</div>

<div class="quick-stats">
    <div class="stat-card fade-in">
        <i class="fas fa-book"></i>
        <div class="stat-info">
            <h3>Total Subjects</h3>
            <p><?php echo e($totalSubjects); ?></p>
        </div>
    </div>
    <div class="stat-card fade-in">
        <i class="fas fa-user-check"></i>
        <div class="stat-info">
            <h3>Enrolled Subjects</h3>
            <p><?php echo e($enrolledSubjects); ?></p>
        </div>
    </div>
    <div class="stat-card fade-in">
        <i class="fas fa-chart-line"></i>
        <div class="stat-info">
            <h3>General Average</h3>
            <p><?php echo e($generalAverage ?? '-'); ?></p>
        </div>
    </div>
    <div class="stat-card fade-in">
        <i class="fas fa-trophy"></i>
        <div class="stat-info">
            <h3>Highest Grade</h3>
            <p><?php echo e($highestGrade ?? '-'); ?></p>
        </div>
    </div>
</div>

<div class="chart-container fade-in">
    <div class="chart-header">
        <h3>Academic Performance Trend</h3>
    </div>
    <canvas id="academicPerformanceChart"></canvas>
</div>

<div class="grades-table-container fade-in">
    <div class="table-header">
        <h3>Grade Report</h3>
        <?php if($enrolledSubjects < $totalSubjects): ?>
            <div class="enrollment-notice">
                <i class="fas fa-info-circle"></i>
                <span>You are enrolled in <?php echo e($enrolledSubjects); ?> out of <?php echo e($totalSubjects); ?> available subjects. Contact your registrar to enroll in additional subjects.</span>
            </div>
        <?php endif; ?>
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
            <?php if($grades->count() > 0): ?>
                <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="<?php echo e(!$grade->is_enrolled ? 'not-enrolled' : ''); ?>">
                <td>
                    <div class="subject-info">
                        <strong><?php echo e($grade->subject->name ?? '-'); ?></strong>
                        <?php if($grade->subject->code): ?>
                            <br><small class="subject-code"><?php echo e($grade->subject->code); ?></small>
                        <?php endif; ?>
                        <?php if(!$grade->is_enrolled): ?>
                            <br><small class="enrollment-status">Not Enrolled</small>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="teacher-cell">
                    <?php echo e($grade->subject->teacher->name ?? 'No teacher assigned'); ?>

                </td>
                <td class="grade-cell <?php echo e($grade->quarter1 ? '' : 'pending'); ?>"><?php echo e($grade->quarter1 ?? '-'); ?></td>
                <td class="grade-cell <?php echo e($grade->quarter2 ? '' : 'pending'); ?>"><?php echo e($grade->quarter2 ?? '-'); ?></td>
                <td class="grade-cell <?php echo e($grade->quarter3 ? '' : 'pending'); ?>"><?php echo e($grade->quarter3 ?? '-'); ?></td>
                <td class="grade-cell <?php echo e($grade->quarter4 ? '' : 'pending'); ?>"><?php echo e($grade->quarter4 ?? '-'); ?></td>
                <td class="grade-cell <?php echo e($grade->final_grade ? '' : 'pending'); ?>"><?php echo e($grade->final_grade ?? '-'); ?></td>
                <td>
                    <?php if(!$grade->is_enrolled): ?>
                        <span class="badge not-enrolled">Not Enrolled</span>
                    <?php elseif($grade->final_grade): ?>
                        <span class="badge <?php echo e($grade->final_grade >= 75 ? 'passed' : 'failed'); ?>">
                            <?php echo e($grade->final_grade >= 75 ? 'Passed' : 'Failed'); ?>

                        </span>
                    <?php else: ?>
                        <span class="badge pending">Pending</span>
                    <?php endif; ?>
                </td>
            </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr class="total-row">
                <td colspan="6"><strong>GENERAL AVERAGE FOR THE SEMESTER</strong></td>
                <td><strong><?php echo e($generalAverage ?? '-'); ?></strong></td>
                <td>
                    <?php if(isset($generalAverage)): ?>
                        <span class="badge <?php echo e($generalAverage >= 75 ? 'passed' : 'failed'); ?>">
                            <?php echo e($generalAverage >= 75 ? 'Passed' : 'Failed'); ?>

                        </span>
                    <?php else: ?>
                        <span class="badge pending">Pending</span>
                    <?php endif; ?>
                </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center" style="padding: 3rem; color: #6b7280;">
                        <i class="fas fa-book" style="font-size: 3rem; margin-bottom: 1rem; color: #d1d5db;"></i>
                        <br>
                        <strong>No subjects available</strong>
                        <br>
                        <small>Contact your registrar to get enrolled in subjects for your grade level.</small>
                    </td>
                </tr>
            <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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

        // Function to load grades based on selected filters
        function loadGrades() {
            const gradeLevel = document.getElementById('gradeLevel').value;
            const schoolYear = document.getElementById('schoolYear').value;

            if (!gradeLevel || !schoolYear) {
                alert('Please select both Grade Level and School Year');
                return;
            }

            // Here you would typically make an AJAX call to fetch grades for the selected year and grade level
            console.log('Loading grades for:', {
                gradeLevel: gradeLevel,
                schoolYear: schoolYear
            });
        }

        // Add event listeners for filter changes
        document.getElementById('gradeLevel').addEventListener('change', loadGrades);
        document.getElementById('schoolYear').addEventListener('change', loadGrades);

        const quarterGrades = <?php echo json_encode($quarterGrades, 15, 512) ?>;
        const generalAverage = <?php echo e($generalAverage ?? 0); ?>;
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Test - Copy\resources\views/student/grades.blade.php ENDPATH**/ ?>