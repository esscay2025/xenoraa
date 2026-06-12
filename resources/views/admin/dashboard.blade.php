@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('content')

<style>
.db-section-title {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--text-secondary);
    margin: 2rem 0 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.db-section-title i { font-size: 0.85rem; }
.db-stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.db-stat {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1rem 1.1rem;
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
    transition: box-shadow 0.15s;
    text-decoration: none;
    color: inherit;
}
.db-stat:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.15); border-color: var(--primary); }
.db-stat-icon {
    width: 36px; height: 36px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    margin-bottom: 0.25rem;
}
.db-stat-num { font-size: 1.6rem; font-weight: 700; line-height: 1; }
.db-stat-label { font-size: 0.72rem; color: var(--text-secondary); font-weight: 500; }
.db-stat-sub { font-size: 0.68rem; color: var(--text-muted, #6b7280); }

.db-content-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.25rem;
    margin-bottom: 2rem;
}
.db-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.1rem 1.25rem;
}
.db-card-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 0.85rem;
}
.db-card-title {
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.07em;
    color: var(--text-secondary);
}
.db-row {
    padding: 0.55rem 0;
    border-bottom: 1px solid var(--border);
    display: flex; justify-content: space-between; align-items: center;
    gap: 0.5rem;
}
.db-row:last-child { border-bottom: none; }
.db-row-name { font-size: 0.82rem; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 170px; }
.db-row-meta { font-size: 0.72rem; color: var(--text-secondary); white-space: nowrap; }

.db-quick-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 0.75rem;
    margin-bottom: 2rem;
}
.db-quick-btn {
    display: flex; align-items: center; gap: 0.6rem;
    padding: 0.65rem 1rem;
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 10px;
    font-size: 0.8rem; font-weight: 500;
    color: var(--text-primary);
    text-decoration: none;
    transition: all 0.15s;
}
.db-quick-btn:hover { border-color: var(--primary); background: rgba(99,102,241,0.06); color: var(--primary); }
.db-quick-btn i { font-size: 0.9rem; width: 16px; text-align: center; }

