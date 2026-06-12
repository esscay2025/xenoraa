@extends('layouts.admin')
@section('title', 'Financial Reports')
@push('styles')
<style>
.acc-page{padding:1.5rem 2rem;}
.acc-page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;flex-wrap:wrap;gap:.75rem;}
.acc-page-title{font-size:1.5rem;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:.6rem;}
.acc-page-title i{color:#6366f1;}
.acc-report-tabs{display:flex;gap:.4rem;flex-wrap:wrap;margin-bottom:1.5rem;border-bottom:2px solid var(--border-color);padding-bottom:.5rem;}
.acc-report-tab{padding:.45rem 1rem;border-radius:7px 7px 0 0;font-size:.83rem;font-weight:600;color:var(--text-muted);cursor:pointer;border:none;background:transparent;transition:all .15s;}
.acc-report-tab:hover,.acc-report-tab.active{background:rgba(99,102,241,.12);color:#6366f1;}
.acc-report-panel{display:none;}
.acc-report-panel.active{display:block;}
.acc-filters{display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1.2rem;align-items:center;}
.acc-filter-input,.acc-filter-select{padding:.4rem .75rem;border-radius:7px;border:1px solid var(--border-color);background:var(--card-bg);color:var(--text-primary);font-size:.82rem;}
.acc-btn-primary{padding:.42rem 1rem;border-radius:7px;background:#6366f1;color:#fff;border:none;font-size:.82rem;font-weight:600;cursor:pointer;}
.acc-card{background:var(--card-bg);border:1px solid var(--border-color);border-radius:12px;overflow:hidden;margin-bottom:1.2rem;}
.acc-report-section{padding:1.2rem 1.4rem;}
.acc-report-section-title{font-size:.85rem;font-weight:700;color:var(--text-primary);margin-bottom:.8rem;padding-bottom:.5rem;border-bottom:1px solid var(--border-color);}
.acc-report-row{display:flex;justify-content:space-between;padding:.4rem 0;font-size:.83rem;color:var(--text-primary);}
.acc-report-row.total{font-weight:700;border-top:1px solid var(--border-color);margin-top:.4rem;padding-top:.6rem;}
.acc-report-row.grand-total{font-weight:800;font-size:.95rem;border-top:2px solid var(--border-color);margin-top:.6rem;padding-top:.8rem;color:#6366f1;}
.acc-report-row .label{color:var(--text-muted);}
.acc-report-row.total .label,.acc-report-row.grand-total .label{color:var(--text-primary);}
.acc-report-row .value{font-weight:600;}
.acc-report-row .value.positive{color:#22c55e;}
.acc-report-row .value.negative{color:#ef4444;}
.acc-two-col{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
.acc-export-bar{display:flex;gap:.6rem;justify-content:flex-end;margin-bottom:.8rem;}
.acc-btn-export{padding:.38rem .9rem;border-radius:7px;border:1px solid var(--border-color);background:var(--card-bg);color:var(--text-primary);font-size:.78rem;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;}
.acc-btn-export:hover{border-color:#6366f1;color:#6366f1;}
@media(max-width:700px){.acc-two-col{grid-template-columns:1fr;}}
</style>
@endpush

@section('content')
<div class="acc-page">
    <div class="acc-page-header">
        <div class="acc-page-title"><i class="fas fa-file-chart-line"></i> Financial Reports</div>
    </div>

    {{-- Report Tabs --}}
    <div class="acc-report-tabs">
        <button class="acc-report-tab active" onclick="showReport('pl', this)"><i class="fas fa-chart-bar"></i> Profit & Loss</button>
        <button class="acc-report-tab" onclick="showReport('bs', this)"><i class="fas fa-balance-scale"></i> Balance Sheet</button>
        <button class="acc-report-tab" onclick="showReport('cf', this)"><i class="fas fa-water"></i> Cash Flow</button>
        <button class="acc-report-tab" onclick="showReport('ar', this)"><i class="fas fa-file-invoice"></i> Aged Receivables</button>
        <button class="acc-report-tab" onclick="showReport('ap', this)"><i class="fas fa-file-invoice-dollar"></i> Aged Payables</button>
        <button class="acc-report-tab" onclick="showReport('exp', this)"><i class="fas fa-chart-pie"></i> Expense Summary</button>
    </div>

    {{-- Date Range Filter --}}
    <form method="GET" class="acc-filters" id="reportFilters">
        <input type="hidden" name="report" id="reportType" value="{{ request('report', 'pl') }}">
        <label style="font-size:.8rem;color:var(--text-muted);">Period:</label>
        <input type="date" name="date_from" class="acc-filter-input" value="{{ $dateFrom }}" required>
        <span style="color:var(--text-muted);font-size:.8rem;">to</span>
        <input type="date" name="date_to" class="acc-filter-input" value="{{ $dateTo }}" required>
        <button type="submit" class="acc-btn-primary"><i class="fas fa-sync-alt"></i> Generate</button>
        <div class="acc-export-bar" style="margin-left:auto;margin-bottom:0;">
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="acc-btn-export"><i class="fas fa-file-csv"></i> CSV</a>
            <button type="button" onclick="window.print()" class="acc-btn-export"><i class="fas fa-print"></i> Print</button>
        </div>
    </form>

    {{-- Profit & Loss --}}
    <div class="acc-report-panel {{ request('report','pl') === 'pl' ? 'active' : '' }}" id="panel-pl">
        <div class="acc-card">
            <div class="acc-report-section">
                <div style="text-align:center;margin-bottom:1.2rem;">
                    <div style="font-size:1.1rem;font-weight:800;color:var(--text-primary);">Profit & Loss Statement</div>
                    <div style="font-size:.8rem;color:var(--text-muted);">{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} — {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</div>
                </div>
                <div class="acc-report-section-title">Income</div>
                @foreach($plIncome as $cat => $amount)
                <div class="acc-report-row"><span class="label">{{ $cat }}</span><span class="value positive">₹{{ number_format($amount, 2) }}</span></div>
                @endforeach
                <div class="acc-report-row total"><span class="label">Total Income</span><span class="value positive">₹{{ number_format($plTotalIncome, 2) }}</span></div>

                <div class="acc-report-section-title" style="margin-top:1.2rem;">Expenses</div>
                @foreach($plExpenses as $cat => $amount)
                <div class="acc-report-row"><span class="label">{{ $cat }}</span><span class="value negative">₹{{ number_format($amount, 2) }}</span></div>
                @endforeach
                <div class="acc-report-row total"><span class="label">Total Expenses</span><span class="value negative">₹{{ number_format($plTotalExpenses, 2) }}</span></div>

                @php $netProfit = $plTotalIncome - $plTotalExpenses; @endphp
                <div class="acc-report-row grand-total">
                    <span class="label">Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}</span>
                    <span class="value {{ $netProfit >= 0 ? 'positive' : 'negative' }}">₹{{ number_format(abs($netProfit), 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Balance Sheet --}}
    <div class="acc-report-panel {{ request('report') === 'bs' ? 'active' : '' }}" id="panel-bs">
        <div class="acc-two-col">
            <div class="acc-card">
                <div class="acc-report-section">
                    <div class="acc-report-section-title">Assets</div>
                    @foreach($bsAssets as $name => $amount)
                    <div class="acc-report-row"><span class="label">{{ $name }}</span><span class="value">₹{{ number_format($amount, 2) }}</span></div>
                    @endforeach
                    <div class="acc-report-row total"><span class="label">Total Assets</span><span class="value positive">₹{{ number_format($bsTotalAssets, 2) }}</span></div>
                </div>
            </div>
            <div class="acc-card">
                <div class="acc-report-section">
                    <div class="acc-report-section-title">Liabilities</div>
                    @foreach($bsLiabilities as $name => $amount)
                    <div class="acc-report-row"><span class="label">{{ $name }}</span><span class="value negative">₹{{ number_format($amount, 2) }}</span></div>
                    @endforeach
                    <div class="acc-report-row total"><span class="label">Total Liabilities</span><span class="value negative">₹{{ number_format($bsTotalLiabilities, 2) }}</span></div>
                    <div class="acc-report-section-title" style="margin-top:1rem;">Equity</div>
                    @foreach($bsEquity as $name => $amount)
                    <div class="acc-report-row"><span class="label">{{ $name }}</span><span class="value">₹{{ number_format($amount, 2) }}</span></div>
                    @endforeach
                    <div class="acc-report-row total"><span class="label">Total Equity</span><span class="value">₹{{ number_format($bsTotalEquity, 2) }}</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cash Flow --}}
    <div class="acc-report-panel {{ request('report') === 'cf' ? 'active' : '' }}" id="panel-cf">
        <div class="acc-card">
            <div class="acc-report-section">
                <div style="text-align:center;margin-bottom:1.2rem;">
                    <div style="font-size:1.1rem;font-weight:800;color:var(--text-primary);">Cash Flow Statement</div>
                    <div style="font-size:.8rem;color:var(--text-muted);">{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} — {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</div>
                </div>
                <div class="acc-report-section-title">Operating Activities</div>
                <div class="acc-report-row"><span class="label">Cash received from customers</span><span class="value positive">₹{{ number_format($cfIncome, 2) }}</span></div>
                <div class="acc-report-row"><span class="label">Cash paid for expenses</span><span class="value negative">-₹{{ number_format($cfExpenses, 2) }}</span></div>
                <div class="acc-report-row total"><span class="label">Net Operating Cash Flow</span><span class="value {{ ($cfIncome - $cfExpenses) >= 0 ? 'positive' : 'negative' }}">₹{{ number_format($cfIncome - $cfExpenses, 2) }}</span></div>
                <div class="acc-report-row grand-total"><span class="label">Net Change in Cash</span><span class="value {{ ($cfIncome - $cfExpenses) >= 0 ? 'positive' : 'negative' }}">₹{{ number_format($cfIncome - $cfExpenses, 2) }}</span></div>
            </div>
        </div>
    </div>

    {{-- Aged Receivables --}}
    <div class="acc-report-panel {{ request('report') === 'ar' ? 'active' : '' }}" id="panel-ar">
        <div class="acc-card">
            <table class="acc-table" style="font-size:.83rem;">
                <thead><tr><th>Customer</th><th>Invoice Ref</th><th>Due Date</th><th>0–30 Days</th><th>31–60 Days</th><th>61–90 Days</th><th>90+ Days</th><th>Total</th></tr></thead>
                <tbody>
                @forelse($agedReceivables as $ar)
                <tr>
                    <td>{{ $ar['customer'] }}</td>
                    <td style="font-size:.75rem;color:var(--text-muted);">{{ $ar['reference'] }}</td>
                    <td>{{ $ar['due_date'] }}</td>
                    <td style="color:#22c55e;">{{ $ar['0_30'] > 0 ? '₹'.number_format($ar['0_30'],2) : '—' }}</td>
                    <td style="color:#f59e0b;">{{ $ar['31_60'] > 0 ? '₹'.number_format($ar['31_60'],2) : '—' }}</td>
                    <td style="color:#ef4444;">{{ $ar['61_90'] > 0 ? '₹'.number_format($ar['61_90'],2) : '—' }}</td>
                    <td style="color:#dc2626;font-weight:700;">{{ $ar['90_plus'] > 0 ? '₹'.number_format($ar['90_plus'],2) : '—' }}</td>
                    <td style="font-weight:700;">₹{{ number_format($ar['total'],2) }}</td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--text-muted);">No outstanding receivables.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Aged Payables --}}
    <div class="acc-report-panel {{ request('report') === 'ap' ? 'active' : '' }}" id="panel-ap">
        <div class="acc-card">
            <table class="acc-table" style="font-size:.83rem;">
                <thead><tr><th>Vendor</th><th>Expense Ref</th><th>Date</th><th>0–30 Days</th><th>31–60 Days</th><th>61–90 Days</th><th>90+ Days</th><th>Total</th></tr></thead>
                <tbody>
                @forelse($agedPayables as $ap)
                <tr>
                    <td>{{ $ap['vendor'] }}</td>
                    <td style="font-size:.75rem;color:var(--text-muted);">{{ $ap['reference'] }}</td>
                    <td>{{ $ap['date'] }}</td>
                    <td style="color:#22c55e;">{{ $ap['0_30'] > 0 ? '₹'.number_format($ap['0_30'],2) : '—' }}</td>
                    <td style="color:#f59e0b;">{{ $ap['31_60'] > 0 ? '₹'.number_format($ap['31_60'],2) : '—' }}</td>
                    <td style="color:#ef4444;">{{ $ap['61_90'] > 0 ? '₹'.number_format($ap['61_90'],2) : '—' }}</td>
                    <td style="color:#dc2626;font-weight:700;">{{ $ap['90_plus'] > 0 ? '₹'.number_format($ap['90_plus'],2) : '—' }}</td>
                    <td style="font-weight:700;">₹{{ number_format($ap['total'],2) }}</td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--text-muted);">No outstanding payables.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Expense Summary --}}
    <div class="acc-report-panel {{ request('report') === 'exp' ? 'active' : '' }}" id="panel-exp">
        <div class="acc-two-col">
            <div class="acc-card">
                <div class="acc-report-section">
                    <div class="acc-report-section-title">Expenses by Category</div>
                    @foreach($expenseSummary as $cat => $amount)
                    <div class="acc-report-row">
                        <span class="label">{{ $cat }}</span>
                        <span class="value negative">₹{{ number_format($amount, 2) }}</span>
                    </div>
                    @endforeach
                    <div class="acc-report-row total"><span class="label">Total</span><span class="value negative">₹{{ number_format(array_sum($expenseSummary), 2) }}</span></div>
                </div>
            </div>
            <div class="acc-card" style="padding:1.2rem 1.4rem;">
                <div class="acc-report-section-title">Chart</div>
                <canvas id="expSummaryChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
function showReport(id, btn) {
    document.querySelectorAll('.acc-report-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.acc-report-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('panel-' + id).classList.add('active');
    btn.classList.add('active');
    document.getElementById('reportType').value = id;
}

// Activate correct tab on load
const activeReport = '{{ request("report","pl") }}';
const activeTab = document.querySelector(`.acc-report-tab[onclick*="'${activeReport}'"]`);
if (activeTab) { document.querySelectorAll('.acc-report-tab').forEach(b => b.classList.remove('active')); activeTab.classList.add('active'); }

// Expense Summary Chart
const expLabels = {!! json_encode(array_keys($expenseSummary)) !!};
const expData = {!! json_encode(array_values($expenseSummary)) !!};
if (expLabels.length > 0) {
    new Chart(document.getElementById('expSummaryChart'), {
        type: 'doughnut',
        data: {
            labels: expLabels,
            datasets: [{ data: expData, backgroundColor: ['#6366f1','#22c55e','#f59e0b','#ef4444','#3b82f6','#8b5cf6','#ec4899','#14b8a6','#f97316','#06b6d4'], borderWidth: 0 }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { font: { size: 10 } } } } }
    });
}
</script>
@endpush
