@extends('layouts.master')

@section('page_title', 'Our Team | LamGame Studio')
@section('page_description', 'Meet the talented game developers behind LamGame Studio. Experienced Unity, Unreal Engine, and mobile game development team from Vietnam.')

@push('meta')
<meta name="keywords" content="game development team, unity developers, vietnam game studio, game programmers">
<meta property="og:title" content="Our Team | LamGame Studio">
<meta property="og:description" content="Meet the talented developers behind LamGame Studio.">
<meta property="og:url" content="{{ url('/team') }}">
<link rel="canonical" href="{{ url('/team') }}">

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "LamGame Studio",
    "url": "{{ url('/') }}",
    "member": [
        {
            "@type": "Person",
            "name": "Tung Nguyen",
            "jobTitle": "Founder & Lead Developer"
        }
    ]
}
</script>
@endpush

@push('styles')
<style>
:root {
    --team-bg: #0D0D1A;
    --team-surface: #161625;
    --team-surface-alt: #1E1E30;
    --team-border: #2A2A40;
    --team-text: #F0F0F5;
    --team-text-muted: #8B8BA3;
    --team-accent: #8B5CF6;
}

.team-page { background: var(--team-bg); color: var(--team-text); min-height: 100vh; }
.team-container { max-width: 1100px; margin: 0 auto; padding: 0 24px; }

/* Hero */
.team-hero {
    padding: 80px 0 60px;
    text-align: center;
    background: linear-gradient(180deg, #1a1a2e 0%, var(--team-bg) 100%);
}
.team-hero h1 {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 800;
    margin-bottom: 16px;
}
.team-hero p {
    color: var(--team-text-muted);
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}

/* Team Grid */
.team-section { padding: 60px 0; }
.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 32px;
    margin-bottom: 60px;
}

/* Team Card */
.team-card {
    background: var(--team-surface);
    border: 1px solid var(--team-border);
    border-radius: 16px;
    padding: 32px;
    text-align: center;
    transition: all 0.3s ease;
}
.team-card:hover {
    border-color: var(--team-accent);
    transform: translateY(-4px);
}
.team-avatar {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #8B5CF6 0%, #6366F1 100%);
    border-radius: 50%;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    font-weight: 700;
    color: #fff;
}
.team-card h3 {
    font-size: 1.25rem;
    margin-bottom: 4px;
}
.team-role {
    color: var(--team-accent);
    font-size: 14px;
    margin-bottom: 16px;
}
.team-bio {
    color: var(--team-text-muted);
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 20px;
}
.team-skills {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
    margin-bottom: 20px;
}
.skill-tag {
    background: var(--team-surface-alt);
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    color: var(--team-text-muted);
}
.team-social {
    display: flex;
    justify-content: center;
    gap: 12px;
}
.team-social a {
    width: 36px;
    height: 36px;
    background: var(--team-surface-alt);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--team-text-muted);
    transition: all 0.2s;
    text-decoration: none;
}
.team-social a:hover {
    background: var(--team-accent);
    color: #fff;
}

/* Values Section */
.values-section {
    padding: 60px 0;
    background: var(--team-surface-alt);
}
.values-header {
    text-align: center;
    margin-bottom: 48px;
}
.values-header h2 {
    font-size: 2rem;
    margin-bottom: 12px;
}
.values-header p {
    color: var(--team-text-muted);
}
.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
}
.value-card {
    background: var(--team-surface);
    border: 1px solid var(--team-border);
    border-radius: 12px;
    padding: 24px;
    text-align: center;
}
.value-icon {
    font-size: 40px;
    margin-bottom: 16px;
}
.value-card h3 {
    font-size: 1.1rem;
    margin-bottom: 8px;
}
.value-card p {
    color: var(--team-text-muted);
    font-size: 14px;
    line-height: 1.6;
}

/* Join Section */
.join-section {
    padding: 80px 0;
    text-align: center;
}
.join-section h2 {
    font-size: 2rem;
    margin-bottom: 16px;
}
.join-section p {
    color: var(--team-text-muted);
    margin-bottom: 32px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}
