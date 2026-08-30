@extends('layouts.master')

@section('page_title', 'Portfolio | LamGame Studio - Game Development Projects')
@section('page_description', 'Explore our game development portfolio. Unity, Unreal Engine, mobile games, and web games built for clients worldwide.')

@push('meta')
<meta name="description" content="Explore our game development portfolio. Unity, Unreal Engine, mobile games, and web games built for clients worldwide.">
<meta name="keywords" content="game development portfolio, unity projects, game studio work, mobile game examples">
<meta property="og:title" content="Portfolio | LamGame Studio">
<meta property="og:description" content="See our game development work - 50+ projects delivered for clients worldwide.">
<meta property="og:url" content="{{ url('/portfolio') }}">
<link rel="canonical" href="{{ url('/portfolio') }}">

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "LamGame Studio Portfolio",
    "description": "Game development portfolio showcasing Unity, Unreal Engine, and mobile game projects",
    "url": "{{ url('/portfolio') }}"
}
</script>
@endpush

@push('styles')
<style>
:root {
    --port-bg: #0D0D1A;
    --port-surface: #161625;
    --port-surface-alt: #1E1E30;
    --port-border: #2A2A40;
    --port-text: #F0F0F5;
    --port-text-muted: #8B8BA3;
    --port-accent: #8B5CF6;
    --port-accent-hover: #A78BFA;
}

.portfolio-page { background: var(--port-bg); color: var(--port-text); min-height: 100vh; }
.portfolio-container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }

/* Hero */
.portfolio-hero {
    padding: 80px 0 60px;
    text-align: center;
    background: linear-gradient(180deg, #1a1a2e 0%, var(--port-bg) 100%);
}
.portfolio-hero h1 {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 800;
    margin-bottom: 16px;
}
.portfolio-hero p {
    color: var(--port-text-muted);
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}

/* Filter */
.portfolio-filter {
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
    padding: 40px 0;
    border-bottom: 1px solid var(--port-border);
}
.filter-btn {
    background: var(--port-surface);
    border: 1px solid var(--port-border);
    color: var(--port-text-muted);
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
}
.filter-btn:hover, .filter-btn.active {
    background: var(--port-accent);
    border-color: var(--port-accent);
    color: #fff;
}

/* Projects Grid */
.projects-section { padding: 60px 0; }
.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 32px;
}
@media (max-width: 768px) {
    .projects-grid { grid-template-columns: 1fr; }
}

/* Project Card */
.project-card {
    background: var(--port-surface);
    border: 1px solid var(--port-border);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}
.project-card:hover {
    border-color: var(--port-accent);
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}
.project-image {
    position: relative;
    height: 220px;
    background: var(--port-surface-alt);
    overflow: hidden;
}
.project-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}
.project-card:hover .project-image img {
    transform: scale(1.05);
}
.project-badges {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.project-badge {
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(4px);
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}
.project-badge.engine { background: rgba(139, 92, 246, 0.8); }
.project-badge.platform { background: rgba(16, 185, 129, 0.8); }

.project-content {
    padding: 24px;
}
.project-content h3 {
    font-size: 1.25rem;
    margin-bottom: 8px;
}
.project-client {
    color: var(--port-accent);
    font-size: 14px;
    margin-bottom: 12px;
}
.project-description {
    color: var(--port-text-muted);
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 16px;
}
.project-tech {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 16px;
}
.tech-tag {
    background: var(--port-surface-alt);
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    color: var(--port-text-muted);
}
.project-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--port-accent);
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
}
.project-link:hover {
    color: var(--port-accent-hover);
}

/* Case Study Modal */
.case-study-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.8);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.case-study-overlay.active { display: flex; }
.case-study-modal {
    background: var(--port-surface);
    border-radius: 20px;
    max-width: 900px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
}
.case-study-close {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(255,255,255,0.1);
    border: none;
    color: #fff;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 24px;
}
.case-study-header {
    position: relative;
    height: 300px;
    background: var(--port-surface-alt);
}
.case-study-header img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.case-study-content {
    padding: 40px;
}
.case-study-content h2 {
    font-size: 2rem;
    margin-bottom: 8px;
}
.case-study-meta {
    display: flex;
    gap: 24px;
    color: var(--port-text-muted);
    margin-bottom: 32px;
    flex-wrap: wrap;
}
.case-study-section {
    margin-bottom: 32px;
}
.case-study-section h3 {
    font-size: 1.1rem;
    margin-bottom: 12px;
    color: var(--port-accent);
}
.case-study-section p {
    color: var(--port-text-muted);
    line-height: 1.7;
}
.case-study-results {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 20px;
}
@media (max-width: 600px) { .case-study-results { grid-template-columns: 1fr; } }
.result-item {
    text-align: center;
    padding: 20px;
    background: var(--port-surface-alt);
    border-radius: 12px;
}
.result-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--port-accent);
}
.result-label {
    font-size: 14px;
    color: var(--port-text-muted);
    margin-top: 4px;
}

