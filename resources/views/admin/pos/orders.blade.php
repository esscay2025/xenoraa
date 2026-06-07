@extends('layouts.admin')

@section('title', 'POS Orders')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fas fa-receipt text-primary me-2"></i>POS Orders</h4>
            <p class="text-muted mb-0 small">All billing counter orders</p>
        </div>
        <a href="{{ route('admin.pos.terminal') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-cash-register me-1"></i> Open POS Terminal
        </a>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Date</label>
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date', date('Y-m-d')) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Payment</label>
                    <select name="payment" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="cash" {{ request('payment') === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="card" {{ request('payment') === 'card' ? 'selected' : '' }}>Card</option>
                        <option value="upi" {{ request('payment') === 'upi' ? 'selected' : '' }}>UPI</option>
                        <option value="split" {{ request('payment') === 'split' ? 'selected' : '' }}>Split</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="void" {{ request('status') === 'void' ? 'selected' : '' }}>Void</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Search</label>
                    <input type="text" name="q" class="form-control form-control-sm" placeholder="Order # or customer..." value="{{ request('q') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary for the day --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="text-muted small">Total Orders</div>
                    <div class="h5 fw-bold mb-0">{{ $summary['count'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="text-muted small">Total Sales</div>
                    <div class="h5 fw-bold mb-0 text-success">{{ $currency }}{{ number_format($summary['total'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="text-muted small">Total Discount</div>
                    <div class="h5 fw-bold mb-0 text-danger">{{ $currency }}{{ number_format($summary['discount'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="text-muted small">Avg Order Value</div>
                    <div class="h5 fw-bold mb-0">{{ $currency }}{{ $summary['count'] > 0 ? number_format($summary['total'] / $summary['count'], 2) : '0.00' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Order #</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Subtotal</th>
                            <th>Discount</th>
                            <th>Tax</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Time</th>
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="{{ $order->status === 'void' ? 'table-danger opacity-75' : '' }}">
                            <td class="ps-4 fw-bold">{{ $order->order_number }}</td>
                            <td>
                                {{ $order->customer_name ?: 'Walk-in' }}
                                @if($order->customer_phone)
                                <div class="small text-muted">{{ $order->customer_phone }}</div>
                                @endif
                            </td>
                            <td>{{ $order->items->count() }}</td>
                            <td>{{ $currency }}{{ number_format($order->subtotal, 2) }}</td>
                            <td class="text-danger">{{ $order->discount_amount > 0 ? '-'.$currency.number_format($order->discount_amount, 2) : '—' }}</td>
                            <td>{{ $order->tax_amount > 0 ? $currency.number_format($order->tax_amount, 2) : '—' }}</td>
                            <td class="fw-bold">{{ $currency }}{{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->payment_method === 'cash' ? 'success' : ($order->payment_method === 'card' ? 'primary' : 'warning') }}-subtle text-{{ $order->payment_method === 'cash' ? 'success' : ($order->payment_method === 'card' ? 'primary' : 'warning') }}">
                                    {{ strtoupper($order->payment_method) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'danger' }}-subtle text-{{ $order->status === 'completed' ? 'success' : 'danger' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="small">{{ $order->created_at->format('h:i A') }}</td>
                            <td class="pe-4">
                                <button class="btn btn-outline-primary btn-sm" onclick="viewReceipt({{ $order->id }})">
                                    <i class="fas fa-receipt"></i>
                                </button>
                                @if($order->status === 'completed')
                                <button class="btn btn-outline-danger btn-sm ms-1" onclick="voidOrder({{ $order->id }})">
                                    <i class="fas fa-ban"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-5 text-muted">
                                <i class="fas fa-receipt fa-2x mb-3 d-block opacity-25"></i>
                                No orders found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($orders->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Receipt Modal --}}
<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold"><i class="fas fa-receipt me-2"></i>Receipt</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="receiptBody" style="font-family:'Courier New',monospace;font-size:12px;">
                <div class="text-center p-4 text-muted">Loading...</div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-dark btn-sm" onclick="window.print()"><i class="fas fa-print me-1"></i>Print</button>
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Void Modal --}}
<div class="modal fade" id="voidModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-danger"><i class="fas fa-ban me-2"></i>Void Order</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="voidId">
                <div class="mb-3">
                    <label class="form-label small">Reason for Void <span class="text-danger">*</span></label>
                    <textarea id="voidReason" class="form-control form-control-sm" rows="3" placeholder="Enter reason..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm" onclick="submitVoid()"><i class="fas fa-ban me-1"></i>Void Order</button>
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const CSRF = '{{ csrf_token() }}';
const ROUTES = {
    getOrder:  '{{ route("admin.pos.get-order", ["order" => "__OID__"]) }}',
    voidOrder: '{{ route("admin.pos.void-order", ["order" => "__OID__"]) }}',
};

async function viewReceipt(orderId) {
    const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
    document.getElementById('receiptBody').innerHTML = '<div class="text-center p-4 text-muted">Loading...</div>';
    modal.show();

    const res  = await fetch(ROUTES.getOrder.replace('__OID__', orderId));
    const data = await res.json();
    const o = data.order;
    const s = data.store;

    let rows = o.items.map(i => `
        <div style="display:flex;justify-content:space-between;margin-bottom:3px;">
            <span style="flex:1;">${i.product_name}</span>
            <span style="width:30px;text-align:center;">x${i.quantity}</span>
            <span style="width:70px;text-align:right;">${s.currency}${i.line_total}</span>
        </div>
    `).join('');

    document.getElementById('receiptBody').innerHTML = `
        <div style="padding:16px;">
            <div style="text-align:center;margin-bottom:10px;">
                <strong style="font-size:14px;text-transform:uppercase;">${s.name}</strong>
                ${s.address ? `<div style="font-size:11px;color:#555;">${s.address}</div>` : ''}
            </div>
            <hr style="border-top:1px dashed #ccc;">
            <div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:3px;"><span>Order #</span><strong>${o.order_number}</strong></div>
            <div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:3px;"><span>Date</span><span>${o.created_at}</span></div>
            ${o.customer_name ? `<div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:3px;"><span>Customer</span><span>${o.customer_name}</span></div>` : ''}
            <hr style="border-top:1px dashed #ccc;">
            ${rows}
            <hr style="border-top:1px dashed #ccc;">
            <div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:3px;"><span>Subtotal</span><span>${s.currency}${o.subtotal}</span></div>
            ${parseFloat(o.discount_amount) > 0 ? `<div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:3px;"><span>Discount</span><span>-${s.currency}${o.discount_amount}</span></div>` : ''}
            ${parseFloat(o.tax_amount) > 0 ? `<div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:3px;"><span>Tax</span><span>${s.currency}${o.tax_amount}</span></div>` : ''}
            <div style="display:flex;justify-content:space-between;font-size:14px;font-weight:900;margin-top:6px;padding-top:6px;border-top:1px dashed #ccc;"><span>TOTAL</span><span>${s.currency}${o.total}</span></div>
            <div style="display:flex;justify-content:space-between;font-size:11px;margin-top:3px;"><span>Paid (${o.payment_method.toUpperCase()})</span><span>${s.currency}${o.amount_paid}</span></div>
            ${parseFloat(o.change_due) > 0 ? `<div style="display:flex;justify-content:space-between;font-size:11px;"><span>Change</span><span>${s.currency}${o.change_due}</span></div>` : ''}
            <hr style="border-top:1px dashed #ccc;">
            <div style="text-align:center;font-size:11px;color:#555;">Thank you for your purchase!</div>
        </div>
    `;
}

function voidOrder(orderId) {
    document.getElementById('voidId').value = orderId;
    document.getElementById('voidReason').value = '';
    new bootstrap.Modal(document.getElementById('voidModal')).show();
}

async function submitVoid() {
    const orderId = document.getElementById('voidId').value;
    const reason  = document.getElementById('voidReason').value.trim();
    if (!reason) { alert('Please enter a reason'); return; }

    const url = ROUTES.voidOrder.replace('__OID__', orderId);
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ reason }),
    });
    const data = await res.json();
    if (data.success) {
        bootstrap.Modal.getInstance(document.getElementById('voidModal')).hide();
        location.reload();
    } else {
        alert(data.message || 'Error voiding order');
    }
}
</script>
@endpush
@endsection
