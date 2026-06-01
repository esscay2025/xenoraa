@extends('layouts.admin')
@section('title', 'Forum Topic: ' . Str::limit($topic->title, 40))
@section('page-title', 'Forum Topic Detail')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.forum.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem;">
        <i class="fas fa-arrow-left" style="margin-right: 0.4rem;"></i> Back to Forum Control
    </a>
</div>

<div class="card" style="margin-bottom: 1.5rem; padding: 1.75rem;">
    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
        <div style="flex: 1;">
            <div style="display: flex; gap: 0.5rem; margin-bottom: 0.75rem; flex-wrap: wrap;">
                @if($topic->is_pinned)<span style="background: rgba(245,158,11,0.15); color: #f59e0b; font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 4px; font-weight: 600;">📌 PINNED</span>@endif
                @if($topic->is_locked)<span style="background: rgba(239,68,68,0.15); color: #ef4444; font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 4px; font-weight: 600;">🔒 LOCKED</span>@endif
                <span style="background: rgba(96,165,250,0.1); color: #93c5fd; font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 4px; text-transform: capitalize;">{{ str_replace('-', ' ', $topic->category) }}</span>
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 0.75rem; color: var(--text-primary);">{{ $topic->title }}</h1>
            <div style="font-size: 0.85rem; color: var(--text-muted);">
                By <strong style="color: var(--text-secondary);">{{ $topic->user?->name ?? 'Deleted User' }}</strong>
                &nbsp;·&nbsp; {{ $topic->created_at->format('M d, Y H:i') }}
                &nbsp;·&nbsp; {{ $topic->views }} views
                &nbsp;·&nbsp; {{ $topic->allReplies->count() }} replies
            </div>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <form method="POST" action="{{ route('admin.forum.pin', $topic) }}" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" class="btn {{ $topic->is_pinned ? 'btn-warning' : 'btn-outline' }}" style="font-size: 0.85rem;">
                    <i class="fas fa-thumbtack"></i> {{ $topic->is_pinned ? 'Unpin' : 'Pin' }}
                </button>
            </form>
            <form method="POST" action="{{ route('admin.forum.lock', $topic) }}" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" class="btn {{ $topic->is_locked ? 'btn-danger' : 'btn-outline' }}" style="font-size: 0.85rem;">
                    <i class="fas fa-{{ $topic->is_locked ? 'lock-open' : 'lock' }}"></i> {{ $topic->is_locked ? 'Unlock' : 'Lock' }}
                </button>
            </form>
            <form method="POST" action="{{ route('admin.forum.destroy', $topic) }}" style="display:inline;" onsubmit="return confirm('Delete this topic and ALL replies permanently?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" style="font-size: 0.85rem;">
                    <i class="fas fa-trash"></i> Delete Topic
                </button>
            </form>
        </div>
    </div>
    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border); color: var(--text-secondary); line-height: 1.8; font-size: 0.95rem;">
        {!! nl2br(e($topic->body)) !!}
    </div>
</div>

<h2 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">All Replies ({{ $topic->allReplies->count() }})</h2>

@forelse($topic->allReplies->sortBy('created_at') as $reply)
<div class="card" style="margin-bottom: 1rem; padding: 1.25rem; {{ $reply->is_deleted ? 'opacity: 0.5; border-color: rgba(239,68,68,0.3);' : '' }}">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 0.75rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #8b5cf6); display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: 0.8rem; flex-shrink: 0;">
                {{ strtoupper(substr($reply->user?->name ?? 'D', 0, 1)) }}
            </div>
            <div>
                <div style="font-size: 0.875rem; font-weight: 600; color: var(--text-primary);">{{ $reply->user?->name ?? 'Deleted User' }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $reply->created_at->format('M d, Y H:i') }}</div>
            </div>
        </div>
        <div style="display: flex; gap: 0.4rem;">
            @if($reply->is_deleted)
            <form method="POST" action="{{ route('admin.forum.reply.restore', $reply) }}" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" style="background: rgba(34,197,94,0.1); color: #22c55e; border: none; padding: 0.3rem 0.7rem; border-radius: 6px; cursor: pointer; font-size: 0.8rem;"><i class="fas fa-undo"></i> Restore</button>
            </form>
            @else
            <form method="POST" action="{{ route('admin.forum.reply.destroy', $reply) }}" style="display:inline;" onsubmit="return confirm('Remove this reply?')">
                @csrf @method('DELETE')
                <button type="submit" style="background: rgba(239,68,68,0.1); color: #ef4444; border: none; padding: 0.3rem 0.7rem; border-radius: 6px; cursor: pointer; font-size: 0.8rem;"><i class="fas fa-trash"></i> Remove</button>
            </form>
            @endif
        </div>
    </div>
    @if($reply->is_deleted)
    <p style="color: var(--text-muted); font-style: italic; font-size: 0.875rem; margin: 0;">[This reply has been removed]</p>
    @else
    <p style="color: var(--text-secondary); font-size: 0.9rem; margin: 0; line-height: 1.7;">{!! nl2br(e($reply->body)) !!}</p>
    @endif
</div>
@empty
<div class="card" style="padding: 2rem; text-align: center; color: var(--text-muted);">
    <i class="fas fa-comment-slash" style="font-size: 1.5rem; margin-bottom: 0.5rem; display: block; opacity: 0.3;"></i>
    No replies yet.
</div>
@endforelse
@endsection
