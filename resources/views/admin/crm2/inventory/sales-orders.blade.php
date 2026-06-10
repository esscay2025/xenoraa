@extends('layouts.admin')
@section('title', 'Sales Orders')
@section('page-title', 'Sales Orders')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-shopping-cart"></i> Sales Orders</h1><p class="crm2-subtitle">Manage your sales orders.</p></div>
    <a href="{{ route('admin.crm2.inventory.sales-orders.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Sales Order</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Order #</th><th>Account</th><th>Total</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($items as $item)
        <tr>
          <td><strong>{{ $item->order_number ?? '#'.$item->id }}</strong></td>
          <td>{{ $item->account_name ?? '—' }}</td>
          <td>{{ $item->total_amount ? '₹'.number_format($item->total_amount,0) : '—' }}</td>
          <td><span class="crm2-badge status-{{ $item->status ?? 'new' }}">{{ ucfirst($item->status ?? 'Draft') }}</span></td>
          <td>{{ $item->created_at->format('d M Y') }}</td>
          <td class="actions-cell">
            <form method="POST" action="{{ route('admin.crm2.inventory.destroy', ['type'=>'sales_orders','id'=>$item->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="6"><div class="crm2-empty"><i class="fas fa-shopping-cart"></i><p>No sales orders found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($items->hasPages())<div class="crm2-pagination">{{ $items->links() }}</div>@endif
  </div>
</div>
@endsection
