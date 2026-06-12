@extends('layouts.admin')
@section('title', 'Transactions')
@section('page-title', 'Transactions')
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
      <h1 class="crm2-title"><i class="fas fa-exchange-alt"></i> Transactions</h1>
    </div>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

  {{-- Filter Bar --}}
  <div class="crm2-card mb-4">
    <div class="crm2-card-body">
      <form method="GET" class="crm2-filter-form">
                  <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transactions..." class="crm2-input">
                  <select name="type" class="crm2-select">
            <option value="">All Types</option>
            <option value="credit" {{ request('type')==='credit'?'selected':'' }}>Income</option>
            <option value="debit" {{ request('type')==='debit'?'selected':'' }}>Expense</option>
            <option value="transfer" {{ request('type')==='transfer'?'selected':'' }}>Transfer</option>
          </select>
                  <select name="bank_account_id" class="crm2-select">
            <option value="">All Accounts</option>
            @foreach($bankAccounts as $ba)
            <option value="{{ $ba->id }}" {{ request('bank_account_id')==$ba->id?'selected':'' }}>{{ $ba->name }}</option>
            @endforeach
          </select>
                  <input type="date" name="date_from" value="{{ request('date_from') }}" class="crm2-input" placeholder="From">
                  <input type="date" name="date_to" value="{{ request('date_to') }}" class="crm2-input" placeholder="To">
        <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('admin.accounts.transactions') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
      </form>
    </div>
  </div>

  {{-- Transactions Table --}}
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table" id="txnTable">
        <thead>
          <tr>
            <th>Date</th>
            <th>Reference</th>
            <th>Description</th>
            <th>Account</th>
            <th>Category</th>
            <th>Type</th>
            <th style="text-align:right;">Amount</th>
          </tr>
        </thead>
        <tbody>
          @forelse($transactions as $txn)
          <tr>
            <td>{{ $txn->transaction_date->format('d M Y') }}</td>
            <td style="font-family:monospace;font-size:.78rem;color:var(--text-muted,#64748b);">{{ $txn->reference_number ?? '—' }}</td>
            <td>{{ $txn->description ?? '—' }}</td>
            <td>{{ $txn->bankAccount?->name ?? '—' }}</td>
            <td>{{ $txn->category ?? '—' }}</td>
            <td>
              @if($txn->type === 'credit')
                <span class="crm2-badge status-won">Income</span>
              @elseif($txn->type === 'debit')
                <span class="crm2-badge status-lost">Expense</span>
              @else
                <span class="crm2-badge status-new">Transfer</span>
              @endif
            </td>
            <td style="text-align:right;font-weight:600;color:{{ $txn->type==='credit'?'#22c55e':'#ef4444' }};">
              {{ $txn->type === 'credit' ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}
            </td>
          </tr>
          @empty
          <tr><td colspan="7"><div class="crm2-empty"><i class="fas fa-exchange-alt"></i><p>No transactions found.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($transactions->hasPages())<div class="crm2-pagination">{{ $transactions->links() }}</div>@endif
  </div>
</div>
@endsection
