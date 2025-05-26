

<?php $__env->startSection('title', 'Subjects'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">My Subjects</h1>
        </div>
    </div>
    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-6 col-lg-4">
                <div class="card subject-card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="subject-icon me-3">
                                <i class="fas fa-book fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0"><?php echo e($subject->name); ?></h5>
                                <small class="text-muted">Code: <?php echo e($subject->code); ?></small>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li><strong>Grade Level:</strong> <?php echo e($subject->grade_level); ?></li>
                            <li><strong>Units:</strong> <?php echo e($subject->units); ?></li>
                            <li><strong>Students:</strong> <?php echo e($subject->students_count ?? 0); ?></li>
                        </ul>
                        <div class="mt-auto">
                            <a href="<?php echo e(route('teacher.subjects.students', $subject)); ?>" class="btn btn-outline-primary btn-sm me-2">
                                <i class="fas fa-users"></i> View Students
                            </a>
                            <a href="<?php echo e(route('teacher.subjects.grades', $subject)); ?>" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-clipboard-list"></i> Manage Grades
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="alert alert-info text-center">No subjects assigned yet.</div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
.subject-card {
    border: none;
    border-radius: 12px;
    transition: box-shadow 0.2s;
}
.subject-card:hover {
    box-shadow: 0 0.5rem 1.5rem 0 rgba(58, 59, 69, 0.15);
}
.subject-icon {
    width: 48px;
    height: 48px;
    background: #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.card-title {
    font-size: 1.2rem;
    font-weight: 600;
}
.btn-sm {
    font-size: 0.9rem;
    padding: 0.35rem 0.75rem;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Test - Copy\resources\views/teacher/subjects.blade.php ENDPATH**/ ?>