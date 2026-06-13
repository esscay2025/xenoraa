@extends('layouts.admin')
@section('title', 'Deals')
@section('page-title', 'Deals')
@push('styles')
<style>
/* ── Stage badges ───────────────────────────────────────────────── */
.stage-badge { display: inline-flex; align-items: center; padding: .2rem .65rem; border-radius: 20px; font-size: .72rem; font-weight: 600; }
.stage-prospecting  { background: #e0e7ff; color: #3730a3; }
.stage-qualification{ background: #dbeafe; color: #1d4ed8; }
.stage-proposal     { background: #fef3c7; color: #92400e; }
.stage-negotiation  { background: #ffedd5; color: #c2410c; }
.stage-closed-won   { background: #d1fae5; color: #065f46; }
.stage-closed-lost  { background: #fee2e2; color: #991b1b; }
/* ── Table extras ───────────────────────────────────────────────── */
.crm2-table tbody tr.clickable-row { cursor: pointer; }
.crm2-table tbody tr.clickable-row:hover { background: var(--bg-hover, rgba(99,102,241,.06)); }
/* ── 3-dot bulk menu ────────────────────────────────────────────── */
.xn-bulk-wrap { position: relative; display: inline-block; }
.xn-bulk-btn  { width: 34px; height: 34px; border-radius: 7px; border: 1px solid var(--border,#e2e8f0); background: var(--bg-card,#fff); color: var(--text-secondary,#64748b); font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .15s; }
.xn-bulk-btn:hover { background: var(--bg-hover,#f1f5f9); }
.xn-bulk-drop { display: none; position: absolute; right: 0; top: calc(100% + 4px); min-width: 200px; background: var(--bg-card,#fff); border: 1px solid var(--border,#e2e8f0); border-radius: 9px; box-shadow: 0 8px 24px rgba(0,0,0,.12); z-index: 999; padding: 5px 0; }
.xn-bulk-drop.open { display: block; }
.xn-bulk-item { display: flex; align-items: center; gap: .6rem; padding: .55rem 1rem; font-size: .84rem; color: var(--text-primary,#1a1a2e); cursor: pointer; transition: background .12s; border: none; background: none; width: 100%; text-align: left; text-decoration: none; }
.xn-bulk-item:hover { background: var(--bg-hover,#f1f5f9); }
.xn-bulk-item i { width: 16px; text-align: center; }
.xn-bulk-item.danger { color: #ef4444; }
.xn-sel-badge { display: none; background: var(--accent,#6366f1); color: #fff; font-size: .72rem; font-weight: 700; padding: .15rem .5rem; border-radius: 10px; margin-left: .3rem; }
.xn-sel-badge.visible { display: inline-block; }
/* ── View toggle ────────────────────────────────────────────────── */
.view-toggle { display: flex; border: 1px solid var(--border,#e2e8f0); border-radius: 8px; overflow: hidden; }
.view-toggle-btn { padding: .38rem .75rem; background: transparent; border: none; cursor: pointer; color: var(--text-secondary,#64748b); font-size: .82rem; display: flex; align-items: center; gap: .35rem; transition: all .15s; }
.view-toggle-btn:hover { background: var(--bg-hover,#f1f5f9); }
.view-toggle-btn.active { background: var(--accent,#6366f1); color: #fff; }
/* ── Kanban board ───────────────────────────────────────────────── */
.xn-kanban-board {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    padding-bottom: 1rem;
    align-items: flex-start;
    min-height: 60vh;
}
.xn-kanban-board::-webkit-scrollbar { height: 6px; }
.xn-kanban-board::-webkit-scrollbar-thumb { background: var(--border,#e2e8f0); border-radius: 3px; }
.xn-kanban-col {
    flex: 0 0 240px;
    min-width: 240px;
    background: var(--bg-primary,#f8fafc);
    border: 1px solid var(--border,#e2e8f0);
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 260px);
}
.xn-kanban-col-header {
    padding: .65rem 1rem;
    border-bottom: 1px solid var(--border,#e2e8f0);
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    background: var(--bg-primary,#f8fafc);
    border-radius: 10px 10px 0 0;
    z-index: 2;
}
.xn-kanban-col-title {
    font-size: .82rem;
    font-weight: 700;
    color: var(--text-primary,#1a1a2e);
    display: flex;
    align-items: center;
    gap: .4rem;
}
.xn-kanban-col-dot {
    width: 9px; height: 9px; border-radius: 50%;
    display: inline-block;
}
.xn-kanban-col-count {
    font-size: .72rem;
    font-weight: 700;
    background: var(--bg-card,#fff);
    border: 1px solid var(--border,#e2e8f0);
    color: var(--text-secondary,#64748b);
    padding: .1rem .45rem;
    border-radius: 10px;
}
.xn-kanban-col-total {
    font-size: .7rem;
    color: var(--text-muted,#94a3b8);
    padding: .2rem .75rem .4rem;
    border-bottom: 1px solid var(--border,#e2e8f0);
}
.xn-kanban-cards {
    flex: 1;
    overflow-y: auto;
    padding: .5rem .5rem;
    display: flex;
    flex-direction: column;
    gap: .5rem;
    min-height: 60px;
}
.xn-kanban-cards::-webkit-scrollbar { width: 4px; }
.xn-kanban-cards::-webkit-scrollbar-thumb { background: var(--border,#e2e8f0); border-radius: 2px; }
/* drag-over highlight */
.xn-kanban-cards.drag-over {
    background: rgba(99,102,241,.06);
    border-radius: 6px;
    outline: 2px dashed var(--accent,#6366f1);
    outline-offset: -2px;
}
/* ── Deal card ──────────────────────────────────────────────────── */
.xn-deal-card {
    background: var(--bg-card,#fff);
    border: 1px solid var(--border,#e2e8f0);
    border-radius: 8px;
    padding: .7rem .85rem;
    cursor: grab;
    transition: box-shadow .15s, transform .1s;
    user-select: none;
}
.xn-deal-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,.10); }
.xn-deal-card.dragging { opacity: .45; transform: rotate(1.5deg); cursor: grabbing; }
.xn-deal-card-name {
    font-size: .84rem;
    font-weight: 700;
    color: var(--accent,#6366f1);
    margin-bottom: .3rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.xn-deal-card-name a { color: inherit; text-decoration: none; }
.xn-deal-card-name a:hover { text-decoration: underline; }
.xn-deal-card-account {
    font-size: .75rem;
    color: var(--text-secondary,#64748b);
    margin-bottom: .45rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.xn-deal-card-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: .3rem;
}
.xn-deal-card-value {
    font-size: .82rem;
    font-weight: 700;
    color: var(--text-primary,#1a1a2e);
}
.xn-deal-card-prob {
    font-size: .72rem;
    background: var(--bg-primary,#f8fafc);
    border: 1px solid var(--border,#e2e8f0);
    color: var(--text-secondary,#64748b);
    padding: .1rem .4rem;
    border-radius: 10px;
}
.xn-deal-card-footer {
    margin-top: .4rem;
    font-size: .7rem;
    color: var(--text-muted,#94a3b8);
    display: flex;
    align-items: center;
    gap: .5rem;
    flex-wrap: wrap;
}
.xn-deal-card-footer i { font-size: .65rem; }
/* ── Kanban empty state ─────────────────────────────────────────── */
.xn-kanban-empty {
    text-align: center;
    padding: 1.5rem .5rem;
    color: var(--text-muted,#94a3b8);
    font-size: .78rem;
}
.xn-kanban-empty i { font-size: 1.4rem; display: block; margin-bottom: .4rem; }
/* ── Toast notification ─────────────────────────────────────────── */
#xnToast {
    position: fixed;
    bottom: 1.5rem;
    right: 1.5rem;
    background: #1e293b;
    color: #fff;
    padding: .6rem 1.1rem;
    border-radius: 8px;
    font-size: .82rem;
    z-index: 9999;
    display: none;
    align-items: center;
    gap: .5rem;
    box-shadow: 0 4px 16px rgba(0,0,0,.2);
}
#xnToast.show { display: flex; }
#xnToast.success { border-left: 3px solid #10b981; }
#xnToast.error   { border-left: 3px solid #ef4444; }
</style>
@endpush
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-funnel-dollar"></i> Deals</h1>
      <p class="crm2-subtitle">Track your sales pipeline and deals.</p>
    </div>
    <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap">
      {{-- View Toggle --}}
      <div class="view-toggle" id="viewToggle">
        <button class="view-toggle-btn active" id="btnListView" onclick="setView('list')" title="List View">
          <i class="fas fa-list"></i> List
        </button>
        <button class="view-toggle-btn" id="btnKanbanView" onclick="setView('kanban')" title="Kanban View">
          <i class="fas fa-columns"></i> Kanban
        </button>
      </div>
      <span class="xn-sel-badge" id="selBadge">0 selected</span>
      <a href="{{ route('admin.crm2.sales.deals.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Deal</a>
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
      <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search deals..." class="crm2-input"></div>
      <div class="filter-group"><select name="stage" class="crm2-select"><option value="">All Stages</option>@foreach(['prospecting','qualification','proposal','negotiation','closed_won','closed_lost'] as $s)<option value="{{ $s }}" {{ request('stage')===$s?'selected':'' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>@endforeach</select></div>
      <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
      <a href="{{ route('admin.crm2.sales.deals') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
    </form>
  </div></div>

  {{-- ── LIST VIEW ─────────────────────────────────────────────────── --}}
  <div id="listView">
    <div class="crm2-card"><div class="crm2-card-body p-0">
      <table class="crm2-table" id="dealsTable">
        <thead>
          <tr>
            <th class="cb-col"><input type="checkbox" id="selectAll" title="Select all" onchange="toggleSelectAll(this)"></th>
            <th>Name</th>
            <th>Account</th>
            <th>Stage</th>
            <th>Value</th>
            <th>Probability</th>
            <th>Expected Close</th>
            <th>Owner</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
          @forelse($deals as $deal)
          @php
            $dealName = $deal->name ?? $deal->title ?? 'Untitled Deal';
            $stageSlug = str_replace('_','-',$deal->stage ?? 'prospecting');
            $viewUrl = route('admin.crm2.sales.deals.show', $deal->id);
          @endphp
          <tr class="clickable-row" onclick="rowNav(event, '{{ $viewUrl }}')">
            <td class="cb-col" onclick="event.stopPropagation()">
              <input type="checkbox" class="deal-cb" value="{{ $deal->id }}" onchange="updateSelection()">
            </td>
            <td style="font-weight:600;color:var(--accent,#6366f1)">{{ $dealName }}</td>
            <td>{{ $deal->account?->name ?? '—' }}</td>
            <td><span class="stage-badge stage-{{ $stageSlug }}">{{ ucwords(str_replace('_',' ',$deal->stage ?? 'prospecting')) }}</span></td>
            <td>{{ ($deal->value ?? $deal->amount) ? '₹'.number_format($deal->value ?? $deal->amount, 0) : '—' }}</td>
            <td>{{ $deal->probability ? $deal->probability.'%' : '—' }}</td>
            <td>{{ ($deal->expected_close ?? $deal->closing_date) ? \Carbon\Carbon::parse($deal->expected_close ?? $deal->closing_date)->format('d M Y') : '—' }}</td>
            <td>{{ $deal->owner?->name ?? '—' }}</td>
            <td>{{ $deal->created_at->format('d M Y') }}</td>
          </tr>
          @empty
          <tr><td colspan="9"><div class="crm2-empty"><i class="fas fa-funnel-dollar"></i><p>No deals found.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($deals->hasPages())<div class="crm2-pagination">{{ $deals->links() }}</div>@endif
    </div>
  </div>

  {{-- ── KANBAN VIEW ───────────────────────────────────────────────── --}}
  <div id="kanbanView" style="display:none">
    @php
      $stages = [
        'prospecting'  => ['label'=>'Prospecting',   'dot'=>'#6366f1', 'deals'=>collect()],
        'qualification'=> ['label'=>'Qualification',  'dot'=>'#3b82f6', 'deals'=>collect()],
        'proposal'     => ['label'=>'Proposal',       'dot'=>'#f59e0b', 'deals'=>collect()],
        'negotiation'  => ['label'=>'Negotiation',    'dot'=>'#f97316', 'deals'=>collect()],
        'closed_won'   => ['label'=>'Closed Won',     'dot'=>'#10b981', 'deals'=>collect()],
        'closed_lost'  => ['label'=>'Closed Lost',    'dot'=>'#ef4444', 'deals'=>collect()],
      ];
      foreach($allDeals as $d) {
        $s = $d->stage ?? 'prospecting';
        if(isset($stages[$s])) $stages[$s]['deals']->push($d);
      }
    @endphp
    <div class="xn-kanban-board" id="kanbanBoard">
      @foreach($stages as $stageKey => $stage)
      @php
        $colTotal = $stage['deals']->sum(fn($d) => $d->value ?? $d->amount ?? 0);
      @endphp
      <div class="xn-kanban-col" data-stage="{{ $stageKey }}">
        <div class="xn-kanban-col-header">
          <div class="xn-kanban-col-title">
            <span class="xn-kanban-col-dot" style="background:{{ $stage['dot'] }}"></span>
            {{ $stage['label'] }}
          </div>
          <span class="xn-kanban-col-count" id="cnt-{{ $stageKey }}">{{ $stage['deals']->count() }}</span>
        </div>
        @if($colTotal > 0)
        <div class="xn-kanban-col-total">₹{{ number_format($colTotal, 0) }}</div>
        @endif
        <div class="xn-kanban-cards" id="col-{{ $stageKey }}"
             ondragover="onDragOver(event)" ondragleave="onDragLeave(event)" ondrop="onDrop(event, '{{ $stageKey }}')">
          @forelse($stage['deals'] as $deal)
          @php
            $dealName = $deal->name ?? $deal->title ?? 'Untitled Deal';
            $dealValue = $deal->value ?? $deal->amount ?? 0;
            $dealClose = $deal->expected_close ?? $deal->closing_date;
          @endphp
          <div class="xn-deal-card"
               draggable="true"
               data-id="{{ $deal->id }}"
               data-stage="{{ $stageKey }}"
               ondragstart="onDragStart(event)"
               ondragend="onDragEnd(event)">
            <div class="xn-deal-card-name">
              <a href="{{ route('admin.crm2.sales.deals.show', $deal->id) }}" onclick="event.stopPropagation()">{{ $dealName }}</a>
            </div>
            @if($deal->account)
            <div class="xn-deal-card-account"><i class="fas fa-building" style="font-size:.65rem;margin-right:.25rem"></i>{{ $deal->account->name }}</div>
            @endif
            <div class="xn-deal-card-meta">
              <span class="xn-deal-card-value">{{ $dealValue ? '₹'.number_format($dealValue, 0) : '—' }}</span>
              @if($deal->probability)
              <span class="xn-deal-card-prob">{{ $deal->probability }}%</span>
              @endif
            </div>
            <div class="xn-deal-card-footer">
              @if($dealClose)
              <span><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($dealClose)->format('d M Y') }}</span>
              @endif
              @if($deal->owner)
              <span><i class="fas fa-user"></i> {{ $deal->owner->name }}</span>
              @endif
            </div>
          </div>
          @empty
          <div class="xn-kanban-empty" id="empty-{{ $stageKey }}">
            <i class="fas fa-inbox"></i>
            No deals
          </div>
          @endforelse
        </div>
      </div>
      @endforeach
    </div>
  </div>

</div>{{-- /crm2-page --}}

{{-- Toast --}}
<div id="xnToast"><i class="fas fa-check-circle"></i> <span id="xnToastMsg"></span></div>

{{-- Hidden bulk-delete form --}}
<form id="bulkDeleteForm" method="POST" action="{{ route('admin.crm2.sales.deals.bulk-delete') }}" style="display:none">
  @csrf @method('DELETE')
  <input type="hidden" name="ids" id="bulkDeleteIds">
</form>
{{-- Hidden bulk-task form --}}
<form id="bulkTaskForm" method="POST" action="{{ route('admin.crm2.sales.deals.bulk-task') }}" style="display:none">
  @csrf
  <input type="hidden" name="ids" id="bulkTaskIds">
</form>

<script>
// ── View toggle ──────────────────────────────────────────────────────────
function setView(v) {
  const isKanban = v === 'kanban';
  document.getElementById('listView').style.display   = isKanban ? 'none'  : 'block';
  document.getElementById('kanbanView').style.display = isKanban ? 'block' : 'none';
  document.getElementById('btnListView').classList.toggle('active',   !isKanban);
  document.getElementById('btnKanbanView').classList.toggle('active', isKanban);
  // hide selection badge and bulk menu in kanban (not applicable)
  document.getElementById('selBadge').style.display = isKanban ? 'none' : '';
  localStorage.setItem('dealsView', v);
}
// Restore saved view on load
(function() {
  const saved = localStorage.getItem('dealsView') || 'list';
  if (saved === 'kanban') setView('kanban');
})();

// ── Row navigation (list view) ───────────────────────────────────────────
function rowNav(event, url) {
  if (event.target.tagName === 'INPUT' || event.target.tagName === 'A' || event.target.tagName === 'BUTTON') return;
  window.location.href = url;
}
// ── Select all / individual checkboxes ──────────────────────────────────
function toggleSelectAll(cb) {
  document.querySelectorAll('.deal-cb').forEach(c => c.checked = cb.checked);
  updateSelection();
}
function updateSelection() {
  const checked = document.querySelectorAll('.deal-cb:checked');
  const badge = document.getElementById('selBadge');
  const total = document.querySelectorAll('.deal-cb').length;
  const allCb = document.getElementById('selectAll');
  badge.textContent = checked.length + ' selected';
  badge.classList.toggle('visible', checked.length > 0);
  allCb.indeterminate = checked.length > 0 && checked.length < total;
  allCb.checked = checked.length === total && total > 0;
}
// ── 3-dot bulk menu ──────────────────────────────────────────────────────
function toggleBulkMenu(e) {
  e.stopPropagation();
  document.getElementById('bulkDrop').classList.toggle('open');
}
document.addEventListener('click', function() {
  document.getElementById('bulkDrop').classList.remove('open');
});
function getSelectedIds() {
  return Array.from(document.querySelectorAll('.deal-cb:checked')).map(c => c.value);
}
function bulkDelete() {
  const ids = getSelectedIds();
  if (!ids.length) { alert('Please select at least one deal.'); return; }
  if (!confirm('Delete ' + ids.length + ' selected deal(s)? This cannot be undone.')) return;
  document.getElementById('bulkDeleteIds').value = ids.join(',');
  document.getElementById('bulkDeleteForm').submit();
}
function bulkExport() {
  const ids = getSelectedIds();
  if (!ids.length) { alert('Please select at least one deal to export.'); return; }
  const rows = [['Name','Account','Stage','Value','Probability','Expected Close','Owner','Created']];
  document.querySelectorAll('#dealsTable tbody tr.clickable-row').forEach(tr => {
    const cb = tr.querySelector('.deal-cb');
    if (cb && cb.checked) {
      const cells = tr.querySelectorAll('td');
      rows.push([
        cells[1]?.innerText.trim()||'', cells[2]?.innerText.trim()||'',
        cells[3]?.innerText.trim()||'', cells[4]?.innerText.trim()||'',
        cells[5]?.innerText.trim()||'', cells[6]?.innerText.trim()||'',
        cells[7]?.innerText.trim()||'', cells[8]?.innerText.trim()||'',
      ]);
    }
  });
  const csv = rows.map(r => r.map(v => '"'+v.replace(/"/g,'""')+'"').join(',')).join('\n');
  const blob = new Blob([csv], {type:'text/csv'});
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = 'deals_export_' + new Date().toISOString().slice(0,10) + '.csv';
  a.click();
  document.getElementById('bulkDrop').classList.remove('open');
}
function bulkCreateTask() {
  const ids = getSelectedIds();
  if (!ids.length) { alert('Please select at least one deal.'); return; }
  document.getElementById('bulkTaskIds').value = ids.join(',');
  document.getElementById('bulkTaskForm').submit();
}

// ── Kanban Drag-and-Drop ─────────────────────────────────────────────────
let _dragCard = null;
let _dragOriginStage = null;

function onDragStart(e) {
  _dragCard = e.currentTarget;
  _dragOriginStage = _dragCard.dataset.stage;
  _dragCard.classList.add('dragging');
  e.dataTransfer.effectAllowed = 'move';
  e.dataTransfer.setData('text/plain', _dragCard.dataset.id);
}
function onDragEnd(e) {
  if (_dragCard) _dragCard.classList.remove('dragging');
  document.querySelectorAll('.xn-kanban-cards').forEach(c => c.classList.remove('drag-over'));
  _dragCard = null;
}
function onDragOver(e) {
  e.preventDefault();
  e.dataTransfer.dropEffect = 'move';
  e.currentTarget.classList.add('drag-over');
}
function onDragLeave(e) {
  e.currentTarget.classList.remove('drag-over');
}
function onDrop(e, newStage) {
  e.preventDefault();
  e.currentTarget.classList.remove('drag-over');
  if (!_dragCard) return;
  const dealId = _dragCard.dataset.id;
  const oldStage = _dragOriginStage;
  if (oldStage === newStage) return;

  // Move card in DOM immediately (optimistic UI)
  const targetCol = document.getElementById('col-' + newStage);
  // Remove empty state if present
  const emptyEl = document.getElementById('empty-' + newStage);
  if (emptyEl) emptyEl.remove();
  targetCol.appendChild(_dragCard);
  _dragCard.dataset.stage = newStage;

  // Update counts
  updateColCount(oldStage);
  updateColCount(newStage);

  // AJAX PATCH to update stage
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
  fetch('/admin/deals/' + dealId + '/stage', {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json',
    },
    body: JSON.stringify({ stage: newStage })
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      showToast('Deal moved to ' + newStage.replace(/_/g,' ').replace(/\b\w/g,c=>c.toUpperCase()), 'success');
    } else {
      // Revert on failure
      const origCol = document.getElementById('col-' + oldStage);
      origCol.appendChild(_dragCard);
      _dragCard.dataset.stage = oldStage;
      updateColCount(oldStage);
      updateColCount(newStage);
      showToast('Failed to update stage. Please try again.', 'error');
    }
  })
  .catch(() => {
    // Revert on network error
    const origCol = document.getElementById('col-' + oldStage);
    origCol.appendChild(_dragCard);
    _dragCard.dataset.stage = oldStage;
    updateColCount(oldStage);
    updateColCount(newStage);
    showToast('Network error. Stage not updated.', 'error');
  });
}

function updateColCount(stage) {
  const col = document.getElementById('col-' + stage);
  const cnt = document.getElementById('cnt-' + stage);
  if (col && cnt) {
    cnt.textContent = col.querySelectorAll('.xn-deal-card').length;
    // Show empty state if no cards
    if (col.querySelectorAll('.xn-deal-card').length === 0 && !document.getElementById('empty-' + stage)) {
      const em = document.createElement('div');
      em.className = 'xn-kanban-empty';
      em.id = 'empty-' + stage;
      em.innerHTML = '<i class="fas fa-inbox"></i>No deals';
      col.appendChild(em);
    }
  }
}

// ── Toast ────────────────────────────────────────────────────────────────
function showToast(msg, type) {
  const t = document.getElementById('xnToast');
  document.getElementById('xnToastMsg').textContent = msg;
  t.className = 'show ' + (type || 'success');
  clearTimeout(t._timer);
  t._timer = setTimeout(() => t.className = '', 3000);
}
</script>
@endsection
