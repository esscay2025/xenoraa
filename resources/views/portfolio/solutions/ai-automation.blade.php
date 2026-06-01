@extends('layouts.app')
@section('title', 'AI Solutions & Automation | Gopi K')
@section('description', 'Drive exponential efficiency with intelligent agentic workflows, custom LLM integrations, and robust robotic process automation.')
@include('portfolio.solutions.style')
@section('content')
<div class="solutions-hero">
    <div class="solutions-hero-inner">
        <span class="solutions-badge"><i class="fas fa-robot"></i> Enterprise Automation</span>
        <h1>AI Solutions & Automation</h1>
        <p>Drive exponential efficiency with intelligent agentic workflows, custom LLM integrations, and robust robotic process automation tailored for your business operations.</p>
    </div>
</div>

<div class="solutions-content">
    <div class="solutions-grid">
        <div class="solutions-image-container">
            <div class="solutions-image-glow"></div>
            <i class="fas fa-brain" style="z-index:1;"></i>
        </div>
        <div class="solutions-text">
            <h2>Cognitive Automation & Agents</h2>
            <p>We build specialized AI agents capable of reasoning, using tools, and executing complex multi-step workflows. Unlike simple chatbots, our cognitive solutions connect directly to your database and APIs to automate data-heavy tasks, customer support, and internal operations with minimal supervision.</p>
            <p>By leveraging state-of-the-art Large Language Models (LLMs) and Vector Databases, we construct context-aware intelligence that understands your proprietary business rules, products, and user preferences.</p>
        </div>
    </div>

    <div class="solutions-grid reverse">
        <div class="solutions-image-container">
            <div class="solutions-image-glow"></div>
            <i class="fas fa-cogs" style="z-index:1;"></i>
        </div>
        <div class="solutions-text">
            <h2>Robotic Process Automation (RPA)</h2>
            <p>Repetitive manual data entry, legacy system synchronization, and document extraction cost organizations thousands of hours weekly. Our RPA services build software bots that mimic human screen interactions, securely handling files, forms, and system transfers.</p>
            <p>We integrate optical character recognition (OCR) and document intelligence to process invoices, contracts, and applications automatically, feeding clean structured data directly into your CRM or ERP.</p>
        </div>
    </div>
</div>

<div class="process-section">
    <div class="process-inner">
        <div class="process-header">
            <h2>How We Do It</h2>
            <p>Our systematic methodology ensures your AI and automation systems are robust, scalable, and return measurable ROI.</p>
        </div>
        <div class="process-steps">
            <div class="process-step-card">
                <div class="process-step-num">1</div>
                <h3>Discovery</h3>
                <p>We audit your existing workflows to identify high-impact automation candidates and analyze data readiness.</p>
            </div>
            <div class="process-step-card">
                <div class="process-step-num">2</div>
                <h3>Architect</h3>
                <p>We map the agentic decision trees, select LLM frameworks (LangChain, LlamaIndex), and design secure database/API bridges.</p>
            </div>
            <div class="process-step-card">
                <div class="process-step-num">3</div>
                <h3>Build & Fine-tune</h3>
                <p>We develop custom pipelines, ingest enterprise data using RAG (Retrieval-Augmented Generation), and run iterative edge-case testing.</p>
            </div>
            <div class="process-step-card">
                <div class="process-step-num">4</div>
                <h3>Deploy & Monitor</h3>
                <p>We deploy bots into secure environments, establish continuous evaluation loops, and provide operational dashboards.</p>
            </div>
        </div>
    </div>
</div>

<div class="cta-section">
    <div class="cta-card">
        <h2>Ready to Automate Your Operations?</h2>
        <p>Schedule a technical discovery session with Gopi K to evaluate your processes and outline a high-ROI automation roadmap.</p>
        <a href="{{ route('about') }}#contact" class="btn btn-primary"><i class="fas fa-calendar-alt"></i> Schedule Discovery Session</a>
    </div>
</div>
@endsection
