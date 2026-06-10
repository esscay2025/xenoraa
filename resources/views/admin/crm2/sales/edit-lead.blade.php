@extends('layouts.admin')
@section('title', 'Edit Lead')
@section('page-title', 'Edit Lead')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-user-plus"></i> Edit Lead</h1><p class="crm2-subtitle">Update the details below and save.</p></div>
    <a href="{{ route('admin.crm2.sales.leads') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Leads</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.sales.update', ['type'=>'lead','id'=>$item->id]) }}">@csrf @method('PATCH')
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Name *</label><input type="text" name="name" class="crm2-input" value="{{ old('name', $item->name) }}" required autofocus></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" class="crm2-input" value="{{ old('email', $item->email) }}"></div>
        <div class="form-group"><label>Mobile</label><input type="text" name="mobile" class="crm2-input" value="{{ old('mobile', $item->mobile) }}"></div>
        <div class="form-group"><label>Source</label><select name="source" class="crm2-select"><option value="manual" {{ $item->source=='manual'?'selected':'' }}>Manual</option><option value="website" {{ $item->source=='website'?'selected':'' }}>Website</option><option value="referral" {{ $item->source=='referral'?'selected':'' }}>Referral</option><option value="linkedin" {{ $item->source=='linkedin'?'selected':'' }}>LinkedIn</option><option value="ai_chatbot" {{ $item->source=='ai_chatbot'?'selected':'' }}>AI Chatbot</option><option value="other" {{ $item->source=='other'?'selected':'' }}>Other</option></select></div>
        <div class="form-group"><label>Status</label><select name="status" class="crm2-select"><option value="new" {{ $item->status=='new'?'selected':'' }}>New</option><option value="contacted" {{ $item->status=='contacted'?'selected':'' }}>Contacted</option><option value="qualified" {{ $item->status=='qualified'?'selected':'' }}>Qualified</option><option value="proposal" {{ $item->status=='proposal'?'selected':'' }}>Proposal</option><option value="won" {{ $item->status=='won'?'selected':'' }}>Won</option><option value="lost" {{ $item->status=='lost'?'selected':'' }}>Lost</option></select></div>
        <div class="form-group"><label>Priority</label><select name="priority" class="crm2-select"><option value="low" {{ $item->priority=='low'?'selected':'' }}>Low</option><option value="medium" {{ $item->priority=='medium'?'selected':'' }}>Medium</option><option value="high" {{ $item->priority=='high'?'selected':'' }}>High</option></select></div>
        <div class="form-group"><label>Deal Value (₹)</label><input type="number" name="deal_value" class="crm2-input" value="{{ old('deal_value', $item->deal_value) }}" step="0.01" min="0"></div>
        <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="4">{{ old('notes', $item->notes) }}</textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update Lead</button>
        <a href="{{ route('admin.crm2.sales.leads') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
