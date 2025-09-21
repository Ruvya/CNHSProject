@extends('layouts.admin')

@section('content')
<div class="container mt-4">
	<h1>School Year Management</h1>

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
		<div class="card-header">School Years</div>
		<div class="card-body">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Name</th>
						<th>Years</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach($years as $year)
					<tr>
						<td>{{ $year->name }}</td>
						<td>{{ $year->start_year }} - {{ $year->end_year }}</td>
						<td>
							<span class="badge bg-{{ $year->status === 'active' ? 'success' : ($year->status === 'closed' ? 'secondary' : 'dark') }}">{{ ucfirst($year->status) }}</span>
						</td>
						<td>
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
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection


