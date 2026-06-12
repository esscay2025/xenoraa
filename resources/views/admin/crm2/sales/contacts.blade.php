@extends('layouts.admin')
@section('title', 'Contacts')
@section('page-title', 'Contacts')
@push('styles')
<style>
/* Checkbox column */
.crm2-table th.cb-col, .crm2-table td.cb-col { width: 38px; padding: 0 0 0 14px; text-align: center; }
.crm2-table input[type=checkbox] { width: 15px; height: 15px; accent-color: var(--accent,#6366f1); cursor: pointer; }
/* Row click cursor */
.crm2-table tbody tr.clickable-row { cursor: pointer; }
.crm2-table tbody tr.clickable-row:hover { background: var(--bg-hover, rgba(99,102,241,.06)); }
/* 3-dot bulk menu */
.xn-bulk-wrap { position: relative; display: inline-block; }
.xn-bulk-btn { width: 34px; height: 34px; border-radius: 7px; border: 1px solid var(--border,#e2e8f0); background: var(--bg-card,#fff); color: var(--text-secondary,#64748b); font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .15s; }
.xn-bulk-btn:hover { background: var(--bg-hover,#f1f5f9); }
.xn-bulk-drop { display: none; position: absolute; right: 0; top: calc(100% + 4px); min-width: 200px; background: var(--bg-card,#fff); border: 1px solid var(--border,#e2e8f0); border-radius: 9px; box-shadow: 0 8px 24px rgba(0,0,0,.12); z-index: 999; padding: 5px 0; }
.xn-bulk-drop.open { display: block; }
.xn-bulk-item { display: flex; align-items: center; gap: .6rem; padding: .55rem 1rem; font-size: .84rem; color: var(--text-primary,#1a1a2e); cursor: pointer; transition: background .12s; border: none; background: none; width: 100%; text-align: left; text-decoration: none; }
.xn-bulk-item:hover { background: var(--bg-hover,#f1f5f9); }
.xn-bulk-item i { width: 16px; text-align: center; }
.xn-bulk-item.danger { color: #ef4444; }
.xn-sel-badge { display: none; background: var(--accent,#6366f1); color: #fff; font-size: .72rem; font-weight: 700; padding: .15rem .5rem; border-radius: 10px; margin-left: .3rem; }
.xn-sel-badge.visible { display: inline-block; }
</style>
@endpush
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-address-book"></i> Contacts</h1>
      <p class="crm2-subtitle">Manage your CRM contacts.</p>
    </div>
    <div style="display:flex;align-items:center;gap:.6rem">
      <span class="xn-sel-badge" id="selBadge">0 selected</span>
      <a href="{{ route('admin.crm2.sales.contacts.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Contact</a>
      <div class="xn-bulk-wrap">
        <button class="xn-bulk-btn" id="bulkMenuBtn" title="More actions" onclick="toggleBulkMenu(event)">&#8942;</button>
        <div class="xn-bulk-drop" id="bulkDrop">
          <button class="xn-bulk-item" onclick="bulkCreateTask()"><i class="fas fa-tasks" style="color:#6366f1"></i> Create Task for Selected</button>
          <button class="xn-bulk-item" onclick="bulkExport()"><i class="fas fa-file-csv" style="color:#10b981"></i> Export Selected Records</button>
          <div style="border-top:1px solid var(--border,#e2e8f0);margin:4px 0"></div>
          <button class="xn-bulk-item danger" onclick="bulkDelete()"><i class="fas fa-trash"></i> Delete Selected Records</button>
        </div>
      </div>
    </div>
  </div>

  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  @if(session('error'))<div class="crm2-alert danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif

  {{-- Filter bar --}}
  <div class="crm2-card mb-4"><div class="crm2-card-body">
    <form method="GET" class="crm2-filter-form">
      <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search contacts..." class="crm2-input"></div>
      <div class="filter-group"><select name="status" class="crm2-select"><option value="">All Status</option>@foreach(['active','inactive','lead','customer','partner','vendor'] as $s)<option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
      <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
      <a href="{{ route('admin.crm2.sales.contacts') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
    </form>
  </div></div>

  {{-- Table --}}
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table" id="contactsTable">
      <thead>
        <tr>
          <th class="cb-col"><input type="checkbox" id="selectAll" title="Select all" onchange="toggleSelectAll(this)"></th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Job Title</th>
          <th>Account</th>
          <th>Status</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
        @forelse($contacts as $contact)
        @php $viewUrl = route('admin.crm2.sales.contacts.show', $contact->id); @endphp
        <tr class="clickable-row" onclick="rowNav(event, '{{ $viewUrl }}')">
          <td class="cb-col" onclick="event.stopPropagation()">
            <input type="checkbox" class="contact-cb" value="{{ $contact->id }}" onchange="updateSelection()">
          </td>
          <td style="font-weight:600;color:var(--accent,#6366f1)">{{ $contact->first_name }} {{ $contact->last_name }}</td>
          <td>{{ $contact->email ?? '—' }}</td>
          <td>{{ $contact->phone ?? '—' }}</td>
          <td>{{ $contact->job_title ?? '—' }}</td>
          <td>{{ $contact->account?->name ?? '—' }}</td>
          <td><span class="crm2-badge status-{{ $contact->status ?? 'active' }}">{{ ucfirst($contact->status ?? 'Active') }}</span></td>
          <td>{{ $contact->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="8"><div class="crm2-empty"><i class="fas fa-address-book"></i><p>No contacts found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($contacts->hasPages())<div class="crm2-pagination">{{ $contacts->links() }}</div>@endif
  </div>
</div>

{{-- Hidden bulk-delete form --}}
<form id="bulkDeleteForm" method="POST" action="{{ route('admin.crm2.sales.contacts.bulk-delete') }}" style="display:none">
  @csrf
  @method('DELETE')
  <input type="hidden" name="ids" id="bulkDeleteIds">
</form>

{{-- Hidden bulk-task form --}}
<form id="bulkTaskForm" method="POST" action="{{ route('admin.crm2.sales.contacts.bulk-task') }}" style="display:none">
  @csrf
  <input type="hidden" name="ids" id="bulkTaskIds">
</form>

<script>
// Row navigation (skip if clicking checkbox or links inside row)
function rowNav(event, url) {
  if (event.target.tagName === 'INPUT' || event.target.tagName === 'A' || event.target.tagName === 'BUTTON') return;
  window.location.href = url;
}

// Select all / individual checkboxes
function toggleSelectAll(cb) {
  document.querySelectorAll('.contact-cb').forEach(c => c.checked = cb.checked);
  updateSelection();
}
function updateSelection() {
  const checked = document.querySelectorAll('.contact-cb:checked');
  const badge = document.getElementById('selBadge');
  const total = document.querySelectorAll('.contact-cb').length;
  const allCb = document.getElementById('selectAll');
  badge.textContent = checked.length + ' selected';
  badge.classList.toggle('visible', checked.length > 0);
  allCb.indeterminate = checked.length > 0 && checked.length < total;
  allCb.checked = checked.length === total && total > 0;
}

// 3-dot bulk menu toggle
function toggleBulkMenu(e) {
  e.stopPropagation();
  document.getElementById('bulkDrop').classList.toggle('open');
}
document.addEventListener('click', function() {
  document.getElementById('bulkDrop').classList.remove('open');
});

// Get selected IDs
function getSelectedIds() {
  return Array.from(document.querySelectorAll('.contact-cb:checked')).map(c => c.value);
}

// Bulk delete
function bulkDelete() {
  const ids = getSelectedIds();
  if (!ids.length) { alert('Please select at least one contact.'); return; }
  if (!confirm('Delete ' + ids.length + ' selected contact(s)? This cannot be undone.')) return;
  document.getElementById('bulkDeleteIds').value = ids.join(',');
  document.getElementById('bulkDeleteForm').submit();
}

// Bulk export CSV
function bulkExport() {
  const ids = getSelectedIds();
  if (!ids.length) { alert('Please select at least one contact to export.'); return; }
  const rows = [['Name','Email','Phone','Job Title','Account','Status','Created']];
  document.querySelectorAll('#contactsTable tbody tr.clickable-row').forEach(tr => {
    const cb = tr.querySelector('.contact-cb');
    if (cb && cb.checked) {
      const cells = tr.querySelectorAll('td');
      rows.push([
        cells[1]?.innerText.trim() || '',
        cells[2]?.innerText.trim() || '',
        cells[3]?.innerText.trim() || '',
        cells[4]?.innerText.trim() || '',
        cells[5]?.innerText.trim() || '',
        cells[6]?.innerText.trim() || '',
        cells[7]?.innerText.trim() || '',
      ]);
    }
  });
  const csv = rows.map(r => r.map(v => '"'+v.replace(/"/g,'""')+'"').join(',')).join('\n');
  const blob = new Blob([csv], {type:'text/csv'});
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = 'contacts_export_' + new Date().toISOString().slice(0,10) + '.csv';
  a.click();
  document.getElementById('bulkDrop').classList.remove('open');
}

// Bulk create task
function bulkCreateTask() {
  const ids = getSelectedIds();
  if (!ids.length) { alert('Please select at least one contact.'); return; }
  document.getElementById('bulkTaskIds').value = ids.join(',');
  document.getElementById('bulkTaskForm').submit();
}
</script>
@endsection
