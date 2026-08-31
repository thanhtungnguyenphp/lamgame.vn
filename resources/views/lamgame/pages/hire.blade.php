@extends('layouts.master')

@section('page_title', 'Hire Game Developers Vietnam | Unity & Unreal Studio')
@section('page_description', 'Hire experienced game developers from Vietnam. Unity, Unreal Engine, Godot specialists. Competitive rates, EU timezone friendly, fluent English.')

@push('meta')
<meta name="description" content="Hire game developers from Vietnam. Professional Unity, Unreal Engine, Godot development. 40-60% lower cost, EU timezone overlap, fluent English communication. 50+ projects delivered.">
<meta name="keywords" content="hire game developers vietnam, vietnam game studio, unity developers for hire, game development outsourcing vietnam, remote game developers asia, game outsourcing ho chi minh, indie game development vietnam, mobile game developers vietnam">
<meta property="og:title" content="Hire Game Developers Vietnam | LamGame Studio">
<meta property="og:description" content="Professional game development studio from Vietnam. 5+ years experience, 50+ projects delivered. Competitive rates for EU/US clients.">
<meta property="og:image" content="{{ asset('images/lamgame-hire-og.png') }}">
<meta property="og:url" content="{{ url('/hire') }}">
<meta name="twitter:card" content="summary_large_image">
<link rel="canonical" href="{{ url('/hire') }}">

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ProfessionalService",
    "name": "LamGame Studio",
    "description": "Professional game development studio in Vietnam specializing in Unity, Unreal Engine, and mobile game development for international clients",
    "url": "{{ url('/hire') }}",
    "logo": "{{ asset('images/lamgame-logo.png') }}",
    "areaServed": ["Worldwide", "Europe", "North America", "Asia"],
    "serviceType": ["Game Development", "Unity Development", "Mobile Game Development", "Web Game Development", "Game Outsourcing"],
    "priceRange": "$$",
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "Ho Chi Minh City",
        "addressRegion": "Ho Chi Minh",
        "addressCountry": "VN"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "10.8231",
        "longitude": "106.6297"
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "email": "salegamevui@gmail.com",
        "contactType": "sales",
        "availableLanguage": ["English", "Vietnamese"]
    },
    "sameAs": [
        "https://discord.gg/lamgame",
        "https://facebook.com/lamgamevn",
        "https://github.com/lamgame"
    ]
}
</script>
@endpush

@push('styles')
<style>
:root {
    --hire-bg: #0D0D1A;
    --hire-surface: #161625;
    --hire-surface-alt: #1E1E30;
    --hire-border: #2A2A40;
    --hire-text: #F0F0F5;
    --hire-text-muted: #8B8BA3;
    --hire-accent: #8B5CF6;
    --hire-accent-hover: #A78BFA;
    --hire-success: #10B981;
    --hire-gradient: linear-gradient(135deg, #8B5CF6 0%, #6366F1 100%);
}

/* Reset & Base */
.hire-page { background: var(--hire-bg); color: var(--hire-text); min-height: 100vh; }
.hire-page * { box-sizing: border-box; }
.hire-container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }

/* Hero Section */
.hire-hero {
    background: linear-gradient(180deg, #1a1a2e 0%, var(--hire-bg) 100%);
    padding: 100px 0 80px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.hire-hero::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: radial-gradient(ellipse at 50% 0%, rgba(139, 92, 246, 0.15) 0%, transparent 60%);
    pointer-events: none;
}
.hire-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(139, 92, 246, 0.15);
    border: 1px solid rgba(139, 92, 246, 0.3);
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 14px;
    color: var(--hire-accent);
    margin-bottom: 24px;
}
.hire-hero h1 {
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 800;
    margin-bottom: 20px;
    line-height: 1.1;
    background: linear-gradient(135deg, #fff 0%, #c4b5fd 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.hire-hero-subtitle {
    font-size: 1.25rem;
    color: var(--hire-text-muted);
    max-width: 700px;
    margin: 0 auto 40px;
    line-height: 1.6;
}
.hire-hero-cta {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
}
.btn-primary {
    background: var(--hire-gradient);
    color: #fff;
    padding: 16px 32px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 40px rgba(139, 92, 246, 0.3); }
.btn-secondary {
    background: transparent;
    color: var(--hire-text);
    padding: 16px 32px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    text-decoration: none;
    border: 1px solid var(--hire-border);
    transition: all 0.3s ease;
}
.btn-secondary:hover { background: var(--hire-surface); border-color: var(--hire-accent); }

/* Stats Bar */
.hire-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    padding: 40px 0;
    border-bottom: 1px solid var(--hire-border);
}
@media (max-width: 768px) { .hire-stats { grid-template-columns: repeat(2, 1fr); } }
.hire-stat {
    text-align: center;
}
.hire-stat-value {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--hire-accent);
    line-height: 1;
}
.hire-stat-label {
    font-size: 14px;
    color: var(--hire-text-muted);
    margin-top: 8px;
}

