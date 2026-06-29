<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ForumSeedContent extends Command
{
    protected $signature = 'forum:seed-content {--count=20}';
    protected $description = 'Seed forum với nội dung chất lượng cho SEO';

    private array $customerIds = [21, 22, 23, 24, 25, 26, 28, 31];

    private array $categories = [
        'thao-luan' => null,
        'chia-se-y-tuong' => null,
        'tim-team' => null,
        'review-khoa-hoc' => null,
        'ho-tro-ky-thuat' => null,
        'showcase' => null,
        'tuyen-dung' => null,
        'game-jam' => null,
    ];

    private array $contentTemplates = [
        'thao-luan' => [
            ['title' => 'Unity hay Unreal Engine - nên chọn engine nào cho người mới?', 'tags' => 'unity,unreal,engine,beginner'],
            ['title' => 'Kinh nghiệm tối ưu hiệu năng game mobile Unity', 'tags' => 'unity,optimization,mobile,performance'],
            ['title' => 'Godot 4.x có thực sự thay thế được Unity cho indie dev?', 'tags' => 'godot,unity,indie,comparison'],
            ['title' => 'Lộ trình học lập trình game từ zero đến release đầu tiên', 'tags' => 'roadmap,beginner,career,gamedev'],
            ['title' => 'Xu hướng game 2026: AI-generated content và procedural design', 'tags' => 'trend,ai,procedural,2026'],
            ['title' => 'So sánh C# vs GDScript vs Lua cho game development', 'tags' => 'programming,csharp,gdscript,lua'],
            ['title' => 'Làm sao kiếm tiền từ game indie tại thị trường Việt Nam?', 'tags' => 'monetization,indie,vietnam,revenue'],
            ['title' => 'Multiplayer networking: Mirror vs Fishnet vs Netcode for GameObjects', 'tags' => 'multiplayer,networking,mirror,unity'],
            ['title' => 'Pixel art vs 3D low-poly: style nào phù hợp cho game mobile?', 'tags' => 'art-style,pixel-art,3d,mobile'],
            ['title' => 'ECS (Entity Component System) có thật sự cần thiết cho game nhỏ?', 'tags' => 'architecture,ecs,design-pattern,performance'],
            ['title' => 'Cách viết Game Design Document (GDD) hiệu quả', 'tags' => 'gdd,design,document,planning'],
            ['title' => 'Shaders trong Unity: từ cơ bản đến Shader Graph nâng cao', 'tags' => 'shader,unity,graphics,visual'],
            ['title' => 'Phỏng vấn game developer tại Việt Nam - câu hỏi thường gặp', 'tags' => 'interview,career,job,vietnam'],
        ],
        'chia-se-y-tuong' => [
            ['title' => 'Ý tưởng game tower defense kết hợp roguelike mechanics', 'tags' => 'idea,tower-defense,roguelike,concept'],
            ['title' => 'Game mô phỏng quán cà phê Việt Nam - concept design', 'tags' => 'idea,simulation,vietnam,cafe'],
            ['title' => 'Battle royale nhưng chỉ dùng puzzle để chiến đấu', 'tags' => 'idea,battle-royale,puzzle,unique'],
            ['title' => 'Idle game chủ đề xây dựng startup công nghệ', 'tags' => 'idea,idle,startup,tech'],
            ['title' => 'Game giáo dục dạy lập trình cho trẻ em bằng visual scripting', 'tags' => 'idea,education,kids,programming'],
            ['title' => 'Horror game set trong bối cảnh trường học Việt Nam', 'tags' => 'idea,horror,vietnam,school'],
            ['title' => 'Farming game + dungeon crawler: ban ngày trồng trọt, đêm chiến đấu', 'tags' => 'idea,farming,dungeon,hybrid'],
            ['title' => 'Game AR tìm kho báu ngoài đời thực tại các thành phố VN', 'tags' => 'idea,ar,treasure,vietnam'],
            ['title' => 'Card game chiến thuật lấy cảm hứng từ lịch sử Việt Nam', 'tags' => 'idea,card-game,history,vietnam'],
            ['title' => 'Ý tưởng game co-op online 4 người giải puzzle escape room', 'tags' => 'idea,coop,puzzle,escape-room'],
            ['title' => 'Game rhythm kết hợp nhạc Việt và indie music', 'tags' => 'idea,rhythm,music,vietnam'],
            ['title' => 'Survival game chủ đề hậu tận thế ở Đông Nam Á', 'tags' => 'idea,survival,post-apocalyptic,southeast-asia'],
        ],
        'ho-tro-ky-thuat' => [
            ['title' => '[Unity] Lỗi NullReferenceException khi Instantiate prefab - cách fix?', 'tags' => 'unity,bug,nullref,instantiate'],
            ['title' => '[Godot] Signal không hoạt động giữa 2 scenes - help!', 'tags' => 'godot,signal,scene,help'],
            ['title' => 'Cách implement A* pathfinding trên tilemap hexagonal', 'tags' => 'pathfinding,a-star,hex,algorithm'],
            ['title' => '[Unity] Build APK bị crash trên Android 14 - logcat error', 'tags' => 'unity,android,build,crash'],
            ['title' => 'Cách tối ưu drawcall khi có 1000+ sprites trên screen', 'tags' => 'optimization,drawcall,sprite,performance'],
            ['title' => '[Phaser 3] Load tilemap từ Tiled bị offset sai - bug hay config?', 'tags' => 'phaser,tiled,tilemap,bug'],
            ['title' => 'Cách implement save/load game data an toàn (encryption)', 'tags' => 'save-system,encryption,security,data'],
            ['title' => '[Unity] Cinemachine camera bị jitter khi follow player - fix?', 'tags' => 'unity,cinemachine,camera,jitter'],
            ['title' => 'WebGL build Unity quá nặng (50MB+) - cách giảm size?', 'tags' => 'unity,webgl,optimization,size'],
            ['title' => 'Implement inventory system với scriptable objects', 'tags' => 'unity,inventory,scriptable-objects,system'],
            ['title' => '[Godot] TileMap collision không detect đúng layer', 'tags' => 'godot,tilemap,collision,physics'],
            ['title' => 'Cách triển khai multiplayer game server trên VPS giá rẻ', 'tags' => 'multiplayer,server,vps,deployment'],
        ],
        'showcase' => [
            ['title' => '[Showcase] Mini RPG pixel art - 3 tháng solo dev', 'tags' => 'showcase,rpg,pixel-art,solo'],
            ['title' => '[Showcase] Game casual mobile đạt 10K downloads sau 1 tháng', 'tags' => 'showcase,mobile,casual,downloads'],
            ['title' => '[Showcase] Visual Novel engine tự build bằng Godot', 'tags' => 'showcase,visual-novel,godot,engine'],
            ['title' => '[Showcase] Procedural dungeon generator - open source', 'tags' => 'showcase,procedural,dungeon,open-source'],
            ['title' => '[Showcase] Game platformer Việt Nam - Hành trình qua các vùng miền', 'tags' => 'showcase,platformer,vietnam,culture'],
            ['title' => '[Showcase] AI NPC dialogue system sử dụng LLM', 'tags' => 'showcase,ai,npc,dialogue'],
            ['title' => '[Showcase] Game jam entry - 48h làm game từ 0', 'tags' => 'showcase,gamejam,48h,challenge'],
            ['title' => '[Showcase] Multiplayer card game - từ prototype đến beta', 'tags' => 'showcase,card-game,multiplayer,progress'],
            ['title' => '[Showcase] Mod Minecraft bằng Java - biome Việt Nam', 'tags' => 'showcase,minecraft,mod,vietnam'],
            ['title' => '[Showcase] 2D fighting game frame-by-frame animation', 'tags' => 'showcase,fighting,2d,animation'],
        ],
        'tim-team' => [
            ['title' => 'Tìm artist 2D pixel cho game RPG (remote, part-time)', 'tags' => 'recruit,artist,pixel-art,rpg'],
            ['title' => 'Cần programmer Unity C# cho dự án mobile multiplayer', 'tags' => 'recruit,programmer,unity,mobile'],
            ['title' => 'Tìm sound designer cho horror game indie', 'tags' => 'recruit,sound,horror,indie'],
            ['title' => 'Team 3 người tìm thêm game designer - project casual game', 'tags' => 'recruit,designer,casual,team'],
            ['title' => 'Cần writer viết story cho visual novel 18+', 'tags' => 'recruit,writer,visual-novel,story'],
            ['title' => 'Tìm QA tester cho game mobile (Android + iOS)', 'tags' => 'recruit,qa,tester,mobile'],
            ['title' => 'Backend developer (Node.js) cho game multiplayer real-time', 'tags' => 'recruit,backend,nodejs,multiplayer'],
            ['title' => 'Tìm partner làm game AR/VR - có funding sẵn', 'tags' => 'recruit,partner,ar,vr'],
        ],
        'review-khoa-hoc' => [
            ['title' => 'Review khóa Complete C# Unity Game Developer (Udemy)', 'tags' => 'review,course,unity,udemy'],
            ['title' => 'So sánh 5 khóa học Godot 4 tốt nhất 2026', 'tags' => 'review,course,godot,comparison'],
            ['title' => 'Review sách "Game Programming Patterns" - có đáng đọc?', 'tags' => 'review,book,design-patterns,programming'],
            ['title' => 'Khóa học Blender cho game artist - top 3 recommendations', 'tags' => 'review,course,blender,3d-art'],
            ['title' => 'Review GameDev.tv bundle - worth it cho beginner?', 'tags' => 'review,course,gamedev-tv,beginner'],
            ['title' => 'Học game dev qua YouTube vs khóa trả phí - kinh nghiệm thực tế', 'tags' => 'review,youtube,free,paid'],
        ],
        'tuyen-dung' => [
            ['title' => '[Tuyển] Senior Unity Developer - Studio A (HCM, 25-40M)', 'tags' => 'job,unity,senior,hcm'],
            ['title' => '[Tuyển] 3D Artist cho game mobile - Remote fulltime', 'tags' => 'job,3d-artist,mobile,remote'],
            ['title' => '[Tuyển] Game Producer - startup gaming Hà Nội', 'tags' => 'job,producer,hanoi,startup'],
            ['title' => '[Tuyển] Technical Artist (Shader/VFX) - AAA studio VN', 'tags' => 'job,tech-artist,shader,aaa'],
            ['title' => '[Tuyển] Intern Game Programmer - cơ hội cho sinh viên', 'tags' => 'job,intern,student,opportunity'],
            ['title' => '[Tuyển] QA Lead cho MMORPG project - competitive salary', 'tags' => 'job,qa-lead,mmorpg,salary'],
        ],
        'game-jam' => [
            ['title' => 'Ludum Dare 57 sắp tới - ai tham gia team VN?', 'tags' => 'gamejam,ludum-dare,team,vietnam'],
            ['title' => 'Tips & tricks cho game jam: hoàn thành game trong 48h', 'tags' => 'gamejam,tips,48h,productivity'],
            ['title' => 'Post-mortem: game jam entry của mình - lessons learned', 'tags' => 'gamejam,post-mortem,lessons,experience'],
            ['title' => 'Tổ chức Game Jam nội bộ cho cộng đồng LamGame', 'tags' => 'gamejam,community,lamgame,event'],
            ['title' => 'GMTK Game Jam 2026 - theme predictions và chuẩn bị', 'tags' => 'gamejam,gmtk,2026,preparation'],
            ['title' => 'Game jam cho beginner: bắt đầu từ đâu, scope thế nào?', 'tags' => 'gamejam,beginner,scope,guide'],
        ],
    ];

    public function handle(): void
    {
        // Load category IDs
        $cats = DB::table('forum_categories')->pluck('id', 'slug');
        foreach ($cats as $slug => $id) {
            if (array_key_exists($slug, $this->categories)) {
                $this->categories[$slug] = $id;
            }
        }

        $count = (int) $this->option('count');
        $created = 0;

        foreach ($this->contentTemplates as $catSlug => $posts) {
            $catId = $this->categories[$catSlug] ?? null;
            if (!$catId) continue;

            foreach ($posts as $template) {
                if ($created >= $count) break 2;

                $slug = Str::slug($template['title']);
                if (DB::table('forum_posts')->where('slug', $slug)->exists()) continue;

                $customerId = $this->customerIds[array_rand($this->customerIds)];
                $customer = DB::table('customers')->find($customerId);

                $content = $this->generateContent($template['title'], $catSlug);

                DB::table('forum_posts')->insert([
                    'customer_id' => $customerId,
                    'title' => $template['title'],
                    'slug' => $slug,
                    'content' => $content,
                    'excerpt' => Str::limit(strip_tags($content), 200),
                    'type' => 'discussion',
                    'author_name' => trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')),
                    'author_email' => $customer->email ?? '',
                    'category_id' => $catId,
                    'status' => 'published',
                    'is_featured' => rand(0, 10) > 8 ? 1 : 0,
                    'views_count' => rand(15, 500),
                    'comments_count' => rand(0, 12),
                    'likes_count' => rand(0, 30),
                    'hot_score' => rand(1, 100),
                    'meta_title' => $template['title'] . ' | Forum LamGame',
                    'meta_description' => Str::limit(strip_tags($content), 160),
                    'meta_keywords' => $template['tags'],
                    'created_at' => Carbon::now()->subDays(rand(1, 60))->subHours(rand(0, 23)),
                    'updated_at' => Carbon::now()->subDays(rand(0, 30)),
                ]);

                $created++;
            }
        }

        $this->info("Created {$created} forum posts.");
    }

    private function generateContent(string $title, string $category): string
    {
        $paragraphs = match ($category) {
            'thao-luan' => $this->discussionContent($title),
            'chia-se-y-tuong' => $this->ideaContent($title),
            'ho-tro-ky-thuat' => $this->technicalContent($title),
            'showcase' => $this->showcaseContent($title),
            'tim-team' => $this->recruitContent($title),
            'review-khoa-hoc' => $this->reviewContent($title),
            'tuyen-dung' => $this->jobContent($title),
            'game-jam' => $this->gamejamContent($title),
            default => ["Nội dung bài viết về: {$title}"],
        };

        return implode("\n\n", $paragraphs);
    }

    private function discussionContent(string $title): array
    {
        return [
            "Chào cả nhà! Mình muốn mở một cuộc thảo luận về chủ đề: **{$title}**.",
            "Theo kinh nghiệm cá nhân, đây là vấn đề mà nhiều game developer Việt Nam gặp phải khi mới bắt đầu. Mình đã nghiên cứu và thử nghiệm khá nhiều, nên muốn chia sẻ góc nhìn của mình.",
            "**Ưu điểm:**\n- Dễ tiếp cận cho người mới\n- Cộng đồng hỗ trợ lớn\n- Tài liệu phong phú\n- Plugin/Asset store đa dạng",
            "**Nhược điểm:**\n- Learning curve ban đầu khá dốc\n- Một số tính năng cần plugin bổ sung\n- Performance cần tối ưu cẩn thận",
            "Mọi người có kinh nghiệm gì về vấn đề này không? Đặc biệt là các bạn đã ship game rồi, chia sẻ thêm nhé! 🎮",
            "**Tags:** #gamedev #vietnam #discussion",
        ];
    }

    private function ideaContent(string $title): array
    {
        return [
            "# Concept: {$title}",
            "Mình đang ấp ủ ý tưởng này khá lâu rồi. Hôm nay muốn chia sẻ concept để nhận feedback từ cộng đồng.",
            "## Core Mechanics\n- Gameplay loop chính xoay quanh việc khám phá và thu thập\n- Progression system dạng unlock dần dần\n- Social features để tương tác với player khác",
            "## Target Audience\n- Casual gamers 18-35 tuổi\n- Thị trường Đông Nam Á (focus VN)\n- Platform: Mobile (iOS + Android)",
            "## Monetization\n- Free-to-play với IAP cosmetics\n- Rewarded ads (optional)\n- Battle pass seasonal",
            "Mọi người thấy concept này thế nào? Có gì cần cải thiện không? Nếu ai quan tâm collab thì inbox mình nhé! 🚀",
        ];
    }

    private function technicalContent(string $title): array
    {
        return [
            "## Vấn đề\n\nMình đang gặp issue với: **{$title}**",
            "### Mô tả chi tiết\nKhi implement tính năng này, mình gặp lỗi khá khó debug. Đã thử Google nhưng không tìm được solution phù hợp.",
            "### Những gì đã thử\n1. Clear cache và rebuild project\n2. Kiểm tra dependencies version\n3. Thử trên device/emulator khác\n4. Check log output",
            "### Environment\n- Engine version: latest stable\n- OS: Windows 11 / macOS\n- Target platform: Mobile",
            "### Mong muốn\nAi đã gặp issue tương tự cho mình xin hướng giải quyết. Cảm ơn các bạn! 🙏",
        ];
    }

    private function showcaseContent(string $title): array
    {
        return [
            "# {$title}",
            "Xin chào mọi người! Mình muốn chia sẻ dự án mình vừa hoàn thành (hoặc đang phát triển).",
            "## Thông tin project\n- **Engine:** Unity / Godot\n- **Thời gian phát triển:** 2-3 tháng\n- **Team size:** Solo / 2-3 người\n- **Platform:** PC / Mobile",
            "## Features chính\n- Core gameplay loop hoàn chỉnh\n- UI/UX được polish\n- Sound effects & music\n- Save/Load system\n- Tutorial cho new players",
            "## Lessons learned\nĐây là kinh nghiệm quý giá nhất mình rút ra được: **Scope nhỏ, polish nhiều.** Ban đầu mình plan quá to, cuối cùng phải cut bớt features để ship được.\n\nFeedback từ mọi người sẽ giúp mình cải thiện rất nhiều! 🎯",
        ];
    }

    private function recruitContent(string $title): array
    {
        return [
            "# {$title}",
            "## Về dự án\nChúng mình đang phát triển game [thể loại] và cần tìm thêm thành viên cho team.",
            "## Yêu cầu\n- Có kinh nghiệm cơ bản (portfolio preferred)\n- Có thể commit 10-20h/tuần\n- Communication tốt (Discord)\n- Passion for games!",
            "## Đãi ngộ\n- Revenue sharing khi release\n- Remote, flexible schedule\n- Học hỏi và phát triển cùng team",
            "Liên hệ: comment bên dưới hoặc DM mình. Mong tìm được đồng đội! 🤝",
        ];
    }

    private function reviewContent(string $title): array
    {
        return [
            "# {$title}",
            "Mình vừa hoàn thành khóa/sách này và muốn chia sẻ review chi tiết cho anh em.",
            "## Đánh giá tổng quan\n- **Chất lượng nội dung:** ⭐⭐⭐⭐ (4/5)\n- **Độ khó:** Trung bình - Nâng cao\n- **Phù hợp cho:** Beginner đến Intermediate\n- **Thời lượng:** 20-40 giờ",
            "## Ưu điểm\n- Giải thích rõ ràng, dễ hiểu\n- Có project thực hành theo\n- Cập nhật cho version mới nhất\n- Cộng đồng hỗ trợ active",
            "## Nhược điểm\n- Một số phần đi quá nhanh\n- Thiếu phần về optimization\n- Giá hơi cao nếu không sale",
            "**Kết luận:** Đáng đầu tư nếu bạn nghiêm túc muốn theo career game dev. Đợi sale Udemy để mua giá tốt! 📚",
        ];
    }

    private function jobContent(string $title): array
    {
        return [
            "# {$title}",
            "## Mô tả công việc\n- Phát triển game features theo GDD\n- Code clean, có unit test\n- Review code của teammates\n- Tham gia sprint planning",
            "## Yêu cầu\n- 2+ năm kinh nghiệm\n- Thành thạo engine/tool liên quan\n- Có portfolio hoặc shipped games\n- Tiếng Anh đọc hiểu tốt",
            "## Quyền lợi\n- Lương competitive\n- Remote/Hybrid flexible\n- Game allowance hàng tháng\n- Đào tạo và phát triển career",
            "📩 Apply: gửi CV + portfolio qua email hoặc comment bên dưới.",
        ];
    }

    private function gamejamContent(string $title): array
    {
        return [
            "# {$title}",
            "Xin chào các game dev! Mình muốn share thông tin về event này.",
            "## Thông tin\n- **Thời gian:** 48-72 giờ\n- **Format:** Online, team hoặc solo\n- **Đăng ký:** Miễn phí\n- **Platform:** itch.io submission",
            "## Tips chuẩn bị\n1. Setup sẵn project template\n2. Chuẩn bị asset library cơ bản\n3. Plan scope nhỏ, focus polish\n4. Ngủ đủ giấc trước jam!",
            "Ai muốn team up cho event này thì comment bên dưới nhé! Let's jam! 🎮🔥",
        ];
    }
}
