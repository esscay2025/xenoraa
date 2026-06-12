@extends('layouts.admin')
@section('title', 'Income')
@push('styles')
<style>
.acc-page{padding:1.5rem 2rem;}
.acc-page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;flex-wrap:wrap;gap:.75rem;}
.acc-page-title{font-size:1.5rem;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:.6rem;}
.acc-page-title i{color:#22c55e;}
.acc-stat-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:.8rem;margin-bottom:1.2rem;}
.acc-stat{background:var(--card-bg);border:1px solid var(--border-color);border-radius:10px;padding:.9rem 1.1rem;}
.acc-stat-label{font-size:.72rem;color:var(--text-muted);text-transform:uppercase;}
.acc-stat-value{font-size:1.3rem;font-weight:700;color:var(--text-primary);}
.acc-stat-value.green{color:#22c55e;}
.acc-card{background:var(--card-bg);border:1px solid var(--border-color);border-radius:12px;overflow:hidden;}
.acc-table{width:100%;border-collapse:collapse;font-size:.83rem;}
.acc-table th{padding:.65rem 1rem;text-align:left;color:var(--text-muted);font-weight:600;font-size:.72rem;text-transform:uppercase;border-bottom:1px solid var(--border-color);background:var(--bg-secondary,rgba(255,255,255,.02));}
.acc-table td{padding:.7rem 1rem;border-bottom:1px solid var(--border-color);color:var(--text-primary);}
.acc-table tr:last-child td{border-bottom:none;}
.acc-table tr:hover td{background:rgba(34,197,94,.04);}
.acc-badge{display:inline-flex;align-items:center;padding:.2rem .6rem;border-radius:20px;font-size:.7rem;font-weight:600;}
.acc-badge.received{background:rgba(34,197,94,.15);color:#22c55e;}
.acc-badge.pending{background:rgba(245,158,11,.15);color:#f59e0b;}
.acc-badge.cancelled{background:rgba(107,114,128,.15);color:#6b7280;}
.acc-badge.recurring{background:rgba(99,102,241,.15);color:#6366f1;}
.acc-filters{display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1rem;align-items:center;}
.acc-filter-select,.acc-filter-input{padding:.4rem .75rem;border-radius:7px;border:1px solid var(--border-color);background:var(--card-bg);color:var(--text-primary);font-size:.82rem;}
.acc-btn-primary{padding:.42rem 1rem;border-radius:7px;background:#22c55e;color:#fff;border:none;font-size:.82rem;font-weight:600;cursor:pointer;}
/* Modal */
.acc-modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;}
.acc-modal-overlay.open{display:flex;}
.acc-modal{background:var(--card-bg);border-radius:14px;padding:1.8rem;width:100%;max-width:560px;border:1px solid var(--border-color);max-height:90vh;overflow-y:auto;}
.acc-modal-title{font-size:1.1rem;font-weight:700;color:var(--text-primary);margin-bottom:1.2rem;}
.acc-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:.8rem;}
.acc-form-group{display:flex;flex-direction:column;gap:.3rem;}
.acc-form-group.full{grid-column:1/-1;}
.acc-form-label{font-size:.78rem;font-weight:600;color:var(--text-muted);}
.acc-form-control{padding:.5rem .75rem;border-radius:7px;border:1px solid var(--border-color);background:var(--input-bg,var(--card-bg));color:var(--text-primary);font-size:.85rem;width:100%;}
.acc-form-control:focus{outline:none;border-color:#22c55e;}
.acc-modal-footer{display:flex;justify-content:flex-end;gap:.6rem;margin-top:1.2rem;}
.acc-btn-cancel{padding:.5rem 1.2rem;border-radius:7px;background:transparent;color:var(--text-muted);border:1px solid var(--border-color);font-size:.85rem;cursor:pointer;}
</style>
@endpush

@section('content')
<div class="acc-page">
    <div class="acc-page-header">
        <div class="acc-page-title"><i class="fas fa-arrow-circle-down"></i> Income</div>
        <button class="xn-btn" onclick="openIncomeModal()" style="background:#22c55e;color:#fff;border:none;"><i class="fas fa-plus"></i> Add Income</button>
    </div>

    <div class="acc-stat-row">
        <div class="acc-stat"><div class="acc-stat-label">Total Income</div><div class="acc-stat-value green">₹{{ number_format($totalIncome, 2) }}</div></div>
        <div class="acc-stat"><div class="acc-stat-label">This Month</div><div class="acc-stat-value green">₹{{ number_format($thisMonthIncome, 2) }}</div></div>
        <div class="acc-stat"><div class="acc-stat-label">Pending</div><div class="acc-stat-value" style="color:#f59e0b;">₹{{ number_format($pendingIncome, 2) }}</div></div>
        <div class="acc-stat"><div class="acc-stat-label">Recurring</div><div class="acc-stat-value" style="color:#6366f1;">{{ $recurringCount }}</div></div>
    </div>

    <form method="GET" class="acc-filters">
        <select name="status" class="acc-filter-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>Received</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <select name="category" class="acc-filter-select" onchange="this.form.submit()">
            <option value="">All Categories</option>
            @foreach(\App\Models\AccIncome::categories() as $cat)
            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" class="acc-filter-input" value="{{ request('date_from') }}">
        <input type="date" name="date_to" class="acc-filter-input" value="{{ request('date_to') }}">
        <button type="submit" class="acc-btn-primary" style="background:#6366f1;">Filter</button>
        <a href="{{ route('admin.accounts.income') }}" style="font-size:.8rem;color:var(--text-muted);align-self:center;">Clear</a>
    </form>

    <div class="acc-card">
        <table class="acc-table">
            <thead>
                <tr>
                    <th>Date</th><th>Number</th><th>Title</th><th>Category</th>
                    <th>Customer</th><th>Amount</th><th>Tax</th><th>Status</th><th>Recurring</th><th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($incomes as $inc)
                <tr>
                    <td>{{ $inc->income_date->format('d M Y') }}</td>
                    <td style="font-size:.75rem;color:var(--text-muted);">{{ $inc->income_number ?? '—' }}</td>
                    <td>{{ $inc->title }}</td>
                    <td>{{ $inc->category ?? '—' }}</td>
                    <td>{{ $inc->customer_name ?? '—' }}</td>
                    <td style="color:#22c55e;font-weight:700;">₹{{ number_format($inc->amount, 2) }}</td>
                    <td>{{ $inc->tax_amount > 0 ? '₹'.number_format($inc->tax_amount,2) : '—' }}</td>
                    <td><span class="acc-badge {{ $inc->status }}">{{ ucfirst($inc->status) }}</span></td>
                    <td>@if($inc->is_recurring)<span class="acc-badge recurring"><i class="fas fa-sync-alt"></i> {{ ucfirst($inc->recurring_frequency) }}</span>@else—@endif</td>
                    <td style="display:flex;gap:.4rem;">
                        <button onclick="editIncome({{ $inc->id }},'{{ addslashes($inc->title) }}','{{ $inc->category }}','{{ $inc->amount }}','{{ $inc->tax_amount }}','{{ $inc->income_date->format('Y-m-d') }}','{{ addslashes($inc->customer_name ?? '') }}','{{ addslashes($inc->reference ?? '') }}','{{ $inc->status }}','{{ $inc->bank_account_id }}','{{ (int)$inc->is_recurring }}','{{ $inc->recurring_frequency }}')" style="background:none;border:none;color:#6366f1;cursor:pointer;font-size:.8rem;"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('admin.accounts.income.delete', $inc->id) }}" onsubmit="return confirm('Delete?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:.8rem;"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" style="text-align:center;padding:2rem;color:var(--text-muted);">No income records yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">{{ $incomes->appends(request()->query())->links() }}</div>
</div>

{{-- Add/Edit Modal --}}
<div class="acc-modal-overlay" id="incomeModal">
    <div class="acc-modal">
        <div class="acc-modal-title" id="incomeModalTitle">Add Income</div>
        <form method="POST" id="incomeModalForm" action="{{ route('admin.accounts.income.store') }}">
            @csrf
            <input type="hidden" name="_method" id="incomeMethod" value="POST">
            <input type="hidden" name="income_id" id="incomeId" value="">
            <div class="acc-form-grid">
                <div class="acc-form-group full">
                    <label class="acc-form-label">Title *</label>
                    <input type="text" name="title" id="iTitle" class="acc-form-control" required placeholder="e.g. Website Development Payment">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Category</label>
                    <select name="category" id="iCategory" class="acc-form-control">
                        <option value="">Select Category</option>
                        @foreach(\App\Models\AccIncome::categories() as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Bank Account</label>
                    <select name="bank_account_id" id="iBankAccount" class="acc-form-control">
                        <option value="">Select Account</option>
                        @foreach($bankAccounts as $ba)
                        <option value="{{ $ba->id }}">{{ $ba->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Amount *</label>
                    <input type="number" name="amount" id="iAmount" class="acc-form-control" step="0.01" required>
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Tax Amount</label>
                    <input type="number" name="tax_amount" id="iTax" class="acc-form-control" step="0.01" value="0">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Date *</label>
                    <input type="date" name="income_date" id="iDate" class="acc-form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Customer Name</label>
                    <input type="text" name="customer_name" id="iCustomer" class="acc-form-control" placeholder="Customer / Client">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Reference</label>
                    <input type="text" name="reference" id="iRef" class="acc-form-control" placeholder="Invoice # or ref">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Status</label>
                    <select name="status" id="iStatus" class="acc-form-control">
                        <option value="received">Received</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Recurring?</label>
                    <select name="is_recurring" id="iRecurring" class="acc-form-control" onchange="toggleRecurring(this.value)">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="acc-form-group" id="iFreqGroup" style="display:none;">
                    <label class="acc-form-label">Frequency</label>
                    <select name="recurring_frequency" id="iFreq" class="acc-form-control">
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
                <div class="acc-form-group full">
                    <label class="acc-form-label">Notes</label>
                    <textarea name="notes" id="iNotes" class="acc-form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="acc-modal-footer">
                <button type="button" class="acc-btn-cancel" onclick="closeIncomeModal()">Cancel</button>
                <button type="submit" class="acc-btn-primary">Save Income</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openIncomeModal() {
    document.getElementById('incomeModalTitle').textContent = 'Add Income';
    document.getElementById('incomeMethod').value = 'POST';
    document.getElementById('incomeModalForm').action = '{{ route("admin.accounts.income.store") }}';
    document.getElementById('incomeId').value = '';
    document.getElementById('iTitle').value = '';
    document.getElementById('iCategory').value = '';
    document.getElementById('iAmount').value = '';
    document.getElementById('iTax').value = '0';
    document.getElementById('iDate').value = '{{ date("Y-m-d") }}';
    document.getElementById('iCustomer').value = '';
    document.getElementById('iRef').value = '';
    document.getElementById('iStatus').value = 'received';
    document.getElementById('iRecurring').value = '0';
    document.getElementById('iFreqGroup').style.display = 'none';
    document.getElementById('iNotes').value = '';
    document.getElementById('incomeModal').classList.add('open');
}
function editIncome(id,title,cat,amount,tax,date,customer,ref,status,bankId,recurring,freq) {
    document.getElementById('incomeModalTitle').textContent = 'Edit Income';
    document.getElementById('incomeMethod').value = 'PUT';
    document.getElementById('incomeModalForm').action = '/admin/accounts/income/' + id;
    document.getElementById('incomeId').value = id;
    document.getElementById('iTitle').value = title;
    document.getElementById('iCategory').value = cat;
    document.getElementById('iAmount').value = amount;
    document.getElementById('iTax').value = tax;
    document.getElementById('iDate').value = date;
    document.getElementById('iCustomer').value = customer;
    document.getElementById('iRef').value = ref;
    document.getElementById('iStatus').value = status;
    document.getElementById('iBankAccount').value = bankId;
    document.getElementById('iRecurring').value = recurring;
    document.getElementById('iFreq').value = freq;
    document.getElementById('iFreqGroup').style.display = recurring == '1' ? 'flex' : 'none';
    document.getElementById('incomeModal').classList.add('open');
}
function closeIncomeModal() { document.getElementById('incomeModal').classList.remove('open'); }
function toggleRecurring(val) { document.getElementById('iFreqGroup').style.display = val == '1' ? 'flex' : 'none'; }
document.getElementById('incomeModal').addEventListener('click', function(e) { if(e.target===this) closeIncomeModal(); });
</script>
@endpush
