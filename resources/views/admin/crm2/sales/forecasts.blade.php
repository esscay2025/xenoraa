@extends('layouts.admin')
@section('title', 'Forecasts')
@section('page-title', 'Forecasts')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-chart-pie"></i> Forecasts</h1><p class="crm2-subtitle">Manage quarterly sales forecasts and targets.</p></div>
    <a href="{{ route('admin.crm2.sales.forecasts.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Forecast</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Year</th><th>Quarter</th><th>Target</th><th>Achieved</th><th>Achievement %</th><th>Notes</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($forecasts as $f)
        <tr>
          <td>{{ $f->year }}</td>
          <td>Q{{ $f->quarter }}</td>
          <td>₹{{ number_format($f->target_amount,0) }}</td>
          <td>₹{{ number_format($f->achieved_amount,0) }}</td>
          <td>@php $pct = $f->target_amount > 0 ? round(($f->achieved_amount/$f->target_amount)*100) : 0; @endphp
            <span class="crm2-badge {{ $pct >= 100 ? 'status-won' : ($pct >= 50 ? 'status-qualified' : 'status-new') }}">{{ $pct }}%</span>
          </td>
          <td>{{ Str::limit($f->notes ?? '—', 40) }}</td>
          <td class="actions-cell">
            <button class="crm2-icon-btn edit" onclick='editRecord("forecast", {{ $f->id }}, @json($f))' title="Edit"><i class="fas fa-edit"></i></button>
            <form method="POST" action="{{ route('admin.crm2.sales.destroy', ['type'=>'forecast','id'=>$f->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="7"><div class="crm2-empty"><i class="fas fa-chart-pie"></i><p>No forecasts found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($forecasts->hasPages())<div class="crm2-pagination">{{ $forecasts->links() }}</div>@endif
  </div>
</div>
<div class="crm2-modal-overlay" id="modal-edit-record">
  <div class="crm2-modal">
    <div class="crm2-modal-header"><h3>Edit Forecast</h3><button onclick="closeModal('modal-edit-record')"><i class="fas fa-times"></i></button></div>
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
    <div class="form-group"><label>Year</label><input name="year" type="number" class="crm2-input" value="${data.year}"></div>
    <div class="form-group"><label>Quarter</label><select name="quarter" class="crm2-select">${[1,2,3,4].map(q=>`<option value="${q}" ${data.quarter==q?'selected':''}>${'Q'+q}</option>`).join('')}</select></div>
    <div class="form-group"><label>Target (₹)</label><input name="target_amount" type="number" class="crm2-input" value="${data.target_amount}"></div>
    <div class="form-group"><label>Achieved (₹)</label><input name="achieved_amount" type="number" class="crm2-input" value="${data.achieved_amount}"></div>
    <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="2">${esc(data.notes)}</textarea></div>
  </div>`;
  openModal('modal-edit-record');
}
</script>
@endpush
@endsection
