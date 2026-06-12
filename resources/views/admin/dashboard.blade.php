@extends('layouts.admin')
@section('title', 'Dashboard')

@push('styles')
<style>
/* ── Dashboard Variables ─────────────────────────────────────── */
.xn-dash {
  --card-bg:      #1e2433;
  --card-border:  rgba(99,102,241,0.18);
  --card-radius:  10px;
  --card-shadow:  0 2px 12px rgba(0,0,0,0.25);
  --text:         #e2e8f0;
  --text-muted:   #94a3b8;
  --text-dim:     #64748b;
  --page-bg:      #0f1623;
  --section-head: #6366f1;
  padding: 20px 24px;
  background: var(--page-bg);
  min-height: 100vh;
  color: var(--text);
  font-family: 'Inter', sans-serif;
}
[data-theme="light"] .xn-dash {
  --card-bg:      #ffffff;
  --card-border:  #e2e8f0;
  --card-shadow:  0 2px 8px rgba(0,0,0,0.07);
  --text:         #0f172a;
  --text-muted:   #475569;
  --text-dim:     #94a3b8;
  --page-bg:      #f1f5f9;
}

/* ── Welcome Bar ─────────────────────────────────────────────── */
.xn-welcome {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
  flex-wrap: wrap;
  gap: 10px;
}
.xn-welcome h1 {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--text);
  margin: 0;
}
.xn-welcome p {
  font-size: 0.8rem;
  color: var(--text-muted);
  margin: 2px 0 0;
}
.xn-quick-btns {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}
.xn-qbtn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 14px;
  border-radius: 7px;
  font-size: 0.78rem;
  font-weight: 600;
  text-decoration: none;
  transition: opacity 0.15s;
}
.xn-qbtn:hover { opacity: 0.85; }
.xn-qbtn-indigo { background: rgba(99,102,241,0.15); color: #818cf8; border: 1px solid rgba(99,102,241,0.3); }
.xn-qbtn-green  { background: rgba(34,197,94,0.12);  color: #4ade80; border: 1px solid rgba(34,197,94,0.25); }
.xn-qbtn-teal   { background: rgba(20,184,166,0.12); color: #2dd4bf; border: 1px solid rgba(20,184,166,0.25); }
.xn-qbtn-amber  { background: rgba(245,158,11,0.12); color: #fbbf24; border: 1px solid rgba(245,158,11,0.25); }

/* ── Section Header ──────────────────────────────────────────── */
.xn-section-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 20px 0 10px;
}
.xn-section-head h2 {
  font-size: 0.7rem;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--text-dim);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 6px;
}
.xn-section-head h2 i { color: var(--section-head); }
.xn-section-head a {
  font-size: 0.75rem;
  color: #818cf8;
  text-decoration: none;
  font-weight: 500;
}
.xn-section-head a:hover { text-decoration: underline; }

/* ── KPI Grid ────────────────────────────────────────────────── */
.xn-kpi-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: 10px;
  margin-bottom: 4px;
}
.xn-kpi {
  background: var(--card-bg);
  border: 1px solid var(--card-border);
  border-radius: var(--card-radius);
  padding: 14px 16px;
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
  color: var(--text);
  transition: transform 0.15s, box-shadow 0.15s;
  position: relative;
  overflow: hidden;
  box-shadow: var(--card-shadow);
}
.xn-kpi::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 2px;
}
.xn-kpi:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.3); }
.xn-kpi-icon {
  width: 38px; height: 38px;
  border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem;
  flex-shrink: 0;
}
.xn-kpi-body { min-width: 0; }
.xn-kpi-val {
  font-size: 1.3rem;
  font-weight: 700;
  line-height: 1;
  color: var(--text);
}
.xn-kpi-lbl {
  font-size: 0.72rem;
  color: var(--text-muted);
  margin-top: 3px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.xn-kpi-sub {
  font-size: 0.68rem;
  margin-top: 2px;
  display: flex;
  align-items: center;
  gap: 3px;
}

/* Colour variants */
.xn-kpi.blue   ::before { background: #3b82f6; }
.xn-kpi.blue   .xn-kpi-icon { background: rgba(59,130,246,0.12); color: #60a5fa; }
.xn-kpi.blue::before { background: #3b82f6; }

.xn-kpi.purple::before { background: #8b5cf6; }
.xn-kpi.purple .xn-kpi-icon { background: rgba(139,92,246,0.12); color: #a78bfa; }

.xn-kpi.indigo::before { background: #6366f1; }
.xn-kpi.indigo .xn-kpi-icon { background: rgba(99,102,241,0.12); color: #818cf8; }

.xn-kpi.green::before { background: #22c55e; }
.xn-kpi.green .xn-kpi-icon { background: rgba(34,197,94,0.12); color: #4ade80; }

.xn-kpi.teal::before { background: #14b8a6; }
.xn-kpi.teal .xn-kpi-icon { background: rgba(20,184,166,0.12); color: #2dd4bf; }

.xn-kpi.orange::before { background: #f97316; }
.xn-kpi.orange .xn-kpi-icon { background: rgba(249,115,22,0.12); color: #fb923c; }

.xn-kpi.yellow::before { background: #f59e0b; }
.xn-kpi.yellow .xn-kpi-icon { background: rgba(245,158,11,0.12); color: #fbbf24; }

.xn-kpi.red::before { background: #ef4444; }
.xn-kpi.red .xn-kpi-icon { background: rgba(239,68,68,0.12); color: #f87171; }

.xn-kpi.pink::before { background: #ec4899; }
.xn-kpi.pink .xn-kpi-icon { background: rgba(236,72,153,0.12); color: #f472b6; }

/* ── Charts Grid ─────────────────────────────────────────────── */
.xn-charts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 12px;
  margin-bottom: 4px;
}

/* ── Card ────────────────────────────────────────────────────── */
.xn-card {
  background: var(--card-bg);
  border: 1px solid var(--card-border);
  border-radius: var(--card-radius);
  box-shadow: var(--card-shadow);
  overflow: hidden;
}
.xn-card-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 16px;
  border-bottom: 1px solid var(--card-border);
}
.xn-card-head h3 {
  font-size: 0.82rem;
  font-weight: 600;
  color: var(--text);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 7px;
}
.xn-card-head h3 i { color: #818cf8; font-size: 0.85rem; }
.xn-card-head a {
  font-size: 0.72rem;
  color: #818cf8;
  text-decoration: none;
  font-weight: 500;
}
.xn-card-body { padding: 14px 16px; }
.xn-card-body.p0 { padding: 0; }

/* ── Table ───────────────────────────────────────────────────── */
.xn-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.8rem;
}
.xn-table th {
  padding: 8px 14px;
  text-align: left;
  font-size: 0.68rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  color: var(--text-dim);
  background: rgba(255,255,255,0.03);
  border-bottom: 1px solid var(--card-border);
}
[data-theme="light"] .xn-table th { background: #f8fafc; }
.xn-table td {
  padding: 9px 14px;
  border-bottom: 1px solid var(--card-border);
  color: var(--text);
  vertical-align: middle;
}
.xn-table tbody tr:last-child td { border-bottom: none; }
.xn-table tbody tr:hover td { background: rgba(255,255,255,0.03); }
[data-theme="light"] .xn-table tbody tr:hover td { background: #f8fafc; }

/* ── Badge ───────────────────────────────────────────────────── */
.xn-badge {
  display: inline-flex;
  align-items: center;
  padding: 2px 8px;
  border-radius: 20px;
  font-size: 0.68rem;
  font-weight: 600;
  text-transform: capitalize;
}
.xn-badge-green  { background: rgba(34,197,94,0.12);  color: #4ade80; }
.xn-badge-blue   { background: rgba(59,130,246,0.12); color: #60a5fa; }
.xn-badge-amber  { background: rgba(245,158,11,0.12); color: #fbbf24; }
.xn-badge-red    { background: rgba(239,68,68,0.12);  color: #f87171; }
.xn-badge-gray   { background: rgba(148,163,184,0.12);color: #94a3b8; }
.xn-badge-purple { background: rgba(139,92,246,0.12); color: #a78bfa; }
.xn-badge-indigo { background: rgba(99,102,241,0.12); color: #818cf8; }
.xn-badge-orange { background: rgba(249,115,22,0.12); color: #fb923c; }

/* ── Quick Actions ───────────────────────────────────────────── */
.xn-actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: 8px;
}
.xn-action-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 12px;
  background: var(--card-bg);
  border: 1px solid var(--card-border);
  border-radius: var(--card-radius);
  font-size: 0.78rem;
  font-weight: 500;
  color: var(--text);
  text-decoration: none;
  transition: border-color 0.15s, background 0.15s;
  box-shadow: var(--card-shadow);
}
.xn-action-btn:hover {
  border-color: #6366f1;
  background: rgba(99,102,241,0.08);
  color: var(--text);
}
.xn-action-btn i { font-size: 0.85rem; width: 14px; text-align: center; }

/* ── Recent 2-col grid ───────────────────────────────────────── */
.xn-recent-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 12px;
}

/* ── Divider ─────────────────────────────────────────────────── */
.xn-divider {
  height: 1px;
  background: var(--card-border);
  margin: 18px 0 0;
}
</style>
@endpush

@section('content')
<div class="xn-dash">

  {{-- ── Welcome Bar ─────────────────────────────────────────────── --}}
  <div class="xn-welcome">
    <div>
      <h1><i class="fas fa-tachometer-alt" style="color:#6366f1;margin-right:8px;"></i>Dashboard</h1>
      <p>Welcome back, {{ auth()->user()->name }}. Here's your business overview for today.</p>
    </div>
    <div class="xn-quick-btns">
      <a href="{{ route('admin.crm2.inventory.quotes.create') }}" class="xn-qbtn xn-qbtn-indigo"><i class="fas fa-file-invoice"></i> New Quote</a>
      <a href="{{ route('admin.crm2.sales.deals.create') }}"    class="xn-qbtn xn-qbtn-green"><i class="fas fa-handshake"></i> New Deal</a>
      <a href="{{ route('admin.pos.terminal') }}"               class="xn-qbtn xn-qbtn-teal"><i class="fas fa-cash-register"></i> POS Terminal</a>
      <a href="{{ route('admin.crm2.analysis') }}"              class="xn-qbtn xn-qbtn-amber"><i class="fas fa-chart-line"></i> View Analysis</a>
    </div>
  </div>

  {{-- ── CRM ────────────────────────────────────────────────────── --}}
  <div class="xn-section-head">
    <h2><i class="fas fa-users"></i> CRM</h2>
    <a href="{{ route('admin.crm2.analysis') }}">View Analysis &rarr;</a>
  </div>
  <div class="xn-kpi-grid">
    <a href="{{ route('admin.crm2.sales.accounts') }}" class="xn-kpi indigo">
      <div class="xn-kpi-icon"><i class="fas fa-building"></i></div>
      <div class="xn-kpi-body"><div class="xn-kpi-val">{{ number_format($crmAccounts) }}</div><div class="xn-kpi-lbl">Accounts</div></div>
    </a>
    <a href="{{ route('admin.crm2.sales.contacts') }}" class="xn-kpi blue">
      <div class="xn-kpi-icon"><i class="fas fa-address-book"></i></div>
      <div class="xn-kpi-body"><div class="xn-kpi-val">{{ number_format($crmContacts) }}</div><div class="xn-kpi-lbl">Contacts</div></div>
    </a>
    <a href="{{ route('admin.crm2.sales.leads') }}" class="xn-kpi purple">
      <div class="xn-kpi-icon"><i class="fas fa-user-tag"></i></div>
      <div class="xn-kpi-body"><div class="xn-kpi-val">{{ number_format($crmLeads) }}</div><div class="xn-kpi-lbl">Leads</div></div>
    </a>
    <a href="{{ route('admin.crm2.sales.deals') }}" class="xn-kpi orange">
      <div class="xn-kpi-icon"><i class="fas fa-funnel-dollar"></i></div>
      <div class="xn-kpi-body">
        <div class="xn-kpi-val">{{ number_format($crmDeals) }}</div>
        <div class="xn-kpi-lbl">Deals</div>
        @if($crmDealsWon > 0)<div class="xn-kpi-sub" style="color:#4ade80;"><i class="fas fa-check-circle"></i> {{ $crmDealsWon }} won</div>@endif
      </div>
    </a>
    <a href="{{ route('admin.crm2.activities.tasks') }}" class="xn-kpi teal">
      <div class="xn-kpi-icon"><i class="fas fa-tasks"></i></div>
      <div class="xn-kpi-body"><div class="xn-kpi-val">{{ number_format($crmActivities) }}</div><div class="xn-kpi-lbl">Open Activities</div></div>
    </a>
    <div class="xn-kpi green" style="cursor:default;">
      <div class="xn-kpi-icon"><i class="fas fa-rupee-sign"></i></div>
      <div class="xn-kpi-body"><div class="xn-kpi-val">₹{{ number_format($crmWonValue, 0) }}</div><div class="xn-kpi-lbl">Won Revenue</div></div>
    </div>
    <div class="xn-kpi yellow" style="cursor:default;">
      <div class="xn-kpi-icon"><i class="fas fa-chart-bar"></i></div>
      <div class="xn-kpi-body"><div class="xn-kpi-val">₹{{ number_format($crmPipelineVal, 0) }}</div><div class="xn-kpi-lbl">Pipeline Value</div></div>
    </div>
  </div>

  {{-- ── Inventory ───────────────────────────────────────────────── --}}
  <div class="xn-section-head">
    <h2><i class="fas fa-boxes"></i> Inventory</h2>
    <a href="{{ route('admin.crm2.inventory.invoices') }}">View All &rarr;</a>
  </div>
  <div class="xn-kpi-grid">
    <a href="{{ route('admin.crm2.inventory.quotes') }}" class="xn-kpi indigo">
      <div class="xn-kpi-icon"><i class="fas fa-file-invoice"></i></div>
      <div class="xn-kpi-body"><div class="xn-kpi-val">{{ number_format($invQuotes) }}</div><div class="xn-kpi-lbl">Quotes</div></div>
    </a>
    <a href="{{ route('admin.crm2.inventory.sales-orders') }}" class="xn-kpi green">
      <div class="xn-kpi-icon"><i class="fas fa-shopping-cart"></i></div>
      <div class="xn-kpi-body"><div class="xn-kpi-val">{{ number_format($invSalesOrders) }}</div><div class="xn-kpi-lbl">Sales Orders</div></div>
    </a>
    <a href="{{ route('admin.crm2.inventory.purchase-orders') }}" class="xn-kpi orange">
      <div class="xn-kpi-icon"><i class="fas fa-truck"></i></div>
      <div class="xn-kpi-body"><div class="xn-kpi-val">{{ number_format($invPOs) }}</div><div class="xn-kpi-lbl">Purchase Orders</div></div>
    </a>
    <a href="{{ route('admin.crm2.inventory.invoices') }}" class="xn-kpi red">
      <div class="xn-kpi-icon"><i class="fas fa-receipt"></i></div>
      <div class="xn-kpi-body">
        <div class="xn-kpi-val">{{ number_format($invInvoices) }}</div>
        <div class="xn-kpi-lbl">Invoices</div>
        @if($invInvoicesDue > 0)<div class="xn-kpi-sub" style="color:#fbbf24;"><i class="fas fa-clock"></i> {{ $invInvoicesDue }} pending</div>@endif
      </div>
    </a>
    <a href="{{ route('admin.crm2.inventory.vendors') }}" class="xn-kpi teal">
      <div class="xn-kpi-icon"><i class="fas fa-store"></i></div>
      <div class="xn-kpi-body"><div class="xn-kpi-val">{{ number_format($invVendors) }}</div><div class="xn-kpi-lbl">Vendors</div></div>
    </a>
    <div class="xn-kpi blue" style="cursor:default;">
      <div class="xn-kpi-icon"><i class="fas fa-rupee-sign"></i></div>
      <div class="xn-kpi-body"><div class="xn-kpi-val">₹{{ number_format($invRevenue, 0) }}</div><div class="xn-kpi-lbl">Revenue (Paid)</div></div>
    </div>
  </div>

  {{-- ── Charts ──────────────────────────────────────────────────── --}}
  <div class="xn-section-head" style="margin-top:20px;">
    <h2><i class="fas fa-chart-area"></i> Analytics</h2>
  </div>
  <div class="xn-charts-grid">
    <div class="xn-card">
      <div class="xn-card-head"><h3><i class="fas fa-chart-area"></i> Invoice Revenue (Last 6 Months)</h3></div>
      <div class="xn-card-body"><canvas id="revenueChart" height="200"></canvas></div>
    </div>
    <div class="xn-card">
      <div class="xn-card-head"><h3><i class="fas fa-funnel-dollar"></i> Deal Pipeline by Stage</h3></div>
      <div class="xn-card-body"><canvas id="pipelineChart" height="200"></canvas></div>
    </div>
  </div>

  {{-- ── Other Modules ───────────────────────────────────────────── --}}
  <div class="xn-section-head">
    <h2><i class="fas fa-th-large"></i> Other Modules</h2>
  </div>
  <div class="xn-kpi-grid">
    <a href="{{ route('admin.ecommerce.products') }}" class="xn-kpi pink">
      <div class="xn-kpi-icon"><i class="fas fa-box-open"></i></div>
      <div class="xn-kpi-body">
        <div class="xn-kpi-val">{{ number_format($ecomProducts) }}</div>
        <div class="xn-kpi-lbl">Products</div>
        <div class="xn-kpi-sub" style="color:#4ade80;"><i class="fas fa-circle" style="font-size:0.5rem;"></i> {{ number_format($ecomActive) }} active</div>
      </div>
    </a>
    <a href="{{ route('admin.pos.orders') }}" class="xn-kpi teal">
      <div class="xn-kpi-icon"><i class="fas fa-receipt"></i></div>
      <div class="xn-kpi-body"><div class="xn-kpi-val">{{ number_format($posOrders) }}</div><div class="xn-kpi-lbl">POS Orders</div></div>
    </a>
    <div class="xn-kpi yellow" style="cursor:default;">
      <div class="xn-kpi-icon"><i class="fas fa-rupee-sign"></i></div>
      <div class="xn-kpi-body"><div class="xn-kpi-val">₹{{ number_format($posTodaySales, 0) }}</div><div class="xn-kpi-lbl">Today's POS Sales</div></div>
    </div>
    <a href="{{ route('admin.pos.sessions') }}" class="xn-kpi green">
      <div class="xn-kpi-icon"><i class="fas fa-desktop"></i></div>
      <div class="xn-kpi-body"><div class="xn-kpi-val">{{ number_format($posActiveSessions) }}</div><div class="xn-kpi-lbl">Active Sessions</div></div>
    </a>
    <a href="{{ route('admin.blog.index') }}" class="xn-kpi purple">
      <div class="xn-kpi-icon"><i class="fas fa-pen-nib"></i></div>
      <div class="xn-kpi-body"><div class="xn-kpi-val">{{ number_format($sitePosts) }}</div><div class="xn-kpi-lbl">Published Posts</div></div>
    </a>
    <a href="{{ route('admin.users.index') }}" class="xn-kpi blue">
      <div class="xn-kpi-icon"><i class="fas fa-users-cog"></i></div>
      <div class="xn-kpi-body"><div class="xn-kpi-val">{{ number_format($siteUsers) }}</div><div class="xn-kpi-lbl">Team Members</div></div>
    </a>
  </div>

  {{-- ── Recent Activity ─────────────────────────────────────────── --}}
  <div class="xn-section-head">
    <h2><i class="fas fa-history"></i> Recent Activity</h2>
  </div>
  <div class="xn-recent-grid">

    {{-- Recent Leads --}}
    <div class="xn-card">
      <div class="xn-card-head">
        <h3><i class="fas fa-user-tag"></i> Recent Leads</h3>
        <a href="{{ route('admin.crm2.sales.leads') }}">View All</a>
      </div>
      <div class="xn-card-body p0">
        @if($recentLeads->isEmpty())
          <div style="padding:20px;text-align:center;color:var(--text-dim);font-size:0.8rem;">No leads yet.</div>
        @else
        <table class="xn-table">
          <thead><tr><th>Name</th><th>Source</th><th>Status</th><th>Added</th></tr></thead>
          <tbody>
            @foreach($recentLeads as $lead)
            <tr>
              <td style="font-weight:500;">{{ Str::limit($lead->first_name.' '.$lead->last_name, 22) }}</td>
              <td style="color:var(--text-muted);font-size:0.75rem;">{{ $lead->lead_source ?? '—' }}</td>
              <td><span class="xn-badge xn-badge-blue">{{ $lead->status ?? 'new' }}</span></td>
              <td style="color:var(--text-dim);font-size:0.72rem;">{{ $lead->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>

    {{-- Recent Invoices --}}
    <div class="xn-card">
      <div class="xn-card-head">
        <h3><i class="fas fa-receipt"></i> Recent Invoices</h3>
        <a href="{{ route('admin.crm2.inventory.invoices') }}">View All</a>
      </div>
      <div class="xn-card-body p0">
        @if($recentInvoices->isEmpty())
          <div style="padding:20px;text-align:center;color:var(--text-dim);font-size:0.8rem;">No invoices yet.</div>
        @else
        <table class="xn-table">
          <thead><tr><th>Invoice #</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
          <tbody>
            @foreach($recentInvoices as $inv)
            <tr>
              <td style="font-weight:500;font-size:0.75rem;">{{ $inv->invoice_number ?? 'N/A' }}</td>
              <td style="font-weight:600;">₹{{ number_format($inv->grand_total ?? 0, 0) }}</td>
              <td>
                @php $s = strtolower($inv->status ?? 'draft'); @endphp
                <span class="xn-badge {{ $s==='paid'?'xn-badge-green':($s==='sent'?'xn-badge-blue':($s==='overdue'?'xn-badge-red':'xn-badge-gray')) }}">{{ ucfirst($s) }}</span>
              </td>
              <td style="color:var(--text-dim);font-size:0.72rem;">{{ $inv->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>

    {{-- Recent Deals --}}
    <div class="xn-card">
      <div class="xn-card-head">
        <h3><i class="fas fa-handshake"></i> Recent Deals</h3>
        <a href="{{ route('admin.crm2.sales.deals') }}">View All</a>
      </div>
      <div class="xn-card-body p0">
        @if($recentDeals->isEmpty())
          <div style="padding:20px;text-align:center;color:var(--text-dim);font-size:0.8rem;">No deals yet.</div>
        @else
        <table class="xn-table">
          <thead><tr><th>Deal</th><th>Value</th><th>Stage</th></tr></thead>
          <tbody>
            @foreach($recentDeals as $deal)
            <tr>
              <td style="font-weight:500;">{{ Str::limit($deal->name ?? $deal->deal_name ?? 'Untitled', 26) }}</td>
              <td style="font-weight:600;">₹{{ number_format($deal->amount ?? 0, 0) }}</td>
              <td>
                @php $st = strtolower(str_replace(' ','_',$deal->stage ?? 'prospecting')); @endphp
                <span class="xn-badge {{ $st==='closed_won'?'xn-badge-green':($st==='closed_lost'?'xn-badge-red':($st==='negotiation'?'xn-badge-orange':($st==='proposal'?'xn-badge-amber':'xn-badge-indigo'))) }}">{{ ucwords(str_replace('_',' ',$st)) }}</span>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>

    {{-- Recent Posts --}}
    <div class="xn-card">
      <div class="xn-card-head">
        <h3><i class="fas fa-pen-nib"></i> Recent Posts</h3>
        <a href="{{ route('admin.blog.index') }}">View All</a>
      </div>
      <div class="xn-card-body p0">
        @if($recentPosts->isEmpty())
          <div style="padding:20px;text-align:center;color:var(--text-dim);font-size:0.8rem;">No posts yet.</div>
        @else
        <table class="xn-table">
          <thead><tr><th>Title</th><th>Status</th><th>Published</th></tr></thead>
          <tbody>
            @foreach($recentPosts as $post)
            <tr>
              <td style="font-weight:500;">{{ Str::limit($post->title, 32) }}</td>
              <td><span class="xn-badge {{ $post->status==='published'?'xn-badge-green':'xn-badge-gray' }}">{{ ucfirst($post->status) }}</span></td>
              <td style="color:var(--text-dim);font-size:0.72rem;">{{ $post->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>

  </div>

  {{-- ── Quick Actions ───────────────────────────────────────────── --}}
  <div class="xn-section-head">
    <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
  </div>
  <div class="xn-actions-grid">
    @php
    $actions = [
      ['route'=>'admin.crm2.sales.accounts.create',          'icon'=>'fas fa-building',      'color'=>'#60a5fa','label'=>'New Account'],
      ['route'=>'admin.crm2.sales.contacts.create',          'icon'=>'fas fa-user-plus',      'color'=>'#2dd4bf','label'=>'New Contact'],
      ['route'=>'admin.crm2.sales.deals.create',             'icon'=>'fas fa-handshake',      'color'=>'#4ade80','label'=>'New Deal'],
      ['route'=>'admin.crm2.inventory.quotes.create',        'icon'=>'fas fa-file-invoice',   'color'=>'#818cf8','label'=>'New Quote'],
      ['route'=>'admin.crm2.inventory.sales-orders.create',  'icon'=>'fas fa-shopping-cart',  'color'=>'#4ade80','label'=>'New Sales Order'],
      ['route'=>'admin.crm2.inventory.invoices.create',      'icon'=>'fas fa-receipt',        'color'=>'#f87171','label'=>'New Invoice'],
      ['route'=>'admin.crm2.inventory.purchase-orders.create','icon'=>'fas fa-truck',         'color'=>'#fbbf24','label'=>'New Purchase Order'],
      ['route'=>'admin.ecommerce.products.create',           'icon'=>'fas fa-box-open',       'color'=>'#f472b6','label'=>'Add Product'],
      ['route'=>'admin.pos.terminal',                        'icon'=>'fas fa-cash-register',  'color'=>'#2dd4bf','label'=>'POS Terminal'],
      ['route'=>'admin.blog.create',                         'icon'=>'fas fa-pen-nib',        'color'=>'#818cf8','label'=>'New Blog Post'],
      ['route'=>'admin.calendar.index',                      'icon'=>'fas fa-calendar-alt',   'color'=>'#60a5fa','label'=>'Calendar'],
      ['route'=>'admin.chat.index',                   'icon'=>'fas fa-comments',       'color'=>'#fbbf24','label'=>'Chat Monitor'],
    ];
    @endphp
    @foreach($actions as $a)
    <a href="{{ route($a['route']) }}" class="xn-action-btn">
      <i class="{{ $a['icon'] }}" style="color:{{ $a['color'] }};"></i>
      {{ $a['label'] }}
    </a>
    @endforeach
  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = 'Inter, sans-serif';
Chart.defaults.color = '#94a3b8';
const isDark = document.documentElement.getAttribute('data-theme') !== 'light';
const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.06)';

// Revenue Chart
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
        backgroundColor: 'rgba(99,102,241,0.1)',
        fill: true, tension: 0.4, pointRadius: 4,
        pointBackgroundColor: '#6366f1', borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: { grid: { color: gridColor }, ticks: { callback: v => '₹' + v.toLocaleString() } },
        x: { grid: { color: gridColor } }
      }
    }
  });
}

// Pipeline Chart
const pipeData = @json($dealsByStage);
const stageColors = {
  prospecting:'#6366f1', qualification:'#8b5cf6',
  proposal:'#f59e0b', negotiation:'#f97316',
  closed_won:'#22c55e', closed_lost:'#ef4444'
};
if (document.getElementById('pipelineChart')) {
  new Chart(document.getElementById('pipelineChart'), {
    type: 'bar',
    data: {
      labels: pipeData.map(d => d.stage.replace(/_/g,' ').replace(/\b\w/g,l=>l.toUpperCase())),
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
        y: { grid: { color: gridColor }, ticks: { stepSize: 1 } },
        x: { grid: { color: gridColor } }
      }
    }
  });
}
</script>
@endpush
