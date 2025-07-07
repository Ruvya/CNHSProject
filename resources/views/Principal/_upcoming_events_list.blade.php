<div class="list-group list-group-flush">
    @forelse($upcomingEvents as $event)
        <div class="list-group-item p-3 border-0">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="bg-primary text-white rounded text-center p-2" style="width: 50px;">
                        <div class="fw-bold">{{ \Carbon\Carbon::parse($event->start)->format('d') }}</div>
                        <small>{{ \Carbon\Carbon::parse($event->start)->format('M') }}</small>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">{{ $event->title ?? 'No Title' }}</h6>
                    <small class="text-muted">{{ Str::limit($event->description ?? '', 50) ?? 'No description' }}</small>
                </div>
            </div>
        </div>
    @empty
        <div class="list-group-item p-3 border-0 text-center">
            <div class="text-muted">
                <i class="fas fa-calendar-alt fa-2x mb-2 opacity-50"></i>
                <p class="mb-0">No upcoming events scheduled.</p>
            </div>
        </div>
    @endforelse
</div> 