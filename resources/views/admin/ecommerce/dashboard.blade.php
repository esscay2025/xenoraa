@extends('layouts.admin')
@section('title', 'E-commerce Dashboard')
@php
    $contentActive = false; $recruitmentActive = false; $financeActive = false;
    $administrationActive = false; $communityActive = false; $crmActive = false;
    $ecommerceActive = true; $siteActive = false;
@endphp
@section('content')
<div style="padding:2rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:1.75rem;font-weight:700;color:#fff;margin:0;">E-commerce</h1>
            <p style="color:#9ca3af;margin:0.25rem 0 0;">Manage your shop, products, and categories.</p>
        </div>
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
            <a href="{{ route('admin.ecommerce.products.create') }}" style="background:#6366f1;color:#fff;padding:0.5rem 1.25rem;border-radius:8px;text-decoration:none;font-size:0.875rem;display:flex;align-items:center;gap:0.5rem;">
                <i class="fas fa-plus"></i> Add Product
            </a>
            <a href="{{ route('shop') }}" target="_blank" style="background:#0f172a;border:1px solid #334155;color:#e2e8f0;padding:0.5rem 1.25rem;border-radius:8px;text-decoration:none;font-size:0.875rem;display:flex;align-items:center;gap:0.5rem;">
                <i class="fas fa-external-link-alt"></i> View Shop
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1rem;margin-bottom:2rem;">
        @foreach([
            ['label'=>'Total Products','value'=>$stats['total_products'],'color'=>'#6366f1','icon'=>'fa-box','link'=>route('admin.ecommerce.products')],
            ['label'=>'Active Products','value'=>$stats['active_products'],'color'=>'#10b981','icon'=>'fa-check-circle','link'=>route('admin.ecommerce.products')],
            ['label'=>'Featured','value'=>$stats['featured_products'],'color'=>'#f59e0b','icon'=>'fa-star','link'=>route('admin.ecommerce.products')],
            ['label'=>'Categories','value'=>$stats['total_categories'],'color'=>'#06b6d4','icon'=>'fa-tags','link'=>route('admin.ecommerce.categories')],
            ['label'=>'Out of Stock','value'=>$stats['out_of_stock'],'color'=>'#ef4444','icon'=>'fa-exclamation-circle','link'=>route('admin.ecommerce.products')],
            ['label'=>'Pending Reviews','value'=>$stats['pending_reviews'],'color'=>'#8b5cf6','icon'=>'fa-star-half-alt','link'=>route('admin.ecommerce.reviews')],
        ] as $stat)
        <a href="{{ $stat['link'] }}" style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.25rem;text-align:center;text-decoration:none;display:block;transition:border-color 0.2s;" onmouseover="this.style.borderColor='{{ $stat['color'] }}'" onmouseout="this.style.borderColor='#334155'">
            <i class="fas {{ $stat['icon'] }}" style="font-size:1.5rem;color:{{ $stat['color'] }};margin-bottom:0.5rem;display:block;"></i>
            <div style="font-size:1.75rem;font-weight:700;color:#fff;">{{ $stat['value'] }}</div>
            <div style="font-size:0.75rem;color:#9ca3af;">{{ $stat['label'] }}</div>
        </a>
        @endforeach
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
        {{-- Recent Products --}}
        <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;overflow:hidden;">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid #334155;display:flex;align-items:center;justify-content:space-between;">
                <h3 style="color:#fff;font-size:0.95rem;font-weight:600;margin:0;">Recent Products</h3>
                <a href="{{ route('admin.ecommerce.products') }}" style="color:#6366f1;font-size:0.8rem;text-decoration:none;">View All</a>
            </div>
            @forelse($recentProducts as $product)
            <div style="padding:0.875rem 1.25rem;border-bottom:1px solid #1e293b;display:flex;align-items:center;gap:0.875rem;">
                <div style="width:40px;height:40px;border-radius:8px;background:#0f172a;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;">
                    @if($product->featured_image)
                        <img src="{{ $product->featured_image }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
                    @else
                        <i class="fas fa-box" style="color:#6366f1;"></i>
                    @endif
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="color:#e2e8f0;font-size:0.875rem;font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $product->name }}</div>
                    <div style="color:#9ca3af;font-size:0.75rem;">{{ $product->category?->name ?? 'Uncategorized' }}</div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <div style="color:#10b981;font-weight:600;font-size:0.875rem;">₹{{ number_format($product->effective_price, 2) }}</div>
                    <div style="font-size:0.7rem;color:{{ $product->stock_status==='in_stock'?'#10b981':'#ef4444' }};">{{ ucwords(str_replace('_',' ',$product->stock_status)) }}</div>
                </div>
            </div>
            @empty
            <div style="padding:2rem;text-align:center;color:#9ca3af;font-size:0.875rem;">No products yet. <a href="{{ route('admin.ecommerce.products.create') }}" style="color:#6366f1;">Add your first product</a></div>
            @endforelse
        </div>

        {{-- Categories --}}
        <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;overflow:hidden;">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid #334155;display:flex;align-items:center;justify-content:space-between;">
                <h3 style="color:#fff;font-size:0.95rem;font-weight:600;margin:0;">Categories</h3>
                <a href="{{ route('admin.ecommerce.categories') }}" style="color:#6366f1;font-size:0.8rem;text-decoration:none;">Manage</a>
            </div>
            @forelse($categories as $cat)
            <div style="padding:0.875rem 1.25rem;border-bottom:1px solid #1e293b;display:flex;align-items:center;gap:0.875rem;">
                <div style="width:36px;height:36px;border-radius:8px;background:#0f172a;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas {{ $cat->icon ?? 'fa-tag' }}" style="color:#6366f1;"></i>
                </div>
                <div style="flex:1;">
                    <div style="color:#e2e8f0;font-size:0.875rem;font-weight:500;">{{ $cat->name }}</div>
                    @if($cat->children->count() > 0)
                        <div style="color:#9ca3af;font-size:0.75rem;">{{ $cat->children->count() }} subcategories</div>
                    @endif
                </div>
                <span style="background:#1e3a5f;color:#60a5fa;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.75rem;">{{ $cat->products_count }} products</span>
            </div>
            @empty
            <div style="padding:2rem;text-align:center;color:#9ca3af;font-size:0.875rem;">No categories yet. <a href="{{ route('admin.ecommerce.categories') }}" style="color:#6366f1;">Add categories</a></div>
            @endforelse
        </div>
    </div>
</div>
@endsection
