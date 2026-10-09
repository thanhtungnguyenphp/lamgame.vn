<div class="pillar-page pillar-page--ai">
    <div class="container">
        {{-- Hero --}}
        <header class="pillar-hero">
            <nav class="pillar-breadcrumb">
                <a href="{{ url('/') }}">Startseite</a> / 
                <a href="{{ route('lamgame.blog') }}">Learn</a> / 
                <span>AI Game Dev</span>
            </nav>
            <h1>🤖 KI für Spieleentwickler</h1>
            <p class="pillar-hero__lead">
                Nutzen Sie KI, um Ihren Spieleentwicklungs-Workflow zu beschleunigen. Von Coding-Assistenten bis zur Kunstgenerierung und NPC-KI.
            </p>
            <div class="pillar-hero__stats">
                <span>📚 {{ $articleCount ?? 0 }} Artikel</span>
                <span>🛠️ 10+ KI-Tools</span>
                <span>⚡ Workflow 2026</span>
            </div>
        </header>

        <div class="pillar-layout">
            {{-- Main Content --}}
            <main class="pillar-main">
                {{-- TOC --}}
                <nav class="pillar-toc">
                    <h2>📑 Inhaltsverzeichnis</h2>
                    <ol>
                        <li><a href="#tong-quan">Überblick: KI in der Spieleentwicklung</a></li>
                        <li><a href="#ai-coding">KI-Coding-Assistenten</a></li>
                        <li><a href="#ai-art">KI-Kunstgenerierung</a></li>
                        <li><a href="#ai-audio">KI-Audio & Musik</a></li>
                        <li><a href="#npc-ai">NPC-KI & Verhalten</a></li>
                        <li><a href="#procedural">Prozedurale Generierung</a></li>
                        <li><a href="#workflow">Praktischer KI-Workflow</a></li>
                        <li><a href="#tools">Tool-Liste</a></li>
                    </ol>
                </nav>

                {{-- Section: Überblick --}}
                <section id="tong-quan" class="pillar-section">
                    <h2>Überblick: KI in der Spieleentwicklung 2026</h2>
                    <p>
                        KI verändert, wie wir Spiele machen. Nicht indem sie Entwickler ersetzt, sondern sie
                        <strong>erweitert</strong> — sie steigert die Fähigkeiten und das Tempo eines Entwicklers.
                    </p>
                    
                    <div class="pillar-highlight">
                        <h4>🎯 Wie hilft KI Spieleentwicklern?</h4>
                        <ul>
                            <li><strong>Coding beschleunigen:</strong> Boilerplate generieren, debuggen, refactoren</li>
                            <li><strong>Assets schnell erstellen:</strong> Concept Art, Sprites, Texturen, Audio</li>
                            <li><strong>Intelligentere NPCs:</strong> Behavior Trees, Dialogsysteme</li>
                            <li><strong>Prozedurale Inhalte:</strong> Automatisch generierte Level, Quests, Items</li>
                            <li><strong>Testing:</strong> KI-Playtesting, Fehlererkennung</li>
                        </ul>
                    </div>
                    
                    <p>
                        <strong>Wichtig:</strong> KI ist ein Werkzeug, keine Magie. Die Ausgabe muss weiterhin
                        von einem erfahrenen Entwickler überprüft und angepasst werden.
                    </p>
                </section>

                {{-- Section: KI-Coding --}}
                <section id="ai-coding" class="pillar-section">
                    <h2>KI-Coding-Assistenten</h2>
                    <p>
                        KI-Coding-Assistenten helfen Ihnen, schneller Code zu schreiben, effizienter zu debuggen und Best Practices zu lernen.
                    </p>
                    
                    <h3>Beliebte Tools</h3>
                    <table class="pillar-table">
                        <thead>
                            <tr>
                                <th>Tool</th>
                                <th>Stärken</th>
                                <th>Preis</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>GitHub Copilot</strong></td>
                                <td>Tiefe IDE-Integration, gute Code-Vorschläge</td>
                                <td>10 $/Monat</td>
                            </tr>
                            <tr>
                                <td><strong>Cursor</strong></td>
                                <td>KI-native IDE, Chat mit der Codebasis</td>
                                <td>Kostenlose Stufe, 20 $/Monat</td>
                            </tr>
                            <tr>
                                <td><strong>Claude (Anthropic)</strong></td>
                                <td>Starkes Reasoning, Code-Erklärung</td>
                                <td>20 $/Monat (Pro)</td>
                            </tr>
                            <tr>
                                <td><strong>ChatGPT</strong></td>
                                <td>Vielseitig, Debug, Architektur</td>
                                <td>20 $/Monat (Plus)</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h3>Effektive Anwendungsfälle</h3>
                    <ul>
                        <li>Boilerplate-Code generieren (MonoBehaviour, ScriptableObject)</li>
                        <li>Code zwischen Engines konvertieren (Unity → Godot)</li>
                        <li>Fehlermeldungen debuggen und erklären</li>
                        <li>Unit-Tests schreiben</li>
                        <li>Performance-kritischen Code optimieren</li>
                        <li>Dokumentation und Kommentare</li>
                    </ul>
                    
                    <h3>Effektive Prompts für Game Dev</h3>
                    <pre><code class="language-text">Ich mache ein [Genre]-Spiel mit [Engine].
