<div class="pillar-page pillar-page--ai">
    <div class="container">
        {{-- Hero --}}
        <header class="pillar-hero">
            <nav class="pillar-breadcrumb">
                <a href="{{ url('/') }}">Home</a> / 
                <a href="{{ route('lamgame.blog') }}">Learn</a> / 
                <span>AI Game Dev</span>
            </nav>
            <h1>🤖 AI for Game Developers</h1>
            <p class="pillar-hero__lead">
                Leverage AI to speed up your game development workflow. From coding assistants to art generation and NPC AI.
            </p>
            <div class="pillar-hero__stats">
                <span>📚 {{ $articleCount ?? 0 }} articles</span>
                <span>🛠️ 10+ AI tools</span>
                <span>⚡ Workflow 2026</span>
            </div>
        </header>

        <div class="pillar-layout">
            {{-- Main Content --}}
            <main class="pillar-main">
                {{-- TOC --}}
                <nav class="pillar-toc">
                    <h2>📑 Table of Contents</h2>
                    <ol>
                        <li><a href="#tong-quan">AI in Game Dev overview</a></li>
                        <li><a href="#ai-coding">AI Coding Assistants</a></li>
                        <li><a href="#ai-art">AI Art Generation</a></li>
                        <li><a href="#ai-audio">AI Audio & Music</a></li>
                        <li><a href="#npc-ai">NPC AI & Behavior</a></li>
                        <li><a href="#procedural">Procedural Generation</a></li>
                        <li><a href="#workflow">Practical AI Workflow</a></li>
                        <li><a href="#tools">Tool list</a></li>
                    </ol>
                </nav>

                {{-- Section: Overview --}}
                <section id="tong-quan" class="pillar-section">
                    <h2>AI in Game Dev overview 2026</h2>
                    <p>
                        AI is changing how we make games. Not by replacing developers, but by
                        <strong>augmenting</strong> them — boosting a developer's capability and speed.
                    </p>
                    
                    <div class="pillar-highlight">
                        <h4>🎯 How does AI help Game Developers?</h4>
                        <ul>
                            <li><strong>Speed up coding:</strong> Generate boilerplate, debug, refactor</li>
                            <li><strong>Create assets fast:</strong> Concept art, sprites, textures, audio</li>
                            <li><strong>Smarter NPCs:</strong> Behavior trees, dialogue systems</li>
                            <li><strong>Procedural content:</strong> Automatically generated levels, quests, items</li>
                            <li><strong>Testing:</strong> AI playtesting, bug detection</li>
                        </ul>
                    </div>
                    
                    <p>
                        <strong>Important:</strong> AI is a tool, not magic. The output still needs review
                        and adjustment by an experienced developer.
                    </p>
                </section>

                {{-- Section: AI Coding --}}
                <section id="ai-coding" class="pillar-section">
                    <h2>AI Coding Assistants</h2>
                    <p>
                        AI coding assistants help you write code faster, debug more efficiently, and learn best practices.
                    </p>
                    
                    <h3>Popular tools</h3>
                    <table class="pillar-table">
                        <thead>
                            <tr>
                                <th>Tool</th>
                                <th>Strengths</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>GitHub Copilot</strong></td>
                                <td>Deep IDE integration, good code suggestions</td>
                                <td>$10/month</td>
                            </tr>
                            <tr>
                                <td><strong>Cursor</strong></td>
                                <td>AI-native IDE, chat with the codebase</td>
                                <td>Free tier, $20/month</td>
                            </tr>
                            <tr>
                                <td><strong>Claude (Anthropic)</strong></td>
                                <td>Strong reasoning, code explanation</td>
                                <td>$20/month (Pro)</td>
                            </tr>
                            <tr>
                                <td><strong>ChatGPT</strong></td>
                                <td>Versatile, debug, architecture</td>
                                <td>$20/month (Plus)</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h3>Effective use cases</h3>
                    <ul>
                        <li>Generate boilerplate code (MonoBehaviour, ScriptableObject)</li>
                        <li>Convert code between engines (Unity → Godot)</li>
                        <li>Debug and explain error messages</li>
                        <li>Write unit tests</li>
                        <li>Optimize performance-critical code</li>
                        <li>Documentation and comments</li>
                    </ul>
                    
                    <h3>Effective prompts for Game Dev</h3>
                    <pre><code class="language-text">I'm making a [genre] game with [engine].
