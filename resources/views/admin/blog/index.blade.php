@extends('layouts.admin')
@section('title', 'Blog Posts')
@section('page-title', 'Blog Posts')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <p class="text-sm text-muted">Manage your blog content</p>
    </div>
    <a href="{{ route('admin.blog.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Post</a>
</div>

<!-- Filters -->
<form method="GET" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
    <select name="status" class="form-control" style="max-width: 150px;" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
        <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
    </select>
</form>

<div class="card" style="padding: 0;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td>
                        <div style="font-weight: 500;">{{ Str::limit($post->title, 50) }}</div>
                        <div class="text-xs text-muted">{{ $post->slug }}</div>
                    </td>
                    <td><span class="text-sm text-secondary">{{ $post->category?->name ?? '—' }}</span></td>
                    <td>
                        <span class="badge {{ $post->status === 'published' ? 'badge-success' : ($post->status === 'draft' ? 'badge-warning' : 'badge-secondary') }}">
                            {{ ucfirst($post->status) }}
                        </span>
                    </td>
                    <td><span class="text-sm">{{ $post->views_count }}</span></td>
                    <td><span class="text-sm text-muted">{{ $post->published_at?->format('M d, Y') ?? '—' }}</span></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline btn-xs" target="_blank"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.blog.edit', $post) }}" class="btn btn-outline btn-xs"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" onsubmit="return confirm('Delete this post?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-muted);">No blog posts found. <a href="{{ route('admin.blog.create') }}" style="color: white;">Create your first post.</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top: 1.5rem;">{{ $posts->links() }}</div>
@endsection
