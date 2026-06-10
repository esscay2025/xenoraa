@extends('layouts.admin')
@section('title', 'Edit Contact')
@section('page-title', 'Edit Contact')
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
.cf-avatar-upload{display:flex;align-items:center;gap:1rem;grid-column:1/-1}
.cf-avatar-preview{width:72px;height:72px;border-radius:50%;background:var(--bg-primary);border:2px solid var(--border);display:flex;align-items:center;justify-content:center;overflow:hidden}
.cf-avatar-preview img{width:100%;height:100%;object-fit:cover}
.cf-avatar-preview i{font-size:1.8rem;color:var(--text-muted)}
</style>
<div class="cf-wrap">
  <div class="cf-header">
    <div>
      <div class="cf-breadcrumb"><a href="{{ route('admin.crm2.sales.contacts') }}">Contacts</a> / Edit Contact</div>
      <h1><i class="fas fa-user-edit"></i> Edit Contact</h1>
    </div>
    <a href="{{ route('admin.crm2.sales.contacts') }}" class="cf-btn cf-btn-ghost"><i class="fas fa-arrow-left"></i> Back</a>
  </div>
  @if($errors->any())<div class="crm2-alert error" style="margin-bottom:1rem"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>@endif
  @if(session('success'))<div class="crm2-alert success" style="margin-bottom:1rem"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <form method="POST" action="{{ route('admin.crm2.sales.update', ['type'=>'contact','id'=>$item->id]) }}" enctype="multipart/form-data">
    @csrf @method('PATCH')
    {{-- Contact Profile --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-id-card"></i> Contact Profile</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-avatar-upload">
          <div class="cf-avatar-preview" id="avatar-preview">
            @if($item->contact_image)<img src="{{ asset('storage/'.$item->contact_image) }}" alt="photo">@else<i class="fas fa-user"></i>@endif
          </div>
          <div>
            <label class="cf-btn cf-btn-ghost" style="cursor:pointer"><i class="fas fa-upload"></i> Change Photo<input type="file" name="contact_image" accept="image/*" style="display:none" onchange="previewAvatar(this)"></label>
            <p style="font-size:.75rem;color:var(--text-muted);margin-top:.3rem">JPG, PNG up to 2MB</p>
          </div>
        </div>
        <div class="cf-field">
          <label>Contact Owner</label>
          <select name="owner_id">
            <option value="">-- Select Owner --</option>
            @foreach($staff as $s)<option value="{{ $s->id }}" {{ old('owner_id',$item->owner_id)==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Salutation</label>
          <select name="salutation">
            <option value="">--</option>
            @foreach(['Mr.','Mrs.','Ms.','Dr.','Prof.'] as $sal)<option value="{{ $sal }}" {{ old('salutation',$item->salutation)==$sal?'selected':'' }}>{{ $sal }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>First Name <span style="color:red">*</span></label>
          <input type="text" name="first_name" value="{{ old('first_name',$item->first_name) }}" required placeholder="First name">
        </div>
        <div class="cf-field">
          <label>Last Name</label>
          <input type="text" name="last_name" value="{{ old('last_name',$item->last_name) }}" placeholder="Last name">
        </div>
        <div class="cf-field">
          <label>Reporting To</label>
          <select name="reporting_to">
            <option value="">-- None --</option>
            @foreach($staff as $s)<option value="{{ $s->id }}" {{ old('reporting_to',$item->reporting_to)==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
          </select>
        </div>
      </div>
    </div>
    {{-- Organization Information --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-building"></i> Organization Information</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field">
          <label>Account Name</label>
          <select name="account_id">
            <option value="">-- None --</option>
            @foreach($accounts_list as $acc)<option value="{{ $acc->id }}" {{ old('account_id',$item->account_id)==$acc->id?'selected':'' }}>{{ $acc->name }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field"><label>Vendor Name</label><input type="text" name="vendor_name" value="{{ old('vendor_name',$item->vendor_name) }}" placeholder="Vendor name"></div>
        <div class="cf-field"><label>Department</label><input type="text" name="department" value="{{ old('department',$item->department) }}" placeholder="Department"></div>
        <div class="cf-field"><label>Title / Job Title</label><input type="text" name="job_title" value="{{ old('job_title',$item->job_title) }}" placeholder="Job title"></div>
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
        <div class="cf-field"><label>Other Phone</label><input type="text" name="other_phone" value="{{ old('other_phone',$item->other_phone) }}" placeholder="Other phone"></div>
        <div class="cf-field"><label>Home Phone</label><input type="text" name="home_phone" value="{{ old('home_phone',$item->home_phone) }}" placeholder="Home phone"></div>
        <div class="cf-field"><label>Fax</label><input type="text" name="fax" value="{{ old('fax',$item->fax) }}" placeholder="Fax number"></div>
        <div class="cf-field">
          <label>Email Opt Out</label>
          <div class="cf-check"><input type="checkbox" name="email_opt_out" value="1" {{ old('email_opt_out',$item->email_opt_out)?'checked':'' }}><span style="font-size:.85rem;color:var(--text-secondary)">Do not send marketing emails</span></div>
        </div>
      </div>
    </div>
    {{-- Professional Information --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-briefcase"></i> Professional Information</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field">
          <label>Lead Source</label>
          <select name="lead_source">
            <option value="">-- None --</option>
            @foreach(['Cold Call','Existing Customer','Self Generated','Employee','Partner','Public Relations','Direct Mail','Conference','Trade Show','Web Site','Word of Mouth','Other'] as $src)
            <option value="{{ $src }}" {{ old('lead_source',$item->lead_source)==$src?'selected':'' }}>{{ $src }}</option>
            @endforeach
          </select>
        </div>
        <div class="cf-field"><label>Assistant</label><input type="text" name="assistant" value="{{ old('assistant',$item->assistant) }}" placeholder="Assistant name"></div>
        <div class="cf-field"><label>Assistant Phone</label><input type="text" name="assistant_phone" value="{{ old('assistant_phone',$item->assistant_phone) }}" placeholder="Assistant phone"></div>
      </div>
    </div>
    {{-- Personal Information --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-user-circle"></i> Personal Information</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field"><label>Date of Birth</label><input type="date" name="date_of_birth" value="{{ old('date_of_birth',$item->date_of_birth) }}"></div>
        <div class="cf-field"><label>Skype ID</label><input type="text" name="skype_id" value="{{ old('skype_id',$item->skype_id) }}" placeholder="Skype username"></div>
        <div class="cf-field"><label>Twitter / X</label><input type="text" name="twitter" value="{{ old('twitter',$item->twitter) }}" placeholder="@handle"></div>
      </div>
    </div>
    {{-- Mailing Address --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-map-marker-alt"></i> Mailing Address</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field"><label>Country</label><input type="text" name="mailing_country" value="{{ old('mailing_country',$item->mailing_country) }}" placeholder="Country"></div>
        <div class="cf-field"><label>Building / Flat</label><input type="text" name="mailing_building" value="{{ old('mailing_building',$item->mailing_building) }}" placeholder="Building name"></div>
        <div class="cf-field"><label>Street</label><input type="text" name="mailing_street" value="{{ old('mailing_street',$item->mailing_street) }}" placeholder="Street address"></div>
        <div class="cf-field"><label>City</label><input type="text" name="mailing_city" value="{{ old('mailing_city',$item->mailing_city) }}" placeholder="City"></div>
        <div class="cf-field"><label>State</label><input type="text" name="mailing_state" value="{{ old('mailing_state',$item->mailing_state) }}" placeholder="State"></div>
        <div class="cf-field"><label>Postal Code</label><input type="text" name="mailing_zip" value="{{ old('mailing_zip',$item->mailing_zip) }}" placeholder="ZIP / Postal code"></div>
        <div class="cf-field"><label>Latitude</label><input type="text" name="mailing_lat" value="{{ old('mailing_lat',$item->mailing_lat) }}" placeholder="e.g. 13.0827"></div>
        <div class="cf-field"><label>Longitude</label><input type="text" name="mailing_lng" value="{{ old('mailing_lng',$item->mailing_lng) }}" placeholder="e.g. 80.2707"></div>
      </div>
    </div>
    {{-- Other Address --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-map"></i> Other Address</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field"><label>Country</label><input type="text" name="other_country" value="{{ old('other_country',$item->other_country) }}" placeholder="Country"></div>
        <div class="cf-field"><label>Building / Flat</label><input type="text" name="other_building" value="{{ old('other_building',$item->other_building) }}" placeholder="Building name"></div>
        <div class="cf-field"><label>Street</label><input type="text" name="other_street" value="{{ old('other_street',$item->other_street) }}" placeholder="Street address"></div>
        <div class="cf-field"><label>City</label><input type="text" name="other_city" value="{{ old('other_city',$item->other_city) }}" placeholder="City"></div>
        <div class="cf-field"><label>State</label><input type="text" name="other_state" value="{{ old('other_state',$item->other_state) }}" placeholder="State"></div>
        <div class="cf-field"><label>Postal Code</label><input type="text" name="other_zip" value="{{ old('other_zip',$item->other_zip) }}" placeholder="ZIP / Postal code"></div>
        <div class="cf-field"><label>Latitude</label><input type="text" name="other_lat" value="{{ old('other_lat',$item->other_lat) }}" placeholder="e.g. 13.0827"></div>
        <div class="cf-field"><label>Longitude</label><input type="text" name="other_lng" value="{{ old('other_lng',$item->other_lng) }}" placeholder="e.g. 80.2707"></div>
      </div>
    </div>
    {{-- Description & Notes --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-sticky-note"></i> Description & Notes</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body" style="grid-template-columns:1fr">
        <div class="cf-field"><label>Description</label><textarea name="description" placeholder="Contact description...">{{ old('description',$item->description) }}</textarea></div>
        <div class="cf-field"><label>Notes</label><textarea name="notes" placeholder="Internal notes...">{{ old('notes',$item->notes) }}</textarea></div>
      </div>
    </div>
    <div class="cf-actions">
      <a href="{{ route('admin.crm2.sales.contacts') }}" class="cf-btn cf-btn-ghost">Cancel</a>
      <button type="submit" class="cf-btn cf-btn-primary"><i class="fas fa-save"></i> Update Contact</button>
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
