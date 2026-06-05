@extends('layouts.admin')
@section('title', 'Media Gallery')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Media Gallery</h4>
        <p class="text-muted mb-0">Manage your images, videos, and YouTube embeds</p>
    </div>
    <a href="{{ route('admin.media.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Media</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="display-6 fw-bold text-primary">{{ $stats['total'] }}</div>
            <small class="text-muted">Total</small>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="display-6 fw-bold text-success">{{ $stats['images'] }}</div>
            <small class="text-muted">Images</small>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="display-6 fw-bold text-info">{{ $stats['videos'] }}</div>
            <small class="text-muted">Videos / YouTube</small>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-2">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="text-muted small">Filter:</span>
            <a href="{{ route('admin.media.index') }}" class="btn btn-sm {{ !request('type') && !request('album') ? 'btn-primary' : 'btn-outline-secondary' }}">All</a>
            <a href="{{ route('admin.media.index', ['type' => 'image']) }}" class="btn btn-sm {{ request('type') === 'image' ? 'btn-primary' : 'btn-outline-secondary' }}"><i class="bi bi-image"></i> Images</a>
            <a href="{{ route('admin.media.index', ['type' => 'video']) }}" class="btn btn-sm {{ request('type') === 'video' ? 'btn-primary' : 'btn-outline-secondary' }}"><i class="bi bi-camera-video"></i> Videos</a>
            <a href="{{ route('admin.media.index', ['type' => 'youtube']) }}" class="btn btn-sm {{ request('type') === 'youtube' ? 'btn-primary' : 'btn-outline-secondary' }}"><i class="bi bi-youtube"></i> YouTube</a>
            @if($albums->isNotEmpty())
            <span class="text-muted mx-2">|</span>
            @foreach($albums as $album)
            <a href="{{ route('admin.media.index', ['album' => $album]) }}" class="btn btn-sm {{ request('album') === $album ? 'btn-primary' : 'btn-outline-secondary' }}">{{ $album }}</a>
            @endforeach
            @endif
        </div>
    </div>
</div>

@if($media->isEmpty())
<div class="card border-0 shadow-sm text-center py-5">
    <div class="card-body">
        <i class="bi bi-images display-1 text-muted"></i>
        <h5 class="mt-3">No Media Yet</h5>
        <p class="text-muted">Upload images, videos, or add YouTube embeds to your gallery.</p>
        <a href="{{ route('admin.media.create') }}" class="btn btn-primary">Add First Media</a>
    </div>
</div>
@else
<div class="row g-3">
    @foreach($media as $item)
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="position-relative" style="padding-top:75%;overflow:hidden;">
                @if($item->type === 'image' && $item->file_path)
                <img src="{{ $item->file_path }}" class="position-absolute top-0 start-0 w-100 h-100" style="object-fit:cover;">
                @elseif($item->thumbnail)
                <img src="{{ $item->thumbnail }}" class="position-absolute top-0 start-0 w-100 h-100" style="object-fit:cover;">
                <div class="position-absolute top-50 start-50 translate-middle"><i class="bi bi-play-circle-fill text-white fs-1"></i></div>
                @else
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                    <i class="bi bi-{{ $item->type === 'video' ? 'camera-video' : 'youtube' }} display-4 text-muted"></i>
                </div>
                @endif
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge bg-{{ $item->is_public ? 'success' : 'secondary' }}">{{ $item->is_public ? 'Public' : 'Private' }}</span>
                </div>
            </div>
            <div class="card-body p-2">
                <small class="fw-bold d-block text-truncate">{{ $item->title ?? 'Untitled' }}</small>
                @if($item->album)
                <small class="text-muted"><i class="bi bi-folder"></i> {{ $item->album }}</small>
                @endif
                <div class="d-flex gap-1 mt-2">
                    <a href="{{ route('admin.media.edit', $item) }}" class="btn btn-sm btn-outline-primary flex-grow-1"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.media.destroy', $item) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger w-100"><i class="bi bi-trash"></i></button></form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
