@extends('layouts.app')

@section('title', 'About Gopi K — Founder, Engineer & Entrepreneur | Go Esscay Solutions')
@section('description', 'Learn about Gopi K — Software Engineer turned Entrepreneur, Founder of Go Esscay Solutions. 14+ years in enterprise technology, AI automation, and digital transformation.')

@push('styles')
<style>
    /* ===== HERO ===== */
    .about-hero {
        background: linear-gradient(135deg, #0a0a0a 0%, #111 60%, #0a0a0a 100%);
        padding: 6rem 2rem 4rem;
        position: relative;
        overflow: hidden;
    }
    .about-hero::before {
        content: '';
        position: absolute;
        top: -100px; right: -100px;
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,0.03) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .about-hero-inner {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1.6fr;
        gap: 4rem;
        align-items: center;
    }
    .about-photo-wrap {
        position: relative;
        display: flex;
        justify-content: center;
    }
    .about-photo {
        width: 320px;
        height: 380px;
        border-radius: 20px;
        object-fit: cover;
        object-position: center top;
        border: 2px solid rgba(255,255,255,0.12);
        box-shadow: 0 30px 80px rgba(0,0,0,0.6);
        display: block;
    }
    .about-photo-badge {
        position: absolute;
        bottom: -16px;
        left: 50%;
        transform: translateX(-50%);
        background: #fff;
        color: #000;
        padding: 0.5rem 1.25rem;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 700;
        white-space: nowrap;
        letter-spacing: 0.05em;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .about-hero-content .eyebrow {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: var(--text-muted);
        margin-bottom: 0.75rem;
    }
    .about-hero-content h1 {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1.1;
        margin: 0 0 0.5rem;
        letter-spacing: -1px;
    }
    .about-hero-content .tagline {
        font-size: 1.15rem;
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
        font-weight: 500;
    }
    .about-hero-content .motto {
        display: inline-block;
        font-size: 1.1rem;
        font-style: italic;
        color: var(--text-primary);
        border-left: 3px solid #fff;
        padding-left: 1rem;
        margin-bottom: 2rem;
    }
    .about-meta-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .about-meta-item {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
    }
    .about-meta-item .val {
        font-size: 1.4rem;
        font-weight: 800;
        display: block;
    }
    .about-meta-item .lbl {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.2rem;
    }
    .social-row {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .social-row a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px; height: 40px;
        border-radius: 50%;
        background: var(--bg-card);
        border: 1px solid var(--border);
        color: var(--text-secondary);
        font-size: 1rem;
        transition: background 0.2s, color 0.2s, border-color 0.2s;
        text-decoration: none;
    }
    .social-row a:hover {
        background: #fff;
        color: #000;
        border-color: #fff;
    }

    /* ===== SECTION COMMON ===== */
    .about-section {
        padding: 5rem 2rem;
    }
    .about-section.alt {
        background: var(--bg-secondary);
    }
    .about-section-inner {
        max-width: 1200px;
        margin: 0 auto;
    }
    .section-eyebrow {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }
    .section-heading {
        font-size: 2rem;
        font-weight: 800;
        margin: 0 0 2.5rem;
        letter-spacing: -0.5px;
    }

    /* ===== STORY ===== */
    .story-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        align-items: start;
    }
    .story-text p {
        color: var(--text-secondary);
        line-height: 1.8;
        margin-bottom: 1.25rem;
        font-size: 1rem;
    }
    .highlight-box {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-left: 4px solid #fff;
        border-radius: 8px;
        padding: 1.25rem 1.5rem;
        margin-top: 1.5rem;
        font-size: 0.95rem;
        color: var(--text-secondary);
        line-height: 1.7;
    }

    /* ===== TIMELINE ===== */
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 7px; top: 0; bottom: 0;
        width: 2px;
        background: var(--border);
    }
    .timeline-item {
        position: relative;
        padding-bottom: 2.5rem;
    }
    .timeline-item:last-child { padding-bottom: 0; }
    .timeline-dot {
        position: absolute;
        left: -2rem;
        top: 4px;
        width: 16px; height: 16px;
        border-radius: 50%;
        background: var(--bg-primary);
        border: 2px solid #fff;
        z-index: 1;
    }
    .timeline-dot.current {
        background: #fff;
        box-shadow: 0 0 0 4px rgba(255,255,255,0.15);
    }
    .timeline-period {
        font-size: 0.78rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.3rem;
    }
    .timeline-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0 0 0.2rem;
    }
    .timeline-company {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-bottom: 0.75rem;
    }
    .timeline-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-top: 0.75rem;
    }
    .timeline-tag {
        background: var(--bg-secondary);
        border: 1px solid var(--border);
        border-radius: 4px;
        padding: 0.2rem 0.6rem;
        font-size: 0.75rem;
        color: var(--text-secondary);
    }
    .timeline-desc {
        font-size: 0.9rem;
        color: var(--text-secondary);
        line-height: 1.7;
    }

    /* ===== SKILLS ===== */
    .skills-category {
        margin-bottom: 2rem;
    }
    .skills-category-title {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: var(--text-muted);
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border);
    }
    .skills-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
    }
    .skill-pill {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 0.4rem 0.9rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--text-primary);
        transition: background 0.2s, border-color 0.2s;
    }
    .skill-pill:hover {
        background: #fff;
        color: #000;
        border-color: #fff;
    }

    /* ===== PASSION CARDS ===== */
    .passion-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    .passion-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 2rem;
        transition: transform 0.2s, border-color 0.2s;
    }
    .passion-card:hover {
        transform: translateY(-4px);
        border-color: rgba(255,255,255,0.3);
    }
    .passion-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
    }
    .passion-card h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0 0 0.75rem;
    }
    .passion-card p {
        font-size: 0.9rem;
        color: var(--text-secondary);
        line-height: 1.7;
        margin: 0;
    }

    /* ===== EDUCATION ===== */
    .edu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.25rem;
    }
    .edu-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.5rem;
    }
    .edu-degree {
        font-size: 1rem;
        font-weight: 700;
        margin: 0 0 0.3rem;
    }
    .edu-school {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }
    .edu-year {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    /* ===== PHILOSOPHY ===== */
    .philosophy-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
    }
    .philosophy-item {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
    }
    .philosophy-item i {
        font-size: 1.75rem;
        margin-bottom: 0.75rem;
        display: block;
        color: var(--text-secondary);
    }
    .philosophy-item h4 {
        font-size: 0.95rem;
        font-weight: 700;
        margin: 0 0 0.5rem;
    }
    .philosophy-item p {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.6;
    }

    /* ===== CTA ===== */
    .about-cta {
        background: linear-gradient(135deg, #111 0%, #1a1a1a 100%);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 3.5rem;
        text-align: center;
        margin-top: 1rem;
    }
    .about-cta h2 {
        font-size: 2rem;
        font-weight: 800;
        margin: 0 0 1rem;
    }
    .about-cta p {
        color: var(--text-secondary);
        font-size: 1.05rem;
        margin-bottom: 2rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 900px) {
        .about-hero-inner { grid-template-columns: 1fr; gap: 2.5rem; text-align: center; }
        .about-photo-wrap { justify-content: center; }
        .about-meta-grid { grid-template-columns: repeat(3, 1fr); }
        .social-row { justify-content: center; }
        .story-grid { grid-template-columns: 1fr; }
        .about-hero-content h1 { font-size: 2.25rem; }
    }
    @media (max-width: 600px) {
        .about-meta-grid { grid-template-columns: 1fr 1fr; }
        .about-photo { width: 260px; height: 300px; }
        .about-cta { padding: 2rem 1.5rem; }
    }
</style>
@endpush

@section('content')

{{-- ===== HERO ===== --}}
<section class="about-hero">
    <div class="about-hero-inner">
        <div class="about-photo-wrap">
            <img src="{{ asset('images/gopi-profile.png') }}" alt="Gopi K" class="about-photo">
            <div class="about-photo-badge">&#9679; Available for Projects</div>
        </div>
        <div class="about-hero-content">
            <p class="eyebrow">About Me</p>
            <h1>Gopi K</h1>
            <p class="tagline">Engineer &bull; Entrepreneur &bull; Innovator &bull; Social Impact Builder</p>
            <p class="motto">"Born for IT."</p>
            <div class="about-meta-grid">
                <div class="about-meta-item">
                    <span class="val">14+</span>
                    <span class="lbl">Years in Tech</span>
                </div>
                <div class="about-meta-item">
                    <span class="val">3</span>
                    <span class="lbl">Global Enterprises</span>
                </div>
                <div class="about-meta-item">
                    <span class="val">2025</span>
                    <span class="lbl">Founded Esscay</span>
                </div>
                <div class="about-meta-item">
                    <span class="val">Chennai</span>
                    <span class="lbl">Based In</span>
                </div>
                <div class="about-meta-item">
                    <span class="val">AI</span>
                    <span class="lbl">Current Focus</span>
                </div>
                <div class="about-meta-item">
                    <span class="val">∞</span>
                    <span class="lbl">Passion</span>
                </div>
            </div>
            <div class="social-row">
                @foreach($socialLinks as $social)
                <a href="{{ $social->url }}" target="_blank" rel="noopener" title="{{ $social->label ?? ucfirst($social->platform) }}">
                    <i class="{{ $social->icon_class }}"></i>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ===== MY STORY ===== --}}
