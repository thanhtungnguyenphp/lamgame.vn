<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Models\Product;

class DemoController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::where('url_key', $slug)->where('has_demo', true)->firstOrFail();

        return view('source-game.demo', [
            'product' => $product,
            'demoUrl' => $product->demo_url ?: asset('storage/demos/' . $product->id . '/index.html'),
        ]);
    }

    public function info(int $id)
    {
        $product = Product::select('id', 'name', 'has_demo', 'demo_url')->findOrFail($id);

        return response()->json([
            'has_demo' => $product->has_demo,
            'demo_url' => $product->has_demo ? ($product->demo_url ?: route('source-game.demo', $product->url_key)) : null,
        ]);
    }

    public function store(Request $request, int $id)
    {
        $product = Product::findOrFail($id);
        $this->authorize('update', $product);

        $request->validate([
            'demo_file' => 'required_without:demo_url|file|mimes:zip|max:51200',
            'demo_url' => 'required_without:demo_file|nullable|url',
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
        $this->authorize('update', $product);

        Storage::disk('public')->deleteDirectory("demos/{$id}");
        $product->update(['demo_url' => null, 'demo_file_path' => null, 'has_demo' => false]);

        return response()->json(['message' => 'Demo removed']);
    }
}
