@extends('layouts.superadmin')
@section('title', 'AI Conversations')
@section('page_title', 'AI Conversations')

@section('content')
<style>
.th-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; }
.th-stat-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:1rem; margin-bottom:1.5rem; }
.th-stat { background:var(--sa-card-bg,#1e293b); border:1px solid var(--sa-border,#334155); border-radius:12px; padding:1.25rem; text-align:center; }
.th-stat-val { font-size:2rem; font-weight:700; color:#6366f1; }
.th-stat-lbl { font-size:.78rem; color:#94a3b8; margin-top:.25rem; text-transform:uppercase; letter-spacing:.05em; }
.th-filter-bar { display:flex; gap:.75rem; flex-wrap:wrap; margin-bottom:1.25rem; align-items:center; }
.th-filter-bar input, .th-filter-bar select { background:#0f172a; border:1px solid #334155; color:#e2e8f0; border-radius:8px; padding:.5rem .9rem; font-size:.875rem; }
.th-filter-bar input:focus, .th-filter-bar select:focus { outline:none; border-color:#6366f1; }
.th-table-wrap { background:var(--sa-card-bg,#1e293b); border:1px solid var(--sa-border,#334155); border-radius:12px; overflow:hidden; }
.th-table { width:100%; border-collapse:collapse; }
.th-table th { background:#0f172a; color:#94a3b8; font-size:.75rem; text-transform:uppercase; letter-spacing:.06em; padding:.85rem 1rem; text-align:left; border-bottom:1px solid #334155; }
.th-table td { padding:.85rem 1rem; border-bottom:1px solid #1e293b; font-size:.875rem; color:#cbd5e1; vertical-align:middle; }
.th-table tr:last-child td { border-bottom:none; }
.th-table tr:hover td { background:rgba(99,102,241,.06); }
.th-badge { display:inline-block; padding:.2rem .65rem; border-radius:20px; font-size:.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.04em; }
.th-badge-sales { background:rgba(16,185,129,.15); color:#10b981; }
.th-badge-support { background:rgba(245,158,11,.15); color:#f59e0b; }
.th-badge-general { background:rgba(99,102,241,.15); color:#818cf8; }
.th-tab-bar { display:flex; gap:.5rem; margin-bottom:1.25rem; flex-wrap:wrap; }
.th-tab { padding:.45rem 1rem; border-radius:20px; font-size:.8rem; font-weight:600; border:1px solid #334155; background:transparent; color:#94a3b8; cursor:pointer; text-decoration:none; transition:all .15s; }
.th-tab.active, .th-tab:hover { background:#6366f1; color:#fff; border-color:#6366f1; }
.th-intent-filter { display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:1.25rem; }
.th-intent-btn { padding:.35rem .9rem; border-radius:20px; font-size:.8rem; font-weight:600; border:1px solid #334155; background:transparent; color:#94a3b8; cursor:pointer; text-decoration:none; transition:all .15s; }
.th-intent-btn.active { background:#6366f1; color:#fff; border-color:#6366f1; }
.th-intent-btn.sales.active { background:#10b981; border-color:#10b981; }
.th-intent-btn.support.active { background:#f59e0b; border-color:#f59e0b; color:#0f172a; }
.th-lead-info { display:flex; flex-direction:column; gap:.15rem; }
.th-lead-name { font-weight:600; color:#e2e8f0; }
.th-lead-meta { font-size:.78rem; color:#64748b; }
.th-view-btn { background:rgba(99,102,241,.2); color:#818cf8; border:none; padding:.35rem .75rem; border-radius:6px; font-size:.75rem; font-weight:600; cursor:pointer; text-decoration:none; display:inline-block; }
.th-del-btn { background:rgba(239,68,68,.15); color:#f87171; border:none; padding:.35rem .75rem; border-radius:6px; font-size:.75rem; font-weight:600; cursor:pointer; }
.th-view-btn:hover, .th-del-btn:hover { opacity:.8; }
</style>

{{-- Header --}}
<div class="th-header">
    <div>
        <h2 style="font-size:1.3rem;font-weight:700;color:#e2e8f0;margin:0;">AI Conversations</h2>
        <p style="color:#64748b;font-size:.875rem;margin:.25rem 0 0;">All conversations with Xena — Xenoraa's AI assistant</p>
    </div>
</div>

{{-- Tab Bar --}}
<div class="th-tab-bar">
    <a href="{{ route('superadmin.training-hub.training') }}" class="th-tab">
        <i class="fas fa-brain"></i> AI Training
    </a>
    <a href="{{ route('superadmin.training-hub.conversations') }}" class="th-tab active">
        <i class="fas fa-comments"></i> AI Conversations
    </a>
</div>

{{-- Stats --}}
<div class="th-stat-grid">
    <div class="th-stat">
        <div class="th-stat-val">{{ $stats['total_sessions'] }}</div>
        <div class="th-stat-lbl">Total Sessions</div>
    </div>
    <div class="th-stat">
        <div class="th-stat-val" style="color:#10b981;">{{ $stats['total_messages'] }}</div>
        <div class="th-stat-lbl">Total Messages</div>
    </div>
    <div class="th-stat">
        <div class="th-stat-val" style="color:#f59e0b;">{{ $stats['leads_captured'] }}</div>
        <div class="th-stat-lbl">Leads Captured</div>
    </div>
    <div class="th-stat">
        <div class="th-stat-val" style="color:#8b5cf6;">{{ $stats['today_sessions'] }}</div>
        <div class="th-stat-lbl">Today's Sessions</div>
    </div>
</div>

{{-- Intent Filter --}}
<div class="th-intent-filter">
    <a href="{{ route('superadmin.training-hub.conversations') }}" class="th-intent-btn {{ !$intent ? 'active' : '' }}">
        All
    </a>
    <a href="{{ route('superadmin.training-hub.conversations', ['intent' => 'sales']) }}" class="th-intent-btn sales {{ $intent === 'sales' ? 'active' : '' }}">
        <i class="fas fa-handshake"></i> Sales
    </a>
    <a href="{{ route('superadmin.training-hub.conversations', ['intent' => 'support']) }}" class="th-intent-btn support {{ $intent === 'support' ? 'active' : '' }}">
        <i class="fas fa-life-ring"></i> Support
    </a>
    <a href="{{ route('superadmin.training-hub.conversations', ['intent' => 'general']) }}" class="th-intent-btn {{ $intent === 'general' ? 'active' : '' }}">
        <i class="fas fa-comment"></i> General
    </a>
</div>

{{-- Filter Bar --}}
<form method="GET" class="th-filter-bar">
    @if($intent)<input type="hidden" name="intent" value="{{ $intent }}">@endif
    <input type="date" name="date_from" value="{{ $dateFrom }}" title="From date">
    <input type="date" name="date_to" value="{{ $dateTo }}" title="To date">
    <button type="submit" style="background:#6366f1;color:#fff;border:none;padding:.5rem 1rem;border-radius:8px;cursor:pointer;font-size:.875rem;">
        <i class="fas fa-filter"></i> Filter
    </button>
    @if($dateFrom || $dateTo)
    <a href="{{ route('superadmin.training-hub.conversations', $intent ? ['intent' => $intent] : []) }}" style="background:#334155;color:#94a3b8;padding:.5rem 1rem;border-radius:8px;font-size:.875rem;text-decoration:none;">
        <i class="fas fa-times"></i> Clear
    </a>
    @endif
</form>

{{-- Table --}}
<div class="th-table-wrap">
    <table class="th-table">
        <thead>
            <tr>
                <th>Visitor / Lead</th>
                <th>Intent</th>
                <th>Messages</th>
                <th>Started</th>
                <th>Last Activity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sessions as $session)
            @php
                $lead = $leads[$session->lead_id] ?? null;
                $sessionIntent = $lead ? (str_contains(strtolower($lead->notes ?? ''), 'sales') ? 'sales' : (str_contains(strtolower($lead->notes ?? ''), 'support') ? 'support' : 'general')) : 'general';
            @endphp
            <tr>
                <td>
                    <div class="th-lead-info">
                        <div class="th-lead-name">{{ $lead?->name ?? 'Anonymous Visitor' }}</div>
                        @if($lead?->email)
                        <div class="th-lead-meta"><i class="fas fa-envelope" style="width:12px;"></i> {{ $lead->email }}</div>
                        @endif
                        @if($lead?->mobile)
                        <div class="th-lead-meta"><i class="fas fa-phone" style="width:12px;"></i> {{ $lead->mobile }}</div>
                        @endif
                        <div class="th-lead-meta" style="color:#475569;font-size:.72rem;">{{ substr($session->session_id, 0, 20) }}...</div>
                    </div>
                </td>
                <td>
                    <span class="th-badge th-badge-{{ $sessionIntent }}">{{ ucfirst($sessionIntent) }}</span>
                </td>
                <td>
                    <span style="font-weight:700;color:#e2e8f0;">{{ $session->message_count }}</span>
                    <span style="color:#64748b;font-size:.78rem;"> msgs</span>
                </td>
                <td style="color:#94a3b8;font-size:.8rem;">
                    {{ \Carbon\Carbon::parse($session->started_at)->format('d M Y') }}<br>
                    <span style="color:#475569;">{{ \Carbon\Carbon::parse($session->started_at)->format('h:i A') }}</span>
                </td>
                <td style="color:#94a3b8;font-size:.8rem;">
                    {{ \Carbon\Carbon::parse($session->last_at)->diffForHumans() }}
                </td>
                <td>
                    <div style="display:flex;gap:.5rem;">
                        <a href="{{ route('superadmin.training-hub.conversations.detail', $session->session_id) }}" class="th-view-btn">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <form method="POST" action="{{ route('superadmin.training-hub.conversations.destroy', $session->session_id) }}" onsubmit="return confirm('Delete this conversation?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="th-del-btn"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:2.5rem;color:#475569;">
                    <i class="fas fa-comments" style="font-size:2rem;margin-bottom:.75rem;display:block;opacity:.3;"></i>
                    No conversations yet. Once visitors chat with Xena, they'll appear here.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($sessions->hasPages())
<div style="margin-top:1rem;">{{ $sessions->links() }}</div>
@endif
@endsection
