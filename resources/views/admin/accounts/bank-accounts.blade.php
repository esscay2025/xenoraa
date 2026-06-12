@extends('layouts.admin')
@section('title', 'Bank Accounts')
@push('styles')
<style>
.acc-page{padding:1.5rem 2rem;}
.acc-page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:.75rem;}
.acc-page-title{font-size:1.5rem;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:.6rem;}
.acc-page-title i{color:#6366f1;}
.acc-bank-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;margin-bottom:1.5rem;}
.acc-bank-card{background:var(--card-bg);border:1px solid var(--border-color);border-radius:12px;padding:1.3rem 1.5rem;display:flex;flex-direction:column;gap:.8rem;position:relative;}
.acc-bank-card-top{display:flex;align-items:center;gap:.8rem;}
.acc-bank-card-icon{width:44px;height:44px;border-radius:10px;background:rgba(99,102,241,.15);display:flex;align-items:center;justify-content:center;color:#6366f1;font-size:1.1rem;}
.acc-bank-card-info{flex:1;}
.acc-bank-card-name{font-size:.95rem;font-weight:700;color:var(--text-primary);}
.acc-bank-card-type{font-size:.75rem;color:var(--text-muted);}
.acc-bank-card-balance{font-size:1.6rem;font-weight:800;color:var(--text-primary);}
.acc-bank-card-balance.neg{color:#ef4444;}
.acc-bank-card-meta{display:flex;gap:1rem;font-size:.75rem;color:var(--text-muted);}
.acc-bank-card-actions{display:flex;gap:.5rem;}
.acc-btn-sm{padding:.3rem .7rem;border-radius:6px;font-size:.75rem;font-weight:600;border:1px solid var(--border-color);background:var(--card-bg);color:var(--text-primary);cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;}
.acc-btn-sm:hover{background:rgba(99,102,241,.1);border-color:#6366f1;color:#6366f1;}
.acc-btn-sm.danger:hover{background:rgba(239,68,68,.1);border-color:#ef4444;color:#ef4444;}
/* Modal */
.acc-modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;}
.acc-modal-overlay.open{display:flex;}
.acc-modal{background:var(--card-bg);border-radius:14px;padding:1.8rem;width:100%;max-width:480px;border:1px solid var(--border-color);}
.acc-modal-title{font-size:1.1rem;font-weight:700;color:var(--text-primary);margin-bottom:1.2rem;}
.acc-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:.8rem;}
.acc-form-group{display:flex;flex-direction:column;gap:.3rem;}
.acc-form-group.full{grid-column:1/-1;}
.acc-form-label{font-size:.78rem;font-weight:600;color:var(--text-muted);}
.acc-form-control{padding:.5rem .75rem;border-radius:7px;border:1px solid var(--border-color);background:var(--input-bg,var(--card-bg));color:var(--text-primary);font-size:.85rem;width:100%;}
.acc-form-control:focus{outline:none;border-color:#6366f1;}
.acc-modal-footer{display:flex;justify-content:flex-end;gap:.6rem;margin-top:1.2rem;}
.acc-btn-primary{padding:.5rem 1.2rem;border-radius:7px;background:#6366f1;color:#fff;border:none;font-size:.85rem;font-weight:600;cursor:pointer;}
.acc-btn-cancel{padding:.5rem 1.2rem;border-radius:7px;background:transparent;color:var(--text-muted);border:1px solid var(--border-color);font-size:.85rem;cursor:pointer;}
</style>
@endpush

@section('content')
<div class="acc-page">
    <div class="acc-page-header">
        <div class="acc-page-title"><i class="fas fa-university"></i> Bank Accounts</div>
        <button class="xn-btn" onclick="openBankModal()" style="background:#6366f1;color:#fff;border:none;"><i class="fas fa-plus"></i> Add Account</button>
    </div>

    <div class="acc-bank-grid">
        @forelse($bankAccounts as $ba)
        <div class="acc-bank-card">
            <div class="acc-bank-card-top">
                <div class="acc-bank-card-icon"><i class="fas {{ $ba->type_icon }}"></i></div>
                <div class="acc-bank-card-info">
                    <div class="acc-bank-card-name">{{ $ba->name }}</div>
                    <div class="acc-bank-card-type">{{ $ba->type_label }}@if($ba->bank_name) · {{ $ba->bank_name }}@endif@if($ba->account_number) · ****{{ substr($ba->account_number,-4) }}@endif</div>
                </div>
            </div>
            <div class="acc-bank-card-balance {{ $ba->current_balance < 0 ? 'neg' : '' }}">₹{{ number_format($ba->current_balance, 2) }}</div>
            <div class="acc-bank-card-meta">
                <span><i class="fas fa-coins"></i> Opening: ₹{{ number_format($ba->opening_balance, 2) }}</span>
                @if($ba->currency !== 'INR')<span>{{ $ba->currency }}</span>@endif
            </div>
            <div class="acc-bank-card-actions">
                <a href="{{ route('admin.accounts.transactions', ['bank_account_id' => $ba->id]) }}" class="acc-btn-sm"><i class="fas fa-exchange-alt"></i> Transactions</a>
                <button class="acc-btn-sm" onclick="editBankAccount({{ $ba->id }}, '{{ addslashes($ba->name) }}', '{{ $ba->account_type }}', '{{ addslashes($ba->bank_name ?? '') }}', '{{ $ba->account_number ?? '' }}', '{{ $ba->ifsc_code ?? '' }}', '{{ $ba->currency }}', {{ $ba->opening_balance }})"><i class="fas fa-edit"></i> Edit</button>
                <form method="POST" action="{{ route('admin.accounts.bank-accounts.delete', $ba->id) }}" onsubmit="return confirm('Delete this account?')" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="acc-btn-sm danger"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--text-muted);">
            <i class="fas fa-university" style="font-size:2.5rem;margin-bottom:.8rem;opacity:.3;display:block;"></i>
            No bank accounts yet. Click <strong>Add Account</strong> to get started.
        </div>
        @endforelse
    </div>
</div>

{{-- Add/Edit Modal --}}
<div class="acc-modal-overlay" id="bankModal">
    <div class="acc-modal">
        <div class="acc-modal-title" id="bankModalTitle">Add Bank Account</div>
        <form method="POST" id="bankModalForm" action="{{ route('admin.accounts.bank-accounts.store') }}">
            @csrf
            <input type="hidden" name="_method" id="bankModalMethod" value="POST">
            <input type="hidden" name="bank_id" id="bankModalId" value="">
            <div class="acc-form-grid">
                <div class="acc-form-group full">
                    <label class="acc-form-label">Account Name *</label>
                    <input type="text" name="name" id="bName" class="acc-form-control" required placeholder="e.g. HDFC Current Account">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Account Type *</label>
                    <select name="account_type" id="bType" class="acc-form-control">
                        <option value="bank">Bank</option>
                        <option value="cash">Cash</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="savings">Savings</option>
                        <option value="wallet">Wallet</option>
                    </select>
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Bank Name</label>
                    <input type="text" name="bank_name" id="bBankName" class="acc-form-control" placeholder="e.g. HDFC Bank">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Account Number</label>
                    <input type="text" name="account_number" id="bAccNo" class="acc-form-control" placeholder="Last 4 digits">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">IFSC Code</label>
                    <input type="text" name="ifsc_code" id="bIfsc" class="acc-form-control" placeholder="HDFC0001234">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Currency</label>
                    <select name="currency" id="bCurrency" class="acc-form-control">
                        <option value="INR">INR</option>
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="GBP">GBP</option>
                        <option value="AED">AED</option>
                        <option value="SGD">SGD</option>
                    </select>
                </div>
                <div class="acc-form-group full">
                    <label class="acc-form-label">Opening Balance</label>
                    <input type="number" name="opening_balance" id="bOpenBal" class="acc-form-control" value="0" step="0.01">
                </div>
            </div>
            <div class="acc-modal-footer">
                <button type="button" class="acc-btn-cancel" onclick="closeBankModal()">Cancel</button>
                <button type="submit" class="acc-btn-primary">Save Account</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openBankModal() {
    document.getElementById('bankModalTitle').textContent = 'Add Bank Account';
    document.getElementById('bankModalMethod').value = 'POST';
    document.getElementById('bankModalForm').action = '{{ route("admin.accounts.bank-accounts.store") }}';
    document.getElementById('bankModalId').value = '';
    document.getElementById('bName').value = '';
    document.getElementById('bType').value = 'bank';
    document.getElementById('bBankName').value = '';
    document.getElementById('bAccNo').value = '';
    document.getElementById('bIfsc').value = '';
    document.getElementById('bCurrency').value = 'INR';
    document.getElementById('bOpenBal').value = '0';
    document.getElementById('bankModal').classList.add('open');
}
function editBankAccount(id, name, type, bankName, accNo, ifsc, currency, openBal) {
    document.getElementById('bankModalTitle').textContent = 'Edit Bank Account';
    document.getElementById('bankModalMethod').value = 'PUT';
    document.getElementById('bankModalForm').action = '/admin/accounts/bank-accounts/' + id;
    document.getElementById('bankModalId').value = id;
    document.getElementById('bName').value = name;
    document.getElementById('bType').value = type;
    document.getElementById('bBankName').value = bankName;
    document.getElementById('bAccNo').value = accNo;
    document.getElementById('bIfsc').value = ifsc;
    document.getElementById('bCurrency').value = currency;
    document.getElementById('bOpenBal').value = openBal;
    document.getElementById('bankModal').classList.add('open');
}
function closeBankModal() { document.getElementById('bankModal').classList.remove('open'); }
document.getElementById('bankModal').addEventListener('click', function(e) { if(e.target === this) closeBankModal(); });
</script>
@endpush
