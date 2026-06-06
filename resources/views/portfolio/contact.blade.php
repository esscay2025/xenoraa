@extends('layouts.app')
@section('title', 'Contact — ' . $siteName)
@section('content')
<style>
.xn-contact-hero { background: linear-gradient(135deg, var(--accent, #6366f1) 0%, color-mix(in srgb, var(--accent, #6366f1) 70%, #000) 100%); padding: 4rem 2rem 3rem; text-align: center; color: #fff; }
.xn-contact-hero h1 { font-size: 2.5rem; font-weight: 800; margin: 0 0 0.75rem; }
.xn-contact-hero p { font-size: 1.1rem; opacity: 0.85; margin: 0; }
.xn-contact-body { max-width: 900px; margin: 0 auto; padding: 3rem 1.5rem; display: grid; grid-template-columns: 1fr 1.5fr; gap: 3rem; }
@media(max-width:700px){ .xn-contact-body { grid-template-columns: 1fr; } }
.xn-contact-info h2 { font-size: 1.4rem; font-weight: 800; margin: 0 0 1.5rem; }
.xn-contact-item { display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; }
.xn-contact-icon { width: 44px; height: 44px; border-radius: 10px; background: rgba(99,102,241,0.1); display: flex; align-items: center; justify-content: center; color: var(--accent, #6366f1); font-size: 1.1rem; flex-shrink: 0; }
.xn-contact-item-text strong { display: block; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted, #6b7280); margin-bottom: 0.2rem; }
.xn-contact-item-text a, .xn-contact-item-text span { color: var(--text-primary, #111); text-decoration: none; font-size: 0.95rem; }
.xn-contact-form-card { background: var(--bg-card, #fff); border: 1px solid var(--border, #e5e7eb); border-radius: 16px; padding: 2rem; }
.xn-contact-form-card h2 { font-size: 1.4rem; font-weight: 800; margin: 0 0 1.5rem; }
.xn-form-group { margin-bottom: 1.25rem; }
.xn-form-group label { display: block; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted, #6b7280); margin-bottom: 0.4rem; }
.xn-form-group input, .xn-form-group textarea, .xn-form-group select { width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border, #e5e7eb); border-radius: 8px; font-size: 0.95rem; background: var(--bg-secondary, #f9fafb); color: var(--text-primary, #111); box-sizing: border-box; }
.xn-form-group textarea { min-height: 140px; resize: vertical; }
.xn-submit-btn { width: 100%; padding: 0.875rem; background: var(--accent, #6366f1); color: #fff; border: none; border-radius: 8px; font-size: 1rem; font-weight: 700; cursor: pointer; }
.xn-submit-btn:hover { opacity: 0.9; }
.xn-social-row { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 2rem; }
.xn-social-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; text-decoration: none; background: var(--bg-hover, #f3f4f6); color: var(--text-primary, #111); border: 1px solid var(--border, #e5e7eb); }
</style>
@php
$heroHeading = $contactPage ? $contactPage->getSectionData('hero')['heading'] ?? 'Get in Touch' : 'Get in Touch';
$heroSub     = $contactPage ? $contactPage->getSectionData('hero')['subheading'] ?? '' : '';
$formEmail   = $contactPage ? $contactPage->getSectionData('form')['email'] ?? ($profile['email'] ?? $tenant->email) : ($profile['email'] ?? $tenant->email);
$formPhone   = $contactPage ? $contactPage->getSectionData('form')['phone'] ?? ($profile['phone'] ?? '') : ($profile['phone'] ?? '');
$formAddress = $contactPage ? $contactPage->getSectionData('form')['address'] ?? ($profile['address'] ?? '') : ($profile['address'] ?? '');
@endphp
<div class="xn-contact-hero" style="--accent:{{ $accentColor }};">
    <h1>{{ $heroHeading }}</h1>
    @if($heroSub)<p>{{ $heroSub }}</p>@endif
</div>
<div class="xn-contact-body" style="--accent:{{ $accentColor }};">
    <div class="xn-contact-info">
        <h2>Contact Information</h2>
        @if($formEmail)
        <div class="xn-contact-item">
            <div class="xn-contact-icon"><i class="fas fa-envelope"></i></div>
            <div class="xn-contact-item-text">
                <strong>Email</strong>
                <a href="mailto:{{ $formEmail }}">{{ $formEmail }}</a>
            </div>
        </div>
        @endif
        @if($formPhone)
        <div class="xn-contact-item">
            <div class="xn-contact-icon"><i class="fas fa-phone"></i></div>
            <div class="xn-contact-item-text">
                <strong>Phone</strong>
                <a href="tel:{{ $formPhone }}">{{ $formPhone }}</a>
            </div>
        </div>
        @endif
        @if($formAddress)
        <div class="xn-contact-item">
            <div class="xn-contact-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div class="xn-contact-item-text">
                <strong>Address</strong>
                <span>{{ $formAddress }}</span>
            </div>
        </div>
        @endif
        @if($socialLinks->count())
        <div class="xn-social-row">
            @foreach($socialLinks as $sl)
            <a href="{{ $sl->url }}" target="_blank" class="xn-social-btn">
                <i class="{{ $sl->icon ?? 'fas fa-link' }}"></i> {{ $sl->platform }}
            </a>
            @endforeach
        </div>
        @endif
    </div>
    <div class="xn-contact-form-card">
        <h2>Send a Message</h2>
        @if(session('success'))
        <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);color:#16a34a;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:0.9rem;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        <form method="POST" action="{{ route('chatbot.contact') }}">
            @csrf
            <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
            <div class="xn-form-group">
                <label>Your Name *</label>
                <input type="text" name="name" required placeholder="John Doe">
            </div>
            <div class="xn-form-group">
                <label>Email Address *</label>
                <input type="email" name="email" required placeholder="john@example.com">
            </div>
            <div class="xn-form-group">
                <label>Subject</label>
                <input type="text" name="subject" placeholder="How can I help you?">
            </div>
            <div class="xn-form-group">
                <label>Message *</label>
                <textarea name="message" required placeholder="Tell me about your project or inquiry..."></textarea>
            </div>
            <button type="submit" class="xn-submit-btn"><i class="fas fa-paper-plane"></i> Send Message</button>
        </form>
    </div>
</div>
@endsection
