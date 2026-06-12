@extends('layouts.admin')
@section('title', 'Edit Project')
@section('page-title', 'Edit Project')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-edit"></i> Edit Project</h1><p class="crm2-subtitle">{{ $project->name }}</p></div>
    <div style="display:flex;gap:10px">
      <a href="{{ route('admin.crm2.projects.show', $project->id) }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Project</a>
      <a href="{{ route('admin.crm2.projects.list') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-list"></i> All Projects</a>
    </div>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form method="POST" action="{{ route('admin.crm2.projects.update', ['type'=>'project','id'=>$project->id]) }}">
      @csrf @method('PATCH')
      <div class="crm2-form-grid">
        <div class="form-group full"><label>Project Name *</label><input type="text" name="name" class="crm2-input" value="{{ $project->name }}" required></div>
        <div class="form-group"><label>Status</label>
          <select name="status" class="crm2-select">
            @foreach(['planning'=>'Planning','active'=>'Active','on_hold'=>'On Hold','completed'=>'Completed','cancelled'=>'Cancelled'] as $v=>$l)
            <option value="{{ $v }}" {{ $project->status==$v?'selected':'' }}>{{ $l }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group"><label>Priority</label>
          <select name="priority" class="crm2-select">
            @foreach(['low'=>'Low','medium'=>'Medium','high'=>'High'] as $v=>$l)
            <option value="{{ $v }}" {{ ($project->priority??'medium')==$v?'selected':'' }}>{{ $l }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group"><label>Start Date</label><input type="date" name="start_date" class="crm2-input" value="{{ $project->start_date?->format('Y-m-d') }}"></div>
        <div class="form-group"><label>End Date</label><input type="date" name="end_date" class="crm2-input" value="{{ $project->end_date?->format('Y-m-d') }}"></div>
        <div class="form-group"><label>Budget (₹)</label><input type="number" name="budget" class="crm2-input" value="{{ $project->budget }}" step="0.01" min="0"></div>
        <div class="form-group"><label>Estimated Cost (₹)</label><input type="number" name="cost" class="crm2-input" value="{{ $project->cost }}" step="0.01" min="0"></div>
        <div class="form-group"><label>Linked Account</label>
          <select name="account_id" class="crm2-select">
            <option value="">— None —</option>
            @foreach($accounts_list as $acc)
            <option value="{{ $acc->id }}" {{ $project->account_id==$acc->id?'selected':'' }}>{{ $acc->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group"><label>Linked Deal</label>
          <select name="deal_id" class="crm2-select">
            <option value="">— None —</option>
            @foreach($deals_list as $deal)
            <option value="{{ $deal->id }}" {{ $project->deal_id==$deal->id?'selected':'' }}>{{ $deal->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group full"><label>Description</label><textarea name="description" class="crm2-textarea" rows="5">{{ $project->description }}</textarea></div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update Project</button>
        <a href="{{ route('admin.crm2.projects.show', $project->id) }}" class="crm2-btn crm2-btn-ghost">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