I need to create a [component/system] with requirements:
- Requirement 1
- Requirement 2
The code should be [performant/clean/maintainable].
Use [pattern if any].</code></pre>
                </section>

                {{-- Section: AI Art --}}
                <section id="ai-art" class="pillar-section">
                    <h2>AI Art Generation</h2>
                    <p>
                        AI image generation helps create concept art, sprites and textures quickly.
                        Especially useful for indie developers without an art budget.
                    </p>
                    
                    <h3>AI Art tools</h3>
                    <ul>
                        <li><strong>Midjourney:</strong> High quality, excellent concept art</li>
                        <li><strong>DALL-E 3:</strong> Integrated with ChatGPT, easy to use</li>
                        <li><strong>Stable Diffusion:</strong> Free, runs locally, many models</li>
                        <li><strong>Leonardo.ai:</strong> Optimized for game assets</li>
                        <li><strong>Scenario.gg:</strong> Train custom models for a consistent art style</li>
                    </ul>
                    
                    <h3>AI Art workflow for games</h3>
                    <ol>
                        <li><strong>Concept:</strong> Use Midjourney/DALL-E to create concept art</li>
                        <li><strong>Refine:</strong> Edit in Photoshop/GIMP</li>
                        <li><strong>Consistency:</strong> Train your own model or use a style reference</li>
                        <li><strong>Export:</strong> Cut sprite sheets, optimize for the game</li>
                    </ol>
                    
                    <div class="pillar-warning">
                        <h4>⚠️ Note on copyright</h4>
                        <p>
                            AI-generated art can have complex copyright issues. For a commercial game,
                            consult a lawyer or use tools with a clear license (such as the Leonardo.ai commercial license).
                        </p>
                    </div>
                </section>

                {{-- Section: AI Audio --}}
                <section id="ai-audio" class="pillar-section">
                    <h2>AI Audio & Music</h2>
                    
                    <h3>AI Music Generation</h3>
                    <ul>
                        <li><strong>Suno:</strong> Create music from a text prompt, many genres</li>
                        <li><strong>Udio:</strong> High quality, AI vocals</li>
                        <li><strong>AIVA:</strong> Classical/orchestral, commercial license</li>
                        <li><strong>Soundraw:</strong> Royalty-free, customizable</li>
                    </ul>
                    
                    <h3>AI Voice & SFX</h3>
                    <ul>
                        <li><strong>ElevenLabs:</strong> High-quality text-to-speech for NPC dialogue</li>
                        <li><strong>Replica Studios:</strong> AI voice actors, many voices</li>
                        <li><strong>AudioGen:</strong> Generate sound effects from text</li>
                    </ul>
                </section>

                {{-- Section: NPC AI --}}
                <section id="npc-ai" class="pillar-section">
                    <h2>NPC AI & Behavior</h2>
                    <p>
                        AI can create smarter NPCs with dynamic dialogue and complex behavior.
                    </p>
                    
                    <h3>Approaches</h3>
                    <ul>
                        <li><strong>LLM-powered NPCs:</strong> Use GPT/Claude for dynamic dialogue</li>
                        <li><strong>Behavior Trees + AI:</strong> AI suggests behavior nodes</li>
                        <li><strong>GOAP (Goal-Oriented Action Planning):</strong> AI generates goals</li>
                        <li><strong>Machine Learning NPCs:</strong> Train NPCs from player behavior</li>
                    </ul>
                    
                    <h3>Convai & Inworld AI</h3>
                    <p>
                        Platforms like Convai and Inworld AI provide SDKs to integrate AI NPCs
                        into Unity/Unreal with voice and personality.
                    </p>
                </section>

                {{-- Section: Procedural --}}
                <section id="procedural" class="pillar-section">
                    <h2>Procedural Generation with AI</h2>
                    
                    <h3>AI-assisted PCG</h3>
                    <ul>
                        <li><strong>Level Generation:</strong> WaveFunctionCollapse + AI constraints</li>
                        <li><strong>Quest Generation:</strong> LLM creates quest narratives</li>
                        <li><strong>Item Generation:</strong> AI balances stats and descriptions</li>
                        <li><strong>Dialogue Trees:</strong> AI expands dialogue branches</li>
                    </ul>
                    
                    <h3>Example: AI Quest Generator</h3>
                    <pre><code class="language-csharp">// Pseudo-code for AI quest generation
