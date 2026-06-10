@extends('layouts.admin')
@section('title', 'Projects')
@section('page-title', 'Projects')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-folder-open"></i> Projects</h1><p class="crm2-subtitle">Manage your CRM projects.</p></div>
    <a href="{{ route('admin.crm2.projects.list.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Project</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card mb-4"><div class="crm2-card-body">
    <form method="GET" class="crm2-filter-form">
      <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search projects..." class="crm2-input"></div>
      <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
      <a href="{{ route('admin.crm2.projects.list') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
    </form>
  </div></div>
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Name</th><th>Status</th><th>Tasks</th><th>Start Date</th><th>End Date</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($projects as $project)
        <tr>
          <td><strong>{{ $project->name }}</strong><br><small class="text-muted">{{ Str::limit($project->description ?? '', 60) }}</small></td>
          <td><span class="crm2-badge status-{{ $project->status ?? 'new' }}">{{ ucfirst($project->status ?? 'Active') }}</span></td>
          <td>{{ $project->tasks_count }}</td>
          <td>{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : '—' }}</td>
          <td>{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : '—' }}</td>
          <td class="actions-cell">
            <a href="{{ route('admin.crm2.projects.list.edit', $project->id) }}" class="crm2-icon-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
            <form method="POST" action="{{ route('admin.crm2.projects.destroy', ['type'=>'project','id'=>$project->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="6"><div class="crm2-empty"><i class="fas fa-folder-open"></i><p>No projects found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($projects->hasPages())<div class="crm2-pagination">{{ $projects->links() }}</div>@endif
  </div>
</div>
@endsection
