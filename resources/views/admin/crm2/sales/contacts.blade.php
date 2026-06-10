@extends('layouts.admin')
@section('title', 'Contacts')
@section('page-title', 'Contacts')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div><h1 class="crm2-title"><i class="fas fa-address-book"></i> Contacts</h1><p class="crm2-subtitle">Manage your CRM contacts.</p></div>
    <a href="{{ route('admin.crm2.sales.contacts.create') }}" class="crm2-btn crm2-btn-primary"><i class="fas fa-plus"></i> New Contact</a>
  </div>
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <div class="crm2-card mb-4"><div class="crm2-card-body">
    <form method="GET" class="crm2-filter-form">
      <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search contacts..." class="crm2-input"></div>
      <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
      <a href="{{ route('admin.crm2.sales.contacts') }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
    </form>
  </div></div>
  <div class="crm2-card"><div class="crm2-card-body p-0">
    <table class="crm2-table">
      <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Job Title</th><th>Account</th><th>Created</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($contacts as $contact)
        <tr>
          <td><strong>{{ $contact->first_name }} {{ $contact->last_name }}</strong></td>
          <td>{{ $contact->email ?? '—' }}</td>
          <td>{{ $contact->phone ?? '—' }}</td>
          <td>{{ $contact->job_title ?? '—' }}</td>
          <td>{{ $contact->account?->name ?? '—' }}</td>
          <td>{{ $contact->created_at->format('d M Y') }}</td>
          <td class="actions-cell">
            <button class="crm2-icon-btn edit" onclick='editRecord("contact", {{ $contact->id }}, @json($contact))' title="Edit"><i class="fas fa-edit"></i></button>
            <form method="POST" action="{{ route('admin.crm2.sales.destroy', ['type'=>'contact','id'=>$contact->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
          </td>
        </tr>
        @empty
        <tr><td colspan="7"><div class="crm2-empty"><i class="fas fa-address-book"></i><p>No contacts found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($contacts->hasPages())<div class="crm2-pagination">{{ $contacts->links() }}</div>@endif
  </div>
</div>
<div class="crm2-modal-overlay" id="modal-edit-record">
  <div class="crm2-modal">
    <div class="crm2-modal-header"><h3 id="edit-modal-title">Edit Contact</h3><button onclick="closeModal('modal-edit-record')"><i class="fas fa-times"></i></button></div>
    <form id="edit-record-form" method="POST">@csrf @method('PATCH')
      <div class="crm2-modal-body" id="edit-modal-body"></div>
      <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-edit-record')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update</button></div>
    </form>
  </div>
</div>

@endsection
