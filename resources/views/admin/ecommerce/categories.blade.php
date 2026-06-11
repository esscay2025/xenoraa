@extends('layouts.admin')
@section('title', 'Product Categories')
@php
    $contentActive = false; $recruitmentActive = false; $financeActive = false;
    $administrationActive = false; $communityActive = false; $crmActive = false;
    $ecommerceActive = true; $siteActive = false;
@endphp
@section('content')
<div class="ec-page">

    {{-- Header --}}
    <div class="ec-header">
        <div>
            <a href="{{ route('admin.ecommerce.dashboard') }}" class="ec-breadcrumb"><i class="fas fa-arrow-left"></i> E-commerce</a>
            <h1 class="ec-title"><i class="fas fa-tags"></i> Product Categories</h1>
            <p class="ec-subtitle">Organise your products into categories</p>
        </div>
    </div>

    @if(session('success'))
        <div class="ec-alert ec-alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="ec-alert ec-alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    <div class="ec-grid-main-side">

        {{-- Category List --}}
        <div class="ec-card">
            <div class="ec-card-header">
                <h3><i class="fas fa-list"></i> All Categories</h3>
                <span class="ec-badge ec-badge-primary">{{ $categories->count() }} total</span>
            </div>
            @forelse($categories as $cat)
            <div class="ec-cat-item">
                <div class="ec-cat-icon">
                    <i class="fas {{ $cat->icon ?? 'fa-tag' }}"></i>
                </div>
                <div style="flex:1;">
                    <div class="ec-cat-name">{{ $cat->name }}</div>
                    @if($cat->description)
                        <div class="ec-cat-meta">{{ Str::limit($cat->description, 60) }}</div>
                    @endif
                    @if($cat->children->count() > 0)
                        <div class="ec-cat-meta" style="margin-top:4px;">
                            @foreach($cat->children as $sub)
                                <span class="ec-badge ec-badge-muted" style="margin-right:4px;">{{ $sub->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <span class="ec-badge ec-badge-info" style="margin-right:8px;">{{ $cat->products_count }} products</span>
                <div class="ec-cat-actions">
                    <button onclick="editCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description ?? '') }}', '{{ $cat->icon ?? '' }}', '{{ $cat->parent_id ?? '' }}')"
                        class="ec-btn-icon" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form method="POST" action="{{ route('admin.ecommerce.categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category?')" style="display:contents;">
                        @csrf @method('DELETE')
                        <button type="submit" class="ec-btn-icon danger" title="Delete"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            @empty
            <div class="ec-empty">
                <i class="fas fa-tags"></i>
                <p>No categories yet. Create your first one.</p>
            </div>
            @endforelse
        </div>

        {{-- Add / Edit Category Form --}}
        <div>
            <div class="ec-card" id="categoryFormCard">
                <div class="ec-card-header">
                    <h3 id="formTitle"><i class="fas fa-plus-circle"></i> Add Category</h3>
                </div>
                <div class="ec-card-body">
                    <form method="POST" id="categoryForm" action="{{ route('admin.ecommerce.categories.store') }}">
                        @csrf
                        <span id="methodField"></span>

                        <div class="ec-form-group">
                            <label class="ec-label">Name <span class="required">*</span></label>
                            <input type="text" name="name" id="catName" class="ec-input" required placeholder="e.g. Electronics">
                        </div>

                        <div class="ec-form-group">
                            <label class="ec-label">Parent Category</label>
                            <select name="parent_id" id="catParent" class="ec-select">
                                <option value="">— Top Level —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="ec-form-group">
                            <label class="ec-label">Icon (Font Awesome class)</label>
                            <input type="text" name="icon" id="catIcon" class="ec-input" placeholder="e.g. fa-laptop">
                            <p style="font-size:0.72rem;color:var(--ec-muted);margin:4px 0 0;">Use any <a href="https://fontawesome.com/icons" target="_blank" style="color:var(--ec-primary);">Font Awesome</a> icon class</p>
                        </div>

                        <div class="ec-form-group">
                            <label class="ec-label">Description</label>
                            <textarea name="description" id="catDesc" class="ec-textarea" rows="3" placeholder="Optional description…"></textarea>
                        </div>

                        <div style="display:flex;gap:8px;">
                            <button type="submit" class="ec-btn ec-btn-primary" style="flex:1;">
                                <i class="fas fa-save"></i> <span id="submitLabel">Save Category</span>
                            </button>
                            <button type="button" onclick="resetForm()" class="ec-btn ec-btn-secondary">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function editCategory(id, name, desc, icon, parentId) {
    document.getElementById('formTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Category';
    document.getElementById('submitLabel').textContent = 'Update Category';
    document.getElementById('catName').value = name;
    document.getElementById('catDesc').value = desc;
    document.getElementById('catIcon').value = icon;
    document.getElementById('catParent').value = parentId || '';
    document.getElementById('categoryForm').action = '/admin/ecommerce/categories/' + id;
    document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('categoryFormCard').scrollIntoView({behavior:'smooth'});
}
function resetForm() {
    document.getElementById('formTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Add Category';
    document.getElementById('submitLabel').textContent = 'Save Category';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryForm').action = '{{ route("admin.ecommerce.categories.store") }}';
    document.getElementById('methodField').innerHTML = '';
}
</script>
@endsection
