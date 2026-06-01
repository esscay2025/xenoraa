@extends('layouts.app')
@section('title', 'Branding & Digital Presence | Gopi K')
@section('description', 'Command attention. We build beautiful, high-converting websites, corporate brands, and professional digital identities.')
@include('portfolio.solutions.style')
@section('content')
<div class="solutions-hero">
    <div class="solutions-hero-inner">
        <span class="solutions-badge"><i class="fas fa-magic"></i> Digital Identity</span>
        <h1>Branding & Digital Presence</h1>
        <p>Command attention. We build beautiful, high-converting websites, corporate brands, and professional digital identities that establish authority and capture leads.</p>
    </div>
</div>

<div class="solutions-content">
    <div class="solutions-grid">
        <div class="solutions-image-container">
            <div class="solutions-image-glow"></div>
            <i class="fas fa-palette" style="z-index:1;"></i>
        </div>
        <div class="solutions-text">
            <h2>Corporate Brand Strategy</h2>
            <p>Your brand is more than a logo; it is the gut feeling your customers have about your business. We develop cohesive brand systems that communicate your values, professionalism, and industry authority.</p>
            <p>From custom typography and color palettes to corporate style guides and marketing collateral, we ensure your business maintains a premium, consistent visual identity across all online and offline channels.</p>
        </div>
    </div>

    <div class="solutions-grid reverse">
        <div class="solutions-image-container">
            <div class="solutions-image-glow"></div>
            <i class="fas fa-bullseye" style="z-index:1;"></i>
        </div>
        <div class="solutions-text">
            <h2>High-Converting Websites</h2>
            <p>A beautiful website is useless if it doesn't generate business. We design and build lightning-fast, responsive corporate websites, landing pages, and portfolios optimized for search engines (SEO) and user conversions.</p>
            <p>Every page we build is engineered for performance, utilizing semantic HTML, highly optimized media assets, and structured schema markup to rank higher on Google and load in under 1.5 seconds.</p>
        </div>
    </div>
</div>

<div class="process-section">
    <div class="process-inner">
        <div class="process-header">
            <h2>Our Creative Process</h2>
            <p>We merge visual storytelling with performance engineering to create digital experiences that look stunning and deliver results.</p>
        </div>
        <div class="process-steps">
            <div class="process-step-card">
                <div class="process-step-num">1</div>
                <h3>Brand Audit</h3>
                <p>We research your competitors, analyze your target audience, and define your brand positioning.</p>
            </div>
            <div class="process-step-card">
                <div class="process-step-num">2</div>
                <h3>Visual Design</h3>
                <p>We develop your brand assets, logo variants, and high-fidelity page mockups for your feedback.</p>
            </div>
            <div class="process-step-card">
                <div class="process-step-num">3</div>
                <h3>Performance Dev</h3>
                <p>We convert designs into clean, semantic, responsive layouts with optimized scripts and stylesheets.</p>
            </div>
            <div class="process-step-card">
                <div class="process-step-num">4</div>
                <h3>SEO & Launch</h3>
                <p>We configure schema markup, submit sitemaps to search engines, and set up conversion tracking before launch.</p>
            </div>
        </div>
    </div>
</div>

<div class="cta-section">
    <div class="cta-card">
        <h2>Elevate Your Digital Presence</h2>
        <p>Establish industry authority and turn casual visitors into loyal customers. Let's design a brand and website that truly represents your excellence.</p>
        <a href="{{ route('about') }}#contact" class="btn btn-primary"><i class="fas fa-paint-brush"></i> Launch Your Brand</a>
    </div>
</div>
@endsection
