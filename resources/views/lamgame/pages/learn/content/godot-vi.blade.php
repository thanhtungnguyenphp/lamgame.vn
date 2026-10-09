<div class="pillar-page pillar-page--godot">
    <div class="container">
        {{-- Hero --}}
        <header class="pillar-hero">
            <nav class="pillar-breadcrumb">
                <a href="{{ url('/') }}">Trang chủ</a> / 
                <a href="{{ route('lamgame.blog') }}">Learn</a> / 
                <span>Godot</span>
            </nav>
            <h1>🤖 Học Godot từ A-Z</h1>
            <p class="pillar-hero__lead">
                Game engine miễn phí, open-source với GDScript dễ học. Lựa chọn hoàn hảo cho indie developer.
            </p>
            <div class="pillar-hero__stats">
                <span>📚 {{ $articleCount ?? 0 }} bài viết</span>
                <span>🎮 {{ $sourceCount ?? 0 }} source code</span>
                <span>🆓 100% miễn phí</span>
            </div>
        </header>

        <div class="pillar-layout">
            {{-- Main Content --}}
            <main class="pillar-main">
                {{-- TOC --}}
                <nav class="pillar-toc">
                    <h2>📑 Mục lục</h2>
                    <ol>
                        <li><a href="#godot-la-gi">Godot là gì?</a></li>
                        <li><a href="#tai-sao-godot">Tại sao chọn Godot?</a></li>
                        <li><a href="#cai-dat">Cài đặt Godot 4</a></li>
                        <li><a href="#gdscript">Học GDScript</a></li>
                        <li><a href="#game-2d">Làm game 2D</a></li>
                        <li><a href="#game-3d">Làm game 3D</a></li>
                        <li><a href="#export">Export & Publish</a></li>
                        <li><a href="#tai-nguyen">Tài nguyên</a></li>
                    </ol>
                </nav>

                {{-- Section: Godot là gì --}}
                <section id="godot-la-gi" class="pillar-section">
                    <h2>Godot là gì?</h2>
                    <p>
                        <strong>Godot Engine</strong> là game engine miễn phí, mã nguồn mở, được phát triển bởi cộng đồng. 
                        Với phiên bản Godot 4, engine này đã trở thành đối thủ cạnh tranh trực tiếp với Unity và Unreal 
                        cho các dự án indie và mid-size.
                    </p>
                    <div class="pillar-highlight">
                        <h4>💡 Điểm nổi bật của Godot 4</h4>
                        <ul>
                            <li>100% miễn phí, không royalty</li>
                            <li>GDScript dễ học như Python</li>
                            <li>Hỗ trợ C# cho Unity developer chuyển đổi</li>
                            <li>Vulkan renderer cho đồ họa hiện đại</li>
                            <li>Lightweight (~100MB) so với Unity (~2GB+)</li>
                        </ul>
                    </div>
                </section>

                {{-- Section: Tại sao chọn Godot --}}
                <section id="tai-sao-godot" class="pillar-section">
                    <h2>Tại sao chọn Godot?</h2>
                    
                    <h3>So sánh Godot vs Unity</h3>
                    <table class="pillar-table">
                        <thead>
                            <tr>
                                <th>Tiêu chí</th>
                                <th>Godot 4</th>
                                <th>Unity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Giá</td>
                                <td>Miễn phí mãi mãi</td>
                                <td>Free tier, sau đó trả phí</td>
                            </tr>
                            <tr>
                                <td>Ngôn ngữ</td>
                                <td>GDScript, C#, C++</td>
                                <td>C#</td>
                            </tr>
                            <tr>
                                <td>Dung lượng</td>
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
                                <td>Cộng đồng VN</td>
                                <td>Đang phát triển</td>
                                <td>Lớn</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <p>
                        <strong>Kết luận:</strong> Godot phù hợp cho indie developer, game 2D, hoặc những ai muốn 
                        thoát khỏi lo ngại về licensing của Unity.
                    </p>
                </section>

                {{-- Section: Cài đặt --}}
                <section id="cai-dat" class="pillar-section">
                    <h2>Cài đặt Godot 4</h2>
                    
                    <h3>Bước 1: Tải Godot</h3>
                    <p>
                        Truy cập <a href="https://godotengine.org/download" target="_blank" rel="noopener">godotengine.org/download</a> 
                        và tải phiên bản phù hợp:
                    </p>
                    <ul>
                        <li><strong>Godot 4.x Standard:</strong> Dùng GDScript (khuyến nghị cho người mới)</li>
                        <li><strong>Godot 4.x .NET:</strong> Dùng C# (cho Unity developer)</li>
                    </ul>
                    
                    <h3>Bước 2: Chạy Godot</h3>
                    <p>
                        Godot là portable app, không cần cài đặt. Giải nén và chạy trực tiếp file executable.
                    </p>
                    
                    <h3>Bước 3: Tạo project đầu tiên</h3>
                    <p>
                        Mở Godot → "New Project" → chọn renderer (Forward+ cho 3D, Compatibility cho mobile) → Create.
                    </p>
                </section>

                {{-- Section: GDScript --}}
                <section id="gdscript" class="pillar-section">
                    <h2>Học GDScript</h2>
                    <p>
                        GDScript là ngôn ngữ scripting của Godot, cú pháp giống Python nhưng tối ưu cho game development.
                    </p>
                    
                    <h3>Hello World</h3>
                    <pre><code class="language-gdscript">extends Node

func _ready():
    print("Hello, Godot!")
    
