@extends('layouts.admin')

@section('content')
<div class="container">
    <!-- Header (match User Management style) -->
    <div class="card mb-4" style="border-radius: 14px; box-shadow: 0 8px 25px rgba(30,58,138,0.12); border: none;">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between" style="padding: 1.2rem 1.5rem;">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <span style="font-size: 2rem; color: #2563eb; background: #f1f5fb; border-radius: 12px; padding: 0.7rem; margin-right: 1rem; display: flex; align-items: center;">
                    <i class="fas fa-layer-group"></i>
                </span>
                <div>
                    <div style="font-size: 1.3rem; font-weight: bold; font-family: 'Poppins', sans-serif; color: #222;">Sections</div>
                    <div style="font-size: 0.95rem; font-family: 'Poppins', sans-serif; color: #555; font-weight: 500;">Manage sections grouped by strand</div>
                </div>
            </div>
            <div class="mb-2 mb-md-0">
                <button type="button" class="btn btn-outline-secondary me-2" data-bs-toggle="modal" data-bs-target="#reportModal">
                    <i class="fas fa-chart-bar me-2"></i>Report
                </button>
                <a href="{{ route('admin.sections.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Section
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @include('admin.partials.semester-notice')

    @forelse($sections as $strand => $group)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>{{ $strand }}</strong>
                <span class="text-muted">{{ $group->count() }} sections</span>
            </div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Grade Level</th>
                            <th>Track</th>
                            <th>Adviser</th>
                            <th>Capacity</th>
                            <th>Enrolled</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group as $section)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.sections.show', $section) }}">{{ $section->name }}</a>
                                </td>
                                <td>{{ $section->grade_level }}</td>
                                <td>{{ $section->strand ?: $section->track }}</td>
                                <td>{{ optional($section->adviser)->name ?: '—' }}</td>
                                <td>{{ $section->max_capacity }}</td>
                                <td>{{ $section->current_enrollment }}</td>
                                <td>
                                    <span class="badge {{ $section->status === 'active' ? 'bg-success' : ($section->status === 'full' ? 'bg-warning text-dark' : 'bg-secondary') }}">{{ ucfirst($section->status) }}</span>
                                </td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('admin.sections.show', $section) }}" class="btn btn-sm btn-outline-success">Manage</a>
                                    <a href="{{ route('admin.sections.edit', $section) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form method="POST" action="{{ route('admin.sections.toggle-status', $section) }}" onsubmit="return confirm('Toggle status for this section?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">{{ $section->status === 'inactive' ? 'Activate' : 'Deactivate' }}</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.sections.hard-delete', $section) }}" onsubmit="return confirm('Permanently delete this section? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="alert alert-info">No sections found.</div>
    @endforelse
</div>

<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">
                    <i class="fas fa-chart-bar me-2 text-info"></i>
                    Sections Report by Strand
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Strand</th>
                                <th>Sections</th>
                                <th>Total Students</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData as $row)
                                <tr>
                                    <td><strong>{{ $row->strand_label }}</strong></td>
                                    <td>
                                        <span class="badge bg-primary">{{ $row->sections_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $row->total_students }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        <i class="fas fa-chart-bar fa-2x mb-2 d-block"></i>
                                        No data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('admin.sections.report.per-strand') }}" class="btn btn-primary" target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i>View Full Report
                </a>
            </div>
        </div>
    </div>
</div>
@endsection


