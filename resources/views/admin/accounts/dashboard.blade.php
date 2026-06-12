@extends('layouts.admin')

@section('title', 'Accounts — Dashboard')

@push('styles')
<style>
.acc-page { padding: 1.5rem 2rem; }
.acc-page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:.75rem; }
.acc-page-title { font-size:1.5rem; font-weight:700; color:var(--text-primary); display:flex; align-items:center; gap:.6rem; }
.acc-page-title i { color:#6366f1; }
.acc-kpi-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:1rem; margin-bottom:1.5rem; }
.acc-kpi-card { background:var(--card-bg); border:1px solid var(--border-color); border-radius:12px; padding:1.1rem 1.3rem; display:flex; flex-direction:column; gap:.3rem; }
.acc-kpi-label { font-size:.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.04em; }
.acc-kpi-value { font-size:1.5rem; font-weight:700; color:var(--text-primary); }
.acc-kpi-sub { font-size:.75rem; color:var(--text-muted); }
.acc-kpi-card.green .acc-kpi-value { color:#22c55e; }
.acc-kpi-card.red .acc-kpi-value { color:#ef4444; }
.acc-kpi-card.blue .acc-kpi-value { color:#3b82f6; }
.acc-kpi-card.amber .acc-kpi-value { color:#f59e0b; }
.acc-kpi-card.indigo .acc-kpi-value { color:#6366f1; }
.acc-charts-row { display:grid; grid-template-columns:2fr 1fr; gap:1rem; margin-bottom:1.5rem; }
.acc-card { background:var(--card-bg); border:1px solid var(--border-color); border-radius:12px; padding:1.2rem 1.4rem; }
.acc-card-title { font-size:.85rem; font-weight:600; color:var(--text-primary); margin-bottom:1rem; display:flex; align-items:center; gap:.5rem; }
.acc-card-title i { color:#6366f1; }
.acc-table { width:100%; border-collapse:collapse; font-size:.82rem; }
.acc-table th { padding:.5rem .75rem; text-align:left; color:var(--text-muted); font-weight:600; font-size:.72rem; text-transform:uppercase; border-bottom:1px solid var(--border-color); }
.acc-table td { padding:.6rem .75rem; border-bottom:1px solid var(--border-color); color:var(--text-primary); }
.acc-table tr:last-child td { border-bottom:none; }
.acc-badge { display:inline-flex; align-items:center; padding:.2rem .6rem; border-radius:20px; font-size:.7rem; font-weight:600; }
.acc-badge.credit { background:rgba(34,197,94,.15); color:#22c55e; }
.acc-badge.debit  { background:rgba(239,68,68,.15); color:#ef4444; }
.acc-badge.received  { background:rgba(34,197,94,.15); color:#22c55e; }
.acc-badge.pending   { background:rgba(245,158,11,.15); color:#f59e0b; }
.acc-badge.paid      { background:rgba(34,197,94,.15); color:#22c55e; }
.acc-two-col { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.5rem; }
.acc-bank-list { display:flex; flex-direction:column; gap:.6rem; }
.acc-bank-item { display:flex; align-items:center; gap:.8rem; padding:.75rem 1rem; background:var(--bg-secondary,rgba(255,255,255,.03)); border-radius:8px; border:1px solid var(--border-color); }
.acc-bank-icon { width:38px; height:38px; border-radius:8px; background:rgba(99,102,241,.15); display:flex; align-items:center; justify-content:center; color:#6366f1; font-size:.9rem; flex-shrink:0; }
.acc-bank-info { flex:1; min-width:0; }
.acc-bank-name { font-size:.82rem; font-weight:600; color:var(--text-primary); }
.acc-bank-type { font-size:.72rem; color:var(--text-muted); }
.acc-bank-balance { font-size:.9rem; font-weight:700; color:var(--text-primary); }
.acc-bank-balance.neg { color:#ef4444; }
@media(max-width:900px){.acc-charts-row,.acc-two-col{grid-template-columns:1fr;}}
</style>
@endpush

@section('content')
<div class="acc-page">
    {{-- Header --}}
    <div class="acc-page-header">
        <div class="acc-page-title"><i class="fas fa-chart-line"></i> Accounts Overview</div>
        <div style="display:flex;gap:.6rem;flex-wrap:wrap;">
            <a href="{{ route('admin.accounts.income') }}" class="xn-btn xn-btn-sm" style="background:#22c55e;color:#fff;border:none;"><i class="fas fa-plus"></i> Add Income</a>
            <a href="{{ route('admin.accounts.expenses') }}" class="xn-btn xn-btn-sm" style="background:#ef4444;color:#fff;border:none;"><i class="fas fa-minus"></i> Add Expense</a>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="acc-kpi-grid">
        <div class="acc-kpi-card blue">
            <div class="acc-kpi-label"><i class="fas fa-university"></i> Total Cash Balance</div>
            <div class="acc-kpi-value">₹{{ number_format($totalCashBalance, 2) }}</div>
            <div class="acc-kpi-sub">Across {{ $bankAccountCount }} account(s)</div>
        </div>
        <div class="acc-kpi-card green">
            <div class="acc-kpi-label"><i class="fas fa-arrow-down"></i> Income This Month</div>
            <div class="acc-kpi-value">₹{{ number_format($incomeThisMonth, 2) }}</div>
            <div class="acc-kpi-sub">{{ $incomeThisMonthCount }} entries</div>
        </div>
        <div class="acc-kpi-card red">
            <div class="acc-kpi-label"><i class="fas fa-arrow-up"></i> Expenses This Month</div>
            <div class="acc-kpi-value">₹{{ number_format($expensesThisMonth, 2) }}</div>
            <div class="acc-kpi-sub">{{ $expensesThisMonthCount }} entries</div>
        </div>
        <div class="acc-kpi-card {{ $netProfitThisMonth >= 0 ? 'green' : 'red' }}">
            <div class="acc-kpi-label"><i class="fas fa-chart-bar"></i> Net Profit (Month)</div>
            <div class="acc-kpi-value">₹{{ number_format($netProfitThisMonth, 2) }}</div>
            <div class="acc-kpi-sub">Income − Expenses</div>
        </div>
        <div class="acc-kpi-card amber">
            <div class="acc-kpi-label"><i class="fas fa-file-invoice"></i> Receivables</div>
            <div class="acc-kpi-value">₹{{ number_format($totalReceivables, 2) }}</div>
            <div class="acc-kpi-sub">Pending invoices</div>
        </div>
        <div class="acc-kpi-card indigo">
            <div class="acc-kpi-label"><i class="fas fa-file-invoice-dollar"></i> Payables</div>
            <div class="acc-kpi-value">₹{{ number_format($totalPayables, 2) }}</div>
            <div class="acc-kpi-sub">Pending expenses</div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="acc-charts-row">
        <div class="acc-card">
            <div class="acc-card-title"><i class="fas fa-chart-area"></i> Cash Flow — Last 6 Months</div>
            <canvas id="cashFlowChart" height="100"></canvas>
        </div>
        <div class="acc-card">
            <div class="acc-card-title"><i class="fas fa-chart-pie"></i> Expenses by Category</div>
            <canvas id="expensePieChart" height="180"></canvas>
        </div>
    </div>

    {{-- Bank Accounts + Recent Transactions --}}
    <div class="acc-two-col">
        <div class="acc-card">
            <div class="acc-card-title"><i class="fas fa-university"></i> Bank Accounts
                <a href="{{ route('admin.accounts.bank-accounts') }}" style="margin-left:auto;font-size:.72rem;color:#6366f1;">View All</a>
            </div>
            <div class="acc-bank-list">
                @forelse($bankAccounts as $ba)
                <div class="acc-bank-item">
                    <div class="acc-bank-icon"><i class="fas {{ $ba->type_icon }}"></i></div>
                    <div class="acc-bank-info">
                        <div class="acc-bank-name">{{ $ba->name }}</div>
                        <div class="acc-bank-type">{{ $ba->type_label }} @if($ba->bank_name)· {{ $ba->bank_name }}@endif</div>
                    </div>
                    <div class="acc-bank-balance {{ $ba->current_balance < 0 ? 'neg' : '' }}">₹{{ number_format($ba->current_balance, 2) }}</div>
                </div>
                @empty
                <div style="color:var(--text-muted);font-size:.82rem;padding:.5rem 0;">No bank accounts yet. <a href="{{ route('admin.accounts.bank-accounts') }}" style="color:#6366f1;">Add one</a></div>
                @endforelse
            </div>
        </div>
        <div class="acc-card">
            <div class="acc-card-title"><i class="fas fa-exchange-alt"></i> Recent Transactions
                <a href="{{ route('admin.accounts.transactions') }}" style="margin-left:auto;font-size:.72rem;color:#6366f1;">View All</a>
            </div>
            <table class="acc-table">
                <thead><tr><th>Date</th><th>Description</th><th>Amount</th></tr></thead>
                <tbody>
                @forelse($recentTransactions as $txn)
                <tr>
                    <td>{{ $txn->transaction_date->format('d M') }}</td>
                    <td>{{ Str::limit($txn->description ?? $txn->category ?? '—', 22) }}</td>
                    <td><span class="acc-badge {{ $txn->type }}">{{ $txn->type === 'credit' ? '+' : '-' }}₹{{ number_format($txn->amount, 0) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="3" style="color:var(--text-muted);text-align:center;">No transactions yet</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Income + Expenses --}}
    <div class="acc-two-col">
        <div class="acc-card">
            <div class="acc-card-title"><i class="fas fa-arrow-circle-down" style="color:#22c55e;"></i> Recent Income
                <a href="{{ route('admin.accounts.income') }}" style="margin-left:auto;font-size:.72rem;color:#6366f1;">View All</a>
            </div>
            <table class="acc-table">
                <thead><tr><th>Date</th><th>Title</th><th>Amount</th><th>Status</th></tr></thead>
                <tbody>
                @forelse($recentIncome as $inc)
                <tr>
                    <td>{{ $inc->income_date->format('d M') }}</td>
                    <td>{{ Str::limit($inc->title, 20) }}</td>
                    <td style="color:#22c55e;font-weight:600;">₹{{ number_format($inc->amount, 0) }}</td>
                    <td><span class="acc-badge {{ $inc->status }}">{{ ucfirst($inc->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="4" style="color:var(--text-muted);text-align:center;">No income yet</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="acc-card">
            <div class="acc-card-title"><i class="fas fa-arrow-circle-up" style="color:#ef4444;"></i> Recent Expenses
                <a href="{{ route('admin.accounts.expenses') }}" style="margin-left:auto;font-size:.72rem;color:#6366f1;">View All</a>
            </div>
            <table class="acc-table">
                <thead><tr><th>Date</th><th>Title</th><th>Amount</th><th>Status</th></tr></thead>
                <tbody>
                @forelse($recentExpenses as $exp)
                <tr>
                    <td>{{ $exp->expense_date->format('d M') }}</td>
                    <td>{{ Str::limit($exp->title, 20) }}</td>
                    <td style="color:#ef4444;font-weight:600;">₹{{ number_format($exp->amount, 0) }}</td>
                    <td><span class="acc-badge {{ $exp->status }}">{{ ucfirst($exp->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="4" style="color:var(--text-muted);text-align:center;">No expenses yet</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const isDark = document.documentElement.classList.contains('dark') || document.body.classList.contains('dark-mode');
const gridColor = isDark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.07)';
const textColor = isDark ? '#9ca3af' : '#6b7280';

// Cash Flow Chart
new Chart(document.getElementById('cashFlowChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($cashFlowLabels) !!},
        datasets: [
            { label: 'Income', data: {!! json_encode($cashFlowIncome) !!}, backgroundColor: 'rgba(34,197,94,0.7)', borderRadius: 4 },
            { label: 'Expenses', data: {!! json_encode($cashFlowExpenses) !!}, backgroundColor: 'rgba(239,68,68,0.7)', borderRadius: 4 }
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: true,
        plugins: { legend: { labels: { color: textColor, font: { size: 11 } } } },
        scales: {
            x: { grid: { color: gridColor }, ticks: { color: textColor } },
            y: { grid: { color: gridColor }, ticks: { color: textColor, callback: v => '₹' + (v/1000).toFixed(0) + 'k' } }
        }
    }
});

// Expense Pie Chart
new Chart(document.getElementById('expensePieChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($expenseCategoryLabels) !!},
        datasets: [{ data: {!! json_encode($expenseCategoryData) !!},
            backgroundColor: ['#6366f1','#22c55e','#f59e0b','#ef4444','#3b82f6','#8b5cf6','#ec4899','#14b8a6'],
            borderWidth: 0 }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { color: textColor, font: { size: 10 }, padding: 8 } } }
    }
});
</script>
@endpush
