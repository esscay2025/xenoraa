@extends('layouts.admin')
@section('title', 'CRM Activities')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-tasks"></i> Activities</h1>
      <p class="crm2-subtitle">Track tasks, meetings, and calls across your entire CRM.</p>
    </div>
    <button class="crm2-btn crm2-btn-primary" onclick="openModal('modal-create-activity')">
      <i class="fas fa-plus"></i> New {{ $tab === 'tasks' ? 'Task' : ($tab === 'meetings' ? 'Meeting' : 'Call') }}
    </button>
  </div>

  {{-- Tabs --}}
  <div class="crm2-tabs">
    <a href="{{ route('admin.crm2.activities', ['tab'=>'tasks']) }}" class="crm2-tab {{ $tab==='tasks'?'active':'' }}"><i class="fas fa-check-square"></i> Tasks</a>
    <a href="{{ route('admin.crm2.activities', ['tab'=>'meetings']) }}" class="crm2-tab {{ $tab==='meetings'?'active':'' }}"><i class="fas fa-calendar-alt"></i> Meetings</a>
    <a href="{{ route('admin.crm2.activities', ['tab'=>'calls']) }}" class="crm2-tab {{ $tab==='calls'?'active':'' }}"><i class="fas fa-phone-alt"></i> Calls</a>
  </div>

  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

  {{-- Filter --}}
  <div class="crm2-card mb-4">
    <div class="crm2-card-body">
      <form method="GET" class="crm2-filter-form">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search {{ $tab }}..." class="crm2-input"></div>
        <div class="filter-group">
          <select name="status" class="crm2-select">
            <option value="">All Status</option>
            <option value="pending" {{ request('status')==='pending'?'selected':'' }}>Pending</option>
            <option value="completed" {{ request('status')==='completed'?'selected':'' }}>Completed</option>
            <option value="cancelled" {{ request('status')==='cancelled'?'selected':'' }}>Cancelled</option>
          </select>
        </div>
        <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('admin.crm2.activities', ['tab'=>$tab]) }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
      </form>
    </div>
  </div>

  {{-- Table --}}
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead>
          <tr>
            <th>Subject</th>
            <th>Description</th>
            <th>Due At</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($activities as $act)
          <tr class="{{ $act->status === 'completed' ? 'row-muted' : '' }}">
            <td>
              <div class="act-subject">
                <span class="act-type-dot" style="background:{{ \App\Models\CrmActivity::TYPES[$act->type]['color'] ?? '#6366f1' }}"></span>
                <strong>{{ $act->subject }}</strong>
              </div>
            </td>
            <td>{{ Str::limit($act->description, 60) ?? '—' }}</td>
            <td>
              @if($act->due_at)
                <span class="{{ $act->due_at->isPast() && $act->status === 'pending' ? 'text-danger' : '' }}">
                  {{ $act->due_at->format('d M Y, H:i') }}
                </span>
              @else
                —
              @endif
            </td>
            <td>
              <span class="crm2-badge status-{{ $act->status }}">{{ ucfirst($act->status) }}</span>
            </td>
            <td class="actions-cell">
              @if($act->status === 'pending')
              <button class="crm2-icon-btn complete" onclick="completeActivity({{ $act->id }}, this)" title="Mark Complete">
                <i class="fas fa-check"></i>
              </button>
              @endif
              <button class="crm2-icon-btn edit" onclick='editActivity({{ $act->id }}, @json($act))' title="Edit"><i class="fas fa-edit"></i></button>
              <form method="POST" action="{{ route('admin.crm2.activity.destroy', $act->id) }}" onsubmit="return confirm('Delete?')" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" class="crm2-icon-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="5">
            <div class="crm2-empty">
              <i class="fas {{ $tab==='tasks'?'fa-check-square':($tab==='meetings'?'fa-calendar-alt':'fa-phone-alt') }}"></i>
              <p>No {{ $tab }} found.</p>
            </div>
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($activities->hasPages())<div class="crm2-pagination">{{ $activities->links() }}</div>@endif
  </div>

  {{-- Create Modal --}}
  <div class="crm2-modal-overlay" id="modal-create-activity">
    <div class="crm2-modal">
      <div class="crm2-modal-header">
        <h3><i class="fas fa-plus-circle"></i> New {{ $tab === 'tasks' ? 'Task' : ($tab === 'meetings' ? 'Meeting' : 'Call') }}</h3>
        <button onclick="closeModal('modal-create-activity')"><i class="fas fa-times"></i></button>
      </div>
      <form method="POST" action="{{ route('admin.crm2.activity.store') }}">@csrf
        <input type="hidden" name="type" value="{{ $tab === 'tasks' ? 'task' : ($tab === 'meetings' ? 'meeting' : 'call') }}">
        <div class="crm2-modal-body">
          <div class="crm2-form-grid">
            <div class="form-group full"><label>Subject *</label><input type="text" name="subject" class="crm2-input" required></div>
            <div class="form-group"><label>Due At</label><input type="datetime-local" name="due_at" class="crm2-input"></div>
            <div class="form-group"><label>Status</label>
              <select name="status" class="crm2-select"><option value="pending">Pending</option><option value="completed">Completed</option><option value="cancelled">Cancelled</option></select>
            </div>
            <div class="form-group"><label>Related To</label>
              <select name="related_type" class="crm2-select" id="related_type_sel" onchange="updateRelatedList()">
                <option value="">— None —</option>
                <option value="App\Models\CrmLead">Lead</option>
                <option value="App\Models\CrmContact">Contact</option>
                <option value="App\Models\CrmAccount">Account</option>
                <option value="App\Models\CrmDeal">Deal</option>
              </select>
            </div>
            <div class="form-group" id="related_id_group" style="display:none">
              <label>Select Record</label>
              <select name="related_id" class="crm2-select" id="related_id_sel">
                <option value="">— Select —</option>
              </select>
            </div>
            <div class="form-group full"><label>Description</label><textarea name="description" class="crm2-textarea" rows="3"></textarea></div>
          </div>
        </div>
        <div class="crm2-modal-footer">
          <button type="button" onclick="closeModal('modal-create-activity')" class="crm2-btn crm2-btn-ghost">Cancel</button>
          <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save</button>
        </div>
      </form>
    </div>
  </div>

  {{-- Edit Modal --}}
  <div class="crm2-modal-overlay" id="modal-edit-activity">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-edit"></i> Edit Activity</h3><button onclick="closeModal('modal-edit-activity')"><i class="fas fa-times"></i></button></div>
      <form id="edit-activity-form" method="POST">@csrf @method('PATCH')
        <div class="crm2-modal-body">
          <div class="crm2-form-grid">
            <div class="form-group full"><label>Subject *</label><input type="text" name="subject" id="ea-subject" class="crm2-input" required></div>
            <div class="form-group"><label>Due At</label><input type="datetime-local" name="due_at" id="ea-due_at" class="crm2-input"></div>
            <div class="form-group"><label>Status</label>
              <select name="status" id="ea-status" class="crm2-select"><option value="pending">Pending</option><option value="completed">Completed</option><option value="cancelled">Cancelled</option></select>
            </div>
            <div class="form-group full"><label>Description</label><textarea name="description" id="ea-description" class="crm2-textarea" rows="3"></textarea></div>
          </div>
        </div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-edit-activity')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update</button></div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

