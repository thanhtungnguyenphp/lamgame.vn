<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Mail\ProductApproved;
use App\Mail\ProductRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminProductController extends Controller
{
    public function sellers()
    {
        $products = Product::with(['seller', 'images'])
            ->whereNotNull('seller_id')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.products.sellers', compact('products'));
    }

    public function pending()
    {
        $products = Product::with(['seller', 'images'])
            ->where('pending_review', true)
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        return view('admin.products.pending', compact('products'));
    }

    public function review($id)
    {
        $product = Product::with(['seller.customer', 'images', 'categories', 'downloadable_links'])
            ->findOrFail($id);
        return view('admin.products.review', compact('product'));
    }

    public function approve(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (!$product->pending_review) {
            return back()->with('error', 'Sản phẩm này không ở trạng thái chờ duyệt.');
        }

        $product->update([
            'status' => 1,
            'pending_review' => false,
            'rejection_reason' => null,
        ]);

        if ($product->seller?->contact_email) {
            Mail::to($product->seller->contact_email)->queue(new ProductApproved($product, $product->seller));
        }

        return back()->with('success', 'Đã duyệt sản phẩm thành công!');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $product = Product::findOrFail($id);

        if (!$product->pending_review) {
            return back()->with('error', 'Sản phẩm này không ở trạng thái chờ duyệt.');
        }

        $product->update([
            'status' => 0,
            'pending_review' => false,
            'rejection_reason' => $request->reason,
        ]);

        if ($product->seller?->contact_email) {
            Mail::to($product->seller->contact_email)->queue(new ProductRejected($product, $product->seller, $request->reason));
        }

        return back()->with('success', 'Đã từ chối sản phẩm.');
    }
}
