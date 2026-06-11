@extends('layouts.admin')
@section('title', 'Vendors')
@section('page-title', 'Vendors')
@section('content')
<style>
.crm2-table tbody tr { cursor:pointer; transition:background .12s; }
.crm2-table tbody tr:hover td { background:var(--bg-hover); }
.crm2-table .actions-cell { white-space:nowrap; }
.vendor-name-link { color:var(--accent); font-weight:600; text-decoration:none; }
.vendor-name-link:hover { text-decoration:underline; }
</style>
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-store"></i> Vendors</h1><p class="crm2-subtitle">Manage your vendors and suppliers.</p></div>
    <a href="{{ route('admin.crm2.inventory.vendors.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Vendor</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>City</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($items as $item)
        @php $viewUrl = route('admin.crm2.inventory.vendors.show', $item->id); @endphp
        <tr onclick="window.location='{{ $viewUrl }}'" title="View {{ $item->name }}">
          <td><a href="{{ $viewUrl }}" class="vendor-name-link" onclick="event.stopPropagation()">{{ $item->name }}</a></td>
          <td>{{ $item->email ?? '—' }}</td>
          <td>{{ $item->phone ?? '—' }}</td>
          <td>{{ $item->city ?? '—' }}</td>
          <td>{{ $item->category ?? '—' }}</td>
          <td><span class="crm2-badge {{ $item->is_active ? 'status-won' : 'status-lost' }}">{{ $item->is_active ? 'Active' : 'Inactive' }}</span></td>
          <td class="actions-cell" onclick="event.stopPropagation()">
            <a href="{{ $viewUrl }}" class="crm2-icon-btn view" title="View"><i class="fas fa-eye"></i></a>
            <a href="{{ route('admin.crm2.inventory.vendors.edit', $item->id) }}" class="crm2-icon-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
            <form method="POST" action="{{ route('admin.crm2.inventory.destroy', ['type'=>'vendors','id'=>$item->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete" title="Delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="7"><div class="crm2-empty"><i class="fas fa-store"></i><p>No vendors found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($items->hasPages())<div class="crm2-pagination">{{ $items->links() }}</div>@endif
  </div>
</div>
@endsection
