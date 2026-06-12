@extends('layouts.admin')
@section('title', 'Vendors')
@section('page-title', 'Vendors')
@section('content')
<style>
.crm2-table tbody tr { cursor: pointer; transition: background .12s; }
.crm2-table tbody tr:hover td { background: var(--bg-hover); }
.vn-cb-cell { width: 38px; padding: 0 0 0 12px !important; }
.vn-checkbox { width: 15px; height: 15px; cursor: pointer; accent-color: var(--accent); }
.vn-name-link { color: var(--accent); text-decoration: none; font-weight: 600; }
.vn-name-link:hover { text-decoration: underline; }
.vn-bulk-wrap { position: relative; display: inline-block; }
.vn-three-dot-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 34px; height: 34px; border-radius: 7px;
    background: var(--bg-card); border: 1.5px solid var(--border);
    color: var(--text-secondary); font-size: 1.1rem; cursor: pointer;
    transition: all .15s; line-height: 1;
}
.vn-three-dot-btn:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--accent); }
.vn-bulk-drop {
    display: none; position: absolute; right: 0; top: calc(100% + 6px);
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 8px; min-width: 210px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.35); z-index: 999; overflow: hidden;
}
.vn-bulk-drop.open { display: block; }
.vn-bulk-drop-item {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 0.65rem 1rem; font-size: 0.82rem;
    color: var(--text-primary); background: transparent;
    border: none; cursor: pointer; width: 100%; text-align: left;
    transition: background .12s;
}
.vn-bulk-drop-item:hover { background: var(--bg-hover); }
.vn-bulk-drop-item.danger { color: #f87171; }
.vn-bulk-drop-item.danger:hover { background: rgba(220,38,38,.12); }
.vn-bulk-drop-sep { border: none; border-top: 1px solid var(--border); margin: 0; }
.vn-bulk-count {
    display: none; align-items: center; gap: 0.4rem;
    font-size: 0.78rem; color: var(--accent); font-weight: 600;
    padding: 0.2rem 0.6rem; background: rgba(99,102,241,.1);
    border-radius: 20px; white-space: nowrap;
}
.vn-bulk-count.visible { display: inline-flex; }
.vn-header-right { display: flex; align-items: center; gap: 0.6rem; }
</style>

<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-truck"></i> Vendors</h1>
      <p class="crm2-subtitle">Manage your vendors.</p>
    </div>
    <div class="vn-header-right">
      <span class="vn-bulk-count" id="vnBulkCount">0 selected</span>
      <div class="vn-bulk-wrap">
        <button class="vn-three-dot-btn" id="vnBulkBtn" title="Bulk actions">&#8942;</button>
        <div class="vn-bulk-drop" id="vnBulkDrop">
          <button class="vn-bulk-drop-item" onclick="vnBulkExport()">
            <i class="fas fa-file-export" style="width:16px;color:#6366f1;"></i> Export Selected
          </button>
          <button class="vn-bulk-drop-item" onclick="vnBulkPrint()">
            <i class="fas fa-print" style="width:16px;color:#10b981;"></i> Print Default View
          </button>
          <hr class="vn-bulk-drop-sep">
          <button class="vn-bulk-drop-item danger" onclick="vnBulkDelete()">
            <i class="fas fa-trash" style="width:16px;"></i> Delete Selected
          </button>
        </div>
      </div>
      <a href="{{ route('admin.crm2.inventory.vendors.create') }}" class="crm2-btn crm2-btn-primary">
        <i class="fas fa-plus"></i> New Vendor
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
    <table class="crm2-table" id="vnTable">
      <thead>
        <tr>
          <th class="vn-cb-cell"><input type="checkbox" class="vn-checkbox" id="vnSelectAll" title="Select all"></th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>City</th>
          <th>Category</th>
          <th>Status</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
        @php $viewUrl = route('admin.crm2.inventory.vendors.show', $item->id); @endphp
        <tr onclick="vnRowClick(event, '{{ $viewUrl }}')" data-id="{{ $item->id }}">
          <td class="vn-cb-cell" onclick="event.stopPropagation()">
            <input type="checkbox" class="vn-checkbox vn-row-cb" value="{{ $item->id }}" title="Select">
          </td>
          <td>
            <a href="{{ $viewUrl }}" class="vn-name-link" onclick="event.stopPropagation()">{{ $item->name }}</a>
          </td>
          <td>{{ $item->email ?? '—' }}</td>
          <td>{{ $item->phone ?? '—' }}</td>
          <td>{{ $item->city ?? '—' }}</td>
          <td>{{ $item->category ?? '—' }}</td>
          <td>
            <span class="crm2-badge {{ $item->status === 'active' ? 'status-active' : 'status-lost' }}">
              {{ ucfirst($item->status ?? 'Active') }}
            </span>
          </td>
          <td>{{ $item->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="crm2-empty">
              <i class="fas fa-truck"></i>
              <p>No vendors found. <a href="{{ route('admin.crm2.inventory.vendors.create') }}">Create the first one</a>.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div></div>
</div>

<form id="vnBulkDeleteForm" method="POST"
      action="{{ route('admin.crm2.inventory.vendors.bulk-delete') }}"
      style="display:none">
  @csrf @method('DELETE')
  <div id="vnBulkDeleteInputs"></div>
</form>

<script>
function vnRowClick(e, url) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'A' || e.target.tagName === 'BUTTON') return;
    window.location = url;
}
const vnSelectAll = document.getElementById('vnSelectAll');
const vnBulkCount = document.getElementById('vnBulkCount');
function vnUpdateCount() {
    const n = document.querySelectorAll('.vn-row-cb:checked').length;
    vnBulkCount.textContent = n + ' selected';
    vnBulkCount.classList.toggle('visible', n > 0);
}
vnSelectAll.addEventListener('change', function() {
    document.querySelectorAll('.vn-row-cb').forEach(cb => cb.checked = this.checked);
    vnUpdateCount();
});
document.querySelectorAll('.vn-row-cb').forEach(cb => {
    cb.addEventListener('change', function() {
        const all = document.querySelectorAll('.vn-row-cb');
        vnSelectAll.checked = [...all].every(c => c.checked);
        vnSelectAll.indeterminate = [...all].some(c => c.checked) && ![...all].every(c => c.checked);
        vnUpdateCount();
    });
});
document.getElementById('vnBulkBtn').addEventListener('click', function(e) {
    e.stopPropagation();
    document.getElementById('vnBulkDrop').classList.toggle('open');
});
document.addEventListener('click', function(e) {
    const drop = document.getElementById('vnBulkDrop');
    const btn  = document.getElementById('vnBulkBtn');
    if (drop && btn && !btn.contains(e.target) && !drop.contains(e.target)) drop.classList.remove('open');
});
function vnGetSelected() {
    return [...document.querySelectorAll('.vn-row-cb:checked')].map(cb => cb.value);
}
function vnBulkExport() {
    const ids = vnGetSelected();
    if (!ids.length) { alert('Please select at least one vendor to export.'); return; }
    document.getElementById('vnBulkDrop').classList.remove('open');
    const headers = ['Name','Email','Phone','City','Category','Status','Created'];
    const rows = [];
    document.querySelectorAll('#vnTable tbody tr[data-id]').forEach(tr => {
        if (!tr.querySelector('.vn-row-cb')?.checked) return;
        const cells = tr.querySelectorAll('td');
        rows.push([cells[1]?.innerText.trim(),cells[2]?.innerText.trim(),cells[3]?.innerText.trim(),cells[4]?.innerText.trim(),cells[5]?.innerText.trim(),cells[6]?.innerText.trim(),cells[7]?.innerText.trim()]);
    });
    let csv = headers.join(',') + '\n';
    rows.forEach(r => { csv += r.map(v => '"' + (v||'').replace(/"/g,'""') + '"').join(',') + '\n'; });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(new Blob([csv], {type:'text/csv'}));
    a.download = 'vendors_' + new Date().toISOString().slice(0,10) + '.csv';
    a.click();
}
function vnBulkPrint() {
    const ids = vnGetSelected();
    if (!ids.length) { alert('Please select at least one vendor to print.'); return; }
    document.getElementById('vnBulkDrop').classList.remove('open');
    document.querySelectorAll('#vnTable tbody tr[data-id]').forEach(tr => {
        tr.style.display = tr.querySelector('.vn-row-cb')?.checked ? '' : 'none';
    });
    window.print();
    document.querySelectorAll('#vnTable tbody tr[data-id]').forEach(tr => tr.style.display = '');
}
function vnBulkDelete() {
    const ids = vnGetSelected();
    if (!ids.length) { alert('Please select at least one vendor to delete.'); return; }
    document.getElementById('vnBulkDrop').classList.remove('open');
    if (!confirm('Delete ' + ids.length + ' selected vendor(s)? This cannot be undone.')) return;
    const c = document.getElementById('vnBulkDeleteInputs');
    c.innerHTML = '';
    ids.forEach(id => { const i = document.createElement('input'); i.type='hidden'; i.name='ids[]'; i.value=id; c.appendChild(i); });
    document.getElementById('vnBulkDeleteForm').submit();
}
</script>
@endsection
