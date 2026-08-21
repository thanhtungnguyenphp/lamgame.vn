@extends('layouts.master')

@section('page_title', 'AI cho Game Developer — Công cụ & Workflow AI trong Game Dev')
@section('page_description', 'Hướng dẫn sử dụng AI trong game development. Từ AI coding assistants, AI art generation đến NPC AI và procedural content.')

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "TechArticle",
    "headline": "AI cho Game Developer — Công cụ & Workflow AI trong Game Dev",
    "description": "Hướng dẫn sử dụng AI trong game development. AI coding, AI art, NPC AI và procedural generation.",
    "author": {
        "@type": "Organization",
        "name": "LamGame.vn"
    },
    "publisher": {
        "@type": "Organization",
        "name": "LamGame.vn",
        "url": "{{ url('/') }}"
    },
    "datePublished": "2026-01-01",
    "dateModified": "{{ now()->toISOString() }}",
    "mainEntityOfPage": "{{ url()->current() }}"
}
</script>
@endpush

@section('content')
<div class="pillar-page pillar-page--ai">
    <div class="container">
        {{-- Hero --}}
        <header class="pillar-hero">
            <nav class="pillar-breadcrumb">
                <a href="{{ url('/') }}">Trang chủ</a> / 
                <a href="{{ route('lamgame.blog') }}">Learn</a> / 
                <span>AI Game Dev</span>
            </nav>
            <h1>🤖 AI cho Game Developer</h1>
            <p class="pillar-hero__lead">
                Tận dụng AI để tăng tốc workflow game development. Từ coding assistants đến art generation và NPC AI.
            </p>
            <div class="pillar-hero__stats">
                <span>📚 {{ $articleCount ?? 0 }} bài viết</span>
                <span>🛠️ 10+ AI tools</span>
                <span>⚡ Workflow 2026</span>
            </div>
        </header>

        <div class="pillar-layout">
            {{-- Main Content --}}
            <main class="pillar-main">
                {{-- TOC --}}
                <nav class="pillar-toc">
                    <h2>📑 Mục lục</h2>
                    <ol>
                        <li><a href="#tong-quan">Tổng quan AI trong Game Dev</a></li>
                        <li><a href="#ai-coding">AI Coding Assistants</a></li>
                        <li><a href="#ai-art">AI Art Generation</a></li>
                        <li><a href="#ai-audio">AI Audio & Music</a></li>
                        <li><a href="#npc-ai">NPC AI & Behavior</a></li>
                        <li><a href="#procedural">Procedural Generation</a></li>
                        <li><a href="#workflow">AI Workflow thực tế</a></li>
                        <li><a href="#tools">Danh sách công cụ</a></li>
                    </ol>
                </nav>

                {{-- Section: Tổng quan --}}
                <section id="tong-quan" class="pillar-section">
                    <h2>Tổng quan AI trong Game Dev 2026</h2>
                    <p>
                        AI đang thay đổi cách chúng ta làm game. Không phải thay thế developer, mà là 
                        <strong>augment</strong> — tăng cường khả năng và tốc độ của developer.
                    </p>
                    
                    <div class="pillar-highlight">
                        <h4>🎯 AI giúp gì cho Game Developer?</h4>
                        <ul>
                            <li><strong>Tăng tốc coding:</strong> Sinh code boilerplate, debug, refactor</li>
                            <li><strong>Tạo asset nhanh:</strong> Concept art, sprites, textures, audio</li>
                            <li><strong>NPC thông minh:</strong> Behavior trees, dialogue systems</li>
                            <li><strong>Procedural content:</strong> Levels, quests, items tự động sinh</li>
                            <li><strong>Testing:</strong> AI playtest, bug detection</li>
                        </ul>
                    </div>
                    
                    <p>
                        <strong>Quan trọng:</strong> AI là công cụ, không phải phép màu. Output vẫn cần review 
                        và điều chỉnh bởi developer có kinh nghiệm.
                    </p>
                </section>

                {{-- Section: AI Coding --}}
                <section id="ai-coding" class="pillar-section">
                    <h2>AI Coding Assistants</h2>
                    <p>
                        AI coding assistants giúp viết code nhanh hơn, debug hiệu quả hơn, và học best practices.
                    </p>
                    
                    <h3>Công cụ phổ biến</h3>
                    <table class="pillar-table">
                        <thead>
                            <tr>
                                <th>Tool</th>
                                <th>Ưu điểm</th>
                                <th>Giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>GitHub Copilot</strong></td>
                                <td>Tích hợp sâu với IDE, code suggestions tốt</td>
                                <td>$10/tháng</td>
                            </tr>
                            <tr>
                                <td><strong>Cursor</strong></td>
                                <td>AI-native IDE, chat với codebase</td>
                                <td>Free tier, $20/tháng</td>
                            </tr>
                            <tr>
                                <td><strong>Claude (Anthropic)</strong></td>
                                <td>Reasoning tốt, code explanation</td>
                                <td>$20/tháng (Pro)</td>
                            </tr>
                            <tr>
                                <td><strong>ChatGPT</strong></td>
                                <td>Đa năng, debug, architecture</td>
                                <td>$20/tháng (Plus)</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h3>Use cases hiệu quả</h3>
                    <ul>
                        <li>Sinh boilerplate code (MonoBehaviour, ScriptableObject)</li>
                        <li>Convert code giữa các engine (Unity → Godot)</li>
                        <li>Debug và giải thích error messages</li>
                        <li>Viết unit tests</li>
                        <li>Optimize performance code</li>
                        <li>Documentation và comments</li>
                    </ul>
                    
                    <h3>Prompt hiệu quả cho Game Dev</h3>
                    <pre><code class="language-text">Tôi đang làm game [thể loại] với [engine].
