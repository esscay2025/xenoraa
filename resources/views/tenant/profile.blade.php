@extends('layouts.app')

@section('title', ($tenant->site_title ?? $tenant->name) . ' — ' . config('xenoraa.platform_name', 'Xenoraa'))
@section('meta_description', $tenant->bio ?? ('Professional profile of ' . $tenant->name . ' on Xenoraa'))

@section('content')
{{-- ============================================================
     TENANT PROFILE PAGE — xenoraa.com/{username} or custom domain
     ============================================================ --}}

@php
    $ownerName   = $tenant->site_title ?? $tenant->name;
    $ownerBio    = $tenant->bio ?? '';
    $ownerAvatar = $tenant->avatar ?? null;
    $settings    = $siteSettings ?? [];
@endphp

{{-- Hero --}}
<section style="padding:5rem 1.5rem 3rem;background:linear-gradient(180deg,#0a0a12 0%,#050508 100%);text-align:center;position:relative;overflow:hidden;">
    <div style="position:absolute;top:0;left:50%;transform:translateX(-50%);width:700px;height:350px;background:radial-gradient(ellipse,rgba(124,58,237,0.1) 0%,transparent 70%);pointer-events:none;"></div>
    <div style="position:relative;max-width:700px;margin:0 auto;">
        @if($ownerAvatar)
        <img src="{{ asset('storage/' . $ownerAvatar) }}" alt="{{ $ownerName }}" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid rgba(124,58,237,0.4);margin-bottom:1.5rem;">
        @else
        <div style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#06b6d4);display:flex;align-items:center;justify-content:center;font-size:2.5rem;font-weight:800;color:#fff;margin:0 auto 1.5rem;font-family:'Space Grotesk',sans-serif;">{{ strtoupper(substr($ownerName,0,1)) }}</div>
        @endif
        <h1 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(1.75rem,5vw,2.5rem);font-weight:800;color:#f0f0f5;margin-bottom:0.5rem;">{{ $ownerName }}</h1>
        <p style="color:#a78bfa;font-size:1rem;font-weight:600;margin-bottom:1rem;">{{ $settings['hero_subtitle'] ?? ($tenant->profession ?? 'Professional') }}</p>
        @if($ownerBio)
        <p style="color:#6b6b8a;font-size:0.95rem;line-height:1.7;max-width:560px;margin:0 auto 1.5rem;">{{ $ownerBio }}</p>
        @endif
        <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;">
            @if($settings['contact_email'] ?? $tenant->email)
            <a href="mailto:{{ $settings['contact_email'] ?? $tenant->email }}" style="display:inline-flex;align-items:center;gap:6px;padding:0.6rem 1.25rem;background:rgba(124,58,237,0.1);border:1px solid rgba(124,58,237,0.3);color:#a78bfa;border-radius:8px;text-decoration:none;font-size:0.875rem;font-weight:600;">Contact</a>
            @endif
            @if($tenant->username)
            <span style="display:inline-flex;align-items:center;gap:6px;padding:0.6rem 1.25rem;background:rgba(255,255,255,0.03);border:1px solid #1e1e2e;color:#6b6b8a;border-radius:8px;font-size:0.8rem;">xenoraa.com/{{ $tenant->username }}</span>
            @endif
        </div>
    </div>
</section>

{{-- Blog Posts --}}
@if($blogPosts && count($blogPosts) > 0)
<section style="padding:3rem 1.5rem;background:#050508;border-top:1px solid #0f0f1a;">
    <div style="max-width:1100px;margin:0 auto;">
        <h2 style="font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:700;color:#f0f0f5;margin-bottom:1.5rem;">Latest Articles</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem;">
            @foreach($blogPosts as $post)
            <a href="/{{ $tenant->username }}/blog/{{ $post->slug }}" style="display:block;background:rgba(255,255,255,0.02);border:1px solid #1e1e2e;border-radius:14px;overflow:hidden;text-decoration:none;transition:border-color 0.2s;" onmouseover="this.style.borderColor='rgba(124,58,237,0.4)'" onmouseout="this.style.borderColor='#1e1e2e'">
                @if($post->featured_image)
                <div style="height:160px;overflow:hidden;">
                    <img src="{{ str_starts_with($post->featured_image,'http') ? $post->featured_image : asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover;">
                </div>
                @endif
                <div style="padding:1.25rem;">
                    <h3 style="font-family:'Space Grotesk',sans-serif;font-size:1rem;font-weight:700;color:#f0f0f5;margin-bottom:0.5rem;line-height:1.4;">{{ $post->title }}</h3>
                    <p style="color:#6b6b8a;font-size:0.8rem;line-height:1.5;">{{ Str::limit($post->excerpt ?? strip_tags($post->content ?? ''), 100) }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Portfolio Items --}}
@if($portfolioItems && count($portfolioItems) > 0)
<section style="padding:3rem 1.5rem;background:#050508;border-top:1px solid #0f0f1a;">
    <div style="max-width:1100px;margin:0 auto;">
        <h2 style="font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:700;color:#f0f0f5;margin-bottom:1.5rem;">Portfolio</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.25rem;">
            @foreach($portfolioItems as $item)
            <div style="background:rgba(255,255,255,0.02);border:1px solid #1e1e2e;border-radius:14px;padding:1.5rem;">
                <h3 style="font-family:'Space Grotesk',sans-serif;font-size:1rem;font-weight:700;color:#f0f0f5;margin-bottom:0.5rem;">{{ $item->title ?? 'Project' }}</h3>
                <p style="color:#6b6b8a;font-size:0.8rem;line-height:1.5;">{{ Str::limit($item->description ?? '', 120) }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Powered by Xenoraa footer --}}
<div style="text-align:center;padding:2rem 1.5rem;border-top:1px solid #0f0f1a;">
    <a href="https://xenoraa.com" style="display:inline-flex;align-items:center;gap:6px;color:#3f3f46;font-size:0.75rem;text-decoration:none;" onmouseover="this.style.color='#a78bfa'" onmouseout="this.style.color='#3f3f46'">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
        Powered by Xenoraa
    </a>
</div>
@endsection
