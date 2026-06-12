@extends('layouts.admin')
@section('title', 'Purchase Orders')
@section('page-title', 'Purchase Orders')
@section('content')
<style>
.crm2-table tbody tr { cursor: pointer; transition: background .12s; }
.crm2-table tbody tr:hover td { background: var(--bg-hover); }
.po-cb-cell { width: 38px; padding: 0 0 0 12px !important; }
.po-checkbox { width: 15px; height: 15px; cursor: pointer; accent-color: var(--accent); }
.po-subject-link { color: var(--accent); text-decoration: none; font-weight: 600; }
.po-subject-link:hover { text-decoration: underline; }
.po-bulk-wrap { position: relative; display: inline-block; }
.po-three-dot-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 34px; height: 34px; border-radius: 7px;
    background: var(--bg-card); border: 1.5px solid var(--border);
    color: var(--text-secondary); font-size: 1.1rem; cursor: pointer;
    transition: all .15s; line-height: 1;
}
.po-three-dot-btn:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--accent); }
.po-bulk-drop {
    display: none; position: absolute; right: 0; top: calc(100% + 6px);
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 8px; min-width: 210px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.35); z-index: 999; overflow: hidden;
}
.po-bulk-drop.open { display: block; }
.po-bulk-drop-item {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 0.65rem 1rem; font-size: 0.82rem;
    color: var(--text-primary); background: transparent;
    border: none; cursor: pointer; width: 100%; text-align: left;
    transition: background .12s;
}
.po-bulk-drop-item:hover { background: var(--bg-hover); }
.po-bulk-drop-item.danger { color: #f87171; }
.po-bulk-drop-item.danger:hover { background: rgba(220,38,38,.12); }
.po-bulk-drop-sep { border: none; border-top: 1px solid var(--border); margin: 0; }
.po-bulk-count {
    display: none; align-items: center; gap: 0.4rem;
    font-size: 0.78rem; color: var(--accent); font-weight: 600;
    padding: 0.2rem 0.6rem; background: rgba(99,102,241,.1);
    border-radius: 20px; white-space: nowrap;
}
.po-bulk-count.visible { display: inline-flex; }
.po-header-right { display: flex; align-items: center; gap: 0.6rem; }
</style>

<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-file-import"></i> Purchase Orders</h1>
      <p class="crm2-subtitle">Manage your purchase orders.</p>
    </div>
    <div class="po-header-right">
      <span class="po-bulk-count" id="poBulkCount">0 selected</span>
      <div class="po-bulk-wrap">
        <button class="po-three-dot-btn" id="poBulkBtn" title="Bulk actions">&#8942;</button>
        <div class="po-bulk-drop" id="poBulkDrop">
          <button class="po-bulk-drop-item" onclick="poBulkExport()">
            <i class="fas fa-file-export" style="width:16px;color:#6366f1;"></i> Export Selected
          </button>
          <button class="po-bulk-drop-item" onclick="poBulkPrint()">
            <i class="fas fa-print" style="width:16px;color:#10b981;"></i> Print Default View
          </button>
          <hr class="po-bulk-drop-sep">
          <button class="po-bulk-drop-item danger" onclick="poBulkDelete()">
            <i class="fas fa-trash" style="width:16px;"></i> Delete Selected
          </button>
        </div>
      </div>
      <a href="{{ route('admin.crm2.inventory.purchase-orders.create') }}" class="crm2-btn crm2-btn-primary">
        <i class="fas fa-plus"></i> New Purchase Order
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
    <table class="crm2-table" id="poTable">
      <thead>
        <tr>
          <th class="po-cb-cell"><input type="checkbox" class="po-checkbox" id="poSelectAll" title="Select all"></th>
          <th>PO Number</th>
          <th>Subject</th>
          <th>Vendor</th>
          <th>Status</th>
          <th>Grand Total</th>
          <th>Expected Delivery</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
        <tr onclick="poRowClick(event, '{{ route('admin.crm2.inventory.purchase-orders.show', $item->id) }}')"
            data-id="{{ $item->id }}">
          <td class="po-cb-cell" onclick="event.stopPropagation()">
            <input type="checkbox" class="po-checkbox po-row-cb" value="{{ $item->id }}" title="Select">
          </td>
          <td>{{ $item->po_number }}</td>
          <td>
            <a href="{{ route('admin.crm2.inventory.purchase-orders.show', $item->id) }}"
               class="po-subject-link" onclick="event.stopPropagation()">{{ $item->subject }}</a>
          </td>
          <td>{{ $item->vendor?->name ?? '—' }}</td>
          <td>
            @php
              $poColors = ['draft'=>'status-draft','sent'=>'status-pending','confirmed'=>'status-active','received'=>'status-won','cancelled'=>'status-lost'];
            @endphp
            <span class="crm2-badge {{ $poColors[$item->status] ?? 'status-new' }}">
              {{ ucfirst($item->status ?? 'Draft') }}
            </span>
          </td>
          <td>{{ $item->grand_total ? '₹' . number_format($item->grand_total, 2) : '—' }}</td>
          <td>{{ $item->expected_delivery ? $item->expected_delivery->format('d M Y') : '—' }}</td>
          <td>{{ $item->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="crm2-empty">
              <i class="fas fa-file-import"></i>
              <p>No purchase orders found. <a href="{{ route('admin.crm2.inventory.purchase-orders.create') }}">Create the first one</a>.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div></div>
</div>

<form id="poBulkDeleteForm" method="POST"
      action="{{ route('admin.crm2.inventory.purchase-orders.bulk-delete') }}"
      style="display:none">
  @csrf @method('DELETE')
  <div id="poBulkDeleteInputs"></div>
</form>

<script>
function poRowClick(e, url) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'A' || e.target.tagName === 'BUTTON') return;
    window.location = url;
}
const poSelectAll = document.getElementById('poSelectAll');
const poBulkCount = document.getElementById('poBulkCount');
function poUpdateCount() {
    const n = document.querySelectorAll('.po-row-cb:checked').length;
    poBulkCount.textContent = n + ' selected';
    poBulkCount.classList.toggle('visible', n > 0);
}
poSelectAll.addEventListener('change', function() {
    document.querySelectorAll('.po-row-cb').forEach(cb => cb.checked = this.checked);
    poUpdateCount();
});
document.querySelectorAll('.po-row-cb').forEach(cb => {
    cb.addEventListener('change', function() {
        const all = document.querySelectorAll('.po-row-cb');
        poSelectAll.checked = [...all].every(c => c.checked);
        poSelectAll.indeterminate = [...all].some(c => c.checked) && ![...all].every(c => c.checked);
        poUpdateCount();
    });
});
document.getElementById('poBulkBtn').addEventListener('click', function(e) {
    e.stopPropagation();
    document.getElementById('poBulkDrop').classList.toggle('open');
});
document.addEventListener('click', function(e) {
    const drop = document.getElementById('poBulkDrop');
    const btn  = document.getElementById('poBulkBtn');
    if (drop && btn && !btn.contains(e.target) && !drop.contains(e.target)) drop.classList.remove('open');
});
function poGetSelected() {
    return [...document.querySelectorAll('.po-row-cb:checked')].map(cb => cb.value);
}
function poBulkExport() {
    const ids = poGetSelected();
    if (!ids.length) { alert('Please select at least one purchase order to export.'); return; }
    document.getElementById('poBulkDrop').classList.remove('open');
    const headers = ['PO Number','Subject','Vendor','Status','Grand Total','Expected Delivery','Created'];
    const rows = [];
    document.querySelectorAll('#poTable tbody tr[data-id]').forEach(tr => {
        if (!tr.querySelector('.po-row-cb')?.checked) return;
        const cells = tr.querySelectorAll('td');
        rows.push([cells[1]?.innerText.trim(),cells[2]?.innerText.trim(),cells[3]?.innerText.trim(),cells[4]?.innerText.trim(),cells[5]?.innerText.trim(),cells[6]?.innerText.trim(),cells[7]?.innerText.trim()]);
    });
    let csv = headers.join(',') + '\n';
    rows.forEach(r => { csv += r.map(v => '"' + (v||'').replace(/"/g,'""') + '"').join(',') + '\n'; });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(new Blob([csv], {type:'text/csv'}));
    a.download = 'purchase_orders_' + new Date().toISOString().slice(0,10) + '.csv';
    a.click();
}
function poBulkPrint() {
    const ids = poGetSelected();
    if (!ids.length) { alert('Please select at least one purchase order to print.'); return; }
    document.getElementById('poBulkDrop').classList.remove('open');
    document.querySelectorAll('#poTable tbody tr[data-id]').forEach(tr => {
        tr.style.display = tr.querySelector('.po-row-cb')?.checked ? '' : 'none';
    });
    window.print();
    document.querySelectorAll('#poTable tbody tr[data-id]').forEach(tr => tr.style.display = '');
}
function poBulkDelete() {
    const ids = poGetSelected();
    if (!ids.length) { alert('Please select at least one purchase order to delete.'); return; }
    document.getElementById('poBulkDrop').classList.remove('open');
    if (!confirm('Delete ' + ids.length + ' selected purchase order(s)? This cannot be undone.')) return;
    const c = document.getElementById('poBulkDeleteInputs');
    c.innerHTML = '';
    ids.forEach(id => { const i = document.createElement('input'); i.type='hidden'; i.name='ids[]'; i.value=id; c.appendChild(i); });
    document.getElementById('poBulkDeleteForm').submit();
}
</script>
@endsection
