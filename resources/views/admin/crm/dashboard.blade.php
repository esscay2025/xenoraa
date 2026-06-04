@extends('layouts.admin')
@section('title', 'CRM Dashboard')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">CRM Dashboard</h1>
        <p class="page-subtitle">Your sales pipeline at a glance</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.newcrm.leads.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Lead</a>
        <a href="{{ route('admin.newcrm.deals.create') }}" class="btn btn-secondary"><i class="fas fa-handshake"></i> New Deal</a>
    </div>
</div>

{{-- Stats Row --}}
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit,minmax(160px,1fr));gap:1rem;margin-bottom:2rem;">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i class="fas fa-building"></i></div>
        <div class="stat-value">{{ $stats['accounts'] }}</div>
        <div class="stat-label">Accounts</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(139,92,246,.15);color:#8b5cf6"><i class="fas fa-address-book"></i></div>
        <div class="stat-value">{{ $stats['contacts'] }}</div>
        <div class="stat-label">Contacts</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(245,158,11,.15);color:#f59e0b"><i class="fas fa-user-tag"></i></div>
        <div class="stat-value">{{ $stats['leads'] }}</div>
        <div class="stat-label">Total Leads</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(249,115,22,.15);color:#f97316"><i class="fas fa-funnel-dollar"></i></div>
        <div class="stat-value">{{ $stats['open_deals'] }}</div>
        <div class="stat-label">Open Deals</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(34,197,94,.15);color:#22c55e"><i class="fas fa-trophy"></i></div>
        <div class="stat-value">₹{{ number_format($stats['won_value'], 0) }}</div>
        <div class="stat-label">Won Value</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(59,130,246,.15);color:#3b82f6"><i class="fas fa-chart-line"></i></div>
        <div class="stat-value">₹{{ number_format($stats['pipeline_value'], 0) }}</div>
        <div class="stat-label">Pipeline Value</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem;">
    {{-- Recent Leads --}}
    <div class="card">
        <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
            <h3 class="card-title"><i class="fas fa-user-tag" style="color:#f59e0b"></i> Recent Leads</h3>
            <a href="{{ route('admin.newcrm.leads') }}" class="btn btn-sm btn-secondary">View All</a>
        </div>
        <div class="card-body" style="padding:0">
            @forelse($recentLeads as $lead)
            <div style="padding:.75rem 1.25rem;border-bottom:1px solid var(--border-color);display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-weight:600;font-size:.9rem">{{ $lead->name }}</div>
                    <div style="font-size:.78rem;color:var(--text-muted)">{{ $lead->company ?? $lead->email }}</div>
                </div>
                <div style="display:flex;gap:.5rem;align-items:center;">
                    @php $src = $lead->source ?? 'manual'; $srcColors = ['ai_chatbot'=>'#6366f1','manual'=>'#6b7280','website_form'=>'#3b82f6','referral'=>'#22c55e','linkedin'=>'#0ea5e9']; @endphp
                    <span style="font-size:.7rem;padding:.2rem .5rem;border-radius:999px;background:{{ $srcColors[$src] ?? '#6b7280' }}22;color:{{ $srcColors[$src] ?? '#6b7280' }}">{{ ucfirst(str_replace('_',' ',$src)) }}</span>
                    @php $sc = ['new'=>'#6366f1','contacted'=>'#3b82f6','qualified'=>'#f59e0b','proposal'=>'#f97316','converted'=>'#22c55e','lost'=>'#ef4444']; @endphp
                    <span style="font-size:.7rem;padding:.2rem .5rem;border-radius:999px;background:{{ $sc[$lead->status] ?? '#6b7280' }}22;color:{{ $sc[$lead->status] ?? '#6b7280' }}">{{ ucfirst($lead->status) }}</span>
                </div>
            </div>
            @empty
            <div style="padding:2rem;text-align:center;color:var(--text-muted)">No leads yet. <a href="{{ route('admin.newcrm.leads.create') }}">Add one</a></div>
            @endforelse
        </div>
    </div>

    {{-- Recent Deals --}}
    <div class="card">
        <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
            <h3 class="card-title"><i class="fas fa-funnel-dollar" style="color:#f97316"></i> Recent Deals</h3>
            <a href="{{ route('admin.newcrm.deals') }}" class="btn btn-sm btn-secondary">View All</a>
        </div>
        <div class="card-body" style="padding:0">
            @forelse($recentDeals as $deal)
            <div style="padding:.75rem 1.25rem;border-bottom:1px solid var(--border-color);display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-weight:600;font-size:.9rem">{{ $deal->title }}</div>
                    <div style="font-size:.78rem;color:var(--text-muted)">{{ $deal->account?->name ?? $deal->contact?->first_name ?? '—' }}</div>
                </div>
                <div style="text-align:right">
                    <div style="font-weight:700;color:#22c55e;font-size:.9rem">₹{{ number_format($deal->value, 0) }}</div>
                    <span style="font-size:.7rem;padding:.2rem .5rem;border-radius:999px;background:{{ $deal->stageColor }}22;color:{{ $deal->stageColor }}">{{ $deal->stageLabel }}</span>
                </div>
            </div>
            @empty
            <div style="padding:2rem;text-align:center;color:var(--text-muted)">No deals yet. <a href="{{ route('admin.newcrm.deals.create') }}">Create one</a></div>
            @endforelse
        </div>
    </div>
