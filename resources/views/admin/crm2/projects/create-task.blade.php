@extends('layouts.admin')
@section('title', 'New Task')
@section('page-title', 'New Task')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-tasks"></i> New Task</h1><p class="crm2-subtitle">Create a new project task.</p></div>
    <a href="{{ route('admin.crm2.projects.tasks') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Tasks</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.projects.store') }}">@csrf
      <input type="hidden" name="_type" value="task">
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Title *</label><input type="text" name="title" class="crm2-input" required autofocus></div>
        <div class="form-group"><label>Project</label><select name="project_id" class="crm2-select"><option value="">— None —</option>@foreach($projects_list as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select></div>
        <div class="form-group"><label>Priority</label><select name="priority" class="crm2-select"><option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option></select></div>
        <div class="form-group"><label>Status</label><select name="status" class="crm2-select"><option value="todo">To Do</option><option value="in_progress">In Progress</option><option value="review">Review</option><option value="done">Done</option></select></div>
        <div class="form-group"><label>Due Date</label><input type="date" name="due_date" class="crm2-input"></div>
        <div class="form-group full"><label>Description</label><textarea name="description" class="crm2-textarea" rows="5"></textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Task</button>
        <a href="{{ route('admin.crm2.projects.tasks') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
