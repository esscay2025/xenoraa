@extends('layouts.admin')
@section('title', 'Dashboard')
@push('styles')
<style>
/* Dashboard-specific layout fixes */
.xn-dashboard-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)) !important;
    gap: 16px;
    margin-bottom: 28px;
    width: 100%;
}
.xn-dashboard-grid-2 {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)) !important;
    gap: 16px;
    margin-bottom: 28px;
    width: 100%;
}
.xn-charts-grid {
    display: grid !important;
    grid-template-columns: 1fr 1fr !important;
    gap: 20px;
    margin-bottom: 28px;
    width: 100%;
}
.xn-recent-grid {
    display: grid !important;
    grid-template-columns: 1fr 1fr !important;
    gap: 20px;
    margin-bottom: 28px;
    width: 100%;
}
.xn-actions-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)) !important;
    gap: 12px;
    width: 100%;
}
@media (max-width: 900px) {
    .xn-charts-grid, .xn-recent-grid { grid-template-columns: 1fr !important; }
}
@media (max-width: 600px) {
    .xn-dashboard-grid, .xn-dashboard-grid-2 { grid-template-columns: 1fr 1fr !important; }
}
</style>
@endpush
@section('content')

<div class="crm2-page">

  {{-- ── Page Header ─────────────────────────────────────────────────────── --}}
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
      <p class="crm2-subtitle">Welcome back, {{ Auth::user()->name }}. Here's your business overview.</p>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
      <a href="{{ route('admin.crm2.inventory.quotes.create') }}" class="crm2-btn crm2-btn-primary btn-sm">
        <i class="fas fa-plus"></i> New Quote
      </a>
      <a href="{{ route('admin.crm2.sales.deals.create') }}" class="crm2-btn crm2-btn-secondary btn-sm">
        <i class="fas fa-handshake"></i> New Deal
      </a>
      <a href="{{ route('admin.pos.terminal') }}" class="crm2-btn crm2-btn-secondary btn-sm">
        <i class="fas fa-cash-register"></i> POS Terminal
      </a>
    </div>
  </div>

  {{-- ── Section: CRM ──────────────────────────────────────────────────────── --}}
  <div style="display:flex; align-items:center; gap:10px; margin:0 0 14px;">
    <span style="font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--crm-secondary);">CRM</span>
    <div style="flex:1; height:1px; background:var(--crm-border);"></div>
    <a href="{{ route('admin.crm2.analysis') }}" style="font-size:0.75rem; color:var(--crm-primary); text-decoration:none; white-space:nowrap;">View Analysis <i class="fas fa-arrow-right" style="font-size:0.65rem;"></i></a>
  </div>
  <div class="xn-dashboard-grid">
    <a href="{{ route('admin.crm2.sales.accounts') }}" class="crm2-kpi-card indigo" style="text-decoration:none;">
      <div class="kpi-icon"><i class="fas fa-building"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ number_format($crmAccounts) }}</div><div class="kpi-label">Accounts</div></div>
    </a>
    <a href="{{ route('admin.crm2.sales.contacts') }}" class="crm2-kpi-card teal" style="text-decoration:none;">
      <div class="kpi-icon"><i class="fas fa-address-book"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ number_format($crmContacts) }}</div><div class="kpi-label">Contacts</div></div>
    </a>
    <a href="{{ route('admin.crm.leads') }}" class="crm2-kpi-card yellow" style="text-decoration:none;">
      <div class="kpi-icon"><i class="fas fa-user-tag"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ number_format($crmLeads) }}</div><div class="kpi-label">Leads</div></div>
    </a>
    <a href="{{ route('admin.crm2.sales.deals') }}" class="crm2-kpi-card orange" style="text-decoration:none;">
      <div class="kpi-icon"><i class="fas fa-funnel-dollar"></i></div>
      <div class="kpi-body">
        <div class="kpi-value">{{ number_format($crmDeals) }}</div>
        <div class="kpi-label">Deals</div>
        <div class="kpi-change up"><i class="fas fa-check-circle"></i> {{ $crmDealsWon }} won</div>
      </div>
    </a>
    <a href="{{ route('admin.crm2.activities') }}" class="crm2-kpi-card purple" style="text-decoration:none;">
      <div class="kpi-icon"><i class="fas fa-tasks"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ number_format($crmActivities) }}</div><div class="kpi-label">Open Activities</div></div>
    </a>
    <div class="crm2-kpi-card green">
      <div class="kpi-icon"><i class="fas fa-rupee-sign"></i></div>
      <div class="kpi-body">
        <div class="kpi-value" style="font-size:1.15rem;">₹{{ number_format($crmWonValue, 0) }}</div>
        <div class="kpi-label">Won Revenue</div>
      </div>
    </div>
    <div class="crm2-kpi-card blue">
      <div class="kpi-icon"><i class="fas fa-chart-bar"></i></div>
      <div class="kpi-body">
        <div class="kpi-value" style="font-size:1.15rem;">₹{{ number_format($crmPipelineVal, 0) }}</div>
        <div class="kpi-label">Pipeline Value</div>
      </div>
    </div>
  </div>

  {{-- ── Section: Inventory ────────────────────────────────────────────────── --}}
  <div style="display:flex; align-items:center; gap:10px; margin:0 0 14px;">
    <span style="font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--crm-secondary);">Inventory</span>
    <div style="flex:1; height:1px; background:var(--crm-border);"></div>
    <a href="{{ route('admin.crm2.inventory') }}" style="font-size:0.75rem; color:var(--crm-primary); text-decoration:none; white-space:nowrap;">View All <i class="fas fa-arrow-right" style="font-size:0.65rem;"></i></a>
  </div>
  <div class="xn-dashboard-grid">
    <a href="{{ route('admin.crm2.inventory.quotes') }}" class="crm2-kpi-card indigo" style="text-decoration:none;">
      <div class="kpi-icon"><i class="fas fa-file-invoice"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ number_format($invQuotes) }}</div><div class="kpi-label">Quotes</div></div>
    </a>
    <a href="{{ route('admin.crm2.inventory.sales-orders') }}" class="crm2-kpi-card green" style="text-decoration:none;">
      <div class="kpi-icon"><i class="fas fa-shopping-cart"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ number_format($invSalesOrders) }}</div><div class="kpi-label">Sales Orders</div></div>
    </a>
    <a href="{{ route('admin.crm2.inventory.purchase-orders') }}" class="crm2-kpi-card yellow" style="text-decoration:none;">
      <div class="kpi-icon"><i class="fas fa-truck"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ number_format($invPOs) }}</div><div class="kpi-label">Purchase Orders</div></div>
    </a>
    <a href="{{ route('admin.crm2.inventory.invoices') }}" class="crm2-kpi-card red" style="text-decoration:none;">
      <div class="kpi-icon"><i class="fas fa-receipt"></i></div>
      <div class="kpi-body">
        <div class="kpi-value">{{ number_format($invInvoices) }}</div>
        <div class="kpi-label">Invoices</div>
        @if($invInvoicesDue > 0)
        <div class="kpi-change down"><i class="fas fa-clock"></i> {{ $invInvoicesDue }} pending</div>
        @endif
      </div>
    </a>
    <a href="{{ route('admin.crm2.inventory.vendors') }}" class="crm2-kpi-card teal" style="text-decoration:none;">
      <div class="kpi-icon"><i class="fas fa-store"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ number_format($invVendors) }}</div><div class="kpi-label">Vendors</div></div>
    </a>
    <div class="crm2-kpi-card green">
      <div class="kpi-icon"><i class="fas fa-rupee-sign"></i></div>
      <div class="kpi-body">
        <div class="kpi-value" style="font-size:1.15rem;">₹{{ number_format($invRevenue, 0) }}</div>
        <div class="kpi-label">Revenue (Paid)</div>
      </div>
    </div>
  </div>

  {{-- ── Charts Row ────────────────────────────────────────────────────────── --}}
  <div class="xn-charts-grid">
    <div class="crm2-card chart-card">
      <div class="crm2-card-header">
        <h3><i class="fas fa-chart-area"></i> Invoice Revenue (Last 6 Months)</h3>
      </div>
      <div class="crm2-card-body"><canvas id="revenueChart" height="220"></canvas></div>
    </div>
    <div class="crm2-card chart-card">
      <div class="crm2-card-header">
        <h3><i class="fas fa-funnel-dollar"></i> Deal Pipeline by Stage</h3>
      </div>
      <div class="crm2-card-body"><canvas id="pipelineChart" height="220"></canvas></div>
    </div>
  </div>

  {{-- ── Section: E-Commerce + POS + Site ────────────────────────────────── --}}
  <div style="display:flex; align-items:center; gap:10px; margin:0 0 14px;">
    <span style="font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--crm-secondary);">Other Modules</span>
    <div style="flex:1; height:1px; background:var(--crm-border);"></div>
  </div>
  <div class="xn-dashboard-grid">
    <a href="{{ route('admin.ecommerce.products') }}" class="crm2-kpi-card purple" style="text-decoration:none;">
      <div class="kpi-icon"><i class="fas fa-box-open"></i></div>
      <div class="kpi-body">
        <div class="kpi-value">{{ number_format($ecomProducts) }}</div>
        <div class="kpi-label">Products</div>
        <div class="kpi-change up"><i class="fas fa-circle"></i> {{ $ecomActive }} active</div>
      </div>
    </a>
    <a href="{{ route('admin.pos.orders') }}" class="crm2-kpi-card teal" style="text-decoration:none;">
      <div class="kpi-icon"><i class="fas fa-receipt"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ number_format($posOrders) }}</div><div class="kpi-label">POS Orders</div></div>
    </a>
    <div class="crm2-kpi-card green">
      <div class="kpi-icon"><i class="fas fa-rupee-sign"></i></div>
      <div class="kpi-body">
        <div class="kpi-value" style="font-size:1.15rem;">₹{{ number_format($posTodaySales, 0) }}</div>
        <div class="kpi-label">Today's POS Sales</div>
      </div>
    </div>
    <a href="{{ route('admin.pos.sessions') }}" class="crm2-kpi-card {{ $posActiveSessions > 0 ? 'green' : 'red' }}" style="text-decoration:none;">
      <div class="kpi-icon"><i class="fas fa-store{{ $posActiveSessions > 0 ? '' : '-slash' }}"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ $posActiveSessions }}</div><div class="kpi-label">Active Sessions</div></div>
    </a>
    <a href="{{ route('admin.blog.index') }}" class="crm2-kpi-card indigo" style="text-decoration:none;">
      <div class="kpi-icon"><i class="fas fa-pen-nib"></i></div>
      <div class="kpi-body">
        <div class="kpi-value">{{ number_format($sitePosts) }}</div>
        <div class="kpi-label">Published Posts</div>
        @if($siteDrafts > 0)
        <div class="kpi-change" style="color:var(--crm-secondary);"><i class="fas fa-file"></i> {{ $siteDrafts }} drafts</div>
        @endif
      </div>
    </a>
    <a href="{{ route('admin.users.index') }}" class="crm2-kpi-card blue" style="text-decoration:none;">
      <div class="kpi-icon"><i class="fas fa-users"></i></div>
      <div class="kpi-body"><div class="kpi-value">{{ number_format($siteUsers) }}</div><div class="kpi-label">Team Members</div></div>
    </a>
  </div>

  {{-- ── Recent Activity Tables ────────────────────────────────────────────── --}}
  <div style="display:flex; align-items:center; gap:10px; margin:0 0 14px;">
    <span style="font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--crm-secondary);">Recent Activity</span>
    <div style="flex:1; height:1px; background:var(--crm-border);"></div>
  </div>
  <div class="xn-charts-grid">

    {{-- Recent Leads --}}
    <div class="crm2-card">
      <div class="crm2-card-header">
        <h3><i class="fas fa-user-tag"></i> Recent Leads</h3>
        <a href="{{ route('admin.crm.leads') }}" class="crm2-btn crm2-btn-ghost btn-sm">View All</a>
      </div>
      <div class="crm2-card-body p-0">
        @if($recentLeads->isEmpty())
          <div class="crm2-empty" style="padding:2rem; text-align:center; color:var(--crm-secondary);"><i class="fas fa-user-tag" style="font-size:2rem; margin-bottom:0.5rem; display:block; opacity:0.3;"></i><p style="margin:0; font-size:0.85rem;">No leads yet.</p></div>
        @else
        <table class="crm2-table">
          <thead><tr><th>Name</th><th>Source</th><th>Status</th><th>Added</th></tr></thead>
          <tbody>
            @foreach($recentLeads as $lead)
            <tr>
              <td style="font-weight:500;">{{ $lead->name ?? ($lead->first_name . ' ' . $lead->last_name) }}</td>
              <td><span class="crm2-badge" style="background:rgba(99,102,241,0.1);color:#818cf8;">{{ $lead->source ?? '—' }}</span></td>
              <td><span class="crm2-badge status-{{ strtolower(str_replace(' ','_',$lead->status ?? 'new')) }}">{{ $lead->status ?? 'New' }}</span></td>
              <td style="color:var(--crm-secondary); font-size:0.78rem;">{{ $lead->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>

    {{-- Recent Invoices --}}
    <div class="crm2-card">
      <div class="crm2-card-header">
        <h3><i class="fas fa-receipt"></i> Recent Invoices</h3>
        <a href="{{ route('admin.crm2.inventory.invoices') }}" class="crm2-btn crm2-btn-ghost btn-sm">View All</a>
      </div>
      <div class="crm2-card-body p-0">
        @if($recentInvoices->isEmpty())
          <div class="crm2-empty" style="padding:2rem; text-align:center; color:var(--crm-secondary);"><i class="fas fa-receipt" style="font-size:2rem; margin-bottom:0.5rem; display:block; opacity:0.3;"></i><p style="margin:0; font-size:0.85rem;">No invoices yet.</p></div>
        @else
        <table class="crm2-table">
          <thead><tr><th>Invoice #</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
          <tbody>
            @foreach($recentInvoices as $inv)
            <tr>
              <td style="font-weight:500; font-size:0.82rem;">{{ $inv->invoice_number ?? '—' }}</td>
              <td style="font-weight:600;">₹{{ number_format($inv->grand_total ?? 0, 0) }}</td>
              <td><span class="crm2-badge status-{{ strtolower($inv->status ?? 'draft') }}">{{ $inv->status ?? 'Draft' }}</span></td>
              <td style="color:var(--crm-secondary); font-size:0.78rem;">{{ $inv->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>

  </div>

  <div class="xn-charts-grid">

    {{-- Recent Deals --}}
    <div class="crm2-card">
      <div class="crm2-card-header">
        <h3><i class="fas fa-handshake"></i> Recent Deals</h3>
        <a href="{{ route('admin.crm2.sales.deals') }}" class="crm2-btn crm2-btn-ghost btn-sm">View All</a>
      </div>
      <div class="crm2-card-body p-0">
        @if($recentDeals->isEmpty())
          <div class="crm2-empty" style="padding:2rem; text-align:center; color:var(--crm-secondary);"><i class="fas fa-handshake" style="font-size:2rem; margin-bottom:0.5rem; display:block; opacity:0.3;"></i><p style="margin:0; font-size:0.85rem;">No deals yet.</p></div>
        @else
        <table class="crm2-table">
          <thead><tr><th>Deal Name</th><th>Value</th><th>Stage</th><th>Added</th></tr></thead>
          <tbody>
            @foreach($recentDeals as $deal)
            <tr>
              <td style="font-weight:500;">{{ Str::limit($deal->name ?? $deal->deal_name ?? 'Untitled', 28) }}</td>
              <td style="font-weight:600;">₹{{ number_format($deal->amount ?? 0, 0) }}</td>
              <td><span class="crm2-badge stage-{{ strtolower(str_replace(' ','_',$deal->stage ?? 'prospecting')) }}">{{ ucwords(str_replace('_', ' ', $deal->stage ?? 'Prospecting')) }}</span></td>
              <td style="color:var(--crm-secondary); font-size:0.78rem;">{{ $deal->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>

    {{-- Recent Blog Posts --}}
    <div class="crm2-card">
      <div class="crm2-card-header">
        <h3><i class="fas fa-pen-nib"></i> Recent Posts</h3>
        <a href="{{ route('admin.blog.index') }}" class="crm2-btn crm2-btn-ghost btn-sm">View All</a>
      </div>
      <div class="crm2-card-body p-0">
        @if($recentPosts->isEmpty())
          <div class="crm2-empty" style="padding:2rem; text-align:center; color:var(--crm-secondary);"><i class="fas fa-pen-nib" style="font-size:2rem; margin-bottom:0.5rem; display:block; opacity:0.3;"></i><p style="margin:0; font-size:0.85rem;">No posts yet.</p></div>
        @else
        <table class="crm2-table">
          <thead><tr><th>Title</th><th>Status</th><th>Published</th></tr></thead>
          <tbody>
            @foreach($recentPosts as $post)
            <tr>
              <td style="font-weight:500;">{{ Str::limit($post->title, 35) }}</td>
              <td><span class="crm2-badge status-{{ $post->status === 'published' ? 'active' : 'draft' }}">{{ ucfirst($post->status) }}</span></td>
              <td style="color:var(--crm-secondary); font-size:0.78rem;">{{ $post->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>

  </div>

  {{-- ── Quick Actions ─────────────────────────────────────────────────────── --}}
  <div style="display:flex; align-items:center; gap:10px; margin:0 0 14px;">
    <span style="font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--crm-secondary);">Quick Actions</span>
    <div style="flex:1; height:1px; background:var(--crm-border);"></div>
  </div>
  <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(200px,1fr)); gap:10px; margin-bottom:32px;">
    @php
    $quickActions = [
        ['route' => 'admin.crm2.sales.accounts.create', 'icon' => 'fas fa-building',      'color' => '#60a5fa', 'label' => 'New Account'],
        ['route' => 'admin.crm2.sales.contacts.create', 'icon' => 'fas fa-user-plus',     'color' => '#2dd4bf', 'label' => 'New Contact'],
        ['route' => 'admin.crm2.sales.deals.create',    'icon' => 'fas fa-handshake',     'color' => '#4ade80', 'label' => 'New Deal'],
        ['route' => 'admin.crm2.inventory.quotes.create','icon' => 'fas fa-file-invoice', 'color' => '#818cf8', 'label' => 'New Quote'],
        ['route' => 'admin.crm2.inventory.sales-orders.create','icon' => 'fas fa-shopping-cart','color' => '#4ade80','label' => 'New Sales Order'],
        ['route' => 'admin.crm2.inventory.invoices.create','icon' => 'fas fa-receipt',    'color' => '#f87171', 'label' => 'New Invoice'],
        ['route' => 'admin.crm2.inventory.purchase-orders.create','icon' => 'fas fa-truck','color' => '#fbbf24','label' => 'New Purchase Order'],
        ['route' => 'admin.ecommerce.products.create',  'icon' => 'fas fa-box-open',      'color' => '#f472b6', 'label' => 'Add Product'],
        ['route' => 'admin.pos.terminal',               'icon' => 'fas fa-cash-register', 'color' => '#2dd4bf', 'label' => 'POS Terminal'],
        ['route' => 'admin.blog.create',                'icon' => 'fas fa-pen-nib',       'color' => '#818cf8', 'label' => 'New Blog Post'],
        ['route' => 'admin.calendar.index',             'icon' => 'fas fa-calendar-alt',  'color' => '#60a5fa', 'label' => 'Calendar'],
        ['route' => 'admin.crm.conversations',          'icon' => 'fas fa-comments',      'color' => '#fbbf24', 'label' => 'Chat Monitor'],
    ];
    @endphp
    @foreach($quickActions as $qa)
    <a href="{{ route($qa['route']) }}" style="display:flex; align-items:center; gap:10px; padding:12px 16px; background:var(--crm-surface); border:1px solid var(--crm-border); border-radius:var(--crm-radius); font-size:0.82rem; font-weight:500; color:var(--crm-text); text-decoration:none; transition:var(--crm-transition);"
       onmouseover="this.style.borderColor='var(--crm-primary)'; this.style.background='var(--crm-hover)';"
       onmouseout="this.style.borderColor='var(--crm-border)'; this.style.background='var(--crm-surface)';">
      <i class="{{ $qa['icon'] }}" style="color:{{ $qa['color'] }}; font-size:0.95rem; width:16px; text-align:center;"></i>
      {{ $qa['label'] }}
    </a>
    @endforeach
  </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = 'Inter, -apple-system, sans-serif';
Chart.defaults.color = '#94a3b8';

// ── Revenue Chart ──────────────────────────────────────────────────────────
const revData = @json($monthlyRevenue);
if (document.getElementById('revenueChart')) {
  new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
      labels: revData.length ? revData.map(d => d.month) : ['Jan','Feb','Mar','Apr','May','Jun'],
      datasets: [{
        label: 'Revenue (₹)',
        data: revData.length ? revData.map(d => d.revenue) : [0,0,0,0,0,0],
        borderColor: '#6366f1',
        backgroundColor: 'rgba(99,102,241,0.12)',
        fill: true,
        tension: 0.4,
        pointRadius: 5,
        pointBackgroundColor: '#6366f1',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { callback: v => '₹' + v.toLocaleString() } },
        x: { grid: { color: 'rgba(255,255,255,0.05)' } }
      }
    }
  });
}

// ── Pipeline Chart ─────────────────────────────────────────────────────────
const pipeData = @json($dealsByStage);
const stageColors = {
  prospecting: '#6366f1', qualification: '#8b5cf6',
  proposal: '#f59e0b', negotiation: '#f97316',
  closed_won: '#22c55e', closed_lost: '#ef4444'
};
if (document.getElementById('pipelineChart')) {
  new Chart(document.getElementById('pipelineChart'), {
    type: 'bar',
    data: {
      labels: pipeData.map(d => d.stage.replace(/_/g,' ').replace(/\b\w/g, l => l.toUpperCase())),
      datasets: [{
        label: 'Deals',
        data: pipeData.map(d => d.count),
        backgroundColor: pipeData.map(d => stageColors[d.stage] || '#6366f1'),
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { stepSize: 1 } },
        x: { grid: { color: 'rgba(255,255,255,0.05)' } }
      }
    }
  });
}
</script>
@endpush
@endsection
