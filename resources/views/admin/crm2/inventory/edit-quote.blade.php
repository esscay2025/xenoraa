@extends('layouts.admin')
@section('title', 'Edit Quote')
@section('page-title', 'Edit Quote')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-file-alt"></i> Edit Quote</h1><p class="crm2-subtitle">Update the details below and save.</p></div>
    <a href="{{ route('admin.crm2.inventory.quotes') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Quotes</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.inventory.update', ['type'=>'quotes','id'=>$item->id]) }}">@csrf @method('PATCH')
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Subject *</label><input type="text" name="subject" class="crm2-input" value="{{ old('subject', $item->subject) }}" required autofocus></div>
        <div class="form-group"><label>Account</label><select name="account_id" class="crm2-select"><option value="">— None —</option>@foreach($accounts_list as $a)<option value="{{ $a->id }}" {{ $item->account_id==$a->id?'selected':'' }}>{{ $a->name }}</option>@endforeach</select></div>
        <div class="form-group"><label>Stage</label><select name="stage" class="crm2-select"><option value="draft" {{ $item->stage=='draft'?'selected':'' }}>Draft</option><option value="sent" {{ $item->stage=='sent'?'selected':'' }}>Sent</option><option value="accepted" {{ $item->stage=='accepted'?'selected':'' }}>Accepted</option><option value="rejected" {{ $item->stage=='rejected'?'selected':'' }}>Rejected</option></select></div>
        <div class="form-group"><label>Valid Until</label><input type="date" name="valid_until" class="crm2-input" value="{{ old('valid_until', $item->valid_until ? \Carbon\Carbon::parse($item->valid_until)->format('Y-m-d') : '') }}"></div>
        <div class="form-group"><label>Subtotal (₹)</label><input type="number" name="subtotal" class="crm2-input" value="{{ old('subtotal', $item->subtotal) }}" step="0.01" min="0"></div>
        <div class="form-group"><label>Discount (₹)</label><input type="number" name="discount_amount" class="crm2-input" value="{{ old('discount_amount', $item->discount_amount) }}" step="0.01" min="0"></div>
        <div class="form-group"><label>Tax (₹)</label><input type="number" name="tax_amount" class="crm2-input" value="{{ old('tax_amount', $item->tax_amount) }}" step="0.01" min="0"></div>
        <div class="form-group"><label>Total (₹)</label><input type="number" name="total" class="crm2-input" value="{{ old('total', $item->total) }}" step="0.01" min="0"></div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="3">{{ old('notes', $item->notes) }}</textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update Quote</button>
        <a href="{{ route('admin.crm2.inventory.quotes') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
