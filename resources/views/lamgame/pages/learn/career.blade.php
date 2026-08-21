@extends('layouts.master')

@section('page_title', 'Game Developer Career — Lộ trình & Việc làm Game Dev Việt Nam')
@section('page_description', 'Hướng dẫn toàn diện về career game developer tại Việt Nam. Roadmap học tập, mức lương, kỹ năng cần thiết và cơ hội việc làm.')

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "Game Developer Career — Lộ trình & Việc làm Game Dev Việt Nam",
    "description": "Hướng dẫn toàn diện về career game developer tại Việt Nam. Roadmap, mức lương, kỹ năng và việc làm.",
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
<div class="pillar-page pillar-page--career">
    <div class="container">
        {{-- Hero --}}
        <header class="pillar-hero">
            <nav class="pillar-breadcrumb">
                <a href="{{ url('/') }}">Trang chủ</a> / 
                <a href="{{ route('lamgame.blog') }}">Learn</a> / 
                <span>Career</span>
            </nav>
            <h1>💼 Game Developer Career</h1>
            <p class="pillar-hero__lead">
                Lộ trình trở thành Game Developer tại Việt Nam. Từ kỹ năng, học tập đến việc làm và mức lương.
            </p>
            <div class="pillar-hero__stats">
                <span>📚 {{ $articleCount ?? 0 }} bài viết</span>
                <span>💼 {{ $jobCount ?? 0 }} việc làm</span>
                <span>🏢 10+ game studios VN</span>
            </div>
        </header>

        <div class="pillar-layout">
            {{-- Main Content --}}
            <main class="pillar-main">
                {{-- TOC --}}
                <nav class="pillar-toc">
                    <h2>📑 Mục lục</h2>
                    <ol>
                        <li><a href="#tong-quan">Tổng quan Game Industry VN</a></li>
                        <li><a href="#vai-tro">Các vai trò trong Game Dev</a></li>
                        <li><a href="#ky-nang">Kỹ năng cần thiết</a></li>
                        <li><a href="#roadmap">Roadmap học tập</a></li>
                        <li><a href="#luong">Mức lương tham khảo</a></li>
                        <li><a href="#studio">Game Studios Việt Nam</a></li>
                        <li><a href="#interview">Chuẩn bị phỏng vấn</a></li>
                        <li><a href="#viec-lam">Việc làm mới nhất</a></li>
                    </ol>
                </nav>

                {{-- Section: Tổng quan --}}
                <section id="tong-quan" class="pillar-section">
                    <h2>Tổng quan Game Industry Việt Nam</h2>
                    <p>
                        Ngành game Việt Nam đang phát triển mạnh với nhiều studio trong nước và outsource. 
                        Đây là cơ hội tốt cho developer muốn theo đuổi đam mê game.
                    </p>
                    
                    <div class="pillar-highlight">
                        <h4>📊 Game Industry VN 2026</h4>
                        <ul>
                            <li><strong>VNG:</strong> Studio game lớn nhất, nhiều IP thành công</li>
                            <li><strong>Gameloft Vietnam:</strong> AAA mobile games, 800+ employees</li>
                            <li><strong>Glass Egg:</strong> Art outsourcing cho global studios</li>
                            <li><strong>Amanotes:</strong> Hypercasual games, 3+ billion downloads</li>
                            <li><strong>Sparx*:</strong> Outsourcing cho AAA titles</li>
                        </ul>
                    </div>
                    
                    <h3>Xu hướng 2026</h3>
                    <ul>
                        <li>Mobile game vẫn chiếm tỷ trọng lớn</li>
                        <li>AI integration trong game development</li>
                        <li>Remote work opportunities tăng</li>
                        <li>Indie scene đang phát triển</li>
                    </ul>
                </section>

                {{-- Section: Vai trò --}}
                <section id="vai-tro" class="pillar-section">
                    <h2>Các vai trò trong Game Development</h2>
                    
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

                {{-- Section: Kỹ năng --}}
                <section id="ky-nang" class="pillar-section">
                    <h2>Kỹ năng cần thiết</h2>
                    
                    <h3>Game Programmer</h3>
                    <table class="pillar-table">
                        <thead>
                            <tr>
                                <th>Kỹ năng</th>
                                <th>Junior</th>
                                <th>Mid</th>
                                <th>Senior</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>C# / C++</td>
                                <td>Cơ bản</td>
                                <td>Thành thạo</td>
                                <td>Expert</td>
                            </tr>
                            <tr>
                                <td>Unity / Unreal</td>
                                <td>1 engine</td>
                                <td>1 engine + kiến thức engine khác</td>
                                <td>Multi-engine</td>
                            </tr>
                            <tr>
                                <td>Math / Physics</td>
                                <td>Cơ bản</td>
                                <td>Linear algebra, physics</td>
                                <td>Advanced</td>
                            </tr>
                            <tr>
                                <td>Design Patterns</td>
                                <td>Biết</td>
                                <td>Áp dụng được</td>
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
                    
                    <h3>Soft Skills quan trọng</h3>
                    <ul>
                        <li><strong>Communication:</strong> Làm việc với designer, artist</li>
                        <li><strong>Problem-solving:</strong> Debug, optimize</li>
                        <li><strong>Time management:</strong> Deadlines, sprints</li>
                        <li><strong>Teamwork:</strong> Agile/Scrum</li>
                        <li><strong>English:</strong> Đọc docs, communicate với global teams</li>
                    </ul>
                </section>

                {{-- Section: Roadmap --}}
                <section id="roadmap" class="pillar-section">
                    <h2>Roadmap học tập</h2>
                    
                    <div class="career-roadmap">
                        <div class="roadmap-phase">
                            <h4>📚 Phase 1: Foundation (3-6 tháng)</h4>
                            <ul>
                                <li>Học C# hoặc C++ cơ bản</li>
                                <li>Làm quen Unity hoặc Godot</li>
                                <li>Hoàn thành 2-3 mini projects</li>
                                <li>Học Git cơ bản</li>
                            </ul>
                        </div>
                        <div class="roadmap-phase">
                            <h4>🎮 Phase 2: Core Skills (6-12 tháng)</h4>
                            <ul>
                                <li>Làm 1 game hoàn chỉnh (2D platformer, puzzle)</li>
                                <li>Học OOP, design patterns</li>
                                <li>Physics, collision, AI cơ bản</li>
                                <li>UI/UX trong game</li>
                            </ul>
                        </div>
                        <div class="roadmap-phase">
                            <h4>💪 Phase 3: Advanced (12-24 tháng)</h4>
                            <ul>
                                <li>Multiplayer networking</li>
                                <li>Performance optimization</li>
                                <li>Shader programming</li>
                                <li>Publish game lên store</li>
                            </ul>
                        </div>
                        <div class="roadmap-phase">
                            <h4>💼 Phase 4: Job Ready</h4>
                            <ul>
                                <li>Portfolio 3-5 projects</li>
                                <li>GitHub active</li>
                                <li>Apply internship/junior positions</li>
                            </ul>
                        </div>
                    </div>
                </section>

                {{-- Section: Lương --}}
                <section id="luong" class="pillar-section">
                    <h2>Mức lương tham khảo (2026)</h2>
                    
                    <div class="pillar-warning">
                        <h4>⚠️ Lưu ý</h4>
                        <p>
                            Mức lương phụ thuộc vào công ty, vị trí, kinh nghiệm và kỹ năng. 
                            Số liệu dưới đây là tham khảo từ các nguồn tuyển dụng.
                        </p>
                    </div>
                    
                    <table class="pillar-table">
                        <thead>
                            <tr>
                                <th>Vị trí</th>
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
                    <p class="pillar-note">* Đơn vị: VND/tháng. Chưa bao gồm bonus, stock options.</p>
                </section>

                {{-- Section: Studios --}}
                <section id="studio" class="pillar-section">
                    <h2>Game Studios Việt Nam</h2>
                    
                    <div class="studio-grid">
                        <div class="studio-card">
                            <h4>🎮 VNG Corporation</h4>
                            <p>HCM & Hà Nội</p>
                            <p>ZingPlay, nhiều IP nội địa</p>
                        </div>
                        <div class="studio-card">
                            <h4>🎮 Gameloft Vietnam</h4>
                            <p>HCM & Đà Nẵng</p>
                            <p>AAA mobile games</p>
                        </div>
                        <div class="studio-card">
                            <h4>🎨 Glass Egg</h4>
                            <p>HCM</p>
                            <p>Art outsourcing</p>
                        </div>
                        <div class="studio-card">
                            <h4>🎵 Amanotes</h4>
                            <p>Hà Nội</p>
                            <p>Music games, hypercasual</p>
                        </div>
                        <div class="studio-card">
                            <h4>🎮 Sparx*</h4>
                            <p>HCM</p>
                            <p>AAA outsourcing</p>
                        </div>
                        <div class="studio-card">
                            <h4>🎮 Sky Mavis</h4>
                            <p>HCM</p>
                            <p>Axie Infinity, blockchain games</p>
                        </div>
                    </div>
                </section>

                {{-- Section: Interview --}}
                <section id="interview" class="pillar-section">
                    <h2>Chuẩn bị phỏng vấn</h2>
                    
                    <h3>Technical Interview</h3>
                    <ul>
                        <li>OOP concepts (inheritance, polymorphism, encapsulation)</li>
                        <li>Design patterns (Singleton, Observer, State, Factory)</li>
                        <li>Data structures (List, Dictionary, Queue, Stack)</li>
                        <li>Unity/Unreal specific: MonoBehaviour lifecycle, Coroutines, ScriptableObjects</li>
                        <li>Math: Vector operations, dot/cross product, quaternions</li>
                    </ul>
                    
                    <h3>Coding Test thường gặp</h3>
                    <ul>
                        <li>Implement simple gameplay mechanic</li>
                        <li>Fix bugs in provided code</li>
                        <li>Optimize performance of a feature</li>
                        <li>Design system architecture</li>
                    </ul>
                    
                    <h3>Portfolio Tips</h3>
                    <ul>
                        <li>Quality > Quantity: 3-5 polished projects</li>
                        <li>Có ít nhất 1 project playable</li>
                        <li>Clean code với comments</li>
                        <li>README giải thích features, tech stack</li>
                        <li>Video demo nếu có thể</li>
                    </ul>
                </section>

                {{-- Section: Việc làm --}}
                <section id="viec-lam" class="pillar-section">
                    <h2>💼 Việc làm Game Developer</h2>
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
                        Xem tất cả việc làm →
                    </a>
                    @else
                    <p class="pillar-empty">
                        <a href="{{ route('lamgame.viec-lam-game') }}">Xem việc làm game mới nhất</a>
                    </p>
                    @endif
                </section>
            </main>

            {{-- Sidebar --}}
            <aside class="pillar-sidebar">
                <div class="pillar-sidebar__sticky">
                    {{-- CTA --}}
                    <div class="pillar-cta-box">
                        <h3>💼 Tìm việc Game Dev</h3>
                        <p>Việc làm từ các studio hàng đầu</p>
                        <a href="{{ route('lamgame.viec-lam-game') }}" class="pillar-btn pillar-btn--primary">
                            Xem việc làm →
                        </a>
                    </div>

                    {{-- Related Pillars --}}
                    <div class="pillar-related">
                        <h4>Học kỹ năng</h4>
                        <ul>
                            <li><a href="{{ route('learn.unity') }}">🎮 Học Unity</a></li>
                            <li><a href="{{ route('learn.godot') }}">🤖 Học Godot</a></li>
                            <li><a href="{{ route('learn.ai-game-dev') }}">🤖 AI cho Game Dev</a></li>
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
.pillar-page--career .pillar-hero{background:linear-gradient(135deg,rgba(34,197,94,.15),rgba(34,197,94,.05))}
.pillar-breadcrumb{font-size:.85rem;color:#7a8599;margin-bottom:16px}
.pillar-breadcrumb a{color:#7a8599;text-decoration:none}
.pillar-breadcrumb a:hover{color:#22c55e}
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
.pillar-toc a:hover{color:#22c55e}
.pillar-section{margin-bottom:48px;padding-bottom:32px;border-bottom:1px solid rgba(255,255,255,.06)}
.pillar-section h2{font-size:1.6rem;margin-bottom:20px;color:#22c55e}
.pillar-section h3{font-size:1.2rem;margin:24px 0 12px;color:#f5f7fa}
.pillar-section p,.pillar-section li{line-height:1.7;color:#b7c0d1}
.pillar-section ul,.pillar-section ol{padding-left:24px;margin:16px 0}
.pillar-section li{margin-bottom:8px}
.pillar-section a{color:#22c55e}
.pillar-highlight{background:rgba(34,197,94,.1);border-left:4px solid #22c55e;padding:20px;border-radius:0 8px 8px 0;margin:20px 0}
.pillar-highlight h4{margin-bottom:12px;color:#22c55e}
.pillar-highlight ul{margin:0;padding-left:20px}
.pillar-warning{background:rgba(255,170,0,.1);border-left:4px solid #ffaa00;padding:20px;border-radius:0 8px 8px 0;margin:20px 0}
.pillar-warning h4{margin-bottom:8px;color:#ffaa00}
.pillar-warning p{margin:0;color:#b7c0d1}
.pillar-table{width:100%;border-collapse:collapse;margin:20px 0}
.pillar-table th,.pillar-table td{padding:12px 16px;text-align:left;border-bottom:1px solid rgba(255,255,255,.1)}
.pillar-table th{background:rgba(34,197,94,.1);color:#22c55e}
.pillar-table tr:hover{background:rgba(255,255,255,.02)}
.pillar-note{font-size:.85rem;color:#7a8599;font-style:italic}
.career-roles{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin:24px 0}
.career-role{background:rgba(17,24,39,.6);border-radius:12px;padding:20px}
.career-role h4{font-size:1rem;margin-bottom:12px;color:#22c55e}
.career-role ul{list-style:none;padding:0;margin:0}
.career-role li{margin-bottom:6px;font-size:.9rem;color:#b7c0d1}
.career-roadmap{display:grid;gap:16px;margin:24px 0}
.roadmap-phase{background:rgba(17,24,39,.6);border:1px solid rgba(34,197,94,.1);border-radius:12px;padding:20px}
.roadmap-phase h4{color:#22c55e;margin-bottom:12px}
.roadmap-phase ul{margin:0;padding-left:20px}
.studio-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin:24px 0}
.studio-card{background:rgba(17,24,39,.6);border-radius:12px;padding:20px}
.studio-card h4{font-size:1rem;margin-bottom:8px;color:#f5f7fa}
.studio-card p{font-size:.85rem;color:#7a8599;margin:4px 0}
.pillar-jobs{display:grid;gap:16px;margin-bottom:20px}
.pillar-job-card{background:rgba(17,24,39,.6);border:1px solid rgba(255,255,255,.06);border-radius:12px;padding:20px}
.pillar-job-card h3{font-size:1.1rem;margin-bottom:8px}
.pillar-job-card h3 a{color:#f5f7fa;text-decoration:none}
.pillar-job-card h3 a:hover{color:#22c55e}
.job-meta{font-size:.85rem;color:#7a8599}
.job-meta span{margin-right:16px}
.pillar-empty{background:rgba(255,255,255,.04);padding:24px;border-radius:8px;text-align:center;color:#7a8599}
.pillar-sidebar__sticky{position:sticky;top:100px}
.pillar-cta-box{background:linear-gradient(135deg,rgba(34,197,94,.2),rgba(34,197,94,.1));border:1px solid rgba(34,197,94,.2);border-radius:12px;padding:24px;text-align:center;margin-bottom:24px}
.pillar-cta-box h3{margin-bottom:8px;font-size:1.1rem}
.pillar-cta-box p{font-size:.9rem;color:#7a8599;margin-bottom:16px}
.pillar-btn{display:inline-block;padding:12px 24px;border-radius:8px;text-decoration:none!important;font-weight:600;transition:all .3s}
.pillar-btn--primary{background:#22c55e;color:#fff!important}
.pillar-btn--primary:hover{background:#16a34a;transform:translateY(-2px)}
.pillar-btn--outline{border:1px solid #22c55e;color:#22c55e!important;background:transparent}
.pillar-btn--outline:hover{background:rgba(34,197,94,.1)}
.pillar-related{background:rgba(17,24,39,.6);border-radius:12px;padding:20px}
.pillar-related h4{font-size:1rem;margin-bottom:12px}
.pillar-related ul{list-style:none;padding:0;margin:0}
.pillar-related li{margin-bottom:10px}
.pillar-related a{color:#7a8599;text-decoration:none}
.pillar-related a:hover{color:#22c55e}
@media(max-width:1024px){.pillar-layout{grid-template-columns:1fr}.pillar-sidebar{display:none}.career-roles,.studio-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:640px){.pillar-hero{padding:24px}.pillar-hero h1{font-size:1.8rem}.pillar-hero__stats{flex-direction:column;gap:8px}.career-roles,.studio-grid{grid-template-columns:1fr}}
</style>
@endpush
