@extends('layouts.admin')
@section('title', 'Edit Case')
@section('page-title', 'Edit Case')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-ticket-alt"></i> Edit Case</h1><p class="crm2-subtitle">Update the details below and save.</p></div>
    <a href="{{ route('admin.crm2.support.cases') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Cases</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.support.update', ['type'=>'case','id'=>$item->id]) }}">@csrf @method('PATCH')
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Subject *</label><input type="text" name="subject" class="crm2-input" value="{{ old('subject', $item->subject) }}" required autofocus></div>
        <div class="form-group"><label>Priority</label><select name="priority" class="crm2-select"><option value="low" {{ $item->priority=='low'?'selected':'' }}>Low</option><option value="medium" {{ $item->priority=='medium'?'selected':'' }}>Medium</option><option value="high" {{ $item->priority=='high'?'selected':'' }}>High</option><option value="critical" {{ $item->priority=='critical'?'selected':'' }}>Critical</option></select></div>
        <div class="form-group"><label>Status</label><select name="status" class="crm2-select"><option value="new" {{ $item->status=='new'?'selected':'' }}>New</option><option value="in_progress" {{ $item->status=='in_progress'?'selected':'' }}>In Progress</option><option value="resolved" {{ $item->status=='resolved'?'selected':'' }}>Resolved</option><option value="closed" {{ $item->status=='closed'?'selected':'' }}>Closed</option></select></div>
        <div class="form-group"><label>Type</label><input type="text" name="type" class="crm2-input" value="{{ old('type', $item->type) }}" placeholder="e.g. Bug, Feature Request"></div>
        <div class="form-group"><label>Origin</label><select name="origin" class="crm2-select"><option value="web" {{ $item->origin=='web'?'selected':'' }}>Web</option><option value="email" {{ $item->origin=='email'?'selected':'' }}>Email</option><option value="phone" {{ $item->origin=='phone'?'selected':'' }}>Phone</option><option value="chat" {{ $item->origin=='chat'?'selected':'' }}>Chat</option></select></div>
        <div class="form-group full"><label>Description</label><textarea name="description" class="crm2-textarea" rows="5">{{ old('description', $item->description) }}</textarea></div>
        <div class="form-group full"><label>Resolution</label><textarea name="resolution" class="crm2-textarea" rows="4">{{ old('resolution', $item->resolution) }}</textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update Case</button>
        <a href="{{ route('admin.crm2.support.cases') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
