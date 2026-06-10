@extends('layouts.admin')
@section('title', 'Cases')
@section('page-title', 'Cases')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-ticket-alt"></i> Cases</h1><p class="crm2-subtitle">Manage customer support cases.</p></div>
    <a href="{{ route('admin.crm2.support.cases.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Case</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card mb-4"><div class="crm2-card-body">
    <form method="GET" class="crm2-filter-form">
      <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search cases..." class="crm2-input"></div>
      <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
      <a href="{{ route('admin.crm2.support.cases') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
    </form>
  </div></div>
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Subject</th><th>Priority</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($cases as $case)
        <tr>
          <td><strong>{{ $case->subject }}</strong><br><small class="text-muted">{{ Str::limit($case->description ?? '', 60) }}</small></td>
          <td><span class="crm2-badge priority-{{ $case->priority ?? 'medium' }}">{{ ucfirst($case->priority ?? 'Medium') }}</span></td>
          <td><span class="crm2-badge status-{{ $case->status ?? 'new' }}">{{ ucfirst($case->status ?? 'Open') }}</span></td>
          <td>{{ $case->created_at->format('d M Y') }}</td>
          <td class="actions-cell">
            <button class="crm2-icon-btn edit" onclick='editRecord("case", {{ $case->id }}, @json($case))' title="Edit"><i class="fas fa-edit"></i></button>
            <form method="POST" action="{{ route('admin.crm2.support.destroy', ['type'=>'case','id'=>$case->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="5"><div class="crm2-empty"><i class="fas fa-ticket-alt"></i><p>No cases found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($cases->hasPages())<div class="crm2-pagination">{{ $cases->links() }}</div>@endif
  </div>
</div>
<div class="crm2-modal-overlay" id="modal-edit-record">
  <div class="crm2-modal">
    <div class="crm2-modal-header"><h3>Edit Case</h3><button onclick="closeModal('modal-edit-record')"><i class="fas fa-times"></i></button></div>
    <form id="edit-record-form" method="POST">@csrf @method('PATCH')
      <div class="crm2-modal-body" id="edit-modal-body"></div>
      <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-edit-record')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update</button></div>
    </form>
  </div>
</div>
@push('scripts')
<script>
function openModal(id){document.getElementById(id).classList.add('active');}
function closeModal(id){document.getElementById(id).classList.remove('active');}
function editRecord(type,id,data){
  const form=document.getElementById('edit-record-form');
  form.action=`/admin/crm2/support/${type}/${id}`;
  function esc(v){return v?String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'):''}
  document.getElementById('edit-modal-body').innerHTML=`<div class="crm2-form-grid">
    <div class="form-group full"><label>Subject *</label><input name="subject" class="crm2-input" value="${esc(data.subject)}" required></div>
    <div class="form-group"><label>Priority</label><select name="priority" class="crm2-select">${['low','medium','high','critical'].map(p=>`<option value="${p}" ${data.priority===p?'selected':''}>${p.charAt(0).toUpperCase()+p.slice(1)}</option>`).join('')}</select></div>
    <div class="form-group"><label>Status</label><select name="status" class="crm2-select">${['open','in_progress','resolved','closed'].map(s=>`<option value="${s}" ${data.status===s?'selected':''}>${s.replace(/_/g,' ').replace(/\w/g,l=>l.toUpperCase())}</option>`).join('')}</select></div>
    <div class="form-group full"><label>Description</label><textarea name="description" class="crm2-textarea" rows="4">${esc(data.description)}</textarea></div>
  </div>`;
  openModal('modal-edit-record');
}
</script>
@endpush
@endsection
