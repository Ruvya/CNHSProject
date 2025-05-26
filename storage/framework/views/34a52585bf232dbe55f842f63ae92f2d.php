<?php $__env->startSection('title', 'Manage Student Enrollment'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Manage Enrollment</h1>
            <p class="text-muted"><?php echo e($student->full_name); ?> (<?php echo e($student->student_id); ?>) - <?php echo e($student->grade_level); ?></p>
        </div>
        <div>
            <a href="<?php echo e(route('registrar.students.show', $student)); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Profile
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Student Info Card -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2 text-primary"></i>
                        Student Information
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="avatar-lg bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                        <?php echo e(substr($student->first_name, 0, 1)); ?><?php echo e(substr($student->last_name, 0, 1)); ?>

                    </div>
                    <h5><?php echo e($student->full_name); ?></h5>
                    <p class="text-muted mb-2"><?php echo e($student->student_id); ?></p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge bg-info"><?php echo e($student->grade_level); ?></span>
                        <?php if($student->section): ?>
                            <span class="badge bg-success"><?php echo e($student->section); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if($student->track): ?>
                        <p class="mb-1"><strong>Track:</strong> <?php echo e($student->track); ?></p>
                    <?php endif; ?>
                    <?php if($student->strand): ?>
                        <p class="mb-0"><strong>Strand:</strong> <?php echo e($student->strand); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Enrollment Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-success"></i>
                        Enrollment Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary"><?php echo e($enrolledSubjects->count()); ?></h4>
                                <small class="text-muted">Enrolled Subjects</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info"><?php echo e($availableSubjects->count()); ?></h4>
                            <small class="text-muted">Available Subjects</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollment Management -->
        <div class="col-md-8">
            <form action="<?php echo e(route('registrar.students.update-enrollment', $student)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-book me-2 text-warning"></i>
                            Subject Enrollment
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Available Subjects -->
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Available Subjects</h6>
                                <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                                    <?php $__empty_1 = true; $__currentLoopData = $availableSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="subjects[]" value="<?php echo e($subject->id); ?>" 
                                                   id="subject_<?php echo e($subject->id); ?>"
                                                   <?php echo e($enrolledSubjects->contains($subject->id) ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="subject_<?php echo e($subject->id); ?>">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <strong><?php echo e($subject->name ?? $subject->subject_name); ?></strong>
                                                        <br><small class="text-muted"><?php echo e($subject->code ?? $subject->subject_code); ?></small>
                                                        <?php if($subject->description): ?>
                                                            <br><small class="text-muted"><?php echo e(Str::limit($subject->description, 60)); ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="badge bg-info"><?php echo e($subject->grade_level); ?></span>
                                                        <br><span class="badge bg-secondary"><?php echo e($subject->units ?? 1); ?> units</span>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        <hr class="my-2">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="text-center py-4">
                                            <i class="fas fa-book fa-2x text-muted mb-2"></i>
                                            <p class="text-muted">No subjects available</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Currently Enrolled -->
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Currently Enrolled Subjects</h6>
                                <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                                    <?php $__empty_1 = true; $__currentLoopData = $enrolledSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="card mb-2">
                                            <div class="card-body p-2">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <strong><?php echo e($subject->name ?? $subject->subject_name); ?></strong>
                                                        <br><small class="text-muted"><?php echo e($subject->code ?? $subject->subject_code); ?></small>
                                                        <?php if($subject->description): ?>
                                                            <br><small class="text-muted"><?php echo e(Str::limit($subject->description, 60)); ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="badge bg-success">Enrolled</span>
                                                        <br><span class="badge bg-secondary"><?php echo e($subject->units ?? 1); ?> units</span>
                                                        <?php if($subject->pivot && $subject->pivot->grade): ?>
                                                            <br><span class="badge bg-<?php echo e($subject->pivot->grade >= 75 ? 'success' : 'danger'); ?>">
                                                                Grade: <?php echo e($subject->pivot->grade); ?>

                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="text-center py-4">
                                            <i class="fas fa-user-graduate fa-2x text-muted mb-2"></i>
                                            <p class="text-muted">No subjects enrolled</p>
                                            <small class="text-muted">Select subjects from the left to enroll the student.</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2 mb-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAll()">
                                        <i class="fas fa-check-double me-1"></i>Select All
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="selectNone()">
                                        <i class="fas fa-times me-1"></i>Select None
                                    </button>
                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="selectByGrade('<?php echo e($student->grade_level); ?>')">
                                        <i class="fas fa-filter me-1"></i>Select <?php echo e($student->grade_level); ?> Only
                                    </button>
                                    <?php if($student->strand): ?>
                                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="selectByStrand('<?php echo e($student->strand); ?>')">
                                            <i class="fas fa-stream me-1"></i>Select <?php echo e($student->strand); ?> Subjects
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Changes will be saved immediately. Unchecked subjects will be removed from enrollment.
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="<?php echo e(route('registrar.students.show', $student)); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Update Enrollment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
.avatar-lg {
    width: 64px;
    height: 64px;
    font-size: 1.5rem;
    font-weight: 600;
}

.form-check-input:checked + .form-check-label {
    background-color: #f8f9fa;
    border-radius: 0.375rem;
    padding: 0.5rem;
}

.card-body p-2 {
    padding: 0.5rem !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function selectAll() {
    document.querySelectorAll('input[name="subjects[]"]').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function selectNone() {
    document.querySelectorAll('input[name="subjects[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}

function selectByGrade(gradeLevel) {
    document.querySelectorAll('input[name="subjects[]"]').forEach(checkbox => {
        const label = checkbox.nextElementSibling;
        const gradeSpan = label.querySelector('.badge.bg-info');
        if (gradeSpan && gradeSpan.textContent.trim() === gradeLevel) {
            checkbox.checked = true;
        } else {
            checkbox.checked = false;
        }
    });
}

function selectByStrand(strand) {
    // This would require additional data attributes or AJAX to filter by strand
    // For now, we'll just select all subjects for the student's grade level
    selectByGrade('<?php echo e($student->grade_level); ?>');
}

// Auto-save functionality (optional)
document.querySelectorAll('input[name="subjects[]"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        // You can add auto-save functionality here if needed
        // For now, we'll just show a visual indicator
        const label = this.nextElementSibling;
        if (this.checked) {
            label.style.backgroundColor = '#d4edda';
        } else {
            label.style.backgroundColor = '';
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.registrar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Test - Copy\resources\views/registrar/students/enrollment.blade.php ENDPATH**/ ?>