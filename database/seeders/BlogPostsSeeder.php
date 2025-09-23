<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BlogPostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing blog posts
        DB::table('blogs')->truncate();
        
        // Sample blog posts
        $posts = [
            [
                'name' => 'Hướng dẫn Unity 2023 - Những tính năng mới đáng chú ý',
                'slug' => 'huong-dan-unity-2023-tinh-nang-moi',
                'short_description' => 'Unity 2023 đã ra mắt với nhiều cải tiến quan trọng giúp game developer tăng hiệu suất và chất lượng game.',
                'description' => '<p>Unity 2023 đã ra mắt với nhiều cải tiến quan trọng giúp game developer tăng hiệu suất và chất lượng game. Trong bài viết này, chúng ta sẽ khám phá những tính năng nổi bật như Unity Netcode for GameObjects, Shader Graph improvements...</p>

<h3>1. Unity Netcode for GameObjects</h3>
<p>Đây là hệ thống networking mới của Unity, thay thế cho UNet cũ. Netcode for GameObjects cung cấp:</p>
<ul>
<li>Client-Server architecture hiện đại</li>
<li>Sync variables và RPC calls dễ sử dụng</li>
<li>Optimized network performance</li>
<li>Built-in lag compensation</li>
</ul>

<h3>2. Shader Graph Improvements</h3>
<p>Shader Graph đã được cải tiến đáng kể với các tính năng mới:</p>
<ul>
<li>Custom Function Nodes</li>
<li>Sub Graph assets</li>
<li>Better performance optimization</li>
<li>More built-in nodes</li>
</ul>

<h3>3. Universal Render Pipeline (URP) Updates</h3>
<p>URP trong Unity 2023 có nhiều cải tiến về hiệu suất và chất lượng visual.</p>

<h3>Kết luận</h3>
<p>Unity 2023 mang đến nhiều cải tiến đáng kể cho game developers. Hãy cập nhật và trải nghiệm những tính năng mới này!</p>',
                'html_content' => '',
                'published_at' => Carbon::now()->subDays(5),
                'status' => 1,
                'author_id' => 1,
                'meta_title' => 'Unity 2023 - Những tính năng mới đáng chú ý | LamGame',
                'meta_description' => 'Khám phá Unity 2023 với Netcode for GameObjects, Shader Graph improvements và URP updates mới nhất.',
                'meta_keywords' => 'Unity 2023, Unity Netcode, Shader Graph, URP, Game Development',
                'locale' => 'vi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'C# Cơ bản cho Game Developer - Từ Hello World đến MonoBehaviour',
                'slug' => 'csharp-co-ban-cho-game-developer',
                'short_description' => 'Hướng dẫn C# từ cơ bản đến nâng cao dành cho những ai muốn bắt đầu với Unity game development.',
                'description' => '<h3>Giới thiệu về C#</h3>
<p>C# là ngôn ngữ lập trình chính được sử dụng trong Unity. Đây là ngôn ngữ mạnh mẽ, dễ học và có syntax rất clear.</p>

<h3>1. Biến và Kiểu dữ liệu</h3>
<pre><code>// Các kiểu dữ liệu cơ bản
int health = 100;
float speed = 5.5f;
bool isAlive = true;
string playerName = "Hero";

// Arrays và Lists
int[] scores = {10, 20, 30};
List&lt;GameObject&gt; enemies = new List&lt;GameObject&gt;();</code></pre>

<h3>2. Functions và Methods</h3>
<pre><code>// Method cơ bản
public void TakeDamage(int damage)
{
    health -= damage;
    if (health <= 0)
    {
        Die();
    }
}</code></pre>

<h3>3. Unity MonoBehaviour</h3>
<p>MonoBehaviour là class cơ sở cho tất cả Unity scripts:</p>
<pre><code>public class Player : MonoBehaviour
{
    void Start()
    {
        // Chạy một lần khi object được tạo
    }
    
    void Update()
    {
        // Chạy mỗi frame
    }
}</code></pre>

<h3>Bài tập thực hành</h3>
<p>Hãy tạo một script đơn giản để di chuyển player trong Unity!</p>',
                'html_content' => '',
                'published_at' => Carbon::now()->subDays(3),
                'status' => 1,
                'author_id' => 1,
                'meta_title' => 'C# Cơ bản cho Game Developer | Unity C# Tutorial',
                'meta_description' => 'Học C# từ cơ bản đến nâng cao cho Unity game development. Từ Hello World đến MonoBehaviour.',
                'meta_keywords' => 'C#, Unity C#, MonoBehaviour, Game Programming, Unity Tutorial',
                'locale' => 'vi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Tối ưu hóa Performance Game Mobile với Unity',
                'slug' => 'toi-uu-hoa-performance-game-mobile-unity',
                'short_description' => 'Hướng dẫn chi tiết cách tối ưu hóa performance cho mobile game để đạt hiệu suất tốt nhất trên nhiều thiết bị.',
                'description' => '<h3>Tại sao Performance Mobile quan trọng?</h3>
<p>Mobile devices có giới hạn về CPU, GPU, RAM và battery. Việc tối ưu hóa performance không chỉ giúp game chạy mượt mà còn tiết kiệm pin và tương thích với nhiều thiết bị hơn.</p>

<h3>1. Object Pooling</h3>
<p>Thay vì liên tục tạo và hủy objects, hãy sử dụng object pooling:</p>
<pre><code>public class ObjectPool : MonoBehaviour
{
    public GameObject prefab;
    private Queue&lt;GameObject&gt; pool = new Queue&lt;GameObject&gt;();
    
    public GameObject GetObject()
    {
        if (pool.Count > 0)
            return pool.Dequeue();
        else
            return Instantiate(prefab);
    }
    
    public void ReturnObject(GameObject obj)
    {
        obj.SetActive(false);
        pool.Enqueue(obj);
    }
}</code></pre>

<h3>2. Texture Optimization</h3>
<ul>
<li>Sử dụng texture compression phù hợp (ASTC cho Android, PVRTC cho iOS)</li>
<li>Giảm kích thước texture khi có thể</li>
<li>Sử dụng mipmaps cho textures</li>
<li>Tránh alpha channels không cần thiết</li>
</ul>

<h3>3. Audio Optimization</h3>
<ul>
<li>Compress audio files</li>
<li>Load audio on demand</li>
<li>Use audio pooling for frequent sounds</li>
</ul>

<h3>4. Code Optimization</h3>
<pre><code>// Tránh allocations trong Update()
void Update()
{
    // BAD: Tạo string mới mỗi frame
    // someText.text = "Score: " + score.ToString();
    
    // GOOD: Cache string
    if (scoreChanged)
    {
        someText.text = "Score: " + score.ToString();
        scoreChanged = false;
    }
}</code></pre>

<h3>5. Profiling và Testing</h3>
<p>Luôn sử dụng Unity Profiler để identify bottlenecks và test trên thiết bị thật.</p>',
                'html_content' => '',
                'published_at' => Carbon::now()->subDays(1),
                'status' => 1,
                'author_id' => 1,
                'meta_title' => 'Tối ưu Performance Mobile Game Unity | Optimization Tips',
                'meta_description' => 'Hướng dẫn tối ưu hóa performance mobile game Unity với Object Pooling, Texture Optimization và nhiều tips khác.',
                'meta_keywords' => 'Unity Mobile Optimization, Performance, Object Pooling, Mobile Game, Unity Tips',
                'locale' => 'vi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Game Design 101: Nguyên tắc thiết kế game hiệu quả',
                'slug' => 'game-design-101-nguyen-tac-thiet-ke-game',
                'short_description' => 'Khám phá các nguyên tắc cơ bản trong game design để tạo ra những trò chơi hấp dẫn và thu hút người chơi.',
                'description' => '<h3>Game Design là gì?</h3>
<p>Game Design là quá trình tạo ra nội dung và quy tắc của một game. Nó bao gồm gameplay mechanics, story, characters, levels, và player experience.</p>

<h3>1. Core Gameplay Loop</h3>
<p>Đây là chu trình hành động cơ bản mà player sẽ lặp lại trong suốt game:</p>
<ul>
<li><strong>Action:</strong> Player thực hiện hành động</li>
<li><strong>Result:</strong> Game phản hồi</li>
<li><strong>Reward:</strong> Player nhận phần thưởng</li>
<li><strong>Progress:</strong> Player tiến bộ</li>
</ul>

<h3>2. Player Motivation</h3>
<p>Hiểu động lực của người chơi là then chốt:</p>
<ul>
<li><strong>Achievement:</strong> Hoàn thành mục tiêu</li>
<li><strong>Social:</strong> Tương tác với người khác</li>
<li><strong>Immersion:</strong> Đắm mình vào thế giới game</li>
<li><strong>Competition:</strong> Cạnh tranh với người khác</li>
</ul>

<h3>3. Difficulty Balancing</h3>
<p>Game cần có độ khó phù hợp:</p>
<ul>
<li>Bắt đầu dễ để player làm quen</li>
<li>Tăng độ khó dần dần</li>
<li>Cung cấp multiple difficulty options</li>
<li>Adaptive difficulty dựa trên skill của player</li>
</ul>

<h3>4. Feedback Systems</h3>
<p>Player cần feedback ngay lập tức:</p>
<ul>
<li>Visual feedback (particles, animations)</li>
<li>Audio feedback (sound effects, music)</li>
<li>Haptic feedback (vibration)</li>
<li>UI feedback (score, health bars)</li>
</ul>

<h3>5. Player Retention</h3>
<p>Làm sao để player quay lại:</p>
<ul>
<li>Daily rewards</li>
<li>Progressive unlocks</li>
<li>Social features</li>
<li>Regular content updates</li>
</ul>

<h3>Kết luận</h3>
<p>Good game design là về việc hiểu player và tạo ra trải nghiệm meaningful. Hãy luôn playtest và lắng nghe feedback!</p>',
                'html_content' => '',
                'published_at' => Carbon::now()->subHours(12),
                'status' => 1,
                'author_id' => 1,
                'meta_title' => 'Game Design 101: Nguyên tắc thiết kế game | LamGame',
                'meta_description' => 'Học các nguyên tắc game design cơ bản: Core Loop, Player Motivation, Difficulty Balancing và Player Retention.',
                'meta_keywords' => 'Game Design, Gameplay, Player Experience, Game Mechanics, Game Theory',
                'locale' => 'vi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert blog posts
        foreach ($posts as $post) {
            DB::table('blogs')->insert($post);
        }

        $this->command->info('Blog posts seeded successfully!');
        $this->command->info('Created ' . count($posts) . ' blog posts.');
    }
}
