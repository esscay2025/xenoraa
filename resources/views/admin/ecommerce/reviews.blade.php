@extends('layouts.admin')
@section('title', 'Product Reviews')
@php
    $contentActive = false; $recruitmentActive = false; $financeActive = false;
    $administrationActive = false; $communityActive = false; $crmActive = false;
    $ecommerceActive = true; $siteActive = false;
@endphp
@section('content')
<div style="padding:2rem;">
    <div style="margin-bottom:2rem;">
        <a href="{{ route('admin.ecommerce.dashboard') }}" style="color:#6366f1;text-decoration:none;font-size:0.875rem;display:block;margin-bottom:0.5rem;"><i class="fas fa-arrow-left"></i> E-commerce</a>
        <h1 style="font-size:1.75rem;font-weight:700;color:#fff;margin:0;">Product Reviews</h1>
    </div>

    @if(session('success'))
        <div style="background:#064e3b;border:1px solid #10b981;color:#6ee7b7;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;">{{ session('success') }}</div>
    @endif

    <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#0f172a;border-bottom:1px solid #334155;">
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Reviewer</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Product</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Rating</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Review</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Status</th>
                    <th style="padding:0.875rem 1rem;text-align:center;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr style="border-bottom:1px solid #1e293b;" onmouseover="this.style.background='#0f172a'" onmouseout="this.style.background='transparent'">
                    <td style="padding:0.875rem 1rem;">
                        <div style="color:#fff;font-weight:600;font-size:0.875rem;">{{ $review->reviewer_name }}</div>
                        @if($review->reviewer_email) <div style="color:#9ca3af;font-size:0.75rem;">{{ $review->reviewer_email }}</div> @endif
                    </td>
                    <td style="padding:0.875rem 1rem;color:#9ca3af;font-size:0.875rem;">{{ $review->product?->name ?? '—' }}</td>
                    <td style="padding:0.875rem 1rem;">
                        <div style="color:#f59e0b;">
                            @for($i=1;$i<=5;$i++) <i class="fas fa-star{{ $i<=$review->rating?'':'-o' }}" style="font-size:0.75rem;"></i> @endfor
                        </div>
                    </td>
                    <td style="padding:0.875rem 1rem;color:#e2e8f0;font-size:0.875rem;max-width:300px;">
                        @if($review->title) <div style="font-weight:600;margin-bottom:0.25rem;">{{ $review->title }}</div> @endif
                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $review->review }}</div>
                    </td>
                    <td style="padding:0.875rem 1rem;">
                        <span style="background:{{ $review->is_approved?'#064e3b':'#3d2a00' }};color:{{ $review->is_approved?'#10b981':'#f59e0b' }};padding:0.2rem 0.6rem;border-radius:20px;font-size:0.75rem;">
                            {{ $review->is_approved?'Approved':'Pending' }}
                        </span>
                    </td>
                    <td style="padding:0.875rem 1rem;text-align:center;">
                        <div style="display:flex;gap:0.5rem;justify-content:center;">
                            @if(!$review->is_approved)
                            <form method="POST" action="{{ route('admin.ecommerce.review.approve', $review) }}">
                                @csrf @method('PATCH')
                                <button type="submit" style="background:#064e3b;color:#10b981;border:none;padding:0.35rem 0.65rem;border-radius:6px;cursor:pointer;font-size:0.75rem;" title="Approve"><i class="fas fa-check"></i></button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.ecommerce.review.destroy', $review) }}" onsubmit="return confirm('Delete this review?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:#450a0a;color:#ef4444;border:none;padding:0.35rem 0.65rem;border-radius:6px;cursor:pointer;font-size:0.75rem;" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="padding:3rem;text-align:center;color:#9ca3af;">No reviews yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reviews->hasPages())
    <div style="margin-top:1.5rem;">{{ $reviews->links() }}</div>
    @endif
</div>
@endsection
