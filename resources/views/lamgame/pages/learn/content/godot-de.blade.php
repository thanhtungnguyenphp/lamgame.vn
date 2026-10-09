<div class="pillar-page pillar-page--godot">
    <div class="container">
        {{-- Hero --}}
        <header class="pillar-hero">
            <nav class="pillar-breadcrumb">
                <a href="{{ url('/') }}">Startseite</a> / 
                <a href="{{ route('lamgame.blog') }}">Learn</a> / 
                <span>Godot</span>
            </nav>
            <h1>🤖 Godot von A bis Z lernen</h1>
            <p class="pillar-hero__lead">
                Eine kostenlose, quelloffene Game-Engine mit leicht erlernbarem GDScript. Die perfekte Wahl für Indie-Entwickler.
            </p>
            <div class="pillar-hero__stats">
                <span>📚 {{ $articleCount ?? 0 }} Artikel</span>
                <span>🎮 {{ $sourceCount ?? 0 }} Quellcode</span>
                <span>🆓 100% kostenlos</span>
            </div>
        </header>

        <div class="pillar-layout">
            {{-- Main Content --}}
            <main class="pillar-main">
                {{-- TOC --}}
                <nav class="pillar-toc">
                    <h2>📑 Inhaltsverzeichnis</h2>
                    <ol>
                        <li><a href="#godot-la-gi">Was ist Godot?</a></li>
                        <li><a href="#tai-sao-godot">Warum Godot wählen?</a></li>
                        <li><a href="#cai-dat">Godot 4 installieren</a></li>
                        <li><a href="#gdscript">GDScript lernen</a></li>
                        <li><a href="#game-2d">2D-Spiele erstellen</a></li>
                        <li><a href="#game-3d">3D-Spiele erstellen</a></li>
                        <li><a href="#export">Export & Veröffentlichung</a></li>
                        <li><a href="#tai-nguyen">Ressourcen</a></li>
                    </ol>
                </nav>

                {{-- Section: Was ist Godot --}}
                <section id="godot-la-gi" class="pillar-section">
                    <h2>Was ist Godot?</h2>
                    <p>
                        <strong>Godot Engine</strong> ist eine kostenlose, quelloffene Game-Engine, die von der Community entwickelt wird.
                        Mit Godot 4 ist die Engine zu einem direkten Konkurrenten von Unity und Unreal
                        für Indie- und mittelgroße Projekte geworden.
                    </p>
                    <div class="pillar-highlight">
                        <h4>💡 Highlights von Godot 4</h4>
                        <ul>
                            <li>100% kostenlos, keine Lizenzgebühren</li>
                            <li>GDScript ist so leicht zu lernen wie Python</li>
                            <li>C#-Unterstützung für wechselnde Unity-Entwickler</li>
                            <li>Vulkan-Renderer für moderne Grafik</li>
                            <li>Leichtgewichtig (~100MB) im Vergleich zu Unity (~2GB+)</li>
                        </ul>
                    </div>
                </section>

                {{-- Section: Warum Godot --}}
                <section id="tai-sao-godot" class="pillar-section">
                    <h2>Warum Godot wählen?</h2>
                    
                    <h3>Vergleich Godot vs Unity</h3>
                    <table class="pillar-table">
                        <thead>
                            <tr>
                                <th>Kriterium</th>
                                <th>Godot 4</th>
                                <th>Unity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Preis</td>
                                <td>Für immer kostenlos</td>
                                <td>Kostenlose Stufe, dann kostenpflichtig</td>
                            </tr>
                            <tr>
                                <td>Sprache</td>
                                <td>GDScript, C#, C++</td>
                                <td>C#</td>
                            </tr>
                            <tr>
                                <td>Größe</td>
                                <td>~100MB</td>
                                <td>~2GB+</td>
                            </tr>
                            <tr>
                                <td>2D-Spiel</td>
                                <td>⭐⭐⭐⭐⭐</td>
                                <td>⭐⭐⭐⭐</td>
                            </tr>
                            <tr>
                                <td>3D-Spiel</td>
                                <td>⭐⭐⭐⭐</td>
                                <td>⭐⭐⭐⭐⭐</td>
                            </tr>
                            <tr>
                                <td>Community</td>
                                <td>Wachsend</td>
                                <td>Groß</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <p>
                        <strong>Fazit:</strong> Godot eignet sich für Indie-Entwickler, 2D-Spiele oder alle, die
                        Bedenken bezüglich der Lizenzierung von Unity vermeiden möchten.
                    </p>
                </section>

                {{-- Section: Installieren --}}
                <section id="cai-dat" class="pillar-section">
                    <h2>Godot 4 installieren</h2>
                    
                    <h3>Schritt 1: Godot herunterladen</h3>
                    <p>
                        Besuchen Sie <a href="https://godotengine.org/download" target="_blank" rel="noopener">godotengine.org/download</a> 
                        und laden Sie die passende Version herunter:
                    </p>
                    <ul>
                        <li><strong>Godot 4.x Standard:</strong> Verwendet GDScript (empfohlen für Anfänger)</li>
                        <li><strong>Godot 4.x .NET:</strong> Verwendet C# (für Unity-Entwickler)</li>
                    </ul>
                    
                    <h3>Schritt 2: Godot ausführen</h3>
                    <p>
                        Godot ist eine portable App, keine Installation nötig. Entpacken und die ausführbare Datei direkt starten.
                    </p>
                    
                    <h3>Schritt 3: Ihr erstes Projekt erstellen</h3>
                    <p>
                        Godot öffnen → „New Project" → einen Renderer wählen (Forward+ für 3D, Compatibility für Mobile) → Create.
                    </p>
                </section>

                {{-- Section: GDScript --}}
                <section id="gdscript" class="pillar-section">
                    <h2>GDScript lernen</h2>
                    <p>
                        GDScript ist die Skriptsprache von Godot, mit einer Python-ähnlichen Syntax, aber optimiert für die Spieleentwicklung.
                    </p>
                    
                    <h3>Hello World</h3>
                    <pre><code class="language-gdscript">extends Node

