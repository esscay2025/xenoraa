@extends('layouts.admin')
@section('title', 'New Lead')
@section('page-title', 'New Lead')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-user-tag"></i> New Lead</h1>
      <p class="crm2-subtitle">Add a new sales lead to your pipeline.</p>
    </div>
    <a href="{{ route('admin.crm2.sales.leads') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Leads</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><i class="fas fa-exclamation-circle"></i><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.sales.store') }}">@csrf
      <input type="hidden" name="_type" value="lead">
      <div class="crm2-form-grid">
        <div class="form-group"><label>Name *</label><input type="text" name="name" class="crm2-input" required autofocus></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" class="crm2-input"></div>
        <div class="form-group"><label>Phone</label><input type="text" name="phone" class="crm2-input"></div>
        <div class="form-group"><label>Company</label><input type="text" name="company" class="crm2-input"></div>
        <div class="form-group"><label>Source</label>
          <select name="source" class="crm2-select"><option value="manual">Manual</option><option value="website">Website</option><option value="referral">Referral</option><option value="linkedin">LinkedIn</option><option value="ai_chatbot">AI Chatbot</option><option value="other">Other</option></select>
        </div>
        <div class="form-group"><label>Status</label>
          <select name="status" class="crm2-select"><option value="new">New</option><option value="contacted">Contacted</option><option value="qualified">Qualified</option><option value="proposal">Proposal</option><option value="won">Won</option><option value="lost">Lost</option></select>
        </div>
        <div class="form-group"><label>Deal Value (₹)</label><input type="number" name="deal_value" class="crm2-input" step="0.01" min="0"></div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="4" placeholder="Additional notes about this lead..."></textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Lead</button>
        <a href="{{ route('admin.crm2.sales.leads') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
