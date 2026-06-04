@extends('layouts.admin')
@section('title', 'Theme Store')
@section('page-title', 'Theme Store')

@section('content')
<style>
.ts-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem; }
.ts-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(320px,1fr)); gap:1.5rem; }
.ts-card {
    background:var(--bg-card);
    border:2px solid var(--border);
    border-radius:16px;
    overflow:hidden;
    transition:all 0.25s;
    position:relative;
}
.ts-card:hover { transform:translateY(-4px); box-shadow:0 16px 48px rgba(0,0,0,0.4); }
.ts-card.active-theme { border-color: var(--accent, #6366f1); box-shadow: 0 0 0 3px rgba(99,102,241,0.2); }
.ts-preview {
    height: 200px;
    position: relative;
    overflow: hidden;
    cursor: pointer;
}
.ts-preview-inner {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    padding: 1.25rem;
    position: relative;
}
.ts-preview-nav { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; }
.ts-preview-logo { width:80px; height:8px; border-radius:4px; opacity:0.8; }
.ts-preview-nav-links { display:flex; gap:8px; }
.ts-preview-nav-link { width:30px; height:6px; border-radius:3px; opacity:0.4; }
.ts-preview-hero { flex:1; display:flex; flex-direction:column; justify-content:center; }
.ts-preview-h1 { height:10px; border-radius:5px; margin-bottom:8px; width:70%; opacity:0.9; }
.ts-preview-h2 { height:7px; border-radius:3px; margin-bottom:12px; width:50%; opacity:0.6; }
.ts-preview-p { height:5px; border-radius:3px; margin-bottom:5px; opacity:0.4; }
.ts-preview-p.short { width:60%; }
.ts-preview-btn { width:80px; height:22px; border-radius:6px; margin-top:10px; opacity:0.9; }
.ts-preview-cards { display:flex; gap:8px; margin-top:10px; }
.ts-preview-card { flex:1; height:40px; border-radius:6px; opacity:0.25; }
.ts-active-badge {
    position:absolute;
    top:10px;
    right:10px;
    background:#22c55e;
    color:#fff;
    font-size:0.65rem;
    font-weight:700;
    padding:0.2rem 0.6rem;
    border-radius:20px;
    letter-spacing:0.05em;
    text-transform:uppercase;
}
.ts-premium-badge {
    position:absolute;
    top:10px;
    left:10px;
    background:linear-gradient(135deg,#f59e0b,#ef4444);
    color:#fff;
    font-size:0.62rem;
    font-weight:700;
    padding:0.2rem 0.6rem;
    border-radius:20px;
    letter-spacing:0.05em;
    text-transform:uppercase;
}
.ts-body { padding:1.25rem 1.25rem 1rem; }
.ts-name { font-size:1.1rem; font-weight:800; margin:0 0 0.2rem; }
.ts-category { font-size:0.72rem; color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.6rem; }
.ts-desc { font-size:0.82rem; color:var(--text-secondary); line-height:1.55; margin-bottom:0.75rem; }
.ts-tags { display:flex; flex-wrap:wrap; gap:0.35rem; margin-bottom:0.75rem; }
.ts-tag { font-size:0.68rem; font-weight:600; padding:0.15rem 0.5rem; border-radius:20px; background:var(--bg-hover); color:var(--text-secondary); }
.ts-sections { font-size:0.75rem; color:var(--text-muted); margin-bottom:0.75rem; }
.ts-sections strong { color:var(--text-secondary); }
.ts-best-for { font-size:0.75rem; color:var(--text-muted); margin-bottom:1rem; }
.ts-best-for strong { color:var(--text-secondary); }
.ts-footer { display:flex; gap:0.75rem; padding:0 1.25rem 1.25rem; }
.ts-btn-activate {
    flex:1;
    padding:0.6rem;
    border-radius:8px;
    font-size:0.82rem;
    font-weight:700;
    border:none;
    cursor:pointer;
    transition:all 0.2s;
}
.ts-btn-activate.is-active { background:rgba(34,197,94,0.15); color:#22c55e; cursor:default; }
.ts-btn-activate.not-active { background:var(--accent,#6366f1); color:#fff; }
.ts-btn-activate.not-active:hover { opacity:0.85; }
.ts-btn-preview { padding:0.6rem 0.9rem; border-radius:8px; font-size:0.82rem; font-weight:600; border:1px solid var(--border); background:transparent; color:var(--text-secondary); cursor:pointer; transition:all 0.2s; }
.ts-btn-preview:hover { color:var(--text-primary); border-color:var(--text-secondary); }

/* Preview Modal */
.ts-modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.85); z-index:9999; display:flex; align-items:center; justify-content:center; padding:1rem; opacity:0; pointer-events:none; transition:opacity 0.25s; }
.ts-modal-overlay.open { opacity:1; pointer-events:all; }
.ts-modal { background:var(--bg-card); border:1px solid var(--border); border-radius:20px; width:100%; max-width:900px; max-height:90vh; overflow:hidden; display:flex; flex-direction:column; }
.ts-modal-header { padding:1.25rem 1.5rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.ts-modal-title { font-size:1.1rem; font-weight:700; }
.ts-modal-close { background:none; border:none; color:var(--text-secondary); font-size:1.2rem; cursor:pointer; padding:0.25rem; }
.ts-modal-close:hover { color:var(--text-primary); }
.ts-modal-body { flex:1; overflow-y:auto; padding:1.5rem; }
.ts-demo-site { border-radius:12px; overflow:hidden; border:1px solid var(--border); }
.ts-demo-nav { padding:0.75rem 1.5rem; display:flex; align-items:center; justify-content:space-between; }
.ts-demo-hero { padding:4rem 2rem; text-align:center; }
.ts-demo-hero h1 { font-size:2rem; font-weight:800; margin:0 0 0.5rem; }
.ts-demo-hero p { font-size:1rem; margin:0 0 1.5rem; opacity:0.7; }
.ts-demo-hero .ts-demo-btn { display:inline-block; padding:0.75rem 2rem; border-radius:8px; font-weight:700; font-size:0.9rem; text-decoration:none; }
.ts-demo-cards { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; padding:1.5rem; }
.ts-demo-card { padding:1.25rem; border-radius:10px; }
.ts-demo-card h3 { font-size:0.9rem; font-weight:700; margin:0 0 0.4rem; }
.ts-demo-card p { font-size:0.8rem; margin:0; opacity:0.7; }
</style>

<div class="ts-header">
    <div>
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.25rem;">
            <a href="{{ route('admin.site.index') }}" style="color:var(--text-muted);text-decoration:none;font-size:0.85rem;"><i class="fas fa-arrow-left"></i> Site Builder</a>
        </div>
        <h1 style="font-size:1.75rem;font-weight:800;margin:0;">Theme Store</h1>
        <p style="color:var(--text-secondary);margin:0.25rem 0 0;font-size:0.9rem;">6 premium, profession-specific themes. Preview and activate with one click.</p>
    </div>
</div>

<div class="ts-grid">
@foreach($themes as $theme)
@php
    $isActive = $activeTheme === $theme['id'];
    $css = [];
    foreach(explode(';', $theme['preview_css']) as $pair) {
        [$k,$v] = explode(':', $pair, 2);
        $css[$k] = $v;
    }
@endphp
<div class="ts-card {{ $isActive ? 'active-theme' : '' }}" id="card-{{ $theme['id'] }}">
    {{-- Preview thumbnail --}}
    <div class="ts-preview" onclick="openPreview('{{ $theme['id'] }}')" title="Click to preview">
        <div class="ts-preview-inner" style="background:{{ $css['bg'] }};">
            <div class="ts-preview-nav">
                <div class="ts-preview-logo" style="background:{{ $css['accent'] }};"></div>
                <div class="ts-preview-nav-links">
                    <div class="ts-preview-nav-link" style="background:{{ $css['text'] }};"></div>
                    <div class="ts-preview-nav-link" style="background:{{ $css['text'] }};"></div>
                    <div class="ts-preview-nav-link" style="background:{{ $css['text'] }};"></div>
                    <div class="ts-preview-nav-link" style="background:{{ $css['accent'] }};width:40px;opacity:0.9;"></div>
                </div>
            </div>
            <div class="ts-preview-hero">
                <div class="ts-preview-h1" style="background:{{ $css['text'] }};"></div>
                <div class="ts-preview-h2" style="background:{{ $css['accent'] }};"></div>
                <div class="ts-preview-p" style="background:{{ $css['text'] }};"></div>
                <div class="ts-preview-p short" style="background:{{ $css['text'] }};"></div>
                <div class="ts-preview-btn" style="background:{{ $css['accent'] }};"></div>
            </div>
            <div class="ts-preview-cards">
                <div class="ts-preview-card" style="background:{{ $css['card'] }};border:1px solid rgba(128,128,128,0.2);"></div>
                <div class="ts-preview-card" style="background:{{ $css['card'] }};border:1px solid rgba(128,128,128,0.2);"></div>
                <div class="ts-preview-card" style="background:{{ $css['card'] }};border:1px solid rgba(128,128,128,0.2);"></div>
            </div>
        </div>
        @if($isActive)
            <div class="ts-active-badge"><i class="fas fa-check"></i> Active</div>
        @endif
        @if($theme['premium'])
            <div class="ts-premium-badge"><i class="fas fa-crown"></i> Premium</div>
        @endif
    </div>

    <div class="ts-body">
        <div class="ts-name">{{ $theme['name'] }}</div>
        <div class="ts-category">{{ $theme['category'] }}</div>
        <div class="ts-desc">{{ $theme['description'] }}</div>
        <div class="ts-tags">
            @foreach($theme['tags'] as $tag)
                <span class="ts-tag">{{ $tag }}</span>
            @endforeach
        </div>
        <div class="ts-sections"><strong>Sections:</strong> {{ implode(', ', $theme['sections']) }}</div>
        <div class="ts-best-for"><strong>Best for:</strong> {{ $theme['best_for'] }}</div>
    </div>

    <div class="ts-footer">
        <button class="ts-btn-activate {{ $isActive ? 'is-active' : 'not-active' }}"
            onclick="{{ $isActive ? '' : 'activateTheme(\''.$theme['id'].'\')' }}"
            id="btn-{{ $theme['id'] }}">
            @if($isActive)
                <i class="fas fa-check"></i> Currently Active
            @else
                <i class="fas fa-bolt"></i> Activate Theme
            @endif
        </button>
        <button class="ts-btn-preview" onclick="openPreview('{{ $theme['id'] }}')">
            <i class="fas fa-eye"></i> Preview
        </button>
    </div>
</div>
@endforeach
</div>

{{-- Preview Modal --}}
<div class="ts-modal-overlay" id="previewModal">
    <div class="ts-modal">
        <div class="ts-modal-header">
            <div class="ts-modal-title" id="modalTitle">Theme Preview</div>
            <button class="ts-modal-close" onclick="closePreview()"><i class="fas fa-times"></i></button>
        </div>
        <div class="ts-modal-body" id="modalBody"></div>
    </div>
</div>

<script>
const themes = @json($themes);
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function openPreview(id) {
    const t = themes[id];
    const css = {};
    t.preview_css.split(';').forEach(p => { const [k,v] = p.split(':'); css[k]=v; });

    const isDark = css.bg.startsWith('#0') || css.bg.startsWith('#1') || css.bg === '#0f0f0f';
    const textColor = isDark ? '#fff' : css.text;
    const mutedColor = isDark ? 'rgba(255,255,255,0.55)' : 'rgba(0,0,0,0.55)';
    const cardBg = css.card;

    document.getElementById('modalTitle').innerHTML = `<i class="fas fa-palette" style="color:${css.accent};margin-right:0.5rem;"></i> ${t.name} — ${t.category}`;

    document.getElementById('modalBody').innerHTML = `
        <div class="ts-demo-site">
            <div class="ts-demo-nav" style="background:${css.bg};border-bottom:1px solid rgba(128,128,128,0.15);">
                <div style="font-weight:800;font-size:1rem;color:${textColor};">✦ ${t.name}</div>
                <div style="display:flex;gap:1.5rem;">
                    ${t.sections.map(s => `<span style="font-size:0.8rem;color:${mutedColor};cursor:pointer;">${s}</span>`).join('')}
                </div>
                <div style="background:${css.accent};color:#fff;padding:0.4rem 1rem;border-radius:6px;font-size:0.8rem;font-weight:700;">Get Started</div>
            </div>
            <div class="ts-demo-hero" style="background:${css.bg};">
                <div style="display:inline-block;background:rgba(128,128,128,0.1);color:${css.accent};font-size:0.72rem;font-weight:700;padding:0.25rem 0.75rem;border-radius:20px;margin-bottom:1rem;letter-spacing:0.08em;text-transform:uppercase;">${t.category}</div>
                <h1 style="color:${textColor};font-size:2rem;font-weight:800;margin:0 0 0.5rem;">${t.hero_title}</h1>
                <p style="color:${mutedColor};font-size:1rem;margin:0 0 1.5rem;">${t.hero_sub}</p>
                <p style="color:${mutedColor};font-size:0.85rem;max-width:500px;margin:0 auto 1.5rem;line-height:1.6;">
                    Welcome to my professional portfolio. I help clients achieve their goals through expertise, dedication, and a results-driven approach.
                </p>
                <a href="#" class="ts-demo-btn" style="background:${css.accent};color:#fff;">Get In Touch</a>
                <a href="#" class="ts-demo-btn" style="background:transparent;color:${css.accent};border:1px solid ${css.accent};margin-left:0.75rem;">View Portfolio</a>
            </div>
            <div class="ts-demo-cards" style="background:${isDark ? 'rgba(255,255,255,0.03)' : 'rgba(0,0,0,0.03)'};">
                ${t.sections.slice(1,4).map(s => `
                    <div class="ts-demo-card" style="background:${cardBg};border:1px solid rgba(128,128,128,0.15);">
                        <div style="width:32px;height:32px;border-radius:8px;background:${css.accent};opacity:0.8;margin-bottom:0.75rem;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.9rem;">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 style="color:${textColor};">${s}</h3>
                        <p style="color:${mutedColor};">Professionally crafted ${s.toLowerCase()} section with rich content and engaging layout.</p>
                    </div>
                `).join('')}
            </div>
            <div style="padding:1.5rem;background:${css.bg};border-top:1px solid rgba(128,128,128,0.1);text-align:center;">
                <div style="font-size:0.75rem;color:${mutedColor};">© 2026 · Powered by Xenoraa · ${t.name} Theme</div>
            </div>
        </div>
        <div style="margin-top:1.25rem;display:flex;justify-content:center;">
            <button onclick="activateTheme('${id}');closePreview();" style="background:${css.accent};color:#fff;border:none;padding:0.75rem 2rem;border-radius:8px;font-weight:700;font-size:0.9rem;cursor:pointer;">
                <i class="fas fa-bolt"></i> Activate ${t.name}
            </button>
        </div>
    `;

    document.getElementById('previewModal').classList.add('open');
}

function closePreview() {
    document.getElementById('previewModal').classList.remove('open');
}

document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) closePreview();
});

function activateTheme(id) {
    const btn = document.getElementById('btn-' + id);
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Activating...';

    fetch('{{ route("admin.site.themes.activate") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ theme: id })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Remove active state from all cards
            document.querySelectorAll('.ts-card').forEach(c => c.classList.remove('active-theme'));
            document.querySelectorAll('.ts-btn-activate').forEach(b => {
                b.className = 'ts-btn-activate not-active';
                b.innerHTML = '<i class="fas fa-bolt"></i> Activate Theme';
                b.disabled = false;
                b.onclick = function() { activateTheme(b.id.replace('btn-','')); };
            });

            // Set active on selected
            document.getElementById('card-' + id).classList.add('active-theme');
            btn.className = 'ts-btn-activate is-active';
            btn.innerHTML = '<i class="fas fa-check"></i> Currently Active';
            btn.disabled = true;
            btn.onclick = null;

            // Show toast
            showToast('Theme activated successfully!', 'success');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-bolt"></i> Activate Theme';
        showToast('Failed to activate theme. Please try again.', 'error');
    });
}

function showToast(msg, type) {
    const toast = document.createElement('div');
    toast.style.cssText = `position:fixed;bottom:2rem;right:2rem;background:${type==='success'?'#22c55e':'#ef4444'};color:#fff;padding:0.75rem 1.25rem;border-radius:10px;font-weight:600;font-size:0.85rem;z-index:99999;box-shadow:0 8px 24px rgba(0,0,0,0.3);transition:all 0.3s;`;
    toast.innerHTML = `<i class="fas fa-${type==='success'?'check':'times'}-circle" style="margin-right:0.5rem;"></i>${msg}`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity='0'; setTimeout(() => toast.remove(), 300); }, 3000);
}
</script>
@endsection
