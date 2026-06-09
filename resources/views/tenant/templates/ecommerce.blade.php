@extends('layouts.app')
@section('title', ($profile['name'] ?? $tenant->name) . ' — Premium Furniture & Office Solutions')
@section('description', $profile['about'] ?? 'Jose Industries — Premium furniture manufacturer since 1986. Center tables, locker cupboards, office tables and more. Shop now with fast delivery across India.')
@section('content')
@php
    $accent = $accentColor ?? '#22c55e';
    $homePage = $homePage ?? null;
    $_show = function(string $key) use ($homePage): bool {
        if (!$homePage) return true;
        return $homePage->isSectionEnabled($key);
    };
    $heroData         = $homePage ? $homePage->getSectionData('hero')         : [];
    $statsData        = $homePage ? $homePage->getSectionData('stats')        : [];
    $aboutData        = $homePage ? $homePage->getSectionData('about')        : [];
    $servicesData     = $homePage ? $homePage->getSectionData('services')     : [];
    $testimonialsData = $homePage ? $homePage->getSectionData('testimonials') : [];
    $contactData      = $homePage ? $homePage->getSectionData('contact')      : [];

    $heroHeading    = $heroData['heading']    ?? $profile['name']    ?? $tenant->name;
    $heroSubheading = $heroData['subheading'] ?? $profile['tagline'] ?? 'Premium Furniture Manufacturer Since 1986';
    $heroCta        = $heroData['cta_text']   ?? 'Shop Now';
    $heroCtaUrl     = $heroData['cta_url']    ?? ($tenantShopUrl ?? '/shop');

    $statsItems = $statsData['items'] ?? $profile['stats'] ?? [
        ['icon' => '🏭', 'value' => '1986',   'label' => 'Est. Year'],
        ['icon' => '📦', 'value' => '500+',   'label' => 'Products'],
        ['icon' => '😊', 'value' => '10,000+','label' => 'Happy Clients'],
        ['icon' => '🏆', 'value' => '35+',    'label' => 'Years Experience'],
    ];

    $aboutText = $aboutData['text'] ?? $profile['about'] ?? 'Jose Industries is a Chennai-based furniture manufacturer established in 1986. We specialize in premium center tables, locker cupboards, office tables, steel racks, and a wide range of commercial and residential furniture. With over 35 years of manufacturing excellence, ISO 9001:2015 certification, and a state-of-the-art production facility, we deliver quality furniture that stands the test of time.';

    $categories = $servicesData['items'] ?? $profile['services'] ?? [
        ['icon' => '🪑', 'title' => 'Center Tables',     'text' => 'Elegant center tables for living rooms and offices — multiple finishes available'],
        ['icon' => '🗄️', 'title' => 'Locker Cupboards',  'text' => 'Heavy-duty steel locker cupboards for offices, schools, and industrial use'],
        ['icon' => '🖥️', 'title' => 'Office Tables',     'text' => 'Ergonomic office tables and workstations for modern workplaces'],
        ['icon' => '📚', 'title' => 'Steel Racks',       'text' => 'Industrial-grade steel racks and shelving for warehouses and offices'],
        ['icon' => '🗂️', 'title' => 'Filing Cabinets',   'text' => 'Secure filing cabinets and document storage solutions'],
        ['icon' => '🛋️', 'title' => 'Wooden Furniture',  'text' => 'Premium wooden furniture crafted from quality timber'],
    ];

    $testimonials = $testimonialsData['items'] ?? $profile['testimonials'] ?? [
        ['name' => 'Rajesh Kumar',   'role' => 'Office Manager, Chennai',    'text' => 'Excellent quality office furniture. The tables are sturdy and the finish is outstanding. Jose Industries delivered on time and the installation team was very professional.'],
        ['name' => 'Priya Nair',     'role' => 'Interior Designer, Bangalore', 'text' => 'I have been sourcing furniture from Jose Industries for 5 years. Their center tables are simply beautiful and the quality is unmatched at this price point.'],
        ['name' => 'Suresh Menon',   'role' => 'School Principal, Coimbatore', 'text' => 'We ordered 50 locker cupboards for our school. The quality is superb, delivery was on time, and the after-sales service is excellent. Highly recommended!'],
    ];

    $contactHeading = $contactData['heading']     ?? 'Get In Touch';
    $contactText    = $contactData['text']        ?? 'Have questions about our products, bulk orders, or custom requirements? Our team is here to help Monday to Saturday, 9 AM to 7 PM.';
    $contactBtn     = $contactData['button_text'] ?? 'Contact Us';
    $contactBtnUrl  = $contactData['button_url']  ?? ($tenantContactUrl ?? '/contact');

    $tenantBase = isset($tenant) && $tenant->custom_domain ? '' : ('/' . ($tenant->username ?? ''));
    $shopUrl    = $tenantBase . '/shop';
    $blogUrl    = $tenantBase . '/blog';

    // Determine if light theme
    // Read color settings from allSettings
    $_settings = $allSettings ?? [];
    $colorBg = $_settings['color_bg'] ?? '#0a0a0a';
    $colorText = $_settings['color_text'] ?? '#ffffff';
    $colorPrimary = $_settings['color_primary'] ?? ($accentColor ?? '#22c55e');
    $isLight = (function($hex) {
        $rgb = sscanf($hex, '#%02x%02x%02x');
        return $rgb && (0.299*$rgb[0]+0.587*$rgb[1]+0.114*$rgb[2])/255 > 0.5;
    })($colorBg);

    // Theme-aware colors
    $ecBg1       = $isLight ? $colorBg       : '#0f0f0f';
    $ecBg2       = $isLight ? '#ffffff'                      : '#1a1a1a';
    $ecBg3       = $isLight ? $colorBg        : '#0d0d0d';
    $ecBg4       = $isLight ? '#e8f5e9'                      : '#111111';
    $ecBg5       = $isLight ? '#f9fafb'                      : '#0a0a0a';
    $ecText1     = $isLight ? $colorText      : '#ffffff';
    $ecText2     = $isLight ? $colorText : '#e2e8f0';
    $ecText3     = $isLight ? '#6b7280'                      : '#94a3b8';
    $ecText4     = $isLight ? '#9ca3af'                      : '#64748b';
    $ecBorder    = $isLight ? 'rgba(22,163,74,0.15)'         : 'rgba(255,255,255,0.07)';
    $ecBorderMid = $isLight ? 'rgba(22,163,74,0.25)'         : 'rgba(255,255,255,0.12)';
    $ecCardBg    = $isLight ? '#ffffff'                      : '#1a1a1a';
    $ecInputBg   = $isLight ? '#f9fafb'                      : '#111111';
    $ecInputBorder = $isLight ? 'rgba(22,163,74,0.3)'        : 'rgba(255,255,255,0.08)';
    $ecInputColor  = $isLight ? '#1a1a1a'                    : '#ffffff';
    $ecShadow    = $isLight ? 'rgba(22,163,74,0.12)'         : 'rgba(0,0,0,0.4)';
