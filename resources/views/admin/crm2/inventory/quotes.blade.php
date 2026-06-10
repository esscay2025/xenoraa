@extends('layouts.admin')
@section('title', 'Quotes')
@section('page-title', 'Quotes')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-file-alt"></i> Quotes</h1><p class="crm2-subtitle">Manage your quotes.</p></div>
    <a href="{{ route('admin.crm2.inventory.quotes.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Quote</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Title</th><th>Account</th><th>Total</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($items as $item)
        <tr>
          <td><strong>{{ $item->title }}</strong></td>
          <td>{{ $item->account_name ?? '—' }}</td>
          <td>{{ $item->total_amount ? '₹'.number_format($item->total_amount,0) : '—' }}</td>
          <td><span class="crm2-badge status-{{ $item->status ?? 'new' }}">{{ ucfirst($item->status ?? 'Draft') }}</span></td>
          <td>{{ $item->created_at->format('d M Y') }}</td>
          <td class="actions-cell">
            <a href="{{ route('admin.crm2.inventory.quotes.edit', $item->id) }}" class="crm2-icon-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
            <form method="POST" action="{{ route('admin.crm2.inventory.destroy', ['type'=>'quotes','id'=>$item->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="6"><div class="crm2-empty"><i class="fas fa-file-alt"></i><p>No quotes found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div></div>
</div>
@endsection
