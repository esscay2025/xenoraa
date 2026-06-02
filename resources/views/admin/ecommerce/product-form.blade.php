@extends('layouts.admin')
@section('title', isset($product) ? 'Edit Product' : 'Add Product')
@php
    $contentActive = false; $recruitmentActive = false; $financeActive = false;
    $administrationActive = false; $communityActive = false; $crmActive = false;
    $ecommerceActive = true; $siteActive = false;
@endphp
@section('content')
<div style="padding:2rem;max-width:900px;">
    <div style="margin-bottom:2rem;">
        <a href="{{ route('admin.ecommerce.products') }}" style="color:#6366f1;text-decoration:none;font-size:0.875rem;display:block;margin-bottom:0.5rem;"><i class="fas fa-arrow-left"></i> Products</a>
        <h1 style="font-size:1.75rem;font-weight:700;color:#fff;margin:0;">{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h1>
    </div>

    @if($errors->any())
        <div style="background:#450a0a;border:1px solid #ef4444;color:#fca5a5;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;">
            @foreach($errors->all() as $error)<div>• {{ $error }}</div>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ isset($product) ? route('admin.ecommerce.product.update', $product) : route('admin.ecommerce.product.store') }}">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;">

            {{-- Left Column --}}
            <div style="display:flex;flex-direction:column;gap:1.5rem;">

                {{-- Basic Info --}}
                <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.5rem;">
                    <h3 style="color:#fff;font-size:1rem;font-weight:600;margin:0 0 1.25rem;">Product Information</h3>

                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                    </div>

                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Short Description</label>
                        <input type="text" name="short_description" value="{{ old('short_description', $product->short_description ?? '') }}" maxlength="500" placeholder="One-line summary..." style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                    </div>

                    <div>
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Full Description</label>
                        <textarea name="description" rows="8" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;resize:vertical;box-sizing:border-box;">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>
                </div>

                {{-- Pricing --}}
                <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.5rem;">
                    <h3 style="color:#fff;font-size:1rem;font-weight:600;margin:0 0 1.25rem;">Pricing</h3>
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
                        <div>
                            <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Regular Price (₹) *</label>
                            <input type="number" name="price" value="{{ old('price', $product->price ?? '0') }}" step="0.01" min="0" required style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                        </div>
                        <div>
                            <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Sale Price (₹)</label>
                            <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price ?? '') }}" step="0.01" min="0" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                        </div>
                        <div>
                            <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Cost Price (₹)</label>
                            <input type="number" name="cost_price" value="{{ old('cost_price', $product->cost_price ?? '') }}" step="0.01" min="0" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                        </div>
                    </div>
                </div>

                {{-- Inventory --}}
                <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.5rem;">
                    <h3 style="color:#fff;font-size:1rem;font-weight:600;margin:0 0 1.25rem;">Inventory</h3>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                        <div>
                            <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                        </div>
                        <div>
                            <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Stock Status *</label>
                            <select name="stock_status" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;">
                                <option value="in_stock" {{ old('stock_status', $product->stock_status ?? 'in_stock')==='in_stock'?'selected':'' }}>In Stock</option>
                                <option value="out_of_stock" {{ old('stock_status', $product->stock_status ?? '')==='out_of_stock'?'selected':'' }}>Out of Stock</option>
                                <option value="pre_order" {{ old('stock_status', $product->stock_status ?? '')==='pre_order'?'selected':'' }}>Pre-order</option>
                            </select>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
                        <input type="checkbox" name="manage_stock" id="manage_stock" value="1" {{ old('manage_stock', $product->manage_stock ?? false) ? 'checked' : '' }} style="width:16px;height:16px;">
                        <label for="manage_stock" style="color:#e2e8f0;font-size:0.875rem;">Track stock quantity</label>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Stock Quantity</label>
                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? '0') }}" min="0" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                    </div>
                </div>

                {{-- SEO --}}
                <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.5rem;">
                    <h3 style="color:#fff;font-size:1rem;font-weight:600;margin:0 0 1.25rem;">SEO</h3>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title ?? '') }}" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Meta Description</label>
                        <textarea name="meta_description" rows="2" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;resize:vertical;box-sizing:border-box;">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div style="display:flex;flex-direction:column;gap:1.5rem;position:sticky;top:1.5rem;">

                {{-- Publish --}}
                <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.25rem;">
                    <h3 style="color:#fff;font-size:0.95rem;font-weight:600;margin:0 0 1rem;">Publish</h3>
                    <div style="display:flex;flex-direction:column;gap:0.75rem;margin-bottom:1.25rem;">
                        <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }} style="width:16px;height:16px;">
                            <span style="color:#e2e8f0;font-size:0.875rem;">Active (visible in shop)</span>
                        </label>
                        <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }} style="width:16px;height:16px;">
                            <span style="color:#e2e8f0;font-size:0.875rem;">Featured product</span>
                        </label>
                    </div>
                    <button type="submit" style="width:100%;background:#6366f1;color:#fff;padding:0.7rem;border-radius:8px;border:none;cursor:pointer;font-weight:600;font-size:0.875rem;">
                        <i class="fas fa-save"></i> {{ isset($product) ? 'Update Product' : 'Create Product' }}
                    </button>
                </div>

                {{-- Category & Type --}}
                <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.25rem;">
                    <h3 style="color:#fff;font-size:0.95rem;font-weight:600;margin:0 0 1rem;">Category & Type</h3>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Category</label>
                        <select name="category_id" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;">
                            <option value="">— Select Category —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                                @foreach($cat->children as $sub)
                                    <option value="{{ $sub->id }}" {{ old('category_id', $product->category_id ?? '')==$sub->id?'selected':'' }}>&nbsp;&nbsp;↳ {{ $sub->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Product Type *</label>
                        <select name="type" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;">
                            <option value="simple"  {{ old('type', $product->type ?? 'simple')==='simple'?'selected':'' }}>Simple Product</option>
                            <option value="digital" {{ old('type', $product->type ?? '')==='digital'?'selected':'' }}>Digital Download</option>
                            <option value="service" {{ old('type', $product->type ?? '')==='service'?'selected':'' }}>Service</option>
                        </select>
                    </div>
                </div>

                {{-- Image --}}
                <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.25rem;">
                    <h3 style="color:#fff;font-size:0.95rem;font-weight:600;margin:0 0 1rem;">Featured Image</h3>
                    @if(isset($product) && $product->featured_image)
                        <img src="{{ $product->featured_image }}" style="width:100%;border-radius:8px;margin-bottom:0.75rem;object-fit:cover;max-height:160px;" onerror="this.style.display='none'">
                    @endif
                    <input type="text" name="featured_image" value="{{ old('featured_image', $product->featured_image ?? '') }}" placeholder="https://... image URL" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.8rem;box-sizing:border-box;">
                    <p style="color:#9ca3af;font-size:0.7rem;margin:0.4rem 0 0;">Enter a direct image URL</p>
                </div>

                {{-- Physical --}}
                <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.25rem;">
                    <h3 style="color:#fff;font-size:0.95rem;font-weight:600;margin:0 0 1rem;">Shipping (optional)</h3>
                    <div style="margin-bottom:0.75rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Weight (kg)</label>
                        <input type="number" name="weight" value="{{ old('weight', $product->weight ?? '') }}" step="0.01" min="0" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Dimensions (L×W×H)</label>
                        <input type="text" name="dimensions" value="{{ old('dimensions', $product->dimensions ?? '') }}" placeholder="e.g. 20×15×10 cm" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
