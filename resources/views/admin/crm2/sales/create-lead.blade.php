@extends('layouts.admin')
@section('title', 'New Lead')
@section('page-title', 'New Lead')
@section('content')
<style>
.lead-form-wrap { max-width: 1100px; margin: 0 auto; padding: 1.5rem 1rem 3rem;  padding-top: 0;  margin-top: 0; }
.lead-form-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: .75rem; }
.lead-form-header h1 { font-size: 1.4rem; font-weight: 700; color: var(--text-primary,#1a1a2e); display: flex; align-items: center; gap: .5rem; }
.lead-section { background: var(--bg-card,#fff); border: 1px solid var(--border,#e2e8f0); border-radius: 10px; margin-bottom: 1.25rem; overflow: hidden; }
.lead-section-header { background: var(--bg-primary,#f8fafc); border-bottom: 1px solid var(--border,#e2e8f0); padding: .65rem 1rem; display: flex; align-items: center; gap: .5rem; cursor: pointer; user-select: none; }
.lead-section-header i.section-icon { color: var(--accent,#6366f1); width: 18px; text-align: center; }
.lead-section-header span { font-size: .85rem; font-weight: 600; color: var(--text-primary,#1a1a2e); flex: 1; }
.lead-section-header .toggle-icon { font-size: .75rem; color: var(--text-muted,#94a3b8); transition: transform .2s; }
.lead-section-header.collapsed .toggle-icon { transform: rotate(-90deg); }
.lead-section-body { padding: 1rem; }
.lead-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: .75rem; }
.lead-grid.cols-2 { grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); }
.lead-grid.cols-1 { grid-template-columns: 1fr; }
.lead-field { display: flex; flex-direction: column; gap: .25rem; }
.lead-field label { font-size: .75rem; font-weight: 600; color: var(--text-secondary,#64748b); text-transform: uppercase; letter-spacing: .03em; }
.lead-field label .req { color: #ef4444; margin-left: 2px; }
.lead-input, .lead-select, .lead-textarea { width: 100%; padding: .45rem .65rem; font-size: .85rem; border: 1px solid var(--border,#e2e8f0); border-radius: 6px; background: var(--bg-primary,#f8fafc); color: var(--text-primary,#1a1a2e); transition: border-color .15s, box-shadow .15s; }
.lead-input:focus, .lead-select:focus, .lead-textarea:focus { outline: none; border-color: var(--accent,#6366f1); box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
.lead-textarea { resize: vertical; min-height: 80px; }
.lead-salutation-row { display: grid; grid-template-columns: 120px 1fr 1fr; gap: .75rem; }
.lead-checkbox-row { display: flex; align-items: center; gap: .5rem; padding: .5rem 0; }
.lead-checkbox-row input[type=checkbox] { width: 16px; height: 16px; accent-color: var(--accent,#6366f1); }
.lead-checkbox-row label { font-size: .85rem; color: var(--text-primary,#1a1a2e); font-weight: 500; cursor: pointer; }
.lead-image-preview { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border,#e2e8f0); display: none; }
.lead-image-preview.show { display: block; }
.lead-image-wrap { display: flex; align-items: center; gap: 1rem; }
.lead-form-actions { display: flex; gap: .75rem; justify-content: flex-end; margin-top: 1.5rem; flex-wrap: wrap; }
.btn-lead-save { background: var(--accent,#6366f1); color: #fff; border: none; padding: .55rem 1.4rem; border-radius: 7px; font-size: .9rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: .4rem; transition: opacity .15s; }
.btn-lead-save:hover { opacity: .88; }
.btn-lead-cancel { background: transparent; color: var(--text-secondary,#64748b); border: 1px solid var(--border,#e2e8f0); padding: .55rem 1.2rem; border-radius: 7px; font-size: .9rem; font-weight: 600; cursor: pointer; text-decoration: none; display: flex; align-items: center; gap: .4rem; }
.btn-lead-cancel:hover { background: var(--bg-primary,#f8fafc); }
/* Sticky top action bar — fixed so it always stays visible */
.lead-sticky-bar {
    position: fixed;
    top: 60px; /* below the global topbar (60px height) */
    left: var(--rail-width, 60px); /* default: rail only closed */
    right: 0;
    z-index: 120;
    background: var(--bg-card,#fff);
    border-bottom: 2px solid var(--accent,#6366f1);
    padding: .75rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    box-shadow: 0 3px 12px rgba(0,0,0,.10);
    flex-wrap: wrap;
    transition: left 0.22s cubic-bezier(0.4,0,0.2,1);
    will-change: transform;
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
}
/* Shift bar right when sidebar panel is open */
body.xn-panel-open .lead-sticky-bar {
    left: calc(var(--rail-width, 60px) + var(--panel-width, 220px));
}
/* Spacer to push form content below the fixed bar */
.lead-sticky-spacer {
    height: 64px;
}
.lead-sticky-bar .lead-sticky-title {
    font-size: .95rem;
    font-weight: 700;
    color: var(--text-primary,#1a1a2e);
    display: flex;
    align-items: center;
    gap: .5rem;
}
.lead-sticky-bar .lead-sticky-actions {
    display: flex;
    align-items: center;
    gap: .6rem;
    flex-wrap: wrap;
}
@media(max-width:640px){
  .lead-grid { grid-template-columns: 1fr; }
  .lead-salutation-row { grid-template-columns: 1fr 1fr; }
  .lead-salutation-row .salutation-field { grid-column: 1/-1; }
}
</style>

<div class="lead-form-wrap">
  {{-- Sticky top action bar --}}
  <div class="lead-sticky-bar" id="leadStickyBar">
    <div class="lead-sticky-title">
      <i class="fas fa-user-plus" style="color:var(--accent,#6366f1)"></i>
      Create New Lead
    </div>
    <div class="lead-sticky-actions">
      <a href="{{ route('admin.crm2.sales.leads') }}" class="btn-lead-cancel">
        <i class="fas fa-arrow-left"></i> Cancel
      </a>
      <button type="submit" form="leadCreateForm" name="_action" value="save_new"
              class="btn-lead-save"
              style="background:var(--bg-card,#fff);color:var(--accent,#6366f1);border:1px solid var(--accent,#6366f1)">
        <i class="fas fa-plus"></i> Save &amp; New
      </button>
      <button type="submit" form="leadCreateForm" class="btn-lead-save">
        <i class="fas fa-save"></i> Save Lead
      </button>
    </div>
  </div>

  {{-- Spacer: pushes content below the fixed sticky bar --}}
  <div class="lead-sticky-spacer"></div>
  @if($errors->any())
  <div class="crm2-alert danger" style="margin-bottom:1rem"><i class="fas fa-exclamation-circle"></i> Please fix the errors below.</div>
  @endif

  <form id="leadCreateForm" method="POST" action="{{ route('admin.crm2.sales.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="_type" value="lead">

    {{-- 1. Lead Profile --}}
    <div class="lead-section" style="margin-top:1.5rem">
      <div class="lead-section-header" onclick="toggleSection(this)">
        <i class="fas fa-id-badge section-icon"></i>
        <span>1. Lead Profile</span>
        <i class="fas fa-chevron-down toggle-icon"></i>
      </div>
      <div class="lead-section-body">
        <div class="lead-image-wrap" style="margin-bottom:.75rem">
          <img id="lead-img-preview" class="lead-image-preview" src="" alt="Lead Photo">
          <div class="lead-field">
            <label>Lead Photo</label>
            <input type="file" name="lead_image" class="lead-input" accept="image/*" onchange="previewImg(this)">
          </div>
        </div>
        <div class="lead-grid">
          <div class="lead-field">
            <label>Lead Owner</label>
            <select name="owner_id" class="lead-select">
              <option value="">— Assign to Staff —</option>
              @foreach($staff as $s)
              <option value="{{ $s->id }}" {{ old('owner_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="lead-field">
            <label>Lead Status</label>
            <select name="lead_status" class="lead-select">
              @foreach(['Not Contacted','Attempted to Contact','Contact in Future','Contacted','Junk Lead','Lead Lost','Pre-Qualified','Not Qualified'] as $st)
              <option value="{{ $st }}" {{ old('lead_status','Not Contacted')===$st?'selected':'' }}>{{ $st }}</option>
              @endforeach
            </select>
          </div>
          <div class="lead-field">
            <label>Lead Source</label>
            <select name="source" class="lead-select">
              @foreach(['Advertisement','Cold Call','Employee Referral','External Referral','Online Store','Partner','Public Relations','Sales Email Alias','Seminar Partner','Internal Seminar','Trade Show','Web Download','Web Research','Chat','X (Twitter)','Facebook'] as $src)
              <option value="{{ $src }}" {{ old('source')===$src?'selected':'' }}>{{ $src }}</option>
              @endforeach
            </select>
          </div>
          <div class="lead-field">
            <label>Rating</label>
            <select name="rating" class="lead-select">
              <option value="">— Select —</option>
              @foreach(['Acquired','Active','Market Failed','Project Cancelled','Shut Down'] as $r)
              <option value="{{ $r }}" {{ old('rating')===$r?'selected':'' }}>{{ $r }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>

    {{-- 2. Personal Information --}}
    <div class="lead-section">
      <div class="lead-section-header" onclick="toggleSection(this)">
        <i class="fas fa-user section-icon"></i>
        <span>2. Personal Information</span>
        <i class="fas fa-chevron-down toggle-icon"></i>
      </div>
      <div class="lead-section-body">
        <div class="lead-salutation-row" style="margin-bottom:.75rem">
          <div class="lead-field salutation-field">
            <label>Salutation</label>
            <select name="salutation" class="lead-select">
              <option value="">—</option>
              @foreach(['Mr.','Mrs.','Ms.','Dr.','Prof.'] as $sal)
              <option value="{{ $sal }}" {{ old('salutation')===$sal?'selected':'' }}>{{ $sal }}</option>
              @endforeach
            </select>
          </div>
          <div class="lead-field">
            <label>First Name <span class="req">*</span></label>
            <input type="text" name="first_name" class="lead-input" value="{{ old('first_name') }}" required placeholder="First name">
          </div>
          <div class="lead-field">
            <label>Last Name</label>
            <input type="text" name="last_name" class="lead-input" value="{{ old('last_name') }}" placeholder="Last name">
          </div>
        </div>
        <div class="lead-grid">
          <div class="lead-field">
            <label>Title / Designation</label>
            <input type="text" name="title" class="lead-input" value="{{ old('title') }}" placeholder="e.g. Sales Manager">
          </div>
          <div class="lead-field">
            <label>Company</label>
            <input type="text" name="company" class="lead-input" value="{{ old('company') }}" placeholder="Company name">
          </div>
          <div class="lead-field">
            <label>Industry</label>
            <select name="industry" class="lead-select">
              <option value="">— Select Industry —</option>
              @foreach(['ASP','Data/Telecom OEM','ERP','Government/Military','Large Enterprise','ManagementISV','MSP','Network Equipment Enterprise','Non-management ISV','Optical Networking','Service Provider','Small/Medium Enterprise','Storage Equipment','Storage Service Provider','System Integrator','Wireless Industry','Management ISV'] as $ind)
              <option value="{{ $ind }}" {{ old('industry')===$ind?'selected':'' }}>{{ $ind }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>

    {{-- 3. Contact Information --}}
    <div class="lead-section">
      <div class="lead-section-header" onclick="toggleSection(this)">
        <i class="fas fa-address-card section-icon"></i>
        <span>3. Contact Information</span>
        <i class="fas fa-chevron-down toggle-icon"></i>
      </div>
      <div class="lead-section-body">
        <div class="lead-grid">
          <div class="lead-field">
            <label>Email <span class="req">*</span></label>
            <input type="email" name="email" class="lead-input" value="{{ old('email') }}" required placeholder="primary@email.com">
          </div>
          <div class="lead-field">
            <label>Secondary Email</label>
            <input type="email" name="secondary_email" class="lead-input" value="{{ old('secondary_email') }}" placeholder="secondary@email.com">
          </div>
          <div class="lead-field">
            <label>Phone</label>
            <input type="tel" name="phone" class="lead-input" value="{{ old('phone') }}" placeholder="+91 XXXXX XXXXX">
          </div>
          <div class="lead-field">
            <label>Mobile</label>
            <input type="tel" name="mobile" class="lead-input" value="{{ old('mobile') }}" placeholder="+91 XXXXX XXXXX">
          </div>
          <div class="lead-field">
            <label>Fax</label>
            <input type="text" name="fax" class="lead-input" value="{{ old('fax') }}" placeholder="Fax number">
          </div>
          <div class="lead-field">
            <label>Website</label>
            <input type="url" name="website" class="lead-input" value="{{ old('website') }}" placeholder="https://example.com">
          </div>
          <div class="lead-field">
            <label><i class="fab fa-twitter" style="color:#1da1f2"></i> X (Twitter)</label>
            <input type="text" name="twitter" class="lead-input" value="{{ old('twitter') }}" placeholder="@handle">
          </div>
          <div class="lead-field">
            <label><i class="fab fa-linkedin" style="color:#0077b5"></i> LinkedIn</label>
            <input type="url" name="linkedin" class="lead-input" value="{{ old('linkedin') }}" placeholder="linkedin.com/in/...">
          </div>
          <div class="lead-field">
            <label><i class="fab fa-facebook" style="color:#1877f2"></i> Facebook</label>
            <input type="url" name="facebook" class="lead-input" value="{{ old('facebook') }}" placeholder="facebook.com/...">
          </div>
          <div class="lead-field">
            <label><i class="fab fa-instagram" style="color:#e1306c"></i> Instagram</label>
            <input type="text" name="instagram" class="lead-input" value="{{ old('instagram') }}" placeholder="@handle">
          </div>
        </div>
        <div class="lead-checkbox-row" style="margin-top:.5rem">
          <input type="checkbox" name="email_opt_out" id="email_opt_out" value="1" {{ old('email_opt_out')?'checked':'' }}>
          <label for="email_opt_out">Email Opt Out — Do not send marketing emails to this lead</label>
        </div>
      </div>
    </div>

    {{-- 4. Address Information --}}
    <div class="lead-section">
      <div class="lead-section-header" onclick="toggleSection(this)">
        <i class="fas fa-map-marker-alt section-icon"></i>
        <span>4. Address Information</span>
        <i class="fas fa-chevron-down toggle-icon"></i>
      </div>
      <div class="lead-section-body">
        <div class="lead-grid">
          <div class="lead-field">
            <label>Country / Region</label>
            <select name="country" class="lead-select">
              <option value="">— Select Country —</option>
              @foreach(['India','Malaysia','Maldives','Singapore','Indonesia','Dubai','United States','United Kingdom','Australia','Canada','Germany','France','Japan','China','Other'] as $c)
              <option value="{{ $c }}" {{ old('country')===$c?'selected':'' }}>{{ $c }}</option>
              @endforeach
            </select>
          </div>
          <div class="lead-field">
            <label>Flat / House No. / Building</label>
            <input type="text" name="flat_no" class="lead-input" value="{{ old('flat_no') }}" placeholder="Apt / Building name">
          </div>
          <div class="lead-field">
            <label>Street Address</label>
            <input type="text" name="street" class="lead-input" value="{{ old('street') }}" placeholder="Street address">
          </div>
          <div class="lead-field">
            <label>City</label>
            <input type="text" name="city" class="lead-input" value="{{ old('city') }}" placeholder="City">
          </div>
          <div class="lead-field">
            <label>State / Province</label>
            <input type="text" name="state" class="lead-input" value="{{ old('state') }}" placeholder="State / Province">
          </div>
          <div class="lead-field">
            <label>Zip / Postal Code</label>
            <input type="text" name="zip" class="lead-input" value="{{ old('zip') }}" placeholder="Postal code">
          </div>
        </div>
      </div>
    </div>

    {{-- 5. Business Information --}}
    <div class="lead-section">
      <div class="lead-section-header" onclick="toggleSection(this)">
        <i class="fas fa-briefcase section-icon"></i>
        <span>5. Business Information</span>
        <i class="fas fa-chevron-down toggle-icon"></i>
      </div>
      <div class="lead-section-body">
        <div class="lead-grid">
          <div class="lead-field">
            <label>Annual Revenue (₹)</label>
            <input type="number" name="annual_revenue" class="lead-input" value="{{ old('annual_revenue') }}" placeholder="0.00" step="0.01" min="0">
          </div>
          <div class="lead-field">
            <label>No. of Employees</label>
            <input type="number" name="no_of_employees" class="lead-input" value="{{ old('no_of_employees') }}" placeholder="e.g. 50" min="1">
          </div>
        </div>
      </div>
    </div>

    {{-- 6. Lead Qualification --}}
    <div class="lead-section">
      <div class="lead-section-header" onclick="toggleSection(this)">
        <i class="fas fa-star section-icon"></i>
        <span>6. Lead Qualification</span>
        <i class="fas fa-chevron-down toggle-icon"></i>
      </div>
      <div class="lead-section-body">
        <div class="lead-grid">
          <div class="lead-field">
            <label>Budget (₹)</label>
            <input type="number" name="budget" class="lead-input" value="{{ old('budget') }}" placeholder="0.00" step="0.01" min="0">
          </div>
          <div class="lead-field">
            <label>Expected Purchase Date</label>
            <input type="date" name="expected_purchase_date" class="lead-input" value="{{ old('expected_purchase_date') }}">
          </div>
          <div class="lead-field">
            <label>Priority</label>
            <select name="priority" class="lead-select">
              @foreach(['Low','Medium','High'] as $p)
              <option value="{{ $p }}" {{ old('priority','Medium')===$p?'selected':'' }}>{{ $p }}</option>
              @endforeach
            </select>
          </div>
          <div class="lead-field">
            <label>Decision Maker</label>
            <input type="text" name="decision_maker" class="lead-input" value="{{ old('decision_maker') }}" placeholder="Name of decision maker">
          </div>
          <div class="lead-field">
            <label>Competitor</label>
            <input type="text" name="competitor" class="lead-input" value="{{ old('competitor') }}" placeholder="Competing company">
          </div>
          <div class="lead-field">
            <label>Interest Level</label>
            <select name="interest_level" class="lead-select">
              <option value="">— Select —</option>
              @foreach(['Very High','High','Medium','Low','Very Low'] as $il)
              <option value="{{ $il }}" {{ old('interest_level')===$il?'selected':'' }}>{{ $il }}</option>
              @endforeach
            </select>
          </div>
          <div class="lead-field">
            <label>Follow-up Date</label>
            <input type="date" name="follow_up_date" class="lead-input" value="{{ old('follow_up_date') }}">
          </div>
          <div class="lead-field">
            <label>Deal Value (₹)</label>
            <input type="number" name="deal_value" class="lead-input" value="{{ old('deal_value') }}" placeholder="0.00" step="0.01" min="0">
          </div>
        </div>
        <div class="lead-grid cols-1" style="margin-top:.75rem">
          <div class="lead-field">
            <label>Requirement</label>
            <textarea name="requirement" class="lead-textarea" placeholder="What does the lead need?">{{ old('requirement') }}</textarea>
          </div>
        </div>
      </div>
    </div>

    {{-- 7. Lead Tracking --}}
    <div class="lead-section">
      <div class="lead-section-header" onclick="toggleSection(this)">
        <i class="fas fa-chart-line section-icon"></i>
        <span>7. Lead Tracking</span>
        <i class="fas fa-chevron-down toggle-icon"></i>
      </div>
      <div class="lead-section-body">
        <div class="lead-grid">
          <div class="lead-field">
            <label>Campaign Source</label>
            <input type="text" name="campaign_source" class="lead-input" value="{{ old('campaign_source') }}" placeholder="e.g. Google Ads">
          </div>
          <div class="lead-field">
            <label>Campaign Name</label>
            <input type="text" name="campaign_name" class="lead-input" value="{{ old('campaign_name') }}" placeholder="Campaign name">
          </div>
          <div class="lead-field">
            <label>Referral Source</label>
            <input type="text" name="referral_source" class="lead-input" value="{{ old('referral_source') }}" placeholder="Who referred this lead?">
          </div>
        </div>
      </div>
    </div>

    {{-- 8. Description & Notes --}}
    <div class="lead-section">
      <div class="lead-section-header" onclick="toggleSection(this)">
        <i class="fas fa-sticky-note section-icon"></i>
        <span>8. Description &amp; Notes</span>
        <i class="fas fa-chevron-down toggle-icon"></i>
      </div>
      <div class="lead-section-body">
        <div class="lead-grid cols-1">
          <div class="lead-field">
            <label>Description</label>
            <textarea name="description" class="lead-textarea" style="min-height:100px" placeholder="Brief description of the lead...">{{ old('description') }}</textarea>
          </div>
          <div class="lead-field">
            <label>Internal Notes</label>
            <textarea name="internal_notes" class="lead-textarea" style="min-height:80px" placeholder="Internal team notes (not visible to lead)...">{{ old('internal_notes') }}</textarea>
          </div>
        </div>
      </div>
    </div>


  </form>
</div>

<script>
function toggleSection(header) {
  const body = header.nextElementSibling;
  const icon = header.querySelector('.toggle-icon');
  if (body.style.display === 'none') {
    body.style.display = 'block';
    header.classList.remove('collapsed');
  } else {
    body.style.display = 'none';
    header.classList.add('collapsed');
  }
}
function previewImg(input) {
  const preview = document.getElementById('lead-img-preview');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => { preview.src = e.target.result; preview.classList.add('show'); };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endsection
