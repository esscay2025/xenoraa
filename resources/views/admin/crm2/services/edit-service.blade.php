@extends('layouts.admin')
@section('title', 'Edit Service')
@section('page-title', 'Edit Service')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-concierge-bell"></i> Edit Service</h1><p class="crm2-subtitle">Update the details below and save.</p></div>
    <a href="{{ route('admin.crm2.services.catalog') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Service Catalog</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.services.update', ['type'=>'service','id'=>\$item->id]) }}">@csrf @method('PATCH')
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Name *</label><input type="text" name="name" class="crm2-input" value="{{ old('name', $item->name) }}" required autofocus></div>
        <div class="form-group"><label>Price (₹)</label><input type="number" name="price" class="crm2-input" value="{{ old('price', $item->price) }}" step="0.01" min="0"></div>
        <div class="form-group"><label>Duration (minutes)</label><input type="number" name="duration" class="crm2-input" value="{{ old('duration', $item->duration) }}" min="0"></div>
        <div class="form-group"><label>Active</label><select name="is_active" class="crm2-select"><option value="1" {{ $item->is_active?'selected':'' }}>Yes</option><option value="0" {{ !$item->is_active?'selected':'' }}>No</option></select></div>
        <div class="form-group full"><label>Description</label><textarea name="description" class="crm2-textarea" rows="5">{{ old('description', $item->description) }}</textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update Service</button>
        <a href="{{ route('admin.crm2.services.catalog') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
