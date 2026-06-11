@extends('layouts.admin')
@section('content')
<style>
/* ── Theme-aware variables (inherit from global admin layout) ── */
.qv-page { display:flex; gap:1.5rem; padding:1.5rem; min-height:100vh; background:var(--bg-primary); }
.qv-main { flex:1; min-width:0; }
.qv-sidebar { width:200px; flex-shrink:0; }
.qv-sticky { position:sticky; top:1rem; }

/* ── Header ── */
.qv-header { display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap; }
.qv-header h1 { font-size:1.3rem; font-weight:700; color:var(--text-primary); margin:0; flex:1; }
.qv-back { display:inline-flex; align-items:center; gap:.4rem; color:var(--accent);
           text-decoration:none; font-size:.82rem; padding:.35rem .75rem;
           border:1.5px solid var(--accent); border-radius:6px; white-space:nowrap; }
.qv-back:hover { background:var(--accent); color:#fff; }
.qv-btn { display:inline-flex; align-items:center; gap:.35rem; font-size:.82rem; font-weight:600;
          padding:.38rem .85rem; border-radius:6px; border:1.5px solid transparent;
          cursor:pointer; text-decoration:none; transition:all .15s; }
.qv-btn.primary { background:var(--accent); color:#fff; border-color:var(--accent); }
.qv-btn.primary:hover { opacity:.88; }
.qv-btn.secondary { background:transparent; color:var(--text-secondary); border-color:var(--border); }
.qv-btn.secondary:hover { background:var(--bg-hover); }
.qv-btn.danger { background:rgba(220,38,38,.12); color:#f87171; border-color:rgba(220,38,38,.3); }
.qv-btn.danger:hover { background:#dc2626; color:#fff; border-color:#dc2626; }
.qv-btn.sm { font-size:.75rem; padding:.28rem .6rem; }

/* ── Stage badge ── */
.qv-stage { display:inline-block; padding:.25rem .75rem; border-radius:20px; font-size:.75rem; font-weight:700; letter-spacing:.03em; }
.stage-draft       { background:rgba(100,116,139,.15); color:#94a3b8; }
.stage-negotiation { background:rgba(245,158,11,.15);  color:#fbbf24; }
.stage-delivered   { background:rgba(59,130,246,.15);  color:#60a5fa; }
.stage-accepted    { background:rgba(34,197,94,.15);   color:#4ade80; }
.stage-declined    { background:rgba(239,68,68,.15);   color:#f87171; }

/* ── Section card ── */
.qv-card { background:var(--bg-card); border:1px solid var(--border); border-radius:10px;
           margin-bottom:1.25rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.07); }
.qv-card-header { display:flex; align-items:center; justify-content:space-between;
                  padding:.7rem 1.2rem; background:var(--accent); cursor:pointer; user-select:none; }
.qv-card-header h3 { color:#fff; font-size:.88rem; font-weight:600; margin:0; }
.qv-card-header .qv-chevron { color:#fff; font-size:.75rem; transition:transform .2s; }
.qv-card-header.collapsed .qv-chevron { transform:rotate(-90deg); }
.qv-card-body { padding:1.2rem; }
.qv-card-body.hidden { display:none; }

/* ── Info grid ── */
.qv-info-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:.9rem 1.5rem; }
.qv-info-item label { display:block; font-size:.7rem; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:.2rem; }
.qv-info-item span { font-size:.88rem; color:var(--text-primary); font-weight:500; }
.qv-info-item span.empty { color:var(--text-muted); font-style:italic; }
.qv-divider { border:none; border-top:1px solid var(--border); margin:1rem 0; }

/* ── Address grid ── */
.qv-addr-grid { display:grid; grid-template-columns:1fr auto 1fr; gap:0 1rem; align-items:start; }
.qv-addr-panel h4 { font-size:.8rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.05em; margin:0 0 .75rem; }
.qv-addr-divider { display:flex; flex-direction:column; align-items:center; padding-top:1.8rem; gap:.5rem; }
.qv-addr-divider .qv-vline { flex:1; width:1px; background:var(--border); min-height:20px; }
.qv-addr-row { display:flex; gap:.4rem; margin-bottom:.4rem; }
.qv-addr-row label { font-size:.7rem; color:var(--text-muted); min-width:70px; }
.qv-addr-row span { font-size:.82rem; color:var(--text-primary); }

/* ── Line items table ── */
.qv-table { width:100%; border-collapse:collapse; font-size:.82rem; }
.qv-table th { background:var(--bg-hover); color:var(--text-secondary); font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; padding:.55rem .75rem; border-bottom:1px solid var(--border); text-align:left; }
.qv-table td { padding:.55rem .75rem; border-bottom:1px solid var(--border); color:var(--text-primary); vertical-align:middle; }
.qv-table tr:last-child td { border-bottom:none; }
.qv-table tr:hover td { background:var(--bg-hover); }
.qv-totals { display:flex; justify-content:flex-end; margin-top:1rem; }
.qv-totals-box { min-width:260px; }
.qv-totals-row { display:flex; justify-content:space-between; padding:.35rem 0; font-size:.84rem; color:var(--text-secondary); border-bottom:1px solid var(--border); }
.qv-totals-row:last-child { border-bottom:none; font-weight:700; font-size:.95rem; color:var(--text-primary); }
.qv-totals-row span:last-child { font-weight:600; color:var(--text-primary); }

/* ── Notes ── */
.qv-note-form textarea { width:100%; padding:.65rem .85rem; border:1.5px solid var(--border); border-radius:7px; background:var(--bg-primary); color:var(--text-primary); font-size:.85rem; resize:vertical; min-height:80px; }
.qv-note-form textarea:focus { outline:none; border-color:var(--accent); }
.qv-note-list { margin-top:1rem; display:flex; flex-direction:column; gap:.75rem; max-height:320px; overflow-y:auto; }
.qv-note-item { background:var(--bg-primary); border:1px solid var(--border); border-radius:8px; padding:.75rem 1rem; }
.qv-note-meta { font-size:.7rem; color:var(--text-muted); margin-bottom:.3rem; }
.qv-note-content { font-size:.85rem; color:var(--text-primary); line-height:1.5; white-space:pre-wrap; }

/* ── Activities ── */
.qv-act-item { display:flex; align-items:flex-start; gap:.85rem; padding:.75rem 0; border-bottom:1px solid var(--border); }
.qv-act-item:last-child { border-bottom:none; }
.qv-act-icon { width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.8rem; color:#fff; }
.qv-act-body { flex:1; min-width:0; }
.qv-act-subject { font-size:.87rem; font-weight:600; color:var(--text-primary); }
.qv-act-meta { font-size:.72rem; color:var(--text-muted); margin-top:.15rem; }
.qv-act-desc { font-size:.8rem; color:var(--text-secondary); margin-top:.3rem; }
.qv-act-actions { display:flex; gap:.4rem; flex-shrink:0; }
.qv-empty { text-align:center; padding:2rem; color:var(--text-muted); font-size:.85rem; }

/* ── Sales Orders ── */
.qv-so-actions { display:flex; gap:.5rem; margin-bottom:1rem; }

/* ── Attachments ── */
.qv-dropzone { border:2px dashed var(--border); border-radius:8px; padding:1.5rem; text-align:center; cursor:pointer; transition:border-color .2s; }
.qv-dropzone:hover, .qv-dropzone.drag-over { border-color:var(--accent); background:rgba(99,102,241,.04); }
.qv-dropzone p { margin:.4rem 0 0; font-size:.82rem; color:var(--text-muted); }
.qv-attach-list { margin-top:1rem; display:flex; flex-direction:column; gap:.5rem; }
.qv-attach-item { display:flex; align-items:center; gap:.75rem; padding:.6rem .9rem; background:var(--bg-primary); border:1px solid var(--border); border-radius:7px; }
.qv-attach-icon { font-size:1.2rem; flex-shrink:0; }
.qv-attach-info { flex:1; min-width:0; }
.qv-attach-name { font-size:.84rem; font-weight:600; color:var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.qv-attach-meta { font-size:.7rem; color:var(--text-muted); }
.qv-attach-del { background:none; border:none; cursor:pointer; color:var(--text-muted); font-size:1rem; padding:.2rem .4rem; border-radius:4px; }
.qv-attach-del:hover { color:#f87171; background:rgba(220,38,38,.12); }

/* ── Emails ── */
.qv-email-tabs { display:flex; gap:.5rem; margin-bottom:1rem; border-bottom:1px solid var(--border); }
.qv-email-tab { padding:.45rem 1rem; font-size:.82rem; font-weight:600; color:var(--text-muted); cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-1px; }
.qv-email-tab.active { color:var(--accent); border-bottom-color:var(--accent); }
.qv-email-pane { display:none; }
.qv-email-pane.active { display:block; }
.qv-email-item { padding:.7rem 0; border-bottom:1px solid var(--border); }
.qv-email-item:last-child { border-bottom:none; }
.qv-email-subject { font-size:.87rem; font-weight:600; color:var(--text-primary); }
.qv-email-meta { font-size:.72rem; color:var(--text-muted); margin-top:.15rem; }

/* ── Right nav ── */
.qv-nav { background:var(--bg-card); border:1px solid var(--border); border-radius:10px; overflow:hidden; }
.qv-nav a { display:flex; align-items:center; gap:.6rem; padding:.65rem 1rem; font-size:.82rem; color:var(--text-secondary); text-decoration:none; border-bottom:1px solid var(--border); transition:all .15s; }
.qv-nav a:last-child { border-bottom:none; }
.qv-nav a:hover, .qv-nav a.active { background:var(--accent); color:#fff; }
.qv-nav a .qv-nav-count { margin-left:auto; background:rgba(99,102,241,.15); color:var(--accent); font-size:.7rem; font-weight:700; padding:.1rem .4rem; border-radius:10px; }
.qv-nav a:hover .qv-nav-count, .qv-nav a.active .qv-nav-count { background:rgba(255,255,255,.25); color:#fff; }

/* ── Slider panel ── */
.qv-slider-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:1000; }
.qv-slider-overlay.open { display:block; }
.qv-slider { position:fixed; top:0; right:-480px; width:460px; max-width:95vw; height:100vh; background:var(--bg-card); border-left:1px solid var(--border); z-index:1001; transition:right .3s ease; overflow-y:auto; display:flex; flex-direction:column; }
.qv-slider.open { right:0; }
.qv-slider-head { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.2rem; border-bottom:1px solid var(--border); background:var(--accent); }
.qv-slider-head h3 { color:#fff; font-size:.95rem; font-weight:700; margin:0; }
.qv-slider-close { background:none; border:none; color:#fff; font-size:1.3rem; cursor:pointer; padding:.2rem .5rem; border-radius:4px; }
.qv-slider-close:hover { background:rgba(255,255,255,.2); }
.qv-slider-body { padding:1.2rem; flex:1; }
.qv-form-group { margin-bottom:1rem; }
.qv-form-group label { display:block; font-size:.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:.35rem; }
.qv-form-group input, .qv-form-group select, .qv-form-group textarea {
    width:100%; padding:.55rem .8rem; border:1.5px solid var(--border); border-radius:7px;
    background:var(--bg-primary); color:var(--text-primary); font-size:.85rem; }
.qv-form-group input:focus, .qv-form-group select:focus, .qv-form-group textarea:focus { outline:none; border-color:var(--accent); }
.qv-form-actions { display:flex; gap:.75rem; margin-top:1.25rem; }

/* ── Alert ── */
.qv-alert { padding:.75rem 1rem; border-radius:7px; margin-bottom:1rem; font-size:.85rem; }
.qv-alert.success { background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.3); }
.qv-alert.error   { background:rgba(239,68,68,.12);  color:#f87171; border:1px solid rgba(239,68,68,.3); }
</style>

<div class="qv-page">
  {{-- ── MAIN CONTENT ── --}}
  <div class="qv-main">

    {{-- Flash messages --}}
    @if(session('success'))
      <div class="qv-alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="qv-alert error">{{ session('error') }}</div>
    @endif

    {{-- Header --}}
    <div class="qv-header">
      <a href="{{ route('admin.crm2.inventory.quotes') }}" class="qv-back">&#8592; Quotes</a>
      <h1>{{ $item->quote_number }} &mdash; {{ $item->subject }}</h1>
      <span class="qv-stage stage-{{ $item->stage }}">{{ \App\Models\CrmQuote::STAGES[$item->stage] ?? ucfirst($item->stage) }}</span>
      <a href="{{ route('admin.crm2.inventory.quotes.edit', $item->id) }}" class="qv-btn primary">&#9998; Edit</a>
    </div>

    {{-- ══ 1. QUOTE INFORMATION ══ --}}
    <div class="qv-card" id="sec-info">
      <div class="qv-card-header" onclick="toggleCard(this)">
        <h3>&#128196; Quote Information</h3>
        <span class="qv-chevron">&#9660;</span>
      </div>
      <div class="qv-card-body">
        {{-- Basic Info --}}
        <div class="qv-info-grid">
          <div class="qv-info-item">
            <label>Quote Number</label>
            <span>{{ $item->quote_number }}</span>
          </div>
          <div class="qv-info-item">
            <label>Subject</label>
            <span>{{ $item->subject }}</span>
          </div>
          <div class="qv-info-item">
            <label>Stage</label>
            <span><span class="qv-stage stage-{{ $item->stage }}">{{ \App\Models\CrmQuote::STAGES[$item->stage] ?? ucfirst($item->stage) }}</span></span>
          </div>
          <div class="qv-info-item">
            <label>Valid Until</label>
            <span>{{ $item->valid_until ? $item->valid_until->format('d M Y') : '—' }}</span>
          </div>
          <div class="qv-info-item">
            <label>Quote Owner</label>
            <span>{{ $item->owner?->name ?? '—' }}</span>
          </div>
          <div class="qv-info-item">
            <label>Team</label>
            <span>{{ $item->team ?: '—' }}</span>
          </div>
          <div class="qv-info-item">
            <label>Carrier</label>
            <span>{{ $item->carrier ?: '—' }}</span>
          </div>
          <div class="qv-info-item">
            <label>Account</label>
            <span>{{ $item->account?->name ?? '—' }}</span>
          </div>
          <div class="qv-info-item">
            <label>Contact</label>
            <span>{{ $item->contact ? ($item->contact->first_name . ' ' . $item->contact->last_name) : '—' }}</span>
          </div>
          <div class="qv-info-item">
            <label>Deal</label>
            <span>{{ $item->deal?->name ?? '—' }}</span>
          </div>
          <div class="qv-info-item">
            <label>Created</label>
            <span>{{ $item->created_at->format('d M Y, H:i') }}</span>
          </div>
          <div class="qv-info-item">
            <label>Last Updated</label>
            <span>{{ $item->updated_at->format('d M Y, H:i') }}</span>
          </div>
        </div>

        <hr class="qv-divider">

        {{-- Address Information --}}
        <h4 style="font-size:.8rem;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.05em;margin:0 0 1rem;">Address Information</h4>
        <div class="qv-addr-grid">
          <div class="qv-addr-panel">
            <h4>Billing Address</h4>
            @if($item->bill_building || $item->bill_street || $item->bill_city)
              @if($item->bill_building)<div class="qv-addr-row"><label>Building</label><span>{{ $item->bill_building }}</span></div>@endif
              @if($item->bill_street)<div class="qv-addr-row"><label>Street</label><span>{{ $item->bill_street }}</span></div>@endif
              @if($item->bill_country)<div class="qv-addr-row"><label>Country</label><span>{{ $item->bill_country }}</span></div>@endif
              @if($item->bill_state)<div class="qv-addr-row"><label>State</label><span>{{ $item->bill_state }}</span></div>@endif
              @if($item->bill_city)<div class="qv-addr-row"><label>City</label><span>{{ $item->bill_city }}</span></div>@endif
              @if($item->bill_zip)<div class="qv-addr-row"><label>Zip</label><span>{{ $item->bill_zip }}</span></div>@endif
            @else
              <span class="empty" style="font-size:.82rem;color:var(--text-muted);font-style:italic;">No billing address</span>
            @endif
          </div>
          <div class="qv-addr-divider">
            <div class="qv-vline"></div>
            <span style="font-size:.7rem;color:var(--text-muted);white-space:nowrap;">&#8644;</span>
            <div class="qv-vline"></div>
          </div>
          <div class="qv-addr-panel">
            <h4>Shipping Address</h4>
            @if($item->ship_building || $item->ship_street || $item->ship_city)
              @if($item->ship_building)<div class="qv-addr-row"><label>Building</label><span>{{ $item->ship_building }}</span></div>@endif
              @if($item->ship_street)<div class="qv-addr-row"><label>Street</label><span>{{ $item->ship_street }}</span></div>@endif
              @if($item->ship_country)<div class="qv-addr-row"><label>Country</label><span>{{ $item->ship_country }}</span></div>@endif
              @if($item->ship_state)<div class="qv-addr-row"><label>State</label><span>{{ $item->ship_state }}</span></div>@endif
              @if($item->ship_city)<div class="qv-addr-row"><label>City</label><span>{{ $item->ship_city }}</span></div>@endif
              @if($item->ship_zip)<div class="qv-addr-row"><label>Zip</label><span>{{ $item->ship_zip }}</span></div>@endif
            @else
              <span class="empty" style="font-size:.82rem;color:var(--text-muted);font-style:italic;">No shipping address</span>
            @endif
          </div>
        </div>

        <hr class="qv-divider">

        {{-- Quoted Items --}}
        <h4 style="font-size:.8rem;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.05em;margin:0 0 1rem;">Quoted Items</h4>
        @php $lineItems = $item->line_items ?? []; @endphp
        @if(count($lineItems) > 0)
          <table class="qv-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Product</th>
                <th>Qty</th>
                <th>List Price (₹)</th>
                <th>Amount (₹)</th>
                <th>Discount (₹)</th>
                <th>Tax (₹)</th>
                <th>Total (₹)</th>
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
          <div class="qv-totals">
            <div class="qv-totals-box">
              <div class="qv-totals-row"><span>Sub Total</span><span>₹{{ number_format($item->subtotal, 2) }}</span></div>
              <div class="qv-totals-row"><span>Discount</span><span>₹{{ number_format($item->discount_amount, 2) }}</span></div>
              <div class="qv-totals-row"><span>Tax</span><span>₹{{ number_format($item->tax_amount, 2) }}</span></div>
              <div class="qv-totals-row"><span>Adjustment</span><span>₹{{ number_format($item->adjustment, 2) }}</span></div>
              <div class="qv-totals-row"><span>Grand Total</span><span>₹{{ number_format($item->grand_total, 2) }}</span></div>
            </div>
          </div>
        @else
          <div class="qv-empty">No quoted items added yet.</div>
        @endif

        <hr class="qv-divider">

        {{-- Terms & Description --}}
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
    <div class="qv-card" id="sec-notes">
      <div class="qv-card-header" onclick="toggleCard(this)">
        <h3>&#128221; Notes <span style="font-size:.75rem;opacity:.8;">({{ $notes->count() }})</span></h3>
        <span class="qv-chevron">&#9660;</span>
      </div>
      <div class="qv-card-body">
        <form method="POST" action="{{ route('admin.crm2.inventory.quotes.notes.store', $item->id) }}" class="qv-note-form">
          @csrf
          <textarea name="content" placeholder="Add a note..." required></textarea>
          <div style="margin-top:.5rem;text-align:right;">
            <button type="submit" class="qv-btn primary">Add Note</button>
          </div>
        </form>
        @if($notes->count())
          <div class="qv-note-list">
            @foreach($notes as $note)
              <div class="qv-note-item">
                <div class="qv-note-meta">{{ $note->user?->name ?? 'System' }} &bull; {{ $note->created_at->diffForHumans() }}</div>
                <div class="qv-note-content">{{ $note->content }}</div>
              </div>
            @endforeach
          </div>
        @else
          <div class="qv-empty">No notes yet. Add the first one above.</div>
        @endif
      </div>
    </div>

    {{-- ══ 3. SALES ORDERS ══ --}}
    <div class="qv-card" id="sec-so">
      <div class="qv-card-header" onclick="toggleCard(this)">
        <h3>&#128203; Sales Orders <span style="font-size:.75rem;opacity:.8;">({{ $salesOrders->count() }})</span></h3>
        <span class="qv-chevron">&#9660;</span>
      </div>
      <div class="qv-card-body">
        <div class="qv-so-actions">
          <button class="qv-btn secondary sm" onclick="openSlider('slider-assign-so')">&#128279; Assign Existing</button>
          <a href="{{ route('admin.crm2.inventory.sales-orders.create') }}?quote_id={{ $item->id }}" class="qv-btn primary sm">&#43; New Sales Order</a>
        </div>
        @if($salesOrders->count())
          <table class="qv-table">
            <thead>
              <tr>
                <th>#</th>
                <th>SO Number</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Grand Total</th>
                <th>Delivery Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($salesOrders as $i => $so)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td><a href="{{ route('admin.crm2.inventory.sales-orders.show', $so->id) }}" style="color:var(--accent);text-decoration:none;font-weight:600;">{{ $so->so_number }}</a></td>
                <td>{{ $so->subject }}</td>
                <td><span class="qv-stage stage-{{ strtolower($so->status) }}">{{ ucfirst($so->status) }}</span></td>
                <td>₹{{ number_format($so->grand_total, 2) }}</td>
                <td>{{ $so->delivery_date ? $so->delivery_date->format('d M Y') : '—' }}</td>
                <td>
                  <a href="{{ route('admin.crm2.inventory.sales-orders.edit', $so->id) }}" class="qv-btn secondary sm">&#9998; Edit</a>
                  <button class="qv-btn danger sm" onclick="unassignSO({{ $so->id }})">&#10006; Unlink</button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div class="qv-empty">No sales orders linked to this quote yet.</div>
        @endif
      </div>
    </div>

    {{-- ══ 4. ATTACHMENTS ══ --}}
    <div class="qv-card" id="sec-attach">
      <div class="qv-card-header" onclick="toggleCard(this)">
        <h3>&#128206; Attachments <span style="font-size:.75rem;opacity:.8;">({{ $attachments->count() }})</span></h3>
        <span class="qv-chevron">&#9660;</span>
      </div>
      <div class="qv-card-body">
        <form method="POST" action="{{ route('admin.crm2.inventory.quotes.attachments.store', $item->id) }}" enctype="multipart/form-data" id="attach-form">
          @csrf
          <div class="qv-dropzone" id="dropzone" onclick="document.getElementById('attach-file').click()">
            <div style="font-size:2rem;">&#128206;</div>
            <p>Click or drag & drop to upload (PDF, DOC, XLS, PNG, JPG, ZIP — max 10 MB)</p>
          </div>
          <input type="file" id="attach-file" name="attachment" style="display:none" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip" onchange="this.form.submit()">
        </form>
        @if($attachments->count())
          <div class="qv-attach-list">
            @foreach($attachments as $att)
              <div class="qv-attach-item">
                <span class="qv-attach-icon">&#128196;</span>
                <div class="qv-attach-info">
                  <div class="qv-attach-name">{{ $att->original_name }}</div>
                  <div class="qv-attach-meta">{{ $att->human_size }} &bull; {{ $att->created_at->format('d M Y') }}</div>
                </div>
                <a href="{{ route('admin.crm2.inventory.quotes.attachments.download', [$item->id, $att->id]) }}" class="qv-btn secondary sm">&#8595; Download</a>
                <button class="qv-attach-del" onclick="deleteAttachment({{ $att->id }})" title="Delete">&#128465;</button>
              </div>
            @endforeach
          </div>
        @else
          <div class="qv-empty" style="margin-top:.75rem;">No attachments yet.</div>
        @endif
      </div>
    </div>

    {{-- ══ 5. OPEN ACTIVITIES ══ --}}
    <div class="qv-card" id="sec-open-act">
      <div class="qv-card-header" onclick="toggleCard(this)">
        <h3>&#128197; Open Activities <span style="font-size:.75rem;opacity:.8;">({{ $openActivities->count() }})</span></h3>
        <span class="qv-chevron">&#9660;</span>
      </div>
      <div class="qv-card-body">
        <div style="margin-bottom:.75rem;text-align:right;">
          <button class="qv-btn primary sm" onclick="openSlider('slider-add-activity')">&#43; Add Activity</button>
        </div>
        @if($openActivities->count())
          @foreach($openActivities as $act)
            @php $typeInfo = \App\Models\CrmActivity::TYPES[$act->type] ?? ['label'=>ucfirst($act->type),'icon'=>'fa-circle','color'=>'#6366f1']; @endphp
            <div class="qv-act-item">
              <div class="qv-act-icon" style="background:{{ $typeInfo['color'] }};">
                <i class="fas {{ $typeInfo['icon'] }}"></i>
              </div>
              <div class="qv-act-body">
                <div class="qv-act-subject">{{ $act->subject }}</div>
                <div class="qv-act-meta">{{ $typeInfo['label'] }} &bull; {{ $act->due_at ? $act->due_at->format('d M Y, H:i') : 'No due date' }}</div>
                @if($act->description)<div class="qv-act-desc">{{ $act->description }}</div>@endif
              </div>
              <div class="qv-act-actions">
                <button class="qv-btn primary sm" onclick="completeActivity({{ $act->id }})">&#10003; Done</button>
                <button class="qv-btn danger sm" onclick="deleteActivity({{ $act->id }})">&#10006;</button>
              </div>
            </div>
          @endforeach
        @else
          <div class="qv-empty">No open activities.</div>
        @endif
      </div>
    </div>

    {{-- ══ 6. CLOSED ACTIVITIES ══ --}}
    <div class="qv-card" id="sec-closed-act">
      <div class="qv-card-header" onclick="toggleCard(this)">
        <h3>&#9989; Closed Activities <span style="font-size:.75rem;opacity:.8;">({{ $closedActivities->count() }})</span></h3>
        <span class="qv-chevron">&#9660;</span>
      </div>
      <div class="qv-card-body">
        @if($closedActivities->count())
          @foreach($closedActivities as $act)
            @php $typeInfo = \App\Models\CrmActivity::TYPES[$act->type] ?? ['label'=>ucfirst($act->type),'icon'=>'fa-circle','color'=>'#6366f1']; @endphp
            <div class="qv-act-item" style="opacity:.7;">
              <div class="qv-act-icon" style="background:{{ $typeInfo['color'] }};">
                <i class="fas {{ $typeInfo['icon'] }}"></i>
              </div>
              <div class="qv-act-body">
                <div class="qv-act-subject" style="text-decoration:line-through;">{{ $act->subject }}</div>
                <div class="qv-act-meta">{{ $typeInfo['label'] }} &bull; Completed {{ $act->completed_at ? $act->completed_at->format('d M Y') : '' }}</div>
              </div>
              <div class="qv-act-actions">
                <button class="qv-btn danger sm" onclick="deleteActivity({{ $act->id }})">&#10006;</button>
              </div>
            </div>
          @endforeach
        @else
          <div class="qv-empty">No closed activities.</div>
        @endif
      </div>
    </div>

    {{-- ══ 7. EMAILS ══ --}}
    <div class="qv-card" id="sec-emails">
      <div class="qv-card-header" onclick="toggleCard(this)">
        <h3>&#9993; Emails</h3>
        <span class="qv-chevron">&#9660;</span>
      </div>
      <div class="qv-card-body">
        <div style="margin-bottom:.75rem;text-align:right;">
          <button class="qv-btn primary sm" onclick="openSlider('slider-send-email')">&#9993; Send Email</button>
        </div>
        @php
          $accountEmails = $item->account_id
            ? \App\Models\CrmAccountEmail::where('account_id', $item->account_id)->latest()->take(20)->get()
            : collect();
          $sentEmails      = $accountEmails->where('status','sent');
          $draftEmails     = $accountEmails->where('status','draft');
          $scheduledEmails = $accountEmails->where('status','scheduled');
        @endphp
        <div class="qv-email-tabs">
          <div class="qv-email-tab active" onclick="switchEmailTab(this,'tab-sent')">Sent ({{ $sentEmails->count() }})</div>
          <div class="qv-email-tab" onclick="switchEmailTab(this,'tab-draft')">Drafts ({{ $draftEmails->count() }})</div>
          <div class="qv-email-tab" onclick="switchEmailTab(this,'tab-scheduled')">Scheduled ({{ $scheduledEmails->count() }})</div>
        </div>
        <div id="tab-sent" class="qv-email-pane active">
          @forelse($sentEmails as $em)
            <div class="qv-email-item">
              <div class="qv-email-subject">{{ $em->subject }}</div>
              <div class="qv-email-meta">To: {{ $em->to_email }} &bull; {{ $em->sent_at ? $em->sent_at->format('d M Y, H:i') : $em->created_at->format('d M Y') }}</div>
            </div>
          @empty
            <div class="qv-empty">No sent emails.</div>
          @endforelse
        </div>
        <div id="tab-draft" class="qv-email-pane">
          @forelse($draftEmails as $em)
            <div class="qv-email-item">
              <div class="qv-email-subject">{{ $em->subject }}</div>
              <div class="qv-email-meta">To: {{ $em->to_email }} &bull; {{ $em->created_at->format('d M Y') }}</div>
            </div>
          @empty
            <div class="qv-empty">No draft emails.</div>
          @endforelse
        </div>
        <div id="tab-scheduled" class="qv-email-pane">
          @forelse($scheduledEmails as $em)
            <div class="qv-email-item">
              <div class="qv-email-subject">{{ $em->subject }}</div>
              <div class="qv-email-meta">To: {{ $em->to_email }} &bull; Scheduled: {{ $em->scheduled_at ? $em->scheduled_at->format('d M Y, H:i') : '—' }}</div>
            </div>
          @empty
            <div class="qv-empty">No scheduled emails.</div>
          @endforelse
        </div>
      </div>
    </div>

  </div>{{-- end qv-main --}}

  {{-- ── RIGHT NAV ── --}}
  <div class="qv-sidebar">
    <div class="qv-sticky">
      <nav class="qv-nav">
        <a href="#sec-info" onclick="scrollTo('sec-info')">&#128196; Quote Info</a>
        <a href="#sec-notes" onclick="scrollTo('sec-notes')">&#128221; Notes <span class="qv-nav-count">{{ $notes->count() }}</span></a>
        <a href="#sec-so" onclick="scrollTo('sec-so')">&#128203; Sales Orders <span class="qv-nav-count">{{ $salesOrders->count() }}</span></a>
        <a href="#sec-attach" onclick="scrollTo('sec-attach')">&#128206; Attachments <span class="qv-nav-count">{{ $attachments->count() }}</span></a>
        <a href="#sec-open-act" onclick="scrollTo('sec-open-act')">&#128197; Open Activities <span class="qv-nav-count">{{ $openActivities->count() }}</span></a>
        <a href="#sec-closed-act" onclick="scrollTo('sec-closed-act')">&#9989; Closed Activities <span class="qv-nav-count">{{ $closedActivities->count() }}</span></a>
        <a href="#sec-emails" onclick="scrollTo('sec-emails')">&#9993; Emails</a>
      </nav>
    </div>
  </div>
</div>

{{-- ══ SLIDERS ══ --}}

{{-- Assign Sales Order --}}
<div class="qv-slider-overlay" id="overlay-assign-so" onclick="closeSlider('slider-assign-so')"></div>
<div class="qv-slider" id="slider-assign-so">
  <div class="qv-slider-head">
    <h3>&#128279; Assign Sales Order</h3>
    <button class="qv-slider-close" onclick="closeSlider('slider-assign-so')">&#10005;</button>
  </div>
  <div class="qv-slider-body">
    <form method="POST" action="{{ route('admin.crm2.inventory.quotes.sales-orders.assign', $item->id) }}">
      @csrf
      <div class="qv-form-group">
        <label>Select Sales Order</label>
        <select name="sales_order_id" required>
          <option value="">-- Select --</option>
          @foreach($allSalesOrders as $so)
            <option value="{{ $so->id }}">{{ $so->so_number }} — {{ $so->subject }}</option>
          @endforeach
        </select>
      </div>
      <div class="qv-form-actions">
        <button type="submit" class="qv-btn primary">Assign</button>
        <button type="button" class="qv-btn secondary" onclick="closeSlider('slider-assign-so')">Cancel</button>
      </div>
    </form>
  </div>
</div>

{{-- Add Activity --}}
<div class="qv-slider-overlay" id="overlay-add-activity" onclick="closeSlider('slider-add-activity')"></div>
<div class="qv-slider" id="slider-add-activity">
  <div class="qv-slider-head">
    <h3>&#128197; Add Activity</h3>
    <button class="qv-slider-close" onclick="closeSlider('slider-add-activity')">&#10005;</button>
  </div>
  <div class="qv-slider-body">
    <form method="POST" action="{{ route('admin.crm2.inventory.quotes.activities.store', $item->id) }}">
      @csrf
      <div class="qv-form-group">
        <label>Activity Type *</label>
        <select name="type" required>
          <option value="">-- Select Type --</option>
          @foreach(\App\Models\CrmActivity::TYPES as $key => $t)
            <option value="{{ $key }}">{{ $t['label'] }}</option>
          @endforeach
        </select>
      </div>
      <div class="qv-form-group">
        <label>Subject *</label>
        <input type="text" name="subject" required placeholder="Activity subject">
      </div>
      <div class="qv-form-group">
        <label>Description</label>
        <textarea name="description" rows="3" placeholder="Optional description..."></textarea>
      </div>
      <div class="qv-form-group">
        <label>Due Date & Time</label>
        <input type="datetime-local" name="due_at">
      </div>
      <div class="qv-form-actions">
        <button type="submit" class="qv-btn primary">Add Activity</button>
        <button type="button" class="qv-btn secondary" onclick="closeSlider('slider-add-activity')">Cancel</button>
      </div>
    </form>
  </div>
</div>

{{-- Send Email --}}
<div class="qv-slider-overlay" id="overlay-send-email" onclick="closeSlider('slider-send-email')"></div>
<div class="qv-slider" id="slider-send-email">
  <div class="qv-slider-head">
    <h3>&#9993; Send Email</h3>
    <button class="qv-slider-close" onclick="closeSlider('slider-send-email')">&#10005;</button>
  </div>
  <div class="qv-slider-body">
    @if(!$mailConfig)
      <div class="qv-alert error">No active mail configuration. Please set up SMTP in CRM Settings first.</div>
    @else
    <form method="POST" action="{{ route('admin.crm2.inventory.quotes.send-mail', $item->id) }}">
      @csrf
      <div class="qv-form-group">
        <label>To *</label>
        <input type="email" name="to_email" required value="{{ $item->contact?->email ?? $item->account?->email ?? '' }}" placeholder="recipient@email.com">
      </div>
      <div class="qv-form-group">
        <label>CC</label>
        <input type="email" name="cc_email" placeholder="cc@email.com">
      </div>
      <div class="qv-form-group">
        <label>BCC</label>
        <input type="email" name="bcc_email" placeholder="bcc@email.com">
      </div>
      <div class="qv-form-group">
        <label>Subject *</label>
        <input type="text" name="subject" required value="Quote {{ $item->quote_number }}: {{ $item->subject }}">
      </div>
      <div class="qv-form-group">
        <label>Template</label>
        <select onchange="applyEmailTemplate(this)">
          <option value="">-- No template --</option>
          @foreach($mailTemplates as $tpl)
            <option value="{{ $tpl->id }}" data-body="{{ htmlspecialchars($tpl->body_html ?? '') }}">{{ $tpl->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="qv-form-group">
        <label>Message *</label>
        <textarea name="body_html" id="email-body" rows="8" required placeholder="Email body..."></textarea>
      </div>
      <div class="qv-form-actions">
        <button type="submit" class="qv-btn primary">&#9993; Send</button>
        <button type="button" class="qv-btn secondary" onclick="closeSlider('slider-send-email')">Cancel</button>
      </div>
    </form>
    @endif
  </div>
</div>

<script>
// ── Card toggle ──
function toggleCard(header) {
    header.classList.toggle('collapsed');
    header.nextElementSibling.classList.toggle('hidden');
}

// ── Scroll to section ──
function scrollTo(id) {
    document.getElementById(id)?.scrollIntoView({behavior:'smooth', block:'start'});
    return false;
}

// ── Slider ──
function openSlider(id) {
    document.getElementById(id).classList.add('open');
    document.getElementById('overlay-' + id.replace('slider-','')).classList.add('open');
}
function closeSlider(id) {
    document.getElementById(id).classList.remove('open');
    document.getElementById('overlay-' + id.replace('slider-','')).classList.remove('open');
}

// ── Email tabs ──
function switchEmailTab(tab, paneId) {
    document.querySelectorAll('.qv-email-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.qv-email-pane').forEach(p => p.classList.remove('active'));
    tab.classList.add('active');
    document.getElementById(paneId).classList.add('active');
}

// ── Email template ──
function applyEmailTemplate(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.dataset.body) {
        document.getElementById('email-body').value = opt.dataset.body;
    }
}

// ── Drag & drop upload ──
const dz = document.getElementById('dropzone');
dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('drag-over'); });
dz.addEventListener('dragleave', () => dz.classList.remove('drag-over'));
dz.addEventListener('drop', e => {
    e.preventDefault(); dz.classList.remove('drag-over');
    const fi = document.getElementById('attach-file');
    fi.files = e.dataTransfer.files;
    document.getElementById('attach-form').submit();
});

// ── Delete attachment ──
function deleteAttachment(attId) {
    if (!confirm('Delete this attachment?')) return;
    fetch('{{ route("admin.crm2.inventory.quotes.attachments.destroy", [$item->id, "__ID__"]) }}'.replace('__ID__', attId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r => r.json()).then(d => { if(d.success) location.reload(); });
}

// ── Complete activity ──
function completeActivity(actId) {
    fetch('{{ route("admin.crm2.inventory.quotes.activities.complete", [$item->id, "__ID__"]) }}'.replace('__ID__', actId), {
        method:'PATCH', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r => r.json()).then(d => { if(d.success) location.reload(); });
}

// ── Delete activity ──
function deleteActivity(actId) {
    if (!confirm('Delete this activity?')) return;
    fetch('{{ route("admin.crm2.inventory.quotes.activities.destroy", [$item->id, "__ID__"]) }}'.replace('__ID__', actId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r => r.json()).then(d => { if(d.success) location.reload(); });
}

// ── Unassign sales order ──
function unassignSO(soId) {
    if (!confirm('Unlink this sales order from the quote?')) return;
    fetch('{{ route("admin.crm2.inventory.quotes.sales-orders.unassign", [$item->id, "__ID__"]) }}'.replace('__ID__', soId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r => r.json()).then(d => { if(d.success) location.reload(); });
}

// ── Highlight active nav on scroll ──
const sections = ['sec-info','sec-notes','sec-so','sec-attach','sec-open-act','sec-closed-act','sec-emails'];
const navLinks  = document.querySelectorAll('.qv-nav a');
window.addEventListener('scroll', () => {
    let cur = '';
    sections.forEach(id => {
        const el = document.getElementById(id);
        if (el && window.scrollY >= el.offsetTop - 120) cur = id;
    });
    navLinks.forEach(a => {
        a.classList.toggle('active', a.getAttribute('href') === '#' + cur);
    });
}, {passive:true});
</script>
@endsection