</div>

{{-- Pipeline by Stage --}}
<div class="card" style="margin-bottom:2rem">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-chart-bar" style="color:#6366f1"></i> Pipeline by Stage</h3>
    </div>
    <div class="card-body">
        <div style="display:flex;gap:1rem;flex-wrap:wrap;">
            @foreach(\App\Models\CrmDeal::STAGES as $key => $info)
            @php $stageData = $dealsByStage->firstWhere('stage', $key); @endphp
            <div style="flex:1;min-width:120px;background:var(--bg-card);border-radius:.75rem;padding:1rem;border:1px solid {{ $info['color'] }}33;text-align:center;">
                <div style="font-size:.75rem;color:var(--text-muted);margin-bottom:.25rem">{{ $info['label'] }}</div>
                <div style="font-size:1.5rem;font-weight:700;color:{{ $info['color'] }}">{{ $stageData?->count ?? 0 }}</div>
                <div style="font-size:.75rem;color:var(--text-secondary)">₹{{ number_format($stageData?->total ?? 0, 0) }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Upcoming Activities --}}
<div class="card">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <h3 class="card-title"><i class="fas fa-tasks" style="color:#22c55e"></i> Upcoming Activities</h3>
        <a href="{{ route('admin.newcrm.activities') }}" class="btn btn-sm btn-secondary">View All</a>
    </div>
    <div class="card-body" style="padding:0">
        @forelse($upcomingActivities as $act)
        @php $typeInfo = \App\Models\CrmActivity::TYPES[$act->type] ?? ['icon'=>'fa-circle','color'=>'#6366f1']; @endphp
        <div style="padding:.75rem 1.25rem;border-bottom:1px solid var(--border-color);display:flex;align-items:center;gap:1rem;">
            <div style="width:36px;height:36px;border-radius:50%;background:{{ $typeInfo['color'] }}22;display:flex;align-items:center;justify-content:center;color:{{ $typeInfo['color'] }};flex-shrink:0">
                <i class="fas {{ $typeInfo['icon'] }}"></i>
            </div>
            <div style="flex:1">
                <div style="font-weight:600;font-size:.9rem">{{ $act->subject }}</div>
                <div style="font-size:.78rem;color:var(--text-muted)">{{ $act->due_at?->format('d M Y, h:i A') }}</div>
            </div>
            <form method="POST" action="{{ route('admin.newcrm.activities.complete', $act) }}">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm" style="background:#22c55e22;color:#22c55e;border:none;cursor:pointer"><i class="fas fa-check"></i></button>
            </form>
        </div>
        @empty
        <div style="padding:2rem;text-align:center;color:var(--text-muted)">No upcoming activities.</div>
        @endforelse
    </div>
</div>
@endsection
