@extends('layouts.admin')
@section('title', 'Edit Vendor')
@section('page-title', 'Edit Vendor')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-store"></i> Edit Vendor</h1><p class="crm2-subtitle">Update the details below and save.</p></div>
    <a href="{{ route('admin.crm2.inventory.vendors') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Vendors</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.inventory.update', ['type'=>'vendors','id'=>\$item->id]) }}">@csrf @method('PATCH')
      <div class="crm2-form-grid">
        <div class="form-group"><label>Name *</label><input type="text" name="name" class="crm2-input" value="{{ old('name', $item->name) }}" required autofocus></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" class="crm2-input" value="{{ old('email', $item->email) }}"></div>
        <div class="form-group"><label>Phone</label><input type="text" name="phone" class="crm2-input" value="{{ old('phone', $item->phone) }}"></div>
        <div class="form-group"><label>Website</label><input type="url" name="website" class="crm2-input" value="{{ old('website', $item->website) }}" placeholder="https://"></div>
        <div class="form-group"><label>City</label><input type="text" name="city" class="crm2-input" value="{{ old('city', $item->city) }}"></div>
        <div class="form-group"><label>Country</label><input type="text" name="country" class="crm2-input" value="{{ old('country', $item->country) }}"></div>
        <div class="form-group"><label>Active</label><select name="is_active" class="crm2-select"><option value="1" {{ $item->is_active?'selected':'' }}>Yes</option><option value="0" {{ !$item->is_active?'selected':'' }}>No</option></select></div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="4">{{ old('notes', $item->notes) }}</textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update Vendor</button>
        <a href="{{ route('admin.crm2.inventory.vendors') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
