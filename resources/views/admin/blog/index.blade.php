@extends('layouts.admin')
@section('title', 'Content')
@section('page-title', 'Content')

@section('content')
{{-- Tab Header --}}
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div style="display: flex; gap: 0; border: 1px solid var(--border); border-radius: 10px; overflow: hidden; background: var(--bg-card);">
        <a href="{{ route('admin.blog.index', ['tab' => 'posts']) }}"
           style="padding: 0.55rem 1.25rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 0.4rem; border-right: 1px solid var(--border); transition: background 0.15s;
           {{ $tab === 'posts' ? 'background: var(--primary); color: #fff;' : 'color: var(--text-secondary);' }}">
            <i class="fas fa-list" style="font-size:0.8rem;"></i> All Posts
        </a>
        <a href="{{ route('admin.blog.index', ['tab' => 'comments']) }}"
           style="padding: 0.55rem 1.25rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 0.4rem; border-right: 1px solid var(--border); transition: background 0.15s;
           {{ $tab === 'comments' ? 'background: var(--primary); color: #fff;' : 'color: var(--text-secondary);' }}">
            <i class="fas fa-comments" style="font-size:0.8rem;"></i> Comments
            @if($comments->total() > 0)<span style="background: rgba(255,255,255,0.2); border-radius: 20px; padding: 0.1rem 0.5rem; font-size: 0.75rem; margin-left: 0.25rem;">{{ $comments->total() }}</span>@endif
        </a>
        <a href="{{ route('admin.blog.index', ['tab' => 'forum']) }}"
           style="padding: 0.55rem 1.25rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 0.4rem; transition: background 0.15s;
           {{ $tab === 'forum' ? 'background: var(--primary); color: #fff;' : 'color: var(--text-secondary);' }}">
            <i class="fas fa-comments" style="font-size:0.8rem;"></i> Forum
            @if($forumStats['total_topics'] > 0)<span style="background: rgba(255,255,255,0.2); border-radius: 20px; padding: 0.1rem 0.5rem; font-size: 0.75rem; margin-left: 0.25rem;">{{ $forumStats['total_topics'] }}</span>@endif
        </a>
    </div>
    @if($tab === 'posts')
    <a href="{{ route('admin.blog.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Post</a>
    @endif
</div>

{{-- ── TAB: ALL POSTS ─────────────────────────────────────────────── --}}
@if($tab === 'posts')

<form method="GET" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
    <input type="hidden" name="tab" value="posts">
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

{{-- ── TAB: COMMENTS ──────────────────────────────────────────────── --}}
@elseif($tab === 'comments')

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

{{-- ── TAB: FORUM ──────────────────────────────────────────────────── --}}
@elseif($tab === 'forum')

{{-- Forum stats --}}
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
    @foreach([['Topics', $forumStats['total_topics'], 'fas fa-comments', '#3b82f6'], ['Replies', $forumStats['total_replies'], 'fas fa-reply', '#22c55e'], ['Pinned', $forumStats['pinned'], 'fas fa-thumbtack', '#f59e0b'], ['Locked', $forumStats['locked'], 'fas fa-lock', '#ef4444']] as [$label, $val, $icon, $color])
    <div class="card" style="padding: 1rem; display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 38px; height: 38px; border-radius: 8px; background: {{ $color }}22; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i class="{{ $icon }}" style="color: {{ $color }}; font-size: 1rem;"></i>
        </div>
        <div>
            <div style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">{{ $val }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Forum filters --}}
<form method="GET" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
    <input type="hidden" name="tab" value="forum">
    <input type="text" name="search" value="{{ request('search') }}" class="form-control" style="max-width: 250px;" placeholder="Search topics…">
    <select name="category" class="form-control" style="max-width: 160px;" onchange="this.form.submit()">
        <option value="">All Categories</option>
        @foreach(['general','technology','business','career','lifestyle','other'] as $cat)
        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-search"></i> Search</button>
</form>

