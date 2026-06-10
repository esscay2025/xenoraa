@extends('layouts.superadmin')
@section('title', 'SEO Management — Super Admin')
@section('page_title', 'SEO Management')
@section('content')
<div class="sa-content">

    {{-- Page Header --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.75rem;gap:1rem;flex-wrap:wrap;">
        <div>
            <h2 style="font-size:1.25rem;font-weight:700;margin-bottom:0.3rem;color:#fff;">SEO Management</h2>
            <p style="font-size:0.8rem;color:#71717a;max-width:560px;">Configure global SEO settings for the Xenoraa platform. These settings apply to all public-facing Xenoraa pages (xenoraa.com).</p>
        </div>
        <a href="{{ route('superadmin.settings') }}" class="sa-action-btn">
            <i class="fas fa-arrow-left"></i> Back to Settings
        </a>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
    <div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);color:#22c55e;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;font-size:0.85rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Tab Navigation --}}
    <div style="display:flex;gap:0.25rem;margin-bottom:1.5rem;border-bottom:1px solid #1a1a1a;overflow-x:auto;padding-bottom:0;" id="seoTabs">
        @php
        $tabs = [
            ['id'=>'general',   'icon'=>'fa-search',        'label'=>'General SEO'],
            ['id'=>'gtag',      'icon'=>'fa-tags',           'label'=>'Google Tag'],
            ['id'=>'opengraph', 'icon'=>'fa-share-alt',      'label'=>'Open Graph'],
            ['id'=>'twitter',   'icon'=>'fa-twitter',        'label'=>'Twitter Card'],
            ['id'=>'sitemap',   'icon'=>'fa-sitemap',        'label'=>'Sitemap & Robots'],
            ['id'=>'schema',    'icon'=>'fa-code',           'label'=>'Schema / JSON-LD'],
            ['id'=>'scripts',   'icon'=>'fa-file-code',      'label'=>'Custom Scripts'],
        ];
        @endphp
        @foreach($tabs as $tab)
        <button type="button"
            onclick="switchTab('{{ $tab['id'] }}')"
            id="tab-btn-{{ $tab['id'] }}"
            style="display:inline-flex;align-items:center;gap:0.4rem;padding:0.6rem 1rem;font-size:0.78rem;font-weight:600;border:none;border-bottom:2px solid transparent;background:transparent;cursor:pointer;white-space:nowrap;transition:all 0.2s;color:#71717a;border-radius:0;"
            class="seo-tab-btn">
            <i class="fas {{ $tab['icon'] }}" style="font-size:0.7rem;"></i>
            {{ $tab['label'] }}
        </button>
        @endforeach
    </div>

    <form method="POST" action="{{ route('superadmin.seo.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ── TAB: General SEO ─────────────────────────────────────────── --}}
        <div id="tab-general" class="seo-tab-panel">
            <div class="sa-card" style="margin-bottom:1.25rem;">
                <div class="sa-card-header">
                    <div style="display:flex;align-items:center;gap:0.6rem;">
                        <div style="width:32px;height:32px;background:rgba(124,58,237,0.12);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-search" style="color:#a855f7;font-size:0.8rem;"></i>
                        </div>
                        <div>
                            <div class="sa-card-title">General SEO</div>
                            <div style="font-size:0.72rem;color:#52525b;">Default meta title, description and keywords for xenoraa.com</div>
                        </div>
                    </div>
                </div>
                <div style="padding:1.5rem;display:grid;gap:1.25rem;">
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">
                            Meta Title <span style="color:#71717a;font-weight:400;text-transform:none;letter-spacing:0;">(50–60 chars recommended)</span>
                        </label>
                        <input type="text" name="seo_meta_title"
                            value="{{ $settings['seo_meta_title'] ?? 'Xenoraa — Build Your Digital Identity' }}"
                            maxlength="120"
                            style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;"
                            onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#222'">
                        <div style="font-size:0.7rem;color:#52525b;margin-top:0.3rem;" id="title-count">
                            <span id="title-len">{{ strlen($settings['seo_meta_title'] ?? 'Xenoraa — Build Your Digital Identity') }}</span> / 120 chars
                        </div>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">
                            Meta Description <span style="color:#71717a;font-weight:400;text-transform:none;letter-spacing:0;">(150–160 chars recommended)</span>
                        </label>
                        <textarea name="seo_meta_description" rows="3"
                            maxlength="320"
                            style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;resize:vertical;transition:border-color 0.2s;"
                            onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#222'">{{ $settings['seo_meta_description'] ?? 'Xenoraa is the all-in-one SaaS platform for professionals to build their digital identity, manage clients, publish content, and grow their brand.' }}</textarea>
                        <div style="font-size:0.7rem;color:#52525b;margin-top:0.3rem;" id="desc-count">
                            <span id="desc-len">{{ strlen($settings['seo_meta_description'] ?? '') }}</span> / 320 chars
                        </div>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">
                            Meta Keywords <span style="color:#71717a;font-weight:400;text-transform:none;letter-spacing:0;">(comma-separated)</span>
                        </label>
                        <input type="text" name="seo_meta_keywords"
                            value="{{ $settings['seo_meta_keywords'] ?? 'xenoraa, personal branding, digital identity, SaaS, portfolio, CRM, AI assistant' }}"
                            style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;"
                            onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#222'">
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Canonical URL</label>
                            <input type="url" name="seo_canonical_url"
                                value="{{ $settings['seo_canonical_url'] ?? 'https://xenoraa.com' }}"
                                placeholder="https://xenoraa.com"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#222'">
                        </div>
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Robots Directive</label>
                            <select name="seo_robots"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;appearance:none;"
                                onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#222'">
                                @foreach(['index, follow','noindex, follow','index, nofollow','noindex, nofollow'] as $opt)
                                <option value="{{ $opt }}" {{ ($settings['seo_robots'] ?? 'index, follow') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            {{-- SERP Preview --}}
            <div class="sa-card">
                <div class="sa-card-header">
                    <div class="sa-card-title">SERP Preview</div>
                    <span style="font-size:0.72rem;color:#52525b;">How your page may appear in Google search results</span>
                </div>
                <div style="padding:1.5rem;">
                    <div style="background:#fff;border-radius:8px;padding:1rem 1.25rem;max-width:600px;">
                        <div style="font-size:0.7rem;color:#202124;margin-bottom:0.2rem;font-family:Arial,sans-serif;">xenoraa.com</div>
                        <div id="serp-title" style="font-size:1.1rem;color:#1a0dab;font-family:Arial,sans-serif;font-weight:400;line-height:1.3;margin-bottom:0.3rem;cursor:pointer;">
                            {{ $settings['seo_meta_title'] ?? 'Xenoraa — Build Your Digital Identity' }}
                        </div>
                        <div id="serp-desc" style="font-size:0.82rem;color:#4d5156;font-family:Arial,sans-serif;line-height:1.5;">
                            {{ Str::limit($settings['seo_meta_description'] ?? 'Xenoraa is the all-in-one SaaS platform for professionals.', 160) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TAB: Google Tag ──────────────────────────────────────────── --}}
        <div id="tab-gtag" class="seo-tab-panel" style="display:none;">
            <div class="sa-card" style="margin-bottom:1.25rem;">
                <div class="sa-card-header">
                    <div style="display:flex;align-items:center;gap:0.6rem;">
                        <div style="width:32px;height:32px;background:rgba(234,179,8,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-tags" style="color:#eab308;font-size:0.8rem;"></i>
                        </div>
                        <div>
                            <div class="sa-card-title">Google Tag (gtag.js)</div>
                            <div style="font-size:0.72rem;color:#52525b;">Configure Google Analytics 4 / Google Tag Manager for xenoraa.com</div>
                        </div>
                    </div>
                    {{-- Enable toggle --}}
                    <label style="display:flex;align-items:center;gap:0.6rem;cursor:pointer;">
                        <span style="font-size:0.78rem;color:#a1a1aa;">Enable</span>
                        <div style="position:relative;width:40px;height:22px;" onclick="toggleGtag(this)">
                            <input type="hidden" name="google_tag_enabled" id="gtag_enabled_input" value="{{ $settings['google_tag_enabled'] ?? '1' }}">
                            <div id="gtag-toggle-track" style="width:40px;height:22px;border-radius:11px;background:{{ ($settings['google_tag_enabled'] ?? '1') === '1' ? '#7c3aed' : '#333' }};transition:background 0.2s;"></div>
                            <div id="gtag-toggle-knob" style="position:absolute;top:3px;left:{{ ($settings['google_tag_enabled'] ?? '1') === '1' ? '21px' : '3px' }};width:16px;height:16px;border-radius:50%;background:#fff;transition:left 0.2s;box-shadow:0 1px 3px rgba(0,0,0,0.4);"></div>
                        </div>
                    </label>
                </div>
                <div style="padding:1.5rem;display:grid;gap:1.25rem;">
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">
                            Measurement ID / Tag ID
                        </label>
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div style="position:relative;flex:1;">
                                <span style="position:absolute;left:0.85rem;top:50%;transform:translateY(-50%);font-size:0.78rem;color:#52525b;font-weight:600;">G-</span>
                                <input type="text" name="google_tag_id" id="gtag_id_input"
                                    value="{{ ltrim($settings['google_tag_id'] ?? 'G-SKMW277LED', 'G-') }}"
                                    placeholder="XXXXXXXXXX"
                                    style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem 0.6rem 2.2rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;font-family:monospace;"
                                    onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#222';updateGtagPreview()">
                            </div>
                            <button type="button" onclick="updateGtagPreview()" class="sa-action-btn" style="white-space:nowrap;">
                                <i class="fas fa-sync-alt"></i> Preview
                            </button>
                        </div>
                        <p style="font-size:0.72rem;color:#52525b;margin-top:0.4rem;">
                            Enter only the part after <code style="background:#1a1a1a;padding:0.1rem 0.3rem;border-radius:3px;color:#a855f7;">G-</code>. Example: <code style="background:#1a1a1a;padding:0.1rem 0.3rem;border-radius:3px;color:#a855f7;">SKMW277LED</code>
                        </p>
                    </div>
                    {{-- Live Code Preview --}}
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">
                            Generated Script Preview
                        </label>
                        <div style="background:#0a0a0a;border:1px solid #1a1a1a;border-radius:8px;padding:1rem;position:relative;overflow:auto;">
                            <button type="button" onclick="copyGtagCode()" style="position:absolute;top:0.6rem;right:0.6rem;background:#1a1a1a;border:1px solid #222;color:#71717a;padding:0.25rem 0.5rem;border-radius:5px;font-size:0.7rem;cursor:pointer;display:flex;align-items:center;gap:0.3rem;">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                            <pre id="gtag-preview" style="margin:0;font-family:'Courier New',monospace;font-size:0.75rem;color:#a855f7;line-height:1.6;white-space:pre-wrap;"></pre>
                        </div>
                    </div>
                    {{-- Status indicator --}}
                    <div style="background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.15);border-radius:8px;padding:0.75rem 1rem;display:flex;align-items:flex-start;gap:0.75rem;">
                        <i class="fas fa-info-circle" style="color:#22c55e;margin-top:0.1rem;flex-shrink:0;"></i>
                        <div style="font-size:0.78rem;color:#a1a1aa;line-height:1.5;">
                            The Google Tag script is injected into the <code style="background:#1a1a1a;padding:0.1rem 0.3rem;border-radius:3px;color:#a855f7;">&lt;head&gt;</code> of all Xenoraa marketing pages (home, pricing, features, blog, etc.) when enabled. It does <strong style="color:#fff;">not</strong> inject into tenant sub-sites.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TAB: Open Graph ─────────────────────────────────────────── --}}
        <div id="tab-opengraph" class="seo-tab-panel" style="display:none;">
            <div class="sa-card" style="margin-bottom:1.25rem;">
                <div class="sa-card-header">
                    <div style="display:flex;align-items:center;gap:0.6rem;">
                        <div style="width:32px;height:32px;background:rgba(59,130,246,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-share-alt" style="color:#3b82f6;font-size:0.8rem;"></i>
                        </div>
                        <div>
                            <div class="sa-card-title">Open Graph Tags</div>
                            <div style="font-size:0.72rem;color:#52525b;">Controls how xenoraa.com looks when shared on Facebook, LinkedIn, WhatsApp, etc.</div>
                        </div>
                    </div>
                </div>
                <div style="padding:1.5rem;display:grid;gap:1.25rem;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">OG Title</label>
                            <input type="text" name="og_title"
                                value="{{ $settings['og_title'] ?? 'Xenoraa — Build Your Digital Identity' }}"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#222'">
                        </div>
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">OG Type</label>
                            <select name="og_type"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;appearance:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#222'">
                                @foreach(['website','article','product','profile'] as $opt)
                                <option value="{{ $opt }}" {{ ($settings['og_type'] ?? 'website') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">OG Description</label>
                        <textarea name="og_description" rows="2"
                            style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;resize:vertical;transition:border-color 0.2s;"
                            onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#222'">{{ $settings['og_description'] ?? 'Xenoraa is the all-in-one SaaS platform for professionals to build their digital identity.' }}</textarea>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">OG Image URL <span style="color:#71717a;font-weight:400;text-transform:none;letter-spacing:0;">(1200×630 recommended)</span></label>
                            <input type="url" name="og_image"
                                value="{{ $settings['og_image'] ?? 'https://xenoraa.com/images/xenoraa/og-image.png' }}"
                                placeholder="https://xenoraa.com/images/og-image.png"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#222'">
                        </div>
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">OG Site Name</label>
                            <input type="text" name="og_site_name"
                                value="{{ $settings['og_site_name'] ?? 'Xenoraa' }}"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#222'">
                        </div>
                    </div>
                    {{-- OG Preview Card --}}
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.6rem;text-transform:uppercase;letter-spacing:0.05em;">Social Share Preview</label>
                        <div style="background:#fff;border-radius:10px;overflow:hidden;max-width:500px;box-shadow:0 4px 20px rgba(0,0,0,0.3);">
                            <div style="height:140px;background:linear-gradient(135deg,#7c3aed,#a855f7);display:flex;align-items:center;justify-content:center;">
                                <span style="color:rgba(255,255,255,0.4);font-size:0.75rem;">OG Image Preview</span>
                            </div>
                            <div style="padding:0.75rem 1rem;border-top:1px solid #e5e7eb;">
                                <div style="font-size:0.65rem;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.2rem;">xenoraa.com</div>
                                <div style="font-size:0.9rem;font-weight:700;color:#111827;margin-bottom:0.2rem;">{{ $settings['og_title'] ?? 'Xenoraa — Build Your Digital Identity' }}</div>
                                <div style="font-size:0.78rem;color:#6b7280;line-height:1.4;">{{ Str::limit($settings['og_description'] ?? 'Xenoraa is the all-in-one SaaS platform for professionals.', 100) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TAB: Twitter Card ────────────────────────────────────────── --}}
        <div id="tab-twitter" class="seo-tab-panel" style="display:none;">
            <div class="sa-card">
                <div class="sa-card-header">
                    <div style="display:flex;align-items:center;gap:0.6rem;">
                        <div style="width:32px;height:32px;background:rgba(29,161,242,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                            <i class="fab fa-twitter" style="color:#1da1f2;font-size:0.8rem;"></i>
                        </div>
                        <div>
                            <div class="sa-card-title">Twitter / X Card</div>
                            <div style="font-size:0.72rem;color:#52525b;">Controls how xenoraa.com looks when shared on Twitter / X</div>
                        </div>
                    </div>
                </div>
                <div style="padding:1.5rem;display:grid;gap:1.25rem;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Card Type</label>
                            <select name="twitter_card"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;appearance:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#1da1f2'" onblur="this.style.borderColor='#222'">
                                @foreach(['summary_large_image','summary','app','player'] as $opt)
                                <option value="{{ $opt }}" {{ ($settings['twitter_card'] ?? 'summary_large_image') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Twitter Handle</label>
                            <input type="text" name="twitter_site"
                                value="{{ $settings['twitter_site'] ?? '@xenoraa' }}"
                                placeholder="@xenoraa"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#1da1f2'" onblur="this.style.borderColor='#222'">
                        </div>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Twitter Title</label>
                        <input type="text" name="twitter_title"
                            value="{{ $settings['twitter_title'] ?? 'Xenoraa — Build Your Digital Identity' }}"
                            style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;"
                            onfocus="this.style.borderColor='#1da1f2'" onblur="this.style.borderColor='#222'">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Twitter Description</label>
                        <textarea name="twitter_description" rows="2"
                            style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;resize:vertical;transition:border-color 0.2s;"
                            onfocus="this.style.borderColor='#1da1f2'" onblur="this.style.borderColor='#222'">{{ $settings['twitter_description'] ?? 'Xenoraa is the all-in-one SaaS platform for professionals to build their digital identity.' }}</textarea>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Twitter Image URL</label>
                        <input type="url" name="twitter_image"
                            value="{{ $settings['twitter_image'] ?? 'https://xenoraa.com/images/xenoraa/og-image.png' }}"
                            placeholder="https://xenoraa.com/images/twitter-card.png"
                            style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;"
                            onfocus="this.style.borderColor='#1da1f2'" onblur="this.style.borderColor='#222'">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TAB: Sitemap & Robots ────────────────────────────────────── --}}
        <div id="tab-sitemap" class="seo-tab-panel" style="display:none;">
            <div class="sa-card" style="margin-bottom:1.25rem;">
                <div class="sa-card-header">
                    <div style="display:flex;align-items:center;gap:0.6rem;">
                        <div style="width:32px;height:32px;background:rgba(34,197,94,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-sitemap" style="color:#22c55e;font-size:0.8rem;"></i>
                        </div>
                        <div>
                            <div class="sa-card-title">Sitemap & Robots.txt</div>
                            <div style="font-size:0.72rem;color:#52525b;">Control sitemap generation and robots.txt directives</div>
                        </div>
                    </div>
                </div>
                <div style="padding:1.5rem;display:grid;gap:1.25rem;">
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Sitemap</label>
                            <select name="sitemap_enabled"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;appearance:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#22c55e'" onblur="this.style.borderColor='#222'">
                                <option value="1" {{ ($settings['sitemap_enabled'] ?? '1') === '1' ? 'selected' : '' }}>Enabled</option>
                                <option value="0" {{ ($settings['sitemap_enabled'] ?? '1') === '0' ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Change Frequency</label>
                            <select name="sitemap_frequency"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;appearance:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#22c55e'" onblur="this.style.borderColor='#222'">
                                @foreach(['always','hourly','daily','weekly','monthly','yearly','never'] as $opt)
                                <option value="{{ $opt }}" {{ ($settings['sitemap_frequency'] ?? 'weekly') === $opt ? 'selected' : '' }}>{{ ucfirst($opt) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Priority</label>
                            <select name="sitemap_priority"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;appearance:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#22c55e'" onblur="this.style.borderColor='#222'">
                                @foreach(['1.0','0.9','0.8','0.7','0.6','0.5'] as $opt)
                                <option value="{{ $opt }}" {{ ($settings['sitemap_priority'] ?? '0.8') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.15);border-radius:8px;padding:0.75rem 1rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                        <div style="display:flex;align-items:center;gap:0.6rem;">
                            <i class="fas fa-file-alt" style="color:#22c55e;"></i>
                            <div>
                                <div style="font-size:0.8rem;color:#fff;font-weight:600;">sitemap.xml</div>
                                <div style="font-size:0.7rem;color:#52525b;">https://xenoraa.com/sitemap.xml</div>
                            </div>
                        </div>
                        <a href="https://xenoraa.com/sitemap.xml" target="_blank" class="sa-action-btn">
                            <i class="fas fa-external-link-alt"></i> View
                        </a>
                    </div>
                    <div style="background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.15);border-radius:8px;padding:0.75rem 1rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                        <div style="display:flex;align-items:center;gap:0.6rem;">
                            <i class="fas fa-robot" style="color:#22c55e;"></i>
                            <div>
                                <div style="font-size:0.8rem;color:#fff;font-weight:600;">robots.txt</div>
                                <div style="font-size:0.7rem;color:#52525b;">https://xenoraa.com/robots.txt</div>
                            </div>
                        </div>
                        <a href="https://xenoraa.com/robots.txt" target="_blank" class="sa-action-btn">
                            <i class="fas fa-external-link-alt"></i> View
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TAB: Schema / JSON-LD ────────────────────────────────────── --}}
        <div id="tab-schema" class="seo-tab-panel" style="display:none;">
            <div class="sa-card">
                <div class="sa-card-header">
                    <div style="display:flex;align-items:center;gap:0.6rem;">
                        <div style="width:32px;height:32px;background:rgba(249,115,22,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-code" style="color:#f97316;font-size:0.8rem;"></i>
                        </div>
                        <div>
                            <div class="sa-card-title">Schema.org / JSON-LD</div>
                            <div style="font-size:0.72rem;color:#52525b;">Structured data for rich results in Google Search</div>
                        </div>
                    </div>
                </div>
                <div style="padding:1.5rem;display:grid;gap:1.25rem;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Organisation Type</label>
                            <select name="schema_org_type"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;appearance:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#222'">
                                @foreach(['Organization','Corporation','LocalBusiness','SoftwareApplication','WebSite'] as $opt)
                                <option value="{{ $opt }}" {{ ($settings['schema_org_type'] ?? 'Organization') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Organisation Name</label>
                            <input type="text" name="schema_org_name"
                                value="{{ $settings['schema_org_name'] ?? 'Xenoraa' }}"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#222'">
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Website URL</label>
                            <input type="url" name="schema_org_url"
                                value="{{ $settings['schema_org_url'] ?? 'https://xenoraa.com' }}"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#222'">
                        </div>
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Logo URL</label>
                            <input type="url" name="schema_org_logo"
                                value="{{ $settings['schema_org_logo'] ?? 'https://xenoraa.com/images/xenoraa/logo.png' }}"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#222'">
                        </div>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Description</label>
                        <textarea name="schema_org_description" rows="2"
                            style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;resize:vertical;transition:border-color 0.2s;"
                            onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#222'">{{ $settings['schema_org_description'] ?? 'Xenoraa is the all-in-one SaaS platform for professionals to build their digital identity.' }}</textarea>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Phone</label>
                            <input type="text" name="schema_org_phone"
                                value="{{ $settings['schema_org_phone'] ?? '' }}"
                                placeholder="+91 XXXXX XXXXX"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#222'">
                        </div>
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Email</label>
                            <input type="email" name="schema_org_email"
                                value="{{ $settings['schema_org_email'] ?? 'hello@xenoraa.com' }}"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#222'">
                        </div>
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Address</label>
                            <input type="text" name="schema_org_address"
                                value="{{ $settings['schema_org_address'] ?? '' }}"
                                placeholder="City, Country"
                                style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.85rem;color:#fff;font-size:0.85rem;outline:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#222'">
                        </div>
                    </div>
                    {{-- JSON-LD Preview --}}
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">Generated JSON-LD</label>
                        <div style="background:#0a0a0a;border:1px solid #1a1a1a;border-radius:8px;padding:1rem;overflow:auto;max-height:200px;">
                            <pre style="margin:0;font-family:'Courier New',monospace;font-size:0.72rem;color:#f97316;line-height:1.6;white-space:pre-wrap;">{
  "@context": "https://schema.org",
  "@type": "{{ $settings['schema_org_type'] ?? 'Organization' }}",
  "name": "{{ $settings['schema_org_name'] ?? 'Xenoraa' }}",
  "url": "{{ $settings['schema_org_url'] ?? 'https://xenoraa.com' }}",
  "logo": "{{ $settings['schema_org_logo'] ?? 'https://xenoraa.com/images/xenoraa/logo.png' }}",
  "description": "{{ $settings['schema_org_description'] ?? 'Xenoraa is the all-in-one SaaS platform.' }}"
}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TAB: Custom Scripts ──────────────────────────────────────── --}}
        <div id="tab-scripts" class="seo-tab-panel" style="display:none;">
            <div class="sa-card" style="margin-bottom:1.25rem;">
                <div class="sa-card-header">
                    <div style="display:flex;align-items:center;gap:0.6rem;">
                        <div style="width:32px;height:32px;background:rgba(168,85,247,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-file-code" style="color:#a855f7;font-size:0.8rem;"></i>
                        </div>
                        <div>
                            <div class="sa-card-title">Custom Scripts</div>
                            <div style="font-size:0.72rem;color:#52525b;">Inject custom HTML/JS into the Xenoraa marketing pages</div>
                        </div>
                    </div>
                </div>
                <div style="padding:1.5rem;display:grid;gap:1.25rem;">
                    <div style="background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.15);border-radius:8px;padding:0.75rem 1rem;display:flex;align-items:flex-start;gap:0.6rem;">
                        <i class="fas fa-exclamation-triangle" style="color:#ef4444;margin-top:0.1rem;flex-shrink:0;font-size:0.85rem;"></i>
                        <p style="font-size:0.78rem;color:#a1a1aa;line-height:1.5;margin:0;">
                            <strong style="color:#fff;">Warning:</strong> Only add trusted scripts here. Malicious code can compromise the entire platform. These scripts are injected into all Xenoraa marketing pages.
                        </p>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">
                            Custom &lt;head&gt; Scripts <span style="color:#71717a;font-weight:400;text-transform:none;letter-spacing:0;">(injected before &lt;/head&gt;)</span>
                        </label>
                        <textarea name="custom_head_scripts" rows="6"
                            placeholder="<!-- Paste scripts, meta tags, or link tags here -->"
                            style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.75rem;color:#a855f7;font-size:0.78rem;outline:none;resize:vertical;font-family:'Courier New',monospace;line-height:1.6;transition:border-color 0.2s;"
                            onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#222'">{{ $settings['custom_head_scripts'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em;">
                            Custom &lt;body&gt; Scripts <span style="color:#71717a;font-weight:400;text-transform:none;letter-spacing:0;">(injected before &lt;/body&gt;)</span>
                        </label>
                        <textarea name="custom_body_scripts" rows="6"
                            placeholder="<!-- Paste analytics, chat widgets, or tracking scripts here -->"
                            style="width:100%;background:#0a0a0a;border:1px solid #222;border-radius:8px;padding:0.75rem;color:#a855f7;font-size:0.78rem;outline:none;resize:vertical;font-family:'Courier New',monospace;line-height:1.6;transition:border-color 0.2s;"
                            onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#222'">{{ $settings['custom_body_scripts'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Save Button (always visible) --}}
        <div style="display:flex;align-items:center;justify-content:flex-end;gap:1rem;padding:1.25rem 0;border-top:1px solid #1a1a1a;margin-top:0.5rem;">
            <span style="font-size:0.78rem;color:#52525b;">Changes apply to all Xenoraa marketing pages immediately after saving.</span>
            <button type="submit" class="sa-btn-primary" style="padding:0.6rem 1.75rem;font-size:0.875rem;">
                <i class="fas fa-save"></i> Save SEO Settings
            </button>
        </div>
    </form>
</div>

<style>
.seo-tab-btn.active {
    color: #a855f7 !important;
    border-bottom-color: #7c3aed !important;
}
.seo-tab-btn:hover:not(.active) {
    color: #a1a1aa !important;
    background: rgba(255,255,255,0.02) !important;
}
</style>

<script>
// ── Tab switching ────────────────────────────────────────────────────────────
function switchTab(id) {
    document.querySelectorAll('.seo-tab-panel').forEach(p => p.style.display = 'none');
    document.querySelectorAll('.seo-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + id).style.display = 'block';
    document.getElementById('tab-btn-' + id).classList.add('active');
    localStorage.setItem('seo_active_tab', id);
}

// Restore last active tab
document.addEventListener('DOMContentLoaded', function () {
    const saved = localStorage.getItem('seo_active_tab') || 'general';
    switchTab(saved);

    // Character counters
    const titleInput = document.querySelector('[name="seo_meta_title"]');
    const descInput  = document.querySelector('[name="seo_meta_description"]');
    if (titleInput) {
        titleInput.addEventListener('input', function () {
            document.getElementById('title-len').textContent = this.value.length;
            const serp = document.getElementById('serp-title');
            if (serp) serp.textContent = this.value || 'Xenoraa — Build Your Digital Identity';
        });
    }
    if (descInput) {
        descInput.addEventListener('input', function () {
            document.getElementById('desc-len').textContent = this.value.length;
            const serp = document.getElementById('serp-desc');
            if (serp) serp.textContent = this.value.substring(0, 160) + (this.value.length > 160 ? '…' : '');
        });
    }

    // Initial gtag preview
    updateGtagPreview();
});

// ── Google Tag toggle ────────────────────────────────────────────────────────
function toggleGtag(wrapper) {
    const input = document.getElementById('gtag_enabled_input');
    const track = document.getElementById('gtag-toggle-track');
    const knob  = document.getElementById('gtag-toggle-knob');
    const isOn  = input.value === '1';
    input.value = isOn ? '0' : '1';
    track.style.background = isOn ? '#333' : '#7c3aed';
    knob.style.left = isOn ? '3px' : '21px';
}

// ── Google Tag preview ───────────────────────────────────────────────────────
function updateGtagPreview() {
    const rawId = document.getElementById('gtag_id_input')?.value?.trim() || 'SKMW277LED';
    const fullId = rawId.startsWith('G-') ? rawId : 'G-' + rawId;
    const code = `<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=${fullId}"><\/script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '${fullId}');
<\/script>`;
    const pre = document.getElementById('gtag-preview');
    if (pre) pre.textContent = code;
}

// ── Copy gtag code ───────────────────────────────────────────────────────────
function copyGtagCode() {
    const text = document.getElementById('gtag-preview')?.textContent || '';
    navigator.clipboard.writeText(text).then(() => {
        const btn = event.currentTarget;
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        btn.style.color = '#22c55e';
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-copy"></i> Copy';
            btn.style.color = '';
        }, 2000);
    });
}
</script>
@endsection
