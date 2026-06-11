@extends('layouts.admin')
@section('content')
<style>
/* ══════════════════════════════════════════════════════
   Price Book View — mirrors Accounts View style
══════════════════════════════════════════════════════ */
/* theme variables inherited from global admin layout */
/* ── Layout ── */
.av-layout { display:flex; gap:1.5rem; padding:1.5rem; align-items:flex-start; max-width:1400px; margin:0 auto; }
.av-main { flex:1; min-width:0; }
.av-nav { width:200px; flex-shrink:0; position:sticky; top:1.5rem; height:fit-content; }
.av-nav-card { background:var(--bg-card); border:1px solid var(--border); border-radius:12px; overflow:hidden; }
.av-nav-title { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--text-muted); padding:.75rem 1rem .4rem; }
.av-nav-item { display:flex; align-items:center; gap:.55rem; padding:.55rem 1rem; font-size:.82rem; color:var(--text-secondary); cursor:pointer; transition:all .15s; text-decoration:none; border-left:3px solid transparent; }
.av-nav-item:hover,.av-nav-item.active { color:var(--accent); background:rgba(var(--accent-rgb,99,102,241),.07); border-left-color:var(--accent); }
.av-nav-item svg { flex-shrink:0; opacity:.7; }
.av-nav-item.active svg { opacity:1; }
/* ── Header ── */
.av-header { background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:1.25rem 1.5rem; margin-bottom:1.25rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
.av-identity { display:flex; align-items:center; gap:1rem; }
.av-avatar { width:52px; height:52px; border-radius:12px; background:var(--accent); color:#fff; font-size:1.3rem; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.av-name { font-size:1.25rem; font-weight:700; color:var(--text-primary); }
.av-meta { display:flex; gap:.4rem; flex-wrap:wrap; margin-top:.3rem; }
.av-badge { display:inline-flex; align-items:center; padding:.15rem .6rem; border-radius:20px; font-size:.72rem; font-weight:600; }
.av-badge.blue { background:rgba(37,99,235,.15); color:#60a5fa; }
.av-badge.green { background:rgba(22,163,74,.15); color:#4ade80; }
.av-badge.gray { background:var(--bg-hover); color:var(--text-secondary); }
.av-badge.orange { background:rgba(234,88,12,.15); color:#fb923c; }
.av-badge.red { background:rgba(220,38,38,.15); color:#f87171; }
/* ── Buttons ── */
.av-actions { display:flex; gap:.5rem; flex-wrap:wrap; align-items:center; }
.av-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.5rem 1rem; border-radius:8px; font-size:.82rem; font-weight:600; cursor:pointer; border:none; text-decoration:none; transition:all .15s; white-space:nowrap; }
.av-btn.primary { background:var(--accent); color:#fff; }
.av-btn.primary:hover { opacity:.88; }
.av-btn.outline { background:transparent; color:var(--text-primary); border:1.5px solid var(--border); }
.av-btn.outline:hover { border-color:var(--accent); color:var(--accent); }
.av-btn.danger { background:rgba(220,38,38,.12); color:#f87171; border:1.5px solid rgba(220,38,38,.3); }
.av-btn.danger:hover { background:#dc2626; color:#fff; border-color:#dc2626; }
.av-btn.sm { padding:.35rem .75rem; font-size:.78rem; }
/* ── Sections ── */
.av-section { background:var(--bg-card); border:1px solid var(--border); border-radius:12px; margin-bottom:1.25rem; overflow:hidden; }
.av-section-header { display:flex; align-items:center; justify-content:space-between; padding:.9rem 1.25rem; border-bottom:1px solid var(--border); background:var(--bg-primary); }
.av-section-title { display:flex; align-items:center; gap:.6rem; font-size:.9rem; font-weight:700; color:var(--text-primary); }
.av-section-title svg { color:var(--accent); }
.av-section-actions { display:flex; gap:.4rem; flex-wrap:wrap; }
.av-section-body { padding:1.25rem; }
/* ── Info Grid ── */
.av-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:.75rem 1.5rem; }
.av-field label { font-size:.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); display:block; margin-bottom:.2rem; }
.av-field span { font-size:.88rem; color:var(--text-primary); display:block; word-break:break-word; }
.av-field span.empty { color:var(--text-muted); font-style:italic; }
/* ── Notes ── */
.av-note-form { display:flex; gap:.75rem; margin-bottom:1rem; }
.av-note-form textarea { flex:1; border:1.5px solid var(--border); border-radius:8px; padding:.6rem .85rem; font-size:.85rem; background:var(--bg-primary); color:var(--text-primary); resize:vertical; min-height:70px; font-family:inherit; }
.av-note-form textarea:focus { outline:none; border-color:var(--accent); }
.av-note-list { display:flex; flex-direction:column; gap:.6rem; }
.av-note-item { background:var(--bg-primary); border:1px solid var(--border); border-radius:8px; padding:.75rem 1rem; }
.av-note-item .note-text { font-size:.85rem; color:var(--text-primary); margin-bottom:.3rem; }
.av-note-item .note-meta { font-size:.72rem; color:var(--text-muted); }
/* ── Table ── */
.av-table { width:100%; border-collapse:collapse; font-size:.83rem; }
.av-table th { text-align:left; padding:.55rem .75rem; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); border-bottom:1.5px solid var(--border); background:var(--bg-primary); }
.av-table td { padding:.6rem .75rem; border-bottom:1px solid var(--border); color:var(--text-primary); vertical-align:middle; }
.av-table tr:last-child td { border-bottom:none; }
.av-table tr:hover td { background:var(--bg-primary); }
.av-table .td-actions { display:flex; gap:.35rem; }
.av-empty { text-align:center; padding:2rem; color:var(--text-muted); font-size:.85rem; }
.av-empty svg { display:block; margin:0 auto .5rem; opacity:.3; }
/* ── Status badges ── */
.st { display:inline-flex; align-items:center; padding:.15rem .55rem; border-radius:20px; font-size:.72rem; font-weight:600; }
.st-green { background:rgba(22,163,74,.15); color:#4ade80; }
.st-blue { background:rgba(37,99,235,.15); color:#60a5fa; }
.st-orange { background:rgba(234,88,12,.15); color:#fb923c; }
.st-gray { background:var(--bg-hover); color:var(--text-secondary); }
.st-red { background:rgba(220,38,38,.15); color:#f87171; }
/* ── Attachments ── */
.av-attach-drop { border:2px dashed var(--border); border-radius:10px; padding:2rem; text-align:center; cursor:pointer; transition:border-color .15s; background:var(--bg-primary); margin-bottom:1rem; }
.av-attach-drop:hover, .av-attach-drop.dragover { border-color:var(--accent); background:rgba(var(--accent-rgb,99,102,241),.04); }
.av-attach-drop svg { color:var(--text-muted); margin-bottom:.5rem; }
.av-attach-drop p { font-size:.85rem; color:var(--text-muted); margin:.3rem 0 0; }
.av-attach-drop p strong { color:var(--accent); }
.av-attach-drop input[type=file] { display:none; }
.av-attach-list { display:flex; flex-direction:column; gap:.5rem; }
.av-attach-item { display:flex; align-items:center; gap:.75rem; padding:.65rem 1rem; background:var(--bg-primary); border:1px solid var(--border); border-radius:8px; }
.av-attach-icon { width:36px; height:36px; border-radius:8px; background:rgba(var(--accent-rgb,99,102,241),.1); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.av-attach-icon svg { color:var(--accent); }
.av-attach-info { flex:1; min-width:0; }
.av-attach-name { font-size:.84rem; font-weight:600; color:var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.av-attach-meta { font-size:.72rem; color:var(--text-muted); }
.av-attach-del { background:none; border:none; cursor:pointer; color:var(--text-muted); padding:.25rem; border-radius:6px; display:flex; align-items:center; }
.av-attach-del:hover { color:#f87171; background:rgba(220,38,38,.12); }
/* ── Product Slider ── */
.av-slider-overlay { position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1000; opacity:0; pointer-events:none; transition:opacity .25s; }
.av-slider-overlay.open { opacity:1; pointer-events:all; }
.av-slider { position:fixed; top:0; right:-480px; width:460px; max-width:95vw; height:100vh; background:var(--bg-card); box-shadow:-4px 0 24px rgba(0,0,0,.18); z-index:1001; transition:right .3s cubic-bezier(.4,0,.2,1); display:flex; flex-direction:column; }
.av-slider.open { right:0; }
.av-slider-head { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid var(--border); flex-shrink:0; }
.av-slider-head h3 { font-size:1rem; font-weight:700; color:var(--text-primary); margin:0; }
.av-slider-close { background:none; border:none; cursor:pointer; color:var(--text-muted); padding:.25rem; border-radius:6px; display:flex; align-items:center; }
.av-slider-close:hover { background:var(--bg-primary); color:var(--text-primary); }
.av-slider-search { padding:.75rem 1.25rem; border-bottom:1px solid var(--border); flex-shrink:0; }
.av-slider-search input { width:100%; border:1.5px solid var(--border); border-radius:8px; padding:.5rem .85rem; font-size:.85rem; background:var(--bg-primary); color:var(--text-primary); box-sizing:border-box; }
.av-slider-search input:focus { outline:none; border-color:var(--accent); }
.av-slider-list { flex:1; overflow-y:auto; }
.av-slider-foot { padding:.75rem 1.25rem; border-top:1px solid var(--border); display:flex; gap:.5rem; flex-shrink:0; }
.av-prod-item { display:flex; align-items:center; gap:.75rem; padding:.65rem 1.25rem; border-bottom:1px solid var(--border); cursor:pointer; transition:background .12s; }
.av-prod-item:hover { background:var(--bg-primary); }
.av-prod-img { width:40px; height:40px; border-radius:6px; object-fit:cover; background:var(--bg-primary); border:1px solid var(--border); flex-shrink:0; }
.av-prod-info { flex:1; min-width:0; }
.av-prod-name { font-size:.85rem; font-weight:600; color:var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.av-prod-price { font-size:.75rem; color:var(--text-muted); }
.av-prod-check { width:20px; height:20px; border-radius:50%; border:2px solid var(--border); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.av-prod-item.selected .av-prod-check { background:var(--accent); border-color:var(--accent); color:#fff; }
/* ── Import CSV Slider ── */
.av-import-body { padding:1.25rem; overflow-y:auto; flex:1; }
.av-import-step { margin-bottom:1.25rem; }
.av-import-step h4 { font-size:.85rem; font-weight:700; color:var(--text-primary); margin:0 0 .5rem; }
.av-import-step p { font-size:.82rem; color:var(--text-muted); margin:0 0 .75rem; }
.av-import-drop { border:2px dashed var(--border); border-radius:10px; padding:1.5rem; text-align:center; cursor:pointer; transition:border-color .15s; background:var(--bg-primary); }
.av-import-drop:hover { border-color:var(--accent); }
.av-import-drop svg { color:var(--text-muted); margin-bottom:.4rem; }
.av-import-drop p { font-size:.82rem; color:var(--text-muted); margin:.25rem 0 0; }
.av-import-drop input[type=file] { display:none; }
/* ── Edit All inline table ── */
.av-edit-all-row td input, .av-edit-all-row td select { width:100%; border:1.5px solid var(--border); border-radius:6px; padding:.35rem .5rem; font-size:.82rem; background:var(--bg-primary); color:var(--text-primary); }
.av-edit-all-row td input:focus, .av-edit-all-row td select:focus { outline:none; border-color:var(--accent); }
/* ── Toast ── */
.av-toast { position:fixed; bottom:1.5rem; right:1.5rem; background:#1e293b; color:#fff; padding:.75rem 1.25rem; border-radius:10px; font-size:.85rem; z-index:9999; display:none; align-items:center; gap:.6rem; box-shadow:0 4px 16px rgba(0,0,0,.2); }
.av-toast.show { display:flex; }
.av-toast.success { background:#16a34a; }
.av-toast.error { background:#dc2626; }
@media(max-width:900px) {
  .av-layout { flex-direction:column-reverse; padding:1rem .5rem; }
  .av-nav { width:100%; position:static; }
  .av-nav-card { display:flex; overflow-x:auto; padding:.5rem; }
  .av-nav-title { display:none; }
  .av-nav-item { white-space:nowrap; border-left:none; border-bottom:3px solid transparent; padding:.4rem .75rem; }
  .av-nav-item.active { border-bottom-color:var(--accent); border-left:none; }
}
</style>

<div class="av-layout">
  {{-- ══ MAIN CONTENT ══ --}}
  <div class="av-main">

    {{-- Header --}}
    <div class="av-header">
      <div class="av-identity">
        <div class="av-avatar">{{ strtoupper(substr($item->name, 0, 1)) }}</div>
        <div>
          <div class="av-name">{{ $item->name }}</div>
          <div class="av-meta">
            @if($item->pricing_model)
              <span class="av-badge blue">{{ $item->pricing_model }}</span>
            @endif
            @if($item->currency)
              <span class="av-badge gray">{{ $item->currency }}</span>
            @endif
            <span class="av-badge {{ $item->is_active ? 'green' : 'red' }}">
              {{ $item->is_active ? 'Active' : 'Inactive' }}
            </span>
          </div>
        </div>
      </div>
      <div class="av-actions">
        <a href="{{ route('admin.crm2.inventory.price-books.edit', $item->id) }}" class="av-btn outline">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Edit
        </a>
        <a href="{{ route('admin.crm2.inventory.price-books') }}" class="av-btn outline">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
          Back
        </a>
        <form method="POST" action="{{ route('admin.crm2.inventory.destroy', ['type'=>'price_book','id'=>$item->id]) }}" onsubmit="return confirm('Delete this price book?')" style="display:inline">
          @csrf @method('DELETE')
          <button type="submit" class="av-btn danger">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
            Delete
          </button>
        </form>
      </div>
    </div>

    {{-- ─── SECTION 1: Price Book Information ─── --}}
    <div class="av-section" id="sec-info">
      <div class="av-section-header">
        <div class="av-section-title">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>
          Price Book Information
        </div>
      </div>
      <div class="av-section-body">
        <div class="av-grid">
          <div class="av-field">
            <label>Price Book Name</label>
            <span>{{ $item->name ?: '—' }}</span>
          </div>
          <div class="av-field">
            <label>Pricing Model</label>
            <span>{{ $item->pricing_model ?: '—' }}</span>
          </div>
          <div class="av-field">
            <label>Pricing Percentage</label>
            <span>{{ $item->pricing_percentage ? number_format($item->pricing_percentage, 2) . '%' : '—' }}</span>
          </div>
          <div class="av-field">
            <label>Currency</label>
            <span>{{ $item->currency ?: 'INR' }}</span>
          </div>
          <div class="av-field">
            <label>Status</label>
            <span>
              <span class="st {{ $item->is_active ? 'st-green' : 'st-red' }}">
                {{ $item->is_active ? 'Active' : 'Inactive' }}
              </span>
            </span>
          </div>
          <div class="av-field">
            <label>Created</label>
            <span>{{ $item->created_at ? $item->created_at->format('d M Y') : '—' }}</span>
          </div>
          <div class="av-field">
            <label>Last Updated</label>
            <span>{{ $item->updated_at ? $item->updated_at->format('d M Y, h:i A') : '—' }}</span>
          </div>
        </div>
        @if($item->description)
        <div style="margin-top:1.25rem; padding-top:1.25rem; border-top:1px solid var(--border)">
          <div class="av-field">
            <label>Description</label>
            <span style="white-space:pre-line">{{ $item->description }}</span>
          </div>
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
          @if($notes->count())
          <span style="margin-left:.4rem;background:var(--accent);color:#fff;border-radius:20px;padding:.1rem .5rem;font-size:.72rem">{{ $notes->count() }}</span>
          @endif
        </div>
      </div>
      <div class="av-section-body">
        <form method="POST" action="{{ route('admin.crm2.inventory.price-books.notes.store', $item->id) }}">
          @csrf
          <div class="av-note-form">
            <textarea name="content" placeholder="Add a note about this price book..." required></textarea>
            <button type="submit" class="av-btn primary" style="align-self:flex-end;white-space:nowrap">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Add Note
            </button>
          </div>
        </form>
        <div class="av-note-list">
          @forelse($notes as $note)
          <div class="av-note-item" id="note-item-{{ $note->id }}">
            <div class="note-text">{{ $note->content }}</div>
            <div class="note-meta" style="display:flex;justify-content:space-between;align-items:center;">
              <span>{{ $note->user ? $note->user->name : 'Unknown' }} &middot; {{ $note->created_at->diffForHumans() }}</span>
              <button onclick="deleteNote({{ $note->id }}, {{ $item->id }})" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:.8rem;padding:.1rem .3rem;" title="Delete note">&#10006;</button>
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

    {{-- ─── SECTION 3: Products ─── --}}
    <div class="av-section" id="sec-products">
      <div class="av-section-header">
        <div class="av-section-title">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
          Products
          <span style="margin-left:.4rem;background:var(--accent);color:#fff;border-radius:20px;padding:.1rem .5rem;font-size:.72rem" id="prod-count-badge">{{ $priceBookProducts->count() }}</span>
        </div>
        <div class="av-section-actions">
          {{-- Add Product --}}
          <button class="av-btn primary sm" onclick="openSlider('slider-add-product')">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Product
          </button>
          {{-- Import List Price --}}
          <button class="av-btn outline sm" onclick="openSlider('slider-import-price')">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Import List Price
          </button>
          {{-- Edit All --}}
          <button class="av-btn outline sm" id="btn-edit-all" onclick="toggleEditAll()">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit All
          </button>
        </div>
      </div>
      <div class="av-section-body" style="padding:0">
        @if($priceBookProducts->count())
        <form id="edit-all-form" method="POST" action="{{ route('admin.crm2.inventory.price-books.products.update-all', $item->id) }}">
          @csrf @method('PUT')
          <table class="av-table" id="products-table">
            <thead>
              <tr>
                <th>Product Name</th>
                <th>Product Code</th>
                <th>Unit Price ({{ $item->currency ?: 'INR' }})</th>
                <th>List Price</th>
                <th>Discount %</th>
                <th>Category</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($priceBookProducts as $prod)
              <tr data-prod-id="{{ $prod->id }}">
                <td>
                  <a href="{{ route('admin.crm2.inventory.products.show', $prod->id) }}" style="color:var(--accent);font-weight:600" class="view-mode">{{ $prod->name }}</a>
                  <input type="hidden" name="products[{{ $prod->id }}][id]" value="{{ $prod->id }}">
                  <span class="edit-mode" style="display:none;font-weight:600;color:var(--text-primary)">{{ $prod->name }}</span>
                </td>
                <td>
                  <span class="view-mode">{{ $prod->product_code ?: '—' }}</span>
                  <span class="edit-mode" style="display:none">{{ $prod->product_code ?: '—' }}</span>
                </td>
                <td>
                  <span class="view-mode">{{ $prod->unit_price ? number_format($prod->unit_price, 2) : '—' }}</span>
                  <input class="edit-mode" type="number" name="products[{{ $prod->id }}][unit_price]" value="{{ $prod->unit_price }}" step="0.01" min="0" style="display:none;width:100px">
                </td>
                <td>
                  <span class="view-mode">{{ $prod->list_price ? number_format($prod->list_price, 2) : '—' }}</span>
                  <input class="edit-mode" type="number" name="products[{{ $prod->id }}][list_price]" value="{{ $prod->list_price ?? $prod->unit_price }}" step="0.01" min="0" style="display:none;width:100px">
                </td>
                <td>
                  <span class="view-mode">{{ $prod->discount_percentage ? $prod->discount_percentage . '%' : '—' }}</span>
                  <input class="edit-mode" type="number" name="products[{{ $prod->id }}][discount_percentage]" value="{{ $prod->discount_percentage ?? 0 }}" step="0.01" min="0" max="100" style="display:none;width:80px">
                </td>
                <td>
                  <span class="view-mode">{{ $prod->product_category ?: '—' }}</span>
                  <span class="edit-mode" style="display:none">{{ $prod->product_category ?: '—' }}</span>
                </td>
                <td>
                  <div class="td-actions">
                    <a href="{{ route('admin.crm2.inventory.products.show', $prod->id) }}" class="av-btn outline sm view-mode">View</a>
                    <button type="button" class="av-btn danger sm view-mode" onclick="removeProductFromPriceBook({{ $prod->id }}, {{ $item->id }}, this)">Remove</button>
                    <span class="edit-mode" style="display:none">
                      <button type="button" class="av-btn outline sm" onclick="cancelEditAll()">Cancel</button>
                    </span>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <div id="edit-all-footer" style="display:none;padding:.75rem 1.25rem;border-top:1px solid var(--border);display:none;gap:.5rem;justify-content:flex-end">
            <button type="button" class="av-btn outline" onclick="cancelEditAll()">Cancel</button>
            <button type="submit" class="av-btn primary">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              Save All Changes
            </button>
          </div>
        </form>
        @else
        <div class="av-empty" id="products-empty">
          <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
          No products in this price book yet. Use "Add Product" to add products.
        </div>
        @endif
      </div>
    </div>

    {{-- ─── SECTION 4: Attachments ─── --}}
    <div class="av-section" id="sec-attachments">
      <div class="av-section-header">
        <div class="av-section-title">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
          Attachments
          <span style="margin-left:.4rem;background:var(--accent);color:#fff;border-radius:20px;padding:.1rem .5rem;font-size:.72rem" id="attach-count-badge">{{ $attachments->count() }}</span>
        </div>
        <div class="av-section-actions">
          <button class="av-btn primary sm" onclick="document.getElementById('attach-file-input').click()">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Upload File
          </button>
        </div>
      </div>
      <div class="av-section-body">
        {{-- Drop zone --}}
        <div class="av-attach-drop" id="attach-drop-zone" onclick="document.getElementById('attach-file-input').click()">
          <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          <p><strong>Click to upload</strong> or drag and drop files here</p>
          <p style="font-size:.75rem">PDF, DOC, XLS, PNG, JPG — Max 10 MB</p>
          <form id="attach-upload-form" method="POST" action="{{ route('admin.crm2.inventory.price-books.attachments.store', $item->id) }}" enctype="multipart/form-data">
            @csrf
            <input type="file" id="attach-file-input" name="attachment" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip,.txt" onchange="submitAttachmentForm(this)">
          </form>
        </div>
        {{-- Attachment list --}}
        <div class="av-attach-list" id="attach-list">
          @forelse($attachments as $att)
          <div class="av-attach-item" id="attach-item-{{ $att->id }}">
            <div class="av-attach-icon">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div class="av-attach-info">
              <div class="av-attach-name">{{ $att->original_name }}</div>
              <div class="av-attach-meta">{{ $att->created_at->format('d M Y') }} &middot; {{ $att->human_size }}</div>
            </div>
            <a href="{{ route('admin.crm2.inventory.price-books.attachments.download', [$item->id, $att->id]) }}" class="av-btn outline sm" download>
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              Download
            </a>
            <button class="av-attach-del" onclick="deleteAttachment({{ $att->id }}, {{ $item->id }}, this)" title="Delete">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
            </button>
          </div>
          @empty
          <div class="av-empty" id="attach-empty-state">
            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
            No attachments yet. Upload files using the button above.
          </div>
          @endforelse
        </div>
      </div>
    </div>

  </div>{{-- end av-main --}}

  {{-- ══ SECTION NAVIGATOR (sticky right sidebar) ══ --}}
  <div class="av-nav">
    <div class="av-nav-card">
      <div class="av-nav-title">Sections</div>
      <a class="av-nav-item active" href="#sec-info" onclick="setActive(this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>
        Price Book Info
      </a>
      <a class="av-nav-item" href="#sec-notes" onclick="setActive(this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Notes
      </a>
      <a class="av-nav-item" href="#sec-products" onclick="setActive(this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
        Products
      </a>
      <a class="av-nav-item" href="#sec-attachments" onclick="setActive(this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
        Attachments
      </a>
    </div>
  </div>

</div>{{-- end av-layout --}}

{{-- ══════════════════════════════════════════════════════
     ADD PRODUCT SLIDER
══════════════════════════════════════════════════════ --}}
<div class="av-slider-overlay" id="overlay-slider-add-product" onclick="closeSlider('slider-add-product')"></div>
<div class="av-slider" id="slider-add-product">
  <div class="av-slider-head">
    <h3>Add Product to Price Book</h3>
    <button class="av-slider-close" onclick="closeSlider('slider-add-product')">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <div class="av-slider-search">
    <input type="text" placeholder="Search products..." oninput="filterProdSlider(this)">
  </div>
  <div class="av-slider-list" id="slider-prod-list">
    @foreach($allProducts as $p)
    <div class="av-prod-item {{ in_array($p->id, $priceBookProducts->pluck('id')->toArray()) ? 'selected' : '' }}"
         id="prod-slider-item-{{ $p->id }}"
         onclick="toggleProductInPriceBook({{ $p->id }}, {{ $item->id }}, this)">
      <img class="av-prod-img"
           src="{{ $p->image ? asset('storage/'.$p->image) : '' }}"
           onerror="this.style.display='none'"
           alt="">
      <div class="av-prod-info">
        <div class="av-prod-name">{{ $p->name }}</div>
        <div class="av-prod-price">{{ $p->product_code ? $p->product_code.' · ' : '' }}{{ $p->unit_price ? '₹'.number_format($p->unit_price,2) : 'No price set' }}</div>
      </div>
      <div class="av-prod-check">
        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
    </div>
    @endforeach
    @if($allProducts->isEmpty())
    <div class="av-empty">No products found. <a href="{{ route('admin.crm2.inventory.products.create') }}" style="color:var(--accent)">Create a product</a></div>
    @endif
  </div>
  <div class="av-slider-foot">
    <button class="av-btn outline" style="flex:1" onclick="closeSlider('slider-add-product')">Done</button>
  </div>
</div>

{{-- ══════════════════════════════════════════════════════
     IMPORT LIST PRICE SLIDER
══════════════════════════════════════════════════════ --}}
<div class="av-slider-overlay" id="overlay-slider-import-price" onclick="closeSlider('slider-import-price')"></div>
<div class="av-slider" id="slider-import-price">
  <div class="av-slider-head">
    <h3>Import List Price</h3>
    <button class="av-slider-close" onclick="closeSlider('slider-import-price')">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <div class="av-import-body">
    <div class="av-import-step">
      <h4>Step 1 — Download Template</h4>
      <p>Download the CSV template, fill in your product prices, then upload below.</p>
      <a href="{{ route('admin.crm2.inventory.price-books.import-template', $item->id) }}" class="av-btn outline" style="margin-bottom:.5rem">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Download CSV Template
      </a>
    </div>
    <div class="av-import-step">
      <h4>Step 2 — Upload Filled CSV</h4>
      <p>Upload the completed CSV file with product codes and list prices.</p>
      <form method="POST" action="{{ route('admin.crm2.inventory.price-books.import', $item->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="av-import-drop" onclick="document.getElementById('import-csv-input').click()">
          <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          <p><strong>Click to select CSV file</strong></p>
          <input type="file" id="import-csv-input" name="csv_file" accept=".csv,.xlsx" onchange="showImportFileName(this)">
        </div>
        <p id="import-file-name" style="font-size:.82rem;color:var(--accent);margin:.5rem 0 0;display:none"></p>
        <div style="margin-top:1rem;display:flex;gap:.5rem">
          <button type="submit" class="av-btn primary" style="flex:1">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Import Prices
          </button>
          <button type="button" class="av-btn outline" onclick="closeSlider('slider-import-price')">Cancel</button>
        </div>
      </form>
    </div>
    <div class="av-import-step" style="background:rgba(var(--accent-rgb,99,102,241),.05);border-radius:8px;padding:1rem">
      <h4 style="display:flex;align-items:center;gap:.4rem">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        CSV Format
      </h4>
      <p style="font-size:.78rem;color:var(--text-muted);margin:0">Required columns: <strong>product_code</strong>, <strong>list_price</strong>. Optional: <strong>discount_percentage</strong>, <strong>unit_price</strong></p>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════════
     TOAST NOTIFICATION
══════════════════════════════════════════════════════ --}}
<div class="av-toast" id="av-toast">
  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
  <span id="av-toast-msg"></span>
</div>

<script>
/* ── Section Navigator ── */
function setActive(el) {
  document.querySelectorAll('.av-nav-item').forEach(i => i.classList.remove('active'));
  el.classList.add('active');
}
/* Scroll spy */
window.addEventListener('scroll', () => {
  const sections = document.querySelectorAll('.av-section[id]');
  let current = '';
  sections.forEach(s => {
    if (s.getBoundingClientRect().top <= 120) current = s.id;
  });
  if (current) {
    document.querySelectorAll('.av-nav-item').forEach(i => {
      i.classList.toggle('active', i.getAttribute('href') === '#' + current);
    });
  }
});

/* ── Slider open/close ── */
function openSlider(id) {
  document.getElementById('slider-' + id.replace('slider-', '')).classList.add('open');
  document.getElementById('overlay-slider-' + id.replace('slider-', '')).classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeSlider(id) {
  const sid = id.startsWith('slider-') ? id : 'slider-' + id;
  const oid = 'overlay-' + sid;
  document.getElementById(sid)?.classList.remove('open');
  document.getElementById(oid)?.classList.remove('open');
  document.body.style.overflow = '';
}

/* ── Toast ── */
function showToast(msg, type = 'success') {
  const t = document.getElementById('av-toast');
  document.getElementById('av-toast-msg').textContent = msg;
  t.className = 'av-toast show ' + type;
  setTimeout(() => t.classList.remove('show'), 3500);
}

/* ── Product slider search filter ── */
function filterProdSlider(input) {
  const q = input.value.toLowerCase();
  document.querySelectorAll('#slider-prod-list .av-prod-item').forEach(item => {
    const name = item.querySelector('.av-prod-name')?.textContent.toLowerCase() || '';
    const code = item.querySelector('.av-prod-price')?.textContent.toLowerCase() || '';
    item.style.display = (name.includes(q) || code.includes(q)) ? '' : 'none';
  });
}

/* ── Toggle product in price book (AJAX) ── */
function toggleProductInPriceBook(productId, priceBookId, el) {
  const isSelected = el.classList.contains('selected');
  const action = isSelected ? 'remove' : 'add';
  const url = `/{{ auth()->user()->username ?? 'admin' }}/crm2/inventory/price-books/${priceBookId}/products/${action}`;
  fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '{{ csrf_token() }}' },
    body: JSON.stringify({ product_id: productId })
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      el.classList.toggle('selected', action === 'add');
      showToast(action === 'add' ? 'Product added to price book.' : 'Product removed from price book.');
      updateProductCount(data.count);
      if (action === 'remove') {
        document.querySelector(`tr[data-prod-id="${productId}"]`)?.remove();
      } else if (data.product) {
        appendProductRow(data.product, priceBookId);
      }
    } else {
      showToast(data.message || 'Something went wrong.', 'error');
    }
  })
  .catch(() => showToast('Network error. Please try again.', 'error'));
}

function updateProductCount(count) {
  const badge = document.getElementById('prod-count-badge');
  if (badge) badge.textContent = count;
}

function appendProductRow(prod, pbId) {
  const tbody = document.querySelector('#products-table tbody');
  if (!tbody) { location.reload(); return; }
  const tr = document.createElement('tr');
  tr.setAttribute('data-prod-id', prod.id);
  tr.innerHTML = `
    <td><a href="/{{ auth()->user()->username ?? 'admin' }}/crm2/inventory/products/${prod.id}" style="color:var(--accent);font-weight:600" class="view-mode">${prod.name}</a></td>
    <td><span class="view-mode">${prod.product_code || '—'}</span></td>
    <td><span class="view-mode">${prod.unit_price ? parseFloat(prod.unit_price).toFixed(2) : '—'}</span></td>
    <td><span class="view-mode">—</span></td>
    <td><span class="view-mode">—</span></td>
    <td><span class="view-mode">${prod.product_category || '—'}</span></td>
    <td><div class="td-actions">
      <a href="/{{ auth()->user()->username ?? 'admin' }}/crm2/inventory/products/${prod.id}" class="av-btn outline sm view-mode">View</a>
      <button type="button" class="av-btn danger sm view-mode" onclick="removeProductFromPriceBook(${prod.id},${pbId},this)">Remove</button>
    </div></td>`;
  tbody.appendChild(tr);
  document.getElementById('products-empty')?.remove();
}

/* ── Remove product from price book ── */
function removeProductFromPriceBook(productId, priceBookId, btn) {
  if (!confirm('Remove this product from the price book?')) return;
  const url = `/{{ auth()->user()->username ?? 'admin' }}/crm2/inventory/price-books/${priceBookId}/products/remove`;
  fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({ product_id: productId })
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      btn.closest('tr')?.remove();
      updateProductCount(data.count);
      const sliderItem = document.getElementById('prod-slider-item-' + productId);
      if (sliderItem) sliderItem.classList.remove('selected');
      showToast('Product removed from price book.');
    } else {
      showToast(data.message || 'Could not remove product.', 'error');
    }
  })
  .catch(() => showToast('Network error.', 'error'));
}

/* ── Edit All toggle ── */
let editAllActive = false;
function toggleEditAll() {
  editAllActive = !editAllActive;
  document.querySelectorAll('.view-mode').forEach(el => el.style.display = editAllActive ? 'none' : '');
  document.querySelectorAll('.edit-mode').forEach(el => el.style.display = editAllActive ? '' : 'none');
  const footer = document.getElementById('edit-all-footer');
  if (footer) footer.style.display = editAllActive ? 'flex' : 'none';
  const btn = document.getElementById('btn-edit-all');
  if (btn) btn.classList.toggle('primary', editAllActive);
}
function cancelEditAll() {
  editAllActive = false;
  document.querySelectorAll('.view-mode').forEach(el => el.style.display = '');
  document.querySelectorAll('.edit-mode').forEach(el => el.style.display = 'none');
  const footer = document.getElementById('edit-all-footer');
  if (footer) footer.style.display = 'none';
  document.getElementById('btn-edit-all')?.classList.remove('primary');
}

/* ── Attachment drag-and-drop ── */
const dropZone = document.getElementById('attach-drop-zone');
if (dropZone) {
  ['dragenter','dragover'].forEach(evt => dropZone.addEventListener(evt, e => { e.preventDefault(); dropZone.classList.add('dragover'); }));
  ['dragleave','drop'].forEach(evt => dropZone.addEventListener(evt, e => { e.preventDefault(); dropZone.classList.remove('dragover'); }));
  dropZone.addEventListener('drop', e => {
    const file = e.dataTransfer.files[0];
    if (file) {
      const input = document.getElementById('attach-file-input');
      const dt = new DataTransfer();
      dt.items.add(file);
      input.files = dt.files;
      submitAttachmentForm(input);
    }
  });
}

function submitAttachmentForm(input) {
  if (!input.files[0]) return;
  const form = document.getElementById('attach-upload-form');
  showToast('Uploading...', 'success');
  form.submit();
}

/* ── Delete note (AJAX) ── */
function deleteNote(noteId, pbId) {
  if (!confirm('Delete this note?')) return;
  fetch(`/{{ auth()->user()->username ?? 'admin' }}/crm2/inventory/price-books/${pbId}/notes/${noteId}`, {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      document.getElementById('note-item-' + noteId)?.remove();
      showToast('Note deleted.');
    } else { showToast('Could not delete note.', 'error'); }
  })
  .catch(() => showToast('Network error.', 'error'));
}

/* ── Delete attachment (AJAX) ── */
function deleteAttachment(attId, pbId, btn) {
  if (!confirm('Delete this attachment?')) return;
  fetch(`/{{ auth()->user()->username ?? 'admin' }}/crm2/inventory/price-books/${pbId}/attachments/${attId}`, {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      document.getElementById('attach-item-' + attId)?.remove();
      const badge = document.getElementById('attach-count-badge');
      if (badge) badge.textContent = parseInt(badge.textContent) - 1;
      showToast('Attachment deleted.');
    } else {
      showToast('Could not delete attachment.', 'error');
    }
  })
  .catch(() => showToast('Network error.', 'error'));
}

/* ── Import CSV file name display ── */
function showImportFileName(input) {
  const p = document.getElementById('import-file-name');
  if (p && input.files[0]) {
    p.textContent = '✓ ' + input.files[0].name;
    p.style.display = 'block';
  }
}

/* ── Flash messages ── */
@if(session('success'))
  document.addEventListener('DOMContentLoaded', () => showToast('{{ session('success') }}', 'success'));
@endif
@if(session('error'))
  document.addEventListener('DOMContentLoaded', () => showToast('{{ session('error') }}', 'error'));
@endif
</script>
@endsection
