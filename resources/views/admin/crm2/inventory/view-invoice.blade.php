@extends('layouts.admin')
@section('content')
<style>
/* ── Inherits all theme vars from global admin layout — no :root override ── */
.inv-page { display:flex; gap:1.5rem; padding:1.5rem; min-height:100vh; background:var(--bg-primary); }
.inv-main  { flex:1; min-width:0; }
.inv-sidebar { width:200px; flex-shrink:0; }
.inv-sticky  { position:sticky; top:1rem; }

/* ── Header ── */
.inv-header { display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap; }
.inv-header h1 { font-size:1.3rem; font-weight:700; color:var(--text-primary); margin:0; flex:1; min-width:0; }
.inv-back { display:inline-flex; align-items:center; gap:.4rem; color:var(--accent);
            text-decoration:none; font-size:.82rem; padding:.35rem .75rem;
            border:1.5px solid var(--accent); border-radius:6px; white-space:nowrap; }
.inv-back:hover { background:var(--accent); color:#fff; }
.inv-btn { display:inline-flex; align-items:center; gap:.35rem; font-size:.82rem; font-weight:600;
           padding:.38rem .85rem; border-radius:6px; border:1.5px solid transparent;
           cursor:pointer; text-decoration:none; transition:all .15s; }
.inv-btn.primary   { background:var(--accent); color:#fff; border-color:var(--accent); }
.inv-btn.primary:hover { opacity:.88; }
.inv-btn.secondary { background:transparent; color:var(--text-secondary); border-color:var(--border); }
.inv-btn.secondary:hover { background:var(--bg-hover); }
.inv-btn.danger    { background:rgba(220,38,38,.12); color:#f87171; border-color:rgba(220,38,38,.3); }
.inv-btn.danger:hover { background:#dc2626; color:#fff; border-color:#dc2626; }
.inv-btn.sm { font-size:.75rem; padding:.28rem .6rem; }

/* ── Status badges ── */
.inv-badge { display:inline-block; padding:.25rem .75rem; border-radius:20px; font-size:.75rem; font-weight:700; letter-spacing:.03em; }
.inv-badge.draft     { background:rgba(100,116,139,.15); color:#94a3b8; }
.inv-badge.sent      { background:rgba(59,130,246,.15);  color:#60a5fa; }
.inv-badge.paid      { background:rgba(34,197,94,.15);   color:#4ade80; }
.inv-badge.overdue   { background:rgba(239,68,68,.15);   color:#f87171; }
.inv-badge.cancelled { background:rgba(239,68,68,.15);   color:#f87171; }
.inv-badge.partial   { background:rgba(245,158,11,.15);  color:#fbbf24; }

/* ── Section card ── */
.inv-card { background:var(--bg-card); border:1px solid var(--border); border-radius:10px;
            margin-bottom:1.25rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.07); }
.inv-card-header { display:flex; align-items:center; justify-content:space-between;
                   padding:.7rem 1.2rem; background:var(--accent); cursor:pointer; user-select:none; }
.inv-card-header h3 { color:#fff; font-size:.88rem; font-weight:600; margin:0; }
.inv-card-header .inv-chevron { color:#fff; font-size:.75rem; transition:transform .2s; }
.inv-card-header.collapsed .inv-chevron { transform:rotate(-90deg); }
.inv-card-body { padding:1.2rem; }
.inv-card-body.hidden { display:none; }

/* ── Info grid ── */
.inv-info-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:.9rem 1.5rem; }
.inv-info-item label { display:block; font-size:.7rem; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:.2rem; }
.inv-info-item span  { font-size:.88rem; color:var(--text-primary); font-weight:500; }
.inv-divider { border:none; border-top:1px solid var(--border); margin:1rem 0; }

/* ── Address grid ── */
.inv-addr-grid { display:grid; grid-template-columns:1fr auto 1fr; gap:0 1rem; align-items:start; }
.inv-addr-panel h4 { font-size:.8rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.05em; margin:0 0 .75rem; }
.inv-addr-divider { display:flex; flex-direction:column; align-items:center; padding-top:1.8rem; gap:.5rem; }
.inv-addr-divider .inv-vline { flex:1; width:1px; background:var(--border); min-height:20px; }
.inv-addr-row { display:flex; gap:.4rem; margin-bottom:.4rem; }
.inv-addr-row label { font-size:.7rem; color:var(--text-muted); min-width:70px; }
.inv-addr-row span  { font-size:.82rem; color:var(--text-primary); }

/* ── Line items table ── */
.inv-table { width:100%; border-collapse:collapse; font-size:.82rem; }
.inv-table th { background:var(--bg-hover); color:var(--text-secondary); font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; padding:.55rem .75rem; border-bottom:1px solid var(--border); text-align:left; }
.inv-table td { padding:.55rem .75rem; border-bottom:1px solid var(--border); color:var(--text-primary); vertical-align:middle; }
.inv-table tr:last-child td { border-bottom:none; }
.inv-table tr:hover td { background:var(--bg-hover); }
.inv-totals { display:flex; justify-content:flex-end; margin-top:1rem; }
.inv-totals-box { min-width:280px; }
.inv-totals-row { display:flex; justify-content:space-between; padding:.35rem 0; font-size:.84rem; color:var(--text-secondary); border-bottom:1px solid var(--border); }
.inv-totals-row:last-child { border-bottom:none; font-weight:700; font-size:.95rem; color:var(--text-primary); }
.inv-totals-row span:last-child { font-weight:600; color:var(--text-primary); }
.inv-totals-row.balance { color:#f87171; }
.inv-totals-row.balance span:last-child { color:#f87171; }

/* ── Notes ── */
.inv-note-form textarea { width:100%; padding:.65rem .85rem; border:1.5px solid var(--border); border-radius:7px; background:var(--bg-primary); color:var(--text-primary); font-size:.85rem; resize:vertical; min-height:80px; box-sizing:border-box; }
.inv-note-form textarea:focus { outline:none; border-color:var(--accent); }
.inv-note-list { margin-top:1rem; display:flex; flex-direction:column; gap:.75rem; max-height:320px; overflow-y:auto; }
.inv-note-item { background:var(--bg-primary); border:1px solid var(--border); border-radius:8px; padding:.75rem 1rem; }
.inv-note-meta    { font-size:.7rem; color:var(--text-muted); margin-bottom:.3rem; }
.inv-note-content { font-size:.85rem; color:var(--text-primary); line-height:1.5; white-space:pre-wrap; }

/* ── Activities ── */
.inv-act-item { display:flex; align-items:flex-start; gap:.85rem; padding:.75rem 0; border-bottom:1px solid var(--border); }
.inv-act-item:last-child { border-bottom:none; }
.inv-act-icon { width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.8rem; color:#fff; }
.inv-act-body { flex:1; min-width:0; }
.inv-act-subject { font-size:.87rem; font-weight:600; color:var(--text-primary); }
.inv-act-meta    { font-size:.72rem; color:var(--text-muted); margin-top:.15rem; }
.inv-act-desc    { font-size:.8rem; color:var(--text-secondary); margin-top:.3rem; }
.inv-act-actions { display:flex; gap:.4rem; flex-shrink:0; }
.inv-empty { text-align:center; padding:2rem; color:var(--text-muted); font-size:.85rem; }

/* ── Attachments ── */
.inv-dropzone { border:2px dashed var(--border); border-radius:8px; padding:1.5rem; text-align:center; cursor:pointer; transition:border-color .2s; }
.inv-dropzone:hover, .inv-dropzone.drag-over { border-color:var(--accent); background:rgba(99,102,241,.04); }
.inv-dropzone p { margin:.4rem 0 0; font-size:.82rem; color:var(--text-muted); }
.inv-attach-list { margin-top:1rem; display:flex; flex-direction:column; gap:.5rem; }
.inv-attach-item { display:flex; align-items:center; gap:.75rem; padding:.6rem .9rem; background:var(--bg-primary); border:1px solid var(--border); border-radius:7px; }
.inv-attach-icon { font-size:1.2rem; flex-shrink:0; }
.inv-attach-info { flex:1; min-width:0; }
.inv-attach-name { font-size:.84rem; font-weight:600; color:var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.inv-attach-meta { font-size:.7rem; color:var(--text-muted); }
.inv-attach-del  { background:none; border:none; cursor:pointer; color:var(--text-muted); font-size:1rem; padding:.2rem .4rem; border-radius:4px; }
.inv-attach-del:hover { color:#f87171; background:rgba(220,38,38,.12); }

/* ── Emails ── */
.inv-email-tabs { display:flex; gap:.5rem; margin-bottom:1rem; border-bottom:1px solid var(--border); }
.inv-email-tab  { padding:.45rem 1rem; font-size:.82rem; font-weight:600; color:var(--text-muted); cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-1px; }
.inv-email-tab.active { color:var(--accent); border-bottom-color:var(--accent); }
.inv-email-pane { display:none; }
.inv-email-pane.active { display:block; }

/* ── Right nav ── */
.inv-nav { background:var(--bg-card); border:1px solid var(--border); border-radius:10px; overflow:hidden; }
.inv-nav a { display:flex; align-items:center; gap:.6rem; padding:.65rem 1rem; font-size:.82rem; color:var(--text-secondary); text-decoration:none; border-bottom:1px solid var(--border); transition:all .15s; }
.inv-nav a:last-child { border-bottom:none; }
.inv-nav a:hover, .inv-nav a.active { background:var(--accent); color:#fff; }
.inv-nav a .inv-nav-count { margin-left:auto; background:rgba(99,102,241,.15); color:var(--accent); font-size:.7rem; font-weight:700; padding:.1rem .4rem; border-radius:10px; }
.inv-nav a:hover .inv-nav-count, .inv-nav a.active .inv-nav-count { background:rgba(255,255,255,.25); color:#fff; }

/* ── Slider ── */
.inv-slider-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:1000; }
.inv-slider-overlay.open { display:block; }
.inv-slider { position:fixed; top:0; right:-480px; width:460px; max-width:95vw; height:100vh; background:var(--bg-card); border-left:1px solid var(--border); z-index:1001; transition:right .3s ease; overflow-y:auto; display:flex; flex-direction:column; }
.inv-slider.open { right:0; }
.inv-slider-head { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.2rem; border-bottom:1px solid var(--border); background:var(--accent); }
.inv-slider-head h3 { color:#fff; font-size:.95rem; font-weight:700; margin:0; }
.inv-slider-close { background:none; border:none; color:#fff; font-size:1.3rem; cursor:pointer; padding:.2rem .5rem; border-radius:4px; }
.inv-slider-close:hover { background:rgba(255,255,255,.2); }
.inv-slider-body { padding:1.2rem; flex:1; }
.inv-form-group { margin-bottom:1rem; }
.inv-form-group label { display:block; font-size:.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:.35rem; }
.inv-form-group input, .inv-form-group select, .inv-form-group textarea {
    width:100%; padding:.55rem .8rem; border:1.5px solid var(--border); border-radius:7px;
    background:var(--bg-primary); color:var(--text-primary); font-size:.85rem; box-sizing:border-box; }
.inv-form-group input:focus, .inv-form-group select:focus, .inv-form-group textarea:focus { outline:none; border-color:var(--accent); }
.inv-form-actions { display:flex; gap:.75rem; margin-top:1.25rem; }

/* ── Alert ── */
.inv-alert { padding:.75rem 1rem; border-radius:7px; margin-bottom:1rem; font-size:.85rem; }
.inv-alert.success { background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.3); }
.inv-alert.error   { background:rgba(239,68,68,.12);  color:#f87171; border:1px solid rgba(239,68,68,.3); }
</style>

<div class="inv-page">
  {{-- ── MAIN CONTENT ── --}}
  <div class="inv-main">

    @if(session('success'))
      <div class="inv-alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="inv-alert error">{{ session('error') }}</div>
    @endif

    {{-- Header --}}
    <div class="inv-header">
      <a href="{{ route('admin.crm2.inventory.invoices') }}" class="inv-back">&#8592; Invoices</a>
      <h1>{{ $item->invoice_number ?? 'INV-'.$item->id }} &mdash; {{ $item->subject }}</h1>
      @php
        $statusClass = match($item->status ?? 'draft') {
          'sent'      => 'sent',
          'paid'      => 'paid',
          'overdue'   => 'overdue',
          'cancelled' => 'cancelled',
          'partial'   => 'partial',
          default     => 'draft',
        };
      @endphp
      <span class="inv-badge {{ $statusClass }}">{{ ucfirst($item->status ?? 'Draft') }}</span>
      <a href="{{ route('admin.crm2.inventory.invoices.edit', $item->id) }}" class="inv-btn primary">&#9998; Edit</a>
    </div>

    {{-- ══ 1. INVOICE INFORMATION ══ --}}
    <div class="inv-card" id="sec-info">
      <div class="inv-card-header" onclick="invToggle(this)">
        <h3>&#128203; Invoice Information</h3>
        <span class="inv-chevron">&#9660;</span>
      </div>
      <div class="inv-card-body">
        <div class="inv-info-grid">
          <div class="inv-info-item">
            <label>Invoice Number</label>
            <span>{{ $item->invoice_number ?? 'INV-'.$item->id }}</span>
          </div>
          <div class="inv-info-item">
            <label>Subject</label>
            <span>{{ $item->subject }}</span>
          </div>
          <div class="inv-info-item">
            <label>Status</label>
            <span><span class="inv-badge {{ $statusClass }}">{{ ucfirst($item->status ?? 'Draft') }}</span></span>
          </div>
          <div class="inv-info-item">
            <label>Invoice Date</label>
            <span>{{ $item->invoice_date ? \Carbon\Carbon::parse($item->invoice_date)->format('d M Y') : '—' }}</span>
          </div>
          <div class="inv-info-item">
            <label>Due Date</label>
            <span>{{ $item->due_date ? \Carbon\Carbon::parse($item->due_date)->format('d M Y') : '—' }}</span>
          </div>
          <div class="inv-info-item">
            <label>Payment Terms</label>
            <span>{{ $item->payment_terms ?: '—' }}</span>
          </div>
          <div class="inv-info-item">
            <label>Sales Order Ref.</label>
            <span>{{ $item->sales_order_ref ?: ($item->salesOrder?->so_number ?? '—') }}</span>
          </div>
          <div class="inv-info-item">
            <label>Account</label>
            <span>{{ $item->account?->name ?? '—' }}</span>
          </div>
          <div class="inv-info-item">
            <label>Contact</label>
            <span>{{ $item->contact ? ($item->contact->first_name . ' ' . $item->contact->last_name) : '—' }}</span>
          </div>
          <div class="inv-info-item">
            <label>Carrier</label>
            <span>{{ $item->carrier ?: '—' }}</span>
          </div>
          <div class="inv-info-item">
            <label>Excise Duty</label>
            <span>{{ $item->excise_duty ? '₹'.number_format($item->excise_duty,2) : '—' }}</span>
          </div>
          <div class="inv-info-item">
            <label>Amount Paid</label>
            <span style="color:#4ade80;font-weight:700;">{{ $item->amount_paid ? '₹'.number_format($item->amount_paid,2) : '₹0.00' }}</span>
          </div>
          <div class="inv-info-item">
            <label>Balance Due</label>
            <span style="color:#f87171;font-weight:700;">₹{{ number_format($item->balance_due ?? 0, 2) }}</span>
          </div>
          <div class="inv-info-item">
            <label>Owner</label>
            <span>{{ $item->owner?->name ?? '—' }}</span>
          </div>
          <div class="inv-info-item">
            <label>Created</label>
            <span>{{ $item->created_at->format('d M Y, H:i') }}</span>
          </div>
          <div class="inv-info-item">
            <label>Last Updated</label>
            <span>{{ $item->updated_at->format('d M Y, H:i') }}</span>
          </div>
        </div>

        <hr class="inv-divider">

        {{-- Address --}}
        <h4 style="font-size:.8rem;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.05em;margin:0 0 1rem;">Address Information</h4>
        <div class="inv-addr-grid">
          <div class="inv-addr-panel">
            <h4>Billing Address</h4>
            @if($item->bill_building || $item->bill_street || $item->bill_city)
              @if($item->bill_building)<div class="inv-addr-row"><label>Building</label><span>{{ $item->bill_building }}</span></div>@endif
              @if($item->bill_street)<div class="inv-addr-row"><label>Street</label><span>{{ $item->bill_street }}</span></div>@endif
              @if($item->bill_country)<div class="inv-addr-row"><label>Country</label><span>{{ $item->bill_country }}</span></div>@endif
              @if($item->bill_state)<div class="inv-addr-row"><label>State</label><span>{{ $item->bill_state }}</span></div>@endif
              @if($item->bill_city)<div class="inv-addr-row"><label>City</label><span>{{ $item->bill_city }}</span></div>@endif
              @if($item->bill_zip)<div class="inv-addr-row"><label>Zip</label><span>{{ $item->bill_zip }}</span></div>@endif
            @else
              <span style="font-size:.82rem;color:var(--text-muted);font-style:italic;">No billing address</span>
            @endif
          </div>
          <div class="inv-addr-divider">
            <div class="inv-vline"></div>
            <span style="font-size:.7rem;color:var(--text-muted);white-space:nowrap;">&#8644;</span>
            <div class="inv-vline"></div>
          </div>
          <div class="inv-addr-panel">
            <h4>Shipping Address</h4>
            @if($item->ship_building || $item->ship_street || $item->ship_city)
              @if($item->ship_building)<div class="inv-addr-row"><label>Building</label><span>{{ $item->ship_building }}</span></div>@endif
              @if($item->ship_street)<div class="inv-addr-row"><label>Street</label><span>{{ $item->ship_street }}</span></div>@endif
              @if($item->ship_country)<div class="inv-addr-row"><label>Country</label><span>{{ $item->ship_country }}</span></div>@endif
              @if($item->ship_state)<div class="inv-addr-row"><label>State</label><span>{{ $item->ship_state }}</span></div>@endif
              @if($item->ship_city)<div class="inv-addr-row"><label>City</label><span>{{ $item->ship_city }}</span></div>@endif
              @if($item->ship_zip)<div class="inv-addr-row"><label>Zip</label><span>{{ $item->ship_zip }}</span></div>@endif
            @else
              <span style="font-size:.82rem;color:var(--text-muted);font-style:italic;">No shipping address</span>
            @endif
          </div>
        </div>

        <hr class="inv-divider">

        {{-- Line Items --}}
        <h4 style="font-size:.8rem;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.05em;margin:0 0 1rem;">Invoiced Items</h4>
        @php $lineItems = $item->line_items ?? []; @endphp
        @if(count($lineItems) > 0)
          <table class="inv-table">
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
          <div class="inv-totals">
            <div class="inv-totals-box">
              <div class="inv-totals-row"><span>Sub Total</span><span>₹{{ number_format($item->subtotal ?? 0, 2) }}</span></div>
              <div class="inv-totals-row"><span>Discount</span><span>₹{{ number_format($item->discount_amount ?? 0, 2) }}</span></div>
              <div class="inv-totals-row"><span>Tax</span><span>₹{{ number_format($item->tax_amount ?? 0, 2) }}</span></div>
              <div class="inv-totals-row"><span>Adjustment</span><span>₹{{ number_format($item->adjustment ?? 0, 2) }}</span></div>
              <div class="inv-totals-row"><span>Grand Total</span><span>₹{{ number_format($item->grand_total ?? $item->total ?? $item->total_amount ?? 0, 2) }}</span></div>
              <div class="inv-totals-row"><span>Amount Paid</span><span style="color:#4ade80;">₹{{ number_format($item->amount_paid ?? 0, 2) }}</span></div>
              <div class="inv-totals-row balance"><span>Balance Due</span><span>₹{{ number_format($item->balance_due ?? 0, 2) }}</span></div>
            </div>
          </div>
        @else
          <div class="inv-empty">No invoiced items added yet.</div>
        @endif

        <hr class="inv-divider">

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
    <div class="inv-card" id="sec-notes">
      <div class="inv-card-header" onclick="invToggle(this)">
        <h3>&#128221; Notes <span style="font-size:.75rem;opacity:.8;">({{ $notes->count() }})</span></h3>
        <span class="inv-chevron">&#9660;</span>
      </div>
      <div class="inv-card-body">
        <form method="POST" action="{{ route('admin.crm2.inventory.invoices.notes.store', $item->id) }}" class="inv-note-form">
          @csrf
          <textarea name="content" placeholder="Add a note..." required></textarea>
          <div style="margin-top:.5rem;text-align:right;">
            <button type="submit" class="inv-btn primary">Add Note</button>
          </div>
        </form>
        @if($notes->count())
          <div class="inv-note-list">
            @foreach($notes as $note)
              <div class="inv-note-item" id="note-item-{{ $note->id }}">
                <div class="inv-note-meta" style="display:flex;justify-content:space-between;align-items:center;">
                  <span>{{ $note->user?->name ?? 'System' }} &bull; {{ $note->created_at->diffForHumans() }}</span>
                  <button onclick="invDeleteNote({{ $note->id }})" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:.8rem;padding:.1rem .3rem;" title="Delete note">&#10006;</button>
                </div>
                <div class="inv-note-content">{{ $note->content }}</div>
              </div>
            @endforeach
          </div>
        @else
          <div class="inv-empty">No notes yet. Add the first one above.</div>
        @endif
      </div>
    </div>

    {{-- ══ 3. ATTACHMENTS ══ --}}
    <div class="inv-card" id="sec-attach">
      <div class="inv-card-header" onclick="invToggle(this)">
        <h3>&#128206; Attachments <span style="font-size:.75rem;opacity:.8;">({{ $attachments->count() }})</span></h3>
        <span class="inv-chevron">&#9660;</span>
      </div>
      <div class="inv-card-body">
        <form method="POST" action="{{ route('admin.crm2.inventory.invoices.attachments.store', $item->id) }}" enctype="multipart/form-data" id="inv-attach-form">
          @csrf
          <div class="inv-dropzone" id="inv-dropzone" onclick="document.getElementById('inv-attach-file').click()">
            <div style="font-size:2rem;">&#128206;</div>
            <p>Click or drag & drop to upload (PDF, DOC, XLS, PNG, JPG, ZIP — max 10 MB)</p>
          </div>
          <input type="file" id="inv-attach-file" name="attachment" style="display:none"
                 accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip"
                 onchange="this.form.submit()">
        </form>
        @if($attachments->count())
          <div class="inv-attach-list">
            @foreach($attachments as $att)
              <div class="inv-attach-item">
                <span class="inv-attach-icon">&#128196;</span>
                <div class="inv-attach-info">
                  <div class="inv-attach-name">{{ $att->original_name }}</div>
                  <div class="inv-attach-meta">{{ $att->human_size }} &bull; {{ $att->created_at->format('d M Y') }}</div>
                </div>
                <a href="{{ route('admin.crm2.inventory.invoices.attachments.download', [$item->id, $att->id]) }}" class="inv-btn secondary sm">&#8595; Download</a>
                <button class="inv-attach-del" onclick="invDeleteAttachment({{ $att->id }})" title="Delete">&#128465;</button>
              </div>
            @endforeach
          </div>
        @else
          <div class="inv-empty" style="margin-top:.75rem;">No attachments yet.</div>
        @endif
      </div>
    </div>

    {{-- ══ 4. OPEN ACTIVITIES ══ --}}
    <div class="inv-card" id="sec-open-act">
      <div class="inv-card-header" onclick="invToggle(this)">
        <h3>&#128197; Open Activities <span style="font-size:.75rem;opacity:.8;">({{ $openActivities->count() }})</span></h3>
        <span class="inv-chevron">&#9660;</span>
      </div>
      <div class="inv-card-body">
        <div style="margin-bottom:.75rem;text-align:right;">
          <button class="inv-btn primary sm" onclick="invOpenSlider('slider-add-activity')">&#43; Add Activity</button>
        </div>
        @if($openActivities->count())
          @foreach($openActivities as $act)
            @php $ti = \App\Models\CrmActivity::TYPES[$act->type] ?? ['label'=>ucfirst($act->type),'icon'=>'fa-circle','color'=>'#6366f1']; @endphp
            <div class="inv-act-item">
              <div class="inv-act-icon" style="background:{{ $ti['color'] }};"><i class="fas {{ $ti['icon'] }}"></i></div>
              <div class="inv-act-body">
                <div class="inv-act-subject">{{ $act->subject }}</div>
                <div class="inv-act-meta">{{ $ti['label'] }} &bull; {{ $act->due_at ? $act->due_at->format('d M Y, H:i') : 'No due date' }}</div>
                @if($act->description)<div class="inv-act-desc">{{ $act->description }}</div>@endif
              </div>
              <div class="inv-act-actions">
                <button class="inv-btn primary sm" onclick="invCompleteActivity({{ $act->id }})">&#10003; Done</button>
                <button class="inv-btn danger sm"  onclick="invDeleteActivity({{ $act->id }})">&#10006;</button>
              </div>
            </div>
          @endforeach
        @else
          <div class="inv-empty">No open activities.</div>
        @endif
      </div>
    </div>

    {{-- ══ 5. CLOSED ACTIVITIES ══ --}}
    <div class="inv-card" id="sec-closed-act">
      <div class="inv-card-header" onclick="invToggle(this)">
        <h3>&#9989; Closed Activities <span style="font-size:.75rem;opacity:.8;">({{ $closedActivities->count() }})</span></h3>
        <span class="inv-chevron">&#9660;</span>
      </div>
      <div class="inv-card-body">
        @if($closedActivities->count())
          @foreach($closedActivities as $act)
            @php $ti = \App\Models\CrmActivity::TYPES[$act->type] ?? ['label'=>ucfirst($act->type),'icon'=>'fa-circle','color'=>'#6366f1']; @endphp
            <div class="inv-act-item" style="opacity:.7;">
              <div class="inv-act-icon" style="background:{{ $ti['color'] }};"><i class="fas {{ $ti['icon'] }}"></i></div>
              <div class="inv-act-body">
                <div class="inv-act-subject" style="text-decoration:line-through;">{{ $act->subject }}</div>
                <div class="inv-act-meta">{{ $ti['label'] }} &bull; Completed {{ $act->completed_at ? $act->completed_at->format('d M Y') : '' }}</div>
              </div>
              <div class="inv-act-actions">
                <button class="inv-btn danger sm" onclick="invDeleteActivity({{ $act->id }})">&#10006;</button>
              </div>
            </div>
          @endforeach
        @else
          <div class="inv-empty">No closed activities.</div>
        @endif
      </div>
    </div>

    {{-- ══ 6. EMAILS ══ --}}
    <div class="inv-card" id="sec-emails">
      <div class="inv-card-header" onclick="invToggle(this)">
        <h3>&#9993; Emails</h3>
        <span class="inv-chevron">&#9660;</span>
      </div>
      <div class="inv-card-body">
        <div style="margin-bottom:.75rem;text-align:right;">
          <button class="inv-btn primary sm" onclick="invOpenSlider('slider-send-email')">&#9993; Send Email</button>
        </div>
        <div class="inv-email-tabs">
          <div class="inv-email-tab active" onclick="invEmailTab(this,'inv-tab-sent')">Sent (0)</div>
          <div class="inv-email-tab" onclick="invEmailTab(this,'inv-tab-draft')">Drafts (0)</div>
          <div class="inv-email-tab" onclick="invEmailTab(this,'inv-tab-scheduled')">Scheduled (0)</div>
        </div>
        <div id="inv-tab-sent" class="inv-email-pane active">
          <div class="inv-empty">No sent emails for this invoice.</div>
        </div>
        <div id="inv-tab-draft" class="inv-email-pane">
          <div class="inv-empty">No draft emails.</div>
        </div>
        <div id="inv-tab-scheduled" class="inv-email-pane">
          <div class="inv-empty">No scheduled emails.</div>
        </div>
      </div>
    </div>

  </div>{{-- end inv-main --}}

  {{-- ── RIGHT NAV ── --}}
  <div class="inv-sidebar">
    <div class="inv-sticky">
      <nav class="inv-nav">
        <a href="#sec-info"       onclick="return invScroll('sec-info')">&#128203; Invoice Info</a>
        <a href="#sec-notes"      onclick="return invScroll('sec-notes')">&#128221; Notes <span class="inv-nav-count">{{ $notes->count() }}</span></a>
        <a href="#sec-attach"     onclick="return invScroll('sec-attach')">&#128206; Attachments <span class="inv-nav-count">{{ $attachments->count() }}</span></a>
        <a href="#sec-open-act"   onclick="return invScroll('sec-open-act')">&#128197; Open Activities <span class="inv-nav-count">{{ $openActivities->count() }}</span></a>
        <a href="#sec-closed-act" onclick="return invScroll('sec-closed-act')">&#9989; Closed Activities <span class="inv-nav-count">{{ $closedActivities->count() }}</span></a>
        <a href="#sec-emails"     onclick="return invScroll('sec-emails')">&#9993; Emails</a>
      </nav>
    </div>
  </div>
</div>

{{-- ══ SLIDERS ══ --}}

{{-- Add Activity --}}
<div class="inv-slider-overlay" id="overlay-add-activity" onclick="invCloseSlider('slider-add-activity')"></div>
<div class="inv-slider" id="slider-add-activity">
  <div class="inv-slider-head">
    <h3>&#128197; Add Activity</h3>
    <button class="inv-slider-close" onclick="invCloseSlider('slider-add-activity')">&#10005;</button>
  </div>
  <div class="inv-slider-body">
    <form method="POST" action="{{ route('admin.crm2.inventory.invoices.activities.store', $item->id) }}">
      @csrf
      <div class="inv-form-group">
        <label>Activity Type *</label>
        <select name="type" required>
          <option value="">-- Select Type --</option>
          @foreach(\App\Models\CrmActivity::TYPES as $key => $t)
            <option value="{{ $key }}">{{ $t['label'] }}</option>
          @endforeach
        </select>
      </div>
      <div class="inv-form-group">
        <label>Subject *</label>
        <input type="text" name="subject" required placeholder="Activity subject">
      </div>
      <div class="inv-form-group">
        <label>Description</label>
        <textarea name="description" rows="3" placeholder="Optional description..."></textarea>
      </div>
      <div class="inv-form-group">
        <label>Due Date & Time</label>
        <input type="datetime-local" name="due_at">
      </div>
      <div class="inv-form-actions">
        <button type="submit" class="inv-btn primary">Add Activity</button>
        <button type="button" class="inv-btn secondary" onclick="invCloseSlider('slider-add-activity')">Cancel</button>
      </div>
    </form>
  </div>
</div>

{{-- Send Email --}}
<div class="inv-slider-overlay" id="overlay-send-email" onclick="invCloseSlider('slider-send-email')"></div>
<div class="inv-slider" id="slider-send-email">
  <div class="inv-slider-head">
    <h3>&#9993; Send Email</h3>
    <button class="inv-slider-close" onclick="invCloseSlider('slider-send-email')">&#10005;</button>
  </div>
  <div class="inv-slider-body">
    @if(!$mailConfig)
      <div class="inv-alert error">No active mail configuration. Please set up SMTP in CRM Settings first.</div>
    @else
    <form method="POST" action="{{ route('admin.crm2.inventory.invoices.send-mail', $item->id) }}">
      @csrf
      <div class="inv-form-group">
        <label>To *</label>
        <input type="email" name="to_email" required value="{{ $item->contact?->email ?? $item->account?->email ?? '' }}" placeholder="recipient@email.com">
      </div>
      <div class="inv-form-group">
        <label>CC</label>
        <input type="email" name="cc_email" placeholder="cc@email.com">
      </div>
      <div class="inv-form-group">
        <label>BCC</label>
        <input type="email" name="bcc_email" placeholder="bcc@email.com">
      </div>
      <div class="inv-form-group">
        <label>Subject *</label>
        <input type="text" name="subject" required value="Invoice {{ $item->invoice_number ?? 'INV-'.$item->id }}: {{ $item->subject }}">
      </div>
      <div class="inv-form-group">
        <label>Template</label>
        <select onchange="invApplyTemplate(this)">
          <option value="">-- No template --</option>
          @foreach($mailTemplates as $tpl)
            <option value="{{ $tpl->id }}" data-body="{{ htmlspecialchars($tpl->body_html ?? '') }}">{{ $tpl->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="inv-form-group">
        <label>Message *</label>
        <textarea name="body_html" id="inv-email-body" rows="8" required placeholder="Email body..."></textarea>
      </div>
      <div class="inv-form-actions">
        <button type="submit" class="inv-btn primary">&#9993; Send</button>
        <button type="button" class="inv-btn secondary" onclick="invCloseSlider('slider-send-email')">Cancel</button>
      </div>
    </form>
    @endif
  </div>
</div>

<script>
function invToggle(header) {
    header.classList.toggle('collapsed');
    header.nextElementSibling.classList.toggle('hidden');
}
function invScroll(id) {
    document.getElementById(id)?.scrollIntoView({behavior:'smooth',block:'start'});
    return false;
}
function invOpenSlider(id) {
    document.getElementById(id).classList.add('open');
    const key = id.replace('slider-','');
    const ov = document.getElementById('overlay-' + key);
    if (ov) ov.classList.add('open');
}
function invCloseSlider(id) {
    document.getElementById(id).classList.remove('open');
    const key = id.replace('slider-','');
    const ov = document.getElementById('overlay-' + key);
    if (ov) ov.classList.remove('open');
}
function invEmailTab(tab, paneId) {
    document.querySelectorAll('.inv-email-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.inv-email-pane').forEach(p => p.classList.remove('active'));
    tab.classList.add('active');
    document.getElementById(paneId).classList.add('active');
}
function invApplyTemplate(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.dataset.body) document.getElementById('inv-email-body').value = opt.dataset.body;
}
// Drag & drop
const invDz = document.getElementById('inv-dropzone');
invDz.addEventListener('dragover', e => { e.preventDefault(); invDz.classList.add('drag-over'); });
invDz.addEventListener('dragleave', () => invDz.classList.remove('drag-over'));
invDz.addEventListener('drop', e => {
    e.preventDefault(); invDz.classList.remove('drag-over');
    document.getElementById('inv-attach-file').files = e.dataTransfer.files;
    document.getElementById('inv-attach-form').submit();
});
function invDeleteNote(noteId) {
    if (!confirm('Delete this note?')) return;
    fetch('{{ route("admin.crm2.inventory.invoices.notes.destroy", [$item->id, "__ID__"]) }}'.replace('__ID__', noteId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) document.getElementById('note-item-'+noteId)?.remove(); });
}
function invDeleteAttachment(attId) {
    if (!confirm('Delete this attachment?')) return;
    fetch('{{ route("admin.crm2.inventory.invoices.attachments.destroy", [$item->id, "__ID__"]) }}'.replace('__ID__', attId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
function invCompleteActivity(actId) {
    fetch('{{ route("admin.crm2.inventory.invoices.activities.complete", [$item->id, "__ID__"]) }}'.replace('__ID__', actId), {
        method:'PATCH', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
function invDeleteActivity(actId) {
    if (!confirm('Delete this activity?')) return;
    fetch('{{ route("admin.crm2.inventory.invoices.activities.destroy", [$item->id, "__ID__"]) }}'.replace('__ID__', actId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
// Highlight nav on scroll
const invSections = ['sec-info','sec-notes','sec-attach','sec-open-act','sec-closed-act','sec-emails'];
const invNavLinks  = document.querySelectorAll('.inv-nav a');
window.addEventListener('scroll', () => {
    let cur = '';
    invSections.forEach(id => {
        const el = document.getElementById(id);
        if (el && window.scrollY >= el.offsetTop - 120) cur = id;
    });
    invNavLinks.forEach(a => a.classList.toggle('active', a.getAttribute('href') === '#' + cur));
}, {passive:true});
</script>
@endsection
