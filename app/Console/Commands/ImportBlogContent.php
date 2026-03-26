<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webbycrown\BlogBagisto\Models\Blog;
use Webbycrown\BlogBagisto\Models\Category;
use Webbycrown\BlogBagisto\Models\Tag;

class ImportBlogContent extends Command
{
    protected $signature = 'blog:import
        {--path= : Path to content/blogs directory}
        {--dry-run : Preview without inserting}
        {--force : Ignore schedule, import all ready posts}
        {--from= : Start from dayXX}
        {--to= : End at dayXX}';

    protected $description = 'Import blog posts from content directory. Marks published posts to avoid duplicates.';

    private string $contentPath;
    private array $categoryMap = [];
    private array $tagMap = [];
    private array $publishLog = [];
    private string $publishLogPath;
    private array $stats = ['created' => 0, 'skipped' => 0, 'scheduled' => 0, 'errors' => 0];

    public function handle(): int
    {
        $this->contentPath = $this->option('path')
            ?: base_path('../content/blogs');

        if (! File::isDirectory($this->contentPath)) {
            $this->error("Directory not found: {$this->contentPath}");
            return 1;
        }

        $this->publishLogPath = $this->contentPath . '/.publish-log.json';
        $this->loadPublishLog();
        $this->loadCategoryMap();
        $this->loadTagMap();

        $folders = collect(File::directories($this->contentPath))
            ->map(fn ($p) => basename($p))
            ->filter(fn ($name) => preg_match('/^day\d+/', $name))
            ->sort()->values();

        if ($from = $this->option('from')) {
            $folders = $folders->filter(fn ($n) => $this->dayNum($n) >= $this->dayNum($from));
        }
        if ($to = $this->option('to')) {
            $folders = $folders->filter(fn ($n) => $this->dayNum($n) <= $this->dayNum($to));
        }

        $this->info("Found {$folders->count()} posts to process");

        foreach ($folders as $folder) {
            $this->processFolder($folder);
        }

        // Save publish log
        if (! $this->option('dry-run')) {
            $this->savePublishLog();
        }

        $this->newLine();
        $this->table(['Status', 'Count'], [
            ['Created', $this->stats['created']],
            ['Skipped (already published)', $this->stats['skipped']],
            ['Scheduled (not yet)', $this->stats['scheduled']],
            ['Errors', $this->stats['errors']],
        ]);

        return 0;
    }