@endphp

<style>
:root {
    --ec-accent: {{ $accent }};
    --ec-accent-dark: color-mix(in srgb, var(--ec-accent) 80%, #000);
    --ec-bg1: {{ $ecBg1 }};
    --ec-bg2: {{ $ecBg2 }};
    --ec-bg3: {{ $ecBg3 }};
    --ec-bg4: {{ $ecBg4 }};
    --ec-bg5: {{ $ecBg5 }};
    --ec-text1: {{ $ecText1 }};
    --ec-text2: {{ $ecText2 }};
    --ec-text3: {{ $ecText3 }};
    --ec-text4: {{ $ecText4 }};
    --ec-border: {{ $ecBorder }};
    --ec-border-mid: {{ $ecBorderMid }};
    --ec-card: {{ $ecCardBg }};
    --ec-input-bg: {{ $ecInputBg }};
    --ec-input-border: {{ $ecInputBorder }};
    --ec-input-color: {{ $ecInputColor }};
    --ec-shadow: {{ $ecShadow }};
}

.xn-ec-wrap { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }

/* ── ANNOUNCEMENT BAR ── */
.ec-announce {
    background: var(--ec-accent);
    color: #fff;
    text-align: center;
    padding: 0.55rem 1rem;
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.03em;
}
.ec-announce a { color: #fff; text-decoration: underline; }

/* ── HERO SLIDER ── */
.ec-hero-slider {
    position: relative;
    overflow: hidden;
    background: var(--ec-bg1);
    min-height: 520px;
}
.ec-slide {
    display: none;
    position: relative;
    padding: 5rem 0 4rem;
    min-height: 520px;
    align-items: center;
}
.ec-slide.active { display: flex; }
.ec-slide-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: {{ $isLight ? '0.12' : '0.25' }};
    z-index: 0;
}
.ec-slide-overlay {
    position: absolute;
    inset: 0;
    background: {{ $isLight
        ? 'linear-gradient(135deg, rgba(240,253,244,0.92) 0%, rgba(240,253,244,0.85) 100%)'
        : 'linear-gradient(135deg, rgba(10,10,10,0.92) 0%, rgba(15,15,15,0.85) 100%)' }};
    z-index: 1;
}
.ec-slide-inner {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
}
@media(max-width:768px){
    .ec-slide-inner { grid-template-columns: 1fr; text-align: center; gap: 2rem; }
    .ec-slide-visual { display: none; }
}
.ec-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: color-mix(in srgb, var(--ec-accent) 15%, transparent);
    color: var(--ec-accent);
    font-size: 0.72rem;
    font-weight: 800;
    padding: 0.35rem 1rem;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    border: 1px solid color-mix(in srgb, var(--ec-accent) 30%, transparent);
    margin-bottom: 1.25rem;
}
.ec-hero-h1 {
    font-size: clamp(2rem, 4vw, 3.5rem);
    font-weight: 900;
    color: var(--ec-text1);
    line-height: 1.1;
    margin-bottom: 1rem;
}
.ec-hero-h1 span { color: var(--ec-accent); }
.ec-hero-sub {
    font-size: 1rem;
    color: var(--ec-text3);
    line-height: 1.8;
    margin-bottom: 2rem;
    max-width: 480px;
}
.ec-hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; }
@media(max-width:768px){ .ec-hero-actions { justify-content: center; } }
.ec-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.9rem 2rem;
    border-radius: 10px;
    font-size: 0.9rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.25s;
    cursor: pointer;
    border: none;
}
.ec-btn-primary {
    background: var(--ec-accent);
    color: #fff;
    box-shadow: 0 8px 24px color-mix(in srgb, var(--ec-accent) 35%, transparent);
}
.ec-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px color-mix(in srgb, var(--ec-accent) 50%, transparent);
    color: #fff;
}
.ec-btn-outline {
    background: transparent;
    color: var(--ec-text1);
    border: 1.5px solid var(--ec-border-mid);
}
.ec-btn-outline:hover { background: color-mix(in srgb, var(--ec-accent) 8%, transparent); color: var(--ec-text1); }

/* Slide visual — product image cards */
.ec-slide-visual {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.ec-hero-card {
    background: var(--ec-card);
    border: 1px solid var(--ec-border);
    border-radius: 16px;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 4px 16px var(--ec-shadow);
}
.ec-hero-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px var(--ec-shadow); }
.ec-hero-card.no-img-card .ec-hero-card-img { display:flex!important; align-items:center; justify-content:center; font-size:2.5rem; }
.ec-hero-card.no-img-card::before { content:"🪑"; font-size:2.5rem; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); }
.ec-hero-card-img {
    width: 100%;
    aspect-ratio: 1;
    object-fit: cover;
    display: block;
    background: var(--ec-bg4);
}
.ec-hero-card-body { padding: 0.75rem; }
.ec-hero-card-badge {
    display: inline-block;
    background: var(--ec-accent);
    color: #fff;
    font-size: 0.6rem;
    font-weight: 700;
    padding: 0.15rem 0.5rem;
    border-radius: 4px;
    margin-bottom: 0.35rem;
    text-transform: uppercase;
}
.ec-hero-card-name { font-size: 0.78rem; font-weight: 700; color: var(--ec-text1); margin-bottom: 0.2rem; }
.ec-hero-card-price { font-size: 0.75rem; color: var(--ec-accent); font-weight: 600; }

