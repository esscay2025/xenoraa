@extends('layouts.admin')
@section('title', 'Accounts')
@section('page-title', 'Accounts')
@push('styles')
<style>
.acc-kpi-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(170px,1fr)); gap:.75rem; margin-bottom:1rem; }
.acc-kpi-card { background:var(--bg-card,#fff); border:1px solid var(--border,#e2e8f0); border-radius:10px; padding:.9rem 1.1rem; }
.acc-kpi-label { font-size:.72rem; color:var(--text-muted,#64748b); text-transform:uppercase; letter-spacing:.04em; margin-bottom:.25rem; }
.acc-kpi-value { font-size:1.35rem; font-weight:700; color:var(--text-primary,#1a1a2e); line-height:1.2; }
.acc-kpi-sub { font-size:.7rem; color:var(--text-muted,#64748b); margin-top:.2rem; }
.acc-kpi-card.green .acc-kpi-value { color:#22c55e; }
.acc-kpi-card.red .acc-kpi-value { color:#ef4444; }
.acc-kpi-card.blue .acc-kpi-value { color:#3b82f6; }
.acc-kpi-card.amber .acc-kpi-value { color:#f59e0b; }
.acc-kpi-card.indigo .acc-kpi-value { color:#6366f1; }
.acc-charts-row { display:grid; grid-template-columns:2fr 1fr; gap:.75rem; margin-bottom:.75rem; }
.acc-two-col { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; margin-bottom:.75rem; }
.acc-bank-item { display:flex; align-items:center; gap:.7rem; padding:.6rem .8rem; background:var(--bg-secondary,rgba(99,102,241,.04)); border-radius:7px; border:1px solid var(--border,#e2e8f0); margin-bottom:.4rem; }
.acc-bank-icon { width:32px; height:32px; border-radius:7px; background:rgba(99,102,241,.12); display:flex; align-items:center; justify-content:center; color:#6366f1; font-size:.8rem; flex-shrink:0; }
.acc-bank-name { font-size:.8rem; font-weight:600; color:var(--text-primary,#1a1a2e); }
.acc-bank-type { font-size:.7rem; color:var(--text-muted,#64748b); }
.acc-bank-balance { font-size:.85rem; font-weight:700; color:var(--text-primary,#1a1a2e); margin-left:auto; }
.acc-bank-balance.neg { color:#ef4444; }
.crm2-card-title { font-size:.82rem; font-weight:600; color:var(--text-primary,#1a1a2e); display:flex; align-items:center; gap:.4rem; }
@media(max-width:900px){.acc-charts-row,.acc-two-col{grid-template-columns:1fr;}}
</style>
@endpush
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-chart-pie"></i> Accounts</h1>
    </div>
    <div style="display:flex;align-items:center;gap:.6rem;">
      <a href="{{ route('admin.accounts.income') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> Add Income</a>
      <a href="{{ route('admin.accounts.expenses') }}" class="crm2-btn" style="background:#ef4444;color:#fff;border:none;border-radius:7px;padding:.45rem .9rem;font-size:.82rem;cursor:pointer;"><i class="fas fa-minus"></i> Add Expense</a>
    </div>
  </div>

  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  @if(session('error'))<div class="crm2-alert danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif

  {{-- KPI Cards --}}
  <div class="acc-kpi-grid">
    <div class="acc-kpi-card blue">
      <div class="acc-kpi-label"><i class="fas fa-university"></i> Total Cash Balance</div>
      <div class="acc-kpi-value">₹{{ number_format($totalCashBalance, 0) }}</div>
      <div class="acc-kpi-sub">{{ $bankAccountCount }} account(s)</div>
    </div>
    <div class="acc-kpi-card green">
      <div class="acc-kpi-label"><i class="fas fa-arrow-down"></i> Income This Month</div>
      <div class="acc-kpi-value">₹{{ number_format($incomeThisMonth, 0) }}</div>
      <div class="acc-kpi-sub">{{ $incomeThisMonthCount }} entries</div>
    </div>
    <div class="acc-kpi-card red">
      <div class="acc-kpi-label"><i class="fas fa-arrow-up"></i> Expenses This Month</div>
      <div class="acc-kpi-value">₹{{ number_format($expensesThisMonth, 0) }}</div>
      <div class="acc-kpi-sub">{{ $expensesThisMonthCount }} entries</div>
    </div>
    <div class="acc-kpi-card {{ $netProfitThisMonth >= 0 ? 'green' : 'red' }}">
      <div class="acc-kpi-label"><i class="fas fa-chart-bar"></i> Net Profit</div>
      <div class="acc-kpi-value">₹{{ number_format($netProfitThisMonth, 0) }}</div>
      <div class="acc-kpi-sub">This month</div>
    </div>
    <div class="acc-kpi-card amber">
      <div class="acc-kpi-label"><i class="fas fa-file-invoice-dollar"></i> Receivables</div>
      <div class="acc-kpi-value">₹{{ number_format($totalReceivables, 0) }}</div>
      <div class="acc-kpi-sub">Pending income</div>
    </div>
    <div class="acc-kpi-card indigo">
      <div class="acc-kpi-label"><i class="fas fa-credit-card"></i> Payables</div>
      <div class="acc-kpi-value">₹{{ number_format($totalPayables, 0) }}</div>
      <div class="acc-kpi-sub">Pending expenses</div>
    </div>
  </div>

  {{-- Charts Row --}}
  <div class="acc-charts-row">
    <div class="crm2-card">
      <div class="crm2-card-body">
        <div class="crm2-card-title" style="margin-bottom:.6rem;"><i class="fas fa-chart-area" style="color:#6366f1;"></i> Cash Flow — Last 6 Months</div>
        <canvas id="cashFlowChart" height="100"></canvas>
      </div>
    </div>
    <div class="crm2-card">
      <div class="crm2-card-body">
        <div class="crm2-card-title" style="margin-bottom:.6rem;"><i class="fas fa-chart-pie" style="color:#6366f1;"></i> Expenses by Category</div>
        <canvas id="expensePieChart" height="160"></canvas>
      </div>
    </div>
  </div>

  {{-- Bank Accounts + Recent Transactions --}}
  <div class="acc-two-col">
    <div class="crm2-card">
      <div class="crm2-card-body">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.6rem;">
          <span class="crm2-card-title"><i class="fas fa-university" style="color:#6366f1;"></i> Bank Accounts</span>
          <a href="{{ route('admin.accounts.bank-accounts') }}" class="crm2-btn crm2-btn-ghost" style="font-size:.72rem;padding:.2rem .5rem;">View All</a>
        </div>
        @forelse($bankAccounts as $ba)
        <div class="acc-bank-item">
          <div class="acc-bank-icon"><i class="fas fa-{{ $ba->type === 'cash' ? 'wallet' : ($ba->type === 'credit_card' ? 'credit-card' : 'university') }}"></i></div>
          <div>
            <div class="acc-bank-name">{{ $ba->name }}</div>
            <div class="acc-bank-type">{{ ucfirst(str_replace('_',' ',$ba->type)) }}@if($ba->bank_name) · {{ $ba->bank_name }}@endif</div>
          </div>
          <div class="acc-bank-balance {{ $ba->current_balance < 0 ? 'neg' : '' }}">₹{{ number_format($ba->current_balance, 0) }}</div>
        </div>
        @empty
        <div style="color:var(--text-muted,#64748b);font-size:.8rem;padding:.5rem 0;">No bank accounts yet. <a href="{{ route('admin.accounts.bank-accounts') }}" style="color:#6366f1;">Add one</a></div>
        @endforelse
      </div>
    </div>
    <div class="crm2-card">
      <div class="crm2-card-body">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.6rem;">
          <span class="crm2-card-title"><i class="fas fa-exchange-alt" style="color:#6366f1;"></i> Recent Transactions</span>
          <a href="{{ route('admin.accounts.transactions') }}" class="crm2-btn crm2-btn-ghost" style="font-size:.72rem;padding:.2rem .5rem;">View All</a>
        </div>
        <table class="crm2-table">
          <thead><tr><th>Date</th><th>Description</th><th>Amount</th></tr></thead>
          <tbody>
          @forelse($recentTransactions as $txn)
          <tr>
            <td>{{ $txn->transaction_date->format('d M') }}</td>
            <td style="max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $txn->description ?? $txn->category ?? '—' }}</td>
            <td><span class="crm2-badge status-{{ $txn->type === 'credit' ? 'won' : 'lost' }}">{{ $txn->type === 'credit' ? '+' : '-' }}₹{{ number_format($txn->amount, 0) }}</span></td>
          </tr>
          @empty
          <tr><td colspan="3" style="text-align:center;color:var(--text-muted,#64748b);padding:.8rem;">No transactions yet</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Recent Income + Expenses --}}
  <div class="acc-two-col">
    <div class="crm2-card">
      <div class="crm2-card-body">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.6rem;">
          <span class="crm2-card-title"><i class="fas fa-arrow-circle-down" style="color:#22c55e;"></i> Recent Income</span>
          <a href="{{ route('admin.accounts.income') }}" class="crm2-btn crm2-btn-ghost" style="font-size:.72rem;padding:.2rem .5rem;">View All</a>
        </div>
        <table class="crm2-table">
          <thead><tr><th>Date</th><th>Title</th><th>Amount</th><th>Status</th></tr></thead>
          <tbody>
          @forelse($recentIncome as $inc)
          <tr>
            <td style="white-space:nowrap;">{{ $inc->income_date->format('d M') }}</td>
            <td style="max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $inc->title }}</td>
            <td style="color:#22c55e;font-weight:600;white-space:nowrap;">₹{{ number_format($inc->amount, 0) }}</td>
            <td><span class="crm2-badge status-{{ $inc->status === 'received' ? 'won' : 'new' }}">{{ ucfirst($inc->status) }}</span></td>
          </tr>
          @empty
          <tr><td colspan="4" style="color:var(--text-muted,#64748b);text-align:center;padding:.8rem;">No income yet</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="crm2-card">
      <div class="crm2-card-body">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.6rem;">
          <span class="crm2-card-title"><i class="fas fa-arrow-circle-up" style="color:#ef4444;"></i> Recent Expenses</span>
          <a href="{{ route('admin.accounts.expenses') }}" class="crm2-btn crm2-btn-ghost" style="font-size:.72rem;padding:.2rem .5rem;">View All</a>
        </div>
        <table class="crm2-table">
          <thead><tr><th>Date</th><th>Title</th><th>Amount</th><th>Status</th></tr></thead>
          <tbody>
          @forelse($recentExpenses as $exp)
          <tr>
            <td style="white-space:nowrap;">{{ $exp->expense_date->format('d M') }}</td>
            <td style="max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $exp->title }}</td>
            <td style="color:#ef4444;font-weight:600;white-space:nowrap;">₹{{ number_format($exp->amount, 0) }}</td>
            <td><span class="crm2-badge status-{{ $exp->status === 'paid' ? 'won' : 'new' }}">{{ ucfirst($exp->status) }}</span></td>
          </tr>
          @empty
          <tr><td colspan="4" style="color:var(--text-muted,#64748b);text-align:center;padding:.8rem;">No expenses yet</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const isDark = document.documentElement.classList.contains('dark') || document.body.classList.contains('dark-mode');
const gridColor = isDark ? 'rgba(255,255,255,.07)' : 'rgba(0,0,0,.06)';
const textColor = isDark ? '#9ca3af' : '#6b7280';
new Chart(document.getElementById('cashFlowChart'), {
  type: 'bar',
  data: {
    labels: {!! json_encode($cashFlowLabels) !!},
    datasets: [
      { label: 'Income', data: {!! json_encode($cashFlowIncome) !!}, backgroundColor: 'rgba(34,197,94,.7)', borderRadius: 4 },
      { label: 'Expenses', data: {!! json_encode($cashFlowExpenses) !!}, backgroundColor: 'rgba(239,68,68,.7)', borderRadius: 4 }
    ]
  },
  options: { responsive:true, maintainAspectRatio:true,
    plugins:{ legend:{ labels:{ color:textColor, font:{size:11} } } },
    scales:{ x:{ ticks:{color:textColor}, grid:{color:gridColor} }, y:{ ticks:{color:textColor, callback:v=>'₹'+(v>=1000?(v/1000)+'k':v)}, grid:{color:gridColor} } }
  }
});
new Chart(document.getElementById('expensePieChart'), {
  type: 'doughnut',
  data: {
    labels: {!! json_encode($expenseCategoryLabels) !!},
    datasets: [{ data: {!! json_encode($expenseCategoryData) !!}, backgroundColor:['#6366f1','#f59e0b','#22c55e','#ef4444','#3b82f6','#ec4899','#14b8a6','#f97316'], borderWidth:0 }]
  },
  options: { responsive:true, maintainAspectRatio:false,
    plugins:{ legend:{ position:'bottom', labels:{color:textColor, font:{size:10}, boxWidth:10} } }
  }
});
</script>
@endpush
