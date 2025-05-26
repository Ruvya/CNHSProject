<?php $__env->startSection('title', 'Report Results'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">
            <i class="fas fa-chart-bar me-2 text-primary"></i>
            <?php echo e(ucwords(str_replace('_', ' ', $reportType))); ?> Report
        </h1>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('admin.reports')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Reports
            </a>
            <button class="btn btn-success" onclick="downloadReport()">
                <i class="fas fa-download me-2"></i>
                Download Report
            </button>
        </div>
    </div>

    <!-- Applied Filters -->
    <?php if(!empty(array_filter($filters))): ?>
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-filter me-2 text-info"></i>
                Applied Filters
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($value)): ?>
                    <div class="col-md-3 mb-2">
                        <strong><?php echo e(ucwords(str_replace('_', ' ', $key))); ?>:</strong>
                        <span class="badge bg-primary"><?php echo e($value); ?></span>
                    </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Summary Statistics -->
    <?php if(!empty($summary)): ?>
    <div class="row mb-4">
        <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(is_numeric($value)): ?>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary"><?php echo e(number_format($value)); ?></h3>
                        <p class="text-muted mb-0"><?php echo e(ucwords(str_replace('_', ' ', $key))); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <!-- Charts Section -->
    <?php if(!empty($chartData)): ?>
    <div class="row mb-4">
        <?php $__currentLoopData = $chartData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chartKey => $chartValues): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(is_array($chartValues) && !empty($chartValues)): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><?php echo e(ucwords(str_replace('_', ' ', $chartKey))); ?></h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chart_<?php echo e($chartKey); ?>" height="200"></canvas>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <!-- Data Table -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-table me-2 text-success"></i>
                Report Data
                <?php if(method_exists($data, 'total')): ?>
                    <span class="badge bg-info ms-2"><?php echo e($data->total()); ?> records</span>
                <?php elseif(is_countable($data)): ?>
                    <span class="badge bg-info ms-2"><?php echo e(count($data)); ?> records</span>
                <?php endif; ?>
            </h6>
        </div>
        <div class="card-body">
            <?php if($data && (method_exists($data, 'count') ? $data->count() > 0 : count($data) > 0)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <?php if($reportType === 'student_enrollment'): ?>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Grade Level</th>
                                    <th>Track</th>
                                    <th>Strand</th>
                                    <th>Enrollment Date</th>
                                <?php elseif($reportType === 'teacher_assignment'): ?>
                                    <th>Teacher Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Assigned Subjects</th>
                                    <th>Contact</th>
                                <?php elseif($reportType === 'grades_report'): ?>
                                    <th>Student</th>
                                    <th>Subject</th>
                                    <th>Q1</th>
                                    <th>Q2</th>
                                    <th>Q3</th>
                                    <th>Q4</th>
                                    <th>Final Grade</th>
                                    <th>Remarks</th>
                                <?php elseif($reportType === 'user_activity'): ?>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Last Activity</th>
                                    <th>Registration Date</th>
                                <?php elseif($reportType === 'monthly_registration'): ?>
                                    <th>Month</th>
                                    <th>Students</th>
                                    <th>Teachers</th>
                                    <th>Total</th>
                                <?php else: ?>
                                    <th>Information</th>
                                    <th>Details</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <?php if($reportType === 'student_enrollment'): ?>
                                        <td><?php echo e($item->student_id ?? 'N/A'); ?></td>
                                        <td><?php echo e($item->full_name ?? $item->first_name . ' ' . $item->last_name); ?></td>
                                        <td><?php echo e($item->email); ?></td>
                                        <td><?php echo e($item->grade_level ?? 'N/A'); ?></td>
                                        <td><?php echo e($item->track ?? 'N/A'); ?></td>
                                        <td><?php echo e($item->strand ?? 'N/A'); ?></td>
                                        <td><?php echo e($item->created_at ? $item->created_at->format('M d, Y') : 'N/A'); ?></td>
                                    <?php elseif($reportType === 'teacher_assignment'): ?>
                                        <td><?php echo e($item->name); ?></td>
                                        <td><?php echo e($item->email); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($item->status === 'active' ? 'success' : 'secondary'); ?>">
                                                <?php echo e(ucfirst($item->status)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php if($item->subjects && $item->subjects->count() > 0): ?>
                                                <?php $__currentLoopData = $item->subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge bg-primary me-1"><?php echo e($subject->name); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <span class="text-muted">No subjects assigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($item->contact_number ?? 'N/A'); ?></td>
                                    <?php elseif($reportType === 'grades_report'): ?>
                                        <td><?php echo e($item->student ? $item->student->full_name : 'N/A'); ?></td>
                                        <td><?php echo e($item->subject ? $item->subject->name : 'N/A'); ?></td>
                                        <td><?php echo e($item->quarter1 ?? '-'); ?></td>
                                        <td><?php echo e($item->quarter2 ?? '-'); ?></td>
                                        <td><?php echo e($item->quarter3 ?? '-'); ?></td>
                                        <td><?php echo e($item->quarter4 ?? '-'); ?></td>
                                        <td>
                                            <?php if($item->final_grade): ?>
                                                <span class="badge bg-<?php echo e($item->final_grade >= 75 ? 'success' : 'danger'); ?>">
                                                    <?php echo e($item->final_grade); ?>

                                                </span>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($item->remarks ?? '-'); ?></td>
                                    <?php elseif($reportType === 'user_activity'): ?>
                                        <td><?php echo e($item->name); ?></td>
                                        <td><?php echo e($item->email); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($item->role === 'Admin' ? 'danger' : ($item->role === 'Teacher' ? 'success' : 'primary')); ?>">
                                                <?php echo e($item->role); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($item->last_activity ? $item->last_activity->diffForHumans() : 'N/A'); ?></td>
                                        <td><?php echo e($item->registration_date ? $item->registration_date->format('M d, Y') : 'N/A'); ?></td>
                                    <?php elseif($reportType === 'monthly_registration'): ?>
                                        <td><?php echo e($item['month'] ?? $item->month); ?></td>
                                        <td><?php echo e($item['students'] ?? $item->students); ?></td>
                                        <td><?php echo e($item['teachers'] ?? $item->teachers); ?></td>
                                        <td><?php echo e($item['total'] ?? $item->total); ?></td>
                                    <?php else: ?>
                                        <td><?php echo e($item->message ?? $item->title ?? 'N/A'); ?></td>
                                        <td><?php echo e($item->description ?? $item->details ?? 'N/A'); ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if(method_exists($data, 'links')): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($data->appends(request()->query())->links()); ?>

                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Data Found</h5>
                    <p class="text-muted">No records match the selected criteria. Try adjusting your filters.</p>
                    <a href="<?php echo e(route('admin.reports')); ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Reports
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
.table th {
    background-color: #343a40;
    color: white;
    font-weight: 600;
    border: none;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.02);
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
}

.badge {
    font-size: 0.75rem;
}

.card-header h6 {
    color: #495057;
}

.chart-container {
    position: relative;
    height: 200px;
    width: 100%;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js default configuration
    Chart.defaults.font.family = "'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif";
    Chart.defaults.color = '#858796';

    <?php if(!empty($chartData)): ?>
        <?php $__currentLoopData = $chartData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chartKey => $chartValues): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(is_array($chartValues) && !empty($chartValues)): ?>
                // Chart for <?php echo e($chartKey); ?>

                const ctx_<?php echo e(str_replace(['-', '_'], '', $chartKey)); ?> = document.getElementById('chart_<?php echo e($chartKey); ?>');
                if (ctx_<?php echo e(str_replace(['-', '_'], '', $chartKey)); ?>) {
                    <?php if(isset($chartValues['labels'])): ?>
                        // Line chart for trends
                        new Chart(ctx_<?php echo e(str_replace(['-', '_'], '', $chartKey)); ?>, {
                            type: 'line',
                            data: {
                                labels: <?php echo json_encode($chartValues['labels']); ?>,
                                datasets: [
                                    <?php if(isset($chartValues['students'])): ?>
                                    {
                                        label: 'Students',
                                        data: <?php echo json_encode($chartValues['students']); ?>,
                                        borderColor: '#4e73df',
                                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                                        borderWidth: 2,
                                        fill: true,
                                        tension: 0.3
                                    },
                                    <?php endif; ?>
                                    <?php if(isset($chartValues['teachers'])): ?>
                                    {
                                        label: 'Teachers',
                                        data: <?php echo json_encode($chartValues['teachers']); ?>,
                                        borderColor: '#1cc88a',
                                        backgroundColor: 'rgba(28, 200, 138, 0.1)',
                                        borderWidth: 2,
                                        fill: true,
                                        tension: 0.3
                                    },
                                    <?php endif; ?>
                                    <?php if(isset($chartValues['total'])): ?>
                                    {
                                        label: 'Total',
                                        data: <?php echo json_encode($chartValues['total']); ?>,
                                        borderColor: '#36b9cc',
                                        backgroundColor: 'rgba(54, 185, 204, 0.1)',
                                        borderWidth: 2,
                                        fill: false,
                                        tension: 0.3
                                    }
                                    <?php endif; ?>
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    <?php else: ?>
                        // Pie/Doughnut chart for distributions
                        new Chart(ctx_<?php echo e(str_replace(['-', '_'], '', $chartKey)); ?>, {
                            type: 'doughnut',
                            data: {
                                labels: <?php echo json_encode(array_keys($chartValues)); ?>,
                                datasets: [{
                                    data: <?php echo json_encode(array_values($chartValues)); ?>,
                                    backgroundColor: [
                                        '#4e73df',
                                        '#1cc88a',
                                        '#36b9cc',
                                        '#f6c23e',
                                        '#e74a3b',
                                        '#858796',
                                        '#6f42c1',
                                        '#20c997'
                                    ],
                                    borderColor: '#ffffff',
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            padding: 20,
                                            usePointStyle: true
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    <?php endif; ?>
                }
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
});

function downloadReport() {
    // Prepare download parameters
    const params = new URLSearchParams({
        report_type: '<?php echo e($reportType); ?>',
        <?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(!empty($value)): ?>
                <?php echo e($key); ?>: '<?php echo e($value); ?>',
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    });

    // For now, show a message about download functionality
    alert('Download functionality will be implemented with CSV/PDF export capabilities.');

    // Future implementation:
    // window.location.href = '<?php echo e(route("admin.reports.download")); ?>?' + params.toString();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Test - Copy\resources\views/admin/reports/show.blade.php ENDPATH**/ ?>