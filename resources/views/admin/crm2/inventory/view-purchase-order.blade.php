@extends('layouts.admin')
@section('content')

<style>
:root {
    --cf-bg: var(--bg-primary, #f8fafc);
    --cf-card: var(--bg-card, #ffffff);
    --cf-border: var(--border, #e2e8f0);
    --cf-accent: var(--accent, #6366f1);
    --cf-text: var(--text-primary, #1e293b);
    --cf-muted: var(--text-muted, #94a3b8);
    --cf-label: var(--text-secondary, #64748b);
    --cf-radius: 10px;
    --cf-shadow: 0 1px 3px rgba(0,0,0,.08);
}
.cf-page { padding: 1.5rem; background: var(--cf-bg); min-height: 100vh; }
.cf-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
.cf-header h1 { font-size: 1.4rem; font-weight: 700; color: var(--cf-text); margin: 0; }
.cf-back { display: inline-flex; align-items: center; gap: .4rem; color: var(--cf-accent);
           text-decoration: none; font-size: .85rem; padding: .4rem .8rem;
           border: 1px solid var(--cf-accent); border-radius: 6px; }
.cf-back:hover { background: var(--cf-accent); color: #fff; }
.cf-section { background: var(--cf-card); border: 1px solid var(--cf-border);
              border-radius: var(--cf-radius); margin-bottom: 1.25rem;
              box-shadow: var(--cf-shadow); overflow: hidden; }
.cf-section-header { display: flex; align-items: center; justify-content: space-between;
                     padding: .75rem 1.25rem; background: var(--cf-accent);
                     cursor: pointer; user-select: none; }
.cf-section-header h3 { color: #fff; font-size: .9rem; font-weight: 600; margin: 0; }
.cf-section-header .cf-chevron { color: #fff; transition: transform .2s; font-size: .8rem; }
.cf-section-body { padding: 1.25rem; }
.cf-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; }
.cf-grid-2 { grid-template-columns: repeat(2, 1fr); }
.cf-grid-3 { grid-template-columns: repeat(3, 1fr); }
.cf-field { display: flex; flex-direction: column; gap: .3rem; }
.cf-field label { font-size: .78rem; font-weight: 600; color: var(--cf-label); text-transform: uppercase; letter-spacing: .04em; }
.cf-field input, .cf-field select, .cf-field textarea {
    padding: .5rem .75rem; border: 1px solid var(--cf-border); border-radius: 6px;
    background: var(--cf-bg); color: var(--cf-text); font-size: .88rem;
    transition: border-color .15s; width: 100%; }
.cf-field input:focus, .cf-field select:focus, .cf-field textarea:focus {
    outline: none; border-color: var(--cf-accent); box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
.cf-field textarea { resize: vertical; min-height: 80px; }
.cf-field-full { grid-column: 1 / -1; }
.cf-actions { display: flex; gap: .75rem; margin-top: 1.5rem; flex-wrap: wrap; }
.cf-btn { padding: .55rem 1.4rem; border-radius: 7px; font-size: .88rem; font-weight: 600;
          cursor: pointer; border: none; transition: all .15s; text-decoration: none;
          display: inline-flex; align-items: center; gap: .4rem; }
.cf-btn-primary { background: var(--cf-accent); color: #fff; }
.cf-btn-primary:hover { opacity: .88; }
.cf-btn-secondary { background: transparent; color: var(--cf-accent);
                    border: 1px solid var(--cf-accent); }
.cf-btn-secondary:hover { background: var(--cf-accent); color: #fff; }
.cf-btn-danger { background: #ef4444; color: #fff; }
.cf-btn-danger:hover { background: #dc2626; }

/* View page */
.cv-page { padding: 1.5rem; background: var(--cf-bg); min-height: 100vh; }
.cv-header { display: flex; align-items: flex-start; justify-content: space-between;
             margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
.cv-title-block h1 { font-size: 1.5rem; font-weight: 700; color: var(--cf-text); margin: 0 0 .3rem; }
.cv-badge { display: inline-block; padding: .25rem .7rem; border-radius: 20px; font-size: .75rem;
            font-weight: 600; background: var(--cf-accent); color: #fff; }
.cv-actions { display: flex; gap: .6rem; flex-wrap: wrap; }
.cv-section { background: var(--cf-card); border: 1px solid var(--cf-border);
              border-radius: var(--cf-radius); margin-bottom: 1.25rem; overflow: hidden;
              box-shadow: var(--cf-shadow); }
.cv-section-header { padding: .65rem 1.25rem; background: var(--cf-accent); }
.cv-section-header h3 { color: #fff; font-size: .85rem; font-weight: 600; margin: 0; }
.cv-section-body { padding: 1.25rem; }
.cv-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
.cv-field { display: flex; flex-direction: column; gap: .2rem; }
.cv-field .cv-label { font-size: .72rem; font-weight: 600; color: var(--cf-muted);
                      text-transform: uppercase; letter-spacing: .04em; }
.cv-field .cv-value { font-size: .9rem; color: var(--cf-text); font-weight: 500; }
.cv-field .cv-empty { color: var(--cf-muted); font-style: italic; }

/* Line items table */
.li-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
.li-table th { background: var(--cf-accent); color: #fff; padding: .5rem .75rem;
               text-align: left; font-size: .78rem; font-weight: 600; }
.li-table td { padding: .5rem .75rem; border-bottom: 1px solid var(--cf-border); color: var(--cf-text); }
.li-table td input, .li-table td select { padding: .3rem .5rem; border: 1px solid var(--cf-border);
    border-radius: 4px; background: var(--cf-bg); color: var(--cf-text); font-size: .82rem; width: 100%; }
.li-table .li-total-row td { background: var(--cf-bg); font-weight: 600; }
.li-add-btn { margin-top: .5rem; padding: .35rem .9rem; background: var(--cf-accent); color: #fff;
              border: none; border-radius: 5px; cursor: pointer; font-size: .82rem; }
.li-remove-btn { background: #ef4444; color: #fff; border: none; border-radius: 4px;
                 padding: .2rem .5rem; cursor: pointer; font-size: .75rem; }
.li-summary { margin-top: 1rem; display: flex; flex-direction: column; align-items: flex-end; gap: .4rem; }
.li-summary-row { display: flex; gap: 2rem; align-items: center; font-size: .88rem; }
.li-summary-row label { color: var(--cf-label); font-weight: 600; min-width: 120px; text-align: right; }
.li-summary-row input { width: 140px; padding: .35rem .6rem; border: 1px solid var(--cf-border);
                        border-radius: 5px; background: var(--cf-bg); color: var(--cf-text); font-size: .88rem; }
.li-grand-total { font-size: 1rem; font-weight: 700; color: var(--cf-accent); }

/* ─── Print / PDF Styles ─── */
@media print {
    .cv-header .cv-actions, .no-print { display: none !important; }
    .cv-page { padding: 0; background: #fff; }
    .cv-section { border: 1px solid #ccc; box-shadow: none; page-break-inside: avoid; }
    .cv-section-header { background: #4f46e5 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    body { font-size: 12px; }
    .li-table th { background: #4f46e5 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .send-mail-overlay, .send-mail-panel { display: none !important; }
}
/* ─── Send Mail Slider Panel ─── */
.send-mail-overlay {
    display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 1000;
    transition: opacity .25s;
}
.send-mail-overlay.active { display: block; }
.send-mail-panel {
    position: fixed; top: 0; right: -520px; width: 480px; max-width: 96vw; height: 100vh;
    background: var(--cf-card, #fff); box-shadow: -4px 0 24px rgba(0,0,0,.18);
    z-index: 1001; transition: right .3s cubic-bezier(.4,0,.2,1);
    display: flex; flex-direction: column; overflow: hidden;
}
.send-mail-panel.active { right: 0; }
.send-mail-panel-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1rem 1.25rem; background: var(--cf-accent, #6366f1); color: #fff;
    flex-shrink: 0;
}
.send-mail-panel-header h3 { margin: 0; font-size: 1rem; font-weight: 700; }
.send-mail-close { background: none; border: none; color: #fff; font-size: 1.4rem;
                   cursor: pointer; line-height: 1; padding: 0; }
.send-mail-body { flex: 1; overflow-y: auto; padding: 1.25rem; }
.send-mail-field { margin-bottom: 1rem; }
.send-mail-field label { display: block; font-size: .78rem; font-weight: 600;
                          color: var(--cf-label, #64748b); text-transform: uppercase;
                          letter-spacing: .04em; margin-bottom: .3rem; }
.send-mail-field input, .send-mail-field textarea {
    width: 100%; padding: .55rem .75rem; border: 1px solid var(--cf-border, #e2e8f0);
    border-radius: 6px; font-size: .88rem; color: var(--cf-text, #1e293b);
    background: var(--cf-bg, #f8fafc); box-sizing: border-box;
}
.send-mail-field textarea { min-height: 140px; resize: vertical; }
.send-mail-field input:focus, .send-mail-field textarea:focus {
    outline: none; border-color: var(--cf-accent, #6366f1);
    box-shadow: 0 0 0 3px rgba(99,102,241,.1);
}
.send-mail-attach-note {
    font-size: .78rem; color: var(--cf-muted, #94a3b8); margin-bottom: 1rem;
    padding: .5rem .75rem; background: #f0f4ff; border-radius: 6px;
    border-left: 3px solid var(--cf-accent, #6366f1);
}
.send-mail-footer { padding: 1rem 1.25rem; border-top: 1px solid var(--cf-border, #e2e8f0);
                    display: flex; gap: .75rem; flex-shrink: 0; }
.btn-send-mail { background: var(--cf-accent, #6366f1); color: #fff; border: none;
                 padding: .6rem 1.5rem; border-radius: 7px; font-size: .9rem;
                 font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: .4rem; }
.btn-send-mail:hover { opacity: .88; }
.btn-cancel-mail { background: transparent; color: var(--cf-accent, #6366f1);
                   border: 1px solid var(--cf-accent, #6366f1); padding: .6rem 1.2rem;
                   border-radius: 7px; font-size: .9rem; font-weight: 600; cursor: pointer; }

</style>
<script>
function toggleSection(el) {
    const body = el.nextElementSibling;
    const chevron = el.querySelector('.cf-chevron');
    body.style.display = body.style.display === 'none' ? 'block' : 'none';
    chevron.style.transform = body.style.display === 'none' ? 'rotate(-90deg)' : '';
}
function addLineItem(tableId) {
    const tbody = document.getElementById(tableId).querySelector('tbody');
    const row = tbody.rows[0].cloneNode(true);
    row.querySelectorAll('input').forEach(i => i.value = '');
    tbody.appendChild(row);
    recalcTotals(tableId);
}
function removeLineItem(btn, tableId) {
    const tbody = document.getElementById(tableId).querySelector('tbody');
    if (tbody.rows.length > 1) { btn.closest('tr').remove(); recalcTotals(tableId); }
}
function recalcTotals(tableId) {
    const tbody = document.getElementById(tableId).querySelector('tbody');
    let subtotal = 0;
    tbody.querySelectorAll('tr').forEach(row => {
        const qty = parseFloat(row.querySelector('.li-qty')?.value) || 0;
        const price = parseFloat(row.querySelector('.li-price')?.value) || 0;
        const disc = parseFloat(row.querySelector('.li-disc')?.value) || 0;
        const tax = parseFloat(row.querySelector('.li-tax')?.value) || 0;
        const amt = qty * price;
        const total = amt - disc + tax;
        if (row.querySelector('.li-amt')) row.querySelector('.li-amt').value = amt.toFixed(2);
        if (row.querySelector('.li-total')) row.querySelector('.li-total').value = total.toFixed(2);
        subtotal += total;
    });
    const discEl = document.getElementById(tableId + '_discount');
    const taxEl = document.getElementById(tableId + '_tax');
    const adjEl = document.getElementById(tableId + '_adjustment');
    const grandEl = document.getElementById(tableId + '_grand');
    const subEl = document.getElementById(tableId + '_subtotal');
    if (subEl) subEl.value = subtotal.toFixed(2);
    const disc = parseFloat(discEl?.value) || 0;
    const tax = parseFloat(taxEl?.value) || 0;
    const adj = parseFloat(adjEl?.value) || 0;
    if (grandEl) grandEl.value = (subtotal - disc + tax + adj).toFixed(2);
}
function serializeLineItems(tableId, fieldId) {
    const tbody = document.getElementById(tableId).querySelector('tbody');
    const items = [];
    tbody.querySelectorAll('tr').forEach(row => {
        items.push({
            product: row.querySelector('.li-product')?.value || '',
            qty: row.querySelector('.li-qty')?.value || '',
            price: row.querySelector('.li-price')?.value || '',
            discount: row.querySelector('.li-disc')?.value || '',
            tax: row.querySelector('.li-tax')?.value || '',
            total: row.querySelector('.li-total')?.value || ''
        });
    });
    document.getElementById(fieldId).value = JSON.stringify(items);
}
</script>

<div class="cv-page">
    <div class="cv-header">
        <div class="cv-title-block">
            <h1>{{ $item->subject ?: 'PO #' . $item->id }}</h1>
            <span class="cv-badge">{{ $item->status ?: 'Created' }}</span>
        </div>
        <div class="cv-actions">
            <a href="{{ route('admin.crm2.inventory.purchase-orders.edit', $item->id) }}" class="cf-btn cf-btn-primary">&#9998; Edit</a>
            <a href="{{ route('admin.crm2.inventory.purchase-orders') }}" class="cf-btn cf-btn-secondary">&#8592; Back</a>
            <form method="POST" action="{{ route('admin.crm2.inventory.destroy', ['type'=>'purchase_order','id'=>$item->id]) }}" style="display:inline" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button type="submit" class="cf-btn cf-btn-danger">&#128465; Delete</button>
            </form>
            <button type="button" class="cf-btn cf-btn-secondary no-print" onclick="window.print()">&#128424; Print / PDF</button>
            <button type="button" class="cf-btn cf-btn-secondary no-print" onclick="openSendMail()">&#9993; Send Mail</button>
        </div>
    </div>

    <div class="cv-section">
        <div class="cv-section-header"><h3>Purchase Order Information</h3></div>
        <div class="cv-section-body">
            <div class="cv-grid">
                <div class="cv-field"><span class="cv-label">Subject</span><span class="cv-value">{{ $item->subject ?: '—' }}</span></div>
                <div class="cv-field"><span class="cv-label">Status</span><span class="cv-value">{{ $item->status ?: '—' }}</span></div>
                <div class="cv-field"><span class="cv-label">Vendor</span><span class="cv-value">{{ $item->vendor ? $item->vendor->name : '—' }}</span></div>
                <div class="cv-field"><span class="cv-label">Carrier</span><span class="cv-value">{{ $item->carrier ?: '—' }}</span></div>
                <div class="cv-field"><span class="cv-label">Tracking Number</span><span class="cv-value">{{ $item->tracking_number ?: '—' }}</span></div>
                <div class="cv-field"><span class="cv-label">PO Date</span><span class="cv-value">{{ $item->po_date ? \Carbon\Carbon::parse($item->po_date)->format('d M Y') : '—' }}</span></div>
                <div class="cv-field"><span class="cv-label">Due Date</span><span class="cv-value">{{ $item->delivery_date ? \Carbon\Carbon::parse($item->delivery_date)->format('d M Y') : '—' }}</span></div>
                <div class="cv-field"><span class="cv-label">Sales Order Ref.</span><span class="cv-value">{{ $item->sales_order_ref ?: '—' }}</span></div>
                <div class="cv-field"><span class="cv-label">Requisition No.</span><span class="cv-value">{{ $item->requisition_no ?: '—' }}</span></div>
                <div class="cv-field"><span class="cv-label">Excise Duty</span><span class="cv-value">{{ $item->excise_duty ? $item->excise_duty . '%' : '—' }}</span></div>
            </div>
        </div>
    </div>

    <div class="cv-section">
        <div class="cv-section-header"><h3>Ordered Items</h3></div>
        <div class="cv-section-body">
            @php $lineItems = is_string($item->line_items) ? json_decode($item->line_items, true) : ($item->line_items ?? []); @endphp
            @if(!empty($lineItems))
            <table class="li-table"><thead><tr><th>#</th><th>Product</th><th>Qty</th><th>Unit Price</th><th>Discount</th><th>Tax</th><th>Total</th></tr></thead>
            <tbody>@foreach($lineItems as $i => $li)<tr><td>{{ $i+1 }}</td><td>{{ $li['product'] ?? '—' }}</td><td>{{ $li['qty'] ?? 1 }}</td><td>₹{{ number_format($li['price'] ?? 0, 2) }}</td><td>₹{{ number_format($li['discount'] ?? 0, 2) }}</td><td>₹{{ number_format($li['tax'] ?? 0, 2) }}</td><td>₹{{ number_format($li['total'] ?? 0, 2) }}</td></tr>@endforeach</tbody></table>
            @else<p style="color:var(--cf-muted);font-style:italic">No items added.</p>@endif
            <div class="li-summary" style="margin-top:1rem">
                <div class="li-summary-row"><label>Sub Total</label><span>₹{{ number_format($item->subtotal ?? 0, 2) }}</span></div>
                <div class="li-summary-row"><label>Discount</label><span>₹{{ number_format($item->discount_amount ?? 0, 2) }}</span></div>
                <div class="li-summary-row"><label>Tax</label><span>₹{{ number_format($item->tax_amount ?? 0, 2) }}</span></div>
                <div class="li-summary-row"><label>Adjustment</label><span>₹{{ number_format($item->adjustment ?? 0, 2) }}</span></div>
                <div class="li-summary-row li-grand-total"><label>Grand Total</label><span>₹{{ number_format($item->grand_total ?? $item->total ?? 0, 2) }}</span></div>
            </div>
        </div>
    </div>
</div>

<!-- Send Mail Overlay & Slider Panel -->
<div class="send-mail-overlay no-print" id="sendMailOverlay" onclick="closeSendMail()"></div>
<div class="send-mail-panel no-print" id="sendMailPanel">
    <div class="send-mail-panel-header">
        <h3>&#9993; Send Purchase Order via Email</h3>
        <button class="send-mail-close" onclick="closeSendMail()">&#10005;</button>
    </div>
    <div class="send-mail-body">
        <div class="send-mail-attach-note">
            &#128206; A PDF copy of this Purchase Order will be automatically attached to the email.
        </div>
        <form method="POST" action="{{ route('admin.crm2.inventory.send-mail', ['type'=>'purchase-order','id'=>$item->id]) }}">
            @csrf
            <div class="send-mail-field">
                <label>To (Email Address)</label>
                <input type="email" name="to_email" required placeholder="recipient@example.com"
                       value="{{ $item->contact?->email ?? $item->account?->email ?? '' }}">
            </div>
            <div class="send-mail-field">
                <label>CC (optional)</label>
                <input type="email" name="cc_email" placeholder="cc@example.com">
            </div>
            <div class="send-mail-field">
                <label>Subject</label>
                <input type="text" name="subject" required
                       value="{{ $item->subject ?: 'Purchase Order #' . $item->id }}">
            </div>
            <div class="send-mail-field">
                <label>Message</label>
                <textarea name="body" placeholder="Write your message here...">Dear {{ $item->contact ? $item->contact->first_name : 'Sir/Madam' }},

Please find attached the Purchase Order for your reference.

Grand Total: ₹{{ number_format($item->grand_total ?? 0, 2) }}

Please feel free to reach out if you have any questions.

Regards,
{{ auth()->user()->name }}</textarea>
            </div>
    </div>
    <div class="send-mail-footer">
        <button type="submit" class="btn-send-mail">&#9993; Send Email</button>
        <button type="button" class="btn-cancel-mail" onclick="closeSendMail()">Cancel</button>
    </div>
        </form>
</div>

<script>

function openSendMail() {
    document.getElementById('sendMailOverlay').classList.add('active');
    document.getElementById('sendMailPanel').classList.add('active');
}
function closeSendMail() {
    document.getElementById('sendMailOverlay').classList.remove('active');
    document.getElementById('sendMailPanel').classList.remove('active');
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSendMail(); });

</script>
@endsection
