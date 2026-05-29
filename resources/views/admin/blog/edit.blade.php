@extends('layouts.admin')
@section('title', 'Edit Blog Post')
@section('page-title', 'Edit Blog Post')

@section('content')
<div style="max-width: 900px;">
    <form method="POST" action="{{ route('admin.blog.update', $blog) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="grid-2" style="align-items: start; gap: 2rem;">
            <div>
                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $blog->title) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Summary</label>
                    <textarea name="summary" class="form-control" rows="3">{{ old('summary', $blog->summary) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Content *</label>
                    <textarea name="content" class="form-control" rows="15" required>{{ old('content', $blog->content) }}</textarea>
                </div>
            </div>
            <div>
                <div class="card" style="margin-bottom: 1rem;">
                    <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">Publish Settings</h3>
                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-control" required>
                            <option value="draft" {{ old('status', $blog->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status', $blog->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control">
                            <option value="">No Category</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $blog->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Featured Image</label>
                        @if($blog->featured_image)
                        <img src="{{ asset('storage/' . $blog->featured_image) }}" style="width: 100%; border-radius: 8px; margin-bottom: 0.5rem; max-height: 150px; object-fit: cover;">
                        @endif
                        <input type="file" name="featured_image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fas fa-save"></i> Update Post</button>
                    <a href="{{ route('admin.blog.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
