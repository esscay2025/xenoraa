@extends('layouts.admin')
@section('title', 'Edit Forecast')
@section('page-title', 'Edit Forecast')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-chart-line"></i> Edit Forecast</h1><p class="crm2-subtitle">Update the details below and save.</p></div>
    <a href="{{ route('admin.crm2.sales.forecasts') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Forecasts</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.sales.update', ['type'=>'forecast','id'=>$item->id]) }}">@csrf @method('PATCH')
      <div class="crm2-form-grid">
        <div class="form-group"><label>Year *</label><input type="number" name="year" class="crm2-input" value="{{ old('year', $item->year) }}" required autofocus></div>
        <div class="form-group"><label>Quarter *</label><select name="quarter" class="crm2-select" required><option value="1" {{ $item->quarter==1?'selected':'' }}>Q1 (Jan–Mar)</option><option value="2" {{ $item->quarter==2?'selected':'' }}>Q2 (Apr–Jun)</option><option value="3" {{ $item->quarter==3?'selected':'' }}>Q3 (Jul–Sep)</option><option value="4" {{ $item->quarter==4?'selected':'' }}>Q4 (Oct–Dec)</option></select></div>
        <div class="form-group"><label>Target Amount (₹)</label><input type="number" name="target_amount" class="crm2-input" value="{{ old('target_amount', $item->target_amount) }}" step="0.01" min="0"></div>
        <div class="form-group"><label>Achieved Amount (₹)</label><input type="number" name="achieved_amount" class="crm2-input" value="{{ old('achieved_amount', $item->achieved_amount) }}" step="0.01" min="0"></div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="4">{{ old('notes', $item->notes) }}</textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update Forecast</button>
        <a href="{{ route('admin.crm2.sales.forecasts') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
