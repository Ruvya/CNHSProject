<?php $__env->startSection('title', 'Subject Offerings Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                Subject Offerings Management
            </h1>
            <p class="text-muted mb-0">Manage subject offerings, schedules, and teacher assignments</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('registrar.subject-offerings.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create New Offering
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Offerings</h6>
                            <h3 class="mb-0"><?php echo e($stats['total_offerings']); ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-list-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Active Offerings</h6>
                            <h3 class="mb-0"><?php echo e($stats['active_offerings']); ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Enrolled</h6>
                            <h3 class="mb-0"><?php echo e($stats['total_enrolled']); ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Capacity</h6>
                            <h3 class="mb-0"><?php echo e($stats['total_capacity']); ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chair fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-filter me-2"></i>Filter Offerings
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('registrar.subject-offerings.index')); ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="school_year" class="form-label">School Year</label>
                        <select name="school_year" id="school_year" class="form-select">
                            <?php $__currentLoopData = $availableSchoolYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($year); ?>" <?php echo e($schoolYear === $year ? 'selected' : ''); ?>>
                                    <?php echo e($year); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="grading" class="form-label">Grading</label>
                        <select name="grading" id="grading" class="form-select">
                            <option value="">All Gradings</option>
                            <?php $__currentLoopData = $availableGradings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gradingOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($gradingOption); ?>" <?php echo e($grading === $gradingOption ? 'selected' : ''); ?>>
                                    <?php echo e($gradingOption); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="grade_level" class="form-label">Grade Level</label>
                        <select name="grade_level" id="grade_level" class="form-select">
                            <option value="">All Grade Levels</option>
                            <?php $__currentLoopData = $availableGradeLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($grade); ?>" <?php echo e($gradeLevel === $grade ? 'selected' : ''); ?>>
                                    <?php echo e($grade); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="<?php echo e(route('registrar.subject-offerings.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Offerings Table -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-table me-2"></i>Subject Offerings
                <?php if($offerings->count() > 0): ?>
                    <span class="badge bg-primary ms-2"><?php echo e($offerings->count()); ?> offerings</span>
                <?php endif; ?>
            </h6>
        </div>
        <div class="card-body">
            <?php if($offerings->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Subject</th>
                                <th>Track</th>
                                <th>Teacher</th>
                                <th>Schedule</th>
                                <th>Enrollment</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $offerings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offering): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div>
                                            <strong><?php echo e($offering->subject->code); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo e($offering->subject->name); ?></small>
                                            <br>
                                            <span class="badge bg-info"><?php echo e($offering->grade_level); ?></span>
                                            <span class="badge bg-secondary"><?php echo e($offering->grading); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <strong><?php echo e($offering->track); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo e($offering->school_year); ?></small>
                                    </td>
                                    <td>
                                        <?php if($offering->teacher): ?>
                                            <span class="text-success">
                                                <i class="fas fa-user-check me-1"></i>
                                                <?php echo e($offering->teacher->name); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-warning">
                                                <i class="fas fa-user-times me-1"></i>
                                                Not Assigned
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($offering->schedules->count() > 0): ?>
                                            <?php $__currentLoopData = $offering->schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="mb-1">
                                                    <small class="badge bg-light text-dark">
                                                        <?php echo e($schedule->day_of_week); ?> <?php echo e($schedule->time_range); ?>

                                                    </small>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <span class="text-muted">No schedule set</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                <?php
                                                    $percentage = $offering->max_students > 0 ? ($offering->enrolled_students / $offering->max_students) * 100 : 0;
                                                ?>
                                                <div class="progress-bar
                                                    <?php if($percentage >= 90): ?> bg-danger
                                                    <?php elseif($percentage >= 70): ?> bg-warning
                                                    <?php else: ?> bg-success
                                                    <?php endif; ?>"
                                                    style="width: <?php echo e($percentage); ?>%"></div>
                                            </div>
                                            <small class="text-muted">
                                                <?php echo e($offering->enrolled_students); ?>/<?php echo e($offering->max_students); ?>

                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($offering->status === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php elseif($offering->status === 'inactive'): ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Full</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo e(route('registrar.subject-offerings.show', $offering)); ?>"
                                               class="btn btn-outline-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('registrar.subject-offerings.edit', $offering)); ?>"
                                               class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger"
                                                    onclick="confirmDelete(<?php echo e($offering->id); ?>)" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Subject Offerings Found</h5>
                    <p class="text-muted">Start by creating your first subject offering for the current school year.</p>
                    <a href="<?php echo e(route('registrar.subject-offerings.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create First Offering
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this subject offering? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmDelete(offeringId) {
    const form = document.getElementById('deleteForm');
    form.action = `/registrar/subject-offerings/${offeringId}`;

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.registrar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Test - Copy\resources\views/registrar/subject-offerings/index.blade.php ENDPATH**/ ?>