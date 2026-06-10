@extends('layouts.admin')
@section('title', 'CRM Sales')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-chart-line"></i> Sales</h1>
      <p class="crm2-subtitle">Manage your leads, contacts, accounts, deals and forecasts.</p>
    </div>
    <button class="crm2-btn crm2-btn-primary" onclick="openModal('modal-create-{{ $tab }}')">
      <i class="fas fa-plus"></i> New {{ ucfirst($tab === 'sales_orders' ? 'Sales Order' : rtrim($tab,'s')) }}
    </button>
  </div>

  {{-- Tabs --}}
  <div class="crm2-tabs">
    @foreach(['leads'=>'fa-user-tag','contacts'=>'fa-address-book','accounts'=>'fa-building','deals'=>'fa-funnel-dollar','forecasts'=>'fa-chart-pie'] as $t => $icon)
    <a href="{{ route('admin.crm2.sales', ['tab'=>$t]) }}" class="crm2-tab {{ $tab===$t?'active':'' }}">
      <i class="fas {{ $icon }}"></i> {{ ucfirst($t) }}
    </a>
    @endforeach
  </div>

  {{-- Flash --}}
  @if(session('success'))<div class="crm2-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  @if(session('error'))<div class="crm2-alert danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif

  {{-- Filter Bar --}}
  <div class="crm2-card mb-4">
    <div class="crm2-card-body">
      <form method="GET" class="crm2-filter-form">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div class="filter-group flex-1">
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Search {{ $tab }}..." class="crm2-input">
        </div>
        @if($tab === 'accounts')
        <div class="filter-group">
          <select name="type" class="crm2-select">
            <option value="">All Types</option>
            @foreach(['prospect','customer','partner','vendor'] as $t)<option value="{{ $t }}" {{ request('type')===$t?'selected':'' }}>{{ ucfirst($t) }}</option>@endforeach
          </select>
        </div>
        @endif
        @if($tab === 'deals')
        <div class="filter-group">
          <select name="stage" class="crm2-select">
            <option value="">All Stages</option>
            @foreach(['prospecting','qualification','proposal','negotiation','closed_won','closed_lost'] as $s)<option value="{{ $s }}" {{ request('stage')===$s?'selected':'' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>@endforeach
          </select>
        </div>
        @endif
        @if($tab === 'leads')
        <div class="filter-group">
          <select name="status" class="crm2-select">
            <option value="">All Status</option>
            @foreach(['new','contacted','qualified','proposal','won','lost'] as $s)<option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach
          </select>
        </div>
        @endif
        <button type="submit" class="crm2-btn crm2-btn-secondary"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('admin.crm2.sales', ['tab'=>$tab]) }}" class="crm2-btn crm2-btn-ghost"><i class="fas fa-times"></i></a>
      </form>
    </div>
  </div>

  {{-- ── LEADS TAB ── --}}
  @if($tab === 'leads')
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>Name</th><th>Email</th><th>Company</th><th>Source</th><th>Status</th><th>Value</th><th>Created</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($leads as $lead)
          <tr>
            <td><strong>{{ $lead->name }}</strong></td>
            <td>{{ $lead->email ?? '—' }}</td>
            <td>{{ $lead->company ?? '—' }}</td>
            <td>{{ ucfirst($lead->source ?? 'manual') }}</td>
            <td><span class="crm2-badge status-{{ $lead->status ?? 'new' }}">{{ ucfirst($lead->status ?? 'New') }}</span></td>
            <td>{{ $lead->deal_value ? '₹'.number_format($lead->deal_value,0) : '—' }}</td>
            <td>{{ $lead->created_at->format('d M Y') }}</td>
            <td class="actions-cell">
              <button class="crm2-icon-btn edit" onclick='editRecord("lead", {{ $lead->id }}, @json($lead))' title="Edit"><i class="fas fa-edit"></i></button>
              @if(($lead->conversations_count ?? 0) > 0 || in_array($lead->source, ['chatbot','ai_chatbot']))
              <a href="{{ route('admin.crm.conversations') }}" class="crm2-icon-btn" style="color:#6366f1;text-decoration:none;" title="View AI Conversations ({{ $lead->conversations_count ?? 0 }} messages)"><i class="fas fa-comments"></i></a>
              @endif
              <form method="POST" action="{{ route('admin.crm2.sales.destroy', ['type'=>'lead','id'=>$lead->id]) }}" onsubmit="return confirm('Delete this lead?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete" title="Delete"><i class="fas fa-trash"></i></button></form>
            </td>
          </tr>
          @empty
          <tr><td colspan="8"><div class="crm2-empty"><i class="fas fa-user-tag"></i><p>No leads found.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($leads->hasPages())<div class="crm2-pagination">{{ $leads->links() }}</div>@endif
  </div>

  {{-- ── CONTACTS TAB ── --}}
  @elseif($tab === 'contacts')
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Job Title</th><th>Account</th><th>Source</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($contacts as $c)
          <tr>
            <td><strong>{{ $c->full_name }}</strong></td>
            <td>{{ $c->email ?? '—' }}</td>
            <td>{{ $c->phone ?? '—' }}</td>
            <td>{{ $c->job_title ?? '—' }}</td>
            <td>{{ $c->account?->name ?? '—' }}</td>
            <td>{{ ucfirst($c->source) }}</td>
            <td><span class="crm2-badge status-{{ $c->status }}">{{ ucfirst($c->status) }}</span></td>
            <td class="actions-cell">
              <button class="crm2-icon-btn edit" onclick='editRecord("contact", {{ $c->id }}, @json($c))' title="Edit"><i class="fas fa-edit"></i></button>
              <form method="POST" action="{{ route('admin.crm2.sales.destroy', ['type'=>'contact','id'=>$c->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
            </td>
          </tr>
          @empty
          <tr><td colspan="8"><div class="crm2-empty"><i class="fas fa-address-book"></i><p>No contacts found.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($contacts->hasPages())<div class="crm2-pagination">{{ $contacts->links() }}</div>@endif
  </div>

  {{-- ── ACCOUNTS TAB ── --}}
  @elseif($tab === 'accounts')
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>Name</th><th>Type</th><th>Industry</th><th>Email</th><th>Phone</th><th>Contacts</th><th>Deals</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($accounts as $acc)
          <tr>
            <td><strong>{{ $acc->name }}</strong></td>
            <td><span class="crm2-badge type-{{ $acc->type }}">{{ ucfirst($acc->type) }}</span></td>
            <td>{{ $acc->industry ?? '—' }}</td>
            <td>{{ $acc->email ?? '—' }}</td>
            <td>{{ $acc->phone ?? '—' }}</td>
            <td>{{ $acc->contacts_count }}</td>
            <td>{{ $acc->deals_count }}</td>
            <td><span class="crm2-badge status-{{ $acc->status }}">{{ ucfirst($acc->status) }}</span></td>
            <td class="actions-cell">
              <button class="crm2-icon-btn edit" onclick='editRecord("account", {{ $acc->id }}, @json($acc))' title="Edit"><i class="fas fa-edit"></i></button>
              <form method="POST" action="{{ route('admin.crm2.sales.destroy', ['type'=>'account','id'=>$acc->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
            </td>
          </tr>
          @empty
          <tr><td colspan="9"><div class="crm2-empty"><i class="fas fa-building"></i><p>No accounts found.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($accounts->hasPages())<div class="crm2-pagination">{{ $accounts->links() }}</div>@endif
  </div>

  {{-- ── DEALS TAB ── --}}
  @elseif($tab === 'deals')
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>Title</th><th>Account</th><th>Contact</th><th>Value</th><th>Stage</th><th>Probability</th><th>Close Date</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($deals as $deal)
          <tr>
            <td><strong>{{ $deal->title }}</strong></td>
            <td>{{ $deal->account?->name ?? '—' }}</td>
            <td>{{ $deal->contact?->full_name ?? '—' }}</td>
            <td>₹{{ number_format($deal->value, 0) }}</td>
            <td><span class="crm2-badge stage-{{ $deal->stage }}">{{ $deal->stage_label }}</span></td>
            <td>
              <div class="crm2-progress-wrap"><div class="crm2-progress-bar" style="width:{{ $deal->probability }}%"></div></div>
              <small>{{ $deal->probability }}%</small>
            </td>
            <td>{{ $deal->expected_close?->format('d M Y') ?? '—' }}</td>
            <td class="actions-cell">
              <button class="crm2-icon-btn edit" onclick='editRecord("deal", {{ $deal->id }}, @json($deal))' title="Edit"><i class="fas fa-edit"></i></button>
              <form method="POST" action="{{ route('admin.crm2.sales.destroy', ['type'=>'deal','id'=>$deal->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
            </td>
          </tr>
          @empty
          <tr><td colspan="8"><div class="crm2-empty"><i class="fas fa-funnel-dollar"></i><p>No deals found.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($deals->hasPages())<div class="crm2-pagination">{{ $deals->links() }}</div>@endif
  </div>

  {{-- ── FORECASTS TAB ── --}}
  @elseif($tab === 'forecasts')
  <div class="crm2-card">
    <div class="crm2-card-body p-0">
      <table class="crm2-table">
        <thead><tr><th>Year</th><th>Quarter</th><th>Target</th><th>Achieved</th><th>Achievement %</th><th>Notes</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($forecasts as $f)
          <tr>
            <td>{{ $f->year }}</td>
            <td>Q{{ $f->quarter }}</td>
            <td>₹{{ number_format($f->target_amount, 0) }}</td>
            <td>₹{{ number_format($f->achieved_amount, 0) }}</td>
            <td>
              <div class="crm2-progress-wrap"><div class="crm2-progress-bar {{ $f->achievement_percent >= 100 ? 'green' : ($f->achievement_percent >= 60 ? 'yellow' : 'red') }}" style="width:{{ min(100,$f->achievement_percent) }}%"></div></div>
              <small>{{ $f->achievement_percent }}%</small>
            </td>
            <td>{{ Str::limit($f->notes, 40) ?? '—' }}</td>
            <td class="actions-cell">
              <button class="crm2-icon-btn edit" onclick='editRecord("forecast", {{ $f->id }}, @json($f))' title="Edit"><i class="fas fa-edit"></i></button>
              <form method="POST" action="{{ route('admin.crm2.sales.destroy', ['type'=>'forecast','id'=>$f->id]) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="crm2-icon-btn delete"><i class="fas fa-trash"></i></button></form>
            </td>
          </tr>
          @empty
          <tr><td colspan="7"><div class="crm2-empty"><i class="fas fa-chart-pie"></i><p>No forecasts yet.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($forecasts->hasPages())<div class="crm2-pagination">{{ $forecasts->links() }}</div>@endif
  </div>
  @endif

  {{-- ══ CREATE MODALS ══ --}}
  {{-- Lead --}}
  <div class="crm2-modal-overlay" id="modal-create-leads">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-user-tag"></i> New Lead</h3><button onclick="closeModal('modal-create-leads')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.sales.store') }}">@csrf
        <input type="hidden" name="_type" value="lead">
        <div class="crm2-modal-body">
          <div class="crm2-form-grid">
            <div class="form-group"><label>Name *</label><input type="text" name="name" class="crm2-input" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" class="crm2-input"></div>
            <div class="form-group"><label>Phone</label><input type="text" name="phone" class="crm2-input"></div>
            <div class="form-group"><label>Company</label><input type="text" name="company" class="crm2-input"></div>
            <div class="form-group"><label>Source</label>
              <select name="source" class="crm2-select"><option value="manual">Manual</option><option value="website">Website</option><option value="referral">Referral</option><option value="linkedin">LinkedIn</option><option value="ai_chatbot">AI Chatbot</option><option value="other">Other</option></select>
            </div>
            <div class="form-group"><label>Status</label>
              <select name="status" class="crm2-select"><option value="new">New</option><option value="contacted">Contacted</option><option value="qualified">Qualified</option><option value="proposal">Proposal</option><option value="won">Won</option><option value="lost">Lost</option></select>
            </div>
            <div class="form-group"><label>Deal Value</label><input type="number" name="deal_value" class="crm2-input" step="0.01"></div>
            <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="3"></textarea></div>
          </div>
        </div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-leads')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Lead</button></div>
      </form>
    </div>
  </div>

  {{-- Contact --}}
  <div class="crm2-modal-overlay" id="modal-create-contacts">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-address-book"></i> New Contact</h3><button onclick="closeModal('modal-create-contacts')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.sales.store') }}">@csrf
        <input type="hidden" name="_type" value="contact">
        <div class="crm2-modal-body">
          <div class="crm2-form-grid">
            <div class="form-group"><label>First Name *</label><input type="text" name="first_name" class="crm2-input" required></div>
            <div class="form-group"><label>Last Name</label><input type="text" name="last_name" class="crm2-input"></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" class="crm2-input"></div>
            <div class="form-group"><label>Phone</label><input type="text" name="phone" class="crm2-input"></div>
            <div class="form-group"><label>Job Title</label><input type="text" name="job_title" class="crm2-input"></div>
            <div class="form-group"><label>Account</label>
              <select name="account_id" class="crm2-select"><option value="">— None —</option>@foreach($accounts_list as $a)<option value="{{ $a->id }}">{{ $a->name }}</option>@endforeach</select>
            </div>
            <div class="form-group"><label>Source</label>
              <select name="source" class="crm2-select"><option value="manual">Manual</option><option value="website">Website</option><option value="referral">Referral</option><option value="linkedin">LinkedIn</option><option value="ai_chatbot">AI Chatbot</option><option value="other">Other</option></select>
            </div>
            <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="2"></textarea></div>
          </div>
        </div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-contacts')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Contact</button></div>
      </form>
    </div>
  </div>

  {{-- Account --}}
  <div class="crm2-modal-overlay" id="modal-create-accounts">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-building"></i> New Account</h3><button onclick="closeModal('modal-create-accounts')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.sales.store') }}">@csrf
        <input type="hidden" name="_type" value="account">
        <div class="crm2-modal-body">
          <div class="crm2-form-grid">
            <div class="form-group"><label>Name *</label><input type="text" name="name" class="crm2-input" required></div>
            <div class="form-group"><label>Type *</label>
              <select name="type" class="crm2-select" required><option value="prospect">Prospect</option><option value="customer">Customer</option><option value="partner">Partner</option><option value="vendor">Vendor</option></select>
            </div>
            <div class="form-group"><label>Industry</label><input type="text" name="industry" class="crm2-input"></div>
            <div class="form-group"><label>Website</label><input type="url" name="website" class="crm2-input"></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" class="crm2-input"></div>
            <div class="form-group"><label>Phone</label><input type="text" name="phone" class="crm2-input"></div>
            <div class="form-group"><label>City</label><input type="text" name="city" class="crm2-input"></div>
            <div class="form-group"><label>Country</label><input type="text" name="country" class="crm2-input"></div>
            <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="2"></textarea></div>
          </div>
        </div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-accounts')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Account</button></div>
      </form>
    </div>
  </div>

  {{-- Deal --}}
  <div class="crm2-modal-overlay" id="modal-create-deals">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-funnel-dollar"></i> New Deal</h3><button onclick="closeModal('modal-create-deals')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.sales.store') }}">@csrf
        <input type="hidden" name="_type" value="deal">
        <div class="crm2-modal-body">
          <div class="crm2-form-grid">
            <div class="form-group full"><label>Title *</label><input type="text" name="title" class="crm2-input" required></div>
            <div class="form-group"><label>Value (₹)</label><input type="number" name="value" class="crm2-input" step="0.01"></div>
            <div class="form-group"><label>Stage *</label>
              <select name="stage" class="crm2-select" required><option value="prospecting">Prospecting</option><option value="qualification">Qualification</option><option value="proposal">Proposal</option><option value="negotiation">Negotiation</option><option value="closed_won">Closed Won</option><option value="closed_lost">Closed Lost</option></select>
            </div>
            <div class="form-group"><label>Account</label>
              <select name="account_id" class="crm2-select"><option value="">— None —</option>@foreach($accounts_list as $a)<option value="{{ $a->id }}">{{ $a->name }}</option>@endforeach</select>
            </div>
            <div class="form-group"><label>Contact</label>
              <select name="contact_id" class="crm2-select"><option value="">— None —</option>@foreach($contacts_list as $c)<option value="{{ $c->id }}">{{ $c->first_name }} {{ $c->last_name }}</option>@endforeach</select>
            </div>
            <div class="form-group"><label>Expected Close</label><input type="date" name="expected_close" class="crm2-input"></div>
            <div class="form-group"><label>Probability (%)</label><input type="number" name="probability" class="crm2-input" min="0" max="100" value="10"></div>
            <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="2"></textarea></div>
          </div>
        </div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-deals')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Deal</button></div>
      </form>
    </div>
  </div>

  {{-- Forecast --}}
  <div class="crm2-modal-overlay" id="modal-create-forecasts">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3><i class="fas fa-chart-pie"></i> New Forecast</h3><button onclick="closeModal('modal-create-forecasts')"><i class="fas fa-times"></i></button></div>
      <form method="POST" action="{{ route('admin.crm2.sales.store') }}">@csrf
        <input type="hidden" name="_type" value="forecast">
        <div class="crm2-modal-body">
          <div class="crm2-form-grid">
            <div class="form-group"><label>Year *</label><input type="number" name="year" class="crm2-input" value="{{ date('Y') }}" required></div>
            <div class="form-group"><label>Quarter *</label>
              <select name="quarter" class="crm2-select" required><option value="1">Q1 (Jan–Mar)</option><option value="2">Q2 (Apr–Jun)</option><option value="3">Q3 (Jul–Sep)</option><option value="4">Q4 (Oct–Dec)</option></select>
            </div>
            <div class="form-group"><label>Target Amount (₹) *</label><input type="number" name="target_amount" class="crm2-input" step="0.01" required></div>
            <div class="form-group"><label>Achieved Amount (₹)</label><input type="number" name="achieved_amount" class="crm2-input" step="0.01" value="0"></div>
            <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="2"></textarea></div>
          </div>
        </div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-create-forecasts')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Save Forecast</button></div>
      </form>
    </div>
  </div>

  {{-- Edit Modal (shared, filled via JS) --}}
  <div class="crm2-modal-overlay" id="modal-edit-record">
    <div class="crm2-modal">
      <div class="crm2-modal-header"><h3 id="edit-modal-title">Edit Record</h3><button onclick="closeModal('modal-edit-record')"><i class="fas fa-times"></i></button></div>
      <form id="edit-record-form" method="POST">@csrf @method('PATCH')
        <div class="crm2-modal-body" id="edit-modal-body"></div>
        <div class="crm2-modal-footer"><button type="button" onclick="closeModal('modal-edit-record')" class="crm2-btn crm2-btn-ghost">Cancel</button><button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-save"></i> Update</button></div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

