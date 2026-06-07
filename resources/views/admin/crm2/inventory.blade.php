@extends('layouts.admin')
@section('title', 'CRM Inventory')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-boxes"></i> Inventory</h1>
      <p class="crm2-subtitle">Manage price books, quotes, sales orders, purchase orders, invoices, and vendors.</p>
    </div>
    <button class="crm2-btn crm2-btn-primary" onclick="openModal('modal-create-{{ $tab }}')">
      <i class="fas fa-plus"></i> New {{ ucwords(str_replace('_',' ', rtrim($tab,'s'))) }}
    </button>
  </div>

  {{-- Tabs --}}
  <div class="crm2-tabs">
    @foreach(['price_books'=>['fa-tag','Price Books'],'quotes'=>['fa-file-alt','Quotes'],'sales_orders'=>['fa-shopping-cart','Sales Orders'],'purchase_orders'=>['fa-truck','Purchase Orders'],'invoices'=>['fa-file-invoice-dollar','Invoices'],'vendors'=>['fa-store','Vendors']] as $t => [$icon,$label])
    <a href="{{ route('admin.crm2.inventory', ['tab'=>$t]) }}" class="crm2-tab {{ $tab===$t?'active':'' }}">
      <i class="fas {{ $icon }}"></i> {{ $label }}
    </a>
    @endforeach
  </div>

  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

  {{-- Filter --}}
  <div class="crm2-card mb-4">
    <div class="crm2-card-body">
      <form method="GET" class="crm2-filter-form">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div class="filter-group flex-1"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="crm2-input"></div>
        @if($tab === 'quotes')
        <div class="filter-group">
          <select name="stage" class="crm2-select"><option value="">All Stages</option>@foreach(\App\Models\CrmQuote::STAGES as $k=>$v)<option value="{{ $k }}" {{ request('stage')===$k?'selected':'' }}>{{ $v }}</option>@endforeach</select>
        </div>
        @endif
        @if($tab === 'invoices')
        <div class="filter-group">
          <select name="status" class="crm2-select"><option value="">All Status</option>@foreach(\App\Models\CrmInvoice::STATUSES as $k=>$v)<option value="{{ $k }}" {{ request('status')===$k?'selected':'' }}>{{ $v }}</option>@endforeach</select>
        </div>
        @endif
        <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('admin.crm2.inventory', ['tab'=>$tab]) }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
      </form>
    </div>
  </div>

  {{-- PRICE BOOKS --}}
  @if($tab === 'price_books')
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>Name</th><th>Description</th><th>Pricing %</th><th>Active</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($priceBooks as $pb)
          <tr>
            <td><strong>{{ $pb->name }}</strong></td>
            <td>{{ Str::limit($pb->description, 60) ?? '—' }}</td>
            <td>{{ $pb->pricing_percentage }}%</td>
            <td><span class="crm2-badge {{ $pb->is_active ? 'status-active' : 'status-inactive' }}">{{ $pb->is_active ? 'Active' : 'Inactive' }}</span></td>
            <td class="actions-cell">
              <form method="POST" action="{{ route('admin.crm2.inventory.destroy', ['type'=>'price_book','id'=>$pb->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
            </td>
          </tr>
          @empty
          <tr><td colspan="5"><div class="crm2-empty"><i class="fas fa-tag"></i><p>No price books yet.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- QUOTES --}}
  @elseif($tab === 'quotes')
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>Quote #</th><th>Subject</th><th>Account</th><th>Stage</th><th>Valid Until</th><th>Total</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($quotes as $q)
          <tr>
            <td><code>{{ $q->quote_number }}</code></td>
            <td><strong>{{ $q->subject }}</strong></td>
            <td>{{ $q->account?->name ?? '—' }}</td>
            <td><span class="crm2-badge stage-{{ $q->stage }}">{{ \App\Models\CrmQuote::STAGES[$q->stage] ?? $q->stage }}</span></td>
            <td>{{ $q->valid_until?->format('d M Y') ?? '—' }}</td>
            <td>₹{{ number_format($q->total, 2) }}</td>
            <td class="actions-cell">
              <form method="POST" action="{{ route('admin.crm2.inventory.destroy', ['type'=>'quote','id'=>$q->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
            </td>
          </tr>
          @empty
          <tr><td colspan="7"><div class="crm2-empty"><i class="fas fa-file-alt"></i><p>No quotes yet.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($quotes->hasPages())<div class="crm2-pagination">{{ $quotes->links() }}</div>@endif
  </div>

  {{-- SALES ORDERS --}}
  @elseif($tab === 'sales_orders')
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>SO #</th><th>Subject</th><th>Account</th><th>Status</th><th>Delivery</th><th>Total</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($salesOrders as $so)
          <tr>
            <td><code>{{ $so->so_number }}</code></td>
            <td><strong>{{ $so->subject }}</strong></td>
            <td>{{ $so->account?->name ?? '—' }}</td>
            <td><span class="crm2-badge status-{{ $so->status }}">{{ \App\Models\CrmSalesOrder::STATUSES[$so->status] ?? $so->status }}</span></td>
            <td>{{ $so->delivery_date?->format('d M Y') ?? '—' }}</td>
            <td>₹{{ number_format($so->total, 2) }}</td>
            <td class="actions-cell">
              <form method="POST" action="{{ route('admin.crm2.inventory.destroy', ['type'=>'sales_order','id'=>$so->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
            </td>
          </tr>
          @empty
          <tr><td colspan="7"><div class="crm2-empty"><i class="fas fa-shopping-cart"></i><p>No sales orders yet.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($salesOrders->hasPages())<div class="crm2-pagination">{{ $salesOrders->links() }}</div>@endif
  </div>

  {{-- PURCHASE ORDERS --}}
  @elseif($tab === 'purchase_orders')
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>PO #</th><th>Subject</th><th>Vendor</th><th>Status</th><th>Expected Delivery</th><th>Total</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($purchaseOrders as $po)
          <tr>
            <td><code>{{ $po->po_number }}</code></td>
            <td><strong>{{ $po->subject }}</strong></td>
            <td>{{ $po->vendor?->name ?? '—' }}</td>
            <td><span class="crm2-badge status-{{ $po->status }}">{{ \App\Models\CrmPurchaseOrder::STATUSES[$po->status] ?? $po->status }}</span></td>
            <td>{{ $po->expected_delivery?->format('d M Y') ?? '—' }}</td>
            <td>₹{{ number_format($po->total, 2) }}</td>
            <td class="actions-cell">
              <form method="POST" action="{{ route('admin.crm2.inventory.destroy', ['type'=>'purchase_order','id'=>$po->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
            </td>
          </tr>
          @empty
          <tr><td colspan="7"><div class="crm2-empty"><i class="fas fa-truck"></i><p>No purchase orders yet.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($purchaseOrders->hasPages())<div class="crm2-pagination">{{ $purchaseOrders->links() }}</div>@endif
  </div>

  {{-- INVOICES --}}
  @elseif($tab === 'invoices')
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>Invoice #</th><th>Subject</th><th>Account</th><th>Status</th><th>Due Date</th><th>Total</th><th>Balance</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($invoices as $inv)
          <tr>
            <td><code>{{ $inv->invoice_number }}</code></td>
            <td><strong>{{ $inv->subject }}</strong></td>
            <td>{{ $inv->account?->name ?? '—' }}</td>
            <td><span class="crm2-badge status-{{ $inv->status }}">{{ \App\Models\CrmInvoice::STATUSES[$inv->status] ?? $inv->status }}</span></td>
            <td class="{{ $inv->due_date?->isPast() && $inv->status !== 'paid' ? 'text-danger' : '' }}">{{ $inv->due_date?->format('d M Y') ?? '—' }}</td>
            <td>₹{{ number_format($inv->total, 2) }}</td>
            <td>₹{{ number_format($inv->balance_due, 2) }}</td>
            <td class="actions-cell">
              <form method="POST" action="{{ route('admin.crm2.inventory.destroy', ['type'=>'invoice','id'=>$inv->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
            </td>
          </tr>
          @empty
          <tr><td colspan="8"><div class="crm2-empty"><i class="fas fa-file-invoice-dollar"></i><p>No invoices yet.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($invoices->hasPages())<div class="crm2-pagination">{{ $invoices->links() }}</div>@endif
  </div>

  {{-- VENDORS --}}
  @elseif($tab === 'vendors')
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($vendors as $v)
          <tr>
            <td><strong>{{ $v->name }}</strong></td>
            <td>{{ $v->email ?? '—' }}</td>
            <td>{{ $v->phone ?? '—' }}</td>
            <td>{{ $v->category ?? '—' }}</td>
            <td><span class="crm2-badge status-{{ $v->status }}">{{ ucfirst($v->status) }}</span></td>
            <td class="actions-cell">
              <form method="POST" action="{{ route('admin.crm2.inventory.destroy', ['type'=>'vendor','id'=>$v->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6"><div class="crm2-empty"><i class="fas fa-store"></i><p>No vendors yet.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($vendors->hasPages())<div class="crm2-pagination">{{ $vendors->links() }}</div>@endif
  </div>
  @endif

  {{-- ══ CREATE MODALS ══ --}}
  {{-- Price Book --}}
  <div class="crm2-modal-overlay" id="modal-create-price_books">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-tag"></i> New Price Book</h3><button onclick="closeModal('modal-create-price_books')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.inventory.store') }}">@csrf
        <input type="hidden" name="_type" value="price_book">
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Name *</label><input type="text" name="name" class="crm2-input" required></div>
          <div class="form-group"><label>Pricing % (markup/discount)</label><input type="number" name="pricing_percentage" class="crm2-input" step="0.01" value="0"></div>
          <div class="form-group"><label>Active</label><select name="is_active" class="crm2-select"><option value="1">Yes</option><option value="0">No</option></select></div>
          <div class="form-group full"><label>Description</label><textarea name="description" class="crm2-textarea" rows="2"></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-price_books')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save</button></div>
      </form>
    </div>
  </div>

  {{-- Quote --}}
  <div class="crm2-modal-overlay" id="modal-create-quotes">
    <div class="crm2-modal crm2-modal-lg">
      <div class="crm2-modal-header"><h3><i class="fas fa-file-alt"></i> New Quote</h3><button onclick="closeModal('modal-create-quotes')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.inventory.store') }}">@csrf
        <input type="hidden" name="_type" value="quote">
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Subject *</label><input type="text" name="subject" class="crm2-input" required></div>
          <div class="form-group"><label>Account</label><select name="account_id" class="crm2-select"><option value="">— None —</option>@foreach($accounts_list as $a)<option value="{{ $a->id }}">{{ $a->name }}</option>@endforeach</select></div>
          <div class="form-group"><label>Contact</label><select name="contact_id" class="crm2-select"><option value="">— None —</option>@foreach($contacts_list as $c)<option value="{{ $c->id }}">{{ $c->first_name }} {{ $c->last_name }}</option>@endforeach</select></div>
          <div class="form-group"><label>Stage</label><select name="stage" class="crm2-select">@foreach(\App\Models\CrmQuote::STAGES as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
          <div class="form-group"><label>Valid Until</label><input type="date" name="valid_until" class="crm2-input"></div>
          @include('admin.crm2._inventory_items_form')
          <div class="form-group full"><label>Terms & Conditions</label><textarea name="terms" class="crm2-textarea" rows="2"></textarea></div>
          <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="2"></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-quotes')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Quote</button></div>
      </form>
    </div>
  </div>

  {{-- Sales Order --}}
  <div class="crm2-modal-overlay" id="modal-create-sales_orders">
    <div class="crm2-modal crm2-modal-lg">
      <div class="crm2-modal-header"><h3><i class="fas fa-shopping-cart"></i> New Sales Order</h3><button onclick="closeModal('modal-create-sales_orders')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.inventory.store') }}">@csrf
        <input type="hidden" name="_type" value="sales_order">
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Subject *</label><input type="text" name="subject" class="crm2-input" required></div>
          <div class="form-group"><label>Account</label><select name="account_id" class="crm2-select"><option value="">— None —</option>@foreach($accounts_list as $a)<option value="{{ $a->id }}">{{ $a->name }}</option>@endforeach</select></div>
          <div class="form-group"><label>Status</label><select name="status" class="crm2-select">@foreach(\App\Models\CrmSalesOrder::STATUSES as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
          <div class="form-group"><label>Delivery Date</label><input type="date" name="delivery_date" class="crm2-input"></div>
          @include('admin.crm2._inventory_items_form')
          <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="2"></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-sales_orders')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save</button></div>
      </form>
    </div>
  </div>

  {{-- Purchase Order --}}
  <div class="crm2-modal-overlay" id="modal-create-purchase_orders">
    <div class="crm2-modal crm2-modal-lg">
      <div class="crm2-modal-header"><h3><i class="fas fa-truck"></i> New Purchase Order</h3><button onclick="closeModal('modal-create-purchase_orders')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.inventory.store') }}">@csrf
        <input type="hidden" name="_type" value="purchase_order">
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Subject *</label><input type="text" name="subject" class="crm2-input" required></div>
          <div class="form-group"><label>Vendor</label><select name="vendor_id" class="crm2-select"><option value="">— None —</option>@foreach($vendors_list as $v)<option value="{{ $v->id }}">{{ $v->name }}</option>@endforeach</select></div>
          <div class="form-group"><label>Status</label><select name="status" class="crm2-select">@foreach(\App\Models\CrmPurchaseOrder::STATUSES as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
          <div class="form-group"><label>Expected Delivery</label><input type="date" name="expected_delivery" class="crm2-input"></div>
          @include('admin.crm2._inventory_items_form')
          <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="2"></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-purchase_orders')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save</button></div>
      </form>
    </div>
  </div>

  {{-- Invoice --}}
  <div class="crm2-modal-overlay" id="modal-create-invoices">
    <div class="crm2-modal crm2-modal-lg">
      <div class="crm2-modal-header"><h3><i class="fas fa-file-invoice-dollar"></i> New Invoice</h3><button onclick="closeModal('modal-create-invoices')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.inventory.store') }}">@csrf
        <input type="hidden" name="_type" value="invoice">
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Subject *</label><input type="text" name="subject" class="crm2-input" required></div>
          <div class="form-group"><label>Account</label><select name="account_id" class="crm2-select"><option value="">— None —</option>@foreach($accounts_list as $a)<option value="{{ $a->id }}">{{ $a->name }}</option>@endforeach</select></div>
          <div class="form-group"><label>Status</label><select name="status" class="crm2-select">@foreach(\App\Models\CrmInvoice::STATUSES as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
          <div class="form-group"><label>Due Date</label><input type="date" name="due_date" class="crm2-input"></div>
          @include('admin.crm2._inventory_items_form')
          <div class="form-group"><label>Amount Paid (₹)</label><input type="number" name="amount_paid" class="crm2-input" step="0.01" value="0"></div>
          <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="2"></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-invoices')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Invoice</button></div>
      </form>
    </div>
  </div>

  {{-- Vendor --}}
  <div class="crm2-modal-overlay" id="modal-create-vendors">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-store"></i> New Vendor</h3><button onclick="closeModal('modal-create-vendors')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.inventory.store') }}">@csrf
        <input type="hidden" name="_type" value="vendor">
        <div class="crm2-modal-body"><div class="crm2-form-grid">
          <div class="form-group full"><label>Name *</label><input type="text" name="name" class="crm2-input" required></div>
          <div class="form-group"><label>Email</label><input type="email" name="email" class="crm2-input"></div>
          <div class="form-group"><label>Phone</label><input type="text" name="phone" class="crm2-input"></div>
          <div class="form-group"><label>Website</label><input type="url" name="website" class="crm2-input"></div>
          <div class="form-group"><label>Category</label><input type="text" name="category" class="crm2-input"></div>
          <div class="form-group"><label>Status</label><select name="status" class="crm2-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
          <div class="form-group full"><label>Address</label><textarea name="address" class="crm2-textarea" rows="2"></textarea></div>
          <div class="form-group full"><label>Description</label><textarea name="description" class="crm2-textarea" rows="2"></textarea></div>
        </div></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-vendors')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Vendor</button></div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
</script>
@endpush
@endsection
