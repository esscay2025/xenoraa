@extends('layouts.admin')
@section('title', 'Newsletter Subscribers')
@section('page-title', 'Newsletter Subscribers')

@section('content')
{{-- Stats --}}
<div class="grid-4" style="margin-bottom: 2rem;">
    <div class="card" style="text-align:center; padding: 1.5rem;">
        <div style="font-size: 2rem; font-weight: 800; color: #3b82f6;">{{ $stats['total'] }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Total Subscribers</div>
    </div>
    <div class="card" style="text-align:center; padding: 1.5rem;">
        <div style="font-size: 2rem; font-weight: 800; color: #22c55e;">{{ $stats['active'] }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Active</div>
    </div>
    <div class="card" style="text-align:center; padding: 1.5rem;">
        <div style="font-size: 2rem; font-weight: 800; color: #ef4444;">{{ $stats['unsubscribed'] }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Unsubscribed</div>
    </div>
    <div class="card" style="text-align:center; padding: 1.5rem;">
        <div style="font-size: 2rem; font-weight: 800; color: #f59e0b;">{{ $stats['this_month'] }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">This Month</div>
    </div>
</div>

{{-- Filters + Export --}}
<div class="card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 1; min-width: 200px;">
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Search Email</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by email..." style="background: var(--bg-card); border: 1px solid var(--border); color: white; padding: 0.6rem 0.9rem; border-radius: 8px; width: 100%;">
        </div>
        <div style="min-width: 140px;">
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Status</label>
            <select name="status" style="background: var(--bg-card); border: 1px solid var(--border); color: white; padding: 0.6rem 0.9rem; border-radius: 8px; width: 100%;">
                <option value="">All</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="unsubscribed" {{ request('status') == 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.25rem;">Filter</button>
        <a href="{{ route('admin.newsletter.export') }}" class="btn btn-outline" style="padding: 0.6rem 1.25rem;">
            <i class="fas fa-download"></i> Export CSV
        </a>
    </form>
</div>

{{-- Subscribers Table --}}
<div class="card">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border);">
        <h2 style="font-size: 1rem; font-weight: 600; margin: 0;">Subscribers ({{ $subscribers->total() }})</h2>
    </div>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 0.75rem 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Email</th>
                    <th style="padding: 0.75rem 1rem; text-align: center; color: var(--text-muted); font-weight: 600;">Status</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Subscribed</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Unsubscribed</th>
                    <th style="padding: 0.75rem 1rem; text-align: center; color: var(--text-muted); font-weight: 600;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $sub)
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 0.75rem 1rem; color: var(--text-primary);">{{ $sub->email }}</td>
                    <td style="padding: 0.75rem 1rem; text-align: center;">
                        @if($sub->unsubscribed_at)
                        <span style="background: rgba(239,68,68,0.1); color: #ef4444; font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 20px;">Unsubscribed</span>
                        @else
                        <span style="background: rgba(34,197,94,0.1); color: #22c55e; font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 20px;">Active</span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem 1rem; color: var(--text-muted); font-size: 0.8rem;">{{ $sub->subscribed_at?->format('M d, Y H:i') }}</td>
                    <td style="padding: 0.75rem 1rem; color: var(--text-muted); font-size: 0.8rem;">{{ $sub->unsubscribed_at?->format('M d, Y H:i') ?? '—' }}</td>
                    <td style="padding: 0.75rem 1rem; text-align: center;">
                        <form method="POST" action="{{ route('admin.newsletter.destroy', $sub) }}" style="display:inline;" onsubmit="return confirm('Permanently delete this subscriber?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background: rgba(239,68,68,0.1); color: #ef4444; border: none; padding: 0.3rem 0.6rem; border-radius: 6px; cursor: pointer; font-size: 0.8rem;"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        <i class="fas fa-envelope" style="font-size: 2rem; margin-bottom: 0.75rem; display: block; opacity: 0.3;"></i>
                        No subscribers yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($subscribers->hasPages())
    <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border);">
        {{ $subscribers->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
