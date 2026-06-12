@extends('layouts.admin')
@section('title', 'Project Tasks')
@section('page-title', 'Project Tasks')
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
.status-todo{background:rgba(148,163,184,.15);color:#94a3b8;border:1px solid rgba(148,163,184,.3)}
.status-in_progress{background:rgba(251,191,36,.12);color:#fbbf24;border:1px solid rgba(251,191,36,.3)}
.status-testing{background:rgba(96,165,250,.12);color:#60a5fa;border:1px solid rgba(96,165,250,.3)}
.status-completed{background:rgba(34,197,94,.12);color:#4ade80;border:1px solid rgba(34,197,94,.3)}
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
      <h1 class="crm2-title"><i class="fas fa-tasks"></i> Project Tasks</h1>
      <p class="crm2-subtitle">All tasks across all projects.</p>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
      <span class="xn-sel-badge" id="selBadge"><i class="fas fa-check-square"></i> <span id="selCount">0</span> selected</span>
      <div style="position:relative">
        <button class="xn-bulk-btn" onclick="toggleBulkMenu(event)"><i class="fas fa-ellipsis-v"></i> Actions</button>
        <div class="xn-bulk-menu" id="bulkMenu">
          <a href="#" onclick="bulkExport(event)"><i class="fas fa-file-csv" style="color:#4ade80"></i> Export Selected (CSV)</a>
          <div class="menu-divider"></div>
          <button class="danger" onclick="bulkDelete(event)"><i class="fas fa-trash"></i> Delete Selected</button>
        </div>
      </div>
      <a href="{{ route('admin.crm2.projects.tasks.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Task</a>
    </div>
  </div>

  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

  <div class="crm2-card mb-4"><div class="crm2-card-body">
    <form method="GET" class="crm2-filter-form">
      <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search tasks..." class="crm2-input"></div>
      <div class="filter-group">
        <select name="project_id" class="crm2-input">
          <option value="">All Projects</option>
          @foreach($projects_list as $p)
          <option value="{{ $p->id }}" {{ request('project_id')==$p->id?'selected':'' }}>{{ $p->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="filter-group">
        <select name="status" class="crm2-input">
          <option value="">All Statuses</option>
          @foreach(['todo'=>'To Do','in_progress'=>'In Progress','testing'=>'Testing','completed'=>'Completed'] as $v=>$l)
          <option value="{{ $v }}" {{ request('status')==$v?'selected':'' }}>{{ $l }}</option>
          @endforeach
        </select>
      </div>
      <div class="filter-group">
        <select name="priority" class="crm2-input">
          <option value="">All Priorities</option>
          <option value="low" {{ request('priority')=='low'?'selected':'' }}>Low</option>
          <option value="medium" {{ request('priority')=='medium'?'selected':'' }}>Medium</option>
          <option value="high" {{ request('priority')=='high'?'selected':'' }}>High</option>
        </select>
      </div>
      <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
      <a href="{{ route('admin.crm2.projects.tasks') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
    </form>
  </div></div>

  <form id="bulkForm" method="POST" action="{{ route('admin.crm2.projects.tasks.bulk-delete') }}">
    @csrf @method('DELETE')
    <div class="crm2-card"><div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead>
          <tr>
            <th style="width:40px"><input type="checkbox" id="selectAll" onchange="toggleAll(this)" style="cursor:pointer;width:16px;height:16px"></th>
            <th>Title</th>
            <th>Project</th>
            <th>Milestone</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Due Date</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tasks as $task)
          <tr class="clickable-row" onclick="rowClick(event, '{{ route('admin.crm2.projects.show', $task->project_id) }}')">
            <td onclick="event.stopPropagation()">
              <input type="checkbox" name="ids[]" value="{{ $task->id }}" class="row-check" onchange="updateSel()" style="cursor:pointer;width:16px;height:16px">
            </td>
            <td><strong>{{ $task->name }}</strong></td>
            <td>
              @if($task->project)
              <a href="{{ route('admin.crm2.projects.show', $task->project_id) }}" onclick="event.stopPropagation()" style="color:#818cf8;text-decoration:none;font-size:13px">
                <i class="fas fa-folder-open" style="margin-right:4px"></i>{{ $task->project->name }}
              </a>
              @else<span class="text-muted">—</span>@endif
            </td>
            <td>{{ $task->milestone?->name ?? '—' }}</td>
            <td><span class="crm2-badge priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span></td>
            <td><span class="crm2-badge status-{{ $task->status }}">{{ ucwords(str_replace('_',' ',$task->status)) }}</span></td>
            <td>
              @if($task->due_date)
                @php $overdue = $task->due_date->isPast() && $task->status !== 'completed'; @endphp
                <span style="{{ $overdue ? 'color:#f87171;font-weight:600' : '' }}">{{ $task->due_date->format('d M Y') }}</span>
              @else<span class="text-muted">—</span>@endif
            </td>
          </tr>
          @empty
          <tr><td colspan="7"><div class="crm2-empty"><i class="fas fa-tasks"></i><p>No tasks found.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($tasks->hasPages())<div class="crm2-pagination">{{ $tasks->links() }}</div>@endif
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
  const ids=getChecked(); if(!ids.length){alert('Select at least one task.');return;}
  if(!confirm('Delete '+ids.length+' task(s)?')) return;
  document.getElementById('bulkForm').submit();
}
function bulkExport(e){
  e.preventDefault(); e.stopPropagation();
  const ids=getChecked(); if(!ids.length){alert('Select at least one task.');return;}
  const f=document.createElement('form'); f.method='POST'; f.action='{{ route("admin.crm2.projects.tasks.bulk-export") }}';
  f.innerHTML='@csrf';
  ids.forEach(id=>{ const i=document.createElement('input'); i.name='ids[]'; i.value=id; f.appendChild(i); });
  document.body.appendChild(f); f.submit();
}
</script>
@endpush
@endsection