func _ready():
    print("Hello, Godot!")
    
func _process(delta):
    # Läuft in jedem Frame
    pass</code></pre>
                    
                    <h3>Kernkonzepte</h3>
                    <ul>
                        <li><strong>Node:</strong> Die Grundeinheit in Godot (wie ein GameObject in Unity)</li>
                        <li><strong>Scene:</strong> Eine Sammlung von Nodes (wie ein Prefab)</li>
                        <li><strong>Signal:</strong> Das Event-System (wie UnityEvent)</li>
                        <li><strong>_ready():</strong> Wird aufgerufen, wenn der Node zur Szene hinzugefügt wird</li>
                        <li><strong>_process(delta):</strong> Wird in jedem Frame aufgerufen (wie Update())</li>
                        <li><strong>_physics_process(delta):</strong> Wird in jedem Physik-Tick aufgerufen (wie FixedUpdate())</li>
                    </ul>
                </section>

                {{-- Section: 2D-Spiel --}}
                <section id="game-2d" class="pillar-section">
                    <h2>2D-Spiele mit Godot erstellen</h2>
                    <p>
                        Godot gilt dank seines nativen 2D-Systems als eine der besten Engines für 2D-Spiele
                        (nicht 3D-ortho wie Unity).
                    </p>
                    
                    <h3>Wichtige 2D-Nodes</h3>
                    <ul>
                        <li><strong>Sprite2D:</strong> Ein Bild anzeigen</li>
                        <li><strong>CharacterBody2D:</strong> Character-Controller (ersetzt KinematicBody2D)</li>
                        <li><strong>RigidBody2D:</strong> Physik-Body</li>
                        <li><strong>TileMap:</strong> Level aus Tiles erstellen</li>
                        <li><strong>AnimationPlayer:</strong> Animationssystem</li>
                        <li><strong>Area2D:</strong> Trigger-Zonen</li>
                    </ul>
                    
                    <h3>Tutorial: Einfacher Platformer</h3>
                    <p>Siehe die detaillierte Anleitung in den Artikeln unten.</p>
                </section>

                {{-- Section: 3D-Spiel --}}
                <section id="game-3d" class="pillar-section">
                    <h2>3D-Spiele mit Godot 4 erstellen</h2>
                    <p>
                        Godot 4 mit dem Vulkan-Renderer hat die 3D-Fähigkeiten erheblich verbessert. Geeignet für
                        Indie-/mittelgroße Projekte, die keine AAA-Grafik erfordern.
                    </p>
                    
                    <h3>Wichtige 3D-Nodes</h3>
                    <ul>
                        <li><strong>MeshInstance3D:</strong> Ein 3D-Modell anzeigen</li>
                        <li><strong>CharacterBody3D:</strong> 3D-Character-Controller</li>
                        <li><strong>Camera3D:</strong> Kamera</li>
                        <li><strong>DirectionalLight3D:</strong> Sonnenlicht</li>
                        <li><strong>WorldEnvironment:</strong> Himmel, Nebel, Post-Processing</li>
                    </ul>
                </section>

                {{-- Section: Export --}}
                <section id="export" class="pillar-section">
                    <h2>Ein Spiel exportieren & veröffentlichen</h2>
                    
                    <h3>Export-Vorlagen</h3>
                    <p>
                        Gehen Sie zu Editor → Manage Export Templates → Download, um Vorlagen für jede Plattform zu erhalten.
                    </p>
                    
                    <h3>Unterstützte Plattformen</h3>
                    <ul>
                        <li>Windows, macOS, Linux</li>
                        <li>Android, iOS</li>
                        <li>Web (HTML5)</li>
                        <li>Konsolen (erfordert eine separate Lizenz)</li>
                    </ul>
                    
                    <h3>Auf Steam veröffentlichen</h3>
                    <p>
                        Verwenden Sie das GodotSteam-Add-on, um das Steamworks SDK zu integrieren.
                    </p>
                </section>

                {{-- Related Articles --}}
                <section id="bai-viet" class="pillar-section">
                    <h2>📚 Artikel über Godot</h2>
                    @if(isset($articles) && $articles->count() > 0)
                    <div class="pillar-articles">
                        @foreach($articles as $article)
                        <article class="pillar-article-card">
                            <h3><a href="{{ route('blog.show', $article->slug) }}">{{ $article->name }}</a></h3>
                            <p>{{ Str::limit($article->short_description, 120) }}</p>
                        </article>
                        @endforeach
                    </div>
                    @else
                    <p class="pillar-empty">
                        Wir bauen weitere Godot-Inhalte auf.
                        <a href="{{ route('lamgame.blog', ['category' => 'programming']) }}">Programming-Artikel ansehen</a> 
                        in der Zwischenzeit.
                    </p>
                    @endif
                </section>

                {{-- Resources --}}
                <section id="tai-nguyen" class="pillar-section">
                    <h2>🔗 Godot-Lernressourcen</h2>
                    <div class="pillar-resources">
                        <div class="pillar-resource">
                            <h4>📖 Offizielle Dokumentation</h4>
                            <ul>
                                <li><a href="https://docs.godotengine.org" target="_blank" rel="noopener">Godot Documentation</a></li>
                                <li><a href="https://gdquest.com" target="_blank" rel="noopener">GDQuest (Tutorials)</a></li>
                                <li><a href="https://kidscancode.org/godot_recipes/4.x/" target="_blank" rel="noopener">Godot Recipes</a></li>
                            </ul>
                        </div>
                        <div class="pillar-resource">
                            <h4>🎬 Videos</h4>
                            <ul>
                                <li><a href="{{ route('lamgame.blog', ['search' => 'godot']) }}">Godot-Artikel auf LamGame</a></li>
                            </ul>
                        </div>
                        <div class="pillar-resource">
                            <h4>💬 Community</h4>
                            <ul>
                                <li><a href="https://discord.gg/godot" target="_blank" rel="noopener">Godot Discord</a></li>
                                <li><a href="https://www.reddit.com/r/godot/" target="_blank" rel="noopener">r/godot</a></li>
                                <li><a href="{{ route('forum.index') }}">LamGame Forum</a></li>
                            </ul>
                        </div>
                    </div>
                </section>
            </main>

            {{-- Sidebar --}}
            <aside class="pillar-sidebar">
                <div class="pillar-sidebar__sticky">
                    {{-- CTA --}}
                    <div class="pillar-cta-box">
                        <h3>🚀 Mit Godot starten</h3>
                        <p>Kostenlos herunterladen, keine Registrierung nötig</p>
                        <a href="https://godotengine.org/download" target="_blank" rel="noopener" class="pillar-btn pillar-btn--primary">
                            Godot 4 herunterladen →
                        </a>
                    </div>

                    {{-- Related Pillars --}}
                    <div class="pillar-related">
                        <h4>Siehe auch</h4>
                        <ul>
                            <li><a href="{{ route('learn.unity') }}">🎮 Unity lernen</a></li>
                            <li><a href="{{ route('learn.ai-game-dev') }}">🤖 KI für Game Dev</a></li>
                            <li><a href="{{ route('lamgame.blog', ['category' => 'programming']) }}">💻 Programming</a></li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
