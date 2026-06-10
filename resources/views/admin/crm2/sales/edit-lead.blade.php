@extends('layouts.admin')
@section('title', 'Edit Lead')
@section('page-title', 'Edit Lead')
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
.cf-field .cf-check{display:flex;align-items:center;gap:.5rem;padding:.45rem 0}
.cf-field .cf-check input{width:auto}
.cf-actions{display:flex;gap:.75rem;justify-content:flex-end;padding:1rem 0}
.cf-btn{padding:.55rem 1.4rem;border-radius:7px;font-size:.85rem;font-weight:600;cursor:pointer;border:none;display:inline-flex;align-items:center;gap:.4rem;transition:all .2s}
.cf-btn-primary{background:var(--accent);color:#fff}
.cf-btn-primary:hover{opacity:.9}
.cf-btn-ghost{background:transparent;border:1px solid var(--border);color:var(--text-secondary)}
.cf-btn-ghost:hover{background:var(--bg-primary)}
</style>
<div class="cf-wrap">
  <div class="cf-header">
    <div>
      <div class="cf-breadcrumb"><a href="{{ route('admin.crm2.sales.leads') }}">Leads</a> / Edit Lead</div>
      <h1><i class="fas fa-user-edit"></i> Edit Lead</h1>
    </div>
    <a href="{{ route('admin.crm2.sales.leads') }}" class="cf-btn cf-btn-ghost"><i class="fas fa-arrow-left"></i> Back</a>
  </div>
  @if($errors->any())<div class="crm2-alert error" style="margin-bottom:1rem"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>@endif
  @if(session('success'))<div class="crm2-alert success" style="margin-bottom:1rem"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <form method="POST" action="{{ route('admin.crm2.sales.update', ['type'=>'lead','id'=>$item->id]) }}" enctype="multipart/form-data">
    @csrf @method('PATCH')
    {{-- Lead Profile --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-user-tie"></i> Lead Profile</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field">
          <label>Lead Owner</label>
          <select name="owner_id">
            <option value="">-- Select Owner --</option>
            @foreach($staff as $s)<option value="{{ $s->id }}" {{ old('owner_id',$item->owner_id)==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Lead Status</label>
          <select name="lead_status">
            <option value="">-- None --</option>
            @foreach(['Not Contacted','Attempted to Contact','Contact in Future','Contacted','Junk Lead','Lost Lead','Pre-Qualified','Not Qualified'] as $s)
            <option value="{{ $s }}" {{ old('lead_status',$item->lead_status)==$s?'selected':'' }}>{{ $s }}</option>
            @endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Lead Source</label>
          <select name="source">
            <option value="">-- None --</option>
            @foreach(['Advertisement','Cold Call','Employee Referral','External Referral','Online Store','Partner','Public Relations','Sales Email Alias','Seminar Partner','Internal Seminar','Trade Show','Web Download','Web Research','Chat'] as $s)
            <option value="{{ $s }}" {{ old('source',$item->source)==$s?'selected':'' }}>{{ $s }}</option>
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
    {{-- Personal Information --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-user"></i> Personal Information</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field">
          <label>Salutation</label>
          <select name="salutation">
            <option value="">--</option>
            @foreach(['Mr.','Mrs.','Ms.','Dr.','Prof.'] as $sal)<option value="{{ $sal }}" {{ old('salutation',$item->salutation)==$sal?'selected':'' }}>{{ $sal }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field"><label>First Name <span style="color:red">*</span></label><input type="text" name="first_name" value="{{ old('first_name',$item->first_name) }}" required placeholder="First name"></div>
        <div class="cf-field"><label>Last Name</label><input type="text" name="last_name" value="{{ old('last_name',$item->last_name) }}" placeholder="Last name"></div>
        <div class="cf-field"><label>Title</label><input type="text" name="title" value="{{ old('title',$item->title) }}" placeholder="Job title"></div>
        <div class="cf-field"><label>Company</label><input type="text" name="company" value="{{ old('company',$item->company) }}" placeholder="Company name"></div>
        <div class="cf-field">
          <label>Industry</label>
          <select name="industry">
            <option value="">-- None --</option>
            @foreach(['Technology','Finance','Healthcare','Education','Manufacturing','Retail','Real Estate','Hospitality','Automotive','Media','Telecommunications','Energy','Government','Non-Profit','Other'] as $ind)
            <option value="{{ $ind }}" {{ old('industry',$item->industry)==$ind?'selected':'' }}>{{ $ind }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
    {{-- Contact Information --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-phone"></i> Contact Information</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field"><label>Email</label><input type="email" name="email" value="{{ old('email',$item->email) }}" placeholder="email@example.com"></div>
        <div class="cf-field"><label>Secondary Email</label><input type="email" name="secondary_email" value="{{ old('secondary_email',$item->secondary_email) }}" placeholder="secondary@example.com"></div>
        <div class="cf-field"><label>Phone</label><input type="text" name="phone" value="{{ old('phone',$item->phone) }}" placeholder="+1 234 567 8900"></div>
        <div class="cf-field"><label>Mobile</label><input type="text" name="mobile" value="{{ old('mobile',$item->mobile) }}" placeholder="+1 234 567 8901"></div>
        <div class="cf-field"><label>Fax</label><input type="text" name="fax" value="{{ old('fax',$item->fax) }}" placeholder="Fax number"></div>
        <div class="cf-field"><label>Website</label><input type="url" name="website" value="{{ old('website',$item->website) }}" placeholder="https://example.com"></div>
        <div class="cf-field"><label>Twitter / X</label><input type="text" name="twitter" value="{{ old('twitter',$item->twitter) }}" placeholder="@handle"></div>
        <div class="cf-field"><label>LinkedIn</label><input type="url" name="linkedin" value="{{ old('linkedin',$item->linkedin) }}" placeholder="LinkedIn URL"></div>
        <div class="cf-field"><label>Facebook</label><input type="url" name="facebook" value="{{ old('facebook',$item->facebook) }}" placeholder="Facebook URL"></div>
        <div class="cf-field"><label>Instagram</label><input type="text" name="instagram" value="{{ old('instagram',$item->instagram) }}" placeholder="@handle"></div>
        <div class="cf-field">
          <label>Email Opt Out</label>
          <div class="cf-check"><input type="checkbox" name="email_opt_out" value="1" {{ old('email_opt_out',$item->email_opt_out)?'checked':'' }}><span style="font-size:.85rem;color:var(--text-secondary)">Do not send marketing emails</span></div>
        </div>
      </div>
    </div>
    {{-- Address Information --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-map-marker-alt"></i> Address Information</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field"><label>Country</label><input type="text" name="country" value="{{ old('country',$item->country) }}" placeholder="Country"></div>
        <div class="cf-field"><label>Building / Flat</label><input type="text" name="building" value="{{ old('building',$item->building) }}" placeholder="Building name"></div>
        <div class="cf-field"><label>Street</label><input type="text" name="street" value="{{ old('street',$item->street) }}" placeholder="Street address"></div>
        <div class="cf-field"><label>City</label><input type="text" name="city" value="{{ old('city',$item->city) }}" placeholder="City"></div>
        <div class="cf-field"><label>State</label><input type="text" name="state" value="{{ old('state',$item->state) }}" placeholder="State"></div>
        <div class="cf-field"><label>Postal Code</label><input type="text" name="zip_code" value="{{ old('zip_code',$item->zip_code) }}" placeholder="ZIP / Postal code"></div>
      </div>
    </div>
    {{-- Business Information --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-chart-bar"></i> Business Information</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field"><label>Annual Revenue</label><input type="number" name="annual_revenue" value="{{ old('annual_revenue',$item->annual_revenue) }}" placeholder="0.00" step="0.01"></div>
        <div class="cf-field"><label>No. of Employees</label><input type="number" name="no_of_employees" value="{{ old('no_of_employees',$item->no_of_employees) }}" placeholder="e.g. 250" min="0"></div>
      </div>
    </div>
    {{-- Lead Qualification --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-star"></i> Lead Qualification</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field"><label>Budget</label><input type="number" name="budget" value="{{ old('budget',$item->budget) }}" placeholder="0.00" step="0.01"></div>
        <div class="cf-field"><label>Deal Value</label><input type="number" name="deal_value" value="{{ old('deal_value',$item->deal_value) }}" placeholder="0.00" step="0.01"></div>
        <div class="cf-field"><label>Expected Purchase Date</label><input type="date" name="expected_purchase_date" value="{{ old('expected_purchase_date',$item->expected_purchase_date) }}"></div>
        <div class="cf-field">
          <label>Priority</label>
          <select name="priority">
            <option value="">-- None --</option>
            @foreach(['High','Medium','Low'] as $p)<option value="{{ $p }}" {{ old('priority',$item->priority)==$p?'selected':'' }}>{{ $p }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Interest Level</label>
          <select name="interest_level">
            <option value="">-- None --</option>
            @foreach(['Very High','High','Medium','Low','Very Low'] as $il)<option value="{{ $il }}" {{ old('interest_level',$item->interest_level)==$il?'selected':'' }}>{{ $il }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Decision Maker</label>
          <div class="cf-check"><input type="checkbox" name="is_decision_maker" value="1" {{ old('is_decision_maker',$item->is_decision_maker)?'checked':'' }}><span style="font-size:.85rem;color:var(--text-secondary)">Is Decision Maker</span></div>
        </div>
        <div class="cf-field"><label>Competitor</label><input type="text" name="competitor" value="{{ old('competitor',$item->competitor) }}" placeholder="Competitor name"></div>
        <div class="cf-field"><label>Follow-up Date</label><input type="date" name="follow_up_date" value="{{ old('follow_up_date',$item->follow_up_date) }}"></div>
        <div class="cf-field" style="grid-column:1/-1"><label>Requirement</label><textarea name="requirement" placeholder="Lead requirement details...">{{ old('requirement',$item->requirement) }}</textarea></div>
      </div>
    </div>
    {{-- Lead Tracking --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-chart-line"></i> Lead Tracking</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field"><label>Campaign Source</label><input type="text" name="campaign_source" value="{{ old('campaign_source',$item->campaign_source) }}" placeholder="Campaign source"></div>
        <div class="cf-field"><label>Campaign Name</label><input type="text" name="campaign_name" value="{{ old('campaign_name',$item->campaign_name) }}" placeholder="Campaign name"></div>
        <div class="cf-field"><label>Referral Source</label><input type="text" name="referral_source" value="{{ old('referral_source',$item->referral_source) }}" placeholder="Referral source"></div>
      </div>
    </div>
    {{-- Description & Notes --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-sticky-note"></i> Description & Notes</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body" style="grid-template-columns:1fr">
        <div class="cf-field"><label>Description</label><textarea name="description" placeholder="Lead description...">{{ old('description',$item->description) }}</textarea></div>
        <div class="cf-field"><label>Internal Notes</label><textarea name="notes" placeholder="Internal notes...">{{ old('notes',$item->notes) }}</textarea></div>
      </div>
    </div>
    <div class="cf-actions">
      <a href="{{ route('admin.crm2.sales.leads') }}" class="cf-btn cf-btn-ghost">Cancel</a>
      <button type="submit" class="cf-btn cf-btn-primary"><i class="fas fa-save"></i> Update Lead</button>
    </div>
  </form>
</div>
@endsection