/* Services Section */
.hire-section {
    padding: 80px 0;
}
.hire-section-header {
    text-align: center;
    margin-bottom: 60px;
}
.hire-section-header h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 16px;
}
.hire-section-header p {
    color: var(--hire-text-muted);
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
}
.service-card {
    background: var(--hire-surface);
    border: 1px solid var(--hire-border);
    border-radius: 16px;
    padding: 32px;
    transition: all 0.3s ease;
}
.service-card:hover {
    border-color: var(--hire-accent);
    transform: translateY(-4px);
}
.service-icon {
    width: 56px;
    height: 56px;
    background: rgba(139, 92, 246, 0.15);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin-bottom: 20px;
}
.service-card h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 12px;
}
.service-card p {
    color: var(--hire-text-muted);
    line-height: 1.6;
    margin-bottom: 16px;
}
.service-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.service-tag {
    background: var(--hire-surface-alt);
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    color: var(--hire-text-muted);
}

/* Why Us Section */
.why-us-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 32px;
}
.why-us-card {
    display: flex;
    gap: 20px;
    padding: 24px;
    background: var(--hire-surface);
    border-radius: 12px;
    border: 1px solid var(--hire-border);
}
.why-us-icon {
    width: 48px;
    height: 48px;
    background: rgba(139, 92, 246, 0.15);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
}
.why-us-content h3 {
    font-size: 1.1rem;
    margin-bottom: 8px;
}
.why-us-content p {
    color: var(--hire-text-muted);
    font-size: 14px;
    line-height: 1.6;
}

/* Process Section */
.process-timeline {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin-top: 60px;
}
.process-timeline::before {
    content: '';
    position: absolute;
    top: 32px;
    left: 10%;
    right: 10%;
    height: 2px;
    background: var(--hire-border);
}
@media (max-width: 768px) {
    .process-timeline { flex-direction: column; gap: 32px; }
    .process-timeline::before { display: none; }
}
.process-step {
    text-align: center;
    flex: 1;
    position: relative;
    z-index: 1;
}
.process-step-num {
    width: 64px;
    height: 64px;
    background: var(--hire-gradient);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 20px;
}
.process-step h4 {
    font-size: 1.1rem;
    margin-bottom: 8px;
}
.process-step p {
    color: var(--hire-text-muted);
    font-size: 14px;
    max-width: 200px;
    margin: 0 auto;
}

/* Tech Stack */
.tech-stack {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 16px;
    margin-top: 40px;
}
.tech-item {
    background: var(--hire-surface);
    border: 1px solid var(--hire-border);
    padding: 16px 24px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 500;
    transition: all 0.3s ease;
}
.tech-item:hover {
    border-color: var(--hire-accent);
    transform: translateY(-2px);
}
.tech-item img {
    width: 32px;
    height: 32px;
    object-fit: contain;
}

/* Pricing Section */
.pricing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}
.pricing-card {
    background: var(--hire-surface);
    border: 1px solid var(--hire-border);
    border-radius: 16px;
    padding: 32px;
    text-align: center;
}
.pricing-card.featured {
    border-color: var(--hire-accent);
    position: relative;
}
.pricing-card.featured::before {
    content: 'Most Popular';
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--hire-gradient);
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.pricing-card h3 {
    font-size: 1.25rem;
    margin-bottom: 8px;
}
.pricing-card .price {
    font-size: 2rem;
    font-weight: 700;
    color: var(--hire-accent);
    margin-bottom: 8px;
}
.pricing-card .price-note {
    color: var(--hire-text-muted);
    font-size: 14px;
    margin-bottom: 24px;
}
.pricing-features {
    text-align: left;
    margin-bottom: 24px;
}
.pricing-features li {
    padding: 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
    color: var(--hire-text-muted);
}
.pricing-features li::before {
    content: '✓';
    color: var(--hire-success);
    font-weight: 700;
}

