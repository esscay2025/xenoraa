@extends('layouts.admin')
@section('title', $contact->first_name.' '.$contact->last_name)
@section('page-title', 'Contact Detail')
@push('styles')
<style>
/* 3-dot action menu */
.xn-bulk-wrap { position: relative; display: inline-block; }
.xn-bulk-btn { width: 34px; height: 34px; border-radius: 7px; border: 1px solid var(--border,#e2e8f0); background: var(--bg-card,#fff); color: var(--text-secondary,#64748b); font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .15s; }
.xn-bulk-btn:hover { background: var(--bg-hover,#f1f5f9); }
.xn-bulk-drop { display: none; position: absolute; right: 0; top: calc(100% + 4px); min-width: 200px; background: var(--bg-card,#fff); border: 1px solid var(--border,#e2e8f0); border-radius: 9px; box-shadow: 0 8px 24px rgba(0,0,0,.12); z-index: 999; padding: 5px 0; }
.xn-bulk-drop.open { display: block; }
.xn-bulk-item { display: flex; align-items: center; gap: .6rem; padding: .55rem 1rem; font-size: .84rem; color: var(--text-primary,#1a1a2e); cursor: pointer; transition: background .12s; border: none; background: none; width: 100%; text-align: left; text-decoration: none; }
.xn-bulk-item:hover { background: var(--bg-hover,#f1f5f9); }
.xn-bulk-item i { width: 16px; text-align: center; }
.xn-bulk-item.danger { color: #ef4444; }
</style>
@endpush
@section('content')
<style>
.cv-wrap{max-width:1100px;margin:0 auto;padding:1.5rem}
.cv-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;gap:1rem;flex-wrap:wrap}
.cv-identity{display:flex;align-items:center;gap:1rem}
.cv-avatar{width:72px;height:72px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:700;color:#fff;overflow:hidden;flex-shrink:0}
.cv-avatar img{width:100%;height:100%;object-fit:cover}
.cv-name{font-size:1.4rem;font-weight:700;color:var(--text-primary)}
.cv-sub{font-size:.85rem;color:var(--text-muted);margin-top:.2rem}
.cv-badges{display:flex;gap:.4rem;flex-wrap:wrap;margin-top:.4rem}
.cv-badge{padding:.2rem .6rem;border-radius:20px;font-size:.72rem;font-weight:600;background:var(--bg-primary);border:1px solid var(--border);color:var(--text-secondary)}
.cv-actions{display:flex;gap:.5rem;flex-wrap:wrap}
.cv-btn{padding:.5rem 1rem;border-radius:7px;font-size:.82rem;font-weight:600;cursor:pointer;border:none;display:inline-flex;align-items:center;gap:.4rem;text-decoration:none;transition:all .2s}
.cv-btn-primary{background:var(--accent);color:#fff}
.cv-btn-ghost{background:transparent;border:1px solid var(--border);color:var(--text-secondary)}
.cv-btn-ghost:hover{background:var(--bg-primary)}
.cv-layout{display:grid;grid-template-columns:1fr 320px;gap:1.2rem}
@media(max-width:768px){.cv-layout{grid-template-columns:1fr}}
.cv-card{background:var(--bg-card);border:1px solid var(--border);border-radius:10px;margin-bottom:1.2rem;overflow:hidden}
.cv-card-header{padding:.75rem 1.2rem;background:var(--bg-primary);border-bottom:1px solid var(--border);font-size:.88rem;font-weight:600;color:var(--text-primary);display:flex;align-items:center;gap:.5rem}
.cv-card-header i{color:var(--accent)}
.cv-card-body{padding:1.2rem}
.cv-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:.8rem}
.cv-field{display:flex;flex-direction:column;gap:.2rem}
.cv-field label{font-size:.72rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em}
.cv-field .val{font-size:.85rem;color:var(--text-primary)}
.cv-field .val.empty{color:var(--text-muted);font-style:italic}
.cv-divider{border:none;border-top:1px solid var(--border);margin:.8rem 0}
.cv-activity-form{display:flex;flex-direction:column;gap:.6rem}
.cv-activity-form select,.cv-activity-form input,.cv-activity-form textarea{background:var(--bg-primary);border:1px solid var(--border);border-radius:6px;padding:.4rem .6rem;font-size:.82rem;color:var(--text-primary);width:100%}
.cv-activity-list{display:flex;flex-direction:column;gap:.6rem;max-height:300px;overflow-y:auto}
.cv-activity-item{display:flex;gap:.6rem;align-items:flex-start;padding:.5rem;background:var(--bg-primary);border-radius:6px;border:1px solid var(--border)}
.cv-activity-icon{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.75rem;flex-shrink:0}
.cv-activity-icon.task{background:#6366f120;color:#6366f1}
.cv-activity-icon.meeting{background:#22c55e20;color:#22c55e}
.cv-activity-icon.call{background:#f59e0b20;color:#f59e0b}
.cv-related-item{display:flex;align-items:center;justify-content:space-between;padding:.5rem;background:var(--bg-primary);border-radius:6px;border:1px solid var(--border);margin-bottom:.4rem;font-size:.82rem}
.cv-related-item a{color:var(--accent);text-decoration:none;font-weight:600}
</style>
<div class="cv-wrap">
  {{-- Header --}}
  <div class="cv-header">
    <div class="cv-identity">
      <div class="cv-avatar">
        @if($contact->contact_image)
          <img src="{{ asset('storage/'.$contact->contact_image) }}" alt="avatar">
        @else
          {{ strtoupper(substr($contact->first_name??'C',0,1)) }}
        @endif
      </div>
      <div>
        <div class="cv-name">{{ $contact->first_name }} {{ $contact->last_name }}</div>
        <div class="cv-sub">{{ $contact->job_title ?? '' }}{{ $contact->job_title && $contact->account ? ' · ' : '' }}{{ $contact->account?->name ?? '' }}</div>
        <div class="cv-badges">
          @if($contact->lead_source)<span class="cv-badge"><i class="fas fa-funnel-dollar"></i> {{ $contact->lead_source }}</span>@endif
          @if($contact->status)<span class="cv-badge">{{ ucfirst($contact->status) }}</span>@endif
          @if($contact->email_opt_out)<span class="cv-badge" style="background:#ef444420;color:#ef4444;border-color:#ef444440">Email Opt-Out</span>@endif
        </div>
      </div>
    </div>
    <div class="cv-actions">
      <a href="{{ route('admin.crm2.sales.contacts.edit', $contact->id) }}" class="cv-btn cv-btn-primary"><i class="fas fa-edit"></i> Edit</a>
      <a href="{{ route('admin.crm2.sales.contacts') }}" class="cv-btn cv-btn-ghost"><i class="fas fa-arrow-left"></i> Back</a>
      {{-- 3-dot action menu --}}
      <div class="xn-bulk-wrap">
        <button class="xn-bulk-btn" id="lvActBtn" onclick="toggleActMenu(event)" title="More actions">&#8942;</button>
        <div class="xn-bulk-drop" id="lvActDrop">
          <form method="POST" action="{{ route('admin.crm2.sales.contacts.clone', $contact->id) }}" style="margin:0">
            @csrf
            <button type="submit" class="xn-bulk-item"><i class="fas fa-copy" style="color:#6366f1"></i> Clone Contact</button>
          </form>
          <button class="xn-bulk-item" onclick="window.print()"><i class="fas fa-print" style="color:#10b981"></i> Print Preview</button>
          <div style="border-top:1px solid var(--border,#e2e8f0);margin:4px 0"></div>
          <form method="POST" action="{{ route('admin.crm2.sales.destroy', ['type'=>'contact','id'=>$contact->id]) }}" onsubmit="return confirm('Delete this contact permanently?')" style="margin:0">
            @csrf @method('DELETE')
            <button type="submit" class="xn-bulk-item danger"><i class="fas fa-trash"></i> Delete Contact</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="cv-layout">
    {{-- Left: Detail Sections --}}
    <div>
      {{-- Contact Profile --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-id-card"></i> Contact Profile</div>
        <div class="cv-card-body">
          <div class="cv-grid">
            <div class="cv-field"><label>Contact Owner</label><div class="val">{{ $contact->owner?->name ?: '—' }}</div></div>
            <div class="cv-field"><label>First Name</label><div class="val">{{ $contact->first_name ?: '—' }}</div></div>
            <div class="cv-field"><label>Last Name</label><div class="val">{{ $contact->last_name ?: '—' }}</div></div>
            <div class="cv-field"><label>Reporting To</label><div class="val">{{ $contact->reportingTo?->name ?: '—' }}</div></div>
          </div>
        </div>
      </div>
      {{-- Organization --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-building"></i> Organization Information</div>
        <div class="cv-card-body">
          <div class="cv-grid">
            <div class="cv-field"><label>Account Name</label><div class="val">@if($contact->account)<a href="{{ route('admin.crm2.sales.accounts.show', $contact->account_id) }}" style="color:var(--accent)">{{ $contact->account->name }}</a>@else<span style="color:var(--text-muted);font-style:italic;">—</span>@endif</div></div>
            <div class="cv-field"><label>Department</label><div class="val">{{ $contact->department ?: '—' }}</div></div>
            <div class="cv-field"><label>Title</label><div class="val">{{ $contact->job_title ?: '—' }}</div></div>
          </div>
        </div>
      </div>
      {{-- Contact Info --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-phone"></i> Contact Information</div>
        <div class="cv-card-body">
          <div class="cv-grid">
            <div class="cv-field"><label>Email</label><div class="val">{{ $contact->email ?: '—' }}</div></div>
            <div class="cv-field"><label>Secondary Email</label><div class="val">{{ $contact->secondary_email ?: '—' }}</div></div>
            <div class="cv-field"><label>Phone</label><div class="val">{{ $contact->phone ?: '—' }}</div></div>
            <div class="cv-field"><label>Mobile</label><div class="val">{{ $contact->mobile ?: '—' }}</div></div>
            <div class="cv-field"><label>Other Phone</label><div class="val">{{ $contact->other_phone ?: '—' }}</div></div>
            <div class="cv-field"><label>Home Phone</label><div class="val">{{ $contact->home_phone ?: '—' }}</div></div>
            <div class="cv-field"><label>Fax</label><div class="val">{{ $contact->fax ?: '—' }}</div></div>
            <div class="cv-field"><label>Email Opt Out</label><div class="val">{{ $contact->email_opt_out ? 'Yes' : 'No' }}</div></div>
          </div>
        </div>
      </div>
      {{-- Professional --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-briefcase"></i> Professional Information</div>
        <div class="cv-card-body">
          <div class="cv-grid">
            <div class="cv-field"><label>Lead Source</label><div class="val">{{ $contact->lead_source ?: '—' }}</div></div>
            <div class="cv-field"><label>Assistant</label><div class="val">{{ $contact->assistant ?: '—' }}</div></div>
            <div class="cv-field"><label>Assistant Phone</label><div class="val">{{ $contact->assistant_phone ?: '—' }}</div></div>
          </div>
        </div>
      </div>
      {{-- Personal --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-user-circle"></i> Personal Information</div>
        <div class="cv-card-body">
          <div class="cv-grid">
            <div class="cv-field"><label>Date of Birth</label><div class="val">{{ $contact->date_of_birth ? \Carbon\Carbon::parse($contact->date_of_birth)->format('d M Y') : '<span style="color:var(--text-muted);font-style:italic;">—</span>' }}</div></div>
            <div class="cv-field"><label>Skype ID</label><div class="val">{{ $contact->skype_id ?: '—' }}</div></div>
            <div class="cv-field"><label>Twitter / X</label><div class="val">{{ $contact->twitter ?: '—' }}</div></div>
          </div>
        </div>
      </div>
      {{-- Mailing Address --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-map-marker-alt"></i> Mailing Address</div>
        <div class="cv-card-body">
          <div class="cv-grid">
            <div class="cv-field"><label>Country</label><div class="val">{{ $contact->mailing_country ?: '—' }}</div></div>
            <div class="cv-field"><label>Building</label><div class="val">{{ $contact->mailing_building ?: '—' }}</div></div>
            <div class="cv-field"><label>Street</label><div class="val">{{ $contact->mailing_street ?: '—' }}</div></div>
            <div class="cv-field"><label>City</label><div class="val">{{ $contact->mailing_city ?: '—' }}</div></div>
            <div class="cv-field"><label>State</label><div class="val">{{ $contact->mailing_state ?: '—' }}</div></div>
            <div class="cv-field"><label>Postal Code</label><div class="val">{{ $contact->mailing_zip ?: '—' }}</div></div>
          </div>
        </div>
      </div>
      {{-- Description --}}
      @if($contact->description || $contact->notes)
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-sticky-note"></i> Description & Notes</div>
        <div class="cv-card-body">
          @if($contact->description)<div class="cv-field" style="margin-bottom:.8rem"><label>Description</label><div class="val">{{ $contact->description }}</div></div>@endif
          @if($contact->notes)<div class="cv-field"><label>Notes</label><div class="val">{{ $contact->notes }}</div></div>@endif
        </div>
      </div>
      @endif
    </div>

    {{-- Right Sidebar --}}
    <div>
      {{-- Quick Info --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-info-circle"></i> Quick Info</div>
        <div class="cv-card-body">
          <div class="cv-field" style="margin-bottom:.6rem"><label>Created</label><div class="val">{{ $contact->created_at->format('d M Y') }}</div></div>
          <div class="cv-field"><label>Last Updated</label><div class="val">{{ $contact->updated_at->format('d M Y') }}</div></div>
        </div>
      </div>
      {{-- Related Deals --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-handshake"></i> Deals ({{ $deals->count() }})</div>
        <div class="cv-card-body">
          @forelse($deals as $deal)
          <div class="cv-related-item">
            <a href="{{ route('admin.crm2.sales.deals.show', $deal->id) }}">{{ $deal->name ?? $deal->title ?? 'Deal #'.$deal->id }}</a>
            <span style="font-size:.75rem;color:var(--text-muted)">{{ ucfirst(str_replace('_',' ',$deal->stage??'')) }}</span>
          </div>
          @empty
          <p style="font-size:.82rem;color:var(--text-muted);text-align:center">No deals yet</p>
          @endforelse
          <a href="{{ route('admin.crm2.sales.deals.create') }}?contact_id={{ $contact->id }}" class="cv-btn cv-btn-ghost" style="width:100%;justify-content:center;margin-top:.5rem"><i class="fas fa-plus"></i> New Deal</a>
        </div>
      </div>
      {{-- Related Leads --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-user-tag"></i> Leads ({{ $leads->count() }})</div>
        <div class="cv-card-body">
          @forelse($leads as $lead)
          <div class="cv-related-item">
            <a href="{{ route('admin.crm2.sales.leads.show', $lead->id) }}">{{ $lead->first_name }} {{ $lead->last_name }}</a>
            <span style="font-size:.75rem;color:var(--text-muted)">{{ ucfirst($lead->lead_status??'') }}</span>
          </div>
          @empty
          <p style="font-size:.82rem;color:var(--text-muted);text-align:center">No leads</p>
          @endforelse
        </div>
      </div>
      {{-- Activities --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-tasks"></i> Activities</div>
        <div class="cv-card-body">
          <form method="POST" action="{{ route('admin.newcrm.activities.store') }}" class="cv-activity-form" style="margin-bottom:.8rem">
            @csrf
            <input type="hidden" name="related_type" value="contact">
            <input type="hidden" name="related_id" value="{{ $contact->id }}">
            <select name="type" required>
              <option value="">Type...</option>
              <option value="task">Task</option>
              <option value="meeting">Meeting</option>
              <option value="call">Call</option>
            </select>
            <input type="text" name="subject" placeholder="Subject" required>
            <input type="datetime-local" name="due_at">
            <button type="submit" class="cv-btn cv-btn-primary" style="justify-content:center"><i class="fas fa-plus"></i> Log Activity</button>
          </form>
          <div class="cv-activity-list">
            @forelse($activities as $act)
            <div class="cv-activity-item">
              <div class="cv-activity-icon {{ $act->type }}"><i class="fas fa-{{ $act->type=='task'?'check':''.($act->type=='meeting'?'calendar-alt':'phone') }}"></i></div>
              <div>
                <div style="font-size:.82rem;font-weight:600;color:var(--text-primary)">{{ $act->subject }}</div>
                <div style="font-size:.75rem;color:var(--text-muted)">{{ $act->due_at ? \Carbon\Carbon::parse($act->due_at)->format('d M Y H:i') : '' }}</div>
              </div>
            </div>
            @empty
            <p style="font-size:.82rem;color:var(--text-muted);text-align:center">No activities</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function toggleActMenu(e) {
  e.stopPropagation();
  document.getElementById('lvActDrop').classList.toggle('open');
}
document.addEventListener('click', function() {
  const d = document.getElementById('lvActDrop');
  if (d) d.classList.remove('open');
});
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    const d = document.getElementById('lvActDrop');
    if (d) d.classList.remove('open');
  }
});
</script>
@endsection
