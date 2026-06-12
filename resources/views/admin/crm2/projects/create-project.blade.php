@extends('layouts.admin')
@section('title', 'New Project')
@section('page-title', 'New Project')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-project-diagram"></i> New Project</h1><p class="crm2-subtitle">Create a new CRM project.</p></div>
    <a href="{{ route('admin.crm2.projects.list') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Projects</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.projects.store') }}">@csrf
      <input type="hidden" name="_type" value="project">
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Project Name *</label><input type="text" name="name" class="crm2-input" required autofocus placeholder="Enter project name"></div>
        <div class="form-group"><label>Status</label>
          <select name="status" class="crm2-select">
            <option value="planning">Planning</option>
            <option value="active" selected>Active</option>
            <option value="on_hold">On Hold</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
        <div class="form-group"><label>Priority</label>
          <select name="priority" class="crm2-select">
            <option value="low">Low</option>
            <option value="medium" selected>Medium</option>
            <option value="high">High</option>
          </select>
        </div>
        <div class="form-group"><label>Start Date</label><input type="date" name="start_date" class="crm2-input"></div>
        <div class="form-group"><label>End Date</label><input type="date" name="end_date" class="crm2-input"></div>
        <div class="form-group"><label>Budget (₹)</label><input type="number" name="budget" class="crm2-input" placeholder="0.00" step="0.01" min="0"></div>
        <div class="form-group"><label>Estimated Cost (₹)</label><input type="number" name="cost" class="crm2-input" placeholder="0.00" step="0.01" min="0"></div>
        <div class="form-group"><label>Linked Account</label>
          <select name="account_id" class="crm2-select">
            <option value="">— None —</option>
            @foreach($accounts_list as $acc)
            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group"><label>Linked Deal</label>
          <select name="deal_id" class="crm2-select">
            <option value="">— None —</option>
            @foreach($deals_list as $deal)
            <option value="{{ $deal->id }}">{{ $deal->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group full"><label>Description</label><textarea name="description" class="crm2-textarea" rows="5" placeholder="Describe the project scope, goals, and deliverables..."></textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Project</button>
        <a href="{{ route('admin.crm2.projects.list') }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
