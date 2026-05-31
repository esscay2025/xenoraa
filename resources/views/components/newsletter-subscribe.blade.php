@props(['variant' => 'default'])
{{-- variant: 'default' | 'compact' | 'hero' --}}

@php
    $formId = 'newsletter-form-' . uniqid();
@endphp

@if($variant === 'hero')
{{-- Full-width hero-style section for homepage / about page --}}
<section style="background: linear-gradient(135deg, #111 0%, #1a1a1a 100%); border: 1px solid var(--border); border-radius: 16px; padding: 3rem 2rem; text-align: center; margin: 4rem 0;">
    <div style="max-width: 560px; margin: 0 auto;">
        <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.06); border: 1px solid var(--border-light); border-radius: 20px; padding: 0.3rem 1rem; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1.25rem;">
            <i class="fas fa-envelope" style="font-size: 0.7rem;"></i> Newsletter
        </div>
        <h2 style="font-size: 1.75rem; font-weight: 800; margin: 0 0 0.75rem; line-height: 1.2;">Stay in the Loop</h2>
        <p style="color: var(--text-secondary); font-size: 0.95rem; margin: 0 0 2rem; line-height: 1.7;">
            Get updates on my latest projects, AI tools, automation tips, and business insights — delivered straight to your inbox.
        </p>

        @if(session('newsletter_success'))
            <div class="alert alert-success" style="margin-bottom: 1rem;">
                <i class="fas fa-check-circle" style="margin-right: 0.4rem;"></i>{{ session('newsletter_success') }}
            </div>
        @endif

        <form id="{{ $formId }}" action="{{ route('newsletter.subscribe') }}" method="POST" style="display: flex; gap: 0.75rem; max-width: 440px; margin: 0 auto; flex-wrap: wrap;">
            @csrf
            <input type="email" name="email" placeholder="your@email.com" required
                style="flex: 1; min-width: 200px; padding: 0.75rem 1rem; background: var(--bg-primary); border: 1px solid var(--border-light); border-radius: 8px; color: var(--text-primary); font-size: 0.9rem; outline: none; transition: border-color 0.2s;"
                onfocus="this.style.borderColor='#555'" onblur="this.style.borderColor='var(--border-light)'">
            <button type="submit"
                style="padding: 0.75rem 1.5rem; background: var(--text-primary); color: var(--bg-primary); border: none; border-radius: 8px; font-size: 0.9rem; font-weight: 700; cursor: pointer; white-space: nowrap; transition: background 0.2s;"
                onmouseover="this.style.background='#e0e0e0'" onmouseout="this.style.background='var(--text-primary)'">
                Subscribe
            </button>
        </form>
        <p style="color: var(--text-muted); font-size: 0.78rem; margin-top: 1rem;">
            <i class="fas fa-lock" style="font-size: 0.7rem; margin-right: 0.3rem;"></i>No spam, ever. Unsubscribe anytime.
        </p>
    </div>
</section>

@elseif($variant === 'compact')
{{-- Compact inline version for footer --}}
<div>
    <h4 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
        <i class="fas fa-envelope" style="margin-right: 0.4rem; font-size: 0.8rem;"></i>Newsletter
    </h4>
    <p style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 0.875rem; line-height: 1.5;">
        Latest projects, AI tools &amp; automation tips.
    </p>

    @if(session('newsletter_success'))
        <div style="background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #86efac; padding: 0.5rem 0.75rem; border-radius: 6px; font-size: 0.8rem; margin-bottom: 0.75rem;">
            <i class="fas fa-check-circle" style="margin-right: 0.3rem;"></i>{{ session('newsletter_success') }}
        </div>
    @endif

    <form id="{{ $formId }}" action="{{ route('newsletter.subscribe') }}" method="POST">
        @csrf
        <div style="display: flex; gap: 0.4rem; margin-bottom: 0.4rem;">
            <input type="email" name="email" placeholder="your@email.com" required
                style="flex: 1; padding: 0.55rem 0.75rem; background: var(--bg-primary); border: 1px solid var(--border-light); border-radius: 6px; color: var(--text-primary); font-size: 0.82rem; outline: none; min-width: 0; transition: border-color 0.2s;"
                onfocus="this.style.borderColor='#555'" onblur="this.style.borderColor='var(--border-light)'">
            <button type="submit"
                style="padding: 0.55rem 0.875rem; background: var(--text-primary); color: var(--bg-primary); border: none; border-radius: 6px; font-size: 0.82rem; font-weight: 700; cursor: pointer; white-space: nowrap; transition: background 0.2s; flex-shrink: 0;"
                onmouseover="this.style.background='#e0e0e0'" onmouseout="this.style.background='var(--text-primary)'">
                Subscribe
            </button>
        </div>
        <p style="color: var(--text-muted); font-size: 0.72rem;">
            <i class="fas fa-lock" style="font-size: 0.65rem; margin-right: 0.25rem;"></i>No spam. Unsubscribe anytime.
        </p>
    </form>
</div>

@else
{{-- Default card-style section --}}
<div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 2rem; text-align: center; margin: 3rem 0;">
    <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0 0 0.5rem;">Stay Updated</h3>
    <p style="color: var(--text-secondary); font-size: 0.875rem; margin: 0 0 1.5rem;">
        Get updates on latest projects, AI tools, automation tips, and business insights.
    </p>

    @if(session('newsletter_success'))
        <div class="alert alert-success" style="margin-bottom: 1rem; text-align: left;">
            <i class="fas fa-check-circle" style="margin-right: 0.4rem;"></i>{{ session('newsletter_success') }}
        </div>
    @endif

    <form id="{{ $formId }}" action="{{ route('newsletter.subscribe') }}" method="POST" style="display: flex; gap: 0.75rem; max-width: 400px; margin: 0 auto;">
        @csrf
        <input type="email" name="email" placeholder="your@email.com" required
            style="flex: 1; padding: 0.625rem 0.875rem; background: var(--bg-secondary); border: 1px solid var(--border-light); border-radius: 8px; color: var(--text-primary); font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
            onfocus="this.style.borderColor='#555'" onblur="this.style.borderColor='var(--border-light)'">
        <button type="submit"
            style="padding: 0.625rem 1.25rem; background: var(--text-primary); color: var(--bg-primary); border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 700; cursor: pointer; white-space: nowrap; transition: background 0.2s;"
            onmouseover="this.style.background='#e0e0e0'" onmouseout="this.style.background='var(--text-primary)'">
            Subscribe
        </button>
    </form>
    <p style="color: var(--text-muted); font-size: 0.78rem; margin-top: 0.875rem;">
        <i class="fas fa-lock" style="font-size: 0.7rem; margin-right: 0.3rem;"></i>No spam, ever. Unsubscribe anytime.
    </p>
</div>
@endif
