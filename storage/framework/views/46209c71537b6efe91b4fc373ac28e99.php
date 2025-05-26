<?php $__env->startSection('title', 'User Management'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">User Management</h1>
    <p class="page-subtitle">Manage teachers and students in the CNHS system</p>
    <div class="page-actions">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="addUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-plus me-2"></i>Add New User
            </button>
            <ul class="dropdown-menu" aria-labelledby="addUserDropdown">
                <li><a class="dropdown-item" href="<?php echo e(route('admin.users.teachers.create')); ?>">
                    <i class="fas fa-chalkboard-teacher me-2"></i>Add Teacher
                </a></li>
                <li><a class="dropdown-item" href="<?php echo e(route('admin.users.students.create')); ?>">
                    <i class="fas fa-user-graduate me-2"></i>Add Student
                </a></li>
            </ul>
        </div>
    </div>
</div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card primary">
            <div class="stat-card-header">
                <div class="stat-card-title">Total Teachers</div>
                <div class="stat-card-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
            </div>
            <div class="stat-card-value"><?php echo e(number_format($teachers->count())); ?></div>
            <div class="stat-card-change positive">
                <i class="fas fa-users me-1"></i>
                Active faculty members
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card success">
            <div class="stat-card-header">
                <div class="stat-card-title">Total Students</div>
                <div class="stat-card-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
            <div class="stat-card-value"><?php echo e(number_format($students->count())); ?></div>
            <div class="stat-card-change positive">
                <i class="fas fa-filter me-1"></i>
                <?php if($selectedGradeLevel && $selectedGradeLevel !== 'all'): ?>
                    Filtered by <?php echo e($selectedGradeLevel); ?>

                <?php else: ?>
                    All grade levels
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if(isset($studentsByGrade) && $studentsByGrade->count() > 0): ?>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card info">
            <div class="stat-card-header">
                <div class="stat-card-title">Grade Levels</div>
                <div class="stat-card-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
            <div class="stat-card-value"><?php echo e($studentsByGrade->count()); ?></div>
            <div class="stat-card-change positive">
                <i class="fas fa-graduation-cap me-1"></i>
                Available grades
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card warning">
            <div class="stat-card-header">
                <div class="stat-card-title">Largest Grade</div>
                <div class="stat-card-icon">
                    <i class="fas fa-trophy"></i>
                </div>
            </div>
            <div class="stat-card-value"><?php echo e($studentsByGrade->sortByDesc('count')->first()->count ?? 0); ?></div>
            <div class="stat-card-change positive">
                <i class="fas fa-star me-1"></i>
                <?php echo e($studentsByGrade->sortByDesc('count')->first()->grade_level ?? 'N/A'); ?>

            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

    <!-- Teachers Section -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>
                    Teachers (<?php echo e($teachers->count()); ?>)
                </h5>
                <a href="<?php echo e(route('admin.users.teachers.create')); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Add Teacher
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if($teachers->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="teachersTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Strand</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($teacher->id); ?></td>
                                <td><?php echo e($teacher->name); ?></td>
                                <td><?php echo e($teacher->email); ?></td>
                                <td><?php echo e($teacher->strand ?? '-'); ?></td>
                                <td><?php echo e($teacher->contact_number ?? '-'); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo e($teacher->status === 'active' ? 'success' : 'secondary'); ?>">
                                        <?php echo e(ucfirst($teacher->status)); ?>

                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.users.teachers.edit', $teacher)); ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="<?php echo e(route('admin.users.teachers.destroy', $teacher)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this teacher?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No teachers found</h5>
                    <p class="text-muted mb-4">There are no teachers in the system yet.</p>
                    <a href="<?php echo e(route('admin.users.teachers.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add First Teacher
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Students Section -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-user-graduate me-2 text-success"></i>
                        Students (<?php echo e($students->count()); ?>)
                        <?php if($selectedGradeLevel && $selectedGradeLevel !== 'all'): ?>
                            - <?php echo e($selectedGradeLevel); ?>

                        <?php endif; ?>
                    </h5>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <!-- Grade Level Filter -->
                    <form method="GET" action="<?php echo e(route('admin.users')); ?>" class="d-flex align-items-center">
                        <label for="grade_level" class="form-label me-2 mb-0 text-muted">Filter by Grade:</label>
                        <select name="grade_level" id="grade_level" class="form-select form-select-sm me-2" style="width: auto;" onchange="this.form.submit()">
                            <option value="all" <?php echo e((!$selectedGradeLevel || $selectedGradeLevel === 'all') ? 'selected' : ''); ?>>
                                All Grades
                            </option>
                            <?php $__currentLoopData = $availableGradeLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gradeLevel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($gradeLevel); ?>" <?php echo e($selectedGradeLevel === $gradeLevel ? 'selected' : ''); ?>>
                                    <?php echo e($gradeLevel); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php if($selectedGradeLevel && $selectedGradeLevel !== 'all'): ?>
                            <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-outline-secondary btn-sm me-2">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        <?php endif; ?>
                    </form>
                    <a href="<?php echo e(route('admin.users.students.create')); ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i>Add Student
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if($students->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="studentsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Grade Level</th>
                                <th>Track</th>
                                <th>Section</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <span class="badge bg-primary"><?php echo e($student->student_id); ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-success text-white rounded-circle me-2 d-flex align-items-center justify-content-center">
                                            <?php echo e(substr($student->first_name, 0, 1)); ?><?php echo e(substr($student->last_name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <a href="<?php echo e(route('admin.users.students.show', $student)); ?>" class="text-decoration-none">
                                                <strong class="text-primary"><?php echo e($student->full_name); ?></strong>
                                            </a>
                                            <br><small class="text-muted"><?php echo e($student->email); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo e($student->email); ?></td>
                                <td>
                                    <span class="badge bg-info"><?php echo e($student->grade_level); ?></span>
                                </td>
                                <td>
                                    <?php if($student->track): ?>
                                        <span class="badge bg-warning text-dark"><?php echo e($student->track); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($student->section): ?>
                                        <span class="badge bg-secondary"><?php echo e($student->section); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('admin.users.students.show', $student)); ?>" class="btn btn-info btn-sm" title="View Profile">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.users.students.edit', $student)); ?>" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.users.students.destroy', $student)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this student?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                    <?php if($selectedGradeLevel && $selectedGradeLevel !== 'all'): ?>
                        <h5 class="text-muted">No students found in <?php echo e($selectedGradeLevel); ?></h5>
                        <p class="text-muted mb-4">There are no students enrolled in this grade level yet.</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="<?php echo e(route('admin.users.students.create')); ?>" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Add Student to <?php echo e($selectedGradeLevel); ?>

                            </a>
                            <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i>View All Students
                            </a>
                        </div>
                    <?php else: ?>
                        <h5 class="text-muted">No students found</h5>
                        <p class="text-muted mb-4">There are no students in the system yet.</p>
                        <a href="<?php echo e(route('admin.users.students.create')); ?>" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Add First Student
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Initialize DataTables for Teachers
    $('#teachersTable').DataTable({
        "pageLength": 10,
        "order": [[ 1, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 6 }
        ],
        "language": {
            "search": "Search teachers:",
            "lengthMenu": "Show _MENU_ teachers per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ teachers",
            "emptyTable": "No teachers available"
        }
    });

    // Initialize DataTables for Students
    $('#studentsTable').DataTable({
        "pageLength": 10,
        "order": [[ 1, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 6 }
        ],
        "language": {
            "search": "Search students:",
            "lengthMenu": "Show _MENU_ students per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ students",
            "emptyTable": "No students available"
        }
    });

    // Add loading state for grade level filter
    $('#grade_level').on('change', function() {
        const form = $(this).closest('form');
        const submitBtn = form.find('button[type="submit"]');

        // Show loading state
        $(this).prop('disabled', true);
        $('body').append('<div class="loading-overlay"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');

        // Submit form
        form.submit();
    });
});
</script>

<style>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.table th {
    background-color: var(--light-color);
    border: none;
    font-weight: 600;
    color: var(--dark-color);
}

.table td {
    border: none;
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background: rgba(37, 99, 235, 0.05);
}

.badge {
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 6px;
}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 0.875rem;
    font-weight: 600;
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Test - Copy\resources\views/admin/users/index.blade.php ENDPATH**/ ?>