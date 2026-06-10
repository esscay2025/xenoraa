@extends('layouts.admin')
@section('title', 'Edit Booking')
@section('page-title', 'Edit Booking')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-calendar-check"></i> Edit Booking</h1><p class="crm2-subtitle">Update the details below and save.</p></div>
    <a href="{{ route('admin.crm2.services.bookings') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Bookings</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.services.update', ['type'=>'booking','id'=>\$item->id]) }}">@csrf @method('PATCH')
      <div class="crm2-form-grid">
        <div class="form-group"><label>Client Name *</label><input type="text" name="client_name" class="crm2-input" value="{{ old('client_name', $item->client_name) }}" required autofocus></div>
        <div class="form-group"><label>Client Email</label><input type="email" name="client_email" class="crm2-input" value="{{ old('client_email', $item->client_email) }}"></div>
        <div class="form-group"><label>Client Phone</label><input type="text" name="client_phone" class="crm2-input" value="{{ old('client_phone', $item->client_phone) }}"></div>
        <div class="form-group"><label>Service</label><select name="service_id" class="crm2-select"><option value="">— None —</option>@foreach($services_list as $s)<option value="{{ $s->id }}" {{ $item->service_id==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach</select></div>
        <div class="form-group"><label>Booking Date & Time</label><input type="datetime-local" name="booking_date" class="crm2-input" value="{{ old('booking_date', $item->booking_date ? $item->booking_date->format('Y-m-d\TH:i') : '') }}"></div>
        <div class="form-group"><label>Status</label><select name="status" class="crm2-select"><option value="pending" {{ $item->status=='pending'?'selected':'' }}>Pending</option><option value="confirmed" {{ $item->status=='confirmed'?'selected':'' }}>Confirmed</option><option value="completed" {{ $item->status=='completed'?'selected':'' }}>Completed</option><option value="cancelled" {{ $item->status=='cancelled'?'selected':'' }}>Cancelled</option></select></div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="4">{{ old('notes', $item->notes) }}</textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update Booking</button>
        <a href="{{ route('admin.crm2.services.bookings') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
