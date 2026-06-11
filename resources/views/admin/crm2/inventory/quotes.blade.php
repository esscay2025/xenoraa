@extends('layouts.admin')
@section('title', 'Quotes')
@section('page-title', 'Quotes')
@section('content')
<style>
.crm2-table tbody tr { cursor: pointer; transition: background .12s; }
.crm2-table tbody tr:hover td { background: var(--bg-hover); }
.crm2-table .actions-cell { white-space: nowrap; }
.crm2-table .actions-cell a,
.crm2-table .actions-cell button { pointer-events: auto; }
.qt-subject-link { color: var(--accent); text-decoration: none; font-weight: 600; }
.qt-subject-link:hover { text-decoration: underline; }
</style>
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-file-alt"></i> Quotes</h1>
      <p class="crm2-subtitle">Manage your quotes.</p>
    </div>
    <a href="{{ route('admin.crm2.inventory.quotes.create') }}" class="crm2-btn crm2-btn-primary">
      <i class="fas fa-plus"></i> New Quote
    </a>
  </div>

  @if(session('success'))
    <div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif

  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead>
        <tr>
          <th>Quote Number</th>
          <th>Subject</th>
          <th>Account</th>
          <th>Stage</th>
          <th>Grand Total</th>
          <th>Valid Until</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
        <tr onclick="window.location='{{ route('admin.crm2.inventory.quotes.show', $item->id) }}'">
          <td>{{ $item->quote_number }}</td>
          <td>
            <a href="{{ route('admin.crm2.inventory.quotes.show', $item->id) }}"
               class="qt-subject-link"
               onclick="event.stopPropagation()">
              {{ $item->subject }}
            </a>
          </td>
          <td>{{ $item->account?->name ?? '—' }}</td>
          <td>
            @php
              $stageColors = [
                'draft'       => 'status-draft',
                'negotiation' => 'status-pending',
                'delivered'   => 'status-active',
                'accepted'    => 'status-won',
                'declined'    => 'status-lost',
              ];
              $stageLabels = \App\Models\CrmQuote::STAGES;
            @endphp
            <span class="crm2-badge {{ $stageColors[$item->stage] ?? 'status-new' }}">
              {{ $stageLabels[$item->stage] ?? ucfirst($item->stage) }}
            </span>
          </td>
          <td>{{ $item->grand_total ? '₹' . number_format($item->grand_total, 2) : '—' }}</td>
          <td>{{ $item->valid_until ? $item->valid_until->format('d M Y') : '—' }}</td>
          <td>{{ $item->created_at->format('d M Y') }}</td>
          <td class="actions-cell" onclick="event.stopPropagation()">
            <a href="{{ route('admin.crm2.inventory.quotes.show', $item->id) }}"
               class="crm2-icon-btn view" title="View">
              <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('admin.crm2.inventory.quotes.edit', $item->id) }}"
               class="crm2-icon-btn edit" title="Edit">
              <i class="fas fa-edit"></i>
            </a>
            <form method="POST"
                  action="{{ route('admin.crm2.inventory.destroy', ['type'=>'quotes','id'=>$item->id]) }}"
                  onsubmit="return confirm('Delete this quote?')"
                  style="display:inline">
              @csrf @method('DELETE')
              <button type="submit" class="crm2-icon-btn delete" title="Delete">
                <i class="fas fa-trash"></i>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="crm2-empty">
              <i class="fas fa-file-alt"></i>
              <p>No quotes found. <a href="{{ route('admin.crm2.inventory.quotes.create') }}">Create the first one</a>.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div></div>
</div>
@endsection
