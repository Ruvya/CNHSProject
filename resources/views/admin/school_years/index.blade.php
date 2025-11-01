@extends('layouts.admin')

@section('content')
<div class="container mt-4">
	<!-- Header (match User Management style) -->
	<div class="card mb-4" style="border-radius: 14px; box-shadow: 0 8px 25px rgba(30,58,138,0.12); border: none;">
		<div class="card-body d-flex flex-wrap align-items-center justify-content-between" style="padding: 1.2rem 1.5rem;">
			<div class="d-flex align-items-center mb-2 mb-md-0">
				<span style="font-size: 2rem; color: #2563eb; background: #f1f5fb; border-radius: 12px; padding: 0.7rem; margin-right: 1rem; display: flex; align-items: center;">
					<i class="fas fa-calendar"></i>
				</span>
				<div>
					<div style="font-size: 1.3rem; font-weight: bold; font-family: 'Poppins', sans-serif; color: #222;">School Year Management</div>
					<div style="font-size: 0.95rem; font-family: 'Poppins', sans-serif; color: #555; font-weight: 500;">Create, activate, close, and archive school years</div>
				</div>
			</div>
		</div>
	</div>

	@if(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif
	@if($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<div class="card mb-4">
		<div class="card-header">Create New School Year</div>
		<div class="card-body">
			<form method="POST" action="{{ route('admin.school-years.store') }}">
				@csrf
				<div class="row g-3 align-items-end">
					<div class="col-md-3">
						<label class="form-label">Start Year</label>
						<input type="number" name="start_year" class="form-control" min="2000" max="3000" required>
					</div>
					<div class="col-md-3">
						<label class="form-label">End Year</label>
						<input type="number" name="end_year" class="form-control" min="2000" max="3000" required>
					</div>
					<div class="col-md-4">
						<label class="form-label">Name</label>
						<input type="text" name="name" class="form-control" placeholder="e.g., 2025-2026" required>
					</div>
					<div class="col-md-2 form-check mt-4">
						<input type="checkbox" name="activate" id="activate" value="1" class="form-check-input">
						<label for="activate" class="form-check-label">Set Active</label>
					</div>
				</div>
				<div class="mt-3">
					<button class="btn btn-primary">Create</button>
				</div>
			</form>
		</div>
	</div>

	<div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>School Years</span>
            <div class="d-flex align-items-center gap-2">
                @if($active)
                    <span class="badge bg-success me-2">Active: {{ $active->name }}</span>
                    <form id="promoteForm" method="POST" action="{{ route('admin.school-years.promote', $active) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="activate_next" value="1">
                        <button type="button" id="promoteBtn" class="btn btn-sm btn-danger">
                            Promote Eligible Students
                        </button>
                    </form>
                @endif
            </div>
        </div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Name</th>
							<th>Years</th>
							<th>Status</th>
							<th>Created</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach($years as $year)
						<tr class="{{ $year->status === 'active' ? 'table-success' : '' }}">
							<td>
								<strong>{{ $year->name }}</strong>
								@if($year->status === 'active')
									<span class="badge bg-success ms-2">Current</span>
								@endif
							</td>
							<td>{{ $year->start_year }} - {{ $year->end_year }}</td>
							<td>
								<span class="badge bg-{{ $year->status === 'active' ? 'success' : ($year->status === 'closed' ? 'secondary' : 'dark') }}">{{ ucfirst($year->status) }}</span>
							</td>
							<td>{{ $year->created_at->format('M d, Y') }}</td>
							<td>
								<div class="btn-group" role="group">
									<a href="{{ route('admin.school-years.show', $year) }}" class="btn btn-sm btn-primary">View</a>
									@if($year->status !== 'active')
										<form method="POST" action="{{ route('admin.school-years.activate', $year) }}" class="d-inline">
											@csrf
											<button class="btn btn-sm btn-success">Activate</button>
										</form>
									@endif
									@if($year->status === 'active' || $year->status === 'closed')
										<form method="POST" action="{{ route('admin.school-years.close', $year) }}" class="d-inline">
											@csrf
											<button class="btn btn-sm btn-warning">Close</button>
										</form>
									@endif
                                    @if($year->status !== 'archived')
										<form method="POST" action="{{ route('admin.school-years.archive', $year) }}" class="d-inline">
											@csrf
											<button class="btn btn-sm btn-outline-dark">Archive</button>
										</form>
									@endif
									@if($year->status === 'archived')
										<form method="POST" action="{{ route('admin.school-years.reopen', $year) }}" class="d-inline">
											@csrf
											<button class="btn btn-sm btn-secondary">Reopen</button>
										</form>
									@endif
                                    @if($year->status !== 'active')
                                        <form method="POST" action="{{ route('admin.school-years.destroy', $year) }}" class="d-inline delete-year-form">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="force" value="0">
                                            <button type="button" class="btn btn-sm btn-danger btn-delete-year">Delete</button>
                                        </form>
                                    @endif
								</div>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

    @if($active)
    <div class="card mt-3">
        <div class="card-header">
            Promotion Advanced Options (Optional)
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Dropped student IDs (comma-separated)</label>
                    <textarea form="promoteForm" name="dropped_ids" class="form-control" rows="2" placeholder="e.g., 12,45,87"></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Transferred student IDs (comma-separated)</label>
                    <textarea form="promoteForm" name="transferred_ids" class="form-control" rows="2" placeholder="e.g., 101,205"></textarea>
                </div>
            </div>
            <small class="text-muted d-block mt-2">If provided, these students will be marked accordingly for the next school year during promotion.</small>
        </div>
    </div>
    @endif
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('promoteBtn');
    if (!btn) return;
    btn.addEventListener('click', function() {
        Swal.fire({
            title: 'Confirm Promotion',
            text: 'Are you sure you want to promote all eligible students based on their grades? Only students who passed will be promoted. This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('promoteForm').submit();
            }
        });
    });

    document.querySelectorAll('.btn-delete-year').forEach(function(delBtn){
        delBtn.addEventListener('click', function(){
            const form = this.closest('.delete-year-form');
            Swal.fire({
                title: 'Delete School Year?',
                text: 'This will permanently remove the school year and all associated records (sections, schedules, yearly records). This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete everything',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    const forceInput = form.querySelector('input[name="force"]');
                    if (forceInput) forceInput.value = '1';
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
