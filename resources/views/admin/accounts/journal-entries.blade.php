@extends('layouts.admin')
@section('title', 'Journal Entries')
@section('page-title', 'Journal Entries')
@push('styles')
<style>
.crm2-table th.cb-col, .crm2-table td.cb-col { width: 38px; padding: 0 0 0 14px; text-align: center; }
.crm2-table input[type=checkbox] { width: 15px; height: 15px; accent-color: var(--accent,#6366f1); cursor: pointer; }
.crm2-table tbody tr.clickable-row { cursor: pointer; }
.crm2-table tbody tr.clickable-row:hover { background: var(--bg-hover, rgba(99,102,241,.06)); }
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

.crm2-table th { white-space: nowrap; }
.crm2-table td { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; }
.crm2-table td.wrap { white-space: normal; }
</style>
@endpush
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-book"></i> Journal Entries</h1>
    </div>
    <button class="crm2-btn crm2-btn-primary" onclick="document.getElementById('addJournalModal').style.display='flex'"><i class="fas fa-plus"></i> New Entry</button>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

  {{-- Filter Bar --}}
  <div class="crm2-card mb-4">
    <div class="crm2-card-body">
      <form method="GET" class="crm2-filter-form">
                  <input type="text" name="search" value="{{ request('search') }}" placeholder="Search entries..." class="crm2-input">
                  <select name="status" class="crm2-select">
            <option value="">All Status</option>
            <option value="draft" {{ request('status')==='draft'?'selected':'' }}>Draft</option>
            <option value="posted" {{ request('status')==='posted'?'selected':'' }}>Posted</option>
            <option value="void" {{ request('status')==='void'?'selected':'' }}>Void</option>
          </select>
                  <input type="date" name="date_from" value="{{ request('date_from') }}" class="crm2-input">
        </div>
        <div class="filter-group">
          <input type="date" name="date_to" value="{{ request('date_to') }}" class="crm2-input">
        </div>
        <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('admin.accounts.journal') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
      </form>
    </div>
  </div>

  {{-- Journal Entries Table --}}
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Reference</th>
            <th>Description</th>
            <th>Lines</th>
            <th>Status</th>
            <th style="text-align:right;">Total Debit</th>
            <th style="text-align:right;">Total Credit</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($journalEntries as $entry)
          <tr>
            <td>{{ $entry->entry_date->format('d M Y') }}</td>
            <td style="font-family:monospace;font-size:.78rem;color:var(--text-muted,#64748b);">{{ $entry->reference_number }}</td>
            <td style="font-weight:500;">{{ $entry->description }}</td>
            <td>{{ $entry->lines->count() }}</td>
            <td>
              <span class="crm2-badge status-{{ $entry->status === 'posted' ? 'won' : ($entry->status === 'void' ? 'lost' : 'new') }}">
                {{ ucfirst($entry->status) }}
              </span>
            </td>
            <td style="text-align:right;font-weight:600;color:#3b82f6;">₹{{ number_format($entry->lines->sum('debit_amount'), 2) }}</td>
            <td style="text-align:right;font-weight:600;color:#22c55e;">₹{{ number_format($entry->lines->sum('credit_amount'), 2) }}</td>
            <td>
              <div style="display:flex;gap:.3rem;justify-content:flex-end;">
                <button class="crm2-btn crm2-btn-ghost" style="padding:.25rem .5rem;font-size:.75rem;" onclick="viewJournalLines({{ $entry->id }})"><i class="fas fa-eye"></i></button>
                @if($entry->status === 'draft')
                <form method="POST" action="{{ route('admin.accounts.journal.delete', $entry->id) }}" onsubmit="return confirm('Delete this entry?')" style="display:inline">
                  @csrf @method('DELETE')
                  <button type="submit" class="crm2-btn" style="padding:.25rem .5rem;font-size:.75rem;background:#ef4444;color:#fff;border:none;"><i class="fas fa-trash"></i></button>
                </form>
                @endif
              </div>
            </td>
          </tr>
          {{-- Expandable lines row --}}
          <tr id="lines-{{ $entry->id }}" style="display:none;background:var(--bg-secondary,rgba(0,0,0,.02));">
            <td colspan="8" style="padding:.5rem 1.5rem 1rem;">
              <table class="crm2-table" style="font-size:.78rem;">
                <thead><tr><th>Account</th><th>Description</th><th style="text-align:right;">Debit</th><th style="text-align:right;">Credit</th></tr></thead>
                <tbody>
                  @foreach($entry->lines as $line)
                  <tr>
                    <td>{{ $line->chartOfAccount?->name ?? $line->account_name }}</td>
                    <td>{{ $line->description ?? '—' }}</td>
                    <td style="text-align:right;color:#3b82f6;">{{ $line->debit_amount ? '₹'.number_format($line->debit_amount,2) : '—' }}</td>
                    <td style="text-align:right;color:#22c55e;">{{ $line->credit_amount ? '₹'.number_format($line->credit_amount,2) : '—' }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </td>
          </tr>
          @empty
          <tr><td colspan="8"><div class="crm2-empty"><i class="fas fa-book"></i><p>No journal entries found.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($journalEntries->hasPages())<div class="crm2-pagination">{{ $journalEntries->links() }}</div>@endif
  </div>
</div>

{{-- Add Journal Entry Modal --}}
<div id="addJournalModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;overflow-y:auto;">
  <div class="crm2-card" style="width:100%;max-width:640px;margin:1rem;">
    <div class="crm2-card-body">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
        <h3 class="crm2-title" style="font-size:1.1rem;margin:0;"><i class="fas fa-book"></i> New Journal Entry</h3>
        <button onclick="document.getElementById('addJournalModal').style.display='none'" style="background:none;border:none;color:var(--text-muted,#64748b);font-size:1.2rem;cursor:pointer;"><i class="fas fa-times"></i></button>
      </div>
      <form method="POST" action="{{ route('admin.accounts.journal.store') }}">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;margin-bottom:.8rem;">
          <div class="crm2-form-group">
            <label class="crm2-label">Date *</label>
            <input type="date" name="entry_date" class="crm2-input" required value="{{ date('Y-m-d') }}">
          </div>
          <div class="crm2-form-group">
            <label class="crm2-label">Status</label>
            <select name="status" class="crm2-select">
              <option value="draft">Draft</option>
              <option value="posted">Posted</option>
            </select>
          <div class="crm2-form-group" style="grid-column:1/-1;">
            <label class="crm2-label">Description *</label>
            <input type="text" name="description" class="crm2-input" required placeholder="e.g. Monthly rent payment">
          </div>
        </div>
        <div style="font-size:.8rem;font-weight:600;color:var(--text-primary,#1a1a2e);margin-bottom:.5rem;">Journal Lines</div>
        <div id="journalLines">
          <div style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:.5rem;margin-bottom:.4rem;" class="journal-line">
            <input type="text" name="lines[0][account_name]" class="crm2-input" placeholder="Account name" required>
            <input type="number" name="lines[0][debit_amount]" class="crm2-input" placeholder="Debit ₹" step="0.01" min="0">
            <input type="number" name="lines[0][credit_amount]" class="crm2-input" placeholder="Credit ₹" step="0.01" min="0">
            <button type="button" onclick="removeLine(this)" class="crm2-btn" style="padding:.3rem .5rem;background:#ef4444;color:#fff;border:none;"><i class="fas fa-times"></i></button>
          </div>
          <div style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:.5rem;margin-bottom:.4rem;" class="journal-line">
            <input type="text" name="lines[1][account_name]" class="crm2-input" placeholder="Account name" required>
            <input type="number" name="lines[1][debit_amount]" class="crm2-input" placeholder="Debit ₹" step="0.01" min="0">
            <input type="number" name="lines[1][credit_amount]" class="crm2-input" placeholder="Credit ₹" step="0.01" min="0">
            <button type="button" onclick="removeLine(this)" class="crm2-btn" style="padding:.3rem .5rem;background:#ef4444;color:#fff;border:none;"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <button type="button" onclick="addLine()" class="crm2-btn crm2-btn-ghost" style="font-size:.78rem;margin-bottom:.8rem;"><i class="fas fa-plus"></i> Add Line</button>
        <div style="display:flex;gap:.6rem;justify-content:flex-end;margin-top:.5rem;">
          <button type="button" onclick="document.getElementById('addJournalModal').style.display='none'" class="crm2-btn crm2-btn-ghost">Cancel</button>
          <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Entry</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
let lineCount = 2;
function addLine() {
  const container = document.getElementById('journalLines');
  const div = document.createElement('div');
  div.className = 'journal-line';
  div.style.cssText = 'display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:.5rem;margin-bottom:.4rem;';
  div.innerHTML = `<input type="text" name="lines[${lineCount}][account_name]" class="crm2-input" placeholder="Account name" required>
    <input type="number" name="lines[${lineCount}][debit_amount]" class="crm2-input" placeholder="Debit ₹" step="0.01" min="0">
    <input type="number" name="lines[${lineCount}][credit_amount]" class="crm2-input" placeholder="Credit ₹" step="0.01" min="0">
    <button type="button" onclick="removeLine(this)" class="crm2-btn" style="padding:.3rem .5rem;background:#ef4444;color:#fff;border:none;"><i class="fas fa-times"></i></button>`;
  container.appendChild(div);
  lineCount++;
}
function removeLine(btn) {
  const lines = document.querySelectorAll('.journal-line');
  if (lines.length > 2) btn.closest('.journal-line').remove();
}
function viewJournalLines(id) {
  const row = document.getElementById('lines-' + id);
  if (row) row.style.display = row.style.display === 'none' ? '' : 'none';
}
</script>
@endpush
