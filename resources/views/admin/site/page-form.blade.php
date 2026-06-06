@extends('layouts.admin')
@section('title', isset($page) && $page ? 'Edit Page: ' . $page->title : 'New Page')
@section('page-title', isset($page) && $page ? 'Edit Page' : 'New Page')

@section('content')
<style>
.pf-wrap { display:grid; grid-template-columns:1fr 320px; gap:1.5rem; align-items:start; }
@media(max-width:900px){ .pf-wrap{grid-template-columns:1fr;} }
.pf-card { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; padding:1.5rem; margin-bottom:1.25rem; }
.pf-section-title { font-size:0.72rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem; }
.form-label { font-size:0.8rem; font-weight:600; color:var(--text-secondary); display:block; margin-bottom:0.35rem; }
.form-control { width:100%; padding:0.6rem 0.85rem; background:var(--bg-secondary); border:1px solid var(--border); border-radius:8px; color:var(--text-primary); font-size:0.875rem; outline:none; box-sizing:border-box; }
.form-control:focus { border-color:var(--accent,#6366f1); }
.btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.55rem 1.1rem; border-radius:8px; font-size:0.875rem; font-weight:600; cursor:pointer; border:none; text-decoration:none; }
.btn-primary { background:var(--accent,#6366f1); color:#fff; }
.btn-outline { background:transparent; border:1px solid var(--border); color:var(--text-primary); }
.btn-sm { padding:0.3rem 0.7rem; font-size:0.75rem; }
.btn-danger { background:rgba(239,68,68,0.1); color:#ef4444; border:1px solid rgba(239,68,68,0.3); }
.section-card { background:var(--bg-secondary); border:1px solid var(--border); border-radius:10px; margin-bottom:0.75rem; overflow:hidden; transition:border-color .2s; }
.section-card.enabled { border-color:var(--accent,#6366f1); }
.section-header { display:flex; align-items:center; gap:0.75rem; padding:0.85rem 1rem; cursor:pointer; user-select:none; }
.section-icon { width:32px; height:32px; border-radius:8px; background:var(--bg-card); display:flex; align-items:center; justify-content:center; font-size:0.85rem; color:var(--accent,#6366f1); flex-shrink:0; }
.section-label { flex:1; font-size:0.875rem; font-weight:600; color:var(--text-primary); }
.section-badge { font-size:0.68rem; font-weight:700; padding:0.15rem 0.5rem; border-radius:20px; }
.section-badge.on { background:rgba(99,102,241,0.15); color:var(--accent,#6366f1); }
.section-badge.off { background:var(--bg-hover); color:var(--text-muted); }
.section-toggle { position:relative; width:40px; height:22px; flex-shrink:0; }
.section-toggle input { opacity:0; width:0; height:0; }
.toggle-slider { position:absolute; cursor:pointer; inset:0; background:#ccc; border-radius:22px; transition:.3s; }
.toggle-slider:before { content:''; position:absolute; width:16px; height:16px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.3s; }
.section-toggle input:checked + .toggle-slider { background:var(--accent,#6366f1); }
.section-toggle input:checked + .toggle-slider:before { transform:translateX(18px); }
.section-expand-btn { color:var(--text-muted); font-size:0.75rem; transition:transform .2s; }
.section-body { padding:0 1rem 1rem; display:none; }
.section-body.open { display:block; }
.section-field { margin-bottom:0.75rem; }
.section-field label { font-size:0.75rem; font-weight:600; color:var(--text-muted); display:block; margin-bottom:0.25rem; }
.section-field input, .section-field textarea, .section-field select { width:100%; padding:0.5rem 0.75rem; background:var(--bg-card); border:1px solid var(--border); border-radius:7px; color:var(--text-primary); font-size:0.8rem; box-sizing:border-box; }
#editor-container { background:var(--bg-secondary); border:1px solid var(--border); border-radius:0 0 8px 8px; min-height:300px; color:var(--text-primary); }
.ql-toolbar { background:var(--bg-card); border:1px solid var(--border); border-radius:8px 8px 0 0; }
.info-banner { background:rgba(99,102,241,0.08); border:1px solid rgba(99,102,241,0.2); border-radius:8px; padding:0.75rem 1rem; margin-bottom:1.25rem; font-size:0.82rem; color:var(--text-secondary); display:flex; gap:0.75rem; align-items:flex-start; }
.info-banner i { color:#818cf8; margin-top:0.1rem; flex-shrink:0; }
/* Items editor */
.items-editor { border:1px solid var(--border); border-radius:8px; overflow:hidden; margin-top:0.5rem; }
.item-row { background:var(--bg-card); border-bottom:1px solid var(--border); padding:0.75rem; position:relative; }
.item-row:last-child { border-bottom:none; }
.item-row-grid { display:grid; gap:0.5rem; }
.item-row-grid-2 { grid-template-columns:1fr 1fr; }
.item-row-grid-3 { grid-template-columns:0.5fr 1fr 1fr; }
.item-row-grid-4 { grid-template-columns:0.4fr 1fr 1fr 0.6fr; }
.item-remove { position:absolute; top:0.5rem; right:0.5rem; background:rgba(239,68,68,0.1); color:#ef4444; border:none; border-radius:5px; padding:0.2rem 0.45rem; font-size:0.7rem; cursor:pointer; }
.item-remove:hover { background:rgba(239,68,68,0.2); }
.add-item-btn { display:flex; align-items:center; justify-content:center; gap:0.4rem; width:100%; padding:0.6rem; background:var(--bg-secondary); border:1px dashed var(--border); border-radius:8px; color:var(--text-muted); font-size:0.8rem; font-weight:600; cursor:pointer; margin-top:0.5rem; }
.add-item-btn:hover { border-color:var(--accent,#6366f1); color:var(--accent,#6366f1); }
.items-section-label { font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.4rem; margin-top:0.75rem; display:flex; align-items:center; gap:0.4rem; }
</style>

<div style="margin-bottom:1.5rem;">
    <a href="{{ route('admin.site.pages') }}" style="color:var(--text-muted);text-decoration:none;font-size:0.85rem;"><i class="fas fa-arrow-left"></i> Page Manager</a>
    <h1 style="font-size:1.6rem;font-weight:800;margin:0.25rem 0 0;">
        {{ isset($page) && $page ? 'Edit: ' . $page->title : 'New Custom Page' }}
    </h1>
    @if(isset($page) && $page && $page->page_type)
        <span style="font-size:0.78rem;color:var(--text-muted);">Theme page &mdash; {{ ucfirst($page->page_type) }}</span>
    @endif
</div>

@if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:0.875rem;">
        <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
    </div>
@endif
@if(session('success'))
    <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);color:#22c55e;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:0.875rem;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<form method="POST"
      action="{{ isset($page) && $page ? route('admin.site.pages.update', $page) : route('admin.site.pages.store') }}"
      id="pageForm" enctype="multipart/form-data">
    @csrf
    @if(isset($page) && $page)
        @method('PUT')
    @endif

    <div class="pf-wrap">
        <div>
            {{-- Title & Slug --}}
            <div class="pf-card">
                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label">Page Title <span style="color:#ef4444">*</span></label>
                    <input type="text" name="title" id="pageTitle" class="form-control"
                           value="{{ old('title', $page?->title ?? '') }}" required placeholder="e.g. Home, About, Contact">
                </div>
                <div class="form-group">
                    <label class="form-label">URL Slug</label>
                    <div style="display:flex;align-items:center;gap:0.5rem;">
                        <span style="font-size:0.8rem;color:var(--text-muted);white-space:nowrap;">/</span>
                        <input type="text" name="slug" id="pageSlug" class="form-control"
                               value="{{ old('slug', $page?->slug ?? '') }}" placeholder="auto-generated from title"
                               @if(isset($page) && $page && $page->page_type) readonly style="opacity:0.6;cursor:not-allowed;" @endif>
                    </div>
                    @if(isset($page) && $page && $page->page_type)
                        <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.25rem;"><i class="fas fa-lock"></i> Slug is locked for system pages.</p>
                    @endif
                </div>
            </div>

            {{-- Theme page: sections --}}
            @if(isset($page) && $page && $page->page_type && isset($sections) && count($sections))
                <div class="pf-card">
                    <div class="pf-section-title"><i class="fas fa-layer-group"></i> Page Sections</div>
                    <div class="info-banner">
                        <i class="fas fa-info-circle"></i>
                        <span>Toggle sections on or off to control what appears on this page. Expand a section to customise its content. All changes are saved when you click <strong>Save Page</strong>.</span>
                    </div>
                    @foreach($sections as $section)
                        @php
                            $key     = $section['key'];
                            $enabled = $section['enabled'] ?? false;
                            $data    = $section['data'] ?? [];
                        @endphp
                        <div class="section-card {{ $enabled ? 'enabled' : '' }}" id="sc-{{ $key }}">
                            <div class="section-header" onclick="toggleSection('{{ $key }}')">
                                <div class="section-icon"><i class="{{ $section['icon'] ?? 'fas fa-puzzle-piece' }}"></i></div>
                                <span class="section-label">{{ $section['label'] }}</span>
                                <span class="section-badge {{ $enabled ? 'on' : 'off' }}" id="badge-{{ $key }}">{{ $enabled ? 'ON' : 'OFF' }}</span>
                                <label class="section-toggle" onclick="event.stopPropagation()">
                                    <input type="checkbox" name="sections[{{ $key }}][enabled]" value="1"
                                           id="toggle-{{ $key }}" {{ $enabled ? 'checked' : '' }}
                                           onchange="onToggleChange('{{ $key }}', this.checked)">
                                    <span class="toggle-slider"></span>
                                </label>
                                <i class="fas fa-chevron-down section-expand-btn" id="chevron-{{ $key }}"></i>
                            </div>
                            <div class="section-body" id="body-{{ $key }}">

                                {{-- Standard text fields --}}
                                @if(array_key_exists('heading', $data))
                                <div class="section-field"><label>Section Heading</label>
                                    <input type="text" name="sections[{{ $key }}][data][heading]"
                                           value="{{ old("sections.{$key}.data.heading", $data['heading'] ?? '') }}"
                                           placeholder="Leave blank to use default"></div>
                                @endif

                                @if(array_key_exists('subheading', $data))
                                <div class="section-field"><label>Sub-heading / Tagline</label>
                                    <input type="text" name="sections[{{ $key }}][data][subheading]"
                                           value="{{ old("sections.{$key}.data.subheading", $data['subheading'] ?? '') }}"
                                           placeholder="Optional sub-heading"></div>
                                @endif

                                @if(array_key_exists('badge', $data))
                                <div class="section-field"><label>Badge / Label (above heading)</label>
                                    <input type="text" name="sections[{{ $key }}][data][badge]"
                                           value="{{ old("sections.{$key}.data.badge", $data['badge'] ?? '') }}"
                                           placeholder="e.g. Welcome, New, Featured"></div>
                                @endif

                                @if(array_key_exists('text', $data))
                                <div class="section-field"><label>Body Text</label>
                                    <textarea name="sections[{{ $key }}][data][text]" rows="3"
                                              placeholder="Optional body text">{{ old("sections.{$key}.data.text", $data['text'] ?? '') }}</textarea></div>
                                @endif

                                @if(array_key_exists('image', $data))
                                <div class="section-field"><label>Image URL</label>
                                    <input type="text" name="sections[{{ $key }}][data][image]"
                                           value="{{ old("sections.{$key}.data.image", $data['image'] ?? '') }}"
                                           placeholder="https://... or /storage/..."></div>
                                @endif

                                @if(array_key_exists('cta_text', $data))
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                                    <div class="section-field"><label>CTA Button Text</label>
                                        <input type="text" name="sections[{{ $key }}][data][cta_text]"
                                               value="{{ old("sections.{$key}.data.cta_text", $data['cta_text'] ?? '') }}"
                                               placeholder="Get Started"></div>
                                    <div class="section-field"><label>CTA Button URL</label>
                                        <input type="text" name="sections[{{ $key }}][data][cta_url]"
                                               value="{{ old("sections.{$key}.data.cta_url", $data['cta_url'] ?? '') }}"
                                               placeholder="/contact"></div>
                                </div>
                                @endif

                                @if(array_key_exists('button_text', $data))
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                                    <div class="section-field"><label>Button Text</label>
                                        <input type="text" name="sections[{{ $key }}][data][button_text]"
                                               value="{{ old("sections.{$key}.data.button_text", $data['button_text'] ?? '') }}"
                                               placeholder="Contact Me"></div>
                                    <div class="section-field"><label>Button URL</label>
                                        <input type="text" name="sections[{{ $key }}][data][button_url]"
                                               value="{{ old("sections.{$key}.data.button_url", $data['button_url'] ?? '') }}"
                                               placeholder="/contact"></div>
                                </div>
                                @endif

                                @if(array_key_exists('count', $data))
                                <div class="section-field"><label>Number of items to show</label>
                                    <input type="number" name="sections[{{ $key }}][data][count]" min="1" max="20"
                                           value="{{ old("sections.{$key}.data.count", $data['count'] ?? 3) }}"></div>
                                @endif

                                @if(array_key_exists('posts_per_page', $data))
                                <div class="section-field"><label>Posts per page</label>
                                    <input type="number" name="sections[{{ $key }}][data][posts_per_page]" min="3" max="50"
                                           value="{{ old("sections.{$key}.data.posts_per_page", $data['posts_per_page'] ?? 9) }}"></div>
                                @endif

                                @if(array_key_exists('products_per_page', $data))
                                <div class="section-field"><label>Products per page</label>
                                    <input type="number" name="sections[{{ $key }}][data][products_per_page]" min="4" max="48"
                                           value="{{ old("sections.{$key}.data.products_per_page", $data['products_per_page'] ?? 12) }}"></div>
                                @endif

                                @if(array_key_exists('bg_color', $data))
                                <div class="section-field"><label>Background Colour</label>
                                    <input type="text" name="sections[{{ $key }}][data][bg_color]"
                                           value="{{ old("sections.{$key}.data.bg_color", $data['bg_color'] ?? '') }}"
                                           placeholder="#ffffff"></div>
                                @endif

                                @if(array_key_exists('embed_url', $data))
                                <div class="section-field"><label>Google Maps Embed URL</label>
                                    <input type="text" name="sections[{{ $key }}][data][embed_url]"
                                           value="{{ old("sections.{$key}.data.embed_url", $data['embed_url'] ?? '') }}"
                                           placeholder="https://www.google.com/maps/embed?..."></div>
                                @endif

                                @if(array_key_exists('email', $data))
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                                    <div class="section-field"><label>Contact Email</label>
                                        <input type="email" name="sections[{{ $key }}][data][email]"
                                               value="{{ old("sections.{$key}.data.email", $data['email'] ?? '') }}"
                                               placeholder="hello@example.com"></div>
                                    <div class="section-field"><label>Phone</label>
                                        <input type="text" name="sections[{{ $key }}][data][phone]"
                                               value="{{ old("sections.{$key}.data.phone", $data['phone'] ?? '') }}"
                                               placeholder="+91 ..."></div>
                                </div>
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                                    <div class="section-field"><label>Address</label>
                                        <input type="text" name="sections[{{ $key }}][data][address]"
                                               value="{{ old("sections.{$key}.data.address", $data['address'] ?? '') }}"
                                               placeholder="City, Country"></div>
                                    <div class="section-field"><label>Working Hours</label>
                                        <input type="text" name="sections[{{ $key }}][data][working_hours]"
                                               value="{{ old("sections.{$key}.data.working_hours", $data['working_hours'] ?? '') }}"
                                               placeholder="Mon-Fri 9am-6pm"></div>
                                </div>
                                @endif

                                @if(array_key_exists('layout', $data))
                                <div class="section-field"><label>Layout</label>
                                    <select name="sections[{{ $key }}][data][layout]">
                                        <option value="grid"  {{ ($data['layout'] ?? 'grid') === 'grid'  ? 'selected' : '' }}>Grid</option>
                                        <option value="list"  {{ ($data['layout'] ?? '')     === 'list'  ? 'selected' : '' }}>List</option>
                                        <option value="cards" {{ ($data['layout'] ?? '')     === 'cards' ? 'selected' : '' }}>Cards</option>
                                    </select></div>
                                @endif

                                @if(array_key_exists('columns', $data))
                                <div class="section-field"><label>Columns</label>
                                    <select name="sections[{{ $key }}][data][columns]">
                                        <option value="2" {{ ($data['columns'] ?? 3) == 2 ? 'selected' : '' }}>2 Columns</option>
                                        <option value="3" {{ ($data['columns'] ?? 3) == 3 ? 'selected' : '' }}>3 Columns</option>
                                        <option value="4" {{ ($data['columns'] ?? 3) == 4 ? 'selected' : '' }}>4 Columns</option>
                                    </select></div>
                                @endif

                                {{-- ── FOLLOWERS section ── --}}
                                @if(array_key_exists('total', $data))
                                <div class="items-section-label"><i class="fas fa-users"></i> Follower Counts</div>
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;">
                                    <div class="section-field"><label>Total Followers</label>
                                        <input type="text" name="sections[{{ $key }}][data][total]"
                                               value="{{ old("sections.{$key}.data.total", $data['total'] ?? '') }}"
                                               placeholder="e.g. 2.5M"></div>
                                    <div class="section-field"><label>Instagram</label>
                                        <input type="text" name="sections[{{ $key }}][data][instagram]"
                                               value="{{ old("sections.{$key}.data.instagram", $data['instagram'] ?? '') }}"
                                               placeholder="e.g. 1.2M"></div>
                                    <div class="section-field"><label>YouTube</label>
                                        <input type="text" name="sections[{{ $key }}][data][youtube]"
                                               value="{{ old("sections.{$key}.data.youtube", $data['youtube'] ?? '') }}"
                                               placeholder="e.g. 800K"></div>
                                    <div class="section-field"><label>Twitter / X</label>
                                        <input type="text" name="sections[{{ $key }}][data][twitter]"
                                               value="{{ old("sections.{$key}.data.twitter", $data['twitter'] ?? '') }}"
                                               placeholder="e.g. 300K"></div>
                                    <div class="section-field"><label>TikTok</label>
                                        <input type="text" name="sections[{{ $key }}][data][tiktok]"
                                               value="{{ old("sections.{$key}.data.tiktok", $data['tiktok'] ?? '') }}"
                                               placeholder="e.g. 500K"></div>
                                </div>
                                @endif

                                {{-- ── ITEMS arrays ── --}}
                                @if(isset($data['items']) && is_array($data['items']))
                                @php
                                    $items       = $data['items'];
                                    $firstItem   = $items[0] ?? [];
                                    $hasIcon     = array_key_exists('icon', $firstItem);
                                    $hasTitle    = array_key_exists('title', $firstItem) || array_key_exists('name', $firstItem);
                                    $hasText     = array_key_exists('text', $firstItem) || array_key_exists('description', $firstItem);
                                    $hasValue    = array_key_exists('value', $firstItem);
                                    $hasLabel    = array_key_exists('label', $firstItem);
                                    $hasRole     = array_key_exists('role', $firstItem);
                                    $hasUrl      = array_key_exists('url', $firstItem);
                                    $hasYear     = array_key_exists('year', $firstItem);
                                    $hasStep     = array_key_exists('step', $firstItem);
                                    $hasPrice    = array_key_exists('price', $firstItem);
                                    $hasAvatar   = array_key_exists('avatar', $firstItem);
                                    $hasCategory = array_key_exists('category', $firstItem);
                                    $hasImage    = array_key_exists('image', $firstItem);
                                    $hasFeatures = array_key_exists('features', $firstItem);
                                    $hasPeriod   = array_key_exists('period', $firstItem);
                                    $itemType    = $key; // use section key as type hint
                                @endphp
                                <div class="items-section-label"><i class="fas fa-list"></i> Items
                                    <span style="font-size:0.65rem;font-weight:400;color:var(--text-muted);">(drag to reorder coming soon)</span>
                                </div>
                                <div class="items-editor" id="items-{{ $key }}">
                                    @foreach($items as $i => $item)
                                    <div class="item-row" id="item-{{ $key }}-{{ $i }}">
                                        <button type="button" class="item-remove" onclick="removeItem('{{ $key }}', {{ $i }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @if($hasStep)
                                        <div class="item-row-grid item-row-grid-4" style="grid-template-columns:0.3fr 1fr 2fr;">
                                            <div class="section-field"><label>Step #</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][step]"
                                                       value="{{ $item['step'] ?? ($i+1) }}" placeholder="{{ $i+1 }}"></div>
                                            <div class="section-field"><label>Title</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][title]"
                                                       value="{{ $item['title'] ?? '' }}" placeholder="Step title"></div>
                                            <div class="section-field"><label>Description</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][text]"
                                                       value="{{ $item['text'] ?? '' }}" placeholder="Brief description"></div>
                                        </div>
                                        @elseif($hasValue && $hasLabel)
                                        {{-- Stats items --}}
                                        <div class="item-row-grid item-row-grid-3" style="grid-template-columns:0.5fr 1fr 1fr;">
                                            <div class="section-field"><label>Icon / Emoji</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][icon]"
                                                       value="{{ $item['icon'] ?? '' }}" placeholder="🏆"></div>
                                            <div class="section-field"><label>Value / Number</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][value]"
                                                       value="{{ $item['value'] ?? '' }}" placeholder="e.g. 10+, 500, 98%"></div>
                                            <div class="section-field"><label>Label</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][label]"
                                                       value="{{ $item['label'] ?? '' }}" placeholder="Years Experience"></div>
                                        </div>
                                        @elseif($hasRole || $hasAvatar)
                                        {{-- Testimonials --}}
                                        <div class="item-row-grid item-row-grid-2">
                                            <div class="section-field"><label>Name</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][name]"
                                                       value="{{ $item['name'] ?? '' }}" placeholder="Client name"></div>
                                            <div class="section-field"><label>Role / Company</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][role]"
                                                       value="{{ $item['role'] ?? '' }}" placeholder="CEO, Company"></div>
                                        </div>
                                        <div class="section-field"><label>Testimonial Text</label>
                                            <textarea name="sections[{{ $key }}][data][items][{{ $i }}][text]" rows="2"
                                                      placeholder="What they said...">{{ $item['text'] ?? '' }}</textarea></div>
                                        <div class="section-field"><label>Avatar URL (optional)</label>
                                            <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][avatar]"
                                                   value="{{ $item['avatar'] ?? '' }}" placeholder="https://..."></div>
                                        @elseif($hasPrice && $hasFeatures)
                                        {{-- Pricing items --}}
                                        <div class="item-row-grid item-row-grid-3">
                                            <div class="section-field"><label>Plan Name</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][name]"
                                                       value="{{ $item['name'] ?? '' }}" placeholder="Basic"></div>
                                            <div class="section-field"><label>Price</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][price]"
                                                       value="{{ $item['price'] ?? '' }}" placeholder="₹999"></div>
                                            <div class="section-field"><label>Period</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][period]"
                                                       value="{{ $item['period'] ?? '/month' }}" placeholder="/month"></div>
                                        </div>
                                        <div class="section-field"><label>Features (one per line)</label>
                                            <textarea name="sections[{{ $key }}][data][items][{{ $i }}][features_text]" rows="3"
                                                      placeholder="Feature 1&#10;Feature 2&#10;Feature 3">{{ is_array($item['features'] ?? '') ? implode("\n", $item['features']) : ($item['features_text'] ?? '') }}</textarea></div>
                                        @elseif($hasCategory || $hasImage)
                                        {{-- Portfolio/Ventures items --}}
                                        <div class="item-row-grid item-row-grid-2">
                                            <div class="section-field"><label>Icon / Emoji</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][icon]"
                                                       value="{{ $item['icon'] ?? '' }}" placeholder="🚀"></div>
                                            <div class="section-field"><label>Category / Tag</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][category]"
                                                       value="{{ $item['category'] ?? '' }}" placeholder="Web, Design, etc."></div>
                                        </div>
                                        <div class="section-field"><label>Title</label>
                                            <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][title]"
                                                   value="{{ $item['title'] ?? '' }}" placeholder="Project title"></div>
                                        <div class="section-field"><label>Description</label>
                                            <textarea name="sections[{{ $key }}][data][items][{{ $i }}][text]" rows="2"
                                                      placeholder="Brief description">{{ $item['text'] ?? '' }}</textarea></div>
                                        <div class="item-row-grid item-row-grid-2">
                                            <div class="section-field"><label>Image URL</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][image]"
                                                       value="{{ $item['image'] ?? '' }}" placeholder="https://..."></div>
                                            <div class="section-field"><label>Link URL</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][url]"
                                                       value="{{ $item['url'] ?? '' }}" placeholder="https://..."></div>
                                        </div>
                                        @elseif($hasYear)
                                        {{-- Achievements --}}
                                        <div class="item-row-grid item-row-grid-3">
                                            <div class="section-field"><label>Icon / Emoji</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][icon]"
                                                       value="{{ $item['icon'] ?? '' }}" placeholder="🏆"></div>
                                            <div class="section-field"><label>Title</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][title]"
                                                       value="{{ $item['title'] ?? '' }}" placeholder="Achievement title"></div>
                                            <div class="section-field"><label>Year</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][year]"
                                                       value="{{ $item['year'] ?? '' }}" placeholder="2024"></div>
                                        </div>
                                        <div class="section-field"><label>Description</label>
                                            <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][text]"
                                                   value="{{ $item['text'] ?? '' }}" placeholder="Brief description"></div>
                                        @elseif($hasUrl && !$hasCategory)
                                        {{-- Ventures (with URL but no category) --}}
                                        <div class="item-row-grid item-row-grid-2">
                                            <div class="section-field"><label>Icon / Emoji</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][icon]"
                                                       value="{{ $item['icon'] ?? '' }}" placeholder="🚀"></div>
                                            <div class="section-field"><label>Title</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][title]"
                                                       value="{{ $item['title'] ?? '' }}" placeholder="Venture name"></div>
                                        </div>
                                        <div class="section-field"><label>Description</label>
                                            <textarea name="sections[{{ $key }}][data][items][{{ $i }}][text]" rows="2"
                                                      placeholder="Brief description">{{ $item['text'] ?? '' }}</textarea></div>
                                        <div class="section-field"><label>Link URL</label>
                                            <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][url]"
                                                   value="{{ $item['url'] ?? '' }}" placeholder="https://..."></div>
                                        @else
                                        {{-- Generic: icon + title + text (services, agenda, etc.) --}}
                                        <div class="item-row-grid item-row-grid-2">
                                            <div class="section-field"><label>Icon / Emoji</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][icon]"
                                                       value="{{ $item['icon'] ?? '' }}" placeholder="💡"></div>
                                            <div class="section-field"><label>Title</label>
                                                <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][title]"
                                                       value="{{ $item['title'] ?? $item['name'] ?? '' }}"
                                                       placeholder="Item title"></div>
                                        </div>
                                        <div class="section-field"><label>Description</label>
                                            <textarea name="sections[{{ $key }}][data][items][{{ $i }}][text]" rows="2"
                                                      placeholder="Brief description">{{ $item['text'] ?? $item['description'] ?? '' }}</textarea></div>
                                        @if($hasPrice)
                                        <div class="section-field"><label>Price (optional)</label>
                                            <input type="text" name="sections[{{ $key }}][data][items][{{ $i }}][price]"
                                                   value="{{ $item['price'] ?? '' }}" placeholder="₹999 / Free"></div>
                                        @endif
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="add-item-btn" onclick="addItem('{{ $key }}')">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Custom page: HTML editor --}}
                <div class="pf-card">
                    <div class="pf-section-title"><i class="fas fa-code"></i> Page Content (HTML Editor)</div>
                    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
                    <div id="editor-container">{!! old('content', $page?->content ?? '') !!}</div>
                    <input type="hidden" name="content" id="pageContent">
                </div>
            @endif

            {{-- SEO --}}
            <div class="pf-card">
                <div class="pf-section-title"><i class="fas fa-search"></i> SEO Settings</div>
                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control"
                           value="{{ old('meta_title', $page?->meta_title ?? '') }}"
                           placeholder="Leave blank to use page title">
                </div>
                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_desc" class="form-control" rows="3"
                              placeholder="Brief description for search engines (150-160 chars)">{{ old('meta_desc', $page?->meta_desc ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div>
            <div class="pf-card">
                <div class="pf-section-title">Publish</div>
                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="published" {{ old('status', $page?->status ?? 'draft') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft"     {{ old('status', $page?->status ?? 'draft') === 'draft'     ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                        <input type="checkbox" name="show_in_menu" value="1"
                               {{ old('show_in_menu', $page?->show_in_menu) ? 'checked' : '' }}
                               style="width:16px;height:16px;accent-color:var(--accent,#6366f1);">
                        <span style="font-size:0.875rem;font-weight:500;">Show in navigation menu</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">
                    <i class="fas fa-{{ isset($page) && $page ? 'save' : 'plus' }}"></i>
                    {{ isset($page) && $page ? 'Save Page' : 'Create Page' }}
                </button>
                @if(isset($page) && $page)
                <a href="{{ route('admin.site.pages') }}" class="btn btn-outline" style="width:100%;margin-top:0.5rem;text-align:center;">Cancel</a>
                @endif
            </div>

            @if(isset($page) && $page)
            <div class="pf-card">
                <div class="pf-section-title">Page Info</div>
                <div style="font-size:0.8rem;color:var(--text-muted);">
                    <div style="margin-bottom:0.5rem;"><strong style="color:var(--text-secondary);">Type:</strong> {{ $page->page_type ? ucfirst($page->page_type) . ' (Theme)' : 'Custom' }}</div>
                    <div style="margin-bottom:0.5rem;"><strong style="color:var(--text-secondary);">Created:</strong> {{ $page->created_at->format('d M Y') }}</div>
                    <div style="margin-bottom:0.5rem;"><strong style="color:var(--text-secondary);">Updated:</strong> {{ $page->updated_at->diffForHumans() }}</div>
                    <div><strong style="color:var(--text-secondary);">URL:</strong><br>
                        <a href="{{ $page->public_url }}" target="_blank"
                           style="color:var(--accent,#6366f1);word-break:break-all;font-size:0.75rem;">{{ $page->public_url }}</a>
                    </div>
                </div>
            </div>
            @if($page->page_type && isset($sections) && count($sections))
            <div class="pf-card">
                <div class="pf-section-title">Sections Summary</div>
                @foreach($sections as $s)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.35rem 0;border-bottom:1px solid var(--border);font-size:0.8rem;">
                    <span style="color:var(--text-secondary);"><i class="{{ $s['icon'] ?? 'fas fa-circle' }}" style="width:14px;opacity:0.6;margin-right:0.35rem;"></i>{{ $s['label'] }}</span>
                    <span style="font-size:0.68rem;font-weight:700;padding:0.1rem 0.4rem;border-radius:20px;{{ ($s['enabled'] ?? false) ? 'background:rgba(99,102,241,0.15);color:var(--accent,#6366f1)' : 'background:var(--bg-hover);color:var(--text-muted)' }}">
                        {{ ($s['enabled'] ?? false) ? 'ON' : 'OFF' }}
                    </span>
                </div>
                @endforeach
            </div>
            @endif
            @endif
        </div>
    </div>
</form>

@if(!(isset($page) && $page && $page->page_type))
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
const quill = new Quill('#editor-container', {
    theme: 'snow',
    placeholder: 'Write your page content here...',
    modules: { toolbar: [[{'header':[1,2,3,4,false]}],['bold','italic','underline','strike'],[{'color':[]},{'background':[]}],[{'list':'ordered'},{'list':'bullet'}],[{'indent':'-1'},{'indent':'+1'}],['blockquote','code-block'],['link','image'],['clean']] }
});
const existingContent = {!! json_encode(old('content', $page?->content ?? '')) !!};
if (existingContent) quill.clipboard.dangerouslyPasteHTML(existingContent);
document.getElementById('pageForm').addEventListener('submit', function() {
    document.getElementById('pageContent').value = quill.root.innerHTML;
});
</script>
@endif

<script>
// Slug auto-generation
const titleInput = document.getElementById('pageTitle');
const slugInput  = document.getElementById('pageSlug');
let slugManuallyEdited = {{ (isset($page) && $page) ? 'true' : 'false' }};
if (titleInput && slugInput && !slugInput.readOnly) {
    titleInput.addEventListener('input', function() {
        if (!slugManuallyEdited)
            slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g,'').trim().replace(/\s+/g,'-');
    });
    slugInput.addEventListener('input', function() { slugManuallyEdited = true; });
}

// Section toggle
function toggleSection(key) {
    const body    = document.getElementById('body-' + key);
    const chevron = document.getElementById('chevron-' + key);
    if (!body) return;
    const isOpen = body.classList.contains('open');
    body.classList.toggle('open', !isOpen);
    if (chevron) chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
}
function onToggleChange(key, checked) {
    const card  = document.getElementById('sc-' + key);
    const badge = document.getElementById('badge-' + key);
    if (card)  card.classList.toggle('enabled', checked);
    if (badge) { badge.textContent = checked ? 'ON' : 'OFF'; badge.className = 'section-badge ' + (checked ? 'on' : 'off'); }
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.section-toggle input:checked').forEach(function(cb) {
        const key    = cb.id.replace('toggle-', '');
        const body   = document.getElementById('body-' + key);
        const chevron = document.getElementById('chevron-' + key);
        if (body)   body.classList.add('open');
        if (chevron) chevron.style.transform = 'rotate(180deg)';
    });
});

// Items editor: remove item
function removeItem(key, idx) {
    const row = document.getElementById('item-' + key + '-' + idx);
    if (row) row.remove();
    renumberItems(key);
}

// Items editor: add item (clone last row with empty values)
function addItem(key) {
    const container = document.getElementById('items-' + key);
    if (!container) return;
    const rows = container.querySelectorAll('.item-row');
    const newIdx = rows.length;
    if (rows.length === 0) {
        alert('No template row found. Please save the page first to initialise items.');
        return;
    }
    const lastRow = rows[rows.length - 1];
    const newRow  = lastRow.cloneNode(true);
    newRow.id     = 'item-' + key + '-' + newIdx;
    // Update all input/textarea names to use new index
    newRow.querySelectorAll('input, textarea, select').forEach(function(el) {
        el.name  = el.name.replace(/\[\d+\]/, '[' + newIdx + ']');
        el.value = '';
        el.id    = '';
    });
    // Update remove button
    const removeBtn = newRow.querySelector('.item-remove');
    if (removeBtn) removeBtn.setAttribute('onclick', "removeItem('" + key + "', " + newIdx + ")");
    container.appendChild(newRow);
}

// Renumber items after removal
function renumberItems(key) {
    const container = document.getElementById('items-' + key);
    if (!container) return;
    container.querySelectorAll('.item-row').forEach(function(row, idx) {
        row.id = 'item-' + key + '-' + idx;
        row.querySelectorAll('input, textarea, select').forEach(function(el) {
            el.name = el.name.replace(/\[\d+\]/, '[' + idx + ']');
        });
        const removeBtn = row.querySelector('.item-remove');
        if (removeBtn) removeBtn.setAttribute('onclick', "removeItem('" + key + "', " + idx + ")");
    });
}
</script>
@endsection
