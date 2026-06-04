@extends('layouts.admin')
@section('title', 'Activities')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Activities</h1><p class="page-subtitle">Calls, emails, meetings, tasks and follow-ups</p></div>
    <button onclick="document.getElementById('newActivityModal').style.display='flex'" class="btn btn-primary"><i class="fas fa-plus"></i> Log Activity</button>
</div>

{{-- Filters --}}
<form method="GET" class="card" style="padding:1rem;margin-bottom:1.5rem;display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
    <select name="type" class="form-control" style="width:150px">
        <option value="">All Types</option>
        @foreach(\App\Models\CrmActivity::TYPES as $k => $info)
        <option value="{{ $k }}" {{ request('type')==$k?'selected':'' }}>{{ $info['label'] }}</option>
        @endforeach
    </select>
    <select name="status" class="form-control" style="width:140px">
        <option value="">All Status</option>
        <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
        <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
    </select>
    <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    @if(request()->hasAny(['type','status']))<a href="{{ route('admin.newcrm.activities') }}" class="btn btn-secondary">Clear</a>@endif
</form>

<div class="card">
    <div class="card-body" style="padding:0">
        <table class="table">
            <thead><tr><th>Type</th><th>Subject</th><th>Related To</th><th>Due Date</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($activities as $act)
                @php $typeInfo = \App\Models\CrmActivity::TYPES[$act->type] ?? ['icon'=>'fa-circle','color'=>'#6366f1','label'=>ucfirst($act->type)]; @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <div style="width:28px;height:28px;border-radius:50%;background:{{ $typeInfo['color'] }}22;display:flex;align-items:center;justify-content:center;color:{{ $typeInfo['color'] }}">
                                <i class="fas {{ $typeInfo['icon'] }} fa-xs"></i>
                            </div>
                            <span style="font-size:.8rem">{{ $typeInfo['label'] }}</span>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600">{{ $act->subject }}</div>
                        @if($act->description)<div style="font-size:.78rem;color:var(--text-muted)">{{ Str::limit($act->description, 60) }}</div>@endif
                    </td>
                    <td style="font-size:.875rem;color:var(--text-secondary)">
                        @if($act->related_type && $act->related)
                            {{ class_basename($act->related_type) }}: {{ $act->related->name ?? ($act->related->first_name ?? $act->related->title ?? '—') }}
                        @else —
                        @endif
                    </td>
                    <td style="font-size:.875rem">
                        @if($act->due_at)
                            @php $overdue = $act->status !== 'completed' && $act->due_at->isPast(); @endphp
                            <span style="color:{{ $overdue ? '#ef4444' : 'var(--text-secondary)' }}">
                                {{ $act->due_at->format('d M Y, h:i A') }}
                                @if($overdue)<span style="font-size:.7rem;color:#ef4444"> (Overdue)</span>@endif
                            </span>
                        @else —
                        @endif
                    </td>
                    <td>
                        <span class="badge" style="background:{{ $act->status==='completed' ? '#22c55e22' : '#f59e0b22' }};color:{{ $act->status==='completed' ? '#22c55e' : '#f59e0b' }}">{{ ucfirst($act->status) }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:.4rem">
                            @if($act->status !== 'completed')
                            <form method="POST" action="{{ route('admin.newcrm.activities.complete', $act) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm" style="background:#22c55e22;color:#22c55e;border:none;cursor:pointer" title="Mark complete"><i class="fas fa-check"></i></button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.newcrm.activities.destroy', $act) }}" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#ef444422;color:#ef4444;border:none;cursor:pointer"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:3rem;color:var(--text-muted)">No activities yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $activities->withQueryString()->links() }}

{{-- New Activity Modal --}}
<div id="newActivityModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:9999;align-items:center;justify-content:center">
    <div style="background:var(--bg-card);border-radius:1rem;padding:2rem;width:100%;max-width:520px;margin:1rem">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
            <h3>Log New Activity</h3>
            <button onclick="document.getElementById('newActivityModal').style.display='none'" style="background:none;border:none;color:var(--text-muted);font-size:1.25rem;cursor:pointer">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.newcrm.activities.store') }}">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div class="form-group">
                    <label class="form-label">Type *</label>
                    <select name="type" class="form-control" required>
                        @foreach(\App\Models\CrmActivity::TYPES as $k => $info)
                        <option value="{{ $k }}">{{ $info['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Subject *</label>
                <input type="text" name="subject" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea name="description" class="form-control" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Due Date &amp; Time</label>
                <input type="datetime-local" name="due_at" class="form-control">
            </div>
            <div style="display:flex;gap:1rem;margin-top:1rem">
                <button type="submit" class="btn btn-primary" style="flex:1"><i class="fas fa-save"></i> Log Activity</button>
                <button type="button" onclick="document.getElementById('newActivityModal').style.display='none'" class="btn btn-secondary" style="flex:1">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
