@extends('layouts.admin')
@section('title', 'Projects')
@section('page-title', 'Projects')
@push('styles')
<style>
.xn-bulk-btn{position:relative;display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.06);color:var(--crm2-text);transition:background .2s}
.xn-bulk-btn:hover{background:rgba(255,255,255,.12)}
.xn-bulk-menu{position:absolute;top:calc(100% + 6px);right:0;min-width:190px;background:var(--crm2-card-bg);border:1px solid var(--crm2-border);border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.35);z-index:999;padding:6px 0;display:none}
.xn-bulk-menu.open{display:block}
.xn-bulk-menu a,.xn-bulk-menu button{display:flex;align-items:center;gap:10px;width:100%;padding:9px 16px;font-size:13px;color:var(--crm2-text);background:none;border:none;cursor:pointer;text-decoration:none;transition:background .15s}
.xn-bulk-menu a:hover,.xn-bulk-menu button:hover{background:rgba(255,255,255,.07)}
.xn-bulk-menu .menu-divider{height:1px;background:var(--crm2-border);margin:4px 0}
.xn-bulk-menu .danger{color:#ef4444}
.xn-sel-badge{display:none;align-items:center;gap:6px;padding:5px 12px;border-radius:20px;background:rgba(99,102,241,.15);color:#818cf8;font-size:12px;font-weight:600;border:1px solid rgba(99,102,241,.3)}
.xn-sel-badge.visible{display:inline-flex}
.proj-progress-bar{width:100%;height:6px;background:rgba(255,255,255,.1);border-radius:3px;overflow:hidden}
.proj-progress-bar-fill{height:100%;border-radius:3px;background:linear-gradient(90deg,#6366f1,#8b5cf6);transition:width .4s}
.proj-progress-text{font-size:11px;color:var(--crm2-text-muted);margin-top:2px}
.status-planning{background:rgba(148,163,184,.15);color:#94a3b8;border:1px solid rgba(148,163,184,.3)}
.status-active{background:rgba(34,197,94,.12);color:#4ade80;border:1px solid rgba(34,197,94,.3)}
.status-on_hold{background:rgba(251,191,36,.12);color:#fbbf24;border:1px solid rgba(251,191,36,.3)}
.status-completed{background:rgba(99,102,241,.12);color:#818cf8;border:1px solid rgba(99,102,241,.3)}
.status-cancelled{background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.3)}
.priority-low{background:rgba(34,197,94,.1);color:#4ade80;border:1px solid rgba(34,197,94,.25)}
.priority-medium{background:rgba(251,191,36,.1);color:#fbbf24;border:1px solid rgba(251,191,36,.25)}
.priority-high{background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.25)}
tr.clickable-row{cursor:pointer}
tr.clickable-row:hover td{background:rgba(255,255,255,.03)}
</style>
@endpush
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-folder-open"></i> Projects</h1>
      <p class="crm2-subtitle">Manage your CRM projects.</p>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
      <span class="xn-sel-badge" id="selBadge"><i class="fas fa-check-square"></i> <span id="selCount">0</span> selected</span>
      <div style="position:relative">
        <button class="xn-bulk-btn" onclick="toggleBulkMenu(event)"><i class="fas fa-ellipsis-v"></i> Actions</button>
        <div class="xn-bulk-menu" id="bulkMenu">
          <a href="#" onclick="bulkExport(event)"><i class="fas fa-file-csv" style="color:#4ade80"></i> Export Selected (CSV)</a>
          <a href="#" onclick="bulkStatus('active',event)"><i class="fas fa-play-circle" style="color:#818cf8"></i> Mark as Active</a>
          <a href="#" onclick="bulkStatus('completed',event)"><i class="fas fa-check-circle" style="color:#4ade80"></i> Mark as Completed</a>
          <div class="menu-divider"></div>
          <button class="danger" onclick="bulkDelete(event)"><i class="fas fa-trash"></i> Delete Selected</button>
        </div>
      </div>
      <a href="{{ route('admin.crm2.projects.list.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Project</a>
    </div>
  </div>

  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

  <div class="crm2-card mb-4"><div class="crm2-card-body">
    <form method="GET" class="crm2-filter-form">
      <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search projects..." class="crm2-input"></div>
      <div class="filter-group">
        <select name="status" class="crm2-input">
          <option value="">All Statuses</option>
          @foreach(['planning'=>'Planning','active'=>'Active','on_hold'=>'On Hold','completed'=>'Completed','cancelled'=>'Cancelled'] as $val=>$label)
          <option value="{{ $val }}" {{ request('status')==$val?'selected':'' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
      <a href="{{ route('admin.crm2.projects.list') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
    </form>
  </div></div>

  <form id="bulkForm" method="POST" action="{{ route('admin.crm2.projects.bulk-delete') }}">
    @csrf @method('DELETE')
    <div class="crm2-card"><div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead>
          <tr>
            <th style="width:40px"><input type="checkbox" id="selectAll" onchange="toggleAll(this)" style="cursor:pointer;width:16px;height:16px"></th>
            <th>Name</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Progress</th>
            <th>Tasks</th>
            <th>Linked To</th>
            <th>End Date</th>
          </tr>
        </thead>
        <tbody>
          @forelse($projects as $project)
          <tr class="clickable-row" onclick="rowClick(event, '{{ route('admin.crm2.projects.show', $project->id) }}')">
            <td onclick="event.stopPropagation()">
              <input type="checkbox" name="ids[]" value="{{ $project->id }}" class="row-check" onchange="updateSel()" style="cursor:pointer;width:16px;height:16px">
            </td>
            <td>
              <strong>{{ $project->name }}</strong>
              @if($project->description)<br><small class="text-muted">{{ Str::limit($project->description, 55) }}</small>@endif
            </td>
            <td><span class="crm2-badge status-{{ $project->status ?? 'planning' }}">{{ ucwords(str_replace('_',' ',$project->status ?? 'Planning')) }}</span></td>
            <td><span class="crm2-badge priority-{{ $project->priority ?? 'medium' }}">{{ ucfirst($project->priority ?? 'Medium') }}</span></td>
            <td style="min-width:100px">
              @php $pct = $project->progress_percent ?? 0; @endphp
              <div class="proj-progress-bar"><div class="proj-progress-bar-fill" style="width:{{ $pct }}%"></div></div>
              <div class="proj-progress-text">{{ $pct }}%</div>
            </td>
            <td>{{ $project->tasks_count ?? 0 }}</td>
            <td>
              @if($project->account)<span style="font-size:12px;color:#818cf8"><i class="fas fa-building" style="margin-right:4px"></i>{{ $project->account->name }}</span>@elseif($project->deal)<span style="font-size:12px;color:#4ade80"><i class="fas fa-handshake" style="margin-right:4px"></i>{{ $project->deal->name }}</span>@else<span class="text-muted">—</span>@endif
            </td>
            <td>
              @if($project->end_date)
                @php $overdue = $project->end_date->isPast() && !in_array($project->status,['completed','cancelled']); @endphp
                <span style="{{ $overdue ? 'color:#f87171;font-weight:600' : '' }}">{{ $project->end_date->format('d M Y') }}</span>
                @if($overdue)<br><small style="color:#f87171"><i class="fas fa-exclamation-circle"></i> Overdue</small>@endif
              @else<span class="text-muted">—</span>@endif
            </td>
          </tr>
          @empty
          <tr><td colspan="8"><div class="crm2-empty"><i class="fas fa-folder-open"></i><p>No projects found. <a href="{{ route('admin.crm2.projects.list.create') }}">Create your first project</a></p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($projects->hasPages())<div class="crm2-pagination">{{ $projects->links() }}</div>@endif
    </div>
  </form>
</div>

@push('scripts')
<script>
function rowClick(e, url){ if(e.target.type==='checkbox') return; window.location=url; }
function toggleAll(cb){ document.querySelectorAll('.row-check').forEach(c=>c.checked=cb.checked); updateSel(); }
function updateSel(){
  const n=document.querySelectorAll('.row-check:checked').length;
  document.getElementById('selCount').textContent=n;
  document.getElementById('selBadge').classList.toggle('visible',n>0);
}
function toggleBulkMenu(e){ e.stopPropagation(); document.getElementById('bulkMenu').classList.toggle('open'); }
document.addEventListener('click',()=>document.getElementById('bulkMenu').classList.remove('open'));
function getChecked(){ return [...document.querySelectorAll('.row-check:checked')].map(c=>c.value); }
function bulkDelete(e){
  e.preventDefault(); e.stopPropagation();
  const ids=getChecked(); if(!ids.length){alert('Select at least one project.');return;}
  if(!confirm('Delete '+ids.length+' project(s)? This cannot be undone.')) return;
  document.getElementById('bulkForm').submit();
}
function bulkStatus(status,e){
  e.preventDefault(); e.stopPropagation();
  const ids=getChecked(); if(!ids.length){alert('Select at least one project.');return;}
  const f=document.createElement('form'); f.method='POST'; f.action='{{ route("admin.crm2.projects.bulk-status") }}';
  f.innerHTML='@csrf<input name="status" value="'+status+'">';
  ids.forEach(id=>{ const i=document.createElement('input'); i.name='ids[]'; i.value=id; f.appendChild(i); });
  document.body.appendChild(f); f.submit();
}
function bulkExport(e){
  e.preventDefault(); e.stopPropagation();
  const ids=getChecked(); if(!ids.length){alert('Select at least one project.');return;}
  const f=document.createElement('form'); f.method='POST'; f.action='{{ route("admin.crm2.projects.bulk-export") }}';
  f.innerHTML='@csrf';
  ids.forEach(id=>{ const i=document.createElement('input'); i.name='ids[]'; i.value=id; f.appendChild(i); });
  document.body.appendChild(f); f.submit();
}
</script>
@endpush
@endsection