/* Contact Form */
.contact-section {
    background: var(--hire-surface);
    border-radius: 24px;
    padding: 60px;
    margin: 40px 0;
}
@media (max-width: 768px) { .contact-section { padding: 32px 24px; } }
.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 60px;
}
@media (max-width: 900px) { .contact-grid { grid-template-columns: 1fr; } }
.contact-info h2 {
    font-size: 2rem;
    margin-bottom: 16px;
}
.contact-info p {
    color: var(--hire-text-muted);
    margin-bottom: 32px;
    line-height: 1.6;
}
.contact-details {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.contact-item {
    display: flex;
    align-items: center;
    gap: 16px;
}
.contact-item-icon {
    width: 48px;
    height: 48px;
    background: rgba(139, 92, 246, 0.15);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}
.contact-item-text strong {
    display: block;
    margin-bottom: 4px;
}
.contact-item-text span {
    color: var(--hire-text-muted);
    font-size: 14px;
}
.hire-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
@media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }
.form-group label {
    display: block;
    font-weight: 500;
    margin-bottom: 8px;
    font-size: 14px;
}
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 14px 16px;
    background: var(--hire-bg);
    border: 1px solid var(--hire-border);
    border-radius: 10px;
    color: var(--hire-text);
    font-size: 15px;
    transition: border-color 0.2s;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--hire-accent);
}
.form-group textarea {
    resize: vertical;
    min-height: 120px;
}
.form-group select {
    cursor: pointer;
}
.form-message {
    padding: 16px;
    border-radius: 10px;
    text-align: center;
    display: none;
}
.form-message.success {
    background: rgba(16, 185, 129, 0.15);
    color: var(--hire-success);
    display: block;
}
.form-message.error {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
    display: block;
}

