@extends('layouts.admin')
@section('title', 'Chat Monitor')
@section('page-title', 'Chat Monitor')

@section('content')
{{-- Stats --}}
<div class="grid-4" style="margin-bottom: 2rem;">
    <div class="card" style="text-align:center; padding: 1.5rem;">
        <div style="font-size: 2rem; font-weight: 800; color: #3b82f6;">{{ $stats['total_messages'] }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Total Messages</div>
    </div>
    <div class="card" style="text-align:center; padding: 1.5rem;">
        <div style="font-size: 2rem; font-weight: 800; color: #22c55e;">{{ $stats['active_users'] }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Active Users</div>
    </div>
    <div class="card" style="text-align:center; padding: 1.5rem;">
        <div style="font-size: 2rem; font-weight: 800; color: #8b5cf6;">{{ $stats['channels'] }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Channels</div>
    </div>
    <div class="card" style="text-align:center; padding: 1.5rem;">
        <div style="font-size: 2rem; font-weight: 800; color: #ef4444;">{{ $stats['deleted_messages'] }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Removed Messages</div>
    </div>
</div>

{{-- Filters + Clear Channel --}}
<div class="card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 1; min-width: 200px;">
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Search Message</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search messages..." class="form-control" style="background: var(--bg-card); border: 1px solid var(--border); color: white; padding: 0.6rem 0.9rem; border-radius: 8px; width: 100%;">
        </div>
        <div style="min-width: 160px;">
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Channel</label>
            <select name="channel" class="form-control" style="background: var(--bg-card); border: 1px solid var(--border); color: white; padding: 0.6rem 0.9rem; border-radius: 8px; width: 100%;">
                <option value="">All Channels</option>
                @foreach($channels as $ch)
                <option value="{{ $ch }}" {{ request('channel') == $ch ? 'selected' : '' }}>#{{ $ch }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.25rem;">Filter</button>
        <a href="{{ route('admin.chat.index') }}" class="btn btn-outline" style="padding: 0.6rem 1.25rem;">Reset</a>
    </form>
</div>

{{-- Clear Channel Form --}}
<div class="card" style="margin-bottom: 1.5rem; padding: 1.25rem; border-color: rgba(239,68,68,0.2);">
    <h3 style="font-size: 0.9rem; font-weight: 600; color: #ef4444; margin: 0 0 0.75rem;"><i class="fas fa-exclamation-triangle" style="margin-right: 0.4rem;"></i>Clear Entire Channel</h3>
    <form method="POST" action="{{ route('admin.chat.clear') }}" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;" onsubmit="return confirm('This will remove ALL messages in this channel. Are you sure?')">
        @csrf
        <select name="channel" required style="background: var(--bg-card); border: 1px solid rgba(239,68,68,0.3); color: white; padding: 0.6rem 0.9rem; border-radius: 8px; min-width: 160px;">
            <option value="">Select channel...</option>
            @foreach($channels as $ch)
            <option value="{{ $ch }}">#{{ $ch }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-danger" style="padding: 0.6rem 1.25rem; font-size: 0.85rem;">
            <i class="fas fa-trash"></i> Clear Channel
        </button>
    </form>
</div>

{{-- Messages Table --}}
<div class="card">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border);">
        <h2 style="font-size: 1rem; font-weight: 600; margin: 0;">Messages ({{ $messages->total() }})</h2>
    </div>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 0.75rem 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">User</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Channel</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Message</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Time</th>
                    <th style="padding: 0.75rem 1rem; text-align: center; color: var(--text-muted); font-weight: 600;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.04); {{ $msg->is_deleted ? 'opacity:0.4;' : '' }}" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 0.75rem 1rem; color: var(--text-secondary); white-space: nowrap;">{{ $msg->user?->name ?? 'Deleted' }}</td>
                    <td style="padding: 0.75rem 1rem;"><span style="background: rgba(96,165,250,0.1); color: #93c5fd; font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: 4px;">#{{ $msg->channel }}</span></td>
                    <td style="padding: 0.75rem 1rem; color: var(--text-secondary); max-width: 400px;">{{ Str::limit($msg->message, 120) }}</td>
                    <td style="padding: 0.75rem 1rem; color: var(--text-muted); font-size: 0.8rem; white-space: nowrap;">{{ $msg->created_at->format('M d H:i') }}</td>
                    <td style="padding: 0.75rem 1rem; text-align: center;">
                        @if($msg->is_deleted)
                        <form method="POST" action="{{ route('admin.chat.restore', $msg) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" style="background: rgba(34,197,94,0.1); color: #22c55e; border: none; padding: 0.3rem 0.6rem; border-radius: 6px; cursor: pointer; font-size: 0.8rem;"><i class="fas fa-undo"></i></button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('admin.chat.destroy', $msg) }}" style="display:inline;" onsubmit="return confirm('Remove this message?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background: rgba(239,68,68,0.1); color: #ef4444; border: none; padding: 0.3rem 0.6rem; border-radius: 6px; cursor: pointer; font-size: 0.8rem;"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        <i class="fas fa-comment-slash" style="font-size: 2rem; margin-bottom: 0.75rem; display: block; opacity: 0.3;"></i>
                        No messages found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($messages->hasPages())
    <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border);">
        {{ $messages->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
