@extends('layouts.admin')
@section('title', 'E-commerce Dashboard')
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
            <h1 class="ec-title"><i class="fas fa-store"></i> E-commerce Dashboard</h1>
            <p class="ec-subtitle">Overview of your store performance</p>
        </div>
        <div class="ec-header-actions">
            <a href="{{ route('admin.ecommerce.products.create') }}" class="ec-btn ec-btn-primary">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>
    </div>

    {{-- KPI Stats --}}
    <div class="ec-stats-grid">
        @foreach([
            ['label'=>'Total Products','value'=>$totalProducts,'icon'=>'fa-box','color'=>'var(--ec-primary)','link'=>route('admin.ecommerce.products')],
            ['label'=>'Categories','value'=>$totalCategories,'icon'=>'fa-tags','color'=>'var(--ec-sky)','link'=>route('admin.ecommerce.categories')],
            ['label'=>'Featured','value'=>$featuredProducts,'icon'=>'fa-star','color'=>'var(--ec-amber)','link'=>route('admin.ecommerce.products')],
            ['label'=>'Low Stock','value'=>$lowStockProducts,'icon'=>'fa-exclamation-triangle','color'=>'var(--ec-red)','link'=>route('admin.ecommerce.products')],
            ['label'=>'Reviews','value'=>$totalReviews,'icon'=>'fa-comment-alt','color'=>'var(--ec-green)','link'=>route('admin.ecommerce.reviews')],
        ] as $stat)
        <a href="{{ $stat['link'] }}" class="ec-stat-card">
            <i class="fas {{ $stat['icon'] }}" style="color:{{ $stat['color'] }};"></i>
            <div class="ec-stat-value">{{ $stat['value'] }}</div>
            <div class="ec-stat-label">{{ $stat['label'] }}</div>
        </a>
        @endforeach
    </div>

    {{-- Recent Products + Categories --}}
    <div class="ec-grid-2">

        {{-- Recent Products --}}
        <div class="ec-card">
            <div class="ec-card-header">
                <h3><i class="fas fa-box-open"></i> Recent Products</h3>
                <a href="{{ route('admin.ecommerce.products') }}" class="ec-btn ec-btn-secondary ec-btn-sm">View All</a>
            </div>
            @forelse($recentProducts as $product)
            <div class="ec-list-item">
                <div class="ec-list-item-icon">
                    @if($product->featured_image)
                        <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" onerror="this.style.display='none'">
                    @else
                        <i class="fas fa-box"></i>
                    @endif
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="ec-list-item-title">{{ $product->name }}</div>
                    <div class="ec-list-item-sub">{{ $product->category?->name ?? 'Uncategorized' }}</div>
                </div>
                <div class="ec-list-item-right">
                    <div class="ec-price">₹{{ number_format($product->effective_price, 2) }}</div>
                    <div style="font-size:0.7rem;color:{{ $product->stock_status==='in_stock'?'var(--ec-green)':'var(--ec-red)' }};">
                        {{ ucwords(str_replace('_',' ',$product->stock_status)) }}
                    </div>
                </div>
            </div>
            @empty
            <div class="ec-empty">
                <i class="fas fa-box"></i>
                <p>No products yet.</p>
                <a href="{{ route('admin.ecommerce.products.create') }}" class="ec-btn ec-btn-primary ec-btn-sm">Add your first product</a>
            </div>
            @endforelse
        </div>

        {{-- Categories --}}
        <div class="ec-card">
            <div class="ec-card-header">
                <h3><i class="fas fa-tags"></i> Categories</h3>
                <a href="{{ route('admin.ecommerce.categories') }}" class="ec-btn ec-btn-secondary ec-btn-sm">Manage</a>
            </div>
            @forelse($categories as $cat)
            <div class="ec-list-item">
                <div class="ec-list-item-icon">
                    <i class="fas {{ $cat->icon ?? 'fa-tag' }}"></i>
                </div>
                <div style="flex:1;">
                    <div class="ec-list-item-title">{{ $cat->name }}</div>
                    @if($cat->children->count() > 0)
                        <div class="ec-list-item-sub">{{ $cat->children->count() }} subcategories</div>
                    @endif
                </div>
                <span class="ec-badge ec-badge-primary">{{ $cat->products_count }} products</span>
            </div>
            @empty
            <div class="ec-empty">
                <i class="fas fa-tags"></i>
                <p>No categories yet.</p>
                <a href="{{ route('admin.ecommerce.categories') }}" class="ec-btn ec-btn-primary ec-btn-sm">Add categories</a>
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
