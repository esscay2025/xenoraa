@extends('layouts.admin')
@section('title', 'Project Tasks')
@section('page-title', 'Project Tasks')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-tasks"></i> Project Tasks</h1><p class="crm2-subtitle">Manage tasks across all projects.</p></div>
    <a href="{{ route('admin.crm2.projects.tasks.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Task</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Title</th><th>Project</th><th>Priority</th><th>Status</th><th>Due Date</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($tasks as $task)
        <tr>
          <td><strong>{{ $task->title }}</strong></td>
          <td>{{ $task->project?->name ?? '—' }}</td>
          <td><span class="crm2-badge priority-{{ $task->priority ?? 'medium' }}">{{ ucfirst($task->priority ?? 'Medium') }}</span></td>
          <td><span class="crm2-badge status-{{ $task->status ?? 'new' }}">{{ ucwords(str_replace('_',' ',$task->status ?? 'todo')) }}</span></td>
          <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : '—' }}</td>
          <td class="actions-cell">
            <a href="{{ route('admin.crm2.projects.tasks.edit', $task->id) }}" class="crm2-icon-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
            <form method="POST" action="{{ route('admin.crm2.projects.destroy', ['type'=>'task','id'=>$task->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="6"><div class="crm2-empty"><i class="fas fa-tasks"></i><p>No tasks found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($tasks->hasPages())<div class="crm2-pagination">{{ $tasks->links() }}</div>@endif
  </div>
</div>
@endsection
