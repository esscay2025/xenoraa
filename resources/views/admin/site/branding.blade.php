@extends('layouts.admin')
@section('title', 'Branding')
@section('page-title', 'Branding')

@section('content')
<style>
.br-layout { display:grid; grid-template-columns:1fr 320px; gap:1.5rem; align-items:start; }
.br-card { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; padding:1.5rem; margin-bottom:1.25rem; }
.br-section-title { font-size:0.85rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.06em; margin:0 0 1.25rem; padding-bottom:0.75rem; border-bottom:1px solid var(--border); }
.br-upload-area {
    border:2px dashed var(--border);
    border-radius:12px;
    padding:2rem;
    text-align:center;
    cursor:pointer;
    transition:all 0.2s;
    position:relative;
    overflow:hidden;
}
.br-upload-area:hover { border-color:var(--accent,#6366f1); background:rgba(99,102,241,0.03); }
.br-upload-area input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; }
.br-upload-icon { font-size:2rem; color:var(--text-muted); margin-bottom:0.5rem; }
.br-upload-text { font-size:0.85rem; color:var(--text-secondary); }
.br-upload-hint { font-size:0.75rem; color:var(--text-muted); margin-top:0.25rem; }
.br-current-img { width:100%; max-width:200px; height:80px; object-fit:contain; border-radius:8px; border:1px solid var(--border); background:var(--bg-secondary); padding:0.5rem; }
.br-favicon-img { width:48px; height:48px; object-fit:contain; border-radius:6px; border:1px solid var(--border); background:var(--bg-secondary); padding:0.25rem; }
.br-color-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
.br-color-field { display:flex; align-items:center; gap:0.75rem; background:var(--bg-secondary); border:1px solid var(--border); border-radius:8px; padding:0.5rem 0.75rem; }
.br-color-field input[type=color] { width:36px; height:36px; border:none; background:none; cursor:pointer; border-radius:6px; padding:0; }
.br-color-field input[type=text] { flex:1; background:none; border:none; color:var(--text-primary); font-size:0.875rem; font-family:'Inter',sans-serif; outline:none; }
.br-preview-box { background:var(--bg-secondary); border:1px solid var(--border); border-radius:12px; overflow:hidden; }
.br-preview-nav { padding:0.75rem 1rem; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid var(--border); }
.br-preview-hero { padding:2rem 1rem; text-align:center; }
@media(max-width:768px) { .br-layout { grid-template-columns:1fr; } }
</style>

<div style="margin-bottom:1rem;">
    <a href="{{ route('admin.site.index') }}" style="color:var(--text-muted);text-decoration:none;font-size:0.85rem;"><i class="fas fa-arrow-left"></i> Site Builder</a>
</div>
<h1 style="font-size:1.75rem;font-weight:800;margin:0 0 0.25rem;">Branding</h1>
<p style="color:var(--text-secondary);margin:0 0 1.5rem;font-size:0.9rem;">Upload your logo, favicon, and set your brand identity.</p>

<form method="POST" action="{{ route('admin.site.branding.save') }}" enctype="multipart/form-data" id="brandingForm">
    @csrf

    <div class="br-layout">
        <div>
            {{-- Site Identity --}}
            <div class="br-card">
                <div class="br-section-title"><i class="fas fa-id-card" style="margin-right:0.4rem;"></i> Site Identity</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div class="form-group">
                        <label class="form-label">Site Name <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $siteName) }}" required
                            placeholder="Your name or brand" id="siteNameInput" oninput="updatePreview()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tagline</label>
                        <input type="text" name="site_tagline" class="form-control" value="{{ old('site_tagline', $tagline) }}"
                            placeholder="Your professional tagline" id="taglineInput" oninput="updatePreview()">
                    </div>
                </div>
            </div>

            {{-- Logo --}}
            <div class="br-card">
                <div class="br-section-title"><i class="fas fa-image" style="margin-right:0.4rem;"></i> Logo</div>
                @if($logo)
                <div style="margin-bottom:1rem;">
                    <div style="font-size:0.78rem;color:var(--text-muted);margin-bottom:0.5rem;">Current Logo</div>
                    <img src="{{ $logo }}" alt="Logo" class="br-current-img" id="logoPreview">
                </div>
                @else
                <div style="margin-bottom:1rem;display:none;" id="logoPreviewWrap">
                    <div style="font-size:0.78rem;color:var(--text-muted);margin-bottom:0.5rem;">Preview</div>
                    <img src="" alt="Logo" class="br-current-img" id="logoPreview">
                </div>
                @endif
                <div class="br-upload-area" id="logoDropZone">
                    <input type="file" name="logo" accept="image/*" onchange="previewLogo(this)">
                    <div class="br-upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="br-upload-text">Click or drag to upload logo</div>
                    <div class="br-upload-hint">PNG, SVG, JPG · Max 4MB · Transparent PNG recommended</div>
                </div>
            </div>

            {{-- Favicon --}}
            <div class="br-card">
                <div class="br-section-title"><i class="fas fa-star" style="margin-right:0.4rem;"></i> Browser Tab Icon (Favicon)</div>
                <div style="display:flex;align-items:center;gap:1.25rem;margin-bottom:1rem;">
                    @if($favicon)
                    <img src="{{ $favicon }}" alt="Favicon" class="br-favicon-img" id="faviconPreview">
                    @else
                    <div class="br-favicon-img" id="faviconPreview" style="display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:1.2rem;"><i class="fas fa-star"></i></div>
                    @endif
                    <div>
                        <div style="font-size:0.85rem;font-weight:600;margin-bottom:0.25rem;">Favicon</div>
                        <div style="font-size:0.75rem;color:var(--text-muted);">Shown in browser tabs and bookmarks.<br>Best size: 32×32 or 64×64 pixels.</div>
                    </div>
                </div>
                <div class="br-upload-area">
                    <input type="file" name="favicon" accept="image/*,.ico" onchange="previewFavicon(this)">
                    <div class="br-upload-icon" style="font-size:1.5rem;"><i class="fas fa-star"></i></div>
                    <div class="br-upload-text">Click to upload favicon</div>
                    <div class="br-upload-hint">ICO, PNG, SVG · Max 1MB · 32×32 recommended</div>
                </div>
            </div>

            {{-- Brand Colours --}}
            <div class="br-card">
                <div class="br-section-title"><i class="fas fa-fill-drip" style="margin-right:0.4rem;"></i> Brand Colours</div>
                <p style="font-size:0.82rem;color:var(--text-muted);margin:0 0 1.25rem;">These colours are used as hints for your theme. The active theme may override them with its own palette.</p>
                <div class="br-color-row">
                    <div class="form-group">
                        <label class="form-label">Accent / Primary Colour</label>
                        <div class="br-color-field">
                            <input type="color" id="accentColor" value="{{ old('color_accent', $colorAccent) }}" oninput="syncColor('accent')">
                            <input type="text" id="accentText" name="color_accent" value="{{ old('color_accent', $colorAccent) }}" oninput="syncColorText('accent')" maxlength="20">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Background Colour</label>
                        <div class="br-color-field">
                            <input type="color" id="bgColor" value="{{ old('color_bg', $colorBg) }}" oninput="syncColor('bg')">
                            <input type="text" id="bgText" name="color_bg" value="{{ old('color_bg', $colorBg) }}" oninput="syncColorText('bg')" maxlength="20">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;padding:0.875rem;">
                <i class="fas fa-save"></i> Save Branding
            </button>
        </div>

        {{-- Live Preview --}}
        <div>
            <div class="br-card" style="position:sticky;top:1rem;">
                <div class="br-section-title"><i class="fas fa-eye" style="margin-right:0.4rem;"></i> Live Preview</div>
                <div class="br-preview-box" id="previewBox">
                    <div class="br-preview-nav" id="previewNav" style="background:{{ $colorBg }};">
                        <div style="display:flex;align-items:center;gap:0.5rem;">
                            @if($logo)
                            <img src="{{ $logo }}" alt="Logo" style="height:24px;width:auto;" id="previewLogoImg">
                            @else
                            <div id="previewLogoImg" style="width:24px;height:24px;border-radius:6px;background:{{ $colorAccent }};"></div>
                            @endif
                            <span style="font-weight:700;font-size:0.85rem;color:#fff;" id="previewName">{{ $siteName }}</span>
                        </div>
                        <div style="width:24px;height:24px;border-radius:4px;background:{{ $colorAccent }};" id="previewAccentDot"></div>
                    </div>
                    <div class="br-preview-hero" id="previewHero" style="background:{{ $colorBg }};">
                        <div style="font-size:0.7rem;font-weight:700;color:{{ $colorAccent }};text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.5rem;" id="previewTagline">{{ $tagline ?: 'Your Tagline Here' }}</div>
                        <div style="font-size:1.1rem;font-weight:800;color:#fff;margin-bottom:0.5rem;" id="previewTitle">{{ $siteName }}</div>
                        <div style="display:inline-block;background:{{ $colorAccent }};color:#fff;padding:0.4rem 1rem;border-radius:6px;font-size:0.75rem;font-weight:700;margin-top:0.5rem;" id="previewBtn">Get In Touch</div>
                    </div>
                    <div style="padding:0.75rem 1rem;background:rgba(0,0,0,0.3);display:flex;align-items:center;gap:0.4rem;">
                        @if($favicon)
                        <img src="{{ $favicon }}" style="width:14px;height:14px;" id="previewFaviconImg">
                        @else
                        <div id="previewFaviconImg" style="width:14px;height:14px;border-radius:2px;background:{{ $colorAccent }};opacity:0.7;"></div>
                        @endif
                        <span style="font-size:0.7rem;color:rgba(255,255,255,0.5);" id="previewTabName">{{ $siteName }} — Tab Preview</span>
                    </div>
                </div>

                <div style="margin-top:1rem;padding:0.75rem;background:var(--bg-secondary);border-radius:8px;">
                    <div style="font-size:0.72rem;color:var(--text-muted);margin-bottom:0.5rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Browser Tab Preview</div>
                    <div style="display:flex;align-items:center;gap:0.5rem;background:var(--bg-hover);border-radius:6px;padding:0.4rem 0.6rem;">
                        @if($favicon)
                        <img src="{{ $favicon }}" style="width:14px;height:14px;" id="tabFaviconImg">
                        @else
                        <div id="tabFaviconImg" style="width:14px;height:14px;border-radius:2px;background:{{ $colorAccent }};"></div>
                        @endif
                        <span style="font-size:0.72rem;color:var(--text-secondary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;" id="tabTitle">{{ $siteName }}</span>
                        <i class="fas fa-times" style="font-size:0.6rem;color:var(--text-muted);margin-left:auto;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function syncColor(type) {
    const val = document.getElementById(type + 'Color').value;
    document.getElementById(type + 'Text').value = val;
    updatePreview();
}
function syncColorText(type) {
    const val = document.getElementById(type + 'Text').value;
    if (/^#[0-9a-fA-F]{6}$/.test(val)) {
        document.getElementById(type + 'Color').value = val;
    }
    updatePreview();
}

