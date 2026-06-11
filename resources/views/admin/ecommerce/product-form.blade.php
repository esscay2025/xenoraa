@extends('layouts.admin')
@section('title', isset($product) ? 'Edit Product' : 'Add Product')
@php
    $contentActive = false; $recruitmentActive = false; $financeActive = false;
    $administrationActive = false; $communityActive = false; $crmActive = false;
    $ecommerceActive = true; $siteActive = false;
@endphp
@section('content')
<div class="ec-page" style="max-width:1100px;">

    {{-- Header --}}
    <div class="ec-header">
        <div>
            <a href="{{ route('admin.ecommerce.products') }}" class="ec-breadcrumb"><i class="fas fa-arrow-left"></i> All Products</a>
            <h1 class="ec-title">
                <i class="fas {{ isset($product) ? 'fa-edit' : 'fa-plus-circle' }}"></i>
                {{ isset($product) ? 'Edit Product' : 'Add New Product' }}
            </h1>
        </div>
        @if(isset($product))
        <div class="ec-header-actions">
            <form method="POST" action="{{ route('admin.ecommerce.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                @csrf @method('DELETE')
                <button type="submit" class="ec-btn ec-btn-danger ec-btn-sm"><i class="fas fa-trash"></i> Delete</button>
            </form>
        </div>
        @endif
    </div>

    @if($errors->any())
        <div class="ec-alert ec-alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>@foreach($errors->all() as $error)<div>• {{ $error }}</div>@endforeach</div>
        </div>
    @endif

    <form method="POST" action="{{ isset($product) ? route('admin.ecommerce.products.update', $product) : route('admin.ecommerce.products.store') }}">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <div class="ec-grid-main-side">

            {{-- Left: Main Fields --}}
            <div>
                {{-- Basic Info --}}
                <div class="ec-card">
                    <div class="ec-card-header">
                        <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                    </div>
                    <div class="ec-card-body">
                        <div class="ec-form-group">
                            <label class="ec-label">Product Name <span class="required">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="ec-input" required placeholder="Enter product name">
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">Description</label>
                            <textarea name="description" class="ec-textarea" rows="5" placeholder="Describe your product…">{{ old('description', $product->description ?? '') }}</textarea>
                        </div>
                        <div class="ec-grid-2" style="gap:12px;">
                            <div class="ec-form-group">
                                <label class="ec-label">SKU</label>
                                <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" class="ec-input" placeholder="e.g. PROD-001">
                            </div>
                            <div class="ec-form-group">
                                <label class="ec-label">Barcode</label>
                                <input type="text" name="barcode" value="{{ old('barcode', $product->barcode ?? '') }}" class="ec-input" placeholder="EAN / UPC">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="ec-card">
                    <div class="ec-card-header">
                        <h3><i class="fas fa-rupee-sign"></i> Pricing</h3>
                    </div>
                    <div class="ec-card-body">
                        <div class="ec-grid-2" style="gap:12px;">
                            <div class="ec-form-group">
                                <label class="ec-label">Price <span class="required">*</span></label>
                                <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" class="ec-input" step="0.01" min="0" required placeholder="0.00">
                            </div>
                            <div class="ec-form-group">
                                <label class="ec-label">Compare Price</label>
                                <input type="number" name="compare_price" value="{{ old('compare_price', $product->compare_price ?? '') }}" class="ec-input" step="0.01" min="0" placeholder="Original / MRP">
                            </div>
                            <div class="ec-form-group">
                                <label class="ec-label">Cost Price</label>
                                <input type="number" name="cost_price" value="{{ old('cost_price', $product->cost_price ?? '') }}" class="ec-input" step="0.01" min="0" placeholder="Your cost">
                            </div>
                            <div class="ec-form-group">
                                <label class="ec-label">Tax Rate (%)</label>
                                <input type="number" name="tax_rate" value="{{ old('tax_rate', $product->tax_rate ?? '') }}" class="ec-input" step="0.01" min="0" max="100" placeholder="e.g. 18">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Inventory --}}
                <div class="ec-card">
                    <div class="ec-card-header">
                        <h3><i class="fas fa-warehouse"></i> Inventory</h3>
                    </div>
                    <div class="ec-card-body">
                        <div class="ec-form-group" style="display:flex;align-items:center;gap:12px;">
                            <label class="ec-toggle">
                                <input type="checkbox" name="track_inventory" value="1" {{ old('track_inventory', $product->track_inventory ?? false) ? 'checked' : '' }}>
                                <span class="ec-toggle-slider"></span>
                            </label>
                            <span style="font-size:0.875rem;color:var(--ec-text);">Track inventory for this product</span>
                        </div>
                        <div class="ec-grid-2" style="gap:12px;">
                            <div class="ec-form-group">
                                <label class="ec-label">Stock Quantity</label>
                                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" class="ec-input" min="0">
                            </div>
                            <div class="ec-form-group">
                                <label class="ec-label">Low Stock Alert</label>
                                <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 5) }}" class="ec-input" min="0">
                            </div>
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">Stock Status</label>
                            <select name="stock_status" class="ec-select">
                                <option value="in_stock"     {{ old('stock_status', $product->stock_status ?? 'in_stock')==='in_stock'?'selected':'' }}>In Stock</option>
                                <option value="out_of_stock" {{ old('stock_status', $product->stock_status ?? '')==='out_of_stock'?'selected':'' }}>Out of Stock</option>
                                <option value="on_backorder" {{ old('stock_status', $product->stock_status ?? '')==='on_backorder'?'selected':'' }}>On Backorder</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Sidebar --}}
            <div>
                {{-- Publish --}}
                <div class="ec-card">
                    <div class="ec-card-header">
                        <h3><i class="fas fa-rocket"></i> Publish</h3>
                    </div>
                    <div class="ec-card-body">
                        <div class="ec-form-group">
                            <label class="ec-label">Visibility</label>
                            <select name="is_active" class="ec-select">
                                <option value="1" {{ old('is_active', $product->is_active ?? 1) == 1 ? 'selected' : '' }}>Active (visible)</option>
                                <option value="0" {{ old('is_active', $product->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive (hidden)</option>
                            </select>
                        </div>
                        <div class="ec-form-group" style="display:flex;align-items:center;gap:12px;">
                            <label class="ec-toggle">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                                <span class="ec-toggle-slider"></span>
                            </label>
                            <span style="font-size:0.875rem;color:var(--ec-text);">Featured product</span>
                        </div>
                        <button type="submit" class="ec-btn ec-btn-primary" style="width:100%;justify-content:center;">
                            <i class="fas fa-save"></i> {{ isset($product) ? 'Update Product' : 'Create Product' }}
                        </button>
                    </div>
                </div>

                {{-- Category & Type --}}
                <div class="ec-card">
                    <div class="ec-card-header">
                        <h3><i class="fas fa-tags"></i> Category & Type</h3>
                    </div>
                    <div class="ec-card-body">
                        <div class="ec-form-group">
                            <label class="ec-label">Category</label>
                            <select name="category_id" class="ec-select">
                                <option value="">— Select Category —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                                    @foreach($cat->children as $sub)
                                        <option value="{{ $sub->id }}" {{ old('category_id', $product->category_id ?? '')==$sub->id?'selected':'' }}>&nbsp;&nbsp;↳ {{ $sub->name }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">Product Type <span class="required">*</span></label>
                            <select name="type" class="ec-select">
                                <option value="simple"  {{ old('type', $product->type ?? 'simple')==='simple'?'selected':'' }}>Simple Product</option>
                                <option value="digital" {{ old('type', $product->type ?? '')==='digital'?'selected':'' }}>Digital Download</option>
                                <option value="service" {{ old('type', $product->type ?? '')==='service'?'selected':'' }}>Service</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="ec-card">
                    <div class="ec-card-header">
                        <h3><i class="fas fa-image"></i> Featured Image</h3>
                    </div>
                    <div class="ec-card-body">
                        @if(isset($product) && $product->featured_image)
                            <img src="{{ $product->featured_image }}" style="width:100%;border-radius:8px;margin-bottom:12px;object-fit:cover;max-height:160px;" onerror="this.style.display='none'">
                        @endif
                        <div class="ec-form-group">
                            <input type="text" name="featured_image" value="{{ old('featured_image', $product->featured_image ?? '') }}" class="ec-input" placeholder="https://… image URL">
                            <p style="font-size:0.72rem;color:var(--ec-muted);margin:4px 0 0;">Enter a direct image URL</p>
                        </div>
                    </div>
                </div>

                {{-- Shipping --}}
                <div class="ec-card">
                    <div class="ec-card-header">
                        <h3><i class="fas fa-shipping-fast"></i> Shipping</h3>
                    </div>
                    <div class="ec-card-body">
                        <div class="ec-form-group">
                            <label class="ec-label">Weight (kg)</label>
                            <input type="number" name="weight" value="{{ old('weight', $product->weight ?? '') }}" class="ec-input" step="0.01" min="0" placeholder="0.00">
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">Dimensions (L×W×H)</label>
                            <input type="text" name="dimensions" value="{{ old('dimensions', $product->dimensions ?? '') }}" class="ec-input" placeholder="e.g. 20×15×10 cm">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
