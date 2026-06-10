@extends('layouts.admin')
@section('title', 'Edit Deal')
@section('page-title', 'Edit Deal')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-handshake"></i> Edit Deal</h1><p class="crm2-subtitle">Update the details below and save.</p></div>
    <a href="{{ route('admin.crm2.sales.deals') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Deals</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.sales.update', ['type'=>'deal','id'=>$item->id]) }}">@csrf @method('PATCH')
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Title *</label><input type="text" name="title" class="crm2-input" value="{{ old('title', $item->title) }}" required autofocus></div>
        <div class="form-group"><label>Account</label><select name="account_id" class="crm2-select"><option value="">— None —</option>@foreach($accounts as $a)<option value="{{ $a->id }}" {{ $item->account_id==$a->id?'selected':'' }}>{{ $a->name }}</option>@endforeach</select></div>
        <div class="form-group"><label>Contact</label><select name="contact_id" class="crm2-select"><option value="">— None —</option>@foreach($contacts as $c)<option value="{{ $c->id }}" {{ $item->contact_id==$c->id?'selected':'' }}>{{ $c->first_name }} {{ $c->last_name }}</option>@endforeach</select></div>
        <div class="form-group"><label>Stage</label><select name="stage" class="crm2-select"><option value="prospecting" {{ $item->stage=='prospecting'?'selected':'' }}>Prospecting</option><option value="qualification" {{ $item->stage=='qualification'?'selected':'' }}>Qualification</option><option value="proposal" {{ $item->stage=='proposal'?'selected':'' }}>Proposal</option><option value="negotiation" {{ $item->stage=='negotiation'?'selected':'' }}>Negotiation</option><option value="closed_won" {{ $item->stage=='closed_won'?'selected':'' }}>Closed Won</option><option value="closed_lost" {{ $item->stage=='closed_lost'?'selected':'' }}>Closed Lost</option></select></div>
        <div class="form-group"><label>Value (₹)</label><input type="number" name="value" class="crm2-input" value="{{ old('value', $item->value) }}" step="0.01" min="0"></div>
        <div class="form-group"><label>Probability (%)</label><input type="number" name="probability" class="crm2-input" value="{{ old('probability', $item->probability) }}" min="0" max="100"></div>
        <div class="form-group"><label>Expected Close Date</label><input type="date" name="expected_close" class="crm2-input" value="{{ old('expected_close', $item->expected_close ? \Carbon\Carbon::parse($item->expected_close)->format('Y-m-d') : '') }}"></div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="4">{{ old('notes', $item->notes) }}</textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update Deal</button>
        <a href="{{ route('admin.crm2.sales.deals') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
