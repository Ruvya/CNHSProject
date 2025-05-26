

<?php $__env->startSection('title', 'My Subjects'); ?>

<?php $__env->startSection('content'); ?>
<div class="subjects-container">
    <!-- Modern Header Section -->
    <div class="subjects-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="page-title">My Subjects</h1>
                <p class="page-subtitle">Subjects assigned to you by the Registrar</p>
            </div>
            <div class="header-badge">
                <div class="assignment-badge">
                    <span class="badge-number"><?php echo e($totalAssigned); ?></span>
                    <span class="badge-text">Subjects Assigned</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Clean Statistics Dashboard -->
    <div class="stats-dashboard">
        <div class="stats-grid">
            <div class="stat-card stat-primary">
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo e($totalAssigned); ?></div>
                    <div class="stat-label">Total Subjects</div>
                </div>
            </div>

            <div class="stat-card stat-success">
                <div class="stat-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo e($totalUnits); ?></div>
                    <div class="stat-label">Total Units</div>
                </div>
            </div>

            <div class="stat-card stat-info">
                <div class="stat-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo e($subjectsWithTeachers); ?></div>
                    <div class="stat-label">With Teachers</div>
                </div>
            </div>

            <div class="stat-card stat-warning">
                <div class="stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo e($student->grade_level); ?></div>
                    <div class="stat-label">Grade Level</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Subjects Section -->
    <div class="subjects-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-clipboard-list"></i>
                My Assigned Subjects
            </h2>
            <p class="section-subtitle">Click on any subject to view detailed information</p>
        </div>

        <?php if($assignedSubjects->isEmpty()): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3 class="empty-title">No Subjects Assigned Yet</h3>
                <p class="empty-description">
                    The Registrar hasn't assigned any subjects to you yet.
                    Please contact the Registrar's office for subject enrollment.
                </p>
                <a href="<?php echo e(route('student.dashboard')); ?>" class="btn-empty-action">
                    <i class="fas fa-home"></i>
                    Go to Dashboard
                </a>
            </div>
        <?php else: ?>
            <div class="subjects-grid">
                <?php $__currentLoopData = $assignedSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="subject-card" onclick="window.location.href='<?php echo e(route('student.subjects.show', $subject->id)); ?>'">
                        <div class="subject-header">
                            <div class="subject-icon-wrapper">
                                <i class="fas fa-book subject-icon"></i>
                            </div>
                            <div class="subject-status">
                                <?php if($subject->teacher): ?>
                                    <span class="status-badge status-active">Active</span>
                                <?php else: ?>
                                    <span class="status-badge status-pending">Pending</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="subject-content">
                            <h3 class="subject-name"><?php echo e($subject->name ?? $subject->subject_name); ?></h3>
                            <p class="subject-code"><?php echo e($subject->code ?? $subject->subject_code); ?></p>

                            <?php if($subject->description): ?>
                                <p class="subject-description">
                                    <?php echo e(Str::limit($subject->description, 100)); ?>

                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="subject-meta">
                            <div class="meta-item">
                                <i class="fas fa-calculator"></i>
                                <span><?php echo e($subject->units ?? 'N/A'); ?> Units</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-tag"></i>
                                <span><?php echo e($subject->strand ?? 'General'); ?></span>
                            </div>
                        </div>

                        <div class="subject-teacher">
                            <?php if($subject->teacher): ?>
                                <div class="teacher-info">
                                    <i class="fas fa-user-tie teacher-icon"></i>
                                    <div class="teacher-details">
                                        <span class="teacher-label">Teacher</span>
                                        <span class="teacher-name"><?php echo e($subject->teacher->name); ?></span>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="teacher-info teacher-pending">
                                    <i class="fas fa-user-clock teacher-icon"></i>
                                    <div class="teacher-details">
                                        <span class="teacher-label">Teacher</span>
                                        <span class="teacher-name">To Be Assigned</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="subject-action">
                            <button class="btn-view-details">
                                <i class="fas fa-arrow-right"></i>
                                View Details
                            </button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Admin Color Variables */
:root {
    --primary-color: #2563eb;
    --primary-dark: #1d4ed8;
    --secondary-color: #64748b;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #06b6d4;
    --dark-color: #1e293b;
    --light-color: #f8fafc;
    --border-radius: 12px;
    --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --box-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Modern Container Styling */
.subjects-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
    background-color: #f1f5f9;
    min-height: 100vh;
}

