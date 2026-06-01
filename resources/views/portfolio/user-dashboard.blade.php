@extends('layouts.app')

@section('title', 'My Dashboard')

@push('styles')
<style>
.dashboard-hero {
    background: linear-gradient(135deg, #0a0a0a 0%, #111 100%);
    border-bottom: 1px solid #1a1a1a;
    padding: 3rem 0 2rem;
}
.dashboard-hero-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}
.dashboard-greeting {
    font-size: 1.75rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: 0.25rem;
}
.dashboard-subtitle {
    color: #888;
    font-size: 1rem;
}
.dashboard-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2.5rem 2rem;
}
.dashboard-section-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.dashboard-section-title i {
    color: #888;
    font-size: 1rem;
}
.module-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}
.module-card {
    background: #111;
    border: 1px solid #1e1e1e;
    border-radius: 16px;
    padding: 1.75rem;
    text-decoration: none;
    color: #fff;
    transition: all 0.25s;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    position: relative;
    overflow: hidden;
}
.module-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 16px 16px 0 0;
    transition: opacity 0.25s;
    opacity: 0.7;
}
.module-card.calendar::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
.module-card.chat::before { background: linear-gradient(90deg, #22c55e, #86efac); }
.module-card.forum::before { background: linear-gradient(90deg, #f59e0b, #fcd34d); }
.module-card.blog::before { background: linear-gradient(90deg, #8b5cf6, #c4b5fd); }
.module-card.profile::before { background: linear-gradient(90deg, #ec4899, #f9a8d4); }
.module-card:hover {
    border-color: #333;
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.4);
}
.module-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}
.module-icon.calendar { background: rgba(59,130,246,0.15); color: #60a5fa; }
.module-icon.chat { background: rgba(34,197,94,0.15); color: #86efac; }
.module-icon.forum { background: rgba(245,158,11,0.15); color: #fcd34d; }
.module-icon.blog { background: rgba(139,92,246,0.15); color: #c4b5fd; }
.module-icon.profile { background: rgba(236,72,153,0.15); color: #f9a8d4; }
.module-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 0.25rem;
}
.module-desc {
    font-size: 0.875rem;
    color: #888;
    line-height: 1.5;
}
.module-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    background: rgba(255,255,255,0.08);
    color: #aaa;
    margin-top: auto;
    width: fit-content;
}
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 1rem;
    margin-bottom: 2.5rem;
}
.stat-mini {
    background: #111;
    border: 1px solid #1e1e1e;
    border-radius: 12px;
    padding: 1.25rem;
    text-align: center;
}
.stat-mini-value {
    font-size: 1.75rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
    margin-bottom: 0.35rem;
}
.stat-mini-label {
    font-size: 0.8rem;
    color: #666;
}
.recent-activity {
    background: #111;
    border: 1px solid #1e1e1e;
    border-radius: 16px;
    overflow: hidden;
}
.activity-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #1e1e1e;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.activity-header h3 {
    font-size: 1rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
}
.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #1a1a1a;
    transition: background 0.15s;
}
.activity-item:last-child { border-bottom: none; }
.activity-item:hover { background: #161616; }
.activity-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #333;
    margin-top: 0.35rem;
    flex-shrink: 0;
}
.activity-dot.blue { background: #3b82f6; }
.activity-dot.green { background: #22c55e; }
.activity-dot.yellow { background: #f59e0b; }
.activity-text { font-size: 0.875rem; color: #ccc; }
.activity-time { font-size: 0.75rem; color: #555; margin-top: 0.2rem; }
@media (max-width: 768px) {
    .module-grid { grid-template-columns: 1fr; }
    .stats-row { grid-template-columns: repeat(2, 1fr); }
    .dashboard-greeting { font-size: 1.35rem; }
}
</style>
@endpush

@section('content')

{{-- Dashboard Hero --}}
<section class="dashboard-hero">
    <div class="dashboard-hero-inner">
        <h1 class="dashboard-greeting">Welcome back, {{ auth()->user()->name }} 👋</h1>
        <p class="dashboard-subtitle">Here's what's happening in your workspace today.</p>
    </div>
</section>

<div class="dashboard-section">

    {{-- Quick Stats --}}
    <div class="stats-row">
        <div class="stat-mini">
            <div class="stat-mini-value">{{ $eventCount ?? 0 }}</div>
            <div class="stat-mini-label">Calendar Events</div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-value">{{ $noteCount ?? 0 }}</div>
            <div class="stat-mini-label">Sticky Notes</div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-value">{{ $forumPostCount ?? 0 }}</div>
            <div class="stat-mini-label">Forum Replies</div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-value">{{ $chatCount ?? 0 }}</div>
            <div class="stat-mini-label">Chat Messages</div>
        </div>
    </div>

    {{-- Modules --}}
    <div class="dashboard-section-title">
        <i class="fas fa-th-large"></i> Your Modules
    </div>
    <div class="module-grid">
        <a href="{{ route('calendar.index') }}" class="module-card calendar">
            <div class="module-icon calendar"><i class="fas fa-calendar-alt"></i></div>
            <div>
                <div class="module-title">Calendar & Notes</div>
                <div class="module-desc">Manage your schedule, set reminders, and keep sticky notes for quick ideas and tasks.</div>
            </div>
            <span class="module-badge"><i class="fas fa-arrow-right" style="margin-right:0.35rem;font-size:0.65rem;"></i> Open Calendar</span>
        </a>

        <a href="{{ route('chat.index') }}" class="module-card chat">
            <div class="module-icon chat"><i class="fas fa-comment-dots"></i></div>
            <div>
                <div class="module-title">Team Chat</div>
                <div class="module-desc">Connect and communicate with other members of the community in real-time group and private chats.</div>
            </div>
            <span class="module-badge"><i class="fas fa-arrow-right" style="margin-right:0.35rem;font-size:0.65rem;"></i> Open Chat</span>
        </a>

        <a href="{{ route('forum.index') }}" class="module-card forum">
            <div class="module-icon forum"><i class="fas fa-comments"></i></div>
            <div>
                <div class="module-title">Community Forum</div>
                <div class="module-desc">Join discussions on AI, startups, technology, and business. Share your insights and learn from others.</div>
            </div>
            <span class="module-badge"><i class="fas fa-arrow-right" style="margin-right:0.35rem;font-size:0.65rem;"></i> Browse Forum</span>
        </a>

        <a href="{{ route('blog') }}" class="module-card blog">
            <div class="module-icon blog"><i class="fas fa-book-open"></i></div>
            <div>
                <div class="module-title">Knowledge Hub</div>
                <div class="module-desc">Read the latest articles on AI, automation, hacking, startups, and personal branding.</div>
            </div>
            <span class="module-badge"><i class="fas fa-arrow-right" style="margin-right:0.35rem;font-size:0.65rem;"></i> Read Blog</span>
        </a>

        <a href="{{ route('profile.edit') }}" class="module-card profile">
            <div class="module-icon profile"><i class="fas fa-user-circle"></i></div>
            <div>
                <div class="module-title">My Profile</div>
                <div class="module-desc">Update your name, email, password, and profile preferences.</div>
            </div>
            <span class="module-badge"><i class="fas fa-arrow-right" style="margin-right:0.35rem;font-size:0.65rem;"></i> Edit Profile</span>
        </a>
    </div>

    {{-- Recent Activity --}}
    <div class="dashboard-section-title">
        <i class="fas fa-clock"></i> Recent Activity
    </div>
    <div class="recent-activity">
        <div class="activity-header">
            <h3>Latest Updates</h3>
        </div>
        @forelse($recentActivity ?? [] as $activity)
        <div class="activity-item">
            <div class="activity-dot {{ $activity['color'] ?? '' }}"></div>
            <div>
                <div class="activity-text">{{ $activity['text'] }}</div>
                <div class="activity-time">{{ $activity['time'] }}</div>
            </div>
        </div>
        @empty
        <div class="activity-item">
            <div class="activity-dot blue"></div>
            <div>
                <div class="activity-text">Welcome to your dashboard! Explore your modules above.</div>
                <div class="activity-time">Just now</div>
            </div>
        </div>
        <div class="activity-item">
            <div class="activity-dot green"></div>
            <div>
                <div class="activity-text">New blog posts are available in the Knowledge Hub.</div>
                <div class="activity-time">Today</div>
            </div>
        </div>
        <div class="activity-item">
            <div class="activity-dot yellow"></div>
            <div>
                <div class="activity-text">Forum discussions are live — join the conversation!</div>
                <div class="activity-time">Today</div>
            </div>
        </div>
        @endforelse
    </div>

</div>
@endsection
