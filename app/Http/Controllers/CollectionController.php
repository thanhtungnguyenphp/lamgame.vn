<?php

namespace App\Http\Controllers;

use App\Models\CollectionItem;
use App\Models\UserCollection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = UserCollection::where('customer_id', auth('customer')->id())
            ->withCount('items')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('lamgame.pages.collections', compact('collections'));
    }

    public function show(string $slug)
    {
        $collection = UserCollection::where('slug', $slug)
            ->where(function ($q) {
                $q->where('is_public', true)
                  ->orWhere('customer_id', auth('customer')->id());
            })
            ->firstOrFail();

        $products = \Illuminate\Support\Facades\DB::table('collection_items')
            ->join('product_flat', 'collection_items.product_id', '=', 'product_flat.product_id')
            ->leftJoin('product_images', function ($join) {
                $join->on('product_flat.product_id', '=', 'product_images.product_id')
                    ->whereRaw('product_images.id = (select min(id) from product_images where product_id = product_flat.product_id)');
            })
            ->where('collection_items.collection_id', $collection->id)
            ->where('product_flat.locale', 'vi')
            ->select('product_flat.name', 'product_flat.url_key', 'product_flat.price', 'product_images.path as image')
            ->paginate(12);

        return view('lamgame.pages.collection-detail', compact('collection', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);

        UserCollection::create([
            'customer_id' => auth('customer')->id(),
            'name'        => $request->name,
        ]);

        return back()->with('success', 'Tạo bộ sưu tập thành công!');
    }

    public function addItem(Request $request, $collectionId)
    {
        $request->validate(['product_id' => 'required|integer']);

        $collection = UserCollection::where('id', $collectionId)
            ->where('customer_id', auth('customer')->id())
            ->firstOrFail();

        CollectionItem::firstOrCreate([
            'collection_id' => $collection->id,
            'product_id'    => $request->product_id,
        ]);

        return back()->with('success', 'Đã thêm vào bộ sưu tập!');
    }

    public function removeItem($collectionId, $productId)
    {
        CollectionItem::where('collection_id', $collectionId)
            ->where('product_id', $productId)
            ->delete();

        return back()->with('success', 'Đã xóa khỏi bộ sưu tập.');
    }

    public function destroy($id)
    {
        UserCollection::where('id', $id)
            ->where('customer_id', auth('customer')->id())
            ->delete();

        return redirect()->route('collections.index')->with('success', 'Đã xóa bộ sưu tập.');
    }
}
