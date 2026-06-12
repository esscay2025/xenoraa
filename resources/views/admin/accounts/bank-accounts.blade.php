@extends('layouts.admin')
@section('title', 'Bank Accounts')
@section('page-title', 'Bank Accounts')
@push('styles')
<style>
.acc-bank-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:1rem; margin-bottom:1.5rem; }
.acc-bank-card { background:var(--bg-card,#fff); border:1px solid var(--border,#e2e8f0); border-radius:12px; padding:1.2rem 1.4rem; display:flex; flex-direction:column; gap:.5rem; position:relative; }
.acc-bank-card-icon { width:42px; height:42px; border-radius:10px; background:rgba(99,102,241,.15); display:flex; align-items:center; justify-content:center; color:#6366f1; font-size:1.1rem; margin-bottom:.3rem; }
.acc-bank-card-name { font-size:1rem; font-weight:700; color:var(--text-primary,#1a1a2e); }
.acc-bank-card-meta { font-size:.75rem; color:var(--text-muted,#64748b); }
.acc-bank-card-balance { font-size:1.4rem; font-weight:700; color:var(--text-primary,#1a1a2e); margin-top:.3rem; }
.acc-bank-card-balance.neg { color:#ef4444; }
.acc-bank-card-actions { display:flex; gap:.5rem; margin-top:.5rem; }

.crm2-table th { white-space: nowrap; }
.crm2-table td { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; }
.crm2-table td.wrap { white-space: normal; }
</style>
@endpush
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-university"></i> Bank Accounts</h1>
    </div>
    <button class="crm2-btn crm2-btn-primary" onclick="document.getElementById('addBankModal').style.display='flex'"><i class="fas fa-plus"></i> Add Account</button>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  @if(session('error'))<div class="crm2-alert danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif

  {{-- Summary KPIs --}}
  <div class="crm2-card mb-4">
    <div class="crm2-card-body" style="display:flex;gap:2rem;flex-wrap:wrap;align-items:center;">
      <div>
        <div style="font-size:.72rem;color:var(--text-muted,#64748b);text-transform:uppercase;letter-spacing:.04em;">Total Cash Balance</div>
        <div style="font-size:1.4rem;font-weight:700;color:#3b82f6;">₹{{ number_format($bankAccounts->sum('current_balance'), 2) }}</div>
      </div>
      <div>
        <div style="font-size:.72rem;color:var(--text-muted,#64748b);text-transform:uppercase;letter-spacing:.04em;">Accounts</div>
        <div style="font-size:1.4rem;font-weight:700;color:var(--text-primary,#1a1a2e);">{{ $bankAccounts->count() }}</div>
      </div>
    </div>
  </div>

  {{-- Account Cards --}}
  <div class="acc-bank-grid">
    @forelse($bankAccounts as $ba)
    <div class="acc-bank-card">
      <div class="acc-bank-card-icon">
        <i class="fas fa-{{ $ba->type === 'cash' ? 'wallet' : ($ba->type === 'credit_card' ? 'credit-card' : 'university') }}"></i>
      </div>
      <div class="acc-bank-card-name">{{ $ba->name }}</div>
      <div class="acc-bank-card-meta">
        {{ ucfirst(str_replace('_',' ',$ba->type)) }}
        @if($ba->bank_name) · {{ $ba->bank_name }}@endif
        @if($ba->account_number) · ****{{ substr($ba->account_number,-4) }}@endif
      </div>
      <div class="acc-bank-card-balance {{ $ba->current_balance < 0 ? 'neg' : '' }}">₹{{ number_format($ba->current_balance, 2) }}</div>
      @if($ba->description)<div style="font-size:.75rem;color:var(--text-muted,#64748b);">{{ $ba->description }}</div>@endif
      <div class="acc-bank-card-actions">
        <button class="crm2-btn crm2-btn-secondary" style="font-size:.75rem;padding:.3rem .7rem;" onclick="editBank({{ $ba->id }}, '{{ addslashes($ba->name) }}', '{{ $ba->type }}', '{{ addslashes($ba->bank_name ?? '') }}', '{{ addslashes($ba->account_number ?? '') }}', {{ $ba->opening_balance }}, '{{ addslashes($ba->description ?? '') }}')"><i class="fas fa-edit"></i> Edit</button>
        <form method="POST" action="{{ route('admin.accounts.bank-accounts.delete', $ba->id) }}" onsubmit="return confirm('Delete this account?')" style="display:inline">
          @csrf @method('DELETE')
          <button type="submit" class="crm2-btn" style="font-size:.75rem;padding:.3rem .7rem;background:#ef4444;color:#fff;border:none;"><i class="fas fa-trash"></i></button>
        </form>
      </div>
    </div>
    @empty
    <div class="crm2-empty" style="grid-column:1/-1;"><i class="fas fa-university"></i><p>No bank accounts yet. Add your first account.</p></div>
    @endforelse
  </div>
</div>

{{-- Add Bank Account Modal --}}
<div id="addBankModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
  <div class="crm2-card" style="width:100%;max-width:480px;margin:1rem;">
    <div class="crm2-card-body">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
        <h3 class="crm2-title" style="font-size:1.1rem;margin:0;"><i class="fas fa-university"></i> Add Bank Account</h3>
        <button onclick="document.getElementById('addBankModal').style.display='none'" style="background:none;border:none;color:var(--text-muted,#64748b);font-size:1.2rem;cursor:pointer;"><i class="fas fa-times"></i></button>
      </div>
      <form method="POST" action="{{ route('admin.accounts.bank-accounts.store') }}">
        @csrf
        <div class="crm2-form-group">
          <label class="crm2-label">Account Name *</label>
          <input type="text" name="name" class="crm2-input" required placeholder="e.g. HDFC Current Account">
        </div>
        <div class="crm2-form-group">
          <label class="crm2-label">Account Type *</label>
          <select name="type" class="crm2-select" required>
            <option value="bank_account">Bank Account</option>
            <option value="savings_account">Savings Account</option>
            <option value="credit_card">Credit Card</option>
            <option value="cash">Cash</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="crm2-form-group">
          <label class="crm2-label">Bank Name</label>
          <input type="text" name="bank_name" class="crm2-input" placeholder="e.g. HDFC Bank">
        </div>
        <div class="crm2-form-group">
          <label class="crm2-label">Account Number</label>
          <input type="text" name="account_number" class="crm2-input" placeholder="Last 4 digits or full number">
        </div>
        <div class="crm2-form-group">
          <label class="crm2-label">Opening Balance (₹)</label>
          <input type="number" name="opening_balance" class="crm2-input" value="0" step="0.01">
        </div>
        <div class="crm2-form-group">
          <label class="crm2-label">Description</label>
          <input type="text" name="description" class="crm2-input" placeholder="Optional note">
        </div>
        <div style="display:flex;gap:.6rem;justify-content:flex-end;margin-top:1rem;">
          <button type="button" onclick="document.getElementById('addBankModal').style.display='none'" class="crm2-btn crm2-btn-ghost">Cancel</button>
          <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Account</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Edit Bank Account Modal --}}
<div id="editBankModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
  <div class="crm2-card" style="width:100%;max-width:480px;margin:1rem;">
    <div class="crm2-card-body">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
        <h3 class="crm2-title" style="font-size:1.1rem;margin:0;"><i class="fas fa-edit"></i> Edit Bank Account</h3>
        <button onclick="document.getElementById('editBankModal').style.display='none'" style="background:none;border:none;color:var(--text-muted,#64748b);font-size:1.2rem;cursor:pointer;"><i class="fas fa-times"></i></button>
      </div>
      <form id="editBankForm" method="POST">
        @csrf @method('PUT')
        <div class="crm2-form-group">
          <label class="crm2-label">Account Name *</label>
          <input type="text" id="editBankName" name="name" class="crm2-input" required>
        </div>
        <div class="crm2-form-group">
          <label class="crm2-label">Account Type *</label>
          <select id="editBankType" name="type" class="crm2-select" required>
            <option value="bank_account">Bank Account</option>
            <option value="savings_account">Savings Account</option>
            <option value="credit_card">Credit Card</option>
            <option value="cash">Cash</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="crm2-form-group">
          <label class="crm2-label">Bank Name</label>
          <input type="text" id="editBankBankName" name="bank_name" class="crm2-input">
        </div>
        <div class="crm2-form-group">
          <label class="crm2-label">Account Number</label>
          <input type="text" id="editBankAccNum" name="account_number" class="crm2-input">
        </div>
        <div class="crm2-form-group">
          <label class="crm2-label">Opening Balance (₹)</label>
          <input type="number" id="editBankOpenBal" name="opening_balance" class="crm2-input" step="0.01">
        </div>
        <div class="crm2-form-group">
          <label class="crm2-label">Description</label>
          <input type="text" id="editBankDesc" name="description" class="crm2-input">
        </div>
        <div style="display:flex;gap:.6rem;justify-content:flex-end;margin-top:1rem;">
          <button type="button" onclick="document.getElementById('editBankModal').style.display='none'" class="crm2-btn crm2-btn-ghost">Cancel</button>
          <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update Account</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
function editBank(id, name, type, bankName, accNum, openBal, desc) {
  document.getElementById('editBankForm').action = '/admin/accounts/bank-accounts/' + id;
  document.getElementById('editBankName').value = name;
  document.getElementById('editBankType').value = type;
  document.getElementById('editBankBankName').value = bankName;
  document.getElementById('editBankAccNum').value = accNum;
  document.getElementById('editBankOpenBal').value = openBal;
  document.getElementById('editBankDesc').value = desc;
  document.getElementById('editBankModal').style.display = 'flex';
}
</script>
@endpush
