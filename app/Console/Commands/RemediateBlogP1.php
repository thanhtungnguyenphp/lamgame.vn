<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RuntimeException;

class RemediateBlogP1 extends Command
{
    protected $signature = 'blog:remediate-p1 {--apply : Apply the reviewed P1 editorial batch}';
    protected $description = 'Rewrite and review the Unity workflow and Game Design 101 pillar articles';

    private const SLUGS = [
        1 => 'huong-dan-unity-2023-tinh-nang-moi',
        4 => 'game-design-101-nguyen-tac-thiet-ke-game',
    ];

    public function handle(): int
    {
        $articles = $this->articles();
        foreach ($articles as $id => $article) {
            $words = $this->wordCount($article['description']);
            $sources = json_decode($article['sources'], true, flags: JSON_THROW_ON_ERROR);
            if ($words < 1200 || count($sources) < 3 || mb_strlen($article['meta_description']) > 160) {
                throw new RuntimeException("Payload ID {$id} không đạt editorial gate.");
            }
            $this->line(sprintf('[%d] %s — %d từ, %d nguồn, meta %d ký tự', $id, $article['name'], $words, count($sources), mb_strlen($article['meta_description'])));
        }

        $records = DB::table('blogs')->whereIn('id', array_keys(self::SLUGS))->orderBy('id')->get();
        $this->assertRecords($records);
        if (! $this->option('apply')) {
            $this->warn('Dry run: chưa thay đổi production DB.');
            return self::SUCCESS;
        }

        $backup = $this->backup($records);
        DB::transaction(function () use ($articles): void {
            $locked = DB::table('blogs')->whereIn('id', array_keys(self::SLUGS))->lockForUpdate()->get();
            $this->assertRecords($locked);
            if (! DB::table('authors')->where('id', 1)->exists()) {
                throw new RuntimeException('LamGame Team author ID 1 không tồn tại.');
            }
            $now = now();
            foreach ($articles as $id => $article) {
                $affected = DB::table('blogs')->where('id', $id)->where('slug', self::SLUGS[$id])->update($article + [
                    'status' => 'published', 'reviewed_at' => $now, 'reviewed_by' => 1, 'updated_at' => $now,
                ]);
                if ($affected !== 1) throw new RuntimeException("Không thể cập nhật blog ID {$id}.");
            }
        }, 3);

        $this->info('Đã hoàn tất transaction Blog P1.');
        $this->line("Backup: {$backup}");
        $this->line('SHA-256: '.hash_file('sha256', $backup));
        return self::SUCCESS;
    }

    private function articles(): array
    {
        return [
            1 => [
                'name' => 'Hướng dẫn Unity 6: workflow làm vertical slice thực tế',
                'short_description' => 'Tutorial Unity 6 từ project structure, Input System và ScriptableObject đến profiling, test và build vertical slice.',
                'description' => File::get(resource_path('content/blog-p1/unity6-workflow.html')),
                'meta_title' => 'Hướng dẫn Unity 6: làm vertical slice thực tế | LamGame',
                'meta_description' => 'Tutorial Unity 6 về Input System, ScriptableObject, prefab, Profiler, testing và build một vertical slice có thể đo lường.',
                'meta_keywords' => 'Unity 6, Unity tutorial, Input System, ScriptableObject, Unity Profiler, vertical slice',
                'sources' => $this->sources([
                    ['title' => 'Unity Manual — Input System', 'url' => 'https://docs.unity3d.com/6000.3/Documentation/Manual/com.unity.inputsystem.html'],
                    ['title' => 'Unity Manual — CPU Usage Profiler', 'url' => 'https://docs.unity3d.com/6000.0/Documentation/Manual/profiler-cpu-introduction.html'],
                    ['title' => 'Unity Manual — Profile a Web build', 'url' => 'https://docs.unity3d.com/6000.5/Documentation/Manual/web-profile.html'],
                ]),
            ],
            4 => [
                'name' => 'Game Design 101: từ core loop đến playtest và balancing',
                'short_description' => 'Hướng dẫn game design thực hành: design goal, core loop, MDA, economy, progression, difficulty, accessibility và playtest.',
                'description' => File::get(resource_path('content/blog-p1/game-design-101.html')),
                'meta_title' => 'Game Design 101: core loop, balancing và playtest | LamGame',
                'meta_description' => 'Game Design 101 thực hành: xây core loop, trade-off, economy, progression, difficulty, accessibility, telemetry và playtest.',
                'meta_keywords' => 'game design, core loop, MDA, game balancing, game economy, playtest, accessibility',
                'sources' => $this->sources([
                    ['title' => 'MDA: A Formal Approach to Game Design and Game Research', 'url' => 'http://se4n.org/papers/mda.pdf'],
                    ['title' => 'Game Accessibility Guidelines', 'url' => 'https://gameaccessibilityguidelines.com/'],
                    ['title' => 'Machinations Documentation', 'url' => 'https://machinations.io/docs/'],
                ]),
            ],
        ];
    }

    private function assertRecords($records): void
    {
        if ($records->count() !== count(self::SLUGS)) throw new RuntimeException('Không tìm thấy đủ record P1.');
        foreach (self::SLUGS as $id => $slug) {
            if ($records->firstWhere('id', $id)?->slug !== $slug) throw new RuntimeException("ID {$id} không khớp slug.");
        }
    }

    private function backup($records): string
    {
        $dir = storage_path('app/private/backups'); File::ensureDirectoryExists($dir, 0700, true);
        $path = $dir.'/blog-p1-remediation-'.now()->format('Y-m-d-His').'.json';
        File::put($path, json_encode(['created_at'=>now()->toIso8601String(),'records'=>$records->values()->all()], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR));
        chmod($path, 0600); return $path;
    }

    private function wordCount(string $html): int { preg_match_all('/[\p{L}\p{N}]+/u', strip_tags($html), $m); return count($m[0] ?? []); }
    private function sources(array $sources): string { return json_encode($sources, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR); }
}
