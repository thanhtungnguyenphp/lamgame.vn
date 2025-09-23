<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\ForumTag;
use App\Models\ForumComment;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForumPostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ForumCategory::all();
        $tags = ForumTag::all();

        $samplePosts = [
            // Thảo luận posts
            [
                'title' => 'Làm thế nào để tối ưu performance game Unity?',
                'content' => "Chào mọi người,\n\nMình đang phát triển game mobile với Unity nhưng gặp vấn đề về hiệu suất. Game có nhiều object di chuyển và FPS giảm mạnh trên mobile.\n\nCác bạn có kinh nghiệm gì về optimization không? Mình đã thử:\n- Object Pooling cho bullets\n- Giảm texture quality\n- Sử dụng LOD system\n\nNhưng vẫn chưa đủ. Có technique nào khác không ạ?",
                'type' => 'discussion',
                'category_slug' => 'thao-luan',
                'author_name' => 'GameDev_VN',
                'author_email' => 'gamedev.vn@example.com',
                'tags' => ['Unity', 'Mobile Games', 'Optimization', 'C#'],
                'is_featured' => true,
                'views_count' => 234,
                'likes_count' => 15,
            ],
            [
                'title' => 'So sánh Unity vs Unreal Engine cho indie developer',
                'content' => "Mình đang băn khoăn không biết chọn Unity hay Unreal Engine để bắt đầu làm game indie.\n\nMình có background lập trình C# và đã làm vài app mobile. Dự án đầu tiên sẽ là game 2D platformer.\n\nMọi người tư vấn giúp mình:\n1. Engine nào dễ học hơn?\n2. Cộng đồng support như thế nào?\n3. Chi phí licensing?\n4. Performance trên mobile?\n\nCảm ơn mọi người!",
                'type' => 'question',
                'category_slug' => 'thao-luan',
                'author_name' => 'NewbieDev2024',
                'author_email' => 'newbie@example.com',
                'tags' => ['Unity', 'Unreal Engine', 'Beginner', '2D Games'],
                'views_count' => 189,
                'likes_count' => 8,
            ],

            // Chia sẻ ý tưởng posts
            [
                'title' => 'Game mobile về nông nghiệp Việt Nam',
                'content' => "Ý tưởng: Game mô phỏng nông nghiệp lấy bối cảnh Việt Nam\n\n## Concept chính:\n- Trồng các loại cây đặc sản VN: lúa, cà phê, cao su, tiêu...\n- Hệ thống thời tiết theo mùa\n- Trade với các tỉnh khác nhau\n- Kết hợp yếu tố văn hóa dân gian\n\n## Gameplay:\n- Idle/clicker game cơ bản\n- Quản lý resources\n- Mini-games cho từng hoạt động\n- Social features: tặng hạt giống, visit farm bạn bè\n\n## Target:\n- Mobile-first\n- Free-to-play với IAP\n- Audience: 25-40 tuổi, nostalgic về quê hương\n\nAi quan tâm join team không? Mình cần:\n- 2D Artist (pixel art style)\n- Unity Developer\n- Game Designer\n\nComment bên dưới nếu interested nhé!",
                'type' => 'idea',
                'category_slug' => 'chia-se-y-tuong',
                'author_name' => 'FarmGameVN',
                'author_email' => 'farmgame@example.com',
                'tags' => ['Mobile Games', 'Simulation', 'Unity', '2D Games', 'Game Design'],
                'is_featured' => true,
                'views_count' => 156,
                'likes_count' => 23,
            ],
            [
                'title' => 'RPG Tam Quốc với gameplay mới',
                'content' => "## Concept: Tam Quốc Realtime Strategy RPG\n\n### Core Idea:\nKết hợp giữa Total War và JRPG, lấy bối cảnh Tam Quốc với historical accuracy cao.\n\n### Unique Features:\n1. **Realtime Tactical Combat**: Không phải turn-based như các game Tam Quốc khác\n2. **AI-driven NPCs**: Các tướng lĩnh có AI personality riêng\n3. **Dynamic Weather**: Ảnh hưởng trực tiếp đến combat (mưa làm chậm cavalry, gió ảnh hưởng archers)\n4. **Diplomacy System**: Phức tạp như Crusader Kings\n\n### Technical Stack:\n- Unreal Engine 5 (Lumen + Nanite)\n- Multiplayer với dedicated servers\n- Platform: PC first, console sau\n\n### Team cần tìm:\n- **Lead Programmer** (Unreal C++)\n- **3D Artists** (characters + environments)\n- **Game Designer** (balance + systems)\n- **Historical Researcher**\n\nBudget dự kiến: $200k-500k, timeline 2-3 năm.\n\nAi có kinh nghiệm với large-scale projects ping mình!",
                'type' => 'idea',
                'category_slug' => 'chia-se-y-tuong',
                'author_name' => 'HistoryGamer',
                'author_email' => 'historygamer@example.com',
                'tags' => ['RPG', 'Strategy', 'Unreal Engine', '3D Games', 'PC'],
                'views_count' => 134,
                'likes_count' => 18,
            ],

            // Tìm team posts
            [
                'title' => '[Tìm team] Game horror indie cần Unity developer',
                'content' => "## Dự án: \"Midnight School\" - Horror Adventure\n\n### Về game:\n- Genre: Horror/Mystery/Adventure\n- Platform: PC (Steam)\n- Art style: Low-poly với lighting atmospher\n- Timeline: 8-12 tháng\n- Budget: Rev-share model\n\n### Progress hiện tại:\n- Design document: 70% complete\n- Art assets: 40% complete\n- Audio: 20% complete\n- Programming: 10% complete\n\n### Cần tìm:\n**Unity Developer (Mid-Senior level)**\n- Kinh nghiệm 2+ năm Unity\n- Biết C# proficiently\n- Có kinh nghiệm với lighting system\n- Bonus: biết shader programming\n\n**Responsibilities:**\n- Character controller\n- Inventory system\n- Save/Load system\n- UI programming\n- Performance optimization\n\n### Team hiện tại:\n- Game Designer/Producer (mình)\n- 2D/3D Artist \n- Sound Designer\n- Writer\n\n### Contact:\nEmail: midnight.school.dev@gmail.com\nDiscord: HorrorGameDev#1234\n\nGửi portfolio + CV nha các bạn!",
                'type' => 'job',
                'category_slug' => 'tim-team',
                'author_name' => 'HorrorGameStudio',
                'author_email' => 'horror@example.com',
                'tags' => ['Unity', 'C#', 'Horror', 'PC', 'Indie'],
                'views_count' => 287,
                'likes_count' => 12,
            ],

            // Review khóa học
            [
                'title' => 'Review khóa học Unity tại GameDev Academy',
                'content' => "Vừa hoàn thành khóa Unity 3 tháng tại GameDev Academy, chia sẻ review chi tiết cho ae.\n\n## Thông tin khóa học:\n- **Tên**: Unity Game Development Bootcamp\n- **Thời gian**: 3 tháng (12 tuần)\n- **Học phí**: 15 triệu VND\n- **Format**: Offline + Online hybrid\n- **Lớp**: 15 học viên\n\n## Curriculum:\n### Week 1-4: Unity Basics\n- Interface và tools\n- C# fundamentals\n- GameObject và Component system\n- Physics và Collision\n\n### Week 5-8: Intermediate Topics\n- Animation system\n- UI/UX design\n- Audio integration\n- Lighting và Post-processing\n\n### Week 9-12: Advanced + Project\n- Optimization techniques\n- Mobile deployment\n- Final project (complete game)\n\n## Pros:\n✅ Giảng viên có kinh nghiệm thực tế (ex-Ubisoft)\n✅ Hands-on practice nhiều\n✅ Support tốt, response nhanh\n✅ Career guidance sau khóa\n✅ Networking với classmates\n\n## Cons:\n❌ Pace hơi nhanh cho beginner\n❌ Thiếu advanced topics (shader, multiplayer)\n❌ Không có guest speakers từ industry\n❌ Lab equipment hơi cũ\n\n## Final project:\nMình làm 2D platformer \"Ninja Cat Adventures\" với:\n- 5 levels\n- Boss fights\n- Achievement system\n- Leaderboard\n\n## Kết quả:\nSau khóa mình apply được 3 company và nhận offer junior Unity developer tại 1 startup game ở TP.HCM với mức lương 12M/tháng.\n\n## Rating: 7.5/10\n\n**Recommend cho**: Người có programming background muốn chuyển sang game dev\n**Không recommend cho**: Complete beginner (nên học C# trước)\n\nAi có câu hỏi comment bên dưới nhé!",
                'type' => 'review',
                'category_slug' => 'review-khoa-hoc',
                'author_name' => 'NewbieCoder',
                'author_email' => 'newbiecoder@example.com',
                'tags' => ['Unity', 'C#', 'Tutorial', 'Beginner', 'Review'],
                'views_count' => 445,
                'likes_count' => 31,
            ],

            // Hỗ trợ kỹ thuật
            [
                'title' => '[HELP] Lỗi NullReferenceException khi Instantiate object',
                'content' => "Chào mọi người, mình đang gặp lỗi NullReferenceException khi instantiate prefab trong Unity.\n\n## Code hiện tại:\n```csharp\npublic class EnemySpawner : MonoBehaviour\n{\n    public GameObject enemyPrefab;\n    public Transform spawnPoint;\n    \n    void Start()\n    {\n        InvokeRepeating(\"SpawnEnemy\", 1f, 2f);\n    }\n    \n    void SpawnEnemy()\n    {\n        GameObject enemy = Instantiate(enemyPrefab, spawnPoint.position, spawnPoint.rotation);\n        // Error ở dòng này:\n        enemy.GetComponent<Enemy>().Initialize(player.transform);\n    }\n}\n```\n\n## Error message:\n`NullReferenceException: Object reference not set to an instance of an object`\n\n## Đã thử:\n- Check prefab đã assign\n- Check spawnPoint không null\n- Check Enemy script đã attach vào prefab\n\nNhưng vẫn bị lỗi. Ai biết lý do không ạ?\n\n**Unity version**: 2023.1.15f1\n**Platform**: Windows\n\nCảm ơn mọi người!",
                'type' => 'question',
                'category_slug' => 'ho-tro-ky-thuat',
                'author_name' => 'UnityLearner',
                'author_email' => 'learner@example.com',
                'tags' => ['Unity', 'C#', 'Bug', 'Beginner'],
                'views_count' => 78,
                'likes_count' => 3,
            ],

            // Showcase
            [
                'title' => '[SHOWCASE] \"Cyber Runner\" - Game endless runner hoàn thành',
                'content' => "Sau 6 tháng làm việc, mình đã hoàn thành game đầu tiên \"Cyber Runner\"!\n\n## Game Info:\n- **Genre**: Endless Runner\n- **Platform**: Android, iOS\n- **Engine**: Unity 2023.1\n- **Art Style**: Cyberpunk low-poly\n- **Team Size**: Solo developer\n\n## Features:\n🏃‍♂️ Smooth character movement với parkour mechanics\n🌃 Procedural level generation\n💰 Coin collection system\n🛍️ In-app purchases cho characters/power-ups\n📊 Leaderboard integration (Google Play Games)\n🎵 Dynamic music system\n⚡ Particle effects và screen shake\n\n## Technical Highlights:\n- **Object Pooling** cho tất cả moving objects\n- **Addressables** cho asset management\n- **Unity Analytics** cho player behavior tracking\n- **Custom shaders** cho neon effects\n- **Optimized** cho 60 FPS trên mid-range phones\n\n## Screenshots:\n[Link imgur album]\n\n## Trailer:\n[YouTube link]\n\n## Download:\n- Google Play: [Link]\n- App Store: [Link] (pending review)\n\n## Development Stats:\n- **Code lines**: ~8,000 (C#)\n- **Art assets**: 150+ models, 50+ textures\n- **Audio**: 20 SFX, 5 background tracks\n- **Build size**: 45MB\n\n## Lessons Learned:\n1. **Scope creep** là kẻ thù lớn nhất\n2. **Playtesting** càng sớm càng tốt\n3. **Performance** quan trọng hơn graphics fancy\n4. **Marketing** khó hơn development 😅\n\n## Next Steps:\nĐang plan game thứ 2 - một puzzle game với mechanics unique hơn.\n\nMọi người feedback giúp mình với! Đặc biệt là về gameplay balance và monetization.\n\nCảm ơn cộng đồng đã support mình suốt quá trình dev! 🙏",
                'type' => 'showcase',
                'category_slug' => 'showcase',
                'author_name' => 'CyberRunner_Dev',
                'author_email' => 'cyberrunner@example.com',
                'tags' => ['Unity', 'Mobile Games', '3D Games', 'Complete', 'Indie'],
                'is_featured' => true,
                'views_count' => 523,
                'likes_count' => 67,
            ],
        ];

        foreach ($samplePosts as $postData) {
            // Find category
            $category = $categories->where('slug', $postData['category_slug'])->first();
            if (!$category) continue;

            // Create post
            $post = ForumPost::create([
                'title' => $postData['title'],
                'content' => $postData['content'],
                'type' => $postData['type'],
                'category_id' => $category->id,
                'author_name' => $postData['author_name'],
                'author_email' => $postData['author_email'],
                'status' => 'published',
                'is_featured' => $postData['is_featured'] ?? false,
                'views_count' => $postData['views_count'] ?? rand(10, 100),
                'likes_count' => $postData['likes_count'] ?? rand(1, 20),
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
                'updated_at' => Carbon::now()->subDays(rand(0, 10)),
            ]);

            // Attach tags
            if (isset($postData['tags'])) {
                $tagIds = [];
                foreach ($postData['tags'] as $tagName) {
                    $tag = $tags->where('name', $tagName)->first();
                    if ($tag) {
                        $tagIds[] = $tag->id;
                    }
                }
                if (!empty($tagIds)) {
                    $post->tags()->attach($tagIds);
                }
            }

            // Add some comments
            $this->createCommentsForPost($post);
        }

        // Update category counts
        foreach ($categories as $category) {
            $category->updateCounts();
        }

        // Update tag counts
        foreach ($tags as $tag) {
            $tag->updatePostsCount();
        }
    }

    /**
     * Create sample comments for a post.
     */
    private function createCommentsForPost(ForumPost $post): void
    {
        $commentAuthors = [
            ['name' => 'UnityExpert', 'email' => 'expert@example.com'],
            ['name' => 'GameOptimizer', 'email' => 'optimizer@example.com'],
            ['name' => 'MobileDev2023', 'email' => 'mobiledev@example.com'],
            ['name' => 'IndieCreator', 'email' => 'indie@example.com'],
            ['name' => 'CodeMaster', 'email' => 'codemaster@example.com'],
        ];

        $commentCount = rand(2, 8);
        
        for ($i = 0; $i < $commentCount; $i++) {
            $author = $commentAuthors[array_rand($commentAuthors)];
            
            $comment = ForumComment::create([
                'post_id' => $post->id,
                'content' => $this->getRandomComment($post->type),
                'author_name' => $author['name'],
                'author_email' => $author['email'],
                'status' => 'published',
                'likes_count' => rand(0, 15),
                'created_at' => Carbon::now()->subDays(rand(0, 7)),
            ]);

            // Sometimes add a reply
            if (rand(1, 100) <= 30) { // 30% chance of reply
                $replyAuthor = $commentAuthors[array_rand($commentAuthors)];
                ForumComment::create([
                    'post_id' => $post->id,
                    'parent_id' => $comment->id,
                    'content' => $this->getRandomReply(),
                    'author_name' => $replyAuthor['name'],
                    'author_email' => $replyAuthor['email'],
                    'status' => 'published',
                    'likes_count' => rand(0, 8),
                    'created_at' => Carbon::now()->subDays(rand(0, 5)),
                ]);
            }
        }
    }

    /**
     * Get random comment content based on post type.
     */
    private function getRandomComment(string $postType): string
    {
        $comments = [
            'discussion' => [
                'Bạn có thể thử sử dụng Object Pooling để tối ưu performance.',
                'Mình cũng gặp vấn đề tương tự. Thử giảm draw calls bằng cách merge meshes.',
                'Unity Profiler sẽ giúp bạn identify bottlenecks chính xác hơn.',
                'Ngoài ra, hãy chú ý đến texture compression settings cho mobile.',
                'Good point! Đã save post để reference sau này.',
            ],
            'idea' => [
                'Ý tưởng hay đấy! Mình có kinh nghiệm về mobile monetization, có thể support.',
                'Concept rất thú vị. Bạn đã research về target audience chưa?',
                'Art style nào bạn dự định sử dụng? Pixel art sẽ fit với theme này.',
                'Mình quan tâm position Unity Developer. Portfolio: [link]',
                'Market research shows farming games rất hot ở SEA region.',
            ],
            'question' => [
                'Bạn check null reference chưa? Có thể enemy.GetComponent<Enemy>() return null.',
                'Try adding some debug logs để trace exact location của error.',
                'Prefab của bạn có Enemy script attached không?',
                'Initialization order có thể là vấn đề. Try sử dụng Awake() thay vì Start().',
                'Post full error stack trace để mọi người debug dễ hơn.',
            ],
            'showcase' => [
                'Wow impressive! Graphics và gameplay smooth quá.',
                'Congrats on shipping! Game nhìn rất polished.',
                'Downloaded rồi, sẽ review và rate 5 sao.',
                'Amazing work for solo dev! Inspiration cho mình quá.',
                'Marketing plan như thế nào? Social media presence quan trọng lắm.',
            ],
            'review' => [
                'Thanks for detailed review! Rất hữu ích cho người đang plan học.',
                'Mức lương 12M cho junior dev ở HCM khá ok đấy.',
                'Curriculum looks comprehensive. Worth the investment.',
                'Bạn có recommend online alternatives không?',
                'Career support sau khóa như thế nào? Job placement rate?',
            ],
        ];

        $typeComments = $comments[$postType] ?? $comments['discussion'];
        return $typeComments[array_rand($typeComments)];
    }

    /**
     * Get random reply content.
     */
    private function getRandomReply(): string
    {
        $replies = [
            'Cảm ơn bạn! Mình sẽ thử method này.',
            'Exactly! Mình cũng nghĩ vậy.',
            'Good suggestion, thanks for sharing!',
            'Đúng rồi, mình quên mất aspect này.',
            'Appreciate the help! 🙏',
            'That makes sense, thanks!',
            'Sẽ update result sau khi test.',
        ];

        return $replies[array_rand($replies)];
    }
}
