

<?php $__env->startSection('content'); ?>
<div class="container">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-gray-800">Subject Management</h1>
                    <p class="text-muted">Manage your subjects and view all available subjects in the system</p>
                </div>
                <div>
                    <a href="<?php echo e(route('registrar.subjects.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create New Subject
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Subjects</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($totalSubjectsCount); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                <?php if($gradeFilter && $gradeFilter !== 'all'): ?>
                                    Grade <?php echo e($gradeFilter); ?> Subjects
                                <?php else: ?>
                                    Filtered Results
                                <?php endif; ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($filteredSubjectsCount); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-filter fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Subjects Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>All Subjects in System</h5>
                    <span class="badge badge-light">
                        <?php if($gradeFilter && $gradeFilter !== 'all'): ?>
                            <?php echo e($filteredSubjectsCount); ?> of <?php echo e($totalSubjectsCount); ?> subjects
                        <?php else: ?>
                            <?php echo e($totalSubjectsCount); ?> subjects
                        <?php endif; ?>
                    </span>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Grade Level Filter -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <form method="GET" action="<?php echo e(route('registrar.subjects')); ?>" id="gradeFilterForm">
                                <div class="form-group">
                                    <label for="grade_level" class="form-label font-weight-bold">
                                        <i class="fas fa-filter me-2"></i>Grade Level Filter
                                    </label>
                                    <select name="grade_level" id="grade_level" class="form-control" onchange="this.form.submit()">
                                        <option value="all" <?php echo e((!$gradeFilter || $gradeFilter === 'all') ? 'selected' : ''); ?>>
                                            All Grades
                                        </option>
                                        <option value="11" <?php echo e($gradeFilter === '11' ? 'selected' : ''); ?>>
                                            Grade 11
                                        </option>
                                        <option value="12" <?php echo e($gradeFilter === '12' ? 'selected' : ''); ?>>
                                            Grade 12
                                        </option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <?php if($gradeFilter && $gradeFilter !== 'all'): ?>
                            <div class="col-md-8 d-flex align-items-end">
                                <div class="alert alert-info mb-0 d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span>Showing subjects for Grade <?php echo e($gradeFilter); ?> only.
                                        <a href="<?php echo e(route('registrar.subjects')); ?>" class="alert-link">Clear filter</a>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if($allSubjects->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="allSubjectsTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Grade Level</th>
                                        <th>Strand</th>
                                        <th>Units</th>
                                        <th>Teacher</th>
                                        <th>Created By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $allSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="<?php echo e($subject->registrar_id == auth()->guard('registrar')->id() ? 'table-primary' : ''); ?>">
                                            <td><strong><?php echo e($subject->code ?? $subject->subject_code); ?></strong></td>
                                            <td><?php echo e($subject->name ?? $subject->subject_name); ?></td>
                                            <td>
                                                <span class="badge badge-info">Grade <?php echo e($subject->grade_level); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary"><?php echo e($subject->strand ?? 'N/A'); ?></span>
                                            </td>
                                            <td><?php echo e($subject->units); ?></td>
                                            <td><?php echo e($subject->teacher->name ?? 'Not Assigned'); ?></td>
                                            <td>
                                                <?php if($subject->registrar_id == auth()->guard('registrar')->id()): ?>
                                                    <span class="badge badge-primary">You</span>
                                                <?php elseif($subject->registrar): ?>
                                                    <span class="badge badge-secondary"><?php echo e($subject->registrar->full_name); ?></span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">Admin</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('registrar.subjects.show', $subject)); ?>"
                                                       class="btn btn-sm btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if($subject->registrar_id == auth()->guard('registrar')->id()): ?>
                                                        <a href="<?php echo e(route('registrar.subjects.edit', $subject)); ?>"
                                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="<?php echo e(route('registrar.subjects.destroy', $subject)); ?>"
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this subject?')">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="text-muted small">View Only</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-list fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-600">No Subjects in System</h5>
                            <p class="text-gray-500">There are no subjects in the system yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startSection('styles'); ?>
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.table-primary {
    background-color: rgba(78, 115, 223, 0.1);
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.btn-group .btn {
    margin-right: 2px;
}

.card {
    border-radius: 0.35rem;
}

.alert {
    border-radius: 0.35rem;
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

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.075);
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    // Initialize DataTables for subjects table
    $('#allSubjectsTable').DataTable({
        "pageLength": 15,
        "order": [[ 2, "asc" ], [ 3, "asc" ], [ 1, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [7] }
        ],
        "language": {
            "search": "Search subjects:",
            "lengthMenu": "Show _MENU_ subjects per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ subjects",
            "infoEmpty": "No subjects found",
            "infoFiltered": "(filtered from _MAX_ total subjects)"
        },
        "responsive": true
    });

    // Add loading state for grade level filter
    $('#grade_level').on('change', function() {
        const form = $(this).closest('form');
        const selectedValue = $(this).val();

        // Show loading state
        $(this).prop('disabled', true);
        $('body').append('<div class="loading-overlay"><div class="spinner-border text-success" role="status"><span class="visually-hidden">Loading...</span></div></div>');

        // Submit form
        form.submit();
    });

    // Add smooth animations for stat cards
    $('.card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
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
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.registrar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Test - Copy\resources\views/registrar/subjects.blade.php ENDPATH**/ ?>