<div class="card" style="padding: 0;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Topic</th>
                    <th>Author</th>
                    <th style="text-align:center;">Replies</th>
                    <th style="text-align:center;">Views</th>
                    <th style="text-align:center;">Status</th>
                    <th>Date</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topics as $topic)
                <tr>
                    <td style="padding: 0.875rem 1rem;">
                        @if($topic->is_pinned)<span style="background: rgba(245,158,11,0.15); color: #f59e0b; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 20px; margin-right: 0.4rem;"><i class="fas fa-thumbtack"></i></span>@endif
                        <a href="{{ route('admin.forum.show', $topic) }}" style="color: var(--text-primary); text-decoration: none; font-weight: 500; display: block; margin-top: 0.25rem;">{{ Str::limit($topic->title, 65) }}</a>
                        <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: capitalize;">{{ str_replace('-', ' ', $topic->category) }}</span>
                    </td>
                    <td style="padding: 0.875rem 1rem; color: var(--text-secondary);">{{ $topic->user?->name ?? 'Deleted' }}</td>
                    <td style="padding: 0.875rem 1rem; text-align: center; color: var(--text-secondary);">{{ $topic->replies_count }}</td>
                    <td style="padding: 0.875rem 1rem; text-align: center; color: var(--text-secondary);">{{ $topic->views }}</td>
                    <td style="padding: 0.875rem 1rem; text-align: center;">
                        @if($topic->is_locked)
                            <span style="background: rgba(239,68,68,0.1); color: #ef4444; font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 20px;">Locked</span>
                        @elseif($topic->is_pinned)
                            <span style="background: rgba(245,158,11,0.1); color: #f59e0b; font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 20px;">Pinned</span>
                        @else
                            <span style="background: rgba(34,197,94,0.1); color: #22c55e; font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 20px;">Active</span>
                        @endif
                    </td>
                    <td style="padding: 0.875rem 1rem; color: var(--text-muted); font-size: 0.8rem; white-space: nowrap;">{{ $topic->created_at->format('M d, Y') }}</td>
                    <td style="padding: 0.875rem 1rem; text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center; flex-wrap: wrap;">
                            <form method="POST" action="{{ route('admin.forum.pin', $topic) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" title="{{ $topic->is_pinned ? 'Unpin' : 'Pin' }}" style="background: rgba(245,158,11,0.1); color: #f59e0b; border: none; padding: 0.35rem 0.6rem; border-radius: 6px; cursor: pointer; font-size: 0.8rem;">
                                    <i class="fas fa-thumbtack"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.forum.lock', $topic) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" title="{{ $topic->is_locked ? 'Unlock' : 'Lock' }}" style="background: rgba(59,130,246,0.1); color: #3b82f6; border: none; padding: 0.35rem 0.6rem; border-radius: 6px; cursor: pointer; font-size: 0.8rem;">
                                    <i class="fas fa-{{ $topic->is_locked ? 'lock-open' : 'lock' }}"></i>
                                </button>
                            </form>
                            <a href="{{ route('forum.show', $topic->slug) }}" target="_blank" style="background: rgba(34,197,94,0.1); color: #22c55e; border: none; padding: 0.35rem 0.6rem; border-radius: 6px; cursor: pointer; font-size: 0.8rem; text-decoration: none;" title="View on site">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.forum.destroy', $topic) }}" style="display:inline;" onsubmit="return confirm('Delete this topic and all its replies?')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Delete" style="background: rgba(239,68,68,0.1); color: #ef4444; border: none; padding: 0.35rem 0.6rem; border-radius: 6px; cursor: pointer; font-size: 0.8rem;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        <i class="fas fa-comments" style="font-size: 2rem; margin-bottom: 0.75rem; display: block; opacity: 0.3;"></i>
                        No forum topics found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($topics->hasPages())
    <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border);">
        {{ $topics->withQueryString()->links() }}
    </div>
    @endif
</div>

@endif
@endsection
