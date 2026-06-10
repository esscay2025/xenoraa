@extends('layouts.admin')
@section('title', 'New Contact')
@section('page-title', 'New Contact')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-address-book"></i> New Contact</h1><p class="crm2-subtitle">Add a new contact to your CRM.</p></div>
    <a href="{{ route('admin.crm2.sales.contacts') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Contacts</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.sales.store') }}">@csrf
      <input type="hidden" name="_type" value="contact">
      <div class="crm2-form-grid">
        <div class="form-group"><label>First Name *</label><input type="text" name="first_name" class="crm2-input" required autofocus></div>
        <div class="form-group"><label>Last Name</label><input type="text" name="last_name" class="crm2-input"></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" class="crm2-input"></div>
        <div class="form-group"><label>Phone</label><input type="text" name="phone" class="crm2-input"></div>
        <div class="form-group"><label>Job Title</label><input type="text" name="job_title" class="crm2-input"></div>
        <div class="form-group"><label>Account</label>
          <select name="account_id" class="crm2-select"><option value="">— None —</option>@foreach($accounts_list as $a)<option value="{{ $a->id }}">{{ $a->name }}</option>@endforeach</select>
        </div>
        <div class="form-group"><label>Source</label>
          <select name="source" class="crm2-select"><option value="manual">Manual</option><option value="website">Website</option><option value="referral">Referral</option><option value="linkedin">LinkedIn</option><option value="ai_chatbot">AI Chatbot</option><option value="other">Other</option></select>
        </div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="4"></textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Contact</button>
        <a href="{{ route('admin.crm2.sales.contacts') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
