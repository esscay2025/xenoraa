@extends('layouts.admin')
@section('title', 'Journal Entries')
@push('styles')
<style>
.acc-page{padding:1.5rem 2rem;}
.acc-page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;flex-wrap:wrap;gap:.75rem;}
.acc-page-title{font-size:1.5rem;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:.6rem;}
.acc-page-title i{color:#8b5cf6;}
.acc-card{background:var(--card-bg);border:1px solid var(--border-color);border-radius:12px;overflow:hidden;margin-bottom:1.2rem;}
.acc-table{width:100%;border-collapse:collapse;font-size:.83rem;}
.acc-table th{padding:.65rem 1rem;text-align:left;color:var(--text-muted);font-weight:600;font-size:.72rem;text-transform:uppercase;border-bottom:1px solid var(--border-color);background:var(--bg-secondary,rgba(255,255,255,.02));}
.acc-table td{padding:.7rem 1rem;border-bottom:1px solid var(--border-color);color:var(--text-primary);}
.acc-table tr:last-child td{border-bottom:none;}
.acc-table tr:hover td{background:rgba(139,92,246,.04);}
.acc-badge{display:inline-flex;align-items:center;padding:.2rem .6rem;border-radius:20px;font-size:.7rem;font-weight:600;}
.acc-badge.draft{background:rgba(107,114,128,.15);color:#6b7280;}
.acc-badge.posted{background:rgba(34,197,94,.15);color:#22c55e;}
.acc-badge.unbalanced{background:rgba(239,68,68,.15);color:#ef4444;}
/* Journal Form */
.acc-je-form{background:var(--card-bg);border:1px solid var(--border-color);border-radius:12px;padding:1.4rem;margin-bottom:1.2rem;}
.acc-je-form-header{display:flex;align-items:center;gap:.6rem;margin-bottom:1rem;font-size:.9rem;font-weight:700;color:var(--text-primary);}
.acc-je-meta{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:.8rem;margin-bottom:1rem;}
.acc-form-group{display:flex;flex-direction:column;gap:.3rem;}
.acc-form-label{font-size:.78rem;font-weight:600;color:var(--text-muted);}
.acc-form-control{padding:.5rem .75rem;border-radius:7px;border:1px solid var(--border-color);background:var(--input-bg,var(--card-bg));color:var(--text-primary);font-size:.85rem;width:100%;}
.acc-form-control:focus{outline:none;border-color:#8b5cf6;}
.acc-je-lines-table{width:100%;border-collapse:collapse;font-size:.82rem;margin-bottom:.8rem;}
.acc-je-lines-table th{padding:.5rem .75rem;text-align:left;color:var(--text-muted);font-weight:600;font-size:.72rem;border-bottom:1px solid var(--border-color);}
.acc-je-lines-table td{padding:.4rem .5rem;}
.acc-je-lines-table input,.acc-je-lines-table select{padding:.38rem .6rem;border-radius:6px;border:1px solid var(--border-color);background:var(--input-bg,var(--card-bg));color:var(--text-primary);font-size:.8rem;width:100%;}
.acc-je-totals{display:flex;justify-content:flex-end;gap:2rem;font-size:.85rem;font-weight:600;padding:.6rem 1rem;background:var(--bg-secondary,rgba(255,255,255,.03));border-radius:8px;margin-bottom:.8rem;}
.acc-je-totals span{color:var(--text-muted);}
.acc-je-totals strong{color:var(--text-primary);}
.acc-btn-primary{padding:.5rem 1.2rem;border-radius:7px;background:#8b5cf6;color:#fff;border:none;font-size:.85rem;font-weight:600;cursor:pointer;}
.acc-btn-secondary{padding:.5rem 1rem;border-radius:7px;background:transparent;color:var(--text-muted);border:1px solid var(--border-color);font-size:.82rem;cursor:pointer;}
.acc-btn-add-line{padding:.35rem .8rem;border-radius:6px;background:rgba(139,92,246,.12);color:#8b5cf6;border:1px solid rgba(139,92,246,.3);font-size:.78rem;cursor:pointer;}
</style>
@endpush

@section('content')
<div class="acc-page">
    <div class="acc-page-header">
        <div class="acc-page-title"><i class="fas fa-book"></i> Journal Entries</div>
    </div>

    {{-- New Journal Entry Form --}}
    <div class="acc-je-form">
        <div class="acc-je-form-header"><i class="fas fa-plus-circle" style="color:#8b5cf6;"></i> New Journal Entry</div>
        <form method="POST" action="{{ route('admin.accounts.journal.store') }}" id="jeForm">
            @csrf
            <div class="acc-je-meta">
                <div class="acc-form-group">
                    <label class="acc-form-label">Date *</label>
                    <input type="date" name="entry_date" class="acc-form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Narration</label>
                    <input type="text" name="narration" class="acc-form-control" placeholder="Journal description">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Reference</label>
                    <input type="text" name="reference" class="acc-form-control" placeholder="External ref">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Status</label>
                    <select name="status" class="acc-form-control">
                        <option value="draft">Draft</option>
                        <option value="posted">Posted</option>
                    </select>
                </div>
            </div>

            <table class="acc-je-lines-table">
                <thead><tr><th style="width:35%">Account</th><th>Description</th><th style="width:130px">Debit (₹)</th><th style="width:130px">Credit (₹)</th><th style="width:40px"></th></tr></thead>
                <tbody id="jeLines">
                    <tr class="je-line">
                        <td><select name="lines[0][chart_account_id]" class="acc-form-control" required>
                            <option value="">Select Account</option>
                            @foreach($chartAccounts as $ca)
                            <option value="{{ $ca->id }}">[{{ strtoupper($ca->type) }}] {{ $ca->name }}</option>
                            @endforeach
                        </select></td>
                        <td><input type="text" name="lines[0][description]" placeholder="Description"></td>
                        <td><input type="number" name="lines[0][debit]" step="0.01" value="0" oninput="updateTotals()"></td>
                        <td><input type="number" name="lines[0][credit]" step="0.01" value="0" oninput="updateTotals()"></td>
                        <td></td>
                    </tr>
                    <tr class="je-line">
                        <td><select name="lines[1][chart_account_id]" class="acc-form-control" required>
                            <option value="">Select Account</option>
                            @foreach($chartAccounts as $ca)
                            <option value="{{ $ca->id }}">[{{ strtoupper($ca->type) }}] {{ $ca->name }}</option>
                            @endforeach
                        </select></td>
                        <td><input type="text" name="lines[1][description]" placeholder="Description"></td>
                        <td><input type="number" name="lines[1][debit]" step="0.01" value="0" oninput="updateTotals()"></td>
                        <td><input type="number" name="lines[1][credit]" step="0.01" value="0" oninput="updateTotals()"></td>
                        <td><button type="button" onclick="removeLine(this)" style="background:none;border:none;color:#ef4444;cursor:pointer;"><i class="fas fa-times"></i></button></td>
                    </tr>
                </tbody>
            </table>

            <div style="display:flex;gap:.6rem;margin-bottom:.8rem;">
                <button type="button" class="acc-btn-add-line" onclick="addLine()"><i class="fas fa-plus"></i> Add Line</button>
            </div>

            <div class="acc-je-totals">
                <div><span>Total Debit: </span><strong id="totalDebit">₹0.00</strong></div>
                <div><span>Total Credit: </span><strong id="totalCredit">₹0.00</strong></div>
                <div id="balanceStatus" style="color:#22c55e;font-size:.8rem;"><i class="fas fa-check-circle"></i> Balanced</div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:.6rem;">
                <button type="submit" class="acc-btn-primary"><i class="fas fa-save"></i> Save Journal Entry</button>
            </div>
        </form>
    </div>

    {{-- Journal Entries List --}}
    <div class="acc-card">
        <table class="acc-table">
            <thead><tr><th>Date</th><th>Number</th><th>Narration</th><th>Reference</th><th>Total Debit</th><th>Total Credit</th><th>Status</th><th>Lines</th><th></th></tr></thead>
            <tbody>
                @forelse($journalEntries as $je)
                <tr>
                    <td>{{ $je->entry_date->format('d M Y') }}</td>
                    <td style="font-size:.75rem;color:var(--text-muted);">{{ $je->journal_number ?? '—' }}</td>
                    <td>{{ $je->narration ?? '—' }}</td>
                    <td>{{ $je->reference ?? '—' }}</td>
                    <td style="font-weight:600;">₹{{ number_format($je->total_debit, 2) }}</td>
                    <td style="font-weight:600;">₹{{ number_format($je->total_credit, 2) }}</td>
                    <td>
                        <span class="acc-badge {{ $je->status }}">{{ ucfirst($je->status) }}</span>
                        @if(!$je->isBalanced())<span class="acc-badge unbalanced" style="margin-left:.3rem;">Unbalanced</span>@endif
                    </td>
                    <td>{{ $je->lines->count() }} lines</td>
                    <td>
                        <form method="POST" action="{{ route('admin.accounts.journal.delete', $je->id) }}" onsubmit="return confirm('Delete?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:.8rem;"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align:center;padding:2rem;color:var(--text-muted);">No journal entries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">{{ $journalEntries->links() }}</div>
</div>
@endsection

@push('scripts')
<script>
let lineIndex = 2;
const accountOptions = `@foreach($chartAccounts as $ca)<option value="{{ $ca->id }}">[{{ strtoupper($ca->type) }}] {{ addslashes($ca->name) }}</option>@endforeach`;

function addLine() {
    const tbody = document.getElementById('jeLines');
    const tr = document.createElement('tr');
    tr.className = 'je-line';
    tr.innerHTML = `
        <td><select name="lines[${lineIndex}][chart_account_id]" class="acc-form-control" required>
            <option value="">Select Account</option>${accountOptions}
        </select></td>
        <td><input type="text" name="lines[${lineIndex}][description]" placeholder="Description"></td>
        <td><input type="number" name="lines[${lineIndex}][debit]" step="0.01" value="0" oninput="updateTotals()"></td>
        <td><input type="number" name="lines[${lineIndex}][credit]" step="0.01" value="0" oninput="updateTotals()"></td>
        <td><button type="button" onclick="removeLine(this)" style="background:none;border:none;color:#ef4444;cursor:pointer;"><i class="fas fa-times"></i></button></td>`;
    tbody.appendChild(tr);
    lineIndex++;
}

function removeLine(btn) {
    const lines = document.querySelectorAll('.je-line');
    if (lines.length <= 2) return;
    btn.closest('tr').remove();
    updateTotals();
}

function updateTotals() {
    let totalD = 0, totalC = 0;
    document.querySelectorAll('.je-line').forEach(row => {
        totalD += parseFloat(row.querySelector('input[name*="[debit]"]').value) || 0;
        totalC += parseFloat(row.querySelector('input[name*="[credit]"]').value') || 0;
    });
    document.getElementById('totalDebit').textContent = '₹' + totalD.toFixed(2);
    document.getElementById('totalCredit').textContent = '₹' + totalC.toFixed(2);
    const balanced = Math.abs(totalD - totalC) < 0.01;
    const el = document.getElementById('balanceStatus');
    el.innerHTML = balanced ? '<i class="fas fa-check-circle"></i> Balanced' : '<i class="fas fa-exclamation-circle"></i> Unbalanced';
    el.style.color = balanced ? '#22c55e' : '#ef4444';
}
</script>
@endpush