<section class="about-section">
    <div class="about-section-inner">
        <p class="section-eyebrow">My Story</p>
        <h2 class="section-heading">From Code to Company</h2>
        <div class="story-grid">
            <div class="story-text">
                <p>I am <strong>Gopi K</strong>, the Founder and Director of <strong>Go Esscay Solutions Pvt Ltd</strong>. My journey in technology began in 2011, and over 14+ years I have evolved from a software engineer writing enterprise Java code to a solution architect, and ultimately to an entrepreneur building a company that helps businesses embrace technology without complexity.</p>
                <p>I grew up in Chennai and completed my B.Tech in Information Technology from Sree Sastha Institute of Engineering & Technology (Anna University, 2010), followed by an MBA in HR from Pondicherry University (2014). These dual disciplines — engineering and management — have deeply shaped how I think about technology, people, and business.</p>
                <p>My career took me through some of the world's most respected technology companies, where I worked on mission-critical systems for global clients including <strong>Nationwide Insurance (USA)</strong>, <strong>Lloyds Banking Group (UK)</strong>, and <strong>First Data Corporation (USA)</strong>. These experiences gave me a global perspective and a deep appreciation for quality, reliability, and scale.</p>
                <div class="highlight-box">
                    <strong>"Technology is not just my career — it is my identity."</strong><br>
                    I am committed to building innovative solutions, empowering businesses through digital transformation, and creating a positive impact through technology, entrepreneurship, and social responsibility.
                </div>
            </div>
            <div class="story-text">
                <p>In 2025, I took the most defining step of my career — founding <strong>Go Esscay Solutions Pvt Ltd</strong>. The vision was clear: help startups and small businesses implement IT, automation, and open-source applications to run their operations smarter, faster, and more efficiently.</p>
                <p>What started as a technology services company is rapidly evolving into a platform focused on helping organisations digitally transform their operations using AI, automation, and open-source tools.</p>
                <p>Beyond business, I have a deep passion for <strong>Artificial Intelligence & Automation</strong>, <strong>Ethical Hacking & Cybersecurity</strong>, and <strong>Social Entrepreneurship</strong>. I believe the most meaningful use of technology is to create lasting impact — in businesses, communities, and lives.</p>
                <p>My long-term aspiration is to become a <strong>Social Entrepreneur</strong> who creates sustainable impact through technology — supporting youth empowerment, digital transformation for small businesses, employment generation, and animal welfare initiatives.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== PROFESSIONAL EXPERIENCE ===== --}}
