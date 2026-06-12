@extends('layouts.admin')
@section('title', $project->name . ' — Project')
@section('page-title', $project->name)
@push('styles')
<style>
/* ── 3-dot action menu ── */
.xn-bulk-btn{position:relative;display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.06);color:var(--crm2-text);transition:background .2s}
.xn-bulk-btn:hover{background:rgba(255,255,255,.12)}
.xn-bulk-menu{position:absolute;top:calc(100% + 6px);right:0;min-width:190px;background:var(--crm2-card-bg);border:1px solid var(--crm2-border);border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.35);z-index:999;padding:6px 0;display:none}
.xn-bulk-menu.open{display:block}
.xn-bulk-menu a,.xn-bulk-menu button{display:flex;align-items:center;gap:10px;width:100%;padding:9px 16px;font-size:13px;color:var(--crm2-text);background:none;border:none;cursor:pointer;text-decoration:none;transition:background .15s}
.xn-bulk-menu a:hover,.xn-bulk-menu button:hover{background:rgba(255,255,255,.07)}
.xn-bulk-menu .menu-divider{height:1px;background:var(--crm2-border);margin:4px 0}
.xn-bulk-menu .danger{color:#ef4444}
/* ── tabs ── */
.proj-tabs{display:flex;gap:4px;border-bottom:1px solid var(--crm2-border);margin-bottom:24px;flex-wrap:wrap}
.proj-tab{padding:10px 18px;font-size:13px;font-weight:500;color:var(--crm2-text-muted);cursor:pointer;border-bottom:2px solid transparent;transition:all .2s;border-radius:6px 6px 0 0;display:flex;align-items:center;gap:7px;background:none;border-left:none;border-right:none;border-top:none}
.proj-tab:hover{color:var(--crm2-text);background:rgba(255,255,255,.04)}
.proj-tab.active{color:#818cf8;border-bottom-color:#6366f1;background:rgba(99,102,241,.06)}
.proj-tab-pane{display:none}
.proj-tab-pane.active{display:block}
/* ── overview ── */
.proj-overview-grid{display:grid;grid-template-columns:2fr 1fr;gap:20px}
@media(max-width:768px){.proj-overview-grid{grid-template-columns:1fr}}
.proj-info-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.proj-info-item label{font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--crm2-text-muted);display:block;margin-bottom:4px}
.proj-info-item span{font-size:14px;color:var(--crm2-text)}
.proj-progress-big{margin:16px 0}
.proj-progress-bar{width:100%;height:10px;background:rgba(255,255,255,.1);border-radius:5px;overflow:hidden}
.proj-progress-bar-fill{height:100%;border-radius:5px;background:linear-gradient(90deg,#6366f1,#8b5cf6);transition:width .6s}
/* ── status badges ── */
.status-planning{background:rgba(148,163,184,.15);color:#94a3b8;border:1px solid rgba(148,163,184,.3)}
.status-active{background:rgba(34,197,94,.12);color:#4ade80;border:1px solid rgba(34,197,94,.3)}
.status-on_hold{background:rgba(251,191,36,.12);color:#fbbf24;border:1px solid rgba(251,191,36,.3)}
.status-completed{background:rgba(99,102,241,.12);color:#818cf8;border:1px solid rgba(99,102,241,.3)}
.status-cancelled{background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.3)}
.priority-low{background:rgba(34,197,94,.1);color:#4ade80;border:1px solid rgba(34,197,94,.25)}
.priority-medium{background:rgba(251,191,36,.1);color:#fbbf24;border:1px solid rgba(251,191,36,.25)}
.priority-high{background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.25)}
.severity-low{background:rgba(34,197,94,.1);color:#4ade80;border:1px solid rgba(34,197,94,.25)}
.severity-medium{background:rgba(251,191,36,.1);color:#fbbf24;border:1px solid rgba(251,191,36,.25)}
.severity-high{background:rgba(249,115,22,.1);color:#fb923c;border:1px solid rgba(249,115,22,.25)}
.severity-critical{background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.25)}
.milestone-pending{background:rgba(148,163,184,.15);color:#94a3b8;border:1px solid rgba(148,163,184,.3)}
.milestone-in_progress{background:rgba(251,191,36,.12);color:#fbbf24;border:1px solid rgba(251,191,36,.3)}
.milestone-completed{background:rgba(34,197,94,.12);color:#4ade80;border:1px solid rgba(34,197,94,.3)}
/* ── kanban ── */
.kanban-board{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;align-items:start}
@media(max-width:900px){.kanban-board{grid-template-columns:repeat(2,1fr)}}
@media(max-width:500px){.kanban-board{grid-template-columns:1fr}}
.kanban-col{background:rgba(255,255,255,.03);border:1px solid var(--crm2-border);border-radius:12px;padding:12px}
.kanban-col-header{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;margin-bottom:10px;display:flex;align-items:center;justify-content:space-between}
.kanban-card{background:var(--crm2-card-bg);border:1px solid var(--crm2-border);border-radius:8px;padding:12px;margin-bottom:8px;cursor:pointer;transition:border-color .2s,box-shadow .2s}
.kanban-card:hover{border-color:#6366f1;box-shadow:0 4px 12px rgba(99,102,241,.15)}
.kanban-card-title{font-size:13px;font-weight:600;margin-bottom:6px}
.kanban-card-meta{font-size:11px;color:var(--crm2-text-muted);display:flex;gap:8px;flex-wrap:wrap}
/* ── inline form ── */
.proj-inline-form{background:rgba(255,255,255,.03);border:1px solid var(--crm2-border);border-radius:10px;padding:16px;margin-bottom:20px}
.proj-inline-form .form-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:12px}
/* ── note card ── */
.note-card{background:rgba(255,255,255,.03);border:1px solid var(--crm2-border);border-radius:10px;padding:14px;margin-bottom:10px}
.note-card-meta{font-size:11px;color:var(--crm2-text-muted);margin-bottom:6px}
.note-card-body{font-size:14px;line-height:1.6}
/* ── time log ── */
.tl-total{font-size:28px;font-weight:700;color:#818cf8}
.tl-label{font-size:12px;color:var(--crm2-text-muted)}
</style>
@endpush
@section('content')
<div class="crm2-page">

  {{-- Header --}}
  <div class="crm2-header">
    <div style="display:flex;align-items:center;gap:12px">
      <a href="{{ route('admin.crm2.projects.list') }}" class="crm2-btn crm2-btn-ghost" style="padding:6px 10px"><i class="fas fa-arrow-left"></i></a>
      <div>
        <h1 class="crm2-title"><i class="fas fa-folder-open" style="color:#818cf8"></i> {{ $project->name }}</h1>
        <p class="crm2-subtitle">{{ $project->description ?? 'No description' }}</p>
      </div>
    </div>
    <div style="display:flex;align-items:center;gap:10px">
      <span class="crm2-badge status-{{ $project->status }}">{{ ucwords(str_replace('_',' ',$project->status)) }}</span>
      <a href="{{ route('admin.crm2.projects.list.edit', $project->id) }}" class="crm2-btn crm2-btn-secondary"><i class="fas fa-edit"></i> Edit</a>
      <div style="position:relative">
        <button class="xn-bulk-btn" id="actBtn" onclick="toggleActMenu(event)"><i class="fas fa-ellipsis-v"></i></button>
        <div class="xn-bulk-menu" id="actMenu">
          <a href="{{ route('admin.crm2.projects.clone', $project->id) }}"><i class="fas fa-copy" style="color:#818cf8"></i> Clone Project</a>
          <a href="#" onclick="window.print();return false"><i class="fas fa-print" style="color:#94a3b8"></i> Print Preview</a>
          <div class="menu-divider"></div>
          <form method="POST" action="{{ route('admin.crm2.projects.destroy', ['type'=>'project','id'=>$project->id]) }}" onsubmit="return confirm('Delete this project and all its data?')">
            @csrf @method('DELETE')
            <button type="submit" class="danger"><i class="fas fa-trash"></i> Delete Project</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

  {{-- Tabs --}}
  <div class="proj-tabs">
    <button class="proj-tab active" onclick="switchTab('overview',this)"><i class="fas fa-chart-pie"></i> Overview</button>
    <button class="proj-tab" onclick="switchTab('tasks',this)"><i class="fas fa-tasks"></i> Tasks <span class="crm2-badge" style="margin-left:4px;padding:2px 7px;font-size:10px">{{ $tasks->count() }}</span></button>
    <button class="proj-tab" onclick="switchTab('milestones',this)"><i class="fas fa-flag"></i> Milestones <span class="crm2-badge" style="margin-left:4px;padding:2px 7px;font-size:10px">{{ $milestones->count() }}</span></button>
    <button class="proj-tab" onclick="switchTab('issues',this)"><i class="fas fa-bug"></i> Issues <span class="crm2-badge" style="margin-left:4px;padding:2px 7px;font-size:10px">{{ $issues->count() }}</span></button>
    <button class="proj-tab" onclick="switchTab('timelog',this)"><i class="fas fa-clock"></i> Time Log</button>
    <button class="proj-tab" onclick="switchTab('notes',this)"><i class="fas fa-sticky-note"></i> Notes <span class="crm2-badge" style="margin-left:4px;padding:2px 7px;font-size:10px">{{ $notes->count() }}</span></button>
  </div>

  {{-- ══ OVERVIEW TAB ══ --}}
  <div class="proj-tab-pane active" id="tab-overview">
    <div class="proj-overview-grid">
      <div>
        <div class="crm2-card mb-4">
          <div class="crm2-card-header"><h3 class="crm2-card-title"><i class="fas fa-info-circle"></i> Project Details</h3></div>
          <div class="crm2-card-body">
            <div class="proj-info-grid">
              <div class="proj-info-item"><label>Status</label><span><span class="crm2-badge status-{{ $project->status }}">{{ ucwords(str_replace('_',' ',$project->status)) }}</span></span></div>
              <div class="proj-info-item"><label>Priority</label><span><span class="crm2-badge priority-{{ $project->priority ?? 'medium' }}">{{ ucfirst($project->priority ?? 'Medium') }}</span></span></div>
              <div class="proj-info-item"><label>Start Date</label><span>{!! $project->start_date ? $project->start_date->format('d M Y') : '<span style="color:var(--crm2-text-muted)">—</span>' !!}</span></div>
              <div class="proj-info-item"><label>End Date</label>
                <span>
                  @if($project->end_date)
                    @php $overdue = $project->end_date->isPast() && !in_array($project->status,['completed','cancelled']); @endphp
                    <span style="{{ $overdue ? 'color:#f87171;font-weight:600' : '' }}">{{ $project->end_date->format('d M Y') }}</span>
                    @if($overdue) <small style="color:#f87171"> (Overdue)</small>@endif
                  @else<span style="color:var(--crm2-text-muted)">—</span>@endif
                </span>
              </div>
              <div class="proj-info-item"><label>Budget</label><span>{{ $project->budget > 0 ? '₹'.number_format($project->budget,2) : '—' }}</span></div>
              <div class="proj-info-item"><label>Cost</label><span>{{ $project->cost > 0 ? '₹'.number_format($project->cost,2) : '—' }}</span></div>
              <div class="proj-info-item"><label>Linked Account</label><span>{{ $project->account?->name ?? '—' }}</span></div>
              <div class="proj-info-item"><label>Linked Deal</label><span>{{ $project->deal?->name ?? '—' }}</span></div>
            </div>
            <div class="proj-progress-big">
              <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                <span style="font-size:13px;font-weight:600">Overall Progress</span>
                <span style="font-size:13px;color:#818cf8;font-weight:700">{{ $project->progress_percent }}%</span>
              </div>
              <div class="proj-progress-bar"><div class="proj-progress-bar-fill" style="width:{{ $project->progress_percent }}%"></div></div>
            </div>
          </div>
        </div>
        {{-- Recent Tasks --}}
        <div class="crm2-card">
          <div class="crm2-card-header" style="display:flex;justify-content:space-between;align-items:center">
            <h3 class="crm2-card-title"><i class="fas fa-tasks"></i> Recent Tasks</h3>
            <button class="crm2-btn crm2-btn-ghost" style="font-size:12px;padding:4px 10px" onclick="switchTab('tasks',document.querySelector('.proj-tab:nth-child(2)'))">View All</button>
          </div>
          <div class="crm2-card-body p-0">
            <table class="crm2-table">
              <thead><tr><th>Task</th><th>Priority</th><th>Status</th><th>Due</th></tr></thead>
              <tbody>
                @forelse($tasks->take(5) as $task)
                <tr>
                  <td><strong>{{ $task->name }}</strong></td>
                  <td><span class="crm2-badge priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span></td>
                  <td><span class="crm2-badge status-{{ $task->status }}">{{ ucwords(str_replace('_',' ',$task->status)) }}</span></td>
                  <td>{{ $task->due_date ? $task->due_date->format('d M Y') : '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:20px;color:var(--crm2-text-muted)">No tasks yet</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div>
        {{-- Stats --}}
        <div class="crm2-card mb-4">
          <div class="crm2-card-header"><h3 class="crm2-card-title"><i class="fas fa-chart-bar"></i> Stats</h3></div>
          <div class="crm2-card-body">
            @php
              $totalTasks  = $tasks->count();
              $doneTasks   = $tasks->where('status','completed')->count();
              $openIssues  = $issues->whereIn('status',['open','in_progress'])->count();
              $totalHours  = $timeLogs->sum('hours');
              $mileDone    = $milestones->where('status','completed')->count();
              $mileTotal   = $milestones->count();
            @endphp
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
              <div style="background:rgba(99,102,241,.08);border:1px solid rgba(99,102,241,.2);border-radius:10px;padding:14px;text-align:center">
                <div style="font-size:24px;font-weight:700;color:#818cf8">{{ $totalTasks }}</div>
                <div style="font-size:11px;color:var(--crm2-text-muted)">Total Tasks</div>
              </div>
              <div style="background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.2);border-radius:10px;padding:14px;text-align:center">
                <div style="font-size:24px;font-weight:700;color:#4ade80">{{ $doneTasks }}</div>
                <div style="font-size:11px;color:var(--crm2-text-muted)">Completed</div>
              </div>
              <div style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:10px;padding:14px;text-align:center">
                <div style="font-size:24px;font-weight:700;color:#f87171">{{ $openIssues }}</div>
                <div style="font-size:11px;color:var(--crm2-text-muted)">Open Issues</div>
              </div>
              <div style="background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.2);border-radius:10px;padding:14px;text-align:center">
                <div style="font-size:24px;font-weight:700;color:#fbbf24">{{ number_format($totalHours,1) }}h</div>
                <div style="font-size:11px;color:var(--crm2-text-muted)">Hours Logged</div>
              </div>
            </div>
            @if($mileTotal > 0)
            <div style="margin-top:16px">
              <div style="font-size:12px;color:var(--crm2-text-muted);margin-bottom:6px">Milestones: {{ $mileDone }}/{{ $mileTotal }} completed</div>
              <div class="proj-progress-bar"><div class="proj-progress-bar-fill" style="width:{{ $mileTotal>0 ? round($mileDone/$mileTotal*100) : 0 }}%;background:linear-gradient(90deg,#f59e0b,#fbbf24)"></div></div>
            </div>
            @endif
          </div>
        </div>
        {{-- Open Issues --}}
        @if($issues->where('status','open')->count() > 0)
        <div class="crm2-card">
          <div class="crm2-card-header"><h3 class="crm2-card-title"><i class="fas fa-bug" style="color:#f87171"></i> Open Issues</h3></div>
          <div class="crm2-card-body" style="padding:12px">
            @foreach($issues->where('status','open')->take(4) as $issue)
            <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--crm2-border)">
              <span class="crm2-badge severity-{{ $issue->severity }}" style="font-size:10px">{{ ucfirst($issue->severity) }}</span>
              <span style="font-size:13px;flex:1">{{ $issue->title }}</span>
            </div>
            @endforeach
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>

  {{-- ══ TASKS TAB ══ --}}
  <div class="proj-tab-pane" id="tab-tasks">
    {{-- Add Task Form --}}
    <div class="proj-inline-form">
      <h4 style="font-size:14px;font-weight:600;margin-bottom:12px"><i class="fas fa-plus-circle" style="color:#6366f1"></i> Add Task</h4>
      <form method="POST" action="{{ route('admin.crm2.projects.store') }}">
        @csrf
        <input type="hidden" name="_type" value="task">
        <input type="hidden" name="project_id" value="{{ $project->id }}">
        <div class="form-row">
          <input type="text" name="title" placeholder="Task title *" class="crm2-input" required>
          <select name="priority" class="crm2-input">
            <option value="low">Low</option>
            <option value="medium" selected>Medium</option>
            <option value="high">High</option>
          </select>
          <select name="status" class="crm2-input">
            <option value="todo">To Do</option>
            <option value="in_progress">In Progress</option>
            <option value="testing">Testing</option>
            <option value="completed">Completed</option>
          </select>
          <input type="date" name="due_date" class="crm2-input" placeholder="Due date">
          <select name="milestone_id" class="crm2-input">
            <option value="">No Milestone</option>
            @foreach($milestones as $ms)
            <option value="{{ $ms->id }}">{{ $ms->name }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> Add Task</button>
      </form>
    </div>

    {{-- Kanban Board --}}
    <div class="kanban-board">
      @foreach(['todo'=>['To Do','#94a3b8'],'in_progress'=>['In Progress','#fbbf24'],'testing'=>['Testing','#60a5fa'],'completed'=>['Completed','#4ade80']] as $status=>[$label,$color])
      @php $colTasks = $tasks->where('status',$status); @endphp
      <div class="kanban-col">
        <div class="kanban-col-header">
          <span style="color:{{ $color }}">{{ $label }}</span>
          <span style="background:rgba(255,255,255,.08);border-radius:10px;padding:2px 8px;font-size:11px">{{ $colTasks->count() }}</span>
        </div>
        @foreach($colTasks as $task)
        <div class="kanban-card">
          <div class="kanban-card-title">{{ $task->name }}</div>
          <div class="kanban-card-meta">
            <span class="crm2-badge priority-{{ $task->priority }}" style="font-size:10px;padding:2px 6px">{{ ucfirst($task->priority) }}</span>
            @if($task->due_date)<span><i class="fas fa-calendar-alt"></i> {{ $task->due_date->format('d M') }}</span>@endif
            @if($task->milestone)<span><i class="fas fa-flag"></i> {{ $task->milestone->name }}</span>@endif
          </div>
          <div style="display:flex;gap:6px;margin-top:8px">
            <form method="POST" action="{{ route('admin.crm2.projects.task.status', $task->id) }}" style="display:inline">
              @csrf @method('PATCH')
              <select name="status" class="crm2-input" style="font-size:11px;padding:3px 6px;height:auto" onchange="this.form.submit()">
                @foreach(['todo'=>'To Do','in_progress'=>'In Progress','testing'=>'Testing','completed'=>'Completed'] as $sv=>$sl)
                <option value="{{ $sv }}" {{ $task->status==$sv?'selected':'' }}>{{ $sl }}</option>
                @endforeach
              </select>
            </form>
            <form method="POST" action="{{ route('admin.crm2.projects.destroy', ['type'=>'task','id'=>$task->id]) }}" onsubmit="return confirm('Delete task?')" style="display:inline">
              @csrf @method('DELETE')
              <button type="submit" class="crm2-icon-btn delete" style="padding:4px 8px;font-size:11px"><i class="fas fa-trash"></i></button>
            </form>
          </div>
        </div>
        @endforeach
        @if($colTasks->isEmpty())
        <div style="text-align:center;padding:20px 10px;color:var(--crm2-text-muted);font-size:12px">No tasks</div>
        @endif
      </div>
      @endforeach
    </div>
  </div>

  {{-- ══ MILESTONES TAB ══ --}}
  <div class="proj-tab-pane" id="tab-milestones">
    <div class="proj-inline-form">
      <h4 style="font-size:14px;font-weight:600;margin-bottom:12px"><i class="fas fa-plus-circle" style="color:#6366f1"></i> Add Milestone</h4>
      <form method="POST" action="{{ route('admin.crm2.projects.milestones.store', $project->id) }}">
        @csrf
        <div class="form-row">
          <input type="text" name="name" placeholder="Milestone name *" class="crm2-input" required>
          <input type="date" name="target_date" class="crm2-input" placeholder="Target date">
          <select name="status" class="crm2-input">
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
          </select>
        </div>
        <textarea name="description" class="crm2-input" placeholder="Description (optional)" rows="2" style="width:100%;margin-bottom:10px"></textarea>
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> Add Milestone</button>
      </form>
    </div>

    @forelse($milestones as $ms)
    <div class="crm2-card mb-3">
      <div class="crm2-card-body" style="display:flex;align-items:flex-start;gap:16px;flex-wrap:wrap">
        <div style="flex:1;min-width:200px">
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
            <i class="fas fa-flag" style="color:{{ $ms->status=='completed' ? '#4ade80' : ($ms->status=='in_progress' ? '#fbbf24' : '#94a3b8') }}"></i>
            <strong style="font-size:15px">{{ $ms->name }}</strong>
            <span class="crm2-badge milestone-{{ $ms->status }}" style="font-size:11px">{{ ucwords(str_replace('_',' ',$ms->status)) }}</span>
          </div>
          @if($ms->description)<p style="font-size:13px;color:var(--crm2-text-muted);margin:0 0 6px 0">{{ $ms->description }}</p>@endif
          @if($ms->target_date)
          @php $msOverdue = \Carbon\Carbon::parse($ms->target_date)->isPast() && $ms->status !== 'completed'; @endphp
          <small style="color:{{ $msOverdue ? '#f87171' : 'var(--crm2-text-muted)' }}"><i class="fas fa-calendar"></i> Target: {{ \Carbon\Carbon::parse($ms->target_date)->format('d M Y') }}{{ $msOverdue ? ' (Overdue)' : '' }}</small>
          @endif
          @php $msTasks = $tasks->where('milestone_id', $ms->id); $msDone = $msTasks->where('status','completed')->count(); $msTotal = $msTasks->count(); @endphp
          @if($msTotal > 0)
          <div style="margin-top:8px">
            <small style="color:var(--crm2-text-muted)">Tasks: {{ $msDone }}/{{ $msTotal }}</small>
            <div class="proj-progress-bar" style="height:4px;margin-top:4px"><div class="proj-progress-bar-fill" style="width:{{ $msTotal>0?round($msDone/$msTotal*100):0 }}%"></div></div>
          </div>
          @endif
        </div>
        <div style="display:flex;gap:8px;align-items:center">
          <form method="POST" action="{{ route('admin.crm2.projects.milestones.update', [$project->id,$ms->id]) }}">
            @csrf @method('PATCH')
            <select name="status" class="crm2-input" style="font-size:12px;padding:4px 8px;height:auto" onchange="this.form.submit()">
              <option value="pending" {{ $ms->status=='pending'?'selected':'' }}>Pending</option>
              <option value="in_progress" {{ $ms->status=='in_progress'?'selected':'' }}>In Progress</option>
              <option value="completed" {{ $ms->status=='completed'?'selected':'' }}>Completed</option>
            </select>
          </form>
          <form method="POST" action="{{ route('admin.crm2.projects.milestones.destroy', [$project->id,$ms->id]) }}" onsubmit="return confirm('Delete milestone?')">
            @csrf @method('DELETE')
            <button type="submit" class="crm2-icon-btn delete" style="padding:5px 10px"><i class="fas fa-trash"></i></button>
          </form>
        </div>
      </div>
    </div>
    @empty
    <div class="crm2-empty"><i class="fas fa-flag"></i><p>No milestones yet. Add one above to track key project phases.</p></div>
    @endforelse
  </div>

  {{-- ══ ISSUES TAB ══ --}}
  <div class="proj-tab-pane" id="tab-issues">
    <div class="proj-inline-form">
      <h4 style="font-size:14px;font-weight:600;margin-bottom:12px"><i class="fas fa-plus-circle" style="color:#6366f1"></i> Report Issue</h4>
      <form method="POST" action="{{ route('admin.crm2.projects.issues.store', $project->id) }}">
        @csrf
        <div class="form-row">
          <input type="text" name="title" placeholder="Issue title *" class="crm2-input" required>
          <select name="severity" class="crm2-input">
            <option value="low">Low</option>
            <option value="medium" selected>Medium</option>
            <option value="high">High</option>
            <option value="critical">Critical</option>
          </select>
          <select name="status" class="crm2-input">
            <option value="open">Open</option>
            <option value="in_progress">In Progress</option>
            <option value="resolved">Resolved</option>
            <option value="closed">Closed</option>
          </select>
          <select name="task_id" class="crm2-input">
            <option value="">Link to Task (optional)</option>
            @foreach($tasks as $t)
            <option value="{{ $t->id }}">{{ $t->name }}</option>
            @endforeach
          </select>
          <input type="date" name="due_date" class="crm2-input" placeholder="Due date">
        </div>
        <textarea name="description" class="crm2-input" placeholder="Description (optional)" rows="2" style="width:100%;margin-bottom:10px"></textarea>
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> Report Issue</button>
      </form>
    </div>

    <div class="crm2-card"><div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>Title</th><th>Severity</th><th>Status</th><th>Linked Task</th><th>Due Date</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($issues as $issue)
          <tr>
            <td>
              <strong>{{ $issue->title }}</strong>
              @if($issue->description)<br><small class="text-muted">{{ Str::limit($issue->description,60) }}</small>@endif
            </td>
            <td><span class="crm2-badge severity-{{ $issue->severity }}">{{ ucfirst($issue->severity) }}</span></td>
            <td>
              <form method="POST" action="{{ route('admin.crm2.projects.issues.update', [$project->id,$issue->id]) }}" style="display:inline">
                @csrf @method('PATCH')
                <select name="status" class="crm2-input" style="font-size:11px;padding:3px 6px;height:auto" onchange="this.form.submit()">
                  @foreach(['open'=>'Open','in_progress'=>'In Progress','resolved'=>'Resolved','closed'=>'Closed'] as $sv=>$sl)
                  <option value="{{ $sv }}" {{ $issue->status==$sv?'selected':'' }}>{{ $sl }}</option>
                  @endforeach
                </select>
              </form>
            </td>
            <td>{{ $issue->task?->name ?? '—' }}</td>
            <td>{{ $issue->due_date ? \Carbon\Carbon::parse($issue->due_date)->format('d M Y') : '—' }}</td>
            <td class="actions-cell">
              <form method="POST" action="{{ route('admin.crm2.projects.issues.destroy', [$project->id,$issue->id]) }}" onsubmit="return confirm('Delete issue?')" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6"><div class="crm2-empty"><i class="fas fa-bug"></i><p>No issues reported.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div></div>
  </div>

  {{-- ══ TIME LOG TAB ══ --}}
  <div class="proj-tab-pane" id="tab-timelog">
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px">
      <div class="crm2-card" style="text-align:center;padding:20px">
        <div class="tl-total">{{ number_format($timeLogs->sum('hours'),1) }}h</div>
        <div class="tl-label">Total Logged</div>
      </div>
      <div class="crm2-card" style="text-align:center;padding:20px">
        <div class="tl-total" style="color:#4ade80">{{ $timeLogs->count() }}</div>
        <div class="tl-label">Log Entries</div>
      </div>
      <div class="crm2-card" style="text-align:center;padding:20px">
        <div class="tl-total" style="color:#fbbf24">{{ $tasks->sum('estimated_hours') > 0 ? number_format($tasks->sum('estimated_hours'),1).'h' : '—' }}</div>
        <div class="tl-label">Estimated</div>
      </div>
    </div>

    <div class="proj-inline-form">
      <h4 style="font-size:14px;font-weight:600;margin-bottom:12px"><i class="fas fa-plus-circle" style="color:#6366f1"></i> Log Time</h4>
      <form method="POST" action="{{ route('admin.crm2.projects.timelog.store', $project->id) }}">
        @csrf
        <div class="form-row">
          <input type="date" name="log_date" class="crm2-input" value="{{ date('Y-m-d') }}" required>
          <input type="number" name="hours" class="crm2-input" placeholder="Hours (e.g. 2.5)" step="0.25" min="0.25" max="24" required>
          <select name="task_id" class="crm2-input">
            <option value="">General (no specific task)</option>
            @foreach($tasks as $t)
            <option value="{{ $t->id }}">{{ $t->name }}</option>
            @endforeach
          </select>
        </div>
        <textarea name="notes" class="crm2-input" placeholder="What did you work on?" rows="2" style="width:100%;margin-bottom:10px"></textarea>
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-clock"></i> Log Time</button>
      </form>
    </div>

    <div class="crm2-card"><div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>Date</th><th>Hours</th><th>Task</th><th>Notes</th><th>Logged By</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($timeLogs->sortByDesc('log_date') as $log)
          <tr>
            <td>{{ \Carbon\Carbon::parse($log->log_date)->format('d M Y') }}</td>
            <td><strong style="color:#818cf8">{{ number_format($log->hours,2) }}h</strong></td>
            <td>{{ $log->task?->name ?? '—' }}</td>
            <td>{{ $log->notes ? Str::limit($log->notes,60) : '—' }}</td>
            <td>{{ $log->logger?->name ?? 'You' }}</td>
            <td class="actions-cell">
              <form method="POST" action="{{ route('admin.crm2.projects.timelog.destroy', [$project->id,$log->id]) }}" onsubmit="return confirm('Delete this time log?')" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6"><div class="crm2-empty"><i class="fas fa-clock"></i><p>No time logged yet.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div></div>
  </div>

  {{-- ══ NOTES TAB ══ --}}
  <div class="proj-tab-pane" id="tab-notes">
    <div class="proj-inline-form">
      <h4 style="font-size:14px;font-weight:600;margin-bottom:12px"><i class="fas fa-plus-circle" style="color:#6366f1"></i> Add Note</h4>
      <form method="POST" action="{{ route('admin.crm2.projects.notes.store', $project->id) }}">
        @csrf
        <textarea name="body" class="crm2-input" placeholder="Write a note, meeting minutes, or update..." rows="4" style="width:100%;margin-bottom:10px" required></textarea>
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Note</button>
      </form>
    </div>

    @forelse($notes->sortByDesc('created_at') as $note)
    <div class="note-card">
      <div class="note-card-meta">
        <i class="fas fa-user-circle"></i> {{ $note->author?->name ?? 'You' }} &nbsp;·&nbsp;
        <i class="fas fa-clock"></i> {{ $note->created_at->diffForHumans() }}
      </div>
      <div class="note-card-body">{{ $note->body }}</div>
      <div style="margin-top:8px">
        <form method="POST" action="{{ route('admin.crm2.projects.notes.destroy', [$project->id,$note->id]) }}" onsubmit="return confirm('Delete note?')" style="display:inline">
          @csrf @method('DELETE')
          <button type="submit" class="crm2-icon-btn delete" style="font-size:11px;padding:3px 8px"><i class="fas fa-trash"></i> Delete</button>
        </form>
      </div>
    </div>
    @empty
    <div class="crm2-empty"><i class="fas fa-sticky-note"></i><p>No notes yet. Add meeting minutes or project updates above.</p></div>
    @endforelse
  </div>

</div>

@push('scripts')
<script>
function switchTab(name, btn) {
  document.querySelectorAll('.proj-tab-pane').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.proj-tab').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-' + name).classList.add('active');
  if (btn) btn.classList.add('active');
}
function toggleActMenu(e) { e.stopPropagation(); document.getElementById('actMenu').classList.toggle('open'); }
document.addEventListener('click', () => document.getElementById('actMenu')?.classList.remove('open'));
</script>
@endpush
@endsection
