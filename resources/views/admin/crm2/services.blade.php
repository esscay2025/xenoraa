@extends('layouts.admin')
@section('title', 'CRM Services')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-concierge-bell"></i> Services</h1>
      <p class="crm2-subtitle">Streamline every aspect of your service business — appointments, delivery, and invoicing.</p>
    </div>
    <button class="crm2-btn crm2-btn-primary" onclick="openModal('modal-create-{{ $tab === \'catalog\' ? \'service\' : \'booking\' }}')">
      <i class="fas fa-plus"></i> {{ $tab === 'catalog' ? 'New Service' : 'New Booking' }}
    </button>
  </div>

  {{-- Tabs --}}
  <div class="crm2-tabs">
    <a href="{{ route('admin.crm2.services', ['tab'=>'catalog']) }}" class="crm2-tab {{ $tab==='catalog'?'active':'' }}"><i class="fas fa-list-alt"></i> Service Catalog</a>
    <a href="{{ route('admin.crm2.services', ['tab'=>'bookings']) }}" class="crm2-tab {{ $tab==='bookings'?'active':'' }}"><i class="fas fa-calendar-check"></i> Bookings</a>
  </div>

  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

  {{-- Filter --}}
  @if($tab === 'bookings')
  <div class="crm2-card mb-4">
    <div class="crm2-card-body">
      <form method="GET" class="crm2-filter-form">
        <input type="hidden" name="tab" value="bookings">
        <div class="filter-group">
          <select name="status" class="crm2-select"><option value="">All Status</option>@foreach(\App\Models\CrmServiceBooking::STATUSES as $k=>$v)<option value="{{ $k }}" {{ request('status')===$k?'selected':'' }}>{{ $v }}</option>@endforeach</select>
        </div>
        <div class="filter-group">
          <select name="service_id" class="crm2-select"><option value="">All Services</option>@foreach($services_list as $s)<option value="{{ $s->id }}" {{ request('service_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach</select>
        </div>
        <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('admin.crm2.services', ['tab'=>'bookings']) }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
      </form>
    </div>
  </div>
  @endif

  {{-- SERVICE CATALOG --}}
  @if($tab === 'catalog')
  <div class="crm2-services-grid">
    @forelse($serviceList as $svc)
    <div class="crm2-service-card {{ !$svc->is_active ? 'inactive' : '' }}">
      <div class="svc-header">
        <div class="svc-icon"><i class="fas fa-concierge-bell"></i></div>
        <div class="svc-status"><span class="crm2-badge {{ $svc->is_active ? 'status-active' : 'status-inactive' }}">{{ $svc->is_active ? 'Active' : 'Inactive' }}</span></div>
      </div>
      <div class="svc-name">{{ $svc->name }}</div>
      <div class="svc-desc">{{ Str::limit($svc->description, 80) ?? 'No description.' }}</div>
      <div class="svc-meta">
        <span><i class="fas fa-rupee-sign"></i> ₹{{ number_format($svc->price, 2) }}</span>
        <span><i class="fas fa-clock"></i> {{ $svc->duration_label }}</span>
      </div>
      <div class="svc-actions">
        <button class="crm2-btn crm2-btn-ghost btn-sm" onclick='editService({{ $svc->id }}, @json($svc))'><i class="fas fa-edit"></i> Edit</button>
        <form method="POST" action="{{ route('admin.crm2.services.destroy', ['type'=>'service','id'=>$svc->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-btn crm2-btn-danger btn-sm"><i class="fas fa-trash"></i></button></form>
      </div>
    </div>
    @empty
    <div class="crm2-empty full-width"><i class="fas fa-concierge-bell"></i><p>No services yet. Create your first service!</p></div>
    @endforelse
  </div>
  @if($serviceList->hasPages())<div class="crm2-pagination">{{ $serviceList->links() }}</div>@endif

  {{-- BOOKINGS --}}
  @elseif($tab === 'bookings')
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>Service</th><th>Contact</th><th>Booking Time</th><th>Status</th><th>Price</th><th>Notes</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($bookings as $bk)
          <tr>
            <td><strong>{{ $bk->service?->name ?? '—' }}</strong></td>
            <td>{{ $bk->contact?->full_name ?? '—' }}</td>
            <td>{{ $bk->booking_time->format('d M Y, H:i') }}</td>
            <td><span class="crm2-badge status-{{ $bk->status }}">{{ \App\Models\CrmServiceBooking::STATUSES[$bk->status] ?? $bk->status }}</span></td>
            <td>₹{{ number_format($bk->price, 2) }}</td>
            <td>{{ Str::limit($bk->notes, 40) ?? '—' }}</td>
            <td class="actions-cell">
              <button class="crm2-icon-btn edit" onclick='editBooking({{ $bk->id }}, @json($bk))' title="Edit"><i class="fas fa-edit"></i></button>
              <form method="POST" action="{{ route('admin.crm2.services.destroy', ['type'=>'booking','id'=>$bk->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
            </td>
          </tr>
          @empty
          <tr><td colspan="7"><div class="crm2-empty"><i class="fas fa-calendar-check"></i><p>No bookings yet.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($bookings->hasPages())<div class="crm2-pagination">{{ $bookings->links() }}</div>@endif
  </div>
  @endif

  {{-- Create Service Modal --}}
  <div class="crm2-modal-overlay" id="modal-create-service">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-concierge-bell"></i> New Service</h3><button onclick="closeModal('modal-create-service')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.services.store') }}">@csrf
        <input type="hidden" name="_type" value="service">
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Service Name *</label><input type="text" name="name" class="crm2-input" required></div>
          <div class="form-group"><label>Price (₹)</label><input type="number" name="price" class="crm2-input" step="0.01" value="0"></div>
          <div class="form-group"><label>Duration (minutes)</label><input type="number" name="duration_minutes" class="crm2-input" value="60" min="1"></div>
          <div class="form-group"><label>Active</label><select name="is_active" class="crm2-select"><option value="1">Yes</option><option value="0">No</option></select></div>
          <div class="form-group full"><label>Description</label><textarea name="description" class="crm2-textarea" rows="3"></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-service')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Service</button></div>
      </form>
    </div>
  </div>

  {{-- Create Booking Modal --}}
  <div class="crm2-modal-overlay" id="modal-create-booking">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-calendar-plus"></i> New Booking</h3><button onclick="closeModal('modal-create-booking')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.services.store') }}">@csrf
        <input type="hidden" name="_type" value="booking">
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Service *</label><select name="service_id" class="crm2-select" required><option value="">— Select Service —</option>@foreach($services_list as $s)<option value="{{ $s->id }}">{{ $s->name }} ({{ $s->duration_label }})</option>@endforeach</select></div>
          <div class="form-group"><label>Contact</label><select name="contact_id" class="crm2-select"><option value="">— None —</option>@foreach($contacts_list as $c)<option value="{{ $c->id }}">{{ $c->first_name }} {{ $c->last_name }}</option>@endforeach</select></div>
          <div class="form-group"><label>Account</label><select name="account_id" class="crm2-select"><option value="">— None —</option>@foreach($accounts_list as $a)<option value="{{ $a->id }}">{{ $a->name }}</option>@endforeach</select></div>
          <div class="form-group"><label>Booking Date & Time *</label><input type="datetime-local" name="booking_time" class="crm2-input" required></div>
          <div class="form-group"><label>Status</label><select name="status" class="crm2-select">@foreach(\App\Models\CrmServiceBooking::STATUSES as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
          <div class="form-group"><label>Price (₹)</label><input type="number" name="price" class="crm2-input" step="0.01" value="0"></div>
          <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="2"></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-booking')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Booking</button></div>
      </form>
    </div>
  </div>

  {{-- Edit Service Modal --}}
  <div class="crm2-modal-overlay" id="modal-edit-service">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-edit"></i> Edit Service</h3><button onclick="closeModal('modal-edit-service')"><i class="fas fa-times"></i></button></div>
      <form id="edit-service-form" method="POST">@csrf @method('PATCH')
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Name</label><input type="text" name="name" id="esvc-name" class="crm2-input"></div>
          <div class="form-group"><label>Price (₹)</label><input type="number" name="price" id="esvc-price" class="crm2-input" step="0.01"></div>
          <div class="form-group"><label>Duration (min)</label><input type="number" name="duration_minutes" id="esvc-duration" class="crm2-input"></div>
          <div class="form-group"><label>Active</label><select name="is_active" id="esvc-active" class="crm2-select"><option value="1">Yes</option><option value="0">No</option></select></div>
          <div class="form-group full"><label>Description</label><textarea name="description" id="esvc-desc" class="crm2-textarea" rows="3"></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-edit-service')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update</button></div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

function editService(id, data) {
  document.getElementById('edit-service-form').action = `/admin/crm2/services/service/${id}`;
  document.getElementById('esvc-name').value = data.name || '';
  document.getElementById('esvc-price').value = data.price || 0;
  document.getElementById('esvc-duration').value = data.duration_minutes || 60;
  document.getElementById('esvc-active').value = data.is_active ? '1' : '0';
  document.getElementById('esvc-desc').value = data.description || '';
  openModal('modal-edit-service');
}

function editBooking(id, data) {
  // For simplicity, reload with edit param
  window.location.href = `?tab=bookings&edit=${id}`;
}
</script>
@endpush
@endsection
