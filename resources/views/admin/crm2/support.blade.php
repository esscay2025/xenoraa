@extends('layouts.admin')
@section('title', 'CRM Support')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-headset"></i> Support</h1>
      <p class="crm2-subtitle">Manage customer support cases and build your solutions knowledge base.</p>
    </div>
    <button class="crm2-btn crm2-btn-primary" onclick="openModal('modal-create-{{ $tab }}')">
      <i class="fas fa-plus"></i> New {{ $tab === 'cases' ? 'Case' : 'Solution' }}
    </button>
  </div>

  {{-- Tabs --}}
  <div class="crm2-tabs">
    <a href="{{ route('admin.crm2.support', ['tab'=>'cases']) }}" class="crm2-tab {{ $tab==='cases'?'active':'' }}"><i class="fas fa-ticket-alt"></i> Cases</a>
    <a href="{{ route('admin.crm2.support', ['tab'=>'solutions']) }}" class="crm2-tab {{ $tab==='solutions'?'active':'' }}"><i class="fas fa-lightbulb"></i> Solutions</a>
  </div>

  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

  {{-- Filter --}}
  <div class="crm2-card mb-4">
    <div class="crm2-card-body">
      <form method="GET" class="crm2-filter-form">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="crm2-input"></div>
        @if($tab === 'cases')
        <div class="filter-group">
          <select name="status" class="crm2-select"><option value="">All Status</option>@foreach(\App\Models\CrmCase::STATUSES as $k=>$v)<option value="{{ $k }}" {{ request('status')===$k?'selected':'' }}>{{ $v }}</option>@endforeach</select>
        </div>
        <div class="filter-group">
          <select name="priority" class="crm2-select"><option value="">All Priority</option>@foreach(\App\Models\CrmCase::PRIORITIES as $k=>$v)<option value="{{ $k }}" {{ request('priority')===$k?'selected':'' }}>{{ $v }}</option>@endforeach</select>
        </div>
        @endif
        <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('admin.crm2.support', ['tab'=>$tab]) }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
      </form>
    </div>
  </div>

  {{-- CASES --}}
  @if($tab === 'cases')
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>Case #</th><th>Subject</th><th>Account</th><th>Priority</th><th>Status</th><th>Origin</th><th>Created</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($cases as $case)
          <tr>
            <td><code>{{ $case->case_number }}</code></td>
            <td><strong>{{ $case->subject }}</strong></td>
            <td>{{ $case->account?->name ?? '—' }}</td>
            <td><span class="crm2-badge priority-{{ $case->priority }}">{{ \App\Models\CrmCase::PRIORITIES[$case->priority] ?? $case->priority }}</span></td>
            <td><span class="crm2-badge status-{{ $case->status }}">{{ \App\Models\CrmCase::STATUSES[$case->status] ?? $case->status }}</span></td>
            <td>{{ ucfirst($case->origin) }}</td>
            <td>{{ $case->created_at->format('d M Y') }}</td>
            <td class="actions-cell">
              <button class="crm2-icon-btn edit" onclick='editCase({{ $case->id }}, @json($case))' title="Edit"><i class="fas fa-edit"></i></button>
              <form method="POST" action="{{ route('admin.crm2.support.destroy', ['type'=>'case','id'=>$case->id]) }}" onsubmit="return confirm('Delete this case?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
            </td>
          </tr>
          @empty
          <tr><td colspan="8"><div class="crm2-empty"><i class="fas fa-ticket-alt"></i><p>No cases found.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($cases->hasPages())<div class="crm2-pagination">{{ $cases->links() }}</div>@endif
  </div>

  {{-- SOLUTIONS --}}
  @elseif($tab === 'solutions')
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>Title</th><th>Category</th><th>Question</th><th>Public</th><th>Views</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($solutions as $sol)
          <tr>
            <td><strong>{{ $sol->title }}</strong></td>
            <td>{{ $sol->category ?? '—' }}</td>
            <td>{{ Str::limit($sol->question, 60) ?? '—' }}</td>
            <td><span class="crm2-badge {{ $sol->is_public ? 'status-active' : 'status-inactive' }}">{{ $sol->is_public ? 'Public' : 'Private' }}</span></td>
            <td>{{ $sol->view_count }}</td>
            <td class="actions-cell">
              <button class="crm2-icon-btn edit" onclick='editSolution({{ $sol->id }}, @json($sol))' title="Edit"><i class="fas fa-edit"></i></button>
              <form method="POST" action="{{ route('admin.crm2.support.destroy', ['type'=>'solution','id'=>$sol->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6"><div class="crm2-empty"><i class="fas fa-lightbulb"></i><p>No solutions yet. Build your knowledge base!</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($solutions->hasPages())<div class="crm2-pagination">{{ $solutions->links() }}</div>@endif
  </div>
  @endif

  {{-- Create Case Modal --}}
  <div class="crm2-modal-overlay" id="modal-create-cases">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-ticket-alt"></i> New Case</h3><button onclick="closeModal('modal-create-cases')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.support.store') }}">@csrf
        <input type="hidden" name="_type" value="case">
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Subject *</label><input type="text" name="subject" class="crm2-input" required></div>
          <div class="form-group"><label>Priority *</label><select name="priority" class="crm2-select" required>@foreach(\App\Models\CrmCase::PRIORITIES as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
          <div class="form-group"><label>Status *</label><select name="status" class="crm2-select" required>@foreach(\App\Models\CrmCase::STATUSES as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
          <div class="form-group"><label>Type</label><select name="type" class="crm2-select"><option value="">— None —</option><option value="question">Question</option><option value="problem">Problem</option><option value="feature_request">Feature Request</option><option value="other">Other</option></select></div>
          <div class="form-group"><label>Origin</label><select name="origin" class="crm2-select"><option value="web">Web</option><option value="email">Email</option><option value="phone">Phone</option><option value="chat">Chat</option></select></div>
          <div class="form-group"><label>Account</label><select name="account_id" class="crm2-select"><option value="">— None —</option>@foreach($accounts_list as $a)<option value="{{ $a->id }}">{{ $a->name }}</option>@endforeach</select></div>
          <div class="form-group"><label>Contact</label><select name="contact_id" class="crm2-select"><option value="">— None —</option>@foreach($contacts_list as $c)<option value="{{ $c->id }}">{{ $c->first_name }} {{ $c->last_name }}</option>@endforeach</select></div>
          <div class="form-group full"><label>Description</label><textarea name="description" class="crm2-textarea" rows="3"></textarea></div>
          <div class="form-group full"><label>Resolution</label><textarea name="resolution" class="crm2-textarea" rows="2"></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-cases')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Case</button></div>
      </form>
    </div>
  </div>

  {{-- Create Solution Modal --}}
  <div class="crm2-modal-overlay" id="modal-create-solutions">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-lightbulb"></i> New Solution</h3><button onclick="closeModal('modal-create-solutions')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.support.store') }}">@csrf
        <input type="hidden" name="_type" value="solution">
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Title *</label><input type="text" name="title" class="crm2-input" required></div>
          <div class="form-group"><label>Category</label><input type="text" name="category" class="crm2-input" placeholder="e.g. Billing, Technical, General"></div>
          <div class="form-group"><label>Visibility</label><select name="is_public" class="crm2-select"><option value="1">Public</option><option value="0">Private</option></select></div>
          <div class="form-group full"><label>Question / Problem</label><textarea name="question" class="crm2-textarea" rows="2"></textarea></div>
          <div class="form-group full"><label>Answer / Solution *</label><textarea name="answer" class="crm2-textarea" rows="4" required></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-solutions')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Solution</button></div>
      </form>
    </div>
  </div>

  {{-- Edit Case Modal --}}
  <div class="crm2-modal-overlay" id="modal-edit-case">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-edit"></i> Edit Case</h3><button onclick="closeModal('modal-edit-case')"><i class="fas fa-times"></i></button></div>
      <form id="edit-case-form" method="POST">@csrf @method('PATCH')
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Subject</label><input type="text" name="subject" id="ec-subject" class="crm2-input"></div>
          <div class="form-group"><label>Priority</label><select name="priority" id="ec-priority" class="crm2-select">@foreach(\App\Models\CrmCase::PRIORITIES as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
          <div class="form-group"><label>Status</label><select name="status" id="ec-status" class="crm2-select">@foreach(\App\Models\CrmCase::STATUSES as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
          <div class="form-group full"><label>Resolution</label><textarea name="resolution" id="ec-resolution" class="crm2-textarea" rows="3"></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-edit-case')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update</button></div>
      </form>
    </div>
  </div>

  {{-- Edit Solution Modal --}}
  <div class="crm2-modal-overlay" id="modal-edit-solution">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-edit"></i> Edit Solution</h3><button onclick="closeModal('modal-edit-solution')"><i class="fas fa-times"></i></button></div>
      <form id="edit-solution-form" method="POST">@csrf @method('PATCH')
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Title</label><input type="text" name="title" id="es-title" class="crm2-input"></div>
          <div class="form-group"><label>Category</label><input type="text" name="category" id="es-category" class="crm2-input"></div>
          <div class="form-group"><label>Visibility</label><select name="is_public" id="es-public" class="crm2-select"><option value="1">Public</option><option value="0">Private</option></select></div>
          <div class="form-group full"><label>Question</label><textarea name="question" id="es-question" class="crm2-textarea" rows="2"></textarea></div>
          <div class="form-group full"><label>Answer *</label><textarea name="answer" id="es-answer" class="crm2-textarea" rows="4"></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-edit-solution')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update</button></div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

function editCase(id, data) {
  document.getElementById('edit-case-form').action = `/admin/crm2/support/case/${id}`;
  document.getElementById('ec-subject').value = data.subject || '';
  document.getElementById('ec-priority').value = data.priority || 'medium';
  document.getElementById('ec-status').value = data.status || 'new';
  document.getElementById('ec-resolution').value = data.resolution || '';
  openModal('modal-edit-case');
}

function editSolution(id, data) {
  document.getElementById('edit-solution-form').action = `/admin/crm2/support/solution/${id}`;
  document.getElementById('es-title').value = data.title || '';
  document.getElementById('es-category').value = data.category || '';
  document.getElementById('es-public').value = data.is_public ? '1' : '0';
  document.getElementById('es-question').value = data.question || '';
  document.getElementById('es-answer').value = data.answer || '';
  openModal('modal-edit-solution');
}
</script>
@endpush
@endsection
