<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class SourceGameRevenueAudit extends Command
{
    protected $signature = 'source-games:audit-revenue-catalog {--strict : Return a failure code when requirements are missing}';

    protected $description = 'Audit the selected paid source-game catalog without changing production data';

    public function handle(): int
    {
        $skus = config('source-game-revenue.featured_skus', []);
        $verifiedAssets = config('source-game-revenue.verified_assets', []);
        $minimumImages = (int) config('source-game-revenue.quality_requirements.minimum_images', 3);
        $privateDisk = Storage::disk('private');

        $products = DB::table('products as p')
            ->leftJoin('product_flat as pf', function ($join) {
                $join->on('pf.product_id', '=', 'p.id')->where('pf.locale', 'vi');
            })
            ->whereIn('p.sku', $skus)
            ->get(['p.id', 'p.sku', 'p.has_demo', 'pf.name', 'pf.url_key', 'pf.price'])
            ->keyBy('sku');

        $rows = [];
        $incomplete = 0;

        foreach ($skus as $sku) {
            $product = $products->get($sku);
            if (! $product) {
                $rows[] = [$sku, 'Missing product', 0, 'No', 'No', 'No', 'No', 'No'];
                $incomplete++;
                continue;
            }

            $assets = $verifiedAssets[$sku] ?? [];
            $databaseImages = DB::table('product_images')
                ->where('product_id', $product->id)
                ->pluck('path')
                ->filter(fn ($path) => file_exists(storage_path('app/public/'.$path)))
                ->count();
            $verifiedScreenshots = collect($assets['screenshots'] ?? [])
                ->filter(fn ($path) => file_exists(public_path(ltrim($path, '/'))))
                ->count();
            $imageCount = $databaseImages + $verifiedScreenshots;

            $demoPath = $assets['demo_path'] ?? null;
            $hasDemo = (bool) $product->has_demo
                || ($demoPath && file_exists(public_path(ltrim($demoPath, '/').'index.html')));

            $links = DB::table('product_downloadable_links')->where('product_id', $product->id)->get();
            $hasDownloadLink = $links->isNotEmpty();
            [$hasDownloadFile, $hasDocumentation, $hasLicense] = $this->inspectDownloads($links, $privateDisk);

            $complete = $imageCount >= $minimumImages
                && $hasDemo
                && $hasDownloadFile
                && $hasDocumentation
                && $hasLicense;

            if (! $complete) {
                $incomplete++;
            }

            $rows[] = [
                $product->sku,
                $product->name ?: '—',
                $imageCount,
                $hasDemo ? 'Yes' : 'No',
                $hasDownloadLink ? 'Yes' : 'No',
                $hasDownloadFile ? 'Yes' : 'No',
                $hasDocumentation ? 'Yes' : 'No',
                $hasLicense ? 'Yes' : 'No',
            ];
        }

        $this->table(['SKU', 'Product', 'Images', 'Demo', 'Link', 'File', 'Docs', 'License'], $rows);
        $this->info(sprintf('%d selected products; %d still need catalog assets.', count($skus), $incomplete));

        return $this->option('strict') && $incomplete > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function inspectDownloads($links, $privateDisk): array
    {
        $hasFile = false;
        $hasDocumentation = false;
        $hasLicense = false;

        foreach ($links as $link) {
            if (($link->type ?? null) === 'url') {
                $hasFile = $hasFile || filter_var($link->url ?? null, FILTER_VALIDATE_URL) !== false;
                continue;
            }

            if (empty($link->file) || ! $privateDisk->exists($link->file)) {
                continue;
            }

            $hasFile = true;
            $fileName = strtolower((string) ($link->file_name ?? $link->file));
            $hasDocumentation = $hasDocumentation || preg_match('/readme|guide|hướng dẫn/', $fileName) === 1;
            $hasLicense = $hasLicense || str_contains($fileName, 'license');

            if (class_exists(ZipArchive::class) && strtolower(pathinfo($link->file, PATHINFO_EXTENSION)) === 'zip') {
                $zip = new ZipArchive();
                if ($zip->open($privateDisk->path($link->file)) === true) {
                    for ($index = 0; $index < $zip->numFiles; $index++) {
                        $entry = strtolower((string) $zip->getNameIndex($index));
                        $baseName = basename($entry);
                        $hasDocumentation = $hasDocumentation || preg_match('/^readme(?:\.[a-z0-9]+)?$|guide|hướng dẫn/', $baseName) === 1;
                        $hasLicense = $hasLicense || preg_match('/^licen[cs]e(?:\.[a-z0-9]+)?$/', $baseName) === 1;
                    }
                    $zip->close();
                }
            }
        }

        return [$hasFile, $hasDocumentation, $hasLicense];
    }
}