/* Slider controls */
.ec-slider-dots {
    position: absolute;
    bottom: 1.5rem;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 0.5rem;
    z-index: 10;
}
.ec-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--ec-border-mid);
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    padding: 0;
}
.ec-dot.active { background: var(--ec-accent); width: 24px; border-radius: 4px; }
.ec-slider-prev, .ec-slider-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    background: var(--ec-card);
    border: 1px solid var(--ec-border-mid);
    color: var(--ec-text1);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.9rem;
}
.ec-slider-prev { left: 1rem; }
.ec-slider-next { right: 1rem; }
.ec-slider-prev:hover, .ec-slider-next:hover { background: var(--ec-accent); color: #fff; border-color: var(--ec-accent); }

/* ── TRUST BAR ── */
.ec-trust {
    background: var(--ec-bg4);
    border-top: 1px solid var(--ec-border);
    border-bottom: 1px solid var(--ec-border);
    padding: 1.5rem 0;
}
.ec-trust-row { display: flex; align-items: center; justify-content: center; gap: 3rem; flex-wrap: wrap; }
.ec-trust-item { display: flex; align-items: center; gap: 0.75rem; }
.ec-trust-icon {
    width: 40px;
    height: 40px;
    background: color-mix(in srgb, var(--ec-accent) 12%, transparent);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    border: 1px solid color-mix(in srgb, var(--ec-accent) 20%, transparent);
}
.ec-trust-text { font-size: 0.82rem; font-weight: 700; color: var(--ec-text1); }
.ec-trust-sub { font-size: 0.72rem; color: var(--ec-text4); }

/* ── STATS ── */
.ec-stats { padding: 4rem 0; background: var(--ec-bg5); }
.ec-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}
@media(max-width:640px){ .ec-stats-grid { grid-template-columns: repeat(2, 1fr); } }
.ec-stat-cell {
    background: var(--ec-card);
    border: 1px solid var(--ec-border);
    border-radius: 16px;
    padding: 2rem 1.5rem;
    text-align: center;
    transition: all 0.2s;
    box-shadow: 0 2px 8px var(--ec-shadow);
}
.ec-stat-cell:hover {
    border-color: var(--ec-accent);
    transform: translateY(-4px);
    box-shadow: 0 8px 24px var(--ec-shadow);
}
.ec-stat-icon { font-size: 2rem; margin-bottom: 0.75rem; }
.ec-stat-num { font-size: 2.2rem; font-weight: 900; color: var(--ec-accent); line-height: 1; }
.ec-stat-label { font-size: 0.75rem; color: var(--ec-text4); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.4rem; font-weight: 600; }

/* ── SECTION COMMON ── */
.ec-section { padding: 5rem 0; }
.ec-section-header { text-align: center; margin-bottom: 3.5rem; }
.ec-section-badge {
    display: inline-block;
    background: color-mix(in srgb, var(--ec-accent) 12%, transparent);
    color: var(--ec-accent);
    font-size: 0.72rem;
    font-weight: 800;
    padding: 0.3rem 0.9rem;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    border: 1px solid color-mix(in srgb, var(--ec-accent) 22%, transparent);
    margin-bottom: 0.75rem;
}
.ec-section-title { font-size: clamp(1.6rem, 3vw, 2.4rem); font-weight: 900; color: var(--ec-text1); margin-bottom: 0.75rem; line-height: 1.2; }
.ec-section-sub { font-size: 1rem; color: var(--ec-text4); max-width: 560px; margin: 0 auto; line-height: 1.7; }

/* ── FEATURED PRODUCTS ── */
.ec-featured { background: var(--ec-bg3); }
.ec-products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
}
@media(max-width:900px){ .ec-products-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width:480px){ .ec-products-grid { grid-template-columns: 1fr; } }
.ec-prod-card {
    background: var(--ec-card);
    border: 1px solid var(--ec-border);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.25s;
    box-shadow: 0 2px 8px var(--ec-shadow);
    text-decoration: none;
    display: block;
}
.ec-prod-card:hover {
    border-color: var(--ec-accent);
    transform: translateY(-4px);
    box-shadow: 0 12px 32px var(--ec-shadow);
}
.ec-prod-img-wrap {
    position: relative;
    aspect-ratio: 4/3;
    overflow: hidden;
    background: var(--ec-bg4);
}
.ec-prod-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s;
}
.ec-prod-card:hover .ec-prod-img-wrap img { transform: scale(1.06); }
.ec-prod-badge {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    background: var(--ec-accent);
    color: #fff;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 0.2rem 0.6rem;
    border-radius: 6px;
    text-transform: uppercase;
}
.ec-prod-body { padding: 1rem; }
.ec-prod-cat { font-size: 0.7rem; color: var(--ec-accent); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.3rem; }
.ec-prod-name { font-size: 0.9rem; font-weight: 700; color: var(--ec-text1); margin-bottom: 0.5rem; line-height: 1.3; }
.ec-prod-price { font-size: 1rem; font-weight: 800; color: var(--ec-accent); }
.ec-prod-orig { font-size: 0.8rem; color: var(--ec-text4); text-decoration: line-through; margin-left: 0.4rem; }

/* ── CATEGORIES ── */
.ec-cats { background: var(--ec-bg1); }
.ec-cats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
}
@media(max-width:768px){ .ec-cats-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width:480px){ .ec-cats-grid { grid-template-columns: 1fr; } }
.ec-cat-card {
    background: var(--ec-card);
    border: 1px solid var(--ec-border);
    border-radius: 16px;
    padding: 2rem 1.5rem;
    text-align: center;
    text-decoration: none;
    transition: all 0.25s;
    display: block;
    box-shadow: 0 2px 8px var(--ec-shadow);
}
.ec-cat-card:hover {
    border-color: var(--ec-accent);
    transform: translateY(-4px);
    box-shadow: 0 12px 32px color-mix(in srgb, var(--ec-accent) 15%, transparent);
}
.ec-cat-icon { font-size: 2.5rem; margin-bottom: 1rem; }
.ec-cat-name { font-size: 1rem; font-weight: 700; color: var(--ec-text1); margin-bottom: 0.5rem; }
.ec-cat-desc { font-size: 0.82rem; color: var(--ec-text4); line-height: 1.5; }
.ec-cat-arrow { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.8rem; color: var(--ec-accent); font-weight: 600; margin-top: 0.75rem; }

/* ── WHY CHOOSE US ── */
.ec-why { background: var(--ec-bg5); }
.ec-why-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}
@media(max-width:768px){ .ec-why-grid { grid-template-columns: 1fr; } }
.ec-why-card {
    background: var(--ec-card);
    border: 1px solid var(--ec-border);
    border-radius: 16px;
    padding: 2rem;
    transition: all 0.2s;
    box-shadow: 0 2px 8px var(--ec-shadow);
}
.ec-why-card:hover { border-color: var(--ec-accent); transform: translateY(-3px); }
.ec-why-icon {
    width: 52px;
    height: 52px;
    background: color-mix(in srgb, var(--ec-accent) 12%, transparent);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1.25rem;
    border: 1px solid color-mix(in srgb, var(--ec-accent) 20%, transparent);
}
.ec-why-title { font-size: 1rem; font-weight: 700; color: var(--ec-text1); margin-bottom: 0.5rem; }
.ec-why-text { font-size: 0.875rem; color: var(--ec-text3); line-height: 1.7; }

