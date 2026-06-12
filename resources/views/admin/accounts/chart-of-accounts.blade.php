@extends('layouts.admin')
@section('title', 'Chart of Accounts')
@section('page-title', 'Chart of Accounts')
@push('styles')
<style>
.coa-type-header { display:flex; align-items:center; gap:.6rem; padding:.6rem .8rem; background:var(--bg-secondary,rgba(99,102,241,.06)); border-radius:8px; margin-bottom:.4rem; cursor:pointer; user-select:none; }
.coa-type-label { font-size:.82rem; font-weight:700; color:var(--crm-text); flex:1; }
.coa-type-total { font-size:.85rem; font-weight:700; color:var(--crm-primary); }
.coa-account-row { display:flex; align-items:center; gap:.6rem; padding:.5rem .8rem .5rem 2rem; border-bottom:1px solid var(--crm-border); font-size:.82rem; }
.coa-account-row:last-child { border-bottom:none; }
.coa-account-code { font-family:monospace; color:var(--crm-secondary); min-width:60px; }
.coa-account-name { flex:1; color:var(--crm-text); font-weight:500; }
.coa-account-balance { font-weight:600; color:var(--crm-text); min-width:110px; text-align:right; }

.crm2-table th { white-space: nowrap; }
.crm2-table td { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; }
.crm2-table td.wrap { white-space: normal; }
</style>
@endpush
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-sitemap"></i> Chart of Accounts</h1>
    </div>
    <button class="crm2-btn crm2-btn-primary" onclick="document.getElementById('addCoaModal').style.display='flex'"><i class="fas fa-plus"></i> Add Account</button>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

  @php
    $typeColors = ['Assets'=>'#3b82f6','Liabilities'=>'#ef4444','Equity'=>'#8b5cf6','Income'=>'#22c55e','Expenses'=>'#f59e0b'];
    $typeIcons  = ['Assets'=>'fa-coins','Liabilities'=>'fa-hand-holding-usd','Equity'=>'fa-balance-scale','Income'=>'fa-arrow-circle-down','Expenses'=>'fa-arrow-circle-up'];
  @endphp

  @foreach($accountsByType as $type => $accounts)
  <div class="crm2-card mb-3">
    <div class="crm2-card-body p-0">
      <div class="coa-type-header" onclick="toggleCoaSection('coa-{{ Str::slug($type) }}')">
        <i class="fas {{ $typeIcons[$type] ?? 'fa-folder' }}" style="color:{{ $typeColors[$type] ?? '#6366f1' }};font-size:.9rem;"></i>
        <span class="coa-type-label">{{ $type }}</span>
        <span class="crm2-badge" style="background:{{ $typeColors[$type] ?? '#6366f1' }}22;color:{{ $typeColors[$type] ?? '#6366f1' }};font-size:.7rem;">{{ $accounts->count() }} accounts</span>
        <span class="coa-type-total">₹{{ number_format($accounts->sum('balance'), 2) }}</span>
        <i class="fas fa-chevron-down" style="color:var(--crm-secondary);font-size:.75rem;transition:.2s;"></i>
      </div>
      <div id="coa-{{ Str::slug($type) }}">
        @foreach($accounts as $acc)
        <div class="coa-account-row">
          <span class="coa-account-code">{{ $acc->code }}</span>
          <span class="coa-account-name">{{ $acc->name }}</span>
          @if($acc->description)<span style="font-size:.72rem;color:var(--crm-secondary);flex:1;">{{ $acc->description }}</span>@endif
          <span class="crm2-badge status-{{ $acc->is_active ? 'won' : 'lost' }}" style="font-size:.68rem;">{{ $acc->is_active ? 'Active' : 'Inactive' }}</span>
          <span class="coa-account-balance">₹{{ number_format($acc->balance ?? 0, 2) }}</span>
          <div style="display:flex;gap:.3rem;">
            <button class="crm2-btn crm2-btn-ghost" style="padding:.2rem .45rem;font-size:.72rem;" onclick="editCoa({{ $acc->id }}, '{{ addslashes($acc->name) }}', '{{ addslashes($acc->code) }}', '{{ $acc->type }}', '{{ addslashes($acc->description ?? '') }}', {{ $acc->is_active ? 1 : 0 }})"><i class="fas fa-edit"></i></button>
            <form method="POST" action="{{ route('admin.accounts.coa.delete', $acc->id) }}" onsubmit="return confirm('Delete this account?')" style="display:inline">
              @csrf @method('DELETE')
              <button type="submit" class="crm2-btn" style="padding:.2rem .45rem;font-size:.72rem;background:#ef4444;color:#fff;border:none;"><i class="fas fa-trash"></i></button>
            </form>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  @endforeach
