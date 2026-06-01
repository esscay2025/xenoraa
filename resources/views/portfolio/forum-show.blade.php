@extends('layouts.app')

@section('title', $topic->title . ' — Forum')

@push('styles')
<style>
.forum-show-wrap {
    max-width: 900px;
    margin: 0 auto;
    padding: 2.5rem 2rem;
}
.forum-breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: #555;
    margin-bottom: 1.5rem;
}
.forum-breadcrumb a { color: #888; text-decoration: none; }
.forum-breadcrumb a:hover { color: #fff; }
.forum-breadcrumb i { font-size: 0.65rem; }
.topic-header {
    background: #111;
    border: 1px solid #1e1e1e;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 1.5rem;
}
.topic-header-top {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
}
.topic-author-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1e3a5f, #2d5a8e);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    font-weight: 700;
    color: #93c5fd;
    flex-shrink: 0;
}
.topic-header-meta { flex: 1; }
.topic-header-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.3;
    margin-bottom: 0.4rem;
}
.topic-header-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 0.8rem;
    color: #666;
    flex-wrap: wrap;
}
.topic-header-info i { font-size: 0.75rem; margin-right: 0.2rem; }
.topic-body {
    font-size: 0.95rem;
    color: #ccc;
    line-height: 1.75;
    padding-top: 1.25rem;
    border-top: 1px solid #1a1a1a;
    white-space: pre-wrap;
    word-break: break-word;
}
.topic-tags {
    display: flex;
    gap: 0.4rem;
    flex-wrap: wrap;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #1a1a1a;
}
.tag-chip {
    display: inline-flex;
    align-items: center;
    padding: 0.2rem 0.6rem;
    background: rgba(255,255,255,0.07);
    border-radius: 20px;
    font-size: 0.75rem;
    color: #aaa;
}
.admin-controls {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #1a1a1a;
    flex-wrap: wrap;
}
.admin-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.35rem 0.875rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid;
    background: none;
    transition: all 0.15s;
    text-decoration: none;
}
.admin-btn-pin { color: #fcd34d; border-color: rgba(245,158,11,0.3); }
.admin-btn-pin:hover { background: rgba(245,158,11,0.1); }
.admin-btn-lock { color: #fca5a5; border-color: rgba(239,68,68,0.3); }
.admin-btn-lock:hover { background: rgba(239,68,68,0.1); }
.admin-btn-delete { color: #fca5a5; border-color: rgba(239,68,68,0.3); }
.admin-btn-delete:hover { background: rgba(239,68,68,0.1); }
/* Replies */
.replies-section { margin-bottom: 1.5rem; }
.replies-header {
    font-size: 0.85rem;
    font-weight: 700;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.reply-card {
    background: #111;
    border: 1px solid #1e1e1e;
    border-radius: 14px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 0.75rem;
    display: grid;
    grid-template-columns: 40px 1fr auto;
    gap: 1rem;
    align-items: flex-start;
}
.reply-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1a3a1a, #2d5a2d);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: 700;
    color: #86efac;
    flex-shrink: 0;
}
.reply-author { font-size: 0.875rem; font-weight: 700; color: #e0e0e0; }
.reply-time { font-size: 0.75rem; color: #555; margin-left: 0.5rem; }
.reply-body {
    font-size: 0.9rem;
    color: #ccc;
    line-height: 1.65;
    margin-top: 0.35rem;
    white-space: pre-wrap;
    word-break: break-word;
}
.reply-delete-btn {
    background: none;
    border: none;
    color: #555;
    cursor: pointer;
    font-size: 0.8rem;
    padding: 0.25rem;
    border-radius: 4px;
    transition: all 0.15s;
    flex-shrink: 0;
}
.reply-delete-btn:hover { color: #ef4444; background: rgba(239,68,68,0.1); }
/* Reply Form */
.reply-form-card {
    background: #111;
    border: 1px solid #1e1e1e;
    border-radius: 16px;
    padding: 1.5rem;
}
.reply-form-title {
    font-size: 1rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 1rem;
}
.reply-textarea {
    width: 100%;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 10px;
    padding: 0.875rem 1rem;
    color: #fff;
    font-size: 0.9rem;
    resize: vertical;
    min-height: 120px;
    font-family: inherit;
    line-height: 1.6;
    transition: border-color 0.2s;
}
.reply-textarea:focus { outline: none; border-color: #444; }
.reply-textarea::placeholder { color: #555; }
.reply-submit-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.65rem 1.5rem;
    background: #fff;
    color: #000;
    border: none;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 700;
    cursor: pointer;
    margin-top: 0.875rem;
    transition: all 0.2s;
}
.reply-submit-btn:hover { background: #e0e0e0; }
.locked-notice {
    background: rgba(239,68,68,0.08);
    border: 1px solid rgba(239,68,68,0.2);
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    color: #fca5a5;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.login-to-reply {
    background: #111;
    border: 1px solid #1e1e1e;
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    color: #888;
}
.login-to-reply a { color: #fff; font-weight: 700; }
@media (max-width: 640px) {
    .forum-show-wrap { padding: 1.25rem 1rem; }
    .reply-card { grid-template-columns: 36px 1fr; }
    .reply-delete-btn { grid-column: 2; }
    .topic-header-title { font-size: 1.2rem; }
}
</style>
@endpush

@section('content')
<div class="forum-show-wrap">

    {{-- Breadcrumb --}}
    <div class="forum-breadcrumb">
        <a href="{{ route('forum.index') }}">Forum</a>
        <i class="fas fa-chevron-right"></i>
        @php $catMeta = $categories[$topic->category] ?? ['label' => ucfirst($topic->category), 'icon' => 'fas fa-tag', 'color' => '#888']; @endphp
        <a href="{{ route('forum.index', ['category' => $topic->category]) }}">{{ $catMeta['label'] }}</a>
        <i class="fas fa-chevron-right"></i>
        <span style="color:#aaa;">{{ Str::limit($topic->title, 50) }}</span>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);color:#86efac;padding:0.875rem 1.25rem;border-radius:10px;margin-bottom:1rem;font-size:0.9rem;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;padding:0.875rem 1.25rem;border-radius:10px;margin-bottom:1rem;font-size:0.9rem;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    {{-- Topic Header --}}
    <div class="topic-header">
        <div class="topic-header-top">
            <div class="topic-author-avatar">
                {{ strtoupper(substr($topic->user->name, 0, 1)) }}
            </div>
            <div class="topic-header-meta">
                <h1 class="topic-header-title">{{ $topic->title }}</h1>
                <div class="topic-header-info">
                    <span><i class="fas fa-user"></i> {{ $topic->user->name }}</span>
                    <span><i class="fas fa-clock"></i> {{ $topic->created_at->diffForHumans() }}</span>
                    <span><i class="fas fa-eye"></i> {{ number_format($topic->views) }} views</span>
                    <span><i class="fas fa-reply"></i> {{ $replies->count() }} replies</span>
                    <span class="cat-tag" style="display:inline-flex;align-items:center;gap:0.3rem;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.7rem;font-weight:600;background:rgba(255,255,255,0.07);color:#aaa;">
                        <i class="{{ $catMeta['icon'] }}" style="color:{{ $catMeta['color'] }};"></i>
                        {{ $catMeta['label'] }}
                    </span>
                    @if($topic->is_pinned)
                    <span style="color:#fcd34d;font-size:0.75rem;"><i class="fas fa-thumbtack"></i> Pinned</span>
                    @endif
                    @if($topic->is_locked)
                    <span style="color:#fca5a5;font-size:0.75rem;"><i class="fas fa-lock"></i> Locked</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="topic-body">{{ $topic->body }}</div>

        @if($topic->tags)
        <div class="topic-tags">
            @foreach($topic->tags_array as $tag)
            <span class="tag-chip"><i class="fas fa-tag" style="font-size:0.65rem;margin-right:0.3rem;"></i>{{ $tag }}</span>
            @endforeach
        </div>
        @endif

        @auth
        @if(auth()->user()->isAdmin())
        <div class="admin-controls">
            <form method="POST" action="{{ route('forum.pin', $topic->slug) }}" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" class="admin-btn admin-btn-pin">
                    <i class="fas fa-thumbtack"></i> {{ $topic->is_pinned ? 'Unpin' : 'Pin' }}
                </button>
            </form>
            <form method="POST" action="{{ route('forum.lock', $topic->slug) }}" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" class="admin-btn admin-btn-lock">
                    <i class="fas fa-lock"></i> {{ $topic->is_locked ? 'Unlock' : 'Lock' }}
                </button>
            </form>
            <form method="POST" action="{{ route('forum.delete', $topic->slug) }}" style="display:inline;" onsubmit="return confirm('Delete this entire topic and all replies?')">
                @csrf @method('DELETE')
                <button type="submit" class="admin-btn admin-btn-delete">
                    <i class="fas fa-trash"></i> Delete Topic
                </button>
            </form>
        </div>
        @endif
        @endauth
    </div>

    {{-- Replies --}}
    @if($replies->count() > 0)
    <div class="replies-section">
        <div class="replies-header">
            <i class="fas fa-reply"></i> {{ $replies->count() }} {{ Str::plural('Reply', $replies->count()) }}
        </div>
        @foreach($replies as $reply)
        <div class="reply-card">
            <div class="reply-avatar">
                {{ strtoupper(substr($reply->user->name, 0, 1)) }}
            </div>
            <div>
                <span class="reply-author">{{ $reply->user->name }}</span>
                <span class="reply-time">{{ $reply->created_at->diffForHumans() }}</span>
                <div class="reply-body">{{ $reply->body }}</div>
            </div>
            @auth
            @if($reply->user_id === auth()->id() || auth()->user()->isAdmin())
            <form method="POST" action="{{ route('forum.reply.delete', $reply->id) }}" onsubmit="return confirm('Delete this reply?')">
                @csrf @method('DELETE')
                <button type="submit" class="reply-delete-btn" title="Delete reply">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
            @endif
            @endauth
        </div>
        @endforeach
    </div>
    @endif

    {{-- Reply Form / Locked Notice / Login Prompt --}}
    @if($topic->is_locked)
    <div class="locked-notice">
        <i class="fas fa-lock" style="font-size:1.25rem;flex-shrink:0;"></i>
        <span>This topic is <strong>locked</strong>. No new replies can be posted.</span>
    </div>
    @elseif(auth()->check())
    <div class="reply-form-card">
        <h3 class="reply-form-title"><i class="fas fa-reply" style="color:#888;margin-right:0.4rem;"></i>Post a Reply</h3>
        <form method="POST" action="{{ route('forum.reply', $topic->slug) }}">
            @csrf
            <textarea
                name="body"
                class="reply-textarea"
                placeholder="Share your thoughts, insights, or questions..."
                required
                minlength="5"
                maxlength="5000"
            ></textarea>
            @error('body')
            <p style="color:#fca5a5;font-size:0.8rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
            <button type="submit" class="reply-submit-btn">
                <i class="fas fa-paper-plane"></i> Post Reply
            </button>
        </form>
    </div>
    @else
    <div class="login-to-reply">
        <i class="fas fa-comment-dots" style="font-size:2rem;color:#333;display:block;margin-bottom:0.75rem;"></i>
        <p>Want to join the discussion?</p>
        <p style="margin-top:0.5rem;">
            <a href="{{ route('login') }}">Sign in</a> or
            <a href="{{ route('register') }}">create an account</a> to post a reply.
        </p>
    </div>
    @endif

</div>
@endsection
