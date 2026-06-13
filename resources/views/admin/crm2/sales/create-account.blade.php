@extends('layouts.admin')
@section('title', 'New Account')
@section('page-title', 'New Account')
@push('styles')
<style>
/* ── Sticky Top Action Bar ─────────────────────────────────────── */
.xn-sticky-bar {
    position: fixed;
    top: 60px;
    left: var(--rail-width, 60px);
    right: 0;
    z-index: 120;
    background: var(--bg-card, #fff);
    border-bottom: 2px solid var(--accent, #6366f1);
    padding: .75rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    box-shadow: 0 3px 12px rgba(0,0,0,.10);
    flex-wrap: wrap;
    will-change: transform;
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    transition: left 0.22s cubic-bezier(0.4,0,0.2,1);
}
body.xn-panel-open .xn-sticky-bar {
    left: calc(var(--rail-width, 60px) + var(--panel-width, 220px));
}
.xn-sticky-spacer { height: 64px; }
.xn-sticky-title {
    display: flex; align-items: center; gap: .5rem;
    font-size: .95rem; font-weight: 700; color: var(--text-primary, #1a1a2e);
}
.xn-sticky-title i { color: var(--accent, #6366f1); }
.xn-sticky-actions { display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; }
.xn-sticky-btn {
    padding: .45rem 1.1rem; border-radius: 7px; font-size: .82rem; font-weight: 600;
    cursor: pointer; border: none; display: inline-flex; align-items: center; gap: .35rem;
    transition: all .18s; text-decoration: none;
}
.xn-sticky-btn-primary { background: var(--accent, #6366f1); color: #fff; }
.xn-sticky-btn-primary:hover { opacity: .88; color: #fff; }
.xn-sticky-btn-outline {
    background: var(--bg-card, #fff); color: var(--accent, #6366f1);
    border: 1px solid var(--accent, #6366f1);
}
.xn-sticky-btn-outline:hover { background: var(--accent, #6366f1); color: #fff; }
.xn-sticky-btn-ghost {
    background: transparent; color: var(--text-secondary, #64748b);
    border: 1px solid var(--border, #e2e8f0);
}
.xn-sticky-btn-ghost:hover { background: var(--bg-primary, #f8fafc); }
</style>
@endpush
@section('content')
<style>
.cf-wrap{max-width:1100px;margin:0 auto;padding:1.5rem}
.cf-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem}
.cf-header h1{font-size:1.4rem;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:.5rem}
.cf-breadcrumb{font-size:.8rem;color:var(--text-muted)}
.cf-breadcrumb a{color:var(--accent);text-decoration:none}
.cf-section{background:var(--bg-card);border:1px solid var(--border);border-radius:10px;margin-bottom:1.2rem;overflow:hidden}
.cf-section-header{display:flex;align-items:center;justify-content:space-between;padding:.75rem 1.2rem;background:var(--bg-primary);border-bottom:1px solid var(--border);cursor:pointer;user-select:none}
.cf-section-title{font-size:.9rem;font-weight:600;color:var(--text-primary);display:flex;align-items:center;gap:.5rem}
.cf-section-title i{color:var(--accent);width:16px}
.cf-section-body{padding:1.2rem;display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:.9rem}
.cf-field{display:flex;flex-direction:column;gap:.3rem}
.cf-field label{font-size:.75rem;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.04em}
.cf-field input,.cf-field select,.cf-field textarea{background:var(--bg-primary);border:1px solid var(--border);border-radius:6px;padding:.45rem .7rem;font-size:.85rem;color:var(--text-primary);width:100%;transition:border-color .2s}
.cf-field input:focus,.cf-field select:focus,.cf-field textarea:focus{outline:none;border-color:var(--accent)}
.cf-field textarea{resize:vertical;min-height:80px}
.cf-actions{display:flex;gap:.75rem;justify-content:flex-end;padding:1rem 0}
.cf-btn{padding:.55rem 1.4rem;border-radius:7px;font-size:.85rem;font-weight:600;cursor:pointer;border:none;display:inline-flex;align-items:center;gap:.4rem;transition:all .2s}
.cf-btn-primary{background:var(--accent);color:#fff}
.cf-btn-primary:hover{opacity:.9}
.cf-btn-ghost{background:transparent;border:1px solid var(--border);color:var(--text-secondary)}
.cf-btn-ghost:hover{background:var(--bg-primary)}
.cf-avatar-upload{display:flex;align-items:center;gap:1rem;grid-column:1/-1}
.cf-avatar-preview{width:72px;height:72px;border-radius:50%;background:var(--bg-primary);border:2px solid var(--border);display:flex;align-items:center;justify-content:center;overflow:hidden}
.cf-avatar-preview img{width:100%;height:100%;object-fit:cover}
.cf-avatar-preview i{font-size:1.8rem;color:var(--text-muted)}
</style>
<div class="cf-wrap">
  {{-- Sticky Top Action Bar --}}
  <div class="xn-sticky-bar">
    <div class="xn-sticky-title">
      <i class="fas fa-building"></i>
      Create New Account
    </div>
    <div class="xn-sticky-actions">
      <a href="{{ route('admin.crm2.sales.accounts') }}" class="xn-sticky-btn xn-sticky-btn-ghost">
        <i class="fas fa-arrow-left"></i> Cancel
      </a>
      <button type="submit" form="accountCreateForm" class="xn-sticky-btn xn-sticky-btn-primary">
        <i class="fas fa-save"></i> Save Account
      </button>
    </div>
  </div>
  <div class="xn-sticky-spacer"></div>

  <div class="cf-header">
    <div>
      <div class="cf-breadcrumb"><a href="{{ route('admin.crm2.sales.accounts') }}">Accounts</a> / New Account</div>
      <h1><i class="fas fa-building"></i> New Account</h1>
    </div>
  </div>
  @if($errors->any())<div class="crm2-alert error" style="margin-bottom:1rem"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>@endif
  <form id="accountCreateForm" method="POST" action="{{ route('admin.crm2.sales.accounts.store') }}" enctype="multipart/form-data">
    @csrf
    {{-- Account Profile --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-building"></i> Account Profile</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-avatar-upload">
          <div class="cf-avatar-preview" id="avatar-preview"><i class="fas fa-building"></i></div>
          <div>
            <label class="cf-btn cf-btn-ghost" style="cursor:pointer"><i class="fas fa-upload"></i> Upload Logo<input type="file" name="account_image" accept="image/*" style="display:none" onchange="previewAvatar(this)"></label>
            <p style="font-size:.75rem;color:var(--text-muted);margin-top:.3rem">JPG, PNG up to 2MB</p>
          </div>
        </div>
        <div class="cf-field">
          <label>Account Owner</label>
          <select name="owner_id">
            <option value="">-- Select Owner --</option>
            @foreach($staff as $s)<option value="{{ $s->id }}" {{ old('owner_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Account Name <span style="color:red">*</span></label>
          <input type="text" name="name" value="{{ old('name') }}" required placeholder="Company name">
        </div>
        <div class="cf-field">
          <label>Account Number</label>
          <input type="text" name="account_number" value="{{ old('account_number') }}" placeholder="ACC-0001">
        </div>
        <div class="cf-field">
          <label>Account Type</label>
          <select name="account_type">
            <option value="">-- None --</option>
            @foreach(['Analyst','Competitor','Customer','Distributor','Integrator','Investor','Partner','Press','Prospect','Reseller','Other'] as $t)
            <option value="{{ $t }}" {{ old('account_type')==$t?'selected':'' }}>{{ $t }}</option>
            @endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Rating</label>
          <select name="rating">
            <option value="">-- None --</option>
            @foreach(['Hot','Warm','Cold'] as $r)<option value="{{ $r }}" {{ old('rating')==$r?'selected':'' }}>{{ $r }}</option>@endforeach
          </select>
        </div>
      </div>
    </div>
    {{-- Company Information --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-industry"></i> Company Information</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field">
          <label>Parent Account</label>
          <select name="parent_account_id">
            <option value="">-- None --</option>
            @foreach($accounts_list as $acc)<option value="{{ $acc->id }}" {{ old('parent_account_id')==$acc->id?'selected':'' }}>{{ $acc->name }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field"><label>Account Site</label><input type="text" name="account_site" value="{{ old('account_site') }}" placeholder="e.g. HQ, Branch"></div>
        <div class="cf-field">
          <label>Industry</label>
          <select name="industry">
            <option value="">-- None --</option>
            @foreach(['Technology','Finance','Healthcare','Education','Retail','Manufacturing','Real Estate','Hospitality','Logistics','Other'] as $ind)
            <option value="{{ $ind }}" {{ old('industry')==$ind?'selected':'' }}>{{ $ind }}</option>
            @endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Ownership</label>
          <select name="ownership">
            <option value="">-- None --</option>
            @foreach(['Public','Private','Subsidiary','Other'] as $o)<option value="{{ $o }}" {{ old('ownership')==$o?'selected':'' }}>{{ $o }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field"><label>Employees</label><input type="number" name="employees" value="{{ old('employees') }}" placeholder="Number of employees"></div>
        <div class="cf-field"><label>Annual Revenue</label><input type="number" name="annual_revenue" value="{{ old('annual_revenue') }}" placeholder="e.g. 1000000" step="0.01"></div>
        <div class="cf-field"><label>SIC Code</label><input type="text" name="sic_code" value="{{ old('sic_code') }}" placeholder="SIC code"></div>
        <div class="cf-field"><label>Ticker Symbol</label><input type="text" name="ticker_symbol" value="{{ old('ticker_symbol') }}" placeholder="e.g. AAPL"></div>
      </div>
    </div>
    {{-- Contact Information --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-phone"></i> Contact Information</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field"><label>Phone</label><input type="text" name="phone" value="{{ old('phone') }}" placeholder="+1 234 567 8900"></div>
        <div class="cf-field"><label>Fax</label><input type="text" name="fax" value="{{ old('fax') }}" placeholder="Fax number"></div>
        <div class="cf-field"><label>Website</label><input type="url" name="website" value="{{ old('website') }}" placeholder="https://example.com"></div>
        <div class="cf-field"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" placeholder="contact@company.com"></div>
      </div>
    </div>
    {{-- Billing Address --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-file-invoice"></i> Billing Address</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field"><label>Country</label><input type="text" name="billing_country" value="{{ old('billing_country') }}" placeholder="Country"></div>
        <div class="cf-field"><label>Building Name</label><input type="text" name="billing_building" value="{{ old('billing_building') }}" placeholder="Building name"></div>
        <div class="cf-field"><label>Street</label><input type="text" name="billing_street" value="{{ old('billing_street') }}" placeholder="Street address"></div>
        <div class="cf-field"><label>City</label><input type="text" name="billing_city" value="{{ old('billing_city') }}" placeholder="City"></div>
        <div class="cf-field"><label>State</label><input type="text" name="billing_state" value="{{ old('billing_state') }}" placeholder="State"></div>
        <div class="cf-field"><label>Postal Code</label><input type="text" name="billing_zip" value="{{ old('billing_zip') }}" placeholder="ZIP / Postal code"></div>
        <div class="cf-field"><label>Latitude</label><input type="text" name="billing_lat" value="{{ old('billing_lat') }}" placeholder="e.g. 13.0827"></div>
        <div class="cf-field"><label>Longitude</label><input type="text" name="billing_lng" value="{{ old('billing_lng') }}" placeholder="e.g. 80.2707"></div>
      </div>
    </div>
    {{-- Shipping Address --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-truck"></i> Shipping Address</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field"><label>Country</label><input type="text" name="shipping_country" value="{{ old('shipping_country') }}" placeholder="Country"></div>
        <div class="cf-field"><label>Building Name</label><input type="text" name="shipping_building" value="{{ old('shipping_building') }}" placeholder="Building name"></div>
        <div class="cf-field"><label>Street</label><input type="text" name="shipping_street" value="{{ old('shipping_street') }}" placeholder="Street address"></div>
        <div class="cf-field"><label>City</label><input type="text" name="shipping_city" value="{{ old('shipping_city') }}" placeholder="City"></div>
        <div class="cf-field"><label>State</label><input type="text" name="shipping_state" value="{{ old('shipping_state') }}" placeholder="State"></div>
        <div class="cf-field"><label>Postal Code</label><input type="text" name="shipping_zip" value="{{ old('shipping_zip') }}" placeholder="ZIP / Postal code"></div>
        <div class="cf-field"><label>Latitude</label><input type="text" name="shipping_lat" value="{{ old('shipping_lat') }}" placeholder="e.g. 13.0827"></div>
        <div class="cf-field"><label>Longitude</label><input type="text" name="shipping_lng" value="{{ old('shipping_lng') }}" placeholder="e.g. 80.2707"></div>
      </div>
    </div>
    {{-- Description --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-sticky-note"></i> Description & Notes</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body" style="grid-template-columns:1fr">
        <div class="cf-field"><label>Description</label><textarea name="description" placeholder="Account description...">{{ old('description') }}</textarea></div>
        <div class="cf-field"><label>Notes</label><textarea name="notes" placeholder="Internal notes...">{{ old('notes') }}</textarea></div>
      </div>
    </div>
    
  </form>
</div>
<script>
function previewAvatar(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      const p = document.getElementById('avatar-preview');
      p.innerHTML = '<img src="'+e.target.result+'" alt="preview">';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endsection
