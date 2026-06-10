@extends('layouts.admin')
@section('title', 'Edit Task')
@section('page-title', 'Edit Task')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-tasks"></i> Edit Task</h1><p class="crm2-subtitle">Update the details below and save.</p></div>
    <a href="{{ route('admin.crm2.projects.tasks') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Tasks</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.projects.update', ['type'=>'task','id'=>$item->id]) }}">@csrf @method('PATCH')
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Name *</label><input type="text" name="name" class="crm2-input" value="{{ old('name', $item->name) }}" required autofocus></div>
        <div class="form-group"><label>Project</label><select name="project_id" class="crm2-select"><option value="">— None —</option>@foreach($projects_list as $p)<option value="{{ $p->id }}" {{ $item->project_id==$p->id?'selected':'' }}>{{ $p->name }}</option>@endforeach</select></div>
        <div class="form-group"><label>Priority</label><select name="priority" class="crm2-select"><option value="low" {{ $item->priority=='low'?'selected':'' }}>Low</option><option value="medium" {{ $item->priority=='medium'?'selected':'' }}>Medium</option><option value="high" {{ $item->priority=='high'?'selected':'' }}>High</option></select></div>
        <div class="form-group"><label>Status</label><select name="status" class="crm2-select"><option value="todo" {{ $item->status=='todo'?'selected':'' }}>To Do</option><option value="in_progress" {{ $item->status=='in_progress'?'selected':'' }}>In Progress</option><option value="review" {{ $item->status=='review'?'selected':'' }}>Review</option><option value="done" {{ $item->status=='done'?'selected':'' }}>Done</option></select></div>
        <div class="form-group"><label>Due Date</label><input type="date" name="due_date" class="crm2-input" value="{{ old('due_date', $item->due_date ? \Carbon\Carbon::parse($item->due_date)->format('Y-m-d') : '') }}"></div>
        <div class="form-group full"><label>Description</label><textarea name="description" class="crm2-textarea" rows="5">{{ old('description', $item->description) }}</textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update Task</button>
        <a href="{{ route('admin.crm2.projects.tasks') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
