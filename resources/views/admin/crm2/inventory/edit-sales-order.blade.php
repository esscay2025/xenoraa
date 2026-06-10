@extends('layouts.admin')
@section('title', 'Edit Sales Order')
@section('page-title', 'Edit Sales Order')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-shopping-cart"></i> Edit Sales Order</h1><p class="crm2-subtitle">Update the details below and save.</p></div>
    <a href="{{ route('admin.crm2.inventory.sales-orders') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Sales Orders</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.inventory.update', ['type'=>'sales_orders','id'=>$item->id]) }}">@csrf @method('PATCH')
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Subject *</label><input type="text" name="subject" class="crm2-input" value="{{ old('subject', $item->subject) }}" required autofocus></div>
        <div class="form-group"><label>Account</label><select name="account_id" class="crm2-select"><option value="">— None —</option>@foreach($accounts_list as $a)<option value="{{ $a->id }}" {{ $item->account_id==$a->id?'selected':'' }}>{{ $a->name }}</option>@endforeach</select></div>
        <div class="form-group"><label>Status</label><select name="status" class="crm2-select"><option value="draft" {{ $item->status=='draft'?'selected':'' }}>Draft</option><option value="confirmed" {{ $item->status=='confirmed'?'selected':'' }}>Confirmed</option><option value="delivered" {{ $item->status=='delivered'?'selected':'' }}>Delivered</option><option value="cancelled" {{ $item->status=='cancelled'?'selected':'' }}>Cancelled</option></select></div>
        <div class="form-group"><label>Delivery Date</label><input type="date" name="delivery_date" class="crm2-input" value="{{ old('delivery_date', $item->delivery_date ? \Carbon\Carbon::parse($item->delivery_date)->format('Y-m-d') : '') }}"></div>
        <div class="form-group"><label>Subtotal (₹)</label><input type="number" name="subtotal" class="crm2-input" value="{{ old('subtotal', $item->subtotal) }}" step="0.01" min="0"></div>
        <div class="form-group"><label>Total (₹)</label><input type="number" name="total" class="crm2-input" value="{{ old('total', $item->total) }}" step="0.01" min="0"></div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="3">{{ old('notes', $item->notes) }}</textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update Sales Order</button>
        <a href="{{ route('admin.crm2.inventory.sales-orders') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
