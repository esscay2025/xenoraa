@extends('layouts.admin')
@section('content')
<style>
/* ── Inherits all theme vars from global admin layout — no :root override ── */
.vv-page    { display:flex; gap:1.5rem; padding:1.5rem; min-height:100vh; background:var(--bg-primary); }
.vv-main    { flex:1; min-width:0; }
.vv-sidebar { width:210px; flex-shrink:0; }
.vv-sticky  { position:sticky; top:1rem; }

/* Header */
.vv-header { display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap; }
.vv-header h1 { font-size:1.3rem; font-weight:700; color:var(--text-primary); margin:0; flex:1; min-width:0; }
.vv-back { display:inline-flex; align-items:center; gap:.4rem; color:var(--accent);
           text-decoration:none; font-size:.82rem; padding:.35rem .75rem;
           border:1.5px solid var(--accent); border-radius:6px; white-space:nowrap; }
.vv-back:hover { background:var(--accent); color:#fff; }
.vv-btn { display:inline-flex; align-items:center; gap:.35rem; font-size:.82rem; font-weight:600;
          padding:.38rem .85rem; border-radius:6px; border:1.5px solid transparent;
          cursor:pointer; text-decoration:none; transition:all .15s; }
.vv-btn.primary   { background:var(--accent); color:#fff; border-color:var(--accent); }
.vv-btn.primary:hover { opacity:.88; }
.vv-btn.secondary { background:transparent; color:var(--text-secondary); border-color:var(--border); }
.vv-btn.secondary:hover { background:var(--bg-hover); }
.vv-btn.danger    { background:rgba(220,38,38,.12); color:#f87171; border-color:rgba(220,38,38,.3); }
.vv-btn.danger:hover { background:#dc2626; color:#fff; border-color:#dc2626; }
.vv-btn.sm { font-size:.75rem; padding:.28rem .6rem; }

/* Status badge */
.vv-badge { display:inline-block; padding:.25rem .75rem; border-radius:20px; font-size:.75rem; font-weight:700; letter-spacing:.03em; }
.vv-badge.active   { background:rgba(34,197,94,.15);  color:#4ade80; }
.vv-badge.inactive { background:rgba(100,116,139,.15); color:#94a3b8; }

/* Section card */
.vv-card { background:var(--bg-card); border:1px solid var(--border); border-radius:10px;
           margin-bottom:1.25rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.07); }
.vv-card-header { display:flex; align-items:center; justify-content:space-between;
                  padding:.7rem 1.2rem; background:var(--accent); cursor:pointer; user-select:none; }
.vv-card-header h3 { color:#fff; font-size:.88rem; font-weight:600; margin:0; }
.vv-card-header .vv-chevron { color:#fff; font-size:.75rem; transition:transform .2s; }
.vv-card-header.collapsed .vv-chevron { transform:rotate(-90deg); }
.vv-card-body { padding:1.2rem; }
.vv-card-body.hidden { display:none; }

/* Info grid */
.vv-info-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:.9rem 1.5rem; }
.vv-info-item label { display:block; font-size:.7rem; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:.2rem; }
.vv-info-item span  { font-size:.88rem; color:var(--text-primary); font-weight:500; }
.vv-divider { border:none; border-top:1px solid var(--border); margin:1rem 0; }

/* Address */
.vv-addr-panel h4 { font-size:.8rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.05em; margin:0 0 .75rem; }
.vv-addr-row { display:flex; gap:.4rem; margin-bottom:.4rem; }
.vv-addr-row label { font-size:.7rem; color:var(--text-muted); min-width:70px; }
.vv-addr-row span  { font-size:.82rem; color:var(--text-primary); }

/* Table */
.vv-table { width:100%; border-collapse:collapse; font-size:.82rem; }
.vv-table th { background:var(--bg-hover); color:var(--text-secondary); font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; padding:.55rem .75rem; border-bottom:1px solid var(--border); text-align:left; }
.vv-table td { padding:.55rem .75rem; border-bottom:1px solid var(--border); color:var(--text-primary); vertical-align:middle; }
.vv-table tr:last-child td { border-bottom:none; }
.vv-table tr:hover td { background:var(--bg-hover); }
.vv-table-actions { display:flex; gap:.35rem; }

/* Notes */
.vv-note-form textarea { width:100%; padding:.65rem .85rem; border:1.5px solid var(--border); border-radius:7px; background:var(--bg-primary); color:var(--text-primary); font-size:.85rem; resize:vertical; min-height:80px; box-sizing:border-box; }
.vv-note-form textarea:focus { outline:none; border-color:var(--accent); }
.vv-note-list { margin-top:1rem; display:flex; flex-direction:column; gap:.75rem; max-height:320px; overflow-y:auto; }
.vv-note-item { background:var(--bg-primary); border:1px solid var(--border); border-radius:8px; padding:.75rem 1rem; }
.vv-note-meta    { font-size:.7rem; color:var(--text-muted); margin-bottom:.3rem; }
.vv-note-content { font-size:.85rem; color:var(--text-primary); line-height:1.5; white-space:pre-wrap; }

/* Activities */
.vv-act-item { display:flex; align-items:flex-start; gap:.85rem; padding:.75rem 0; border-bottom:1px solid var(--border); }
.vv-act-item:last-child { border-bottom:none; }
.vv-act-icon { width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.8rem; color:#fff; }
.vv-act-body { flex:1; min-width:0; }
.vv-act-subject { font-size:.87rem; font-weight:600; color:var(--text-primary); }
.vv-act-meta    { font-size:.72rem; color:var(--text-muted); margin-top:.15rem; }
.vv-act-desc    { font-size:.8rem; color:var(--text-secondary); margin-top:.3rem; }
.vv-act-actions { display:flex; gap:.4rem; flex-shrink:0; }
.vv-empty { text-align:center; padding:2rem; color:var(--text-muted); font-size:.85rem; }

/* Attachments */
.vv-dropzone { border:2px dashed var(--border); border-radius:8px; padding:1.5rem; text-align:center; cursor:pointer; transition:border-color .2s; }
.vv-dropzone:hover, .vv-dropzone.drag-over { border-color:var(--accent); background:rgba(99,102,241,.04); }
.vv-dropzone p { margin:.4rem 0 0; font-size:.82rem; color:var(--text-muted); }
.vv-attach-list { margin-top:1rem; display:flex; flex-direction:column; gap:.5rem; }
.vv-attach-item { display:flex; align-items:center; gap:.75rem; padding:.6rem .9rem; background:var(--bg-primary); border:1px solid var(--border); border-radius:7px; }
.vv-attach-icon { font-size:1.2rem; flex-shrink:0; }
.vv-attach-info { flex:1; min-width:0; }
.vv-attach-name { font-size:.84rem; font-weight:600; color:var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.vv-attach-meta { font-size:.7rem; color:var(--text-muted); }
.vv-attach-del  { background:none; border:none; cursor:pointer; color:var(--text-muted); font-size:1rem; padding:.2rem .4rem; border-radius:4px; }
.vv-attach-del:hover { color:#f87171; background:rgba(220,38,38,.12); }

/* Emails */
.vv-email-tabs { display:flex; gap:.5rem; margin-bottom:1rem; border-bottom:1px solid var(--border); }
.vv-email-tab  { padding:.45rem 1rem; font-size:.82rem; font-weight:600; color:var(--text-muted); cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-1px; }
.vv-email-tab.active { color:var(--accent); border-bottom-color:var(--accent); }
.vv-email-pane { display:none; }
.vv-email-pane.active { display:block; }

/* Right nav */
.vv-nav { background:var(--bg-card); border:1px solid var(--border); border-radius:10px; overflow:hidden; }
.vv-nav a { display:flex; align-items:center; gap:.6rem; padding:.65rem 1rem; font-size:.82rem; color:var(--text-secondary); text-decoration:none; border-bottom:1px solid var(--border); transition:all .15s; }
.vv-nav a:last-child { border-bottom:none; }
.vv-nav a:hover, .vv-nav a.active { background:var(--accent); color:#fff; }
.vv-nav a .vv-nav-count { margin-left:auto; background:rgba(99,102,241,.15); color:var(--accent); font-size:.7rem; font-weight:700; padding:.1rem .4rem; border-radius:10px; }
.vv-nav a:hover .vv-nav-count, .vv-nav a.active .vv-nav-count { background:rgba(255,255,255,.25); color:#fff; }

/* Slider */
.vv-slider-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:1000; }
.vv-slider-overlay.open { display:block; }
.vv-slider { position:fixed; top:0; right:-500px; width:460px; max-width:95vw; height:100vh; background:var(--bg-card); border-left:1px solid var(--border); z-index:1001; transition:right .3s ease; overflow-y:auto; display:flex; flex-direction:column; }
.vv-slider.open { right:0; }
.vv-slider-head { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.2rem; border-bottom:1px solid var(--border); background:var(--accent); }
.vv-slider-head h3 { color:#fff; font-size:.95rem; font-weight:700; margin:0; }
.vv-slider-close { background:none; border:none; color:#fff; font-size:1.3rem; cursor:pointer; padding:.2rem .5rem; border-radius:4px; }
.vv-slider-close:hover { background:rgba(255,255,255,.2); }
.vv-slider-body { padding:1.2rem; flex:1; }
.vv-form-group { margin-bottom:1rem; }
.vv-form-group label { display:block; font-size:.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:.35rem; }
.vv-form-group input, .vv-form-group select, .vv-form-group textarea {
    width:100%; padding:.55rem .8rem; border:1.5px solid var(--border); border-radius:7px;
    background:var(--bg-primary); color:var(--text-primary); font-size:.85rem; box-sizing:border-box; }
.vv-form-group input:focus, .vv-form-group select:focus, .vv-form-group textarea:focus { outline:none; border-color:var(--accent); }
.vv-form-actions { display:flex; gap:.75rem; margin-top:1.25rem; }

/* Alert */
.vv-alert { padding:.75rem 1rem; border-radius:7px; margin-bottom:1rem; font-size:.85rem; }
.vv-alert.success { background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.3); }
.vv-alert.error   { background:rgba(239,68,68,.12);  color:#f87171; border:1px solid rgba(239,68,68,.3); }
</style>

<div class="vv-page">
  {{-- ── MAIN CONTENT ── --}}
  <div class="vv-main">

    @if(session('success'))
      <div class="vv-alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="vv-alert error">{{ session('error') }}</div>
    @endif

    {{-- Header --}}
    <div class="vv-header">
      <a href="{{ route('admin.crm2.inventory.vendors') }}" class="vv-back">&#8592; Vendors</a>
      <h1>{{ $item->name }}</h1>
      <span class="vv-badge {{ $item->is_active ? 'active' : 'inactive' }}">{{ $item->is_active ? 'Active' : 'Inactive' }}</span>
      <a href="{{ route('admin.crm2.inventory.vendors.edit', $item->id) }}" class="vv-btn primary">&#9998; Edit</a>
    </div>

    {{-- ══ 1. VENDOR INFORMATION ══ --}}
    <div class="vv-card" id="sec-info">
      <div class="vv-card-header" onclick="vvToggle(this)">
        <h3>&#127981; Vendor Information</h3>
        <span class="vv-chevron">&#9660;</span>
      </div>
      <div class="vv-card-body">
        <div class="vv-info-grid">
          <div class="vv-info-item"><label>Vendor Name</label><span>{{ $item->name }}</span></div>
          <div class="vv-info-item"><label>Category</label><span>{{ $item->category ?: '—' }}</span></div>
          <div class="vv-info-item"><label>Email</label><span>{{ $item->email ?: '—' }}</span></div>
          <div class="vv-info-item"><label>Phone</label><span>{{ $item->phone ?: '—' }}</span></div>
          <div class="vv-info-item"><label>Fax</label><span>{{ $item->fax ?: '—' }}</span></div>
          <div class="vv-info-item"><label>Website</label>
            <span>@if($item->website)<a href="{{ $item->website }}" target="_blank" style="color:var(--accent);">{{ $item->website }}</a>@else —@endif</span>
          </div>
          <div class="vv-info-item"><label>GL Account</label><span>{{ $item->gl_account ?: '—' }}</span></div>
          <div class="vv-info-item"><label>Payment Terms</label><span>{{ $item->payment_terms ?: '—' }}</span></div>
          <div class="vv-info-item"><label>Currency</label><span>{{ $item->currency ?: '—' }}</span></div>
          <div class="vv-info-item"><label>Status</label>
            <span><span class="vv-badge {{ $item->is_active ? 'active' : 'inactive' }}">{{ $item->is_active ? 'Active' : 'Inactive' }}</span></span>
          </div>
          <div class="vv-info-item"><label>Owner</label><span>{{ $item->owner?->name ?? '—' }}</span></div>
          <div class="vv-info-item"><label>Created</label><span>{{ $item->created_at->format('d M Y, H:i') }}</span></div>
          <div class="vv-info-item"><label>Last Updated</label><span>{{ $item->updated_at->format('d M Y, H:i') }}</span></div>
        </div>

        <hr class="vv-divider">

        {{-- Address --}}
        <h4 style="font-size:.8rem;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.05em;margin:0 0 1rem;">Address</h4>
        <div class="vv-addr-panel">
          @if($item->bill_building || $item->bill_street || $item->bill_city)
            @if($item->bill_building)<div class="vv-addr-row"><label>Building</label><span>{{ $item->bill_building }}</span></div>@endif
            @if($item->bill_street)<div class="vv-addr-row"><label>Street</label><span>{{ $item->bill_street }}</span></div>@endif
            @if($item->bill_country)<div class="vv-addr-row"><label>Country</label><span>{{ $item->bill_country }}</span></div>@endif
            @if($item->bill_state)<div class="vv-addr-row"><label>State</label><span>{{ $item->bill_state }}</span></div>@endif
            @if($item->bill_city)<div class="vv-addr-row"><label>City</label><span>{{ $item->bill_city }}</span></div>@endif
            @if($item->bill_zip)<div class="vv-addr-row"><label>Zip</label><span>{{ $item->bill_zip }}</span></div>@endif
          @else
            <span style="font-size:.82rem;color:var(--text-muted);font-style:italic;">No address on record.</span>
          @endif
        </div>

        <hr class="vv-divider">

        <div>
          <label style="font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:.4rem;">Description</label>
          <div style="font-size:.85rem;color:var(--text-primary);background:var(--bg-primary);border:1px solid var(--border);border-radius:7px;padding:.75rem;min-height:60px;white-space:pre-wrap;">{{ $item->description ?: '—' }}</div>
        </div>
      </div>
    </div>

    {{-- ══ 2. NOTES ══ --}}
    <div class="vv-card" id="sec-notes">
      <div class="vv-card-header" onclick="vvToggle(this)">
        <h3>&#128221; Notes <span style="font-size:.75rem;opacity:.8;">({{ $notes->count() }})</span></h3>
        <span class="vv-chevron">&#9660;</span>
      </div>
      <div class="vv-card-body">
        <form method="POST" action="{{ route('admin.crm2.inventory.vendors.notes.store', $item->id) }}" class="vv-note-form">
          @csrf
          <textarea name="content" placeholder="Add a note..." required></textarea>
          <div style="margin-top:.5rem;text-align:right;">
            <button type="submit" class="vv-btn primary">Add Note</button>
          </div>
        </form>
        @if($notes->count())
          <div class="vv-note-list">
            @foreach($notes as $note)
              <div class="vv-note-item">
                <div class="vv-note-meta">{{ $note->user?->name ?? 'System' }} &bull; {{ $note->created_at->diffForHumans() }}</div>
                <div class="vv-note-content">{{ $note->content }}</div>
              </div>
            @endforeach
          </div>
        @else
          <div class="vv-empty">No notes yet. Add the first one above.</div>
        @endif
      </div>
    </div>

    {{-- ══ 3. ATTACHMENTS ══ --}}
    <div class="vv-card" id="sec-attach">
      <div class="vv-card-header" onclick="vvToggle(this)">
        <h3>&#128206; Attachments <span style="font-size:.75rem;opacity:.8;">({{ $attachments->count() }})</span></h3>
        <span class="vv-chevron">&#9660;</span>
      </div>
      <div class="vv-card-body">
        <form method="POST" action="{{ route('admin.crm2.inventory.vendors.attachments.store', $item->id) }}" enctype="multipart/form-data" id="vv-attach-form">
          @csrf
          <div class="vv-dropzone" id="vv-dropzone" onclick="document.getElementById('vv-attach-file').click()">
            <div style="font-size:2rem;">&#128206;</div>
            <p>Click or drag & drop to upload (PDF, DOC, XLS, PNG, JPG, ZIP — max 10 MB)</p>
          </div>
          <input type="file" id="vv-attach-file" name="attachment" style="display:none"
                 accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip"
                 onchange="this.form.submit()">
        </form>
        @if($attachments->count())
          <div class="vv-attach-list">
            @foreach($attachments as $att)
              <div class="vv-attach-item">
                <span class="vv-attach-icon">&#128196;</span>
                <div class="vv-attach-info">
                  <div class="vv-attach-name">{{ $att->original_name }}</div>
                  <div class="vv-attach-meta">{{ $att->human_size }} &bull; {{ $att->created_at->format('d M Y') }}</div>
                </div>
                <a href="{{ route('admin.crm2.inventory.vendors.attachments.download', [$item->id, $att->id]) }}" class="vv-btn secondary sm">&#8595; Download</a>
                <button class="vv-attach-del" onclick="vvDeleteAttachment({{ $att->id }})" title="Delete">&#128465;</button>
              </div>
            @endforeach
          </div>
        @else
          <div class="vv-empty" style="margin-top:.75rem;">No attachments yet.</div>
        @endif
      </div>
    </div>

    {{-- ══ 4. PRODUCTS ══ --}}
    <div class="vv-card" id="sec-products">
      <div class="vv-card-header" onclick="vvToggle(this)">
        <h3>&#128230; Products <span style="font-size:.75rem;opacity:.8;">({{ $products->count() }})</span></h3>
        <span class="vv-chevron">&#9660;</span>
      </div>
      <div class="vv-card-body">
        <div style="display:flex;gap:.5rem;margin-bottom:.75rem;flex-wrap:wrap;">
          <button class="vv-btn primary sm" onclick="vvOpenSlider('slider-assign-product')">&#43; Assign Product</button>
          <a href="{{ route('admin.crm2.inventory.products.create') }}?vendor_id={{ $item->id }}" class="vv-btn secondary sm">&#43; New Product</a>
        </div>
        @if($products->count())
          <table class="vv-table">
            <thead><tr><th>Product Name</th><th>Code</th><th>Category</th><th>Unit Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
              @foreach($products as $prod)
              <tr>
                <td><strong>{{ $prod->name }}</strong></td>
                <td>{{ $prod->product_code ?: '—' }}</td>
                <td>{{ $prod->product_category ?: '—' }}</td>
                <td>{{ $prod->unit_price ? '₹'.number_format($prod->unit_price,2) : '—' }}</td>
                <td>{{ $prod->qty_in_stock ?? '—' }}</td>
                <td><span class="vv-badge {{ $prod->is_active ? 'active' : 'inactive' }}">{{ $prod->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td>
                  <div class="vv-table-actions">
                    <a href="{{ route('admin.crm2.inventory.products.edit', $prod->id) }}" class="vv-btn secondary sm">&#9998; Edit</a>
                    <button class="vv-btn danger sm" onclick="vvUnassignProduct({{ $prod->id }})">&#10006; Unlink</button>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div class="vv-empty">No products linked to this vendor.</div>
        @endif
      </div>
    </div>

    {{-- ══ 5. PURCHASE ORDERS ══ --}}
    <div class="vv-card" id="sec-pos">
      <div class="vv-card-header" onclick="vvToggle(this)">
        <h3>&#128230; Purchase Orders <span style="font-size:.75rem;opacity:.8;">({{ $purchaseOrders->count() }})</span></h3>
        <span class="vv-chevron">&#9660;</span>
      </div>
      <div class="vv-card-body">
        <div style="display:flex;gap:.5rem;margin-bottom:.75rem;flex-wrap:wrap;">
          <button class="vv-btn primary sm" onclick="vvOpenSlider('slider-assign-po')">&#43; Assign PO</button>
          <a href="{{ route('admin.crm2.inventory.purchase-orders.create') }}?vendor_id={{ $item->id }}" class="vv-btn secondary sm">&#43; New Purchase Order</a>
        </div>
        @if($purchaseOrders->count())
          <table class="vv-table">
            <thead><tr><th>PO Number</th><th>Subject</th><th>Status</th><th>Grand Total</th><th>Expected Delivery</th><th>Actions</th></tr></thead>
            <tbody>
              @foreach($purchaseOrders as $po)
              @php
                $poStatusClass = match($po->status ?? 'draft') {
                  'approved'  => 'active',
                  'delivered' => 'active',
                  'cancelled' => 'inactive',
                  default     => 'inactive',
                };
              @endphp
              <tr>
                <td><a href="{{ route('admin.crm2.inventory.purchase-orders.show', $po->id) }}" style="color:var(--accent);font-weight:600;">{{ $po->po_number ?? 'PO-'.$po->id }}</a></td>
                <td>{{ $po->subject }}</td>
                <td><span class="vv-badge {{ $poStatusClass }}">{{ ucfirst($po->status ?? 'Draft') }}</span></td>
                <td>{{ ($po->grand_total ?? $po->total) ? '₹'.number_format($po->grand_total ?? $po->total,0) : '—' }}</td>
                <td>{{ $po->expected_delivery ? \Carbon\Carbon::parse($po->expected_delivery)->format('d M Y') : '—' }}</td>
                <td>
                  <div class="vv-table-actions">
                    <a href="{{ route('admin.crm2.inventory.purchase-orders.edit', $po->id) }}" class="vv-btn secondary sm">&#9998; Edit</a>
                    <button class="vv-btn danger sm" onclick="vvUnassignPO({{ $po->id }})">&#10006; Unlink</button>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div class="vv-empty">No purchase orders linked to this vendor.</div>
        @endif
      </div>
    </div>

    {{-- ══ 6. CONTACTS ══ --}}
    <div class="vv-card" id="sec-contacts">
      <div class="vv-card-header" onclick="vvToggle(this)">
        <h3>&#128101; Contacts <span style="font-size:.75rem;opacity:.8;">({{ $contacts->count() }})</span></h3>
        <span class="vv-chevron">&#9660;</span>
      </div>
      <div class="vv-card-body">
        <div style="display:flex;gap:.5rem;margin-bottom:.75rem;flex-wrap:wrap;">
          <button class="vv-btn primary sm" onclick="vvOpenSlider('slider-assign-contact')">&#43; Assign Contact</button>
          <a href="{{ route('admin.newcrm.contacts.create') }}?vendor_id={{ $item->id }}" class="vv-btn secondary sm">&#43; New Contact</a>
        </div>
        @if($contacts->count())
          <table class="vv-table">
            <thead><tr><th>Name</th><th>Job Title</th><th>Email</th><th>Phone</th><th>Actions</th></tr></thead>
            <tbody>
              @foreach($contacts as $contact)
              <tr>
                <td><strong>{{ $contact->full_name }}</strong></td>
                <td>{{ $contact->job_title ?: '—' }}</td>
                <td>{{ $contact->email ?: '—' }}</td>
                <td>{{ $contact->phone ?: '—' }}</td>
                <td>
                  <div class="vv-table-actions">
                    <a href="{{ route('admin.crm2.contacts.edit', $contact->id) }}" class="vv-btn secondary sm">&#9998; Edit</a>
                    <button class="vv-btn danger sm" onclick="vvUnassignContact({{ $contact->id }})">&#10006; Unlink</button>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div class="vv-empty">No contacts linked to this vendor.</div>
        @endif
      </div>
    </div>

    {{-- ══ 7. OPEN ACTIVITIES ══ --}}
    <div class="vv-card" id="sec-open-act">
      <div class="vv-card-header" onclick="vvToggle(this)">
        <h3>&#128197; Open Activities <span style="font-size:.75rem;opacity:.8;">({{ $openActivities->count() }})</span></h3>
        <span class="vv-chevron">&#9660;</span>
      </div>
      <div class="vv-card-body">
        <div style="margin-bottom:.75rem;text-align:right;">
          <button class="vv-btn primary sm" onclick="vvOpenSlider('slider-add-activity')">&#43; Add Activity</button>
        </div>
        @if($openActivities->count())
          @foreach($openActivities as $act)
            @php $ti = \App\Models\CrmActivity::TYPES[$act->type] ?? ['label'=>ucfirst($act->type),'icon'=>'fa-circle','color'=>'#6366f1']; @endphp
            <div class="vv-act-item">
              <div class="vv-act-icon" style="background:{{ $ti['color'] }};"><i class="fas {{ $ti['icon'] }}"></i></div>
              <div class="vv-act-body">
                <div class="vv-act-subject">{{ $act->subject }}</div>
                <div class="vv-act-meta">{{ $ti['label'] }} &bull; {{ $act->due_at ? $act->due_at->format('d M Y, H:i') : 'No due date' }}</div>
                @if($act->description)<div class="vv-act-desc">{{ $act->description }}</div>@endif
              </div>
              <div class="vv-act-actions">
                <button class="vv-btn primary sm" onclick="vvCompleteActivity({{ $act->id }})">&#10003; Done</button>
                <button class="vv-btn danger sm"  onclick="vvDeleteActivity({{ $act->id }})">&#10006;</button>
              </div>
            </div>
          @endforeach
        @else
          <div class="vv-empty">No open activities.</div>
        @endif
      </div>
    </div>

    {{-- ══ 8. CLOSED ACTIVITIES ══ --}}
    <div class="vv-card" id="sec-closed-act">
      <div class="vv-card-header" onclick="vvToggle(this)">
        <h3>&#9989; Closed Activities <span style="font-size:.75rem;opacity:.8;">({{ $closedActivities->count() }})</span></h3>
        <span class="vv-chevron">&#9660;</span>
      </div>
      <div class="vv-card-body">
        @if($closedActivities->count())
          @foreach($closedActivities as $act)
            @php $ti = \App\Models\CrmActivity::TYPES[$act->type] ?? ['label'=>ucfirst($act->type),'icon'=>'fa-circle','color'=>'#6366f1']; @endphp
            <div class="vv-act-item" style="opacity:.7;">
              <div class="vv-act-icon" style="background:{{ $ti['color'] }};"><i class="fas {{ $ti['icon'] }}"></i></div>
              <div class="vv-act-body">
                <div class="vv-act-subject" style="text-decoration:line-through;">{{ $act->subject }}</div>
                <div class="vv-act-meta">{{ $ti['label'] }} &bull; Completed {{ $act->completed_at ? $act->completed_at->format('d M Y') : '' }}</div>
              </div>
              <div class="vv-act-actions">
                <button class="vv-btn danger sm" onclick="vvDeleteActivity({{ $act->id }})">&#10006;</button>
              </div>
            </div>
          @endforeach
        @else
          <div class="vv-empty">No closed activities.</div>
        @endif
      </div>
    </div>

    {{-- ══ 9. EMAILS ══ --}}
    <div class="vv-card" id="sec-emails">
      <div class="vv-card-header" onclick="vvToggle(this)">
        <h3>&#9993; Emails</h3>
        <span class="vv-chevron">&#9660;</span>
      </div>
      <div class="vv-card-body">
        <div style="margin-bottom:.75rem;text-align:right;">
          <button class="vv-btn primary sm" onclick="vvOpenSlider('slider-send-email')">&#9993; Send Email</button>
        </div>
        <div class="vv-email-tabs">
          <div class="vv-email-tab active" onclick="vvEmailTab(this,'vv-tab-sent')">Sent (0)</div>
          <div class="vv-email-tab" onclick="vvEmailTab(this,'vv-tab-draft')">Drafts (0)</div>
          <div class="vv-email-tab" onclick="vvEmailTab(this,'vv-tab-scheduled')">Scheduled (0)</div>
        </div>
        <div id="vv-tab-sent" class="vv-email-pane active"><div class="vv-empty">No sent emails for this vendor.</div></div>
        <div id="vv-tab-draft" class="vv-email-pane"><div class="vv-empty">No draft emails.</div></div>
        <div id="vv-tab-scheduled" class="vv-email-pane"><div class="vv-empty">No scheduled emails.</div></div>
      </div>
    </div>

  </div>{{-- end vv-main --}}

  {{-- ── RIGHT NAV ── --}}
  <div class="vv-sidebar">
    <div class="vv-sticky">
      <nav class="vv-nav">
        <a href="#sec-info"       onclick="return vvScroll('sec-info')">&#127981; Vendor Info</a>
        <a href="#sec-notes"      onclick="return vvScroll('sec-notes')">&#128221; Notes <span class="vv-nav-count">{{ $notes->count() }}</span></a>
        <a href="#sec-attach"     onclick="return vvScroll('sec-attach')">&#128206; Attachments <span class="vv-nav-count">{{ $attachments->count() }}</span></a>
        <a href="#sec-products"   onclick="return vvScroll('sec-products')">&#128230; Products <span class="vv-nav-count">{{ $products->count() }}</span></a>
        <a href="#sec-pos"        onclick="return vvScroll('sec-pos')">&#128230; Purchase Orders <span class="vv-nav-count">{{ $purchaseOrders->count() }}</span></a>
        <a href="#sec-contacts"   onclick="return vvScroll('sec-contacts')">&#128101; Contacts <span class="vv-nav-count">{{ $contacts->count() }}</span></a>
        <a href="#sec-open-act"   onclick="return vvScroll('sec-open-act')">&#128197; Open Activities <span class="vv-nav-count">{{ $openActivities->count() }}</span></a>
        <a href="#sec-closed-act" onclick="return vvScroll('sec-closed-act')">&#9989; Closed Activities <span class="vv-nav-count">{{ $closedActivities->count() }}</span></a>
        <a href="#sec-emails"     onclick="return vvScroll('sec-emails')">&#9993; Emails</a>
      </nav>
    </div>
  </div>
</div>

{{-- ══ SLIDERS ══ --}}

{{-- Assign Product --}}
<div class="vv-slider-overlay" id="overlay-assign-product" onclick="vvCloseSlider('slider-assign-product')"></div>
<div class="vv-slider" id="slider-assign-product">
  <div class="vv-slider-head"><h3>&#128230; Assign Product</h3><button class="vv-slider-close" onclick="vvCloseSlider('slider-assign-product')">&#10005;</button></div>
  <div class="vv-slider-body">
    <form method="POST" action="{{ route('admin.crm2.inventory.vendors.products.assign', $item->id) }}">
      @csrf
      <div class="vv-form-group">
        <label>Select Product *</label>
        <select name="product_id" required>
          <option value="">-- Select Product --</option>
          @foreach($allProducts as $prod)
            @if($prod->vendor_id != $item->id)
              <option value="{{ $prod->id }}">{{ $prod->name }} {{ $prod->product_code ? '('.$prod->product_code.')' : '' }}</option>
            @endif
          @endforeach
        </select>
      </div>
      <div class="vv-form-actions">
        <button type="submit" class="vv-btn primary">Assign</button>
        <button type="button" class="vv-btn secondary" onclick="vvCloseSlider('slider-assign-product')">Cancel</button>
      </div>
    </form>
  </div>
</div>

{{-- Assign Purchase Order --}}
<div class="vv-slider-overlay" id="overlay-assign-po" onclick="vvCloseSlider('slider-assign-po')"></div>
<div class="vv-slider" id="slider-assign-po">
  <div class="vv-slider-head"><h3>&#128230; Assign Purchase Order</h3><button class="vv-slider-close" onclick="vvCloseSlider('slider-assign-po')">&#10005;</button></div>
  <div class="vv-slider-body">
    <form method="POST" action="{{ route('admin.crm2.inventory.vendors.pos.assign', $item->id) }}">
      @csrf
      <div class="vv-form-group">
        <label>Select Purchase Order *</label>
        <select name="po_id" required>
          <option value="">-- Select Purchase Order --</option>
          @foreach($allPurchaseOrders as $po)
            @if($po->vendor_id != $item->id)
              <option value="{{ $po->id }}">{{ $po->po_number ?? 'PO-'.$po->id }} — {{ $po->subject }}</option>
            @endif
          @endforeach
        </select>
      </div>
      <div class="vv-form-actions">
        <button type="submit" class="vv-btn primary">Assign</button>
        <button type="button" class="vv-btn secondary" onclick="vvCloseSlider('slider-assign-po')">Cancel</button>
      </div>
    </form>
  </div>
</div>

{{-- Assign Contact --}}
<div class="vv-slider-overlay" id="overlay-assign-contact" onclick="vvCloseSlider('slider-assign-contact')"></div>
<div class="vv-slider" id="slider-assign-contact">
  <div class="vv-slider-head"><h3>&#128101; Assign Contact</h3><button class="vv-slider-close" onclick="vvCloseSlider('slider-assign-contact')">&#10005;</button></div>
  <div class="vv-slider-body">
    <form method="POST" action="{{ route('admin.crm2.inventory.vendors.contacts.assign', $item->id) }}">
      @csrf
      <div class="vv-form-group">
        <label>Select Contact *</label>
        <select name="contact_id" required>
          <option value="">-- Select Contact --</option>
          @foreach($allContacts as $contact)
            @if($contact->vendor_id != $item->id)
              <option value="{{ $contact->id }}">{{ $contact->full_name }} {{ $contact->email ? '('.$contact->email.')' : '' }}</option>
            @endif
          @endforeach
        </select>
      </div>
      <div class="vv-form-actions">
        <button type="submit" class="vv-btn primary">Assign</button>
        <button type="button" class="vv-btn secondary" onclick="vvCloseSlider('slider-assign-contact')">Cancel</button>
      </div>
    </form>
  </div>
</div>

{{-- Add Activity --}}
<div class="vv-slider-overlay" id="overlay-add-activity" onclick="vvCloseSlider('slider-add-activity')"></div>
<div class="vv-slider" id="slider-add-activity">
  <div class="vv-slider-head"><h3>&#128197; Add Activity</h3><button class="vv-slider-close" onclick="vvCloseSlider('slider-add-activity')">&#10005;</button></div>
  <div class="vv-slider-body">
    <form method="POST" action="{{ route('admin.crm2.inventory.vendors.activities.store', $item->id) }}">
      @csrf
      <div class="vv-form-group">
        <label>Activity Type *</label>
        <select name="type" required>
          <option value="">-- Select Type --</option>
          @foreach(\App\Models\CrmActivity::TYPES as $key => $t)
            <option value="{{ $key }}">{{ $t['label'] }}</option>
          @endforeach
        </select>
      </div>
      <div class="vv-form-group">
        <label>Subject *</label>
        <input type="text" name="subject" required placeholder="Activity subject">
      </div>
      <div class="vv-form-group">
        <label>Description</label>
        <textarea name="description" rows="3" placeholder="Optional description..."></textarea>
      </div>
      <div class="vv-form-group">
        <label>Due Date & Time</label>
        <input type="datetime-local" name="due_at">
      </div>
      <div class="vv-form-actions">
        <button type="submit" class="vv-btn primary">Add Activity</button>
        <button type="button" class="vv-btn secondary" onclick="vvCloseSlider('slider-add-activity')">Cancel</button>
      </div>
    </form>
  </div>
</div>

{{-- Send Email --}}
<div class="vv-slider-overlay" id="overlay-send-email" onclick="vvCloseSlider('slider-send-email')"></div>
<div class="vv-slider" id="slider-send-email">
  <div class="vv-slider-head"><h3>&#9993; Send Email</h3><button class="vv-slider-close" onclick="vvCloseSlider('slider-send-email')">&#10005;</button></div>
  <div class="vv-slider-body">
    @if(!$mailConfig)
      <div class="vv-alert error">No active mail configuration. Please set up SMTP in CRM Settings first.</div>
    @else
    <form method="POST" action="{{ route('admin.crm2.inventory.vendors.send-mail', $item->id) }}">
      @csrf
      <div class="vv-form-group">
        <label>To *</label>
        <input type="email" name="to_email" required value="{{ $item->email ?? '' }}" placeholder="recipient@email.com">
      </div>
      <div class="vv-form-group"><label>CC</label><input type="email" name="cc_email" placeholder="cc@email.com"></div>
      <div class="vv-form-group"><label>BCC</label><input type="email" name="bcc_email" placeholder="bcc@email.com"></div>
      <div class="vv-form-group">
        <label>Subject *</label>
        <input type="text" name="subject" required value="Re: {{ $item->name }}">
      </div>
      <div class="vv-form-group">
        <label>Template</label>
        <select onchange="vvApplyTemplate(this)">
          <option value="">-- No template --</option>
          @foreach($mailTemplates as $tpl)
            <option value="{{ $tpl->id }}" data-body="{{ htmlspecialchars($tpl->body_html ?? '') }}">{{ $tpl->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="vv-form-group">
        <label>Message *</label>
        <textarea name="body_html" id="vv-email-body" rows="8" required placeholder="Email body..."></textarea>
      </div>
      <div class="vv-form-actions">
        <button type="submit" class="vv-btn primary">&#9993; Send</button>
        <button type="button" class="vv-btn secondary" onclick="vvCloseSlider('slider-send-email')">Cancel</button>
      </div>
    </form>
    @endif
  </div>
</div>

<script>
function vvToggle(header) {
    header.classList.toggle('collapsed');
    header.nextElementSibling.classList.toggle('hidden');
}
function vvScroll(id) {
    document.getElementById(id)?.scrollIntoView({behavior:'smooth',block:'start'});
    return false;
}
function vvOpenSlider(id) {
    document.getElementById(id).classList.add('open');
    const key = id.replace('slider-','');
    const ov = document.getElementById('overlay-' + key);
    if (ov) ov.classList.add('open');
}
function vvCloseSlider(id) {
    document.getElementById(id).classList.remove('open');
    const key = id.replace('slider-','');
    const ov = document.getElementById('overlay-' + key);
    if (ov) ov.classList.remove('open');
}
function vvEmailTab(tab, paneId) {
    document.querySelectorAll('.vv-email-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.vv-email-pane').forEach(p => p.classList.remove('active'));
    tab.classList.add('active');
    document.getElementById(paneId).classList.add('active');
}
function vvApplyTemplate(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.dataset.body) document.getElementById('vv-email-body').value = opt.dataset.body;
}
// Drag & drop
const vvDz = document.getElementById('vv-dropzone');
vvDz.addEventListener('dragover', e => { e.preventDefault(); vvDz.classList.add('drag-over'); });
vvDz.addEventListener('dragleave', () => vvDz.classList.remove('drag-over'));
vvDz.addEventListener('drop', e => {
    e.preventDefault(); vvDz.classList.remove('drag-over');
    document.getElementById('vv-attach-file').files = e.dataTransfer.files;
    document.getElementById('vv-attach-form').submit();
});
function vvDeleteAttachment(attId) {
    if (!confirm('Delete this attachment?')) return;
    fetch('{{ route("admin.crm2.inventory.vendors.attachments.destroy", [$item->id, "__ID__"]) }}'.replace('__ID__', attId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
function vvCompleteActivity(actId) {
    fetch('{{ route("admin.crm2.inventory.vendors.activities.complete", [$item->id, "__ID__"]) }}'.replace('__ID__', actId), {
        method:'PATCH', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
function vvDeleteActivity(actId) {
    if (!confirm('Delete this activity?')) return;
    fetch('{{ route("admin.crm2.inventory.vendors.activities.destroy", [$item->id, "__ID__"]) }}'.replace('__ID__', actId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
function vvUnassignProduct(productId) {
    if (!confirm('Unlink this product from the vendor?')) return;
    fetch('{{ route("admin.crm2.inventory.vendors.products.unassign", [$item->id, "__ID__"]) }}'.replace('__ID__', productId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
function vvUnassignPO(poId) {
    if (!confirm('Unlink this purchase order from the vendor?')) return;
    fetch('{{ route("admin.crm2.inventory.vendors.pos.unassign", [$item->id, "__ID__"]) }}'.replace('__ID__', poId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
function vvUnassignContact(contactId) {
    if (!confirm('Unlink this contact from the vendor?')) return;
    fetch('{{ route("admin.crm2.inventory.vendors.contacts.unassign", [$item->id, "__ID__"]) }}'.replace('__ID__', contactId), {
        method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
// Highlight nav on scroll
const vvSections = ['sec-info','sec-notes','sec-attach','sec-products','sec-pos','sec-contacts','sec-open-act','sec-closed-act','sec-emails'];
const vvNavLinks  = document.querySelectorAll('.vv-nav a');
window.addEventListener('scroll', () => {
    let cur = '';
    vvSections.forEach(id => {
        const el = document.getElementById(id);
        if (el && window.scrollY >= el.offsetTop - 120) cur = id;
    });
    vvNavLinks.forEach(a => a.classList.toggle('active', a.getAttribute('href') === '#' + cur));
}, {passive:true});
</script>
@endsection
