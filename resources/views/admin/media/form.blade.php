@extends('layouts.admin')
@section('title', $item ? 'Edit Media' : 'Add Media')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ $item ? 'Edit Media' : 'Add Media' }}</h4>
    <a href="{{ route('admin.media.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ $item ? route('admin.media.update', $item) : route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($item) @method('PUT') @endif

                    @if(!$item)
                    <div class="mb-3">
                        <label class="form-label">Media Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" id="mediaType" onchange="toggleFields()">
                            <option value="image" {{ old('type') === 'image' ? 'selected' : '' }}>Image</option>
                            <option value="video" {{ old('type') === 'video' ? 'selected' : '' }}>Video</option>
                            <option value="youtube" {{ old('type') === 'youtube' ? 'selected' : '' }}>YouTube</option>
                        </select>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $item->title ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $item->description ?? '') }}</textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Album / Category</label>
                            <input type="text" name="album" class="form-control" value="{{ old('album', $item->album ?? '') }}" list="albumList" placeholder="e.g., Portfolio, Events">
                            <datalist id="albumList">
                                @foreach($albums as $album)
                                <option value="{{ $album }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Visibility</label>
                            <select name="is_public" class="form-select">
                                <option value="1" {{ old('is_public', $item->is_public ?? 1) == 1 ? 'selected' : '' }}>Public</option>
                                <option value="0" {{ old('is_public', $item->is_public ?? 1) == 0 ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>
                    </div>

                    @if(!$item)
                    <div id="fileField" class="mb-3">
                        <label class="form-label">Upload File <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept="image/*,video/*">
                        <small class="text-muted">Max 50MB. Images: JPG, PNG, GIF, WebP. Videos: MP4, MOV, AVI</small>
                    </div>
                    <div id="youtubeField" class="mb-3" style="display:none;">
                        <label class="form-label">YouTube URL <span class="text-danger">*</span></label>
                        <input type="url" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                        <small class="text-muted">YouTube video URL</small>
                    </div>
                    @else
                    @if($item->type === 'youtube')
                    <div class="mb-3">
                        <label class="form-label">YouTube URL</label>
                        <input type="url" name="video_url" class="form-control" value="{{ $item->video_url }}">
                    </div>
                    @endif
                    @endif

                    @if($item)
                    <div class="mb-3">
                        <label class="form-label">Current Media</label>
                        @if($item->type === 'image' && $item->file_path)
                        <div><img src="{{ $item->file_path }}" class="rounded" style="max-height:200px;"></div>
                        @elseif($item->type === 'youtube')
                        <div><a href="{{ $item->video_url }}" target="_blank">{{ $item->video_url }}</a></div>
                        @if($item->thumbnail)
                        <div class="mt-2"><img src="{{ $item->thumbnail }}" class="rounded" style="max-height:120px;"></div>
                        @endif
                        @elseif($item->file_path)
                        <div><video src="{{ $item->file_path }}" controls style="max-height:200px;"></video></div>
                        @endif
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> {{ $item ? 'Update' : 'Upload' }} Media</button>
                </form>
            </div>
        </div>
    </div>
</div>

@if(!$item)
<script>
function toggleFields() {
    const type = document.getElementById('mediaType').value;
    document.getElementById('fileField').style.display = type !== 'youtube' ? 'block' : 'none';
    document.getElementById('youtubeField').style.display = type === 'youtube' ? 'block' : 'none';
}
</script>
@endif
@endsection