/* Testimonials */
.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 24px;
}
.testimonial-card {
    background: var(--hire-surface);
    border: 1px solid var(--hire-border);
    border-radius: 16px;
    padding: 32px;
}
.testimonial-quote {
    font-size: 1.1rem;
    line-height: 1.7;
    margin-bottom: 24px;
    font-style: italic;
    color: var(--hire-text-muted);
}
.testimonial-author {
    display: flex;
    align-items: center;
    gap: 16px;
}
.testimonial-avatar {
    width: 48px;
    height: 48px;
    background: var(--hire-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
}
.testimonial-info strong {
    display: block;
    margin-bottom: 4px;
}
.testimonial-info span {
    color: var(--hire-text-muted);
    font-size: 14px;
}

/* CTA Section */
.cta-section {
    text-align: center;
    padding: 80px 0;
    background: linear-gradient(180deg, var(--hire-bg) 0%, #1a1a2e 100%);
}
.cta-section h2 {
    font-size: 2.5rem;
    margin-bottom: 16px;
}
.cta-section p {
    color: var(--hire-text-muted);
    margin-bottom: 32px;
    font-size: 1.1rem;
}
</style>
@endpush

@section('content')
<div class="hire-page" role="main">
    <!-- Hero Section -->
    <section class="hire-hero" aria-labelledby="hire-hero-title">
        <div class="hire-container">
            <div class="hire-hero-badge" role="status">
                <span aria-hidden="true">🌏</span> Based in Vietnam • Working Globally
            </div>
            <h1 id="hire-hero-title">Hire Expert Game Developers</h1>
            <p class="hire-hero-subtitle">
                We build games that players love. From concept to launch, our experienced team delivers 
                high-quality game development at competitive rates with smooth English communication.
            </p>
            <div class="hire-hero-cta" role="group" aria-label="Main actions">
                <a href="#contact" class="btn-primary" role="button">Start Your Project</a>
                <a href="/portfolio" class="btn-secondary" role="button">View Portfolio</a>
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <section class="hire-container" aria-label="Company statistics">
        <div class="hire-stats" role="list">
            <div class="hire-stat" role="listitem">
                <div class="hire-stat-value" aria-label="5 plus years">5+</div>
                <div class="hire-stat-label">Years Experience</div>
            </div>
            <div class="hire-stat" role="listitem">
                <div class="hire-stat-value" aria-label="50 plus projects">50+</div>
                <div class="hire-stat-label">Projects Delivered</div>
            </div>
            <div class="hire-stat" role="listitem">
                <div class="hire-stat-value" aria-label="20 plus clients">20+</div>
                <div class="hire-stat-label">Happy Clients</div>
            </div>
            <div class="hire-stat" role="listitem">
                <div class="hire-stat-value">98%</div>
                <div class="hire-stat-label">Client Satisfaction</div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="hire-section" aria-labelledby="services-title">
        <div class="hire-container">
            <div class="hire-section-header">
                <h2 id="services-title">Our Services</h2>
                <p>Full-cycle game development from prototyping to post-launch support</p>
            </div>
            <div class="services-grid" role="list">
                <article class="service-card" role="listitem">
                    <div class="service-icon" aria-hidden="true">🎮</div>
                    <h3>Unity Game Development</h3>
                    <p>Cross-platform games for mobile, PC, console, and WebGL. 2D, 3D, AR/VR experiences.</p>
                    <div class="service-tags" role="list" aria-label="Platforms">
                        <span class="service-tag" role="listitem">Mobile</span>
                        <span class="service-tag" role="listitem">PC</span>
                        <span class="service-tag" role="listitem">WebGL</span>
                        <span class="service-tag" role="listitem">AR/VR</span>
                    </div>
                </article>
                <article class="service-card" role="listitem">
                    <div class="service-icon" aria-hidden="true">🔥</div>
                    <h3>Unreal Engine Development</h3>
                    <p>High-fidelity games and real-time 3D experiences with stunning visuals.</p>
                    <div class="service-tags" role="list" aria-label="Technologies">
                        <span class="service-tag" role="listitem">AAA Quality</span>
                        <span class="service-tag" role="listitem">Blueprints</span>
                        <span class="service-tag" role="listitem">C++</span>
                    </div>
                </article>
                <article class="service-card" role="listitem">
                    <div class="service-icon" aria-hidden="true">🌿</div>
                    <h3>Godot Development</h3>
                    <p>Lightweight, open-source game development. Perfect for indie games and prototypes.</p>
                    <div class="service-tags" role="list" aria-label="Features">
                        <span class="service-tag" role="listitem">2D Games</span>
                        <span class="service-tag" role="listitem">GDScript</span>
                        <span class="service-tag" role="listitem">Open Source</span>
                    </div>
                </article>
                <article class="service-card" role="listitem">
                    <div class="service-icon" aria-hidden="true">📱</div>
                    <h3>Mobile Game Development</h3>
                    <p>Native and cross-platform mobile games optimized for iOS and Android.</p>
                    <div class="service-tags" role="list" aria-label="Platforms">
                        <span class="service-tag" role="listitem">iOS</span>
                        <span class="service-tag" role="listitem">Android</span>
                        <span class="service-tag" role="listitem">Hyper-casual</span>
                    </div>
                </article>
                <article class="service-card" role="listitem">
                    <div class="service-icon" aria-hidden="true">🌐</div>
                    <h3>HTML5 & Web Games</h3>
                    <p>Browser-based games, playable ads, and interactive web experiences.</p>
                    <div class="service-tags" role="list" aria-label="Features">
                        <span class="service-tag" role="listitem">Playable Ads</span>
                        <span class="service-tag" role="listitem">Web Apps</span>
                        <span class="service-tag" role="listitem">Canvas</span>
                    </div>
                </article>
                <article class="service-card" role="listitem">
                    <div class="service-icon" aria-hidden="true">🛠️</div>
                    <h3>Game Porting & Optimization</h3>
                    <p>Port your existing game to new platforms or optimize performance.</p>
                    <div class="service-tags" role="list" aria-label="Features">
                        <span class="service-tag" role="listitem">Cross-platform</span>
                        <span class="service-tag" role="listitem">Performance</span>
                        <span class="service-tag" role="listitem">Console</span>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Why Us Section -->
    <section class="hire-section" style="background: var(--hire-surface-alt);" aria-labelledby="why-us-title">
        <div class="hire-container">
            <div class="hire-section-header">
                <h2 id="why-us-title">Why Work With Us</h2>
                <p>We're not just developers — we're your partners in bringing ideas to life</p>
            </div>
            <div class="why-us-grid" role="list">
                <article class="why-us-card" role="listitem">
                    <div class="why-us-icon" aria-hidden="true">💰</div>
                    <div class="why-us-content">
                        <h3>Competitive Rates</h3>
                        <p>High-quality development at 40-60% lower cost compared to Western agencies. No compromise on quality.</p>
                    </div>
                </article>
                <article class="why-us-card" role="listitem">
                    <div class="why-us-icon" aria-hidden="true">🗣️</div>
                    <div class="why-us-content">
                        <h3>Fluent English</h3>
                        <p>Clear communication, detailed documentation, and daily updates in English. No language barriers.</p>
                    </div>
                </article>
                <article class="why-us-card" role="listitem">
                    <div class="why-us-icon" aria-hidden="true">🌍</div>
                    <div class="why-us-content">
                        <h3>EU-Friendly Timezone</h3>
                        <p>UTC+7 timezone with flexible hours. Overlap available for European and US clients.</p>
                    </div>
                </article>
                <article class="why-us-card" role="listitem">
                    <div class="why-us-icon" aria-hidden="true">⚡</div>
                    <div class="why-us-content">
                        <h3>Fast Turnaround</h3>
                        <p>Agile methodology with 2-week sprints. Regular demos and quick iterations.</p>
                    </div>
                </article>
                <article class="why-us-card" role="listitem">
                    <div class="why-us-icon" aria-hidden="true">🔒</div>
                    <div class="why-us-content">
                        <h3>Full IP Ownership</h3>
                        <p>You own 100% of the code and assets. NDA signed before project start.</p>
                    </div>
                </article>
                <article class="why-us-card" role="listitem">
                    <div class="why-us-icon" aria-hidden="true">🎯</div>
                    <div class="why-us-content">
                        <h3>Dedicated Team</h3>
                        <p>Your project gets dedicated resources. Direct communication with developers, not middlemen.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="hire-section" aria-labelledby="process-title">
        <div class="hire-container">
            <div class="hire-section-header">
                <h2 id="process-title">How We Work</h2>
                <p>A streamlined process from first contact to project delivery</p>
            </div>
            <ol class="process-timeline" role="list">
                <li class="process-step">
                    <div class="process-step-num" aria-hidden="true">1</div>
                    <h4>Discovery Call</h4>
                    <p>Free consultation to understand your vision and requirements</p>
                </li>
                <li class="process-step">
                    <div class="process-step-num" aria-hidden="true">2</div>
                    <h4>Proposal & Planning</h4>
                    <p>Detailed scope, timeline, and transparent pricing</p>
                </li>
                <li class="process-step">
                    <div class="process-step-num" aria-hidden="true">3</div>
                    <h4>Development</h4>
                    <p>Agile sprints with regular demos and feedback loops</p>
                </li>
                <li class="process-step">
                    <div class="process-step-num" aria-hidden="true">4</div>
                    <h4>QA & Launch</h4>
                    <p>Thorough testing, deployment, and post-launch support</p>
                </li>
            </ol>
        </div>
    </section>

    <!-- Tech Stack -->
    <section class="hire-section" style="background: var(--hire-surface-alt);" aria-labelledby="tech-title">
        <div class="hire-container">
            <div class="hire-section-header">
                <h2 id="tech-title">Our Tech Stack</h2>
                <p>Industry-standard tools and technologies</p>
            </div>
            <div class="tech-stack" role="list" aria-label="Technologies we use">
                <div class="tech-item" role="listitem">
                    <span aria-hidden="true" style="font-size: 24px;">🎮</span> Unity
                </div>
                <div class="tech-item" role="listitem">
                    <span aria-hidden="true" style="font-size: 24px;">🔥</span> Unreal Engine
                </div>
                <div class="tech-item" role="listitem">
                    <span aria-hidden="true" style="font-size: 24px;">🌿</span> Godot
                </div>
                <div class="tech-item" role="listitem">
                    <span aria-hidden="true" style="font-size: 24px;">📘</span> C#
                </div>
                <div class="tech-item" role="listitem">
                    <span aria-hidden="true" style="font-size: 24px;">⚡</span> C++
                </div>
                <div class="tech-item" role="listitem">
                    <span aria-hidden="true" style="font-size: 24px;">🎨</span> Blender
                </div>
                <div class="tech-item" role="listitem">
                    <span aria-hidden="true" style="font-size: 24px;">🖼️</span> Photoshop
                </div>
                <div class="tech-item" role="listitem">
                    <span aria-hidden="true" style="font-size: 24px;">🔧</span> Git
                </div>
                <div class="tech-item" role="listitem">
                    <span aria-hidden="true" style="font-size: 24px;">☁️</span> AWS/GCP
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="hire-section" id="pricing" aria-labelledby="pricing-title">
        <div class="hire-container">
            <div class="hire-section-header">
                <h2 id="pricing-title">Engagement Models</h2>
                <p>Flexible options to match your project needs</p>
            </div>
            <div class="pricing-grid" role="list">
                <article class="pricing-card" role="listitem">
                    <h3>Fixed Price</h3>
                    <div class="price" aria-label="Starting from 5000 dollars">From $5,000</div>
                    <div class="price-note">Per project</div>
                    <ul class="pricing-features" role="list">
                        <li role="listitem">Best for well-defined scope</li>
                        <li role="listitem">Fixed budget & timeline</li>
                        <li role="listitem">Milestone-based payments</li>
                        <li role="listitem">Full ownership on delivery</li>
                        <li role="listitem">30-day post-launch support</li>
                    </ul>
                    <a href="#contact" class="btn-primary" role="button" style="display: block;">Get Quote</a>
                </article>
                <article class="pricing-card featured" role="listitem" aria-label="Most popular option">
                    <h3>Dedicated Team</h3>
                    <div class="price" aria-label="2500 dollars per month">$2,500/mo</div>
                    <div class="price-note">Per developer</div>
                    <ul class="pricing-features" role="list">
                        <li role="listitem">Ideal for ongoing projects</li>
                        <li role="listitem">Dedicated resources</li>
                        <li role="listitem">Flexible scope changes</li>
                        <li role="listitem">Direct communication</li>
                        <li role="listitem">Monthly billing</li>
                    </ul>
                    <a href="#contact" class="btn-primary" role="button" style="display: block;">Get Quote</a>
                </article>
                <article class="pricing-card" role="listitem">
                    <h3>Hourly</h3>
                    <div class="price" aria-label="25 to 45 dollars per hour">$25-45/hr</div>
                    <div class="price-note">Based on skill level</div>
                    <ul class="pricing-features" role="list">
                        <li role="listitem">Maximum flexibility</li>
                        <li role="listitem">Pay only for work done</li>
                        <li role="listitem">Detailed time tracking</li>
                        <li role="listitem">Weekly invoicing</li>
                        <li role="listitem">Scale up/down anytime</li>
                    </ul>
                    <a href="#contact" class="btn-primary" role="button" style="display: block;">Get Quote</a>
                </article>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="hire-section" style="background: var(--hire-surface-alt);" aria-labelledby="testimonials-title">
        <div class="hire-container">
            <div class="hire-section-header">
                <h2 id="testimonials-title">What Clients Say</h2>
                <p>Trusted by game studios and indie developers worldwide</p>
            </div>
            <div class="testimonials-grid" role="list">
                <blockquote class="testimonial-card" role="listitem">
                    <p class="testimonial-quote">"The team delivered our mobile game prototype in just 6 weeks. Communication was excellent and they really understood our vision. Highly recommended!"</p>
                    <footer class="testimonial-author">
                        <div class="testimonial-avatar" aria-hidden="true">M</div>
                        <div class="testimonial-info">
                            <strong>Marcus T.</strong>
                            <span>Indie Game Studio, Germany</span>
                        </div>
                    </footer>
                </blockquote>
                <blockquote class="testimonial-card" role="listitem">
                    <p class="testimonial-quote">"Professional, responsive, and technically skilled. They helped us port our Unity game to WebGL with smooth performance. Great value for money."</p>
                    <footer class="testimonial-author">
                        <div class="testimonial-avatar" aria-hidden="true">S</div>
                        <div class="testimonial-info">
                            <strong>Sarah L.</strong>
                            <span>Game Publisher, Netherlands</span>
                        </div>
                    </footer>
                </blockquote>
                <blockquote class="testimonial-card" role="listitem">
                    <p class="testimonial-quote">"We've been working with LamGame for over a year now. They're like an extension of our team. Quality work and always on time."</p>
                    <footer class="testimonial-author">
                        <div class="testimonial-avatar" aria-hidden="true">J</div>
                        <div class="testimonial-info">
                            <strong>James K.</strong>
                            <span>Mobile Games Company, UK</span>
                        </div>
                    </footer>
                </blockquote>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="hire-section" id="contact" aria-labelledby="contact-title">
        <div class="hire-container">
            <div class="contact-section">
                <div class="contact-grid">
                    <div class="contact-info">
                        <h2 id="contact-title">Let's Build Something Great</h2>
                        <p>Tell us about your project and we'll get back to you within 24 hours with a free consultation.</p>
                        
                        <address class="contact-details" style="font-style: normal;">
                            <div class="contact-item">
                                <div class="contact-item-icon" aria-hidden="true">📧</div>
                                <div class="contact-item-text">
                                    <strong>Email</strong>
                                    <span><a href="mailto:salegamevui@gmail.com" style="color: var(--hire-text-muted); text-decoration: none;">salegamevui@gmail.com</a></span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-item-icon" aria-hidden="true">💬</div>
                                <div class="contact-item-text">
                                    <strong>Discord</strong>
                                    <span>discord.gg/lamgame</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-item-icon" aria-hidden="true">🕐</div>
                                <div class="contact-item-text">
                                    <strong>Working Hours</strong>
                                    <span>Mon-Fri, 9AM-6PM (UTC+7)</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-item-icon" aria-hidden="true">📍</div>
                                <div class="contact-item-text">
                                    <strong>Location</strong>
                                    <span>Ho Chi Minh City, Vietnam</span>
                                </div>
                            </div>
                        </address>
                        
                        <!-- Calendly Integration -->
                        <div class="calendly-cta" style="margin-top: 32px; padding: 24px; background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 12px;">
                            <h4 style="margin-bottom: 8px; font-size: 16px;"><span aria-hidden="true">📅</span> Prefer a Video Call?</h4>
                            <p style="color: var(--hire-text-muted); font-size: 14px; margin-bottom: 16px;">Book a free 30-minute consultation to discuss your project.</p>
                            <a href="https://calendly.com/lamgame/consultation" target="_blank" rel="noopener" class="btn-secondary" role="button" style="display: inline-block; padding: 12px 24px; font-size: 14px;">
                                Schedule a Call →
                            </a>
                        </div>
                    </div>
                    
                    <div class="contact-form-wrap">
                        <div id="hire-form-message" class="form-message" role="alert" aria-live="polite"></div>
                        <form class="hire-form" id="hireFormEN" onsubmit="event.preventDefault(); submitHireFormEN();" aria-label="Project inquiry form">
                            @csrf
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="hire-name">Your Name <span aria-hidden="true">*</span><span class="sr-only">(required)</span></label>
                                    <input type="text" id="hire-name" name="name" required maxlength="100" placeholder="John Smith" autocomplete="name">
                                </div>
                                <div class="form-group">
                                    <label for="hire-email">Email <span aria-hidden="true">*</span><span class="sr-only">(required)</span></label>
                                    <input type="email" id="hire-email" name="email" required maxlength="255" placeholder="john@company.com" autocomplete="email">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="hire-company">Company</label>
                                    <input type="text" id="hire-company" name="company" maxlength="255" placeholder="Your Company" autocomplete="organization">
                                </div>
                                <div class="form-group">
                                    <label for="hire-country">Country</label>
                                    <input type="text" id="hire-country" name="country" maxlength="100" placeholder="Germany" autocomplete="country-name">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="hire-project-type">Project Type <span aria-hidden="true">*</span><span class="sr-only">(required)</span></label>
                                    <select id="hire-project-type" name="project_type" required>
                                        <option value="">Select type...</option>
                                        <option value="game-unity">🎮 Unity Game</option>
                                        <option value="game-unreal">🔥 Unreal Engine Game</option>
                                        <option value="game-mobile">📱 Mobile Game</option>
                                        <option value="game-web">🌐 Web/HTML5 Game</option>
                                        <option value="porting">🛠️ Game Porting</option>
                                        <option value="other">📦 Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="hire-budget">Budget Range</label>
                                    <select id="hire-budget" name="budget_range">
                                        <option value="">Not sure yet</option>
                                        <option value="< $5,000">Under $5,000</option>
                                        <option value="$5,000 - $15,000">$5,000 - $15,000</option>
                                        <option value="$15,000 - $50,000">$15,000 - $50,000</option>
                                        <option value="> $50,000">Over $50,000</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="hire-description">Project Description <span aria-hidden="true">*</span><span class="sr-only">(required)</span></label>
                                <textarea id="hire-description" name="description" required maxlength="5000" placeholder="Tell us about your project: What are you building? Target platforms? Key features? Timeline?"></textarea>
                            </div>
                            <button type="submit" class="btn-primary" id="hireSubmitBtnEN" style="width: 100%;" aria-describedby="hire-form-message">
                                Send Project Brief
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" aria-labelledby="final-cta-title">
        <div class="hire-container">
            <h2 id="final-cta-title">Ready to Start Your Project?</h2>
            <p>Get a free consultation and project estimate within 24 hours</p>
            <a href="#contact" class="btn-primary" role="button">Get Free Quote</a>
        </div>
    </section>
</div>

<!-- Accessibility: Screen reader only class -->
<style>
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}
</style>
@endsection

@push('scripts')
<script>
function submitHireFormEN() {
    const form = document.getElementById('hireFormEN');
    const btn = document.getElementById('hireSubmitBtnEN');
    const msg = document.getElementById('hire-form-message');
    const data = Object.fromEntries(new FormData(form));
    
    // Add source language
    data.source = 'hire-page-en';

    btn.disabled = true;
    btn.setAttribute('aria-busy', 'true');
    btn.textContent = 'Sending...';
    msg.className = 'form-message';
    msg.style.display = 'none';

    fetch('/api/v1/hire-request', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json' 
        },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(d => {
        btn.disabled = false;
        btn.removeAttribute('aria-busy');
        btn.textContent = 'Send Project Brief';
        if (d.status === 'success') {
            msg.className = 'form-message success';
            msg.setAttribute('role', 'status');
            msg.innerHTML = '✓ Thank you! We\'ve received your project brief and will get back to you within 24 hours.';
            msg.style.display = 'block';
            form.reset();
            // Scroll to message
            msg.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Focus message for screen readers
            msg.focus();
            
            // Track conversion events
            if (typeof trackEvent === 'function') {
                trackEvent('generate_lead', {
                    event_category: 'hire',
                    event_label: data.project_type || 'general',
                    value: 1
                });
            }
            if (typeof trackFBLead === 'function') {
                trackFBLead('Hire Form - ' + (data.project_type || 'General'));
            }
        } else {
            const errors = d.errors ? Object.values(d.errors).flat().join('<br>') : (d.message || 'Something went wrong. Please try again.');
            msg.className = 'form-message error';
            msg.setAttribute('role', 'alert');
            msg.innerHTML = errors;
            msg.style.display = 'block';
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.removeAttribute('aria-busy');
        btn.textContent = 'Send Project Brief';
        msg.className = 'form-message error';
        msg.setAttribute('role', 'alert');
        msg.innerHTML = 'Network error. Please try again or email us directly at salegamevui@gmail.com';
        msg.style.display = 'block';
    });
}

// Track Calendly clicks
document.addEventListener('DOMContentLoaded', function() {
    const calendlyLink = document.querySelector('a[href*="calendly"]');
    if (calendlyLink) {
        calendlyLink.addEventListener('click', function() {
            if (typeof trackEvent === 'function') {
                trackEvent('cta_click', {
                    event_category: 'hire',
                    event_label: 'calendly_schedule',
                    value: 1
                });
            }
            if (typeof trackFBEvent === 'function') {
                trackFBEvent('Schedule');
            }
        });
    }
    
    // Track CTA button clicks
    document.querySelectorAll('.btn-primary[href="#contact"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (typeof trackEvent === 'function') {
                trackEvent('cta_click', {
                    event_category: 'hire',
                    event_label: 'get_quote_click',
                    value: 1
                });
            }
        });
    });
});
</script>
@endpush
