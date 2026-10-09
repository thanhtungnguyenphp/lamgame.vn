<div class="pillar-page">
    <div class="container">
        {{-- Hero --}}
        <header class="pillar-hero">
            <nav class="pillar-breadcrumb">
                <a href="{{ url('/') }}">Startseite</a> / 
                <a href="{{ route('lamgame.blog') }}">Learn</a> / 
                <span>Unity</span>
            </nav>
            <h1>🎮 Unity von A bis Z lernen</h1>
            <p class="pillar-hero__lead">
                Ein kompletter Leitfaden, um Unity-Entwickler zu werden. Von den Grundlagen bis zur Veröffentlichung eines Spiels im Store.
            </p>
            <div class="pillar-hero__stats">
                <span>📚 {{ $articleCount ?? 0 }} Artikel</span>
                <span>🎮 {{ $sourceCount ?? 0 }} Quellcode</span>
                <span>💼 {{ $jobCount ?? 0 }} Jobs</span>
            </div>
        </header>

        <div class="pillar-layout">
            {{-- Main Content --}}
            <main class="pillar-main">
                {{-- TOC --}}
                <nav class="pillar-toc">
                    <h2>📑 Inhaltsverzeichnis</h2>
                    <ol>
                        <li><a href="#unity-la-gi">Was ist Unity?</a></li>
                        <li><a href="#bat-dau">Erste Schritte mit Unity</a></li>
                        <li><a href="#co-ban">Grundlagen</a></li>
                        <li><a href="#nang-cao">Fortgeschrittene Techniken</a></li>
                        <li><a href="#best-practices">Best Practices</a></li>
                        <li><a href="#source-code">Quellcode</a></li>
                        <li><a href="#viec-lam">Unity-Jobs</a></li>
                        <li><a href="#tai-nguyen">Lernressourcen</a></li>
                    </ol>
                </nav>

                {{-- Section: Was ist Unity --}}
                <section id="unity-la-gi" class="pillar-section">
                    <h2>Was ist Unity?</h2>
                    <p>
                        Unity ist die weltweit beliebteste Game-Engine, die zur Entwicklung von 2D-, 3D- und
                        VR/AR-Spielen auf vielen Plattformen verwendet wird. Mit Unity können Sie Spiele erstellen für:
                    </p>
                    <ul>
                        <li><strong>Mobile:</strong> iOS, Android</li>
                        <li><strong>Desktop:</strong> Windows, macOS, Linux</li>
                        <li><strong>Konsole:</strong> PlayStation, Xbox, Nintendo Switch</li>
                        <li><strong>Web:</strong> WebGL</li>
                        <li><strong>XR:</strong> VR (Oculus, HTC Vive), AR (ARKit, ARCore)</li>
                    </ul>
                    <p>
                        Unity verwendet C# als Hauptprogrammiersprache — leicht zu lernen mit einer großen Community.
                    </p>
                </section>

                {{-- Section: Erste Schritte --}}
                <section id="bat-dau" class="pillar-section">
                    <h2>Erste Schritte mit Unity</h2>
                    <h3>1. Unity Hub installieren</h3>
                    <p>
                        Laden Sie Unity Hub von <a href="https://unity.com/download" target="_blank" rel="noopener">unity.com/download</a> 
                        herunter und installieren Sie die neueste Unity-LTS-Version (2022 LTS oder Unity 6).
                    </p>
                    
                    <h3>2. Ihr erstes Projekt erstellen</h3>
                    <p>
                        Wählen Sie im Unity Hub „New Project" → wählen Sie eine Vorlage (2D, 3D, URP, HDRP) → benennen und erstellen.
                    </p>
                    
                    <h3>3. Mit dem Editor vertraut werden</h3>
                    <ul>
                        <li><strong>Scene View:</strong> Wo Sie Ihr Spiel gestalten</li>
                        <li><strong>Game View:</strong> Vorschau des Spiels während der Ausführung</li>
                        <li><strong>Hierarchy:</strong> Die Liste der GameObjects in der Szene</li>
                        <li><strong>Inspector:</strong> Details und Komponenten eines Objekts</li>
                        <li><strong>Project:</strong> Assets und Dateien</li>
                    </ul>
                </section>

                {{-- Section: Grundlagen --}}
                <section id="co-ban" class="pillar-section">
                    <h2>Grundlagen</h2>
                    
                    <h3>GameObject & Komponenten</h3>
                    <p>
                        Alles in Unity ist ein GameObject. Jedes GameObject kann viele Komponenten
                        haben, wie Transform, Renderer, Collider, Script usw.
                    </p>
                    
                    <h3>Scripting mit C#</h3>
                    <p>
                        Skripte in Unity erben von MonoBehaviour. Die wichtigen Methoden sind:
                    </p>
                    <ul>
                        <li><code>Start()</code> — Läuft einmal, wenn das Objekt aktiviert wird</li>
                        <li><code>Update()</code> — Läuft in jedem Frame</li>
                        <li><code>FixedUpdate()</code> — Läuft im Physik-Timestep</li>
                        <li><code>OnCollisionEnter()</code> — Bei einer Kollision</li>
                    </ul>
                    
                    <h3>Ähnliche Artikel</h3>
                    <div class="pillar-articles">
                        @forelse($unityArticles ?? [] as $article)
                        <a href="{{ route('blog.show', $article->slug) }}" class="pillar-article-card">
                            <h4>{{ $article->name }}</h4>
                            <span>{{ $article->reading_time ?? 5 }} Min. Lesezeit</span>
                        </a>
                        @empty
                        <p>Noch keine Artikel. <a href="{{ route('lamgame.blog') }}?category=unity-development">Alle Unity-Artikel ansehen →</a></p>
                        @endforelse
                    </div>
                </section>

                {{-- Section: Fortgeschritten --}}
                <section id="nang-cao" class="pillar-section">
                    <h2>Fortgeschrittene Techniken</h2>
                    <ul>
                        <li><strong>Addressables:</strong> Assets effizient verwalten</li>
                        <li><strong>Networking:</strong> Multiplayer mit Netcode/Mirror</li>
                        <li><strong>Shader Graph:</strong> Visueller Shader-Editor</li>
                        <li><strong>DOTS/ECS:</strong> Data-Oriented Tech Stack</li>
                        <li><strong>Optimierung:</strong> Profiling, Speicher, Batching</li>
                    </ul>
                </section>

                {{-- Section: Best Practices --}}
                <section id="best-practices" class="pillar-section">
                    <h2>Best Practices</h2>
                    <ul>
                        <li>Object Pooling statt ständigem Instantiate/Destroy verwenden</li>
                        <li>GetComponent in Update vermeiden, Referenzen cachen</li>
                        <li>ScriptableObjects für Daten verwenden</li>
                        <li>Das Projekt mit einer Standard-Ordnerstruktur organisieren</li>
                        <li>Versionskontrolle mit Git LFS für große Assets</li>
                    </ul>
                </section>

                {{-- Section: Quellcode --}}
                <section id="source-code" class="pillar-section">
                    <h2>🎮 Unity-Quellcode</h2>
                    <p>Sparen Sie Zeit mit fertigem Unity-Quellcode:</p>
                    <div class="pillar-sources">
                        @forelse($unitySources ?? [] as $source)
                        <a href="{{ $source['url'] }}" class="pillar-source-card">
                            <img src="{{ $source['thumbnail'] }}" alt="{{ $source['title'] }}" loading="lazy">
                            <div>
                                <h4>{{ $source['title'] }}</h4>
                                <span>{{ $source['is_free'] ? 'Kostenlos' : number_format($source['price'], 0, ',', '.') . 'đ' }}</span>
                            </div>
                        </a>
                        @empty
                        <p><a href="{{ route('lamgame.source-game') }}?engine=unity">Alle Unity-Quellen ansehen →</a></p>
                        @endforelse
                    </div>
                </section>

                {{-- Section: Jobs --}}
                <section id="viec-lam" class="pillar-section">
                    <h2>💼 Unity-Developer-Jobs</h2>
                    <p>
                        Unity Developer ist eine der gefragtesten Positionen der Spielebranche in Vietnam.
                        Das Durchschnittsgehalt liegt je nach Erfahrung zwischen 15-40 Mio. VND/Monat.
                    </p>
                    <a href="{{ route('lamgame.viec-lam-game') }}?keyword=unity" class="pillar-btn">
                        {{ $jobCount ?? 0 }}+ Unity-Jobs ansehen →
                    </a>
                </section>

                {{-- Section: Ressourcen --}}
                <section id="tai-nguyen" class="pillar-section">
                    <h2>📚 Lernressourcen</h2>
                    <ul>
                        <li><a href="https://learn.unity.com" target="_blank" rel="noopener">Unity Learn</a> — Offizielle Tutorials</li>
                        <li><a href="https://docs.unity.com" target="_blank" rel="noopener">Unity Documentation</a> — API-Referenz</li>
                        <li><a href="{{ route('forum.index') }}">LamGame Forum</a> — Community-Fragen & -Antworten</li>
                        <li><a href="{{ route('lamgame.blog') }}?category=unity-development">Unity Blog</a> — Neueste Artikel</li>
                    </ul>
                </section>

                {{-- Last Updated --}}
                <footer class="pillar-footer">
                    <p><strong>Zuletzt aktualisiert:</strong> {{ now()->format('d/m/Y') }}</p>
                    <p>
                        Dieser Artikel wurde vom <a href="{{ route('authors.index') }}">LamGame-Team</a> 
                        verfasst, um einen umfassenden Leitfaden für Unity-Lernende bereitzustellen.
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
                    <h3>📊 Unity bei LamGame</h3>
                    <ul>
                        <li>{{ $articleCount ?? 0 }} Artikel</li>
                        <li>{{ $sourceCount ?? 0 }} Quellcode</li>
                        <li>{{ $jobCount ?? 0 }} Jobs</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</div>
