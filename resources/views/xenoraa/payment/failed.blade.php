@extends('layouts.xenoraa')
@section('title', 'Payment Failed — Xenoraa')

@section('content')
<section style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:4rem 1.5rem;">
    <div style="text-align:center;max-width:520px;">
        <div style="width:80px;height:80px;background:rgba(239,68,68,0.12);border:2px solid rgba(239,68,68,0.3);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;font-size:2rem;">✕</div>
        <h1 style="font-family:'Space Grotesk',sans-serif;font-size:2rem;font-weight:700;color:#f0f0f5;margin-bottom:0.75rem;">Payment Failed</h1>
        <p style="color:#6b6b8a;font-size:1rem;line-height:1.6;margin-bottom:2rem;">Your payment could not be processed. No charges were made. Please try again or contact support at <a href="mailto:support@xenoraa.com" style="color:#a78bfa;">support@xenoraa.com</a>.</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('xenoraa.pricing') }}" style="display:inline-flex;align-items:center;gap:8px;padding:0.75rem 1.5rem;background:#7c3aed;color:#fff;border-radius:10px;text-decoration:none;font-weight:600;font-size:0.9rem;">Try Again</a>
            <a href="{{ url('/') }}" style="display:inline-flex;align-items:center;gap:8px;padding:0.75rem 1.5rem;background:transparent;color:#9090aa;border:1px solid #1e1e2e;border-radius:10px;text-decoration:none;font-weight:600;font-size:0.9rem;">Back to Home</a>
        </div>
    </div>
</section>
@endsection
