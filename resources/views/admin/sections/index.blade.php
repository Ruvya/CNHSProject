@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Sections by Strand</h1>
        <div>
            <a href="{{ route('admin.sections.report.per-strand') }}" class="btn btn-outline-secondary">Report</a>
            <a href="{{ route('admin.sections.create') }}" class="btn btn-primary">Create Section</a>
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
                                    <form method="POST" action="{{ route('admin.sections.destroy', $section) }}" onsubmit="return confirm('Deactivate this section? This is a soft action.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Deactivate</button>
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
@endsection