.btn-primary {
    background: linear-gradient(135deg, #8B5CF6 0%, #6366F1 100%);
    color: #fff;
    padding: 14px 28px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
}
</style>
@endpush

@section('content')
<div class="team-page">
    <!-- Hero -->
    <section class="team-hero">
        <div class="team-container">
            <h1>Meet Our Team</h1>
            <p>Passionate game developers dedicated to bringing your ideas to life. Based in Vietnam, working globally.</p>
        </div>
    </section>

    <!-- Team Members -->
    <section class="team-section">
        <div class="team-container">
            <div class="team-grid">
                
                <!-- Founder -->
                <div class="team-card">
                    <div class="team-avatar">T</div>
                    <h3>Tung Nguyen</h3>
                    <div class="team-role">Founder & Lead Developer</div>
                    <p class="team-bio">5+ years in game development. Specialized in Unity and full-stack development. Passionate about building tools for game developers.</p>
                    <div class="team-skills">
                        <span class="skill-tag">Unity</span>
                        <span class="skill-tag">C#</span>
                        <span class="skill-tag">Laravel</span>
                        <span class="skill-tag">React</span>
                    </div>
                    <div class="team-social">
                        <a href="https://github.com/thanhtungnguyenphp" target="_blank" rel="noopener" title="GitHub">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        </a>
                        <a href="https://linkedin.com/in/thanhtungnguyen" target="_blank" rel="noopener" title="LinkedIn">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Developer 1 -->
                <div class="team-card">
                    <div class="team-avatar">M</div>
                    <h3>Minh Le</h3>
                    <div class="team-role">Senior Unity Developer</div>
                    <p class="team-bio">4+ years building mobile games. Expert in optimization and multiplayer systems. Shipped 20+ games on iOS and Android.</p>
                    <div class="team-skills">
                        <span class="skill-tag">Unity</span>
                        <span class="skill-tag">C#</span>
                        <span class="skill-tag">Photon</span>
                        <span class="skill-tag">Mobile</span>
                    </div>
                    <div class="team-social">
                        <a href="#" title="LinkedIn">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Developer 2 -->
                <div class="team-card">
                    <div class="team-avatar">H</div>
                    <h3>Hoa Tran</h3>
                    <div class="team-role">Game Designer & Artist</div>
                    <p class="team-bio">Creative professional with background in game design and 2D/3D art. Creates engaging gameplay experiences and beautiful visuals.</p>
                    <div class="team-skills">
                        <span class="skill-tag">Game Design</span>
                        <span class="skill-tag">Blender</span>
                        <span class="skill-tag">Photoshop</span>
                        <span class="skill-tag">UI/UX</span>
                    </div>
                    <div class="team-social">
                        <a href="#" title="ArtStation">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M0 17.723l2.027 3.505h.001a2.424 2.424 0 0 0 2.164 1.333h13.457l-2.792-4.838H0zm24-2.026-3.796-6.581a2.424 2.424 0 0 0-2.101-1.216H6.189l9.596 16.633L24 15.697zm-6.084-10.559-5.529 9.581 2.792 4.838L24 5.138h-6.084z"/></svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <div class="team-container">
            <div class="values-header">
                <h2>Our Values</h2>
                <p>What drives us to deliver exceptional work</p>
            </div>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">🎯</div>
                    <h3>Quality First</h3>
                    <p>We don't cut corners. Every line of code is written with care and attention to detail.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">🤝</div>
                    <h3>Clear Communication</h3>
                    <p>Regular updates, honest feedback, and no surprises. You always know where your project stands.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">⚡</div>
                    <h3>Fast Delivery</h3>
                    <p>We respect deadlines. Agile methodology ensures we deliver on time, every time.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">💡</div>
                    <h3>Creative Solutions</h3>
                    <p>We don't just code — we solve problems and bring creative ideas to technical challenges.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Join Section -->
    <section class="join-section">
        <div class="team-container">
            <h2>Want to Work Together?</h2>
            <p>We're always looking for talented developers and exciting projects. Let's build something great.</p>
            <a href="/hire#contact" class="btn-primary">Start a Project</a>
        </div>
    </section>
</div>
@endsection
