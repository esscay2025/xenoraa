@extends('layouts.admin')
@section('title', 'Financial Reports')
@section('page-title', 'Financial Reports')
@push('styles')
<style>
.report-type-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:1rem; margin-bottom:1.5rem; }
.report-type-card { background:var(--bg-card,#fff); border:2px solid var(--border,#e2e8f0); border-radius:12px; padding:1.1rem 1.3rem; cursor:pointer; transition:border-color .15s,box-shadow .15s; display:flex; flex-direction:column; gap:.4rem; }
.report-type-card:hover, .report-type-card.active { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.12); }
.report-type-card .icon { width:36px; height:36px; border-radius:8px; background:rgba(99,102,241,.12); display:flex; align-items:center; justify-content:center; color:#6366f1; font-size:.9rem; }
.report-type-card .label { font-size:.85rem; font-weight:600; color:var(--text-primary,#1a1a2e); }
.report-type-card .desc { font-size:.72rem; color:var(--text-muted,#64748b); }
.report-section { margin-bottom:1.5rem; }
.report-section-title { font-size:.82rem; font-weight:700; color:var(--text-muted,#64748b); text-transform:uppercase; letter-spacing:.04em; padding:.5rem 0; border-bottom:1px solid var(--border,#e2e8f0); margin-bottom:.5rem; }
.report-row { display:flex; align-items:center; padding:.45rem 0; border-bottom:1px solid var(--border,#e2e8f0); font-size:.83rem; }
.report-row:last-child { border-bottom:none; }
.report-row-label { flex:1; color:var(--text-primary,#1a1a2e); }
.report-row-value { font-weight:600; color:var(--text-primary,#1a1a2e); min-width:120px; text-align:right; }
.report-row.total { background:var(--bg-secondary,rgba(99,102,241,.05)); border-radius:6px; padding:.5rem .6rem; font-weight:700; }
.report-row.total .report-row-value { color:#6366f1; }
</style>
@endpush
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-chart-bar"></i> Financial Reports</h1>
    </div>
    <div style="display:flex;gap:.6rem;">
      <button class="crm2-btn crm2-btn-secondary" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
      <a href="{{ request()->fullUrlWithQuery(['export'=>'csv']) }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-file-csv"></i> Export CSV</a>
    </div>
  </div>

  {{-- Report Type Selector --}}
  <div class="report-type-grid">
    @php
      $reportTypes = [
        ['key'=>'pl', 'icon'=>'fa-chart-line', 'label'=>'Profit & Loss', 'desc'=>'Income vs Expenses summary'],
        ['key'=>'balance_sheet', 'icon'=>'fa-balance-scale', 'label'=>'Balance Sheet', 'desc'=>'Assets, Liabilities & Equity'],
        ['key'=>'cash_flow', 'icon'=>'fa-water', 'label'=>'Cash Flow', 'desc'=>'Cash in and out over time'],
        ['key'=>'aged_receivables', 'icon'=>'fa-file-invoice-dollar', 'label'=>'Aged Receivables', 'desc'=>'Outstanding income by age'],
        ['key'=>'aged_payables', 'icon'=>'fa-hand-holding-usd', 'label'=>'Aged Payables', 'desc'=>'Outstanding expenses by age'],
        ['key'=>'expense_summary', 'icon'=>'fa-receipt', 'label'=>'Expense Summary', 'desc'=>'Expenses grouped by category'],
      ];
      $activeReport = request('report', 'pl');
    @endphp
    @foreach($reportTypes as $rt)
    <a href="{{ route('admin.accounts.reports', ['report'=>$rt['key'], 'date_from'=>request('date_from'), 'date_to'=>request('date_to')]) }}" class="report-type-card {{ $activeReport === $rt['key'] ? 'active' : '' }}" style="text-decoration:none;">
      <div class="icon"><i class="fas {{ $rt['icon'] }}"></i></div>
      <div class="label">{{ $rt['label'] }}</div>
      <div class="desc">{{ $rt['desc'] }}</div>
    </a>
    @endforeach
  </div>

  {{-- Date Range Filter --}}
  <div class="crm2-card mb-4">
    <div class="crm2-card-body">
      <form method="GET" class="crm2-filter-form">
        <input type="hidden" name="report" value="{{ $activeReport }}">
          <input type="date" name="date_from" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}" class="crm2-input">
          <input type="date" name="date_to" value="{{ request('date_to', now()->format('Y-m-d')) }}" class="crm2-input">
        <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-sync"></i> Generate</button>
        {{-- Quick range buttons --}}
        <a href="{{ route('admin.accounts.reports', ['report'=>$activeReport, 'date_from'=>now()->startOfMonth()->format('Y-m-d'), 'date_to'=>now()->format('Y-m-d')]) }}" class="crm2-btn crm2-btn-ghost" style="font-size:.78rem;">This Month</a>
        <a href="{{ route('admin.accounts.reports', ['report'=>$activeReport, 'date_from'=>now()->startOfQuarter()->format('Y-m-d'), 'date_to'=>now()->format('Y-m-d')]) }}" class="crm2-btn crm2-btn-ghost" style="font-size:.78rem;">This Quarter</a>
        <a href="{{ route('admin.accounts.reports', ['report'=>$activeReport, 'date_from'=>now()->startOfYear()->format('Y-m-d'), 'date_to'=>now()->format('Y-m-d')]) }}" class="crm2-btn crm2-btn-ghost" style="font-size:.78rem;">This Year</a>
      </form>
    </div>
  </div>

  {{-- Report Output --}}
  <div class="crm2-card">
    <div class="crm2-card-body">
      @if($activeReport === 'pl')
        <div style="font-size:1rem;font-weight:700;color:var(--text-primary,#1a1a2e);margin-bottom:1rem;"><i class="fas fa-chart-line" style="color:#6366f1;"></i> Profit & Loss Statement</div>
        <div class="report-section">
          <div class="report-section-title">Income</div>
          @foreach($reportData['income_by_category'] ?? [] as $cat => $amt)
          <div class="report-row"><span class="report-row-label">{{ $cat }}</span><span class="report-row-value" style="color:#22c55e;">₹{{ number_format($amt,2) }}</span></div>
          @endforeach
          <div class="report-row total"><span class="report-row-label">Total Income</span><span class="report-row-value" style="color:#22c55e;">₹{{ number_format($reportData['total_income'] ?? 0, 2) }}</span></div>
        </div>
        <div class="report-section">
          <div class="report-section-title">Expenses</div>
          @foreach($reportData['expense_by_category'] ?? [] as $cat => $amt)
          <div class="report-row"><span class="report-row-label">{{ $cat }}</span><span class="report-row-value" style="color:#ef4444;">₹{{ number_format($amt,2) }}</span></div>
          @endforeach
          <div class="report-row total"><span class="report-row-label">Total Expenses</span><span class="report-row-value" style="color:#ef4444;">₹{{ number_format($reportData['total_expenses'] ?? 0, 2) }}</span></div>
        </div>
        <div class="report-row total" style="margin-top:.5rem;">
          <span class="report-row-label" style="font-size:.95rem;">Net Profit / (Loss)</span>
          @php $net = ($reportData['total_income'] ?? 0) - ($reportData['total_expenses'] ?? 0); @endphp
          <span class="report-row-value" style="font-size:1rem;color:{{ $net >= 0 ? '#22c55e' : '#ef4444' }};">₹{{ number_format($net, 2) }}</span>
        </div>

      @elseif($activeReport === 'balance_sheet')
        <div style="font-size:1rem;font-weight:700;color:var(--text-primary,#1a1a2e);margin-bottom:1rem;"><i class="fas fa-balance-scale" style="color:#6366f1;"></i> Balance Sheet</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
          <div>
            <div class="report-section-title">Assets</div>
            @foreach($reportData['assets'] ?? [] as $acc)
            <div class="report-row"><span class="report-row-label">{{ $acc->name }}</span><span class="report-row-value">₹{{ number_format($acc->balance ?? 0, 2) }}</span></div>
            @endforeach
            <div class="report-row total"><span class="report-row-label">Total Assets</span><span class="report-row-value">₹{{ number_format(collect($reportData['assets'] ?? [])->sum('balance'), 2) }}</span></div>
          </div>
          <div>
            <div class="report-section-title">Liabilities & Equity</div>
            @foreach($reportData['liabilities'] ?? [] as $acc)
            <div class="report-row"><span class="report-row-label">{{ $acc->name }}</span><span class="report-row-value">₹{{ number_format($acc->balance ?? 0, 2) }}</span></div>
            @endforeach
            @foreach($reportData['equity'] ?? [] as $acc)
            <div class="report-row"><span class="report-row-label">{{ $acc->name }}</span><span class="report-row-value">₹{{ number_format($acc->balance ?? 0, 2) }}</span></div>
            @endforeach
            <div class="report-row total"><span class="report-row-label">Total L + E</span><span class="report-row-value">₹{{ number_format(collect($reportData['liabilities'] ?? [])->sum('balance') + collect($reportData['equity'] ?? [])->sum('balance'), 2) }}</span></div>
          </div>
        </div>

      @elseif($activeReport === 'expense_summary')
        <div style="font-size:1rem;font-weight:700;color:var(--text-primary,#1a1a2e);margin-bottom:1rem;"><i class="fas fa-receipt" style="color:#6366f1;"></i> Expense Summary by Category</div>
        @foreach($reportData['expense_by_category'] ?? [] as $cat => $amt)
        <div class="report-row"><span class="report-row-label">{{ $cat }}</span><span class="report-row-value" style="color:#ef4444;">₹{{ number_format($amt,2) }}</span></div>
        @endforeach
        <div class="report-row total"><span class="report-row-label">Total Expenses</span><span class="report-row-value">₹{{ number_format($reportData['total_expenses'] ?? 0, 2) }}</span></div>

      @else
        <div class="crm2-empty"><i class="fas fa-chart-bar"></i><p>Select a report type above and click Generate.</p></div>
      @endif
    </div>
  </div>
</div>
@endsection