function updatePreview() {
    const name = document.getElementById('siteNameInput').value || 'Your Site';
    const tagline = document.getElementById('taglineInput').value || 'Your Tagline Here';
    const accent = document.getElementById('accentText').value || '#6366f1';
    const bg = document.getElementById('bgText').value || '#0a0a0a';

    document.getElementById('previewName').textContent = name;
    document.getElementById('previewTitle').textContent = name;
    document.getElementById('previewTagline').textContent = tagline;
    document.getElementById('previewTagline').style.color = accent;
    document.getElementById('previewNav').style.background = bg;
    document.getElementById('previewHero').style.background = bg;
    document.getElementById('previewAccentDot').style.background = accent;
    document.getElementById('previewBtn').style.background = accent;
    document.getElementById('tabTitle').textContent = name;
    document.getElementById('previewTabName').textContent = name + ' — Tab Preview';
}

function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const wrap = document.getElementById('logoPreviewWrap');
            const img = document.getElementById('logoPreview');
            if (wrap) wrap.style.display = 'block';
            if (img) { img.src = e.target.result; img.style.display = 'block'; }
            // Update preview nav logo
            const navLogo = document.getElementById('previewLogoImg');
            if (navLogo && navLogo.tagName === 'IMG') navLogo.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewFavicon(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            ['previewFaviconImg', 'tabFaviconImg'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    if (el.tagName === 'IMG') { el.src = e.target.result; }
                    else {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.cssText = el.style.cssText;
                        img.id = id;
                        el.replaceWith(img);
                    }
                }
            });
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
