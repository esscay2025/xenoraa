@extends('layouts.admin')
@section('title', 'Vendors')
@section('page-title', 'Vendors')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-store"></i> Vendors</h1><p class="crm2-subtitle">Manage your vendors and suppliers.</p></div>
    <a href="{{ route('admin.crm2.inventory.vendors.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Vendor</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>City</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($items as $item)
        <tr>
          <td><strong>{{ $item->name }}</strong></td>
          <td>{{ $item->email ?? '—' }}</td>
          <td>{{ $item->phone ?? '—' }}</td>
          <td>{{ $item->city ?? '—' }}</td>
          <td><span class="crm2-badge {{ $item->is_active ? 'status-won' : 'status-lost' }}">{{ $item->is_active ? 'Active' : 'Inactive' }}</span></td>
          <td class="actions-cell">
            <a href="{{ route('admin.crm2.inventory.vendors.edit', $item->id) }}" class="crm2-icon-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
            <form method="POST" action="{{ route('admin.crm2.inventory.destroy', ['type'=>'vendors','id'=>$item->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="6"><div class="crm2-empty"><i class="fas fa-store"></i><p>No vendors found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($items->hasPages())<div class="crm2-pagination">{{ $items->links() }}</div>@endif
  </div>
</div>
@endsection
