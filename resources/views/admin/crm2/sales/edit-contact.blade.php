@extends('layouts.admin')
@section('title', 'Edit Contact')
@section('page-title', 'Edit Contact')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-address-book"></i> Edit Contact</h1><p class="crm2-subtitle">Update the details below and save.</p></div>
    <a href="{{ route('admin.crm2.sales.contacts') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Contacts</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.sales.update', ['type'=>'contact','id'=>$item->id]) }}">@csrf @method('PATCH')
      <div class="crm2-form-grid">
        <div class="form-group"><label>First Name *</label><input type="text" name="first_name" class="crm2-input" value="{{ old('first_name', $item->first_name) }}" required autofocus></div>
        <div class="form-group"><label>Last Name</label><input type="text" name="last_name" class="crm2-input" value="{{ old('last_name', $item->last_name) }}"></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" class="crm2-input" value="{{ old('email', $item->email) }}"></div>
        <div class="form-group"><label>Phone</label><input type="text" name="phone" class="crm2-input" value="{{ old('phone', $item->phone) }}"></div>
        <div class="form-group"><label>Job Title</label><input type="text" name="job_title" class="crm2-input" value="{{ old('job_title', $item->job_title) }}"></div>
        <div class="form-group"><label>Department</label><input type="text" name="department" class="crm2-input" value="{{ old('department', $item->department) }}"></div>
        <div class="form-group"><label>Account</label><select name="account_id" class="crm2-select"><option value="">— None —</option>@foreach($accounts as $a)<option value="{{ $a->id }}" {{ $item->account_id==$a->id?'selected':'' }}>{{ $a->name }}</option>@endforeach</select></div>
        <div class="form-group"><label>Status</label><select name="status" class="crm2-select"><option value="active" {{ $item->status=='active'?'selected':'' }}>Active</option><option value="inactive" {{ $item->status=='inactive'?'selected':'' }}>Inactive</option></select></div>
        <div class="form-group"><label>City</label><input type="text" name="city" class="crm2-input" value="{{ old('city', $item->city) }}"></div>
        <div class="form-group"><label>Country</label><input type="text" name="country" class="crm2-input" value="{{ old('country', $item->country) }}"></div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="4">{{ old('notes', $item->notes) }}</textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update Contact</button>
        <a href="{{ route('admin.crm2.sales.contacts') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
