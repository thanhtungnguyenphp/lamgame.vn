<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SourceGameSeller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class ProductManageController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $locale = 'vi';

        $query = DB::table('products')
            ->join('product_flat', 'products.id', '=', 'product_flat.product_id')
            ->where('product_flat.locale', $locale)
            ->leftJoin('source_game_sellers', 'products.seller_id', '=', 'source_game_sellers.id')
            ->select([
                'products.id',
                'products.sku',
                'products.type',
                'products.seller_id',
                'products.pending_review',
                'products.rejection_reason',
                'products.created_at',
                'products.updated_at',
                'product_flat.name',
                'product_flat.url_key',
                'product_flat.price',
                'product_flat.special_price',
                'product_flat.status',
                'product_flat.short_description',
                'source_game_sellers.shop_name as seller_shop_name',
                'source_game_sellers.shop_slug as seller_shop_slug',
            ]);

        // Filters
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('product_flat.name', 'like', "%{$search}%")
                  ->orWhere('products.sku', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('product_flat.status', $request->integer('status'));
        }

        if ($request->has('pending_review')) {
            $query->where('products.pending_review', $request->boolean('pending_review'));
        }

        if ($sellerId = $request->input('seller_id')) {
            $query->where('products.seller_id', $sellerId);
        }

        if ($categoryId = $request->input('category_id')) {
            $query->whereExists(function ($q) use ($categoryId) {
                $q->select(DB::raw(1))
                  ->from('product_categories')
                  ->whereColumn('product_categories.product_id', 'products.id')
                  ->where('product_categories.category_id', $categoryId);
            });
        }

        // Sort
        $sortable = ['created_at', 'price', 'name'];
        $sortBy = in_array($request->input('sort_by'), $sortable) ? $request->input('sort_by') : 'created_at';
        $sortDir = $request->input('sort_dir') === 'asc' ? 'asc' : 'desc';

        $sortColumn = match ($sortBy) {
            'price' => 'product_flat.price',
            'name'  => 'product_flat.name',
            default => 'products.created_at',
        };

        $query->orderBy($sortColumn, $sortDir);

        $perPage = min(max($request->integer('per_page', 15), 1), 100);
        $products = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data'   => $products->items(),
            'meta'   => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'per_page'     => $products->perPage(),
                'total'        => $products->total(),
            ],
        ]);
    }

    public function detail(int $id): JsonResponse
    {
        $product = Product::with(['seller:id,shop_name,shop_slug,contact_email', 'images', 'downloadable_links', 'categories.translations'])
            ->find($id);

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm.'], 404);
        }

        $flat = $product->flat;

        // EAV custom attributes
        $customAttrs = $this->getCustomAttributes($id);

        // Stats
        $purchaseCount = DB::table('order_items')->where('product_id', $id)->sum('qty_ordered');
        $reviewStats = DB::table('product_reviews')
            ->where('product_id', $id)
            ->where('status', 'approved')
            ->selectRaw('COALESCE(AVG(rating), 0) as avg, COUNT(*) as count')
            ->first();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'                => $product->id,
                'sku'               => $product->sku,
                'type'              => $product->type,
                'pending_review'    => (bool) $product->pending_review,
                'rejection_reason'  => $product->rejection_reason,
                'name'              => $flat?->name,
                'description'       => $flat?->description,
                'short_description' => $flat?->short_description,
                'url_key'           => $flat?->url_key,
                'price'             => $flat?->price ? (float) $flat->price : null,
                'special_price'     => $flat?->special_price ? (float) $flat->special_price : null,
                'special_price_from' => $flat?->special_price_from,
                'special_price_to'  => $flat?->special_price_to,
                'status'            => $flat?->status ? (int) $flat->status : 0,
                'thumbnail'         => $flat?->thumbnail,
                'images'            => $product->images->map(fn ($img) => [
                    'id' => $img->id, 'path' => $img->path, 'position' => $img->position,
                ]),
                'categories'        => $product->categories->map(fn ($cat) => [
                    'id' => $cat->id, 'name' => $cat->translations->first()?->name,
                ]),
                'downloadable_links' => $product->downloadable_links->map(fn ($link) => [
                    'id' => $link->id, 'title' => $link->title ?? $link->file_name, 'price' => (float) $link->price, 'type' => $link->type,
                ]),
                'attributes'        => $customAttrs,
                'seller'            => $product->seller ? [
                    'id'        => $product->seller->id,
                    'shop_name' => $product->seller->shop_name,
                    'shop_slug' => $product->seller->shop_slug,
                ] : null,
                'stats' => [
                    'purchase_count' => (int) $purchaseCount,
                    'rating_avg'     => round((float) $reviewStats->avg, 2),
                    'rating_count'   => (int) $reviewStats->count,
                ],
                'meta' => [
                    'meta_title'       => $flat?->meta_title,
                    'meta_description' => $flat?->meta_description,
                    'meta_keywords'    => $flat?->meta_keywords,
                ],
                'created_at' => $product->created_at?->toIso8601String(),
                'updated_at' => $product->updated_at?->toIso8601String(),
            ],
        ]);
    }

    public function statistics(Request $request): JsonResponse
    {
        $locale = 'vi';

        // Totals
        $totals = DB::table('product_flat')
            ->where('locale', $locale)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as published
            ")
            ->first();

        $pendingReview = DB::table('products')->where('pending_review', true)->count();
        $draft = (int) $totals->total - (int) $totals->published - $pendingReview;

        // By category (top 10)
        $byCategory = DB::table('product_categories')
            ->join('category_translations', function ($j) use ($locale) {
                $j->on('product_categories.category_id', '=', 'category_translations.category_id')
                  ->where('category_translations.locale', $locale);
            })
            ->select('product_categories.category_id', 'category_translations.name', DB::raw('COUNT(*) as count'))
            ->groupBy('product_categories.category_id', 'category_translations.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // By seller (top 10)
        $bySeller = DB::table('products')
            ->join('source_game_sellers', 'products.seller_id', '=', 'source_game_sellers.id')
            ->select('products.seller_id', 'source_game_sellers.shop_name', DB::raw('COUNT(*) as count'))
            ->whereNotNull('products.seller_id')
            ->groupBy('products.seller_id', 'source_game_sellers.shop_name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Top selling (top 10)
        $topSelling = DB::table('order_items')
            ->join('product_flat', function ($j) use ($locale) {
                $j->on('order_items.product_id', '=', 'product_flat.product_id')
                  ->where('product_flat.locale', $locale);
            })
            ->select('order_items.product_id as id', 'product_flat.name', DB::raw('SUM(order_items.qty_ordered) as purchase_count'))
            ->groupBy('order_items.product_id', 'product_flat.name')
            ->orderByDesc('purchase_count')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'total'          => (int) $totals->total,
                'published'      => (int) $totals->published,
                'draft'          => max($draft, 0),
                'pending_review' => $pendingReview,
                'by_category'    => $byCategory,
                'by_seller'      => $bySeller,
                'top_selling'    => $topSelling,
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'sku'                          => 'required|string|unique:products,sku',
            'name'                         => 'required|string|max:255',
            'description'                  => 'required|string',
            'short_description'            => 'nullable|string|max:500',
            'url_key'                      => 'nullable|string|unique:product_flat,url_key',
            'price'                        => 'required|numeric|min:0',
            'special_price'                => 'nullable|numeric|min:0',
            'special_price_from'           => 'nullable|date',
            'special_price_to'             => 'nullable|date|after_or_equal:special_price_from',
            'seller_id'                    => 'nullable|integer|exists:source_game_sellers,id',
            'category_ids'                 => 'nullable|array',
            'category_ids.*'               => 'integer|exists:categories,id',
            'status'                       => 'nullable|in:0,1',
            'attributes'                   => 'nullable|array',
            'attributes.game_engine'       => 'nullable|string',
            'attributes.programming_language' => 'nullable|string',
            'attributes.file_size'         => 'nullable|string',
            'attributes.version'           => 'nullable|string',
            'attributes.video_demo_url'    => 'nullable|string',
            'attributes.demo_url'          => 'nullable|string',
            'attributes.author_name'       => 'nullable|string',
            'meta_title'                   => 'nullable|string|max:255',
            'meta_description'             => 'nullable|string|max:500',
            'meta_keywords'                => 'nullable|string|max:255',
        ]);

        $locale = 'vi';
        $channel = 'default';

        DB::beginTransaction();
        try {
            $productRepo = app(\Webkul\Product\Repositories\ProductRepository::class);

            // Step 1: Create base product
            $product = $productRepo->create([
                'type'                => 'downloadable',
                'sku'                 => $request->input('sku'),
                'attribute_family_id' => 1,
            ]);

            // Step 2: Update via repository (handles EAV + flat)
            $urlKey = $request->input('url_key') ?: \Illuminate\Support\Str::slug($request->input('name')) . '-' . $product->id;

            $updateData = [
                'sku'                 => $request->input('sku'),
                'channel'             => $channel,
                'locale'              => $locale,
                'name'                => $request->input('name'),
                'url_key'             => $urlKey,
                'description'         => $request->input('description'),
                'short_description'   => $request->input('short_description', ''),
                'price'               => $request->input('price'),
                'special_price'       => $request->input('special_price'),
                'special_price_from'  => $request->input('special_price_from'),
                'special_price_to'    => $request->input('special_price_to'),
                'status'              => $request->integer('status', 0),
                'visible_individually' => 1,
                'guest_checkout'      => 0,
                'meta_title'          => $request->input('meta_title'),
                'meta_description'    => $request->input('meta_description'),
                'meta_keywords'       => $request->input('meta_keywords'),
            ];

            if ($request->has('category_ids')) {
                $updateData['categories'] = $request->input('category_ids');
            }

            $productRepo->update($updateData, $product->id);

            // Step 3: Set seller_id + pending_review
            $directUpdate = [];
            if ($sellerId = $request->input('seller_id')) {
                $directUpdate['seller_id'] = $sellerId;
                $directUpdate['pending_review'] = true;
            }
            if (!empty($directUpdate)) {
                DB::table('products')->where('id', $product->id)->update($directUpdate);
            }

            // Step 4: Save custom EAV attributes
            if ($attrs = $request->input('attributes')) {
                $this->saveCustomAttributes($product->id, $attrs);
            }

            DB::commit();

            $product->refresh();

            Event::dispatch('catalog.product.update.after', $product);

            return response()->json([
                'status'  => 'success',
                'message' => 'Đã tạo sản phẩm.',
                'data'    => ['id' => $product->id, 'sku' => $product->sku],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('ProductManage store error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Lỗi tạo sản phẩm: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm.'], 404);
        }

        $request->validate([
            'sku'                          => "sometimes|string|unique:products,sku,{$id}",
            'name'                         => 'sometimes|string|max:255',
            'description'                  => 'sometimes|string',
            'short_description'            => 'nullable|string|max:500',
            'url_key'                      => "nullable|string|unique:product_flat,url_key,{$id},product_id",
            'price'                        => 'sometimes|numeric|min:0',
            'special_price'                => 'nullable|numeric|min:0',
            'special_price_from'           => 'nullable|date',
            'special_price_to'             => 'nullable|date|after_or_equal:special_price_from',
            'seller_id'                    => 'nullable|integer|exists:source_game_sellers,id',
            'category_ids'                 => 'nullable|array',
            'category_ids.*'               => 'integer|exists:categories,id',
            'status'                       => 'nullable|in:0,1',
            'attributes'                   => 'nullable|array',
            'meta_title'                   => 'nullable|string|max:255',
            'meta_description'             => 'nullable|string|max:500',
            'meta_keywords'                => 'nullable|string|max:255',
        ]);

        $locale = 'vi';
        $channel = 'default';

        DB::beginTransaction();
        try {
            $productRepo = app(\Webkul\Product\Repositories\ProductRepository::class);

            $updateData = ['channel' => $channel, 'locale' => $locale];

            $flatFields = ['name', 'description', 'short_description', 'url_key', 'price',
                'special_price', 'special_price_from', 'special_price_to', 'status',
                'meta_title', 'meta_description', 'meta_keywords'];

            foreach ($flatFields as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->input($field);
                }
            }

            if ($request->has('sku')) {
                $updateData['sku'] = $request->input('sku');
                DB::table('products')->where('id', $id)->update(['sku' => $request->input('sku')]);
            }

            if ($request->has('category_ids')) {
                $updateData['categories'] = $request->input('category_ids');
            }

            Event::dispatch('catalog.product.update.before', $id);

            $updatedProduct = $productRepo->update($updateData, $id);

            // Direct fields on products table
            $directUpdate = [];
            if ($request->has('seller_id')) {
                $directUpdate['seller_id'] = $request->input('seller_id');
            }
            if (!empty($directUpdate)) {
                DB::table('products')->where('id', $id)->update($directUpdate);
            }

            // Custom EAV attributes
            if ($attrs = $request->input('attributes')) {
                $this->saveCustomAttributes($id, $attrs);
            }

            DB::commit();

            Event::dispatch('catalog.product.update.after', $updatedProduct);

            return response()->json([
                'status'  => 'success',
                'message' => 'Đã cập nhật sản phẩm.',
                'data'    => ['id' => $id],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('ProductManage update error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Lỗi cập nhật: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm.'], 404);
        }

        // Block delete if product has orders
        $hasOrders = DB::table('order_items')->where('product_id', $id)->exists();
        if ($hasOrders) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không thể xóa sản phẩm đã có đơn hàng. Hãy chuyển sang draft thay vì xóa.',
            ], 422);
        }

        try {
            $productRepo = app(\Webkul\Product\Repositories\ProductRepository::class);
            $productRepo->delete($id);

            // Decrement seller stats
            if ($product->seller_id) {
                SourceGameSeller::where('id', $product->seller_id)->decrement('total_products');
            }

            return response()->json(['status' => 'success', 'message' => 'Đã xóa sản phẩm.']);
        } catch (\Exception $e) {
            \Log::error('ProductManage destroy error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Lỗi xóa sản phẩm: ' . $e->getMessage()], 500);
        }
    }

    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm.'], 404);
        }

        $request->validate(['status' => 'required|in:0,1']);

        $newStatus = $request->integer('status');

        if ($newStatus === 1 && $product->pending_review) {
            return response()->json(['status' => 'error', 'message' => 'Sản phẩm đang chờ duyệt, không thể publish trực tiếp.'], 422);
        }

        DB::table('product_flat')
            ->where('product_id', $id)
            ->update(['status' => $newStatus]);

        return response()->json([
            'status'  => 'success',
            'message' => $newStatus === 1 ? 'Đã publish sản phẩm.' : 'Đã chuyển sản phẩm sang draft.',
        ]);
    }

    public function review(Request $request, int $id): JsonResponse
    {
        $product = Product::with('seller')->find($id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm.'], 404);
        }

        if (!$product->pending_review) {
            return response()->json(['status' => 'error', 'message' => 'Sản phẩm không ở trạng thái chờ duyệt.'], 422);
        }

        $request->validate([
            'action'           => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|nullable|string|max:1000',
        ]);

        if ($request->input('action') === 'approve') {
            $product->update([
                'pending_review'   => false,
                'rejection_reason' => null,
            ]);
            DB::table('product_flat')->where('product_id', $id)->update(['status' => 1]);

            if ($product->seller?->contact_email) {
                \Illuminate\Support\Facades\Mail::to($product->seller->contact_email)
                    ->queue(new \App\Mail\ProductApproved($product, $product->seller));
            }

            return response()->json(['status' => 'success', 'message' => 'Đã duyệt sản phẩm.']);
        }

        // Reject
        $product->update([
            'pending_review'   => false,
            'rejection_reason' => $request->input('rejection_reason'),
        ]);
        DB::table('product_flat')->where('product_id', $id)->update(['status' => 0]);

        if ($product->seller?->contact_email) {
            \Illuminate\Support\Facades\Mail::to($product->seller->contact_email)
                ->queue(new \App\Mail\ProductRejected($product, $product->seller, $request->input('rejection_reason')));
        }

        return response()->json(['status' => 'success', 'message' => 'Đã từ chối sản phẩm.']);
    }

    public function uploadImages(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm.'], 404);
        }

        $request->validate([
            'images'   => 'required|array|min:1|max:10',
            'images.*' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $lastPosition = DB::table('product_images')
            ->where('product_id', $id)
            ->max('position') ?? 0;

        $uploaded = [];

        foreach ($request->file('images') as $file) {
            $manager = new ImageManager();
            $image = $manager->make($file)->encode('webp');
            $path = 'product/' . $id . '/' . Str::random(40) . '.webp';
            Storage::put($path, $image);

            $record = DB::table('product_images')->insertGetId([
                'type'       => 'images',
                'path'       => $path,
                'product_id' => $id,
                'position'   => ++$lastPosition,
            ]);

            $uploaded[] = [
                'id'       => $record,
                'path'     => $path,
                'url'      => Storage::url($path),
                'position' => $lastPosition,
            ];
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Đã upload ' . count($uploaded) . ' hình.',
            'data'    => $uploaded,
        ]);
    }

    public function deleteImage(int $id, int $imageId): JsonResponse
    {
        $image = DB::table('product_images')
            ->where('id', $imageId)
            ->where('product_id', $id)
            ->first();

        if (!$image) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy hình.'], 404);
        }

        Storage::delete($image->path);
        DB::table('product_images')->where('id', $imageId)->delete();

        return response()->json(['status' => 'success', 'message' => 'Đã xóa hình.']);
    }

    public function reorderImages(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm.'], 404);
        }

        $request->validate([
            'image_ids'   => 'required|array|min:1',
            'image_ids.*' => 'integer',
        ]);

        $position = 0;
        foreach ($request->input('image_ids') as $imageId) {
            DB::table('product_images')
                ->where('id', $imageId)
                ->where('product_id', $id)
                ->update(['position' => ++$position]);
        }

        return response()->json(['status' => 'success', 'message' => 'Đã sắp xếp lại hình.']);
    }

    private function saveCustomAttributes(int $productId, array $attrs): void
    {
        $codes = ['game_engine', 'programming_language', 'file_size', 'version', 'video_demo_url', 'demo_url', 'author_name'];

        $attributeMap = DB::table('attributes')
            ->whereIn('code', $codes)
            ->pluck('id', 'code');

        foreach ($attrs as $code => $value) {
            if (!isset($attributeMap[$code])) continue;

            DB::table('product_attribute_values')->updateOrInsert(
                ['product_id' => $productId, 'attribute_id' => $attributeMap[$code]],
                ['text_value' => $value]
            );
        }
    }

    private function getCustomAttributes(int $productId): array
    {
        $codes = ['game_engine', 'programming_language', 'file_size', 'version', 'video_demo_url', 'demo_url', 'author_name'];

        $values = DB::table('product_attribute_values')
            ->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
            ->where('product_attribute_values.product_id', $productId)
            ->whereIn('attributes.code', $codes)
            ->select('attributes.code', 'product_attribute_values.text_value')
            ->get()
            ->pluck('text_value', 'code');

        $result = [];
        foreach ($codes as $code) {
            $result[$code] = $values[$code] ?? null;
        }

        return $result;
    }
}
