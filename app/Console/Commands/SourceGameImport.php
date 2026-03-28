<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SourceGameImport extends Command
{
    protected $signature = 'source-game:import
                            {--file=database/seeds/source-games.json : JSON file path}
                            {--dry-run : Preview without creating}';

    protected $description = 'Import source games from JSON file into Bagisto as downloadable products';

    public function handle(): int
    {
        $file = base_path($this->option('file'));

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        $games = json_decode(file_get_contents($file), true);
        if (!$games) {
            $this->error('Invalid JSON');
            return 1;
        }

        // Resolve source-game category
        $categoryId = DB::table('category_translations')
            ->where('slug', 'source-game')
            ->value('category_id');

        if (!$categoryId) {
            $this->error('Category "source-game" not found. Create it in admin first.');
            return 1;
        }

        $productRepo = app(\Webkul\Product\Repositories\ProductRepository::class);
        $created = 0;
        $skipped = 0;

        foreach ($games as $i => $game) {
            $slug = Str::slug($game['title']);

            // Skip if exists
            if (DB::table('product_flat')->where('url_key', $slug)->exists()) {
                $this->line("⏭ [{$i}] {$game['title']} — already exists");
                $skipped++;
                continue;
            }

            if ($this->option('dry-run')) {
                $this->info("[DRY] {$game['title']} → /source-game/{$slug}");
                $created++;
                continue;
            }

            DB::beginTransaction();
            try {
                $sku = 'SG-' . strtoupper(Str::random(8));

                $product = $productRepo->create([
                    'type' => 'downloadable',
                    'sku' => $sku,
                    'attribute_family_id' => 1,
                ]);

                $productRepo->update([
                    'sku' => $sku,
                    'channel' => 'default',
                    'locale' => 'vi',
                    'name' => $game['title'],
                    'url_key' => $slug . '-' . $product->id,
                    'short_description' => $game['short_description'] ?? Str::limit(strip_tags($game['description'] ?? ''), 200),
                    'description' => $game['description'] ?? '',
                    'price' => $game['price'] ?? 0,
                    'status' => 1,
                    'visible_individually' => 1,
                    'guest_checkout' => 1,
                    'categories' => [$categoryId],
                ], $product->id);

                // Download and save thumbnail if URL provided
                if (!empty($game['image_url'])) {
                    $this->saveProductImage($product->id, $game['image_url']);
                }

                Event::dispatch('catalog.product.create.after', $product->refresh());

                DB::commit();
                $created++;
                $this->info("✅ [{$i}] {$game['title']} (ID:{$product->id})");

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("❌ [{$i}] {$game['title']}: {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info("Done: {$created} created, {$skipped} skipped");
        return 0;
    }

    private function saveProductImage(int $productId, string $url): void
    {
        try {
            $response = Http::timeout(15)->get($url);
            if (!$response->successful()) return;

            $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $filename = $productId . '/' . Str::random(20) . '.' . $ext;
            $path = 'product/' . $filename;

            Storage::disk('public')->put($path, $response->body());

            DB::table('product_images')->insert([
                'type' => null,
                'path' => $path,
                'product_id' => $productId,
                'position' => 0,
            ]);
        } catch (\Exception $e) {
            $this->warn("  ⚠ Image failed: {$e->getMessage()}");
        }
    }
}