/* ── ABOUT ── */
.ec-about { background: var(--ec-bg3); }
.ec-about-inner {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 5rem;
    align-items: center;
}
@media(max-width:768px){ .ec-about-inner { grid-template-columns: 1fr; gap: 2rem; } }
.ec-about-visual {
    background: var(--ec-card);
    border: 1px solid var(--ec-border);
    border-radius: 24px;
    padding: 2.5rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    box-shadow: 0 4px 24px var(--ec-shadow);
}
.ec-about-metric {
    background: color-mix(in srgb, var(--ec-accent) 6%, transparent);
    border: 1px solid color-mix(in srgb, var(--ec-accent) 15%, transparent);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
}
.ec-about-metric-val { font-size: 1.75rem; font-weight: 900; color: var(--ec-accent); }
.ec-about-metric-label { font-size: 0.75rem; color: var(--ec-text4); margin-top: 0.25rem; font-weight: 600; }
.ec-about-badge {
    display: inline-block;
    background: color-mix(in srgb, var(--ec-accent) 12%, transparent);
    color: var(--ec-accent);
    font-size: 0.72rem;
    font-weight: 800;
    padding: 0.3rem 0.9rem;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    border: 1px solid color-mix(in srgb, var(--ec-accent) 22%, transparent);
    margin-bottom: 1rem;
}
.ec-about-h2 { font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 900; color: var(--ec-text1); margin-bottom: 1rem; line-height: 1.2; }
.ec-about-text { font-size: 1rem; color: var(--ec-text3); line-height: 1.8; margin-bottom: 1.5rem; }
.ec-about-features { list-style: none; padding: 0; margin: 0 0 2rem; display: flex; flex-direction: column; gap: 0.75rem; }
.ec-about-features li { display: flex; align-items: center; gap: 0.75rem; font-size: 0.9rem; color: var(--ec-text2); }
.ec-about-features li::before {
    content: '✓';
    width: 22px;
    height: 22px;
    background: color-mix(in srgb, var(--ec-accent) 15%, transparent);
    color: var(--ec-accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 800;
    flex-shrink: 0;
}

/* ── TESTIMONIALS ── */
.ec-testimonials { background: var(--ec-bg4); }
.ec-test-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
@media(max-width:768px){ .ec-test-grid { grid-template-columns: 1fr; } }
.ec-test-card {
    background: var(--ec-card);
    border: 1px solid var(--ec-border);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 2px 8px var(--ec-shadow);
}
.ec-test-stars { color: #f59e0b; font-size: 0.9rem; margin-bottom: 1rem; letter-spacing: 0.1em; }
.ec-test-text { font-size: 0.9rem; color: var(--ec-text3); line-height: 1.7; margin-bottom: 1.5rem; font-style: italic; }
.ec-test-author { display: flex; align-items: center; gap: 0.75rem; }
.ec-test-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: color-mix(in srgb, var(--ec-accent) 15%, transparent);
    border: 2px solid color-mix(in srgb, var(--ec-accent) 30%, transparent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    font-weight: 800;
    color: var(--ec-accent);
    flex-shrink: 0;
}
.ec-test-name { font-size: 0.9rem; font-weight: 700; color: var(--ec-text1); }
.ec-test-role { font-size: 0.75rem; color: var(--ec-text4); }

/* ── NEWSLETTER ── */
.ec-newsletter {
    background: linear-gradient(135deg, color-mix(in srgb, var(--ec-accent) 10%, var(--ec-bg5)) 0%, var(--ec-bg5) 100%);
    border-top: 1px solid color-mix(in srgb, var(--ec-accent) 20%, transparent);
    border-bottom: 1px solid color-mix(in srgb, var(--ec-accent) 20%, transparent);
    padding: 4rem 0;
}
.ec-newsletter-inner { text-align: center; max-width: 560px; margin: 0 auto; }
.ec-newsletter-title { font-size: 1.75rem; font-weight: 900; color: var(--ec-text1); margin-bottom: 0.75rem; }
.ec-newsletter-sub { font-size: 0.95rem; color: var(--ec-text3); margin-bottom: 2rem; }
.ec-newsletter-form { display: flex; gap: 0.75rem; max-width: 420px; margin: 0 auto; }
@media(max-width:480px){ .ec-newsletter-form { flex-direction: column; } }
.ec-newsletter-input {
    flex: 1;
    background: var(--ec-input-bg);
    border: 1px solid var(--ec-input-border);
    color: var(--ec-input-color);
    padding: 0.85rem 1.25rem;
    border-radius: 10px;
    font-size: 0.9rem;
    outline: none;
}
.ec-newsletter-input:focus { border-color: var(--ec-accent); }
.ec-newsletter-btn {
    background: var(--ec-accent);
    color: #fff;
    border: none;
    padding: 0.85rem 1.5rem;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    white-space: nowrap;
}

/* ── CONTACT ── */
.ec-contact { background: var(--ec-bg1); }
.ec-contact-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: start; }
@media(max-width:768px){ .ec-contact-inner { grid-template-columns: 1fr; gap: 2rem; } }
.ec-contact-h2 { font-size: 1.75rem; font-weight: 900; color: var(--ec-text1); margin-bottom: 0.75rem; }
.ec-contact-sub { font-size: 0.95rem; color: var(--ec-text3); line-height: 1.7; margin-bottom: 2rem; }
.ec-contact-items { display: flex; flex-direction: column; gap: 1rem; }
.ec-contact-item { display: flex; align-items: center; gap: 1rem; }
.ec-contact-item-icon {
    width: 44px;
    height: 44px;
    background: color-mix(in srgb, var(--ec-accent) 12%, transparent);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
    border: 1px solid color-mix(in srgb, var(--ec-accent) 20%, transparent);
}
.ec-contact-item-label { font-size: 0.75rem; color: var(--ec-text4); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; }
.ec-contact-item-val { font-size: 0.9rem; color: var(--ec-text2); font-weight: 600; }
.ec-form {
    background: var(--ec-card);
    border: 1px solid var(--ec-border);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 24px var(--ec-shadow);
}
.ec-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
@media(max-width:480px){ .ec-form-row { grid-template-columns: 1fr; } }
.ec-form-group { margin-bottom: 1rem; }
.ec-form-label { display: block; font-size: 0.8rem; font-weight: 600; color: var(--ec-text3); margin-bottom: 0.4rem; }
.ec-form-input {
    width: 100%;
    background: var(--ec-input-bg);
    border: 1px solid var(--ec-input-border);
    color: var(--ec-input-color);
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-size: 0.9rem;
    outline: none;
    box-sizing: border-box;
}
.ec-form-input:focus { border-color: var(--ec-accent); }
.ec-form-textarea { min-height: 120px; resize: vertical; }
</style>

{{-- Announcement Bar --}}
<div class="ec-announce">
    🏭 Manufacturer Direct Pricing &nbsp;|&nbsp; <strong>35+ Years</strong> of Excellence &nbsp;|&nbsp; ISO 9001:2015 Certified &nbsp;
    <a href="{{ $shopUrl }}">Explore Products →</a>
</div>

{{-- HERO SLIDER --}}
@if($_show('hero'))
<div class="ec-hero-slider" id="ecSlider">
    {{-- Slide 1: Center Tables --}}
    <div class="ec-slide active">
        <div class="ec-slide-bg" style="background-image: url('https://5.imimg.com/data5/SELLER/Default/2023/3/295157876/TG/ND/QE/3671/center-table-500x500.jpg');"></div>
        <div class="ec-slide-overlay"></div>
        <div class="ec-slide-inner">
            <div>
                <div class="ec-hero-badge">🪑 Premium Furniture</div>
                <h1 class="ec-hero-h1">
                    {{ $heroHeading }}<br>
                    <span>Premium Center Tables</span>
                </h1>
                <p class="ec-hero-sub">Elegant center tables crafted with precision — available in glass, wood, and steel finishes. Perfect for living rooms and corporate lobbies.</p>
                <div class="ec-hero-actions">
                    <a href="{{ $heroCtaUrl }}" class="ec-btn ec-btn-primary">
                        <i class="fas fa-shopping-bag"></i> {{ $heroCta }}
                    </a>
                    <a href="{{ $tenantBase }}/about" class="ec-btn ec-btn-outline">
                        <i class="fas fa-info-circle"></i> Our Story
                    </a>
                </div>
            </div>
            <div class="ec-slide-visual">
                @php
                    $featuredProducts = \App\Models\Product::where('user_id', $tenant->id)
                        ->where('is_active', 1)
                        ->orderBy('created_at', 'desc')
                        ->take(4)
                        ->get();
                @endphp
                @forelse($featuredProducts as $fp)
                <div class="ec-hero-card">
                    @if($fp->featured_image)
                    <img src="{{ str_starts_with($fp->featured_image, 'http') ? $fp->featured_image : asset('storage/' . $fp->featured_image) }}"
                         alt="{{ $fp->name }}"
                         class="ec-hero-card-img"
                         onerror="this.onerror=null;this.style.display='none';this.parentElement.classList.add('no-img-card')">
                    @else
                    <div class="ec-hero-card-img" style="display:flex;align-items:center;justify-content:center;font-size:2.5rem;">🪑</div>
                    @endif
                    <div class="ec-hero-card-body">
                        <div class="ec-hero-card-badge">New</div>
                        <div class="ec-hero-card-name">{{ Str::limit($fp->name, 28) }}</div>
                        <div class="ec-hero-card-price">₹{{ number_format($fp->effective_price, 0) }}</div>
                    </div>
                </div>
                @empty
                <div class="ec-hero-card">
                    <div class="ec-hero-card-img" style="display:flex;align-items:center;justify-content:center;font-size:3rem;background:color-mix(in srgb, var(--ec-accent) 8%, var(--ec-bg4));">🪑</div>
                    <div class="ec-hero-card-body">
                        <div class="ec-hero-card-badge">Featured</div>
                        <div class="ec-hero-card-name">Center Tables</div>
                        <div class="ec-hero-card-price">From ₹4,500</div>
                    </div>
                </div>
                <div class="ec-hero-card">
                    <div class="ec-hero-card-img" style="display:flex;align-items:center;justify-content:center;font-size:3rem;background:color-mix(in srgb, var(--ec-accent) 8%, var(--ec-bg4));">🗄️</div>
                    <div class="ec-hero-card-body">
                        <div class="ec-hero-card-badge">Hot</div>
                        <div class="ec-hero-card-name">Locker Cupboards</div>
                        <div class="ec-hero-card-price">From ₹6,000</div>
                    </div>
                </div>
                <div class="ec-hero-card">
                    <div class="ec-hero-card-img" style="display:flex;align-items:center;justify-content:center;font-size:3rem;background:color-mix(in srgb, var(--ec-accent) 8%, var(--ec-bg4));">🖥️</div>
                    <div class="ec-hero-card-body">
                        <div class="ec-hero-card-badge">Sale</div>
                        <div class="ec-hero-card-name">Office Tables</div>
                        <div class="ec-hero-card-price">From ₹5,500</div>
                    </div>
                </div>
                <div class="ec-hero-card">
                    <div class="ec-hero-card-img" style="display:flex;align-items:center;justify-content:center;font-size:3rem;background:color-mix(in srgb, var(--ec-accent) 8%, var(--ec-bg4));">📚</div>
                    <div class="ec-hero-card-body">
                        <div class="ec-hero-card-badge">Top</div>
                        <div class="ec-hero-card-name">Steel Racks</div>
                        <div class="ec-hero-card-price">From ₹3,200</div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Slide 2: Locker Cupboards --}}
    <div class="ec-slide">
        <div class="ec-slide-bg" style="background-image: url('https://5.imimg.com/data5/SELLER/Default/2023/3/295157876/TG/ND/QE/3671/locker-cupboard-500x500.jpg');"></div>
        <div class="ec-slide-overlay"></div>
        <div class="ec-slide-inner">
            <div>
                <div class="ec-hero-badge">🗄️ Industrial Grade</div>
                <h1 class="ec-hero-h1">
                    Heavy-Duty<br>
                    <span>Locker Cupboards</span>
                </h1>
                <p class="ec-hero-sub">Secure, durable locker cupboards for offices, schools, hospitals, and industrial facilities. Available in multiple sizes and configurations.</p>
                <div class="ec-hero-actions">
                    <a href="{{ $shopUrl }}" class="ec-btn ec-btn-primary">
                        <i class="fas fa-shopping-bag"></i> View Lockers
                    </a>
                    <a href="{{ $tenantBase }}/contact" class="ec-btn ec-btn-outline">
                        <i class="fas fa-phone"></i> Get Quote
                    </a>
                </div>
            </div>
            <div class="ec-slide-visual">
                <div class="ec-hero-card">
                    <div class="ec-hero-card-img" style="display:flex;align-items:center;justify-content:center;font-size:3.5rem;background:color-mix(in srgb, var(--ec-accent) 8%, var(--ec-bg4));">🗄️</div>
                    <div class="ec-hero-card-body">
                        <div class="ec-hero-card-badge">Best Seller</div>
                        <div class="ec-hero-card-name">6-Door Locker</div>
                        <div class="ec-hero-card-price">₹8,500</div>
                    </div>
                </div>
                <div class="ec-hero-card">
                    <div class="ec-hero-card-img" style="display:flex;align-items:center;justify-content:center;font-size:3.5rem;background:color-mix(in srgb, var(--ec-accent) 8%, var(--ec-bg4));">🔒</div>
                    <div class="ec-hero-card-body">
                        <div class="ec-hero-card-badge">Secure</div>
                        <div class="ec-hero-card-name">12-Door Locker</div>
                        <div class="ec-hero-card-price">₹14,000</div>
                    </div>
                </div>
                <div class="ec-hero-card">
                    <div class="ec-hero-card-img" style="display:flex;align-items:center;justify-content:center;font-size:3.5rem;background:color-mix(in srgb, var(--ec-accent) 8%, var(--ec-bg4));">🏫</div>
                    <div class="ec-hero-card-body">
                        <div class="ec-hero-card-badge">School</div>
                        <div class="ec-hero-card-name">Student Locker</div>
                        <div class="ec-hero-card-price">₹6,200</div>
                    </div>
                </div>
                <div class="ec-hero-card">
                    <div class="ec-hero-card-img" style="display:flex;align-items:center;justify-content:center;font-size:3.5rem;background:color-mix(in srgb, var(--ec-accent) 8%, var(--ec-bg4));">🏭</div>
                    <div class="ec-hero-card-body">
                        <div class="ec-hero-card-badge">Industrial</div>
                        <div class="ec-hero-card-name">Heavy Duty</div>
                        <div class="ec-hero-card-price">₹11,000</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Slide 3: Office Tables --}}
    <div class="ec-slide">
        <div class="ec-slide-bg" style="background-image: url('https://5.imimg.com/data5/SELLER/Default/2023/3/295157876/TG/ND/QE/3671/office-table-500x500.jpg');"></div>
        <div class="ec-slide-overlay"></div>
        <div class="ec-slide-inner">
            <div>
                <div class="ec-hero-badge">🖥️ Workspace Solutions</div>
                <h1 class="ec-hero-h1">
                    Modern Office<br>
                    <span>Tables & Workstations</span>
                </h1>
                <p class="ec-hero-sub">Ergonomic office tables and executive desks designed for productivity. Crafted with premium materials for lasting comfort and style.</p>
                <div class="ec-hero-actions">
                    <a href="{{ $shopUrl }}" class="ec-btn ec-btn-primary">
                        <i class="fas fa-shopping-bag"></i> Shop Office Furniture
                    </a>
                    <a href="{{ $tenantBase }}/about" class="ec-btn ec-btn-outline">
                        <i class="fas fa-building"></i> About Us
                    </a>
                </div>
            </div>
            <div class="ec-slide-visual">
                <div class="ec-hero-card">
                    <div class="ec-hero-card-img" style="display:flex;align-items:center;justify-content:center;font-size:3.5rem;background:color-mix(in srgb, var(--ec-accent) 8%, var(--ec-bg4));">🖥️</div>
                    <div class="ec-hero-card-body">
                        <div class="ec-hero-card-badge">Executive</div>
                        <div class="ec-hero-card-name">Executive Desk</div>
                        <div class="ec-hero-card-price">₹12,500</div>
                    </div>
                </div>
                <div class="ec-hero-card">
                    <div class="ec-hero-card-img" style="display:flex;align-items:center;justify-content:center;font-size:3.5rem;background:color-mix(in srgb, var(--ec-accent) 8%, var(--ec-bg4));">💼</div>
                    <div class="ec-hero-card-body">
                        <div class="ec-hero-card-badge">Manager</div>
                        <div class="ec-hero-card-name">Manager Table</div>
                        <div class="ec-hero-card-price">₹9,800</div>
                    </div>
                </div>
                <div class="ec-hero-card">
                    <div class="ec-hero-card-img" style="display:flex;align-items:center;justify-content:center;font-size:3.5rem;background:color-mix(in srgb, var(--ec-accent) 8%, var(--ec-bg4));">🖱️</div>
                    <div class="ec-hero-card-body">
                        <div class="ec-hero-card-badge">Workstation</div>
                        <div class="ec-hero-card-name">Computer Desk</div>
                        <div class="ec-hero-card-price">₹5,500</div>
                    </div>
                </div>
                <div class="ec-hero-card">
                    <div class="ec-hero-card-img" style="display:flex;align-items:center;justify-content:center;font-size:3.5rem;background:color-mix(in srgb, var(--ec-accent) 8%, var(--ec-bg4));">📋</div>
                    <div class="ec-hero-card-body">
                        <div class="ec-hero-card-badge">Reception</div>
                        <div class="ec-hero-card-name">Reception Desk</div>
                        <div class="ec-hero-card-price">₹18,000</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Slider Controls --}}
    <button class="ec-slider-prev" onclick="ecSlide(-1)" aria-label="Previous slide"><i class="fas fa-chevron-left"></i></button>
    <button class="ec-slider-next" onclick="ecSlide(1)" aria-label="Next slide"><i class="fas fa-chevron-right"></i></button>
    <div class="ec-slider-dots" id="ecDots">
        <button class="ec-dot active" onclick="ecGoTo(0)"></button>
        <button class="ec-dot" onclick="ecGoTo(1)"></button>
        <button class="ec-dot" onclick="ecGoTo(2)"></button>
    </div>
