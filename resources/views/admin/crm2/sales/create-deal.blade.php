@extends('layouts.admin')
@section('title', 'New Deal')
@section('page-title', 'New Deal')
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
.cf-wrap{max-width:900px;margin:0 auto;padding:1.5rem}
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
</style>
<div class="cf-wrap">
  {{-- Sticky Top Action Bar --}}
  <div class="xn-sticky-bar">
    <div class="xn-sticky-title">
      <i class="fas fa-handshake"></i>
      Create New Deal
    </div>
    <div class="xn-sticky-actions">
      <a href="{{ route('admin.crm2.sales.deals') }}" class="xn-sticky-btn xn-sticky-btn-ghost">
        <i class="fas fa-arrow-left"></i> Cancel
      </a>
      <button type="submit" form="dealCreateForm" class="xn-sticky-btn xn-sticky-btn-primary">
        <i class="fas fa-save"></i> Save Deal
      </button>
    </div>
  </div>
  <div class="xn-sticky-spacer"></div>

  <div class="cf-header">
    <div>
      <div class="cf-breadcrumb"><a href="{{ route('admin.crm2.sales.deals') }}">Deals</a> / New Deal</div>
      <h1><i class="fas fa-handshake"></i> New Deal</h1>
    </div>
  </div>
  @if($errors->any())<div class="crm2-alert error" style="margin-bottom:1rem"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>@endif
  <form id="dealCreateForm" method="POST" action="{{ route('admin.crm2.sales.deals.store') }}">
    @csrf
    {{-- Deal Information --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-handshake"></i> Deal Information</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body">
        <div class="cf-field">
          <label>Deal Owner</label>
          <select name="owner_id">
            <option value="">-- Select Owner --</option>
            @foreach($staff as $s)<option value="{{ $s->id }}" {{ old('owner_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Deal Name <span style="color:red">*</span></label>
          <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Acme Corp - Enterprise License">
        </div>
        <div class="cf-field">
          <label>Account Name</label>
          <select name="account_id">
            <option value="">-- None --</option>
            @foreach($accounts_list as $acc)<option value="{{ $acc->id }}" {{ (old('account_id',$prefill_account_id??''))==$acc->id?'selected':'' }}>{{ $acc->name }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Type</label>
          <select name="type">
            <option value="">-- None --</option>
            @foreach(['Existing Business','New Business'] as $t)<option value="{{ $t }}" {{ old('type')==$t?'selected':'' }}>{{ $t }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Next Step</label>
          <input type="text" name="next_step" value="{{ old('next_step') }}" placeholder="e.g. Send proposal">
        </div>
        <div class="cf-field">
          <label>Lead Source</label>
          <select name="lead_source">
            <option value="">-- None --</option>
            @foreach(['Cold Call','Existing Customer','Self Generated','Employee','Partner','Public Relations','Direct Mail','Conference','Trade Show','Web Site','Word of Mouth','Other'] as $src)
            <option value="{{ $src }}" {{ old('lead_source')==$src?'selected':'' }}>{{ $src }}</option>
            @endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Contact Name</label>
          <select name="contact_id">
            <option value="">-- None --</option>
            @foreach($contacts_list as $c)<option value="{{ $c->id }}" {{ (old('contact_id',$prefill_contact_id??''))==$c->id?'selected':'' }}>{{ $c->first_name }} {{ $c->last_name }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Amount</label>
          <input type="number" name="amount" value="{{ old('amount') }}" placeholder="0.00" step="0.01">
        </div>
        <div class="cf-field">
          <label>Closing Date</label>
          <input type="date" name="closing_date" value="{{ old('closing_date') }}">
        </div>
        <div class="cf-field">
          <label>Stage</label>
          <select name="stage">
            <option value="">-- None --</option>
            @foreach(['prospecting','qualification','proposal','negotiation','closed_won','closed_lost'] as $st)
            <option value="{{ $st }}" {{ old('stage')==$st?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$st)) }}</option>
            @endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Qualification</label>
          <select name="qualification">
            <option value="">-- None --</option>
            @foreach(['Hot','Warm','Cold'] as $q)<option value="{{ $q }}" {{ old('qualification')==$q?'selected':'' }}>{{ $q }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Probability (%)</label>
          <input type="number" name="probability" value="{{ old('probability') }}" placeholder="0-100" min="0" max="100">
        </div>
        <div class="cf-field">
          <label>Expected Revenue</label>
          <input type="number" name="expected_revenue" value="{{ old('expected_revenue') }}" placeholder="0.00" step="0.01">
        </div>
        <div class="cf-field">
          <label>Campaign Source</label>
          <input type="text" name="campaign_source" value="{{ old('campaign_source') }}" placeholder="Campaign name">
        </div>
      </div>
    </div>
    {{-- Description --}}
    <div class="cf-section">
      <div class="cf-section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'grid':'none'">
        <span class="cf-section-title"><i class="fas fa-sticky-note"></i> Description</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="cf-section-body" style="grid-template-columns:1fr">
        <div class="cf-field"><label>Description</label><textarea name="description" placeholder="Deal description...">{{ old('description') }}</textarea></div>
        <div class="cf-field"><label>Notes</label><textarea name="notes" placeholder="Internal notes...">{{ old('notes') }}</textarea></div>
      </div>
    </div>
    
  </form>
</div>
@endsection
