@extends('layouts.admin')
@section('title', 'Expenses')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-arrow-circle-up" style="color:#ef4444;"></i> Expenses</h1>
      <p class="crm2-subtitle">Track all your business expenses and outgoings.</p>
    </div>
    <div style="display:flex;align-items:center;gap:.6rem;">
      <button class="crm2-btn crm2-btn-primary" onclick="document.getElementById('addExpenseModal').style.display='flex'"><i class="fas fa-plus"></i> Add Expense</button>
      <div class="xn-bulk-wrap">
        <button class="xn-bulk-btn" onclick="toggleBulkMenu(event)" title="More actions">&#8942;</button>
        <div class="xn-bulk-drop" id="bulkDrop">
          <button class="xn-bulk-item" onclick="exportCSV()"><i class="fas fa-file-csv" style="color:#10b981;"></i> Export CSV</button>
          <div style="border-top:1px solid var(--border,#e2e8f0);margin:4px 0;"></div>
          <button class="xn-bulk-item danger" onclick="bulkDelete()"><i class="fas fa-trash"></i> Delete Selected</button>
        </div>
      </div>
    </div>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  @if(session('error'))<div class="crm2-alert danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif

  {{-- Filter Bar --}}
  <div class="crm2-card mb-4">
    <div class="crm2-card-body">
      <form method="GET" class="crm2-filter-form">
        <div class="filter-group flex-1">
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Search expenses..." class="crm2-input">
        </div>
        <div class="filter-group">
          <select name="status" class="crm2-select">
            <option value="">All Status</option>
            <option value="paid" {{ request('status')==='paid'?'selected':'' }}>Paid</option>
            <option value="pending" {{ request('status')==='pending'?'selected':'' }}>Pending</option>
            <option value="overdue" {{ request('status')==='overdue'?'selected':'' }}>Overdue</option>
          </select>
        </div>
        <div class="filter-group">
          <select name="category" class="crm2-select">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ request('category')===$cat?'selected':'' }}>{{ $cat }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('admin.accounts.expenses') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
      </form>
    </div>
  </div>

  {{-- Expenses Table --}}
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table" id="expTable">
        <thead>
          <tr>
            <th class="cb-col"><input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)"></th>
            <th>Reference</th>
            <th>Date</th>
            <th>Title</th>
            <th>Category</th>
            <th>Vendor</th>
            <th>Tax</th>
            <th>Status</th>
            <th style="text-align:right;">Amount</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($expenses as $exp)
          <tr>
            <td class="cb-col" onclick="event.stopPropagation()">
              <input type="checkbox" class="exp-cb" value="{{ $exp->id }}" onchange="updateSelection()">
            </td>
            <td style="font-family:monospace;font-size:.78rem;color:var(--text-muted);">{{ $exp->reference_number }}</td>
            <td>{{ $exp->expense_date->format('d M Y') }}</td>
            <td style="font-weight:600;color:var(--accent,#6366f1);">{{ $exp->title }}</td>
            <td>{{ $exp->category ?? '—' }}</td>
            <td>{{ $exp->vendor_name ?? '—' }}</td>
            <td>{{ $exp->tax_amount ? '₹'.number_format($exp->tax_amount,0) : '—' }}</td>
            <td>
              <span class="crm2-badge status-{{ $exp->status === 'paid' ? 'won' : ($exp->status === 'overdue' ? 'lost' : 'new') }}">
                {{ ucfirst($exp->status) }}
              </span>
              @if($exp->is_billable)<span class="crm2-badge status-qualified" style="margin-left:.3rem;">Billable</span>@endif
            </td>
            <td style="text-align:right;font-weight:700;color:#ef4444;">₹{{ number_format($exp->amount, 2) }}</td>
            <td onclick="event.stopPropagation()">
              <div style="display:flex;gap:.3rem;justify-content:flex-end;">
                <button class="crm2-btn crm2-btn-ghost" style="padding:.25rem .5rem;font-size:.75rem;" onclick="editExpense({{ $exp->id }})"><i class="fas fa-edit"></i></button>
                <form method="POST" action="{{ route('admin.accounts.expenses.delete', $exp->id) }}" onsubmit="return confirm('Delete this expense?')" style="display:inline">
                  @csrf @method('DELETE')
                  <button type="submit" class="crm2-btn" style="padding:.25rem .5rem;font-size:.75rem;background:#ef4444;color:#fff;border:none;"><i class="fas fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="10"><div class="crm2-empty"><i class="fas fa-arrow-circle-up"></i><p>No expense entries found.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($expenses->hasPages())<div class="crm2-pagination">{{ $expenses->links() }}</div>@endif
  </div>
</div>