const relatedData = {
  'App\\Models\\CrmLead': @json($leads_list->map(fn($l) => ['id'=>$l->id,'label'=>$l->name])),
  'App\\Models\\CrmContact': @json($contacts_list->map(fn($c) => ['id'=>$c->id,'label'=>$c->first_name.' '.$c->last_name])),
  'App\\Models\\CrmAccount': @json($accounts_list->map(fn($a) => ['id'=>$a->id,'label'=>$a->name])),
};

function updateRelatedList() {
  const type = document.getElementById('related_type_sel').value;
  const group = document.getElementById('related_id_group');
  const sel = document.getElementById('related_id_sel');
  if (!type) { group.style.display = 'none'; return; }
  group.style.display = 'block';
  const items = relatedData[type] || [];
  sel.innerHTML = '<option value="">— Select —</option>' + items.map(i => `<option value="${i.id}">${i.label}</option>`).join('');
}

function editActivity(id, data) {
  document.getElementById('edit-activity-form').action = `/admin/crm2/activity/${id}`;
  document.getElementById('ea-subject').value = data.subject || '';
  document.getElementById('ea-due_at').value = data.due_at ? data.due_at.replace(' ','T').substring(0,16) : '';
  document.getElementById('ea-status').value = data.status || 'pending';
  document.getElementById('ea-description').value = data.description || '';
  openModal('modal-edit-activity');
}

function completeActivity(id, btn) {
  fetch(`/admin/crm2/activity/${id}/complete`, {
    method: 'PATCH',
    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
  }).then(r => r.json()).then(d => {
    if (d.success) { btn.closest('tr').classList.add('row-muted'); btn.remove(); location.reload(); }
  });
}
</script>
@endpush
@endsection
