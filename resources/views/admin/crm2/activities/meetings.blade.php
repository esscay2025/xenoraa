@extends('layouts.admin')
@section('title', 'Meetings')
@section('page-title', 'Meetings')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-calendar-alt"></i> Meetings</h1><p class="crm2-subtitle">Manage your CRM meetings.</p></div>
    <a href="{{ route('admin.crm2.activities.meetings.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Meeting</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card mb-4"><div class="crm2-card-body">
    <form method="GET" class="crm2-filter-form">
      <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search meetings..." class="crm2-input"></div>
      <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
      <a href="{{ route('admin.crm2.activities.meetings') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
    </form>
  </div></div>
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Title</th><th>Due Date</th><th>Priority</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($activities as $activity)
        <tr>
          <td><strong>{{ $activity->subject }}</strong></td>
          <td>{{ $activity->due_at ? \Carbon\Carbon::parse($activity->due_at)->format('d M Y') : '—' }}</td>
          <td><span class="crm2-badge priority-{{ ($activity->status ?? 'pending') ?? 'medium' }}">{{ ucfirst(($activity->status ?? 'pending') ?? 'Medium') }}</span></td>
          <td><span class="crm2-badge status-{{ ($activity->status === 'completed') ? 'won' : 'new' }}">{{ ($activity->status === 'completed') ? 'Completed' : 'Pending' }}</span></td>
          <td>{{ $activity->created_at->format('d M Y') }}</td>
          <td class="actions-cell">
            @if(!($activity->status === 'completed'))
            <form method="POST" action="{{ route('admin.crm2.activity.complete', $activity->id) }}" style="display:inline">@csrf @method('PATCH')<button type="submit" class="crm2-icon-btn" title="Mark Complete" style="color:#22c55e;"><i class="fas fa-check"></i></button></form>
            @endif
            <form method="POST" action="{{ route('admin.crm2.activity.destroy', $activity->id) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="6"><div class="crm2-empty"><i class="fas fa-calendar-alt"></i><p>No meetings found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($activities->hasPages())<div class="crm2-pagination">{{ $activities->links() }}</div>@endif
  </div>
</div>
@endsection
