<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Webkul\Product\Models\Product;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Category\Repositories\CategoryRepository;

class SellerProductController extends Controller
{
    public function __construct(
        protected AttributeFamilyRepository $attributeFamilyRepository,
        protected CategoryRepository $categoryRepository
    ) {}

    public function index()
    {
        $seller = Auth::guard('customer')->user()->seller;
        
        $products = Product::where('seller_id', $seller->id)
            ->with(['images', 'inventories'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('seller.products.index', [
            'products' => $products,
            'seller' => $seller,
            'page_title' => 'Quản lý sản phẩm - Seller',
        ]);
    }

    public function create()
    {
        $seller = Auth::guard('customer')->user()->seller;
        
        $attributeFamilies = $this->attributeFamilyRepository->all();
        $categories = $this->categoryRepository->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);

        return view('seller.products.create', [
            'seller' => $seller,
            'attributeFamilies' => $attributeFamilies,
            'categories' => $categories,
            'page_title' => 'Thêm sản phẩm mới - Seller',
        ]);
    }

    public function store(Request $request)
    {
        $seller = Auth::guard('customer')->user()->seller;

        $validated = $request->validate([
            'type' => 'required|in:simple,downloadable',
            'attribute_family_id' => 'required|exists:attribute_families,id',
            'sku' => 'required|unique:products,sku|regex:/^[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*$/',
            'name' => 'required|string|max:255',
            'url_key' => 'required|string|unique:product_flat,url_key',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'special_price' => 'nullable|numeric|min:0',
            'categories' => 'required|array',
            'images.*' => 'nullable|image|max:2048',
            'source_file' => 'nullable|file|max:524288', // 512MB
        ]);

        // Auto-convert SKU to slug format (lowercase, replace _ with -)
        $validated['sku'] = strtolower(str_replace('_', '-', $validated['sku']));

        // Create product
        $product = Product::create([
            'type' => $validated['type'],
            'attribute_family_id' => $validated['attribute_family_id'],
            'sku' => $validated['sku'],
            'seller_id' => $seller->id,
            'status' => 0, // Draft - waiting for admin approval
        ]);

        // Save product flat data
        $product->product_flats()->create([
            'sku' => $validated['sku'],
            'name' => $validated['name'],
            'url_key' => $validated['url_key'],
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'special_price' => $validated['special_price'],
            'locale' => core()->getCurrentLocale()->code,
            'channel' => core()->getCurrentChannel()->code,
        ]);

        // Attach categories
        $product->categories()->sync($validated['categories']);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product/' . $product->id, 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        // Handle source file for downloadable products
        if ($validated['type'] === 'downloadable' && $request->hasFile('source_file')) {
            $path = $request->file('source_file')->store('products/downloads/' . $product->id, 'public');
            
            $product->downloadable_links()->create([
                'title' => $validated['name'],
                'price' => 0,
                'type' => 'file',
                'file' => $path,
                'downloads' => 0,
                'sort_order' => 1,
            ]);
        }

        return redirect()->route('seller.products.index')
            ->with('success', 'Sản phẩm đã được tạo và đang chờ admin duyệt.');
    }

    public function edit($id)
    {
        $seller = Auth::guard('customer')->user()->seller;
        $product = Product::where('seller_id', $seller->id)->findOrFail($id);
        
        $attributeFamilies = $this->attributeFamilyRepository->all();
        $categories = $this->categoryRepository->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);

        return view('seller.products.edit', [
            'product' => $product,
            'seller' => $seller,
            'attributeFamilies' => $attributeFamilies,
            'categories' => $categories,
            'page_title' => 'Chỉnh sửa sản phẩm - Seller',
        ]);
    }

    public function update(Request $request, $id)
    {
        $seller = Auth::guard('customer')->user()->seller;
        $product = Product::where('seller_id', $seller->id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'special_price' => 'nullable|numeric|min:0',
            'categories' => 'required|array',
            'images.*' => 'nullable|image|max:2048',
            'source_file' => 'nullable|file|max:524288',
        ]);

        // Update product flat
        $product->product_flats()->update([
            'name' => $validated['name'],
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'special_price' => $validated['special_price'],
        ]);

        // Update categories
        $product->categories()->sync($validated['categories']);

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product/' . $product->id, 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        // Handle new source file
        if ($request->hasFile('source_file')) {
            // Delete old downloadable links
            $product->downloadable_links()->delete();
            
            $path = $request->file('source_file')->store('products/downloads/' . $product->id, 'public');
            
            $product->downloadable_links()->create([
                'title' => $validated['name'],
                'price' => 0,
                'type' => 'file',
                'file' => $path,
                'downloads' => 0,
                'sort_order' => 1,
            ]);
        }

        return redirect()->route('seller.products.index')
            ->with('success', 'Sản phẩm đã được cập nhật.');
    }

    public function destroy($id)
    {
        $seller = Auth::guard('customer')->user()->seller;
        $product = Product::where('seller_id', $seller->id)->findOrFail($id);
        
        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', 'Sản phẩm đã được xóa.');
    }

    public function deleteImage($imageId)
    {
        $seller = Auth::guard('customer')->user()->seller;
        
        $image = \Webkul\Product\Models\ProductImage::findOrFail($imageId);
        
        // Check if image belongs to seller's product
        if ($image->product->seller_id !== $seller->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        // Delete record
        $image->delete();

        return response()->json(['success' => true, 'message' => 'Đã xóa hình ảnh']);
    }
}
