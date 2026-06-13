@extends('layouts.admin')
@section('title', 'New Booking')
@section('page-title', 'New Booking')
@push('styles')
<style>
/* ── Sticky Top Action Bar ─────────────────────────────────────── */
.xn-sticky-bar {
    position: fixed;
    top: 60px;
    left: var(--rail-width, 60px);
    right: 0;
    z-index: 120;
    background: var(--bg-card, #fff);
    border-bottom: 2px solid var(--accent, #6366f1);
    padding: .75rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    box-shadow: 0 3px 12px rgba(0,0,0,.10);
    flex-wrap: wrap;
    will-change: transform;
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    transition: left 0.22s cubic-bezier(0.4,0,0.2,1);
}
body.xn-panel-open .xn-sticky-bar {
    left: calc(var(--rail-width, 60px) + var(--panel-width, 220px));
}
.xn-sticky-spacer { height: 64px; }
.xn-sticky-title {
    display: flex; align-items: center; gap: .5rem;
    font-size: .95rem; font-weight: 700; color: var(--text-primary, #1a1a2e);
}
.xn-sticky-title i { color: var(--accent, #6366f1); }
.xn-sticky-actions { display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; }
.xn-sticky-btn {
    padding: .45rem 1.1rem; border-radius: 7px; font-size: .82rem; font-weight: 600;
    cursor: pointer; border: none; display: inline-flex; align-items: center; gap: .35rem;
    transition: all .18s; text-decoration: none;
}
.xn-sticky-btn-primary { background: var(--accent, #6366f1); color: #fff; }
.xn-sticky-btn-primary:hover { opacity: .88; color: #fff; }
.xn-sticky-btn-outline {
    background: var(--bg-card, #fff); color: var(--accent, #6366f1);
    border: 1px solid var(--accent, #6366f1);
}
.xn-sticky-btn-outline:hover { background: var(--accent, #6366f1); color: #fff; }
.xn-sticky-btn-ghost {
    background: transparent; color: var(--text-secondary, #64748b);
    border: 1px solid var(--border, #e2e8f0);
}
.xn-sticky-btn-ghost:hover { background: var(--bg-primary, #f8fafc); }
</style>
@endpush
@section('content')
<div class="crm2-page">
  {{-- Sticky Top Action Bar --}}
  <div class="xn-sticky-bar">
    <div class="xn-sticky-title">
      <i class="fas fa-calendar-check"></i>
      Create New Booking
    </div>
    <div class="xn-sticky-actions">
      <a href="{{ route('admin.crm2.services.bookings') }}" class="xn-sticky-btn xn-sticky-btn-ghost">
        <i class="fas fa-arrow-left"></i> Cancel
      </a>
      <button type="submit" form="bookingCreateForm" class="xn-sticky-btn xn-sticky-btn-primary">
        <i class="fas fa-save"></i> Save Booking
      </button>
    </div>
  </div>
  <div class="xn-sticky-spacer"></div>

  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-calendar-check"></i> New Booking</h1><p class="crm2-subtitle">Schedule a new service booking.</p></div>
    <a href="{{ route('admin.crm2.services.bookings') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Bookings</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form id="bookingCreateForm" method="POST" action="{{ route('admin.crm2.services.store') }}">@csrf
      <input type="hidden" name="_type" value="booking">
      <div class="crm2-form-grid">
        <div class="form-group"><label>Client Name *</label><input type="text" name="client_name" class="crm2-input" required autofocus></div>
        <div class="form-group"><label>Client Email</label><input type="email" name="client_email" class="crm2-input"></div>
        <div class="form-group"><label>Client Phone</label><input type="text" name="client_phone" class="crm2-input"></div>
        <div class="form-group"><label>Service</label><select name="service_id" class="crm2-select"><option value="">— None —</option>@foreach($services_list as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select></div>
        <div class="form-group"><label>Booking Date & Time</label><input type="datetime-local" name="booking_date" class="crm2-input"></div>
        <div class="form-group"><label>Status</label><select name="status" class="crm2-select"><option value="pending">Pending</option><option value="confirmed">Confirmed</option><option value="completed">Completed</option><option value="cancelled">Cancelled</option></select></div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="4"></textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Booking</button>
        <a href="{{ route('admin.crm2.services.bookings') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
