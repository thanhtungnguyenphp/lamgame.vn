<div class="pillar-page pillar-page--career">
    <div class="container">
        {{-- Hero --}}
        <header class="pillar-hero">
            <nav class="pillar-breadcrumb">
                <a href="{{ url('/') }}">Startseite</a> / 
                <a href="{{ route('lamgame.blog') }}">Learn</a> / 
                <span>Career</span>
            </nav>
            <h1>💼 Game-Developer-Karriere</h1>
            <p class="pillar-hero__lead">
                Der Weg zum Game Developer in Vietnam. Von Fähigkeiten und Lernen bis zu Jobs und Gehalt.
            </p>
            <div class="pillar-hero__stats">
                <span>📚 {{ $articleCount ?? 0 }} Artikel</span>
                <span>💼 {{ $jobCount ?? 0 }} Jobs</span>
                <span>🏢 10+ VN-Spielestudios</span>
            </div>
        </header>

        <div class="pillar-layout">
            {{-- Main Content --}}
            <main class="pillar-main">
                {{-- TOC --}}
                <nav class="pillar-toc">
                    <h2>📑 Inhaltsverzeichnis</h2>
                    <ol>
                        <li><a href="#tong-quan">Überblick über die VN-Spielebranche</a></li>
                        <li><a href="#vai-tro">Rollen in der Spieleentwicklung</a></li>
                        <li><a href="#ky-nang">Erforderliche Fähigkeiten</a></li>
                        <li><a href="#roadmap">Lern-Roadmap</a></li>
                        <li><a href="#luong">Referenzgehalt</a></li>
                        <li><a href="#studio">Vietnamesische Spielestudios</a></li>
                        <li><a href="#interview">Interview-Vorbereitung</a></li>
                        <li><a href="#viec-lam">Neueste Jobs</a></li>
                    </ol>
                </nav>

                {{-- Section: Überblick --}}
                <section id="tong-quan" class="pillar-section">
                    <h2>Überblick über die vietnamesische Spielebranche</h2>
                    <p>
                        Vietnams Spielebranche wächst stark mit vielen inländischen und Outsourcing-Studios.
                        Das ist eine großartige Chance für Entwickler, die ihre Leidenschaft für Spiele verfolgen wollen.
                    </p>
                    
                    <div class="pillar-highlight">
                        <h4>📊 VN-Spielebranche 2026</h4>
                        <ul>
                            <li><strong>VNG:</strong> Das größte Spielestudio mit vielen erfolgreichen IPs</li>
                            <li><strong>Gameloft Vietnam:</strong> AAA-Mobile-Games, 800+ Mitarbeiter</li>
                            <li><strong>Glass Egg:</strong> Art-Outsourcing für globale Studios</li>
                            <li><strong>Amanotes:</strong> Hypercasual-Games, 3+ Milliarden Downloads</li>
                            <li><strong>Sparx*:</strong> Outsourcing für AAA-Titel</li>
                        </ul>
                    </div>
                    
                    <h3>Trends 2026</h3>
                    <ul>
                        <li>Mobile-Games machen weiterhin einen großen Anteil aus</li>
                        <li>KI-Integration in der Spieleentwicklung</li>
                        <li>Remote-Arbeitsmöglichkeiten nehmen zu</li>
                        <li>Die Indie-Szene wächst</li>
                    </ul>
                </section>

                {{-- Section: Rollen --}}
                <section id="vai-tro" class="pillar-section">
                    <h2>Rollen in der Spieleentwicklung</h2>
                    
                    <div class="career-roles">
                        <div class="career-role">
                            <h4>💻 Programmierer</h4>
                            <ul>
                                <li>Gameplay Programmer</li>
                                <li>Engine Programmer</li>
                                <li>Tools Programmer</li>
                                <li>Backend/Server Developer</li>
                                <li>AI Programmer</li>
                            </ul>
                        </div>
                        <div class="career-role">
                            <h4>🎨 Artist</h4>
                            <ul>
                                <li>2D Artist / Illustrator</li>
                                <li>3D Modeler</li>
                                <li>Technical Artist</li>
                                <li>Animator</li>
                                <li>UI/UX Artist</li>
                            </ul>
                        </div>
                        <div class="career-role">
                            <h4>🎯 Design</h4>
                            <ul>
                                <li>Game Designer</li>
                                <li>Level Designer</li>
                                <li>Narrative Designer</li>
                                <li>System Designer</li>
                                <li>Economy Designer</li>
                            </ul>
                        </div>
                        <div class="career-role">
                            <h4>📋 Produktion</h4>
                            <ul>
                                <li>Producer</li>
                                <li>Product Manager</li>
                                <li>QA Tester</li>
                                <li>Project Manager</li>
                                <li>Data Analyst</li>
                            </ul>
                        </div>
                    </div>
                </section>

                {{-- Section: Fähigkeiten --}}
                <section id="ky-nang" class="pillar-section">
                    <h2>Erforderliche Fähigkeiten</h2>
                    
                    <h3>Game Programmer</h3>
                    <table class="pillar-table">
                        <thead>
                            <tr>
                                <th>Fähigkeit</th>
                                <th>Junior</th>
                                <th>Mid</th>
                                <th>Senior</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>C# / C++</td>
                                <td>Grundlagen</td>
                                <td>Versiert</td>
                                <td>Experte</td>
                            </tr>
                            <tr>
                                <td>Unity / Unreal</td>
                                <td>1 Engine</td>
                                <td>1 Engine + Kenntnis einer weiteren</td>
                                <td>Multi-Engine</td>
                            </tr>
                            <tr>
                                <td>Mathe / Physik</td>
                                <td>Grundlagen</td>
                                <td>Lineare Algebra, Physik</td>
                                <td>Fortgeschritten</td>
                            </tr>
                            <tr>
                                <td>Design Patterns</td>
                                <td>Kennt sie</td>
                                <td>Kann sie anwenden</td>
                                <td>Architect</td>
                            </tr>
                            <tr>
                                <td>Git</td>
                                <td>Basis</td>
                                <td>Branching, Merge</td>
                                <td>CI/CD</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h3>Wichtige Soft Skills</h3>
                    <ul>
                        <li><strong>Kommunikation:</strong> Zusammenarbeit mit Designern und Artists</li>
                        <li><strong>Problemlösung:</strong> Debuggen, optimieren</li>
                        <li><strong>Zeitmanagement:</strong> Deadlines, Sprints</li>
                        <li><strong>Teamarbeit:</strong> Agile/Scrum</li>
                        <li><strong>Englisch:</strong> Dokumentation lesen, mit globalen Teams kommunizieren</li>
                    </ul>
                </section>

                {{-- Section: Roadmap --}}
                <section id="roadmap" class="pillar-section">
                    <h2>Lern-Roadmap</h2>
                    
                    <div class="career-roadmap">
                        <div class="roadmap-phase">
                            <h4>📚 Phase 1: Grundlagen (3-6 Monate)</h4>
                            <ul>
                                <li>Grundlegendes C# oder C++ lernen</li>
                                <li>Mit Unity oder Godot vertraut werden</li>
                                <li>2-3 Mini-Projekte abschließen</li>
                                <li>Grundlegendes Git lernen</li>
                            </ul>
                        </div>
                        <div class="roadmap-phase">
                            <h4>🎮 Phase 2: Kernfähigkeiten (6-12 Monate)</h4>
                            <ul>
                                <li>1 komplettes Spiel bauen (2D-Platformer, Puzzle)</li>
                                <li>OOP, Design Patterns lernen</li>
                                <li>Grundlegende Physik, Kollision, KI</li>
                                <li>UI/UX in Spielen</li>
                            </ul>
                        </div>
                        <div class="roadmap-phase">
                            <h4>💪 Phase 3: Fortgeschritten (12-24 Monate)</h4>
                            <ul>
                                <li>Multiplayer-Networking</li>
                                <li>Performance-Optimierung</li>
                                <li>Shader-Programmierung</li>
                                <li>Ein Spiel im Store veröffentlichen</li>
                            </ul>
                        </div>
                        <div class="roadmap-phase">
                            <h4>💼 Phase 4: Jobbereit</h4>
                            <ul>
                                <li>Portfolio mit 3-5 Projekten</li>
                                <li>Aktives GitHub</li>
                                <li>Auf Praktikums-/Junior-Stellen bewerben</li>
                            </ul>
                        </div>
                    </div>
                </section>

                {{-- Section: Gehalt --}}
                <section id="luong" class="pillar-section">
                    <h2>Referenzgehalt (2026)</h2>
                    
                    <div class="pillar-warning">
                        <h4>⚠️ Hinweis</h4>
                        <p>
                            Das Gehalt hängt von Unternehmen, Position, Erfahrung und Fähigkeiten ab.
                            Die Zahlen unten sind Referenzwerte aus Recruiting-Quellen.
                        </p>
                    </div>
                    
                    <table class="pillar-table">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Junior</th>
                                <th>Mid</th>
                                <th>Senior</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Unity Developer</td>
                                <td>10-18M</td>
                                <td>18-35M</td>
                                <td>35-60M+</td>
                            </tr>
                            <tr>
                                <td>Game Designer</td>
                                <td>10-15M</td>
                                <td>15-30M</td>
                                <td>30-50M+</td>
                            </tr>
                            <tr>
                                <td>3D Artist</td>
                                <td>10-18M</td>
                                <td>18-35M</td>
                                <td>35-55M+</td>
                            </tr>
                            <tr>
                                <td>Technical Artist</td>
                                <td>15-22M</td>
                                <td>22-40M</td>
                                <td>40-70M+</td>
                            </tr>
                            <tr>
                                <td>Game Producer</td>
                                <td>15-25M</td>
                                <td>25-45M</td>
                                <td>45-80M+</td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="pillar-note">* Einheit: VND/Monat. Ohne Bonus und Aktienoptionen.</p>
                </section>

                {{-- Section: Studios --}}
                <section id="studio" class="pillar-section">
                    <h2>Vietnamesische Spielestudios</h2>
                    
                    <div class="studio-grid">
                        <div class="studio-card">
                            <h4>🎮 VNG Corporation</h4>
                            <p>HCMC & Hanoi</p>
                            <p>ZingPlay, viele inländische IPs</p>
                        </div>
                        <div class="studio-card">
                            <h4>🎮 Gameloft Vietnam</h4>
                            <p>HCMC & Da Nang</p>
                            <p>AAA-Mobile-Games</p>
                        </div>
                        <div class="studio-card">
                            <h4>🎨 Glass Egg</h4>
                            <p>HCMC</p>
                            <p>Art-Outsourcing</p>
                        </div>
                        <div class="studio-card">
                            <h4>🎵 Amanotes</h4>
                            <p>Hanoi</p>
                            <p>Musikspiele, Hypercasual</p>
                        </div>
                        <div class="studio-card">
                            <h4>🎮 Sparx*</h4>
                            <p>HCMC</p>
                            <p>AAA-Outsourcing</p>
                        </div>
                        <div class="studio-card">
                            <h4>🎮 Sky Mavis</h4>
                            <p>HCMC</p>
                            <p>Axie Infinity, Blockchain-Games</p>
                        </div>
                    </div>
                </section>

                {{-- Section: Interview --}}
                <section id="interview" class="pillar-section">
                    <h2>Interview-Vorbereitung</h2>
                    
                    <h3>Technisches Interview</h3>
                    <ul>
                        <li>OOP-Konzepte (Vererbung, Polymorphie, Kapselung)</li>
                        <li>Design Patterns (Singleton, Observer, State, Factory)</li>
                        <li>Datenstrukturen (List, Dictionary, Queue, Stack)</li>
                        <li>Unity/Unreal-spezifisch: MonoBehaviour-Lebenszyklus, Coroutines, ScriptableObjects</li>
                        <li>Mathe: Vektoroperationen, Skalar-/Kreuzprodukt, Quaternionen</li>
                    </ul>
                    
                    <h3>Häufige Coding-Tests</h3>
                    <ul>
                        <li>Eine einfache Gameplay-Mechanik implementieren</li>
                        <li>Fehler in bereitgestelltem Code beheben</li>
                        <li>Die Performance einer Funktion optimieren</li>
                        <li>Eine Systemarchitektur entwerfen</li>
                    </ul>
                    
                    <h3>Portfolio-Tipps</h3>
                    <ul>
                        <li>Qualität > Quantität: 3-5 ausgefeilte Projekte</li>
                        <li>Mindestens 1 spielbares Projekt</li>
                        <li>Sauberer Code mit Kommentaren</li>
                        <li>README, das Funktionen und Tech-Stack erklärt</li>
                        <li>Ein Demo-Video, wenn möglich</li>
                    </ul>
                </section>

                {{-- Section: Jobs --}}
                <section id="viec-lam" class="pillar-section">
                    <h2>💼 Game-Developer-Jobs</h2>
                    @if(isset($jobs) && count($jobs) > 0)
                    <div class="pillar-jobs">
                        @foreach($jobs as $job)
                        <article class="pillar-job-card">
                            <h3><a href="{{ route('lamgame.job.detail', $job->slug) }}">{{ $job->title }}</a></h3>
                            <div class="job-meta">
                                <span>{{ $job->company_name ?? 'N/A' }}</span>
                                <span>{{ $job->location ?? 'N/A' }}</span>
                            </div>
                        </article>
                        @endforeach
                    </div>
                    <a href="{{ route('lamgame.viec-lam-game') }}" class="pillar-btn pillar-btn--outline">
                        Alle Jobs ansehen →
                    </a>
                    @else
                    <p class="pillar-empty">
                        <a href="{{ route('lamgame.viec-lam-game') }}">Die neuesten Game-Jobs ansehen</a>
                    </p>
                    @endif
                </section>
            </main>

            {{-- Sidebar --}}
            <aside class="pillar-sidebar">
                <div class="pillar-sidebar__sticky">
                    {{-- CTA --}}
                    <div class="pillar-cta-box">
                        <h3>💼 Game-Dev-Jobs finden</h3>
                        <p>Jobs von Top-Studios</p>
                        <a href="{{ route('lamgame.viec-lam-game') }}" class="pillar-btn pillar-btn--primary">
                            Jobs ansehen →
                        </a>
                    </div>

                    {{-- Related Pillars --}}
                    <div class="pillar-related">
                        <h4>Fähigkeiten lernen</h4>
                        <ul>
                            <li><a href="{{ route('learn.unity') }}">🎮 Unity lernen</a></li>
                            <li><a href="{{ route('learn.godot') }}">🤖 Godot lernen</a></li>
                            <li><a href="{{ route('learn.ai-game-dev') }}">🤖 KI für Game Dev</a></li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
