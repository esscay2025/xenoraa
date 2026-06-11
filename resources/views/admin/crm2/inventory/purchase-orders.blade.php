@extends('layouts.admin')
@section('content')
<div class="crm2-page">
  <div class="crm2-page-header">
    <h1 class="crm2-page-title"><i class="fas fa-truck"></i> Purchase Orders</h1>
    <a href="{{ route('admin.crm2.inventory.purchase-orders.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Purchase Order</a>
  </div>
  <div class="crm2-card">
    <table class="crm2-table">
      <thead>
        <tr>
          <th>PO Number</th>
          <th>Subject</th>
          <th>Vendor</th>
          <th>Status</th>
          <th>Grand Total</th>
          <th>Expected Delivery</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
        <tr style="cursor:pointer;" onclick="window.location='{{ route('admin.crm2.inventory.purchase-orders.show', $item->id) }}'">
          <td><strong>{{ $item->po_number ?? 'PO-'.$item->id }}</strong></td>
          <td>
            <a href="{{ route('admin.crm2.inventory.purchase-orders.show', $item->id) }}"
               style="color:var(--accent);font-weight:600;text-decoration:none;"
               onclick="event.stopPropagation()">
              {{ $item->subject ?? '—' }}
            </a>
          </td>
          <td>{{ $item->vendor?->name ?? $item->vendor_name ?? '—' }}</td>
          <td>
            <span class="crm2-badge status-{{ $item->status ?? 'draft' }}">
              {{ \App\Models\CrmPurchaseOrder::STATUSES[$item->status] ?? ucfirst($item->status ?? 'Draft') }}
            </span>
          </td>
          <td>{{ $item->grand_total ? '₹'.number_format($item->grand_total, 0) : ($item->total ? '₹'.number_format($item->total, 0) : '—') }}</td>
          <td>{{ $item->expected_delivery ? $item->expected_delivery->format('d M Y') : '—' }}</td>
          <td onclick="event.stopPropagation()">
            <a href="{{ route('admin.crm2.inventory.purchase-orders.show', $item->id) }}"
               class="crm2-icon-btn view" title="View"><i class="fas fa-eye"></i></a>
            <a href="{{ route('admin.crm2.inventory.purchase-orders.edit', $item->id) }}"
               class="crm2-icon-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
            <form method="POST"
                  action="{{ route('admin.crm2.inventory.destroy', ['type'=>'purchase_orders','id'=>$item->id]) }}"
                  onsubmit="return confirm('Delete this purchase order?')"
                  style="display:inline">
              @csrf @method('DELETE')
              <button type="submit" class="crm2-icon-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7">
            <div class="crm2-empty"><i class="fas fa-truck"></i><p>No purchase orders found.</p></div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($items->hasPages())
    <div class="crm2-pagination">{{ $items->links() }}</div>
  @endif
</div>
@endsection
