@extends('layouts.teacher')

@section('content')
<div class="container">
    <div class="card mb-4 border-0" style="background: linear-gradient(90deg, #0ea5e9 0%, #012970 70%); color: #fff;">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="me-3" style="width: 46px; height: 46px; background: rgba(255,255,255,.15); border-radius: 10px; display: grid; place-items: center;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <h3 class="mb-0" style="font-weight: 700;">My Sections</h3>
                    <small class="opacity-75">View sections you advise and their students</small>
                </div>
            </div>
            <span class="badge bg-light text-dark" style="font-size: .9rem; padding:.6rem 1rem;">
                {{ $sections->count() }} Section{{ $sections->count() === 1 ? '' : 's' }}
            </span>
        </div>
    </div>

    @if($sections->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mb-2"><i class="fas fa-info-circle text-primary"></i></div>
                <h5 class="mb-1">No Sections Assigned</h5>
                <p class="text-muted mb-0">You are not assigned as adviser to any section.</p>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:28%">Section</th>
                            <th>Grade</th>
                            <th>Track / Strand</th>
                            <th>School Year</th>
                            <th>Students</th>
                            <th class="text-end" style="width:120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sections as $section)
                            @php($count = $section->students()->count())
                            <tr>
                                <td class="fw-semibold">{{ $section->name }}</td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary" style="font-weight:600;">
                                        Grade {{ $section->grade_level }}
                                    </span>
                                </td>
                                <td>{{ $section->track }} {{ $section->strand ? ' / '.$section->strand : '' }}</td>
                                <td>{{ $section->school_year }}</td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark">{{ $count }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('teacher.sections.show', $section) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-users me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection


