@extends('layouts.admin')
@section('title', 'New Project')
@section('page-title', 'New Project')
@push('styles')
<style>
/* ── Sticky Top Action Bar ─────────────────────────────────────── */
.xn-sticky-bar {
    position: fixed;
    top: 60px;
    left: var(--rail-width, 60px);
    right: 0;
    z-index: 120;
    background: var(--bg-card, #fff);
    border-bottom: 2px solid var(--accent, #6366f1);
    padding: .75rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    box-shadow: 0 3px 12px rgba(0,0,0,.10);
    flex-wrap: wrap;
    will-change: transform;
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    transition: left 0.22s cubic-bezier(0.4,0,0.2,1);
}
body.xn-panel-open .xn-sticky-bar {
    left: calc(var(--rail-width, 60px) + var(--panel-width, 220px));
}
.xn-sticky-spacer { height: 64px; }
.xn-sticky-title {
    display: flex; align-items: center; gap: .5rem;
    font-size: .95rem; font-weight: 700; color: var(--text-primary, #1a1a2e);
}
.xn-sticky-title i { color: var(--accent, #6366f1); }
.xn-sticky-actions { display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; }
.xn-sticky-btn {
    padding: .45rem 1.1rem; border-radius: 7px; font-size: .82rem; font-weight: 600;
    cursor: pointer; border: none; display: inline-flex; align-items: center; gap: .35rem;
    transition: all .18s; text-decoration: none;
}
.xn-sticky-btn-primary { background: var(--accent, #6366f1); color: #fff; }
.xn-sticky-btn-primary:hover { opacity: .88; color: #fff; }
.xn-sticky-btn-outline {
    background: var(--bg-card, #fff); color: var(--accent, #6366f1);
    border: 1px solid var(--accent, #6366f1);
}
.xn-sticky-btn-outline:hover { background: var(--accent, #6366f1); color: #fff; }
.xn-sticky-btn-ghost {
    background: transparent; color: var(--text-secondary, #64748b);
    border: 1px solid var(--border, #e2e8f0);
}
.xn-sticky-btn-ghost:hover { background: var(--bg-primary, #f8fafc); }
</style>
@endpush
@section('content')
<div class="crm2-page">
  {{-- Sticky Top Action Bar --}}
  <div class="xn-sticky-bar">
    <div class="xn-sticky-title">
      <i class="fas fa-project-diagram"></i>
      Create New Project
    </div>
    <div class="xn-sticky-actions">
      <a href="{{ route('admin.crm2.projects.list') }}" class="xn-sticky-btn xn-sticky-btn-ghost">
        <i class="fas fa-arrow-left"></i> Cancel
      </a>
      <button type="submit" form="projectCreateForm" class="xn-sticky-btn xn-sticky-btn-primary">
        <i class="fas fa-save"></i> Save Project
      </button>
    </div>
  </div>
  <div class="xn-sticky-spacer"></div>

  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-project-diagram"></i> New Project</h1><p class="crm2-subtitle">Create a new CRM project.</p></div>
    <a href="{{ route('admin.crm2.projects.list') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-arrow-left"></i> Back to Projects</a>
  </div>
  @if($errors->any())<div class="crm2-alert danger"><ul style="margin:0;padding-left:1.2rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="crm2-card"><div class="crm2-card-body">
    <form id="projectCreateForm" method="POST" action="{{ route('admin.crm2.projects.store') }}">@csrf
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
