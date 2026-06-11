@extends('layouts.admin')
@section('title', 'All Products')
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
            <h1 class="ec-title"><i class="fas fa-box-open"></i> All Products</h1>
            <p class="ec-subtitle">Manage your product catalogue</p>
        </div>
        <div class="ec-header-actions">
            <a href="{{ route('admin.ecommerce.products.template') }}" class="ec-btn ec-btn-secondary ec-btn-sm" title="Download Excel import template">
                <i class="fas fa-file-excel" style="color:var(--ec-green);"></i> Template
            </a>
            <button onclick="document.getElementById('importModal').style.display='flex'" class="ec-btn ec-btn-secondary ec-btn-sm">
                <i class="fas fa-upload"></i> Import
            </button>
            <a href="{{ route('admin.ecommerce.products.create') }}" class="ec-btn ec-btn-primary">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="ec-alert ec-alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="ec-alert ec-alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- Filter Bar --}}
    <div class="ec-card">
        <div class="ec-filter-bar">
            <div class="ec-search-wrap">
                <i class="fas fa-search"></i>
                <form method="GET" action="{{ route('admin.ecommerce.products') }}" style="display:contents;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products…" class="ec-input" style="padding-left:34px;">
                </form>
            </div>
            <form method="GET" action="{{ route('admin.ecommerce.products') }}" style="display:flex;gap:8px;flex-wrap:wrap;">
                @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                <select name="category" class="ec-select" style="width:auto;" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="ec-select" style="width:auto;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="active"    {{ request('status')==='active'?'selected':'' }}>Active</option>
                    <option value="inactive"  {{ request('status')==='inactive'?'selected':'' }}>Inactive</option>
                    <option value="in_stock"  {{ request('status')==='in_stock'?'selected':'' }}>In Stock</option>
                    <option value="out_of_stock" {{ request('status')==='out_of_stock'?'selected':'' }}>Out of Stock</option>
                </select>
                @if(request('search') || request('category') || request('status'))
                    <a href="{{ route('admin.ecommerce.products') }}" class="ec-btn ec-btn-secondary ec-btn-sm">Clear</a>
                @endif
            </form>
        </div>

        <div class="ec-table-wrap">
            <table class="ec-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="ec-product-info">
                                <div class="ec-product-thumb">
                                    @if($product->featured_image)
                                        <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" onerror="this.style.display='none'">
                                    @else
                                        <i class="fas fa-box"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="ec-product-name">{{ $product->name }}</div>
                                    @if($product->is_featured)
                                        <span class="ec-badge ec-badge-warning" style="margin-top:3px;"><i class="fas fa-star"></i> Featured</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td style="color:var(--ec-muted);font-size:0.8rem;">{{ $product->sku ?? '—' }}</td>
                        <td>
                            @if($product->category)
                                <span class="ec-badge ec-badge-info">{{ $product->category->name }}</span>
                            @else
                                <span style="color:var(--ec-muted);">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="ec-price">₹{{ number_format($product->effective_price, 2) }}</div>
                            @if($product->compare_price && $product->compare_price > $product->price)
                                <div style="font-size:0.72rem;color:var(--ec-muted);text-decoration:line-through;">₹{{ number_format($product->compare_price, 2) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($product->track_inventory)
                                <span class="ec-badge {{ $product->stock_quantity > 5 ? 'ec-badge-success' : ($product->stock_quantity > 0 ? 'ec-badge-warning' : 'ec-badge-danger') }}">
                                    {{ $product->stock_quantity }} units
                                </span>
                            @else
                                <span class="ec-badge ec-badge-muted">Not tracked</span>
                            @endif
                        </td>
                        <td>
                            <span class="ec-badge {{ $product->stock_status==='in_stock' ? 'ec-badge-success' : 'ec-badge-danger' }}">
                                {{ ucwords(str_replace('_',' ',$product->stock_status)) }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;justify-content:center;align-items:center;">
                                <a href="{{ route('admin.ecommerce.products.edit', $product) }}" class="ec-btn-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.ecommerce.products.toggle', $product) }}" style="display:contents;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="ec-btn-icon {{ $product->is_featured ? '' : '' }}" title="Toggle Featured"
                                        style="{{ $product->is_featured ? 'color:var(--ec-amber);border-color:var(--ec-amber);' : '' }}">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.ecommerce.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')" style="display:contents;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="ec-btn-icon danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="ec-empty">
                                <i class="fas fa-box"></i>
                                <p>No products found.</p>
                                <a href="{{ route('admin.ecommerce.products.create') }}" class="ec-btn ec-btn-primary ec-btn-sm">Add your first product</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="ec-count">
            {{ $products->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>

{{-- Import Modal --}}
<div id="importModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:9999;align-items:center;justify-content:center;">
    <div class="ec-card" style="width:100%;max-width:480px;margin:1rem;border-radius:16px;">
        <div class="ec-card-header">
            <h3><i class="fas fa-upload" style="color:var(--ec-sky);"></i> Import Products</h3>
            <button onclick="document.getElementById('importModal').style.display='none'" class="ec-btn-icon"><i class="fas fa-times"></i></button>
        </div>
        <div class="ec-card-body">
            <div class="ec-alert ec-alert-info" style="margin-bottom:16px;">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Before importing:</strong>
                    <ul style="margin:6px 0 0;padding-left:18px;line-height:1.8;">
                        <li>Download the <a href="{{ route('admin.ecommerce.products.template') }}" style="color:var(--ec-sky);">Excel template</a> first</li>
                        <li>Fill in your product data following the column headers</li>
                        <li>Save as <strong>.xlsx</strong> or <strong>.csv</strong> format</li>
                        <li>Maximum <strong>500 products</strong> per import</li>
                    </ul>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.ecommerce.products.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="ec-form-group">
                    <label class="ec-label">Select File <span class="required">*</span></label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="ec-input" style="cursor:pointer;">
                    <p style="margin:4px 0 0;font-size:0.72rem;color:var(--ec-muted);">Accepted: .xlsx, .xls, .csv — Max 10MB</p>
                </div>
                <div style="display:flex;gap:10px;">
                    <button type="submit" class="ec-btn ec-btn-primary" style="flex:1;">
                        <i class="fas fa-upload"></i> Import Products
                    </button>
                    <button type="button" onclick="document.getElementById('importModal').style.display='none'" class="ec-btn ec-btn-secondary">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
