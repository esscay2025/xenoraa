@extends('layouts.admin')
@section('title', 'Edit Activity')
@section('page-title', 'Edit Activity')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-edit"></i> Edit {{ ucfirst($item->type) }}</h1><p class="crm2-subtitle">Update the activity details below.</p></div>
    <a href="{{ route($backRoute) }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.activity.update', $item->id) }}">@csrf @method('PATCH')
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Subject *</label><input type="text" name="subject" class="crm2-input" value="{{ old('subject', $item->subject) }}" required autofocus></div>
        <div class="form-group"><label>Status</label><select name="status" class="crm2-select"><option value="pending" {{ $item->status=='pending'?'selected':'' }}>Pending</option><option value="in_progress" {{ $item->status=='in_progress'?'selected':'' }}>In Progress</option><option value="completed" {{ $item->status=='completed'?'selected':'' }}>Completed</option><option value="cancelled" {{ $item->status=='cancelled'?'selected':'' }}>Cancelled</option></select></div>
        <div class="form-group"><label>Due Date & Time</label><input type="datetime-local" name="due_at" class="crm2-input" value="{{ old('due_at', $item->due_at ? \Carbon\Carbon::parse($item->due_at)->format('Y-m-d\TH:i') : '') }}"></div>
        <div class="form-group full"><label>Description</label><textarea name="description" class="crm2-textarea" rows="5">{{ old('description', $item->description) }}</textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update {{ ucfirst($item->type) }}</button>
        <a href="{{ route($backRoute) }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
