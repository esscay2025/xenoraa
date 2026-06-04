@extends('layouts.admin')
@section('title', 'Pipeline & Deals')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Pipeline &amp; Deals</h1><p class="page-subtitle">Visual Kanban pipeline and deal management</p></div>
    <a href="{{ route('admin.newcrm.deals.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Deal</a>
</div>

{{-- View Toggle --}}
<div style="display:flex;gap:.5rem;margin-bottom:1.5rem">
    <button onclick="showView('kanban')" id="btnKanban" class="btn btn-primary">
        <i class="fas fa-th-large"></i> Kanban
    </button>
    <button onclick="showView('list')" id="btnList" class="btn btn-secondary">
        <i class="fas fa-list"></i> List
    </button>
</div>

{{-- Kanban View --}}
<div id="viewKanban">
    <div style="display:grid;grid-template-columns:repeat(6,minmax(200px,1fr));gap:1rem;overflow-x:auto;padding-bottom:1rem">
        @foreach($pipeline as $stageKey => $stageData)
        <div style="background:var(--bg-secondary);border-radius:.75rem;padding:1rem;min-height:300px" data-stage="{{ $stageKey }}">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
                <div>
                    <div style="font-weight:700;font-size:.875rem;color:{{ $stageData['info']['color'] }}">{{ $stageData['info']['label'] }}</div>
                    <div style="font-size:.75rem;color:var(--text-muted)">₹{{ number_format($stageData['total'],0) }}</div>
                </div>
                <span style="background:{{ $stageData['info']['color'] }}22;color:{{ $stageData['info']['color'] }};border-radius:999px;padding:.15rem .5rem;font-size:.75rem;font-weight:700">{{ $stageData['deals']->count() }}</span>
            </div>
            @foreach($stageData['deals'] as $deal)
            <div class="deal-card" data-deal-id="{{ $deal->id }}" style="background:var(--bg-card);border-radius:.5rem;padding:.75rem;margin-bottom:.5rem;border:1px solid var(--border-color);cursor:pointer;transition:transform .15s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                <div style="font-weight:600;font-size:.875rem;margin-bottom:.25rem">{{ Str::limit($deal->title, 30) }}</div>
                @if($deal->account)<div style="font-size:.75rem;color:var(--text-muted)">{{ $deal->account->name }}</div>@endif
                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:.5rem">
                    <span style="font-weight:700;color:#22c55e;font-size:.875rem">₹{{ number_format($deal->value,0) }}</span>
                    <span style="font-size:.7rem;color:var(--text-muted)">{{ $deal->probability }}%</span>
                </div>
                @if($deal->expected_close)
                <div style="font-size:.7rem;color:var(--text-muted);margin-top:.25rem"><i class="fas fa-calendar fa-xs"></i> {{ $deal->expected_close->format('d M') }}</div>
                @endif
                <div style="display:flex;gap:.25rem;margin-top:.5rem">
                    <a href="{{ route('admin.newcrm.deals.edit', $deal) }}" class="btn btn-sm" style="padding:.2rem .5rem;font-size:.7rem;background:var(--bg-hover);color:var(--text-secondary);border:none"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="{{ route('admin.newcrm.deals.destroy', $deal) }}" onsubmit="return confirm('Delete?')" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="padding:.2rem .5rem;font-size:.7rem;background:#ef444411;color:#ef4444;border:none;cursor:pointer"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>

{{-- List View --}}
<div id="viewList" style="display:none">
    <form method="GET" class="card" style="padding:1rem;margin-bottom:1.5rem;display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
        <div style="flex:1;min-width:200px"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search deals..." class="form-control"></div>
        <select name="stage" class="form-control" style="width:160px">
            <option value="">All Stages</option>
            @foreach(\App\Models\CrmDeal::STAGES as $k => $info)
            <option value="{{ $k }}" {{ request('stage')==$k?'selected':'' }}>{{ $info['label'] }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
    <div class="card">
        <div class="card-body" style="padding:0">
            <table class="table">
                <thead><tr><th>Deal</th><th>Account</th><th>Stage</th><th>Value</th><th>Probability</th><th>Close Date</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($deals as $deal)
                    <tr>
                        <td><div style="font-weight:600">{{ $deal->title }}</div></td>
                        <td>{{ $deal->account?->name ?? $deal->contact?->first_name ?? '—' }}</td>
                        <td><span class="badge" style="background:{{ $deal->stageColor }}22;color:{{ $deal->stageColor }}">{{ $deal->stageLabel }}</span></td>
                        <td style="font-weight:700;color:#22c55e">₹{{ number_format($deal->value,0) }}</td>
                        <td>{{ $deal->probability }}%</td>
                        <td style="font-size:.875rem;color:var(--text-muted)">{{ $deal->expected_close?->format('d M Y') ?? '—' }}</td>
                        <td>
                            <div style="display:flex;gap:.5rem">
                                <a href="{{ route('admin.newcrm.deals.edit', $deal) }}" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('admin.newcrm.deals.destroy', $deal) }}" onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background:#ef444422;color:#ef4444;border:none;cursor:pointer"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--text-muted)">No deals yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $deals->withQueryString()->links() }}
</div>

<script>
function showView(view) {
    document.getElementById('viewKanban').style.display = view === 'kanban' ? 'block' : 'none';
    document.getElementById('viewList').style.display = view === 'list' ? 'block' : 'none';
    document.getElementById('btnKanban').className = view === 'kanban' ? 'btn btn-primary' : 'btn btn-secondary';
    document.getElementById('btnList').className = view === 'list' ? 'btn btn-primary' : 'btn btn-secondary';
    localStorage.setItem('crmDealsView', view);
}
// Restore last view
const savedView = localStorage.getItem('crmDealsView') || 'kanban';
showView(savedView);
</script>
@endsection
