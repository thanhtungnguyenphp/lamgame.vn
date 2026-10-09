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
