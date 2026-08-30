@extends('layouts.master')

@section('page_title', 'Hire Game Developers | LamGame Studio')
@section('page_description', 'Hire experienced game developers from Vietnam. Unity, Unreal Engine, Godot specialists. Competitive rates, EU timezone friendly.')

@push('meta')
<meta name="description" content="Hire experienced game developers from Vietnam. Unity, Unreal Engine, Godot specialists. Competitive rates, EU timezone friendly, English communication.">
<meta name="keywords" content="hire game developers, vietnam game studio, unity developers for hire, game development outsourcing, remote game developers">
<meta property="og:title" content="Hire Game Developers | LamGame Studio">
<meta property="og:description" content="Professional game development studio from Vietnam. 5+ years experience, 50+ projects delivered.">
<meta property="og:image" content="{{ asset('images/lamgame-hire-og.png') }}">
<meta property="og:url" content="{{ url('/hire') }}">
<meta name="twitter:card" content="summary_large_image">
<link rel="canonical" href="{{ url('/hire') }}">

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ProfessionalService",
    "name": "LamGame Studio",
    "description": "Professional game development studio specializing in Unity, Unreal Engine, and mobile game development",
    "url": "{{ url('/hire') }}",
    "logo": "{{ asset('images/lamgame-logo.png') }}",
    "areaServed": ["Worldwide", "Europe", "North America", "Asia"],
    "serviceType": ["Game Development", "Unity Development", "Mobile Game Development", "Web Game Development"],
    "priceRange": "$$",
    "address": {
        "@type": "PostalAddress",
        "addressCountry": "VN"
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "email": "hello@lamgame.vn",
        "contactType": "sales"
    }
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
<div class="hire-page">
    <!-- Hero Section -->
    <section class="hire-hero">
        <div class="hire-container">
            <div class="hire-hero-badge">
                <span>🌏</span> Based in Vietnam • Working Globally
            </div>
            <h1>Hire Expert Game Developers</h1>
            <p class="hire-hero-subtitle">
                We build games that players love. From concept to launch, our experienced team delivers 
                high-quality game development at competitive rates with smooth English communication.
            </p>
            <div class="hire-hero-cta">
                <a href="#contact" class="btn-primary">Start Your Project</a>
                <a href="#portfolio" class="btn-secondary">View Portfolio</a>
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <section class="hire-container">
        <div class="hire-stats">
            <div class="hire-stat">
                <div class="hire-stat-value">5+</div>
                <div class="hire-stat-label">Years Experience</div>
            </div>
            <div class="hire-stat">
                <div class="hire-stat-value">50+</div>
                <div class="hire-stat-label">Projects Delivered</div>
            </div>
            <div class="hire-stat">
                <div class="hire-stat-value">20+</div>
                <div class="hire-stat-label">Happy Clients</div>
            </div>
            <div class="hire-stat">
                <div class="hire-stat-value">98%</div>
                <div class="hire-stat-label">Client Satisfaction</div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="hire-section">
        <div class="hire-container">
            <div class="hire-section-header">
                <h2>Our Services</h2>
                <p>Full-cycle game development from prototyping to post-launch support</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">🎮</div>
                    <h3>Unity Game Development</h3>
                    <p>Cross-platform games for mobile, PC, console, and WebGL. 2D, 3D, AR/VR experiences.</p>
                    <div class="service-tags">
                        <span class="service-tag">Mobile</span>
                        <span class="service-tag">PC</span>
                        <span class="service-tag">WebGL</span>
                        <span class="service-tag">AR/VR</span>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-icon">🔥</div>
                    <h3>Unreal Engine Development</h3>
                    <p>High-fidelity games and real-time 3D experiences with stunning visuals.</p>
                    <div class="service-tags">
                        <span class="service-tag">AAA Quality</span>
                        <span class="service-tag">Blueprints</span>
                        <span class="service-tag">C++</span>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-icon">🌿</div>
                    <h3>Godot Development</h3>
                    <p>Lightweight, open-source game development. Perfect for indie games and prototypes.</p>
                    <div class="service-tags">
                        <span class="service-tag">2D Games</span>
                        <span class="service-tag">GDScript</span>
                        <span class="service-tag">Open Source</span>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-icon">📱</div>
                    <h3>Mobile Game Development</h3>
                    <p>Native and cross-platform mobile games optimized for iOS and Android.</p>
                    <div class="service-tags">
                        <span class="service-tag">iOS</span>
                        <span class="service-tag">Android</span>
                        <span class="service-tag">Hyper-casual</span>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-icon">🌐</div>
                    <h3>HTML5 & Web Games</h3>
                    <p>Browser-based games, playable ads, and interactive web experiences.</p>
                    <div class="service-tags">
                        <span class="service-tag">Playable Ads</span>
                        <span class="service-tag">Web Apps</span>
                        <span class="service-tag">Canvas</span>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-icon">🛠️</div>
                    <h3>Game Porting & Optimization</h3>
                    <p>Port your existing game to new platforms or optimize performance.</p>
                    <div class="service-tags">
                        <span class="service-tag">Cross-platform</span>
                        <span class="service-tag">Performance</span>
                        <span class="service-tag">Console</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Us Section -->
    <section class="hire-section" style="background: var(--hire-surface-alt);">
        <div class="hire-container">
            <div class="hire-section-header">
                <h2>Why Work With Us</h2>
                <p>We're not just developers — we're your partners in bringing ideas to life</p>
            </div>
            <div class="why-us-grid">
                <div class="why-us-card">
                    <div class="why-us-icon">💰</div>
                    <div class="why-us-content">
                        <h3>Competitive Rates</h3>
                        <p>High-quality development at 40-60% lower cost compared to Western agencies. No compromise on quality.</p>
                    </div>
                </div>
                <div class="why-us-card">
                    <div class="why-us-icon">🗣️</div>
                    <div class="why-us-content">
                        <h3>Fluent English</h3>
                        <p>Clear communication, detailed documentation, and daily updates in English. No language barriers.</p>
                    </div>
                </div>
                <div class="why-us-card">
                    <div class="why-us-icon">🌍</div>
                    <div class="why-us-content">
                        <h3>EU-Friendly Timezone</h3>
                        <p>UTC+7 timezone with flexible hours. Overlap available for European and US clients.</p>
                    </div>
                </div>
                <div class="why-us-card">
                    <div class="why-us-icon">⚡</div>
                    <div class="why-us-content">
                        <h3>Fast Turnaround</h3>
                        <p>Agile methodology with 2-week sprints. Regular demos and quick iterations.</p>
                    </div>
                </div>
                <div class="why-us-card">
                    <div class="why-us-icon">🔒</div>
                    <div class="why-us-content">
                        <h3>Full IP Ownership</h3>
                        <p>You own 100% of the code and assets. NDA signed before project start.</p>
                    </div>
                </div>
                <div class="why-us-card">
                    <div class="why-us-icon">🎯</div>
                    <div class="why-us-content">
                        <h3>Dedicated Team</h3>
                        <p>Your project gets dedicated resources. Direct communication with developers, not middlemen.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="hire-section">
        <div class="hire-container">
            <div class="hire-section-header">
                <h2>How We Work</h2>
                <p>A streamlined process from first contact to project delivery</p>
            </div>
            <div class="process-timeline">
                <div class="process-step">
                    <div class="process-step-num">1</div>
                    <h4>Discovery Call</h4>
                    <p>Free consultation to understand your vision and requirements</p>
                </div>
                <div class="process-step">
                    <div class="process-step-num">2</div>
                    <h4>Proposal & Planning</h4>
                    <p>Detailed scope, timeline, and transparent pricing</p>
                </div>
                <div class="process-step">
                    <div class="process-step-num">3</div>
                    <h4>Development</h4>
                    <p>Agile sprints with regular demos and feedback loops</p>
                </div>
                <div class="process-step">
                    <div class="process-step-num">4</div>
                    <h4>QA & Launch</h4>
                    <p>Thorough testing, deployment, and post-launch support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tech Stack -->
    <section class="hire-section" style="background: var(--hire-surface-alt);">
        <div class="hire-container">
            <div class="hire-section-header">
                <h2>Our Tech Stack</h2>
                <p>Industry-standard tools and technologies</p>
            </div>
            <div class="tech-stack">
                <div class="tech-item">
                    <span style="font-size: 24px;">🎮</span> Unity
                </div>
                <div class="tech-item">
                    <span style="font-size: 24px;">🔥</span> Unreal Engine
                </div>
                <div class="tech-item">
                    <span style="font-size: 24px;">🌿</span> Godot
                </div>
                <div class="tech-item">
                    <span style="font-size: 24px;">📘</span> C#
                </div>
                <div class="tech-item">
                    <span style="font-size: 24px;">⚡</span> C++
                </div>
                <div class="tech-item">
                    <span style="font-size: 24px;">🎨</span> Blender
                </div>
                <div class="tech-item">
                    <span style="font-size: 24px;">🖼️</span> Photoshop
                </div>
                <div class="tech-item">
                    <span style="font-size: 24px;">🔧</span> Git
                </div>
                <div class="tech-item">
                    <span style="font-size: 24px;">☁️</span> AWS/GCP
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="hire-section" id="pricing">
        <div class="hire-container">
            <div class="hire-section-header">
                <h2>Engagement Models</h2>
                <p>Flexible options to match your project needs</p>
            </div>
            <div class="pricing-grid">
                <div class="pricing-card">
                    <h3>Fixed Price</h3>
                    <div class="price">From $5,000</div>
                    <div class="price-note">Per project</div>
                    <ul class="pricing-features">
                        <li>Best for well-defined scope</li>
                        <li>Fixed budget & timeline</li>
                        <li>Milestone-based payments</li>
                        <li>Full ownership on delivery</li>
                        <li>30-day post-launch support</li>
                    </ul>
                    <a href="#contact" class="btn-primary" style="display: block;">Get Quote</a>
                </div>
                <div class="pricing-card featured">
                    <h3>Dedicated Team</h3>
                    <div class="price">$2,500/mo</div>
                    <div class="price-note">Per developer</div>
                    <ul class="pricing-features">
                        <li>Ideal for ongoing projects</li>
                        <li>Dedicated resources</li>
                        <li>Flexible scope changes</li>
                        <li>Direct communication</li>
                        <li>Monthly billing</li>
                    </ul>
                    <a href="#contact" class="btn-primary" style="display: block;">Get Quote</a>
                </div>
                <div class="pricing-card">
                    <h3>Hourly</h3>
                    <div class="price">$25-45/hr</div>
                    <div class="price-note">Based on skill level</div>
                    <ul class="pricing-features">
                        <li>Maximum flexibility</li>
                        <li>Pay only for work done</li>
                        <li>Detailed time tracking</li>
                        <li>Weekly invoicing</li>
                        <li>Scale up/down anytime</li>
                    </ul>
                    <a href="#contact" class="btn-primary" style="display: block;">Get Quote</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="hire-section" style="background: var(--hire-surface-alt);">
        <div class="hire-container">
            <div class="hire-section-header">
                <h2>What Clients Say</h2>
                <p>Trusted by game studios and indie developers worldwide</p>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <p class="testimonial-quote">"The team delivered our mobile game prototype in just 6 weeks. Communication was excellent and they really understood our vision. Highly recommended!"</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">M</div>
                        <div class="testimonial-info">
                            <strong>Marcus T.</strong>
                            <span>Indie Game Studio, Germany</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-quote">"Professional, responsive, and technically skilled. They helped us port our Unity game to WebGL with smooth performance. Great value for money."</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">S</div>
                        <div class="testimonial-info">
                            <strong>Sarah L.</strong>
                            <span>Game Publisher, Netherlands</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-quote">"We've been working with LamGame for over a year now. They're like an extension of our team. Quality work and always on time."</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">J</div>
                        <div class="testimonial-info">
                            <strong>James K.</strong>
                            <span>Mobile Games Company, UK</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="hire-section" id="contact">
        <div class="hire-container">
            <div class="contact-section">
                <div class="contact-grid">
                    <div class="contact-info">
                        <h2>Let's Build Something Great</h2>
                        <p>Tell us about your project and we'll get back to you within 24 hours with a free consultation.</p>
                        
                        <div class="contact-details">
                            <div class="contact-item">
                                <div class="contact-item-icon">📧</div>
                                <div class="contact-item-text">
                                    <strong>Email</strong>
                                    <span>hello@lamgame.vn</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-item-icon">💬</div>
                                <div class="contact-item-text">
                                    <strong>Discord</strong>
                                    <span>discord.gg/lamgame</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-item-icon">🕐</div>
                                <div class="contact-item-text">
                                    <strong>Working Hours</strong>
                                    <span>Mon-Fri, 9AM-6PM (UTC+7)</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-item-icon">📍</div>
                                <div class="contact-item-text">
                                    <strong>Location</strong>
                                    <span>Ho Chi Minh City, Vietnam</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-form-wrap">
                        <div id="hire-form-message" class="form-message"></div>
                        <form class="hire-form" id="hireFormEN" onsubmit="event.preventDefault(); submitHireFormEN();">
                            @csrf
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Your Name *</label>
                                    <input type="text" name="name" required maxlength="100" placeholder="John Smith">
                                </div>
                                <div class="form-group">
                                    <label>Email *</label>
                                    <input type="email" name="email" required maxlength="255" placeholder="john@company.com">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Company</label>
                                    <input type="text" name="company" maxlength="255" placeholder="Your Company">
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" name="country" maxlength="100" placeholder="Germany">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Project Type *</label>
                                    <select name="project_type" required>
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
                                    <label>Budget Range</label>
                                    <select name="budget_range">
                                        <option value="">Not sure yet</option>
                                        <option value="< $5,000">Under $5,000</option>
                                        <option value="$5,000 - $15,000">$5,000 - $15,000</option>
                                        <option value="$15,000 - $50,000">$15,000 - $50,000</option>
                                        <option value="> $50,000">Over $50,000</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Project Description *</label>
                                <textarea name="description" required maxlength="5000" placeholder="Tell us about your project: What are you building? Target platforms? Key features? Timeline?"></textarea>
                            </div>
                            <button type="submit" class="btn-primary" id="hireSubmitBtnEN" style="width: 100%;">
                                Send Project Brief
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="hire-container">
            <h2>Ready to Start Your Project?</h2>
            <p>Get a free consultation and project estimate within 24 hours</p>
            <a href="#contact" class="btn-primary">Get Free Quote</a>
        </div>
    </section>
</div>
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
        btn.textContent = 'Send Project Brief';
        if (d.status === 'success') {
            msg.className = 'form-message success';
            msg.innerHTML = '✓ Thank you! We\'ve received your project brief and will get back to you within 24 hours.';
            msg.style.display = 'block';
            form.reset();
            // Scroll to message
            msg.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            const errors = d.errors ? Object.values(d.errors).flat().join('<br>') : (d.message || 'Something went wrong. Please try again.');
            msg.className = 'form-message error';
            msg.innerHTML = errors;
            msg.style.display = 'block';
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.textContent = 'Send Project Brief';
        msg.className = 'form-message error';
        msg.innerHTML = 'Network error. Please try again or email us directly at hello@lamgame.vn';
        msg.style.display = 'block';
    });
}
</script>
@endpush
