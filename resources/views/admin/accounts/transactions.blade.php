@extends('layouts.admin')
@section('title', 'Transactions')
@push('styles')
<style>
.acc-page{padding:1.5rem 2rem;}
.acc-page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;flex-wrap:wrap;gap:.75rem;}
.acc-page-title{font-size:1.5rem;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:.6rem;}
.acc-page-title i{color:#6366f1;}
.acc-filters{display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1.2rem;align-items:center;}
.acc-filter-select,.acc-filter-input{padding:.4rem .75rem;border-radius:7px;border:1px solid var(--border-color);background:var(--card-bg);color:var(--text-primary);font-size:.82rem;}
.acc-card{background:var(--card-bg);border:1px solid var(--border-color);border-radius:12px;overflow:hidden;}
.acc-table{width:100%;border-collapse:collapse;font-size:.83rem;}
.acc-table th{padding:.65rem 1rem;text-align:left;color:var(--text-muted);font-weight:600;font-size:.72rem;text-transform:uppercase;border-bottom:1px solid var(--border-color);background:var(--bg-secondary,rgba(255,255,255,.02));}
.acc-table td{padding:.7rem 1rem;border-bottom:1px solid var(--border-color);color:var(--text-primary);}
.acc-table tr:last-child td{border-bottom:none;}
.acc-table tr:hover td{background:rgba(99,102,241,.04);}
.acc-badge{display:inline-flex;align-items:center;padding:.2rem .6rem;border-radius:20px;font-size:.7rem;font-weight:600;}
.acc-badge.credit{background:rgba(34,197,94,.15);color:#22c55e;}
.acc-badge.debit{background:rgba(239,68,68,.15);color:#ef4444;}
.acc-badge.reconciled{background:rgba(99,102,241,.15);color:#6366f1;}
.acc-amount-credit{color:#22c55e;font-weight:700;}
.acc-amount-debit{color:#ef4444;font-weight:700;}
.acc-add-row{background:var(--card-bg);border:1px solid var(--border-color);border-radius:12px;padding:1.2rem 1.4rem;margin-bottom:1rem;}
.acc-add-row-title{font-size:.85rem;font-weight:600;color:var(--text-primary);margin-bottom:.8rem;}
.acc-inline-form{display:flex;gap:.6rem;flex-wrap:wrap;align-items:flex-end;}
.acc-form-group-inline{display:flex;flex-direction:column;gap:.25rem;}
.acc-form-label{font-size:.72rem;font-weight:600;color:var(--text-muted);}
.acc-form-control{padding:.42rem .7rem;border-radius:7px;border:1px solid var(--border-color);background:var(--input-bg,var(--card-bg));color:var(--text-primary);font-size:.82rem;}
.acc-form-control:focus{outline:none;border-color:#6366f1;}
.acc-btn-primary{padding:.42rem 1rem;border-radius:7px;background:#6366f1;color:#fff;border:none;font-size:.82rem;font-weight:600;cursor:pointer;}
</style>
@endpush

@section('content')
<div class="acc-page">
    <div class="acc-page-header">
        <div class="acc-page-title"><i class="fas fa-exchange-alt"></i> Transactions</div>
    </div>

    {{-- Quick Add Transaction --}}
    <div class="acc-add-row">
        <div class="acc-add-row-title">Record Transaction</div>
        <form method="POST" action="{{ route('admin.accounts.transactions.store') }}">
            @csrf
            <div class="acc-inline-form">
                <div class="acc-form-group-inline">
                    <label class="acc-form-label">Bank Account</label>
                    <select name="bank_account_id" class="acc-form-control" required>
                        <option value="">Select Account</option>
                        @foreach($bankAccounts as $ba)
                        <option value="{{ $ba->id }}" {{ request('bank_account_id') == $ba->id ? 'selected' : '' }}>{{ $ba->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="acc-form-group-inline">
                    <label class="acc-form-label">Type</label>
                    <select name="type" class="acc-form-control" required>
                        <option value="credit">Money In (Credit)</option>
                        <option value="debit">Money Out (Debit)</option>
                    </select>
                </div>
                <div class="acc-form-group-inline">
                    <label class="acc-form-label">Amount</label>
                    <input type="number" name="amount" class="acc-form-control" placeholder="0.00" step="0.01" required style="width:110px;">
                </div>
                <div class="acc-form-group-inline">
                    <label class="acc-form-label">Date</label>
                    <input type="date" name="transaction_date" class="acc-form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="acc-form-group-inline">
                    <label class="acc-form-label">Description</label>
                    <input type="text" name="description" class="acc-form-control" placeholder="Description" style="width:180px;">
                </div>
                <div class="acc-form-group-inline">
                    <label class="acc-form-label">Category</label>
                    <input type="text" name="category" class="acc-form-control" placeholder="Category" style="width:130px;">
                </div>
                <div class="acc-form-group-inline">
                    <label class="acc-form-label">Payee</label>
                    <input type="text" name="payee" class="acc-form-control" placeholder="Payee" style="width:130px;">
                </div>
                <div class="acc-form-group-inline" style="justify-content:flex-end;">
                    <label class="acc-form-label">&nbsp;</label>
                    <button type="submit" class="acc-btn-primary"><i class="fas fa-plus"></i> Add</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Filters --}}
    <form method="GET" class="acc-filters">
        <select name="bank_account_id" class="acc-filter-select" onchange="this.form.submit()">
            <option value="">All Accounts</option>
            @foreach($bankAccounts as $ba)
            <option value="{{ $ba->id }}" {{ request('bank_account_id') == $ba->id ? 'selected' : '' }}>{{ $ba->name }}</option>
            @endforeach
        </select>
        <select name="type" class="acc-filter-select" onchange="this.form.submit()">
            <option value="">All Types</option>
            <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Credit (In)</option>
            <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Debit (Out)</option>
        </select>
        <input type="date" name="date_from" class="acc-filter-input" value="{{ request('date_from') }}" placeholder="From">
        <input type="date" name="date_to" class="acc-filter-input" value="{{ request('date_to') }}" placeholder="To">
        <button type="submit" class="acc-btn-primary" style="padding:.4rem .9rem;font-size:.8rem;">Filter</button>
        <a href="{{ route('admin.accounts.transactions') }}" style="font-size:.8rem;color:var(--text-muted);align-self:center;">Clear</a>
    </form>

    {{-- Table --}}
    <div class="acc-card">
        <table class="acc-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Account</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Payee</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Reconciled</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $txn)
                <tr>
                    <td>{{ $txn->transaction_date->format('d M Y') }}</td>
                    <td style="font-size:.75rem;color:var(--text-muted);">{{ $txn->reference_number ?? '—' }}</td>
                    <td>{{ $txn->bankAccount->name ?? '—' }}</td>
                    <td>{{ $txn->description ?? '—' }}</td>
                    <td>{{ $txn->category ?? '—' }}</td>
                    <td>{{ $txn->payee ?? '—' }}</td>
                    <td><span class="acc-badge {{ $txn->type }}">{{ ucfirst($txn->type) }}</span></td>
                    <td class="acc-amount-{{ $txn->type }}">{{ $txn->type === 'credit' ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}</td>
                    <td>@if($txn->is_reconciled)<span class="acc-badge reconciled"><i class="fas fa-check"></i> Yes</span>@else<span style="color:var(--text-muted);font-size:.75rem;">No</span>@endif</td>
                    <td>
                        <form method="POST" action="{{ route('admin.accounts.transactions.delete', $txn->id) }}" onsubmit="return confirm('Delete?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:.8rem;"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" style="text-align:center;padding:2rem;color:var(--text-muted);">No transactions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">{{ $transactions->appends(request()->query())->links() }}</div>
</div>
@endsection
