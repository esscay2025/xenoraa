@extends('layouts.admin')
@section('title', 'New Blog Post')
@section('page-title', 'New Blog Post')

@section('content')
<div style="max-width: 900px;">
    <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid-2" style="align-items: start; gap: 2rem;">
            <div>
                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" placeholder="Post title" value="{{ old('title') }}" required>
                    @error('title')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Summary</label>
                    <textarea name="summary" class="form-control" rows="3" placeholder="Brief summary for listing pages...">{{ old('summary') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Content *</label>
                    <textarea name="content" class="form-control" rows="15" placeholder="Write your blog post content here..." required>{{ old('content') }}</textarea>
                    @error('content')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <div class="card" style="margin-bottom: 1rem;">
                    <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">Publish Settings</h3>
                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-control" required>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control">
                            <option value="">No Category</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Featured Image</label>
                        <input type="file" name="featured_image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fas fa-save"></i> Save Post</button>
                    <a href="{{ route('admin.blog.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
