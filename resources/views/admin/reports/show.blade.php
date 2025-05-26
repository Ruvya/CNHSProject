@extends('layouts.admin')

@section('title', 'Report Results')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">
            <i class="fas fa-chart-bar me-2 text-primary"></i>
            {{ ucwords(str_replace('_', ' ', $reportType)) }} Report
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary">
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
    @if(!empty(array_filter($filters)))
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-filter me-2 text-info"></i>
                Applied Filters
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($filters as $key => $value)
                    @if(!empty($value))
                    <div class="col-md-3 mb-2">
                        <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                        <span class="badge bg-primary">{{ $value }}</span>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Summary Statistics -->
    @if(!empty($summary))
    <div class="row mb-4">
        @foreach($summary as $key => $value)
            @if(is_numeric($value))
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary">{{ number_format($value) }}</h3>
                        <p class="text-muted mb-0">{{ ucwords(str_replace('_', ' ', $key)) }}</p>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
    @endif

    <!-- Charts Section -->
    @if(!empty($chartData))
    <div class="row mb-4">
        @foreach($chartData as $chartKey => $chartValues)
            @if(is_array($chartValues) && !empty($chartValues))
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">{{ ucwords(str_replace('_', ' ', $chartKey)) }}</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chart_{{ $chartKey }}" height="200"></canvas>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
    @endif

    <!-- Data Table -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-table me-2 text-success"></i>
                Report Data
                @if(method_exists($data, 'total'))
                    <span class="badge bg-info ms-2">{{ $data->total() }} records</span>
                @elseif(is_countable($data))
                    <span class="badge bg-info ms-2">{{ count($data) }} records</span>
                @endif
            </h6>
        </div>
        <div class="card-body">
            @if($data && (method_exists($data, 'count') ? $data->count() > 0 : count($data) > 0))
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                @if($reportType === 'student_enrollment')
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Grade Level</th>
                                    <th>Track</th>
                                    <th>Strand</th>
                                    <th>Enrollment Date</th>
                                @elseif($reportType === 'teacher_assignment')
                                    <th>Teacher Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Assigned Subjects</th>
                                    <th>Contact</th>
                                @elseif($reportType === 'grades_report')
                                    <th>Student</th>
                                    <th>Subject</th>
                                    <th>Q1</th>
                                    <th>Q2</th>
                                    <th>Q3</th>
                                    <th>Q4</th>
                                    <th>Final Grade</th>
                                    <th>Remarks</th>
                                @elseif($reportType === 'user_activity')
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Last Activity</th>
                                    <th>Registration Date</th>
                                @elseif($reportType === 'monthly_registration')
                                    <th>Month</th>
                                    <th>Students</th>
                                    <th>Teachers</th>
                                    <th>Total</th>
                                @else
                                    <th>Information</th>
                                    <th>Details</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                                <tr>
                                    @if($reportType === 'student_enrollment')
                                        <td>{{ $item->student_id ?? 'N/A' }}</td>
                                        <td>{{ $item->full_name ?? $item->first_name . ' ' . $item->last_name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->grade_level ?? 'N/A' }}</td>
                                        <td>{{ $item->track ?? 'N/A' }}</td>
                                        <td>{{ $item->strand ?? 'N/A' }}</td>
                                        <td>{{ $item->created_at ? $item->created_at->format('M d, Y') : 'N/A' }}</td>
                                    @elseif($reportType === 'teacher_assignment')
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->subjects && $item->subjects->count() > 0)
                                                @foreach($item->subjects as $subject)
                                                    <span class="badge bg-primary me-1">{{ $subject->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">No subjects assigned</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->contact_number ?? 'N/A' }}</td>
                                    @elseif($reportType === 'grades_report')
                                        <td>{{ $item->student ? $item->student->full_name : 'N/A' }}</td>
                                        <td>{{ $item->subject ? $item->subject->name : 'N/A' }}</td>
                                        <td>{{ $item->quarter1 ?? '-' }}</td>
                                        <td>{{ $item->quarter2 ?? '-' }}</td>
                                        <td>{{ $item->quarter3 ?? '-' }}</td>
                                        <td>{{ $item->quarter4 ?? '-' }}</td>
                                        <td>
                                            @if($item->final_grade)
                                                <span class="badge bg-{{ $item->final_grade >= 75 ? 'success' : 'danger' }}">
                                                    {{ $item->final_grade }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $item->remarks ?? '-' }}</td>
                                    @elseif($reportType === 'user_activity')
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item->role === 'Admin' ? 'danger' : ($item->role === 'Teacher' ? 'success' : 'primary') }}">
                                                {{ $item->role }}
                                            </span>
                                        </td>
                                        <td>{{ $item->last_activity ? $item->last_activity->diffForHumans() : 'N/A' }}</td>
                                        <td>{{ $item->registration_date ? $item->registration_date->format('M d, Y') : 'N/A' }}</td>
                                    @elseif($reportType === 'monthly_registration')
                                        <td>{{ $item['month'] ?? $item->month }}</td>
                                        <td>{{ $item['students'] ?? $item->students }}</td>
                                        <td>{{ $item['teachers'] ?? $item->teachers }}</td>
                                        <td>{{ $item['total'] ?? $item->total }}</td>
                                    @else
                                        <td>{{ $item->message ?? $item->title ?? 'N/A' }}</td>
                                        <td>{{ $item->description ?? $item->details ?? 'N/A' }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(method_exists($data, 'links'))
                    <div class="d-flex justify-content-center mt-4">
                        {{ $data->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Data Found</h5>
                    <p class="text-muted">No records match the selected criteria. Try adjusting your filters.</p>
                    <a href="{{ route('admin.reports') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Reports
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
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
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js default configuration
    Chart.defaults.font.family = "'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif";
    Chart.defaults.color = '#858796';

    @if(!empty($chartData))
        @foreach($chartData as $chartKey => $chartValues)
            @if(is_array($chartValues) && !empty($chartValues))
                // Chart for {{ $chartKey }}
                const ctx_{{ str_replace(['-', '_'], '', $chartKey) }} = document.getElementById('chart_{{ $chartKey }}');
                if (ctx_{{ str_replace(['-', '_'], '', $chartKey) }}) {
                    @if(isset($chartValues['labels']))
                        // Line chart for trends
                        new Chart(ctx_{{ str_replace(['-', '_'], '', $chartKey) }}, {
                            type: 'line',
                            data: {
                                labels: {!! json_encode($chartValues['labels']) !!},
                                datasets: [
                                    @if(isset($chartValues['students']))
                                    {
                                        label: 'Students',
                                        data: {!! json_encode($chartValues['students']) !!},
                                        borderColor: '#4e73df',
                                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                                        borderWidth: 2,
                                        fill: true,
                                        tension: 0.3
                                    },
                                    @endif
                                    @if(isset($chartValues['teachers']))
                                    {
                                        label: 'Teachers',
                                        data: {!! json_encode($chartValues['teachers']) !!},
                                        borderColor: '#1cc88a',
                                        backgroundColor: 'rgba(28, 200, 138, 0.1)',
                                        borderWidth: 2,
                                        fill: true,
                                        tension: 0.3
                                    },
                                    @endif
                                    @if(isset($chartValues['total']))
                                    {
                                        label: 'Total',
                                        data: {!! json_encode($chartValues['total']) !!},
                                        borderColor: '#36b9cc',
                                        backgroundColor: 'rgba(54, 185, 204, 0.1)',
                                        borderWidth: 2,
                                        fill: false,
                                        tension: 0.3
                                    }
                                    @endif
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
                    @else
                        // Pie/Doughnut chart for distributions
                        new Chart(ctx_{{ str_replace(['-', '_'], '', $chartKey) }}, {
                            type: 'doughnut',
                            data: {
                                labels: {!! json_encode(array_keys($chartValues)) !!},
                                datasets: [{
                                    data: {!! json_encode(array_values($chartValues)) !!},
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
                    @endif
                }
            @endif
        @endforeach
    @endif
});

function downloadReport() {
    // Prepare download parameters
    const params = new URLSearchParams({
        report_type: '{{ $reportType }}',
        @foreach($filters as $key => $value)
            @if(!empty($value))
                {{ $key }}: '{{ $value }}',
            @endif
        @endforeach
    });

    // For now, show a message about download functionality
    alert('Download functionality will be implemented with CSV/PDF export capabilities.');

    // Future implementation:
    // window.location.href = '{{ route("admin.reports.download") }}?' + params.toString();
}
</script>
@endsection
