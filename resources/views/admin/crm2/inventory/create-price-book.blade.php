@extends('layouts.admin')
@section('title', 'New Price Book')
@section('page-title', 'New Price Book')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-tag"></i> New Price Book</h1><p class="crm2-subtitle">Create a new price book for your products and services.</p></div>
    <a href="{{ route('admin.crm2.inventory.price-books') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Price Books</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.inventory.store') }}">@csrf
      <input type="hidden" name="_type" value="price_books">
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Name *</label><input type="text" name="name" class="crm2-input" required autofocus></div>
        <div class="form-group full"><label>Description</label><textarea name="description" class="crm2-textarea" rows="4"></textarea></div>
        <div class="form-group"><label>Currency</label><input type="text" name="currency" class="crm2-input" value="INR" placeholder="INR"></div>
        <div class="form-group"><label>Active</label><select name="is_active" class="crm2-select"><option value="1">Yes</option><option value="0">No</option></select></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Price Book</button>
        <a href="{{ route('admin.crm2.inventory.price-books') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
