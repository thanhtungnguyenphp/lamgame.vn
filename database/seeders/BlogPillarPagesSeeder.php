<?php

namespace Database\Seeders;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BlogPillarPagesSeeder extends Seeder
{
    public function run()
    {
        $pillars = [
            [
                'title' => 'Học làm game từ A-Z — Lộ trình hoàn chỉnh cho người mới 2026',
                'slug' => 'hoc-lam-game-tu-a-z-lo-trinh-2026',
                'category' => 'game-dev-tutorial',
                'tags' => 'pillar,beginner,roadmap,game-dev,tutorial,learning',
                'meta_description' => 'Lộ trình học làm game hoàn chỉnh 2026: từ zero đến publish. Tổng hợp tutorials, tools, resources miễn phí cho game developer Việt Nam.',
                'short_description' => 'Trang tổng hợp lộ trình học game development từ A-Z. Tất cả tutorials, tools, resources cần thiết.',
                'description' => '<h2>🎮 Lộ trình học Game Development 2026</h2><p>Đây là trang tổng hợp TẤT CẢ resources bạn cần để trở thành game developer. Từ beginner đến publish game đầu tiên.</p><h2>Phase 1: Nền tảng (Tuần 1-4)</h2><ul><li>📖 <a href="/blog/top-5-nguon-hoc-lam-game-mien-phi-tieng-viet-2026">Top 5 nguồn học làm game miễn phí</a></li><li>📖 <a href="/blog/so-sanh-unity-vs-godot-2026-nen-chon-engine-nao">Chọn game engine: Unity vs Godot</a></li><li>📖 <a href="/blog/typescript-cho-game-dev-tai-sao-va-bat-dau">TypeScript cho Game Dev</a></li></ul><h2>Phase 2: Tutorials thực hành (Tuần 5-8)</h2><ul><li>📖 <a href="/blog/huong-dan-lam-game-flappy-bird-phaser-3">Làm game Flappy Bird (Phaser 3)</a></li><li>📖 <a href="/blog/match-3-game-engine-xay-dung-tu-dau-phaser-3">Xây dựng Match-3 engine</a></li><li>📖 <a href="/blog/xay-dung-game-multiplayer-online-websocket-phaser-3">Game Multiplayer Online</a></li><li>📖 <a href="/blog/cach-tao-ai-opponent-game-co-minimax-algorithm">Tạo AI opponent (Minimax)</a></li><li>📖 <a href="/blog/huong-dan-tich-hop-leaderboard-realtime-game-html5">Tích hợp Leaderboard</a></li></ul><h2>Phase 3: Polish & Performance (Tuần 9-10)</h2><ul><li>📖 <a href="/blog/toi-uu-hieu-nang-game-html5-10-tips">Tối ưu hiệu năng HTML5 — 10 tips</a></li><li>📖 <a href="/blog/huong-dan-tao-game-design-document-gdd-indie">Tạo Game Design Document</a></li></ul><h2>Phase 4: Publish & Monetize (Tuần 11-12)</h2><ul><li>📖 <a href="/blog/huong-dan-publish-game-google-play-2026">Publish lên Google Play</a></li><li>📖 <a href="/blog/cach-kiem-tien-tu-game-html5-admob-iap">Kiếm tiền từ game HTML5</a></li></ul><h2>Tools & Resources</h2><ul><li>🎮 <a href="/choi-game">49+ game HTML5 demos</a></li><li>📦 <a href="/source-game">13 source code game miễn phí</a></li><li>🤖 <a href="/ai-tools">AI Tools: GDD, Asset, Story Generator</a></li><li>💬 <a href="/forum">Cộng đồng LamGame Forum</a></li><li>💼 <a href="/viec-lam-game">Việc làm Game Developer</a></li></ul><h2>FAQ</h2><h3>Học làm game mất bao lâu?</h3><p>Với lộ trình này, bạn có thể publish game đầu tiên trong 2-3 tháng nếu học 1-2 giờ/ngày.</p><h3>Cần biết toán giỏi không?</h3><p>Không cần cao siêu. Cần: cộng trừ nhân chia, tọa độ (x,y), logic cơ bản. Học thêm dần khi cần.</p><h3>Nên học Unity hay Phaser?</h3><p>Phaser cho web games (nhanh, nhẹ). Unity cho mobile/PC games (mạnh, nhiều job). Xem <a href="/blog/so-sanh-unity-vs-godot-2026-nen-chon-engine-nao">bài so sánh chi tiết</a>.</p>',
            ],
            [
                'title' => 'AI cho Game Developer — Tổng hợp công cụ và hướng dẫn 2026',
                'slug' => 'ai-cho-game-developer-tong-hop-2026',
                'category' => 'ai-tools',
                'tags' => 'pillar,ai,tools,game-dev,productivity,2026',
                'meta_description' => 'Tổng hợp tất cả công cụ AI và hướng dẫn sử dụng AI trong game development 2026. Từ tạo assets đến code generation.',
                'short_description' => 'Trang tổng hợp tất cả về AI trong game development: tools, tutorials, workflows, best practices.',
                'description' => '<h2>🤖 AI Revolution trong Game Development</h2><p>AI đang thay đổi cách làm game. Trang này tổng hợp mọi thứ bạn cần biết về AI cho game dev.</p><h2>AI Tools trên LamGame</h2><ul><li>🖼️ <a href="/ai-tools#asset">Asset Generator</a> — Tạo sprites, tilesets, UI bằng AI</li><li>📝 <a href="/ai-tools#gdd">GDD Generator</a> — Tạo Game Design Document tự động</li><li>💡 <a href="/ai-tools#name">Name Generator</a> — Đặt tên game sáng tạo</li><li>📖 <a href="/ai-tools#story">Story Writer</a> — Viết cốt truyện, quest, dialogue</li></ul><h2>Bài viết hướng dẫn</h2><ul><li>📖 <a href="/blog/10-cong-cu-ai-mien-phi-cho-game-developer-2026">10 công cụ AI miễn phí cho Game Dev</a></li><li>📖 <a href="/blog/cach-tao-ai-opponent-game-co-minimax-algorithm">Tạo AI opponent (Minimax Algorithm)</a></li><li>📖 <a href="/blog/xu-huong-game-dev-2026-ai-indie-cloud">Xu hướng AI trong Game Dev 2026</a></li></ul><h2>Workflows thực tế</h2><h3>AI-Assisted Game Jam (48h)</h3><ol><li><strong>Giờ 0-2:</strong> Dùng GDD Generator tạo concept</li><li><strong>Giờ 2-8:</strong> AI generate sprites + background</li><li><strong>Giờ 8-36:</strong> Code với GitHub Copilot assist</li><li><strong>Giờ 36-44:</strong> AI generate music + SFX</li><li><strong>Giờ 44-48:</strong> Polish + publish</li></ol><h3>Solo Dev Monthly Sprint</h3><ol><li><strong>Tuần 1:</strong> Design (GDD Generator + Story Writer)</li><li><strong>Tuần 2-3:</strong> Development (Copilot + Asset Generator)</li><li><strong>Tuần 4:</strong> Polish + Publish (AI testing, ASO)</li></ol><h2>FAQ</h2><h3>AI có thay thế game developer không?</h3><p>Không. AI là tool, không phải replacement. Developer giỏi dùng AI = output x5. Developer không biết AI = bị tụt lại.</p><h3>AI-generated assets có bị copyright không?</h3><p>Đa số AI tools hiện tại cho commercial license. Luôn đọc ToS của từng tool. Self-hosted (Stable Diffusion) an toàn nhất.</p>',
            ],
            [
                'title' => 'Career Game Developer Việt Nam — Hướng dẫn toàn diện',
                'slug' => 'career-game-developer-viet-nam-huong-dan',
                'category' => 'career',
                'tags' => 'pillar,career,vietnam,salary,job,path,interview',
                'meta_description' => 'Hướng dẫn toàn diện career game developer Việt Nam: lương, kỹ năng, công ty, lộ trình, phỏng vấn. Cập nhật 2026.',
                'short_description' => 'Trang tổng hợp career game developer VN: lương, skills, companies, interview tips, career path.',
                'description' => '<h2>💼 Career Game Developer Việt Nam 2026</h2><p>Tất cả thông tin bạn cần để xây dựng sự nghiệp game developer tại Việt Nam.</p><h2>Bài viết chuyên sâu</h2><ul><li>📖 <a href="/blog/luong-game-developer-viet-nam-2026">Bảng lương Game Dev VN 2026</a></li><li>📖 <a href="/blog/con-duong-tro-thanh-game-developer-viet-nam-2026">Lộ trình từ zero đến pro</a></li><li>📖 <a href="/blog/xu-huong-game-dev-2026-ai-indie-cloud">Xu hướng ngành 2026</a></li><li>📖 <a href="/blog/top-5-nguon-hoc-lam-game-mien-phi-tieng-viet-2026">Nguồn học miễn phí</a></li></ul><h2>Bảng lương nhanh (VNĐ/tháng)</h2><table><tr><th>Level</th><th>Unity</th><th>Unreal</th><th>Web/HTML5</th></tr><tr><td>Fresher</td><td>8-12M</td><td>10-15M</td><td>10-14M</td></tr><tr><td>Junior</td><td>15-22M</td><td>18-28M</td><td>16-25M</td></tr><tr><td>Mid</td><td>25-40M</td><td>30-50M</td><td>28-42M</td></tr><tr><td>Senior</td><td>40-70M</td><td>50-90M</td><td>42-65M</td></tr></table><h2>Top Companies</h2><ul><li>VNG Games, Amanotes, Topebox, Gameloft VN</li><li>Sky Mavis, Glass Egg, Sparx*</li><li>100+ indie studios (2-10 người)</li></ul><h2>Skills được trả premium 2026</h2><ul><li>AI/ML integration (+30-40%)</li><li>Blockchain gaming (+25-35%)</li><li>Multiplayer networking (+20-30%)</li><li>Technical Art (+20-25%)</li></ul><p>💼 <a href="/viec-lam-game">Xem việc làm mới nhất</a></p><p>📦 <a href="/source-game">Xây portfolio từ source game</a></p><p>💬 <a href="/forum">Networking tại Forum</a></p>',
            ],
            [
                'title' => 'Source Game Marketplace — 13+ game templates Phaser 3 miễn phí',
                'slug' => 'source-game-marketplace-phaser-3-mien-phi',
                'category' => 'game-dev-tutorial',
                'tags' => 'pillar,source-code,marketplace,phaser,free,download',
                'meta_description' => 'Download 13+ source game templates miễn phí: Phaser 3 + TypeScript. 2048, Tetris, Flappy Bird, Snake, Match-3, Chess, và hơn thế.',
                'short_description' => 'Trang tổng hợp tất cả source game templates miễn phí trên LamGame. Download, customize, và publish.',
                'description' => '<h2>📦 Source Game Marketplace</h2><p>Tất cả source code game miễn phí, xây dựng bằng <strong>Phaser 3 + TypeScript</strong>, sẵn sàng customize và publish.</p><h2>13 Games có sẵn</h2><table><tr><th>Game</th><th>Genre</th><th>Demo</th><th>Download</th></tr><tr><td>2048 Ghép Số</td><td>Puzzle</td><td><a href="/choi-game/2048-ghep-so">Chơi</a></td><td><a href="/source-game">Source</a></td></tr><tr><td>Xếp Gạch (Tetris)</td><td>Puzzle</td><td><a href="/choi-game/xep-gach-kinh-dien">Chơi</a></td><td><a href="/source-game">Source</a></td></tr><tr><td>Chim Bay (Flappy)</td><td>Arcade</td><td><a href="/choi-game/chim-bay-vuot-ong">Chơi</a></td><td><a href="/source-game">Source</a></td></tr><tr><td>Rắn Săn Mồi</td><td>Arcade</td><td><a href="/choi-game/ran-san-moi">Chơi</a></td><td><a href="/source-game">Source</a></td></tr><tr><td>Kẹo Ngọt Xếp 3</td><td>Puzzle</td><td><a href="/choi-game/keo-ngot-xep-3">Chơi</a></td><td><a href="/source-game">Source</a></td></tr><tr><td>Cờ Vua Online</td><td>Board</td><td><a href="/choi-game/co-vua-online">Chơi</a></td><td><a href="/source-game">Source</a></td></tr><tr><td>Sudoku Vui</td><td>Puzzle</td><td><a href="/choi-game/sudoku-vui">Chơi</a></td><td><a href="/source-game">Source</a></td></tr><tr><td>Nhảy Hình Học</td><td>Platformer</td><td><a href="/choi-game/nhay-hinh-hoc">Chơi</a></td><td><a href="/source-game">Source</a></td></tr><tr><td>Lật Thẻ Nhớ</td><td>Memory</td><td><a href="/choi-game/lat-the-nho">Chơi</a></td><td><a href="/source-game">Source</a></td></tr><tr><td>Dò Mìn</td><td>Puzzle</td><td><a href="/choi-game/do-min">Chơi</a></td><td><a href="/source-game">Source</a></td></tr><tr><td>Đoán Chữ</td><td>Word</td><td><a href="/choi-game/doan-chu">Chơi</a></td><td><a href="/source-game">Source</a></td></tr><tr><td>Phòng Thủ</td><td>Strategy</td><td><a href="/choi-game/phong-thu">Chơi</a></td><td><a href="/source-game">Source</a></td></tr><tr><td>Simon Nói</td><td>Memory</td><td><a href="/choi-game/simon-noi">Chơi</a></td><td><a href="/source-game">Source</a></td></tr></table><h2>Tech Stack</h2><ul><li><strong>Phaser 3</strong> — HTML5 game framework</li><li><strong>TypeScript</strong> — type-safe code</li><li><strong>Vite</strong> — fast build tool</li><li><strong>pnpm Monorepo</strong> — shared code architecture</li><li><strong>Capacitor</strong> — wrap thành Android app</li><li><strong>Leaderboard API</strong> — Redis-based ranking</li></ul><h2>Hướng dẫn sử dụng</h2><ol><li>Clone repo: <code>git clone https://github.com/lamgame/games.git</code></li><li>Install: <code>pnpm install</code></li><li>Dev: <code>GAME=2048-ghep-so pnpm dev</code></li><li>Customize assets + logic</li><li>Build: <code>pnpm build:all</code></li><li>Deploy hoặc wrap Android</li></ol><h2>Bài viết liên quan</h2><ul><li>📖 <a href="/blog/review-13-source-game-phaser-3-mien-phi-lamgame">Review chi tiết 13 source games</a></li><li>📖 <a href="/blog/huong-dan-publish-game-google-play-2026">Publish lên Google Play</a></li><li>📖 <a href="/blog/cach-kiem-tien-tu-game-html5-admob-iap">Kiếm tiền từ game HTML5</a></li></ul><p>🤖 <a href="/ai-tools">Dùng AI tạo assets cho game</a></p><p>💬 <a href="/forum">Hỏi đáp tại Forum</a></p>',
            ],
        ];

        $now = Carbon::now();
        foreach ($pillars as $i => $p) {
            if (Blog::where('slug', $p['slug'])->exists()) {
                echo "⏭️ Skip: {$p['slug']}\n";
                continue;
            }
            Blog::create([
                'name' => $p['title'],
                'slug' => $p['slug'],
                'short_description' => $p['short_description'],
                'description' => $p['description'],
                'channels' => 'default',
                'default_category' => $p['category'],
                'tags' => $p['tags'],
                'author' => 'LamGame Team',
                'author_id' => 1,
                'locale' => 'vi',
                'status' => 1,
                'allow_comments' => 1,
                'meta_title' => $p['title'] . ' | LamGame',
                'meta_description' => $p['meta_description'],
                'meta_keywords' => $p['tags'],
                'published_at' => $now->subHours($i * 2),
            ]);
            echo "✅ {$p['title']}\n";
        }
        echo "\n🎉 4 Pillar Pages created!\n";
    }
}
