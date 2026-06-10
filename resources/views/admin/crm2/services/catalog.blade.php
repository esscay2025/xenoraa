@extends('layouts.admin')
@section('title', 'Service Catalog')
@section('page-title', 'Service Catalog')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-list-alt"></i> Service Catalog</h1><p class="crm2-subtitle">Manage your service offerings.</p></div>
    <a href="{{ route('admin.crm2.services.catalog.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Service</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Name</th><th>Price</th><th>Duration</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($services as $service)
        <tr>
          <td><strong>{{ $service->name }}</strong><br><small class="text-muted">{{ Str::limit($service->description ?? '', 60) }}</small></td>
          <td>{{ $service->price ? '₹'.number_format($service->price,0) : '—' }}</td>
          <td>{{ $service->duration ? $service->duration.' min' : '—' }}</td>
          <td><span class="crm2-badge {{ $service->is_active ? 'status-won' : 'status-lost' }}">{{ $service->is_active ? 'Active' : 'Inactive' }}</span></td>
          <td class="actions-cell">
            <a href="{{ route('admin.crm2.services.catalog.edit', $service->id) }}" class="crm2-icon-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
            <form method="POST" action="{{ route('admin.crm2.services.destroy', ['type'=>'service','id'=>$service->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="5"><div class="crm2-empty"><i class="fas fa-concierge-bell"></i><p>No services found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($services->hasPages())<div class="crm2-pagination">{{ $services->links() }}</div>@endif
  </div>
</div>
@endsection
