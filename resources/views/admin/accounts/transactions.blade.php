@extends('layouts.admin')
@section('title', 'Transactions')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-exchange-alt"></i> Transactions</h1>
      <p class="crm2-subtitle">All income and expense transactions across your accounts.</p>
    </div>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

  {{-- Filter Bar --}}
  <div class="crm2-card mb-4">
    <div class="crm2-card-body">
      <form method="GET" class="crm2-filter-form">
        <div class="filter-group flex-1">
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transactions..." class="crm2-input">
        </div>
        <div class="filter-group">
          <select name="type" class="crm2-select">
            <option value="">All Types</option>
            <option value="credit" {{ request('type')==='credit'?'selected':'' }}>Income</option>
            <option value="debit" {{ request('type')==='debit'?'selected':'' }}>Expense</option>
            <option value="transfer" {{ request('type')==='transfer'?'selected':'' }}>Transfer</option>
          </select>
        </div>
        <div class="filter-group">
          <select name="bank_account_id" class="crm2-select">
            <option value="">All Accounts</option>
            @foreach($bankAccounts as $ba)
            <option value="{{ $ba->id }}" {{ request('bank_account_id')==$ba->id?'selected':'' }}>{{ $ba->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="filter-group">
          <input type="date" name="date_from" value="{{ request('date_from') }}" class="crm2-input" placeholder="From">
        </div>
        <div class="filter-group">
          <input type="date" name="date_to" value="{{ request('date_to') }}" class="crm2-input" placeholder="To">
        </div>
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
            <td style="font-family:monospace;font-size:.78rem;color:var(--text-muted);">{{ $txn->reference_number ?? '—' }}</td>
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
