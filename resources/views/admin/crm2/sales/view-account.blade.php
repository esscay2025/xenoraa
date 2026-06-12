@extends('layouts.admin')
@section('title', $account->name)
@section('page-title', 'Account Detail')
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
/* ── Layout ── */
.av-layout{display:flex;gap:0;min-height:calc(100vh - 120px);max-width:1400px;margin:0 auto;padding:1.5rem 1rem}
.av-main{flex:1;min-width:0;padding-right:1.5rem}
.av-nav{width:220px;flex-shrink:0;position:sticky;top:80px;height:fit-content;max-height:calc(100vh - 100px);overflow-y:auto}

/* ── Header ── */
.av-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;gap:1rem;flex-wrap:wrap;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:1.25rem 1.5rem}
.av-identity{display:flex;align-items:center;gap:1rem}
.av-avatar{width:64px;height:64px;border-radius:10px;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:700;color:#fff;flex-shrink:0}
.av-name{font-size:1.3rem;font-weight:700;color:var(--text-primary)}
.av-meta{display:flex;gap:.5rem;flex-wrap:wrap;margin-top:.3rem}
.av-badge{display:inline-flex;align-items:center;gap:.3rem;padding:.2rem .65rem;border-radius:20px;font-size:.72rem;font-weight:600;background:var(--accent);color:#fff}
.av-badge.green{background:#16a34a}
.av-badge.blue{background:#2563eb}
.av-badge.orange{background:#ea580c}
.av-badge.gray{background:#6b7280;color:#fff}
.av-actions{display:flex;gap:.5rem;flex-wrap:wrap;align-items:center}
.av-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.45rem 1rem;border-radius:8px;font-size:.82rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .15s}
.av-btn.primary{background:var(--accent);color:#fff}
.av-btn.primary:hover{opacity:.9}
.av-btn.outline{background:transparent;border:1.5px solid var(--border);color:var(--text-primary)}
.av-btn.outline:hover{background:var(--bg-card);border-color:var(--accent);color:var(--accent)}
.av-btn.danger{background:#ef4444;color:#fff}
.av-btn.success{background:#16a34a;color:#fff}
.av-btn.sm{padding:.3rem .7rem;font-size:.75rem}

/* ── Section Navigator ── */
.av-nav-card{background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:.75rem 0;overflow:hidden}
.av-nav-title{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);padding:.25rem 1rem .5rem}
.av-nav-item{display:flex;align-items:center;gap:.6rem;padding:.5rem 1rem;font-size:.82rem;color:var(--text-secondary);cursor:pointer;transition:all .15s;text-decoration:none;border-left:3px solid transparent}
.av-nav-item:hover,.av-nav-item.active{color:var(--accent);background:rgba(var(--accent-rgb,99,102,241),.07);border-left-color:var(--accent)}
.av-nav-item svg{flex-shrink:0;opacity:.7}
.av-nav-item.active svg{opacity:1}

/* ── Sections ── */
.av-section{background:var(--bg-card);border:1px solid var(--border);border-radius:12px;margin-bottom:1.25rem;overflow:hidden}
.av-section-header{display:flex;align-items:center;justify-content:space-between;padding:.9rem 1.25rem;border-bottom:1px solid var(--border);background:var(--bg-primary)}
.av-section-title{display:flex;align-items:center;gap:.6rem;font-size:.9rem;font-weight:700;color:var(--text-primary)}
.av-section-title svg{color:var(--accent)}
.av-section-actions{display:flex;gap:.4rem;flex-wrap:wrap}
.av-section-body{padding:1.25rem}

/* ── Info Grid ── */
.av-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:.75rem 1.5rem}
.av-field label{font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted);display:block;margin-bottom:.2rem}
.av-field span{font-size:.88rem;color:var(--text-primary);display:block;word-break:break-word}
.av-field span.empty{color:var(--text-muted);font-style:italic}

/* ── Notes ── */
.av-note-form{display:flex;gap:.75rem;margin-bottom:1rem}
.av-note-form textarea{flex:1;border:1.5px solid var(--border);border-radius:8px;padding:.6rem .85rem;font-size:.85rem;background:var(--bg-primary);color:var(--text-primary);resize:vertical;min-height:70px;font-family:inherit}
.av-note-form textarea:focus{outline:none;border-color:var(--accent)}
.av-note-list{display:flex;flex-direction:column;gap:.6rem}
.av-note-item{background:var(--bg-primary);border:1px solid var(--border);border-radius:8px;padding:.75rem 1rem}
.av-note-item .note-text{font-size:.85rem;color:var(--text-primary);margin-bottom:.3rem}
.av-note-item .note-meta{font-size:.72rem;color:var(--text-muted)}

/* ── Table ── */
.av-table{width:100%;border-collapse:collapse;font-size:.83rem}
.av-table th{text-align:left;padding:.55rem .75rem;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted);border-bottom:1.5px solid var(--border);background:var(--bg-primary)}
.av-table td{padding:.6rem .75rem;border-bottom:1px solid var(--border);color:var(--text-primary);vertical-align:middle}
.av-table tr:last-child td{border-bottom:none}
.av-table tr:hover td{background:var(--bg-primary)}
.av-table .td-actions{display:flex;gap:.35rem}
.av-empty{text-align:center;padding:2rem;color:var(--text-muted);font-size:.85rem}
.av-empty svg{display:block;margin:0 auto .5rem;opacity:.3}

/* ── Activity Tabs ── */
.av-tabs{display:flex;gap:0;border-bottom:1.5px solid var(--border);margin-bottom:1rem}
.av-tab{padding:.5rem 1rem;font-size:.82rem;font-weight:600;cursor:pointer;color:var(--text-muted);border-bottom:2.5px solid transparent;margin-bottom:-1.5px;transition:all .15s}
.av-tab.active{color:var(--accent);border-bottom-color:var(--accent)}
.av-tab-pane{display:none}
.av-tab-pane.active{display:block}

/* ── Status badges ── */
.st{display:inline-flex;align-items:center;padding:.15rem .55rem;border-radius:20px;font-size:.72rem;font-weight:600}
.st-green{background:#dcfce7;color:#16a34a}
.st-blue{background:#dbeafe;color:#2563eb}
.st-orange{background:#ffedd5;color:#ea580c}
.st-gray{background:#f3f4f6;color:#6b7280}
.st-red{background:#fee2e2;color:#dc2626}
.st-purple{background:#ede9fe;color:#7c3aed}

/* ── Slider Panel ── */
.av-slider-overlay{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1000;opacity:0;pointer-events:none;transition:opacity .25s}
.av-slider-overlay.open{opacity:1;pointer-events:all}
.av-slider{position:fixed;top:0;right:-480px;width:460px;max-width:95vw;height:100vh;background:var(--bg-card);box-shadow:-4px 0 24px rgba(0,0,0,.18);z-index:1001;transition:right .3s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column}
.av-slider.open{right:0}
.av-slider-head{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;border-bottom:1px solid var(--border);flex-shrink:0}
.av-slider-head h3{font-size:1rem;font-weight:700;color:var(--text-primary);margin:0}
.av-slider-close{background:none;border:none;cursor:pointer;color:var(--text-muted);padding:.25rem;border-radius:6px;display:flex;align-items:center}
.av-slider-close:hover{background:var(--bg-primary);color:var(--text-primary)}
.av-slider-search{padding:.75rem 1.25rem;border-bottom:1px solid var(--border);flex-shrink:0}
.av-slider-search input{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:.5rem .85rem;font-size:.85rem;background:var(--bg-primary);color:var(--text-primary);box-sizing:border-box}
.av-slider-search input:focus{outline:none;border-color:var(--accent)}
.av-slider-list{flex:1;overflow-y:auto;padding:.5rem 0}
.av-slider-item{display:flex;align-items:center;justify-content:space-between;padding:.65rem 1.25rem;cursor:pointer;transition:background .12s;border-bottom:1px solid var(--border)}
.av-slider-item:hover{background:var(--bg-primary)}
.av-slider-item .si-name{font-size:.85rem;font-weight:600;color:var(--text-primary)}
.av-slider-item .si-sub{font-size:.75rem;color:var(--text-muted)}
.av-slider-item .si-check{width:18px;height:18px;border-radius:4px;border:2px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.av-slider-item.selected .si-check{background:var(--accent);border-color:var(--accent);color:#fff}
.av-slider-foot{padding:.75rem 1.25rem;border-top:1px solid var(--border);flex-shrink:0;display:flex;gap:.5rem}

/* ── Activity Popup ── */
.av-popup-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1002;display:none;align-items:center;justify-content:center}
.av-popup-overlay.open{display:flex}
.av-popup{background:var(--bg-card);border-radius:14px;width:520px;max-width:95vw;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.25)}
.av-popup-head{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;border-bottom:1px solid var(--border);position:sticky;top:0;background:var(--bg-card);z-index:1}
.av-popup-head h3{font-size:1rem;font-weight:700;margin:0;color:var(--text-primary)}
.av-popup-body{padding:1.25rem}
.av-form-row{display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:.75rem}
.av-form-row.full{grid-template-columns:1fr}
.av-form-group label{font-size:.75rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.25rem;text-transform:uppercase;letter-spacing:.04em}
.av-form-group input,.av-form-group select,.av-form-group textarea{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:.5rem .75rem;font-size:.85rem;background:var(--bg-primary);color:var(--text-primary);box-sizing:border-box;font-family:inherit}
.av-form-group input:focus,.av-form-group select:focus,.av-form-group textarea:focus{outline:none;border-color:var(--accent)}
.av-form-group textarea{resize:vertical;min-height:80px}
.av-popup-foot{padding:.75rem 1.25rem;border-top:1px solid var(--border);display:flex;gap:.5rem;justify-content:flex-end;position:sticky;bottom:0;background:var(--bg-card)}

/* ── Product Add Slider ── */
.av-prod-item{display:flex;align-items:center;gap:.75rem;padding:.65rem 1.25rem;border-bottom:1px solid var(--border);cursor:pointer;transition:background .12s}
.av-prod-item:hover{background:var(--bg-primary)}
.av-prod-img{width:40px;height:40px;border-radius:6px;object-fit:cover;background:var(--bg-primary);border:1px solid var(--border);flex-shrink:0}
.av-prod-info{flex:1;min-width:0}
.av-prod-name{font-size:.85rem;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.av-prod-price{font-size:.75rem;color:var(--text-muted)}
.av-prod-check{width:20px;height:20px;border-radius:50%;border:2px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.av-prod-item.selected .av-prod-check{background:var(--accent);border-color:var(--accent);color:#fff}

/* ── Dropdown button ── */
.av-dropdown{position:relative;display:inline-flex}
.av-dropdown-menu{position:absolute;top:calc(100% + 4px);left:0;background:var(--bg-card);border:1px solid var(--border);border-radius:8px;box-shadow:0 8px 24px rgba(0,0,0,.15);min-width:160px;z-index:100;display:none;overflow:hidden}
.av-dropdown.open .av-dropdown-menu{display:block}
.av-dropdown-item{display:flex;align-items:center;gap:.5rem;padding:.55rem 1rem;font-size:.83rem;color:var(--text-primary);cursor:pointer;transition:background .12s}
.av-dropdown-item:hover{background:var(--bg-primary);color:var(--accent)}

@media(max-width:900px){
  .av-layout{flex-direction:column-reverse;padding:1rem .5rem}
  .av-nav{width:100%;position:static;height:auto;max-height:none}
  .av-main{padding-right:0}
  .av-nav-card{display:flex;overflow-x:auto;padding:.5rem}
  .av-nav-title{display:none}
  .av-nav-item{white-space:nowrap;border-left:none;border-bottom:3px solid transparent;padding:.4rem .75rem}
  .av-nav-item.active{border-bottom-color:var(--accent);border-left:none}
}
</style>

<div class="av-layout">

  {{-- ── MAIN CONTENT ── --}}
  <div class="av-main">

    {{-- Header --}}
    <div class="av-header">
      <div class="av-identity">
        <div class="av-avatar">{{ strtoupper(substr($account->name,0,1)) }}</div>
        <div>
          <div class="av-name">{{ $account->name }}</div>
          <div class="av-meta">
            @if($account->type)<span class="av-badge blue">{{ $account->type }}</span>@endif
            @if($account->industry)<span class="av-badge gray">{{ $account->industry }}</span>@endif
            @if($account->rating)<span class="av-badge orange">{{ $account->rating }}</span>@endif
          </div>
        </div>
      </div>
      <div class="av-actions">
        <a href="{{ route('admin.crm2.sales.accounts.edit', $account->id) }}" class="av-btn outline">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Edit
        </a>
        <a href="{{ route('admin.crm2.sales.accounts') }}" class="av-btn outline"><i class="fas fa-arrow-left"></i> Back</a>
        {{-- 3-dot action menu --}}
        <div class="xn-bulk-wrap">
          <button class="xn-bulk-btn" id="lvActBtn" onclick="toggleActMenu(event)" title="More actions">&#8942;</button>
          <div class="xn-bulk-drop" id="lvActDrop">
            <form method="POST" action="{{ route('admin.crm2.sales.accounts.clone', $account->id) }}" style="margin:0">
              @csrf
              <button type="submit" class="xn-bulk-item"><i class="fas fa-copy" style="color:#6366f1"></i> Clone Account</button>
            </form>
            <button class="xn-bulk-item" onclick="window.print()"><i class="fas fa-print" style="color:#10b981"></i> Print Preview</button>
            <div style="border-top:1px solid var(--border,#e2e8f0);margin:4px 0"></div>
            <form method="POST" action="{{ route('admin.crm2.sales.destroy', ['type'=>'account','id'=>$account->id]) }}" onsubmit="return confirm('Delete this account permanently?')" style="margin:0">
              @csrf @method('DELETE')
              <button type="submit" class="xn-bulk-item danger"><i class="fas fa-trash"></i> Delete Account</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- ─── SECTION 1: Account Information ─── --}}
    <div class="av-section" id="sec-info">
      <div class="av-section-header">
        <div class="av-section-title">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
          Account Information
        </div>
      </div>
      <div class="av-section-body">
        <div class="av-grid">
          <div class="av-field"><label>Account Name</label><span>{{ $account->name ?: '—' }}</span></div>
          <div class="av-field"><label>Account Number</label><span>{{ $account->account_number ?: '—' }}</span></div>
          <div class="av-field"><label>Account Type</label><span>{{ $account->type ?: '—' }}</span></div>
          <div class="av-field"><label>Industry</label><span>{{ $account->industry ?: '—' }}</span></div>
          <div class="av-field"><label>Rating</label><span>{{ $account->rating ?: '—' }}</span></div>
          <div class="av-field"><label>Ownership</label><span>{{ $account->ownership ?: '—' }}</span></div>
          <div class="av-field"><label>Employees</label><span>{{ $account->employees ?: '—' }}</span></div>
          <div class="av-field"><label>Annual Revenue</label><span>{{ $account->annual_revenue ? number_format($account->annual_revenue,2) : '—' }}</span></div>
          <div class="av-field"><label>SIC Code</label><span>{{ $account->sic_code ?: '—' }}</span></div>
          <div class="av-field"><label>Ticker Symbol</label><span>{{ $account->ticker_symbol ?: '—' }}</span></div>
          <div class="av-field"><label>Parent Account</label><span>{{ $account->parent_account ?: '—' }}</span></div>
          <div class="av-field"><label>Phone</label><span>{{ $account->phone ?: '—' }}</span></div>
          <div class="av-field"><label>Fax</label><span>{{ $account->fax ?: '—' }}</span></div>
          <div class="av-field"><label>Website</label><span>{{ $account->website ? '<a href="'.$account->website.'" target="_blank" style="color:var(--accent)">'.$account->website.'</a>' : '—' }}</span></div>
          <div class="av-field"><label>Email</label><span>{{ $account->email ?: '—' }}</span></div>
          <div class="av-field"><label>Account Owner</label><span>{{ $account->owner ? $account->owner->name : '—' }}</span></div>
        </div>
        @if($account->billing_street || $account->billing_city)
        <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border)">
          <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted);margin-bottom:.75rem">Billing Address</div>
          <div class="av-grid">
            <div class="av-field"><label>Country</label><span>{{ $account->billing_country ?: '—' }}</span></div>
            <div class="av-field"><label>Street</label><span>{{ $account->billing_street ?: '—' }}</span></div>
            <div class="av-field"><label>City</label><span>{{ $account->billing_city ?: '—' }}</span></div>
            <div class="av-field"><label>State</label><span>{{ $account->billing_state ?: '—' }}</span></div>
            <div class="av-field"><label>ZIP</label><span>{{ $account->billing_zip ?: '—' }}</span></div>
          </div>
        </div>
        @endif
        @if($account->description)
        <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border)">
          <div class="av-field"><label>Description</label><span>{{ $account->description }}</span></div>
        </div>
        @endif
      </div>
    </div>

    {{-- ─── SECTION 2: Notes ─── --}}
    <div class="av-section" id="sec-notes">
      <div class="av-section-header">
        <div class="av-section-title">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          Notes
        </div>
      </div>
      <div class="av-section-body">
        <form method="POST" action="{{ route('admin.crm2.accounts.notes.store', $account->id) }}">
          @csrf
          <div class="av-note-form">
            <textarea name="content" placeholder="Add a note about this account..." required></textarea>
            <button type="submit" class="av-btn primary" style="align-self:flex-end;white-space:nowrap">Add Note</button>
          </div>
        </form>
        <div class="av-note-list">
          @forelse($notes as $note)
          <div class="av-note-item">
            <div class="note-text">{{ $note->content }}</div>
            <div class="note-meta">
              {{ $note->user ? $note->user->name : 'Unknown' }} &middot; {{ $note->created_at->diffForHumans() }}
            </div>
          </div>
          @empty
          <div class="av-empty">
            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg>
            No notes yet. Add the first note above.
          </div>
          @endforelse
        </div>
      </div>
    </div>

    {{-- ─── SECTION 3: Deals ─── --}}
    <div class="av-section" id="sec-deals">
      <div class="av-section-header">
        <div class="av-section-title">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
          Deals <span style="margin-left:.4rem;background:var(--accent);color:#fff;border-radius:20px;padding:.1rem .5rem;font-size:.72rem">{{ $deals->count() }}</span>
        </div>
        <div class="av-section-actions">
          <button class="av-btn outline sm" onclick="openSlider('slider-deals')">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
            Assign
          </button>
          <a href="{{ route('admin.crm2.sales.deals.create') }}?account_id={{ $account->id }}" class="av-btn primary sm">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            New Deal
          </a>
        </div>
      </div>
      <div class="av-section-body" style="padding:0">
        @if($deals->count())
        <table class="av-table">
          <thead><tr><th>Deal Name</th><th>Stage</th><th>Amount</th><th>Close Date</th><th>Actions</th></tr></thead>
          <tbody>
            @foreach($deals as $deal)
            <tr>
              <td><a href="{{ route('admin.crm2.sales.deals.show', $deal->id) }}" style="color:var(--accent);font-weight:600">{{ $deal->name ?: $deal->title }}</a></td>
              <td><span class="st st-blue">{{ $deal->stage ?: '—' }}</span></td>
              <td>{{ $deal->amount ? number_format($deal->amount,2) : '—' }}</td>
              <td>{{ $deal->closing_date ? \Carbon\Carbon::parse($deal->closing_date)->format('d M Y') : '—' }}</td>
              <td><div class="td-actions">
                <a href="{{ route('admin.crm2.sales.deals.show', $deal->id) }}" class="av-btn outline sm">View</a>
                <a href="{{ route('admin.crm2.sales.deals.edit', $deal->id) }}" class="av-btn outline sm">Edit</a>
              </div></td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        <div class="av-empty">No deals linked to this account yet.</div>
        @endif
      </div>
    </div>

    {{-- ─── SECTION 4: Contacts ─── --}}
    <div class="av-section" id="sec-contacts">
      <div class="av-section-header">
        <div class="av-section-title">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
          Contacts <span style="margin-left:.4rem;background:var(--accent);color:#fff;border-radius:20px;padding:.1rem .5rem;font-size:.72rem">{{ $contacts->count() }}</span>
        </div>
        <div class="av-section-actions">
          <button class="av-btn outline sm" onclick="openSlider('slider-contacts')">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
            Assign
          </button>
          <a href="{{ route('admin.crm2.sales.contacts.create') }}?account_id={{ $account->id }}" class="av-btn primary sm">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            New Contact
          </a>
        </div>
      </div>
      <div class="av-section-body" style="padding:0">
        @if($contacts->count())
        <table class="av-table">
          <thead><tr><th>Name</th><th>Title</th><th>Email</th><th>Phone</th><th>Actions</th></tr></thead>
          <tbody>
            @foreach($contacts as $contact)
            <tr>
              <td><a href="{{ route('admin.crm2.sales.contacts.show', $contact->id) }}" style="color:var(--accent);font-weight:600">{{ $contact->first_name }} {{ $contact->last_name }}</a></td>
              <td>{{ $contact->job_title ?: '—' }}</td>
              <td>{{ $contact->email ?: '—' }}</td>
              <td>{{ $contact->phone ?: $contact->mobile ?: '—' }}</td>
              <td><div class="td-actions">
                <a href="{{ route('admin.crm2.sales.contacts.show', $contact->id) }}" class="av-btn outline sm">View</a>
                <a href="{{ route('admin.crm2.sales.contacts.edit', $contact->id) }}" class="av-btn outline sm">Edit</a>
              </div></td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        <div class="av-empty">No contacts linked to this account yet.</div>
        @endif
      </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         EMAIL SECTION
    ═══════════════════════════════════════════════════════ --}}
    <div class="av-section" id="sec-email">
      <div class="av-section-header">
        <div class="av-section-title">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
          Email
        </div>
        <div class="av-section-actions">
          <button class="av-btn primary sm" onclick="openComposeSlider()">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Compose Mail
          </button>
        </div>
      </div>
      <div class="av-section-body" style="padding:0">
        {{-- Email Tabs --}}
        <div class="av-email-tabs">
          <button class="av-email-tab active" onclick="switchEmailTab('mail', this)">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
            Mail
            <span class="av-email-count" id="email-count-mail">{{ $sentEmails->count() }}</span>
          </button>
          <button class="av-email-tab" onclick="switchEmailTab('draft', this)">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Draft
            <span class="av-email-count" id="email-count-draft">{{ $draftEmails->count() }}</span>
          </button>
          <button class="av-email-tab" onclick="switchEmailTab('scheduled', this)">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Scheduled
            <span class="av-email-count" id="email-count-scheduled">{{ $scheduledEmails->count() }}</span>
          </button>
        </div>

        {{-- Scheduled Source Dropdown (only visible on Scheduled tab) --}}
        <div class="av-email-source-bar" id="email-source-bar" style="display:none">
          <label class="av-email-source-label">Source:</label>
          <div class="av-dropdown" id="dd-email-source">
            <button class="av-dropdown-btn" onclick="toggleDropdown('dd-email-source')">
              <span id="email-source-label-text">Accounts - Sent Email from CRM</span>
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="av-dropdown-menu">
              <button class="av-dropdown-item active" onclick="setEmailSource('crm', this)">Accounts - Sent Email from CRM</button>
              <button class="av-dropdown-item" onclick="setEmailSource('contact', this)">Emails Associated with the related contact</button>
            </div>
          </div>
        </div>

        {{-- Mail Tab Pane --}}
        <div class="av-email-pane active" id="email-pane-mail">
          @if($sentEmails->count())
          <div class="av-email-list">
            @foreach($sentEmails as $em)
            <div class="av-email-item">
              <div class="av-email-avatar">{{ strtoupper(substr($em->to_email, 0, 1)) }}</div>
              <div class="av-email-content">
                <div class="av-email-header">
                  <span class="av-email-to">To: {{ $em->to_email }}</span>
                  <span class="av-email-date">{{ $em->sent_at ? $em->sent_at->format('d M Y, h:i A') : $em->created_at->format('d M Y, h:i A') }}</span>
                </div>
                <div class="av-email-subject">{{ $em->subject }}</div>
                <div class="av-email-preview">{{ strip_tags($em->body_html) }}</div>
                @if($em->error_message)
                <div class="av-email-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> {{ $em->error_message }}</div>
                @endif
              </div>
              <div class="av-email-actions">
                <button class="av-icon-btn" title="View" onclick="viewEmail({{ $em->id }})">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
                <button class="av-icon-btn danger" title="Delete" onclick="deleteEmail({{ $em->id }}, this)">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                </button>
              </div>
            </div>
            @endforeach
          </div>
          @else
          <div class="av-empty">
            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="opacity:0.3;margin-bottom:8px"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
            <p>No emails sent yet. Click <strong>Compose Mail</strong> to send your first email.</p>
          </div>
          @endif
        </div>

        {{-- Draft Tab Pane --}}
        <div class="av-email-pane" id="email-pane-draft">
          @if($draftEmails->count())
          <div class="av-email-list">
            @foreach($draftEmails as $em)
            <div class="av-email-item">
              <div class="av-email-avatar draft">{{ strtoupper(substr($em->to_email, 0, 1)) }}</div>
              <div class="av-email-content">
                <div class="av-email-header">
                  <span class="av-email-to">To: {{ $em->to_email }}</span>
                  <span class="av-email-date">{{ $em->created_at->format('d M Y, h:i A') }}</span>
                </div>
                <div class="av-email-subject">{{ $em->subject }} <span class="av-email-badge draft">Draft</span></div>
                <div class="av-email-preview">{{ strip_tags($em->body_html) }}</div>
                @if($em->error_message)
                <div class="av-email-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> {{ $em->error_message }}</div>
                @endif
              </div>
              <div class="av-email-actions">
                <button class="av-icon-btn" title="Edit Draft" onclick="editDraft({{ $em->id }}, {{ json_encode($em) }})">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <button class="av-icon-btn danger" title="Delete" onclick="deleteEmail({{ $em->id }}, this)">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                </button>
              </div>
            </div>
            @endforeach
          </div>
          @else
          <div class="av-empty">
            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="opacity:0.3;margin-bottom:8px"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            <p>No drafts saved.</p>
          </div>
          @endif
        </div>

        {{-- Scheduled Tab Pane --}}
        <div class="av-email-pane" id="email-pane-scheduled">
          @if($scheduledEmails->count())
          <div class="av-email-list">
            @foreach($scheduledEmails as $em)
            <div class="av-email-item">
              <div class="av-email-avatar scheduled">{{ strtoupper(substr($em->to_email, 0, 1)) }}</div>
              <div class="av-email-content">
                <div class="av-email-header">
                  <span class="av-email-to">To: {{ $em->to_email }}</span>
                  <span class="av-email-date">Scheduled: {{ $em->scheduled_at ? $em->scheduled_at->format('d M Y, h:i A') : '—' }}</span>
                </div>
                <div class="av-email-subject">{{ $em->subject }} <span class="av-email-badge scheduled">Scheduled</span></div>
                <div class="av-email-preview">{{ strip_tags($em->body_html) }}</div>
              </div>
              <div class="av-email-actions">
                <button class="av-icon-btn danger" title="Cancel" onclick="deleteEmail({{ $em->id }}, this)">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </button>
              </div>
            </div>
            @endforeach
          </div>
          @else
          <div class="av-empty">
            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="opacity:0.3;margin-bottom:8px"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <p>No scheduled emails.</p>
          </div>
          @endif
        </div>
      </div>
    </div>

    {{-- ─── SECTION 5: Open Activities ─── --}}
    <div class="av-section" id="sec-open-activities">
      <div class="av-section-header">
        <div class="av-section-title">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          Open Activities <span style="margin-left:.4rem;background:#f59e0b;color:#fff;border-radius:20px;padding:.1rem .5rem;font-size:.72rem">{{ $openActivities->count() }}</span>
        </div>
        <div class="av-section-actions">
          <div class="av-dropdown" id="dd-activity">
            <button class="av-btn primary sm" onclick="toggleDropdown('dd-activity')">
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Add Activity
              <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="av-dropdown-menu">
              <div class="av-dropdown-item" onclick="openActivityPopup('Task')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                Task
              </div>
              <div class="av-dropdown-item" onclick="openActivityPopup('Meeting')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                Meeting
              </div>
              <div class="av-dropdown-item" onclick="openActivityPopup('Call')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 .94h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                Call
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="av-section-body" style="padding:0">
        <div class="av-tabs" style="padding:0 1.25rem;padding-top:.75rem">
          <div class="av-tab active" onclick="switchTab('open','task')">Tasks ({{ $openActivities->where('type','Task')->count() }})</div>
          <div class="av-tab" onclick="switchTab('open','meeting')">Meetings ({{ $openActivities->where('type','Meeting')->count() }})</div>
          <div class="av-tab" onclick="switchTab('open','call')">Calls ({{ $openActivities->where('type','Call')->count() }})</div>
        </div>
        <div id="open-task" class="av-tab-pane active" style="padding:0">
          @php $tasks = $openActivities->where('type','Task') @endphp
          @if($tasks->count())
          <table class="av-table">
            <thead><tr><th>Subject</th><th>Due Date</th><th>Status</th></tr></thead>
            <tbody>@foreach($tasks as $act)<tr>
              <td>{{ $act->subject }}</td>
              <td>{{ $act->due_at ? \Carbon\Carbon::parse($act->due_at)->format('d M Y') : '—' }}</td>
              <td><span class="st st-orange">{{ $act->status ?: 'Open' }}</span></td>
            </tr>@endforeach</tbody>
          </table>
          @else<div class="av-empty">No open tasks.</div>@endif
        </div>
        <div id="open-meeting" class="av-tab-pane" style="padding:0">
          @php $meetings = $openActivities->where('type','Meeting') @endphp
          @if($meetings->count())
          <table class="av-table">
            <thead><tr><th>Subject</th><th>Due Date</th><th>Status</th></tr></thead>
            <tbody>@foreach($meetings as $act)<tr>
              <td>{{ $act->subject }}</td>
              <td>{{ $act->due_at ? \Carbon\Carbon::parse($act->due_at)->format('d M Y') : '—' }}</td>
              <td><span class="st st-blue">{{ $act->status ?: 'Open' }}</span></td>
            </tr>@endforeach</tbody>
          </table>
          @else<div class="av-empty">No open meetings.</div>@endif
        </div>
        <div id="open-call" class="av-tab-pane" style="padding:0">
          @php $calls = $openActivities->where('type','Call') @endphp
          @if($calls->count())
          <table class="av-table">
            <thead><tr><th>Subject</th><th>Due Date</th><th>Status</th></tr></thead>
            <tbody>@foreach($calls as $act)<tr>
              <td>{{ $act->subject }}</td>
              <td>{{ $act->due_at ? \Carbon\Carbon::parse($act->due_at)->format('d M Y') : '—' }}</td>
              <td><span class="st st-purple">{{ $act->status ?: 'Open' }}</span></td>
            </tr>@endforeach</tbody>
          </table>
          @else<div class="av-empty">No open calls.</div>@endif
        </div>
      </div>
    </div>

    {{-- ─── SECTION 6: Closed Activities ─── --}}
    <div class="av-section" id="sec-closed-activities">
      <div class="av-section-header">
        <div class="av-section-title">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
          Closed Activities <span style="margin-left:.4rem;background:#16a34a;color:#fff;border-radius:20px;padding:.1rem .5rem;font-size:.72rem">{{ $closedActivities->count() }}</span>
        </div>
      </div>
      <div class="av-section-body" style="padding:0">
        <div class="av-tabs" style="padding:0 1.25rem;padding-top:.75rem">
          <div class="av-tab active" onclick="switchTab('closed','task')">Closed Tasks ({{ $closedActivities->where('type','Task')->count() }})</div>
          <div class="av-tab" onclick="switchTab('closed','meeting')">Closed Meetings ({{ $closedActivities->where('type','Meeting')->count() }})</div>
          <div class="av-tab" onclick="switchTab('closed','call')">Closed Calls ({{ $closedActivities->where('type','Call')->count() }})</div>
        </div>
        <div id="closed-task" class="av-tab-pane active" style="padding:0">
          @php $ctasks = $closedActivities->where('type','Task') @endphp
          @if($ctasks->count())
          <table class="av-table">
            <thead><tr><th>Subject</th><th>Completed</th></tr></thead>
            <tbody>@foreach($ctasks as $act)<tr>
              <td>{{ $act->subject }}</td>
              <td>{{ $act->completed_at ? \Carbon\Carbon::parse($act->completed_at)->format('d M Y') : '—' }}</td>
            </tr>@endforeach</tbody>
          </table>
          @else<div class="av-empty">No closed tasks.</div>@endif
        </div>
        <div id="closed-meeting" class="av-tab-pane" style="padding:0">
          @php $cmeetings = $closedActivities->where('type','Meeting') @endphp
          @if($cmeetings->count())
          <table class="av-table">
            <thead><tr><th>Subject</th><th>Completed</th></tr></thead>
            <tbody>@foreach($cmeetings as $act)<tr>
              <td>{{ $act->subject }}</td>
              <td>{{ $act->completed_at ? \Carbon\Carbon::parse($act->completed_at)->format('d M Y') : '—' }}</td>
            </tr>@endforeach</tbody>
          </table>
          @else<div class="av-empty">No closed meetings.</div>@endif
        </div>
        <div id="closed-call" class="av-tab-pane" style="padding:0">
          @php $ccalls = $closedActivities->where('type','Call') @endphp
          @if($ccalls->count())
          <table class="av-table">
            <thead><tr><th>Subject</th><th>Completed</th></tr></thead>
            <tbody>@foreach($ccalls as $act)<tr>
              <td>{{ $act->subject }}</td>
              <td>{{ $act->completed_at ? \Carbon\Carbon::parse($act->completed_at)->format('d M Y') : '—' }}</td>
            </tr>@endforeach</tbody>
          </table>
          @else<div class="av-empty">No closed calls.</div>@endif
        </div>
      </div>
    </div>

    {{-- ─── SECTION 7: Products ─── --}}
    <div class="av-section" id="sec-products">
      <div class="av-section-header">
        <div class="av-section-title">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
          Products <span style="margin-left:.4rem;background:var(--accent);color:#fff;border-radius:20px;padding:.1rem .5rem;font-size:.72rem">{{ $accountProducts->count() }}</span>
        </div>
        <div class="av-section-actions">
          <button class="av-btn primary sm" onclick="openSlider('slider-products')">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Product
          </button>
        </div>
      </div>
      <div class="av-section-body" style="padding:0">
        @if($accountProducts->count())
        <table class="av-table">
          <thead><tr><th>Product</th><th>Code</th><th>Unit Price</th><th>Category</th><th>Stock</th></tr></thead>
          <tbody>
            @foreach($accountProducts as $prod)
            <tr>
              <td><a href="{{ route('admin.crm2.inventory.products.show', $prod->id) }}" style="color:var(--accent);font-weight:600">{{ $prod->name }}</a></td>
              <td>{{ $prod->product_code ?: '—' }}</td>
              <td>{{ $prod->unit_price ? number_format($prod->unit_price,2) : '—' }}</td>
              <td>{{ $prod->product_category ?: '—' }}</td>
              <td>{{ $prod->qty_in_stock ?? '—' }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        <div class="av-empty">No products linked to this account yet. Use "Add Product" to link products.</div>
        @endif
      </div>
    </div>

    {{-- ─── SECTION 8: Quotes ─── --}}
    <div class="av-section" id="sec-quotes">
      <div class="av-section-header">
        <div class="av-section-title">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Quotes <span style="margin-left:.4rem;background:var(--accent);color:#fff;border-radius:20px;padding:.1rem .5rem;font-size:.72rem">{{ $quotes->count() }}</span>
        </div>
        <div class="av-section-actions">
          <button class="av-btn outline sm" onclick="openSlider('slider-quotes')">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
            Assign
          </button>
          <a href="{{ route('admin.crm2.inventory.quotes.create') }}?account_id={{ $account->id }}" class="av-btn primary sm">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            New Quote
          </a>
        </div>
      </div>
      <div class="av-section-body" style="padding:0">
        @if($quotes->count())
        <table class="av-table">
          <thead><tr><th>Quote #</th><th>Subject</th><th>Stage</th><th>Total</th><th>Valid Until</th></tr></thead>
          <tbody>@foreach($quotes as $q)<tr>
            <td><a href="{{ route('admin.crm2.inventory.quotes.show', $q->id) }}" style="color:var(--accent);font-weight:600">{{ $q->quote_number ?: '#'.$q->id }}</a></td>
            <td>{{ $q->subject ?: '—' }}</td>
            <td><span class="st st-blue">{{ $q->stage ?: '—' }}</span></td>
            <td>{{ $q->grand_total ? number_format($q->grand_total,2) : '—' }}</td>
            <td>{{ $q->valid_until ? \Carbon\Carbon::parse($q->valid_until)->format('d M Y') : '—' }}</td>
          </tr>@endforeach</tbody>
        </table>
        @else<div class="av-empty">No quotes linked to this account.</div>@endif
      </div>
    </div>

    {{-- ─── SECTION 9: Sales Orders ─── --}}
    <div class="av-section" id="sec-sales-orders">
      <div class="av-section-header">
        <div class="av-section-title">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
          Sales Orders <span style="margin-left:.4rem;background:var(--accent);color:#fff;border-radius:20px;padding:.1rem .5rem;font-size:.72rem">{{ $salesOrders->count() }}</span>
        </div>
        <div class="av-section-actions">
          <button class="av-btn outline sm" onclick="openSlider('slider-sales-orders')">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
            Assign
          </button>
          <a href="{{ route('admin.crm2.inventory.sales-orders.create') }}?account_id={{ $account->id }}" class="av-btn primary sm">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            New Sales Order
          </a>
        </div>
      </div>
      <div class="av-section-body" style="padding:0">
        @if($salesOrders->count())
        <table class="av-table">
          <thead><tr><th>SO #</th><th>Subject</th><th>Status</th><th>Total</th><th>Delivery Date</th></tr></thead>
          <tbody>@foreach($salesOrders as $so)<tr>
            <td><a href="{{ route('admin.crm2.inventory.sales-orders.show', $so->id) }}" style="color:var(--accent);font-weight:600">{{ $so->so_number ?: '#'.$so->id }}</a></td>
            <td>{{ $so->subject ?: '—' }}</td>
            <td><span class="st st-green">{{ $so->status ?: '—' }}</span></td>
            <td>{{ $so->grand_total ? number_format($so->grand_total,2) : '—' }}</td>
            <td>{{ $so->delivery_date ? \Carbon\Carbon::parse($so->delivery_date)->format('d M Y') : '—' }}</td>
          </tr>@endforeach</tbody>
        </table>
        @else<div class="av-empty">No sales orders linked to this account.</div>@endif
      </div>
    </div>


    {{-- ─── SECTION: Purchase Orders ─── --}}
    <div class="av-section" id="sec-purchase-orders">
      <div class="av-section-header">
        <div class="av-section-title">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
          Purchase Orders <span style="margin-left:.4rem;background:var(--accent);color:#fff;border-radius:20px;padding:.1rem .5rem;font-size:.72rem">{{ $purchaseOrders->count() }}</span>
        </div>
        <div class="av-section-actions">
          <a href="{{ route('admin.crm2.inventory.purchase-orders.create') }}?account_id={{ $account->id }}" class="av-btn primary sm">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            New Purchase Order
          </a>
        </div>
      </div>
      <div class="av-section-body" style="padding:0">
        @if($purchaseOrders->count())
        <table class="av-table">
          <thead><tr><th>PO #</th><th>Subject</th><th>Status</th><th>Total</th><th>Due Date</th></tr></thead>
          <tbody>@foreach($purchaseOrders as $po)<tr>
            <td><a href="{{ route('admin.crm2.inventory.purchase-orders.show', $po->id) }}" style="color:var(--accent);font-weight:600">{{ $po->po_number ?: '#'.$po->id }}</a></td>
            <td>{{ $po->subject ?: '—' }}</td>
            <td><span class="st st-orange">{{ $po->status ?: '—' }}</span></td>
            <td>{{ $po->grand_total ? number_format($po->grand_total,2) : '—' }}</td>
            <td>{{ $po->due_date ? \Carbon\Carbon::parse($po->due_date)->format('d M Y') : '—' }}</td>
          </tr>@endforeach</tbody>
        </table>
        @else<div class="av-empty">No purchase orders linked to this account.</div>@endif
      </div>
    </div>

    {{-- ─── SECTION 10: Invoices ─── --}}
    <div class="av-section" id="sec-invoices">
      <div class="av-section-header">
        <div class="av-section-title">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
          Invoices <span style="margin-left:.4rem;background:var(--accent);color:#fff;border-radius:20px;padding:.1rem .5rem;font-size:.72rem">{{ $invoices->count() }}</span>
        </div>
        <div class="av-section-actions">
          <button class="av-btn outline sm" onclick="openSlider('slider-invoices')">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
            Assign
          </button>
          <a href="{{ route('admin.crm2.inventory.invoices.create') }}?account_id={{ $account->id }}" class="av-btn primary sm">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            New Invoice
          </a>
        </div>
      </div>
      <div class="av-section-body" style="padding:0">
        @if($invoices->count())
        <table class="av-table">
          <thead><tr><th>Invoice #</th><th>Subject</th><th>Status</th><th>Total</th><th>Due Date</th></tr></thead>
          <tbody>@foreach($invoices as $inv)<tr>
            <td><a href="{{ route('admin.crm2.inventory.invoices.show', $inv->id) }}" style="color:var(--accent);font-weight:600">{{ $inv->invoice_number ?: '#'.$inv->id }}</a></td>
            <td>{{ $inv->subject ?: '—' }}</td>
            <td><span class="st {{ $inv->status=='Paid'?'st-green':($inv->status=='Overdue'?'st-red':'st-orange') }}">{{ $inv->status ?: '—' }}</span></td>
            <td>{{ $inv->grand_total ? number_format($inv->grand_total,2) : '—' }}</td>
            <td>{{ $inv->due_date ? \Carbon\Carbon::parse($inv->due_date)->format('d M Y') : '—' }}</td>
          </tr>@endforeach</tbody>
        </table>
        @else<div class="av-empty">No invoices linked to this account.</div>@endif
      </div>
    </div>


  </div>{{-- end av-main --}}

  {{-- ── SECTION NAVIGATOR (frozen right sidebar) ── --}}
  <div class="av-nav">
    <div class="av-nav-card">
      <div class="av-nav-title">Sections</div>
      <a class="av-nav-item active" href="#sec-info" onclick="setActive(this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
        Account Info
      </a>
      <a class="av-nav-item" href="#sec-notes" onclick="setActive(this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Notes
      </a>
      <a class="av-nav-item" href="#sec-deals" onclick="setActive(this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
        Deals
      </a>
      <a class="av-nav-item" href="#sec-contacts" onclick="setActive(this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        Contacts
      </a>
      <a class="av-nav-item" href="#sec-email" onclick="setActive(this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
        Email
      </a>
      <a class="av-nav-item" href="#sec-open-activities" onclick="setActive(this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Open Activities
      </a>
      <a class="av-nav-item" href="#sec-closed-activities" onclick="setActive(this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        Closed Activities
      </a>
      <a class="av-nav-item" href="#sec-products" onclick="setActive(this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
        Products
      </a>
      <a class="av-nav-item" href="#sec-quotes" onclick="setActive(this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Quotes
      </a>
      <a class="av-nav-item" href="#sec-sales-orders" onclick="setActive(this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
        Sales Orders
      </a>
      <a class="av-nav-item" href="#sec-purchase-orders" onclick="setActive(this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
        Purchase Orders
      </a>
      <a class="av-nav-item" href="#sec-invoices" onclick="setActive(this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
        Invoices
      </a>
    </div>
  </div>

</div>{{-- end av-layout --}}

{{-- ═══════════════════════════════════════════════════════
     SLIDER PANELS
═══════════════════════════════════════════════════════ --}}

{{-- Deals Assign Slider --}}
<div class="av-slider-overlay" id="overlay-slider-deals" onclick="closeSlider('slider-deals')"></div>
<div class="av-slider" id="slider-deals">
  <div class="av-slider-head">
    <h3>Assign Deal to Account</h3>
    <button class="av-slider-close" onclick="closeSlider('slider-deals')">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <div class="av-slider-search"><input type="text" placeholder="Search deals..." oninput="filterSlider(this,'slider-deals-list')"></div>
  <div class="av-slider-list" id="slider-deals-list">
    @foreach($allDeals as $d)
    <div class="av-slider-item {{ $d->account_id==$account->id?'selected':'' }}" onclick="assignRecord('deal',{{ $d->id }},{{ $account->id }},this)">
      <div><div class="si-name">{{ $d->name ?: $d->title }}</div><div class="si-sub">{{ $d->stage }} &middot; {{ $d->amount ? number_format($d->amount,2) : '—' }}</div></div>
      <div class="si-check"><svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>
    </div>
    @endforeach
  </div>
  <div class="av-slider-foot">
    <button class="av-btn outline" style="flex:1" onclick="closeSlider('slider-deals')">Cancel</button>
  </div>
</div>

{{-- Contacts Assign Slider --}}
<div class="av-slider-overlay" id="overlay-slider-contacts" onclick="closeSlider('slider-contacts')"></div>
<div class="av-slider" id="slider-contacts">
  <div class="av-slider-head">
    <h3>Assign Contact to Account</h3>
    <button class="av-slider-close" onclick="closeSlider('slider-contacts')">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <div class="av-slider-search"><input type="text" placeholder="Search contacts..." oninput="filterSlider(this,'slider-contacts-list')"></div>
  <div class="av-slider-list" id="slider-contacts-list">
    @foreach($allContacts as $c)
    <div class="av-slider-item {{ $c->account_id==$account->id?'selected':'' }}" onclick="assignRecord('contact',{{ $c->id }},{{ $account->id }},this)">
      <div><div class="si-name">{{ $c->first_name }} {{ $c->last_name }}</div><div class="si-sub">{{ $c->job_title ?: $c->email }}</div></div>
      <div class="si-check"><svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>
    </div>
    @endforeach
  </div>
  <div class="av-slider-foot">
    <button class="av-btn outline" style="flex:1" onclick="closeSlider('slider-contacts')">Cancel</button>
  </div>
</div>

{{-- Products Add Slider --}}
<div class="av-slider-overlay" id="overlay-slider-products" onclick="closeSlider('slider-products')"></div>
<div class="av-slider" id="slider-products">
  <div class="av-slider-head">
    <h3>Add Product to Account</h3>
    <button class="av-slider-close" onclick="closeSlider('slider-products')">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <div class="av-slider-search"><input type="text" placeholder="Search products..." oninput="filterSlider(this,'slider-products-list')"></div>
  <div class="av-slider-list" id="slider-products-list">
    @foreach($allProducts as $p)
    <div class="av-prod-item {{ in_array($p->id,$accountProducts->pluck('id')->toArray())?'selected':'' }}" onclick="assignRecord('product',{{ $p->id }},{{ $account->id }},this)">
      @if($p->image)
      <img src="{{ asset('storage/'.$p->image) }}" class="av-prod-img" alt="{{ $p->name }}">
      @else
      <div class="av-prod-img" style="display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:700;color:var(--accent)">{{ strtoupper(substr($p->name,0,1)) }}</div>
      @endif
      <div class="av-prod-info">
        <div class="av-prod-name">{{ $p->name }}</div>
        <div class="av-prod-price">{{ $p->product_code }} &middot; {{ $p->unit_price ? number_format($p->unit_price,2) : '—' }}</div>
      </div>
      <div class="av-prod-check"><svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>
    </div>
    @endforeach
  </div>
  <div class="av-slider-foot">
    <button class="av-btn outline" style="flex:1" onclick="closeSlider('slider-products')">Done</button>
  </div>
</div>

{{-- Quotes Assign Slider --}}
<div class="av-slider-overlay" id="overlay-slider-quotes" onclick="closeSlider('slider-quotes')"></div>
<div class="av-slider" id="slider-quotes">
  <div class="av-slider-head">
    <h3>Assign Quote to Account</h3>
    <button class="av-slider-close" onclick="closeSlider('slider-quotes')">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <div class="av-slider-search"><input type="text" placeholder="Search quotes..." oninput="filterSlider(this,'slider-quotes-list')"></div>
  <div class="av-slider-list" id="slider-quotes-list">
    @foreach($allQuotes as $q)
    <div class="av-slider-item {{ $q->account_id==$account->id?'selected':'' }}" onclick="assignRecord('quote',{{ $q->id }},{{ $account->id }},this)">
      <div><div class="si-name">{{ $q->quote_number ?: '#'.$q->id }} — {{ $q->subject }}</div><div class="si-sub">{{ $q->stage }}</div></div>
      <div class="si-check"><svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>
    </div>
    @endforeach
  </div>
  <div class="av-slider-foot">
    <button class="av-btn outline" style="flex:1" onclick="closeSlider('slider-quotes')">Cancel</button>
  </div>
</div>

{{-- Sales Orders Assign Slider --}}
<div class="av-slider-overlay" id="overlay-slider-sales-orders" onclick="closeSlider('slider-sales-orders')"></div>
<div class="av-slider" id="slider-sales-orders">
  <div class="av-slider-head">
    <h3>Assign Sales Order to Account</h3>
    <button class="av-slider-close" onclick="closeSlider('slider-sales-orders')">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <div class="av-slider-search"><input type="text" placeholder="Search sales orders..." oninput="filterSlider(this,'slider-so-list')"></div>
  <div class="av-slider-list" id="slider-so-list">
    @foreach($allSalesOrders as $so)
    <div class="av-slider-item {{ $so->account_id==$account->id?'selected':'' }}" onclick="assignRecord('sales-order',{{ $so->id }},{{ $account->id }},this)">
      <div><div class="si-name">{{ $so->so_number ?: '#'.$so->id }} — {{ $so->subject }}</div><div class="si-sub">{{ $so->status }}</div></div>
      <div class="si-check"><svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>
    </div>
    @endforeach
  </div>
  <div class="av-slider-foot">
    <button class="av-btn outline" style="flex:1" onclick="closeSlider('slider-sales-orders')">Cancel</button>
  </div>
</div>

{{-- Invoices Assign Slider --}}
<div class="av-slider-overlay" id="overlay-slider-invoices" onclick="closeSlider('slider-invoices')"></div>
<div class="av-slider" id="slider-invoices">
  <div class="av-slider-head">
    <h3>Assign Invoice to Account</h3>
    <button class="av-slider-close" onclick="closeSlider('slider-invoices')">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <div class="av-slider-search"><input type="text" placeholder="Search invoices..." oninput="filterSlider(this,'slider-inv-list')"></div>
  <div class="av-slider-list" id="slider-inv-list">
    @foreach($allInvoices as $inv)
    <div class="av-slider-item {{ $inv->account_id==$account->id?'selected':'' }}" onclick="assignRecord('invoice',{{ $inv->id }},{{ $account->id }},this)">
      <div><div class="si-name">{{ $inv->invoice_number ?: '#'.$inv->id }} — {{ $inv->subject }}</div><div class="si-sub">{{ $inv->status }}</div></div>
      <div class="si-check"><svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>
    </div>
    @endforeach
  </div>
  <div class="av-slider-foot">
    <button class="av-btn outline" style="flex:1" onclick="closeSlider('slider-invoices')">Cancel</button>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     COMPOSE EMAIL SLIDER
═══════════════════════════════════════════════════════ --}}
<div class="av-slider-overlay" id="overlay-slider-compose-email" onclick="closeComposeSlider()"></div>
<div class="av-slider av-compose-slider" id="slider-compose-email">
  <div class="av-slider-head">
    <h3 id="compose-slider-title">Compose Email</h3>
    <button class="av-slider-close" onclick="closeComposeSlider()">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <div class="av-slider-body" style="padding:20px;overflow-y:auto;flex:1">

    {{-- Mail Config Warning --}}
    @if(!$mailConfig)
    <div class="av-email-warning">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      No active mail configuration found. Emails will be saved as drafts.
      <a href="{{ route('admin.crm2.integrations.mail-config') }}" target="_blank" style="color:var(--accent);text-decoration:underline">Configure Mail</a>
    </div>
    @endif

    {{-- Template Picker --}}
    <div class="av-form-group" style="margin-bottom:16px">
      <label class="av-form-label">Mail Template <span style="font-weight:400;color:var(--text-muted)">(optional)</span></label>
      <div class="av-template-picker">
        <select id="compose-template-select" class="av-form-input" onchange="loadEmailTemplate(this.value)">
          <option value="">— Select a template —</option>
          @foreach($mailTemplates as $tpl)
          <option value="{{ $tpl->id }}">{{ $tpl->name }} ({{ ucfirst(str_replace('_', ' ', $tpl->type)) }})</option>
          @endforeach
        </select>
        <button type="button" class="av-btn outline" id="load-template-btn" onclick="loadEmailTemplate(document.getElementById('compose-template-select').value)" style="white-space:nowrap">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Load
        </button>
      </div>
    </div>

    {{-- To --}}
    <div class="av-form-group" style="margin-bottom:12px">
      <label class="av-form-label">To <span style="color:var(--accent)">*</span></label>
      <input type="email" id="compose-to" class="av-form-input" placeholder="recipient@example.com"
        value="{{ $account->email ?? '' }}">
    </div>

    {{-- CC / BCC toggle --}}
    <div style="display:flex;gap:8px;margin-bottom:12px">
      <button type="button" class="av-btn outline" style="font-size:0.75rem;padding:4px 10px" onclick="toggleCcBcc('cc')">+ CC</button>
      <button type="button" class="av-btn outline" style="font-size:0.75rem;padding:4px 10px" onclick="toggleCcBcc('bcc')">+ BCC</button>
    </div>
    <div id="compose-cc-row" style="display:none;margin-bottom:12px">
      <label class="av-form-label">CC</label>
      <input type="email" id="compose-cc" class="av-form-input" placeholder="cc@example.com">
    </div>
    <div id="compose-bcc-row" style="display:none;margin-bottom:12px">
      <label class="av-form-label">BCC</label>
      <input type="email" id="compose-bcc" class="av-form-input" placeholder="bcc@example.com">
    </div>

    {{-- Subject --}}
    <div class="av-form-group" style="margin-bottom:12px">
      <label class="av-form-label">Subject <span style="color:var(--accent)">*</span></label>
      <input type="text" id="compose-subject" class="av-form-input" placeholder="Email subject">
    </div>

    {{-- Body --}}
    <div class="av-form-group" style="margin-bottom:12px">
      <label class="av-form-label">Message <span style="color:var(--accent)">*</span></label>
      <div class="av-email-editor-toolbar">
        <button type="button" onclick="execCmd('bold')" title="Bold"><strong>B</strong></button>
        <button type="button" onclick="execCmd('italic')" title="Italic"><em>I</em></button>
        <button type="button" onclick="execCmd('underline')" title="Underline"><u>U</u></button>
        <button type="button" onclick="execCmd('insertUnorderedList')" title="Bullet List">&#8226;</button>
        <button type="button" onclick="execCmd('insertOrderedList')" title="Numbered List">1.</button>
        <button type="button" onclick="insertLink()" title="Insert Link">&#128279;</button>
        <button type="button" onclick="clearEditor()" title="Clear" style="margin-left:auto;color:var(--text-muted)">Clear</button>
      </div>
      <div id="compose-body" class="av-email-editor" contenteditable="true" data-placeholder="Write your email message here..."></div>
    </div>

    {{-- Schedule Date (hidden by default) --}}
    <div id="compose-schedule-row" style="display:none;margin-bottom:12px">
      <label class="av-form-label">Schedule Date & Time</label>
      <input type="datetime-local" id="compose-scheduled-at" class="av-form-input">
    </div>

    {{-- Status message --}}
    <div id="compose-status" style="display:none;padding:10px 14px;border-radius:8px;font-size:0.85rem;margin-bottom:12px"></div>

  </div>
  {{-- Footer Actions --}}
  <div class="av-slider-footer" style="padding:16px 20px;border-top:1px solid var(--border);display:flex;gap:10px;flex-wrap:wrap">
    <button type="button" class="av-btn primary" id="compose-send-btn" onclick="submitEmail('send')">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
      Send
    </button>
    <button type="button" class="av-btn outline" onclick="submitEmail('draft')">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
      Save Draft
    </button>
    <button type="button" class="av-btn outline" onclick="toggleSchedule()">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      Schedule
    </button>
    <button type="button" class="av-btn outline" style="margin-left:auto" onclick="closeComposeSlider()">Cancel</button>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     ACTIVITY POPUP
═══════════════════════════════════════════════════════ --}}
<div class="av-popup-overlay" id="popup-activity">
  <div class="av-popup">
    <div class="av-popup-head">
      <h3 id="popup-activity-title">Add Activity</h3>
      <button class="av-slider-close" onclick="closeActivityPopup()">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <form method="POST" action="{{ route('admin.crm2.accounts.activities.store', $account->id) }}">
      @csrf
      <input type="hidden" name="type" id="popup-activity-type" value="Task">
      <div class="av-popup-body">
        <div class="av-form-row full">
          <div class="av-form-group">
            <label>Subject *</label>
            <input type="text" name="subject" required placeholder="Activity subject">
          </div>
        </div>
        <div class="av-form-row">
          <div class="av-form-group">
            <label>Due Date</label>
            <input type="datetime-local" name="due_at">
          </div>
          <div class="av-form-group">
            <label>Status</label>
            <select name="status">
              <option value="Open">Open</option>
              <option value="In Progress">In Progress</option>
              <option value="Completed">Completed</option>
              <option value="Deferred">Deferred</option>
            </select>
          </div>
        </div>
        <div class="av-form-row full">
          <div class="av-form-group">
            <label>Description</label>
            <textarea name="description" placeholder="Details..."></textarea>
          </div>
        </div>
      </div>
      <div class="av-popup-foot">
        <button type="button" class="av-btn outline" onclick="closeActivityPopup()">Cancel</button>
        <button type="submit" class="av-btn primary">Save Activity</button>
      </div>
    </form>
  </div>
</div>


<style>
/* ── Email Section ── */
.av-email-tabs { display:flex; gap:0; border-bottom:1px solid var(--border); padding:0 20px; background:var(--bg-card); }
.av-email-tab { display:flex; align-items:center; gap:6px; padding:12px 16px; font-size:0.85rem; font-weight:500; color:var(--text-muted); border:none; background:none; cursor:pointer; border-bottom:2px solid transparent; transition:all .2s; }
.av-email-tab:hover { color:var(--text-primary); }
.av-email-tab.active { color:var(--accent); border-bottom-color:var(--accent); }
.av-email-count { display:inline-flex; align-items:center; justify-content:center; min-width:18px; height:18px; padding:0 5px; background:var(--bg-secondary); color:var(--text-muted); border-radius:9px; font-size:0.7rem; font-weight:600; }
.av-email-tab.active .av-email-count { background:var(--accent); color:#fff; }
.av-email-source-bar { display:flex; align-items:center; gap:10px; padding:10px 20px; background:var(--bg-secondary); border-bottom:1px solid var(--border); font-size:0.82rem; }
.av-email-source-label { color:var(--text-muted); white-space:nowrap; }
.av-email-pane { display:none; }
.av-email-pane.active { display:block; }
.av-email-list { display:flex; flex-direction:column; }
.av-email-item { display:flex; align-items:flex-start; gap:12px; padding:14px 20px; border-bottom:1px solid var(--border); transition:background .15s; }
.av-email-item:hover { background:var(--bg-secondary); }
.av-email-avatar { width:36px; height:36px; border-radius:50%; background:var(--accent); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.9rem; flex-shrink:0; }
.av-email-avatar.draft { background:var(--text-muted); }
.av-email-avatar.scheduled { background:#f59e0b; }
.av-email-content { flex:1; min-width:0; }
.av-email-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:3px; gap:8px; }
.av-email-to { font-size:0.82rem; color:var(--text-muted); }
.av-email-date { font-size:0.75rem; color:var(--text-muted); white-space:nowrap; }
.av-email-subject { font-size:0.9rem; font-weight:600; color:var(--text-primary); margin-bottom:3px; display:flex; align-items:center; gap:8px; }
.av-email-preview { font-size:0.8rem; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:400px; }
.av-email-error { display:flex; align-items:center; gap:4px; font-size:0.75rem; color:#ef4444; margin-top:4px; }
.av-email-badge { font-size:0.68rem; font-weight:600; padding:2px 7px; border-radius:10px; text-transform:uppercase; letter-spacing:.04em; }
.av-email-badge.draft { background:rgba(100,116,139,.15); color:#64748b; }
.av-email-badge.scheduled { background:rgba(245,158,11,.15); color:#f59e0b; }
.av-email-actions { display:flex; gap:6px; flex-shrink:0; }
.av-email-warning { display:flex; align-items:center; gap:8px; padding:10px 14px; background:rgba(245,158,11,.1); border:1px solid rgba(245,158,11,.3); border-radius:8px; font-size:0.82rem; color:#92400e; margin-bottom:16px; flex-wrap:wrap; }
[data-theme="dark"] .av-email-warning { color:#fbbf24; }
/* Compose Slider */
.av-compose-slider { width:520px !important; right:-540px; }
.av-template-picker { display:flex; gap:8px; }
.av-template-picker select { flex:1; }
.av-email-editor-toolbar { display:flex; gap:4px; padding:6px 8px; background:var(--bg-secondary); border:1px solid var(--border); border-bottom:none; border-radius:8px 8px 0 0; flex-wrap:wrap; }
.av-email-editor-toolbar button { padding:3px 8px; background:none; border:1px solid transparent; border-radius:4px; cursor:pointer; font-size:0.82rem; color:var(--text-primary); transition:all .15s; }
.av-email-editor-toolbar button:hover { background:var(--bg-card); border-color:var(--border); }
.av-email-editor { min-height:200px; padding:12px; background:var(--bg-card); border:1px solid var(--border); border-radius:0 0 8px 8px; color:var(--text-primary); font-size:0.875rem; line-height:1.6; overflow-y:auto; max-height:300px; }
.av-email-editor:empty:before { content:attr(data-placeholder); color:var(--text-muted); pointer-events:none; }
.av-email-editor:focus { outline:none; border-color:var(--accent); }
</style>
<script>
// ── Section Navigator ──
function setActive(el) {
  document.querySelectorAll('.av-nav-item').forEach(i => i.classList.remove('active'));
  el.classList.add('active');
}

// Scroll spy
const sections = document.querySelectorAll('.av-section[id]');
const navItems = document.querySelectorAll('.av-nav-item');
window.addEventListener('scroll', () => {
  let current = '';
  sections.forEach(s => {
    if (window.scrollY >= s.offsetTop - 120) current = s.id;
  });
  navItems.forEach(n => {
    n.classList.toggle('active', n.getAttribute('href') === '#' + current);
  });
}, { passive: true });

// ── Activity Tabs ──
function switchTab(group, type) {
  document.querySelectorAll('#sec-' + group + '-activities .av-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('#sec-' + group + '-activities .av-tab-pane').forEach(p => p.classList.remove('active'));
  event.target.classList.add('active');
  document.getElementById(group + '-' + type).classList.add('active');
}

// ── Dropdown ──
function toggleDropdown(id) {
  const dd = document.getElementById(id);
  dd.classList.toggle('open');
  document.addEventListener('click', function handler(e) {
    if (!dd.contains(e.target)) { dd.classList.remove('open'); document.removeEventListener('click', handler); }
  });
}

// ── Sliders ──
function openSlider(id) {
  document.getElementById('overlay-' + id).classList.add('open');
  document.getElementById(id).classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeSlider(id) {
  document.getElementById('overlay-' + id).classList.remove('open');
  document.getElementById(id).classList.remove('open');
  document.body.style.overflow = '';
}

// ── Slider Search ──
function filterSlider(input, listId) {
  const q = input.value.toLowerCase();
  document.querySelectorAll('#' + listId + ' .av-slider-item, #' + listId + ' .av-prod-item').forEach(item => {
    item.style.display = item.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
}

// ── Assign Record via AJAX ──
function assignRecord(type, recordId, accountId, el) {
  const isSelected = el.classList.contains('selected');
  const action = isSelected ? 'unassign' : 'assign';
  fetch('{{ route("admin.crm2.accounts.assign", $account->id) }}', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({ type, record_id: recordId, action })
  }).then(r => r.json()).then(data => {
    if (data.success) {
      el.classList.toggle('selected');
      // Update count badge
      location.reload();
    }
  }).catch(() => alert('Failed to assign. Please try again.'));
}

// ── Activity Popup ──
function openActivityPopup(type) {
  document.getElementById('popup-activity-type').value = type;
  document.getElementById('popup-activity-title').textContent = 'Add ' + type;
  document.getElementById('popup-activity').classList.add('open');
  document.body.style.overflow = 'hidden';
  // Close dropdown
  document.querySelectorAll('.av-dropdown').forEach(d => d.classList.remove('open'));
}
function closeActivityPopup() {
  document.getElementById('popup-activity').classList.remove('open');
  document.body.style.overflow = '';
}
// Close popup on overlay click
document.getElementById('popup-activity').addEventListener('click', function(e) {
  if (e.target === this) closeActivityPopup();
});

// ── Email Section ──
let currentEditEmailId = null;
const accountId = {{ $account->id }};
const csrfToken = '{{ csrf_token() }}';
const emailListUrl = '{{ route("admin.crm2.accounts.emails.list", $account->id) }}';
const emailStoreUrl = '{{ route("admin.crm2.accounts.emails.store", $account->id) }}';
const emailTemplateUrl = '{{ route("admin.crm2.accounts.emails.template", $account->id) }}';
const emailDestroyBase = '{{ url("admin/crm2/sales/accounts/" . $account->id . "/emails") }}';

function switchEmailTab(tab, btn) {
  document.querySelectorAll('.av-email-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.av-email-pane').forEach(p => p.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('email-pane-' + tab).classList.add('active');
  const sourceBar = document.getElementById('email-source-bar');
  if (sourceBar) sourceBar.style.display = tab === 'scheduled' ? 'flex' : 'none';
}

function setEmailSource(source, btn) {
  document.querySelectorAll('#dd-email-source .av-dropdown-item').forEach(i => i.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('email-source-label-text').textContent = btn.textContent.trim();
  document.getElementById('dd-email-source').classList.remove('open');
}

function openComposeSlider(emailData) {
  currentEditEmailId = null;
  document.getElementById('compose-slider-title').textContent = 'Compose Email';
  document.getElementById('compose-send-btn').style.display = '';
  // Reset form
  document.getElementById('compose-to').value = '{{ $account->email ?? "" }}';
  document.getElementById('compose-cc').value = '';
  document.getElementById('compose-bcc').value = '';
  document.getElementById('compose-subject').value = '';
  document.getElementById('compose-body').innerHTML = '';
  document.getElementById('compose-template-select').value = '';
  document.getElementById('compose-cc-row').style.display = 'none';
  document.getElementById('compose-bcc-row').style.display = 'none';
  document.getElementById('compose-schedule-row').style.display = 'none';
  document.getElementById('compose-status').style.display = 'none';
  if (emailData) {
    // Pre-fill for edit draft
    currentEditEmailId = emailData.id;
    document.getElementById('compose-slider-title').textContent = 'Edit Draft';
    document.getElementById('compose-send-btn').style.display = '';
    document.getElementById('compose-to').value = emailData.to_email || '';
    document.getElementById('compose-cc').value = emailData.cc_email || '';
    document.getElementById('compose-bcc').value = emailData.bcc_email || '';
    document.getElementById('compose-subject').value = emailData.subject || '';
    document.getElementById('compose-body').innerHTML = emailData.body_html || '';
    if (emailData.cc_email) document.getElementById('compose-cc-row').style.display = '';
    if (emailData.bcc_email) document.getElementById('compose-bcc-row').style.display = '';
  }
  document.getElementById('overlay-slider-compose-email').classList.add('open');
  document.getElementById('slider-compose-email').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeComposeSlider() {
  document.getElementById('overlay-slider-compose-email').classList.remove('open');
  document.getElementById('slider-compose-email').classList.remove('open');
  document.body.style.overflow = '';
}

function toggleCcBcc(field) {
  const row = document.getElementById('compose-' + field + '-row');
  row.style.display = row.style.display === 'none' ? '' : 'none';
}

function toggleSchedule() {
  const row = document.getElementById('compose-schedule-row');
  const isVisible = row.style.display !== 'none';
  row.style.display = isVisible ? 'none' : '';
  if (!isVisible) {
    // Auto-set to 1 hour from now
    const dt = new Date(Date.now() + 3600000);
    const pad = n => String(n).padStart(2,'0');
    document.getElementById('compose-scheduled-at').value =
      dt.getFullYear() + '-' + pad(dt.getMonth()+1) + '-' + pad(dt.getDate()) +
      'T' + pad(dt.getHours()) + ':' + pad(dt.getMinutes());
  }
}

function loadEmailTemplate(templateId) {
  if (!templateId) return;
  fetch(emailTemplateUrl + '?template_id=' + templateId, {
    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
  }).then(r => r.json()).then(data => {
    if (data.success) {
      document.getElementById('compose-subject').value = data.subject || '';
      document.getElementById('compose-body').innerHTML = data.body_html || '';
    }
  }).catch(() => {});
}

function execCmd(cmd) {
  document.getElementById('compose-body').focus();
  document.execCommand(cmd, false, null);
}
function insertLink() {
  const url = prompt('Enter URL:', 'https://');
  if (url) { document.getElementById('compose-body').focus(); document.execCommand('createLink', false, url); }
}
function clearEditor() {
  if (confirm('Clear the email body?')) document.getElementById('compose-body').innerHTML = '';
}

function submitEmail(action) {
  const to = document.getElementById('compose-to').value.trim();
  const subject = document.getElementById('compose-subject').value.trim();
  const body = document.getElementById('compose-body').innerHTML.trim();
  const statusEl = document.getElementById('compose-status');

  if (!to) { showComposeStatus('Please enter a recipient email address.', 'error'); return; }
  if (!subject) { showComposeStatus('Please enter a subject.', 'error'); return; }
  if (!body || body === '') { showComposeStatus('Please write a message.', 'error'); return; }
  if (action === 'schedule') {
    const sched = document.getElementById('compose-scheduled-at').value;
    if (!sched) { showComposeStatus('Please set a schedule date and time.', 'error'); return; }
  }

  const sendBtn = document.getElementById('compose-send-btn');
  sendBtn.disabled = true;
  sendBtn.textContent = action === 'send' ? 'Sending...' : 'Saving...';

  const payload = {
    action,
    to_email: to,
    cc_email: document.getElementById('compose-cc').value.trim() || null,
    bcc_email: document.getElementById('compose-bcc').value.trim() || null,
    subject,
    body_html: body,
    mail_template_id: document.getElementById('compose-template-select').value || null,
    scheduled_at: action === 'schedule' ? document.getElementById('compose-scheduled-at').value : null,
  };

  fetch(emailStoreUrl, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
    body: JSON.stringify(payload)
  }).then(r => r.json()).then(data => {
    sendBtn.disabled = false;
    sendBtn.textContent = 'Send';
    if (data.success) {
      showComposeStatus(data.message, data.status === 'sent' ? 'success' : 'warning');
      setTimeout(() => {
        closeComposeSlider();
        location.reload();
      }, 1800);
    } else {
      showComposeStatus(data.message || 'Failed to send email.', 'error');
    }
  }).catch(err => {
    sendBtn.disabled = false;
    sendBtn.textContent = 'Send';
    showComposeStatus('Network error. Please try again.', 'error');
  });
}

function showComposeStatus(msg, type) {
  const el = document.getElementById('compose-status');
  el.style.display = 'block';
  el.textContent = msg;
  const colors = {
    success: 'rgba(34,197,94,.15)',
    error:   'rgba(239,68,68,.15)',
    warning: 'rgba(245,158,11,.15)'
  };
  const textColors = { success: '#15803d', error: '#dc2626', warning: '#92400e' };
  el.style.background = colors[type] || colors.success;
  el.style.color = textColors[type] || textColors.success;
  el.style.border = '1px solid ' + (el.style.background);
}

function editDraft(emailId, emailData) {
  openComposeSlider(emailData);
}

function viewEmail(emailId) {
  // Open a simple modal or redirect — for now open in new tab
  window.open(emailDestroyBase + '/' + emailId + '/view', '_blank');
}

function deleteEmail(emailId, btn) {
  if (!confirm('Delete this email?')) return;
  fetch(emailDestroyBase + '/' + emailId, {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
  }).then(r => r.json()).then(data => {
    if (data.success) {
      const item = btn.closest('.av-email-item');
      if (item) item.remove();
    }
  }).catch(() => alert('Failed to delete.'));
}
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
