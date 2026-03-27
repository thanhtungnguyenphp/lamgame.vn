<?php

namespace App\Http\Controllers;

use App\Models\SourceGameVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SellerVersionController extends Controller
{
    public function index($productId)
    {
        $seller = request()->auth_seller;
        $product = \Webkul\Product\Models\Product::where('id', $productId)
            ->where('seller_id', $seller->id)
            ->firstOrFail();

        $versions = SourceGameVersion::where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('seller.versions.index', compact('product', 'versions'));
    }

    public function store(Request $request, $productId)
    {
        $seller = request()->auth_seller;
        \Webkul\Product\Models\Product::where('id', $productId)
            ->where('seller_id', $seller->id)
            ->firstOrFail();

        $request->validate([
            'version'   => 'required|string|max:50',
            'changelog' => 'nullable|string|max:2000',
            'file'      => 'required|file|max:102400', // 100MB
        ]);

        $file = $request->file('file');
        $path = $file->store("source-games/{$productId}/versions", 'public');

        SourceGameVersion::create([
            'product_id'  => $productId,
            'version'     => $request->version,
            'changelog'   => $request->changelog,
            'file_path'   => $path,
            'file_size'   => $file->getSize(),
            'uploaded_by'  => $seller->id,
        ]);

        return redirect()->route('seller.products.versions', $productId)
            ->with('success', "Version {$request->version} đã upload thành công!");
    }
}