/* CTA */
.portfolio-cta {
    text-align: center;
    padding: 80px 0;
    background: linear-gradient(180deg, var(--port-bg) 0%, #1a1a2e 100%);
}
.portfolio-cta h2 {
    font-size: 2rem;
    margin-bottom: 16px;
}
.portfolio-cta p {
    color: var(--port-text-muted);
    margin-bottom: 32px;
}
.btn-primary {
    background: linear-gradient(135deg, #8B5CF6 0%, #6366F1 100%);
    color: #fff;
    padding: 16px 32px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 40px rgba(139, 92, 246, 0.3);
}
</style>
@endpush

@section('content')
<div class="portfolio-page">
    <!-- Hero -->
    <section class="portfolio-hero">
        <div class="portfolio-container">
            <h1>Our Portfolio</h1>
            <p>Explore our game development projects. From mobile games to PC titles, we've helped clients worldwide bring their visions to life.</p>
        </div>
    </section>

    <!-- Filter -->
    <section class="portfolio-container">
        <div class="portfolio-filter">
            <button class="filter-btn active" data-filter="all">All Projects</button>
            <button class="filter-btn" data-filter="unity">Unity</button>
            <button class="filter-btn" data-filter="unreal">Unreal Engine</button>
            <button class="filter-btn" data-filter="godot">Godot</button>
            <button class="filter-btn" data-filter="mobile">Mobile</button>
            <button class="filter-btn" data-filter="web">Web/HTML5</button>
        </div>
    </section>

    <!-- Projects Grid -->
    <section class="projects-section">
        <div class="portfolio-container">
            <div class="projects-grid">
                
                <!-- Project 1: Mobile Puzzle Game -->
                <div class="project-card" data-category="unity mobile">
                    <div class="project-image">
                        <img src="{{ asset('images/portfolio/puzzle-game.svg') }}" alt="Puzzle Adventure Game" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 220%22><rect fill=%22%231E1E30%22 width=%22400%22 height=%22220%22/><text x=%22200%22 y=%22110%22 fill=%22%238B5CF6%22 font-size=%2240%22 text-anchor=%22middle%22>🧩</text></svg>'">
                        <div class="project-badges">
                            <span class="project-badge engine">Unity</span>
                            <span class="project-badge platform">iOS/Android</span>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>Puzzle Adventure</h3>
                        <div class="project-client">Indie Studio, Germany</div>
                        <p class="project-description">A casual puzzle game with 200+ levels, daily challenges, and social features. Built with Unity for iOS and Android.</p>
                        <div class="project-tech">
                            <span class="tech-tag">Unity 2022</span>
                            <span class="tech-tag">C#</span>
                            <span class="tech-tag">Firebase</span>
                            <span class="tech-tag">AdMob</span>
                        </div>
                        <a href="#" class="project-link" onclick="openCaseStudy('puzzle'); return false;">View Case Study →</a>
                    </div>
                </div>

                <!-- Project 2: 3D Platformer -->
                <div class="project-card" data-category="unity mobile">
                    <div class="project-image">
                        <img src="{{ asset('images/portfolio/platformer.svg') }}" alt="3D Platformer Game" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 220%22><rect fill=%22%231E1E30%22 width=%22400%22 height=%22220%22/><text x=%22200%22 y=%22110%22 fill=%22%2310B981%22 font-size=%2240%22 text-anchor=%22middle%22>🎮</text></svg>'">
                        <div class="project-badges">
                            <span class="project-badge engine">Unity</span>
                            <span class="project-badge platform">PC/Mobile</span>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>Sky Runner 3D</h3>
                        <div class="project-client">Game Publisher, Netherlands</div>
                        <p class="project-description">Fast-paced 3D platformer with procedurally generated levels. Optimized for mobile with 60fps performance.</p>
                        <div class="project-tech">
                            <span class="tech-tag">Unity URP</span>
                            <span class="tech-tag">DOTween</span>
                            <span class="tech-tag">Shader Graph</span>
                        </div>
                        <a href="#" class="project-link" onclick="openCaseStudy('platformer'); return false;">View Case Study →</a>
                    </div>
                </div>

                <!-- Project 3: Card Game -->
                <div class="project-card" data-category="unity mobile">
                    <div class="project-image">
                        <img src="{{ asset('images/portfolio/card-game.svg') }}" alt="Card Battle Game" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 220%22><rect fill=%22%231E1E30%22 width=%22400%22 height=%22220%22/><text x=%22200%22 y=%22110%22 fill=%22%23F59E0B%22 font-size=%2240%22 text-anchor=%22middle%22>🃏</text></svg>'">
                        <div class="project-badges">
                            <span class="project-badge engine">Unity</span>
                            <span class="project-badge platform">Mobile</span>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>Card Clash Arena</h3>
                        <div class="project-client">Startup, UK</div>
                        <p class="project-description">Multiplayer collectible card game with real-time PvP battles, deck building, and seasonal events.</p>
                        <div class="project-tech">
                            <span class="tech-tag">Unity</span>
                            <span class="tech-tag">Photon PUN</span>
                            <span class="tech-tag">PlayFab</span>
                        </div>
                        <a href="#" class="project-link" onclick="openCaseStudy('card'); return false;">View Case Study →</a>
                    </div>
                </div>

                <!-- Project 4: HTML5 Game -->
                <div class="project-card" data-category="web">
                    <div class="project-image">
                        <img src="{{ asset('images/portfolio/html5-game.svg') }}" alt="HTML5 Playable Ad" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 220%22><rect fill=%22%231E1E30%22 width=%22400%22 height=%22220%22/><text x=%22200%22 y=%22110%22 fill=%22%23EC4899%22 font-size=%2240%22 text-anchor=%22middle%22>🌐</text></svg>'">
                        <div class="project-badges">
                            <span class="project-badge engine">HTML5</span>
                            <span class="project-badge platform">Web</span>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>Playable Ad Campaign</h3>
                        <div class="project-client">Ad Network, France</div>
                        <p class="project-description">10 interactive playable ads for mobile game marketing campaigns. Under 2MB, 60fps across all devices.</p>
                        <div class="project-tech">
                            <span class="tech-tag">PixiJS</span>
                            <span class="tech-tag">JavaScript</span>
                            <span class="tech-tag">GSAP</span>
                        </div>
                        <a href="#" class="project-link" onclick="openCaseStudy('playable'); return false;">View Case Study →</a>
                    </div>
                </div>

                <!-- Project 5: Unreal Project -->
                <div class="project-card" data-category="unreal">
                    <div class="project-image">
                        <img src="{{ asset('images/portfolio/unreal-game.svg') }}" alt="Unreal Engine Game" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 220%22><rect fill=%22%231E1E30%22 width=%22400%22 height=%22220%22/><text x=%22200%22 y=%22110%22 fill=%22%23EF4444%22 font-size=%2240%22 text-anchor=%22middle%22>🔥</text></svg>'">
                        <div class="project-badges">
                            <span class="project-badge engine">Unreal</span>
                            <span class="project-badge platform">PC</span>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>Horror Survival Demo</h3>
                        <div class="project-client">Indie Developer, Sweden</div>
                        <p class="project-description">First-person horror game prototype with photorealistic graphics and advanced AI systems.</p>
                        <div class="project-tech">
                            <span class="tech-tag">Unreal 5</span>
                            <span class="tech-tag">Blueprints</span>
                            <span class="tech-tag">Lumen</span>
                        </div>
                        <a href="#" class="project-link" onclick="openCaseStudy('horror'); return false;">View Case Study →</a>
                    </div>
                </div>

                <!-- Project 6: Godot Game -->
                <div class="project-card" data-category="godot">
                    <div class="project-image">
                        <img src="{{ asset('images/portfolio/godot-game.svg') }}" alt="Godot 2D Game" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 220%22><rect fill=%22%231E1E30%22 width=%22400%22 height=%22220%22/><text x=%22200%22 y=%22110%22 fill=%22%2322D3EE%22 font-size=%2240%22 text-anchor=%22middle%22>🌿</text></svg>'">
                        <div class="project-badges">
                            <span class="project-badge engine">Godot</span>
                            <span class="project-badge platform">PC/Web</span>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>Pixel Roguelike</h3>
                        <div class="project-client">Solo Developer, Poland</div>
                        <p class="project-description">Retro-style roguelike with procedural dungeons, permadeath, and pixel art graphics.</p>
                        <div class="project-tech">
                            <span class="tech-tag">Godot 4</span>
                            <span class="tech-tag">GDScript</span>
                            <span class="tech-tag">Aseprite</span>
                        </div>
                        <a href="#" class="project-link" onclick="openCaseStudy('roguelike'); return false;">View Case Study →</a>
                    </div>
                </div>

                <!-- Project 7: VR Game -->
                <div class="project-card" data-category="unity">
                    <div class="project-image">
                        <img src="{{ asset('images/portfolio/vr-game.svg') }}" alt="VR Game" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 220%22><rect fill=%22%231E1E30%22 width=%22400%22 height=%22220%22/><text x=%22200%22 y=%22110%22 fill=%22%236366F1%22 font-size=%2240%22 text-anchor=%22middle%22>🥽</text></svg>'">
                        <div class="project-badges">
                            <span class="project-badge engine">Unity</span>
                            <span class="project-badge platform">Quest 2</span>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>VR Training Simulator</h3>
                        <div class="project-client">Enterprise, USA</div>
                        <p class="project-description">Industrial training VR application with hand tracking and realistic physics simulation.</p>
                        <div class="project-tech">
                            <span class="tech-tag">Unity XR</span>
                            <span class="tech-tag">Oculus SDK</span>
                            <span class="tech-tag">Hand Tracking</span>
                        </div>
                        <a href="#" class="project-link" onclick="openCaseStudy('vr'); return false;">View Case Study →</a>
                    </div>
                </div>

                <!-- Project 8: Hyper Casual -->
                <div class="project-card" data-category="unity mobile">
                    <div class="project-image">
                        <img src="{{ asset('images/portfolio/hypercasual.svg') }}" alt="Hyper Casual Game" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 220%22><rect fill=%22%231E1E30%22 width=%22400%22 height=%22220%22/><text x=%22200%22 y=%22110%22 fill=%22%23A855F7%22 font-size=%2240%22 text-anchor=%22middle%22>⚡</text></svg>'">
                        <div class="project-badges">
                            <span class="project-badge engine">Unity</span>
                            <span class="project-badge platform">Mobile</span>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>Hyper Casual Bundle</h3>
                        <div class="project-client">Publisher, Turkey</div>
                        <p class="project-description">5 hyper-casual games delivered in 8 weeks. Quick prototypes optimized for UA testing.</p>
                        <div class="project-tech">
                            <span class="tech-tag">Unity</span>
                            <span class="tech-tag">IronSource</span>
                            <span class="tech-tag">GameAnalytics</span>
                        </div>
                        <a href="#" class="project-link" onclick="openCaseStudy('hypercasual'); return false;">View Case Study →</a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="portfolio-cta">
        <div class="portfolio-container">
            <h2>Have a Project in Mind?</h2>
            <p>Let's discuss how we can bring your game idea to life</p>
            <a href="/hire#contact" class="btn-primary">Start Your Project</a>
        </div>
    </section>
</div>

<!-- Case Study Modal -->
<div class="case-study-overlay" id="caseStudyOverlay" onclick="closeCaseStudy(event)">
    <div class="case-study-modal" onclick="event.stopPropagation()">
        <button class="case-study-close" onclick="closeCaseStudy()">&times;</button>
        <div class="case-study-header">
            <img id="caseStudyImage" src="" alt="">
        </div>
        <div class="case-study-content">
            <h2 id="caseStudyTitle"></h2>
            <div class="case-study-meta">
                <span id="caseStudyClient"></span>
                <span id="caseStudyDuration"></span>
                <span id="caseStudyTeam"></span>
            </div>
            
            <div class="case-study-section">
                <h3>The Challenge</h3>
                <p id="caseStudyChallenge"></p>
            </div>
            
            <div class="case-study-section">
                <h3>Our Solution</h3>
                <p id="caseStudySolution"></p>
            </div>
            
            <div class="case-study-section">
                <h3>Results</h3>
                <div class="case-study-results" id="caseStudyResults"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Filter functionality
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        const filter = btn.dataset.filter;
        document.querySelectorAll('.project-card').forEach(card => {
            if (filter === 'all' || card.dataset.category.includes(filter)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// Case studies data
const caseStudies = {
    puzzle: {
        title: 'Puzzle Adventure',
        image: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 300"><rect fill="%231E1E30" width="900" height="300"/><text x="450" y="150" fill="%238B5CF6" font-size="60" text-anchor="middle">🧩</text></svg>',
        client: '📍 Indie Studio, Germany',
        duration: '⏱️ 4 months',
        team: '👥 3 developers',
        challenge: 'The client needed a puzzle game that could compete with top titles in the casual gaming market. Key requirements included smooth performance on older devices, engaging gameplay loop, and robust monetization without being intrusive.',
        solution: 'We designed a unique puzzle mechanic combining match-3 with adventure elements. Implemented a custom level editor for rapid content creation, integrated Firebase for analytics and remote config, and optimized rendering to maintain 60fps on devices as old as iPhone 6S.',
        results: [
            { value: '500K+', label: 'Downloads' },
            { value: '4.7★', label: 'App Store Rating' },
            { value: '25%', label: 'Day 7 Retention' }
        ]
    },
    platformer: {
        title: 'Sky Runner 3D',
        image: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 300"><rect fill="%231E1E30" width="900" height="300"/><text x="450" y="150" fill="%2310B981" font-size="60" text-anchor="middle">🎮</text></svg>',
        client: '📍 Game Publisher, Netherlands',
        duration: '⏱️ 3 months',
        team: '👥 2 developers',
        challenge: 'Create a fast-paced 3D platformer that runs smoothly on mid-range mobile devices while maintaining visual quality. The game needed procedural level generation to ensure unlimited replayability.',
        solution: 'Used Unity\'s URP for optimized rendering, implemented custom LOD system, and created a modular chunk-based level generation system. Applied aggressive batching and GPU instancing for performance.',
        results: [
            { value: '60fps', label: 'Stable Performance' },
            { value: '50MB', label: 'App Size' },
            { value: '12min', label: 'Avg Session' }
        ]
    },
    card: {
        title: 'Card Clash Arena',
        image: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 300"><rect fill="%231E1E30" width="900" height="300"/><text x="450" y="150" fill="%23F59E0B" font-size="60" text-anchor="middle">🃏</text></svg>',
        client: '📍 Gaming Startup, UK',
        duration: '⏱️ 6 months',
        team: '👥 4 developers',
        challenge: 'Build a real-time multiplayer card game with robust matchmaking, anti-cheat measures, and a complex economy system. The game needed to handle 10,000+ concurrent players.',
        solution: 'Integrated Photon PUN for real-time multiplayer with custom server authoritative logic. Used PlayFab for backend services, economy management, and player data. Implemented extensive server-side validation.',
        results: [
            { value: '15K', label: 'DAU Peak' },
            { value: '$45K', label: 'Monthly Revenue' },
            { value: '<100ms', label: 'Avg Latency' }
        ]
    },
    playable: {
        title: 'Playable Ad Campaign',
        image: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 300"><rect fill="%231E1E30" width="900" height="300"/><text x="450" y="150" fill="%23EC4899" font-size="60" text-anchor="middle">🌐</text></svg>',
        client: '📍 Ad Network, France',
        duration: '⏱️ 6 weeks',
        team: '👥 2 developers',
        challenge: 'Create 10 playable ads that load instantly, run at 60fps on all devices, and stay under 2MB file size. Each ad needed to showcase a different mobile game while maintaining engagement.',
        solution: 'Built a custom lightweight framework using PixiJS with aggressive asset compression. Implemented lazy loading, texture atlases, and minimal code footprint. Created reusable component system for rapid iteration.',
        results: [
            { value: '<1.5MB', label: 'Avg File Size' },
            { value: '8.5%', label: 'CTR Improvement' },
            { value: '10', label: 'Ads Delivered' }
        ]
    },
    horror: {
        title: 'Horror Survival Demo',
        image: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 300"><rect fill="%231E1E30" width="900" height="300"/><text x="450" y="150" fill="%23EF4444" font-size="60" text-anchor="middle">🔥</text></svg>',
        client: '📍 Indie Developer, Sweden',
        duration: '⏱️ 3 months',
        team: '👥 3 developers',
        challenge: 'Create a visually stunning horror game prototype using Unreal Engine 5 that could be used to pitch to publishers. Required photorealistic graphics and advanced AI behavior.',
        solution: 'Leveraged Unreal 5\'s Lumen for real-time global illumination and Nanite for detailed environments. Implemented behavior tree-based AI with dynamic patrol and chase states. Created atmospheric audio system.',
        results: [
            { value: 'Publisher', label: 'Deal Secured' },
            { value: '30min', label: 'Demo Length' },
            { value: '4K/60', label: 'Target Specs' }
        ]
    },
    roguelike: {
        title: 'Pixel Roguelike',
        image: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 300"><rect fill="%231E1E30" width="900" height="300"/><text x="450" y="150" fill="%2322D3EE" font-size="60" text-anchor="middle">🌿</text></svg>',
        client: '📍 Solo Developer, Poland',
        duration: '⏱️ 4 months',
        team: '👥 2 developers',
        challenge: 'Help a solo developer complete their roguelike game within budget. Required procedural dungeon generation, pixel-perfect movement, and multiple character classes.',
        solution: 'Used Godot 4 for lightweight development. Implemented custom dungeon generator using BSP trees, created modular ability system, and built robust save system. Delivered with WebGL export for itch.io.',
        results: [
            { value: '10K+', label: 'itch.io Players' },
            { value: '92%', label: 'Positive Reviews' },
            { value: 'On Budget', label: 'Delivery' }
        ]
    },
    vr: {
        title: 'VR Training Simulator',
        image: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 300"><rect fill="%231E1E30" width="900" height="300"/><text x="450" y="150" fill="%236366F1" font-size="60" text-anchor="middle">🥽</text></svg>',
        client: '📍 Enterprise, USA',
        duration: '⏱️ 5 months',
        team: '👥 4 developers',
        challenge: 'Create a VR training application for industrial safety procedures. Required realistic hand interactions, physics-based objects, and comprehensive analytics tracking.',
        solution: 'Built on Unity XR with Oculus Integration SDK. Implemented custom hand tracking interactions, physics-based tool manipulation, and integration with enterprise LMS for training certification.',
        results: [
            { value: '40%', label: 'Training Time Reduced' },
            { value: '500+', label: 'Employees Trained' },
            { value: '$200K', label: 'Contract Value' }
        ]
    },
    hypercasual: {
        title: 'Hyper Casual Bundle',
        image: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 300"><rect fill="%231E1E30" width="900" height="300"/><text x="450" y="150" fill="%23A855F7" font-size="60" text-anchor="middle">⚡</text></svg>',
        client: '📍 Publisher, Turkey',
        duration: '⏱️ 8 weeks',
        team: '👥 2 developers',
        challenge: 'Deliver 5 hyper-casual game prototypes optimized for UA testing within tight timeline. Each game needed to be under 50MB with instant loading and seamless ad integration.',
        solution: 'Created a rapid prototyping framework with pre-built components for common mechanics. Integrated IronSource mediation, GameAnalytics, and A/B testing from day one. Streamlined approval process.',
        results: [
            { value: '5 Games', label: 'In 8 Weeks' },
            { value: '2 Scaled', label: 'To Publishing' },
            { value: '1M+', label: 'Total Installs' }
        ]
    }
};

function openCaseStudy(key) {
    const study = caseStudies[key];
    if (!study) return;
    
    document.getElementById('caseStudyImage').src = study.image;
    document.getElementById('caseStudyTitle').textContent = study.title;
    document.getElementById('caseStudyClient').textContent = study.client;
    document.getElementById('caseStudyDuration').textContent = study.duration;
    document.getElementById('caseStudyTeam').textContent = study.team;
    document.getElementById('caseStudyChallenge').textContent = study.challenge;
    document.getElementById('caseStudySolution').textContent = study.solution;
    
    const resultsHtml = study.results.map(r => `
        <div class="result-item">
            <div class="result-value">${r.value}</div>
            <div class="result-label">${r.label}</div>
        </div>
    `).join('');
    document.getElementById('caseStudyResults').innerHTML = resultsHtml;
    
    document.getElementById('caseStudyOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeCaseStudy(event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById('caseStudyOverlay').classList.remove('active');
    document.body.style.overflow = '';
}

// Close on escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeCaseStudy();
});
</script>
@endpush
