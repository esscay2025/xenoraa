@extends('layouts.admin')
@section('title', 'New Purchase Order')
@section('page-title', 'New Purchase Order')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-truck"></i> New Purchase Order</h1><p class="crm2-subtitle">Create a new purchase order.</p></div>
    <a href="{{ route('admin.crm2.inventory.purchase-orders') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Purchase Orders</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.inventory.store') }}">@csrf
      <input type="hidden" name="_type" value="purchase_orders">
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Subject *</label><input type="text" name="subject" class="crm2-input" required autofocus></div>
        <div class="form-group"><label>Vendor</label><select name="vendor_id" class="crm2-select"><option value="">— None —</option>@foreach($vendors_list as $v)<option value="{{ $v->id }}">{{ $v->name }}</option>@endforeach</select></div>
        <div class="form-group"><label>Status</label><select name="status" class="crm2-select"><option value="draft">Draft</option><option value="sent">Sent</option><option value="received">Received</option><option value="cancelled">Cancelled</option></select></div>
        <div class="form-group"><label>Due Date</label><input type="date" name="due_date" class="crm2-input"></div>
        <div class="form-group"><label>Total Amount (₹)</label><input type="number" name="total_amount" class="crm2-input" step="0.01" min="0"></div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="4"></textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Purchase Order</button>
        <a href="{{ route('admin.crm2.inventory.purchase-orders') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
