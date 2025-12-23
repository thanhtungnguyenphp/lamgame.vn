<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

        $products = $this->productRepository
            ->where('company_id', $seller->id)
            ->where('type', 'downloadable')
            ->with(['flat', 'images', 'downloadable_links'])
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

        $categories = $this->categoryRepository
            ->getModel()
            ->whereHas('translations', function($q) {
                $q->where('slug', 'like', '%source%');
            })
            ->with('translations')
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

        // Create product
        $product = $this->productRepository->create([
            'type' => 'downloadable',
            'sku' => 'SG-' . strtoupper(Str::random(8)),
            'company_id' => $seller->id,
            'attribute_family_id' => 1,
            'vi' => [
                'name' => $validated['name'],
                'short_description' => $validated['short_description'],
                'description' => $validated['description'],
                'url_key' => Str::slug($validated['name']),
            ],
            'price' => $validated['price'],
            'status' => 0, // Pending approval
            'visible_individually' => 1,
            'categories' => [$validated['category_id']],
        ]);

        // Upload images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product/' . $product->id, 'public');
                $this->productImageRepository->create([
                    'product_id' => $product->id,
                    'type' => 'images',
                    'path' => $path,
                ]);
            }
        }

        // Upload source files
        if ($request->hasFile('source_files')) {
            foreach ($request->file('source_files') as $index => $file) {
                $path = $file->store('downloadable/' . $product->id, 'public');
                $this->downloadableLinkRepository->create([
                    'product_id' => $product->id,
                    'title' => $file->getClientOriginalName(),
                    'type' => 'file',
                    'file' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'downloads' => 0,
                ]);
            }
        }

        // Update seller stats
        $seller->increment('total_products');

        return redirect()->route('seller.products.index')
            ->with('success', 'Sản phẩm đã được tạo và đang chờ duyệt');
    }

    public function edit($id)
    {
        $seller = Auth::guard('customer')->user()->seller;
        $product = $this->productRepository->findOrFail($id);

        if ($product->company_id != $seller->id) {
            abort(403, 'Unauthorized');
        }

        $categories = $this->categoryRepository
            ->getModel()
            ->whereHas('translations', function($q) {
                $q->where('slug', 'like', '%source%');
            })
            ->with('translations')
            ->get();

        return view('seller.products.edit', compact('product', 'categories', 'seller'));
    }

    public function update(Request $request, $id)
    {
        $seller = Auth::guard('customer')->user()->seller;
        $product = $this->productRepository->findOrFail($id);

        if ($product->company_id != $seller->id) {
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

        $this->productRepository->update([
            'vi' => [
                'name' => $validated['name'],
                'short_description' => $validated['short_description'],
                'description' => $validated['description'],
            ],
            'price' => $validated['price'],
            'categories' => [$validated['category_id']],
        ], $id);

        // Upload new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product/' . $product->id, 'public');
                $this->productImageRepository->create([
                    'product_id' => $product->id,
                    'type' => 'images',
                    'path' => $path,
                ]);
            }
        }

        // Upload new source files
        if ($request->hasFile('source_files')) {
            foreach ($request->file('source_files') as $file) {
                $path = $file->store('downloadable/' . $product->id, 'public');
                $this->downloadableLinkRepository->create([
                    'product_id' => $product->id,
                    'title' => $file->getClientOriginalName(),
                    'type' => 'file',
                    'file' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'downloads' => 0,
                ]);
            }
        }

        return redirect()->route('seller.products.index')
            ->with('success', 'Sản phẩm đã được cập nhật');
    }

    public function destroy($id)
    {
        $seller = Auth::guard('customer')->user()->seller;
        $product = $this->productRepository->findOrFail($id);

        if ($product->company_id != $seller->id) {
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

        $this->productRepository->delete($id);
        $seller->decrement('total_products');

        return redirect()->route('seller.products.index')
            ->with('success', 'Sản phẩm đã được xóa');
    }
}
