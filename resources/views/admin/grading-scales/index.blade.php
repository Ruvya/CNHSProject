@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Grading Scales</h4>
        <a href="{{ route('admin.grading-scales.create') }}" class="btn btn-primary">Add Grading Scale</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-chart-bar me-2 text-primary"></i>Students per Grading Scale</h5>
        </div>
        <div class="card-body">
            <canvas id="gradingScaleBarChart" height="120"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Grade Range</th>
                        <th>Description</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($scales as $scale)
                    <tr>
                        <td>{{ $scale->order }}</td>
                        <td>{{ $scale->min_grade }}&ndash;{{ $scale->max_grade }}</td>
                        <td>{{ $scale->description }}</td>
                        <td>{{ $scale->remarks }}</td>
                        <td>
                            <a href="{{ route('admin.grading-scales.edit', $scale) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.grading-scales.destroy', $scale) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this grading scale?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">No grading scales defined.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch("{{ route('admin.grading-scales.analytics') }}")
        .then(res => res.json())
        .then(res => {
            const data = res.data;
            const ctx = document.getElementById('gradingScaleBarChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Number of Students',
                        data: data.counts,
                        backgroundColor: '#3b82f6',
                        borderColor: '#2563eb',
                        borderWidth: 2,
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#374151',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#6b7280' },
                            grid: { color: '#f3f4f6' }
                        },
                        x: {
                            ticks: { color: '#6b7280' },
                            grid: { display: false }
                        }
                    }
                }
            });
        });
});
</script>
@endpush 