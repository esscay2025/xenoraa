@extends('layouts.app')
@section('title', 'Custom Application Development | Gopi K')
@section('description', 'Engineered to perform. We design and build secure, scalable, and custom web and mobile applications tailored to solve your unique business challenges.')
@include('portfolio.solutions.style')
@section('content')
<div class="solutions-hero">
    <div class="solutions-hero-inner">
        <span class="solutions-badge"><i class="fas fa-code"></i> Engineering Excellence</span>
        <h1>Custom Application Development</h1>
        <p>Engineered to perform. We design and build secure, scalable, and custom web and mobile applications tailored to solve your unique business challenges.</p>
    </div>
</div>

<div class="solutions-content">
    <div class="solutions-grid">
        <div class="solutions-image-container">
            <div class="solutions-image-glow"></div>
            <i class="fas fa-laptop-code" style="z-index:1;"></i>
        </div>
        <div class="solutions-text">
            <h2>Bespoke Web Applications</h2>
            <p>Off-the-shelf software often forces you to compromise your business processes. Our custom web applications are built from the ground up to match your exact workflows, operational logic, and scaling demands.</p>
            <p>Using robust modern frameworks like Laravel, Node.js, and React, we deliver secure, high-concurrency web platforms, customer portals, internal management dashboards, and SaaS products designed for flawless user experiences.</p>
        </div>
    </div>

    <div class="solutions-grid reverse">
        <div class="solutions-image-container">
            <div class="solutions-image-glow"></div>
            <i class="fas fa-mobile-alt" style="z-index:1;"></i>
        </div>
        <div class="solutions-text">
            <h2>Native & Hybrid Mobile Apps</h2>
            <p>Engage your customers and empower your field workforce with premium mobile experiences. We develop native iOS and Android apps, as well as cross-platform solutions using React Native and Flutter, ensuring native-grade performance with optimized shared codebases.</p>
            <p>Our mobile solutions feature offline-first databases, real-time sync, background location tracking, secure biometrics, and push notifications, all backed by highly secure, scalable cloud APIs.</p>
        </div>
    </div>
</div>

<div class="process-section">
    <div class="process-inner">
        <div class="process-header">
            <h2>Our Development Process</h2>
            <p>We combine agile development cycles with rigorous software engineering practices to deliver high-quality software on schedule.</p>
        </div>
        <div class="process-steps">
            <div class="process-step-card">
                <div class="process-step-num">1</div>
                <h3>Blueprint</h3>
                <p>We document your functional requirements, map user journeys, and establish database entity-relationship models.</p>
            </div>
            <div class="process-step-card">
                <div class="process-step-num">2</div>
                <h3>UX/UI Design</h3>
                <p>We create high-fidelity interactive wireframes, ensuring modern aesthetics, responsive layouts, and accessible designs.</p>
            </div>
            <div class="process-step-card">
                <div class="process-step-num">3</div>
                <h3>Agile Sprints</h3>
                <p>We build in iterative 2-week sprints, providing continuous test environments for you to review and refine features.</p>
            </div>
            <div class="process-step-card">
                <div class="process-step-num">4</div>
                <h3>Launch & Support</h3>
                <p>We run automated security audits, deploy to secure cloud environments (AWS/DigitalOcean), and provide ongoing SLA maintenance.</p>
            </div>
        </div>
    </div>
</div>

<div class="cta-section">
    <div class="cta-card">
        <h2>Have a Custom App Idea?</h2>
        <p>Let's turn your concept into a production-ready application. Share your requirements and get a detailed architectural and cost estimate.</p>
        <a href="{{ route('about') }}#contact" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Request Technical Proposal</a>
    </div>
</div>
@endsection