</div>
@endif

{{-- TRUST BAR --}}
<div class="ec-trust">
    <div class="xn-ec-wrap">
        <div class="ec-trust-row">
            <div class="ec-trust-item">
                <div class="ec-trust-icon">🏭</div>
                <div>
                    <div class="ec-trust-text">Direct Manufacturer</div>
                    <div class="ec-trust-sub">Factory-direct pricing</div>
                </div>
            </div>
            <div class="ec-trust-item">
                <div class="ec-trust-icon">🏆</div>
                <div>
                    <div class="ec-trust-text">ISO 9001:2015</div>
                    <div class="ec-trust-sub">Quality certified</div>
                </div>
            </div>
            <div class="ec-trust-item">
                <div class="ec-trust-icon">🚚</div>
                <div>
                    <div class="ec-trust-text">Pan India Delivery</div>
                    <div class="ec-trust-sub">Fast & safe shipping</div>
                </div>
            </div>
            <div class="ec-trust-item">
                <div class="ec-trust-icon">🔧</div>
                <div>
                    <div class="ec-trust-text">Installation Support</div>
                    <div class="ec-trust-sub">Professional setup</div>
                </div>
            </div>
            <div class="ec-trust-item">
                <div class="ec-trust-icon">📞</div>
                <div>
                    <div class="ec-trust-text">Bulk Orders Welcome</div>
                    <div class="ec-trust-sub">Custom quotes available</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- STATS --}}
