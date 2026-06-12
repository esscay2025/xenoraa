@extends('layouts.admin')
@section('title', 'Sales Orders')
@section('page-title', 'Sales Orders')
@section('content')
<style>
.crm2-table tbody tr { cursor: pointer; transition: background .12s; }
.crm2-table tbody tr:hover td { background: var(--bg-hover); }
.so-cb-cell { width: 38px; padding: 0 0 0 12px !important; }
.so-checkbox { width: 15px; height: 15px; cursor: pointer; accent-color: var(--accent); }
.so-subject-link { color: var(--accent); text-decoration: none; font-weight: 600; }
.so-subject-link:hover { text-decoration: underline; }
.so-bulk-wrap { position: relative; display: inline-block; }
.so-three-dot-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 34px; height: 34px; border-radius: 7px;
    background: var(--bg-card); border: 1.5px solid var(--border);
    color: var(--text-secondary); font-size: 1.1rem; cursor: pointer;
    transition: all .15s; line-height: 1;
}
.so-three-dot-btn:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--accent); }
.so-bulk-drop {
    display: none; position: absolute; right: 0; top: calc(100% + 6px);
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 8px; min-width: 210px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.35); z-index: 999; overflow: hidden;
}
.so-bulk-drop.open { display: block; }
.so-bulk-drop-item {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 0.65rem 1rem; font-size: 0.82rem;
    color: var(--text-primary); background: transparent;
    border: none; cursor: pointer; width: 100%; text-align: left;
    transition: background .12s;
}
.so-bulk-drop-item:hover { background: var(--bg-hover); }
.so-bulk-drop-item.danger { color: #f87171; }
.so-bulk-drop-item.danger:hover { background: rgba(220,38,38,.12); }
.so-bulk-drop-sep { border: none; border-top: 1px solid var(--border); margin: 0; }
.so-bulk-count {
    display: none; align-items: center; gap: 0.4rem;
    font-size: 0.78rem; color: var(--accent); font-weight: 600;
    padding: 0.2rem 0.6rem; background: rgba(99,102,241,.1);
    border-radius: 20px; white-space: nowrap;
}
.so-bulk-count.visible { display: inline-flex; }
.so-header-right { display: flex; align-items: center; gap: 0.6rem; }
</style>

<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-shopping-cart"></i> Sales Orders</h1>
      <p class="crm2-subtitle">Manage your sales orders.</p>
    </div>
    <div class="so-header-right">
      <span class="so-bulk-count" id="soBulkCount">0 selected</span>
      <div class="so-bulk-wrap">
        <button class="so-three-dot-btn" id="soBulkBtn" title="Bulk actions">&#8942;</button>
        <div class="so-bulk-drop" id="soBulkDrop">
          <button class="so-bulk-drop-item" onclick="soBulkExport()">
            <i class="fas fa-file-export" style="width:16px;color:#6366f1;"></i> Export Selected
          </button>
          <button class="so-bulk-drop-item" onclick="soBulkPrint()">
            <i class="fas fa-print" style="width:16px;color:#10b981;"></i> Print Default View
          </button>
          <hr class="so-bulk-drop-sep">
          <button class="so-bulk-drop-item danger" onclick="soBulkDelete()">
            <i class="fas fa-trash" style="width:16px;"></i> Delete Selected
          </button>
        </div>
      </div>
      <a href="{{ route('admin.crm2.inventory.sales-orders.create') }}" class="crm2-btn crm2-btn-primary">
        <i class="fas fa-plus"></i> New Sales Order
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
    <table class="crm2-table" id="soTable">
      <thead>
        <tr>
          <th class="so-cb-cell"><input type="checkbox" class="so-checkbox" id="soSelectAll" title="Select all"></th>
          <th>SO Number</th>
          <th>Subject</th>
          <th>Account</th>
          <th>Status</th>
          <th>Grand Total</th>
          <th>Delivery Date</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
        <tr onclick="soRowClick(event, '{{ route('admin.crm2.inventory.sales-orders.show', $item->id) }}')"
            data-id="{{ $item->id }}">
          <td class="so-cb-cell" onclick="event.stopPropagation()">
            <input type="checkbox" class="so-checkbox so-row-cb" value="{{ $item->id }}" title="Select">
          </td>
          <td>{{ $item->so_number }}</td>
          <td>
            <a href="{{ route('admin.crm2.inventory.sales-orders.show', $item->id) }}"
               class="so-subject-link" onclick="event.stopPropagation()">{{ $item->subject }}</a>
          </td>
          <td>{{ $item->account?->name ?? '—' }}</td>
          <td>
            @php
              $soColors = ['created'=>'status-new','confirmed'=>'status-active','shipped'=>'status-pending','delivered'=>'status-won','cancelled'=>'status-lost'];
            @endphp
            <span class="crm2-badge {{ $soColors[$item->status] ?? 'status-new' }}">
              {{ \App\Models\CrmSalesOrder::STATUSES[$item->status] ?? ucfirst($item->status ?? 'Draft') }}
            </span>
          </td>
          <td>{{ $item->grand_total ? '₹' . number_format($item->grand_total, 2) : '—' }}</td>
          <td>{{ $item->delivery_date ? $item->delivery_date->format('d M Y') : '—' }}</td>
          <td>{{ $item->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="crm2-empty">
              <i class="fas fa-shopping-cart"></i>
              <p>No sales orders found. <a href="{{ route('admin.crm2.inventory.sales-orders.create') }}">Create the first one</a>.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div></div>
</div>

<form id="soBulkDeleteForm" method="POST"
      action="{{ route('admin.crm2.inventory.sales-orders.bulk-delete') }}"
      style="display:none">
  @csrf @method('DELETE')
  <div id="soBulkDeleteInputs"></div>
</form>

<script>
function soRowClick(e, url) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'A' || e.target.tagName === 'BUTTON') return;
    window.location = url;
}
const soSelectAll = document.getElementById('soSelectAll');
const soBulkCount = document.getElementById('soBulkCount');
function soUpdateCount() {
    const n = document.querySelectorAll('.so-row-cb:checked').length;
    soBulkCount.textContent = n + ' selected';
    soBulkCount.classList.toggle('visible', n > 0);
}
soSelectAll.addEventListener('change', function() {
    document.querySelectorAll('.so-row-cb').forEach(cb => cb.checked = this.checked);
    soUpdateCount();
});
document.querySelectorAll('.so-row-cb').forEach(cb => {
    cb.addEventListener('change', function() {
        const all = document.querySelectorAll('.so-row-cb');
        soSelectAll.checked = [...all].every(c => c.checked);
        soSelectAll.indeterminate = [...all].some(c => c.checked) && ![...all].every(c => c.checked);
        soUpdateCount();
    });
});
document.getElementById('soBulkBtn').addEventListener('click', function(e) {
    e.stopPropagation();
    document.getElementById('soBulkDrop').classList.toggle('open');
});
document.addEventListener('click', function(e) {
    const drop = document.getElementById('soBulkDrop');
    const btn  = document.getElementById('soBulkBtn');
    if (drop && btn && !btn.contains(e.target) && !drop.contains(e.target)) drop.classList.remove('open');
});
function soGetSelected() {
    return [...document.querySelectorAll('.so-row-cb:checked')].map(cb => cb.value);
}
function soBulkExport() {
    const ids = soGetSelected();
    if (!ids.length) { alert('Please select at least one sales order to export.'); return; }
    document.getElementById('soBulkDrop').classList.remove('open');
    const headers = ['SO Number','Subject','Account','Status','Grand Total','Delivery Date','Created'];
    const rows = [];
    document.querySelectorAll('#soTable tbody tr[data-id]').forEach(tr => {
        if (!tr.querySelector('.so-row-cb')?.checked) return;
        const cells = tr.querySelectorAll('td');
        rows.push([cells[1]?.innerText.trim(),cells[2]?.innerText.trim(),cells[3]?.innerText.trim(),cells[4]?.innerText.trim(),cells[5]?.innerText.trim(),cells[6]?.innerText.trim(),cells[7]?.innerText.trim()]);
    });
    let csv = headers.join(',') + '\n';
    rows.forEach(r => { csv += r.map(v => '"' + (v||'').replace(/"/g,'""') + '"').join(',') + '\n'; });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(new Blob([csv], {type:'text/csv'}));
    a.download = 'sales_orders_' + new Date().toISOString().slice(0,10) + '.csv';
    a.click();
}
function soBulkPrint() {
    const ids = soGetSelected();
    if (!ids.length) { alert('Please select at least one sales order to print.'); return; }
    document.getElementById('soBulkDrop').classList.remove('open');
    document.querySelectorAll('#soTable tbody tr[data-id]').forEach(tr => {
        tr.style.display = tr.querySelector('.so-row-cb')?.checked ? '' : 'none';
    });
    window.print();
    document.querySelectorAll('#soTable tbody tr[data-id]').forEach(tr => tr.style.display = '');
}
function soBulkDelete() {
    const ids = soGetSelected();
    if (!ids.length) { alert('Please select at least one sales order to delete.'); return; }
    document.getElementById('soBulkDrop').classList.remove('open');
    if (!confirm('Delete ' + ids.length + ' selected sales order(s)? This cannot be undone.')) return;
    const c = document.getElementById('soBulkDeleteInputs');
    c.innerHTML = '';
    ids.forEach(id => { const i = document.createElement('input'); i.type='hidden'; i.name='ids[]'; i.value=id; c.appendChild(i); });
    document.getElementById('soBulkDeleteForm').submit();
}
</script>
@endsection
