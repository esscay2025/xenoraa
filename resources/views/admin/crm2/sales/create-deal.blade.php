@extends('layouts.admin')
@section('title', 'New Deal')
@section('page-title', 'New Deal')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-funnel-dollar"></i> New Deal</h1><p class="crm2-subtitle">Add a new deal to your pipeline.</p></div>
    <a href="{{ route('admin.crm2.sales.deals') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Deals</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.sales.store') }}">@csrf
      <input type="hidden" name="_type" value="deal">
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Title *</label><input type="text" name="title" class="crm2-input" required autofocus></div>
        <div class="form-group"><label>Value (₹)</label><input type="number" name="value" class="crm2-input" step="0.01" min="0"></div>
        <div class="form-group"><label>Stage *</label><select name="stage" class="crm2-select" required><option value="prospecting">Prospecting</option><option value="qualification">Qualification</option><option value="proposal">Proposal</option><option value="negotiation">Negotiation</option><option value="closed_won">Closed Won</option><option value="closed_lost">Closed Lost</option></select></div>
        <div class="form-group"><label>Account</label><select name="account_id" class="crm2-select"><option value="">— None —</option>@foreach($accounts_list as $a)<option value="{{ $a->id }}">{{ $a->name }}</option>@endforeach</select></div>
        <div class="form-group"><label>Contact</label><select name="contact_id" class="crm2-select"><option value="">— None —</option>@foreach($contacts_list as $c)<option value="{{ $c->id }}">{{ $c->first_name }} {{ $c->last_name }}</option>@endforeach</select></div>
        <div class="form-group"><label>Expected Close Date</label><input type="date" name="expected_close" class="crm2-input"></div>
        <div class="form-group"><label>Probability (%)</label><input type="number" name="probability" class="crm2-input" min="0" max="100" value="10"></div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="4"></textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Deal</button>
        <a href="{{ route('admin.crm2.sales.deals') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
