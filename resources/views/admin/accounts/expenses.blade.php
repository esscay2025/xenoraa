@extends('layouts.admin')
@section('title', 'Expenses')
@push('styles')
<style>
.acc-page{padding:1.5rem 2rem;}
.acc-page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;flex-wrap:wrap;gap:.75rem;}
.acc-page-title{font-size:1.5rem;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:.6rem;}
.acc-page-title i{color:#ef4444;}
.acc-stat-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:.8rem;margin-bottom:1.2rem;}
.acc-stat{background:var(--card-bg);border:1px solid var(--border-color);border-radius:10px;padding:.9rem 1.1rem;}
.acc-stat-label{font-size:.72rem;color:var(--text-muted);text-transform:uppercase;}
.acc-stat-value{font-size:1.3rem;font-weight:700;color:var(--text-primary);}
.acc-card{background:var(--card-bg);border:1px solid var(--border-color);border-radius:12px;overflow:hidden;}
.acc-table{width:100%;border-collapse:collapse;font-size:.83rem;}
.acc-table th{padding:.65rem 1rem;text-align:left;color:var(--text-muted);font-weight:600;font-size:.72rem;text-transform:uppercase;border-bottom:1px solid var(--border-color);background:var(--bg-secondary,rgba(255,255,255,.02));}
.acc-table td{padding:.7rem 1rem;border-bottom:1px solid var(--border-color);color:var(--text-primary);}
.acc-table tr:last-child td{border-bottom:none;}
.acc-table tr:hover td{background:rgba(239,68,68,.04);}
.acc-badge{display:inline-flex;align-items:center;padding:.2rem .6rem;border-radius:20px;font-size:.7rem;font-weight:600;}
.acc-badge.paid{background:rgba(34,197,94,.15);color:#22c55e;}
.acc-badge.pending{background:rgba(245,158,11,.15);color:#f59e0b;}
.acc-badge.cancelled{background:rgba(107,114,128,.15);color:#6b7280;}
.acc-badge.billable{background:rgba(99,102,241,.15);color:#6366f1;}
.acc-filters{display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1rem;align-items:center;}
.acc-filter-select,.acc-filter-input{padding:.4rem .75rem;border-radius:7px;border:1px solid var(--border-color);background:var(--card-bg);color:var(--text-primary);font-size:.82rem;}
.acc-btn-primary{padding:.42rem 1rem;border-radius:7px;background:#ef4444;color:#fff;border:none;font-size:.82rem;font-weight:600;cursor:pointer;}
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
.acc-form-control:focus{outline:none;border-color:#ef4444;}
.acc-modal-footer{display:flex;justify-content:flex-end;gap:.6rem;margin-top:1.2rem;}
.acc-btn-cancel{padding:.5rem 1.2rem;border-radius:7px;background:transparent;color:var(--text-muted);border:1px solid var(--border-color);font-size:.85rem;cursor:pointer;}
</style>
@endpush

@section('content')
<div class="acc-page">
    <div class="acc-page-header">
        <div class="acc-page-title"><i class="fas fa-arrow-circle-up"></i> Expenses</div>
        <button class="xn-btn" onclick="openExpenseModal()" style="background:#ef4444;color:#fff;border:none;"><i class="fas fa-plus"></i> Add Expense</button>
    </div>

    <div class="acc-stat-row">
        <div class="acc-stat"><div class="acc-stat-label">Total Expenses</div><div class="acc-stat-value" style="color:#ef4444;">₹{{ number_format($totalExpenses, 2) }}</div></div>
        <div class="acc-stat"><div class="acc-stat-label">This Month</div><div class="acc-stat-value" style="color:#ef4444;">₹{{ number_format($thisMonthExpenses, 2) }}</div></div>
        <div class="acc-stat"><div class="acc-stat-label">Pending</div><div class="acc-stat-value" style="color:#f59e0b;">₹{{ number_format($pendingExpenses, 2) }}</div></div>
        <div class="acc-stat"><div class="acc-stat-label">Billable</div><div class="acc-stat-value" style="color:#6366f1;">₹{{ number_format($billableExpenses, 2) }}</div></div>
    </div>

    <form method="GET" class="acc-filters">
        <select name="status" class="acc-filter-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <select name="category" class="acc-filter-select" onchange="this.form.submit()">
            <option value="">All Categories</option>
            @foreach(\App\Models\AccExpense::categories() as $cat)
            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" class="acc-filter-input" value="{{ request('date_from') }}">
        <input type="date" name="date_to" class="acc-filter-input" value="{{ request('date_to') }}">
        <button type="submit" class="acc-btn-primary" style="background:#6366f1;">Filter</button>
        <a href="{{ route('admin.accounts.expenses') }}" style="font-size:.8rem;color:var(--text-muted);align-self:center;">Clear</a>
    </form>

    <div class="acc-card">
        <table class="acc-table">
            <thead>
                <tr>
                    <th>Date</th><th>Number</th><th>Title</th><th>Category</th>
                    <th>Vendor</th><th>Amount</th><th>Tax</th><th>Status</th><th>Billable</th><th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $exp)
                <tr>
                    <td>{{ $exp->expense_date->format('d M Y') }}</td>
                    <td style="font-size:.75rem;color:var(--text-muted);">{{ $exp->expense_number ?? '—' }}</td>
                    <td>{{ $exp->title }}</td>
                    <td>{{ $exp->category ?? '—' }}</td>
                    <td>{{ $exp->vendor_name ?? '—' }}</td>
                    <td style="color:#ef4444;font-weight:700;">₹{{ number_format($exp->amount, 2) }}</td>
                    <td>{{ $exp->tax_amount > 0 ? '₹'.number_format($exp->tax_amount,2) : '—' }}</td>
                    <td><span class="acc-badge {{ $exp->status }}">{{ ucfirst($exp->status) }}</span></td>
                    <td>@if($exp->is_billable)<span class="acc-badge billable">Billable</span>@else—@endif</td>
                    <td style="display:flex;gap:.4rem;">
                        <button onclick="editExpense({{ $exp->id }},'{{ addslashes($exp->title) }}','{{ $exp->category }}','{{ $exp->amount }}','{{ $exp->tax_amount }}','{{ $exp->expense_date->format('Y-m-d') }}','{{ addslashes($exp->vendor_name ?? '') }}','{{ addslashes($exp->reference ?? '') }}','{{ $exp->status }}','{{ $exp->bank_account_id }}','{{ (int)$exp->is_billable }}')" style="background:none;border:none;color:#6366f1;cursor:pointer;font-size:.8rem;"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('admin.accounts.expenses.delete', $exp->id) }}" onsubmit="return confirm('Delete?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:.8rem;"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" style="text-align:center;padding:2rem;color:var(--text-muted);">No expense records yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">{{ $expenses->appends(request()->query())->links() }}</div>
</div>

{{-- Modal --}}
<div class="acc-modal-overlay" id="expenseModal">
    <div class="acc-modal">
        <div class="acc-modal-title" id="expenseModalTitle">Add Expense</div>
        <form method="POST" id="expenseModalForm" action="{{ route('admin.accounts.expenses.store') }}">
            @csrf
            <input type="hidden" name="_method" id="expenseMethod" value="POST">
            <input type="hidden" name="expense_id" id="expenseId" value="">
            <div class="acc-form-grid">
                <div class="acc-form-group full">
                    <label class="acc-form-label">Title *</label>
                    <input type="text" name="title" id="eTitle" class="acc-form-control" required placeholder="e.g. Office Rent - June">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Category</label>
                    <select name="category" id="eCategory" class="acc-form-control">
                        <option value="">Select Category</option>
                        @foreach(\App\Models\AccExpense::categories() as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Bank Account</label>
                    <select name="bank_account_id" id="eBankAccount" class="acc-form-control">
                        <option value="">Select Account</option>
                        @foreach($bankAccounts as $ba)
                        <option value="{{ $ba->id }}">{{ $ba->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Amount *</label>
                    <input type="number" name="amount" id="eAmount" class="acc-form-control" step="0.01" required>
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Tax Amount</label>
                    <input type="number" name="tax_amount" id="eTax" class="acc-form-control" step="0.01" value="0">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Date *</label>
                    <input type="date" name="expense_date" id="eDate" class="acc-form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Vendor Name</label>
                    <input type="text" name="vendor_name" id="eVendor" class="acc-form-control" placeholder="Vendor / Supplier">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Reference</label>
                    <input type="text" name="reference" id="eRef" class="acc-form-control" placeholder="PO # or ref">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Status</label>
                    <select name="status" id="eStatus" class="acc-form-control">
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Billable?</label>
                    <select name="is_billable" id="eBillable" class="acc-form-control">
                        <option value="0">No</option>
                        <option value="1">Yes — Billable to Client</option>
                    </select>
                </div>
                <div class="acc-form-group full">
                    <label class="acc-form-label">Notes</label>
                    <textarea name="notes" id="eNotes" class="acc-form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="acc-modal-footer">
                <button type="button" class="acc-btn-cancel" onclick="closeExpenseModal()">Cancel</button>
                <button type="submit" class="acc-btn-primary">Save Expense</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openExpenseModal() {
    document.getElementById('expenseModalTitle').textContent = 'Add Expense';
    document.getElementById('expenseMethod').value = 'POST';
    document.getElementById('expenseModalForm').action = '{{ route("admin.accounts.expenses.store") }}';
    document.getElementById('expenseId').value = '';
    ['eTitle','eAmount','eVendor','eRef','eNotes'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('eCategory').value = '';
    document.getElementById('eTax').value = '0';
    document.getElementById('eDate').value = '{{ date("Y-m-d") }}';
    document.getElementById('eStatus').value = 'paid';
    document.getElementById('eBillable').value = '0';
    document.getElementById('expenseModal').classList.add('open');
}
function editExpense(id,title,cat,amount,tax,date,vendor,ref,status,bankId,billable) {
    document.getElementById('expenseModalTitle').textContent = 'Edit Expense';
    document.getElementById('expenseMethod').value = 'PUT';
    document.getElementById('expenseModalForm').action = '/admin/accounts/expenses/' + id;
    document.getElementById('expenseId').value = id;
    document.getElementById('eTitle').value = title;
    document.getElementById('eCategory').value = cat;
    document.getElementById('eAmount').value = amount;
    document.getElementById('eTax').value = tax;
    document.getElementById('eDate').value = date;
    document.getElementById('eVendor').value = vendor;
    document.getElementById('eRef').value = ref;
    document.getElementById('eStatus').value = status;
    document.getElementById('eBankAccount').value = bankId;
    document.getElementById('eBillable').value = billable;
    document.getElementById('expenseModal').classList.add('open');
}
function closeExpenseModal() { document.getElementById('expenseModal').classList.remove('open'); }
document.getElementById('expenseModal').addEventListener('click', function(e) { if(e.target===this) closeExpenseModal(); });
</script>
@endpush
