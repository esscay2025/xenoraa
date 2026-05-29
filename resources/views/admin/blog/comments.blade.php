@extends('layouts.admin')
@section('title', 'Blog Comments')
@section('page-title', 'Blog Comments')

@section('content')
<div class="card" style="padding: 0;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Comment</th>
                    <th>Author</th>
                    <th>Post</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comments as $comment)
                <tr>
                    <td style="max-width: 300px;"><p style="margin: 0; font-size: 0.875rem;">{{ Str::limit($comment->comment, 80) }}</p></td>
                    <td>
                        <div style="font-size: 0.875rem; font-weight: 500;">{{ $comment->user?->name ?? $comment->visitor_name ?? 'Anonymous' }}</div>
                        <div class="text-xs text-muted">{{ $comment->visitor_email ?? $comment->user?->email }}</div>
                    </td>
                    <td><span class="text-sm text-secondary">{{ Str::limit($comment->post?->title ?? '—', 30) }}</span></td>
                    <td>
                        <span class="badge {{ $comment->is_approved ? 'badge-success' : 'badge-warning' }}">
                            {{ $comment->is_approved ? 'Approved' : 'Pending' }}
                        </span>
                    </td>
                    <td><span class="text-sm text-muted">{{ $comment->created_at->format('M d, Y') }}</span></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <form method="POST" action="{{ route('admin.blog.comments.toggle', $comment) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn {{ $comment->is_approved ? 'btn-warning' : 'btn-success' }} btn-xs">
                                    {{ $comment->is_approved ? 'Unapprove' : 'Approve' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.blog.comments.destroy', $comment) }}" onsubmit="return confirm('Delete this comment?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-muted);">No comments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top: 1.5rem;">{{ $comments->links() }}</div>
@endsection
