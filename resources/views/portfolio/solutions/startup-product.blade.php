@extends('layouts.app')
@section('title', 'Startup Product Development | Gopi K')
@section('description', 'From concept to launch. We help founders validate, design, and build high-performance Minimum Viable Products (MVPs) that scale.')
@include('portfolio.solutions.style')
@section('content')
<div class="solutions-hero">
    <div class="solutions-hero-inner">
        <span class="solutions-badge"><i class="fas fa-rocket"></i> Venture Engineering</span>
        <h1>Startup Product Development</h1>
        <p>From concept to launch. We help founders validate, design, and build high-performance Minimum Viable Products (MVPs) optimized for market entry and investor presentation.</p>
    </div>
</div>

<div class="solutions-content">
    <div class="solutions-grid">
        <div class="solutions-image-container">
            <div class="solutions-image-glow"></div>
            <i class="fas fa-vial" style="z-index:1;"></i>
        </div>
        <div class="solutions-text">
            <h2>Rapid MVP Development</h2>
            <p>For early-stage startups, speed to market is everything. Building too much too early can drain capital. We help you define your core value proposition and build a focused, high-performance Minimum Viable Product (MVP).</p>
            <p>Our MVPs are engineered using scalable, production-grade architectures. This ensures that when you find product-market fit, your code doesn't need to be rewritten from scratch — it's ready to handle thousands of users.</p>
        </div>
    </div>

    <div class="solutions-grid reverse">
        <div class="solutions-image-container">
            <div class="solutions-image-glow"></div>
            <i class="fas fa-shield-alt" style="z-index:1;"></i>
        </div>
        <div class="solutions-text">
            <h2>Fractional CTO & Architecture</h2>
            <p>Early-stage founders often lack the technical leadership needed to make long-term architectural decisions. Our Fractional CTO services provide elite engineering direction, helping you select your tech stack, design cloud infrastructure, and establish security standards.</p>
            <p>We represent your startup in technical investor meetings, prepare detailed system documentation, and help you hire and onboard your permanent internal engineering team.</p>
        </div>
    </div>
</div>

<div class="process-section">
    <div class="process-inner">
        <div class="process-header">
            <h2>Our Startup Process</h2>
            <p>We work in hyper-fast, collaborative cycles designed to maximize learning, conserve capital, and hit milestones quickly.</p>
        </div>
        <div class="process-steps">
            <div class="process-step-card">
                <div class="process-step-num">1</div>
                <h3>Scope & Focus</h3>
                <p>We run a collaborative workshop to strip away non-essential features and define the leanest possible MVP scope.</p>
            </div>
            <div class="process-step-card">
                <div class="process-step-num">2</div>
                <h3>Prototype</h3>
                <p>We create interactive wireframes to validate user experience and flows before writing any code.</p>
            </div>
            <div class="process-step-card">
                <div class="process-step-num">3</div>
                <h3>Build (MVP)</h3>
                <p>We build your product using clean, modular code with robust APIs, ready for integrations.</p>
            </div>
            <div class="process-step-card">
                <div class="process-step-num">4</div>
                <h3>Launch & Scale</h3>
                <p>We deploy to scalable cloud clusters, set up product analytics, and assist with your launch strategy.</p>
            </div>
        </div>
    </div>
</div>

<div class="cta-section">
    <div class="cta-card">
        <h2>Building the Next Big Thing?</h2>
        <p>Let's map out your product roadmap, define your MVP, and outline the technical architecture to bring your vision to life.</p>
        <a href="{{ route('about') }}#contact" class="btn btn-primary"><i class="fas fa-bolt"></i> Kickstart Your MVP</a>
    </div>
</div>
@endsection
