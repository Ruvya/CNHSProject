<?php $__env->startSection('title', 'Add New Subject'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-plus me-2 text-primary"></i>
                Add New Subject
            </h1>
            <p class="text-muted mb-0">Create a new academic subject for the curriculum</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('admin.subjects')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Subjects
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-1">
                        <i class="fas fa-book me-2 text-primary"></i>
                        Subject Information
                    </h5>
                    <p class="text-muted mb-0 small">Fill in the details for the new subject</p>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.subjects.store')); ?>" method="POST" id="subjectForm">
                        <?php echo csrf_field(); ?>

                        <!-- Basic Information Section -->
                        <div class="form-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-info-circle me-2 text-primary"></i>
                                Basic Information
                            </h6>
                            <div class="row">
                                <!-- Subject Name -->
                                <div class="col-md-8 mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        Subject Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="name"
                                           name="name"
                                           value="<?php echo e(old('name')); ?>"
                                           placeholder="e.g., Mathematics, English, Science"
                                           required>
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
                                    <div class="form-text">Enter the full name of the subject</div>
                                </div>

                                <!-- Subject Code -->
                                <div class="col-md-4 mb-3">
                                    <label for="code" class="form-label fw-semibold">
                                        Subject Code <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="code"
                                           name="code"
                                           value="<?php echo e(old('code')); ?>"
                                           placeholder="e.g., MATH101, ENG101"
                                           required>
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
                                    <div class="form-text">Unique identifier</div>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Details Section -->
                        <div class="form-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-graduation-cap me-2 text-success"></i>
                                Academic Details
                            </h6>
                            <div class="row">
                                <!-- Grade Level -->
                                <div class="col-md-4 mb-3">
                                    <label for="grade_level" class="form-label fw-semibold">
                                        Grade Level <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select <?php $__errorArgs = ['grade_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="grade_level"
                                            name="grade_level"
                                            required>
                                        <option value="">Select Grade Level</option>
                                        <option value="Grade 11" <?php echo e(old('grade_level') === 'Grade 11' ? 'selected' : ''); ?>>Grade 11</option>
                                        <option value="Grade 12" <?php echo e(old('grade_level') === 'Grade 12' ? 'selected' : ''); ?>>Grade 12</option>
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
                                    <div class="form-text">Target grade level</div>
                                </div>

                                <!-- Grading -->
                                <div class="col-md-8 mb-3">
                                    <label for="grading" class="form-label fw-semibold">
                                        Grading <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select <?php $__errorArgs = ['grading'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="grading"
                                            name="grading"
                                            required>
                                        <option value="">Select Grading</option>
                                        <option value="First Grading" <?php echo e(old('grading') === 'First Grading' ? 'selected' : ''); ?>>First Grading</option>
                                        <option value="Second Grading" <?php echo e(old('grading') === 'Second Grading' ? 'selected' : ''); ?>>Second Grading</option>
                                        <option value="Third Grading" <?php echo e(old('grading') === 'Third Grading' ? 'selected' : ''); ?>>Third Grading</option>
                                        <option value="Fourth Grading" <?php echo e(old('grading') === 'Fourth Grading' ? 'selected' : ''); ?>>Fourth Grading</option>
                                        <option value="All Gradings" <?php echo e(old('grading') === 'All Gradings' ? 'selected' : ''); ?>>All Gradings</option>
                                    </select>
                                    <?php $__errorArgs = ['grading'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <div class="form-text">When subject is offered during the school year</div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Teacher -->
                                <div class="col-md-12 mb-3">
                                    <label for="teacher_id" class="form-label fw-semibold">
                                        Default Teacher Assignment
                                    </label>
                                    <select class="form-select <?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="teacher_id"
                                            name="teacher_id">
                                        <option value="">No Default Teacher</option>
                                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($teacher->id); ?>" <?php echo e(old('teacher_id') == $teacher->id ? 'selected' : ''); ?>>
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
                                    <div class="form-text">Optional default teacher (can be changed when offering the subject)</div>
                                </div>


                            </div>
                        </div>

                        <!-- Track & Strand Section -->
                        <div class="form-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-route me-2 text-warning"></i>
                                Track & Strand (Optional)
                            </h6>
                            <div class="row">
                                <!-- Track -->
                                <div class="col-md-6 mb-3">
                                    <label for="track" class="form-label fw-semibold">Track</label>
                                    <select class="form-select <?php $__errorArgs = ['track'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="track" name="track">
                                        <option value="">Select Track (Optional)</option>
                                        <option value="Academic Track" <?php echo e(old('track') === 'Academic Track' ? 'selected' : ''); ?>>Academic Track</option>
                                        <option value="Technical-Vocational-Livelihood Track" <?php echo e(old('track') === 'Technical-Vocational-Livelihood Track' ? 'selected' : ''); ?>>Technical-Vocational-Livelihood Track</option>
                                        <option value="Sports Track" <?php echo e(old('track') === 'Sports Track' ? 'selected' : ''); ?>>Sports Track</option>
                                        <option value="Arts and Design Track" <?php echo e(old('track') === 'Arts and Design Track' ? 'selected' : ''); ?>>Arts and Design Track</option>
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
                                    <div class="form-text">Senior High School track</div>
                                </div>

                                <!-- Strand -->
                                <div class="col-md-6 mb-3">
                                    <label for="strand" class="form-label fw-semibold">Strand</label>
                                    <select class="form-select <?php $__errorArgs = ['strand'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="strand" name="strand">
                                        <option value="">Select Strand (Optional)</option>
                                        <option value="HUMSS" <?php echo e(old('strand') === 'HUMSS' ? 'selected' : ''); ?>>HUMSS (Humanities and Social Sciences)</option>
                                        <option value="STEM" <?php echo e(old('strand') === 'STEM' ? 'selected' : ''); ?>>STEM (Science, Technology, Engineering and Mathematics)</option>
                                        <option value="ABM" <?php echo e(old('strand') === 'ABM' ? 'selected' : ''); ?>>ABM (Accountancy, Business and Management)</option>
                                        <option value="GAS" <?php echo e(old('strand') === 'GAS' ? 'selected' : ''); ?>>GAS (General Academic Strand)</option>
                                        <option value="TVL-ICT" <?php echo e(old('strand') === 'TVL-ICT' ? 'selected' : ''); ?>>TVL-ICT (Information and Communications Technology)</option>
                                        <option value="TVL-HE" <?php echo e(old('strand') === 'TVL-HE' ? 'selected' : ''); ?>>TVL-HE (Home Economics)</option>
                                        <option value="TVL-AFA" <?php echo e(old('strand') === 'TVL-AFA' ? 'selected' : ''); ?>>TVL-AFA (Agri-Fishery Arts)</option>
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
                                    <div class="form-text">Specific strand within track</div>
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="form-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-align-left me-2 text-info"></i>
                                Description (Optional)
                            </h6>
                            <div class="mb-3">
                                <label for="description" class="form-label fw-semibold">Subject Description</label>
                                <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                          id="description"
                                          name="description"
                                          rows="4"
                                          placeholder="Enter subject description, objectives, or additional information..."><?php echo e(old('description')); ?></textarea>
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
                                <div class="form-text">Provide a brief description of the subject content and objectives</div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="<?php echo e(route('admin.subjects')); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Subject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Auto-generate subject code based on subject name
    $('#name').on('input', function() {
        const name = $(this).val();
        const codeField = $('#code');

        if (name && !codeField.val()) {
            // Generate a simple code from the name
            let code = name.toUpperCase()
                          .replace(/[^A-Z0-9\s]/g, '')
                          .split(' ')
                          .map(word => word.substring(0, 3))
                          .join('');

            // Limit to 8 characters
            code = code.substring(0, 8);

            codeField.val(code);
        }
    });

    // Update strand options based on track selection
    $('#track').on('change', function() {
        const track = $(this).val();
        const strandSelect = $('#strand');

        // Clear current options
        strandSelect.html('<option value="">Select Strand (Optional)</option>');

        if (track === 'Academic Track') {
            strandSelect.append(`
                <option value="HUMSS">HUMSS (Humanities and Social Sciences)</option>
                <option value="STEM">STEM (Science, Technology, Engineering and Mathematics)</option>
                <option value="ABM">ABM (Accountancy, Business and Management)</option>
                <option value="GAS">GAS (General Academic Strand)</option>
            `);
        } else if (track === 'Technical-Vocational-Livelihood Track') {
            strandSelect.append(`
                <option value="TVL-ICT">TVL-ICT (Information and Communications Technology)</option>
                <option value="TVL-HE">TVL-HE (Home Economics)</option>
                <option value="TVL-AFA">TVL-AFA (Agri-Fishery Arts)</option>
            `);
        }
    });
});
</script>

<style>
/* Form Section Styling */
.form-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    border-left: 4px solid #2563eb;
}

.section-title {
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.form-label {
    color: #374151;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 0.75rem;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-text {
    color: #6b7280;
    font-size: 0.875rem;
}

.card {
    border: none;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border-radius: 12px;
}

.card-header {
    background: white;
    border-bottom: 1px solid #e5e7eb;
    border-radius: 12px 12px 0 0;
}

.card-body {
    padding: 2rem;
}

.btn {
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .form-section {
        padding: 1rem;
    }

    .card-body {
        padding: 1.5rem;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Test - Copy\resources\views/admin/subjects/create.blade.php ENDPATH**/ ?>