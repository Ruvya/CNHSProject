@extends('layouts.registrar')

@section('content')
<div class="container mt-4">
	<h1>Registrar: School Years</h1>

	<div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>School Year</th>
							<th>Status</th>
							<th>Registrar Record</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach($years as $year)
						<tr>
							<td><strong>{{ $year->name }}</strong></td>
							<td><span class="badge bg-{{ $year->status === 'active' ? 'success' : ($year->status === 'closed' ? 'secondary' : 'dark') }}">{{ ucfirst($year->status) }}</span></td>
							<td>
								@if($registrarYears->has($year->name))
									<span class="text-success">Present</span>
								@else
									<span class="text-danger">Missing</span>
								@endif
							</td>
							<td>
								<a href="{{ route('admin.school-years.show', $year) }}" class="btn btn-sm btn-outline-primary">Open School Year</a>
								<a href="{{ route('admin.school-years.statistics', $year) }}" class="btn btn-sm btn-outline-info">View Stats</a>
								<a href="{{ route('registrar.yearly-records.index') }}" class="btn btn-sm btn-outline-secondary">Yearly Records</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection


