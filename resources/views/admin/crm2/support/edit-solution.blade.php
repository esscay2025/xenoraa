@extends('layouts.admin')
@section('title', 'Edit Solution')
@section('page-title', 'Edit Solution')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-lightbulb"></i> Edit Solution</h1><p class="crm2-subtitle">Update the details below and save.</p></div>
    <a href="{{ route('admin.crm2.support.solutions') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Solutions</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.support.update', ['type'=>'solution','id'=>\$item->id]) }}">@csrf @method('PATCH')
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Title *</label><input type="text" name="title" class="crm2-input" value="{{ old('title', $item->title) }}" required autofocus></div>
        <div class="form-group"><label>Status</label><select name="is_published" class="crm2-select"><option value="0" {{ !$item->is_published?'selected':'' }}>Draft</option><option value="1" {{ $item->is_published?'selected':'' }}>Published</option></select></div>
        <div class="form-group full"><label>Content *</label><textarea name="content" class="crm2-textarea" rows="10" required>{{ old('content', $item->content) }}</textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update Solution</button>
        <a href="{{ route('admin.crm2.support.solutions') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
