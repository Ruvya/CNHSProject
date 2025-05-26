

<?php $__env->startSection('title', 'Class List'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Class List</h2>
                    <p class="text-muted">Manage your students and their grades</p>
                </div>
                <div>
                    <span class="badge bg-primary fs-6"><?php echo e($students->count()); ?> Students</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter me-2"></i>Filter Students
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('teacher.classlist')); ?>">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="subject_id" class="form-label">Subject</label>
                                <select class="form-select" id="subject_id" name="subject_id">
                                    <option value="">All Subjects</option>
                                    <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($subject->id); ?>"
                                            <?php echo e(request('subject_id') == $subject->id ? 'selected' : ''); ?>>
                                            <?php echo e($subject->name); ?> (<?php echo e($subject->code); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="grade_level" class="form-label">Grade Level</label>
                                <select class="form-select" id="grade_level" name="grade_level">
                                    <option value="">All Grades</option>
                                    <?php $__currentLoopData = $gradeLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($grade); ?>"
                                            <?php echo e(request('grade_level') == $grade ? 'selected' : ''); ?>>
                                            Grade <?php echo e($grade); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="section" class="form-label">Section</label>
                                <select class="form-select" id="section" name="section">
                                    <option value="">All Sections</option>
                                    <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($section); ?>"
                                            <?php echo e(request('section') == $section ? 'selected' : ''); ?>>
                                            Section <?php echo e($section); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-1"></i>Filter
                                </button>
                                <a href="<?php echo e(route('teacher.classlist')); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Students
                        <?php if(request()->hasAny(['subject_id', 'grade_level', 'section'])): ?>
                            <small class="text-muted">(Filtered Results)</small>
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if($students->isEmpty()): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Students Found</h5>
                            <p class="text-muted">
                                <?php if(request()->hasAny(['subject_id', 'grade_level', 'section'])): ?>
                                    Try adjusting your filters to see more students.
                                <?php else: ?>
                                    No students are enrolled in your subjects yet.
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Grade & Section</th>
                                        <th>Enrolled Subjects</th>
                                        <th>Latest Grade</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $enrolledSubjects = $student->subjects->where('teacher_id', $teacher->id);
                                            $latestGrade = $student->grades->where('subject.teacher_id', $teacher->id)->sortByDesc('updated_at')->first();
                                        ?>
                                        <tr>
                                            <td>
                                                <span class="fw-bold"><?php echo e($student->student_id); ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <?php echo e(strtoupper(substr($student->first_name, 0, 1))); ?><?php echo e(strtoupper(substr($student->last_name, 0, 1))); ?>

                                                    </div>
                                                    <div>
                                                        <div class="fw-bold"><?php echo e($student->full_name); ?></div>
                                                        <small class="text-muted"><?php echo e($student->email); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">Grade <?php echo e($student->grade_level); ?></span>
                                                <span class="badge bg-secondary">Section <?php echo e($student->section); ?></span>
                                            </td>
                                            <td>
                                                <?php if($enrolledSubjects->count() > 0): ?>
                                                    <?php $__currentLoopData = $enrolledSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="badge bg-light text-dark me-1"><?php echo e($subject->code); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">None</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($latestGrade): ?>
                                                    <span class="badge <?php echo e($latestGrade->final_grade >= 75 ? 'bg-success' : 'bg-warning'); ?>">
                                                        <?php echo e(number_format($latestGrade->final_grade, 1)); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">No grades</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('teacher.students.show', $student->id)); ?>"
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="View Profile">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if($enrolledSubjects->count() > 0): ?>
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-sm btn-outline-success dropdown-toggle"
                                                                    data-bs-toggle="dropdown" title="Manage Grades">
                                                                <i class="fas fa-star"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <?php $__currentLoopData = $enrolledSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                           href="<?php echo e(route('teacher.grades.edit', [$student->id, $subject->id])); ?>">
                                                                            <?php echo e($subject->name); ?>

                                                                        </a>
                                                                    </li>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </ul>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 0.75rem;
    font-weight: 600;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.btn-group .dropdown-toggle::after {
    margin-left: 0.255em;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// Fetch subjects based on grade level
$('#gradeLevel').on('change', function() {
    const gradeLevel = $(this).val();
    $('#subject').html('<option value="">Loading...</option>');
    if (gradeLevel) {
        fetch(`/teacher/get-subjects?gradeLevel=${gradeLevel}`)
            .then(res => res.json())
            .then(data => {
                let options = '<option value="">Select Subject</option>';
                data.forEach(subject => {
                    options += `<option value="${subject.id}">${subject.name}</option>`;
                });
                $('#subject').html(options);
            });
    } else {
        $('#subject').html('<option value="">Select Subject</option>');
    }
});

// Fetch students based on filters
$('#viewStudentsBtn').on('click', function(e) {
    e.preventDefault();
    const gradeLevel = $('#gradeLevel').val();
    const subjectId = $('#subject').val();
    const section = $('#section').val();
    if (!gradeLevel || !subjectId || !section) {
        $('#studentList').html('<div class="alert alert-warning">Please select all filters.</div>');
        return;
    }
    $('#studentList').html('<div class="text-center text-muted">Loading students...</div>');
    fetch(`/teacher/get-students?gradeLevel=${gradeLevel}&subjectId=${subjectId}&section=${section}`)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                $('#studentList').html('<div class="alert alert-info">No students found for the selected filters.</div>');
                return;
            }
            let table = `<div class="table-responsive"><table class="table table-bordered table-students"><thead><tr><th>#</th><th>Name</th><th>Student ID</th><th>Section</th><th>Gender</th></tr></thead><tbody>`;
            data.forEach((student, idx) => {
                table += `<tr><td>${idx+1}</td><td>${student.name}</td><td>${student.student_id}</td><td>${student.section}</td><td>${student.gender}</td></tr>`;
            });
            table += '</tbody></table></div>';
            $('#studentList').html(table);
        });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Test - Copy\resources\views/teacher/classlist.blade.php ENDPATH**/ ?>