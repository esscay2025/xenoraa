@extends('layouts.app')

@section('title', 'Community Forum')

@push('styles')
<style>
.forum-hero {
    background: linear-gradient(135deg, #0a0a0a 0%, #0d0d0d 100%);
    border-bottom: 1px solid #1a1a1a;
    padding: 3rem 0 2rem;
}
.forum-hero-inner {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 2rem;
}
.forum-hero h1 { font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem; }
.forum-hero p { color: #888; font-size: 1rem; }
.forum-stats { display: flex; gap: 2rem; margin-top: 1.25rem; }
.forum-stat { text-align: center; }
.forum-stat-value { font-size: 1.5rem; font-weight: 800; color: #fff; line-height: 1; }
.forum-stat-label { font-size: 0.75rem; color: #666; margin-top: 0.2rem; }

.forum-layout {
    max-width: 1100px;
    margin: 0 auto;
    padding: 2rem;
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 1.5rem;
}
/* Sidebar */
.forum-sidebar {
    position: sticky;
    top: 90px;
    height: fit-content;
}
.forum-sidebar-card {
    background: #111;
    border: 1px solid #1e1e1e;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 1rem;
}
.forum-sidebar-title {
    padding: 0.875rem 1.25rem;
    font-size: 0.7rem;
    font-weight: 700;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    border-bottom: 1px solid #1a1a1a;
}
.forum-cat-btn {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    width: 100%;
    padding: 0.65rem 1.25rem;
    background: none;
    border: none;
    color: #888;
    font-size: 0.875rem;
    cursor: pointer;
    text-align: left;
    transition: all 0.15s;
    text-decoration: none;
    border-left: 3px solid transparent;
}
.forum-cat-btn:hover { color: #fff; background: rgba(255,255,255,0.04); }
.forum-cat-btn.active { color: #fff; background: rgba(255,255,255,0.06); border-left-color: #fff; }
.forum-cat-btn i { width: 16px; text-align: center; font-size: 0.85rem; }
/* Topics */
.forum-main {}
.forum-toolbar {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
}
.forum-search {
    flex: 1;
    min-width: 200px;
    background: #111;
    border: 1px solid #1e1e1e;
    border-radius: 10px;
    padding: 0.6rem 1rem;
    color: #fff;
    font-size: 0.875rem;
}
.forum-search:focus { outline: none; border-color: #333; }
.forum-search::placeholder { color: #555; }
.btn-new-topic {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.6rem 1.25rem;
    background: #fff;
    color: #000;
    border: none;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
    white-space: nowrap;
}
.btn-new-topic:hover { background: #e0e0e0; }
.topic-card {
    background: #111;
    border: 1px solid #1e1e1e;
    border-radius: 14px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 0.75rem;
    transition: all 0.2s;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 1rem;
    align-items: center;
}
.topic-card:hover { border-color: #2a2a2a; transform: translateX(2px); }
.topic-card.pinned { border-color: #2a2a1a; background: #111108; }
.topic-title-row {
    display: flex;
    align-items: flex-start;
    gap: 0.6rem;
    margin-bottom: 0.4rem;
    flex-wrap: wrap;
}
.topic-title {
    font-size: 1rem;
    font-weight: 700;
    color: #fff;
    text-decoration: none;
    line-height: 1.4;
}
.topic-title:hover { color: #ccc; }
.topic-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    flex-shrink: 0;
}
.badge-pinned { background: rgba(245,158,11,0.15); color: #fcd34d; }
.badge-locked { background: rgba(239,68,68,0.15); color: #fca5a5; }
.topic-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 0.8rem;
    color: #666;
    flex-wrap: wrap;
}
.topic-meta i { font-size: 0.75rem; margin-right: 0.2rem; }
.topic-excerpt {
    font-size: 0.85rem;
    color: #666;
    margin: 0.35rem 0 0;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.topic-stats {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    min-width: 70px;
}
.topic-stat-box {
    text-align: center;
    background: #1a1a1a;
    border-radius: 8px;
    padding: 0.4rem 0.75rem;
    min-width: 60px;
}
.topic-stat-num { font-size: 1.1rem; font-weight: 800; color: #fff; line-height: 1; }
.topic-stat-lbl { font-size: 0.65rem; color: #666; margin-top: 0.15rem; }
.cat-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    background: rgba(255,255,255,0.07);
    color: #aaa;
}
/* Modal */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.75);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.modal-overlay.open { display: flex; }
.modal-box {
    background: #111;
    border: 1px solid #2a2a2a;
    border-radius: 20px;
    padding: 2rem;
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}
.modal-title { font-size: 1.25rem; font-weight: 800; color: #fff; margin-bottom: 1.5rem; }
.form-group { margin-bottom: 1.25rem; }
.form-label { display: block; font-size: 0.875rem; font-weight: 600; color: #aaa; margin-bottom: 0.4rem; }
.form-control {
    width: 100%;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 10px;
    padding: 0.7rem 1rem;
    color: #fff;
    font-size: 0.9rem;
    transition: border-color 0.2s;
    font-family: inherit;
}
.form-control:focus { outline: none; border-color: #444; }
.form-control::placeholder { color: #555; }
textarea.form-control { resize: vertical; min-height: 140px; }
select.form-control { appearance: none; cursor: pointer; }
.modal-actions { display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem; }
.btn-cancel {
    padding: 0.6rem 1.25rem;
    background: transparent;
    border: 1px solid #333;
    border-radius: 10px;
    color: #aaa;
    font-size: 0.875rem;
    cursor: pointer;
}
.btn-submit {
    padding: 0.6rem 1.5rem;
    background: #fff;
    border: none;
    border-radius: 10px;
    color: #000;
    font-size: 0.875rem;
    font-weight: 700;
    cursor: pointer;
}
.empty-forum {
    text-align: center;
    padding: 4rem 2rem;
    color: #555;
}
.empty-forum i { font-size: 3rem; color: #222; margin-bottom: 1rem; display: block; }
.login-prompt {
    background: #111;
    border: 1px solid #1e1e1e;
    border-radius: 14px;
    padding: 1.5rem;
    text-align: center;
    color: #888;
    font-size: 0.9rem;
}
.login-prompt a { color: #fff; font-weight: 700; }
@media (max-width: 768px) {
    .forum-layout { grid-template-columns: 1fr; padding: 1rem; }
    .forum-sidebar { position: static; }
    .forum-sidebar-card { display: flex; flex-wrap: wrap; padding: 0.5rem; border-radius: 10px; }
    .forum-sidebar-title { display: none; }
    .forum-cat-btn { width: auto; border-radius: 20px; border-left: none; padding: 0.35rem 0.75rem; font-size: 0.8rem; }
    .forum-cat-btn.active { background: rgba(255,255,255,0.1); }
    .topic-card { grid-template-columns: 1fr; }
    .topic-stats { flex-direction: row; justify-content: flex-start; }
    .forum-stats { gap: 1.25rem; }
}
</style>
@endpush

@section('content')

{{-- Forum Hero --}}
<section class="forum-hero">
    <div class="forum-hero-inner">
        <h1><i class="fas fa-comments" style="color:#555;margin-right:0.5rem;"></i>Community Forum</h1>
        <p>Join the conversation. Share ideas, ask questions, and learn from the community.</p>
        <div class="forum-stats">
            <div class="forum-stat">
                <div class="forum-stat-value">{{ number_format($stats['topics']) }}</div>
                <div class="forum-stat-label">Topics</div>
            </div>
            <div class="forum-stat">
                <div class="forum-stat-value">{{ number_format($stats['replies']) }}</div>
                <div class="forum-stat-label">Replies</div>
            </div>
        </div>
    </div>
</section>

<div class="forum-layout">

    {{-- Sidebar --}}
    <div class="forum-sidebar">
        <div class="forum-sidebar-card">
            <div class="forum-sidebar-title">Categories</div>
            <a href="{{ route('forum.index') }}" class="forum-cat-btn {{ !$category ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> All Topics
            </a>
            @foreach($categories as $key => $cat)
            <a href="{{ route('forum.index', ['category' => $key]) }}"
               class="forum-cat-btn {{ $category === $key ? 'active' : '' }}">
                <i class="{{ $cat['icon'] }}" style="color:{{ $cat['color'] }};"></i>
                {{ $cat['label'] }}
            </a>
            @endforeach
        </div>

        @auth
        <div class="forum-sidebar-card" style="padding:1.25rem;">
            <button class="btn-new-topic" style="width:100%;justify-content:center;" onclick="openNewTopicModal()">
                <i class="fas fa-plus"></i> New Topic
            </button>
        </div>
        @else
        <div class="login-prompt">
            <i class="fas fa-lock" style="font-size:1.5rem;color:#333;display:block;margin-bottom:0.75rem;"></i>
            <a href="{{ route('login') }}">Sign in</a> or <a href="{{ route('register') }}">register</a> to start a discussion.
        </div>
        @endauth
    </div>

    {{-- Main Content --}}
    <div class="forum-main">

        {{-- Toolbar --}}
        <div class="forum-toolbar">
            <form method="GET" action="{{ route('forum.index') }}" style="flex:1;display:flex;gap:0.75rem;flex-wrap:wrap;">
                @if($category)
                <input type="hidden" name="category" value="{{ $category }}">
                @endif
                <input type="text" name="search" value="{{ $search }}" class="forum-search"
                       placeholder="Search topics...">
                <button type="submit" style="padding:0.6rem 1rem;background:#1a1a1a;border:1px solid #2a2a2a;border-radius:10px;color:#aaa;cursor:pointer;font-size:0.875rem;">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            @auth
            <button class="btn-new-topic" onclick="openNewTopicModal()">
                <i class="fas fa-plus"></i> New Topic
            </button>
            @endauth
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

        {{-- Topics List --}}
        @forelse($topics as $topic)
        @php
        $catMeta = $categories[$topic->category] ?? ['label' => ucfirst($topic->category), 'icon' => 'fas fa-tag', 'color' => '#888'];
        @endphp
        <div class="topic-card {{ $topic->is_pinned ? 'pinned' : '' }}">
            <div>
                <div class="topic-title-row">
                    <a href="{{ route('forum.show', $topic->slug) }}" class="topic-title">
                        {{ $topic->title }}
                    </a>
                    @if($topic->is_pinned)
                    <span class="topic-badge badge-pinned"><i class="fas fa-thumbtack"></i> Pinned</span>
                    @endif
                    @if($topic->is_locked)
                    <span class="topic-badge badge-locked"><i class="fas fa-lock"></i> Locked</span>
                    @endif
                </div>
                <p class="topic-excerpt">{{ Str::limit(strip_tags($topic->body), 160) }}</p>
                <div class="topic-meta" style="margin-top:0.6rem;">
                    <span class="cat-tag">
                        <i class="{{ $catMeta['icon'] }}" style="color:{{ $catMeta['color'] }};"></i>
                        {{ $catMeta['label'] }}
                    </span>
                    <span><i class="fas fa-user"></i> {{ $topic->user->name }}</span>
                    <span><i class="fas fa-clock"></i> {{ $topic->created_at->diffForHumans() }}</span>
                    <span><i class="fas fa-eye"></i> {{ number_format($topic->views) }} views</span>
                    @auth
                    @if(auth()->user()->isAdmin())
                    <span style="margin-left:auto;display:flex;gap:0.4rem;">
                        <form method="POST" action="{{ route('forum.pin', $topic->slug) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" style="background:none;border:none;color:#f59e0b;cursor:pointer;font-size:0.75rem;" title="{{ $topic->is_pinned ? 'Unpin' : 'Pin' }}">
                                <i class="fas fa-thumbtack"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('forum.lock', $topic->slug) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:0.75rem;" title="{{ $topic->is_locked ? 'Unlock' : 'Lock' }}">
                                <i class="fas fa-lock"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('forum.delete', $topic->slug) }}" style="display:inline;" onsubmit="return confirm('Delete this topic?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:0.75rem;" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </span>
                    @endif
                    @endauth
                </div>
            </div>
            <div class="topic-stats">
                <div class="topic-stat-box">
                    <div class="topic-stat-num">{{ $topic->replies_count }}</div>
                    <div class="topic-stat-lbl">replies</div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-forum">
            <i class="fas fa-comments"></i>
            <p>No topics found. Be the first to start a discussion!</p>
            @auth
            <button class="btn-new-topic" style="margin-top:1rem;" onclick="openNewTopicModal()">
                <i class="fas fa-plus"></i> Start a Discussion
            </button>
            @endauth
        </div>
        @endforelse

        {{-- Pagination --}}
        @if($topics->hasPages())
        <div style="margin-top:1.5rem;">
            {{ $topics->appends(request()->query())->links() }}
        </div>
        @endif

    </div>
</div>

{{-- New Topic Modal --}}
@auth
<div class="modal-overlay" id="newTopicModal">
    <div class="modal-box">
        <h2 class="modal-title"><i class="fas fa-plus-circle" style="color:#888;margin-right:0.5rem;"></i>Start a New Discussion</h2>
        <form method="POST" action="{{ route('forum.create') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Topic Title *</label>
                <input type="text" name="title" class="form-control" placeholder="What would you like to discuss?" required minlength="5" maxlength="255">
            </div>
            <div class="form-group">
                <label class="form-label">Category *</label>
                <select name="category" class="form-control" required>
                    <option value="">Select a category...</option>
                    @foreach($categories as $key => $cat)
                    <option value="{{ $key }}">{{ $cat['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Your Message *</label>
                <textarea name="body" class="form-control" rows="6" placeholder="Describe your topic in detail..." required minlength="20" maxlength="10000"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Tags <span style="color:#555;">(optional, comma-separated)</span></label>
                <input type="text" name="tags" class="form-control" placeholder="e.g. AI, automation, tools">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeNewTopicModal()">Cancel</button>
                <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Post Topic</button>
            </div>
        </form>
    </div>
</div>
@endauth

@endsection

@push('scripts')
<script>
function openNewTopicModal() {
    document.getElementById('newTopicModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeNewTopicModal() {
    document.getElementById('newTopicModal').classList.remove('open');
    document.body.style.overflow = '';
}
document.getElementById('newTopicModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeNewTopicModal();
});
</script>
@endpush
