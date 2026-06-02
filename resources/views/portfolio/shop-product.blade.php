@extends('layouts.app')
@section('title', $product->meta_title ?? $product->name . ' — Gopi K Shop')
@section('content')

<style>
.product-detail-layout { display: grid; grid-template-columns: 1fr 420px; gap: 2.5rem; max-width: 1100px; margin: 0 auto; padding: 2.5rem 1.5rem; align-items: start; }
@media(max-width:900px){ .product-detail-layout { grid-template-columns: 1fr; } }
.product-main-img { width: 100%; border-radius: 16px; aspect-ratio: 4/3; object-fit: cover; background: #1e293b; }
.product-info-card { background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 2rem; position: sticky; top: 90px; }
.star-rating { color: #f59e0b; font-size: 0.875rem; }
.btn-primary { display: block; text-align: center; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; padding: 0.875rem; border-radius: 10px; text-decoration: none; font-size: 1rem; font-weight: 700; transition: opacity 0.2s; border: none; cursor: pointer; width: 100%; }
.btn-primary:hover { opacity: 0.85; }
.btn-secondary { display: block; text-align: center; background: transparent; border: 1px solid #6366f1; color: #a78bfa; padding: 0.75rem; border-radius: 10px; text-decoration: none; font-size: 0.875rem; font-weight: 600; transition: background 0.2s; width: 100%; box-sizing: border-box; cursor: pointer; }
.btn-secondary:hover { background: #1e1b4b; }
</style>

{{-- Breadcrumb --}}
<div style="max-width:1100px;margin:0 auto;padding:1.5rem 1.5rem 0;display:flex;align-items:center;gap:0.5rem;font-size:0.8rem;color:#9ca3af;flex-wrap:wrap;">
    <a href="{{ route('shop') }}" style="color:#6366f1;text-decoration:none;">Shop</a>
    <i class="fas fa-chevron-right" style="font-size:0.65rem;"></i>
    @if($product->category)
        <a href="{{ route('shop', ['category'=>$product->category->slug]) }}" style="color:#6366f1;text-decoration:none;">{{ $product->category->name }}</a>
        <i class="fas fa-chevron-right" style="font-size:0.65rem;"></i>
    @endif
    <span style="color:#fff;">{{ $product->name }}</span>
</div>

<div class="product-detail-layout">

    {{-- Left: Images & Description --}}
    <div>
        {{-- Main Image --}}
        @if($product->featured_image)
            <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" class="product-main-img" onerror="this.style.display='none'">
        @else
            <div style="width:100%;aspect-ratio:4/3;background:linear-gradient(135deg,#1e293b,#0f172a);border-radius:16px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-box" style="font-size:4rem;color:#6366f1;opacity:0.4;"></i>
            </div>
        @endif

        {{-- Description --}}
        @if($product->description)
        <div style="margin-top:2rem;background:#1e293b;border:1px solid #334155;border-radius:16px;padding:2rem;">
            <h2 style="color:#fff;font-size:1.25rem;font-weight:700;margin:0 0 1.25rem;padding-bottom:0.75rem;border-bottom:1px solid #334155;">Product Description</h2>
            <div style="color:#cbd5e1;line-height:1.8;font-size:0.95rem;">{!! $product->description !!}</div>
        </div>
        @endif

        {{-- Reviews --}}
        <div style="margin-top:2rem;background:#1e293b;border:1px solid #334155;border-radius:16px;padding:2rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid #334155;">
                <h2 style="color:#fff;font-size:1.25rem;font-weight:700;margin:0;">Customer Reviews</h2>
                @if($avgRating)
                <div style="display:flex;align-items:center;gap:0.5rem;">
                    <span class="star-rating">
                        @for($i=1;$i<=5;$i++) <i class="fas fa-star{{ $i<=$avgRating?'':'-o' }}"></i> @endfor
                    </span>
                    <span style="color:#fff;font-weight:700;">{{ number_format($avgRating,1) }}</span>
                    <span style="color:#9ca3af;font-size:0.8rem;">({{ $approvedReviews->count() }})</span>
                </div>
                @endif
            </div>

            @forelse($approvedReviews as $review)
            <div style="padding:1rem 0;border-bottom:1px solid #1e293b;">
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.5rem;">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:0.875rem;flex-shrink:0;">
                        {{ strtoupper(substr($review->reviewer_name,0,1)) }}
                    </div>
                    <div>
                        <div style="color:#fff;font-weight:600;font-size:0.875rem;">{{ $review->reviewer_name }}</div>
                        <div class="star-rating" style="font-size:0.7rem;">
                            @for($i=1;$i<=5;$i++) <i class="fas fa-star{{ $i<=$review->rating?'':'-o' }}"></i> @endfor
                        </div>
                    </div>
                    <div style="margin-left:auto;color:#9ca3af;font-size:0.75rem;">{{ $review->created_at->diffForHumans() }}</div>
                </div>
                @if($review->title) <div style="color:#e2e8f0;font-weight:600;font-size:0.875rem;margin-bottom:0.25rem;">{{ $review->title }}</div> @endif
                <p style="color:#9ca3af;font-size:0.875rem;margin:0;line-height:1.6;">{{ $review->review }}</p>
            </div>
            @empty
            <p style="color:#9ca3af;text-align:center;padding:1.5rem 0;margin:0;">No reviews yet. Be the first to review this product.</p>
            @endforelse
        </div>
    </div>

    {{-- Right: Purchase Card --}}
    <div class="product-info-card">
        {{-- Badges --}}
        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:1rem;">
            @if($product->is_featured) <span style="background:#3d2a00;color:#f59e0b;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.7rem;font-weight:600;"><i class="fas fa-star"></i> Featured</span> @endif
            @if($product->type==='digital') <span style="background:#1a1a3e;color:#a78bfa;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.7rem;font-weight:600;"><i class="fas fa-download"></i> Digital</span> @endif
            @if($product->type==='service') <span style="background:#064e3b;color:#6ee7b7;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.7rem;font-weight:600;"><i class="fas fa-concierge-bell"></i> Service</span> @endif
        </div>

        <h1 style="color:#fff;font-size:1.5rem;font-weight:800;margin:0 0 0.75rem;line-height:1.3;">{{ $product->name }}</h1>

        @if($product->short_description)
            <p style="color:#9ca3af;font-size:0.9rem;line-height:1.6;margin:0 0 1.25rem;">{{ $product->short_description }}</p>
        @endif

        {{-- Rating --}}
        @if($approvedReviews->count() > 0)
        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1.25rem;">
            <span class="star-rating">
                @for($i=1;$i<=5;$i++) <i class="fas fa-star{{ $i<=$avgRating?'':'-o' }}"></i> @endfor
            </span>
            <span style="color:#9ca3af;font-size:0.8rem;">{{ number_format($avgRating,1) }} ({{ $approvedReviews->count() }} reviews)</span>
        </div>
        @endif

        {{-- Price --}}
        <div style="margin-bottom:1.5rem;padding:1.25rem;background:#0f172a;border-radius:10px;border:1px solid #334155;">
            <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                <span style="font-size:2rem;font-weight:800;color:#10b981;">₹{{ number_format($product->effective_price, 2) }}</span>
                @if($product->sale_price && $product->sale_price < $product->price)
                    <span style="font-size:1.1rem;color:#9ca3af;text-decoration:line-through;">₹{{ number_format($product->price, 2) }}</span>
                    <span style="background:#064e3b;color:#10b981;padding:0.2rem 0.6rem;border-radius:10px;font-size:0.8rem;font-weight:700;">Save {{ $product->discount_percent }}%</span>
                @endif
            </div>
        </div>

        {{-- Stock Status --}}
        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1.5rem;">
            @php $sc = ['in_stock'=>['#10b981','In Stock'],'out_of_stock'=>['#ef4444','Out of Stock'],'pre_order'=>['#f59e0b','Pre-order']]; $s = $sc[$product->stock_status] ?? ['#9ca3af','Unknown']; @endphp
            <i class="fas fa-circle" style="font-size:0.5rem;color:{{ $s[0] }};"></i>
            <span style="color:{{ $s[0] }};font-size:0.875rem;font-weight:600;">{{ $s[1] }}</span>
        </div>

        {{-- CTA --}}
        <div style="display:flex;flex-direction:column;gap:0.75rem;margin-bottom:1.5rem;">
            <a href="#" class="btn-primary" onclick="alert('Payment gateway coming soon! Contact Gopi at gopi@gopi.blog to purchase.');return false;">
                <i class="fas fa-shopping-cart"></i> Buy Now
            </a>
            <a href="#chatbot-window" class="btn-secondary" onclick="if(typeof toggleChatbot==='function')toggleChatbot();return false;">
                <i class="fas fa-comments"></i> Ask Gopi About This
            </a>
        </div>

        {{-- Meta --}}
        <div style="border-top:1px solid #334155;padding-top:1.25rem;display:flex;flex-direction:column;gap:0.5rem;">
            @if($product->sku) <div style="display:flex;justify-content:space-between;font-size:0.8rem;"><span style="color:#9ca3af;">SKU</span><span style="color:#e2e8f0;">{{ $product->sku }}</span></div> @endif
            @if($product->category) <div style="display:flex;justify-content:space-between;font-size:0.8rem;"><span style="color:#9ca3af;">Category</span><a href="{{ route('shop', ['category'=>$product->category->slug]) }}" style="color:#a78bfa;text-decoration:none;">{{ $product->category->name }}</a></div> @endif
            <div style="display:flex;justify-content:space-between;font-size:0.8rem;"><span style="color:#9ca3af;">Type</span><span style="color:#e2e8f0;text-transform:capitalize;">{{ $product->type }}</span></div>
            <div style="display:flex;justify-content:space-between;font-size:0.8rem;"><span style="color:#9ca3af;">Views</span><span style="color:#e2e8f0;">{{ number_format($product->views) }}</span></div>
        </div>
    </div>
</div>

{{-- Related Products --}}
@if($relatedProducts->count() > 0)
<div style="max-width:1100px;margin:0 auto;padding:0 1.5rem 3rem;">
    <h2 style="color:#fff;font-size:1.25rem;font-weight:700;margin:0 0 1.5rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="fas fa-th-large" style="color:#6366f1;"></i> Related Products
    </h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.5rem;">
        @foreach($relatedProducts as $rp)
        <a href="{{ route('shop.product', $rp) }}" style="text-decoration:none;background:#1e293b;border:1px solid #334155;border-radius:12px;overflow:hidden;display:block;transition:transform 0.2s,border-color 0.2s;" onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='#6366f1'" onmouseout="this.style.transform='';this.style.borderColor='#334155'">
            @if($rp->featured_image)
                <img src="{{ $rp->featured_image }}" alt="{{ $rp->name }}" style="width:100%;height:160px;object-fit:cover;" onerror="this.style.display='none'">
            @else
                <div style="width:100%;height:160px;background:#0f172a;display:flex;align-items:center;justify-content:center;"><i class="fas fa-box" style="font-size:2rem;color:#6366f1;opacity:0.4;"></i></div>
            @endif
            <div style="padding:1rem;">
                <div style="color:#fff;font-weight:600;font-size:0.875rem;margin-bottom:0.4rem;">{{ $rp->name }}</div>
                <div style="color:#10b981;font-weight:700;">₹{{ number_format($rp->effective_price, 2) }}</div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

@endsection
