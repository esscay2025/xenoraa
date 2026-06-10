@extends('layouts.admin')
@section('title', 'Price Books')
@section('page-title', 'Price Books')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-tag"></i> Price Books</h1><p class="crm2-subtitle">Manage your price books.</p></div>
    <a href="{{ route('admin.crm2.inventory.price-books.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Price Book</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Name</th><th>Description</th><th>Active</th><th>Created</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($items as $item)
        <tr>
          <td><strong>{{ $item->name }}</strong></td>
          <td>{{ Str::limit($item->description ?? '—', 50) }}</td>
          <td><span class="crm2-badge {{ $item->is_active ? 'status-won' : 'status-lost' }}">{{ $item->is_active ? 'Active' : 'Inactive' }}</span></td>
          <td>{{ $item->created_at->format('d M Y') }}</td>
          <td class="actions-cell">
            <form method="POST" action="{{ route('admin.crm2.inventory.destroy', ['type'=>'price_books','id'=>$item->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="5"><div class="crm2-empty"><i class="fas fa-tag"></i><p>No price books found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div></div>
</div>
@endsection
