<div class="pillar-page pillar-page--career">
    <div class="container">
        {{-- Hero --}}
        <header class="pillar-hero">
            <nav class="pillar-breadcrumb">
                <a href="{{ url('/') }}">Home</a> / 
                <a href="{{ route('lamgame.blog') }}">Learn</a> / 
                <span>Career</span>
            </nav>
            <h1>💼 Game Developer Career</h1>
            <p class="pillar-hero__lead">
                The path to becoming a Game Developer in Vietnam. From skills and learning to jobs and salary.
            </p>
            <div class="pillar-hero__stats">
                <span>📚 {{ $articleCount ?? 0 }} articles</span>
                <span>💼 {{ $jobCount ?? 0 }} jobs</span>
                <span>🏢 10+ VN game studios</span>
            </div>
        </header>

        <div class="pillar-layout">
            {{-- Main Content --}}
            <main class="pillar-main">
                {{-- TOC --}}
                <nav class="pillar-toc">
                    <h2>📑 Table of Contents</h2>
                    <ol>
                        <li><a href="#tong-quan">Vietnam Game Industry overview</a></li>
                        <li><a href="#vai-tro">Roles in Game Dev</a></li>
                        <li><a href="#ky-nang">Required skills</a></li>
                        <li><a href="#roadmap">Learning roadmap</a></li>
                        <li><a href="#luong">Reference salary</a></li>
                        <li><a href="#studio">Vietnam Game Studios</a></li>
                        <li><a href="#interview">Interview preparation</a></li>
                        <li><a href="#viec-lam">Latest jobs</a></li>
                    </ol>
                </nav>

                {{-- Section: Overview --}}
                <section id="tong-quan" class="pillar-section">
                    <h2>Vietnam Game Industry overview</h2>
                    <p>
                        Vietnam's game industry is growing strongly with many domestic and outsourcing studios.
                        This is a great opportunity for developers who want to pursue their passion for games.
                    </p>
                    
                    <div class="pillar-highlight">
                        <h4>📊 VN Game Industry 2026</h4>
                        <ul>
                            <li><strong>VNG:</strong> The largest game studio, with many successful IPs</li>
                            <li><strong>Gameloft Vietnam:</strong> AAA mobile games, 800+ employees</li>
                            <li><strong>Glass Egg:</strong> Art outsourcing for global studios</li>
                            <li><strong>Amanotes:</strong> Hypercasual games, 3+ billion downloads</li>
                            <li><strong>Sparx*:</strong> Outsourcing for AAA titles</li>
                        </ul>
                    </div>
                    
                    <h3>Trends in 2026</h3>
                    <ul>
                        <li>Mobile games still make up a large share</li>
                        <li>AI integration in game development</li>
                        <li>Remote work opportunities are increasing</li>
                        <li>The indie scene is growing</li>
                    </ul>
                </section>

                {{-- Section: Roles --}}
                <section id="vai-tro" class="pillar-section">
                    <h2>Roles in Game Development</h2>
                    
                    <div class="career-roles">
                        <div class="career-role">
                            <h4>💻 Programmer</h4>
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
                            <h4>📋 Production</h4>
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

                {{-- Section: Skills --}}
                <section id="ky-nang" class="pillar-section">
                    <h2>Required skills</h2>
                    
                    <h3>Game Programmer</h3>
                    <table class="pillar-table">
                        <thead>
                            <tr>
                                <th>Skill</th>
                                <th>Junior</th>
                                <th>Mid</th>
                                <th>Senior</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>C# / C++</td>
                                <td>Basic</td>
                                <td>Proficient</td>
                                <td>Expert</td>
                            </tr>
                            <tr>
                                <td>Unity / Unreal</td>
                                <td>1 engine</td>
                                <td>1 engine + knowledge of another</td>
                                <td>Multi-engine</td>
                            </tr>
                            <tr>
                                <td>Math / Physics</td>
                                <td>Basic</td>
                                <td>Linear algebra, physics</td>
                                <td>Advanced</td>
                            </tr>
                            <tr>
                                <td>Design Patterns</td>
                                <td>Aware</td>
                                <td>Can apply</td>
                                <td>Architect</td>
                            </tr>
                            <tr>
                                <td>Git</td>
                                <td>Basic</td>
                                <td>Branching, merge</td>
                                <td>CI/CD</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h3>Important soft skills</h3>
                    <ul>
                        <li><strong>Communication:</strong> Working with designers and artists</li>
                        <li><strong>Problem-solving:</strong> Debug, optimize</li>
                        <li><strong>Time management:</strong> Deadlines, sprints</li>
                        <li><strong>Teamwork:</strong> Agile/Scrum</li>
                        <li><strong>English:</strong> Reading docs, communicating with global teams</li>
                    </ul>
                </section>

                {{-- Section: Roadmap --}}
                <section id="roadmap" class="pillar-section">
                    <h2>Learning roadmap</h2>
                    
                    <div class="career-roadmap">
                        <div class="roadmap-phase">
                            <h4>📚 Phase 1: Foundation (3-6 months)</h4>
                            <ul>
                                <li>Learn basic C# or C++</li>
                                <li>Get familiar with Unity or Godot</li>
                                <li>Complete 2-3 mini projects</li>
                                <li>Learn basic Git</li>
                            </ul>
                        </div>
                        <div class="roadmap-phase">
                            <h4>🎮 Phase 2: Core Skills (6-12 months)</h4>
                            <ul>
                                <li>Build 1 complete game (2D platformer, puzzle)</li>
                                <li>Learn OOP, design patterns</li>
                                <li>Basic physics, collision, AI</li>
                                <li>UI/UX in games</li>
                            </ul>
                        </div>
                        <div class="roadmap-phase">
                            <h4>💪 Phase 3: Advanced (12-24 months)</h4>
                            <ul>
                                <li>Multiplayer networking</li>
                                <li>Performance optimization</li>
                                <li>Shader programming</li>
                                <li>Publish a game to the store</li>
                            </ul>
                        </div>
                        <div class="roadmap-phase">
                            <h4>💼 Phase 4: Job Ready</h4>
                            <ul>
                                <li>Portfolio of 3-5 projects</li>
                                <li>Active GitHub</li>
                                <li>Apply to internship/junior positions</li>
                            </ul>
                        </div>
                    </div>
                </section>

                {{-- Section: Salary --}}
                <section id="luong" class="pillar-section">
                    <h2>Reference salary (2026)</h2>
                    
                    <div class="pillar-warning">
                        <h4>⚠️ Note</h4>
                        <p>
                            Salary depends on the company, position, experience and skills.
                            The figures below are references from recruitment sources.
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
                    <p class="pillar-note">* Unit: VND/month. Excludes bonus and stock options.</p>
                </section>

                {{-- Section: Studios --}}
                <section id="studio" class="pillar-section">
                    <h2>Vietnam Game Studios</h2>
                    
                    <div class="studio-grid">
                        <div class="studio-card">
                            <h4>🎮 VNG Corporation</h4>
                            <p>HCMC & Hanoi</p>
                            <p>ZingPlay, many domestic IPs</p>
                        </div>
                        <div class="studio-card">
                            <h4>🎮 Gameloft Vietnam</h4>
                            <p>HCMC & Da Nang</p>
                            <p>AAA mobile games</p>
                        </div>
                        <div class="studio-card">
                            <h4>🎨 Glass Egg</h4>
                            <p>HCMC</p>
                            <p>Art outsourcing</p>
                        </div>
                        <div class="studio-card">
                            <h4>🎵 Amanotes</h4>
                            <p>Hanoi</p>
                            <p>Music games, hypercasual</p>
                        </div>
                        <div class="studio-card">
                            <h4>🎮 Sparx*</h4>
                            <p>HCMC</p>
                            <p>AAA outsourcing</p>
                        </div>
                        <div class="studio-card">
                            <h4>🎮 Sky Mavis</h4>
                            <p>HCMC</p>
                            <p>Axie Infinity, blockchain games</p>
                        </div>
                    </div>
                </section>

                {{-- Section: Interview --}}
                <section id="interview" class="pillar-section">
                    <h2>Interview preparation</h2>
                    
                    <h3>Technical Interview</h3>
                    <ul>
                        <li>OOP concepts (inheritance, polymorphism, encapsulation)</li>
                        <li>Design patterns (Singleton, Observer, State, Factory)</li>
                        <li>Data structures (List, Dictionary, Queue, Stack)</li>
                        <li>Unity/Unreal specific: MonoBehaviour lifecycle, Coroutines, ScriptableObjects</li>
                        <li>Math: Vector operations, dot/cross product, quaternions</li>
                    </ul>
                    
                    <h3>Common coding tests</h3>
                    <ul>
                        <li>Implement a simple gameplay mechanic</li>
                        <li>Fix bugs in provided code</li>
                        <li>Optimize the performance of a feature</li>
                        <li>Design a system architecture</li>
                    </ul>
                    
                    <h3>Portfolio Tips</h3>
                    <ul>
                        <li>Quality > Quantity: 3-5 polished projects</li>
                        <li>Have at least 1 playable project</li>
                        <li>Clean code with comments</li>
                        <li>README explaining features and tech stack</li>
                        <li>A demo video if possible</li>
                    </ul>
                </section>

                {{-- Section: Jobs --}}
                <section id="viec-lam" class="pillar-section">
                    <h2>💼 Game Developer jobs</h2>
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
                        View all jobs →
                    </a>
                    @else
                    <p class="pillar-empty">
                        <a href="{{ route('lamgame.viec-lam-game') }}">View the latest game jobs</a>
                    </p>
                    @endif
                </section>
            </main>

            {{-- Sidebar --}}
            <aside class="pillar-sidebar">
                <div class="pillar-sidebar__sticky">
                    {{-- CTA --}}
                    <div class="pillar-cta-box">
                        <h3>💼 Find Game Dev jobs</h3>
                        <p>Jobs from top studios</p>
                        <a href="{{ route('lamgame.viec-lam-game') }}" class="pillar-btn pillar-btn--primary">
                            View jobs →
                        </a>
                    </div>

                    {{-- Related Pillars --}}
                    <div class="pillar-related">
                        <h4>Learn skills</h4>
                        <ul>
                            <li><a href="{{ route('learn.unity') }}">🎮 Learn Unity</a></li>
                            <li><a href="{{ route('learn.godot') }}">🤖 Learn Godot</a></li>
                            <li><a href="{{ route('learn.ai-game-dev') }}">🤖 AI for Game Dev</a></li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