Ich muss ein [Komponente/System] erstellen mit Anforderungen:
- Anforderung 1
- Anforderung 2
Der Code sollte [performant/sauber/wartbar] sein.
Verwende [Pattern, falls vorhanden].</code></pre>
                </section>

                {{-- Section: KI-Kunst --}}
                <section id="ai-art" class="pillar-section">
                    <h2>KI-Kunstgenerierung</h2>
                    <p>
                        KI-Bildgenerierung hilft, schnell Concept Art, Sprites und Texturen zu erstellen.
                        Besonders nützlich für Indie-Entwickler ohne Kunstbudget.
                    </p>
                    
                    <h3>KI-Kunst-Tools</h3>
                    <ul>
                        <li><strong>Midjourney:</strong> Hohe Qualität, exzellente Concept Art</li>
                        <li><strong>DALL-E 3:</strong> In ChatGPT integriert, einfach zu bedienen</li>
                        <li><strong>Stable Diffusion:</strong> Kostenlos, lokal lauffähig, viele Modelle</li>
                        <li><strong>Leonardo.ai:</strong> Für Spiel-Assets optimiert</li>
                        <li><strong>Scenario.gg:</strong> Eigene Modelle für einen konsistenten Kunststil trainieren</li>
                    </ul>
                    
                    <h3>KI-Kunst-Workflow für Spiele</h3>
                    <ol>
                        <li><strong>Konzept:</strong> Mit Midjourney/DALL-E Concept Art erstellen</li>
                        <li><strong>Verfeinern:</strong> In Photoshop/GIMP bearbeiten</li>
                        <li><strong>Konsistenz:</strong> Eigenes Modell trainieren oder eine Stilreferenz verwenden</li>
                        <li><strong>Export:</strong> Sprite-Sheets zuschneiden, für das Spiel optimieren</li>
                    </ol>
                    
                    <div class="pillar-warning">
                        <h4>⚠️ Hinweis zum Urheberrecht</h4>
                        <p>
                            KI-generierte Kunst kann komplexe Urheberrechtsprobleme haben. Für ein kommerzielles Spiel
                            sollten Sie einen Anwalt konsultieren oder Tools mit einer klaren Lizenz verwenden (wie die kommerzielle Lizenz von Leonardo.ai).
                        </p>
                    </div>
                </section>

                {{-- Section: KI-Audio --}}
                <section id="ai-audio" class="pillar-section">
                    <h2>KI-Audio & Musik</h2>
                    
                    <h3>KI-Musikgenerierung</h3>
                    <ul>
                        <li><strong>Suno:</strong> Musik aus einem Text-Prompt erstellen, viele Genres</li>
                        <li><strong>Udio:</strong> Hohe Qualität, KI-Gesang</li>
                        <li><strong>AIVA:</strong> Klassik/Orchester, kommerzielle Lizenz</li>
                        <li><strong>Soundraw:</strong> Lizenzfrei, anpassbar</li>
                    </ul>
                    
                    <h3>KI-Stimme & SFX</h3>
                    <ul>
                        <li><strong>ElevenLabs:</strong> Hochwertige Text-to-Speech für NPC-Dialoge</li>
                        <li><strong>Replica Studios:</strong> KI-Sprecher, viele Stimmen</li>
                        <li><strong>AudioGen:</strong> Soundeffekte aus Text generieren</li>
                    </ul>
                </section>

                {{-- Section: NPC-KI --}}
                <section id="npc-ai" class="pillar-section">
                    <h2>NPC-KI & Verhalten</h2>
                    <p>
                        KI kann intelligentere NPCs mit dynamischen Dialogen und komplexem Verhalten erstellen.
                    </p>
                    
                    <h3>Ansätze</h3>
                    <ul>
                        <li><strong>LLM-gestützte NPCs:</strong> GPT/Claude für dynamische Dialoge verwenden</li>
                        <li><strong>Behavior Trees + KI:</strong> KI schlägt Behavior-Nodes vor</li>
                        <li><strong>GOAP (Goal-Oriented Action Planning):</strong> KI generiert Ziele</li>
                        <li><strong>Machine-Learning-NPCs:</strong> NPCs aus Spielerverhalten trainieren</li>
                    </ul>
                    
                    <h3>Convai & Inworld AI</h3>
                    <p>
                        Plattformen wie Convai und Inworld AI bieten SDKs zur Integration von KI-NPCs
                        in Unity/Unreal mit Stimme und Persönlichkeit.
                    </p>
                </section>

                {{-- Section: Prozedural --}}
                <section id="procedural" class="pillar-section">
                    <h2>Prozedurale Generierung mit KI</h2>
                    
                    <h3>KI-gestützte PCG</h3>
                    <ul>
                        <li><strong>Level-Generierung:</strong> WaveFunctionCollapse + KI-Constraints</li>
                        <li><strong>Quest-Generierung:</strong> LLM erstellt Quest-Narrative</li>
                        <li><strong>Item-Generierung:</strong> KI balanciert Werte und Beschreibungen</li>
                        <li><strong>Dialogbäume:</strong> KI erweitert Dialogzweige</li>
                    </ul>
                    
                    <h3>Beispiel: KI-Quest-Generator</h3>
                    <pre><code class="language-csharp">// Pseudo-Code für KI-Quest-Generierung
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
                    <h2>Praktischer KI-Workflow</h2>
                    
                    <h3>Workflow für Indie-Entwickler</h3>
                    <div class="pillar-workflow">
                        <div class="pillar-workflow__step">
                            <span class="step-num">1</span>
                            <h4>Konzept & Design</h4>
                            <p>ChatGPT/Claude verwenden, um Spielmechaniken und das GDD zu entwickeln</p>
                        </div>
                        <div class="pillar-workflow__step">
                            <span class="step-num">2</span>
                            <h4>Art Direction</h4>
                            <p>Midjourney erstellt Concept Art und einen Style Guide</p>
                        </div>
                        <div class="pillar-workflow__step">
                            <span class="step-num">3</span>
                            <h4>Coding</h4>
                            <p>Cursor/Copilot helfen beim Schreiben von Code</p>
                        </div>
                        <div class="pillar-workflow__step">
                            <span class="step-num">4</span>
                            <h4>Asset-Erstellung</h4>
                            <p>KI-Kunst + KI-Audio für den Prototyp</p>
                        </div>
                        <div class="pillar-workflow__step">
                            <span class="step-num">5</span>
                            <h4>Testing</h4>
                            <p>KI-gestützte QA, Playtest-Analyse</p>
                        </div>
                    </div>
                </section>

                {{-- Section: Tools --}}
                <section id="tools" class="pillar-section">
                    <h2>🛠️ KI-Tool-Liste</h2>
                    
                    <div class="pillar-tools-grid">
                        <div class="pillar-tool-category">
                            <h4>💻 Coding</h4>
                            <ul>
                                <li>GitHub Copilot</li>
                                <li>Cursor IDE</li>
                                <li>Claude / ChatGPT</li>
                                <li>Codeium (kostenlos)</li>
                            </ul>
                        </div>
                        <div class="pillar-tool-category">
                            <h4>🎨 Kunst</h4>
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
                            <h4>🤖 NPC-KI</h4>
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
                    <h2>📚 Artikel über AI Game Dev</h2>
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
                        Wir fügen weitere Inhalte hinzu.
                        <a href="{{ route('lamgame.ai-tools') }}">Entdecken Sie die KI-Tools von LamGame</a>.
                    </p>
                    @endif
                </section>
            </main>

            {{-- Sidebar --}}
            <aside class="pillar-sidebar">
                <div class="pillar-sidebar__sticky">
                    {{-- CTA --}}
                    <div class="pillar-cta-box">
                        <h3>🤖 KI-Tools ausprobieren</h3>
                        <p>KI-Tools für Spieleentwickler</p>
                        <a href="{{ route('lamgame.ai-tools') }}" class="pillar-btn pillar-btn--primary">
                            KI-Tools entdecken →
                        </a>
                    </div>

                    {{-- Related Pillars --}}
                    <div class="pillar-related">
                        <h4>Siehe auch</h4>
                        <ul>
                            <li><a href="{{ route('learn.unity') }}">🎮 Unity lernen</a></li>
                            <li><a href="{{ route('learn.godot') }}">🤖 Godot lernen</a></li>
                            <li><a href="{{ route('lamgame.blog', ['category' => 'programming']) }}">💻 Programming</a></li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
