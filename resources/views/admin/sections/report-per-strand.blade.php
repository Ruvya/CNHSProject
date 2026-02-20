@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Sections Report by Strand</h1>
        <a href="{{ route('admin.sections.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

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
                @forelse($report as $row)
                    <tr>
                        <td>{{ $row->strand_label }}</td>
                        <td>{{ $row->sections_count }}</td>
                        <td>{{ $row->total_students }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">No data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection


