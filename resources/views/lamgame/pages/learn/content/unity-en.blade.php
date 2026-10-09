<div class="pillar-page">
    <div class="container">
        {{-- Hero --}}
        <header class="pillar-hero">
            <nav class="pillar-breadcrumb">
                <a href="{{ url('/') }}">Home</a> / 
                <a href="{{ route('lamgame.blog') }}">Learn</a> / 
                <span>Unity</span>
            </nav>
            <h1>🎮 Learn Unity from A-Z</h1>
            <p class="pillar-hero__lead">
                A complete guide to becoming a Unity Developer. From the basics to publishing a game to the store.
            </p>
            <div class="pillar-hero__stats">
                <span>📚 {{ $articleCount ?? 0 }} articles</span>
                <span>🎮 {{ $sourceCount ?? 0 }} source code</span>
                <span>💼 {{ $jobCount ?? 0 }} jobs</span>
            </div>
        </header>

        <div class="pillar-layout">
            {{-- Main Content --}}
            <main class="pillar-main">
                {{-- TOC --}}
                <nav class="pillar-toc">
                    <h2>📑 Table of Contents</h2>
                    <ol>
                        <li><a href="#unity-la-gi">What is Unity?</a></li>
                        <li><a href="#bat-dau">Getting started with Unity</a></li>
                        <li><a href="#co-ban">Fundamentals</a></li>
                        <li><a href="#nang-cao">Advanced techniques</a></li>
                        <li><a href="#best-practices">Best Practices</a></li>
                        <li><a href="#source-code">Source Code</a></li>
                        <li><a href="#viec-lam">Unity jobs</a></li>
                        <li><a href="#tai-nguyen">Learning resources</a></li>
                    </ol>
                </nav>

                {{-- Section: What is Unity --}}
                <section id="unity-la-gi" class="pillar-section">
                    <h2>What is Unity?</h2>
                    <p>
                        Unity is the world's most popular game engine, used to develop 2D, 3D and
                        VR/AR games across many platforms. With Unity, you can create games for:
                    </p>
                    <ul>
                        <li><strong>Mobile:</strong> iOS, Android</li>
                        <li><strong>Desktop:</strong> Windows, macOS, Linux</li>
                        <li><strong>Console:</strong> PlayStation, Xbox, Nintendo Switch</li>
                        <li><strong>Web:</strong> WebGL</li>
                        <li><strong>XR:</strong> VR (Oculus, HTC Vive), AR (ARKit, ARCore)</li>
                    </ul>
                    <p>
                        Unity uses C# as its main programming language — easy to learn with a large community.
                    </p>
                </section>

                {{-- Section: Getting started --}}
                <section id="bat-dau" class="pillar-section">
                    <h2>Getting started with Unity</h2>
                    <h3>1. Install Unity Hub</h3>
                    <p>
                        Download Unity Hub from <a href="https://unity.com/download" target="_blank" rel="noopener">unity.com/download</a> 
                        and install the latest Unity LTS version (2022 LTS or Unity 6).
                    </p>
                    
                    <h3>2. Create your first project</h3>
                    <p>
                        In Unity Hub, select "New Project" → choose a template (2D, 3D, URP, HDRP) → name it and create.
                    </p>
                    
                    <h3>3. Get familiar with the Editor</h3>
                    <ul>
                        <li><strong>Scene View:</strong> Where you design your game</li>
                        <li><strong>Game View:</strong> Preview the game while running</li>
                        <li><strong>Hierarchy:</strong> The list of GameObjects in the scene</li>
                        <li><strong>Inspector:</strong> Details and components of an object</li>
                        <li><strong>Project:</strong> Assets and files</li>
                    </ul>
                </section>

                {{-- Section: Fundamentals --}}
                <section id="co-ban" class="pillar-section">
                    <h2>Fundamentals</h2>
                    
                    <h3>GameObject & Components</h3>
                    <p>
                        Everything in Unity is a GameObject. Each GameObject can have many Components
                        attached, such as Transform, Renderer, Collider, Script, etc.
                    </p>
                    
                    <h3>Scripting with C#</h3>
                    <p>
                        Scripts in Unity inherit from MonoBehaviour. The important methods are:
                    </p>
                    <ul>
                        <li><code>Start()</code> — Runs once when the object is enabled</li>
                        <li><code>Update()</code> — Runs every frame</li>
                        <li><code>FixedUpdate()</code> — Runs on the physics timestep</li>
                        <li><code>OnCollisionEnter()</code> — On collision</li>
                    </ul>
                    
                    <h3>Related articles</h3>
                    <div class="pillar-articles">
                        @forelse($unityArticles ?? [] as $article)
                        <a href="{{ route('blog.show', $article->slug) }}" class="pillar-article-card">
                            <h4>{{ $article->name }}</h4>
                            <span>{{ $article->reading_time ?? 5 }} min read</span>
                        </a>
                        @empty
                        <p>No articles yet. <a href="{{ route('lamgame.blog') }}?category=unity-development">View all Unity articles →</a></p>
                        @endforelse
                    </div>
                </section>

                {{-- Section: Advanced --}}
                <section id="nang-cao" class="pillar-section">
                    <h2>Advanced techniques</h2>
                    <ul>
                        <li><strong>Addressables:</strong> Manage assets efficiently</li>
                        <li><strong>Networking:</strong> Multiplayer with Netcode/Mirror</li>
                        <li><strong>Shader Graph:</strong> Visual shader editor</li>
                        <li><strong>DOTS/ECS:</strong> Data-Oriented Tech Stack</li>
                        <li><strong>Optimization:</strong> Profiling, memory, batching</li>
                    </ul>
                </section>

                {{-- Section: Best Practices --}}
                <section id="best-practices" class="pillar-section">
                    <h2>Best Practices</h2>
                    <ul>
                        <li>Use Object Pooling instead of constant Instantiate/Destroy</li>
                        <li>Avoid GetComponent in Update, cache references</li>
                        <li>Use ScriptableObjects for data</li>
                        <li>Organize the project with a standard folder structure</li>
                        <li>Version control with Git LFS for large assets</li>
                    </ul>
                </section>

                {{-- Section: Source Code --}}
                <section id="source-code" class="pillar-section">
                    <h2>🎮 Unity Source Code</h2>
                    <p>Save time with ready-made Unity source code:</p>
                    <div class="pillar-sources">
                        @forelse($unitySources ?? [] as $source)
                        <a href="{{ $source['url'] }}" class="pillar-source-card">
                            <img src="{{ $source['thumbnail'] }}" alt="{{ $source['title'] }}" loading="lazy">
                            <div>
                                <h4>{{ $source['title'] }}</h4>
                                <span>{{ $source['is_free'] ? 'Free' : number_format($source['price'], 0, ',', '.') . 'đ' }}</span>
                            </div>
                        </a>
                        @empty
                        <p><a href="{{ route('lamgame.source-game') }}?engine=unity">View all Unity Source →</a></p>
                        @endforelse
                    </div>
                </section>

                {{-- Section: Jobs --}}
                <section id="viec-lam" class="pillar-section">
                    <h2>💼 Unity Developer jobs</h2>
                    <p>
                        Unity Developer is one of the hottest positions in the game industry in Vietnam.
                        The average salary ranges from 15-40M VND/month depending on experience.
                    </p>
                    <a href="{{ route('lamgame.viec-lam-game') }}?keyword=unity" class="pillar-btn">
                        View {{ $jobCount ?? 0 }}+ Unity jobs →
                    </a>
                </section>

                {{-- Section: Resources --}}
                <section id="tai-nguyen" class="pillar-section">
                    <h2>📚 Learning resources</h2>
                    <ul>
                        <li><a href="https://learn.unity.com" target="_blank" rel="noopener">Unity Learn</a> — Official tutorials</li>
                        <li><a href="https://docs.unity.com" target="_blank" rel="noopener">Unity Documentation</a> — API reference</li>
                        <li><a href="{{ route('forum.index') }}">LamGame Forum</a> — Community Q&A</li>
                        <li><a href="{{ route('lamgame.blog') }}?category=unity-development">Unity Blog</a> — Latest articles</li>
                    </ul>
                </section>

                {{-- Last Updated --}}
                <footer class="pillar-footer">
                    <p><strong>Last updated:</strong> {{ now()->format('d/m/Y') }}</p>
                    <p>
                        This article was written by the <a href="{{ route('authors.index') }}">LamGame team</a> 
                        to provide a comprehensive guide for Unity learners.
                    </p>
                </footer>
            </main>

            {{-- Sidebar --}}
            <aside class="pillar-sidebar">
                <div class="pillar-sidebar__card">
                    <h3>🔗 Quick Links</h3>
                    <ul>
                        <li><a href="{{ route('lamgame.source-game') }}?engine=unity">Unity Source Games</a></li>
                        <li><a href="{{ route('lamgame.viec-lam-game') }}?keyword=unity">Unity Jobs</a></li>
                        <li><a href="{{ route('lamgame.blog') }}?category=unity-development">Unity Blog</a></li>
                        <li><a href="{{ route('forum.index') }}?category=unity">Unity Forum</a></li>
                    </ul>
                </div>
                
                <div class="pillar-sidebar__card">
                    <h3>📊 Unity at LamGame</h3>
                    <ul>
                        <li>{{ $articleCount ?? 0 }} articles</li>
                        <li>{{ $sourceCount ?? 0 }} source code</li>
                        <li>{{ $jobCount ?? 0 }} jobs</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</div>