</div>

{{-- Add Account Modal --}}
<div id="addCoaModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
  <div class="crm2-card" style="width:100%;max-width:460px;margin:1rem;">
    <div class="crm2-card-body">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
        <h3 class="crm2-title" style="font-size:1.1rem;margin:0;"><i class="fas fa-plus"></i> Add Account</h3>
        <button onclick="document.getElementById('addCoaModal').style.display='none'" style="background:none;border:none;color:var(--crm-secondary);font-size:1.2rem;cursor:pointer;"><i class="fas fa-times"></i></button>
      </div>
      <form method="POST" action="{{ route('admin.accounts.coa.store') }}">
        @csrf
        <div class="crm2-form-group">
          <label class="crm2-label">Account Name *</label>
          <input type="text" name="name" class="crm2-input" required placeholder="e.g. Office Rent">
        </div>
        <div class="crm2-form-group">
          <label class="crm2-label">Account Code</label>
          <input type="text" name="code" class="crm2-input" placeholder="e.g. 5001">
        </div>
        <div class="crm2-form-group">
          <label class="crm2-label">Type *</label>
          <select name="type" class="crm2-select" required>
            <option value="Assets">Assets</option>
            <option value="Liabilities">Liabilities</option>
            <option value="Equity">Equity</option>
            <option value="Income">Income</option>
            <option value="Expenses">Expenses</option>
          </select>
        </div>
        <div class="crm2-form-group">
          <label class="crm2-label">Description</label>
          <input type="text" name="description" class="crm2-input" placeholder="Optional description">
        </div>
        <div style="display:flex;gap:.6rem;justify-content:flex-end;margin-top:1rem;">
          <button type="button" onclick="document.getElementById('addCoaModal').style.display='none'" class="crm2-btn crm2-btn-ghost">Cancel</button>
          <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Edit Account Modal --}}
<div id="editCoaModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
  <div class="crm2-card" style="width:100%;max-width:460px;margin:1rem;">
    <div class="crm2-card-body">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
        <h3 class="crm2-title" style="font-size:1.1rem;margin:0;"><i class="fas fa-edit"></i> Edit Account</h3>
        <button onclick="document.getElementById('editCoaModal').style.display='none'" style="background:none;border:none;color:var(--crm-secondary);font-size:1.2rem;cursor:pointer;"><i class="fas fa-times"></i></button>
      </div>
      <form id="editCoaForm" method="POST">
        @csrf @method('PUT')
        <div class="crm2-form-group">
          <label class="crm2-label">Account Name *</label>
          <input type="text" id="editCoaName" name="name" class="crm2-input" required>
        </div>
        <div class="crm2-form-group">
          <label class="crm2-label">Account Code</label>
          <input type="text" id="editCoaCode" name="code" class="crm2-input">
        </div>
        <div class="crm2-form-group">
          <label class="crm2-label">Type *</label>
          <select id="editCoaType" name="type" class="crm2-select" required>
            <option value="Assets">Assets</option>
            <option value="Liabilities">Liabilities</option>
            <option value="Equity">Equity</option>
            <option value="Income">Income</option>
            <option value="Expenses">Expenses</option>
          </select>
        </div>
        <div class="crm2-form-group">
          <label class="crm2-label">Description</label>
          <input type="text" id="editCoaDesc" name="description" class="crm2-input">
        </div>
        <div class="crm2-form-group" style="display:flex;align-items:center;gap:.5rem;">
          <input type="checkbox" id="editCoaActive" name="is_active" value="1">
          <label for="editCoaActive" class="crm2-label" style="margin:0;">Active</label>
        </div>
        <div style="display:flex;gap:.6rem;justify-content:flex-end;margin-top:1rem;">
          <button type="button" onclick="document.getElementById('editCoaModal').style.display='none'" class="crm2-btn crm2-btn-ghost">Cancel</button>
          <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
function toggleCoaSection(id) {
  const el = document.getElementById(id);
  if (el) el.style.display = el.style.display === 'none' ? '' : 'none';
}
function editCoa(id, name, code, type, desc, active) {
  document.getElementById('editCoaForm').action = '/admin/accounts/chart-of-accounts/' + id;
  document.getElementById('editCoaName').value = name;
  document.getElementById('editCoaCode').value = code;
  document.getElementById('editCoaType').value = type;
  document.getElementById('editCoaDesc').value = desc;
  document.getElementById('editCoaActive').checked = active == 1;
  document.getElementById('editCoaModal').style.display = 'flex';
}
</script>
@endpush