func _process(delta):
    # Chạy mỗi frame
    pass</code></pre>
                    
                    <h3>Các khái niệm cơ bản</h3>
                    <ul>
                        <li><strong>Node:</strong> Đơn vị cơ bản trong Godot (như GameObject trong Unity)</li>
                        <li><strong>Scene:</strong> Tập hợp các Nodes (như Prefab)</li>
                        <li><strong>Signal:</strong> Event system (như UnityEvent)</li>
                        <li><strong>_ready():</strong> Gọi khi node được thêm vào scene</li>
                        <li><strong>_process(delta):</strong> Gọi mỗi frame (như Update())</li>
                        <li><strong>_physics_process(delta):</strong> Gọi mỗi physics tick (như FixedUpdate())</li>
                    </ul>
                </section>

                {{-- Section: Game 2D --}}
                <section id="game-2d" class="pillar-section">
                    <h2>Làm game 2D với Godot</h2>
                    <p>
                        Godot được đánh giá là một trong những engine tốt nhất cho game 2D nhờ hệ thống 2D native 
                        (không phải 3D ortho như Unity).
                    </p>
                    
                    <h3>Các Node 2D quan trọng</h3>
                    <ul>
                        <li><strong>Sprite2D:</strong> Hiển thị hình ảnh</li>
                        <li><strong>CharacterBody2D:</strong> Character controller (thay KinematicBody2D)</li>
                        <li><strong>RigidBody2D:</strong> Physics body</li>
                        <li><strong>TileMap:</strong> Tạo level từ tiles</li>
                        <li><strong>AnimationPlayer:</strong> Animation system</li>
                        <li><strong>Area2D:</strong> Trigger zones</li>
                    </ul>
                    
                    <h3>Tutorial: Platformer cơ bản</h3>
                    <p>Xem hướng dẫn chi tiết trong các bài viết bên dưới.</p>
                </section>

                {{-- Section: Game 3D --}}
                <section id="game-3d" class="pillar-section">
                    <h2>Làm game 3D với Godot 4</h2>
                    <p>
                        Godot 4 với Vulkan renderer đã cải thiện đáng kể khả năng 3D. Phù hợp cho các dự án 
                        indie/mid-size, không yêu cầu AAA graphics.
                    </p>
                    
                    <h3>Các Node 3D quan trọng</h3>
                    <ul>
                        <li><strong>MeshInstance3D:</strong> Hiển thị 3D model</li>
                        <li><strong>CharacterBody3D:</strong> 3D character controller</li>
                        <li><strong>Camera3D:</strong> Camera</li>
                        <li><strong>DirectionalLight3D:</strong> Sun light</li>
                        <li><strong>WorldEnvironment:</strong> Sky, fog, post-processing</li>
                    </ul>
                </section>

                {{-- Section: Export --}}
                <section id="export" class="pillar-section">
                    <h2>Export & Publish Game</h2>
                    
                    <h3>Export Templates</h3>
                    <p>
                        Vào Editor → Manage Export Templates → Download để tải templates cho các platform.
                    </p>
                    
                    <h3>Platforms hỗ trợ</h3>
                    <ul>
                        <li>Windows, macOS, Linux</li>
                        <li>Android, iOS</li>
                        <li>Web (HTML5)</li>
                        <li>Consoles (cần license riêng)</li>
                    </ul>
                    
                    <h3>Publish lên Steam</h3>
                    <p>
                        Sử dụng GodotSteam addon để tích hợp Steamworks SDK.
                    </p>
                </section>

                {{-- Related Articles --}}
                <section id="bai-viet" class="pillar-section">
                    <h2>📚 Bài viết về Godot</h2>
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
                        Chúng tôi đang xây dựng thêm nội dung về Godot. 
                        <a href="{{ route('lamgame.blog', ['category' => 'programming']) }}">Xem bài viết Programming</a> 
                        trong khi chờ đợi.
                    </p>
                    @endif
                </section>

                {{-- Resources --}}
                <section id="tai-nguyen" class="pillar-section">
                    <h2>🔗 Tài nguyên học Godot</h2>
                    <div class="pillar-resources">
                        <div class="pillar-resource">
                            <h4>📖 Tài liệu chính thức</h4>
                            <ul>
                                <li><a href="https://docs.godotengine.org" target="_blank" rel="noopener">Godot Documentation</a></li>
                                <li><a href="https://gdquest.com" target="_blank" rel="noopener">GDQuest (Tutorial)</a></li>
                                <li><a href="https://kidscancode.org/godot_recipes/4.x/" target="_blank" rel="noopener">Godot Recipes</a></li>
                            </ul>
                        </div>
                        <div class="pillar-resource">
                            <h4>🎬 Video tiếng Việt</h4>
                            <ul>
                                <li><a href="{{ route('lamgame.blog', ['search' => 'godot']) }}">Bài viết Godot trên LamGame</a></li>
                            </ul>
                        </div>
                        <div class="pillar-resource">
                            <h4>💬 Cộng đồng</h4>
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
                        <h3>🚀 Bắt đầu với Godot</h3>
                        <p>Tải miễn phí, không cần đăng ký</p>
                        <a href="https://godotengine.org/download" target="_blank" rel="noopener" class="pillar-btn pillar-btn--primary">
                            Tải Godot 4 →
                        </a>
                    </div>

                    {{-- Related Pillars --}}
                    <div class="pillar-related">
                        <h4>Xem thêm</h4>
                        <ul>
                            <li><a href="{{ route('learn.unity') }}">🎮 Học Unity</a></li>
                            <li><a href="{{ route('learn.ai-game-dev') }}">🤖 AI cho Game Dev</a></li>
                            <li><a href="{{ route('lamgame.blog', ['category' => 'programming']) }}">💻 Programming</a></li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