/* colour helpers */
.ic-blue   { background: rgba(59,130,246,0.12); color: #60a5fa; }
.ic-green  { background: rgba(34,197,94,0.12);  color: #4ade80; }
.ic-amber  { background: rgba(245,158,11,0.12); color: #fbbf24; }
.ic-red    { background: rgba(239,68,68,0.12);  color: #f87171; }
.ic-purple { background: rgba(168,85,247,0.12); color: #c084fc; }
.ic-indigo { background: rgba(99,102,241,0.12); color: #818cf8; }
.ic-teal   { background: rgba(20,184,166,0.12); color: #2dd4bf; }
.ic-pink   { background: rgba(236,72,153,0.12); color: #f472b6; }
.ic-orange { background: rgba(249,115,22,0.12); color: #fb923c; }
</style>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- CRM MODULE --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<div class="db-section-title">
    <i class="fas fa-handshake ic-blue" style="background:none;"></i> CRM
</div>
<div class="db-stat-grid">
    <a href="{{ route('admin.crm2.sales.accounts') }}" class="db-stat">
        <div class="db-stat-icon ic-blue"><i class="fas fa-building"></i></div>
        <div class="db-stat-num">{{ $crmAccounts }}</div>
        <div class="db-stat-label">Accounts</div>
    </a>
    <a href="{{ route('admin.crm2.sales.contacts') }}" class="db-stat">
        <div class="db-stat-icon ic-teal"><i class="fas fa-address-book"></i></div>
        <div class="db-stat-num">{{ $crmContacts }}</div>
        <div class="db-stat-label">Contacts</div>
    </a>
    <a href="{{ route('admin.crm.leads') }}" class="db-stat">
        <div class="db-stat-icon ic-amber"><i class="fas fa-user-tag"></i></div>
        <div class="db-stat-num">{{ $crmLeads }}</div>
        <div class="db-stat-label">Leads</div>
    </a>
    <a href="{{ route('admin.crm2.sales.deals') }}" class="db-stat">
        <div class="db-stat-icon ic-green"><i class="fas fa-handshake"></i></div>
        <div class="db-stat-num">{{ $crmDeals }}</div>
        <div class="db-stat-label">Deals</div>
        <div class="db-stat-sub">{{ $crmDealsOpen }} open · {{ $crmDealsWon }} won</div>
    </a>
    <a href="{{ route('admin.crm2.activities') }}" class="db-stat">
        <div class="db-stat-icon ic-purple"><i class="fas fa-tasks"></i></div>
        <div class="db-stat-num">{{ $crmActivities }}</div>
        <div class="db-stat-label">Open Activities</div>
    </a>
</div>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- INVENTORY MODULE --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<div class="db-section-title">
    <i class="fas fa-boxes" style="color:#fb923c;"></i> Inventory
</div>
<div class="db-stat-grid">
    <a href="{{ route('admin.crm2.inventory.quotes') }}" class="db-stat">
        <div class="db-stat-icon ic-indigo"><i class="fas fa-file-invoice"></i></div>
        <div class="db-stat-num">{{ $invQuotes }}</div>
        <div class="db-stat-label">Quotes</div>
    </a>
    <a href="{{ route('admin.crm2.inventory.sales-orders') }}" class="db-stat">
        <div class="db-stat-icon ic-green"><i class="fas fa-shopping-cart"></i></div>
        <div class="db-stat-num">{{ $invSalesOrders }}</div>
        <div class="db-stat-label">Sales Orders</div>
    </a>
    <a href="{{ route('admin.crm2.inventory.purchase-orders') }}" class="db-stat">
        <div class="db-stat-icon ic-amber"><i class="fas fa-truck"></i></div>
        <div class="db-stat-num">{{ $invPOs }}</div>
        <div class="db-stat-label">Purchase Orders</div>
    </a>
    <a href="{{ route('admin.crm2.inventory.invoices') }}" class="db-stat">
        <div class="db-stat-icon ic-red"><i class="fas fa-receipt"></i></div>
        <div class="db-stat-num">{{ $invInvoices }}</div>
        <div class="db-stat-label">Invoices</div>
        <div class="db-stat-sub">{{ $invInvoicesDue }} pending</div>
    </a>
    <a href="{{ route('admin.crm2.inventory.vendors') }}" class="db-stat">
        <div class="db-stat-icon ic-teal"><i class="fas fa-store"></i></div>
        <div class="db-stat-num">{{ $invVendors }}</div>
        <div class="db-stat-label">Vendors</div>
    </a>
    <div class="db-stat">
        <div class="db-stat-icon ic-green"><i class="fas fa-rupee-sign"></i></div>
        <div class="db-stat-num" style="font-size:1.2rem;">₹{{ number_format($invRevenue, 0) }}</div>
        <div class="db-stat-label">Revenue (Paid)</div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- E-COMMERCE MODULE --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<div class="db-section-title">
    <i class="fas fa-shopping-bag" style="color:#f472b6;"></i> E-Commerce
</div>
<div class="db-stat-grid">
    <a href="{{ route('admin.ecommerce.products') }}" class="db-stat">
        <div class="db-stat-icon ic-pink"><i class="fas fa-box-open"></i></div>
        <div class="db-stat-num">{{ $ecomProducts }}</div>
        <div class="db-stat-label">Products</div>
        <div class="db-stat-sub">{{ $ecomActive }} active</div>
    </a>
    <a href="{{ route('admin.ecommerce.categories') }}" class="db-stat">
        <div class="db-stat-icon ic-purple"><i class="fas fa-tags"></i></div>
        <div class="db-stat-label" style="font-size:0.8rem; margin-top:0.25rem;">Categories</div>
    </a>
    <a href="{{ route('admin.ecommerce.reviews') }}" class="db-stat">
        <div class="db-stat-icon ic-amber"><i class="fas fa-star"></i></div>
        <div class="db-stat-label" style="font-size:0.8rem; margin-top:0.25rem;">Reviews</div>
    </a>
</div>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- POINT OF SALE MODULE --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<div class="db-section-title">
    <i class="fas fa-cash-register" style="color:#2dd4bf;"></i> Point of Sale
</div>
<div class="db-stat-grid">
    <a href="{{ route('admin.pos.orders') }}" class="db-stat">
        <div class="db-stat-icon ic-teal"><i class="fas fa-receipt"></i></div>
        <div class="db-stat-num">{{ $posOrders }}</div>
        <div class="db-stat-label">Total Orders</div>
    </a>
    <div class="db-stat">
        <div class="db-stat-icon ic-green"><i class="fas fa-rupee-sign"></i></div>
        <div class="db-stat-num" style="font-size:1.2rem;">₹{{ number_format($posTodaySales, 0) }}</div>
        <div class="db-stat-label">Today's Sales</div>
    </div>
    <a href="{{ route('admin.pos.sessions') }}" class="db-stat">
        <div class="db-stat-icon {{ $posActiveSessions > 0 ? 'ic-green' : 'ic-red' }}"><i class="fas fa-store{{ $posActiveSessions > 0 ? '' : '-slash' }}"></i></div>
        <div class="db-stat-num">{{ $posActiveSessions }}</div>
        <div class="db-stat-label">Active Sessions</div>
    </a>
    <a href="{{ route('admin.pos.terminal') }}" class="db-stat">
        <div class="db-stat-icon ic-indigo"><i class="fas fa-cash-register"></i></div>
        <div class="db-stat-label" style="font-size:0.8rem; margin-top:0.25rem; font-weight:600;">Open Terminal</div>
    </a>
</div>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- SITE BUILDER MODULE --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<div class="db-section-title">
    <i class="fas fa-globe" style="color:#818cf8;"></i> Site Builder
</div>
<div class="db-stat-grid">
    <a href="{{ route('admin.blog.index') }}" class="db-stat">
        <div class="db-stat-icon ic-indigo"><i class="fas fa-pen-nib"></i></div>
        <div class="db-stat-num">{{ $sitePosts }}</div>
        <div class="db-stat-label">Published Posts</div>
        <div class="db-stat-sub">{{ $siteDrafts }} drafts</div>
    </a>
    <a href="{{ route('admin.users.index') }}" class="db-stat">
        <div class="db-stat-icon ic-blue"><i class="fas fa-users"></i></div>
        <div class="db-stat-num">{{ $siteUsers }}</div>
        <div class="db-stat-label">Team Members</div>
    </a>
    <a href="{{ route('admin.site.index') }}" class="db-stat">
        <div class="db-stat-icon ic-purple"><i class="fas fa-cog"></i></div>
        <div class="db-stat-label" style="font-size:0.8rem; margin-top:0.25rem; font-weight:600;">Site Settings</div>
    </a>
</div>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- QUICK ACTIONS --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<div class="db-section-title">
    <i class="fas fa-bolt" style="color:#fbbf24;"></i> Quick Actions
</div>
<div class="db-quick-grid">
    <a href="{{ route('admin.crm2.sales.accounts.create') }}" class="db-quick-btn"><i class="fas fa-building" style="color:#60a5fa;"></i> New Account</a>
    <a href="{{ route('admin.crm2.sales.contacts.create') }}" class="db-quick-btn"><i class="fas fa-user-plus" style="color:#2dd4bf;"></i> New Contact</a>
    <a href="{{ route('admin.crm2.sales.deals.create') }}" class="db-quick-btn"><i class="fas fa-handshake" style="color:#4ade80;"></i> New Deal</a>
    <a href="{{ route('admin.crm2.inventory.quotes.create') }}" class="db-quick-btn"><i class="fas fa-file-invoice" style="color:#818cf8;"></i> New Quote</a>
    <a href="{{ route('admin.crm2.inventory.sales-orders.create') }}" class="db-quick-btn"><i class="fas fa-shopping-cart" style="color:#4ade80;"></i> New Sales Order</a>
    <a href="{{ route('admin.crm2.inventory.invoices.create') }}" class="db-quick-btn"><i class="fas fa-receipt" style="color:#f87171;"></i> New Invoice</a>
    <a href="{{ route('admin.crm2.inventory.purchase-orders.create') }}" class="db-quick-btn"><i class="fas fa-truck" style="color:#fbbf24;"></i> New Purchase Order</a>
    <a href="{{ route('admin.ecommerce.products.create') }}" class="db-quick-btn"><i class="fas fa-box-open" style="color:#f472b6;"></i> Add Product</a>
    <a href="{{ route('admin.pos.terminal') }}" class="db-quick-btn"><i class="fas fa-cash-register" style="color:#2dd4bf;"></i> POS Terminal</a>
    <a href="{{ route('admin.blog.create') }}" class="db-quick-btn"><i class="fas fa-pen-nib" style="color:#818cf8;"></i> New Blog Post</a>
    <a href="{{ route('admin.calendar.index') }}" class="db-quick-btn"><i class="fas fa-calendar-alt" style="color:#60a5fa;"></i> Calendar</a>
    <a href="{{ route('admin.crm.conversations') }}" class="db-quick-btn"><i class="fas fa-comments" style="color:#fbbf24;"></i> Chat Monitor</a>
</div>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- RECENT ACTIVITY PANELS --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<div class="db-section-title">
    <i class="fas fa-history" style="color:#94a3b8;"></i> Recent Activity
</div>
<div class="db-content-grid">

    {{-- Recent Leads --}}
    <div class="db-card">
        <div class="db-card-header">
            <span class="db-card-title"><i class="fas fa-user-tag" style="color:#fbbf24; margin-right:0.35rem;"></i> Recent Leads</span>
            <a href="{{ route('admin.crm.leads') }}" class="btn btn-outline btn-xs">View All</a>
        </div>
        @forelse($recentLeads as $lead)
        <div class="db-row">
            <span class="db-row-name">{{ $lead->name ?? ($lead->first_name . ' ' . $lead->last_name) }}</span>
            <span class="db-row-meta">{{ $lead->created_at->diffForHumans() }}</span>
        </div>
        @empty
        <p class="text-sm text-muted">No leads yet.</p>
        @endforelse
    </div>

    {{-- Recent Deals --}}
    <div class="db-card">
        <div class="db-card-header">
            <span class="db-card-title"><i class="fas fa-handshake" style="color:#4ade80; margin-right:0.35rem;"></i> Recent Deals</span>
            <a href="{{ route('admin.crm2.sales.deals') }}" class="btn btn-outline btn-xs">View All</a>
        </div>
        @forelse($recentDeals as $deal)
        <div class="db-row">
            <span class="db-row-name">{{ Str::limit($deal->name ?? $deal->deal_name ?? 'Untitled', 35) }}</span>
            <span class="badge badge-{{ $deal->stage === 'Closed Won' ? 'success' : ($deal->stage === 'Closed Lost' ? 'danger' : 'info') }}" style="font-size:0.65rem;">{{ $deal->stage ?? 'Open' }}</span>
        </div>
        @empty
        <p class="text-sm text-muted">No deals yet.</p>
        @endforelse
    </div>

    {{-- Recent Invoices --}}
    <div class="db-card">
        <div class="db-card-header">
            <span class="db-card-title"><i class="fas fa-receipt" style="color:#f87171; margin-right:0.35rem;"></i> Recent Invoices</span>
            <a href="{{ route('admin.crm2.inventory.invoices') }}" class="btn btn-outline btn-xs">View All</a>
        </div>
        @forelse($recentInvoices as $inv)
        <div class="db-row">
            <span class="db-row-name">{{ $inv->invoice_number ?? $inv->subject }}</span>
            <span class="badge badge-{{ $inv->status === 'Paid' ? 'success' : ($inv->status === 'Overdue' ? 'danger' : 'warning') }}" style="font-size:0.65rem;">{{ $inv->status }}</span>
        </div>
        @empty
        <p class="text-sm text-muted">No invoices yet.</p>
        @endforelse
    </div>

    {{-- Recent Blog Posts --}}
    <div class="db-card">
        <div class="db-card-header">
            <span class="db-card-title"><i class="fas fa-pen-nib" style="color:#818cf8; margin-right:0.35rem;"></i> Recent Posts</span>
            <a href="{{ route('admin.blog.index') }}" class="btn btn-outline btn-xs">View All</a>
        </div>
        @forelse($recentPosts as $post)
        <div class="db-row">
            <span class="db-row-name">{{ Str::limit($post->title, 35) }}</span>
            <span class="badge {{ $post->status === 'published' ? 'badge-success' : 'badge-secondary' }}" style="font-size:0.65rem;">{{ $post->status }}</span>
        </div>
        @empty
        <p class="text-sm text-muted">No posts yet.</p>
        @endforelse
    </div>

</div>

@endsection
