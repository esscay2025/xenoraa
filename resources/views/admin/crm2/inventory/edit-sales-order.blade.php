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

/* ── Product Search & Select ─────────────────────────────────────────── */
let productSearchTimeout = null;
function initProductSearch(inputEl, tableId) {
    inputEl.addEventListener('input', function() {
        clearTimeout(productSearchTimeout);
        const q = this.value.trim();
        const dropdown = inputEl.parentElement.querySelector('.product-dropdown');
        if (q.length < 1) { if (dropdown) dropdown.remove(); return; }
        productSearchTimeout = setTimeout(() => {
            fetch(`{{ route('admin.crm2.inventory.products.search') }}?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(products => {
                    let existing = inputEl.parentElement.querySelector('.product-dropdown');
                    if (existing) existing.remove();
                    if (!products.length) return;
                    const dd = document.createElement('div');
                    dd.className = 'product-dropdown';
                    dd.style.cssText = 'position:absolute;z-index:9999;background:var(--bg-card,#fff);border:1px solid var(--border,#e2e8f0);border-radius:6px;box-shadow:0 4px 12px rgba(0,0,0,.12);max-height:220px;overflow-y:auto;min-width:280px;';
                    products.forEach(p => {
                        const item = document.createElement('div');
                        item.style.cssText = 'padding:.5rem .75rem;cursor:pointer;font-size:.85rem;border-bottom:1px solid var(--border,#e2e8f0);';
                        item.innerHTML = `<strong>${p.name}</strong>${p.product_code ? ' <span style="color:#94a3b8;font-size:.78rem">['+p.product_code+']</span>' : ''}<br><span style="color:var(--accent,#6366f1);font-size:.8rem">\u20b9${parseFloat(p.unit_price).toLocaleString('en-IN',{minimumFractionDigits:2})} ${p.usage_unit ? '/ '+p.usage_unit : ''}</span>`;
                        item.addEventListener('mouseenter', () => item.style.background = 'var(--bg-primary,#f8fafc)');
                        item.addEventListener('mouseleave', () => item.style.background = '');
                        item.addEventListener('click', () => {
                            inputEl.value = p.name;
                            inputEl.dataset.productId = p.id;
                            const row = inputEl.closest('tr');
                            if (row) {
                                const priceEl = row.querySelector('.li-price');
                                if (priceEl) { priceEl.value = parseFloat(p.unit_price).toFixed(2); priceEl.dispatchEvent(new Event('input')); }
                            }
                            dd.remove();
                        });
                        dd.appendChild(item);
                    });
                    inputEl.parentElement.style.position = 'relative';
                    inputEl.parentElement.appendChild(dd);
                    setTimeout(() => document.addEventListener('click', function handler(e) {
                        if (!dd.contains(e.target) && e.target !== inputEl) { dd.remove(); document.removeEventListener('click', handler); }
                    }), 10);
                });
        }, 250);
    });
}
function addLineItemWithSearch(tableId) {
    const tbody = document.getElementById(tableId).querySelector('tbody');
    const row = tbody.rows[0].cloneNode(true);
    row.querySelectorAll('input').forEach(i => {
        if (i.classList.contains('li-qty')) i.value = '1';
        else if (i.classList.contains('li-price') || i.classList.contains('li-disc') || i.classList.contains('li-tax') || i.classList.contains('li-amt') || i.classList.contains('li-total')) i.value = '0';
        else i.value = '';
        delete i.dataset.productId;
    });
    row.querySelectorAll('.product-dropdown').forEach(d => d.remove());
    tbody.appendChild(row);
    const newInput = row.querySelector('.li-product');
    if (newInput) initProductSearch(newInput, tableId);
    recalcTotals(tableId);
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.li-product').forEach(inp => {
        const tableId = inp.closest('table')?.id;
        if (tableId) initProductSearch(inp, tableId);
    });
});

</script>

<div class="cf-page">
    <div class="cf-header">
        <a href="{{ route('admin.crm2.inventory.sales-orders') }}" class="cf-back">&#8592; Sales Orders</a>
        <h1>Edit Sales Order</h1>
    </div>
    <form method="POST" action="{{ route('admin.crm2.inventory.update', ['type'=>'sales_order','id'=>$item->id]) }}" onsubmit="serializeLineItems('so_items','so_items_json')">
        @csrf @method('PATCH')

        <div class="cf-section">
            <div class="cf-section-header" onclick="toggleSection(this)">
                <h3>Sales Order Information</h3><span class="cf-chevron">&#9660;</span>
            </div>
            <div class="cf-section-body">
                <div class="cf-grid cf-grid-3">
                    <div class="cf-field"><label>Sales Order Owner</label>
                        <select name="owner_id"><option value="">-- Select Owner --</option>
                        @foreach($staff as $s)<option value="{{ $s->id }}" {{ $item->owner_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>@endforeach</select>
                    </div>
                    <div class="cf-field"><label>Subject *</label><input type="text" name="subject" required value="{{ old('subject', $item->subject) }}"></div>
                    <div class="cf-field"><label>Status</label>
                        <select name="status">@foreach(['Created','Approved','Delivered','Cancelled'] as $s)
                        <option value="{{ $s }}" {{ $item->status == $s ? 'selected' : '' }}>{{ $s }}</option>@endforeach</select>
                    </div>
                    <div class="cf-field"><label>Customer No.</label><input type="text" name="customer_no" value="{{ old('customer_no', $item->customer_no) }}"></div>
                    <div class="cf-field"><label>Quote</label>
                        <select name="quote_id"><option value="">-- Select Quote --</option>
                        @foreach($quotes as $q)<option value="{{ $q->id }}" {{ $item->quote_id == $q->id ? 'selected' : '' }}>{{ $q->subject }}</option>@endforeach</select>
                    </div>
                    <div class="cf-field"><label>Pending Amount (₹)</label><input type="number" name="pending" step="0.01" value="{{ old('pending', $item->pending) }}"></div>
                    <div class="cf-field"><label>Carrier</label>
                        <select name="carrier"><option value="">-- Select --</option>
                        @foreach(['FedEx','DHL','UPS','DTDC','Blue Dart','India Post'] as $c)
                        <option value="{{ $c }}" {{ $item->carrier == $c ? 'selected' : '' }}>{{ $c }}</option>@endforeach</select>
                    </div>
                    <div class="cf-field"><label>Sales Commission (%)</label><input type="number" name="sales_commission" step="0.01" value="{{ old('sales_commission', $item->sales_commission) }}"></div>
                    <div class="cf-field"><label>Account</label>
                        <select name="account_id"><option value="">-- Select Account --</option>
                        @foreach($accounts as $a)<option value="{{ $a->id }}" {{ $item->account_id == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>@endforeach</select>
                    </div>
                    <div class="cf-field"><label>Deal</label>
                        <select name="deal_id"><option value="">-- Select Deal --</option>
                        @foreach($deals as $d)<option value="{{ $d->id }}" {{ $item->deal_id == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>@endforeach</select>
                    </div>
                    <div class="cf-field"><label>Purchase Order Ref.</label><input type="text" name="purchase_order" value="{{ old('purchase_order', $item->purchase_order) }}"></div>
                    <div class="cf-field"><label>Due Date</label><input type="date" name="delivery_date" value="{{ old('delivery_date', $item->delivery_date ? substr($item->delivery_date,0,10) : '') }}"></div>
                    <div class="cf-field"><label>Contact</label>
                        <select name="contact_id"><option value="">-- Select Contact --</option>
                        @foreach($contacts as $c)<option value="{{ $c->id }}" {{ $item->contact_id == $c->id ? 'selected' : '' }}>{{ $c->first_name }} {{ $c->last_name }}</option>@endforeach</select>
                    </div>
                    <div class="cf-field"><label>Excise Duty (%)</label><input type="number" name="excise_duty" step="0.01" value="{{ old('excise_duty', $item->excise_duty) }}"></div>
                </div>
            </div>
        </div>

        <div class="cf-section">
            <div class="cf-section-header" onclick="toggleSection(this)">
                <h3>Address Information</h3><span class="cf-chevron">&#9660;</span>
            </div>
            <div class="cf-section-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem">
                    <div>
                        <h4 style="color:var(--cf-accent);margin:0 0 .75rem;font-size:.85rem">Billing Address</h4>
                        <div class="cf-grid" style="grid-template-columns:1fr 1fr">
                            <div class="cf-field cf-field-full"><label>Country / Region</label><input type="text" name="bill_country" value="{{ old('bill_country', $item->bill_country) }}"></div>
                            <div class="cf-field cf-field-full"><label>Building / Apartment</label><input type="text" name="bill_building" value="{{ old('bill_building', $item->bill_building) }}"></div>
                            <div class="cf-field cf-field-full"><label>Street Address</label><input type="text" name="bill_street" value="{{ old('bill_street', $item->bill_street) }}"></div>
                            <div class="cf-field"><label>City</label><input type="text" name="bill_city" value="{{ old('bill_city', $item->bill_city) }}"></div>
                            <div class="cf-field"><label>State / Province</label><input type="text" name="bill_state" value="{{ old('bill_state', $item->bill_state) }}"></div>
                            <div class="cf-field"><label>Zip / Postal Code</label><input type="text" name="bill_zip" value="{{ old('bill_zip', $item->bill_zip) }}"></div>
                        </div>
                    </div>
                    <div>
                        <h4 style="color:var(--cf-accent);margin:0 0 .75rem;font-size:.85rem">Shipping Address</h4>
                        <div class="cf-grid" style="grid-template-columns:1fr 1fr">
                            <div class="cf-field cf-field-full"><label>Country / Region</label><input type="text" name="ship_country" value="{{ old('ship_country', $item->ship_country) }}"></div>
                            <div class="cf-field cf-field-full"><label>Building / Apartment</label><input type="text" name="ship_building" value="{{ old('ship_building', $item->ship_building) }}"></div>
                            <div class="cf-field cf-field-full"><label>Street Address</label><input type="text" name="ship_street" value="{{ old('ship_street', $item->ship_street) }}"></div>
                            <div class="cf-field"><label>City</label><input type="text" name="ship_city" value="{{ old('ship_city', $item->ship_city) }}"></div>
                            <div class="cf-field"><label>State / Province</label><input type="text" name="ship_state" value="{{ old('ship_state', $item->ship_state) }}"></div>
                            <div class="cf-field"><label>Zip / Postal Code</label><input type="text" name="ship_zip" value="{{ old('ship_zip', $item->ship_zip) }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="cf-section">
            <div class="cf-section-header" onclick="toggleSection(this)">
                <h3>Ordered Items</h3><span class="cf-chevron">&#9660;</span>
            </div>
            <div class="cf-section-body">
                <input type="hidden" name="line_items" id="so_items_json">
                <table class="li-table" id="so_items">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Qty</th>
                            <th>List Price (₹)</th>
                            <th>Amount (₹)</th>
                            <th>Discount (₹)</th>
                            <th>Tax (₹)</th>
                            <th>Total (₹)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="so_items_body">
                        @php $lineItems = is_string($item->line_items) ? json_decode($item->line_items, true) : ($item->line_items ?? []); @endphp
                        @if(!empty($lineItems))
                            @foreach($lineItems as $li)
                            <tr>
                                <td>{ $loop->iteration }</td>
                                <td><input type="text" class="li-product" value="{ $li['product'] ?? '' }"></td>
                                <td><input type="number" class="li-qty" value="{ $li['qty'] ?? 1 }" min="1" style="width:60px" oninput="recalcTotals('so_items')"></td>
                                <td><input type="number" class="li-price" step="0.01" value="{ $li['price'] ?? 0 }" style="width:90px" oninput="recalcTotals('so_items')"></td>
                                <td><input type="number" class="li-amt" step="0.01" value="{ $li['amount'] ?? 0 }" style="width:90px" readonly></td>
                                <td><input type="number" class="li-disc" step="0.01" value="{ $li['discount'] ?? 0 }" style="width:80px" oninput="recalcTotals('so_items')"></td>
                                <td><input type="number" class="li-tax" step="0.01" value="{ $li['tax'] ?? 0 }" style="width:80px" oninput="recalcTotals('so_items')"></td>
                                <td><input type="number" class="li-total" step="0.01" value="{ $li['total'] ?? 0 }" style="width:90px" readonly></td>
                                <td><button type="button" class="li-remove-btn" onclick="removeLineItem(this,'so_items')">&#10005;</button></td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td>1</td>
                            <td><input type="text" class="li-product" placeholder="Search product..." autocomplete="off"></td>
                            <td><input type="number" class="li-qty" value="1" min="1" style="width:60px" oninput="recalcTotals('so_items')"></td>
                            <td><input type="number" class="li-price" step="0.01" value="0" style="width:90px" oninput="recalcTotals('so_items')"></td>
                            <td><input type="number" class="li-amt" step="0.01" value="0" style="width:90px" readonly></td>
                            <td><input type="number" class="li-disc" step="0.01" value="0" style="width:80px" oninput="recalcTotals('so_items')"></td>
                            <td><input type="number" class="li-tax" step="0.01" value="0" style="width:80px" oninput="recalcTotals('so_items')"></td>
                            <td><input type="number" class="li-total" step="0.01" value="0" style="width:90px" readonly></td>
                            <td><button type="button" class="li-remove-btn" onclick="removeLineItem(this,'so_items')">&#10005;</button></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <button type="button" class="li-add-btn" onclick="addLineItemWithSearch('so_items')">+ Add Row</button>
                <div class="li-summary">
                    <div class="li-summary-row"><label>Sub Total (₹)</label><input type="number" id="so_items_subtotal" name="subtotal" step="0.01" value="{ $item->subtotal ?? 0 }" readonly></div>
                    <div class="li-summary-row"><label>Discount (₹)</label><input type="number" id="so_items_discount" name="discount_amount" step="0.01" value="{ $item->discount_amount ?? 0 }" oninput="recalcTotals('so_items')"></div>
                    <div class="li-summary-row"><label>Tax (₹)</label><input type="number" id="so_items_tax" name="tax_amount" step="0.01" value="{ $item->tax_amount ?? 0 }" oninput="recalcTotals('so_items')"></div>
                    <div class="li-summary-row"><label>Adjustment (₹)</label><input type="number" id="so_items_adjustment" name="adjustment" step="0.01" value="{ $item->adjustment ?? 0 }" oninput="recalcTotals('so_items')"></div>
                    <div class="li-summary-row li-grand-total"><label>Grand Total (₹)</label><input type="number" id="so_items_grand" name="grand_total" step="0.01" value="{ $item->grand_total ?? 0 }" readonly></div>
                </div>
            </div>
        </div>

        <div class="cf-section">
            <div class="cf-section-header" onclick="toggleSection(this)">
                <h3>Terms &amp; Description</h3><span class="cf-chevron">&#9660;</span>
            </div>
            <div class="cf-section-body">
                <div class="cf-grid cf-grid-2">
                    <div class="cf-field"><label>Terms and Conditions</label><textarea name="terms" rows="4">{{ old('terms', $item->terms) }}</textarea></div>
                    <div class="cf-field"><label>Notes</label><textarea name="notes" rows="4">{{ old('notes', $item->notes) }}</textarea></div>
                </div>
            </div>
        </div>

        <div class="cf-actions">
            <button type="submit" class="cf-btn cf-btn-primary">&#10003; Update Sales Order</button>
            <a href="{{ route('admin.crm2.inventory.sales-orders') }}" class="cf-btn cf-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