public async Task<Quest> GenerateQuest(string context) {
    var prompt = $@"
        Game: Fantasy RPG
        Player level: {playerLevel}
        Location: {currentLocation}
        Generate a side quest with:
        - Objective
        - NPCs involved
        - Rewards
        - Difficulty: {difficulty}
    ";
    
    var response = await aiService.Complete(prompt);
    return ParseQuestFromResponse(response);
}</code></pre>
                </section>

                {{-- Section: Workflow --}}
                <section id="workflow" class="pillar-section">
                    <h2>Practical AI Workflow</h2>
                    
                    <h3>Workflow for Indie Developers</h3>
                    <div class="pillar-workflow">
                        <div class="pillar-workflow__step">
                            <span class="step-num">1</span>
                            <h4>Concept & Design</h4>
                            <p>Use ChatGPT/Claude to brainstorm game mechanics and the GDD</p>
                        </div>
                        <div class="pillar-workflow__step">
                            <span class="step-num">2</span>
                            <h4>Art Direction</h4>
                            <p>Midjourney creates concept art and a style guide</p>
                        </div>
                        <div class="pillar-workflow__step">
                            <span class="step-num">3</span>
                            <h4>Coding</h4>
                            <p>Cursor/Copilot help write code</p>
                        </div>
                        <div class="pillar-workflow__step">
                            <span class="step-num">4</span>
                            <h4>Asset Creation</h4>
                            <p>AI art + AI audio for the prototype</p>
                        </div>
                        <div class="pillar-workflow__step">
                            <span class="step-num">5</span>
                            <h4>Testing</h4>
                            <p>AI-assisted QA, playtest analysis</p>
                        </div>
                    </div>
                </section>

                {{-- Section: Tools --}}
                <section id="tools" class="pillar-section">
                    <h2>🛠️ AI tool list</h2>
                    
                    <div class="pillar-tools-grid">
                        <div class="pillar-tool-category">
                            <h4>💻 Coding</h4>
                            <ul>
                                <li>GitHub Copilot</li>
                                <li>Cursor IDE</li>
                                <li>Claude / ChatGPT</li>
                                <li>Codeium (free)</li>
                            </ul>
                        </div>
                        <div class="pillar-tool-category">
                            <h4>🎨 Art</h4>
                            <ul>
                                <li>Midjourney</li>
                                <li>Stable Diffusion</li>
                                <li>Leonardo.ai</li>
                                <li>Scenario.gg</li>
                            </ul>
                        </div>
                        <div class="pillar-tool-category">
                            <h4>🎵 Audio</h4>
                            <ul>
                                <li>Suno / Udio</li>
                                <li>ElevenLabs</li>
                                <li>Soundraw</li>
                                <li>AudioGen</li>
                            </ul>
                        </div>
                        <div class="pillar-tool-category">
                            <h4>🤖 NPC AI</h4>
                            <ul>
                                <li>Convai</li>
                                <li>Inworld AI</li>
                                <li>Replica Studios</li>
                            </ul>
                        </div>
                    </div>
                </section>

                {{-- Related Articles --}}
                <section id="bai-viet" class="pillar-section">
                    <h2>📚 Articles about AI Game Dev</h2>
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
                        We are adding more content.
                        <a href="{{ route('lamgame.ai-tools') }}">Explore LamGame's AI Tools</a>.
                    </p>
                    @endif
                </section>
            </main>

            {{-- Sidebar --}}
            <aside class="pillar-sidebar">
                <div class="pillar-sidebar__sticky">
                    {{-- CTA --}}
                    <div class="pillar-cta-box">
                        <h3>🤖 Try AI Tools</h3>
                        <p>AI tools for Game Developers</p>
                        <a href="{{ route('lamgame.ai-tools') }}" class="pillar-btn pillar-btn--primary">
                            Explore AI Tools →
                        </a>
                    </div>

                    {{-- Related Pillars --}}
                    <div class="pillar-related">
                        <h4>See also</h4>
                        <ul>
                            <li><a href="{{ route('learn.unity') }}">🎮 Learn Unity</a></li>
                            <li><a href="{{ route('learn.godot') }}">🤖 Learn Godot</a></li>
                            <li><a href="{{ route('lamgame.blog', ['category' => 'programming']) }}">💻 Programming</a></li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
