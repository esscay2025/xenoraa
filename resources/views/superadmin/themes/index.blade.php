@extends('layouts.superadmin')
@section('title', 'Theme Store — Super Admin')
@section('page_title', 'Theme Store')

@section('content')
<div class="sa-content">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
        <div>
            <h2 style="font-size:1.25rem;font-weight:700;margin-bottom:0.25rem;">Theme Store</h2>
            <p style="font-size:0.8rem;color:#71717a;">Manage profession-specific themes. Tenants see only themes matching their profession.</p>
        </div>
        <a href="{{ route('superadmin.themes.create') }}" class="sa-btn-primary">
            <i class="fas fa-plus"></i> Add Theme
        </a>
    </div>

    @if(session('success'))
    <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);color:#22c55e;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:0.85rem;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Stats --}}
    <div class="sa-stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:1.5rem;">
        <div class="sa-stat-card">
            <div class="sa-stat-label">Total Themes</div>
            <div class="sa-stat-value">{{ $themes->count() }}</div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-label">Active</div>
            <div class="sa-stat-value">{{ $themes->where('is_active', true)->count() }}</div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-label">Premium</div>
            <div class="sa-stat-value">{{ $themes->where('is_premium', true)->count() }}</div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-label">Professions</div>
            <div class="sa-stat-value">{{ $themes->pluck('profession_key')->filter()->unique()->count() }}</div>
        </div>
    </div>

    {{-- Themes Grid --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1.25rem;">
        @forelse($themes as $theme)
        @php
            $styles = $theme->getPreviewStyles();
            $bg     = $styles['bg']     ?? '#111';
            $accent = $styles['accent'] ?? '#7c3aed';
            $text   = $styles['text']   ?? '#fff';
        @endphp
        <div class="sa-card" style="overflow:visible;">
            {{-- Preview Banner --}}
            <div style="height:100px;background:{{ $bg }};border-radius:12px 12px 0 0;padding:1rem 1.25rem;position:relative;overflow:hidden;">
                <div style="position:absolute;inset:0;background:linear-gradient(135deg,{{ $bg }} 60%,{{ $accent }}33);"></div>
                <div style="position:relative;z-index:1;">
                    <div style="font-size:0.6rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:{{ $accent }};margin-bottom:0.3rem;">{{ $theme->category }}</div>
                    <div style="font-size:1rem;font-weight:700;color:{{ $text }};line-height:1.2;">{{ $theme->name }}</div>
                    <div style="font-size:0.7rem;color:{{ $text }}99;margin-top:0.2rem;">{{ $theme->hero_sub }}</div>
                </div>
                <div style="position:absolute;top:0.75rem;right:0.75rem;display:flex;gap:0.4rem;">
                    @if($theme->is_premium)
                    <span style="font-size:0.55rem;font-weight:700;background:{{ $accent }};color:#fff;padding:0.15rem 0.5rem;border-radius:4px;letter-spacing:0.08em;text-transform:uppercase;">Premium</span>
                    @endif
                    @if(!$theme->is_active)
                    <span style="font-size:0.55rem;font-weight:700;background:#ef4444;color:#fff;padding:0.15rem 0.5rem;border-radius:4px;letter-spacing:0.08em;text-transform:uppercase;">Inactive</span>
                    @endif
                </div>
            </div>

            {{-- Details --}}
            <div style="padding:1rem 1.25rem;">
                <p style="font-size:0.78rem;color:#a1a1aa;margin-bottom:0.75rem;line-height:1.5;">{{ Str::limit($theme->description, 100) }}</p>

                <div style="display:flex;flex-wrap:wrap;gap:0.3rem;margin-bottom:0.75rem;">
                    @foreach(($theme->tags ?? []) as $tag)
                    <span style="font-size:0.6rem;font-weight:600;background:rgba(255,255,255,0.05);border:1px solid #222;color:#a1a1aa;padding:0.15rem 0.5rem;border-radius:4px;">{{ $tag }}</span>
                    @endforeach
                </div>

                <div style="font-size:0.72rem;color:#52525b;margin-bottom:0.75rem;">
                    <i class="fas fa-user-tie" style="width:14px;"></i> {{ $theme->best_for }}
                </div>

                <div style="font-size:0.72rem;color:#52525b;margin-bottom:1rem;">
                    <i class="fas fa-key" style="width:14px;"></i> Profession Key: <code style="color:#a855f7;">{{ $theme->profession_key ?? 'any' }}</code>
                </div>

                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                    <a href="{{ route('superadmin.themes.preview', $theme) }}" target="_blank" class="sa-action-btn" style="color:#a855f7;border-color:#a855f733;">
                        <i class="fas fa-eye"></i> Preview
                    </a>
                    <a href="{{ route('superadmin.themes.edit', $theme) }}" class="sa-action-btn">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('superadmin.themes.toggle', $theme) }}" style="display:inline;">
                        @csrf @method('PATCH')
                        <button type="submit" class="sa-action-btn" style="{{ $theme->is_active ? 'color:#ef4444;' : 'color:#22c55e;' }}">
                            <i class="fas fa-{{ $theme->is_active ? 'eye-slash' : 'eye' }}"></i>
                            {{ $theme->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('superadmin.themes.destroy', $theme) }}" style="display:inline;" onsubmit="return confirm('Delete this theme?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="sa-action-btn danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:3rem;color:#52525b;">
            <i class="fas fa-palette" style="font-size:2rem;margin-bottom:1rem;display:block;"></i>
            No themes found. <a href="{{ route('superadmin.themes.create') }}" style="color:#a855f7;">Add the first theme</a>.
        </div>
        @endforelse
    </div>

</div>
@endsection