@if($_show('stats'))
<section class="ec-stats">
    <div class="xn-ec-wrap">
        <div class="ec-stats-grid">
            @foreach($statsItems as $stat)
            <div class="ec-stat-cell">
                <div class="ec-stat-icon">{{ $stat['icon'] ?? '📊' }}</div>
                <div class="ec-stat-num">{{ $stat['value'] ?? $stat['num'] ?? '—' }}</div>
                <div class="ec-stat-label">{{ $stat['label'] ?? '' }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FEATURED PRODUCTS --}}
<section class="ec-section ec-featured">
    <div class="xn-ec-wrap">
        <div class="ec-section-header">
            <div class="ec-section-badge">🔥 Featured Products</div>
            <h2 class="ec-section-title">Our Best Sellers</h2>
            <p class="ec-section-sub">Handpicked from our most popular collection — quality furniture trusted by thousands of customers across India.</p>
        </div>
        @php
            $featuredProds = \App\Models\Product::where('user_id', $tenant->id)
                ->where('is_active', 1)
                ->whereNotNull('featured_image')
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();
        @endphp
        @if($featuredProds->isNotEmpty())
        <div class="ec-products-grid">
            @foreach($featuredProds as $prod)
            <a href="{{ $shopUrl }}" class="ec-prod-card">
                <div class="ec-prod-img-wrap">
                    <img src="{{ str_starts_with($prod->featured_image, 'http') ? $prod->featured_image : asset('storage/' . $prod->featured_image) }}"
                         alt="{{ $prod->name }}"
                         loading="lazy"
                         onerror="this.parentElement.innerHTML='<div style=\'display:flex;align-items:center;justify-content:center;height:100%;font-size:3rem;\'>🪑</div>'">
                    <div class="ec-prod-badge">New</div>
                </div>
                <div class="ec-prod-body">
                    <div class="ec-prod-cat">{{ $prod->category?->name ?? 'Furniture' }}</div>
                    <div class="ec-prod-name">{{ Str::limit($prod->name, 50) }}</div>
                    <div>
                        <span class="ec-prod-price">₹{{ number_format($prod->effective_price, 0) }}</span>
                        @if($prod->sale_price && $prod->price > $prod->sale_price)
                        <span class="ec-prod-orig">₹{{ number_format($prod->price, 0) }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div style="text-align:center;margin-top:2.5rem;">
            <a href="{{ $shopUrl }}" class="ec-btn ec-btn-primary">
                <i class="fas fa-th-large"></i> View All Products
            </a>
        </div>
        @endif
    </div>
</section>

{{-- CATEGORIES --}}
@if($_show('services'))
<section class="ec-section ec-cats">
    <div class="xn-ec-wrap">
        <div class="ec-section-header">
            <div class="ec-section-badge">Shop by Category</div>
            <h2 class="ec-section-title">Explore Our Collections</h2>
            <p class="ec-section-sub">From center tables and locker cupboards to office workstations and steel racks — find the perfect furniture for your space.</p>
        </div>
        <div class="ec-cats-grid">
            @foreach($categories as $cat)
            <a href="{{ $shopUrl }}" class="ec-cat-card">
                <div class="ec-cat-icon">{{ $cat['icon'] ?? '🛍️' }}</div>
                <div class="ec-cat-name">{{ $cat['title'] ?? $cat['name'] ?? 'Category' }}</div>
                <div class="ec-cat-desc">{{ $cat['text'] ?? $cat['description'] ?? '' }}</div>
                <div class="ec-cat-arrow">Explore <i class="fas fa-arrow-right"></i></div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- WHY CHOOSE US --}}
