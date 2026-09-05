@extends('layouts.master')

@section('page_title', 'Học Unity từ A-Z — Hướng dẫn toàn diện cho Game Developer')
@section('page_description', 'Hướng dẫn học Unity từ cơ bản đến nâng cao. Tutorial, best practices, source code và việc làm Unity Developer tại Việt Nam.')

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "TechArticle",
    "headline": "Học Unity từ A-Z — Hướng dẫn toàn diện cho Game Developer",
    "description": "Hướng dẫn học Unity từ cơ bản đến nâng cao. Tutorial, best practices, source code và việc làm Unity Developer tại Việt Nam.",
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
<div class="pillar-page">
    <div class="container">
        {{-- Hero --}}
        <header class="pillar-hero">
            <nav class="pillar-breadcrumb">
                <a href="{{ url('/') }}">Trang chủ</a> / 
                <a href="{{ route('lamgame.blog') }}">Learn</a> / 
                <span>Unity</span>
            </nav>
            <h1>🎮 Học Unity từ A-Z</h1>
            <p class="pillar-hero__lead">
                Hướng dẫn toàn diện để trở thành Unity Developer. Từ cơ bản đến publish game lên store.
            </p>
            <div class="pillar-hero__stats">
                <span>📚 {{ $articleCount ?? 0 }} bài viết</span>
                <span>🎮 {{ $sourceCount ?? 0 }} source code</span>
                <span>💼 {{ $jobCount ?? 0 }} việc làm</span>
            </div>
        </header>

        <div class="pillar-layout">
            {{-- Main Content --}}
            <main class="pillar-main">
                {{-- TOC --}}
                <nav class="pillar-toc">
                    <h2>📑 Mục lục</h2>
                    <ol>
                        <li><a href="#unity-la-gi">Unity là gì?</a></li>
                        <li><a href="#bat-dau">Bắt đầu với Unity</a></li>
                        <li><a href="#co-ban">Kiến thức cơ bản</a></li>
                        <li><a href="#nang-cao">Kỹ thuật nâng cao</a></li>
                        <li><a href="#best-practices">Best Practices</a></li>
                        <li><a href="#source-code">Source Code</a></li>
                        <li><a href="#viec-lam">Việc làm Unity</a></li>
                        <li><a href="#tai-nguyen">Tài nguyên học tập</a></li>
                    </ol>
                </nav>

                {{-- Section: Unity là gì --}}
                <section id="unity-la-gi" class="pillar-section">
                    <h2>Unity là gì?</h2>
                    <p>
                        Unity là game engine phổ biến nhất thế giới, được sử dụng để phát triển game 2D, 3D, 
                        VR/AR trên nhiều nền tảng khác nhau. Với Unity, bạn có thể tạo game cho:
                    </p>
                    <ul>
                        <li><strong>Mobile:</strong> iOS, Android</li>
                        <li><strong>Desktop:</strong> Windows, macOS, Linux</li>
                        <li><strong>Console:</strong> PlayStation, Xbox, Nintendo Switch</li>
                        <li><strong>Web:</strong> WebGL</li>
                        <li><strong>XR:</strong> VR (Oculus, HTC Vive), AR (ARKit, ARCore)</li>
                    </ul>
                    <p>
                        Unity sử dụng C# làm ngôn ngữ lập trình chính, dễ học và có cộng đồng lớn.
                    </p>
                </section>

                {{-- Section: Bắt đầu --}}
                <section id="bat-dau" class="pillar-section">
                    <h2>Bắt đầu với Unity</h2>
                    <h3>1. Cài đặt Unity Hub</h3>
                    <p>
                        Tải Unity Hub từ <a href="https://unity.com/download" target="_blank" rel="noopener">unity.com/download</a> 
                        và cài đặt phiên bản Unity LTS mới nhất (2022 LTS hoặc Unity 6).
                    </p>
                    
                    <h3>2. Tạo project đầu tiên</h3>
                    <p>
                        Trong Unity Hub, chọn "New Project" → chọn template (2D, 3D, URP, HDRP) → đặt tên và tạo.
                    </p>
                    
                    <h3>3. Làm quen với Editor</h3>
                    <ul>
                        <li><strong>Scene View:</strong> Nơi bạn thiết kế game</li>
                        <li><strong>Game View:</strong> Preview game khi chạy</li>
                        <li><strong>Hierarchy:</strong> Danh sách GameObjects trong scene</li>
                        <li><strong>Inspector:</strong> Chi tiết và components của object</li>
                        <li><strong>Project:</strong> Assets và files</li>
                    </ul>
                </section>

                {{-- Section: Cơ bản --}}
                <section id="co-ban" class="pillar-section">
                    <h2>Kiến thức cơ bản</h2>
                    
                    <h3>GameObject & Components</h3>
                    <p>
                        Mọi thứ trong Unity đều là GameObject. Mỗi GameObject có thể gắn nhiều Components 
                        như Transform, Renderer, Collider, Script, v.v.
                    </p>
                    
                    <h3>Scripting với C#</h3>
                    <p>
                        Scripts trong Unity kế thừa từ MonoBehaviour. Các methods quan trọng:
                    </p>
                    <ul>
                        <li><code>Start()</code> — Chạy một lần khi object được enable</li>
                        <li><code>Update()</code> — Chạy mỗi frame</li>
                        <li><code>FixedUpdate()</code> — Chạy theo physics timestep</li>
                        <li><code>OnCollisionEnter()</code> — Khi va chạm</li>
                    </ul>
                    
                    <h3>Bài viết liên quan</h3>
                    <div class="pillar-articles">
                        @forelse($unityArticles ?? [] as $article)
                        <a href="{{ route('blog.show', $article->slug) }}" class="pillar-article-card">
                            <h4>{{ $article->name }}</h4>
                            <span>{{ $article->reading_time ?? 5 }} phút đọc</span>
                        </a>
                        @empty
                        <p>Chưa có bài viết. <a href="{{ route('lamgame.blog') }}?category=unity-development">Xem tất cả bài Unity →</a></p>
                        @endforelse
                    </div>
                </section>

                {{-- Section: Nâng cao --}}
                <section id="nang-cao" class="pillar-section">
                    <h2>Kỹ thuật nâng cao</h2>
                    <ul>
                        <li><strong>Addressables:</strong> Quản lý assets hiệu quả</li>
                        <li><strong>Networking:</strong> Multiplayer với Netcode/Mirror</li>
                        <li><strong>Shader Graph:</strong> Visual shader editor</li>
                        <li><strong>DOTS/ECS:</strong> Data-Oriented Tech Stack</li>
                        <li><strong>Optimization:</strong> Profiling, memory, batching</li>
                    </ul>
                </section>

                {{-- Section: Best Practices --}}
                <section id="best-practices" class="pillar-section">
                    <h2>Best Practices</h2>
                    <ul>
                        <li>Sử dụng Object Pooling thay vì Instantiate/Destroy liên tục</li>
                        <li>Tránh GetComponent trong Update, cache references</li>
                        <li>Sử dụng ScriptableObjects cho data</li>
                        <li>Organize project theo folder structure chuẩn</li>
                        <li>Version control với Git LFS cho large assets</li>
                    </ul>
                </section>

                {{-- Section: Source Code --}}
                <section id="source-code" class="pillar-section">
                    <h2>🎮 Source Code Unity</h2>
                    <p>Tiết kiệm thời gian với source code Unity sẵn có:</p>
                    <div class="pillar-sources">
                        @forelse($unitySources ?? [] as $source)
                        <a href="{{ $source['url'] }}" class="pillar-source-card">
                            <img src="{{ $source['thumbnail'] }}" alt="{{ $source['title'] }}" loading="lazy">
                            <div>
                                <h4>{{ $source['title'] }}</h4>
                                <span>{{ $source['is_free'] ? 'Miễn phí' : number_format($source['price'], 0, ',', '.') . 'đ' }}</span>
                            </div>
                        </a>
                        @empty
                        <p><a href="{{ route('lamgame.source-game') }}?engine=unity">Xem tất cả Unity Source →</a></p>
                        @endforelse
                    </div>
                </section>

                {{-- Section: Việc làm --}}
                <section id="viec-lam" class="pillar-section">
                    <h2>💼 Việc làm Unity Developer</h2>
                    <p>
                        Unity Developer là một trong những vị trí hot nhất ngành game tại Việt Nam. 
                        Mức lương trung bình từ 15-40 triệu/tháng tùy kinh nghiệm.
                    </p>
                    <a href="{{ route('lamgame.viec-lam-game') }}?keyword=unity" class="pillar-btn">
                        Xem {{ $jobCount ?? 0 }}+ việc làm Unity →
                    </a>
                </section>

                {{-- Section: Tài nguyên --}}
                <section id="tai-nguyen" class="pillar-section">
                    <h2>📚 Tài nguyên học tập</h2>
                    <ul>
                        <li><a href="https://learn.unity.com" target="_blank" rel="noopener">Unity Learn</a> — Official tutorials</li>
                        <li><a href="https://docs.unity.com" target="_blank" rel="noopener">Unity Documentation</a> — API reference</li>
                        <li><a href="{{ route('forum.index') }}">LamGame Forum</a> — Hỏi đáp cộng đồng</li>
                        <li><a href="{{ route('lamgame.blog') }}?category=unity-development">Unity Blog</a> — Bài viết mới nhất</li>
                    </ul>
                </section>

                {{-- Last Updated --}}
                <footer class="pillar-footer">
                    <p><strong>Cập nhật lần cuối:</strong> {{ now()->format('d/m/Y') }}</p>
                    <p>
                        Bài viết này được biên soạn bởi <a href="{{ route('authors.index') }}">đội ngũ LamGame</a> 
                        với mục đích cung cấp hướng dẫn toàn diện cho người học Unity tại Việt Nam.
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
                    <h3>📊 Unity tại LamGame</h3>
                    <ul>
                        <li>{{ $articleCount ?? 0 }} bài viết</li>
                        <li>{{ $sourceCount ?? 0 }} source code</li>
                        <li>{{ $jobCount ?? 0 }} việc làm</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</div>

