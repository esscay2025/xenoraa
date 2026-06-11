@extends('layouts.admin')
@section('title', 'Price Books')
@section('page-title', 'Price Books')
@section('content')
<style>
.crm2-table tbody tr.clickable-row { cursor: pointer; }
.crm2-table tbody tr.clickable-row:hover td { background: var(--bg-primary, #f8fafc); }
.crm2-table tbody tr.clickable-row td.actions-cell { cursor: default; }
</style>
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-tag"></i> Price Books</h1><p class="crm2-subtitle">Manage your price books.</p></div>
    <a href="{{ route('admin.crm2.inventory.price-books.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Price Book</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Pricing Model</th>
          <th>Pricing %</th>
          <th>Currency</th>
          <th>Active</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
        <tr class="clickable-row" onclick="window.location='{{ route('admin.crm2.inventory.price-books.show', $item->id) }}'">
          <td>
            <a href="{{ route('admin.crm2.inventory.price-books.show', $item->id) }}"
               style="color:var(--accent,#6366f1);font-weight:600;text-decoration:none"
               onclick="event.stopPropagation()">
              {{ $item->name }}
            </a>
          </td>
          <td>{{ $item->pricing_model ?: '—' }}</td>
          <td>{{ $item->pricing_percentage ? number_format($item->pricing_percentage, 2) . '%' : '—' }}</td>
          <td>{{ $item->currency ?: 'INR' }}</td>
          <td><span class="crm2-badge {{ $item->is_active ? 'status-won' : 'status-lost' }}">{{ $item->is_active ? 'Active' : 'Inactive' }}</span></td>
          <td>{{ $item->created_at->format('d M Y') }}</td>
          <td class="actions-cell" onclick="event.stopPropagation()">
            <a href="{{ route('admin.crm2.inventory.price-books.show', $item->id) }}" class="crm2-icon-btn view" title="View"><i class="fas fa-eye"></i></a>
            <a href="{{ route('admin.crm2.inventory.price-books.edit', $item->id) }}" class="crm2-icon-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
            <form method="POST" action="{{ route('admin.crm2.inventory.destroy', ['type'=>'price_book','id'=>$item->id]) }}" onsubmit="return confirm('Delete this price book?')" style="display:inline">
              @csrf @method('DELETE')
              <button type="submit" class="crm2-icon-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="7"><div class="crm2-empty"><i class="fas fa-tag"></i><p>No price books found. <a href="{{ route('admin.crm2.inventory.price-books.create') }}" style="color:var(--accent,#6366f1)">Create one</a>.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
    @if($items->hasPages())
    <div style="padding:1rem 1.25rem">{{ $items->links() }}</div>
    @endif
  </div></div>
</div>
@endsection
