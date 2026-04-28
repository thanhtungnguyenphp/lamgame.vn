<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use App\Models\SourceGameSeller;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Category\Repositories\CategoryRepository;

class SellerProductController extends Controller
{
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductDownloadableLinkRepository $downloadableLinkRepository,
        protected ProductImageRepository $productImageRepository,
        protected AttributeRepository $attributeRepository,
        protected CategoryRepository $categoryRepository
    ) {}

    public function index()
    {
        $seller = Auth::guard('customer')->user()->seller;
        
        if (!$seller || !$seller->isActive()) {
            return redirect()->route('seller.pending');
        }

        $products = \App\Models\Product::where('seller_id', $seller->id)
            ->where('type', 'downloadable')
            ->with(['product_flats', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('shop::seller.products.index', compact('products', 'seller'));
    }

    public function create()
    {
        $seller = Auth::guard('customer')->user()->seller;
        
        if (!$seller || !$seller->canUploadProduct()) {
            return redirect()->route('seller.pending')->with('error', 'Bạn không có quyền upload sản phẩm');
        }

        // Load categories có slug chứa "source"
        $categories = $this->categoryRepository
            ->getModel()
            ->with('translations')
            ->where('status', 1)
            ->whereHas('translations', function($q) {
                $q->where('slug', 'like', '%source%');
            })
            ->orderBy('position')
            ->get();

        return view('shop::seller.products.create', compact('categories', 'seller'));
    }

    public function store(Request $request)
    {
        $seller = Auth::guard('customer')->user()->seller;
        
        if (!$seller || !$seller->canUploadProduct()) {
            return back()->with('error', 'Bạn không có quyền upload sản phẩm');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'game_engine' => 'nullable|string|max:100',
            'programming_language' => 'nullable|string|max:100',
            'version' => 'nullable|string|max:50',
            'requirements' => 'nullable|string',
            'images.*' => 'nullable|image|max:5120',
            'source_files.*' => 'nullable|file|max:102400',
        ]);

        $sku = 'SG-' . strtoupper(Str::random(8));
        $locale = core()->getCurrentLocale()->code ?? 'vi';
        $channel = core()->getCurrentChannel()->code ?? 'default';

        DB::beginTransaction();
        try {
            // Step 1: Create product (basic record only)
            $product = $this->productRepository->create([
                'type' => 'downloadable',
                'sku' => $sku,
                'attribute_family_id' => 1,
            ]);

            // Step 2: Prepare data for update (attribute values)
            $urlKey = Str::slug($validated['name']) . '-' . $product->id;
            
            $updateData = [
                'sku' => $sku,
                'channel' => $channel,
                'locale' => $locale,
                'name' => $validated['name'],
                'url_key' => $urlKey,
                'short_description' => $validated['short_description'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'status' => 0,
                'visible_individually' => 1,
                'guest_checkout' => 0,
                'categories' => [$validated['category_id']],
            ];

            // Step 3: Handle images upload
            if ($request->hasFile('images')) {
                $updateData['images'] = [];
                foreach ($request->file('images') as $index => $image) {
                    $updateData['images'][$index] = $image;
                }
            }

            // Step 4: Handle downloadable links
            if ($request->hasFile('source_files')) {
                $updateData['downloadable_links'] = [];
                foreach ($request->file('source_files') as $index => $file) {
                    $updateData['downloadable_links']['link_' . $index] = [
                        $locale => [
                            'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                        ],
                        'price' => 0,
                        'type' => 'file',
                        'file' => $file,
                        'file_name' => $file->getClientOriginalName(),
                        'downloads' => 0,
                        'sort_order' => $index,
                    ];
                }
            }

            // Step 5: Update product with all attribute values
            $this->productRepository->update($updateData, $product->id);

            // Step 6: Set seller_id (not in fillable, use direct update)
            DB::table('products')->where('id', $product->id)->update([
                'seller_id' => $seller->id,
                'pending_review' => true,
            ]);

            // Refresh product and dispatch event to trigger flat indexer
            $product->refresh();
            Event::dispatch('catalog.product.create.after', $product);

            // Update seller stats
            $seller->increment('total_products');

            DB::commit();

            return redirect()->route('seller.products.index')
                ->with('success', 'Sản phẩm đã được tạo và đang chờ duyệt');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Seller product create error: ' . $e->getMessage(), [
                'seller_id' => $seller->id,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $seller = Auth::guard('customer')->user()->seller;
        
        // Handle both model binding and raw ID
        $productId = $id instanceof \Webkul\Product\Contracts\Product ? $id->id : $id;
        
        $product = $this->productRepository->findOrFail($productId);

        $productSellerId = DB::table('products')->where('id', $productId)->value('seller_id');
        if ($productSellerId != $seller->id) {
            abort(403, 'Unauthorized');
        }

        $categories = $this->categoryRepository
            ->getModel()
            ->whereHas('translations', function($q) {
                $q->where('slug', 'like', '%source%');
            })
            ->with('translations')
            ->get();

        return view('shop::seller.products.edit', compact('product', 'categories', 'seller'));
    }

    public function update(Request $request, $id)
    {
        $seller = Auth::guard('customer')->user()->seller;
        
        // Handle both model binding and raw ID
        $productId = $id instanceof \Webkul\Product\Contracts\Product ? $id->id : $id;
        
        $product = $this->productRepository->findOrFail($productId);

        // Check ownership via seller_id
        $productSellerId = DB::table('products')->where('id', $productId)->value('seller_id');
        if ($productSellerId != $seller->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|max:5120',
            'source_files.*' => 'nullable|file|max:102400',
        ]);

        $locale = core()->getCurrentLocale()->code ?? 'vi';
        $channel = core()->getCurrentChannel()->code ?? 'default';

        DB::beginTransaction();
        try {
            $updateData = [
                'channel' => $channel,
                'locale' => $locale,
                'name' => $validated['name'],
                'short_description' => $validated['short_description'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'categories' => [$validated['category_id']],
            ];

            // Handle new images
            if ($request->hasFile('images')) {
                $updateData['images'] = [];
                foreach ($request->file('images') as $index => $image) {
                    $updateData['images'][$index] = $image;
                }
            }

            // Handle new downloadable links
            if ($request->hasFile('source_files')) {
                $updateData['downloadable_links'] = [];
                foreach ($request->file('source_files') as $index => $file) {
                    $updateData['downloadable_links']['link_' . $index] = [
                        $locale => [
                            'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                        ],
                        'price' => 0,
                        'type' => 'file',
                        'file' => $file,
                        'file_name' => $file->getClientOriginalName(),
                        'downloads' => 0,
                        'sort_order' => $index,
                    ];
                }
            }

            $this->productRepository->update($updateData, $productId);

            // Dispatch event to trigger flat indexer
            $product->refresh();
            Event::dispatch('catalog.product.update.after', $product);

            DB::commit();

            return redirect()->route('seller.products.index')
                ->with('success', 'Sản phẩm đã được cập nhật');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Seller product update error: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $seller = Auth::guard('customer')->user()->seller;
        
        // Handle both model binding and raw ID
        $productId = $id instanceof \Webkul\Product\Contracts\Product ? $id->id : $id;
        
        $product = $this->productRepository->findOrFail($productId);

        $productSellerId = DB::table('products')->where('id', $productId)->value('seller_id');
        if ($productSellerId != $seller->id) {
            abort(403, 'Unauthorized');
        }

        // Delete files
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        foreach ($product->downloadable_links as $link) {
            if ($link->file) {
                Storage::disk('public')->delete($link->file);
            }
        }

        $this->productRepository->delete($productId);
        $seller->decrement('total_products');

        return redirect()->route('seller.products.index')
            ->with('success', 'Sản phẩm đã được xóa');
    }
}
