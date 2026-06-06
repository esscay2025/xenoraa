@extends('layouts.superadmin')
@section('title', ($theme ? 'Edit Theme' : 'Add Theme') . ' — Super Admin')
@section('page_title', $theme ? 'Edit Theme: ' . $theme->name : 'Add New Theme')

@section('content')
<div class="sa-content" style="max-width:800px;">

    <div style="margin-bottom:1.5rem;">
        <a href="{{ route('superadmin.themes.index') }}" style="color:#71717a;font-size:0.8rem;text-decoration:none;">
            <i class="fas fa-arrow-left"></i> Back to Theme Store
        </a>
    </div>

    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:0.85rem;">
        <ul style="margin:0;padding-left:1.25rem;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ $theme ? route('superadmin.themes.update', $theme) : route('superadmin.themes.store') }}" enctype="multipart/form-data">
        @csrf
        @if($theme) @method('PUT') @endif

        <div class="sa-card" style="padding:1.5rem;margin-bottom:1.25rem;">
            <h3 style="font-size:0.875rem;font-weight:700;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid #1a1a1a;">Basic Information</h3>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">Theme Name *</label>
                    <input type="text" name="name" value="{{ old('name', $theme?->name) }}" required
                        style="width:100%;background:#1a1a1a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.875rem;color:#fff;font-size:0.85rem;outline:none;">
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">Slug (auto-generated if blank)</label>
                    <input type="text" name="slug" value="{{ old('slug', $theme?->slug) }}"
                        style="width:100%;background:#1a1a1a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.875rem;color:#fff;font-size:0.85rem;outline:none;" placeholder="e.g. consultant">
                </div>
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">Category *</label>
                <input type="text" name="category" value="{{ old('category', $theme?->category) }}" required
                    style="width:100%;background:#1a1a1a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.875rem;color:#fff;font-size:0.85rem;outline:none;" placeholder="e.g. IT & Technology">
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">Description</label>
                <textarea name="description" rows="3"
                    style="width:100%;background:#1a1a1a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.875rem;color:#fff;font-size:0.85rem;outline:none;resize:vertical;">{{ old('description', $theme?->description) }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">Tags (comma-separated)</label>
                    <input type="text" name="tags" value="{{ old('tags', $theme ? implode(', ', $theme->tags ?? []) : '') }}"
                        style="width:100%;background:#1a1a1a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.875rem;color:#fff;font-size:0.85rem;outline:none;" placeholder="Dark, Minimal, Professional">
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">Best For</label>
                    <input type="text" name="best_for" value="{{ old('best_for', $theme?->best_for) }}"
                        style="width:100%;background:#1a1a1a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.875rem;color:#fff;font-size:0.85rem;outline:none;" placeholder="IT Consultants, Architects">
                </div>
            </div>
        </div>

        <div class="sa-card" style="padding:1.5rem;margin-bottom:1.25rem;">
            <h3 style="font-size:0.875rem;font-weight:700;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid #1a1a1a;">Profession Matching</h3>
            <p style="font-size:0.78rem;color:#71717a;margin-bottom:1rem;">Tenants will only see themes that match their profession. Set the profession key and keywords below.</p>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">Profession Key</label>
                    <input type="text" name="profession_key" value="{{ old('profession_key', $theme?->profession_key) }}"
                        style="width:100%;background:#1a1a1a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.875rem;color:#fff;font-size:0.85rem;outline:none;" placeholder="e.g. consultant">
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">Profession Keywords (comma-separated)</label>
                    <input type="text" name="profession_keywords" value="{{ old('profession_keywords', $theme ? implode(', ', $theme->profession_keywords ?? []) : '') }}"
                        style="width:100%;background:#1a1a1a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.875rem;color:#fff;font-size:0.85rem;outline:none;" placeholder="it, tech, software, developer">
                </div>
            </div>
        </div>

        <div class="sa-card" style="padding:1.5rem;margin-bottom:1.25rem;">
            <h3 style="font-size:0.875rem;font-weight:700;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid #1a1a1a;">Visual Settings</h3>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">Accent Color</label>
                    <div style="display:flex;gap:0.5rem;align-items:center;">
                        <input type="color" name="accent_color" value="{{ old('accent_color', $theme?->accent_color ?? '#7c3aed') }}"
                            style="width:40px;height:36px;border:1px solid #222;border-radius:6px;background:#1a1a1a;cursor:pointer;">
                        <input type="text" id="accent_hex" value="{{ old('accent_color', $theme?->accent_color ?? '#7c3aed') }}"
                            style="flex:1;background:#1a1a1a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.875rem;color:#fff;font-size:0.85rem;outline:none;" readonly>
                    </div>
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">Background Color</label>
                    <div style="display:flex;gap:0.5rem;align-items:center;">
                        <input type="color" name="bg_color" value="{{ old('bg_color', $theme?->bg_color ?? '#ffffff') }}"
                            style="width:40px;height:36px;border:1px solid #222;border-radius:6px;background:#1a1a1a;cursor:pointer;">
                        <input type="text" id="bg_hex" value="{{ old('bg_color', $theme?->bg_color ?? '#ffffff') }}"
                            style="flex:1;background:#1a1a1a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.875rem;color:#fff;font-size:0.85rem;outline:none;" readonly>
                    </div>
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $theme?->sort_order ?? 0) }}"
                        style="width:100%;background:#1a1a1a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.875rem;color:#fff;font-size:0.85rem;outline:none;">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">Hero Title</label>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $theme?->hero_title) }}"
                        style="width:100%;background:#1a1a1a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.875rem;color:#fff;font-size:0.85rem;outline:none;" placeholder="IT Consultant & Solution Architect">
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">Hero Sub-title</label>
                    <input type="text" name="hero_sub" value="{{ old('hero_sub', $theme?->hero_sub) }}"
                        style="width:100%;background:#1a1a1a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.875rem;color:#fff;font-size:0.85rem;outline:none;" placeholder="Cloud · DevOps · Digital Transformation">
                </div>
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.75rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">Thumbnail Image</label>
                <input type="file" name="thumbnail_file" accept="image/*"
                    style="width:100%;background:#1a1a1a;border:1px solid #222;border-radius:8px;padding:0.6rem 0.875rem;color:#a1a1aa;font-size:0.85rem;">
                @if($theme?->thumbnail)
                <div style="margin-top:0.5rem;">
                    <img src="{{ asset('storage/' . $theme->thumbnail) }}" alt="Thumbnail" style="height:60px;border-radius:6px;">
                </div>
                @endif
            </div>
        </div>

        <div class="sa-card" style="padding:1.5rem;margin-bottom:1.25rem;">
            <h3 style="font-size:0.875rem;font-weight:700;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid #1a1a1a;">Status</h3>
            <div style="display:flex;gap:2rem;">
                <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-size:0.85rem;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $theme?->is_active ?? true) ? 'checked' : '' }}
                        style="width:16px;height:16px;accent-color:#7c3aed;">
                    Active (visible to tenants)
                </label>
                <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-size:0.85rem;">
                    <input type="checkbox" name="is_premium" value="1" {{ old('is_premium', $theme?->is_premium) ? 'checked' : '' }}
                        style="width:16px;height:16px;accent-color:#7c3aed;">
                    Premium Theme
                </label>
            </div>
        </div>

        <div style="display:flex;gap:0.75rem;">
            <button type="submit" class="sa-btn-primary">
                <i class="fas fa-save"></i> {{ $theme ? 'Update Theme' : 'Create Theme' }}
            </button>
            <a href="{{ route('superadmin.themes.index') }}" class="sa-action-btn" style="padding:0.5rem 1.25rem;">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
document.querySelector('[name="accent_color"]').addEventListener('input', function() {
    document.getElementById('accent_hex').value = this.value;
});
document.querySelector('[name="bg_color"]').addEventListener('input', function() {
    document.getElementById('bg_hex').value = this.value;
});
</script>
@endsection
