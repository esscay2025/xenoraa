@extends('layouts.admin')
@section('title', 'New Forecast')
@section('page-title', 'New Forecast')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-chart-pie"></i> New Forecast</h1><p class="crm2-subtitle">Set a quarterly sales target and forecast.</p></div>
    <a href="{{ route('admin.crm2.sales.forecasts') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Forecasts</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.sales.store') }}">@csrf
      <input type="hidden" name="_type" value="forecast">
      <div class="crm2-form-grid">
        <div class="form-group"><label>Year *</label><input type="number" name="year" class="crm2-input" value="{{ date('Y') }}" required></div>
        <div class="form-group"><label>Quarter *</label><select name="quarter" class="crm2-select" required><option value="1">Q1 (Jan–Mar)</option><option value="2">Q2 (Apr–Jun)</option><option value="3">Q3 (Jul–Sep)</option><option value="4">Q4 (Oct–Dec)</option></select></div>
        <div class="form-group"><label>Target Amount (₹) *</label><input type="number" name="target_amount" class="crm2-input" step="0.01" min="0" required></div>
        <div class="form-group"><label>Achieved Amount (₹)</label><input type="number" name="achieved_amount" class="crm2-input" step="0.01" min="0" value="0"></div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="4"></textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Forecast</button>
        <a href="{{ route('admin.crm2.sales.forecasts') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
