@extends('layouts.admin')
@section('title', 'Solutions')
@section('page-title', 'Solutions')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-lightbulb"></i> Solutions</h1><p class="crm2-subtitle">Manage your knowledge base and solutions.</p></div>
    <a href="{{ route('admin.crm2.support.solutions.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Solution</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card mb-4"><div class="crm2-card-body">
    <form method="GET" class="crm2-filter-form">
      <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search solutions..." class="crm2-input"></div>
      <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
      <a href="{{ route('admin.crm2.support.solutions') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
    </form>
  </div></div>
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Title</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($solutions as $solution)
        <tr>
          <td><strong>{{ $solution->title }}</strong><br><small class="text-muted">{{ Str::limit($solution->content ?? '', 80) }}</small></td>
          <td><span class="crm2-badge {{ $solution->is_published ? 'status-won' : 'status-new' }}">{{ $solution->is_published ? 'Published' : 'Draft' }}</span></td>
          <td>{{ $solution->created_at->format('d M Y') }}</td>
          <td class="actions-cell">
            <a href="{{ route('admin.crm2.support.solutions.edit', $solution->id) }}" class="crm2-icon-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
            <form method="POST" action="{{ route('admin.crm2.support.destroy', ['type'=>'solution','id'=>$solution->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="4"><div class="crm2-empty"><i class="fas fa-lightbulb"></i><p>No solutions found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($solutions->hasPages())<div class="crm2-pagination">{{ $solutions->links() }}</div>@endif
  </div>
</div>
@endsection
