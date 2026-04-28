<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SourceGameEarning;
use App\Models\SourceGameSeller;
use App\Models\SourceGameWithdrawal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerManageController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $query = SourceGameSeller::with('customer:id,first_name,last_name,email');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('shop_name', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->input('business_type')) {
            $query->where('business_type', $type);
        }

        if ($request->has('verified')) {
            $query->where('verified', $request->boolean('verified'));
        }

        $sortable = ['created_at', 'total_revenue', 'total_products', 'rating_avg'];
        $sortBy = in_array($request->input('sort_by'), $sortable) ? $request->input('sort_by') : 'created_at';
        $sortDir = $request->input('sort_dir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $perPage = min(max($request->integer('per_page', 15), 1), 100);
        $sellers = $query->paginate($perPage);

        $data = $sellers->map(function ($seller) {
            return [
                'id'            => $seller->id,
                'customer_id'   => $seller->customer_id,
                'customer_name' => $seller->customer ? trim($seller->customer->first_name . ' ' . $seller->customer->last_name) : null,
                'customer_email' => $seller->customer?->email,
                'shop_name'     => $seller->shop_name,
                'shop_slug'     => $seller->shop_slug,
                'business_type' => $seller->business_type,
                'status'        => $seller->status,
                'verified'      => (bool) $seller->verified,
                'total_products' => (int) $seller->total_products,
                'total_sales'   => (int) $seller->total_sales,
                'total_revenue' => (float) $seller->total_revenue,
                'rating_avg'    => (float) $seller->rating_avg,
                'created_at'    => $seller->created_at?->toIso8601String(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $data,
            'meta'   => [
                'current_page' => $sellers->currentPage(),
                'last_page'    => $sellers->lastPage(),
                'per_page'     => $sellers->perPage(),
                'total'        => $sellers->total(),
            ],
        ]);
    }

    public function detail(int $id): JsonResponse
    {
        $seller = SourceGameSeller::with('customer:id,first_name,last_name,email,phone')->find($id);

        if (!$seller) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy seller.'], 404);
        }

        $totalEarnings = SourceGameEarning::where('seller_id', $id)->where('status', 'completed')->sum('seller_amount');
        $totalWithdrawn = SourceGameWithdrawal::where('seller_id', $id)->where('status', 'completed')->sum('amount');
        $pendingWithdrawals = SourceGameWithdrawal::where('seller_id', $id)->whereIn('status', ['pending', 'processing'])->sum('amount');
        $availableBalance = $totalEarnings - $totalWithdrawn - $pendingWithdrawals;

        // Recent products
        $recentProducts = DB::table('products')
            ->join('product_flat', 'products.id', '=', 'product_flat.product_id')
            ->where('products.seller_id', $id)
            ->where('product_flat.locale', 'vi')
            ->select('products.id', 'product_flat.name', 'product_flat.price', 'product_flat.status', 'products.pending_review')
            ->orderByDesc('products.created_at')
            ->limit(5)
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'               => $seller->id,
                'customer_id'      => $seller->customer_id,
                'customer'         => $seller->customer ? [
                    'name'  => trim($seller->customer->first_name . ' ' . $seller->customer->last_name),
                    'email' => $seller->customer->email,
                    'phone' => $seller->customer->phone,
                ] : null,
                'shop_name'        => $seller->shop_name,
                'shop_slug'        => $seller->shop_slug,
                'shop_description' => $seller->shop_description,
                'shop_logo'        => $seller->logo_url,
                'shop_banner'      => $seller->banner_url,
                'contact_email'    => $seller->contact_email,
                'contact_phone'    => $seller->contact_phone,
                'website'          => $seller->website,
                'business_type'    => $seller->business_type,
                'status'           => $seller->status,
                'verified'         => (bool) $seller->verified,
                'verified_at'      => $seller->verified_at?->toIso8601String(),
                'stats' => [
                    'total_products'      => (int) $seller->total_products,
                    'total_sales'         => (int) $seller->total_sales,
                    'total_revenue'       => (float) $seller->total_revenue,
                    'total_earnings'      => (float) $totalEarnings,
                    'total_withdrawn'     => (float) $totalWithdrawn,
                    'pending_withdrawals' => (float) $pendingWithdrawals,
                    'available_balance'   => (float) $availableBalance,
                    'rating_avg'          => (float) $seller->rating_avg,
                    'rating_count'        => (int) $seller->rating_count,
                ],
                'bank_info' => [
                    'bank_name'    => $seller->bank_name,
                    'bank_account' => $seller->bank_account,
                    'bank_holder'  => $seller->bank_holder,
                ],
                'recent_products' => $recentProducts,
                'created_at'      => $seller->created_at?->toIso8601String(),
            ],
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $seller = SourceGameSeller::find($id);
        if (!$seller) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy seller.'], 404);
        }

        $validated = $request->validate([
            'shop_name'        => 'sometimes|string|max:255',
            'shop_description' => 'sometimes|nullable|string',
            'contact_email'    => 'sometimes|nullable|email',
            'contact_phone'    => 'sometimes|nullable|string|max:20',
            'website'          => 'sometimes|nullable|url',
            'business_type'    => 'sometimes|in:individual,company',
            'tax_id'           => 'sometimes|nullable|string|max:50',
            'bank_name'        => 'sometimes|nullable|string|max:100',
            'bank_account'     => 'sometimes|nullable|string|max:50',
            'bank_holder'      => 'sometimes|nullable|string|max:100',
        ]);

        $seller->update($validated);
        $seller->refresh();

        return response()->json([
            'status'  => 'success',
            'message' => 'Seller updated successfully',
            'data'    => [
                'id'               => $seller->id,
                'shop_name'        => $seller->shop_name,
                'shop_slug'        => $seller->shop_slug,
                'shop_description' => $seller->shop_description,
                'contact_email'    => $seller->contact_email,
                'contact_phone'    => $seller->contact_phone,
                'website'          => $seller->website,
                'business_type'    => $seller->business_type,
                'status'           => $seller->status,
                'verified'         => (bool) $seller->verified,
                'bank_info'        => [
                    'bank_name'    => $seller->bank_name,
                    'bank_account' => $seller->bank_account,
                    'bank_holder'  => $seller->bank_holder,
                ],
                'updated_at'       => $seller->updated_at?->toIso8601String(),
            ],
        ]);
    }

    public function approve(Request $request, int $id): JsonResponse
    {
        $seller = SourceGameSeller::find($id);
        if (!$seller) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy seller.'], 404);
        }
        if ($seller->status !== 'pending') {
            return response()->json(['status' => 'error', 'message' => 'Seller không ở trạng thái pending.'], 422);
        }

        $seller->update(['status' => 'active', 'verified' => true, 'verified_at' => now()]);

        return response()->json(['status' => 'success', 'message' => 'Đã duyệt seller.']);
    }

    public function reject(Request $request, int $id): JsonResponse
    {
        $seller = SourceGameSeller::find($id);
        if (!$seller) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy seller.'], 404);
        }
        if ($seller->status !== 'pending') {
            return response()->json(['status' => 'error', 'message' => 'Seller không ở trạng thái pending.'], 422);
        }

        $request->validate(['reason' => 'required|string|max:1000']);
        $seller->update(['status' => 'rejected']);

        return response()->json(['status' => 'success', 'message' => 'Đã từ chối seller.']);
    }

    public function suspend(Request $request, int $id): JsonResponse
    {
        $seller = SourceGameSeller::find($id);
        if (!$seller) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy seller.'], 404);
        }
        if ($seller->status !== 'active') {
            return response()->json(['status' => 'error', 'message' => 'Chỉ có thể suspend seller đang active.'], 422);
        }

        $request->validate(['reason' => 'required|string|max:1000']);

        $seller->update(['status' => 'suspended']);

        // Hide all seller products
        DB::table('product_flat')
            ->whereIn('product_id', function ($q) use ($id) {
                $q->select('id')->from('products')->where('seller_id', $id);
            })
            ->update(['status' => 0]);

        return response()->json(['status' => 'success', 'message' => 'Đã tạm khóa seller và ẩn sản phẩm.']);
    }

    public function activate(Request $request, int $id): JsonResponse
    {
        $seller = SourceGameSeller::find($id);
        if (!$seller) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy seller.'], 404);
        }
        if (!in_array($seller->status, ['suspended', 'rejected'])) {
            return response()->json(['status' => 'error', 'message' => 'Chỉ có thể activate seller đang suspended hoặc rejected.'], 422);
        }

        $seller->update(['status' => 'active']);

        // Restore products that are not pending review
        DB::table('product_flat')
            ->whereIn('product_id', function ($q) use ($id) {
                $q->select('id')->from('products')->where('seller_id', $id)->where('pending_review', false);
            })
            ->update(['status' => 1]);

        return response()->json(['status' => 'success', 'message' => 'Đã kích hoạt lại seller và khôi phục sản phẩm.']);
    }

    public function statistics(Request $request): JsonResponse
    {
        $byStatus = SourceGameSeller::selectRaw("status, COUNT(*) as count")
            ->groupBy('status')->pluck('count', 'status');

        $byType = SourceGameSeller::selectRaw("business_type, COUNT(*) as count")
            ->groupBy('business_type')->pluck('count', 'business_type');

        $verifiedCount = SourceGameSeller::where('verified', true)->count();
        $newThisMonth = SourceGameSeller::where('created_at', '>=', now()->startOfMonth())->count();

        $topSellers = SourceGameSeller::select('id', 'shop_name', 'total_products', 'total_revenue', 'rating_avg')
            ->where('status', 'active')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'total'     => (int) $byStatus->sum(),
                'by_status' => [
                    'active'    => (int) ($byStatus['active'] ?? 0),
                    'pending'   => (int) ($byStatus['pending'] ?? 0),
                    'suspended' => (int) ($byStatus['suspended'] ?? 0),
                    'rejected'  => (int) ($byStatus['rejected'] ?? 0),
                    'banned'    => (int) ($byStatus['banned'] ?? 0),
                ],
                'by_business_type' => [
                    'individual' => (int) ($byType['individual'] ?? 0),
                    'company'    => (int) ($byType['company'] ?? 0),
                ],
                'verified_count' => $verifiedCount,
                'new_this_month' => $newThisMonth,
                'top_sellers'    => $topSellers,
            ],
        ]);
    }
}
