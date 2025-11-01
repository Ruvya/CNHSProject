@extends('layouts.admin')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="card mb-4" style="border-radius: 14px; box-shadow: 0 8px 25px rgba(30,58,138,0.12); border: none;">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between" style="padding: 1.2rem 1.5rem;">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <span style="font-size: 2rem; color: #2563eb; background: #f1f5fb; border-radius: 12px; padding: 0.7rem; margin-right: 1rem; display: flex; align-items: center;">
                    <i class="fas fa-sitemap"></i>
                </span>
                <div>
                    <div style="font-size: 1.3rem; font-weight: bold; font-family: 'Poppins', sans-serif; color: #222;">Tracks & Clusters</div>
                    <div style="font-size: 0.95rem; font-family: 'Poppins', sans-serif; color: #555; font-weight: 500;">Manage tracks and their clusters</div>
                </div>
            </div>
            <div class="mb-2 mb-md-0">
                <a href="{{ route('admin.tracks.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create New Track
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @forelse($tracks as $track)
        <div class="card mb-4" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e9ecef;">
            <!-- Track Header -->
            <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px 12px 0 0 !important; padding: 1rem 1.5rem;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-folder-open me-2" style="font-size: 1.2rem;"></i>
                    <div>
                        <h5 class="mb-0" style="font-weight: 600;">{{ $track->name }}</h5>
                        @if($track->description)
                            <small style="opacity: 0.9;">{{ $track->description }}</small>
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <span class="badge bg-light text-dark">{{ $track->clusters->count() }} {{ Str::plural('Cluster', $track->clusters->count()) }}</span>
                    <span class="badge {{ $track->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ ucfirst($track->status) }}
                    </span>
                </div>
            </div>

            <!-- Track Actions -->
            <div class="card-body" style="padding: 1rem 1.5rem;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <a href="{{ route('admin.clusters.create', ['track_id' => $track->id]) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>Add Cluster
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.tracks.edit', $track) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit me-1"></i>Edit Track
                        </a>
                        <form method="POST" action="{{ route('admin.tracks.toggle-status', $track) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Toggle Status">
                                <i class="fas fa-toggle-{{ $track->status === 'active' ? 'on' : 'off' }} me-1"></i>
                                {{ $track->status === 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.tracks.destroy', $track) }}" class="d-inline" 
                              onsubmit="return confirm('Are you sure you want to delete this track? This will also delete all its clusters if it has no assigned students/subjects.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Clusters Section -->
                @if($track->clusters->count() > 0)
                    <div style="border-top: 2px solid #e9ecef; padding-top: 1rem;">
                        <h6 class="mb-3" style="color: #495057; font-weight: 600;">
                            <i class="fas fa-list me-2"></i>Clusters
                        </h6>
                        <div class="row">
                            @foreach($track->clusters as $cluster)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px; background: #f8f9fa;">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1" style="font-weight: 600; color: #212529;">
                                                        <i class="fas fa-tag me-1" style="color: #667eea;"></i>{{ $cluster->name }}
                                                    </h6>
                                                    @if($cluster->description)
                                                        <small class="text-muted">{{ Str::limit($cluster->description, 50) }}</small>
                                                    @endif
                                                </div>
                                                <span class="badge {{ $cluster->status === 'active' ? 'bg-success' : 'bg-secondary' }}" style="font-size: 0.7rem;">
                                                    {{ ucfirst($cluster->status) }}
                                                </span>
                                            </div>
                                            <div class="d-flex gap-1 mt-2">
                                                <a href="{{ route('admin.clusters.edit', $cluster) }}" class="btn btn-sm btn-outline-primary" style="font-size: 0.75rem; padding: 0.2rem 0.5rem;">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.clusters.toggle-status', $cluster) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary" style="font-size: 0.75rem; padding: 0.2rem 0.5rem;" title="Toggle Status">
                                                        <i class="fas fa-toggle-{{ $cluster->status === 'active' ? 'on' : 'off' }}"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.clusters.destroy', $cluster) }}" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this cluster?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" style="font-size: 0.75rem; padding: 0.2rem 0.5rem;">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-4" style="border-top: 2px solid #e9ecef; color: #6c757d;">
                        <i class="fas fa-inbox fa-2x mb-2" style="opacity: 0.5;"></i>
                        <p class="mb-0">No clusters yet. Click "Add Cluster" to create one.</p>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="card" style="border-radius: 12px;">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-3x mb-3" style="color: #dee2e6;"></i>
                <h5 class="text-muted">No tracks found</h5>
                <p class="text-muted mb-4">Get started by creating your first track.</p>
                <a href="{{ route('admin.tracks.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Your First Track
                </a>
            </div>
        </div>
    @endforelse
</div>
@endsection