<section class="about-section alt">
    <div class="about-section-inner">
        <p class="section-eyebrow">Career Journey</p>
        <h2 class="section-heading">Professional Experience</h2>
        <div class="timeline">

            <div class="timeline-item">
                <div class="timeline-dot current"></div>
                <div class="timeline-period">2025 – Present</div>
                <div class="timeline-title">Founder & Director</div>
                <div class="timeline-company">Go Esscay Solutions Pvt Ltd &mdash; Chennai, India</div>
                <p class="timeline-desc">Founded Go Esscay Solutions with a vision to help businesses embrace technology without complexity. Leading a team delivering website development, digital marketing, open-source solutions, CRM implementation, business automation, AI integration, and DevOps consulting. The company is evolving into a platform for digital transformation at scale.</p>
                <div class="timeline-tags">
                    <span class="timeline-tag">Entrepreneurship</span>
                    <span class="timeline-tag">AI Integration</span>
                    <span class="timeline-tag">Business Automation</span>
                    <span class="timeline-tag">DevOps</span>
                    <span class="timeline-tag">CRM</span>
                    <span class="timeline-tag">Digital Marketing</span>
                    <span class="timeline-tag">Open Source</span>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-period">2022 – 2024</div>
                <div class="timeline-title">Solution Architect</div>
                <div class="timeline-company">Trillion Thoughts Technologies &mdash; Chennai, India</div>
                <p class="timeline-desc">Transitioned from pure software development into solution architecture and business leadership. Responsible for team management, business operations, project delivery, customer engagement, and solution consulting. This role strengthened the ability to bridge the gap between technology and business outcomes.</p>
                <div class="timeline-tags">
                    <span class="timeline-tag">Solution Architecture</span>
                    <span class="timeline-tag">Team Management</span>
                    <span class="timeline-tag">Project Delivery</span>
                    <span class="timeline-tag">Customer Engagement</span>
                    <span class="timeline-tag">Business Operations</span>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-period">2014 – 2021</div>
                <div class="timeline-title">Senior Associate</div>
                <div class="timeline-company">Cognizant Technology Solutions &mdash; Chennai, India</div>
                <p class="timeline-desc">Worked on mission-critical applications for global organisations including Nationwide Insurance (USA), Lloyds Banking Group (UK), and First Data Corporation (USA). Contributed to retirement systems, insurance platforms, online banking applications, underwriting systems, and policy management solutions.</p>
                <div class="timeline-tags">
                    <span class="timeline-tag">Core Java</span>
                    <span class="timeline-tag">Spring MVC</span>
                    <span class="timeline-tag">Spring Security</span>
                    <span class="timeline-tag">Spring Batch</span>
                    <span class="timeline-tag">Hibernate</span>
                    <span class="timeline-tag">Oracle</span>
                    <span class="timeline-tag">REST APIs</span>
                    <span class="timeline-tag">Agile Scrum</span>
                    <span class="timeline-tag">Jenkins</span>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-period">2011 – 2014</div>
                <div class="timeline-title">Senior Software Engineer</div>
                <div class="timeline-company">Ensteps Solutions Pvt Ltd &mdash; Chennai, India</div>
                <p class="timeline-desc">Worked on large-scale educational management systems and enterprise web applications. Built strong foundations in Java development, Spring Framework, MySQL, and application architecture that would define the rest of the career.</p>
                <div class="timeline-tags">
                    <span class="timeline-tag">Java</span>
                    <span class="timeline-tag">Spring Framework</span>
                    <span class="timeline-tag">MySQL</span>
                    <span class="timeline-tag">Application Architecture</span>
                    <span class="timeline-tag">Enterprise Software</span>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ===== CORE COMPETENCIES ===== --}}
