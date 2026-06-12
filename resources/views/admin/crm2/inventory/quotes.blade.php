@extends('layouts.admin')
@section('title', 'Quotes')
@section('page-title', 'Quotes')
@section('content')
<style>
/* ── Table base ── */
.crm2-table tbody tr { cursor: pointer; transition: background .12s; }
.crm2-table tbody tr:hover td { background: var(--bg-hover); }
.qt-subject-link { color: var(--accent); text-decoration: none; font-weight: 600; }
.qt-subject-link:hover { text-decoration: underline; }

/* ── Checkbox column ── */
.qt-cb-cell { width: 38px; padding: 0 0 0 12px !important; }
.qt-checkbox { width: 15px; height: 15px; cursor: pointer; accent-color: var(--accent); }

/* ── 3-dot bulk menu ── */
.qt-bulk-wrap { position: relative; display: inline-block; }
.qt-three-dot-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 34px; height: 34px; border-radius: 7px;
    background: var(--bg-card); border: 1.5px solid var(--border);
    color: var(--text-secondary); font-size: 1.1rem; cursor: pointer;
    transition: all .15s; line-height: 1;
}
.qt-three-dot-btn:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--accent); }
.qt-bulk-drop {
    display: none; position: absolute; right: 0; top: calc(100% + 6px);
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 8px; min-width: 210px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.35); z-index: 999; overflow: hidden;
}
.qt-bulk-drop.open { display: block; }
.qt-bulk-drop-item {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 0.65rem 1rem; font-size: 0.82rem;
    color: var(--text-primary); background: transparent;
    border: none; cursor: pointer; width: 100%; text-align: left;
    transition: background .12s;
}
.qt-bulk-drop-item:hover { background: var(--bg-hover); }
.qt-bulk-drop-item.danger { color: #f87171; }
.qt-bulk-drop-item.danger:hover { background: rgba(220,38,38,.12); }
.qt-bulk-drop-sep { border: none; border-top: 1px solid var(--border); margin: 0; }
.qt-bulk-count {
    display: none; align-items: center; gap: 0.4rem;
    font-size: 0.78rem; color: var(--accent); font-weight: 600;
    padding: 0.2rem 0.6rem; background: rgba(99,102,241,.1);
    border-radius: 20px; white-space: nowrap;
}
.qt-bulk-count.visible { display: inline-flex; }

/* ── Header right group ── */
.qt-header-right { display: flex; align-items: center; gap: 0.6rem; }
</style>

<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-file-alt"></i> Quotes</h1>
      <p class="crm2-subtitle">Manage your quotes.</p>
    </div>
    <div class="qt-header-right">
      {{-- Selection count badge --}}
      <span class="qt-bulk-count" id="qtBulkCount">0 selected</span>

      {{-- 3-dot bulk actions button --}}
      <div class="qt-bulk-wrap">
        <button class="qt-three-dot-btn" id="qtBulkBtn" title="Bulk actions">&#8942;</button>
        <div class="qt-bulk-drop" id="qtBulkDrop">
          <button class="qt-bulk-drop-item" onclick="qtBulkExport()">
            <i class="fas fa-file-export" style="width:16px;color:#6366f1;"></i> Export Selected
          </button>
          <button class="qt-bulk-drop-item" onclick="qtBulkPrint()">
            <i class="fas fa-print" style="width:16px;color:#10b981;"></i> Print Default View
          </button>
          <hr class="qt-bulk-drop-sep">
          <button class="qt-bulk-drop-item danger" onclick="qtBulkDelete()">
            <i class="fas fa-trash" style="width:16px;"></i> Delete Selected
          </button>
        </div>
      </div>

      {{-- New Quote button --}}
      <a href="{{ route('admin.crm2.inventory.quotes.create') }}" class="crm2-btn crm2-btn-primary">
        <i class="fas fa-plus"></i> New Quote
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
    <table class="crm2-table" id="qtTable">
      <thead>
        <tr>
          <th class="qt-cb-cell">
            <input type="checkbox" class="qt-checkbox" id="qtSelectAll" title="Select all">
          </th>
          <th>Quote Number</th>
          <th>Subject</th>
          <th>Account</th>
          <th>Stage</th>
          <th>Grand Total</th>
          <th>Valid Until</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
        <tr onclick="qtRowClick(event, '{{ route('admin.crm2.inventory.quotes.show', $item->id) }}')"
            data-id="{{ $item->id }}">
          <td class="qt-cb-cell" onclick="event.stopPropagation()">
            <input type="checkbox" class="qt-checkbox qt-row-cb" value="{{ $item->id }}" title="Select">
          </td>
          <td>{{ $item->quote_number }}</td>
          <td>
            <a href="{{ route('admin.crm2.inventory.quotes.show', $item->id) }}"
               class="qt-subject-link"
               onclick="event.stopPropagation()">
              {{ $item->subject }}
            </a>
          </td>
          <td>{{ $item->account?->name ?? '—' }}</td>
          <td>
            @php
              $stageColors = [
                'draft'       => 'status-draft',
                'negotiation' => 'status-pending',
                'delivered'   => 'status-active',
                'accepted'    => 'status-won',
                'declined'    => 'status-lost',
              ];
              $stageLabels = \App\Models\CrmQuote::STAGES;
            @endphp
            <span class="crm2-badge {{ $stageColors[$item->stage] ?? 'status-new' }}">
              {{ $stageLabels[$item->stage] ?? ucfirst($item->stage) }}
            </span>
          </td>
          <td>{{ $item->grand_total ? '₹' . number_format($item->grand_total, 2) : '—' }}</td>
          <td>{{ $item->valid_until ? $item->valid_until->format('d M Y') : '—' }}</td>
          <td>{{ $item->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="crm2-empty">
              <i class="fas fa-file-alt"></i>
              <p>No quotes found. <a href="{{ route('admin.crm2.inventory.quotes.create') }}">Create the first one</a>.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div></div>
</div>

{{-- Hidden bulk-delete form --}}
<form id="qtBulkDeleteForm" method="POST"
      action="{{ route('admin.crm2.inventory.quotes.bulk-delete') }}"
      style="display:none">
  @csrf @method('DELETE')
  <div id="qtBulkDeleteInputs"></div>
</form>

<script>
// ── Row click (navigate unless clicking checkbox or link) ──
function qtRowClick(e, url) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'A' || e.target.tagName === 'BUTTON') return;
    window.location = url;
}

// ── Checkbox logic ──
const qtSelectAll = document.getElementById('qtSelectAll');
const qtBulkCount = document.getElementById('qtBulkCount');

function qtUpdateCount() {
    const checked = document.querySelectorAll('.qt-row-cb:checked').length;
    qtBulkCount.textContent = checked + ' selected';
    qtBulkCount.classList.toggle('visible', checked > 0);
}

qtSelectAll.addEventListener('change', function() {
    document.querySelectorAll('.qt-row-cb').forEach(cb => cb.checked = this.checked);
    qtUpdateCount();
});

document.querySelectorAll('.qt-row-cb').forEach(cb => {
    cb.addEventListener('change', function() {
        const all = document.querySelectorAll('.qt-row-cb');
        qtSelectAll.checked = [...all].every(c => c.checked);
        qtSelectAll.indeterminate = [...all].some(c => c.checked) && ![...all].every(c => c.checked);
        qtUpdateCount();
    });
});

// ── 3-dot bulk menu toggle ──
document.getElementById('qtBulkBtn').addEventListener('click', function(e) {
    e.stopPropagation();
    document.getElementById('qtBulkDrop').classList.toggle('open');
});
document.addEventListener('click', function(e) {
    const drop = document.getElementById('qtBulkDrop');
    const btn  = document.getElementById('qtBulkBtn');
    if (!btn.contains(e.target) && !drop.contains(e.target)) {
        drop.classList.remove('open');
    }
});

// ── Get selected IDs ──
function qtGetSelected() {
    return [...document.querySelectorAll('.qt-row-cb:checked')].map(cb => cb.value);
}

// ── Bulk Export (CSV) ──
function qtBulkExport() {
    const ids = qtGetSelected();
    if (!ids.length) { alert('Please select at least one quote to export.'); return; }
    document.getElementById('qtBulkDrop').classList.remove('open');

    // Build CSV from visible table rows
    const headers = ['Quote Number','Subject','Account','Stage','Grand Total','Valid Until','Created'];
    const rows = [];
    document.querySelectorAll('#qtTable tbody tr[data-id]').forEach(tr => {
        const cb = tr.querySelector('.qt-row-cb');
        if (!cb || !cb.checked) return;
        const cells = tr.querySelectorAll('td');
        rows.push([
            cells[1]?.innerText.trim(),
            cells[2]?.innerText.trim(),
            cells[3]?.innerText.trim(),
            cells[4]?.innerText.trim(),
            cells[5]?.innerText.trim(),
            cells[6]?.innerText.trim(),
            cells[7]?.innerText.trim(),
        ]);
    });
    let csv = headers.join(',') + '\n';
    rows.forEach(r => { csv += r.map(v => '"' + (v||'').replace(/"/g,'""') + '"').join(',') + '\n'; });
    const blob = new Blob([csv], {type:'text/csv'});
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'quotes_export_' + new Date().toISOString().slice(0,10) + '.csv';
    a.click();
}

// ── Bulk Print ──
function qtBulkPrint() {
    const ids = qtGetSelected();
    if (!ids.length) { alert('Please select at least one quote to print.'); return; }
    document.getElementById('qtBulkDrop').classList.remove('open');

    // Highlight selected rows and print
    document.querySelectorAll('#qtTable tbody tr[data-id]').forEach(tr => {
        const cb = tr.querySelector('.qt-row-cb');
        tr.style.display = (cb && cb.checked) ? '' : 'none';
    });
    window.print();
    // Restore
    document.querySelectorAll('#qtTable tbody tr[data-id]').forEach(tr => tr.style.display = '');
}

// ── Bulk Delete ──
function qtBulkDelete() {
    const ids = qtGetSelected();
    if (!ids.length) { alert('Please select at least one quote to delete.'); return; }
    document.getElementById('qtBulkDrop').classList.remove('open');
    if (!confirm('Delete ' + ids.length + ' selected quote(s)? This cannot be undone.')) return;

    const container = document.getElementById('qtBulkDeleteInputs');
    container.innerHTML = '';
    ids.forEach(id => {
        const inp = document.createElement('input');
        inp.type = 'hidden'; inp.name = 'ids[]'; inp.value = id;
        container.appendChild(inp);
    });
    document.getElementById('qtBulkDeleteForm').submit();
}
</script>
@endsection