    private function processFolder(string $folder): void
    {
        $path = $this->contentPath . '/' . $folder;
        $metaFile = $path . '/metadata.json';
        $htmlFile = $path . '/index.html';

        if (! File::exists($metaFile) || ! File::exists($htmlFile)) {
            $this->warn("  ✗ Skip {$folder}: missing files");
            $this->stats['errors']++;
            return;
        }

        $meta = json_decode(File::get($metaFile), true);
        if (! $meta || empty($meta['slug'])) {
            $this->warn("  ✗ Skip {$folder}: invalid metadata");
            $this->stats['errors']++;
            return;
        }

        $slug = $meta['slug'];
        $status = $meta['status'] ?? 'ready';

        // Skip drafts
        if ($status === 'draft') {
            $this->line("  📝 Draft: {$slug}");
            return;
        }

        // Skip already published (check log + DB)
        if ($this->isPublished($folder, $slug)) {
            $this->line("  ⏭ Already published: {$slug}");
            $this->stats['skipped']++;
            return;
        }

        // Check schedule
        $publishDate = Carbon::parse($meta['publishDate'] . ' ' . ($meta['publishTime'] ?? '08:00'));
        if (! $this->option('force') && $publishDate->isFuture()) {
            $this->line("  ⏳ Scheduled {$publishDate->format('Y-m-d H:i')}: {$slug}");
            $this->stats['scheduled']++;
            return;
        }

        // Extract body
        $html = File::get($htmlFile);
        $body = $this->extractBody($html);

        // Process images in content — upload to server & rewrite URLs
        $body = $this->processImages($body, $path, $slug);

        // Resolve category & tags
        $categoryId = $this->resolveCategory($meta['category'] ?? 'Tin tức');
        $tagIds = $this->resolveTags($meta['tags'] ?? []);

        $data = [
            'name'              => $meta['title'],
            'slug'              => $slug,
            'short_description' => $meta['description'] ?? '',
            'description'       => $body,
            'default_category'  => $categoryId,
            'categorys'         => (string) $categoryId,
            'tags'              => implode(',', $tagIds),
            'author'            => $meta['author'] ?? 'LamGame Team',
            'author_id'         => 1,
            'locale'            => $meta['language'] ?? 'vi',
            'channels'          => '1',
            'status'            => 1,
            'allow_comments'    => 1,
            'meta_title'        => $meta['title'],
            'meta_description'  => $meta['description'] ?? '',
            'meta_keywords'     => implode(', ', $meta['keywords'] ?? []),
            'published_at'      => $publishDate,
        ];

        if ($this->option('dry-run')) {
            $this->info("  ✓ [DRY] {$meta['title']} → {$publishDate->format('Y-m-d H:i')}");
            $this->stats['created']++;
            return;
        }

        try {
            $blog = Blog::create($data);

            // Upload thumbnail
            $this->uploadThumbnail($path, $blog, $meta);

            // Mark as published
            $this->markPublished($folder, $slug, $blog->id);

            // Update metadata.json status to "published"
            $meta['status'] = 'published';
            $meta['published_id'] = $blog->id;
            $meta['published_at_server'] = now()->toIso8601String();
            File::put($metaFile, json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $this->info("  ✓ Created #{$blog->id}: {$meta['title']}");
            $this->stats['created']++;
        } catch (\Exception $e) {
            $this->error("  ✗ Error {$folder}: {$e->getMessage()}");
            $this->stats['errors']++;
        }
    }

    /**
     * Upload images from content folder to server storage,
     * rewrite src URLs in HTML body.
     */
    private function processImages(string $body, string $folderPath, string $slug): string
    {
        $imagesDir = $folderPath . '/images';

        // Rewrite local image references: images/xxx.webp → uploaded URL
        if (File::isDirectory($imagesDir)) {
            foreach (File::files($imagesDir) as $file) {
                $filename = $file->getFilename();
                $storagePath = 'blogs/content/' . $slug . '/' . $filename;

                Storage::disk('public')->put($storagePath, File::get($file));

                $publicUrl = '/storage/' . $storagePath;
                // Replace all references to this image
                $body = str_replace(
                    ['images/' . $filename, './images/' . $filename],
                    $publicUrl,
                    $body
                );
            }
        }

        // Also handle inline base64 or external images — leave as-is
        return $body;
    }

    private function uploadThumbnail(string $path, Blog $blog, array $meta): void
    {
        // Try common thumbnail formats
        $thumbNames = ['thumbnail.jpg', 'thumbnail.webp', 'thumbnail.png', 'thumbnail.svg'];
        if (! empty($meta['thumbnail'])) {
            array_unshift($thumbNames, $meta['thumbnail']);
        }

        foreach ($thumbNames as $name) {
            $thumbFile = $path . '/' . $name;
            if (File::exists($thumbFile)) {
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $storagePath = 'blogs/' . $blog->id . '/' . Str::random(40) . '.' . $ext;
                Storage::disk('public')->put($storagePath, File::get($thumbFile));
                $blog->update(['src' => $storagePath]);
                return;
            }
        }
    }

    private function isPublished(string $folder, string $slug): bool
    {
        // Check publish log first (fast)
        if (isset($this->publishLog[$folder])) {
            return true;
        }
        // Fallback: check DB
        return Blog::where('slug', $slug)->exists();
    }

    private function markPublished(string $folder, string $slug, int $blogId): void
    {
        $this->publishLog[$folder] = [
            'slug'         => $slug,
            'blog_id'      => $blogId,
            'published_at' => now()->toIso8601String(),
        ];
    }

    // --- Publish Log ---

    private function loadPublishLog(): void
    {
        if (File::exists($this->publishLogPath)) {
            $this->publishLog = json_decode(File::get($this->publishLogPath), true) ?? [];
        }
    }

    private function savePublishLog(): void
    {
        File::put(
            $this->publishLogPath,
            json_encode($this->publishLog, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    // --- Category & Tag helpers ---

    private function loadCategoryMap(): void
    {
        Category::all()->each(function ($cat) {
            $this->categoryMap[Str::lower($cat->name)] = $cat->id;
            $this->categoryMap[Str::lower($cat->slug)] = $cat->id;
        });
    }

    private function loadTagMap(): void
    {
        Tag::all()->each(function ($tag) {
            $this->tagMap[Str::lower($tag->name)] = $tag->id;
            $this->tagMap[Str::lower($tag->slug)] = $tag->id;
        });
    }

    private function resolveCategory(string $name): int
    {
        $key = Str::lower($name);
        if (isset($this->categoryMap[$key])) {
            return $this->categoryMap[$key];
        }
        $cat = Category::create(['name' => $name, 'slug' => Str::slug($name), 'status' => 1, 'locale' => 'vi']);
        $this->categoryMap[$key] = $cat->id;
        return $cat->id;
    }

    private function resolveTags(array $tags): array
    {
        $ids = [];
        foreach ($tags as $tagName) {
            $key = Str::lower($tagName);
            if (isset($this->tagMap[$key])) {
                $ids[] = $this->tagMap[$key];
                continue;
            }
            $tag = Tag::create(['name' => $tagName, 'slug' => Str::slug($tagName), 'status' => 1, 'locale' => 'vi']);
            $this->tagMap[$key] = $tag->id;
            $ids[] = $tag->id;
        }
        return $ids;
    }

    private function extractBody(string $html): string
    {
        if (preg_match('/<article[^>]*>(.*?)<\/article>/si', $html, $m)) return trim($m[1]);
        if (preg_match('/<body[^>]*>(.*?)<\/body>/si', $html, $m)) return trim($m[1]);
        if (preg_match('/(<h[12][^>]*>.*)/si', $html, $m)) return trim($m[1]);
        return $html;
    }

    private function dayNum(string $name): int
    {
        preg_match('/day(\d+)/', $name, $m);
        return (int) ($m[1] ?? 0);
    }
}
