@extends('layouts.admin')
@section('title', 'Leads')
@section('page-title', 'Leads')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-user-tag"></i> Leads</h1>
      <p class="crm2-subtitle">Track and manage your sales leads.</p>
    </div>
    <a href="{{ route('admin.crm2.sales.leads.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Lead</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  @if(session('error'))<div class="crm2-alert danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif
  <div class="crm2-card mb-4"><div class="crm2-card-body">
    <form method="GET" class="crm2-filter-form">
      <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search leads..." class="crm2-input"></div>
      <div class="filter-group"><select name="status" class="crm2-select"><option value="">All Status</option>@foreach(['new','contacted','qualified','proposal','won','lost'] as $s)<option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
      <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
      <a href="{{ route('admin.crm2.sales.leads') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
    </form>
  </div></div>
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Name</th><th>Email</th><th>Company</th><th>Source</th><th>Status</th><th>Value</th><th>Created</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($leads as $lead)
        <tr>
          <td><strong>{{ $lead->name }}</strong></td>
          <td>{{ $lead->email ?? '—' }}</td>
          <td>{{ $lead->company ?? '—' }}</td>
          <td>{{ ucfirst($lead->source ?? 'manual') }}</td>
          <td><span class="crm2-badge status-{{ $lead->status ?? 'new' }}">{{ ucfirst($lead->status ?? 'New') }}</span></td>
          <td>{{ $lead->deal_value ? '₹'.number_format($lead->deal_value,0) : '—' }}</td>
          <td>{{ $lead->created_at->format('d M Y') }}</td>
          <td class="actions-cell">
            <button class="crm2-icon-btn edit" onclick='editRecord("lead", {{ $lead->id }}, @json($lead))' title="Edit"><i class="fas fa-edit"></i></button>
            <form method="POST" action="{{ route('admin.crm2.sales.destroy', ['type'=>'lead','id'=>$lead->id]) }}" onsubmit="return confirm('Delete this lead?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete" title="Delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="8"><div class="crm2-empty"><i class="fas fa-user-tag"></i><p>No leads found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($leads->hasPages())<div class="crm2-pagination">{{ $leads->links() }}</div>@endif
  </div>
</div>
{{-- Edit Modal --}}
<div class="crm2-modal-overlay" id="modal-edit-record">
  <div class="crm2-modal">
    <div class="crm2-modal-header"><h3 id="edit-modal-title">Edit Lead</h3><button onclick="closeModal('modal-edit-record')"><i class="fas fa-times"></i></button></div>
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
  form.action=`/admin/crm2/sales/${type}/${id}`;
  document.getElementById('edit-modal-title').innerHTML=`<i class="fas fa-edit"></i> Edit Lead`;
  function esc(v){return v?String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'):''}
  document.getElementById('edit-modal-body').innerHTML=`<div class="crm2-form-grid">
    <div class="form-group"><label>Name *</label><input name="name" class="crm2-input" value="${esc(data.name)}" required></div>
    <div class="form-group"><label>Email</label><input name="email" class="crm2-input" value="${esc(data.email)}"></div>
    <div class="form-group"><label>Phone</label><input name="phone" class="crm2-input" value="${esc(data.phone)}"></div>
    <div class="form-group"><label>Company</label><input name="company" class="crm2-input" value="${esc(data.company)}"></div>
    <div class="form-group"><label>Status</label><select name="status" class="crm2-select">${['new','contacted','qualified','proposal','won','lost'].map(s=>`<option value="${s}" ${data.status===s?'selected':''}>${s.charAt(0).toUpperCase()+s.slice(1)}</option>`).join('')}</select></div>
    <div class="form-group"><label>Deal Value</label><input name="deal_value" type="number" class="crm2-input" value="${data.deal_value||''}"></div>
    <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="3">${esc(data.notes)}</textarea></div>
  </div>`;
  openModal('modal-edit-record');
}
</script>
@endpush
@endsection
