<?php $__env->startSection('title', 'Admin Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">
            <i class="fas fa-chart-line me-2 text-primary"></i>
            Admin Reports
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Reports</li>
            </ol>
        </nav>
    </div>

    <!-- Report Generation Form -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-filter me-2 text-success"></i>
                Generate Report
            </h5>
        </div>
        <div class="card-body">
            <form id="reportForm" method="GET" action="<?php echo e(route('admin.reports.generate')); ?>">
                <div class="row">
                    <!-- Report Type -->
                    <div class="col-md-6 mb-3">
                        <label for="report_type" class="form-label">Report Type <span class="text-danger">*</span></label>
                        <select name="report_type" id="report_type" class="form-select" required>
                            <option value="">Select Report Type</option>
                            <option value="student_enrollment">Student Enrollment Report</option>
                            <option value="teacher_assignment">Teacher Assignment Report</option>
                            <option value="attendance_summary">Attendance Summary Report</option>
                            <option value="grades_report">Grades Report</option>
                            <option value="user_activity">User Activity Report</option>
                            <option value="monthly_registration">Monthly Registration Trends</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="col-md-3 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <!-- Grade Level Filter -->
                    <div class="col-md-3 mb-3">
                        <label for="grade_level" class="form-label">Grade Level</label>
                        <select name="grade_level" id="grade_level" class="form-select">
                            <option value="">All Grade Levels</option>
                            <?php $__currentLoopData = $gradelevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($grade); ?>"><?php echo e($grade); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Track Filter -->
                    <div class="col-md-3 mb-3">
                        <label for="track" class="form-label">Track</label>
                        <select name="track" id="track" class="form-select">
                            <option value="">All Tracks</option>
                            <?php $__currentLoopData = $tracks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $track): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($track); ?>"><?php echo e($track); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Strand Filter -->
                    <div class="col-md-3 mb-3">
                        <label for="strand" class="form-label">Strand</label>
                        <select name="strand" id="strand" class="form-select">
                            <option value="">All Strands</option>
                            <?php $__currentLoopData = $strands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $strand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($strand); ?>"><?php echo e($strand); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Subject Filter -->
                    <div class="col-md-3 mb-3">
                        <label for="subject_id" class="form-label">Subject</label>
                        <select name="subject_id" id="subject_id" class="form-select">
                            <option value="">All Subjects</option>
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- Teacher Filter -->
                    <div class="col-md-4 mb-3">
                        <label for="teacher_id" class="form-label">Teacher</label>
                        <select name="teacher_id" id="teacher_id" class="form-select">
                            <option value="">All Teachers</option>
                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- User Role Filter -->
                    <div class="col-md-4 mb-3">
                        <label for="user_role" class="form-label">User Role</label>
                        <select name="user_role" id="user_role" class="form-select">
                            <option value="">All Roles</option>
                            <option value="student">Students</option>
                            <option value="teacher">Teachers</option>
                            <option value="admin">Admins</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        Generate Report
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-2"></i>
                        Reset Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Types Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Available Report Types
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="report-type-item">
                                <h6><i class="fas fa-user-graduate me-2 text-primary"></i>Student Enrollment Report</h6>
                                <p class="text-muted">Comprehensive list of enrolled students with filtering options by grade, track, and enrollment date.</p>
                            </div>
                            <div class="report-type-item">
                                <h6><i class="fas fa-chalkboard-teacher me-2 text-success"></i>Teacher Assignment Report</h6>
                                <p class="text-muted">Overview of teacher assignments to subjects and classes, including workload distribution.</p>
                            </div>
                            <div class="report-type-item">
                                <h6><i class="fas fa-calendar-check me-2 text-warning"></i>Attendance Summary Report</h6>
                                <p class="text-muted">Student and teacher attendance statistics (feature coming soon).</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="report-type-item">
                                <h6><i class="fas fa-graduation-cap me-2 text-info"></i>Grades Report</h6>
                                <p class="text-muted">Academic performance analysis with grade distribution and statistics.</p>
                            </div>
                            <div class="report-type-item">
                                <h6><i class="fas fa-users me-2 text-secondary"></i>User Activity Report</h6>
                                <p class="text-muted">System usage and user activity tracking across all user roles.</p>
                            </div>
                            <div class="report-type-item">
                                <h6><i class="fas fa-chart-line me-2 text-danger"></i>Monthly Registration Trends</h6>
                                <p class="text-muted">Registration patterns and trends over time for students and teachers.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
.report-type-item {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.report-type-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.report-type-item h6 {
    color: #495057;
    margin-bottom: 0.5rem;
}

.report-type-item p {
    font-size: 0.875rem;
    margin-bottom: 0;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.card-header h5 {
    color: #495057;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic filter visibility based on report type
    const reportTypeSelect = document.getElementById('report_type');
    const filters = {
        'student_enrollment': ['grade_level', 'track', 'strand', 'start_date', 'end_date'],
        'teacher_assignment': ['subject_id', 'status'],
        'attendance_summary': ['grade_level', 'start_date', 'end_date'],
        'grades_report': ['subject_id', 'grade_level', 'start_date', 'end_date'],
        'user_activity': ['user_role', 'start_date', 'end_date'],
        'monthly_registration': ['start_date', 'end_date']
    };

    reportTypeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        const relevantFilters = filters[selectedType] || [];
        
        // Hide all filter groups first
        document.querySelectorAll('.col-md-3, .col-md-4').forEach(col => {
            const input = col.querySelector('select, input');
            if (input && input.id !== 'report_type') {
                col.style.display = relevantFilters.includes(input.id) ? 'block' : 'none';
            }
        });
    });

    // Set default date range (last 30 days)
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(startDate.getDate() - 30);
    
    document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
    document.getElementById('start_date').value = startDate.toISOString().split('T')[0];
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Test - Copy\resources\views/admin/reports/index.blade.php ENDPATH**/ ?>