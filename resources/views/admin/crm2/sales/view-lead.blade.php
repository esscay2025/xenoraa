@extends('layouts.admin')
@section('title', 'Lead: ' . ($lead->salutation ? $lead->salutation . ' ' : '') . $lead->first_name . ' ' . $lead->last_name)
@section('page-title', 'Lead Details')
@section('content')
<style>
.lv-wrap { max-width: 1100px; margin: 0 auto; padding: 1.5rem 1rem 3rem; }
.lv-topbar { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
.lv-identity { display: flex; align-items: center; gap: 1rem; }
.lv-avatar { width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border,#e2e8f0); background: var(--bg-primary,#f8fafc); display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: var(--accent,#6366f1); flex-shrink: 0; overflow: hidden; }
.lv-avatar img { width: 100%; height: 100%; object-fit: cover; }
.lv-name { font-size: 1.3rem; font-weight: 700; color: var(--text-primary,#1a1a2e); }
.lv-sub { font-size: .85rem; color: var(--text-secondary,#64748b); margin-top: .2rem; }
.lv-actions { display: flex; gap: .6rem; flex-wrap: wrap; align-items: flex-start; }
.btn-lv { padding: .5rem 1rem; border-radius: 7px; font-size: .85rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: .4rem; border: none; text-decoration: none; transition: opacity .15s; }
.btn-lv-primary { background: var(--accent,#6366f1); color: #fff; }
.btn-lv-success { background: #10b981; color: #fff; }
.btn-lv-warning { background: #f59e0b; color: #fff; }
.btn-lv-danger { background: #ef4444; color: #fff; }
.btn-lv-ghost { background: transparent; color: var(--text-secondary,#64748b); border: 1px solid var(--border,#e2e8f0) !important; }
.btn-lv:hover { opacity: .85; }
.lv-status-bar { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1.25rem; }
.lv-badge { padding: .3rem .8rem; border-radius: 20px; font-size: .78rem; font-weight: 600; display: inline-flex; align-items: center; gap: .3rem; }
.lv-badge-blue { background: #dbeafe; color: #1d4ed8; }
.lv-badge-green { background: #d1fae5; color: #065f46; }
.lv-badge-yellow { background: #fef3c7; color: #92400e; }
.lv-badge-red { background: #fee2e2; color: #991b1b; }
.lv-badge-purple { background: #ede9fe; color: #5b21b6; }
.lv-badge-gray { background: #f1f5f9; color: #475569; }
.lv-grid { display: grid; grid-template-columns: 1fr 340px; gap: 1.25rem; }
@media(max-width:900px){ .lv-grid { grid-template-columns: 1fr; } }
.lv-section { background: var(--bg-card,#fff); border: 1px solid var(--border,#e2e8f0); border-radius: 10px; margin-bottom: 1.25rem; overflow: hidden; }
.lv-section-header { background: var(--bg-primary,#f8fafc); border-bottom: 1px solid var(--border,#e2e8f0); padding: .65rem 1rem; display: flex; align-items: center; gap: .5rem; }
.lv-section-header i { color: var(--accent,#6366f1); width: 18px; text-align: center; }
.lv-section-header span { font-size: .85rem; font-weight: 600; color: var(--text-primary,#1a1a2e); }
.lv-section-body { padding: 1rem; }
.lv-fields { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: .75rem 1.25rem; }
.lv-field label { font-size: .72rem; font-weight: 600; color: var(--text-muted,#94a3b8); text-transform: uppercase; letter-spacing: .04em; display: block; margin-bottom: .2rem; }
.lv-field .val { font-size: .88rem; color: var(--text-primary,#1a1a2e); word-break: break-word; }
.lv-field .val a { color: var(--accent,#6366f1); text-decoration: none; }
.lv-field .val a:hover { text-decoration: underline; }
.lv-empty { color: var(--text-muted,#94a3b8); font-style: italic; }
/* Activity section */
.lv-activity-tabs { display: flex; gap: .5rem; border-bottom: 1px solid var(--border,#e2e8f0); padding: 0 1rem; margin-bottom: 0; }
.lv-activity-tab { padding: .6rem .9rem; font-size: .82rem; font-weight: 600; color: var(--text-secondary,#64748b); cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -1px; transition: color .15s, border-color .15s; }
.lv-activity-tab.active { color: var(--accent,#6366f1); border-bottom-color: var(--accent,#6366f1); }
.lv-activity-pane { display: none; padding: 1rem; }
.lv-activity-pane.active { display: block; }
.lv-act-form { display: grid; grid-template-columns: 1fr 1fr; gap: .65rem; }
.lv-act-form .full { grid-column: 1/-1; }
.lv-input, .lv-select, .lv-textarea { width: 100%; padding: .42rem .65rem; font-size: .83rem; border: 1px solid var(--border,#e2e8f0); border-radius: 6px; background: var(--bg-primary,#f8fafc); color: var(--text-primary,#1a1a2e); }
.lv-input:focus, .lv-select:focus, .lv-textarea:focus { outline: none; border-color: var(--accent,#6366f1); }
.lv-textarea { resize: vertical; min-height: 70px; }
.lv-act-list { list-style: none; padding: 0; margin: 0; }
.lv-act-item { display: flex; align-items: flex-start; gap: .75rem; padding: .65rem 0; border-bottom: 1px solid var(--border,#e2e8f0); }
.lv-act-item:last-child { border-bottom: none; }
.lv-act-icon { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .8rem; flex-shrink: 0; }
.lv-act-icon.task { background: #dbeafe; color: #1d4ed8; }
.lv-act-icon.meeting { background: #d1fae5; color: #065f46; }
.lv-act-icon.call { background: #fef3c7; color: #92400e; }
.lv-act-info { flex: 1; }
.lv-act-subject { font-size: .85rem; font-weight: 600; color: var(--text-primary,#1a1a2e); }
.lv-act-meta { font-size: .75rem; color: var(--text-muted,#94a3b8); margin-top: .15rem; }
.lv-act-status { font-size: .72rem; font-weight: 600; padding: .15rem .5rem; border-radius: 10px; }
.lv-act-status.pending { background: #fef3c7; color: #92400e; }
.lv-act-status.completed { background: #d1fae5; color: #065f46; }
/* Convert modal */
.convert-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 9999; align-items: center; justify-content: center; }
.convert-overlay.show { display: flex; }
.convert-modal { background: var(--bg-card,#fff); border-radius: 12px; width: 100%; max-width: 480px; margin: 1rem; box-shadow: 0 20px 60px rgba(0,0,0,.2); }
.convert-modal-header { padding: 1.1rem 1.25rem; border-bottom: 1px solid var(--border,#e2e8f0); display: flex; align-items: center; justify-content: space-between; }
.convert-modal-header h3 { font-size: 1rem; font-weight: 700; color: var(--text-primary,#1a1a2e); display: flex; align-items: center; gap: .5rem; }
.convert-modal-header h3 i { color: #10b981; }
.convert-close { background: none; border: none; font-size: 1.1rem; color: var(--text-muted,#94a3b8); cursor: pointer; }
.convert-modal-body { padding: 1.25rem; }
.convert-info-row { display: flex; align-items: center; gap: .75rem; padding: .75rem; background: var(--bg-primary,#f8fafc); border-radius: 8px; margin-bottom: .75rem; border: 1px solid var(--border,#e2e8f0); }
.convert-info-row i { font-size: 1.1rem; color: var(--accent,#6366f1); width: 24px; text-align: center; }
.convert-info-row .ci-label { font-size: .75rem; font-weight: 600; color: var(--text-muted,#94a3b8); text-transform: uppercase; letter-spacing: .04em; }
.convert-info-row .ci-val { font-size: .9rem; font-weight: 600; color: var(--text-primary,#1a1a2e); }
.convert-checkbox-row { display: flex; align-items: flex-start; gap: .65rem; padding: .75rem; background: #f0fdf4; border-radius: 8px; border: 1px solid #bbf7d0; margin-top: .25rem; }
.convert-checkbox-row input[type=checkbox] { width: 17px; height: 17px; accent-color: #10b981; margin-top: 2px; flex-shrink: 0; }
.convert-checkbox-row label { font-size: .88rem; font-weight: 600; color: #065f46; cursor: pointer; }
.convert-checkbox-row .sub { font-size: .78rem; color: #047857; margin-top: .15rem; }
.convert-modal-footer { padding: 1rem 1.25rem; border-top: 1px solid var(--border,#e2e8f0); display: flex; gap: .65rem; justify-content: flex-end; }
</style>

<div class="lv-wrap">
  {{-- Top bar --}}
  <div class="lv-topbar">
    <div class="lv-identity">
      <div class="lv-avatar">
        @if($lead->lead_image)
          <img src="{{ asset('storage/'.$lead->lead_image) }}" alt="{{ $lead->first_name }}">
        @else
          <i class="fas fa-user"></i>
        @endif
      </div>
      <div>
        <div class="lv-name">
          {{ $lead->salutation ? $lead->salutation.' ' : '' }}{{ $lead->first_name }} {{ $lead->last_name }}
        </div>
        <div class="lv-sub">
          {{ $lead->title ?? '' }}{{ ($lead->title && $lead->company) ? ' · ' : '' }}{{ $lead->company ?? '' }}
        </div>
      </div>
    </div>
    <div class="lv-actions">
      @if(!$lead->is_converted)
      <button class="btn-lv btn-lv-success" onclick="openConvertModal()">
        <i class="fas fa-exchange-alt"></i> Convert
      </button>
      @else
      <span class="lv-badge lv-badge-green"><i class="fas fa-check-circle"></i> Converted</span>
      @endif
      <a href="{{ route('admin.crm2.sales.leads.edit', $lead->id) }}" class="btn-lv btn-lv-primary">
        <i class="fas fa-edit"></i> Edit
      </a>
      <form method="POST" action="{{ route('admin.crm2.sales.destroy', ['type'=>'lead','id'=>$lead->id]) }}" onsubmit="return confirm('Delete this lead permanently?')" style="display:inline">
        @csrf @method('DELETE')
        <button type="submit" class="btn-lv btn-lv-danger"><i class="fas fa-trash"></i> Delete</button>
      </form>
      <a href="{{ route('admin.crm2.sales.leads') }}" class="btn-lv btn-lv-ghost"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
  </div>

  {{-- Status badges --}}
  <div class="lv-status-bar">
    @php
      $statusColors = ['Not Contacted'=>'gray','Attempted to Contact'=>'yellow','Contacted'=>'blue','Pre-Qualified'=>'purple','Junk Lead'=>'red','Lead Lost'=>'red','Contact in Future'=>'yellow','Not Qualified'=>'gray'];
      $sc = $statusColors[$lead->lead_status ?? 'Not Contacted'] ?? 'gray';
    @endphp
    <span class="lv-badge lv-badge-{{ $sc }}"><i class="fas fa-circle" style="font-size:.5rem"></i> {{ $lead->lead_status ?? 'Not Contacted' }}</span>
    @if($lead->source)<span class="lv-badge lv-badge-blue"><i class="fas fa-share-alt"></i> {{ $lead->source }}</span>@endif
    @if($lead->priority)<span class="lv-badge lv-badge-{{ $lead->priority==='High'?'red':($lead->priority==='Medium'?'yellow':'gray') }}"><i class="fas fa-flag"></i> {{ $lead->priority }} Priority</span>@endif
    @if($lead->rating)<span class="lv-badge lv-badge-purple"><i class="fas fa-star"></i> {{ $lead->rating }}</span>@endif
    @if($lead->owner)<span class="lv-badge lv-badge-gray"><i class="fas fa-user-tie"></i> {{ $lead->owner->name }}</span>@endif
  </div>

  @if(session('success'))<div class="crm2-alert success" style="margin-bottom:1rem"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  @if(session('error'))<div class="crm2-alert danger" style="margin-bottom:1rem"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif

  <div class="lv-grid">
    {{-- Left: detail sections --}}
    <div>
      {{-- Personal Information --}}
      <div class="lv-section">
        <div class="lv-section-header"><i class="fas fa-user"></i><span>Personal Information</span></div>
        <div class="lv-section-body">
          <div class="lv-fields">
            <div class="lv-field"><label>Full Name</label><div class="val">{{ $lead->salutation ? $lead->salutation.' ' : '' }}{{ $lead->first_name }} {{ $lead->last_name }}</div></div>
            <div class="lv-field"><label>Title</label><div class="val">{{ $lead->title ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Company</label><div class="val">{{ $lead->company ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Industry</label><div class="val">{{ $lead->industry ?: '<span class="lv-empty">—</span>' }}</div></div>
          </div>
        </div>
      </div>

      {{-- Contact Information --}}
      <div class="lv-section">
        <div class="lv-section-header"><i class="fas fa-address-card"></i><span>Contact Information</span></div>
        <div class="lv-section-body">
          <div class="lv-fields">
            <div class="lv-field"><label>Email</label><div class="val">@if($lead->email)<a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>@else<span class="lv-empty">—</span>@endif</div></div>
            <div class="lv-field"><label>Secondary Email</label><div class="val">@if($lead->secondary_email)<a href="mailto:{{ $lead->secondary_email }}">{{ $lead->secondary_email }}</a>@else<span class="lv-empty">—</span>@endif</div></div>
            <div class="lv-field"><label>Phone</label><div class="val">@if($lead->phone)<a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a>@else<span class="lv-empty">—</span>@endif</div></div>
            <div class="lv-field"><label>Mobile</label><div class="val">@if($lead->mobile)<a href="tel:{{ $lead->mobile }}">{{ $lead->mobile }}</a>@else<span class="lv-empty">—</span>@endif</div></div>
            <div class="lv-field"><label>Fax</label><div class="val">{{ $lead->fax ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Website</label><div class="val">@if($lead->website)<a href="{{ $lead->website }}" target="_blank">{{ $lead->website }}</a>@else<span class="lv-empty">—</span>@endif</div></div>
            <div class="lv-field"><label><i class="fab fa-twitter" style="color:#1da1f2"></i> X</label><div class="val">{{ $lead->twitter ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label><i class="fab fa-linkedin" style="color:#0077b5"></i> LinkedIn</label><div class="val">@if($lead->linkedin)<a href="{{ $lead->linkedin }}" target="_blank">View Profile</a>@else<span class="lv-empty">—</span>@endif</div></div>
            <div class="lv-field"><label><i class="fab fa-facebook" style="color:#1877f2"></i> Facebook</label><div class="val">@if($lead->facebook)<a href="{{ $lead->facebook }}" target="_blank">View Profile</a>@else<span class="lv-empty">—</span>@endif</div></div>
            <div class="lv-field"><label><i class="fab fa-instagram" style="color:#e1306c"></i> Instagram</label><div class="val">{{ $lead->instagram ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Email Opt Out</label><div class="val">{{ $lead->email_opt_out ? '<span style="color:#ef4444;font-weight:600">Yes</span>' : 'No' }}</div></div>
          </div>
        </div>
      </div>

      {{-- Address Information --}}
      <div class="lv-section">
        <div class="lv-section-header"><i class="fas fa-map-marker-alt"></i><span>Address Information</span></div>
        <div class="lv-section-body">
          <div class="lv-fields">
            <div class="lv-field"><label>Flat / Building</label><div class="val">{{ $lead->flat_no ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Street</label><div class="val">{{ $lead->street ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>City</label><div class="val">{{ $lead->city ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>State</label><div class="val">{{ $lead->state ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Zip</label><div class="val">{{ $lead->zip ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Country</label><div class="val">{{ $lead->country ?: '<span class="lv-empty">—</span>' }}</div></div>
          </div>
        </div>
      </div>

      {{-- Business Information --}}
      <div class="lv-section">
        <div class="lv-section-header"><i class="fas fa-briefcase"></i><span>Business Information</span></div>
        <div class="lv-section-body">
          <div class="lv-fields">
            <div class="lv-field"><label>Annual Revenue</label><div class="val">{{ $lead->annual_revenue ? '₹'.number_format($lead->annual_revenue,0) : '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>No. of Employees</label><div class="val">{{ $lead->no_of_employees ? number_format($lead->no_of_employees) : '<span class="lv-empty">—</span>' }}</div></div>
          </div>
        </div>
      </div>

      {{-- Lead Qualification --}}
      <div class="lv-section">
        <div class="lv-section-header"><i class="fas fa-star"></i><span>Lead Qualification</span></div>
        <div class="lv-section-body">
          <div class="lv-fields">
            <div class="lv-field"><label>Budget</label><div class="val">{{ $lead->budget ? '₹'.number_format($lead->budget,0) : '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Deal Value</label><div class="val">{{ $lead->deal_value ? '₹'.number_format($lead->deal_value,0) : '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Expected Purchase</label><div class="val">{{ $lead->expected_purchase_date ? \Carbon\Carbon::parse($lead->expected_purchase_date)->format('d M Y') : '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Priority</label><div class="val">{{ $lead->priority ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Interest Level</label><div class="val">{{ $lead->interest_level ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Decision Maker</label><div class="val">{{ $lead->decision_maker ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Competitor</label><div class="val">{{ $lead->competitor ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Follow-up Date</label><div class="val">{{ $lead->follow_up_date ? \Carbon\Carbon::parse($lead->follow_up_date)->format('d M Y') : '<span class="lv-empty">—</span>' }}</div></div>
          </div>
          @if($lead->requirement)
          <div style="margin-top:.75rem">
            <div class="lv-field"><label>Requirement</label><div class="val" style="white-space:pre-line">{{ $lead->requirement }}</div></div>
          </div>
          @endif
        </div>
      </div>

      {{-- Lead Tracking --}}
      <div class="lv-section">
        <div class="lv-section-header"><i class="fas fa-chart-line"></i><span>Lead Tracking</span></div>
        <div class="lv-section-body">
          <div class="lv-fields">
            <div class="lv-field"><label>Lead Source</label><div class="val">{{ $lead->source ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Campaign Source</label><div class="val">{{ $lead->campaign_source ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Campaign Name</label><div class="val">{{ $lead->campaign_name ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Referral Source</label><div class="val">{{ $lead->referral_source ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Created Date</label><div class="val">{{ $lead->created_at->format('d M Y, h:i A') }}</div></div>
            <div class="lv-field"><label>Last Activity</label><div class="val">{{ $lead->last_activity_date ? \Carbon\Carbon::parse($lead->last_activity_date)->format('d M Y') : '<span class="lv-empty">—</span>' }}</div></div>
            @if($lead->is_converted)
            <div class="lv-field"><label>Converted Date</label><div class="val">{{ $lead->converted_date ? \Carbon\Carbon::parse($lead->converted_date)->format('d M Y') : '—' }}</div></div>
            @endif
          </div>
        </div>
      </div>

      {{-- Description & Notes --}}
      @if($lead->description || $lead->internal_notes)
      <div class="lv-section">
        <div class="lv-section-header"><i class="fas fa-sticky-note"></i><span>Description &amp; Notes</span></div>
        <div class="lv-section-body">
          @if($lead->description)
          <div class="lv-field" style="margin-bottom:.75rem"><label>Description</label><div class="val" style="white-space:pre-line">{{ $lead->description }}</div></div>
          @endif
          @if($lead->internal_notes)
          <div class="lv-field"><label>Internal Notes</label><div class="val" style="white-space:pre-line">{{ $lead->internal_notes }}</div></div>
          @endif
        </div>
      </div>
      @endif
    </div>

    {{-- Right sidebar --}}
    <div>
      {{-- Quick Info Card --}}
      <div class="lv-section" style="margin-bottom:1.25rem">
        <div class="lv-section-header"><i class="fas fa-info-circle"></i><span>Quick Info</span></div>
        <div class="lv-section-body" style="padding:.75rem">
          <div style="display:flex;flex-direction:column;gap:.6rem">
            <div class="lv-field"><label>Lead Owner</label><div class="val">{{ $lead->owner ? $lead->owner->name : '<span class="lv-empty">Unassigned</span>' }}</div></div>
            <div class="lv-field"><label>Status</label><div class="val">{{ $lead->lead_status ?? 'Not Contacted' }}</div></div>
            <div class="lv-field"><label>Rating</label><div class="val">{{ $lead->rating ?: '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Deal Value</label><div class="val">{{ $lead->deal_value ? '₹'.number_format($lead->deal_value,0) : '<span class="lv-empty">—</span>' }}</div></div>
            <div class="lv-field"><label>Follow-up</label><div class="val">{{ $lead->follow_up_date ? \Carbon\Carbon::parse($lead->follow_up_date)->format('d M Y') : '<span class="lv-empty">—</span>' }}</div></div>
          </div>
        </div>
      </div>

      {{-- Activities Section --}}
      <div class="lv-section">
        <div class="lv-section-header"><i class="fas fa-tasks"></i><span>Activities</span></div>
        <div class="lv-activity-tabs">
          <div class="lv-activity-tab active" onclick="switchTab(this,'tab-log')">Log Activity</div>
          <div class="lv-activity-tab" onclick="switchTab(this,'tab-history')">History ({{ count($activities) }})</div>
        </div>

        {{-- Log Activity --}}
        <div class="lv-activity-pane active" id="tab-log">
          <div style="display:flex;gap:.5rem;margin-bottom:.75rem">
            <button class="btn-lv btn-lv-primary" style="font-size:.78rem;padding:.35rem .75rem" onclick="setActivityType('task',this)"><i class="fas fa-check-square"></i> Task</button>
            <button class="btn-lv btn-lv-ghost" style="font-size:.78rem;padding:.35rem .75rem" onclick="setActivityType('meeting',this)"><i class="fas fa-calendar-alt"></i> Meeting</button>
            <button class="btn-lv btn-lv-ghost" style="font-size:.78rem;padding:.35rem .75rem" onclick="setActivityType('call',this)"><i class="fas fa-phone"></i> Call</button>
          </div>
          <form method="POST" action="{{ route('admin.crm2.activity.store') }}">
            @csrf
            <input type="hidden" name="related_type" value="lead">
            <input type="hidden" name="related_id" value="{{ $lead->id }}">
            <input type="hidden" name="type" id="act-type" value="task">
            <div class="lv-act-form">
              <div class="full">
                <input type="text" name="subject" class="lv-input" placeholder="Subject / Title" required>
              </div>
              <div>
                <input type="datetime-local" name="due_at" class="lv-input">
              </div>
              <div>
                <select name="status" class="lv-select">
                  <option value="pending">Pending</option>
                  <option value="completed">Completed</option>
                </select>
              </div>
              <div class="full">
                <textarea name="description" class="lv-textarea" placeholder="Description (optional)"></textarea>
              </div>
              <div class="full" style="text-align:right">
                <button type="submit" class="btn-lv btn-lv-primary" style="font-size:.82rem"><i class="fas fa-plus"></i> Add Activity</button>
              </div>
            </div>
          </form>
        </div>

        {{-- Activity History --}}
        <div class="lv-activity-pane" id="tab-history">
          @if(count($activities) === 0)
          <div style="text-align:center;padding:1.5rem;color:var(--text-muted,#94a3b8)"><i class="fas fa-history" style="font-size:1.5rem;margin-bottom:.5rem;display:block"></i>No activities yet.</div>
          @else
          <ul class="lv-act-list">
            @foreach($activities as $act)
            <li class="lv-act-item">
              <div class="lv-act-icon {{ $act->type }}">
                @if($act->type==='task')<i class="fas fa-check-square"></i>
                @elseif($act->type==='meeting')<i class="fas fa-calendar-alt"></i>
                @else<i class="fas fa-phone"></i>@endif
              </div>
              <div class="lv-act-info">
                <div class="lv-act-subject">{{ $act->subject }}</div>
                <div class="lv-act-meta">
                  {{ ucfirst($act->type) }}
                  @if($act->due_at) · Due {{ \Carbon\Carbon::parse($act->due_at)->format('d M Y') }}@endif
                </div>
              </div>
              <span class="lv-act-status {{ $act->status }}">{{ ucfirst($act->status) }}</span>
            </li>
            @endforeach
          </ul>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Convert Confirmation Modal --}}
<div class="convert-overlay" id="convert-overlay">
  <div class="convert-modal">
    <div class="convert-modal-header">
      <h3><i class="fas fa-exchange-alt"></i> Convert Lead</h3>
      <button class="convert-close" onclick="closeConvertModal()"><i class="fas fa-times"></i></button>
    </div>
    <div class="convert-modal-body">
      <p style="font-size:.85rem;color:var(--text-secondary,#64748b);margin-bottom:1rem">
        Converting this lead will create the following records automatically:
      </p>
      <div class="convert-info-row">
        <i class="fas fa-building"></i>
        <div>
          <div class="ci-label">Create New Account</div>
          <div class="ci-val">{{ $lead->company ?: ($lead->first_name.' '.$lead->last_name) }}</div>
        </div>
      </div>
      <div class="convert-info-row">
        <i class="fas fa-user"></i>
        <div>
          <div class="ci-label">Create New Contact</div>
          <div class="ci-val">{{ ($lead->salutation ? $lead->salutation.' ' : '') }}{{ $lead->first_name }} {{ $lead->last_name }}</div>
        </div>
      </div>
      <div class="convert-checkbox-row">
        <input type="checkbox" id="create_deal" name="create_deal" value="1" checked>
        <div>
          <label for="create_deal">Create a new Deal for this Account</label>
          <div class="sub">A deal will be created linked to the new account and contact{{ $lead->deal_value ? ' with value ₹'.number_format($lead->deal_value,0) : '' }}.</div>
        </div>
      </div>
    </div>
    <div class="convert-modal-footer">
      <button class="btn-lv btn-lv-ghost" onclick="closeConvertModal()">Cancel</button>
      <form method="POST" action="{{ route('admin.crm2.sales.leads.convert', $lead->id) }}" id="convert-form">
        @csrf
        <input type="hidden" name="create_deal" id="convert-deal-input" value="1">
        <button type="submit" class="btn-lv btn-lv-success"><i class="fas fa-exchange-alt"></i> Convert Now</button>
      </form>
    </div>
  </div>
</div>

<script>
function openConvertModal() {
  document.getElementById('convert-overlay').classList.add('show');
}
function closeConvertModal() {
  document.getElementById('convert-overlay').classList.remove('show');
}
document.getElementById('create_deal').addEventListener('change', function() {
  document.getElementById('convert-deal-input').value = this.checked ? '1' : '0';
});
function switchTab(el, tabId) {
  document.querySelectorAll('.lv-activity-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.lv-activity-pane').forEach(p => p.classList.remove('active'));
  el.classList.add('active');
  document.getElementById(tabId).classList.add('active');
}
function setActivityType(type, btn) {
  document.getElementById('act-type').value = type;
  document.querySelectorAll('.lv-activity-pane button[onclick^="setActivityType"]').forEach(b => {
    b.classList.remove('btn-lv-primary');
    b.classList.add('btn-lv-ghost');
  });
  btn.classList.remove('btn-lv-ghost');
  btn.classList.add('btn-lv-primary');
}
document.addEventListener('keydown', e => { if(e.key==='Escape') closeConvertModal(); });
</script>
@endsection
