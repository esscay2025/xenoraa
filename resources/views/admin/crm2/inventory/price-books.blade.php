@extends('layouts.admin')
@section('title', 'Price Books')
@section('page-title', 'Price Books')
@section('content')
<style>
.crm2-table tbody tr { cursor: pointer; transition: background .12s; }
.crm2-table tbody tr:hover td { background: var(--bg-hover); }
.pb-cb-cell { width: 38px; padding: 0 0 0 12px !important; }
.pb-checkbox { width: 15px; height: 15px; cursor: pointer; accent-color: var(--accent); }
.pb-name-link { color: var(--accent); text-decoration: none; font-weight: 600; }
.pb-name-link:hover { text-decoration: underline; }
.pb-bulk-wrap { position: relative; display: inline-block; }
.pb-three-dot-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 34px; height: 34px; border-radius: 7px;
    background: var(--bg-card); border: 1.5px solid var(--border);
    color: var(--text-secondary); font-size: 1.1rem; cursor: pointer;
    transition: all .15s; line-height: 1;
}
.pb-three-dot-btn:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--accent); }
.pb-bulk-drop {
    display: none; position: absolute; right: 0; top: calc(100% + 6px);
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 8px; min-width: 210px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.35); z-index: 999; overflow: hidden;
}
.pb-bulk-drop.open { display: block; }
.pb-bulk-drop-item {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 0.65rem 1rem; font-size: 0.82rem;
    color: var(--text-primary); background: transparent;
    border: none; cursor: pointer; width: 100%; text-align: left;
    transition: background .12s;
}
.pb-bulk-drop-item:hover { background: var(--bg-hover); }
.pb-bulk-drop-item.danger { color: #f87171; }
.pb-bulk-drop-item.danger:hover { background: rgba(220,38,38,.12); }
.pb-bulk-drop-sep { border: none; border-top: 1px solid var(--border); margin: 0; }
.pb-bulk-count {
    display: none; align-items: center; gap: 0.4rem;
    font-size: 0.78rem; color: var(--accent); font-weight: 600;
    padding: 0.2rem 0.6rem; background: rgba(99,102,241,.1);
    border-radius: 20px; white-space: nowrap;
}
.pb-bulk-count.visible { display: inline-flex; }
.pb-header-right { display: flex; align-items: center; gap: 0.6rem; }
</style>

<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-tag"></i> Price Books</h1>
      <p class="crm2-subtitle">Manage your price books.</p>
    </div>
    <div class="pb-header-right">
      <span class="pb-bulk-count" id="pbBulkCount">0 selected</span>
      <div class="pb-bulk-wrap">
        <button class="pb-three-dot-btn" id="pbBulkBtn" title="Bulk actions">&#8942;</button>
        <div class="pb-bulk-drop" id="pbBulkDrop">
          <button class="pb-bulk-drop-item" onclick="pbBulkExport()">
            <i class="fas fa-file-export" style="width:16px;color:#6366f1;"></i> Export Selected
          </button>
          <button class="pb-bulk-drop-item" onclick="pbBulkPrint()">
            <i class="fas fa-print" style="width:16px;color:#10b981;"></i> Print Default View
          </button>
          <hr class="pb-bulk-drop-sep">
          <button class="pb-bulk-drop-item danger" onclick="pbBulkDelete()">
            <i class="fas fa-trash" style="width:16px;"></i> Delete Selected
          </button>
        </div>
      </div>
      <a href="{{ route('admin.crm2.inventory.price-books.create') }}" class="crm2-btn crm2-btn-primary">
        <i class="fas fa-plus"></i> New Price Book
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
    <table class="crm2-table" id="pbTable">
      <thead>
        <tr>
          <th class="pb-cb-cell"><input type="checkbox" class="pb-checkbox" id="pbSelectAll" title="Select all"></th>
          <th>Name</th>
          <th>Pricing Model</th>
          <th>Pricing %</th>
          <th>Currency</th>
          <th>Active</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
        <tr onclick="pbRowClick(event, '{{ route('admin.crm2.inventory.price-books.show', $item->id) }}')"
            data-id="{{ $item->id }}">
          <td class="pb-cb-cell" onclick="event.stopPropagation()">
            <input type="checkbox" class="pb-checkbox pb-row-cb" value="{{ $item->id }}" title="Select">
          </td>
          <td>
            <a href="{{ route('admin.crm2.inventory.price-books.show', $item->id) }}"
               class="pb-name-link" onclick="event.stopPropagation()">{{ $item->name }}</a>
          </td>
          <td>{{ $item->pricing_model ?: '—' }}</td>
          <td>{{ $item->pricing_percentage ? number_format($item->pricing_percentage, 2) . '%' : '—' }}</td>
          <td>{{ $item->currency ?: 'INR' }}</td>
          <td>
            <span class="crm2-badge {{ $item->is_active ? 'status-won' : 'status-lost' }}">
              {{ $item->is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td>{{ $item->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="7">
            <div class="crm2-empty">
              <i class="fas fa-tag"></i>
              <p>No price books found. <a href="{{ route('admin.crm2.inventory.price-books.create') }}">Create one</a>.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
    @if($items->hasPages())
    <div style="padding:1rem 1.25rem">{{ $items->links() }}</div>
    @endif
  </div></div>
</div>

<form id="pbBulkDeleteForm" method="POST"
      action="{{ route('admin.crm2.inventory.price-books.bulk-delete') }}"
      style="display:none">
  @csrf @method('DELETE')
  <div id="pbBulkDeleteInputs"></div>
</form>

<script>
function pbRowClick(e, url) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'A' || e.target.tagName === 'BUTTON') return;
    window.location = url;
}
const pbSelectAll = document.getElementById('pbSelectAll');
const pbBulkCount = document.getElementById('pbBulkCount');
function pbUpdateCount() {
    const n = document.querySelectorAll('.pb-row-cb:checked').length;
    pbBulkCount.textContent = n + ' selected';
    pbBulkCount.classList.toggle('visible', n > 0);
}
pbSelectAll.addEventListener('change', function() {
    document.querySelectorAll('.pb-row-cb').forEach(cb => cb.checked = this.checked);
    pbUpdateCount();
});
document.querySelectorAll('.pb-row-cb').forEach(cb => {
    cb.addEventListener('change', function() {
        const all = document.querySelectorAll('.pb-row-cb');
        pbSelectAll.checked = [...all].every(c => c.checked);
        pbSelectAll.indeterminate = [...all].some(c => c.checked) && ![...all].every(c => c.checked);
        pbUpdateCount();
    });
});
document.getElementById('pbBulkBtn').addEventListener('click', function(e) {
    e.stopPropagation();
    document.getElementById('pbBulkDrop').classList.toggle('open');
});
document.addEventListener('click', function(e) {
    const drop = document.getElementById('pbBulkDrop');
    const btn  = document.getElementById('pbBulkBtn');
    if (drop && btn && !btn.contains(e.target) && !drop.contains(e.target)) drop.classList.remove('open');
});
function pbGetSelected() {
    return [...document.querySelectorAll('.pb-row-cb:checked')].map(cb => cb.value);
}
function pbBulkExport() {
    const ids = pbGetSelected();
    if (!ids.length) { alert('Please select at least one price book to export.'); return; }
    document.getElementById('pbBulkDrop').classList.remove('open');
    const headers = ['Name','Pricing Model','Pricing %','Currency','Active','Created'];
    const rows = [];
    document.querySelectorAll('#pbTable tbody tr[data-id]').forEach(tr => {
        if (!tr.querySelector('.pb-row-cb')?.checked) return;
        const cells = tr.querySelectorAll('td');
        rows.push([cells[1]?.innerText.trim(),cells[2]?.innerText.trim(),cells[3]?.innerText.trim(),cells[4]?.innerText.trim(),cells[5]?.innerText.trim(),cells[6]?.innerText.trim()]);
    });
    let csv = headers.join(',') + '\n';
    rows.forEach(r => { csv += r.map(v => '"' + (v||'').replace(/"/g,'""') + '"').join(',') + '\n'; });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(new Blob([csv], {type:'text/csv'}));
    a.download = 'price_books_' + new Date().toISOString().slice(0,10) + '.csv';
    a.click();
}
function pbBulkPrint() {
    const ids = pbGetSelected();
    if (!ids.length) { alert('Please select at least one price book to print.'); return; }
    document.getElementById('pbBulkDrop').classList.remove('open');
    document.querySelectorAll('#pbTable tbody tr[data-id]').forEach(tr => {
        tr.style.display = tr.querySelector('.pb-row-cb')?.checked ? '' : 'none';
    });
    window.print();
    document.querySelectorAll('#pbTable tbody tr[data-id]').forEach(tr => tr.style.display = '');
}
function pbBulkDelete() {
    const ids = pbGetSelected();
    if (!ids.length) { alert('Please select at least one price book to delete.'); return; }
    document.getElementById('pbBulkDrop').classList.remove('open');
    if (!confirm('Delete ' + ids.length + ' selected price book(s)? This cannot be undone.')) return;
    const c = document.getElementById('pbBulkDeleteInputs');
    c.innerHTML = '';
    ids.forEach(id => { const i = document.createElement('input'); i.type='hidden'; i.name='ids[]'; i.value=id; c.appendChild(i); });
    document.getElementById('pbBulkDeleteForm').submit();
}
</script>
@endsection
