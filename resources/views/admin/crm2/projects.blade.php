@extends('layouts.admin')
@section('title', 'CRM Projects')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-project-diagram"></i> Projects</h1>
      <p class="crm2-subtitle">Unified Sales and Project Management — bridge the gap between sales and project tracking.</p>
    </div>
    <button class="crm2-btn crm2-btn-primary" onclick="openModal('modal-create-{{ $tab === \'projects\' ? \'project\' : \'task\' }}')">
      <i class="fas fa-plus"></i> {{ $tab === 'projects' ? 'New Project' : 'New Task' }}
    </button>
  </div>

  {{-- Tabs --}}
  <div class="crm2-tabs">
    <a href="{{ route('admin.crm2.projects', ['tab'=>'projects']) }}" class="crm2-tab {{ $tab==='projects'?'active':'' }}"><i class="fas fa-folder-open"></i> Projects</a>
    <a href="{{ route('admin.crm2.projects', ['tab'=>'tasks']) }}" class="crm2-tab {{ $tab==='tasks'?'active':'' }}"><i class="fas fa-tasks"></i> Tasks</a>
  </div>

  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

  {{-- Filter --}}
  <div class="crm2-card mb-4">
    <div class="crm2-card-body">
      <form method="GET" class="crm2-filter-form">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="crm2-input"></div>
        <div class="filter-group">
          <select name="status" class="crm2-select">
            <option value="">All Status</option>
            @if($tab === 'projects')
              @foreach(\App\Models\CrmProject::STATUSES as $k=>$v)<option value="{{ $k }}" {{ request('status')===$k?'selected':'' }}>{{ $v }}</option>@endforeach
            @else
              @foreach(\App\Models\CrmProjectTask::STATUSES as $k=>$v)<option value="{{ $k }}" {{ request('status')===$k?'selected':'' }}>{{ $v }}</option>@endforeach
            @endif
          </select>
        </div>
        <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('admin.crm2.projects', ['tab'=>$tab]) }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
      </form>
    </div>
  </div>

  {{-- PROJECTS --}}
  @if($tab === 'projects')
  <div class="crm2-projects-grid">
    @forelse($projects as $proj)
    <div class="crm2-project-card">
      <div class="proj-header">
        <div class="proj-status"><span class="crm2-badge status-{{ $proj->status }}">{{ \App\Models\CrmProject::STATUSES[$proj->status] ?? $proj->status }}</span></div>
        <div class="proj-priority"><span class="crm2-badge priority-{{ $proj->priority }}">{{ ucfirst($proj->priority) }}</span></div>
      </div>
      <div class="proj-name">{{ $proj->name }}</div>
      <div class="proj-desc">{{ Str::limit($proj->description, 80) ?? 'No description.' }}</div>
      <div class="proj-meta">
        <span><i class="fas fa-building"></i> {{ $proj->account?->name ?? 'No Account' }}</span>
        <span><i class="fas fa-calendar"></i> {{ $proj->end_date?->format('d M Y') ?? 'No deadline' }}</span>
      </div>
      <div class="proj-progress-wrap">
        <div class="proj-progress-bar" style="width:{{ $proj->progress_percent }}%"></div>
      </div>
      <div class="proj-progress-label">{{ $proj->progress_percent }}% complete ({{ $proj->tasks_count }} tasks)</div>
      <div class="proj-budget">Budget: ₹{{ number_format($proj->budget, 0) }}</div>
      <div class="proj-actions">
        <a href="{{ route('admin.crm2.projects', ['tab'=>'tasks', 'project_id'=>$proj->id]) }}" class="crm2-btn crm2-btn-ghost btn-sm"><i class="fas fa-tasks"></i> Tasks</a>
        <button class="crm2-btn crm2-btn-ghost btn-sm" onclick='editProject({{ $proj->id }}, @json($proj))'><i class="fas fa-edit"></i> Edit</button>
        <form method="POST" action="{{ route('admin.crm2.projects.destroy', ['type'=>'project','id'=>$proj->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-btn crm2-btn-danger btn-sm"><i class="fas fa-trash"></i></button></form>
      </div>
    </div>
    @empty
    <div class="crm2-empty full-width"><i class="fas fa-project-diagram"></i><p>No projects yet. Create your first project!</p></div>
    @endforelse
  </div>
  @if($projects->hasPages())<div class="crm2-pagination">{{ $projects->links() }}</div>@endif

  {{-- TASKS --}}
  @elseif($tab === 'tasks')
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>Task</th><th>Project</th><th>Priority</th><th>Status</th><th>Due Date</th><th>Hours Est.</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($projectTasks as $task)
          <tr class="{{ $task->status === 'done' ? 'row-muted' : '' }}">
            <td>
              <strong>{{ $task->name }}</strong>
              @if($task->description)<br><small class="text-muted">{{ Str::limit($task->description, 50) }}</small>@endif
            </td>
            <td>{{ $task->project?->name ?? '—' }}</td>
            <td><span class="crm2-badge priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span></td>
            <td>
              <select class="crm2-select-inline" onchange="updateTaskStatus({{ $task->id }}, this.value)">
                @foreach(\App\Models\CrmProjectTask::STATUSES as $k=>$v)
                <option value="{{ $k }}" {{ $task->status===$k?'selected':'' }}>{{ $v }}</option>
                @endforeach
              </select>
            </td>
            <td class="{{ $task->due_date?->isPast() && $task->status !== 'done' ? 'text-danger' : '' }}">
              {{ $task->due_date?->format('d M Y') ?? '—' }}
            </td>
            <td>{{ $task->estimated_hours ? $task->estimated_hours.'h' : '—' }}</td>
            <td class="actions-cell">
              <button class="crm2-icon-btn edit" onclick='editProjectTask({{ $task->id }}, @json($task))' title="Edit"><i class="fas fa-edit"></i></button>
              <form method="POST" action="{{ route('admin.crm2.projects.destroy', ['type'=>'task','id'=>$task->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
            </td>
          </tr>
          @empty
          <tr><td colspan="7"><div class="crm2-empty"><i class="fas fa-tasks"></i><p>No tasks found.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($projectTasks->hasPages())<div class="crm2-pagination">{{ $projectTasks->links() }}</div>@endif
  </div>
  @endif

  {{-- Create Project Modal --}}
  <div class="crm2-modal-overlay" id="modal-create-project">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-folder-plus"></i> New Project</h3><button onclick="closeModal('modal-create-project')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.projects.store') }}">@csrf
        <input type="hidden" name="_type" value="project">
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Project Name *</label><input type="text" name="name" class="crm2-input" required></div>
          <div class="form-group"><label>Status</label><select name="status" class="crm2-select">@foreach(\App\Models\CrmProject::STATUSES as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
          <div class="form-group"><label>Priority</label><select name="priority" class="crm2-select"><option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option><option value="urgent">Urgent</option></select></div>
          <div class="form-group"><label>Account</label><select name="account_id" class="crm2-select"><option value="">— None —</option>@foreach($accounts_list as $a)<option value="{{ $a->id }}">{{ $a->name }}</option>@endforeach</select></div>
          <div class="form-group"><label>Deal</label><select name="deal_id" class="crm2-select"><option value="">— None —</option>@foreach($deals_list as $d)<option value="{{ $d->id }}">{{ $d->title }}</option>@endforeach</select></div>
          <div class="form-group"><label>Start Date</label><input type="date" name="start_date" class="crm2-input"></div>
          <div class="form-group"><label>End Date</label><input type="date" name="end_date" class="crm2-input"></div>
          <div class="form-group"><label>Budget (₹)</label><input type="number" name="budget" class="crm2-input" step="0.01" value="0"></div>
          <div class="form-group full"><label>Description</label><textarea name="description" class="crm2-textarea" rows="3"></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-project')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Project</button></div>
      </form>
    </div>
  </div>

  {{-- Create Task Modal --}}
  <div class="crm2-modal-overlay" id="modal-create-task">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-plus-circle"></i> New Task</h3><button onclick="closeModal('modal-create-task')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.projects.store') }}">@csrf
        <input type="hidden" name="_type" value="task">
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Task Name *</label><input type="text" name="name" class="crm2-input" required></div>
          <div class="form-group"><label>Project *</label><select name="project_id" class="crm2-select" required><option value="">— Select Project —</option>@foreach($projects_list as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select></div>
          <div class="form-group"><label>Priority</label><select name="priority" class="crm2-select"><option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option><option value="urgent">Urgent</option></select></div>
          <div class="form-group"><label>Status</label><select name="status" class="crm2-select">@foreach(\App\Models\CrmProjectTask::STATUSES as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
          <div class="form-group"><label>Due Date</label><input type="date" name="due_date" class="crm2-input"></div>
          <div class="form-group"><label>Est. Hours</label><input type="number" name="estimated_hours" class="crm2-input" step="0.5" min="0"></div>
          <div class="form-group full"><label>Description</label><textarea name="description" class="crm2-textarea" rows="2"></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-task')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Task</button></div>
      </form>
    </div>
  </div>

  {{-- Edit Project Modal --}}
  <div class="crm2-modal-overlay" id="modal-edit-project">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-edit"></i> Edit Project</h3><button onclick="closeModal('modal-edit-project')"><i class="fas fa-times"></i></button></div>
      <form id="edit-project-form" method="POST">@csrf @method('PATCH')
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Name</label><input type="text" name="name" id="ep-name" class="crm2-input"></div>
          <div class="form-group"><label>Status</label><select name="status" id="ep-status" class="crm2-select">@foreach(\App\Models\CrmProject::STATUSES as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
          <div class="form-group"><label>Priority</label><select name="priority" id="ep-priority" class="crm2-select"><option value="low">Low</option><option value="medium">Medium</option><option value="high">High</option><option value="urgent">Urgent</option></select></div>
          <div class="form-group"><label>End Date</label><input type="date" name="end_date" id="ep-end" class="crm2-input"></div>
          <div class="form-group"><label>Budget (₹)</label><input type="number" name="budget" id="ep-budget" class="crm2-input" step="0.01"></div>
          <div class="form-group full"><label>Description</label><textarea name="description" id="ep-desc" class="crm2-textarea" rows="2"></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-edit-project')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update</button></div>
      </form>
    </div>
  </div>

  {{-- Edit Task Modal --}}
  <div class="crm2-modal-overlay" id="modal-edit-task">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-edit"></i> Edit Task</h3><button onclick="closeModal('modal-edit-task')"><i class="fas fa-times"></i></button></div>
      <form id="edit-task-form" method="POST">@csrf @method('PATCH')
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Name</label><input type="text" name="name" id="et-name" class="crm2-input"></div>
          <div class="form-group"><label>Status</label><select name="status" id="et-status" class="crm2-select">@foreach(\App\Models\CrmProjectTask::STATUSES as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
          <div class="form-group"><label>Priority</label><select name="priority" id="et-priority" class="crm2-select"><option value="low">Low</option><option value="medium">Medium</option><option value="high">High</option><option value="urgent">Urgent</option></select></div>
          <div class="form-group"><label>Due Date</label><input type="date" name="due_date" id="et-due" class="crm2-input"></div>
          <div class="form-group"><label>Est. Hours</label><input type="number" name="estimated_hours" id="et-hours" class="crm2-input" step="0.5"></div>
          <div class="form-group full"><label>Description</label><textarea name="description" id="et-desc" class="crm2-textarea" rows="2"></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-edit-task')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update</button></div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

function editProject(id, data) {
  document.getElementById('edit-project-form').action = `/admin/crm2/projects/project/${id}`;
  document.getElementById('ep-name').value = data.name || '';
  document.getElementById('ep-status').value = data.status || 'planning';
  document.getElementById('ep-priority').value = data.priority || 'medium';
  document.getElementById('ep-end').value = data.end_date || '';
  document.getElementById('ep-budget').value = data.budget || 0;
  document.getElementById('ep-desc').value = data.description || '';
  openModal('modal-edit-project');
}

function editProjectTask(id, data) {
  document.getElementById('edit-task-form').action = `/admin/crm2/projects/task/${id}`;
  document.getElementById('et-name').value = data.name || '';
  document.getElementById('et-status').value = data.status || 'todo';
  document.getElementById('et-priority').value = data.priority || 'medium';
  document.getElementById('et-due').value = data.due_date || '';
  document.getElementById('et-hours').value = data.estimated_hours || '';
  document.getElementById('et-desc').value = data.description || '';
  openModal('modal-edit-task');
}

function updateTaskStatus(id, status) {
  fetch(`/admin/crm2/projects/task/${id}/status`, {
    method: 'PATCH',
    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({ status })
  }).then(r => r.json()).then(d => { if (!d.success) alert('Failed to update status'); });
}
</script>
@endpush
@endsection
