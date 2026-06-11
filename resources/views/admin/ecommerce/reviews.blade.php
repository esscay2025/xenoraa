@extends('layouts.admin')
@section('title', 'Product Reviews')
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
            <h1 class="ec-title"><i class="fas fa-star"></i> Product Reviews</h1>
            <p class="ec-subtitle">Moderate and manage customer reviews</p>
        </div>
        <div class="ec-header-actions">
            <span class="ec-badge ec-badge-warning" style="font-size:0.8rem;padding:6px 14px;">
                {{ $reviews->where('is_approved',false)->count() }} pending
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="ec-alert ec-alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="ec-card">
        <div class="ec-table-wrap">
            <table class="ec-table">
                <thead>
                    <tr>
                        <th>Reviewer</th>
                        <th>Product</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Status</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="ec-avatar">{{ strtoupper(substr($review->reviewer_name,0,1)) }}</div>
                                <div>
                                    <div style="font-weight:600;font-size:0.875rem;">{{ $review->reviewer_name }}</div>
                                    @if($review->reviewer_email)
                                        <div style="font-size:0.72rem;color:var(--ec-muted);">{{ $review->reviewer_email }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td style="color:var(--ec-secondary);font-size:0.875rem;">{{ $review->product?->name ?? '—' }}</td>
                        <td>
                            <div class="ec-stars">
                                @for($i=1;$i<=5;$i++)
                                    <i class="fas fa-star{{ $i<=$review->rating?'':'-o' }}"></i>
                                @endfor
                            </div>
                        </td>
                        <td style="max-width:280px;">
                            @if($review->title)
                                <div style="font-weight:600;font-size:0.875rem;margin-bottom:3px;">{{ $review->title }}</div>
                            @endif
                            <div style="font-size:0.8rem;color:var(--ec-secondary);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $review->review }}</div>
                        </td>
                        <td>
                            <span class="ec-badge {{ $review->is_approved ? 'ec-badge-success' : 'ec-badge-warning' }}">
                                <i class="fas {{ $review->is_approved ? 'fa-check' : 'fa-clock' }}"></i>
                                {{ $review->is_approved ? 'Approved' : 'Pending' }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;justify-content:center;">
                                @if(!$review->is_approved)
                                <form method="POST" action="{{ route('admin.ecommerce.review.approve', $review) }}" style="display:contents;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="ec-btn-icon" title="Approve" style="color:var(--ec-green);border-color:var(--ec-green);">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('admin.ecommerce.review.destroy', $review) }}" onsubmit="return confirm('Delete this review?')" style="display:contents;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="ec-btn-icon danger" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="ec-empty">
                                <i class="fas fa-star"></i>
                                <p>No reviews yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reviews->hasPages())
        <div class="ec-count">{{ $reviews->links() }}</div>
        @endif
    </div>

</div>
@endsection