<section class="ec-section ec-why">
    <div class="xn-ec-wrap">
        <div class="ec-section-header">
            <div class="ec-section-badge">Why Jose Industries</div>
            <h2 class="ec-section-title">Built on 35+ Years of Excellence</h2>
            <p class="ec-section-sub">We combine decades of craftsmanship with modern manufacturing techniques to deliver furniture that lasts a lifetime.</p>
        </div>
        <div class="ec-why-grid">
            <div class="ec-why-card">
                <div class="ec-why-icon">🏭</div>
                <div class="ec-why-title">Direct Manufacturer</div>
                <div class="ec-why-text">We manufacture everything in-house at our Chennai facility. No middlemen means better quality control and competitive pricing for you.</div>
            </div>
            <div class="ec-why-card">
                <div class="ec-why-icon">🏆</div>
                <div class="ec-why-title">ISO 9001:2015 Certified</div>
                <div class="ec-why-text">Our quality management system is internationally certified. Every product goes through rigorous quality testing before it reaches you.</div>
            </div>
            <div class="ec-why-card">
                <div class="ec-why-icon">🔬</div>
                <div class="ec-why-title">Quality Testing Lab</div>
                <div class="ec-why-text">We have a dedicated quality testing laboratory that ensures every piece of furniture meets our strict standards for durability and finish.</div>
            </div>
            <div class="ec-why-card">
                <div class="ec-why-icon">🎨</div>
                <div class="ec-why-title">Custom Designs</div>
                <div class="ec-why-text">Need something specific? Our R&D team can create custom furniture designs tailored to your exact requirements and specifications.</div>
            </div>
            <div class="ec-why-card">
                <div class="ec-why-icon">🚚</div>
                <div class="ec-why-title">Pan India Delivery</div>
                <div class="ec-why-text">We deliver across India with our dedicated logistics team. Bulk orders get special handling and priority delivery scheduling.</div>
            </div>
            <div class="ec-why-card">
                <div class="ec-why-icon">💼</div>
                <div class="ec-why-title">Bulk Order Specialists</div>
                <div class="ec-why-text">Schools, hospitals, offices, and institutions trust us for large-scale furniture procurement. Get special pricing for bulk orders.</div>
            </div>
        </div>
    </div>
</section>