{{-- Add Expense Modal --}}
<div id="addExpenseModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;overflow-y:auto;">
  <div class="crm2-card" style="width:100%;max-width:520px;margin:1rem;">
    <div class="crm2-card-body">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
        <h3 class="crm2-title" style="font-size:1.1rem;margin:0;"><i class="fas fa-plus"></i> Add Expense</h3>
        <button onclick="document.getElementById('addExpenseModal').style.display='none'" style="background:none;border:none;color:var(--text-muted);font-size:1.2rem;cursor:pointer;"><i class="fas fa-times"></i></button>
      </div>
      <form method="POST" action="{{ route('admin.accounts.expenses.store') }}">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;">
          <div class="crm2-form-group" style="grid-column:1/-1;">
            <label class="crm2-label">Title *</label>
            <input type="text" name="title" class="crm2-input" required placeholder="e.g. AWS Server Costs">
          </div>
          <div class="crm2-form-group">
            <label class="crm2-label">Amount (₹) *</label>
            <input type="number" name="amount" class="crm2-input" required step="0.01" min="0">
          </div>
          <div class="crm2-form-group">
            <label class="crm2-label">Date *</label>
            <input type="date" name="expense_date" class="crm2-input" required value="{{ date('Y-m-d') }}">
          </div>
          <div class="crm2-form-group">
            <label class="crm2-label">Category</label>
            <input type="text" name="category" class="crm2-input" list="expCats" placeholder="e.g. Software">
            <datalist id="expCats">
              @foreach($categories as $cat)<option value="{{ $cat }}">@endforeach
            </datalist>
          </div>
          <div class="crm2-form-group">
            <label class="crm2-label">Status</label>
            <select name="status" class="crm2-select">
              <option value="paid">Paid</option>
              <option value="pending">Pending</option>
              <option value="overdue">Overdue</option>
            </select>
          </div>
          <div class="crm2-form-group">
            <label class="crm2-label">Vendor Name</label>
            <input type="text" name="vendor_name" class="crm2-input" placeholder="e.g. Amazon Web Services">
          </div>
          <div class="crm2-form-group">
            <label class="crm2-label">Bank Account</label>
            <select name="bank_account_id" class="crm2-select">
              <option value="">— Select Account —</option>
              @foreach($bankAccounts as $ba)<option value="{{ $ba->id }}">{{ $ba->name }}</option>@endforeach
            </select>
          </div>
          <div class="crm2-form-group">
            <label class="crm2-label">Tax Amount (₹)</label>
            <input type="number" name="tax_amount" class="crm2-input" step="0.01" min="0" value="0">
          </div>
          <div class="crm2-form-group">
            <label class="crm2-label">Payment Method</label>
            <select name="payment_method" class="crm2-select">
              <option value="">— Select —</option>
              <option value="bank_transfer">Bank Transfer</option>
              <option value="cash">Cash</option>
              <option value="cheque">Cheque</option>
              <option value="upi">UPI</option>
              <option value="card">Card</option>
            </select>
          </div>
          <div class="crm2-form-group" style="grid-column:1/-1;">
            <label class="crm2-label">Notes</label>
            <textarea name="notes" class="crm2-input" rows="2" placeholder="Optional notes"></textarea>
          </div>
          <div class="crm2-form-group" style="display:flex;align-items:center;gap:.5rem;">
            <input type="checkbox" name="is_billable" id="isBillable" value="1">
            <label for="isBillable" class="crm2-label" style="margin:0;">Billable to Client</label>
          </div>
        </div>
        <div style="display:flex;gap:.6rem;justify-content:flex-end;margin-top:1rem;">
          <button type="button" onclick="document.getElementById('addExpenseModal').style.display='none'" class="crm2-btn crm2-btn-ghost">Cancel</button>
          <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Expense</button>
        </div>
      </form>
    </div>
  </div>
</div>

<form id="bulkDeleteForm" method="POST" action="{{ '#' }}" style="display:none">
  @csrf @method('DELETE')
  <input type="hidden" name="ids" id="bulkDeleteIds">
</form>
@endsection
@push('scripts')
<script>
function toggleSelectAll(cb) { document.querySelectorAll('.exp-cb').forEach(c => c.checked = cb.checked); }
function updateSelection() {}
function toggleBulkMenu(e) {
  e.stopPropagation();
  const d = document.getElementById('bulkDrop');
  d.classList.toggle('open');
  document.addEventListener('click', () => d.classList.remove('open'), {once:true});
}
function bulkDelete() {
  const ids = [...document.querySelectorAll('.exp-cb:checked')].map(c => c.value);
  if (!ids.length) { alert('Select at least one entry.'); return; }
  if (!confirm('Delete ' + ids.length + ' selected entries?')) return;
  document.getElementById('bulkDeleteIds').value = ids.join(',');
  document.getElementById('bulkDeleteForm').submit();
}
function exportCSV() { window.location = '{{ route("admin.accounts.expenses") }}?export=csv'; }
function editExpense(id) { window.location = '/admin/accounts/expenses/' + id + '/edit'; }
</script>
@endpush
