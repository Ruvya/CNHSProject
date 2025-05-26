<?php $__env->startSection('title', 'Edit Subject'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-edit me-2 text-warning"></i>
                Edit Subject
            </h1>
            <p class="text-muted mb-0">Update information for <?php echo e($subject->name); ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('admin.subjects')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Subjects
            </a>
            <a href="<?php echo e(route('admin.subjects.show', $subject)); ?>" class="btn btn-outline-info">
                <i class="fas fa-eye me-2"></i>View Details
            </a>
        </div>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-1">
                        <i class="fas fa-edit me-2 text-warning"></i>
                        Edit Subject Information
                    </h5>
                    <p class="text-muted mb-0 small">Modify the subject details below</p>
                </div>
            <div class="card-body">
                <form action="<?php echo e(route('admin.subjects.update', $subject)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="row">
                        <!-- Subject Name -->
                        <div class="col-md-8 mb-3">
                            <label for="name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="name" name="name" value="<?php echo e(old('name', $subject->name)); ?>"
                                   placeholder="e.g., Mathematics, English, Science">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Subject Code -->
                        <div class="col-md-4 mb-3">
                            <label for="code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="code" name="code" value="<?php echo e(old('code', $subject->code)); ?>"
                                   placeholder="e.g., MATH101, ENG101">
                            <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Grade Level -->
                        <div class="col-md-4 mb-3">
                            <label for="grade_level" class="form-label">Grade Level <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['grade_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="grade_level" name="grade_level">
                                <option value="">Select Grade Level</option>
                                <option value="Grade 11" <?php echo e(old('grade_level', $subject->grade_level) === 'Grade 11' ? 'selected' : ''); ?>>Grade 11</option>
                                <option value="Grade 12" <?php echo e(old('grade_level', $subject->grade_level) === 'Grade 12' ? 'selected' : ''); ?>>Grade 12</option>
                            </select>
                            <?php $__errorArgs = ['grade_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Units -->
                        <div class="col-md-4 mb-3">
                            <label for="units" class="form-label">Units <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['units'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="units" name="units">
                                <option value="">Select Units</option>
                                <?php for($i = 1; $i <= 6; $i++): ?>
                                    <option value="<?php echo e($i); ?>" <?php echo e(old('units', $subject->units) == $i ? 'selected' : ''); ?>>
                                        <?php echo e($i); ?> <?php echo e(Str::plural('unit', $i)); ?>

                                    </option>
                                <?php endfor; ?>
                            </select>
                            <?php $__errorArgs = ['units'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Teacher -->
                        <div class="col-md-4 mb-3">
                            <label for="teacher_id" class="form-label">Assigned Teacher</label>
                            <select class="form-select <?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="teacher_id" name="teacher_id">
                                <option value="">No Teacher Assigned</option>
                                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($teacher->id); ?>" <?php echo e(old('teacher_id', $subject->teacher_id) == $teacher->id ? 'selected' : ''); ?>>
                                        <?php echo e($teacher->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Track -->
                        <div class="col-md-6 mb-3">
                            <label for="track" class="form-label">Track</label>
                            <select class="form-select <?php $__errorArgs = ['track'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="track" name="track">
                                <option value="">Select Track (Optional)</option>
                                <option value="Academic Track" <?php echo e(old('track', $subject->track) === 'Academic Track' ? 'selected' : ''); ?>>Academic Track</option>
                                <option value="Technical-Vocational-Livelihood Track" <?php echo e(old('track', $subject->track) === 'Technical-Vocational-Livelihood Track' ? 'selected' : ''); ?>>Technical-Vocational-Livelihood Track</option>
                                <option value="Sports Track" <?php echo e(old('track', $subject->track) === 'Sports Track' ? 'selected' : ''); ?>>Sports Track</option>
                                <option value="Arts and Design Track" <?php echo e(old('track', $subject->track) === 'Arts and Design Track' ? 'selected' : ''); ?>>Arts and Design Track</option>
                            </select>
                            <?php $__errorArgs = ['track'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Strand -->
                        <div class="col-md-6 mb-3">
                            <label for="strand" class="form-label">Strand</label>
                            <select class="form-select <?php $__errorArgs = ['strand'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="strand" name="strand">
                                <option value="">Select Strand (Optional)</option>
                                <option value="HUMSS" <?php echo e(old('strand', $subject->strand) === 'HUMSS' ? 'selected' : ''); ?>>HUMSS (Humanities and Social Sciences)</option>
                                <option value="STEM" <?php echo e(old('strand', $subject->strand) === 'STEM' ? 'selected' : ''); ?>>STEM (Science, Technology, Engineering and Mathematics)</option>
                                <option value="ABM" <?php echo e(old('strand', $subject->strand) === 'ABM' ? 'selected' : ''); ?>>ABM (Accountancy, Business and Management)</option>
                                <option value="GAS" <?php echo e(old('strand', $subject->strand) === 'GAS' ? 'selected' : ''); ?>>GAS (General Academic Strand)</option>
                                <option value="TVL-ICT" <?php echo e(old('strand', $subject->strand) === 'TVL-ICT' ? 'selected' : ''); ?>>TVL-ICT (Information and Communications Technology)</option>
                                <option value="TVL-HE" <?php echo e(old('strand', $subject->strand) === 'TVL-HE' ? 'selected' : ''); ?>>TVL-HE (Home Economics)</option>
                                <option value="TVL-AFA" <?php echo e(old('strand', $subject->strand) === 'TVL-AFA' ? 'selected' : ''); ?>>TVL-AFA (Agri-Fishery Arts)</option>
                            </select>
                            <?php $__errorArgs = ['strand'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  id="description" name="description" rows="4"
                                  placeholder="Enter subject description, objectives, or additional information..."><?php echo e(old('description', $subject->description)); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Optional: Provide a brief description of the subject content and objectives.</div>
                    </div>

                    <!-- Current Information Display -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Current Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li><strong>Created:</strong> <?php echo e($subject->created_at->format('M d, Y')); ?></li>
                                    <li><strong>Last Updated:</strong> <?php echo e($subject->updated_at->format('M d, Y')); ?></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li><strong>Enrolled Students:</strong> <?php echo e($subject->students()->count()); ?></li>
                                    <li><strong>Status:</strong>
                                        <?php if($subject->teacher_id): ?>
                                            <span class="badge bg-success">Assigned</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Unassigned</span>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo e(route('admin.subjects')); ?>" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>Update Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Update strand options based on track selection
    $('#track').on('change', function() {
        const track = $(this).val();
        const strandSelect = $('#strand');
        const currentStrand = '<?php echo e(old("strand", $subject->strand)); ?>';

        // Clear current options
        strandSelect.html('<option value="">Select Strand (Optional)</option>');

        if (track === 'Academic Track') {
            strandSelect.append(`
                <option value="HUMSS" ${currentStrand === 'HUMSS' ? 'selected' : ''}>HUMSS (Humanities and Social Sciences)</option>
                <option value="STEM" ${currentStrand === 'STEM' ? 'selected' : ''}>STEM (Science, Technology, Engineering and Mathematics)</option>
                <option value="ABM" ${currentStrand === 'ABM' ? 'selected' : ''}>ABM (Accountancy, Business and Management)</option>
                <option value="GAS" ${currentStrand === 'GAS' ? 'selected' : ''}>GAS (General Academic Strand)</option>
            `);
        } else if (track === 'Technical-Vocational-Livelihood Track') {
            strandSelect.append(`
                <option value="TVL-ICT" ${currentStrand === 'TVL-ICT' ? 'selected' : ''}>TVL-ICT (Information and Communications Technology)</option>
                <option value="TVL-HE" ${currentStrand === 'TVL-HE' ? 'selected' : ''}>TVL-HE (Home Economics)</option>
                <option value="TVL-AFA" ${currentStrand === 'TVL-AFA' ? 'selected' : ''}>TVL-AFA (Agri-Fishery Arts)</option>
            `);
        }
    });

    // Trigger track change on page load to populate strands
    $('#track').trigger('change');
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Test - Copy\resources\views/admin/subjects/edit.blade.php ENDPATH**/ ?>