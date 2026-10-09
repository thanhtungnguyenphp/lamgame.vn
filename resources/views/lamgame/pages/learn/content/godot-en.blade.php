<div class="pillar-page pillar-page--godot">
    <div class="container">
        {{-- Hero --}}
        <header class="pillar-hero">
            <nav class="pillar-breadcrumb">
                <a href="{{ url('/') }}">Home</a> / 
                <a href="{{ route('lamgame.blog') }}">Learn</a> / 
                <span>Godot</span>
            </nav>
            <h1>🤖 Learn Godot from A-Z</h1>
            <p class="pillar-hero__lead">
                A free, open-source game engine with easy-to-learn GDScript. The perfect choice for indie developers.
            </p>
            <div class="pillar-hero__stats">
                <span>📚 {{ $articleCount ?? 0 }} articles</span>
                <span>🎮 {{ $sourceCount ?? 0 }} source code</span>
                <span>🆓 100% free</span>
            </div>
        </header>

        <div class="pillar-layout">
            {{-- Main Content --}}
            <main class="pillar-main">
                {{-- TOC --}}
                <nav class="pillar-toc">
                    <h2>📑 Table of Contents</h2>
                    <ol>
                        <li><a href="#godot-la-gi">What is Godot?</a></li>
                        <li><a href="#tai-sao-godot">Why choose Godot?</a></li>
                        <li><a href="#cai-dat">Installing Godot 4</a></li>
                        <li><a href="#gdscript">Learn GDScript</a></li>
                        <li><a href="#game-2d">Making 2D games</a></li>
                        <li><a href="#game-3d">Making 3D games</a></li>
                        <li><a href="#export">Export & Publish</a></li>
                        <li><a href="#tai-nguyen">Resources</a></li>
                    </ol>
                </nav>

                {{-- Section: What is Godot --}}
                <section id="godot-la-gi" class="pillar-section">
                    <h2>What is Godot?</h2>
                    <p>
                        <strong>Godot Engine</strong> is a free, open-source game engine developed by the community.
                        With Godot 4, the engine has become a direct competitor to Unity and Unreal
                        for indie and mid-size projects.
                    </p>
                    <div class="pillar-highlight">
                        <h4>💡 Highlights of Godot 4</h4>
                        <ul>
                            <li>100% free, no royalties</li>
                            <li>GDScript is as easy to learn as Python</li>
                            <li>C# support for Unity developers switching over</li>
                            <li>Vulkan renderer for modern graphics</li>
                            <li>Lightweight (~100MB) compared to Unity (~2GB+)</li>
                        </ul>
                    </div>
                </section>

                {{-- Section: Why Godot --}}
                <section id="tai-sao-godot" class="pillar-section">
                    <h2>Why choose Godot?</h2>
                    
                    <h3>Godot vs Unity comparison</h3>
                    <table class="pillar-table">
                        <thead>
                            <tr>
                                <th>Criterion</th>
                                <th>Godot 4</th>
                                <th>Unity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Price</td>
                                <td>Free forever</td>
                                <td>Free tier, then paid</td>
                            </tr>
                            <tr>
                                <td>Language</td>
                                <td>GDScript, C#, C++</td>
                                <td>C#</td>
                            </tr>
                            <tr>
                                <td>Size</td>
                                <td>~100MB</td>
                                <td>~2GB+</td>
                            </tr>
                            <tr>
                                <td>2D Game</td>
                                <td>⭐⭐⭐⭐⭐</td>
                                <td>⭐⭐⭐⭐</td>
                            </tr>
                            <tr>
                                <td>3D Game</td>
                                <td>⭐⭐⭐⭐</td>
                                <td>⭐⭐⭐⭐⭐</td>
                            </tr>
                            <tr>
                                <td>Community</td>
                                <td>Growing</td>
                                <td>Large</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <p>
                        <strong>Conclusion:</strong> Godot suits indie developers, 2D games, or anyone who wants
                        to avoid concerns about Unity's licensing.
                    </p>
                </section>

                {{-- Section: Install --}}
                <section id="cai-dat" class="pillar-section">
                    <h2>Installing Godot 4</h2>
                    
                    <h3>Step 1: Download Godot</h3>
                    <p>
                        Visit <a href="https://godotengine.org/download" target="_blank" rel="noopener">godotengine.org/download</a> 
                        and download the right version:
                    </p>
                    <ul>
                        <li><strong>Godot 4.x Standard:</strong> Uses GDScript (recommended for beginners)</li>
                        <li><strong>Godot 4.x .NET:</strong> Uses C# (for Unity developers)</li>
                    </ul>
                    
                    <h3>Step 2: Run Godot</h3>
                    <p>
                        Godot is a portable app, no installation needed. Extract and run the executable directly.
                    </p>
                    
                    <h3>Step 3: Create your first project</h3>
                    <p>
                        Open Godot → "New Project" → choose a renderer (Forward+ for 3D, Compatibility for mobile) → Create.
                    </p>
                </section>

                {{-- Section: GDScript --}}
                <section id="gdscript" class="pillar-section">
                    <h2>Learn GDScript</h2>
                    <p>
                        GDScript is Godot's scripting language, with syntax similar to Python but optimized for game development.
                    </p>
                    
                    <h3>Hello World</h3>
                    <pre><code class="language-gdscript">extends Node

