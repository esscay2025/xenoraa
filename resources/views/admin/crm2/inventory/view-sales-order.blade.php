@extends('layouts.admin')
@section('content')
<style>
/* ── Inherits all theme vars from global admin layout ── */
.sov-page { display:flex; gap:1.5rem; padding:1.5rem; min-height:100vh; background:var(--bg-primary); }
.sov-main { flex:1; min-width:0; }
.sov-sidebar { width:200px; flex-shrink:0; }
.sov-sticky { position:sticky; top:1rem; }

/* ── Header ── */
.sov-header { display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap; }
.sov-header h1 { font-size:1.3rem; font-weight:700; color:var(--text-primary); margin:0; flex:1; }
.sov-back { display:inline-flex; align-items:center; gap:.4rem; color:var(--accent);
            text-decoration:none; font-size:.82rem; padding:.35rem .75rem;
            border:1.5px solid var(--accent); border-radius:6px; white-space:nowrap; }
.sov-back:hover { background:var(--accent); color:#fff; }
.sov-btn { display:inline-flex; align-items:center; gap:.35rem; font-size:.82rem; font-weight:600;
           padding:.38rem .85rem; border-radius:6px; border:1.5px solid transparent;
           cursor:pointer; text-decoration:none; transition:all .15s; }
.sov-btn.primary   { background:var(--accent); color:#fff; border-color:var(--accent); }
.sov-btn.primary:hover { opacity:.88; }
.sov-btn.secondary { background:transparent; color:var(--text-secondary); border-color:var(--border); }
.sov-btn.secondary:hover { background:var(--bg-hover); }
.sov-btn.danger    { background:rgba(220,38,38,.12); color:#f87171; border-color:rgba(220,38,38,.3); }
.sov-btn.danger:hover { background:#dc2626; color:#fff; border-color:#dc2626; }
.sov-btn.sm { font-size:.75rem; padding:.28rem .6rem; }

/* ── Status badge ── */
.sov-badge { display:inline-block; padding:.25rem .75rem; border-radius:20px; font-size:.75rem; font-weight:700; letter-spacing:.03em; }
.sov-badge.draft      { background:rgba(100,116,139,.15); color:#94a3b8; }
.sov-badge.approved   { background:rgba(34,197,94,.15);   color:#4ade80; }
.sov-badge.packing    { background:rgba(245,158,11,.15);   color:#fbbf24; }
.sov-badge.shipped    { background:rgba(59,130,246,.15);   color:#60a5fa; }
.sov-badge.delivered  { background:rgba(99,102,241,.15);   color:#a5b4fc; }
.sov-badge.cancelled  { background:rgba(239,68,68,.15);    color:#f87171; }

/* ── Section card ── */
.sov-card { background:var(--bg-card); border:1px solid var(--border); border-radius:10px;
            margin-bottom:1.25rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.07); }
.sov-card-header { display:flex; align-items:center; justify-content:space-between;
                   padding:.7rem 1.2rem; background:var(--accent); cursor:pointer; user-select:none; }
.sov-card-header h3 { color:#fff; font-size:.88rem; font-weight:600; margin:0; }
.sov-card-header .sov-chevron { color:#fff; font-size:.75rem; transition:transform .2s; }
.sov-card-header.collapsed .sov-chevron { transform:rotate(-90deg); }
.sov-card-body { padding:1.2rem; }
.sov-card-body.hidden { display:none; }

/* ── Info grid ── */
.sov-info-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:.9rem 1.5rem; }
.sov-info-item label { display:block; font-size:.7rem; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:.2rem; }
.sov-info-item span  { font-size:.88rem; color:var(--text-primary); font-weight:500; }
.sov-divider { border:none; border-top:1px solid var(--border); margin:1rem 0; }

/* ── Address grid ── */
.sov-addr-grid { display:grid; grid-template-columns:1fr auto 1fr; gap:0 1rem; align-items:start; }
.sov-addr-panel h4 { font-size:.8rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.05em; margin:0 0 .75rem; }
.sov-addr-divider { display:flex; flex-direction:column; align-items:center; padding-top:1.8rem; gap:.5rem; }
.sov-addr-divider .sov-vline { flex:1; width:1px; background:var(--border); min-height:20px; }
.sov-addr-row { display:flex; gap:.4rem; margin-bottom:.4rem; }
.sov-addr-row label { font-size:.7rem; color:var(--text-muted); min-width:70px; }
.sov-addr-row span  { font-size:.82rem; color:var(--text-primary); }

/* ── Line items table ── */
.sov-table { width:100%; border-collapse:collapse; font-size:.82rem; }
.sov-table th { background:var(--bg-hover); color:var(--text-secondary); font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; padding:.55rem .75rem; border-bottom:1px solid var(--border); text-align:left; }
.sov-table td { padding:.55rem .75rem; border-bottom:1px solid var(--border); color:var(--text-primary); vertical-align:middle; }
.sov-table tr:last-child td { border-bottom:none; }
.sov-table tr:hover td { background:var(--bg-hover); }
.sov-totals { display:flex; justify-content:flex-end; margin-top:1rem; }
.sov-totals-box { min-width:260px; }
.sov-totals-row { display:flex; justify-content:space-between; padding:.35rem 0; font-size:.84rem; color:var(--text-secondary); border-bottom:1px solid var(--border); }
.sov-totals-row:last-child { border-bottom:none; font-weight:700; font-size:.95rem; color:var(--text-primary); }
.sov-totals-row span:last-child { font-weight:600; color:var(--text-primary); }

/* ── Notes ── */
.sov-note-form textarea { width:100%; padding:.65rem .85rem; border:1.5px solid var(--border); border-radius:7px; background:var(--bg-primary); color:var(--text-primary); font-size:.85rem; resize:vertical; min-height:80px; box-sizing:border-box; }
.sov-note-form textarea:focus { outline:none; border-color:var(--accent); }
.sov-note-list { margin-top:1rem; display:flex; flex-direction:column; gap:.75rem; max-height:320px; overflow-y:auto; }
.sov-note-item { background:var(--bg-primary); border:1px solid var(--border); border-radius:8px; padding:.75rem 1rem; }
.sov-note-meta    { font-size:.7rem; color:var(--text-muted); margin-bottom:.3rem; }
.sov-note-content { font-size:.85rem; color:var(--text-primary); line-height:1.5; white-space:pre-wrap; }

/* ── Activities ── */
.sov-act-item { display:flex; align-items:flex-start; gap:.85rem; padding:.75rem 0; border-bottom:1px solid var(--border); }
.sov-act-item:last-child { border-bottom:none; }
.sov-act-icon { width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.8rem; color:#fff; }
.sov-act-body { flex:1; min-width:0; }
.sov-act-subject { font-size:.87rem; font-weight:600; color:var(--text-primary); }
.sov-act-meta    { font-size:.72rem; color:var(--text-muted); margin-top:.15rem; }
.sov-act-desc    { font-size:.8rem; color:var(--text-secondary); margin-top:.3rem; }
.sov-act-actions { display:flex; gap:.4rem; flex-shrink:0; }
.sov-empty { text-align:center; padding:2rem; color:var(--text-muted); font-size:.85rem; }

/* ── Attachments ── */
.sov-dropzone { border:2px dashed var(--border); border-radius:8px; padding:1.5rem; text-align:center; cursor:pointer; transition:border-color .2s; }
.sov-dropzone:hover, .sov-dropzone.drag-over { border-color:var(--accent); background:rgba(99,102,241,.04); }
.sov-dropzone p { margin:.4rem 0 0; font-size:.82rem; color:var(--text-muted); }
.sov-attach-list { margin-top:1rem; display:flex; flex-direction:column; gap:.5rem; }
.sov-attach-item { display:flex; align-items:center; gap:.75rem; padding:.6rem .9rem; background:var(--bg-primary); border:1px solid var(--border); border-radius:7px; }
.sov-attach-icon { font-size:1.2rem; flex-shrink:0; }
.sov-attach-info { flex:1; min-width:0; }
.sov-attach-name { font-size:.84rem; font-weight:600; color:var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.sov-attach-meta { font-size:.7rem; color:var(--text-muted); }
.sov-attach-del  { background:none; border:none; cursor:pointer; color:var(--text-muted); font-size:1rem; padding:.2rem .4rem; border-radius:4px; }
.sov-attach-del:hover { color:#f87171; background:rgba(220,38,38,.12); }

/* ── Emails ── */
.sov-email-tabs { display:flex; gap:.5rem; margin-bottom:1rem; border-bottom:1px solid var(--border); }
.sov-email-tab  { padding:.45rem 1rem; font-size:.82rem; font-weight:600; color:var(--text-muted); cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-1px; }
.sov-email-tab.active { color:var(--accent); border-bottom-color:var(--accent); }
.sov-email-pane { display:none; }
.sov-email-pane.active { display:block; }
.sov-email-item { padding:.7rem 0; border-bottom:1px solid var(--border); }
.sov-email-item:last-child { border-bottom:none; }
.sov-email-subject { font-size:.87rem; font-weight:600; color:var(--text-primary); }
.sov-email-meta    { font-size:.72rem; color:var(--text-muted); margin-top:.15rem; }

/* ── Right nav ── */
.sov-nav { background:var(--bg-card); border:1px solid var(--border); border-radius:10px; overflow:hidden; }
.sov-nav a { display:flex; align-items:center; gap:.6rem; padding:.65rem 1rem; font-size:.82rem; color:var(--text-secondary); text-decoration:none; border-bottom:1px solid var(--border); transition:all .15s; }
.sov-nav a:last-child { border-bottom:none; }
.sov-nav a:hover, .sov-nav a.active { background:var(--accent); color:#fff; }
.sov-nav a .sov-nav-count { margin-left:auto; background:rgba(99,102,241,.15); color:var(--accent); font-size:.7rem; font-weight:700; padding:.1rem .4rem; border-radius:10px; }
.sov-nav a:hover .sov-nav-count, .sov-nav a.active .sov-nav-count { background:rgba(255,255,255,.25); color:#fff; }

/* ── Slider ── */
.sov-slider-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:1000; }
.sov-slider-overlay.open { display:block; }
.sov-slider { position:fixed; top:0; right:-480px; width:460px; max-width:95vw; height:100vh; background:var(--bg-card); border-left:1px solid var(--border); z-index:1001; transition:right .3s ease; overflow-y:auto; display:flex; flex-direction:column; }
.sov-slider.open { right:0; }
.sov-slider-head { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.2rem; border-bottom:1px solid var(--border); background:var(--accent); }
.sov-slider-head h3 { color:#fff; font-size:.95rem; font-weight:700; margin:0; }
.sov-slider-close { background:none; border:none; color:#fff; font-size:1.3rem; cursor:pointer; padding:.2rem .5rem; border-radius:4px; }
.sov-slider-close:hover { background:rgba(255,255,255,.2); }
.sov-slider-body { padding:1.2rem; flex:1; }
.sov-form-group { margin-bottom:1rem; }
.sov-form-group label { display:block; font-size:.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:.35rem; }
.sov-form-group input, .sov-form-group select, .sov-form-group textarea {
    width:100%; padding:.55rem .8rem; border:1.5px solid var(--border); border-radius:7px;
    background:var(--bg-primary); color:var(--text-primary); font-size:.85rem; box-sizing:border-box; }
.sov-form-group input:focus, .sov-form-group select:focus, .sov-form-group textarea:focus { outline:none; border-color:var(--accent); }
.sov-form-actions { display:flex; gap:.75rem; margin-top:1.25rem; }

/* ── Alert ── */
.sov-alert { padding:.75rem 1rem; border-radius:7px; margin-bottom:1rem; font-size:.85rem; }
.sov-alert.success { background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.3); }
.sov-alert.error   { background:rgba(239,68,68,.12);  color:#f87171; border:1px solid rgba(239,68,68,.3); }
</style>

<div class="sov-page">
  {{-- ── MAIN CONTENT ── --}}
  <div class="sov-main">

    @if(session('success'))
      <div class="sov-alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="sov-alert error">{{ session('error') }}</div>
    @endif

    {{-- Header --}}
    <div class="sov-header">
      <a href="{{ route('admin.crm2.inventory.sales-orders') }}" class="sov-back">&#8592; Sales Orders</a>
      <h1>{{ $item->so_number }} &mdash; {{ $item->subject }}</h1>
      <span class="sov-badge {{ $item->status ?? 'draft' }}">{{ \App\Models\CrmSalesOrder::STATUSES[$item->status] ?? ucfirst($item->status ?? 'Draft') }}</span>
      <a href="{{ route('admin.crm2.inventory.sales-orders.edit', $item->id) }}" class="sov-btn primary">&#9998; Edit</a>
    </div>

    {{-- ══ 1. SALES ORDER INFORMATION ══ --}}
    <div class="sov-card" id="sec-info">
      <div class="sov-card-header" onclick="sovToggle(this)">
        <h3>&#128203; Sales Order Information</h3>
        <span class="sov-chevron">&#9660;</span>
      </div>
      <div class="sov-card-body">
        <div class="sov-info-grid">
          <div class="sov-info-item">
            <label>SO Number</label>
            <span>{{ $item->so_number }}</span>
          </div>
          <div class="sov-info-item">
            <label>Subject</label>
            <span>{{ $item->subject }}</span>
          </div>
          <div class="sov-info-item">
            <label>Status</label>
            <span><span class="sov-badge {{ $item->status ?? 'draft' }}">{{ \App\Models\CrmSalesOrder::STATUSES[$item->status] ?? ucfirst($item->status ?? 'Draft') }}</span></span>
          </div>
          <div class="sov-info-item">
            <label>Delivery Date</label>
            <span>{{ $item->delivery_date ? $item->delivery_date->format('d M Y') : '—' }}</span>
          </div>
          <div class="sov-info-item">
            <label>Customer No.</label>
            <span>{{ $item->customer_no ?: '—' }}</span>
          </div>
          <div class="sov-info-item">
            <label>Purchase Order</label>
            <span>{{ $item->purchase_order ?: '—' }}</span>
          </div>
          <div class="sov-info-item">
            <label>Carrier</label>
            <span>{{ $item->carrier ?: '—' }}</span>
          </div>
          <div class="sov-info-item">
            <label>Pending</label>
            <span>{{ $item->pending ?: '—' }}</span>
          </div>
          <div class="sov-info-item">
            <label>Sales Commission</label>
            <span>{{ $item->sales_commission ? '₹'.number_format($item->sales_commission,2) : '—' }}</span>
          </div>
          <div class="sov-info-item">
            <label>Excise Duty</label>
            <span>{{ $item->excise_duty ? '₹'.number_format($item->excise_duty,2) : '—' }}</span>
          </div>
          <div class="sov-info-item">
            <label>Owner</label>
            <span>{{ $item->owner?->name ?? '—' }}</span>
          </div>
          <div class="sov-info-item">
            <label>Account</label>
            <span>{{ $item->account?->name ?? '—' }}</span>
          </div>
          <div class="sov-info-item">
            <label>Contact</label>
            <span>{{ $item->contact ? ($item->contact->first_name . ' ' . $item->contact->last_name) : '—' }}</span>
          </div>
          <div class="sov-info-item">
            <label>Quote</label>
            <span>
              @if($item->quote)
                <a href="{{ route('admin.crm2.inventory.quotes.show', $item->quote_id) }}" style="color:var(--accent);text-decoration:none;">{{ $item->quote->quote_number }}</a>
              @else —
              @endif
            </span>
          </div>
          <div class="sov-info-item">
            <label>Created</label>
            <span>{{ $item->created_at->format('d M Y, H:i') }}</span>
          </div>
          <div class="sov-info-item">
            <label>Last Updated</label>
            <span>{{ $item->updated_at->format('d M Y, H:i') }}</span>
          </div>
        </div>

        <hr class="sov-divider">

        {{-- Address --}}
        <h4 style="font-size:.8rem;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.05em;margin:0 0 1rem;">Address Information</h4>
        <div class="sov-addr-grid">
          <div class="sov-addr-panel">
            <h4>Billing Address</h4>
            @if($item->bill_building || $item->bill_street || $item->bill_city)
              @if($item->bill_building)<div class="sov-addr-row"><label>Building</label><span>{{ $item->bill_building }}</span></div>@endif
              @if($item->bill_street)<div class="sov-addr-row"><label>Street</label><span>{{ $item->bill_street }}</span></div>@endif
              @if($item->bill_country)<div class="sov-addr-row"><label>Country</label><span>{{ $item->bill_country }}</span></div>@endif
              @if($item->bill_state)<div class="sov-addr-row"><label>State</label><span>{{ $item->bill_state }}</span></div>@endif
              @if($item->bill_city)<div class="sov-addr-row"><label>City</label><span>{{ $item->bill_city }}</span></div>@endif
              @if($item->bill_zip)<div class="sov-addr-row"><label>Zip</label><span>{{ $item->bill_zip }}</span></div>@endif
            @else
              <span style="font-size:.82rem;color:var(--text-muted);font-style:italic;">No billing address</span>
            @endif
          </div>
          <div class="sov-addr-divider">
            <div class="sov-vline"></div>
            <span style="font-size:.7rem;color:var(--text-muted);white-space:nowrap;">&#8644;</span>
            <div class="sov-vline"></div>
          </div>
          <div class="sov-addr-panel">
            <h4>Shipping Address</h4>
            @if($item->ship_building || $item->ship_street || $item->ship_city)
              @if($item->ship_building)<div class="sov-addr-row"><label>Building</label><span>{{ $item->ship_building }}</span></div>@endif
              @if($item->ship_street)<div class="sov-addr-row"><label>Street</label><span>{{ $item->ship_street }}</span></div>@endif
              @if($item->ship_country)<div class="sov-addr-row"><label>Country</label><span>{{ $item->ship_country }}</span></div>@endif
              @if($item->ship_state)<div class="sov-addr-row"><label>State</label><span>{{ $item->ship_state }}</span></div>@endif
              @if($item->ship_city)<div class="sov-addr-row"><label>City</label><span>{{ $item->ship_city }}</span></div>@endif
              @if($item->ship_zip)<div class="sov-addr-row"><label>Zip</label><span>{{ $item->ship_zip }}</span></div>@endif
            @else
              <span style="font-size:.82rem;color:var(--text-muted);font-style:italic;">No shipping address</span>
            @endif
          </div>
        </div>

        <hr class="sov-divider">

        {{-- Line Items --}}
        <h4 style="font-size:.8rem;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.05em;margin:0 0 1rem;">Ordered Items</h4>
        @php $lineItems = $item->line_items ?? []; @endphp
        @if(count($lineItems) > 0)
          <table class="sov-table">
            <thead>
              <tr>
                <th>#</th><th>Product</th><th>Qty</th><th>List Price (₹)</th>
                <th>Amount (₹)</th><th>Discount (₹)</th><th>Tax (₹)</th><th>Total (₹)</th>
              </tr>
            </thead>
            <tbody>
              @foreach($lineItems as $i => $li)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $li['product'] ?? $li['name'] ?? '—' }}</td>
                <td>{{ $li['qty'] ?? 1 }}</td>
                <td>₹{{ number_format($li['price'] ?? 0, 2) }}</td>
                <td>₹{{ number_format(($li['qty'] ?? 1) * ($li['price'] ?? 0), 2) }}</td>
                <td>₹{{ number_format($li['disc'] ?? $li['discount'] ?? 0, 2) }}</td>
                <td>₹{{ number_format($li['tax'] ?? 0, 2) }}</td>
                <td>₹{{ number_format($li['total'] ?? 0, 2) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <div class="sov-totals">
            <div class="sov-totals-box">
              <div class="sov-totals-row"><span>Sub Total</span><span>₹{{ number_format($item->subtotal, 2) }}</span></div>
              <div class="sov-totals-row"><span>Discount</span><span>₹{{ number_format($item->discount_amount, 2) }}</span></div>
              <div class="sov-totals-row"><span>Tax</span><span>₹{{ number_format($item->tax_amount, 2) }}</span></div>
              <div class="sov-totals-row"><span>Adjustment</span><span>₹{{ number_format($item->adjustment, 2) }}</span></div>
              <div class="sov-totals-row"><span>Grand Total</span><span>₹{{ number_format($item->grand_total, 2) }}</span></div>
            </div>
          </div>
        @else
          <div class="sov-empty">No ordered items added yet.</div>
        @endif

        <hr class="sov-divider">

        {{-- Terms --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
          <div>
            <label style="font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:.4rem;">Terms & Conditions</label>
            <div style="font-size:.85rem;color:var(--text-primary);background:var(--bg-primary);border:1px solid var(--border);border-radius:7px;padding:.75rem;min-height:60px;white-space:pre-wrap;">{{ $item->terms ?: '—' }}</div>
          </div>
          <div>
            <label style="font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:.4rem;">Description / Notes</label>
            <div style="font-size:.85rem;color:var(--text-primary);background:var(--bg-primary);border:1px solid var(--border);border-radius:7px;padding:.75rem;min-height:60px;white-space:pre-wrap;">{{ $item->notes ?: '—' }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- ══ 2. NOTES ══ --}}
    <div class="sov-card" id="sec-notes">
      <div class="sov-card-header" onclick="sovToggle(this)">
        <h3>&#128221; Notes <span style="font-size:.75rem;opacity:.8;">({{ $notes->count() }})</span></h3>
        <span class="sov-chevron">&#9660;</span>
      </div>
      <div class="sov-card-body">
        <form method="POST" action="{{ route('admin.crm2.inventory.sales-orders.notes.store', $item->id) }}" class="sov-note-form">
          @csrf
          <textarea name="content" placeholder="Add a note..." required></textarea>
          <div style="margin-top:.5rem;text-align:right;">
            <button type="submit" class="sov-btn primary">Add Note</button>
          </div>
        </form>
        @if($notes->count())
          <div class="sov-note-list">
            @foreach($notes as $note)
              <div class="sov-note-item" id="note-item-{{ $note->id }}">
                <div class="sov-note-meta" style="display:flex;justify-content:space-between;align-items:center;">
                  <span>{{ $note->user?->name ?? 'System' }} &bull; {{ $note->created_at->diffForHumans() }}</span>
                  <button onclick="sovDeleteNote({{ $note->id }})" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:.8rem;padding:.1rem .3rem;" title="Delete note">&#10006;</button>
                </div>
                <div class="sov-note-content">{{ $note->content }}</div>
              </div>
            @endforeach
          </div>
        @else
          <div class="sov-empty">No notes yet. Add the first one above.</div>
        @endif
      </div>
    </div>

    {{-- ══ 3. INVOICES ══ --}}
    <div class="sov-card" id="sec-invoices">
      <div class="sov-card-header" onclick="sovToggle(this)">
        <h3>&#128196; Invoices <span style="font-size:.75rem;opacity:.8;">({{ $invoices->count() }})</span></h3>
        <span class="sov-chevron">&#9660;</span>
      </div>
      <div class="sov-card-body">
        <div style="display:flex;gap:.5rem;margin-bottom:1rem;">
          <button class="sov-btn secondary sm" onclick="sovOpenSlider('slider-assign-inv')">&#128279; Assign Existing</button>
          <a href="{{ route('admin.crm2.inventory.invoices.create') }}?so_id={{ $item->id }}" class="sov-btn primary sm">&#43; New Invoice</a>
        </div>
        @if($invoices->count())
          <table class="sov-table">
            <thead>
              <tr><th>#</th><th>Invoice No.</th><th>Subject</th><th>Status</th><th>Grand Total</th><th>Due Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
              @foreach($invoices as $i => $inv)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td><a href="{{ route('admin.crm2.inventory.invoices.show', $inv->id) }}" style="color:var(--accent);text-decoration:none;font-weight:600;">{{ $inv->invoice_number }}</a></td>
                <td>{{ $inv->subject }}</td>
                <td>
                  @php $sc = ['unpaid'=>'packing','partially_paid'=>'shipped','paid'=>'approved','overdue'=>'cancelled','void'=>'draft']; @endphp
                  <span class="sov-badge {{ $sc[$inv->status] ?? 'draft' }}">{{ \App\Models\CrmInvoice::STATUSES[$inv->status] ?? ucfirst($inv->status) }}</span>
                </td>
                <td>₹{{ number_format($inv->grand_total, 2) }}</td>
                <td>{{ $inv->due_date ? $inv->due_date->format('d M Y') : '—' }}</td>
                <td>
                  <a href="{{ route('admin.crm2.inventory.invoices.edit', $inv->id) }}" class="sov-btn secondary sm">&#9998; Edit</a>
                  <button class="sov-btn danger sm" onclick="sovUnassignInv({{ $inv->id }})">&#10006; Unlink</button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div class="sov-empty">No invoices linked to this sales order yet.</div>
        @endif
      </div>
    </div>

    {{-- ══ 4. ATTACHMENTS ══ --}}
    <div class="sov-card" id="sec-attach">
      <div class="sov-card-header" onclick="sovToggle(this)">
        <h3>&#128206; Attachments <span style="font-size:.75rem;opacity:.8;">({{ $attachments->count() }})</span></h3>
        <span class="sov-chevron">&#9660;</span>
      </div>
      <div class="sov-card-body">
        <form method="POST" action="{{ route('admin.crm2.inventory.sales-orders.attachments.store', $item->id) }}" enctype="multipart/form-data" id="sov-attach-form">
          @csrf
          <div class="sov-dropzone" id="sov-dropzone" onclick="document.getElementById('sov-attach-file').click()">
            <div style="font-size:2rem;">&#128206;</div>
            <p>Click or drag & drop to upload (PDF, DOC, XLS, PNG, JPG, ZIP — max 10 MB)</p>
          </div>
          <input type="file" id="sov-attach-file" name="attachment" style="display:none"
                 accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip"
                 onchange="this.form.submit()">
        </form>
        @if($attachments->count())
          <div class="sov-attach-list">
            @foreach($attachments as $att)
              <div class="sov-attach-item">
                <span class="sov-attach-icon">&#128196;</span>
                <div class="sov-attach-info">
                  <div class="sov-attach-name">{{ $att->original_name }}</div>
                  <div class="sov-attach-meta">{{ $att->human_size }} &bull; {{ $att->created_at->format('d M Y') }}</div>
                </div>
                <a href="{{ route('admin.crm2.inventory.sales-orders.attachments.download', [$item->id, $att->id]) }}" class="sov-btn secondary sm">&#8595; Download</a>
                <button class="sov-attach-del" onclick="sovDeleteAttachment({{ $att->id }})" title="Delete">&#128465;</button>
              </div>
            @endforeach
          </div>
        @else
          <div class="sov-empty" style="margin-top:.75rem;">No attachments yet.</div>
        @endif
      </div>
    </div>

    {{-- ══ 5. OPEN ACTIVITIES ══ --}}
    <div class="sov-card" id="sec-open-act">
      <div class="sov-card-header" onclick="sovToggle(this)">
        <h3>&#128197; Open Activities <span style="font-size:.75rem;opacity:.8;">({{ $openActivities->count() }})</span></h3>
        <span class="sov-chevron">&#9660;</span>
      </div>
      <div class="sov-card-body">
        <div style="margin-bottom:.75rem;text-align:right;">
          <button class="sov-btn primary sm" onclick="sovOpenSlider('slider-add-activity')">&#43; Add Activity</button>
        </div>
        @if($openActivities->count())
          @foreach($openActivities as $act)
            @php $ti = \App\Models\CrmActivity::TYPES[$act->type] ?? ['label'=>ucfirst($act->type),'icon'=>'fa-circle','color'=>'#6366f1']; @endphp
            <div class="sov-act-item">
              <div class="sov-act-icon" style="background:{{ $ti['color'] }};"><i class="fas {{ $ti['icon'] }}"></i></div>
              <div class="sov-act-body">
                <div class="sov-act-subject">{{ $act->subject }}</div>
                <div class="sov-act-meta">{{ $ti['label'] }} &bull; {{ $act->due_at ? $act->due_at->format('d M Y, H:i') : 'No due date' }}</div>
                @if($act->description)<div class="sov-act-desc">{{ $act->description }}</div>@endif
              </div>
              <div class="sov-act-actions">
                <button class="sov-btn primary sm" onclick="sovCompleteActivity({{ $act->id }})">&#10003; Done</button>
                <button class="sov-btn danger sm"  onclick="sovDeleteActivity({{ $act->id }})">&#10006;</button>
              </div>
            </div>
          @endforeach
        @else
          <div class="sov-empty">No open activities.</div>
        @endif
      </div>
    </div>

    {{-- ══ 6. CLOSED ACTIVITIES ══ --}}
    <div class="sov-card" id="sec-closed-act">
      <div class="sov-card-header" onclick="sovToggle(this)">
        <h3>&#9989; Closed Activities <span style="font-size:.75rem;opacity:.8;">({{ $closedActivities->count() }})</span></h3>
        <span class="sov-chevron">&#9660;</span>
      </div>
      <div class="sov-card-body">
        @if($closedActivities->count())
          @foreach($closedActivities as $act)
            @php $ti = \App\Models\CrmActivity::TYPES[$act->type] ?? ['label'=>ucfirst($act->type),'icon'=>'fa-circle','color'=>'#6366f1']; @endphp
            <div class="sov-act-item" style="opacity:.7;">
              <div class="sov-act-icon" style="background:{{ $ti['color'] }};"><i class="fas {{ $ti['icon'] }}"></i></div>
              <div class="sov-act-body">
                <div class="sov-act-subject" style="text-decoration:line-through;">{{ $act->subject }}</div>
                <div class="sov-act-meta">{{ $ti['label'] }} &bull; Completed {{ $act->completed_at ? $act->completed_at->format('d M Y') : '' }}</div>
              </div>
              <div class="sov-act-actions">
                <button class="sov-btn danger sm" onclick="sovDeleteActivity({{ $act->id }})">&#10006;</button>
              </div>
            </div>
          @endforeach
        @else
          <div class="sov-empty">No closed activities.</div>
        @endif
      </div>
    </div>

    {{-- ══ 7. EMAILS ══ --}}
    <div class="sov-card" id="sec-emails">
      <div class="sov-card-header" onclick="sovToggle(this)">
        <h3>&#9993; Emails</h3>
        <span class="sov-chevron">&#9660;</span>
      </div>
      <div class="sov-card-body">
        <div style="margin-bottom:.75rem;text-align:right;">
          <button class="sov-btn primary sm" onclick="sovOpenSlider('slider-send-email')">&#9993; Send Email</button>
        </div>
        @php
          $accountEmails = $item->account_id
            ? \App\Models\CrmAccountEmail::where('account_id', $item->account_id)->latest()->take(20)->get()
            : collect();
          $sentEmails      = $accountEmails->where('status','sent');
          $draftEmails     = $accountEmails->where('status','draft');
          $scheduledEmails = $accountEmails->where('status','scheduled');
        @endphp
        <div class="sov-email-tabs">
          <div class="sov-email-tab active" onclick="sovEmailTab(this,'sov-tab-sent')">Sent ({{ $sentEmails->count() }})</div>
          <div class="sov-email-tab" onclick="sovEmailTab(this,'sov-tab-draft')">Drafts ({{ $draftEmails->count() }})</div>
          <div class="sov-email-tab" onclick="sovEmailTab(this,'sov-tab-scheduled')">Scheduled ({{ $scheduledEmails->count() }})</div>
        </div>
        <div id="sov-tab-sent" class="sov-email-pane active">
          @forelse($sentEmails as $em)
            <div class="sov-email-item">
              <div class="sov-email-subject">{{ $em->subject }}</div>
              <div class="sov-email-meta">To: {{ $em->to_email }} &bull; {{ $em->sent_at ? $em->sent_at->format('d M Y, H:i') : $em->created_at->format('d M Y') }}</div>
            </div>
          @empty
            <div class="sov-empty">No sent emails.</div>
          @endforelse
        </div>
        <div id="sov-tab-draft" class="sov-email-pane">
          @forelse($draftEmails as $em)
            <div class="sov-email-item">
              <div class="sov-email-subject">{{ $em->subject }}</div>
              <div class="sov-email-meta">To: {{ $em->to_email }} &bull; {{ $em->created_at->format('d M Y') }}</div>
            </div>
          @empty
            <div class="sov-empty">No draft emails.</div>
          @endforelse
        </div>
        <div id="sov-tab-scheduled" class="sov-email-pane">
          @forelse($scheduledEmails as $em)
            <div class="sov-email-item">
              <div class="sov-email-subject">{{ $em->subject }}</div>
              <div class="sov-email-meta">To: {{ $em->to_email }} &bull; Scheduled: {{ $em->scheduled_at ? $em->scheduled_at->format('d M Y, H:i') : '—' }}</div>
            </div>
          @empty
            <div class="sov-empty">No scheduled emails.</div>
          @endforelse
        </div>
      </div>
    </div>

  </div>{{-- end sov-main --}}

  {{-- ── RIGHT NAV ── --}}
  <div class="sov-sidebar">
    <div class="sov-sticky">
      <nav class="sov-nav">
        <a href="#sec-info"       onclick="return sovScroll('sec-info')">&#128203; SO Info</a>
        <a href="#sec-notes"      onclick="return sovScroll('sec-notes')">&#128221; Notes <span class="sov-nav-count">{{ $notes->count() }}</span></a>
        <a href="#sec-invoices"   onclick="return sovScroll('sec-invoices')">&#128196; Invoices <span class="sov-nav-count">{{ $invoices->count() }}</span></a>
        <a href="#sec-attach"     onclick="return sovScroll('sec-attach')">&#128206; Attachments <span class="sov-nav-count">{{ $attachments->count() }}</span></a>
        <a href="#sec-open-act"   onclick="return sovScroll('sec-open-act')">&#128197; Open Activities <span class="sov-nav-count">{{ $openActivities->count() }}</span></a>
        <a href="#sec-closed-act" onclick="return sovScroll('sec-closed-act')">&#9989; Closed Activities <span class="sov-nav-count">{{ $closedActivities->count() }}</span></a>
        <a href="#sec-emails"     onclick="return sovScroll('sec-emails')">&#9993; Emails</a>
      </nav>
    </div>
  </div>
</div>

{{-- ══ SLIDERS ══ --}}

{{-- Assign Invoice --}}
<div class="sov-slider-overlay" id="overlay-assign-inv" onclick="sovCloseSlider('slider-assign-inv')"></div>
<div class="sov-slider" id="slider-assign-inv">
  <div class="sov-slider-head">
    <h3>&#128279; Assign Invoice</h3>
    <button class="sov-slider-close" onclick="sovCloseSlider('slider-assign-inv')">&#10005;</button>
  </div>
  <div class="sov-slider-body">
    <form method="POST" action="{{ route('admin.crm2.inventory.sales-orders.invoices.assign', $item->id) }}">
      @csrf
      <div class="sov-form-group">
        <label>Select Invoice</label>
        <select name="invoice_id" required>
          <option value="">-- Select --</option>
          @foreach($allInvoices as $inv)
            <option value="{{ $inv->id }}">{{ $inv->invoice_number }} — {{ $inv->subject }}</option>
          @endforeach
        </select>
      </div>
      <div class="sov-form-actions">
        <button type="submit" class="sov-btn primary">Assign</button>
        <button type="button" class="sov-btn secondary" onclick="sovCloseSlider('slider-assign-inv')">Cancel</button>
      </div>
    </form>
  </div>
</div>

{{-- Add Activity --}}
<div class="sov-slider-overlay" id="overlay-add-activity" onclick="sovCloseSlider('slider-add-activity')"></div>
<div class="sov-slider" id="slider-add-activity">
  <div class="sov-slider-head">
    <h3>&#128197; Add Activity</h3>
    <button class="sov-slider-close" onclick="sovCloseSlider('slider-add-activity')">&#10005;</button>
  </div>
  <div class="sov-slider-body">
    <form method="POST" action="{{ route('admin.crm2.inventory.sales-orders.activities.store', $item->id) }}">
      @csrf
      <div class="sov-form-group">
        <label>Activity Type *</label>
        <select name="type" required>
          <option value="">-- Select Type --</option>
          @foreach(\App\Models\CrmActivity::TYPES as $key => $t)
            <option value="{{ $key }}">{{ $t['label'] }}</option>
          @endforeach
        </select>
      </div>
      <div class="sov-form-group">
        <label>Subject *</label>
        <input type="text" name="subject" required placeholder="Activity subject">
      </div>
      <div class="sov-form-group">
        <label>Description</label>
        <textarea name="description" rows="3" placeholder="Optional description..."></textarea>
      </div>
      <div class="sov-form-group">
        <label>Due Date & Time</label>
        <input type="datetime-local" name="due_at">
      </div>
      <div class="sov-form-actions">
        <button type="submit" class="sov-btn primary">Add Activity</button>
        <button type="button" class="sov-btn secondary" onclick="sovCloseSlider('slider-add-activity')">Cancel</button>
      </div>
    </form>
  </div>
</div>

{{-- Send Email --}}
<div class="sov-slider-overlay" id="overlay-send-email" onclick="sovCloseSlider('slider-send-email')"></div>
<div class="sov-slider" id="slider-send-email">
  <div class="sov-slider-head">
    <h3>&#9993; Send Email</h3>
    <button class="sov-slider-close" onclick="sovCloseSlider('slider-send-email')">&#10005;</button>
  </div>
  <div class="sov-slider-body">
    @if(!$mailConfig)
      <div class="sov-alert error">No active mail configuration. Please set up SMTP in CRM Settings first.</div>
    @else
    <form method="POST" action="{{ route('admin.crm2.inventory.sales-orders.send-mail', $item->id) }}">
      @csrf
      <div class="sov-form-group">
        <label>To *</label>
        <input type="email" name="to_email" required value="{{ $item->contact?->email ?? $item->account?->email ?? '' }}" placeholder="recipient@email.com">
      </div>
      <div class="sov-form-group">
        <label>CC</label>
        <input type="email" name="cc_email" placeholder="cc@email.com">
      </div>
      <div class="sov-form-group">
        <label>BCC</label>
        <input type="email" name="bcc_email" placeholder="bcc@email.com">
      </div>
      <div class="sov-form-group">
        <label>Subject *</label>
        <input type="text" name="subject" required value="Sales Order {{ $item->so_number }}: {{ $item->subject }}">
      </div>
      <div class="sov-form-group">
        <label>Template</label>
        <select onchange="sovApplyTemplate(this)">
          <option value="">-- No template --</option>
          @foreach($mailTemplates as $tpl)
            <option value="{{ $tpl->id }}" data-body="{{ htmlspecialchars($tpl->body_html ?? '') }}">{{ $tpl->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="sov-form-group">
        <label>Message *</label>
        <textarea name="body_html" id="sov-email-body" rows="8" required placeholder="Email body..."></textarea>
      </div>
      <div class="sov-form-actions">
        <button type="submit" class="sov-btn primary">&#9993; Send</button>
        <button type="button" class="sov-btn secondary" onclick="sovCloseSlider('slider-send-email')">Cancel</button>
      </div>
    </form>
    @endif
  </div>
</div>

<script>
function sovToggle(header) {
    header.classList.toggle('collapsed');
    header.nextElementSibling.classList.toggle('hidden');
}
function sovScroll(id) {
    document.getElementById(id)?.scrollIntoView({behavior:'smooth',block:'start'});
    return false;
}
function sovOpenSlider(id) {
    document.getElementById(id).classList.add('open');
    const key = id.replace('slider-','');
    const ov = document.getElementById('overlay-' + key);
    if (ov) ov.classList.add('open');
}
function sovCloseSlider(id) {
    document.getElementById(id).classList.remove('open');
    const key = id.replace('slider-','');
    const ov = document.getElementById('overlay-' + key);
    if (ov) ov.classList.remove('open');
}
function sovEmailTab(tab, paneId) {
    document.querySelectorAll('.sov-email-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.sov-email-pane').forEach(p => p.classList.remove('active'));
    tab.classList.add('active');
    document.getElementById(paneId).classList.add('active');
}
function sovApplyTemplate(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.dataset.body) document.getElementById('sov-email-body').value = opt.dataset.body;
}
// Drag & drop
const dz = document.getElementById('sov-dropzone');
dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('drag-over'); });
dz.addEventListener('dragleave', () => dz.classList.remove('drag-over'));
dz.addEventListener('drop', e => {
    e.preventDefault(); dz.classList.remove('drag-over');
    document.getElementById('sov-attach-file').files = e.dataTransfer.files;
    document.getElementById('sov-attach-form').submit();
});
function sovDeleteNote(noteId) {
    if (!confirm('Delete this note?')) return;
    fetch('{{ route("admin.crm2.inventory.sales-orders.notes.destroy", [$item->id, "__ID__"]) }}'.replace('__ID__', noteId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) document.getElementById('note-item-'+noteId)?.remove(); });
}
function sovDeleteAttachment(attId) {
    if (!confirm('Delete this attachment?')) return;
    fetch('{{ route("admin.crm2.inventory.sales-orders.attachments.destroy", [$item->id, "__ID__"]) }}'.replace('__ID__', attId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
function sovCompleteActivity(actId) {
    fetch('{{ route("admin.crm2.inventory.sales-orders.activities.complete", [$item->id, "__ID__"]) }}'.replace('__ID__', actId), {
        method:'PATCH', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
function sovDeleteActivity(actId) {
    if (!confirm('Delete this activity?')) return;
    fetch('{{ route("admin.crm2.inventory.sales-orders.activities.destroy", [$item->id, "__ID__"]) }}'.replace('__ID__', actId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
function sovUnassignInv(invId) {
    if (!confirm('Unlink this invoice from the sales order?')) return;
    fetch('{{ route("admin.crm2.inventory.sales-orders.invoices.unassign", [$item->id, "__ID__"]) }}'.replace('__ID__', invId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
// Highlight nav on scroll
const sovSections = ['sec-info','sec-notes','sec-invoices','sec-attach','sec-open-act','sec-closed-act','sec-emails'];
const sovNavLinks  = document.querySelectorAll('.sov-nav a');
window.addEventListener('scroll', () => {
    let cur = '';
    sovSections.forEach(id => {
        const el = document.getElementById(id);
        if (el && window.scrollY >= el.offsetTop - 120) cur = id;
    });
    sovNavLinks.forEach(a => a.classList.toggle('active', a.getAttribute('href') === '#' + cur));
}, {passive:true});
</script>
@endsection