<section class="about-section">
    <div class="about-section-inner">
        <p class="section-eyebrow">Competencies</p>
        <h2 class="section-heading">Core Skills & Expertise</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            <div class="skills-category">
                <div class="skills-category-title"><i class="fas fa-code" style="margin-right:0.4rem;"></i> Technology</div>
                <div class="skills-wrap">
                    <span class="skill-pill">Java</span>
                    <span class="skill-pill">Spring Boot</span>
                    <span class="skill-pill">Spring MVC</span>
                    <span class="skill-pill">REST APIs</span>
                    <span class="skill-pill">Hibernate</span>
                    <span class="skill-pill">MyBatis</span>
                    <span class="skill-pill">Oracle</span>
                    <span class="skill-pill">MySQL</span>
                    <span class="skill-pill">PHP / Laravel</span>
                    <span class="skill-pill">Git</span>
                    <span class="skill-pill">Jenkins</span>
                    <span class="skill-pill">DevOps</span>
                    <span class="skill-pill">AI Automation</span>
                    <span class="skill-pill">Open Source</span>
                </div>
            </div>
            <div class="skills-category">
                <div class="skills-category-title"><i class="fas fa-chart-line" style="margin-right:0.4rem;"></i> Business</div>
                <div class="skills-wrap">
                    <span class="skill-pill">Digital Transformation</span>
                    <span class="skill-pill">Business Development</span>
                    <span class="skill-pill">Sales Strategy</span>
                    <span class="skill-pill">CRM Implementation</span>
                    <span class="skill-pill">Solution Consulting</span>
                    <span class="skill-pill">Project Management</span>
                    <span class="skill-pill">Client Relations</span>
                    <span class="skill-pill">Startup Consulting</span>
                </div>
            </div>
            <div class="skills-category">
                <div class="skills-category-title"><i class="fas fa-users" style="margin-right:0.4rem;"></i> Leadership</div>
                <div class="skills-wrap">
                    <span class="skill-pill">Team Building</span>
                    <span class="skill-pill">Mentoring</span>
                    <span class="skill-pill">Strategic Planning</span>
                    <span class="skill-pill">Entrepreneurship</span>
                    <span class="skill-pill">Innovation Management</span>
                    <span class="skill-pill">Agile Methodologies</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PASSION AREAS ===== --}}
