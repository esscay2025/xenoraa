@extends('layouts.admin')
@section('title', 'New Account')
@section('page-title', 'New Account')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-building"></i> New Account</h1><p class="crm2-subtitle">Add a new company or organisation.</p></div>
    <a href="{{ route('admin.crm2.sales.accounts') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Accounts</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.sales.store') }}">@csrf
      <input type="hidden" name="_type" value="account">
      <div class="crm2-form-grid">
        <div class="form-group"><label>Name *</label><input type="text" name="name" class="crm2-input" required autofocus></div>
        <div class="form-group"><label>Type *</label><select name="type" class="crm2-select" required><option value="prospect">Prospect</option><option value="customer">Customer</option><option value="partner">Partner</option><option value="vendor">Vendor</option></select></div>
        <div class="form-group"><label>Industry</label><input type="text" name="industry" class="crm2-input"></div>
        <div class="form-group"><label>Website</label><input type="url" name="website" class="crm2-input" placeholder="https://"></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" class="crm2-input"></div>
        <div class="form-group"><label>Phone</label><input type="text" name="phone" class="crm2-input"></div>
        <div class="form-group"><label>City</label><input type="text" name="city" class="crm2-input"></div>
        <div class="form-group"><label>Country</label><input type="text" name="country" class="crm2-input"></div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="4"></textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Account</button>
        <a href="{{ route('admin.crm2.sales.accounts') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
