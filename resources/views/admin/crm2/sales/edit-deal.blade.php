@extends('layouts.admin')
@section('title', 'Edit Deal')
@section('page-title', 'Edit Deal')
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
  <div class="cf-header">
    <div>
      <div class="cf-breadcrumb"><a href="{{ route('admin.crm2.sales.deals') }}">Deals</a> / Edit Deal</div>
      <h1><i class="fas fa-handshake"></i> Edit Deal</h1>
    </div>
    <a href="{{ route('admin.crm2.sales.deals') }}" class="cf-btn cf-btn-ghost"><i class="fas fa-arrow-left"></i> Back</a>
  </div>
  @if($errors->any())<div class="crm2-alert error" style="margin-bottom:1rem"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>@endif
  @if(session('success'))<div class="crm2-alert success" style="margin-bottom:1rem"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
  <form method="POST" action="{{ route('admin.crm2.sales.update', ['type'=>'deal','id'=>$item->id]) }}">
    @csrf @method('PATCH')
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
            @foreach($staff as $s)<option value="{{ $s->id }}" {{ old('owner_id',$item->owner_id)==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Deal Name <span style="color:red">*</span></label>
          <input type="text" name="name" value="{{ old('name',$item->name) }}" required placeholder="e.g. Acme Corp - Enterprise License">
        </div>
        <div class="cf-field">
          <label>Account Name</label>
          <select name="account_id">
            <option value="">-- None --</option>
            @foreach($accounts_list as $acc)<option value="{{ $acc->id }}" {{ old('account_id',$item->account_id)==$acc->id?'selected':'' }}>{{ $acc->name }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Type</label>
          <select name="type">
            <option value="">-- None --</option>
            @foreach(['Existing Business','New Business'] as $t)<option value="{{ $t }}" {{ old('type',$item->type)==$t?'selected':'' }}>{{ $t }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Next Step</label>
          <input type="text" name="next_step" value="{{ old('next_step',$item->next_step) }}" placeholder="e.g. Send proposal">
        </div>
        <div class="cf-field">
          <label>Lead Source</label>
          <select name="lead_source">
            <option value="">-- None --</option>
            @foreach(['Cold Call','Existing Customer','Self Generated','Employee','Partner','Public Relations','Direct Mail','Conference','Trade Show','Web Site','Word of Mouth','Other'] as $src)
            <option value="{{ $src }}" {{ old('lead_source',$item->lead_source)==$src?'selected':'' }}>{{ $src }}</option>
            @endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Contact Name</label>
          <select name="contact_id">
            <option value="">-- None --</option>
            @foreach($contacts_list as $c)<option value="{{ $c->id }}" {{ old('contact_id',$item->contact_id)==$c->id?'selected':'' }}>{{ $c->first_name }} {{ $c->last_name }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Amount</label>
          <input type="number" name="amount" value="{{ old('amount',$item->amount) }}" placeholder="0.00" step="0.01">
        </div>
        <div class="cf-field">
          <label>Closing Date</label>
          <input type="date" name="closing_date" value="{{ old('closing_date',$item->closing_date ? $item->closing_date->format('Y-m-d') : '') }}">
        </div>
        <div class="cf-field">
          <label>Stage</label>
          <select name="stage">
            <option value="">-- None --</option>
            @foreach(['prospecting','qualification','proposal','negotiation','closed_won','closed_lost'] as $st)
            <option value="{{ $st }}" {{ old('stage',$item->stage)==$st?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$st)) }}</option>
            @endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Qualification</label>
          <select name="qualification">
            <option value="">-- None --</option>
            @foreach(['Hot','Warm','Cold'] as $q)<option value="{{ $q }}" {{ old('qualification',$item->qualification)==$q?'selected':'' }}>{{ $q }}</option>@endforeach
          </select>
        </div>
        <div class="cf-field">
          <label>Probability (%)</label>
          <input type="number" name="probability" value="{{ old('probability',$item->probability) }}" placeholder="0-100" min="0" max="100">
        </div>
        <div class="cf-field">
          <label>Expected Revenue</label>
          <input type="number" name="expected_revenue" value="{{ old('expected_revenue',$item->expected_revenue) }}" placeholder="0.00" step="0.01">
        </div>
        <div class="cf-field">
          <label>Campaign Source</label>
          <input type="text" name="campaign_source" value="{{ old('campaign_source',$item->campaign_source) }}" placeholder="Campaign name">
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
        <div class="cf-field"><label>Description</label><textarea name="description" placeholder="Deal description...">{{ old('description',$item->description) }}</textarea></div>
        <div class="cf-field"><label>Notes</label><textarea name="notes" placeholder="Internal notes...">{{ old('notes',$item->notes) }}</textarea></div>
      </div>
    </div>
    <div class="cf-actions">
      <a href="{{ route('admin.crm2.sales.deals') }}" class="cf-btn cf-btn-ghost">Cancel</a>
      <button type="submit" class="cf-btn cf-btn-primary"><i class="fas fa-save"></i> Update Deal</button>
    </div>
  </form>
</div>
@endsection
