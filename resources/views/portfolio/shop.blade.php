@extends('layouts.app')
@section('title', ($tenant ? ($tenant->name . ' Shop') : 'Shop') . ($currentCategory ? ' — ' . $currentCategory->name : ''))
@section('content')
@php
    // Resolve tenant settings (layouts/app.blade.php already sets $tenantSettings, $siteName, $tenantAccent)
    // but we may also receive $tenant from ShopController
    $shopSiteName  = $siteName ?? ($tenant?->name ?? 'Shop');
    $shopTagline   = $siteTagline ?? ($tenant?->profile_tagline ?? 'Browse our products');
    $shopAccent    = $tenantAccent ?? '#6366f1';
    $shopLogoPath  = $tenantLogo ?? null;
@endphp
<style>
.shop-layout { display: grid; grid-template-columns: 260px 1fr; gap: 2rem; max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem; }
@media(max-width:768px){ .shop-layout { grid-template-columns: 1fr; } .shop-sidebar { display: none; } .shop-sidebar.open { display: block; } }
.shop-sidebar { position: sticky; top: 90px; align-self: start; }
.sidebar-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; margin-bottom: 1.25rem; }
.sidebar-card-header { padding: 0.875rem 1.25rem; border-bottom: 1px solid var(--border); font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
.cat-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.7rem 1.25rem; border-bottom: 1px solid var(--bg-secondary); text-decoration: none; color: var(--text-secondary); font-size: 0.875rem; transition: background 0.15s; }
.cat-item:hover, .cat-item.active { background: var(--bg-secondary); color: var(--accent); }
.cat-item.active { border-left: 3px solid var(--accent); }
.sub-cat-item { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1.25rem 0.5rem 2.5rem; text-decoration: none; color: var(--text-muted); font-size: 0.8rem; transition: background 0.15s; border-bottom: 1px solid var(--bg-secondary); }
.sub-cat-item:hover, .sub-cat-item.active { background: var(--bg-secondary); color: var(--accent); }
.product-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s; display: flex; flex-direction: column; }
.product-card:hover { transform: translateY(-4px); border-color: var(--accent); box-shadow: 0 8px 30px rgba(var(--accent-rgb),0.2); }
.product-img { width: 100%; height: 200px; object-fit: cover; background: var(--bg-secondary); display: flex; align-items: center; justify-content: center; }
.product-img img { width: 100%; height: 100%; object-fit: cover; }
.product-img-placeholder { width: 100%; height: 200px; background: linear-gradient(135deg, var(--bg-card), var(--bg-secondary)); display: flex; align-items: center; justify-content: center; }
.product-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
.product-badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; margin-bottom: 0.5rem; }
.badge-featured { background: rgba(245,158,11,0.15); color: #f59e0b; }
.badge-sale { background: rgba(239,68,68,0.15); color: #f87171; }
.badge-digital { background: rgba(139,92,246,0.15); color: #a78bfa; }
.badge-service { background: rgba(16,185,129,0.15); color: #6ee7b7; }
.product-name { color: var(--text-primary); font-weight: 700; font-size: 1rem; margin: 0 0 0.5rem; text-decoration: none; line-height: 1.3; }
.product-name:hover { color: var(--accent); }
.product-desc { color: var(--text-muted); font-size: 0.8rem; line-height: 1.5; margin: 0 0 1rem; flex: 1; }
.product-price { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
.price-current { color: var(--accent); font-weight: 700; font-size: 1.1rem; }
.price-original { color: var(--text-muted); font-size: 0.875rem; text-decoration: line-through; }
.price-discount { background: rgba(var(--accent-rgb),0.15); color: var(--accent); padding: 0.15rem 0.5rem; border-radius: 10px; font-size: 0.7rem; font-weight: 600; }
.btn-view { display: block; text-align: center; background: var(--accent); color: #fff; padding: 0.6rem; border-radius: 8px; text-decoration: none; font-size: 0.875rem; font-weight: 600; transition: opacity 0.2s; }
.btn-view:hover { opacity: 0.85; }
.products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.5rem; }
@media(max-width:640px){ .products-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; } }
@media(max-width:400px){ .products-grid { grid-template-columns: 1fr; } }
</style>

{{-- Hero Banner --}}
<section style="background:linear-gradient(135deg,var(--bg-secondary) 0%,var(--bg-card) 50%,var(--bg-secondary) 100%);padding:3rem 1.5rem;text-align:center;border-bottom:1px solid var(--border);">
    <div style="max-width:700px;margin:0 auto;">
        <div style="display:inline-flex;align-items:center;gap:0.5rem;background:var(--bg-card);border:1px solid var(--border);padding:0.4rem 1rem;border-radius:20px;font-size:0.8rem;color:var(--accent);margin-bottom:1rem;">
            @if($shopLogoPath)
                <img src="{{ $shopLogoPath }}" alt="{{ $shopSiteName }}" style="height:20px;width:auto;vertical-align:middle;">
            @else
                <i class="fas fa-shopping-bag"></i>
            @endif
            {{ $shopSiteName }}
        </div>
        <h1 style="font-size:2.5rem;font-weight:800;color:var(--text-primary);margin:0 0 1rem;line-height:1.2;">
            {{ $currentCategory ? $currentCategory->name : 'Shop' }}
        </h1>
        <p style="color:var(--text-secondary);font-size:1rem;margin:0 0 1.5rem;">
            {{ $currentCategory ? ($currentCategory->description ?? 'Browse products in this category.') : ($shopTagline ?: 'Browse our products and services.') }}
        </p>
        {{-- Search --}}
        @php
            $shopRoute = $tenant ? route('tenant.shop', $tenant->username) : route('shop');
        @endphp
        <form method="GET" action="{{ $shopRoute }}" style="display:flex;max-width:480px;margin:0 auto;gap:0.5rem;">
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." style="flex:1;background:var(--bg-card);border:1px solid var(--border);color:var(--text-primary);padding:0.65rem 1rem;border-radius:10px;font-size:0.875rem;outline:none;">
            <button type="submit" style="background:var(--accent);color:#fff;padding:0.65rem 1.25rem;border-radius:10px;border:none;cursor:pointer;font-weight:600;"><i class="fas fa-search"></i></button>
        </form>
    </div>
</section>

{{-- Mobile filter toggle --}}
<div style="display:none;padding:1rem 1.5rem;background:var(--bg-secondary);border-bottom:1px solid var(--border);" class="mobile-filter-bar">
    <button onclick="document.querySelector('.shop-sidebar').classList.toggle('open')" style="background:var(--bg-card);border:1px solid var(--border);color:var(--text-primary);padding:0.5rem 1rem;border-radius:8px;cursor:pointer;font-size:0.875rem;width:100%;">
        <i class="fas fa-filter"></i> Filter by Category
    </button>
</div>

<div class="shop-layout">
    {{-- Left Sidebar --}}
    <aside class="shop-sidebar">
        {{-- Categories --}}
        <div class="sidebar-card">
            <div class="sidebar-card-header"><i class="fas fa-tags" style="margin-right:0.5rem;color:var(--accent);"></i>Categories</div>
            <a href="{{ $shopRoute }}" class="cat-item {{ !request('category') ? 'active' : '' }}">
                <i class="fas fa-th" style="color:var(--accent);width:16px;"></i>
                <span>All Products</span>
                <span style="margin-left:auto;background:rgba(var(--accent-rgb),0.15);color:var(--accent);padding:0.1rem 0.5rem;border-radius:10px;font-size:0.7rem;">{{ $categories->sum('products_count') }}</span>
            </a>
            @foreach($categories as $cat)
                <a href="{{ $shopRoute . '?category=' . $cat->slug }}" class="cat-item {{ request('category')===$cat->slug ? 'active' : '' }}">
                    <i class="fas {{ $cat->icon ?? 'fa-tag' }}" style="color:var(--accent);width:16px;"></i>
                    <span>{{ $cat->name }}</span>
                    <span style="margin-left:auto;background:rgba(var(--accent-rgb),0.15);color:var(--accent);padding:0.1rem 0.5rem;border-radius:10px;font-size:0.7rem;">{{ $cat->products_count }}</span>
                </a>
                @foreach($cat->children as $sub)
                    <a href="{{ $shopRoute . '?category=' . $sub->slug }}" class="sub-cat-item {{ request('category')===$sub->slug ? 'active' : '' }}">
                        <i class="fas fa-angle-right" style="color:var(--accent);"></i>
                        {{ $sub->name }}
                    </a>
                @endforeach
            @endforeach
        </div>
        {{-- Sort --}}
        <div class="sidebar-card">
            <div class="sidebar-card-header"><i class="fas fa-sort" style="margin-right:0.5rem;color:var(--accent);"></i>Sort By</div>
            @php
                $sorts = ['featured'=>'Featured First','price_asc'=>'Price: Low to High','price_desc'=>'Price: High to Low','name'=>'Name A-Z'];
            @endphp
            @foreach($sorts as $val => $label)
            <a href="{{ $shopRoute . '?' . http_build_query(array_merge(request()->except('sort'), ['sort'=>$val])) }}" class="cat-item {{ request('sort')===$val ? 'active' : '' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </aside>

    {{-- Main Content --}}
    <main>
        {{-- Featured Products --}}
        @if($featuredProducts->count() > 0 && !request('category') && !request('search'))
        <div style="margin-bottom:2rem;">
            <h2 style="font-size:1.1rem;font-weight:700;color:var(--text-primary);margin:0 0 1rem;display:flex;align-items:center;gap:0.5rem;">
                <i class="fas fa-star" style="color:var(--accent);"></i> Featured Products
            </h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;">
                @foreach($featuredProducts as $fp)
                <a href="{{ route('shop.product', $fp) }}" style="text-decoration:none;" class="product-card">
                    <div class="product-img">
                        @if($fp->featured_image)
                            <img src="{{ $fp->featured_image }}" alt="{{ $fp->name }}" loading="lazy">
                        @else
                            <div class="product-img-placeholder"><i class="fas fa-box" style="font-size:2rem;color:var(--accent);opacity:0.5;"></i></div>
                        @endif
                    </div>
                    <div class="product-body">
                        <span class="product-badge badge-featured"><i class="fas fa-star"></i> Featured</span>
                        <div class="product-name">{{ $fp->name }}</div>
                        <div class="product-price">
                            <span class="price-current">₹{{ number_format($fp->effective_price, 2) }}</span>
                            @if($fp->discount_percent) <span class="price-discount">-{{ $fp->discount_percent }}%</span> @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <hr style="border:none;border-top:1px solid var(--border);margin:2rem 0;">
        </div>
        @endif

        {{-- Products Grid --}}
        @if($products->count() > 0)
        <div class="products-grid">
            @foreach($products as $product)
            <div class="product-card">
                <a href="{{ route('shop.product', $product) }}" style="text-decoration:none;">
                    <div class="product-img">
                        @if($product->featured_image)
                            <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" loading="lazy">
                        @else
                            <div class="product-img-placeholder"><i class="fas fa-box" style="font-size:2rem;color:var(--accent);opacity:0.5;"></i></div>
                        @endif
                    </div>
                </a>
                <div class="product-body">
                    <div style="display:flex;gap:0.4rem;flex-wrap:wrap;margin-bottom:0.5rem;">
                        @if($product->is_featured) <span class="product-badge badge-featured"><i class="fas fa-star"></i> Featured</span> @endif
                        @if($product->discount_percent) <span class="product-badge badge-sale">-{{ $product->discount_percent }}% OFF</span> @endif
                        @if($product->type==='digital') <span class="product-badge badge-digital"><i class="fas fa-download"></i> Digital</span> @endif
                        @if($product->type==='service') <span class="product-badge badge-service"><i class="fas fa-concierge-bell"></i> Service</span> @endif
                    </div>
                    <a href="{{ route('shop.product', $product) }}" class="product-name">{{ $product->name }}</a>
                    @if($product->short_description)
                        <p class="product-desc">{{ Str::limit($product->short_description, 80) }}</p>
                    @else
                        <div class="product-desc"></div>
                    @endif
                    <div class="product-price">
                        <span class="price-current">₹{{ number_format($product->effective_price, 2) }}</span>
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <span class="price-original">₹{{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                    <a href="{{ route('shop.product', $product) }}" class="btn-view">View Details</a>
                </div>
            </div>
            @endforeach
        </div>
        {{-- Pagination --}}
        @if($products->hasPages())
        <div style="margin-top:2rem;display:flex;justify-content:center;">
            {{ $products->links() }}
        </div>
        @endif
        @else
        <div style="text-align:center;padding:4rem 2rem;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;">
            <i class="fas fa-search" style="font-size:3rem;color:var(--border);margin-bottom:1rem;display:block;"></i>
            <h3 style="color:var(--text-primary);margin:0 0 0.5rem;">No products found</h3>
            <p style="color:var(--text-muted);margin:0 0 1.5rem;">Try a different search or browse all categories.</p>
            <a href="{{ $shopRoute }}" style="background:var(--accent);color:#fff;padding:0.6rem 1.5rem;border-radius:8px;text-decoration:none;font-size:0.875rem;">Browse All Products</a>
        </div>
        @endif
    </main>
</div>
{{-- Newsletter CTA --}}
@include('components.newsletter-subscribe', ['variant' => 'hero'])
@endsection
