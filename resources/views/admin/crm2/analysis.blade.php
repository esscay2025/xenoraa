@extends('layouts.admin')
@section('title', 'CRM Analysis')
@section('content')
<div class="crm2-page">
  {{-- Page Header --}}
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-chart-line"></i> CRM Analysis</h1>
      <p class="crm2-subtitle">Deep insights into your sales pipeline, team performance, and business health.</p>
    </div>
  </div>

  {{-- KPI Cards --}}
  <div class="crm2-kpi-grid">
    <div class="crm2-kpi-card blue">
      <div class="kpi-icon"><i class="fas fa-user-tag"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ number_format($kpis['total_leads']) }}</div><div class="kpi-label">Total Leads</div></div>
    </div>
    <div class="crm2-kpi-card purple">
      <div class="kpi-icon"><i class="fas fa-address-book"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ number_format($kpis['total_contacts']) }}</div><div class="kpi-label">Contacts</div></div>
    </div>
    <div class="crm2-kpi-card indigo">
      <div class="kpi-icon"><i class="fas fa-building"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ number_format($kpis['total_accounts']) }}</div><div class="kpi-label">Accounts</div></div>
    </div>
    <div class="crm2-kpi-card orange">
      <div class="kpi-icon"><i class="fas fa-funnel-dollar"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ number_format($kpis['open_deals']) }}</div><div class="kpi-label">Open Deals</div></div>
    </div>
    <div class="crm2-kpi-card green">
      <div class="kpi-icon"><i class="fas fa-rupee-sign"></i></div>
      <div class="kpi-body"><div class="kpi-value">₹{{ number_format($kpis['won_value'], 0) }}</div><div class="kpi-label">Won Revenue</div></div>
    </div>
    <div class="crm2-kpi-card teal">
      <div class="kpi-icon"><i class="fas fa-chart-bar"></i></div>
      <div class="kpi-body"><div class="kpi-value">₹{{ number_format($kpis['pipeline_value'], 0) }}</div><div class="kpi-label">Pipeline Value</div></div>
    </div>
    <div class="crm2-kpi-card yellow">
      <div class="kpi-icon"><i class="fas fa-trophy"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ $kpis['win_rate'] }}%</div><div class="kpi-label">Win Rate</div></div>
    </div>
    <div class="crm2-kpi-card red">
      <div class="kpi-icon"><i class="fas fa-headset"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ number_format($kpis['open_cases']) }}</div><div class="kpi-label">Open Cases</div></div>
    </div>
  </div>

  {{-- Charts Row --}}
  <div class="crm2-charts-grid">
    {{-- Monthly Revenue --}}
    <div class="crm2-card chart-card">
      <div class="crm2-card-header"><h3><i class="fas fa-chart-area"></i> Monthly Revenue (Last 12 Months)</h3></div>
      <div class="crm2-card-body"><canvas id="revenueChart" height="220"></canvas></div>
    </div>
    {{-- Deal Pipeline --}}
    <div class="crm2-card chart-card">
      <div class="crm2-card-header"><h3><i class="fas fa-funnel-dollar"></i> Deal Pipeline by Stage</h3></div>
      <div class="crm2-card-body"><canvas id="pipelineChart" height="220"></canvas></div>
    </div>
  </div>

  <div class="crm2-charts-grid">
    {{-- Activity Breakdown --}}
    <div class="crm2-card chart-card">
      <div class="crm2-card-header"><h3><i class="fas fa-tasks"></i> Activity Breakdown</h3></div>
      <div class="crm2-card-body" style="display:flex;align-items:center;justify-content:center;">
        <canvas id="activityChart" height="220" style="max-width:300px;"></canvas>
      </div>
    </div>
    {{-- Top Accounts --}}
    <div class="crm2-card chart-card">
      <div class="crm2-card-header"><h3><i class="fas fa-star"></i> Top Accounts by Revenue</h3></div>
      <div class="crm2-card-body">
        @if($topAccounts->isEmpty())
          <div class="crm2-empty"><i class="fas fa-building"></i><p>No closed deals yet.</p></div>
        @else
          <div class="crm2-top-list">
            @foreach($topAccounts as $acc)
            <div class="crm2-top-item">
              <div class="top-rank">{{ $loop->iteration }}</div>
              <div class="top-name">{{ $acc->name }}</div>
              <div class="top-value">₹{{ number_format($acc->total, 0) }}</div>
            </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const chartDefaults = { font: { family: 'Inter, sans-serif' }, color: '#94a3b8' };
Chart.defaults.font.family = 'Inter, sans-serif';
Chart.defaults.color = '#94a3b8';

// Monthly Revenue
const revData = @json($monthlyRevenue);
new Chart(document.getElementById('revenueChart'), {
  type: 'line',
  data: {
    labels: revData.map(d => d.month),
    datasets: [{ label: 'Revenue (₹)', data: revData.map(d => d.revenue), borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,0.15)', fill: true, tension: 0.4, pointRadius: 4 }]
  },
  options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { callback: v => '₹' + v.toLocaleString() } }, x: { grid: { color: 'rgba(255,255,255,0.05)' } } } }
});

// Pipeline
const pipeData = @json($dealsByStage);
const stageColors = { prospecting: '#6366f1', qualification: '#8b5cf6', proposal: '#f59e0b', negotiation: '#f97316', closed_won: '#22c55e', closed_lost: '#ef4444' };
new Chart(document.getElementById('pipelineChart'), {
  type: 'bar',
  data: {
    labels: pipeData.map(d => d.stage.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())),
    datasets: [
      { label: 'Count', data: pipeData.map(d => d.count), backgroundColor: pipeData.map(d => stageColors[d.stage] || '#6366f1'), borderRadius: 6, yAxisID: 'y' },
      { label: 'Value (₹)', data: pipeData.map(d => d.total), backgroundColor: 'rgba(99,102,241,0.3)', borderColor: '#6366f1', type: 'line', yAxisID: 'y1', tension: 0.4 }
    ]
  },
  options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { grid: { color: 'rgba(255,255,255,0.05)' } }, y1: { position: 'right', grid: { display: false }, ticks: { callback: v => '₹' + v.toLocaleString() } }, x: { grid: { color: 'rgba(255,255,255,0.05)' } } } }
});

// Activity Donut
const actData = @json($activityTypes);
const actColors = { task: '#22c55e', meeting: '#8b5cf6', call: '#6366f1', email: '#3b82f6', note: '#f59e0b', demo: '#f97316' };
new Chart(document.getElementById('activityChart'), {
  type: 'doughnut',
  data: {
    labels: actData.map(d => d.type.charAt(0).toUpperCase() + d.type.slice(1)),
    datasets: [{ data: actData.map(d => d.count), backgroundColor: actData.map(d => actColors[d.type] || '#6366f1'), borderWidth: 0, hoverOffset: 8 }]
  },
  options: { responsive: true, cutout: '70%', plugins: { legend: { position: 'bottom' } } }
});
</script>
@endpush
@endsection
