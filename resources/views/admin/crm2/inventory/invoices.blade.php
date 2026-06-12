@extends('layouts.admin')
@section('title', 'Invoices')
@section('page-title', 'Invoices')
@section('content')
<style>
.crm2-table tbody tr { cursor: pointer; transition: background .12s; }
.crm2-table tbody tr:hover td { background: var(--bg-hover); }
.inv-cb-cell { width: 38px; padding: 0 0 0 12px !important; }
.inv-checkbox { width: 15px; height: 15px; cursor: pointer; accent-color: var(--accent); }
.inv-subject-link { color: var(--accent); text-decoration: none; font-weight: 600; }
.inv-subject-link:hover { text-decoration: underline; }
.inv-bulk-wrap { position: relative; display: inline-block; }
.inv-three-dot-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 34px; height: 34px; border-radius: 7px;
    background: var(--bg-card); border: 1.5px solid var(--border);
    color: var(--text-secondary); font-size: 1.1rem; cursor: pointer;
    transition: all .15s; line-height: 1;
}
.inv-three-dot-btn:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--accent); }
.inv-bulk-drop {
    display: none; position: absolute; right: 0; top: calc(100% + 6px);
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 8px; min-width: 210px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.35); z-index: 999; overflow: hidden;
}
.inv-bulk-drop.open { display: block; }
.inv-bulk-drop-item {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 0.65rem 1rem; font-size: 0.82rem;
    color: var(--text-primary); background: transparent;
    border: none; cursor: pointer; width: 100%; text-align: left;
    transition: background .12s;
}
.inv-bulk-drop-item:hover { background: var(--bg-hover); }
.inv-bulk-drop-item.danger { color: #f87171; }
.inv-bulk-drop-item.danger:hover { background: rgba(220,38,38,.12); }
.inv-bulk-drop-sep { border: none; border-top: 1px solid var(--border); margin: 0; }
.inv-bulk-count {
    display: none; align-items: center; gap: 0.4rem;
    font-size: 0.78rem; color: var(--accent); font-weight: 600;
    padding: 0.2rem 0.6rem; background: rgba(99,102,241,.1);
    border-radius: 20px; white-space: nowrap;
}
.inv-bulk-count.visible { display: inline-flex; }
.inv-header-right { display: flex; align-items: center; gap: 0.6rem; }
</style>

<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-file-invoice-dollar"></i> Invoices</h1>
      <p class="crm2-subtitle">Manage your invoices.</p>
    </div>
    <div class="inv-header-right">
      <span class="inv-bulk-count" id="invBulkCount">0 selected</span>
      <div class="inv-bulk-wrap">
        <button class="inv-three-dot-btn" id="invBulkBtn" title="Bulk actions">&#8942;</button>
        <div class="inv-bulk-drop" id="invBulkDrop">
          <button class="inv-bulk-drop-item" onclick="invBulkExport()">
            <i class="fas fa-file-export" style="width:16px;color:#6366f1;"></i> Export Selected
          </button>
          <button class="inv-bulk-drop-item" onclick="invBulkPrint()">
            <i class="fas fa-print" style="width:16px;color:#10b981;"></i> Print Default View
          </button>
          <hr class="inv-bulk-drop-sep">
          <button class="inv-bulk-drop-item danger" onclick="invBulkDelete()">
            <i class="fas fa-trash" style="width:16px;"></i> Delete Selected
          </button>
        </div>
      </div>
      <a href="{{ route('admin.crm2.inventory.invoices.create') }}" class="crm2-btn crm2-btn-primary">
        <i class="fas fa-plus"></i> New Invoice
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="crm2-alert error"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
  @endif

  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table" id="invTable">
      <thead>
        <tr>
          <th class="inv-cb-cell"><input type="checkbox" class="inv-checkbox" id="invSelectAll" title="Select all"></th>
          <th>Invoice #</th>
          <th>Subject</th>
          <th>Account</th>
          <th>Status</th>
          <th>Grand Total</th>
          <th>Amount Paid</th>
          <th>Due Date</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
        <tr onclick="invRowClick(event, '{{ route('admin.crm2.inventory.invoices.show', $item->id) }}')"
            data-id="{{ $item->id }}">
          <td class="inv-cb-cell" onclick="event.stopPropagation()">
            <input type="checkbox" class="inv-checkbox inv-row-cb" value="{{ $item->id }}" title="Select">
          </td>
          <td>{{ $item->invoice_number ?? 'INV-' . $item->id }}</td>
          <td>
            <a href="{{ route('admin.crm2.inventory.invoices.show', $item->id) }}"
               class="inv-subject-link" onclick="event.stopPropagation()">{{ $item->subject }}</a>
          </td>
          <td>{{ $item->account?->name ?? '—' }}</td>
          <td>
            @php
              $invColors = ['draft'=>'status-draft','sent'=>'status-pending','paid'=>'status-won','overdue'=>'status-lost','cancelled'=>'status-lost','partial'=>'status-active'];
            @endphp
            <span class="crm2-badge {{ $invColors[$item->status] ?? 'status-new' }}">
              {{ ucfirst($item->status ?? 'Draft') }}
            </span>
          </td>
          <td>{{ $item->grand_total ? '₹' . number_format($item->grand_total, 2) : '—' }}</td>
          <td>{{ $item->amount_paid ? '₹' . number_format($item->amount_paid, 2) : '—' }}</td>
          <td>{{ $item->due_date ? $item->due_date->format('d M Y') : '—' }}</td>
          <td>{{ $item->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="9">
            <div class="crm2-empty">
              <i class="fas fa-file-invoice-dollar"></i>
              <p>No invoices found. <a href="{{ route('admin.crm2.inventory.invoices.create') }}">Create the first one</a>.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div></div>
</div>

<form id="invBulkDeleteForm" method="POST"
      action="{{ route('admin.crm2.inventory.invoices.bulk-delete') }}"
      style="display:none">
  @csrf @method('DELETE')
  <div id="invBulkDeleteInputs"></div>
</form>

<script>
function invRowClick(e, url) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'A' || e.target.tagName === 'BUTTON') return;
    window.location = url;
}
const invSelectAll = document.getElementById('invSelectAll');
const invBulkCount = document.getElementById('invBulkCount');
function invUpdateCount() {
    const n = document.querySelectorAll('.inv-row-cb:checked').length;
    invBulkCount.textContent = n + ' selected';
    invBulkCount.classList.toggle('visible', n > 0);
}
invSelectAll.addEventListener('change', function() {
    document.querySelectorAll('.inv-row-cb').forEach(cb => cb.checked = this.checked);
    invUpdateCount();
});
document.querySelectorAll('.inv-row-cb').forEach(cb => {
    cb.addEventListener('change', function() {
        const all = document.querySelectorAll('.inv-row-cb');
        invSelectAll.checked = [...all].every(c => c.checked);
        invSelectAll.indeterminate = [...all].some(c => c.checked) && ![...all].every(c => c.checked);
        invUpdateCount();
    });
});
document.getElementById('invBulkBtn').addEventListener('click', function(e) {
    e.stopPropagation();
    document.getElementById('invBulkDrop').classList.toggle('open');
});
document.addEventListener('click', function(e) {
    const drop = document.getElementById('invBulkDrop');
    const btn  = document.getElementById('invBulkBtn');
    if (drop && btn && !btn.contains(e.target) && !drop.contains(e.target)) drop.classList.remove('open');
});
function invGetSelected() {
    return [...document.querySelectorAll('.inv-row-cb:checked')].map(cb => cb.value);
}
function invBulkExport() {
    const ids = invGetSelected();
    if (!ids.length) { alert('Please select at least one invoice to export.'); return; }
    document.getElementById('invBulkDrop').classList.remove('open');
    const headers = ['Invoice #','Subject','Account','Status','Grand Total','Amount Paid','Due Date','Created'];
    const rows = [];
    document.querySelectorAll('#invTable tbody tr[data-id]').forEach(tr => {
        if (!tr.querySelector('.inv-row-cb')?.checked) return;
        const cells = tr.querySelectorAll('td');
        rows.push([cells[1]?.innerText.trim(),cells[2]?.innerText.trim(),cells[3]?.innerText.trim(),cells[4]?.innerText.trim(),cells[5]?.innerText.trim(),cells[6]?.innerText.trim(),cells[7]?.innerText.trim(),cells[8]?.innerText.trim()]);
    });
    let csv = headers.join(',') + '\n';
    rows.forEach(r => { csv += r.map(v => '"' + (v||'').replace(/"/g,'""') + '"').join(',') + '\n'; });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(new Blob([csv], {type:'text/csv'}));
    a.download = 'invoices_' + new Date().toISOString().slice(0,10) + '.csv';
    a.click();
}
function invBulkPrint() {
    const ids = invGetSelected();
    if (!ids.length) { alert('Please select at least one invoice to print.'); return; }
    document.getElementById('invBulkDrop').classList.remove('open');
    document.querySelectorAll('#invTable tbody tr[data-id]').forEach(tr => {
        tr.style.display = tr.querySelector('.inv-row-cb')?.checked ? '' : 'none';
    });
    window.print();
    document.querySelectorAll('#invTable tbody tr[data-id]').forEach(tr => tr.style.display = '');
}
function invBulkDelete() {
    const ids = invGetSelected();
    if (!ids.length) { alert('Please select at least one invoice to delete.'); return; }
    document.getElementById('invBulkDrop').classList.remove('open');
    if (!confirm('Delete ' + ids.length + ' selected invoice(s)? This cannot be undone.')) return;
    const c = document.getElementById('invBulkDeleteInputs');
    c.innerHTML = '';
    ids.forEach(id => { const i = document.createElement('input'); i.type='hidden'; i.name='ids[]'; i.value=id; c.appendChild(i); });
    document.getElementById('invBulkDeleteForm').submit();
}
</script>
@endsection
