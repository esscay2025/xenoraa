@extends('layouts.admin')
@section('title', 'Leads')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Leads</h1><p class="page-subtitle">All leads — manual, AI chatbot, website, referral and more</p></div>
    <a href="{{ route('admin.newcrm.leads.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Lead</a>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:1rem;margin-bottom:1.5rem">
    @foreach([['Total','total','#6366f1','fa-users'],['New','new','#3b82f6','fa-star'],['AI Leads','ai','#8b5cf6','fa-robot'],['Manual','manual','#6b7280','fa-pencil-alt'],['Converted','converted','#22c55e','fa-check-circle']] as [$label,$key,$color,$icon])
    <div class="stat-card" style="border-left:3px solid {{ $color }}">
        <div style="display:flex;align-items:center;gap:.75rem">
            <div style="width:36px;height:36px;border-radius:50%;background:{{ $color }}22;display:flex;align-items:center;justify-content:center;color:{{ $color }}"><i class="fas {{ $icon }}"></i></div>
            <div><div style="font-size:1.5rem;font-weight:700;color:{{ $color }}">{{ $stats[$key] }}</div><div style="font-size:.75rem;color:var(--text-muted)">{{ $label }}</div></div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<form method="GET" class="card" style="padding:1rem;margin-bottom:1.5rem;display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
    <div style="flex:1;min-width:200px"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, company..." class="form-control"></div>
    <select name="status" class="form-control" style="width:150px">
        <option value="">All Status</option>
        @foreach(['new','contacted','qualified','proposal','converted','lost'] as $s)
        <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <select name="source" class="form-control" style="width:160px">
        <option value="">All Sources</option>
        @foreach(['manual','ai_chatbot','website_form','referral','linkedin','cold_outreach','other'] as $s)
        <option value="{{ $s }}" {{ request('source')==$s?'selected':'' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
        @endforeach
    </select>
    <select name="priority" class="form-control" style="width:130px">
        <option value="">All Priority</option>
        @foreach(['low','medium','high','urgent'] as $p)
        <option value="{{ $p }}" {{ request('priority')==$p?'selected':'' }}>{{ ucfirst($p) }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    @if(request()->hasAny(['search','status','source','priority']))<a href="{{ route('admin.newcrm.leads') }}" class="btn btn-secondary">Clear</a>@endif
</form>

<div class="card">
    <div class="card-body" style="padding:0">
        <table class="table">
            <thead><tr><th>Lead</th><th>Company</th><th>Source</th><th>Priority</th><th>Status</th><th>Value</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($leads as $lead)
                @php
                    $srcColors = ['ai_chatbot'=>'#6366f1','manual'=>'#6b7280','website_form'=>'#3b82f6','referral'=>'#22c55e','linkedin'=>'#0ea5e9','cold_outreach'=>'#f97316','other'=>'#6b7280'];
                    $statusColors = ['new'=>'#6366f1','contacted'=>'#3b82f6','qualified'=>'#f59e0b','proposal'=>'#f97316','converted'=>'#22c55e','lost'=>'#ef4444'];
                    $priColors = ['low'=>'#6b7280','medium'=>'#3b82f6','high'=>'#f59e0b','urgent'=>'#ef4444'];
                @endphp
                <tr>
                    <td>
                        <div style="font-weight:600">{{ $lead->name }}</div>
                        <div style="font-size:.78rem;color:var(--text-muted)">{{ $lead->email }}</div>
                    </td>
                    <td>{{ $lead->company ?? '—' }}</td>
                    <td>
                        <span class="badge" style="background:{{ $srcColors[$lead->source ?? 'manual'] ?? '#6b7280' }}22;color:{{ $srcColors[$lead->source ?? 'manual'] ?? '#6b7280' }}">
                            @if(($lead->source ?? 'manual') === 'ai_chatbot')<i class="fas fa-robot fa-xs"></i> @endif
                            {{ ucwords(str_replace('_',' ',$lead->source ?? 'manual')) }}
                        </span>
                    </td>
                    <td><span class="badge" style="background:{{ $priColors[$lead->priority ?? 'medium'] }}22;color:{{ $priColors[$lead->priority ?? 'medium'] }}">{{ ucfirst($lead->priority ?? 'medium') }}</span></td>
                    <td><span class="badge" style="background:{{ $statusColors[$lead->status] ?? '#6b7280' }}22;color:{{ $statusColors[$lead->status] ?? '#6b7280' }}">{{ ucfirst($lead->status) }}</span></td>
                    <td>{{ $lead->deal_value ? '₹'.number_format($lead->deal_value,0) : '—' }}</td>
                    <td style="font-size:.78rem;color:var(--text-muted)">{{ $lead->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:.4rem">
                            <a href="{{ route('admin.newcrm.leads.show', $lead) }}" class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.newcrm.leads.edit', $lead) }}" class="btn btn-sm btn-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                            @if($lead->status !== 'converted')
                            <button onclick="openConvertModal({{ $lead->id }}, '{{ addslashes($lead->name) }}')" class="btn btn-sm" style="background:#22c55e22;color:#22c55e;border:none;cursor:pointer" title="Convert to Deal"><i class="fas fa-exchange-alt"></i></button>
                            @endif
                            <form method="POST" action="{{ route('admin.newcrm.leads.destroy', $lead) }}" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#ef444422;color:#ef4444;border:none;cursor:pointer"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:3rem;color:var(--text-muted)">No leads yet. <a href="{{ route('admin.newcrm.leads.create') }}">Add your first lead</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $leads->withQueryString()->links() }}

{{-- Convert to Deal Modal --}}
<div id="convertModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:9999;align-items:center;justify-content:center">
    <div style="background:var(--bg-card);border-radius:1rem;padding:2rem;width:100%;max-width:480px;margin:1rem">
        <h3 style="margin-bottom:1.5rem">Convert Lead to Deal</h3>
        <form id="convertForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Deal Title *</label>
                <input type="text" name="deal_title" id="convertDealTitle" class="form-control" required>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div class="form-group">
                    <label class="form-label">Deal Value (₹)</label>
                    <input type="number" name="deal_value" class="form-control" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Stage *</label>
                    <select name="stage" class="form-control" required>
                        @foreach(['prospecting','qualification','proposal','negotiation'] as $s)
                        <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Expected Close Date</label>
                <input type="date" name="expected_close" class="form-control">
            </div>
            <div style="display:flex;gap:1rem;margin-top:1rem">
                <button type="submit" class="btn btn-primary" style="flex:1"><i class="fas fa-exchange-alt"></i> Convert</button>
                <button type="button" onclick="closeConvertModal()" class="btn btn-secondary" style="flex:1">Cancel</button>
            </div>
        </form>
    </div>
</div>
<script>
function openConvertModal(leadId, leadName) {
    document.getElementById('convertForm').action = '/admin/newcrm/leads/' + leadId + '/convert';
    document.getElementById('convertDealTitle').value = leadName + ' — Deal';
    document.getElementById('convertModal').style.display = 'flex';
}
function closeConvertModal() {
    document.getElementById('convertModal').style.display = 'none';
}
</script>
@endsection
