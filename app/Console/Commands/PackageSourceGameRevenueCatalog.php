<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use ZipArchive;

class PackageSourceGameRevenueCatalog extends Command
{
    protected $signature = 'source-games:package-revenue-catalog
        {--sku=* : Package only selected configured SKUs}
        {--dry-run : Validate inputs without writing archives}
        {--force : Overwrite an existing archive}';

    protected $description = 'Build verified source-game ZIP archives at the downloadable paths already stored in the database';

    public function handle(): int
    {
        if (! class_exists(ZipArchive::class)) {
            $this->error('PHP ZipArchive extension is required.');
            return self::FAILURE;
        }

        $configured = config('source-game-revenue.verified_assets', []);
        $requested = array_values(array_filter($this->option('sku')));
        $skus = $requested ?: array_keys($configured);

        $unknown = array_diff($skus, array_keys($configured));
        if ($unknown) {
            $this->error('SKUs are not configured as verified: '.implode(', ', $unknown));
            return self::FAILURE;
        }

        $products = DB::table('products as p')
            ->leftJoin('product_flat as pf', function ($join) {
                $join->on('pf.product_id', '=', 'p.id')->where('pf.locale', 'vi');
            })
            ->whereIn('p.sku', $skus)
            ->get(['p.id', 'p.sku', 'pf.name'])
            ->keyBy('sku');

        $failed = false;

        foreach ($skus as $sku) {
            try {
                $product = $products->get($sku);
                if (! $product) {
                    throw new RuntimeException('Product does not exist.');
                }

                $sourceRelative = $configured[$sku]['source_path'] ?? null;
                $sourcePath = $sourceRelative ? public_path($sourceRelative) : null;
                if (! $sourcePath || ! is_dir($sourcePath)) {
                    throw new RuntimeException('Verified source directory is missing.');
                }

                $link = DB::table('product_downloadable_links')
                    ->where('product_id', $product->id)
                    ->where('type', 'file')
                    ->orderBy('sort_order')
                    ->first();
                if (! $link || empty($link->file)) {
                    throw new RuntimeException('No file-type downloadable link is configured.');
                }

                $licenses = $this->licensesFor((int) $product->id);
                if ($licenses->isEmpty()) {
                    throw new RuntimeException('No active product license is configured.');
                }

                $files = $this->sourceFiles($sourcePath);
                if ($files === []) {
                    throw new RuntimeException('Source directory is empty.');
                }

                $this->line(sprintf(
                    '%s: %d source files -> %s%s',
                    $sku,
                    count($files),
                    $link->file,
                    $this->option('dry-run') ? ' [dry-run]' : ''
                ));

                if ($this->option('dry-run')) {
                    continue;
                }

                $destination = Storage::disk('private')->path($link->file);
                if (is_file($destination) && ! $this->option('force')) {
                    throw new RuntimeException('Archive already exists; use --force to replace it.');
                }

                $this->buildArchive(
                    $destination,
                    $sourcePath,
                    $files,
                    $sku,
                    (string) ($product->name ?: $sku),
                    $configured[$sku]['demo_path'] ?? null,
                    $licenses->all(),
                );

                $this->info(sprintf(
                    '%s packaged: %s (%d bytes, sha256 %s)',
                    $sku,
                    $link->file,
                    filesize($destination),
                    hash_file('sha256', $destination),
                ));
            } catch (\Throwable $e) {
                $failed = true;
                $this->error($sku.': '.$e->getMessage());
            }
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }

    private function licensesFor(int $productId)
    {
        return DB::table('product_licenses as pl')
            ->join('license_types as lt', 'lt.id', '=', 'pl.license_type_id')
            ->where('pl.product_id', $productId)
            ->where('pl.is_active', true)
            ->orderBy('pl.price')
            ->get([
                'lt.name',
                'lt.slug',
                'lt.max_projects',
                'lt.allows_resale',
                'lt.description',
            ]);
    }

    private function sourceFiles(string $sourcePath): array
    {
        $root = realpath($sourcePath);
        if ($root === false) {
            return [];
        }

        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (! $file->isFile() || $file->isLink()) {
                continue;
            }

            $realPath = $file->getRealPath();
            if ($realPath === false || ! str_starts_with($realPath, $root.DIRECTORY_SEPARATOR)) {
                throw new RuntimeException('Source contains an unsafe path.');
            }

            $relative = str_replace(DIRECTORY_SEPARATOR, '/', substr($realPath, strlen($root) + 1));
            if ($relative === '' || str_contains($relative, '../')) {
                throw new RuntimeException('Source contains an invalid relative path.');
            }

            $files[$relative] = $realPath;
        }

        ksort($files);

        return $files;
    }

