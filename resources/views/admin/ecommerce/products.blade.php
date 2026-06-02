@extends('layouts.admin')
@section('title', 'Products')
@php
    $contentActive = false; $recruitmentActive = false; $financeActive = false;
    $administrationActive = false; $communityActive = false; $crmActive = false;
    $ecommerceActive = true; $siteActive = false;
@endphp
@section('content')
<div style="padding:2rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <a href="{{ route('admin.ecommerce.dashboard') }}" style="color:#6366f1;text-decoration:none;font-size:0.875rem;display:block;margin-bottom:0.5rem;"><i class="fas fa-arrow-left"></i> E-commerce</a>
            <h1 style="font-size:1.75rem;font-weight:700;color:#fff;margin:0;">Products</h1>
        </div>
        <a href="{{ route('admin.ecommerce.products.create') }}" style="background:#6366f1;color:#fff;padding:0.5rem 1.25rem;border-radius:8px;text-decoration:none;font-size:0.875rem;display:flex;align-items:center;gap:0.5rem;">
            <i class="fas fa-plus"></i> Add Product
        </a>
    </div>

    @if(session('success'))
        <div style="background:#064e3b;border:1px solid #10b981;color:#6ee7b7;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <form method="GET" style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.25rem;margin-bottom:1.5rem;display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
        <div style="flex:1;min-width:180px;">
            <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Product name or SKU..." style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.5rem 0.75rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
        </div>
        <div style="min-width:160px;">
            <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Category</label>
            <select name="category" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.5rem 0.75rem;border-radius:8px;font-size:0.875rem;">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="min-width:150px;">
            <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Stock Status</label>
            <select name="status" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.5rem 0.75rem;border-radius:8px;font-size:0.875rem;">
                <option value="">All Statuses</option>
                <option value="in_stock" {{ request('status')==='in_stock'?'selected':'' }}>In Stock</option>
                <option value="out_of_stock" {{ request('status')==='out_of_stock'?'selected':'' }}>Out of Stock</option>
                <option value="pre_order" {{ request('status')==='pre_order'?'selected':'' }}>Pre-order</option>
            </select>
        </div>
        <button type="submit" style="background:#6366f1;color:#fff;padding:0.5rem 1.25rem;border-radius:8px;border:none;cursor:pointer;font-size:0.875rem;">Filter</button>
        <a href="{{ route('admin.ecommerce.products') }}" style="background:#374151;color:#e2e8f0;padding:0.5rem 1rem;border-radius:8px;text-decoration:none;font-size:0.875rem;">Reset</a>
    </form>

    {{-- Products Table --}}
    <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#0f172a;border-bottom:1px solid #334155;">
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Product</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Category</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Price</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Stock</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Type</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Status</th>
                    <th style="padding:0.875rem 1rem;text-align:center;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr style="border-bottom:1px solid #1e293b;" onmouseover="this.style.background='#0f172a'" onmouseout="this.style.background='transparent'">
                    <td style="padding:0.875rem 1rem;">
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div style="width:44px;height:44px;border-radius:8px;background:#0f172a;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;">
                                @if($product->featured_image)
                                    <img src="{{ $product->featured_image }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
                                @else
                                    <i class="fas fa-box" style="color:#6366f1;"></i>
                                @endif
                            </div>
                            <div>
                                <div style="color:#fff;font-weight:600;font-size:0.875rem;">{{ $product->name }}</div>
                                @if($product->sku) <div style="color:#9ca3af;font-size:0.75rem;">SKU: {{ $product->sku }}</div> @endif
                                @if($product->is_featured) <span style="background:#3d2a00;color:#f59e0b;padding:0.1rem 0.4rem;border-radius:10px;font-size:0.65rem;"><i class="fas fa-star"></i> Featured</span> @endif
                            </div>
                        </div>
                    </td>
                    <td style="padding:0.875rem 1rem;color:#9ca3af;font-size:0.875rem;">{{ $product->category?->name ?? '—' }}</td>
                    <td style="padding:0.875rem 1rem;">
                        <div style="color:#10b981;font-weight:600;font-size:0.875rem;">₹{{ number_format($product->effective_price, 2) }}</div>
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <div style="color:#9ca3af;font-size:0.75rem;text-decoration:line-through;">₹{{ number_format($product->price, 2) }}</div>
                        @endif
                    </td>
                    <td style="padding:0.875rem 1rem;">
                        @php $sc = ['in_stock'=>['#064e3b','#10b981'],'out_of_stock'=>['#450a0a','#ef4444'],'pre_order'=>['#1a1a3e','#a78bfa']]; $c = $sc[$product->stock_status] ?? ['#1e293b','#9ca3af']; @endphp
                        <span style="background:{{ $c[0] }};color:{{ $c[1] }};padding:0.2rem 0.6rem;border-radius:20px;font-size:0.75rem;">{{ ucwords(str_replace('_',' ',$product->stock_status)) }}</span>
                        @if($product->manage_stock) <div style="color:#9ca3af;font-size:0.7rem;margin-top:2px;">Qty: {{ $product->stock_quantity }}</div> @endif
                    </td>
                    <td style="padding:0.875rem 1rem;color:#9ca3af;font-size:0.875rem;text-transform:capitalize;">{{ $product->type }}</td>
                    <td style="padding:0.875rem 1rem;">
                        <span style="background:{{ $product->is_active?'#064e3b':'#450a0a' }};color:{{ $product->is_active?'#10b981':'#ef4444' }};padding:0.2rem 0.6rem;border-radius:20px;font-size:0.75rem;">
                            {{ $product->is_active?'Active':'Inactive' }}
                        </span>
                    </td>
                    <td style="padding:0.875rem 1rem;text-align:center;">
                        <div style="display:flex;gap:0.5rem;justify-content:center;">
                            <a href="{{ route('admin.ecommerce.product.edit', $product) }}" style="background:#1e3a5f;color:#60a5fa;padding:0.35rem 0.65rem;border-radius:6px;font-size:0.75rem;text-decoration:none;" title="Edit"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('admin.ecommerce.product.toggle-featured', $product) }}">
                                @csrf @method('PATCH')
                                <button type="submit" style="background:{{ $product->is_featured?'#3d2a00':'#1e293b' }};color:{{ $product->is_featured?'#f59e0b':'#9ca3af' }};border:1px solid #334155;padding:0.35rem 0.65rem;border-radius:6px;cursor:pointer;font-size:0.75rem;" title="Toggle Featured"><i class="fas fa-star"></i></button>
                            </form>
                            <form method="POST" action="{{ route('admin.ecommerce.product.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:#450a0a;color:#ef4444;border:none;padding:0.35rem 0.65rem;border-radius:6px;cursor:pointer;font-size:0.75rem;" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="padding:3rem;text-align:center;color:#9ca3af;">
                    <i class="fas fa-box" style="font-size:2rem;margin-bottom:0.75rem;display:block;opacity:0.3;"></i>
                    No products yet. <a href="{{ route('admin.ecommerce.products.create') }}" style="color:#6366f1;">Add your first product</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div style="margin-top:1.5rem;">{{ $products->links() }}</div>
    @endif
</div>
@endsection
