@extends('layouts.admin')
@section('title', 'Edit Account')
@section('page-title', 'Edit Account')
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
  <div class="cf-header">
    <div>
      <div class="cf-breadcrumb"><a href="{{ route('admin.crm2.sales.accounts') }}">Accounts</a> / Edit Account</div>
      <h1><i class="fas fa-building"></i> Edit Account</h1>
    </div>
    <a href="{{ route('admin.crm2.sales.accounts') }}" class="cf-btn cf-btn-ghost"><i class="fas fa-arrow-left"></i> Back</a>
  </div>
  @if($errors->any())<div class="crm2-alert error" style="margin-bottom:1rem"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>@endif
  @if(session('success'))<div class="crm2-alert success" style="margin-bottom:1rem"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <form method="POST" action="{{ route('admin.crm2.sales.update', ['type'=>'account','id'=>$item->id]) }}" enctype="multipart/form-data">
    @csrf @method('PATCH')
    {{-- Account Profile --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-building"></i> Account Profile</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-avatar-upload">
          <div class="cf-avatar-preview" id="avatar-preview">
            @if($item->account_image)<img src="{{ asset('storage/'.$item->account_image) }}" alt="logo">@else<i class="fas fa-building"></i>@endif
          </div>
          <div>
            <label class="cf-btn cf-btn-ghost" style="cursor:pointer"><i class="fas fa-upload"></i> Change Logo<input type="file" name="account_image" accept="image/*" style="display:none" onchange="previewAvatar(this)"></label>
            <p style="font-size:.75rem;color:var(--text-muted);margin-top:.3rem">JPG, PNG up to 2MB</p>
          </div>
        </div>
        <div class="cf-field">
          <label>Account Owner</label>
          <select name="owner_id">
            <option value="">-- Select Owner --</option>
            @foreach($staff as $s)<option value="{{ $s->id }}" {{ old('owner_id',$item->owner_id)==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Account Name <span style="color:red">*</span></label>
          <input type="text" name="name" value="{{ old('name',$item->name) }}" required placeholder="Company name">
        </div>
        <div class="cf-field">
          <label>Account Number</label>
          <input type="text" name="account_number" value="{{ old('account_number',$item->account_number) }}" placeholder="ACC-0001">
        </div>
        <div class="cf-field">
          <label>Account Type</label>
          <select name="account_type">
            <option value="">-- None --</option>
            @foreach(['Analyst','Competitor','Customer','Distributor','Integrator','Investor','Partner','Press','Prospect','Reseller','Other'] as $t)
            <option value="{{ $t }}" {{ old('account_type',$item->account_type)==$t?'selected':'' }}>{{ $t }}</option>
            @endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Rating</label>
          <select name="rating">
            <option value="">-- None --</option>
            @foreach(['Hot','Warm','Cold'] as $r)<option value="{{ $r }}" {{ old('rating',$item->rating)==$r?'selected':'' }}>{{ $r }}</option>@endforeach
          </select>
        </div>
      </div>
    </div>
    {{-- Company Information --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-info-circle"></i> Company Information</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field">
          <label>Parent Account</label>
          <select name="parent_account_id">
            <option value="">-- None --</option>
            @foreach($accounts_list as $acc)
              @if($acc->id != $item->id)
              <option value="{{ $acc->id }}" {{ old('parent_account_id',$item->parent_account_id)==$acc->id?'selected':'' }}>{{ $acc->name }}</option>
              @endif
            @endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Industry</label>
          <select name="industry">
            <option value="">-- None --</option>
            @foreach(['Technology','Finance','Healthcare','Education','Manufacturing','Retail','Real Estate','Hospitality','Automotive','Media','Telecommunications','Energy','Government','Non-Profit','Other'] as $ind)
            <option value="{{ $ind }}" {{ old('industry',$item->industry)==$ind?'selected':'' }}>{{ $ind }}</option>
            @endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Ownership</label>
          <select name="ownership">
            <option value="">-- None --</option>
            @foreach(['Public','Private','Subsidiary','Other'] as $o)<option value="{{ $o }}" {{ old('ownership',$item->ownership)==$o?'selected':'' }}>{{ $o }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field"><label>No. of Employees</label><input type="number" name="employees" value="{{ old('employees',$item->employees) }}" placeholder="e.g. 250" min="0"></div>
        <div class="cf-field"><label>Annual Revenue</label><input type="number" name="annual_revenue" value="{{ old('annual_revenue',$item->annual_revenue) }}" placeholder="0.00" step="0.01"></div>
        <div class="cf-field"><label>SIC Code</label><input type="text" name="sic_code" value="{{ old('sic_code',$item->sic_code) }}" placeholder="e.g. 7372"></div>
        <div class="cf-field"><label>Ticker Symbol</label><input type="text" name="ticker_symbol" value="{{ old('ticker_symbol',$item->ticker_symbol) }}" placeholder="e.g. AAPL"></div>
      </div>
    </div>
    {{-- Contact Information --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-phone"></i> Contact Information</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field"><label>Phone</label><input type="text" name="phone" value="{{ old('phone',$item->phone) }}" placeholder="+1 234 567 8900"></div>
        <div class="cf-field"><label>Fax</label><input type="text" name="fax" value="{{ old('fax',$item->fax) }}" placeholder="Fax number"></div>
        <div class="cf-field"><label>Website</label><input type="url" name="website" value="{{ old('website',$item->website) }}" placeholder="https://example.com"></div>
        <div class="cf-field"><label>Email</label><input type="email" name="email" value="{{ old('email',$item->email) }}" placeholder="info@company.com"></div>
      </div>
    </div>
    {{-- Billing Address --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-file-invoice"></i> Billing Address</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field"><label>Country</label><input type="text" name="billing_country" value="{{ old('billing_country',$item->billing_country) }}" placeholder="Country"></div>
        <div class="cf-field"><label>Building / Flat</label><input type="text" name="billing_building" value="{{ old('billing_building',$item->billing_building) }}" placeholder="Building name"></div>
        <div class="cf-field"><label>Street</label><input type="text" name="billing_street" value="{{ old('billing_street',$item->billing_street) }}" placeholder="Street address"></div>
        <div class="cf-field"><label>City</label><input type="text" name="billing_city" value="{{ old('billing_city',$item->billing_city) }}" placeholder="City"></div>
        <div class="cf-field"><label>State</label><input type="text" name="billing_state" value="{{ old('billing_state',$item->billing_state) }}" placeholder="State"></div>
        <div class="cf-field"><label>Postal Code</label><input type="text" name="billing_zip" value="{{ old('billing_zip',$item->billing_zip) }}" placeholder="ZIP / Postal code"></div>
      </div>
    </div>
    {{-- Shipping Address --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-truck"></i> Shipping Address</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field"><label>Country</label><input type="text" name="shipping_country" value="{{ old('shipping_country',$item->shipping_country) }}" placeholder="Country"></div>
        <div class="cf-field"><label>Building / Flat</label><input type="text" name="shipping_building" value="{{ old('shipping_building',$item->shipping_building) }}" placeholder="Building name"></div>
        <div class="cf-field"><label>Street</label><input type="text" name="shipping_street" value="{{ old('shipping_street',$item->shipping_street) }}" placeholder="Street address"></div>
        <div class="cf-field"><label>City</label><input type="text" name="shipping_city" value="{{ old('shipping_city',$item->shipping_city) }}" placeholder="City"></div>
        <div class="cf-field"><label>State</label><input type="text" name="shipping_state" value="{{ old('shipping_state',$item->shipping_state) }}" placeholder="State"></div>
        <div class="cf-field"><label>Postal Code</label><input type="text" name="shipping_zip" value="{{ old('shipping_zip',$item->shipping_zip) }}" placeholder="ZIP / Postal code"></div>
      </div>
    </div>
    {{-- Description & Notes --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-sticky-note"></i> Description & Notes</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body" style="grid-template-columns:1fr">
        <div class="cf-field"><label>Description</label><textarea name="description" placeholder="Account description...">{{ old('description',$item->description) }}</textarea></div>
        <div class="cf-field"><label>Notes</label><textarea name="notes" placeholder="Internal notes...">{{ old('notes',$item->notes) }}</textarea></div>
      </div>
    </div>
    <div class="cf-actions">
      <a href="{{ route('admin.crm2.sales.accounts') }}" class="cf-btn cf-btn-ghost">Cancel</a>
      <button type="submit" class="cf-btn cf-btn-primary"><i class="fas fa-save"></i> Update Account</button>
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