Cần tạo [component/system] với yêu cầu:
- Yêu cầu 1
- Yêu cầu 2
Code cần [performance/clean/maintainable].
Sử dụng [pattern nếu có].</code></pre>
                </section>

                {{-- Section: AI Art --}}
                <section id="ai-art" class="pillar-section">
                    <h2>AI Art Generation</h2>
                    <p>
                        AI image generation giúp tạo concept art, sprites, textures nhanh chóng. 
                        Đặc biệt hữu ích cho indie developer không có budget cho artist.
                    </p>
                    
                    <h3>Công cụ AI Art</h3>
                    <ul>
                        <li><strong>Midjourney:</strong> Chất lượng cao, concept art tuyệt vời</li>
                        <li><strong>DALL-E 3:</strong> Tích hợp ChatGPT, dễ dùng</li>
                        <li><strong>Stable Diffusion:</strong> Miễn phí, chạy local, nhiều models</li>
                        <li><strong>Leonardo.ai:</strong> Tối ưu cho game assets</li>
                        <li><strong>Scenario.gg:</strong> Train custom models cho art style nhất quán</li>
                    </ul>
                    
                    <h3>Workflow AI Art cho Game</h3>
                    <ol>
                        <li><strong>Concept:</strong> Dùng Midjourney/DALL-E tạo concept art</li>
                        <li><strong>Refine:</strong> Chỉnh sửa trong Photoshop/GIMP</li>
                        <li><strong>Consistency:</strong> Train model riêng hoặc dùng style reference</li>
                        <li><strong>Export:</strong> Cắt sprite sheets, optimize cho game</li>
                    </ol>
                    
                    <div class="pillar-warning">
                        <h4>⚠️ Lưu ý về bản quyền</h4>
                        <p>
                            AI-generated art có thể có vấn đề về copyright phức tạp. Nếu game commercial, 
                            nên tham khảo luật sư hoặc sử dụng tools có license rõ ràng (như Leonardo.ai commercial license).
                        </p>
                    </div>
                </section>

                {{-- Section: AI Audio --}}
                <section id="ai-audio" class="pillar-section">
                    <h2>AI Audio & Music</h2>
                    
                    <h3>AI Music Generation</h3>
                    <ul>
                        <li><strong>Suno:</strong> Tạo nhạc từ text prompt, nhiều thể loại</li>
                        <li><strong>Udio:</strong> Chất lượng cao, giọng hát AI</li>
                        <li><strong>AIVA:</strong> Classical/orchestral, license commercial</li>
                        <li><strong>Soundraw:</strong> Royalty-free, customize được</li>
                    </ul>
                    
                    <h3>AI Voice & SFX</h3>
                    <ul>
                        <li><strong>ElevenLabs:</strong> Text-to-speech chất lượng cao cho NPC dialogue</li>
                        <li><strong>Replica Studios:</strong> AI voice actors, nhiều giọng</li>
                        <li><strong>AudioGen:</strong> Sinh sound effects từ text</li>
                    </ul>
                </section>

                {{-- Section: NPC AI --}}
                <section id="npc-ai" class="pillar-section">
                    <h2>NPC AI & Behavior</h2>
                    <p>
                        AI có thể tạo NPC thông minh hơn với dialogue động và behavior phức tạp.
                    </p>
                    
                    <h3>Approaches</h3>
                    <ul>
                        <li><strong>LLM-powered NPCs:</strong> Dùng GPT/Claude cho dialogue động</li>
                        <li><strong>Behavior Trees + AI:</strong> AI suggest behavior nodes</li>
                        <li><strong>GOAP (Goal-Oriented Action Planning):</strong> AI generate goals</li>
                        <li><strong>Machine Learning NPCs:</strong> Train NPC từ player behavior</li>
                    </ul>
                    
                    <h3>Convai & Inworld AI</h3>
                    <p>
                        Các platforms như Convai và Inworld AI cung cấp SDK để tích hợp AI NPCs 
                        vào Unity/Unreal với voice và personality.
                    </p>
                </section>

                {{-- Section: Procedural --}}
                <section id="procedural" class="pillar-section">
                    <h2>Procedural Generation với AI</h2>
                    
                    <h3>AI-assisted PCG</h3>
                    <ul>
                        <li><strong>Level Generation:</strong> WaveFunctionCollapse + AI constraints</li>
                        <li><strong>Quest Generation:</strong> LLM tạo quest narratives</li>
                        <li><strong>Item Generation:</strong> AI balance stats và descriptions</li>
                        <li><strong>Dialogue Trees:</strong> AI expand dialogue branches</li>
                    </ul>
                    
                    <h3>Ví dụ: AI Quest Generator</h3>
                    <pre><code class="language-csharp">// Pseudo-code cho AI quest generation
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
                    <h2>AI Workflow thực tế</h2>
                    
                    <h3>Workflow cho Indie Developer</h3>
                    <div class="pillar-workflow">
                        <div class="pillar-workflow__step">
                            <span class="step-num">1</span>
                            <h4>Concept & Design</h4>
                            <p>Dùng ChatGPT/Claude brainstorm game mechanics, GDD</p>
                        </div>
                        <div class="pillar-workflow__step">
                            <span class="step-num">2</span>
                            <h4>Art Direction</h4>
                            <p>Midjourney tạo concept art, style guide</p>
                        </div>
                        <div class="pillar-workflow__step">
                            <span class="step-num">3</span>
                            <h4>Coding</h4>
                            <p>Cursor/Copilot hỗ trợ viết code</p>
                        </div>
                        <div class="pillar-workflow__step">
                            <span class="step-num">4</span>
                            <h4>Asset Creation</h4>
                            <p>AI art + AI audio cho prototype</p>
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
                    <h2>🛠️ Danh sách công cụ AI</h2>
                    
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
                    <h2>📚 Bài viết về AI Game Dev</h2>
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
                        Đang cập nhật thêm nội dung. 
                        <a href="{{ route('lamgame.ai-tools') }}">Khám phá AI Tools</a> của LamGame.
                    </p>
                    @endif
                </section>
            </main>

            {{-- Sidebar --}}
            <aside class="pillar-sidebar">
                <div class="pillar-sidebar__sticky">
                    {{-- CTA --}}
                    <div class="pillar-cta-box">
                        <h3>🤖 Thử AI Tools</h3>
                        <p>Công cụ AI cho Game Developer</p>
                        <a href="{{ route('lamgame.ai-tools') }}" class="pillar-btn pillar-btn--primary">
                            Khám phá AI Tools →
                        </a>
                    </div>

                    {{-- Related Pillars --}}
                    <div class="pillar-related">
                        <h4>Xem thêm</h4>
                        <ul>
                            <li><a href="{{ route('learn.unity') }}">🎮 Học Unity</a></li>
                            <li><a href="{{ route('learn.godot') }}">🤖 Học Godot</a></li>
                            <li><a href="{{ route('lamgame.blog', ['category' => 'programming']) }}">💻 Programming</a></li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.pillar-page{padding:40px 0 80px;background:#0a0a0f;color:#f5f7fa;min-height:100vh}
.pillar-page--ai .pillar-hero{background:linear-gradient(135deg,rgba(0,209,255,.15),rgba(124,92,255,.1))}
.pillar-breadcrumb{font-size:.85rem;color:#7a8599;margin-bottom:16px}
.pillar-breadcrumb a{color:#7a8599;text-decoration:none}
.pillar-breadcrumb a:hover{color:#00d1ff}
.pillar-hero{padding:48px;border-radius:16px;margin-bottom:40px;background:linear-gradient(135deg,rgba(124,92,255,.15),rgba(124,92,255,.05));border:1px solid rgba(124,92,255,.1)}
.pillar-hero h1{font-size:2.4rem;margin-bottom:16px;font-weight:800}
.pillar-hero__lead{font-size:1.15rem;color:#b7c0d1;max-width:600px;line-height:1.6}
.pillar-hero__stats{display:flex;gap:24px;margin-top:24px;flex-wrap:wrap}
.pillar-hero__stats span{background:rgba(255,255,255,.08);padding:8px 16px;border-radius:8px;font-size:.9rem}
.pillar-layout{display:grid;grid-template-columns:1fr 300px;gap:48px}
.pillar-toc{background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);border-radius:12px;padding:24px;margin-bottom:32px}
.pillar-toc h2{font-size:1.1rem;margin-bottom:16px}
.pillar-toc ol{padding-left:20px}
.pillar-toc li{margin-bottom:8px}
.pillar-toc a{color:#7a8599;text-decoration:none}
.pillar-toc a:hover{color:#00d1ff}
.pillar-section{margin-bottom:48px;padding-bottom:32px;border-bottom:1px solid rgba(255,255,255,.06)}
.pillar-section h2{font-size:1.6rem;margin-bottom:20px;color:#00d1ff}
.pillar-section h3{font-size:1.2rem;margin:24px 0 12px;color:#f5f7fa}
.pillar-section p,.pillar-section li{line-height:1.7;color:#b7c0d1}
.pillar-section ul,.pillar-section ol{padding-left:24px;margin:16px 0}
.pillar-section li{margin-bottom:8px}
.pillar-section a{color:#00d1ff}
.pillar-highlight{background:rgba(0,209,255,.1);border-left:4px solid #00d1ff;padding:20px;border-radius:0 8px 8px 0;margin:20px 0}
.pillar-highlight h4{margin-bottom:12px;color:#00d1ff}
.pillar-highlight ul{margin:0;padding-left:20px}
.pillar-warning{background:rgba(255,170,0,.1);border-left:4px solid #ffaa00;padding:20px;border-radius:0 8px 8px 0;margin:20px 0}
.pillar-warning h4{margin-bottom:8px;color:#ffaa00}
.pillar-warning p{margin:0;color:#b7c0d1}
.pillar-table{width:100%;border-collapse:collapse;margin:20px 0}
.pillar-table th,.pillar-table td{padding:12px 16px;text-align:left;border-bottom:1px solid rgba(255,255,255,.1)}
.pillar-table th{background:rgba(0,209,255,.1);color:#00d1ff}
.pillar-table tr:hover{background:rgba(255,255,255,.02)}
pre{background:#111827;border-radius:8px;padding:16px;overflow-x:auto;margin:16px 0}
code{font-family:'Fira Code',monospace;font-size:.9rem;color:#f5f7fa}
.pillar-workflow{display:flex;gap:16px;flex-wrap:wrap;margin:24px 0}
.pillar-workflow__step{flex:1;min-width:180px;background:rgba(17,24,39,.6);border:1px solid rgba(0,209,255,.1);border-radius:12px;padding:20px;position:relative}
.pillar-workflow__step .step-num{position:absolute;top:-12px;left:16px;background:#00d1ff;color:#0a0a0f;width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem}
.pillar-workflow__step h4{margin:8px 0;font-size:1rem}
.pillar-workflow__step p{font-size:.85rem;color:#7a8599;margin:0}
.pillar-tools-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin:24px 0}
.pillar-tool-category{background:rgba(17,24,39,.6);border-radius:12px;padding:20px}
.pillar-tool-category h4{font-size:1rem;margin-bottom:12px;color:#00d1ff}
.pillar-tool-category ul{list-style:none;padding:0;margin:0}
.pillar-tool-category li{margin-bottom:6px;font-size:.9rem;color:#b7c0d1}
.pillar-articles{display:grid;gap:16px}
.pillar-article-card{background:rgba(17,24,39,.6);border:1px solid rgba(255,255,255,.06);border-radius:12px;padding:20px}
.pillar-article-card h3{font-size:1.1rem;margin-bottom:8px}
.pillar-article-card h3 a{color:#f5f7fa;text-decoration:none}
.pillar-article-card h3 a:hover{color:#00d1ff}
.pillar-article-card p{font-size:.9rem;color:#7a8599;margin:0}
.pillar-empty{background:rgba(255,255,255,.04);padding:24px;border-radius:8px;text-align:center;color:#7a8599}
.pillar-sidebar__sticky{position:sticky;top:100px}
.pillar-cta-box{background:linear-gradient(135deg,rgba(0,209,255,.2),rgba(124,92,255,.1));border:1px solid rgba(0,209,255,.2);border-radius:12px;padding:24px;text-align:center;margin-bottom:24px}
.pillar-cta-box h3{margin-bottom:8px;font-size:1.1rem}
.pillar-cta-box p{font-size:.9rem;color:#7a8599;margin-bottom:16px}
.pillar-btn{display:inline-block;padding:12px 24px;border-radius:8px;text-decoration:none!important;font-weight:600;transition:all .3s}
.pillar-btn--primary{background:linear-gradient(135deg,#00d1ff,#7c5cff);color:#fff!important}
.pillar-btn--primary:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,209,255,.3)}
.pillar-related{background:rgba(17,24,39,.6);border-radius:12px;padding:20px}
.pillar-related h4{font-size:1rem;margin-bottom:12px}
.pillar-related ul{list-style:none;padding:0;margin:0}
.pillar-related li{margin-bottom:10px}
.pillar-related a{color:#7a8599;text-decoration:none}
.pillar-related a:hover{color:#00d1ff}
@media(max-width:1024px){.pillar-layout{grid-template-columns:1fr}.pillar-sidebar{display:none}.pillar-tools-grid{grid-template-columns:repeat(2,1fr)}.pillar-workflow{flex-direction:column}}
@media(max-width:640px){.pillar-hero{padding:24px}.pillar-hero h1{font-size:1.8rem}.pillar-hero__stats{flex-direction:column;gap:8px}.pillar-tools-grid{grid-template-columns:1fr}}
</style>
@endpush
