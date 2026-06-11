@extends('layouts.admin')
@section('content')
<style>
/* ── Inherits all theme vars from global admin layout — no :root override ── */
.pov-page { display:flex; gap:1.5rem; padding:1.5rem; min-height:100vh; background:var(--bg-primary); }
.pov-main { flex:1; min-width:0; }
.pov-sidebar { width:200px; flex-shrink:0; }
.pov-sticky { position:sticky; top:1rem; }

/* ── Header ── */
.pov-header { display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap; }
.pov-header h1 { font-size:1.3rem; font-weight:700; color:var(--text-primary); margin:0; flex:1; }
.pov-back { display:inline-flex; align-items:center; gap:.4rem; color:var(--accent);
            text-decoration:none; font-size:.82rem; padding:.35rem .75rem;
            border:1.5px solid var(--accent); border-radius:6px; white-space:nowrap; }
.pov-back:hover { background:var(--accent); color:#fff; }
.pov-btn { display:inline-flex; align-items:center; gap:.35rem; font-size:.82rem; font-weight:600;
           padding:.38rem .85rem; border-radius:6px; border:1.5px solid transparent;
           cursor:pointer; text-decoration:none; transition:all .15s; }
.pov-btn.primary   { background:var(--accent); color:#fff; border-color:var(--accent); }
.pov-btn.primary:hover { opacity:.88; }
.pov-btn.secondary { background:transparent; color:var(--text-secondary); border-color:var(--border); }
.pov-btn.secondary:hover { background:var(--bg-hover); }
.pov-btn.danger    { background:rgba(220,38,38,.12); color:#f87171; border-color:rgba(220,38,38,.3); }
.pov-btn.danger:hover { background:#dc2626; color:#fff; border-color:#dc2626; }
.pov-btn.sm { font-size:.75rem; padding:.28rem .6rem; }

/* ── Status badges ── */
.pov-badge { display:inline-block; padding:.25rem .75rem; border-radius:20px; font-size:.75rem; font-weight:700; letter-spacing:.03em; }
.pov-badge.draft     { background:rgba(100,116,139,.15); color:#94a3b8; }
.pov-badge.ordered   { background:rgba(59,130,246,.15);  color:#60a5fa; }
.pov-badge.received  { background:rgba(34,197,94,.15);   color:#4ade80; }
.pov-badge.cancelled { background:rgba(239,68,68,.15);   color:#f87171; }

/* ── Section card ── */
.pov-card { background:var(--bg-card); border:1px solid var(--border); border-radius:10px;
            margin-bottom:1.25rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.07); }
.pov-card-header { display:flex; align-items:center; justify-content:space-between;
                   padding:.7rem 1.2rem; background:var(--accent); cursor:pointer; user-select:none; }
.pov-card-header h3 { color:#fff; font-size:.88rem; font-weight:600; margin:0; }
.pov-card-header .pov-chevron { color:#fff; font-size:.75rem; transition:transform .2s; }
.pov-card-header.collapsed .pov-chevron { transform:rotate(-90deg); }
.pov-card-body { padding:1.2rem; }
.pov-card-body.hidden { display:none; }

/* ── Info grid ── */
.pov-info-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:.9rem 1.5rem; }
.pov-info-item label { display:block; font-size:.7rem; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:.2rem; }
.pov-info-item span  { font-size:.88rem; color:var(--text-primary); font-weight:500; }
.pov-divider { border:none; border-top:1px solid var(--border); margin:1rem 0; }

/* ── Address grid ── */
.pov-addr-grid { display:grid; grid-template-columns:1fr auto 1fr; gap:0 1rem; align-items:start; }
.pov-addr-panel h4 { font-size:.8rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.05em; margin:0 0 .75rem; }
.pov-addr-divider { display:flex; flex-direction:column; align-items:center; padding-top:1.8rem; gap:.5rem; }
.pov-addr-divider .pov-vline { flex:1; width:1px; background:var(--border); min-height:20px; }
.pov-addr-row { display:flex; gap:.4rem; margin-bottom:.4rem; }
.pov-addr-row label { font-size:.7rem; color:var(--text-muted); min-width:70px; }
.pov-addr-row span  { font-size:.82rem; color:var(--text-primary); }

/* ── Line items table ── */
.pov-table { width:100%; border-collapse:collapse; font-size:.82rem; }
.pov-table th { background:var(--bg-hover); color:var(--text-secondary); font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; padding:.55rem .75rem; border-bottom:1px solid var(--border); text-align:left; }
.pov-table td { padding:.55rem .75rem; border-bottom:1px solid var(--border); color:var(--text-primary); vertical-align:middle; }
.pov-table tr:last-child td { border-bottom:none; }
.pov-table tr:hover td { background:var(--bg-hover); }
.pov-totals { display:flex; justify-content:flex-end; margin-top:1rem; }
.pov-totals-box { min-width:260px; }
.pov-totals-row { display:flex; justify-content:space-between; padding:.35rem 0; font-size:.84rem; color:var(--text-secondary); border-bottom:1px solid var(--border); }
.pov-totals-row:last-child { border-bottom:none; font-weight:700; font-size:.95rem; color:var(--text-primary); }
.pov-totals-row span:last-child { font-weight:600; color:var(--text-primary); }

/* ── Notes ── */
.pov-note-form textarea { width:100%; padding:.65rem .85rem; border:1.5px solid var(--border); border-radius:7px; background:var(--bg-primary); color:var(--text-primary); font-size:.85rem; resize:vertical; min-height:80px; box-sizing:border-box; }
.pov-note-form textarea:focus { outline:none; border-color:var(--accent); }
.pov-note-list { margin-top:1rem; display:flex; flex-direction:column; gap:.75rem; max-height:320px; overflow-y:auto; }
.pov-note-item { background:var(--bg-primary); border:1px solid var(--border); border-radius:8px; padding:.75rem 1rem; }
.pov-note-meta    { font-size:.7rem; color:var(--text-muted); margin-bottom:.3rem; }
.pov-note-content { font-size:.85rem; color:var(--text-primary); line-height:1.5; white-space:pre-wrap; }

/* ── Activities ── */
.pov-act-item { display:flex; align-items:flex-start; gap:.85rem; padding:.75rem 0; border-bottom:1px solid var(--border); }
.pov-act-item:last-child { border-bottom:none; }
.pov-act-icon { width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.8rem; color:#fff; }
.pov-act-body { flex:1; min-width:0; }
.pov-act-subject { font-size:.87rem; font-weight:600; color:var(--text-primary); }
.pov-act-meta    { font-size:.72rem; color:var(--text-muted); margin-top:.15rem; }
.pov-act-desc    { font-size:.8rem; color:var(--text-secondary); margin-top:.3rem; }
.pov-act-actions { display:flex; gap:.4rem; flex-shrink:0; }
.pov-empty { text-align:center; padding:2rem; color:var(--text-muted); font-size:.85rem; }

/* ── Attachments ── */
.pov-dropzone { border:2px dashed var(--border); border-radius:8px; padding:1.5rem; text-align:center; cursor:pointer; transition:border-color .2s; }
.pov-dropzone:hover, .pov-dropzone.drag-over { border-color:var(--accent); background:rgba(99,102,241,.04); }
.pov-dropzone p { margin:.4rem 0 0; font-size:.82rem; color:var(--text-muted); }
.pov-attach-list { margin-top:1rem; display:flex; flex-direction:column; gap:.5rem; }
.pov-attach-item { display:flex; align-items:center; gap:.75rem; padding:.6rem .9rem; background:var(--bg-primary); border:1px solid var(--border); border-radius:7px; }
.pov-attach-icon { font-size:1.2rem; flex-shrink:0; }
.pov-attach-info { flex:1; min-width:0; }
.pov-attach-name { font-size:.84rem; font-weight:600; color:var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.pov-attach-meta { font-size:.7rem; color:var(--text-muted); }
.pov-attach-del  { background:none; border:none; cursor:pointer; color:var(--text-muted); font-size:1rem; padding:.2rem .4rem; border-radius:4px; }
.pov-attach-del:hover { color:#f87171; background:rgba(220,38,38,.12); }

/* ── Emails ── */
.pov-email-tabs { display:flex; gap:.5rem; margin-bottom:1rem; border-bottom:1px solid var(--border); }
.pov-email-tab  { padding:.45rem 1rem; font-size:.82rem; font-weight:600; color:var(--text-muted); cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-1px; }
.pov-email-tab.active { color:var(--accent); border-bottom-color:var(--accent); }
.pov-email-pane { display:none; }
.pov-email-pane.active { display:block; }
.pov-email-item { padding:.7rem 0; border-bottom:1px solid var(--border); }
.pov-email-item:last-child { border-bottom:none; }
.pov-email-subject { font-size:.87rem; font-weight:600; color:var(--text-primary); }
.pov-email-meta    { font-size:.72rem; color:var(--text-muted); margin-top:.15rem; }

/* ── Right nav ── */
.pov-nav { background:var(--bg-card); border:1px solid var(--border); border-radius:10px; overflow:hidden; }
.pov-nav a { display:flex; align-items:center; gap:.6rem; padding:.65rem 1rem; font-size:.82rem; color:var(--text-secondary); text-decoration:none; border-bottom:1px solid var(--border); transition:all .15s; }
.pov-nav a:last-child { border-bottom:none; }
.pov-nav a:hover, .pov-nav a.active { background:var(--accent); color:#fff; }
.pov-nav a .pov-nav-count { margin-left:auto; background:rgba(99,102,241,.15); color:var(--accent); font-size:.7rem; font-weight:700; padding:.1rem .4rem; border-radius:10px; }
.pov-nav a:hover .pov-nav-count, .pov-nav a.active .pov-nav-count { background:rgba(255,255,255,.25); color:#fff; }

/* ── Slider ── */
.pov-slider-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:1000; }
.pov-slider-overlay.open { display:block; }
.pov-slider { position:fixed; top:0; right:-480px; width:460px; max-width:95vw; height:100vh; background:var(--bg-card); border-left:1px solid var(--border); z-index:1001; transition:right .3s ease; overflow-y:auto; display:flex; flex-direction:column; }
.pov-slider.open { right:0; }
.pov-slider-head { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.2rem; border-bottom:1px solid var(--border); background:var(--accent); }
.pov-slider-head h3 { color:#fff; font-size:.95rem; font-weight:700; margin:0; }
.pov-slider-close { background:none; border:none; color:#fff; font-size:1.3rem; cursor:pointer; padding:.2rem .5rem; border-radius:4px; }
.pov-slider-close:hover { background:rgba(255,255,255,.2); }
.pov-slider-body { padding:1.2rem; flex:1; }
.pov-form-group { margin-bottom:1rem; }
.pov-form-group label { display:block; font-size:.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:.35rem; }
.pov-form-group input, .pov-form-group select, .pov-form-group textarea {
    width:100%; padding:.55rem .8rem; border:1.5px solid var(--border); border-radius:7px;
    background:var(--bg-primary); color:var(--text-primary); font-size:.85rem; box-sizing:border-box; }
.pov-form-group input:focus, .pov-form-group select:focus, .pov-form-group textarea:focus { outline:none; border-color:var(--accent); }
.pov-form-actions { display:flex; gap:.75rem; margin-top:1.25rem; }

/* ── Alert ── */
.pov-alert { padding:.75rem 1rem; border-radius:7px; margin-bottom:1rem; font-size:.85rem; }
.pov-alert.success { background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.3); }
.pov-alert.error   { background:rgba(239,68,68,.12);  color:#f87171; border:1px solid rgba(239,68,68,.3); }
</style>

<div class="pov-page">
  {{-- ── MAIN CONTENT ── --}}
  <div class="pov-main">

    @if(session('success'))
      <div class="pov-alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="pov-alert error">{{ session('error') }}</div>
    @endif

    {{-- Header --}}
    <div class="pov-header">
      <a href="{{ route('admin.crm2.inventory.purchase-orders') }}" class="pov-back">&#8592; Purchase Orders</a>
      <h1>{{ $item->po_number }} &mdash; {{ $item->subject }}</h1>
      <span class="pov-badge {{ $item->status ?? 'draft' }}">{{ \App\Models\CrmPurchaseOrder::STATUSES[$item->status] ?? ucfirst($item->status ?? 'Draft') }}</span>
      <a href="{{ route('admin.crm2.inventory.purchase-orders.edit', $item->id) }}" class="pov-btn primary">&#9998; Edit</a>
    </div>

    {{-- ══ 1. PURCHASE ORDER INFORMATION ══ --}}
    <div class="pov-card" id="sec-info">
      <div class="pov-card-header" onclick="povToggle(this)">
        <h3>&#128203; Purchase Order Information</h3>
        <span class="pov-chevron">&#9660;</span>
      </div>
      <div class="pov-card-body">
        <div class="pov-info-grid">
          <div class="pov-info-item">
            <label>PO Number</label>
            <span>{{ $item->po_number }}</span>
          </div>
          <div class="pov-info-item">
            <label>Subject</label>
            <span>{{ $item->subject }}</span>
          </div>
          <div class="pov-info-item">
            <label>Status</label>
            <span><span class="pov-badge {{ $item->status ?? 'draft' }}">{{ \App\Models\CrmPurchaseOrder::STATUSES[$item->status] ?? ucfirst($item->status ?? 'Draft') }}</span></span>
          </div>
          <div class="pov-info-item">
            <label>PO Date</label>
            <span>{{ $item->po_date ? $item->po_date->format('d M Y') : '—' }}</span>
          </div>
          <div class="pov-info-item">
            <label>Expected Delivery</label>
            <span>{{ $item->expected_delivery ? $item->expected_delivery->format('d M Y') : '—' }}</span>
          </div>
          <div class="pov-info-item">
            <label>Vendor</label>
            <span>{{ $item->vendor?->name ?? '—' }}</span>
          </div>
          <div class="pov-info-item">
            <label>Contact</label>
            <span>{{ $item->contact ? ($item->contact->first_name . ' ' . $item->contact->last_name) : '—' }}</span>
          </div>
          <div class="pov-info-item">
            <label>Carrier</label>
            <span>{{ $item->carrier ?: '—' }}</span>
          </div>
          <div class="pov-info-item">
            <label>Tracking Number</label>
            <span>{{ $item->tracking_no ?: '—' }}</span>
          </div>
          <div class="pov-info-item">
            <label>Requisition No.</label>
            <span>{{ $item->requisition_no ?: '—' }}</span>
          </div>
          <div class="pov-info-item">
            <label>Sales Commission</label>
            <span>{{ $item->sales_commission ? '₹'.number_format($item->sales_commission,2) : '—' }}</span>
          </div>
          <div class="pov-info-item">
            <label>Excise Duty</label>
            <span>{{ $item->excise_duty ? '₹'.number_format($item->excise_duty,2) : '—' }}</span>
          </div>
          <div class="pov-info-item">
            <label>Owner</label>
            <span>{{ $item->owner?->name ?? '—' }}</span>
          </div>
          <div class="pov-info-item">
            <label>Created</label>
            <span>{{ $item->created_at->format('d M Y, H:i') }}</span>
          </div>
          <div class="pov-info-item">
            <label>Last Updated</label>
            <span>{{ $item->updated_at->format('d M Y, H:i') }}</span>
          </div>
        </div>

        <hr class="pov-divider">

        {{-- Address --}}
        <h4 style="font-size:.8rem;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.05em;margin:0 0 1rem;">Address Information</h4>
        <div class="pov-addr-grid">
          <div class="pov-addr-panel">
            <h4>Billing Address</h4>
            @if($item->bill_building || $item->bill_street || $item->bill_city)
              @if($item->bill_building)<div class="pov-addr-row"><label>Building</label><span>{{ $item->bill_building }}</span></div>@endif
              @if($item->bill_street)<div class="pov-addr-row"><label>Street</label><span>{{ $item->bill_street }}</span></div>@endif
              @if($item->bill_country)<div class="pov-addr-row"><label>Country</label><span>{{ $item->bill_country }}</span></div>@endif
              @if($item->bill_state)<div class="pov-addr-row"><label>State</label><span>{{ $item->bill_state }}</span></div>@endif
              @if($item->bill_city)<div class="pov-addr-row"><label>City</label><span>{{ $item->bill_city }}</span></div>@endif
              @if($item->bill_zip)<div class="pov-addr-row"><label>Zip</label><span>{{ $item->bill_zip }}</span></div>@endif
            @else
              <span style="font-size:.82rem;color:var(--text-muted);font-style:italic;">No billing address</span>
            @endif
          </div>
          <div class="pov-addr-divider">
            <div class="pov-vline"></div>
            <span style="font-size:.7rem;color:var(--text-muted);white-space:nowrap;">&#8644;</span>
            <div class="pov-vline"></div>
          </div>
          <div class="pov-addr-panel">
            <h4>Shipping Address</h4>
            @if($item->ship_building || $item->ship_street || $item->ship_city)
              @if($item->ship_building)<div class="pov-addr-row"><label>Building</label><span>{{ $item->ship_building }}</span></div>@endif
              @if($item->ship_street)<div class="pov-addr-row"><label>Street</label><span>{{ $item->ship_street }}</span></div>@endif
              @if($item->ship_country)<div class="pov-addr-row"><label>Country</label><span>{{ $item->ship_country }}</span></div>@endif
              @if($item->ship_state)<div class="pov-addr-row"><label>State</label><span>{{ $item->ship_state }}</span></div>@endif
              @if($item->ship_city)<div class="pov-addr-row"><label>City</label><span>{{ $item->ship_city }}</span></div>@endif
              @if($item->ship_zip)<div class="pov-addr-row"><label>Zip</label><span>{{ $item->ship_zip }}</span></div>@endif
            @else
              <span style="font-size:.82rem;color:var(--text-muted);font-style:italic;">No shipping address</span>
            @endif
          </div>
        </div>

        <hr class="pov-divider">

        {{-- Line Items --}}
        <h4 style="font-size:.8rem;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.05em;margin:0 0 1rem;">Ordered Items</h4>
        @php $lineItems = $item->line_items ?? []; @endphp
        @if(count($lineItems) > 0)
          <table class="pov-table">
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
          <div class="pov-totals">
            <div class="pov-totals-box">
              <div class="pov-totals-row"><span>Sub Total</span><span>₹{{ number_format($item->subtotal ?? 0, 2) }}</span></div>
              <div class="pov-totals-row"><span>Discount</span><span>₹{{ number_format($item->discount_amount ?? 0, 2) }}</span></div>
              <div class="pov-totals-row"><span>Tax</span><span>₹{{ number_format($item->tax_amount ?? 0, 2) }}</span></div>
              <div class="pov-totals-row"><span>Adjustment</span><span>₹{{ number_format($item->adjustment ?? 0, 2) }}</span></div>
              <div class="pov-totals-row"><span>Grand Total</span><span>₹{{ number_format($item->grand_total ?? $item->total ?? 0, 2) }}</span></div>
            </div>
          </div>
        @else
          <div class="pov-empty">No ordered items added yet.</div>
        @endif

        <hr class="pov-divider">

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
    <div class="pov-card" id="sec-notes">
      <div class="pov-card-header" onclick="povToggle(this)">
        <h3>&#128221; Notes <span style="font-size:.75rem;opacity:.8;">({{ $notes->count() }})</span></h3>
        <span class="pov-chevron">&#9660;</span>
      </div>
      <div class="pov-card-body">
        <form method="POST" action="{{ route('admin.crm2.inventory.purchase-orders.notes.store', $item->id) }}" class="pov-note-form">
          @csrf
          <textarea name="content" placeholder="Add a note..." required></textarea>
          <div style="margin-top:.5rem;text-align:right;">
            <button type="submit" class="pov-btn primary">Add Note</button>
          </div>
        </form>
        @if($notes->count())
          <div class="pov-note-list">
            @foreach($notes as $note)
              <div class="pov-note-item" id="note-item-{{ $note->id }}">
                <div class="pov-note-meta" style="display:flex;justify-content:space-between;align-items:center;">
                  <span>{{ $note->user?->name ?? 'System' }} &bull; {{ $note->created_at->diffForHumans() }}</span>
                  <button onclick="povDeleteNote({{ $note->id }})" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:.8rem;padding:.1rem .3rem;" title="Delete note">&#10006;</button>
                </div>
                <div class="pov-note-content">{{ $note->content }}</div>
              </div>
            @endforeach
          </div>
        @else
          <div class="pov-empty">No notes yet. Add the first one above.</div>
        @endif
      </div>
    </div>

    {{-- ══ 3. ATTACHMENTS ══ --}}
    <div class="pov-card" id="sec-attach">
      <div class="pov-card-header" onclick="povToggle(this)">
        <h3>&#128206; Attachments <span style="font-size:.75rem;opacity:.8;">({{ $attachments->count() }})</span></h3>
        <span class="pov-chevron">&#9660;</span>
      </div>
      <div class="pov-card-body">
        <form method="POST" action="{{ route('admin.crm2.inventory.purchase-orders.attachments.store', $item->id) }}" enctype="multipart/form-data" id="pov-attach-form">
          @csrf
          <div class="pov-dropzone" id="pov-dropzone" onclick="document.getElementById('pov-attach-file').click()">
            <div style="font-size:2rem;">&#128206;</div>
            <p>Click or drag & drop to upload (PDF, DOC, XLS, PNG, JPG, ZIP — max 10 MB)</p>
          </div>
          <input type="file" id="pov-attach-file" name="attachment" style="display:none"
                 accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip"
                 onchange="this.form.submit()">
        </form>
        @if($attachments->count())
          <div class="pov-attach-list">
            @foreach($attachments as $att)
              <div class="pov-attach-item">
                <span class="pov-attach-icon">&#128196;</span>
                <div class="pov-attach-info">
                  <div class="pov-attach-name">{{ $att->original_name }}</div>
                  <div class="pov-attach-meta">{{ $att->human_size }} &bull; {{ $att->created_at->format('d M Y') }}</div>
                </div>
                <a href="{{ route('admin.crm2.inventory.purchase-orders.attachments.download', [$item->id, $att->id]) }}" class="pov-btn secondary sm">&#8595; Download</a>
                <button class="pov-attach-del" onclick="povDeleteAttachment({{ $att->id }})" title="Delete">&#128465;</button>
              </div>
            @endforeach
          </div>
        @else
          <div class="pov-empty" style="margin-top:.75rem;">No attachments yet.</div>
        @endif
      </div>
    </div>

    {{-- ══ 4. OPEN ACTIVITIES ══ --}}
    <div class="pov-card" id="sec-open-act">
      <div class="pov-card-header" onclick="povToggle(this)">
        <h3>&#128197; Open Activities <span style="font-size:.75rem;opacity:.8;">({{ $openActivities->count() }})</span></h3>
        <span class="pov-chevron">&#9660;</span>
      </div>
      <div class="pov-card-body">
        <div style="margin-bottom:.75rem;text-align:right;">
          <button class="pov-btn primary sm" onclick="povOpenSlider('slider-add-activity')">&#43; Add Activity</button>
        </div>
        @if($openActivities->count())
          @foreach($openActivities as $act)
            @php $ti = \App\Models\CrmActivity::TYPES[$act->type] ?? ['label'=>ucfirst($act->type),'icon'=>'fa-circle','color'=>'#6366f1']; @endphp
            <div class="pov-act-item">
              <div class="pov-act-icon" style="background:{{ $ti['color'] }};"><i class="fas {{ $ti['icon'] }}"></i></div>
              <div class="pov-act-body">
                <div class="pov-act-subject">{{ $act->subject }}</div>
                <div class="pov-act-meta">{{ $ti['label'] }} &bull; {{ $act->due_at ? $act->due_at->format('d M Y, H:i') : 'No due date' }}</div>
                @if($act->description)<div class="pov-act-desc">{{ $act->description }}</div>@endif
              </div>
              <div class="pov-act-actions">
                <button class="pov-btn primary sm" onclick="povCompleteActivity({{ $act->id }})">&#10003; Done</button>
                <button class="pov-btn danger sm"  onclick="povDeleteActivity({{ $act->id }})">&#10006;</button>
              </div>
            </div>
          @endforeach
        @else
          <div class="pov-empty">No open activities.</div>
        @endif
      </div>
    </div>

    {{-- ══ 5. CLOSED ACTIVITIES ══ --}}
    <div class="pov-card" id="sec-closed-act">
      <div class="pov-card-header" onclick="povToggle(this)">
        <h3>&#9989; Closed Activities <span style="font-size:.75rem;opacity:.8;">({{ $closedActivities->count() }})</span></h3>
        <span class="pov-chevron">&#9660;</span>
      </div>
      <div class="pov-card-body">
        @if($closedActivities->count())
          @foreach($closedActivities as $act)
            @php $ti = \App\Models\CrmActivity::TYPES[$act->type] ?? ['label'=>ucfirst($act->type),'icon'=>'fa-circle','color'=>'#6366f1']; @endphp
            <div class="pov-act-item" style="opacity:.7;">
              <div class="pov-act-icon" style="background:{{ $ti['color'] }};"><i class="fas {{ $ti['icon'] }}"></i></div>
              <div class="pov-act-body">
                <div class="pov-act-subject" style="text-decoration:line-through;">{{ $act->subject }}</div>
                <div class="pov-act-meta">{{ $ti['label'] }} &bull; Completed {{ $act->completed_at ? $act->completed_at->format('d M Y') : '' }}</div>
              </div>
              <div class="pov-act-actions">
                <button class="pov-btn danger sm" onclick="povDeleteActivity({{ $act->id }})">&#10006;</button>
              </div>
            </div>
          @endforeach
        @else
          <div class="pov-empty">No closed activities.</div>
        @endif
      </div>
    </div>

    {{-- ══ 6. EMAILS ══ --}}
    <div class="pov-card" id="sec-emails">
      <div class="pov-card-header" onclick="povToggle(this)">
        <h3>&#9993; Emails</h3>
        <span class="pov-chevron">&#9660;</span>
      </div>
      <div class="pov-card-body">
        <div style="margin-bottom:.75rem;text-align:right;">
          <button class="pov-btn primary sm" onclick="povOpenSlider('slider-send-email')">&#9993; Send Email</button>
        </div>
        @php
          $vendorEmails = collect(); // POs are vendor-facing; no account email history by default
        @endphp
        <div class="pov-email-tabs">
          <div class="pov-email-tab active" onclick="povEmailTab(this,'pov-tab-sent')">Sent (0)</div>
          <div class="pov-email-tab" onclick="povEmailTab(this,'pov-tab-draft')">Drafts (0)</div>
          <div class="pov-email-tab" onclick="povEmailTab(this,'pov-tab-scheduled')">Scheduled (0)</div>
        </div>
        <div id="pov-tab-sent" class="pov-email-pane active">
          <div class="pov-empty">No sent emails for this purchase order.</div>
        </div>
        <div id="pov-tab-draft" class="pov-email-pane">
          <div class="pov-empty">No draft emails.</div>
        </div>
        <div id="pov-tab-scheduled" class="pov-email-pane">
          <div class="pov-empty">No scheduled emails.</div>
        </div>
      </div>
    </div>

  </div>{{-- end pov-main --}}

  {{-- ── RIGHT NAV ── --}}
  <div class="pov-sidebar">
    <div class="pov-sticky">
      <nav class="pov-nav">
        <a href="#sec-info"       onclick="return povScroll('sec-info')">&#128203; PO Info</a>
        <a href="#sec-notes"      onclick="return povScroll('sec-notes')">&#128221; Notes <span class="pov-nav-count">{{ $notes->count() }}</span></a>
        <a href="#sec-attach"     onclick="return povScroll('sec-attach')">&#128206; Attachments <span class="pov-nav-count">{{ $attachments->count() }}</span></a>
        <a href="#sec-open-act"   onclick="return povScroll('sec-open-act')">&#128197; Open Activities <span class="pov-nav-count">{{ $openActivities->count() }}</span></a>
        <a href="#sec-closed-act" onclick="return povScroll('sec-closed-act')">&#9989; Closed Activities <span class="pov-nav-count">{{ $closedActivities->count() }}</span></a>
        <a href="#sec-emails"     onclick="return povScroll('sec-emails')">&#9993; Emails</a>
      </nav>
    </div>
  </div>
</div>

{{-- ══ SLIDERS ══ --}}

{{-- Add Activity --}}
<div class="pov-slider-overlay" id="overlay-add-activity" onclick="povCloseSlider('slider-add-activity')"></div>
<div class="pov-slider" id="slider-add-activity">
  <div class="pov-slider-head">
    <h3>&#128197; Add Activity</h3>
    <button class="pov-slider-close" onclick="povCloseSlider('slider-add-activity')">&#10005;</button>
  </div>
  <div class="pov-slider-body">
    <form method="POST" action="{{ route('admin.crm2.inventory.purchase-orders.activities.store', $item->id) }}">
      @csrf
      <div class="pov-form-group">
        <label>Activity Type *</label>
        <select name="type" required>
          <option value="">-- Select Type --</option>
          @foreach(\App\Models\CrmActivity::TYPES as $key => $t)
            <option value="{{ $key }}">{{ $t['label'] }}</option>
          @endforeach
        </select>
      </div>
      <div class="pov-form-group">
        <label>Subject *</label>
        <input type="text" name="subject" required placeholder="Activity subject">
      </div>
      <div class="pov-form-group">
        <label>Description</label>
        <textarea name="description" rows="3" placeholder="Optional description..."></textarea>
      </div>
      <div class="pov-form-group">
        <label>Due Date & Time</label>
        <input type="datetime-local" name="due_at">
      </div>
      <div class="pov-form-actions">
        <button type="submit" class="pov-btn primary">Add Activity</button>
        <button type="button" class="pov-btn secondary" onclick="povCloseSlider('slider-add-activity')">Cancel</button>
      </div>
    </form>
  </div>
</div>

{{-- Send Email --}}
<div class="pov-slider-overlay" id="overlay-send-email" onclick="povCloseSlider('slider-send-email')"></div>
<div class="pov-slider" id="slider-send-email">
  <div class="pov-slider-head">
    <h3>&#9993; Send Email</h3>
    <button class="pov-slider-close" onclick="povCloseSlider('slider-send-email')">&#10005;</button>
  </div>
  <div class="pov-slider-body">
    @if(!$mailConfig)
      <div class="pov-alert error">No active mail configuration. Please set up SMTP in CRM Settings first.</div>
    @else
    <form method="POST" action="{{ route('admin.crm2.inventory.purchase-orders.send-mail', $item->id) }}">
      @csrf
      <div class="pov-form-group">
        <label>To *</label>
        <input type="email" name="to_email" required value="{{ $item->contact?->email ?? $item->vendor?->email ?? '' }}" placeholder="recipient@email.com">
      </div>
      <div class="pov-form-group">
        <label>CC</label>
        <input type="email" name="cc_email" placeholder="cc@email.com">
      </div>
      <div class="pov-form-group">
        <label>BCC</label>
        <input type="email" name="bcc_email" placeholder="bcc@email.com">
      </div>
      <div class="pov-form-group">
        <label>Subject *</label>
        <input type="text" name="subject" required value="Purchase Order {{ $item->po_number }}: {{ $item->subject }}">
      </div>
      <div class="pov-form-group">
        <label>Template</label>
        <select onchange="povApplyTemplate(this)">
          <option value="">-- No template --</option>
          @foreach($mailTemplates as $tpl)
            <option value="{{ $tpl->id }}" data-body="{{ htmlspecialchars($tpl->body_html ?? '') }}">{{ $tpl->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="pov-form-group">
        <label>Message *</label>
        <textarea name="body_html" id="pov-email-body" rows="8" required placeholder="Email body..."></textarea>
      </div>
      <div class="pov-form-actions">
        <button type="submit" class="pov-btn primary">&#9993; Send</button>
        <button type="button" class="pov-btn secondary" onclick="povCloseSlider('slider-send-email')">Cancel</button>
      </div>
    </form>
    @endif
  </div>
</div>

<script>
function povToggle(header) {
    header.classList.toggle('collapsed');
    header.nextElementSibling.classList.toggle('hidden');
}
function povScroll(id) {
    document.getElementById(id)?.scrollIntoView({behavior:'smooth',block:'start'});
    return false;
}
function povOpenSlider(id) {
    document.getElementById(id).classList.add('open');
    const key = id.replace('slider-','');
    const ov = document.getElementById('overlay-' + key);
    if (ov) ov.classList.add('open');
}
function povCloseSlider(id) {
    document.getElementById(id).classList.remove('open');
    const key = id.replace('slider-','');
    const ov = document.getElementById('overlay-' + key);
    if (ov) ov.classList.remove('open');
}
function povEmailTab(tab, paneId) {
    document.querySelectorAll('.pov-email-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.pov-email-pane').forEach(p => p.classList.remove('active'));
    tab.classList.add('active');
    document.getElementById(paneId).classList.add('active');
}
function povApplyTemplate(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.dataset.body) document.getElementById('pov-email-body').value = opt.dataset.body;
}
// Drag & drop
const povDz = document.getElementById('pov-dropzone');
povDz.addEventListener('dragover', e => { e.preventDefault(); povDz.classList.add('drag-over'); });
povDz.addEventListener('dragleave', () => povDz.classList.remove('drag-over'));
povDz.addEventListener('drop', e => {
    e.preventDefault(); povDz.classList.remove('drag-over');
    document.getElementById('pov-attach-file').files = e.dataTransfer.files;
    document.getElementById('pov-attach-form').submit();
});
function povDeleteNote(noteId) {
    if (!confirm('Delete this note?')) return;
    fetch('{{ route("admin.crm2.inventory.purchase-orders.notes.destroy", [$item->id, "__ID__"]) }}'.replace('__ID__', noteId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) document.getElementById('note-item-'+noteId)?.remove(); });
}
function povDeleteAttachment(attId) {
    if (!confirm('Delete this attachment?')) return;
    fetch('{{ route("admin.crm2.inventory.purchase-orders.attachments.destroy", [$item->id, "__ID__"]) }}'.replace('__ID__', attId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
function povCompleteActivity(actId) {
    fetch('{{ route("admin.crm2.inventory.purchase-orders.activities.complete", [$item->id, "__ID__"]) }}'.replace('__ID__', actId), {
        method:'PATCH', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
function povDeleteActivity(actId) {
    if (!confirm('Delete this activity?')) return;
    fetch('{{ route("admin.crm2.inventory.purchase-orders.activities.destroy", [$item->id, "__ID__"]) }}'.replace('__ID__', actId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
// Highlight nav on scroll
const povSections = ['sec-info','sec-notes','sec-attach','sec-open-act','sec-closed-act','sec-emails'];
const povNavLinks  = document.querySelectorAll('.pov-nav a');
window.addEventListener('scroll', () => {
    let cur = '';
    povSections.forEach(id => {
        const el = document.getElementById(id);
        if (el && window.scrollY >= el.offsetTop - 120) cur = id;
    });
    povNavLinks.forEach(a => a.classList.toggle('active', a.getAttribute('href') === '#' + cur));
}, {passive:true});
</script>
@endsection
