<?php

namespace App\Http\Controllers;

use App\Models\SourceGameSeller;
use Illuminate\Support\Facades\DB;

class SellerProfileController extends Controller
{
    public function show(string $slug)
    {
        $seller = SourceGameSeller::where('shop_slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $products = DB::table('products')
            ->join('product_flat', 'products.id', '=', 'product_flat.product_id')
            ->leftJoin('product_images', function ($join) {
                $join->on('products.id', '=', 'product_images.product_id')
                    ->whereRaw('product_images.id = (select min(id) from product_images where product_id = products.id)');
            })
            ->where('products.seller_id', $seller->id)
            ->where('products.type', 'downloadable')
            ->where('product_flat.locale', 'vi')
            ->where('product_flat.status', 1)
            ->select(
                'products.id',
                'product_flat.name',
                'product_flat.url_key',
                'product_flat.price',
                'product_flat.short_description',
                'product_images.path as image'
            )
            ->orderBy('products.created_at', 'desc')
            ->paginate(12);

        return view('lamgame.pages.seller-profile', compact('seller', 'products'));
    }
}