{{-- ABOUT --}}
@if($_show('about'))
<section class="ec-section ec-about">
    <div class="xn-ec-wrap">
        <div class="ec-about-inner">
            <div class="ec-about-visual">
                <div class="ec-about-metric">
                    <div class="ec-about-metric-val">1986</div>
                    <div class="ec-about-metric-label">Est. Year</div>
                </div>
                <div class="ec-about-metric">
                    <div class="ec-about-metric-val">500+</div>
                    <div class="ec-about-metric-label">Products</div>
                </div>
                <div class="ec-about-metric">
                    <div class="ec-about-metric-val">10K+</div>
                    <div class="ec-about-metric-label">Clients</div>
                </div>
                <div class="ec-about-metric">
                    <div class="ec-about-metric-val">35+</div>
                    <div class="ec-about-metric-label">Years Exp.</div>
                </div>
            </div>
            <div>
                <div class="ec-about-badge">About Jose Industries</div>
                <h2 class="ec-about-h2">Chennai's Trusted Furniture Manufacturer</h2>
                <p class="ec-about-text">{{ $aboutText }}</p>
                <ul class="ec-about-features">
                    <li>ISO 9001:2015 certified manufacturing facility</li>
                    <li>State-of-the-art production with dedicated R&D department</li>
                    <li>Raw materials sourced from authorized, quality-verified vendors</li>
                    <li>Dedicated departments for Production, QC, Logistics & Packaging</li>
                    <li>Custom designs available for bulk and institutional orders</li>
                    <li>GST registered, transparent pricing with no hidden charges</li>
                </ul>
                <a href="{{ $shopUrl }}" class="ec-btn ec-btn-primary">
                    <i class="fas fa-shopping-cart"></i> Shop Our Products
                </a>
            </div>
        </div>
    </div>
</section>
@endif

{{-- TESTIMONIALS --}}
@if($_show('testimonials'))
<section class="ec-section ec-testimonials">
    <div class="xn-ec-wrap">
        <div class="ec-section-header">
            <div class="ec-section-badge">Customer Reviews</div>
            <h2 class="ec-section-title">What Our Clients Say</h2>
            <p class="ec-section-sub">Thousands of businesses and institutions across India trust Jose Industries for their furniture needs.</p>
        </div>
        <div class="ec-test-grid">
            @foreach($testimonials as $t)
            <div class="ec-test-card">
                <div class="ec-test-stars">★★★★★</div>
                <p class="ec-test-text">"{{ $t['text'] ?? '' }}"</p>
                <div class="ec-test-author">
                    <div class="ec-test-avatar">{{ strtoupper(substr($t['name'] ?? 'C', 0, 1)) }}</div>
                    <div>
                        <div class="ec-test-name">{{ $t['name'] ?? 'Customer' }}</div>
                        <div class="ec-test-role">{{ $t['role'] ?? 'Verified Buyer' }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- NEWSLETTER --}}
<div class="ec-newsletter">
    <div class="xn-ec-wrap">
        <div class="ec-newsletter-inner">
            <h2 class="ec-newsletter-title">Stay Updated</h2>
            <p class="ec-newsletter-sub">Subscribe to get the latest product launches, special offers, and bulk pricing updates from Jose Industries.</p>
            <form class="ec-newsletter-form" onsubmit="return false;">
                <input type="email" class="ec-newsletter-input" placeholder="Enter your email address">
                <button class="ec-newsletter-btn"><i class="fas fa-paper-plane"></i> Subscribe</button>
            </form>
        </div>
    </div>
</div>

{{-- CONTACT --}}
@if($_show('contact'))
<section class="ec-section ec-contact">
    <div class="xn-ec-wrap">
        <div class="ec-contact-inner">
            <div>
                <h2 class="ec-contact-h2">{{ $contactHeading }}</h2>
                <p class="ec-contact-sub">{{ $contactText }}</p>
                <div class="ec-contact-items">
                    @if(!empty($profile['email']))
                    <div class="ec-contact-item">
                        <div class="ec-contact-item-icon">📧</div>
                        <div>
                            <div class="ec-contact-item-label">Email</div>
                            <div class="ec-contact-item-val">{{ $profile['email'] }}</div>
                        </div>
                    </div>
                    @endif
                    @if(!empty($profile['phone']))
                    <div class="ec-contact-item">
                        <div class="ec-contact-item-icon">📞</div>
                        <div>
                            <div class="ec-contact-item-label">Phone / WhatsApp</div>
                            <div class="ec-contact-item-val">{{ $profile['phone'] }}</div>
                        </div>
                    </div>
                    @endif
                    <div class="ec-contact-item">
                        <div class="ec-contact-item-icon">📍</div>
                        <div>
                            <div class="ec-contact-item-label">Address</div>
                            <div class="ec-contact-item-val">Chennai, Tamil Nadu, India</div>
                        </div>
                    </div>
                    <div class="ec-contact-item">
                        <div class="ec-contact-item-icon">🕐</div>
                        <div>
                            <div class="ec-contact-item-label">Business Hours</div>
                            <div class="ec-contact-item-val">Mon–Sat, 9 AM – 7 PM IST</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ec-form">
                <form action="{{ $tenantBase }}/contact" method="POST">
                    @csrf
                    <div class="ec-form-row">
                        <div class="ec-form-group">
                            <label class="ec-form-label">Your Name</label>
                            <input type="text" name="name" class="ec-form-input" placeholder="John Doe" required>
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-form-label">Email Address</label>
                            <input type="email" name="email" class="ec-form-input" placeholder="john@example.com" required>
                        </div>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-form-label">Subject</label>
                        <input type="text" name="subject" class="ec-form-input" placeholder="Product enquiry, bulk order, custom design...">
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-form-label">Message</label>
                        <textarea name="message" class="ec-form-input ec-form-textarea" placeholder="Tell us about your requirements..." required></textarea>
                    </div>
                    <button type="submit" class="ec-btn ec-btn-primary" style="width:100%;justify-content:center;">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endif

<script>
(function() {
    var slides = document.querySelectorAll('.ec-slide');
    var dots = document.querySelectorAll('.ec-dot');
    var current = 0;
    var timer;

    function show(n) {
        slides[current].classList.remove('active');
        dots[current].classList.remove('active');
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('active');
        dots[current].classList.add('active');
    }

    window.ecSlide = function(dir) { clearInterval(timer); show(current + dir); startTimer(); };
    window.ecGoTo  = function(n)   { clearInterval(timer); show(n); startTimer(); };

    function startTimer() {
        timer = setInterval(function() { show(current + 1); }, 5000);
    }
    startTimer();
})();
</script>

@endsection
