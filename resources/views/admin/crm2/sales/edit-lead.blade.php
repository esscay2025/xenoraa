@extends('layouts.admin')
@section('title', 'Edit Lead')
@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
    <div>
        <h1 style="font-size:1.25rem;font-weight:700;color:var(--text-primary);margin:0;">Edit Lead</h1>
        <p style="font-size:.82rem;color:var(--text-muted);margin:.2rem 0 0;">Update lead information</p>
    </div>
    <a href="{{ route('admin.crm2.sales.leads') }}" class="btn-secondary" style="display:inline-flex;align-items:center;gap:.4rem;padding:.45rem .9rem;border:1px solid var(--border);border-radius:7px;font-size:.83rem;color:var(--text-secondary);text-decoration:none;background:var(--bg-card);">
        <i class="fas fa-arrow-left"></i> Back to Leads
    </a>
</div>

@if($errors->any())
<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:.75rem 1rem;margin-bottom:1rem;color:#dc2626;font-size:.85rem;">
    <ul style="margin:0;padding-left:1.25rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form method="POST" action="{{ route('admin.crm2.sales.leads.update', $lead->id) }}" enctype="multipart/form-data">
@csrf @method('PATCH')
<div style="display:grid;gap:1rem;">

{{-- SECTION 1: Lead Profile --}}
<div class="crm-section">
    <div class="crm-section-header"><i class="fas fa-user-tie"></i><span>Lead Profile</span></div>
    <div class="crm-section-body">
        <div class="crm-grid-4">
            <div class="crm-field">
                <label>Lead Owner</label>
                <select name="owner_id" class="crm-input">
                    <option value="">-- Select Owner --</option>
                    @foreach($staff as $s)
                    <option value="{{ $s->id }}" {{ $lead->owner_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="crm-field">
                <label>Lead Status</label>
                <select name="lead_status" class="crm-input">
                    @foreach(['Not Contacted','Attempted to Contact','Contact in Future','Contacted','Junk Lead','Lost Lead','Pre-Qualified','Not Qualified'] as $s)
                    <option value="{{ $s }}" {{ $lead->lead_status == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="crm-field">
                <label>Lead Source</label>
                <select name="source" class="crm-input">
                    @foreach(['','Advertisement','Cold Call','Employee Referral','External Referral','Online Store','Partner','Public Relations','Sales Email Alias','Seminar Partner','Internal Seminar','Trade Show','Web Download','Web Research','Chat'] as $s)
                    <option value="{{ $s }}" {{ $lead->source == $s ? 'selected' : '' }}>{{ $s ?: '-- Select --' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="crm-field">
                <label>Rating</label>
                <select name="rating" class="crm-input">
                    @foreach(['','Acquired','Active','Market Failed','Project Cancelled','Shut Down'] as $r)
                    <option value="{{ $r }}" {{ $lead->rating == $r ? 'selected' : '' }}>{{ $r ?: '-- Select --' }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 2: Personal Information --}}
<div class="crm-section">
    <div class="crm-section-header"><i class="fas fa-id-card"></i><span>Personal Information</span></div>
    <div class="crm-section-body">
        <div class="crm-grid-4">
            <div class="crm-field">
                <label>Salutation</label>
                <select name="salutation" class="crm-input">
                    @foreach(['','Mr.','Mrs.','Ms.','Dr.','Prof.'] as $s)
                    <option value="{{ $s }}" {{ $lead->salutation == $s ? 'selected' : '' }}>{{ $s ?: '-- Select --' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="crm-field">
                <label>First Name <span style="color:#ef4444">*</span></label>
                <input type="text" name="first_name" value="{{ old('first_name', $lead->first_name) }}" class="crm-input" required>
            </div>
            <div class="crm-field">
                <label>Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name', $lead->last_name) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>Title / Designation</label>
                <input type="text" name="title" value="{{ old('title', $lead->title) }}" class="crm-input" placeholder="e.g. CEO">
            </div>
            <div class="crm-field">
                <label>Company</label>
                <input type="text" name="company" value="{{ old('company', $lead->company) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>Industry</label>
                <select name="industry" class="crm-input">
                    @foreach(['','Technology','Finance','Healthcare','Education','Retail','Manufacturing','Real Estate','Hospitality','Media','Consulting','Other'] as $i)
                    <option value="{{ $i }}" {{ $lead->industry == $i ? 'selected' : '' }}>{{ $i ?: '-- Select --' }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 3: Contact Information --}}
<div class="crm-section">
    <div class="crm-section-header"><i class="fas fa-phone-alt"></i><span>Contact Information</span></div>
    <div class="crm-section-body">
        <div class="crm-grid-4">
            <div class="crm-field">
                <label>Email <span style="color:#ef4444">*</span></label>
                <input type="email" name="email" value="{{ old('email', $lead->email) }}" class="crm-input" required>
            </div>
            <div class="crm-field">
                <label>Secondary Email</label>
                <input type="email" name="secondary_email" value="{{ old('secondary_email', $lead->secondary_email) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>Mobile</label>
                <input type="text" name="mobile" value="{{ old('mobile', $lead->mobile) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>Fax</label>
                <input type="text" name="fax" value="{{ old('fax', $lead->fax) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>Website</label>
                <input type="url" name="website" value="{{ old('website', $lead->website) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>X / Twitter</label>
                <input type="text" name="twitter" value="{{ old('twitter', $lead->twitter) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>LinkedIn</label>
                <input type="text" name="linkedin" value="{{ old('linkedin', $lead->linkedin) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>Facebook</label>
                <input type="text" name="facebook" value="{{ old('facebook', $lead->facebook) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>Instagram</label>
                <input type="text" name="instagram" value="{{ old('instagram', $lead->instagram) }}" class="crm-input">
            </div>
            <div class="crm-field" style="display:flex;align-items:center;gap:.5rem;padding-top:1.5rem;">
                <input type="checkbox" name="email_opt_out" id="email_opt_out" value="1" {{ $lead->email_opt_out ? 'checked' : '' }} style="width:16px;height:16px;accent-color:var(--accent);">
                <label for="email_opt_out" style="font-size:.83rem;color:var(--text-secondary);cursor:pointer;margin:0;">Email Opt-Out</label>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 4: Address Information --}}
<div class="crm-section">
    <div class="crm-section-header"><i class="fas fa-map-marker-alt"></i><span>Address Information</span></div>
    <div class="crm-section-body">
        <div class="crm-grid-4">
            <div class="crm-field">
                <label>Country</label>
                <input type="text" name="country" value="{{ old('country', $lead->country) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>Flat / Building</label>
                <input type="text" name="flat_no" value="{{ old('flat_no', $lead->flat_no) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>Street</label>
                <input type="text" name="street" value="{{ old('street', $lead->street) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>City</label>
                <input type="text" name="city" value="{{ old('city', $lead->city) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>State</label>
                <input type="text" name="state" value="{{ old('state', $lead->state) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>ZIP / Postal Code</label>
                <input type="text" name="zip" value="{{ old('zip', $lead->zip) }}" class="crm-input">
            </div>
        </div>
    </div>
</div>

{{-- SECTION 5: Business Information --}}
<div class="crm-section">
    <div class="crm-section-header"><i class="fas fa-briefcase"></i><span>Business Information</span></div>
    <div class="crm-section-body">
        <div class="crm-grid-4">
            <div class="crm-field">
                <label>Annual Revenue</label>
                <input type="number" name="annual_revenue" value="{{ old('annual_revenue', $lead->annual_revenue) }}" class="crm-input" step="0.01">
            </div>
            <div class="crm-field">
                <label>No. of Employees</label>
                <input type="number" name="no_of_employees" value="{{ old('no_of_employees', $lead->no_of_employees) }}" class="crm-input">
            </div>
        </div>
    </div>
</div>

{{-- SECTION 6: Lead Qualification --}}
<div class="crm-section">
    <div class="crm-section-header"><i class="fas fa-chart-line"></i><span>Lead Qualification</span></div>
    <div class="crm-section-body">
        <div class="crm-grid-4">
            <div class="crm-field">
                <label>Budget</label>
                <input type="number" name="budget" value="{{ old('budget', $lead->budget) }}" class="crm-input" step="0.01">
            </div>
            <div class="crm-field">
                <label>Deal Value</label>
                <input type="number" name="deal_value" value="{{ old('deal_value', $lead->deal_value) }}" class="crm-input" step="0.01">
            </div>
            <div class="crm-field">
                <label>Expected Purchase Date</label>
                <input type="date" name="expected_purchase_date" value="{{ old('expected_purchase_date', $lead->expected_purchase_date ? \Carbon\Carbon::parse($lead->expected_purchase_date)->format('Y-m-d') : '') }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>Priority</label>
                <select name="priority" class="crm-input">
                    @foreach(['','High','Medium','Low'] as $p)
                    <option value="{{ $p }}" {{ $lead->priority == $p ? 'selected' : '' }}>{{ $p ?: '-- Select --' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="crm-field">
                <label>Interest Level</label>
                <select name="interest_level" class="crm-input">
                    @foreach(['','Very High','High','Medium','Low','Very Low'] as $il)
                    <option value="{{ $il }}" {{ $lead->interest_level == $il ? 'selected' : '' }}>{{ $il ?: '-- Select --' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="crm-field">
                <label>Decision Maker</label>
                <select name="decision_maker" class="crm-input">
                    <option value="">-- Select --</option>
                    <option value="Yes" {{ $lead->decision_maker == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $lead->decision_maker == 'No' ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="crm-field">
                <label>Competitor</label>
                <input type="text" name="competitor" value="{{ old('competitor', $lead->competitor) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>Follow-up Date</label>
                <input type="date" name="follow_up_date" value="{{ old('follow_up_date', $lead->follow_up_date ? \Carbon\Carbon::parse($lead->follow_up_date)->format('Y-m-d') : '') }}" class="crm-input">
            </div>
            <div class="crm-field" style="grid-column:1/-1;">
                <label>Requirement</label>
                <textarea name="requirement" class="crm-input" rows="2">{{ old('requirement', $lead->requirement) }}</textarea>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 7: Lead Tracking --}}
<div class="crm-section">
    <div class="crm-section-header"><i class="fas fa-bullhorn"></i><span>Lead Tracking</span></div>
    <div class="crm-section-body">
        <div class="crm-grid-4">
            <div class="crm-field">
                <label>Campaign Source</label>
                <input type="text" name="campaign_source" value="{{ old('campaign_source', $lead->campaign_source) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>Campaign Name</label>
                <input type="text" name="campaign_name" value="{{ old('campaign_name', $lead->campaign_name) }}" class="crm-input">
            </div>
            <div class="crm-field">
                <label>Referral Source</label>
                <input type="text" name="referral_source" value="{{ old('referral_source', $lead->referral_source) }}" class="crm-input">
            </div>
        </div>
    </div>
</div>

{{-- SECTION 8: Description --}}
<div class="crm-section">
    <div class="crm-section-header"><i class="fas fa-align-left"></i><span>Description & Notes</span></div>
    <div class="crm-section-body">
        <div class="crm-grid-2">
            <div class="crm-field">
                <label>Description</label>
                <textarea name="description" class="crm-input" rows="3">{{ old('description', $lead->description) }}</textarea>
            </div>
            <div class="crm-field">
                <label>Internal Notes</label>
                <textarea name="internal_notes" class="crm-input" rows="3">{{ old('internal_notes', $lead->internal_notes) }}</textarea>
            </div>
        </div>
    </div>
</div>

{{-- ACTION BUTTONS --}}
<div style="display:flex;gap:.75rem;justify-content:flex-end;padding:.5rem 0;">
    <a href="{{ route('admin.crm2.sales.leads') }}" style="padding:.55rem 1.25rem;border:1px solid var(--border);border-radius:7px;font-size:.85rem;color:var(--text-secondary);text-decoration:none;background:var(--bg-card);">Cancel</a>
    <button type="submit" style="padding:.55rem 1.5rem;background:var(--accent);color:#fff;border:none;border-radius:7px;font-size:.85rem;font-weight:600;cursor:pointer;">
        <i class="fas fa-save" style="margin-right:.4rem;"></i>Update Lead
    </button>
</div>

</div>
</form>

<style>
.crm-section { background:var(--bg-card); border:1px solid var(--border); border-radius:10px; overflow:hidden; }
.crm-section-header { background:var(--bg-primary); border-bottom:1px solid var(--border); padding:.65rem 1rem; display:flex; align-items:center; gap:.5rem; }
.crm-section-header i { color:var(--accent); width:18px; text-align:center; }
.crm-section-header span { font-size:.85rem; font-weight:600; color:var(--text-primary); }
.crm-section-body { padding:1rem; }
.crm-grid-4 { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:.75rem 1rem; }
.crm-grid-2 { display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:.75rem 1rem; }
.crm-field label { display:block; font-size:.75rem; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:.04em; margin-bottom:.3rem; }
.crm-input { width:100%; padding:.42rem .65rem; font-size:.83rem; border:1px solid var(--border); border-radius:6px; background:var(--bg-primary); color:var(--text-primary); box-sizing:border-box; }
.crm-input:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px color-mix(in srgb,var(--accent) 15%,transparent); }
textarea.crm-input { resize:vertical; min-height:70px; }
</style>
@endsection