    private function buildArchive(
        string $destination,
        string $sourcePath,
        array $files,
        string $sku,
        string $productName,
        ?string $demoPath,
        array $licenses,
    ): void {
        $directory = dirname($destination);
        if (! is_dir($directory) && ! mkdir($directory, 0775, true) && ! is_dir($directory)) {
            throw new RuntimeException('Unable to create archive directory.');
        }

        $temporary = $destination.'.tmp-'.bin2hex(random_bytes(6));
        $zip = new ZipArchive();
        if ($zip->open($temporary, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Unable to create temporary ZIP archive.');
        }

        try {
            $checksums = [];
            foreach ($files as $relative => $realPath) {
                $archivePath = 'src/'.$relative;
                if (! $zip->addFile($realPath, $archivePath)) {
                    throw new RuntimeException('Unable to add '.$relative.' to archive.');
                }
                $checksums[] = hash_file('sha256', $realPath).'  '.$archivePath;
            }

            $readme = $this->readme($sku, $productName, $demoPath, count($files));
            $license = $this->license($sku, $licenses);
            $checksums[] = hash('sha256', $readme).'  README.md';
            $checksums[] = hash('sha256', $license).'  LICENSE.md';

            $zip->addFromString('README.md', $readme);
            $zip->addFromString('LICENSE.md', $license);
            $zip->addFromString('CHECKSUMS.sha256', implode("\n", $checksums)."\n");
        } finally {
            $zip->close();
        }

        if (! is_file($temporary) || filesize($temporary) === 0) {
            @unlink($temporary);
            throw new RuntimeException('Generated ZIP archive is empty.');
        }

        if (! rename($temporary, $destination)) {
            @unlink($temporary);
            throw new RuntimeException('Unable to atomically publish ZIP archive.');
        }
    }

    private function readme(string $sku, string $productName, ?string $demoPath, int $fileCount): string
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $demoUrl = $demoPath ? $baseUrl.'/'.ltrim($demoPath, '/') : 'Không có';
        $marketplaceTermsUrl = $baseUrl.'/dieu-khoan-marketplace';

        return <<<MD
# {$productName}

- SKU: `{$sku}`
- Số file source: {$fileCount}
- Demo đã xác minh: {$demoUrl}

## Cấu trúc gói

- `src/`: source và asset của game.
- `LICENSE.md`: các tier license đang áp dụng cho sản phẩm.
- `CHECKSUMS.sha256`: checksum SHA-256 để kiểm tra tính toàn vẹn.

## Chạy local

Game là ứng dụng web tĩnh. Không mở trực tiếp bằng `file://`; hãy chạy qua HTTP server:

```bash
cd src
python3 -m http.server 8080
```

Sau đó mở `http://localhost:8080/`.

## Lưu ý

- Giữ nguyên cấu trúc thư mục vì HTML/JavaScript tham chiếu asset theo đường dẫn tương đối.
- Quyền sử dụng phụ thuộc license gắn với đơn hàng; xem `LICENSE.md`.
- Hỗ trợ và điều khoản: {$marketplaceTermsUrl}
MD;
    }

    private function license(string $sku, array $licenses): string
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $termsUrl = $baseUrl.'/dieu-khoan-su-dung';
        $marketplaceTermsUrl = $baseUrl.'/dieu-khoan-marketplace';

        $rows = collect($licenses)->map(function ($license) {
            $projects = (int) $license->max_projects === 0 ? 'Không giới hạn' : (string) $license->max_projects;
            $endProductResale = $license->allows_resale ? 'Có, với sản phẩm cuối' : 'Không';

            return "| {$license->name} | {$projects} | {$endProductResale} | {$license->description} |";
        })->implode("\n");

        return <<<MD
# Điều khoản license — {$sku}

Gói ZIP này không tự cấp thêm quyền. Quyền sử dụng được xác định bởi tier license đã chọn trong đơn hàng LamGame và điều khoản đang công bố tại:

- {$termsUrl}
- {$marketplaceTermsUrl}

| Tier | Số dự án | Bán sản phẩm cuối | Mô tả trong hệ thống |
|---|---:|---|---|
{$rows}

## Giới hạn chung

- Không chia sẻ download link hoặc license key.
- Không phân phối, bán lại hoặc công khai raw source code nguyên bản.
- `allows_resale` chỉ áp dụng cho sản phẩm cuối đã được phát triển/chỉnh sửa, không phải raw source.
- Không được xóa thông tin bản quyền hoặc sử dụng cho mục đích bất hợp pháp.
- Nếu nội dung tier và trang điều khoản khác nhau, điều khoản gắn với đơn hàng và quy định đang có hiệu lực được ưu tiên.
MD;
    }
}