<section class="about-section alt">
    <div class="about-section-inner">
        <p class="section-eyebrow">What Drives Me</p>
        <h2 class="section-heading">Passion Areas</h2>
        <div class="passion-grid">
            <div class="passion-card">
                <div class="passion-icon">&#129302;</div>
                <h3>Artificial Intelligence & Automation</h3>
                <p>I strongly believe AI will redefine the future of business. My current focus includes AI-powered business automation, AI Agents, workflow automation, CRM automation, business process digitisation, productivity systems, and open-source AI solutions.</p>
            </div>
            <div class="passion-card">
                <div class="passion-icon">&#128737;</div>
                <h3>Ethical Hacking & Cybersecurity</h3>
                <p>Beyond software engineering, I have a deep personal interest in ethical hacking, security testing, vulnerability assessment, secure application design, and cybersecurity best practices — explored as a passion-driven pursuit over many years.</p>
            </div>
            <div class="passion-card">
                <div class="passion-icon">&#127758;</div>
                <h3>Social Entrepreneurship</h3>
                <p>My long-term goal extends beyond building successful businesses. I aspire to create sustainable impact through technology — supporting technology education, youth empowerment, digital transformation for small businesses, employment generation, and animal welfare initiatives.</p>
            </div>
            <div class="passion-card">
                <div class="passion-icon">&#128640;</div>
                <h3>Open Source & Innovation</h3>
                <p>I am a strong advocate for open-source technology. I believe that accessible, affordable, and powerful open-source solutions can level the playing field for small businesses and startups — and I actively build and promote such solutions.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== LEADERSHIP PHILOSOPHY ===== --}}
