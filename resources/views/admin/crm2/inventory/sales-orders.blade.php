@extends('layouts.admin')
@section('title', 'Sales Orders')
@section('page-title', 'Sales Orders')
@section('content')
<style>
.crm2-table tbody tr { cursor: pointer; transition: background .12s; }
.crm2-table tbody tr:hover td { background: var(--bg-hover); }
.crm2-table .actions-cell { white-space: nowrap; }
.so-subject-link { color: var(--accent); text-decoration: none; font-weight: 600; }
.so-subject-link:hover { text-decoration: underline; }
</style>
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-shopping-cart"></i> Sales Orders</h1>
      <p class="crm2-subtitle">Manage your sales orders.</p>
    </div>
    <a href="{{ route('admin.crm2.inventory.sales-orders.create') }}" class="crm2-btn crm2-btn-primary">
      <i class="fas fa-plus"></i> New Sales Order
    </a>
  </div>

  @if(session('success'))
    <div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif

  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead>
        <tr>
          <th>SO Number</th>
          <th>Subject</th>
          <th>Account</th>
          <th>Status</th>
          <th>Grand Total</th>
          <th>Delivery Date</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
        <tr onclick="window.location='{{ route('admin.crm2.inventory.sales-orders.show', $item->id) }}'">
          <td>{{ $item->so_number ?? '#'.$item->id }}</td>
          <td>
            <a href="{{ route('admin.crm2.inventory.sales-orders.show', $item->id) }}"
               class="so-subject-link"
               onclick="event.stopPropagation()">
              {{ $item->subject }}
            </a>
          </td>
          <td>{{ $item->account?->name ?? '—' }}</td>
          <td>
            @php
              $statusColors = [
                'draft'     => 'status-draft',
                'approved'  => 'status-active',
                'packing'   => 'status-pending',
                'shipped'   => 'status-won',
                'delivered' => 'status-active',
                'cancelled' => 'status-lost',
              ];
            @endphp
            <span class="crm2-badge {{ $statusColors[$item->status] ?? 'status-new' }}">
              {{ \App\Models\CrmSalesOrder::STATUSES[$item->status] ?? ucfirst($item->status ?? 'Draft') }}
            </span>
          </td>
          <td>{{ $item->grand_total ? '₹' . number_format($item->grand_total, 2) : '—' }}</td>
          <td>{{ $item->delivery_date ? $item->delivery_date->format('d M Y') : '—' }}</td>
          <td>{{ $item->created_at->format('d M Y') }}</td>
          <td class="actions-cell" onclick="event.stopPropagation()">
            <a href="{{ route('admin.crm2.inventory.sales-orders.show', $item->id) }}"
               class="crm2-icon-btn view" title="View">
              <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('admin.crm2.inventory.sales-orders.edit', $item->id) }}"
               class="crm2-icon-btn edit" title="Edit">
              <i class="fas fa-edit"></i>
            </a>
            <form method="POST"
                  action="{{ route('admin.crm2.inventory.destroy', ['type'=>'sales_orders','id'=>$item->id]) }}"
                  onsubmit="return confirm('Delete this sales order?')"
                  style="display:inline">
              @csrf @method('DELETE')
              <button type="submit" class="crm2-icon-btn delete" title="Delete">
                <i class="fas fa-trash"></i>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="crm2-empty">
              <i class="fas fa-shopping-cart"></i>
              <p>No sales orders found. <a href="{{ route('admin.crm2.inventory.sales-orders.create') }}">Create the first one</a>.</p>
            </div>
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
</div>
@endsection