/* Header Section */
.subjects-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    border-radius: var(--border-radius);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--box-shadow-lg);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title {
    color: white;
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.page-subtitle {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.1rem;
    margin: 0.5rem 0 0 0;
    font-weight: 300;
}

.assignment-badge {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 1rem 1.5rem;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.badge-number {
    display: block;
    color: white;
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
}

.badge-text {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.9rem;
    font-weight: 500;
}

/* Statistics Dashboard */
.stats-dashboard {
    margin-bottom: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: var(--box-shadow);
    transition: transform 0.2s, box-shadow 0.2s;
    border-left: 4px solid var(--primary-color);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--box-shadow-lg);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-primary .stat-icon { background: var(--primary-color); }
.stat-success .stat-icon { background: var(--success-color); }
.stat-info .stat-icon { background: var(--info-color); }
.stat-warning .stat-icon { background: var(--warning-color); }

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark-color);
    line-height: 1;
}

.stat-label {
    color: var(--secondary-color);
    font-size: 0.9rem;
    font-weight: 500;
    margin-top: 0.25rem;
}

/* Section Header */
.section-header {
    text-align: center;
    margin-bottom: 2rem;
}

.section-title {
    color: var(--dark-color);
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.section-title i {
    color: var(--primary-color);
}

.section-subtitle {
    color: var(--secondary-color);
    font-size: 1.1rem;
    margin: 0;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}

.empty-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 2rem;
    background: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: white;
}

.empty-title {
    color: var(--dark-color);
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.empty-description {
    color: var(--secondary-color);
    font-size: 1.1rem;
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.btn-empty-action {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--primary-color);
    color: white;
    padding: 0.75rem 2rem;
    border-radius: var(--border-radius);
    text-decoration: none;
    font-weight: 500;
    transition: transform 0.2s;
}

.btn-empty-action:hover {
    transform: translateY(-1px);
    color: white;
    background: var(--primary-dark);
}

/* Subjects Grid */
.subjects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
}

/* Subject Cards */
.subject-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--box-shadow);
    transition: all 0.2s ease;
    cursor: pointer;
    border: none;
    position: relative;
    overflow: hidden;
}

.subject-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-color);
}

.subject-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--box-shadow-lg);
}

.subject-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.subject-icon-wrapper {
    width: 50px;
    height: 50px;
    background: var(--primary-color);
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
}

.subject-icon {
    color: white;
    font-size: 1.5rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-active {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
}

.status-pending {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
}

.subject-content {
    margin-bottom: 1rem;
}

.subject-name {
    color: var(--dark-color);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    line-height: 1.3;
}

.subject-code {
    color: var(--primary-color);
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.subject-description {
    color: var(--secondary-color);
    font-size: 0.9rem;
    line-height: 1.5;
    margin: 0;
}

.subject-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--secondary-color);
    font-size: 0.9rem;
}

.meta-item i {
    color: var(--primary-color);
}

.subject-teacher {
    background: var(--light-color);
    border-radius: var(--border-radius);
    padding: 1rem;
    margin-bottom: 1rem;
}

.teacher-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.teacher-icon {
    color: var(--success-color);
    font-size: 1.2rem;
}

.teacher-pending .teacher-icon {
    color: var(--warning-color);
}

.teacher-details {
    display: flex;
    flex-direction: column;
}

.teacher-label {
    color: var(--secondary-color);
    font-size: 0.8rem;
    font-weight: 500;
}

.teacher-name {
    color: var(--dark-color);
    font-weight: 600;
    font-size: 0.9rem;
}

.subject-action {
    text-align: center;
}

.btn-view-details {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: var(--border-radius);
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    cursor: pointer;
    width: 100%;
    justify-content: center;
}

.btn-view-details:hover {
    transform: translateY(-1px);
    background: var(--primary-dark);
    box-shadow: var(--box-shadow);
}

/* Responsive Design */
@media (max-width: 768px) {
    .subjects-container {
        padding: 1rem;
    }

    .page-title {
        font-size: 2rem;
    }

    .header-content {
        flex-direction: column;
        text-align: center;
    }

    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .subjects-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .subject-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
}

@media (max-width: 480px) {
    .stat-card {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
    }

    .stat-number {
        font-size: 1.5rem;
    }
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Test - Copy\resources\views/student/subjects.blade.php ENDPATH**/ ?>