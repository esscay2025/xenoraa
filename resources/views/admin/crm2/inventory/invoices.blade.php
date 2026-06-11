@extends('layouts.admin')
@section('title', 'Invoices')
@section('page-title', 'Invoices')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-file-invoice-dollar"></i> Invoices</h1>
      <p class="crm2-subtitle">Manage your invoices.</p>
    </div>
    <a href="{{ route('admin.crm2.inventory.invoices.create') }}" class="crm2-btn crm2-btn-primary">
      <i class="fas fa-plus"></i> New Invoice
    </a>
  </div>
  @if(session('success'))
    <div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead>
          <tr>
            <th>Invoice #</th>
            <th>Subject</th>
            <th>Account</th>
            <th>Status</th>
            <th>Grand Total</th>
            <th>Amount Paid</th>
            <th>Due Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $item)
          <tr style="cursor:pointer;" onclick="window.location='{{ route('admin.crm2.inventory.invoices.show', $item->id) }}'">
            <td><strong>{{ $item->invoice_number ?? 'INV-'.$item->id }}</strong></td>
            <td>
              <a href="{{ route('admin.crm2.inventory.invoices.show', $item->id) }}"
                 style="color:var(--accent);font-weight:600;text-decoration:none;"
                 onclick="event.stopPropagation()">
                {{ $item->subject ?? '—' }}
              </a>
            </td>
            <td>{{ $item->account?->name ?? $item->account_name ?? '—' }}</td>
            <td>
              @php
                $sc = match($item->status ?? 'draft') {
                  'sent'      => 'blue',
                  'paid'      => 'green',
                  'overdue'   => 'red',
                  'cancelled' => 'red',
                  'partial'   => 'orange',
                  default     => 'gray',
                };
              @endphp
              <span class="crm2-badge status-{{ $item->status ?? 'draft' }}">{{ ucfirst($item->status ?? 'Draft') }}</span>
            </td>
            <td>{{ ($item->grand_total ?? $item->total ?? $item->total_amount) ? '₹'.number_format($item->grand_total ?? $item->total ?? $item->total_amount, 0) : '—' }}</td>
            <td>{{ $item->amount_paid ? '₹'.number_format($item->amount_paid, 0) : '—' }}</td>
            <td>{{ $item->due_date ? \Carbon\Carbon::parse($item->due_date)->format('d M Y') : '—' }}</td>
            <td onclick="event.stopPropagation()">
              <a href="{{ route('admin.crm2.inventory.invoices.show', $item->id) }}"
                 class="crm2-icon-btn view" title="View"><i class="fas fa-eye"></i></a>
              <a href="{{ route('admin.crm2.inventory.invoices.edit', $item->id) }}"
                 class="crm2-icon-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
              <form method="POST"
                    action="{{ route('admin.crm2.inventory.destroy', ['type'=>'invoices','id'=>$item->id]) }}"
                    onsubmit="return confirm('Delete this invoice?')"
                    style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" class="crm2-icon-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8">
              <div class="crm2-empty"><i class="fas fa-file-invoice-dollar"></i><p>No invoices found.</p></div>
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