function editRecord(type, id, data) {
  const form = document.getElementById('edit-record-form');
  form.action = `/admin/crm2/sales/${type}/${id}`;
  document.getElementById('edit-modal-title').innerHTML = `<i class="fas fa-edit"></i> Edit ${type.charAt(0).toUpperCase()+type.slice(1)}`;

  let html = '';
  if (type === 'lead') {
    html = `<div class="crm2-form-grid">
      <div class="form-group"><label>Name *</label><input name="name" class="crm2-input" value="${esc(data.name)}" required></div>
      <div class="form-group"><label>Email</label><input name="email" class="crm2-input" value="${esc(data.email)}"></div>
      <div class="form-group"><label>Phone</label><input name="phone" class="crm2-input" value="${esc(data.phone)}"></div>
      <div class="form-group"><label>Company</label><input name="company" class="crm2-input" value="${esc(data.company)}"></div>
      <div class="form-group"><label>Status</label><select name="status" class="crm2-select">${['new','contacted','qualified','proposal','won','lost'].map(s=>`<option value="${s}" ${data.status===s?'selected':''}>${s.charAt(0).toUpperCase()+s.slice(1)}</option>`).join('')}</select></div>
      <div class="form-group"><label>Deal Value</label><input name="deal_value" type="number" class="crm2-input" value="${data.deal_value||''}"></div>
      <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="2">${esc(data.notes)}</textarea></div>
    </div>`;
  } else if (type === 'account') {
    html = `<div class="crm2-form-grid">
      <div class="form-group"><label>Name *</label><input name="name" class="crm2-input" value="${esc(data.name)}" required></div>
      <div class="form-group"><label>Type</label><select name="type" class="crm2-select">${['prospect','customer','partner','vendor'].map(t=>`<option value="${t}" ${data.type===t?'selected':''}>${t.charAt(0).toUpperCase()+t.slice(1)}</option>`).join('')}</select></div>
      <div class="form-group"><label>Industry</label><input name="industry" class="crm2-input" value="${esc(data.industry)}"></div>
      <div class="form-group"><label>Email</label><input name="email" class="crm2-input" value="${esc(data.email)}"></div>
      <div class="form-group"><label>Phone</label><input name="phone" class="crm2-input" value="${esc(data.phone)}"></div>
      <div class="form-group"><label>Status</label><select name="status" class="crm2-select"><option value="active" ${data.status==='active'?'selected':''}>Active</option><option value="inactive" ${data.status==='inactive'?'selected':''}>Inactive</option></select></div>
    </div>`;
  } else if (type === 'deal') {
    html = `<div class="crm2-form-grid">
      <div class="form-group full"><label>Title *</label><input name="title" class="crm2-input" value="${esc(data.title)}" required></div>
      <div class="form-group"><label>Value (₹)</label><input name="value" type="number" class="crm2-input" value="${data.value||''}"></div>
      <div class="form-group"><label>Stage</label><select name="stage" class="crm2-select">${['prospecting','qualification','proposal','negotiation','closed_won','closed_lost'].map(s=>`<option value="${s}" ${data.stage===s?'selected':''}>${s.replace('_',' ').replace(/\b\w/g,l=>l.toUpperCase())}</option>`).join('')}</select></div>
      <div class="form-group"><label>Probability (%)</label><input name="probability" type="number" class="crm2-input" value="${data.probability||10}" min="0" max="100"></div>
      <div class="form-group"><label>Expected Close</label><input name="expected_close" type="date" class="crm2-input" value="${data.expected_close||''}"></div>
      <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="2">${esc(data.notes)}</textarea></div>
    </div>`;
  } else if (type === 'contact') {
    html = `<div class="crm2-form-grid">
      <div class="form-group"><label>First Name *</label><input name="first_name" class="crm2-input" value="${esc(data.first_name)}" required></div>
      <div class="form-group"><label>Last Name</label><input name="last_name" class="crm2-input" value="${esc(data.last_name)}"></div>
      <div class="form-group"><label>Email</label><input name="email" class="crm2-input" value="${esc(data.email)}"></div>
      <div class="form-group"><label>Phone</label><input name="phone" class="crm2-input" value="${esc(data.phone)}"></div>
      <div class="form-group"><label>Job Title</label><input name="job_title" class="crm2-input" value="${esc(data.job_title)}"></div>
      <div class="form-group"><label>Status</label><select name="status" class="crm2-select"><option value="active" ${data.status==='active'?'selected':''}>Active</option><option value="inactive" ${data.status==='inactive'?'selected':''}>Inactive</option></select></div>
    </div>`;
  } else if (type === 'forecast') {
    html = `<div class="crm2-form-grid">
      <div class="form-group"><label>Year</label><input name="year" type="number" class="crm2-input" value="${data.year}"></div>
      <div class="form-group"><label>Quarter</label><select name="quarter" class="crm2-select">${[1,2,3,4].map(q=>`<option value="${q}" ${data.quarter==q?'selected':''}>Q${q}</option>`).join('')}</select></div>
      <div class="form-group"><label>Target (₹)</label><input name="target_amount" type="number" class="crm2-input" value="${data.target_amount}"></div>
      <div class="form-group"><label>Achieved (₹)</label><input name="achieved_amount" type="number" class="crm2-input" value="${data.achieved_amount}"></div>
      <div class="form-group full"><label>Notes</label><textarea name="notes" class="crm2-textarea" rows="2">${esc(data.notes)}</textarea></div>
    </div>`;
  }
  document.getElementById('edit-modal-body').innerHTML = html;
  openModal('modal-edit-record');
}

function esc(v) { return v ? String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;') : ''; }
</script>
@endpush
@endsection
