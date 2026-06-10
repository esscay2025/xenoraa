<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $theme->name }} — {{ $theme->category }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --accent: {{ $accent }};
            --bg: {{ $bg }};
            --text: {{ $text }};
            --card: {{ $card }};
            --muted: {{ $isDark ? 'rgba(255,255,255,0.55)' : 'rgba(0,0,0,0.55)' }};
            --border: {{ $isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)' }};
        }
        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; background: var(--bg); color: var(--text); }

        /* ── Top Admin Bar ── */
        .preview-bar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 9999;
            background: #18181b; border-bottom: 1px solid #333;
            padding: 0.6rem 1.5rem; display: flex; align-items: center; justify-content: space-between;
        }
        .preview-bar-left { display: flex; align-items: center; gap: 1rem; }
        .preview-bar-badge {
            background: var(--accent); color: #fff; font-size: 0.65rem; font-weight: 700;
            padding: 0.2rem 0.6rem; border-radius: 20px; letter-spacing: 0.08em; text-transform: uppercase;
        }
        .preview-bar-title { color: #fff; font-size: 0.85rem; font-weight: 600; }
        .preview-bar-sub { color: #71717a; font-size: 0.75rem; margin-left: 0.5rem; }
        .preview-bar-close {
            background: #27272a; color: #a1a1aa; border: 1px solid #333; padding: 0.4rem 1rem;
            border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer; text-decoration: none;
            display: flex; align-items: center; gap: 0.4rem;
        }
        .preview-bar-close:hover { background: #3f3f46; color: #fff; }

        /* ── Site Wrapper ── */
        .site-wrap { padding-top: 48px; }

        /* ── Navbar ── */
        .site-nav {
            background: var(--bg); border-bottom: 1px solid var(--border);
            padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 48px; z-index: 100;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .site-nav-logo { font-size: 1.2rem; font-weight: 800; color: var(--accent); letter-spacing: -0.02em; }
        .site-nav-links { display: flex; gap: 2rem; }
        .site-nav-links a { font-size: 0.85rem; color: var(--muted); text-decoration: none; font-weight: 500; transition: color 0.2s; }
        .site-nav-links a:hover { color: var(--text); }
        .site-nav-cta {
            background: var(--accent); color: #fff; padding: 0.5rem 1.25rem; border-radius: 8px;
            font-size: 0.85rem; font-weight: 700; text-decoration: none; transition: opacity 0.2s;
        }
        .site-nav-cta:hover { opacity: 0.85; }

        /* ── Hero ── */
        .site-hero {
            padding: 6rem 2rem; text-align: center; max-width: 800px; margin: 0 auto;
        }
        .site-hero-badge {
            display: inline-block; background: {{ $isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)' }};
            color: var(--accent); font-size: 0.72rem; font-weight: 700; padding: 0.3rem 0.9rem;
            border-radius: 20px; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 1.25rem;
            border: 1px solid {{ $isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.08)' }};
        }
        .site-hero h1 { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 900; line-height: 1.1; margin-bottom: 1rem; color: var(--text); letter-spacing: -0.03em; }
        .site-hero h1 span { color: var(--accent); }
        .site-hero p { font-size: 1.1rem; color: var(--muted); line-height: 1.7; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto; }
        .site-hero-btns { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .btn-primary {
            background: var(--accent); color: #fff; padding: 0.85rem 2rem; border-radius: 10px;
            font-size: 0.95rem; font-weight: 700; text-decoration: none; transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .btn-primary:hover { opacity: 0.85; transform: translateY(-1px); }
        .btn-outline {
            background: transparent; color: var(--text); padding: 0.85rem 2rem; border-radius: 10px;
            font-size: 0.95rem; font-weight: 700; text-decoration: none; transition: all 0.2s;
            border: 2px solid var(--border); display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }

        /* ── Stats ── */
        .site-stats {
            display: flex; justify-content: center; gap: 3rem; padding: 2rem;
            border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);
            background: {{ $isDark ? 'rgba(255,255,255,0.02)' : 'rgba(0,0,0,0.02)' }};
        }
        .site-stat-item { text-align: center; }
        .site-stat-value { font-size: 2rem; font-weight: 900; color: var(--accent); }
        .site-stat-label { font-size: 0.75rem; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; margin-top: 0.25rem; }

        /* ── Section ── */
        .site-section { padding: 5rem 2rem; max-width: 1100px; margin: 0 auto; }
        .site-section-header { text-align: center; margin-bottom: 3rem; }
        .site-section-badge {
            display: inline-block; color: var(--accent); font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.75rem;
        }
        .site-section-title { font-size: clamp(1.5rem, 3vw, 2.25rem); font-weight: 800; color: var(--text); margin-bottom: 0.75rem; }
        .site-section-sub { font-size: 1rem; color: var(--muted); max-width: 550px; margin: 0 auto; line-height: 1.7; }

        /* ── Cards Grid ── */
        .site-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; }
        .site-card {
            background: var(--card); border: 1px solid var(--border); border-radius: 16px;
            padding: 1.75rem; transition: all 0.25s;
        }
        .site-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,0.15); }
        .site-card-icon {
            width: 48px; height: 48px; border-radius: 12px; background: var(--accent);
            display: flex; align-items: center; justify-content: center; color: #fff;
            font-size: 1.1rem; margin-bottom: 1rem; opacity: 0.9;
        }
        .site-card h3 { font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.5rem; }
        .site-card p { font-size: 0.85rem; color: var(--muted); line-height: 1.6; }

        /* ── About / Two-col ── */
        .site-two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; }
        .site-two-col-img {
            background: {{ $isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' }};
            border: 1px solid var(--border); border-radius: 20px; height: 320px;
            display: flex; align-items: center; justify-content: center; font-size: 4rem; color: var(--accent);
        }
        .site-two-col-content h2 { font-size: 2rem; font-weight: 800; color: var(--text); margin-bottom: 1rem; }
        .site-two-col-content p { font-size: 0.95rem; color: var(--muted); line-height: 1.8; margin-bottom: 1.5rem; }
        .site-feature-list { list-style: none; display: flex; flex-direction: column; gap: 0.6rem; }
        .site-feature-list li { display: flex; align-items: center; gap: 0.6rem; font-size: 0.9rem; color: var(--text); }
        .site-feature-list li::before { content: '✓'; color: var(--accent); font-weight: 700; }

        /* ── Testimonials ── */
        .site-testimonials { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }
        .site-testimonial {
            background: var(--card); border: 1px solid var(--border); border-radius: 16px;
            padding: 1.75rem; position: relative;
        }
        .site-testimonial-quote { font-size: 0.9rem; color: var(--text); line-height: 1.7; margin-bottom: 1.25rem; font-style: italic; }
        .site-testimonial-author { display: flex; align-items: center; gap: 0.75rem; }
        .site-testimonial-avatar {
            width: 40px; height: 40px; border-radius: 50%; background: var(--accent);
            display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 0.9rem;
        }
        .site-testimonial-name { font-size: 0.85rem; font-weight: 700; color: var(--text); }
        .site-testimonial-role { font-size: 0.75rem; color: var(--muted); }
        .site-stars { color: #f59e0b; font-size: 0.8rem; margin-bottom: 0.75rem; }

        /* ── CTA Banner ── */
        .site-cta {
            background: var(--accent); padding: 5rem 2rem; text-align: center;
        }
        .site-cta h2 { font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: 0.75rem; }
        .site-cta p { font-size: 1rem; color: rgba(255,255,255,0.8); margin-bottom: 2rem; }
        .btn-white {
            background: #fff; color: var(--accent); padding: 0.85rem 2rem; border-radius: 10px;
            font-size: 0.95rem; font-weight: 700; text-decoration: none; display: inline-flex;
            align-items: center; gap: 0.5rem; transition: all 0.2s;
        }
        .btn-white:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.2); }

        /* ── Footer ── */
        .site-footer {
            background: {{ $isDark ? 'rgba(255,255,255,0.03)' : 'rgba(0,0,0,0.03)' }};
            border-top: 1px solid var(--border); padding: 3rem 2rem;
        }
        .site-footer-inner { max-width: 1100px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .site-footer-logo { font-size: 1.1rem; font-weight: 800; color: var(--accent); }
        .site-footer-links { display: flex; gap: 1.5rem; }
        .site-footer-links a { font-size: 0.8rem; color: var(--muted); text-decoration: none; }
        .site-footer-copy { font-size: 0.75rem; color: var(--muted); }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .site-nav-links { display: none; }
            .site-two-col { grid-template-columns: 1fr; }
            .site-stats { gap: 1.5rem; flex-wrap: wrap; }
        }
    </style>
</head>
<body>

{{-- ── Admin Preview Bar ── --}}
<div class="preview-bar">
    <div class="preview-bar-left">
        <span class="preview-bar-badge">Preview Mode</span>
        <span class="preview-bar-title">{{ $theme->name }}</span>
        <span class="preview-bar-sub">{{ $theme->category }}</span>
    </div>
    <a href="{{ route('superadmin.themes.index') }}" class="preview-bar-close">
        <i class="fas fa-times"></i> Close Preview
    </a>
</div>

<div class="site-wrap">

    {{-- ── Navigation ── --}}
    <nav class="site-nav">
        <div class="site-nav-logo">✦ {{ $theme->name }}</div>
        <div class="site-nav-links">
            @foreach($sections as $section)
                <a href="#">{{ $section }}</a>
            @endforeach
        </div>
        <a href="#" class="site-nav-cta">Get Started</a>
    </nav>

    {{-- ── Hero Section ── --}}
    <section style="background:{{ $bg }};">
        <div class="site-hero">
            <div class="site-hero-badge">{{ $theme->category }}</div>
            <h1>{{ $theme->hero_title ?? 'Your Professional Website' }} <span>Starts Here</span></h1>
            <p>{{ $theme->description ?? 'A beautifully crafted, responsive website template designed for your profession. Fully customisable through the Xenoraa Site Builder.' }}</p>
            <div class="site-hero-btns">
                <a href="#" class="btn-primary"><i class="fas fa-bolt"></i> Get Started Free</a>
                <a href="#" class="btn-outline"><i class="fas fa-play"></i> Watch Demo</a>
            </div>
        </div>
    </section>

    {{-- ── Stats ── --}}
    <div class="site-stats">
        <div class="site-stat-item">
            <div class="site-stat-value">500+</div>
            <div class="site-stat-label">Happy Clients</div>
        </div>
        <div class="site-stat-item">
            <div class="site-stat-value">12+</div>
            <div class="site-stat-label">Years Experience</div>
        </div>
        <div class="site-stat-item">
            <div class="site-stat-value">98%</div>
            <div class="site-stat-label">Satisfaction Rate</div>
        </div>
        <div class="site-stat-item">
            <div class="site-stat-value">24/7</div>
            <div class="site-stat-label">Support</div>
        </div>
    </div>

    {{-- ── Services / Features Section ── --}}
    <section style="background:{{ $isDark ? 'rgba(255,255,255,0.02)' : 'rgba(0,0,0,0.02)' }};">
        <div class="site-section">
            <div class="site-section-header">
                <div class="site-section-badge">What We Offer</div>
                <div class="site-section-title">{{ $sections[1] ?? 'Our Services' }}</div>
                <div class="site-section-sub">Professionally crafted sections tailored for your industry, designed to convert visitors into clients.</div>
            </div>
            <div class="site-cards">
                @php
                    $icons = ['fas fa-star', 'fas fa-chart-line', 'fas fa-shield-alt', 'fas fa-users', 'fas fa-rocket', 'fas fa-award'];
                    $descs = [
                        'Expert solutions tailored to your specific needs and goals.',
                        'Data-driven strategies that deliver measurable results.',
                        'Trusted by hundreds of clients across the industry.',
                        'Collaborative approach with dedicated support.',
                        'Innovative methods to accelerate your growth.',
                        'Award-winning service with a proven track record.',
                    ];
                @endphp
                @foreach(array_slice($sections, 0, 6) as $i => $section)
                <div class="site-card">
                    <div class="site-card-icon"><i class="{{ $icons[$i % count($icons)] }}"></i></div>
                    <h3>{{ $section }}</h3>
                    <p>{{ $descs[$i % count($descs)] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── About Section ── --}}
    <section style="background:{{ $bg }};">
        <div class="site-section">
            <div class="site-two-col">
                <div class="site-two-col-img">
                    <i class="fas fa-building" style="font-size:5rem;opacity:0.3;"></i>
                </div>
                <div class="site-two-col-content">
                    <div class="site-section-badge">About Us</div>
                    <h2>Why Choose the <span style="color:var(--accent);">{{ $theme->name }}</span> Template?</h2>
                    <p>This template is purpose-built for {{ $theme->best_for ?? 'professionals' }}. Every section, colour, and layout has been crafted to make your online presence stand out and convert visitors into clients.</p>
                    <ul class="site-feature-list">
                        <li>Fully responsive on all devices</li>
                        <li>Customisable via the Xenoraa Site Builder</li>
                        <li>SEO-optimised structure</li>
                        <li>Integrated with all Xenoraa modules</li>
                        <li>Blog, Forum, Shop, and Jobs pages included</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Testimonials ── --}}
    <section style="background:{{ $isDark ? 'rgba(255,255,255,0.02)' : 'rgba(0,0,0,0.02)' }};">
        <div class="site-section">
            <div class="site-section-header">
                <div class="site-section-badge">Testimonials</div>
                <div class="site-section-title">What Our Clients Say</div>
                <div class="site-section-sub">Real feedback from professionals who use this template to grow their business.</div>
            </div>
            <div class="site-testimonials">
                @php
                    $testimonials = [
                        ['quote' => 'The design is absolutely stunning. My clients always compliment how professional my website looks.', 'name' => 'Priya Sharma', 'role' => 'Business Owner', 'initial' => 'P'],
                        ['quote' => 'Setting up my online presence was incredibly easy. The template handled everything beautifully.', 'name' => 'Rahul Verma', 'role' => 'Consultant', 'initial' => 'R'],
                        ['quote' => 'I love how the theme perfectly matches my profession. It feels tailor-made for my industry.', 'name' => 'Anita Patel', 'role' => 'Professional', 'initial' => 'A'],
                    ];
                @endphp
                @foreach($testimonials as $t)
                <div class="site-testimonial">
                    <div class="site-stars">★★★★★</div>
                    <div class="site-testimonial-quote">"{{ $t['quote'] }}"</div>
                    <div class="site-testimonial-author">
                        <div class="site-testimonial-avatar">{{ $t['initial'] }}</div>
                        <div>
                            <div class="site-testimonial-name">{{ $t['name'] }}</div>
                            <div class="site-testimonial-role">{{ $t['role'] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── CTA Banner ── --}}
    <div class="site-cta">
        <h2>Ready to Build Your Professional Website?</h2>
        <p>Activate the {{ $theme->name }} theme in your Xenoraa dashboard and go live in minutes.</p>
        <a href="{{ route('superadmin.themes.index') }}" class="btn-white">
            <i class="fas fa-arrow-left"></i> Back to Theme Store
        </a>
    </div>

    {{-- ── Footer ── --}}
    <footer class="site-footer">
        <div class="site-footer-inner">
            <div class="site-footer-logo">✦ {{ $theme->name }}</div>
            <div class="site-footer-links">
                @foreach($sections as $section)
                    <a href="#">{{ $section }}</a>
                @endforeach
            </div>
            <div class="site-footer-copy">© {{ date('Y') }} · Powered by Xenoraa · {{ $theme->name }} Theme</div>
        </div>
    </footer>

</div>
</body>
</html>