<style>
.pillar-page { padding: 2rem 0; }
.pillar-hero { text-align: center; margin-bottom: 3rem; padding-bottom: 2rem; border-bottom: 1px solid #eee; }
.pillar-hero h1 { font-size: 2.5rem; margin-bottom: 1rem; }
.pillar-hero__lead { font-size: 1.25rem; color: #666; max-width: 700px; margin: 0 auto 1.5rem; }
.pillar-hero__stats { display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap; }
.pillar-hero__stats span { background: #f3f4f6; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; }

.pillar-layout { display: grid; grid-template-columns: 1fr 280px; gap: 3rem; }
@media (max-width: 900px) { .pillar-layout { grid-template-columns: 1fr; } }

.pillar-toc { background: #f9fafb; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; }
.pillar-toc h2 { font-size: 1rem; margin-bottom: 1rem; }
.pillar-toc ol { padding-left: 1.25rem; margin: 0; }
.pillar-toc li { margin-bottom: 0.5rem; }
.pillar-toc a { color: #667eea; text-decoration: none; }
.pillar-toc a:hover { text-decoration: underline; }

.pillar-section { margin-bottom: 3rem; }
.pillar-section h2 { font-size: 1.5rem; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #667eea; }
.pillar-section h3 { font-size: 1.1rem; margin: 1.5rem 0 0.75rem; }
.pillar-section p { line-height: 1.7; margin-bottom: 1rem; }
.pillar-section ul, .pillar-section ol { padding-left: 1.5rem; margin-bottom: 1rem; }
.pillar-section li { margin-bottom: 0.5rem; line-height: 1.6; }
.pillar-section code { background: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 0.9em; }
.pillar-section a { color: #667eea; }

.pillar-articles { display: grid; gap: 1rem; margin-top: 1rem; }
.pillar-article-card { display: block; padding: 1rem; border: 1px solid #eee; border-radius: 8px; text-decoration: none; color: inherit; transition: all 0.2s; }
.pillar-article-card:hover { border-color: #667eea; box-shadow: 0 2px 8px rgba(102,126,234,0.1); }
.pillar-article-card h4 { margin: 0 0 0.25rem; font-size: 1rem; }
.pillar-article-card span { font-size: 0.85rem; color: #888; }

.pillar-sources { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem; }
.pillar-source-card { display: flex; flex-direction: column; border: 1px solid #eee; border-radius: 8px; overflow: hidden; text-decoration: none; color: inherit; }
.pillar-source-card img { width: 100%; height: 120px; object-fit: cover; }
.pillar-source-card > div { padding: 0.75rem; }
.pillar-source-card h4 { margin: 0 0 0.25rem; font-size: 0.9rem; }
.pillar-source-card span { font-size: 0.8rem; color: #10b981; font-weight: 600; }

.pillar-btn { display: inline-block; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 8px; text-decoration: none; font-weight: 600; margin-top: 1rem; }
.pillar-btn:hover { opacity: 0.9; }

.pillar-sidebar__card { background: #f9fafb; padding: 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; }
.pillar-sidebar__card h3 { font-size: 1rem; margin-bottom: 1rem; }
.pillar-sidebar__card ul { list-style: none; padding: 0; margin: 0; }
.pillar-sidebar__card li { margin-bottom: 0.5rem; }
.pillar-sidebar__card a { color: #667eea; text-decoration: none; }
.pillar-sidebar__card a:hover { text-decoration: underline; }

.pillar-footer { margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid #eee; color: #666; font-size: 0.9rem; }
</style>
@endsection
