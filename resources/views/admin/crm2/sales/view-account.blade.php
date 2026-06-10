@extends('layouts.admin')
@section('title', $account->name)
@section('page-title', 'Account Detail')
@section('content')
<style>
.cv-wrap{max-width:1100px;margin:0 auto;padding:1.5rem}
.cv-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;gap:1rem;flex-wrap:wrap}
.cv-identity{display:flex;align-items:center;gap:1rem}
.cv-avatar{width:72px;height:72px;border-radius:12px;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:700;color:#fff;overflow:hidden;flex-shrink:0}
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
.cv-summary-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:.8rem;margin-bottom:.8rem}
.cv-summary-card{background:var(--bg-primary);border:1px solid var(--border);border-radius:8px;padding:.8rem;text-align:center}
.cv-summary-card .num{font-size:1.4rem;font-weight:700;color:var(--accent)}
.cv-summary-card .lbl{font-size:.72rem;color:var(--text-muted);margin-top:.2rem}
.cv-related-item{display:flex;align-items:center;justify-content:space-between;padding:.5rem;background:var(--bg-primary);border-radius:6px;border:1px solid var(--border);margin-bottom:.4rem;font-size:.82rem}
.cv-related-item a{color:var(--accent);text-decoration:none;font-weight:600}
</style>
<div class="cv-wrap">
  <div class="cv-header">
    <div class="cv-identity">
      <div class="cv-avatar">
        @if($account->account_image)
          <img src="{{ asset('storage/'.$account->account_image) }}" alt="logo">
        @else
          {{ strtoupper(substr($account->name??'A',0,1)) }}
        @endif
      </div>
      <div>
        <div class="cv-name">{{ $account->name }}</div>
        <div class="cv-sub">{{ $account->industry ?? '' }}{{ $account->industry && $account->account_type ? ' · ' : '' }}{{ $account->account_type ?? '' }}</div>
        <div class="cv-badges">
          @if($account->rating)<span class="cv-badge">{{ $account->rating }}</span>@endif
          @if($account->website)<a href="{{ $account->website }}" target="_blank" class="cv-badge" style="color:var(--accent)"><i class="fas fa-globe"></i> Website</a>@endif
        </div>
      </div>
    </div>
    <div class="cv-actions">
      <a href="{{ route('admin.crm2.sales.accounts.edit', $account->id) }}" class="cv-btn cv-btn-primary"><i class="fas fa-edit"></i> Edit</a>
      <a href="{{ route('admin.crm2.sales.accounts') }}" class="cv-btn cv-btn-ghost"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
  </div>

  {{-- CRM Summary --}}
  <div class="cv-summary-grid">
    <div class="cv-summary-card"><div class="num">{{ $contacts->count() }}</div><div class="lbl">Contacts</div></div>
    <div class="cv-summary-card"><div class="num">{{ $deals->count() }}</div><div class="lbl">Deals</div></div>
    <div class="cv-summary-card"><div class="num">{{ $leads->count() }}</div><div class="lbl">Leads</div></div>
  </div>

  <div class="cv-layout">
    <div>
      {{-- Account Profile --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-building"></i> Account Profile</div>
        <div class="cv-card-body">
          <div class="cv-grid">
            <div class="cv-field"><label>Account Owner</label><div class="val">{{ $account->owner?->name ?: '—' }}</div></div>
            <div class="cv-field"><label>Account Name</label><div class="val">{{ $account->name }}</div></div>
            <div class="cv-field"><label>Account Number</label><div class="val">{{ $account->account_number ?: '—' }}</div></div>
            <div class="cv-field"><label>Account Type</label><div class="val">{{ $account->account_type ?: '—' }}</div></div>
            <div class="cv-field"><label>Rating</label><div class="val">{{ $account->rating ?: '—' }}</div></div>
          </div>
        </div>
      </div>
      {{-- Company Information --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-industry"></i> Company Information</div>
        <div class="cv-card-body">
          <div class="cv-grid">
            <div class="cv-field"><label>Industry</label><div class="val">{{ $account->industry ?: '—' }}</div></div>
            <div class="cv-field"><label>Ownership</label><div class="val">{{ $account->ownership ?: '—' }}</div></div>
            <div class="cv-field"><label>Employees</label><div class="val">{{ $account->employees ? number_format($account->employees) : '<span style="color:var(--text-muted);font-style:italic;">—</span>' }}</div></div>
            <div class="cv-field"><label>Annual Revenue</label><div class="val">{{ $account->annual_revenue ? '$'.number_format($account->annual_revenue) : '<span style="color:var(--text-muted);font-style:italic;">—</span>' }}</div></div>
            <div class="cv-field"><label>SIC Code</label><div class="val">{{ $account->sic_code ?: '—' }}</div></div>
            <div class="cv-field"><label>Ticker Symbol</label><div class="val">{{ $account->ticker_symbol ?: '—' }}</div></div>
          </div>
        </div>
      </div>
      {{-- Contact Info --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-phone"></i> Contact Information</div>
        <div class="cv-card-body">
          <div class="cv-grid">
            <div class="cv-field"><label>Phone</label><div class="val">{{ $account->phone ?: '—' }}</div></div>
            <div class="cv-field"><label>Fax</label><div class="val">{{ $account->fax ?: '—' }}</div></div>
            <div class="cv-field"><label>Website</label><div class="val">@if($account->website)<a href="{{ $account->website }}" target="_blank" style="color:var(--accent)">{{ $account->website }}</a>@else<span style="color:var(--text-muted);font-style:italic;">—</span>@endif</div></div>
            <div class="cv-field"><label>Email</label><div class="val">{{ $account->email ?: '—' }}</div></div>
          </div>
        </div>
      </div>
      {{-- Billing Address --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-file-invoice"></i> Billing Address</div>
        <div class="cv-card-body">
          <div class="cv-grid">
            <div class="cv-field"><label>Country</label><div class="val">{{ $account->billing_country ?: '—' }}</div></div>
            <div class="cv-field"><label>Building</label><div class="val">{{ $account->billing_building ?: '—' }}</div></div>
            <div class="cv-field"><label>Street</label><div class="val">{{ $account->billing_street ?: '—' }}</div></div>
            <div class="cv-field"><label>City</label><div class="val">{{ $account->billing_city ?: '—' }}</div></div>
            <div class="cv-field"><label>State</label><div class="val">{{ $account->billing_state ?: '—' }}</div></div>
            <div class="cv-field"><label>Postal Code</label><div class="val">{{ $account->billing_zip ?: '—' }}</div></div>
          </div>
        </div>
      </div>
    </div>

    {{-- Right Sidebar --}}
    <div>
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-info-circle"></i> Quick Info</div>
        <div class="cv-card-body">
          <div class="cv-field" style="margin-bottom:.6rem"><label>Created</label><div class="val">{{ $account->created_at->format('d M Y') }}</div></div>
          <div class="cv-field"><label>Last Updated</label><div class="val">{{ $account->updated_at->format('d M Y') }}</div></div>
        </div>
      </div>
      {{-- Contacts --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-users"></i> Contacts ({{ $contacts->count() }})</div>
        <div class="cv-card-body">
          @forelse($contacts->take(5) as $c)
          <div class="cv-related-item">
            <a href="{{ route('admin.crm2.sales.contacts.show', $c->id) }}">{{ $c->first_name }} {{ $c->last_name }}</a>
            <span style="font-size:.75rem;color:var(--text-muted)">{{ $c->job_title ?? '' }}</span>
          </div>
          @empty
          <p style="font-size:.82rem;color:var(--text-muted);text-align:center">No contacts</p>
          @endforelse
          <a href="{{ route('admin.crm2.sales.contacts.create') }}?account_id={{ $account->id }}" class="cv-btn cv-btn-ghost" style="width:100%;justify-content:center;margin-top:.5rem"><i class="fas fa-plus"></i> New Contact</a>
        </div>
      </div>
      {{-- Deals --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-handshake"></i> Deals ({{ $deals->count() }})</div>
        <div class="cv-card-body">
          @forelse($deals->take(5) as $deal)
          <div class="cv-related-item">
            <a href="{{ route('admin.crm2.sales.deals.show', $deal->id) }}">{{ $deal->name ?? $deal->title ?? 'Deal #'.$deal->id }}</a>
            <span style="font-size:.75rem;color:var(--text-muted)">{{ ucfirst(str_replace('_',' ',$deal->stage??'')) }}</span>
          </div>
          @empty
          <p style="font-size:.82rem;color:var(--text-muted);text-align:center">No deals</p>
          @endforelse
          <a href="{{ route('admin.crm2.sales.deals.create') }}?account_id={{ $account->id }}" class="cv-btn cv-btn-ghost" style="width:100%;justify-content:center;margin-top:.5rem"><i class="fas fa-plus"></i> New Deal</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
