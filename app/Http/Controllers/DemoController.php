<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Models\Product;

class DemoController extends Controller
{
    public function show(string $slug)
    {
        // Try finding by url_key in product_flat
        $product = \DB::table('product_flat')
            ->join('products', 'products.id', '=', 'product_flat.product_id')
            ->where('product_flat.url_key', $slug)
            ->where('product_flat.locale', 'vi')
            ->where('products.has_demo', true)
            ->select('products.*', 'product_flat.name', 'product_flat.url_key', 'product_flat.price')
            ->first();

        if (!$product) {
            abort(404);
        }

        // Determine demo URL
        $demoUrl = $product->demo_url;
        if (!$demoUrl) {
            // Fallback: check /games/{slug}/index.html
            if (file_exists(public_path("games/{$slug}/index.html"))) {
                $demoUrl = "/games/{$slug}/index.html";
            } else {
                $demoUrl = asset("storage/demos/{$product->id}/index.html");
            }
        }

        return view('source-game.demo', [
            'product'  => $product,
            'demoUrl'  => $demoUrl,
            'backUrl'  => route('lamgame.source-game.detail', $slug),
            'buyUrl'   => route('lamgame.source-game.detail', $slug),
        ]);
    }

    public function info(int $id)
    {
        $product = Product::select('id', 'has_demo', 'demo_url')->find($id);

        if (!$product) {
            return response()->json(['has_demo' => false, 'demo_url' => null]);
        }

        $urlKey = \DB::table('product_flat')
            ->where('product_id', $id)->where('locale', 'vi')
            ->value('url_key');

        return response()->json([
            'has_demo' => (bool) $product->has_demo,
            'demo_url' => $product->has_demo
                ? ($product->demo_url ?: route('source-game.demo', $urlKey))
                : null,
        ]);
    }

    public function store(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'demo_file' => 'required_without:demo_url|file|mimes:zip|max:51200',
            'demo_url'  => 'required_without:demo_file|nullable|url',
        ]);

        if ($request->hasFile('demo_file')) {
            $path = "demos/{$id}";
            Storage::disk('public')->deleteDirectory($path);

            $destination = Storage::disk('public')->path($path);

            $zip = new \ZipArchive;
            $tmpPath = $request->file('demo_file')->getPathname();

            if ($zip->open($tmpPath) === true) {
                try {
                    $this->safeExtract($zip, $destination);
                } catch (\RuntimeException $e) {
                    $zip->close();
                    Storage::disk('public')->deleteDirectory($path);

                    return response()->json(['message' => $e->getMessage()], 422);
                }
                $zip->close();
            } else {
                return response()->json(['message' => 'Không mở được file ZIP.'], 422);
            }

            $product->update(['demo_file_path' => $path, 'demo_url' => null, 'has_demo' => true]);
        } else {
            $product->update(['demo_url' => $request->demo_url, 'demo_file_path' => null, 'has_demo' => true]);
        }

        return response()->json(['message' => 'Demo uploaded', 'has_demo' => true]);
    }

    /**
     * Giải nén ZIP an toàn: chống Zip-Slip (path traversal) + whitelist đuôi file web.
     *
     * @throws \RuntimeException khi phát hiện entry nguy hiểm hoặc đuôi không cho phép
     */
    private function safeExtract(\ZipArchive $zip, string $destination): void
    {
        // Đuôi file an toàn cho demo web (HTML5/WebGL game)
        $allowedExt = [
            'html', 'htm', 'js', 'mjs', 'css', 'json', 'wasm', 'data', 'mem', 'symbols',
            'png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'ico', 'bmp',
            'mp3', 'ogg', 'wav', 'mp4', 'webm',
            'woff', 'woff2', 'ttf', 'otf', 'eot',
            'txt', 'map', 'xml', 'glb', 'gltf', 'bin', 'unityweb', 'br', 'gz', 'atlas',
        ];

        $realDestination = rtrim($destination, '/') . '/';

        if (! is_dir($realDestination)) {
            @mkdir($realDestination, 0755, true);
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);

            if ($name === false || $name === '') {
                continue;
            }

            // Chuẩn hóa, chặn absolute path và null byte
            $name = str_replace('\\', '/', $name);

            if (str_contains($name, "\0") || str_starts_with($name, '/') || preg_match('~^[A-Za-z]:~', $name)) {
                throw new \RuntimeException('File ZIP chứa đường dẫn không hợp lệ.');
            }

            // Chặn path traversal
            if (preg_match('~(^|/)\.\.(/|$)~', $name)) {
                throw new \RuntimeException('File ZIP chứa đường dẫn traversal (../) — bị từ chối.');
            }

            $isDir = str_ends_with($name, '/');

            // Kiểm tra đích nằm trong thư mục cho phép (phòng thủ kép)
            $target = $realDestination . $name;
            $targetDir = $isDir ? $target : dirname($target);
            $resolvedBase = realpath($realDestination) ?: $realDestination;

            if (! $isDir) {
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if ($ext === '' || ! in_array($ext, $allowedExt, true)) {
                    throw new \RuntimeException("File ZIP chứa loại tệp không cho phép: .{$ext}");
                }
            }

            if (! is_dir($targetDir)) {
                @mkdir($targetDir, 0755, true);
            }

            if ($isDir) {
                continue;
            }

            // Xác nhận thư mục đích thực sự nằm trong base sau khi tạo
            $resolvedTargetDir = realpath($targetDir);
            if ($resolvedTargetDir === false || ! str_starts_with($resolvedTargetDir . '/', rtrim($resolvedBase, '/') . '/')) {
                throw new \RuntimeException('File ZIP cố ghi ra ngoài thư mục demo — bị từ chối.');
            }

            $stream = $zip->getStream($name);
            if ($stream === false) {
                continue;
            }
            file_put_contents($target, stream_get_contents($stream));
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
    }

    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);

        Storage::disk('public')->deleteDirectory("demos/{$id}");
        $product->update(['demo_url' => null, 'demo_file_path' => null, 'has_demo' => false]);

        return response()->json(['message' => 'Demo removed']);
    }
}
