@extends('layouts.admin')
@section('title', 'Deals')
@section('page-title', 'Deals')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-funnel-dollar"></i> Deals</h1><p class="crm2-subtitle">Track your sales pipeline and deals.</p></div>
    <a href="{{ route('admin.crm2.sales.deals.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Deal</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card mb-4"><div class="crm2-card-body">
    <form method="GET" class="crm2-filter-form">
      <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search deals..." class="crm2-input"></div>
      <div class="filter-group"><select name="stage" class="crm2-select"><option value="">All Stages</option>@foreach(['prospecting','qualification','proposal','negotiation','closed_won','closed_lost'] as $s)<option value="{{ $s }}" {{ request('stage')===$s?'selected':'' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>@endforeach</select></div>
      <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
      <a href="{{ route('admin.crm2.sales.deals') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
    </form>
  </div></div>
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Title</th><th>Value</th><th>Stage</th><th>Account</th><th>Probability</th><th>Expected Close</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($deals as $deal)
        <tr>
          <td><strong>{{ $deal->title }}</strong></td>
          <td>{{ $deal->value ? '₹'.number_format($deal->value,0) : '—' }}</td>
          <td><span class="crm2-badge stage-{{ str_replace('_','-',$deal->stage) }}">{{ ucwords(str_replace('_',' ',$deal->stage)) }}</span></td>
          <td>{{ $deal->account?->name ?? '—' }}</td>
          <td>{{ $deal->probability ?? 0 }}%</td>
          <td>{{ $deal->expected_close ? \Carbon\Carbon::parse($deal->expected_close)->format('d M Y') : '—' }}</td>
          <td class="actions-cell">
            <button class="crm2-icon-btn edit" onclick='editRecord("deal", {{ $deal->id }}, @json($deal))' title="Edit"><i class="fas fa-edit"></i></button>
            <form method="POST" action="{{ route('admin.crm2.sales.destroy', ['type'=>'deal','id'=>$deal->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="7"><div class="crm2-empty"><i class="fas fa-funnel-dollar"></i><p>No deals found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($deals->hasPages())<div class="crm2-pagination">{{ $deals->links() }}</div>@endif
  </div>
</div>
<div class="crm2-modal-overlay" id="modal-edit-record">
  <div class="crm2-modal">
    <div class="crm2-modal-header"><h3>Edit Deal</h3><button onclick="closeModal('modal-edit-record')"><i class="fas fa-times"></i></button></div>
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
    <div class="form-group full"><label>Title *</label><input name="title" class="crm2-input" value="${esc(data.title)}" required></div>
    <div class="form-group"><label>Value (₹)</label><input name="value" type="number" class="crm2-input" value="${data.value||''}"></div>
    <div class="form-group"><label>Stage</label><select name="stage" class="crm2-select">${['prospecting','qualification','proposal','negotiation','closed_won','closed_lost'].map(s=>`<option value="${s}" ${data.stage===s?'selected':''}>${s.replace(/_/g,' ').replace(/\w/g,l=>l.toUpperCase())}</option>`).join('')}</select></div>
    <div class="form-group"><label>Probability (%)</label><input name="probability" type="number" class="crm2-input" value="${data.probability||10}" min="0" max="100"></div>
    <div class="form-group"><label>Expected Close</label><input name="expected_close" type="date" class="crm2-input" value="${data.expected_close||''}"></div>
    <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="2">${esc(data.notes)}</textarea></div>
  </div>`;
  openModal('modal-edit-record');
}
</script>
@endpush
@endsection
