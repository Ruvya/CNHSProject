<?php $__env->startSection('title', 'Students - ' . $subject->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-gray-800"><?php echo e($subject->name); ?> - Students</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('teacher.dashboard')); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('teacher.subjects')); ?>">Subjects</a></li>
                            <li class="breadcrumb-item active"><?php echo e($subject->name); ?> Students</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="<?php echo e(route('teacher.subjects')); ?>" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Back to Subjects
                    </a>
                    <a href="<?php echo e(route('teacher.subjects.grades', $subject)); ?>" class="btn btn-success">
                        <i class="fas fa-clipboard-list"></i> Manage Grades
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="text-primary font-weight-bold">Subject Code</h6>
                            <p class="mb-0"><?php echo e($subject->code); ?></p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-primary font-weight-bold">Grade Level</h6>
                            <p class="mb-0">Grade <?php echo e($subject->grade_level); ?></p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-primary font-weight-bold">Units</h6>
                            <p class="mb-0"><?php echo e($subject->units); ?></p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-primary font-weight-bold">Total Students</h6>
                            <p class="mb-0"><?php echo e($students->count()); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Enrolled Students</h6>
                    <span class="badge badge-primary"><?php echo e($students->count()); ?> Students</span>
                </div>
                <div class="card-body">
                    <?php if($students->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="studentsTable">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Grade Level</th>
                                        <th>Section</th>
                                        <th>Current Grade</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $grade = $student->grades->first();
                                            $finalGrade = $grade ? $grade->final_grade : null;
                                            $status = $finalGrade ? ($finalGrade >= 75 ? 'Passed' : 'Failed') : 'Pending';
                                            $statusClass = $finalGrade ? ($finalGrade >= 75 ? 'success' : 'danger') : 'warning';
                                        ?>
                                        <tr>
                                            <td><?php echo e($student->student_id); ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo e($student->profile_picture ? asset('storage/' . $student->profile_picture) : asset('images/photo.jpg')); ?>" 
                                                         class="rounded-circle me-2" width="32" height="32" alt="Profile">
                                                    <div>
                                                        <strong><?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?></strong>
                                                        <?php if($student->middle_name): ?>
                                                            <br><small class="text-muted"><?php echo e($student->middle_name); ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Grade <?php echo e($student->grade_level); ?></td>
                                            <td><?php echo e($student->section ?? 'N/A'); ?></td>
                                            <td>
                                                <?php if($finalGrade): ?>
                                                    <span class="badge badge-<?php echo e($statusClass); ?>"><?php echo e($finalGrade); ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">No grade</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?php echo e($statusClass); ?>"><?php echo e($status); ?></span>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('teacher.subjects.grades.edit', [$subject, $student])); ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Edit Grade">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?php echo e(route('teacher.students.show', $student)); ?>" 
                                                   class="btn btn-sm btn-outline-info" title="View Profile">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-600">No Students Enrolled</h5>
                            <p class="text-gray-500">There are currently no students enrolled in this subject.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
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
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    $('#studentsTable').DataTable({
        "pageLength": 25,
        "order": [[ 1, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [6] }
        ],
        "language": {
            "search": "Search students:",
            "lengthMenu": "Show _MENU_ students per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ students",
            "infoEmpty": "No students found",
            "infoFiltered": "(filtered from _MAX_ total students)"
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Test - Copy\resources\views/teacher/subjects/students.blade.php ENDPATH**/ ?>