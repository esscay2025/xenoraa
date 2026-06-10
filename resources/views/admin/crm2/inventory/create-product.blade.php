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
</script>

<div class="cf-page">
    <div class="cf-header">
        <a href="{{ route('admin.crm2.inventory.products') }}" class="cf-back">&#8592; Products</a>
        <h1>New Product</h1>
    </div>
    <form method="POST" action="{{ route('admin.crm2.inventory.products.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="cf-section">
            <div class="cf-section-header" onclick="toggleSection(this)">
                <h3>Product Information</h3><span class="cf-chevron">&#9660;</span>
            </div>
            <div class="cf-section-body">
                <div class="cf-grid cf-grid-3">
                    <div class="cf-field"><label>Product Owner</label>
                        <select name="owner_id"><option value="">-- Select Owner --</option>
                        @foreach($staff as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select>
                    </div>
                    <div class="cf-field"><label>Product Name *</label>
                        <input type="text" name="name" required value="{{ old('name') }}" placeholder="Product name"></div>
                    <div class="cf-field"><label>Product Code</label>
                        <input type="text" name="product_code" value="{{ old('product_code') }}" placeholder="SKU or code"></div>
                    <div class="cf-field"><label>Category</label>
                        <input type="text" name="category" value="{{ old('category') }}" placeholder="e.g. Electronics"></div>
                    <div class="cf-field"><label>Vendor</label>
                        <select name="vendor_id"><option value="">-- Select Vendor --</option>
                        @foreach($vendors as $v)<option value="{{ $v->id }}">{{ $v->name }}</option>@endforeach</select>
                    </div>
                    <div class="cf-field"><label>Active</label>
                        <select name="is_active"><option value="1" selected>Yes</option><option value="0">No</option></select>
                    </div>
                </div>
            </div>
        </div>

        <div class="cf-section">
            <div class="cf-section-header" onclick="toggleSection(this)">
                <h3>Pricing Information</h3><span class="cf-chevron">&#9660;</span>
            </div>
            <div class="cf-section-body">
                <div class="cf-grid cf-grid-3">
                    <div class="cf-field"><label>Unit Price (₹)</label>
                        <input type="number" name="unit_price" step="0.01" value="{{ old('unit_price') }}" placeholder="0.00"></div>
                    <div class="cf-field"><label>Currency</label>
                        <select name="currency">@foreach(['INR'=>'INR (₹)','USD'=>'USD ($)','EUR'=>'EUR (€)','GBP'=>'GBP (£)'] as $v=>$l)
                        <option value="{{ $v }}">{{ $l }}</option>@endforeach</select>
                    </div>
                    <div class="cf-field"><label>Price Book</label>
                        <select name="price_book_id"><option value="">-- Select Price Book --</option>
                        @foreach($price_books as $pb)<option value="{{ $pb->id }}">{{ $pb->name }}</option>@endforeach</select>
                    </div>
                    <div class="cf-field"><label>Tax (%)</label>
                        <input type="number" name="tax" step="0.01" value="{{ old('tax') }}" placeholder="0.00"></div>
                    <div class="cf-field"><label>Commission Rate (%)</label>
                        <input type="number" name="commission_rate" step="0.01" value="{{ old('commission_rate') }}" placeholder="0.00"></div>
                </div>
            </div>
        </div>

        <div class="cf-section">
            <div class="cf-section-header" onclick="toggleSection(this)">
                <h3>Stock Information</h3><span class="cf-chevron">&#9660;</span>
            </div>
            <div class="cf-section-body">
                <div class="cf-grid cf-grid-3">
                    <div class="cf-field"><label>Qty in Stock</label>
                        <input type="number" name="qty_in_stock" value="{{ old('qty_in_stock', 0) }}" min="0"></div>
                    <div class="cf-field"><label>Qty Ordered</label>
                        <input type="number" name="qty_ordered" value="{{ old('qty_ordered', 0) }}" min="0"></div>
                    <div class="cf-field"><label>Reorder Level</label>
                        <input type="number" name="reorder_level" value="{{ old('reorder_level', 0) }}" min="0"></div>
                    <div class="cf-field"><label>Usage Unit</label>
                        <input type="text" name="usage_unit" value="{{ old('usage_unit') }}" placeholder="e.g. Pieces, Kg, Litre"></div>
                    <div class="cf-field"><label>Handler</label>
                        <input type="text" name="handler" value="{{ old('handler') }}" placeholder="Warehouse / handler name"></div>
                </div>
            </div>
        </div>

        <div class="cf-section">
            <div class="cf-section-header" onclick="toggleSection(this)">
                <h3>Product Image &amp; Description</h3><span class="cf-chevron">&#9660;</span>
            </div>
            <div class="cf-section-body">
                <div class="cf-grid cf-grid-2">
                    <div class="cf-field"><label>Product Image</label>
                        <input type="file" name="image" accept="image/*"></div>
                    <div class="cf-field"><label>Description</label>
                        <textarea name="description" rows="4" placeholder="Product description...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="cf-actions">
            <button type="submit" class="cf-btn cf-btn-primary">&#10003; Save Product</button>
            <a href="{{ route('admin.crm2.inventory.products') }}" class="cf-btn cf-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