<section class="about-section">
    <div class="about-section-inner">
        <p class="section-eyebrow">My Philosophy</p>
        <h2 class="section-heading">Leadership & Values</h2>
        <div class="philosophy-grid">
            <div class="philosophy-item">
                <i class="fas fa-lightbulb"></i>
                <h4>Create Opportunities</h4>
                <p>Leadership is about opening doors for others and enabling growth through vision and action.</p>
            </div>
            <div class="philosophy-item">
                <i class="fas fa-puzzle-piece"></i>
                <h4>Solve Meaningful Problems</h4>
                <p>Focus on problems that matter — challenges that, when solved, create real value for real people.</p>
            </div>
            <div class="philosophy-item">
                <i class="fas fa-users"></i>
                <h4>Build Strong Teams</h4>
                <p>Successful organisations are built on people, purpose, and continuous learning — not just technology.</p>
            </div>
            <div class="philosophy-item">
                <i class="fas fa-seedling"></i>
                <h4>Enable Growth</h4>
                <p>Empower every individual and organisation to reach their full potential through the right tools and mindset.</p>
            </div>
            <div class="philosophy-item">
                <i class="fas fa-infinity"></i>
                <h4>Long-Term Value</h4>
                <p>Build for sustainability, not just speed. Create solutions that last and impact that endures.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== EDUCATION ===== --}}
<section class="about-section alt">
    <div class="about-section-inner">
        <p class="section-eyebrow">Academic Background</p>
        <h2 class="section-heading">Education</h2>
        <div class="edu-grid">
            <div class="edu-card">
                <div style="font-size:1.5rem; margin-bottom:0.75rem;">&#127891;</div>
                <div class="edu-degree">MBA (Human Resources)</div>
                <div class="edu-school">Pondicherry University</div>
                <div class="edu-year">2012 – 2014</div>
            </div>
            <div class="edu-card">
                <div style="font-size:1.5rem; margin-bottom:0.75rem;">&#128187;</div>
                <div class="edu-degree">B.Tech — Information Technology</div>
                <div class="edu-school">Sree Sastha Institute of Engineering & Technology, Anna University</div>
                <div class="edu-year">2006 – 2010</div>
            </div>
            <div class="edu-card">
                <div style="font-size:1.5rem; margin-bottom:0.75rem;">&#128218;</div>
                <div class="edu-degree">Higher Secondary Education</div>
                <div class="edu-school">Holy Cross Matriculation Higher Secondary School</div>
                <div class="edu-year">Pre-University</div>
            </div>
            <div class="edu-card">
                <div style="font-size:1.5rem; margin-bottom:0.75rem;">&#128214;</div>
                <div class="edu-degree">Secondary Education</div>
                <div class="edu-school">Avichi Higher Secondary School</div>
                <div class="edu-year">Secondary</div>
            </div>
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="about-section">
    <div class="about-section-inner">
        <div class="about-cta">
            <h2>Let's Build Something Together</h2>
            <p>Whether you need a technology partner, a consultant, or just want to connect — I am always open to meaningful conversations about technology, business, and impact.</p>
            <div class="cta-buttons">
                <a href="{{ route('jobs') }}" class="btn btn-primary">
                    <i class="fas fa-briefcase"></i> View Open Positions
                </a>
                <a href="{{ route('blog') }}" class="btn btn-outline">
                    <i class="fas fa-pen-nib"></i> Read My Blog
                </a>
                @foreach($socialLinks->where('platform','linkedin') as $li)
                <a href="{{ $li->url }}" class="btn btn-outline" target="_blank" rel="noopener">
                    <i class="fab fa-linkedin-in"></i> Connect on LinkedIn
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

@endsection
