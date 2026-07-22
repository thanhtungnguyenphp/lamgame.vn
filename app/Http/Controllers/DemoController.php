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

            $zip = new \ZipArchive;
            $tmpPath = $request->file('demo_file')->getPathname();
            if ($zip->open($tmpPath) === true) {
                $zip->extractTo(Storage::disk('public')->path($path));
                $zip->close();
            }
            $product->update(['demo_file_path' => $path, 'demo_url' => null, 'has_demo' => true]);
        } else {
            $product->update(['demo_url' => $request->demo_url, 'demo_file_path' => null, 'has_demo' => true]);
        }

        return response()->json(['message' => 'Demo uploaded', 'has_demo' => true]);
    }

    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);

        Storage::disk('public')->deleteDirectory("demos/{$id}");
        $product->update(['demo_url' => null, 'demo_file_path' => null, 'has_demo' => false]);

        return response()->json(['message' => 'Demo removed']);
    }
}