func _ready():
    print("Hello, Godot!")
    
func _process(delta):
    # Runs every frame
    pass</code></pre>
                    
                    <h3>Core concepts</h3>
                    <ul>
                        <li><strong>Node:</strong> The basic unit in Godot (like a GameObject in Unity)</li>
                        <li><strong>Scene:</strong> A collection of Nodes (like a Prefab)</li>
                        <li><strong>Signal:</strong> The event system (like UnityEvent)</li>
                        <li><strong>_ready():</strong> Called when the node is added to the scene</li>
                        <li><strong>_process(delta):</strong> Called every frame (like Update())</li>
                        <li><strong>_physics_process(delta):</strong> Called every physics tick (like FixedUpdate())</li>
                    </ul>
                </section>

                {{-- Section: 2D Game --}}
                <section id="game-2d" class="pillar-section">
                    <h2>Making 2D games with Godot</h2>
                    <p>
                        Godot is considered one of the best engines for 2D games thanks to its native 2D system
                        (not 3D ortho like Unity).
                    </p>
                    
                    <h3>Important 2D Nodes</h3>
                    <ul>
                        <li><strong>Sprite2D:</strong> Display an image</li>
                        <li><strong>CharacterBody2D:</strong> Character controller (replaces KinematicBody2D)</li>
                        <li><strong>RigidBody2D:</strong> Physics body</li>
                        <li><strong>TileMap:</strong> Build levels from tiles</li>
                        <li><strong>AnimationPlayer:</strong> Animation system</li>
                        <li><strong>Area2D:</strong> Trigger zones</li>
                    </ul>
                    
                    <h3>Tutorial: Basic platformer</h3>
                    <p>See the detailed guide in the articles below.</p>
                </section>

                {{-- Section: 3D Game --}}
                <section id="game-3d" class="pillar-section">
                    <h2>Making 3D games with Godot 4</h2>
                    <p>
                        Godot 4 with the Vulkan renderer has significantly improved 3D capabilities. Suitable for
                        indie/mid-size projects that don't require AAA graphics.
                    </p>
                    
                    <h3>Important 3D Nodes</h3>
                    <ul>
                        <li><strong>MeshInstance3D:</strong> Display a 3D model</li>
                        <li><strong>CharacterBody3D:</strong> 3D character controller</li>
                        <li><strong>Camera3D:</strong> Camera</li>
                        <li><strong>DirectionalLight3D:</strong> Sun light</li>
                        <li><strong>WorldEnvironment:</strong> Sky, fog, post-processing</li>
                    </ul>
                </section>

                {{-- Section: Export --}}
                <section id="export" class="pillar-section">
                    <h2>Export & Publish a Game</h2>
                    
                    <h3>Export Templates</h3>
                    <p>
                        Go to Editor → Manage Export Templates → Download to get templates for each platform.
                    </p>
                    
                    <h3>Supported platforms</h3>
                    <ul>
                        <li>Windows, macOS, Linux</li>
                        <li>Android, iOS</li>
                        <li>Web (HTML5)</li>
                        <li>Consoles (requires a separate license)</li>
                    </ul>
                    
                    <h3>Publishing to Steam</h3>
                    <p>
                        Use the GodotSteam add-on to integrate the Steamworks SDK.
                    </p>
                </section>

                {{-- Related Articles --}}
                <section id="bai-viet" class="pillar-section">
                    <h2>📚 Articles about Godot</h2>
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
                        We are building more Godot content.
                        <a href="{{ route('lamgame.blog', ['category' => 'programming']) }}">See Programming articles</a> 
                        in the meantime.
                    </p>
                    @endif
                </section>

                {{-- Resources --}}
                <section id="tai-nguyen" class="pillar-section">
                    <h2>🔗 Godot learning resources</h2>
                    <div class="pillar-resources">
                        <div class="pillar-resource">
                            <h4>📖 Official documentation</h4>
                            <ul>
                                <li><a href="https://docs.godotengine.org" target="_blank" rel="noopener">Godot Documentation</a></li>
                                <li><a href="https://gdquest.com" target="_blank" rel="noopener">GDQuest (Tutorials)</a></li>
                                <li><a href="https://kidscancode.org/godot_recipes/4.x/" target="_blank" rel="noopener">Godot Recipes</a></li>
                            </ul>
                        </div>
                        <div class="pillar-resource">
                            <h4>🎬 Videos</h4>
                            <ul>
                                <li><a href="{{ route('lamgame.blog', ['search' => 'godot']) }}">Godot articles on LamGame</a></li>
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
                        <h3>🚀 Get started with Godot</h3>
                        <p>Download for free, no registration needed</p>
                        <a href="https://godotengine.org/download" target="_blank" rel="noopener" class="pillar-btn pillar-btn--primary">
                            Download Godot 4 →
                        </a>
                    </div>

                    {{-- Related Pillars --}}
                    <div class="pillar-related">
                        <h4>See also</h4>
                        <ul>
                            <li><a href="{{ route('learn.unity') }}">🎮 Learn Unity</a></li>
                            <li><a href="{{ route('learn.ai-game-dev') }}">🤖 AI for Game Dev</a></li>
                            <li><a href="{{ route('lamgame.blog', ['category' => 'programming']) }}">💻 Programming</a></li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
