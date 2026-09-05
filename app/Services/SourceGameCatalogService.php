<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SourceGameCatalogService
{
    private const CACHE_TTL = 300;

    /**
     * Products that have a public, individually visible Vietnamese detail URL.
     */
    public function publishedProductIds(): array
    {
        return Cache::remember('source_game_catalog.published_ids', self::CACHE_TTL, function () {
            return DB::table('products as p')
                ->join('product_flat as pf', function ($join) {
                    $join->on('pf.product_id', '=', 'p.id')
                        ->where('pf.locale', 'vi')
                        ->where('pf.channel', 'default');
                })
                ->where('p.type', 'downloadable')
                ->where('pf.status', 1)
                ->where('pf.visible_individually', 1)
                ->whereNotNull('pf.url_key')
                ->where('pf.url_key', '!=', '')
                ->distinct()
                ->pluck('p.id')
                ->map(fn ($id) => (int) $id)
                ->all();
        });
    }

    public function publishedCount(): int
    {
        return count($this->publishedProductIds());
    }

    /**
     * Paid SKUs that currently satisfy the audited merchandising baseline.
     */
    public function merchandisableSkus(): array
    {
        return Cache::remember('source_game_catalog.merchandisable_skus', self::CACHE_TTL, function () {
            $verifiedAssets = config('source-game-revenue.verified_assets', []);
            $minimumImages = (int) config('source-game-revenue.quality_requirements.minimum_images', 3);

            if (empty($verifiedAssets)) {
                return [];
            }

            $products = DB::table('products')
                ->whereIn('sku', array_keys($verifiedAssets))
                ->get(['id', 'sku'])
                ->keyBy('sku');

            $linksByProduct = DB::table('product_downloadable_links')
                ->whereIn('product_id', $products->pluck('id'))
                ->get()
                ->groupBy('product_id');

            return collect($verifiedAssets)
                ->filter(function (array $assets, string $sku) use ($products, $linksByProduct, $minimumImages) {
                    $product = $products->get($sku);
                    if (! $product) {
                        return false;
                    }

                    $demoPath = trim((string) ($assets['demo_path'] ?? ''), '/');
                    $demoEntry = $demoPath !== '' ? public_path($demoPath.'/index.html') : null;
                    $hasDemo = $demoEntry && is_file($demoEntry);
                    $imageCount = collect($assets['screenshots'] ?? [])
                        ->filter(fn ($path) => is_file(public_path(ltrim((string) $path, '/'))))
                        ->count();

                    return $hasDemo
                        && $imageCount >= $minimumImages
                        && $this->hasDeliverableDownload($linksByProduct->get($product->id, collect()));
                })
                ->keys()
                ->values()
                ->all();
        });
    }

    public function merchandisableProductIds(): array
    {
        $skus = $this->merchandisableSkus();
        if (empty($skus)) {
            return [];
        }

        return DB::table('products')
            ->whereIn('sku', $skus)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public function isVerifiedSku(?string $sku): bool
    {
        return $sku !== null && in_array($sku, $this->merchandisableSkus(), true);
    }

    public function hasDeliverableDownload(iterable $links): bool
    {
        foreach ($links as $link) {
            $type = data_get($link, 'type');
            if ($type === 'url' && filter_var(data_get($link, 'url'), FILTER_VALIDATE_URL)) {
                return true;
            }

            $file = data_get($link, 'file');
            if ($type !== 'url' && $file && Storage::disk('private')->exists($file)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Free products need a deliverable link; paid products additionally need
     * the audited catalog baseline before they can be promoted or purchased.
     */
    public function isAvailable(?string $sku, float $price, iterable $links): bool
    {
        if (! $this->hasDeliverableDownload($links)) {
            return false;
        }

        return $price <= 0 || $this->isVerifiedSku($sku);
    }

    public function clearCache(): void
    {
        Cache::forget('source_game_catalog.published_ids');
        Cache::forget('source_game_catalog.merchandisable_skus');
    }
}
