@extends('layouts.admin')
@section('title', 'Bookings')
@section('page-title', 'Bookings')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-calendar-check"></i> Bookings</h1><p class="crm2-subtitle">Manage service bookings and appointments.</p></div>
    <a href="{{ route('admin.crm2.services.bookings.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Booking</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Client</th><th>Service</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($bookings as $booking)
        <tr>
          <td><strong>{{ $booking->client_name }}</strong><br><small>{{ $booking->client_email ?? '' }}</small></td>
          <td>{{ $booking->service?->name ?? '—' }}</td>
          <td>{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y H:i') : '—' }}</td>
          <td><span class="crm2-badge status-{{ $booking->status ?? 'new' }}">{{ ucfirst($booking->status ?? 'Pending') }}</span></td>
          <td class="actions-cell">
            <a href="{{ route('admin.crm2.services.bookings.edit', $booking->id) }}" class="crm2-icon-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
            <form method="POST" action="{{ route('admin.crm2.services.destroy', ['type'=>'booking','id'=>$booking->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="5"><div class="crm2-empty"><i class="fas fa-calendar-check"></i><p>No bookings found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($bookings->hasPages())<div class="crm2-pagination">{{ $bookings->links() }}</div>@endif
  </div>
</div>
@endsection
