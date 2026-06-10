@extends('layouts.admin')
@section('title', 'New Sales Order')
@section('page-title', 'New Sales Order')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-shopping-cart"></i> New Sales Order</h1><p class="crm2-subtitle">Create a new sales order.</p></div>
    <a href="{{ route('admin.crm2.inventory.sales-orders') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Sales Orders</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.inventory.store') }}">@csrf
      <input type="hidden" name="_type" value="sales_orders">
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Subject *</label><input type="text" name="subject" class="crm2-input" required autofocus></div>
        <div class="form-group"><label>Account</label><select name="account_id" class="crm2-select"><option value="">— None —</option>@foreach($accounts_list as $a)<option value="{{ $a->id }}">{{ $a->name }}</option>@endforeach</select></div>
        <div class="form-group"><label>Status</label><select name="status" class="crm2-select"><option value="draft">Draft</option><option value="confirmed">Confirmed</option><option value="delivered">Delivered</option><option value="cancelled">Cancelled</option></select></div>
        <div class="form-group"><label>Due Date</label><input type="date" name="due_date" class="crm2-input"></div>
        <div class="form-group"><label>Total Amount (₹)</label><input type="number" name="total_amount" class="crm2-input" step="0.01" min="0"></div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="4"></textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Sales Order</button>
        <a href="{{ route('admin.crm2.inventory.sales-orders') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
