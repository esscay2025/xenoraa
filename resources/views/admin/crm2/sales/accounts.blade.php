@extends('layouts.admin')
@section('title', 'Accounts')
@section('page-title', 'Accounts')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-building"></i> Accounts</h1><p class="crm2-subtitle">Manage companies and organisations.</p></div>
    <a href="{{ route('admin.crm2.sales.accounts.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Account</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card mb-4"><div class="crm2-card-body">
    <form method="GET" class="crm2-filter-form">
      <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search accounts..." class="crm2-input"></div>
      <div class="filter-group"><select name="type" class="crm2-select"><option value="">All Types</option>@foreach(['prospect','customer','partner','vendor'] as $t)<option value="{{ $t }}" {{ request('type')===$t?'selected':'' }}>{{ ucfirst($t) }}</option>@endforeach</select></div>
      <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
      <a href="{{ route('admin.crm2.sales.accounts') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
    </form>
  </div></div>
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Name</th><th>Type</th><th>Industry</th><th>Email</th><th>Contacts</th><th>Deals</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($accounts as $account)
        <tr>
          <td><strong>{{ $account->name }}</strong></td>
          <td><span class="crm2-badge">{{ ucfirst($account->type) }}</span></td>
          <td>{{ $account->industry ?? '—' }}</td>
          <td>{{ $account->email ?? '—' }}</td>
          <td>{{ $account->contacts_count }}</td>
          <td>{{ $account->deals_count }}</td>
          <td class="actions-cell">
            <button class="crm2-icon-btn edit" onclick='editRecord("account", {{ $account->id }}, @json($account))' title="Edit"><i class="fas fa-edit"></i></button>
            <form method="POST" action="{{ route('admin.crm2.sales.destroy', ['type'=>'account','id'=>$account->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="7"><div class="crm2-empty"><i class="fas fa-building"></i><p>No accounts found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($accounts->hasPages())<div class="crm2-pagination">{{ $accounts->links() }}</div>@endif
  </div>
</div>
<div class="crm2-modal-overlay" id="modal-edit-record">
  <div class="crm2-modal">
    <div class="crm2-modal-header"><h3>Edit Account</h3><button onclick="closeModal('modal-edit-record')"><i class="fas fa-times"></i></button></div>
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
  function esc(v){return v?String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'):''}
  document.getElementById('edit-modal-body').innerHTML=`<div class="crm2-form-grid">
    <div class="form-group"><label>Name *</label><input name="name" class="crm2-input" value="${esc(data.name)}" required></div>
    <div class="form-group"><label>Type</label><select name="type" class="crm2-select">${['prospect','customer','partner','vendor'].map(t=>`<option value="${t}" ${data.type===t?'selected':''}>${t.charAt(0).toUpperCase()+t.slice(1)}</option>`).join('')}</select></div>
    <div class="form-group"><label>Industry</label><input name="industry" class="crm2-input" value="${esc(data.industry)}"></div>
    <div class="form-group"><label>Email</label><input name="email" class="crm2-input" value="${esc(data.email)}"></div>
    <div class="form-group"><label>Phone</label><input name="phone" class="crm2-input" value="${esc(data.phone)}"></div>
    <div class="form-group"><label>Status</label><select name="status" class="crm2-select"><option value="active" ${data.status==="active"?"selected":""}>Active</option><option value="inactive" ${data.status==="inactive"?"selected":""}>Inactive</option></select></div>
  </div>`;
  openModal('modal-edit-record');
}
</script>
@endpush
@endsection
