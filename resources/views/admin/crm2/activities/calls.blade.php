@extends('layouts.admin')
@section('title', 'Calls')
@section('page-title', 'Calls')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-phone-alt"></i> Calls</h1><p class="crm2-subtitle">Manage your CRM calls.</p></div>
    <a href="{{ route('admin.crm2.activities.calls.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Call</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card mb-4"><div class="crm2-card-body">
    <form method="GET" class="crm2-filter-form">
      <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search calls..." class="crm2-input"></div>
      <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
      <a href="{{ route('admin.crm2.activities.calls') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
    </form>
  </div></div>
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Title</th><th>Due Date</th><th>Priority</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($activities as $activity)
        <tr>
          <td><strong>{{ $activity->title }}</strong></td>
          <td>{{ $activity->due_date ? \Carbon\Carbon::parse($activity->due_date)->format('d M Y') : '—' }}</td>
          <td><span class="crm2-badge priority-{{ $activity->priority ?? 'medium' }}">{{ ucfirst($activity->priority ?? 'Medium') }}</span></td>
          <td><span class="crm2-badge status-{{ $activity->is_completed ? 'won' : 'new' }}">{{ $activity->is_completed ? 'Completed' : 'Pending' }}</span></td>
          <td>{{ $activity->created_at->format('d M Y') }}</td>
          <td class="actions-cell">
            @if(!$activity->is_completed)
            <form method="POST" action="{{ route('admin.crm2.activity.complete', $activity->id) }}" style="display:inline">@csrf @method('PATCH')<button type="submit" class="crm2-icon-btn" title="Mark Complete" style="color:#22c55e;"><i class="fas fa-check"></i></button></form>
            @endif
            <form method="POST" action="{{ route('admin.crm2.activity.destroy', $activity->id) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="6"><div class="crm2-empty"><i class="fas fa-phone-alt"></i><p>No calls found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($activities->hasPages())<div class="crm2-pagination">{{ $activities->links() }}</div>@endif
  </div>
</div>
@endsection
