@extends('layouts.admin')
@section('title', 'Edit Account')
@section('page-title', 'Edit Account')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-building"></i> Edit Account</h1><p class="crm2-subtitle">Update the details below and save.</p></div>
    <a href="{{ route('admin.crm2.sales.accounts') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Accounts</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.sales.update', ['type'=>'account','id'=>$item->id]) }}">@csrf @method('PATCH')
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Name *</label><input type="text" name="name" class="crm2-input" value="{{ old('name', $item->name) }}" required autofocus></div>
        <div class="form-group"><label>Type</label><select name="type" class="crm2-select"><option value="prospect" {{ $item->type=='prospect'?'selected':'' }}>Prospect</option><option value="customer" {{ $item->type=='customer'?'selected':'' }}>Customer</option><option value="partner" {{ $item->type=='partner'?'selected':'' }}>Partner</option><option value="competitor" {{ $item->type=='competitor'?'selected':'' }}>Competitor</option></select></div>
        <div class="form-group"><label>Industry</label><input type="text" name="industry" class="crm2-input" value="{{ old('industry', $item->industry) }}"></div>
        <div class="form-group"><label>Phone</label><input type="text" name="phone" class="crm2-input" value="{{ old('phone', $item->phone) }}"></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" class="crm2-input" value="{{ old('email', $item->email) }}"></div>
        <div class="form-group"><label>Website</label><input type="url" name="website" class="crm2-input" value="{{ old('website', $item->website) }}" placeholder="https://"></div>
        <div class="form-group"><label>Annual Revenue (₹)</label><input type="number" name="annual_revenue" class="crm2-input" value="{{ old('annual_revenue', $item->annual_revenue) }}" step="0.01" min="0"></div>
        <div class="form-group"><label>Employees</label><input type="number" name="employees" class="crm2-input" value="{{ old('employees', $item->employees) }}" min="0"></div>
        <div class="form-group"><label>Status</label><select name="status" class="crm2-select"><option value="active" {{ $item->status=='active'?'selected':'' }}>Active</option><option value="inactive" {{ $item->status=='inactive'?'selected':'' }}>Inactive</option></select></div>
        <div class="form-group"><label>City</label><input type="text" name="city" class="crm2-input" value="{{ old('city', $item->city) }}"></div>
        <div class="form-group"><label>Country</label><input type="text" name="country" class="crm2-input" value="{{ old('country', $item->country) }}"></div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="4">{{ old('notes', $item->notes) }}</textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update Account</button>
        <a href="{{ route('admin.crm2.sales.accounts') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
