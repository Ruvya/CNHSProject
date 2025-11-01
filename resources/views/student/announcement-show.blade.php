@extends('layouts.student')

@section('title', 'Announcement')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-3" style="border-radius: 14px; border: none;">
        <div class="card-body" style="padding: 1.25rem 1.5rem;">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <h3 class="mb-1" style="font-weight: 800; color: #1f2937;">{{ $announcement->title }}</h3>
                    <div class="text-muted" style="font-weight: 500;">
                        {{ optional($announcement->author)->name ?? 'Announcement' }} • {{ $announcement->created_at->format('M d, Y g:i A') }}
                    </div>
                </div>
                <a href="{{ route('student.announcements') }}" class="btn btn-outline-secondary btn-sm" style="height: 36px;">Back to Announcements</a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm" style="border-radius: 14px; border: none;">
        <div class="card-body" style="padding: 1.25rem 1.5rem;">
            <div style="white-space: pre-wrap; font-size: 1.05rem; line-height: 1.75; color: #334155;">{!! nl2br(e($announcement->content)) !!}</div>
        </div>
    </div>
</div>
@endsection


