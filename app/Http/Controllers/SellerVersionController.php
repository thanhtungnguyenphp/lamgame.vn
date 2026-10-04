<?php

namespace App\Http\Controllers;

use App\Models\SourceGameVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerVersionController extends Controller
{
    public function index($productId)
    {
        $seller = Auth::guard('customer')->user()->seller;
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
        $seller = Auth::guard('customer')->user()->seller;
        \Webkul\Product\Models\Product::where('id', $productId)
            ->where('seller_id', $seller->id)
            ->firstOrFail();

        $request->validate([
            'version'   => 'required|string|max:50',
            'changelog' => 'nullable|string|max:2000',
            'file'      => 'required|file|max:102400|mimes:zip,rar,7z,gz,tar', // 100MB, chỉ archive
        ]);

        $file = $request->file('file');

        // Chặn đuôi kép / đuôi thực thi (phòng thủ thêm ngoài mimes)
        $ext = strtolower($file->getClientOriginalExtension());
        $allowedExt = ['zip', 'rar', '7z', 'gz', 'tar'];
        if (! in_array($ext, $allowedExt, true)) {
            return back()->withErrors(['file' => 'Chỉ chấp nhận file nén: ' . implode(', ', $allowedExt)]);
        }

        // Lưu vào disk PRIVATE để không lộ qua /storage (phải tải qua route có kiểm tra quyền)
        $path = $file->store("source-games/{$productId}/versions", 'private');

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

    /**
     * Tải file version. Cho phép: seller sở hữu sản phẩm HOẶC khách đã mua sản phẩm.
     */
    public function download($productId, $versionId)
    {
        $customer = Auth::guard('customer')->user();

        if (! $customer) {
            abort(403);
        }

        $version = SourceGameVersion::where('product_id', $productId)
            ->where('id', $versionId)
            ->firstOrFail();

        $isOwner = $customer->seller
            && \Webkul\Product\Models\Product::where('id', $productId)
                ->where('seller_id', $customer->seller->id)
                ->exists();

        $hasPurchased = \Webkul\Sales\Models\OrderItem::query()
            ->where('product_id', $productId)
            ->whereHas('order', function ($q) use ($customer) {
                $q->where('customer_id', $customer->id)
                    ->whereIn('status', ['processing', 'completed']);
            })
            ->exists();

        if (! $isOwner && ! $hasPurchased) {
            abort(403, 'Bạn cần mua sản phẩm này để tải source.');
        }

        if (! Storage::disk('private')->exists($version->file_path)) {
            abort(404, 'File không tồn tại.');
        }

        $version->increment('downloads');

        return Storage::disk('private')->download($version->file_path);
    }
}
