{{-- Inventory Line Items --}}
<div class="form-group full">
  <label>Line Items</label>
  <div class="inv-items-table">
    <div class="inv-items-header">
      <span>Product/Service</span><span>Qty</span><span>Unit Price</span><span>Discount</span><span>Tax %</span><span>Total</span><span></span>
    </div>
    <div id="inv-items-body">
      <div class="inv-item-row">
        <input type="text" name="item_name[]" class="crm2-input" placeholder="Item name">
        <input type="number" name="item_qty[]" class="crm2-input" value="1" min="1" onchange="calcRow(this)">
        <input type="number" name="item_price[]" class="crm2-input" value="0" step="0.01" onchange="calcRow(this)">
        <input type="number" name="item_discount[]" class="crm2-input" value="0" step="0.01" onchange="calcRow(this)">
        <input type="number" name="item_tax_rate[]" class="crm2-input" value="0" step="0.01" onchange="calcRow(this)">
        <input type="number" name="item_total[]" class="crm2-input row-total" value="0" readonly>
        <button type="button" class="crm2-icon-btn delete" onclick="removeRow(this)"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <button type="button" class="crm2-btn crm2-btn-ghost mt-2" onclick="addItemRow()"><i class="fas fa-plus"></i> Add Item</button>
  </div>
  <div class="inv-totals mt-2">
    <div class="inv-total-row"><span>Subtotal:</span><input type="number" name="subtotal" class="crm2-input inv-subtotal" value="0" step="0.01" readonly></div>
    <div class="inv-total-row"><span>Discount:</span><input type="number" name="discount_amount" class="crm2-input inv-discount" value="0" step="0.01" readonly></div>
    <div class="inv-total-row"><span>Tax:</span><input type="number" name="tax_amount" class="crm2-input inv-tax" value="0" step="0.01" readonly></div>
    <div class="inv-total-row total"><span>Total:</span><input type="number" name="total" class="crm2-input inv-grand-total" value="0" step="0.01" readonly></div>
  </div>
</div>

<script>
function calcRow(el) {
  const row = el.closest('.inv-item-row');
  const qty   = parseFloat(row.querySelector('[name="item_qty[]"]').value) || 0;
  const price = parseFloat(row.querySelector('[name="item_price[]"]').value) || 0;
  const disc  = parseFloat(row.querySelector('[name="item_discount[]"]').value) || 0;
  const taxR  = parseFloat(row.querySelector('[name="item_tax_rate[]"]').value) || 0;
  const taxA  = (qty * price * taxR) / 100;
  const total = (qty * price) - disc + taxA;
  row.querySelector('.row-total').value = total.toFixed(2);
  recalcTotals(el.closest('.inv-items-table'));
}
function recalcTotals(container) {
  const wrapper = container.closest('.form-group');
  let subtotal = 0, disc = 0, tax = 0;
  container.querySelectorAll('.inv-item-row').forEach(row => {
    const qty   = parseFloat(row.querySelector('[name="item_qty[]"]').value) || 0;
    const price = parseFloat(row.querySelector('[name="item_price[]"]').value) || 0;
    const d     = parseFloat(row.querySelector('[name="item_discount[]"]').value) || 0;
    const taxR  = parseFloat(row.querySelector('[name="item_tax_rate[]"]').value) || 0;
    subtotal += qty * price;
    disc += d;
    tax += (qty * price * taxR) / 100;
  });
  wrapper.querySelector('.inv-subtotal').value = subtotal.toFixed(2);
  wrapper.querySelector('.inv-discount').value = disc.toFixed(2);
  wrapper.querySelector('.inv-tax').value = tax.toFixed(2);
  wrapper.querySelector('.inv-grand-total').value = (subtotal - disc + tax).toFixed(2);
}
function addItemRow() {
  const body = document.getElementById('inv-items-body');
  const row = body.querySelector('.inv-item-row').cloneNode(true);
  row.querySelectorAll('input').forEach(i => { if (!i.classList.contains('row-total')) i.value = i.type === 'number' ? (i.name.includes('qty') ? 1 : 0) : ''; else i.value = '0'; });
  body.appendChild(row);
}
function removeRow(btn) {
  const body = document.getElementById('inv-items-body');
  if (body.querySelectorAll('.inv-item-row').length > 1) {
    btn.closest('.inv-item-row').remove();
    recalcTotals(body.closest('.inv-items-table'));
  }
}
</script>
