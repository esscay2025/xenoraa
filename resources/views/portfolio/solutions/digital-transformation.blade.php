@extends('layouts.app')
@section('title', 'Digital Transformation | Gopi K')
@section('description', 'Modernize your enterprise. We help legacy organizations transition to cloud architectures, migrate data, and streamline workflows.')
@include('portfolio.solutions.style')
@section('content')
<div class="solutions-hero">
    <div class="solutions-hero-inner">
        <span class="solutions-badge"><i class="fas fa-sync"></i> Enterprise Modernization</span>
        <h1>Digital Transformation</h1>
        <p>Modernize your enterprise. We help legacy organizations transition to cloud architectures, migrate data, and streamline workflows for the digital age.</p>
    </div>
</div>

<div class="solutions-content">
    <div class="solutions-grid">
        <div class="solutions-image-container">
            <div class="solutions-image-glow"></div>
            <i class="fas fa-cloud-upload-alt" style="z-index:1;"></i>
        </div>
        <div class="solutions-text">
            <h2>Cloud Migration & Infrastructure</h2>
            <p>Legacy on-premise servers and fragmented databases limit business agility and create security risks. We help your business safely transition to robust cloud environments (AWS, Azure, or GCP).</p>
            <p>We architect secure virtual private clouds (VPCs), configure auto-scaling server clusters, implement containerization with Docker/Kubernetes, and establish strict automated backups, ensuring 99.99% uptime and enterprise-grade data security.</p>
        </div>
    </div>

    <div class="solutions-grid reverse">
        <div class="solutions-image-container">
            <div class="solutions-image-glow"></div>
            <i class="fas fa-database" style="z-index:1;"></i>
        </div>
        <div class="solutions-text">
            <h2>Data Modernization & BI</h2>
            <p>Data trapped in disconnected spreadsheets and paper files is a wasted asset. We build centralized data pipelines and warehouses that consolidate your operational, sales, and financial records into a single source of truth.</p>
            <p>Using modern Business Intelligence (BI) tools and custom analytical dashboards, we empower your executive team with real-time reports, predictive charts, and actionable metrics to drive data-backed decisions.</p>
        </div>
    </div>
</div>

<div class="process-section">
    <div class="process-inner">
        <div class="process-header">
            <h2>Our Transformation Roadmap</h2>
            <p>We minimize operational downtime by executing systematic, non-disruptive cloud migrations and software integrations.</p>
        </div>
        <div class="process-steps">
            <div class="process-step-card">
                <div class="process-step-num">1</div>
                <h3>Audit</h3>
                <p>We analyze your legacy hardware, software dependencies, and data flows to map all technical bottlenecks.</p>
            </div>
            <div class="process-step-card">
                <div class="process-step-num">2</div>
                <h3>Strategy</h3>
                <p>We design the target cloud architecture, plan data migration paths, and establish risk-mitigation backups.</p>
            </div>
            <div class="process-step-card">
                <div class="process-step-num">3</div>
                <h3>Execution</h3>
                <p>We set up cloud infrastructure, execute staging data migrations, and run side-by-side performance comparisons.</p>
            </div>
            <div class="process-step-card">
                <div class="process-step-num">4</div>
                <h3>Adoption</h3>
                <p>We transition active operations to the new platform, run employee training, and establish continuous monitoring.</p>
            </div>
        </div>
    </div>
</div>

<div class="cta-section">
    <div class="cta-card">
        <h2>Modernize Your Business Today</h2>
        <p>Overcome the limitations of legacy systems. Schedule an architectural consultation to map your transition to the cloud.</p>
        <a href="{{ route('about') }}#contact" class="btn btn-primary"><i class="fas fa-chart-line"></i> Consult with Gopi K</a>
    </div>
</div>
@endsection
