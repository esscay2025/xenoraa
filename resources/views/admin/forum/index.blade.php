@extends('layouts.admin')
@section('title', 'Forum Control')
@section('page-title', 'Forum Control')

@section('content')
{{-- Stats --}}
<div class="grid-4" style="margin-bottom: 2rem;">
    <div class="card" style="text-align:center; padding: 1.5rem;">
        <div style="font-size: 2rem; font-weight: 800; color: #3b82f6;">{{ $stats['total_topics'] }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Total Topics</div>
    </div>
    <div class="card" style="text-align:center; padding: 1.5rem;">
        <div style="font-size: 2rem; font-weight: 800; color: #22c55e;">{{ $stats['total_replies'] }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Total Replies</div>
    </div>
    <div class="card" style="text-align:center; padding: 1.5rem;">
        <div style="font-size: 2rem; font-weight: 800; color: #f59e0b;">{{ $stats['pinned'] }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Pinned Topics</div>
    </div>
    <div class="card" style="text-align:center; padding: 1.5rem;">
        <div style="font-size: 2rem; font-weight: 800; color: #ef4444;">{{ $stats['locked'] }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Locked Topics</div>
    </div>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 1; min-width: 200px;">
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search topics..." class="form-control" style="background: var(--bg-card); border: 1px solid var(--border); color: white; padding: 0.6rem 0.9rem; border-radius: 8px; width: 100%;">
        </div>
        <div style="min-width: 160px;">
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Category</label>
            <select name="category" class="form-control" style="background: var(--bg-card); border: 1px solid var(--border); color: white; padding: 0.6rem 0.9rem; border-radius: 8px; width: 100%;">
                <option value="">All Categories</option>
                <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>General</option>
                <option value="ai-automation" {{ request('category') == 'ai-automation' ? 'selected' : '' }}>AI & Automation</option>
                <option value="startup-business" {{ request('category') == 'startup-business' ? 'selected' : '' }}>Startup & Business</option>
                <option value="tech-development" {{ request('category') == 'tech-development' ? 'selected' : '' }}>Tech & Development</option>
                <option value="career-branding" {{ request('category') == 'career-branding' ? 'selected' : '' }}>Career & Branding</option>
            </select>
        </div>
        <div style="min-width: 140px;">
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Status</label>
            <select name="status" class="form-control" style="background: var(--bg-card); border: 1px solid var(--border); color: white; padding: 0.6rem 0.9rem; border-radius: 8px; width: 100%;">
                <option value="">All</option>
                <option value="pinned" {{ request('status') == 'pinned' ? 'selected' : '' }}>Pinned</option>
                <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>Locked</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.25rem;">Filter</button>
        <a href="{{ route('admin.forum.index') }}" class="btn btn-outline" style="padding: 0.6rem 1.25rem;">Reset</a>
    </form>
</div>

{{-- Topics Table --}}
<div class="card">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: between;">
        <h2 style="font-size: 1rem; font-weight: 600; margin: 0;">Forum Topics ({{ $topics->total() }})</h2>
    </div>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 0.75rem 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Topic</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Author</th>
                    <th style="padding: 0.75rem 1rem; text-align: center; color: var(--text-muted); font-weight: 600;">Replies</th>
                    <th style="padding: 0.75rem 1rem; text-align: center; color: var(--text-muted); font-weight: 600;">Views</th>
                    <th style="padding: 0.75rem 1rem; text-align: center; color: var(--text-muted); font-weight: 600;">Status</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Date</th>
                    <th style="padding: 0.75rem 1rem; text-align: center; color: var(--text-muted); font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topics as $topic)
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.15s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 0.875rem 1rem; max-width: 320px;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            @if($topic->is_pinned)<span style="background: rgba(245,158,11,0.15); color: #f59e0b; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 600;">📌 PINNED</span>@endif
                            @if($topic->is_locked)<span style="background: rgba(239,68,68,0.15); color: #ef4444; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 600;">🔒 LOCKED</span>@endif
                        </div>
                        <a href="{{ route('admin.forum.show', $topic) }}" style="color: var(--text-primary); text-decoration: none; font-weight: 500; display: block; margin-top: 0.25rem;" onmouseover="this.style.color='#60a5fa'" onmouseout="this.style.color='var(--text-primary)'">
                            {{ Str::limit($topic->title, 65) }}
                        </a>
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
@endsection
