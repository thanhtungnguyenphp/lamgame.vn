<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SourceGameEarning;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EarningManageController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $query = SourceGameEarning::with(['seller:id,shop_name', 'order:id,increment_id'])
            ->select('source_game_earnings.*');

        if ($sellerId = $request->input('seller_id')) {
            $query->where('seller_id', $sellerId);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($from = $request->input('date_from')) {
            $query->whereDate('source_game_earnings.created_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('source_game_earnings.created_at', '<=', $to);
        }

        $sortable = ['created_at', 'order_amount', 'seller_amount'];
        $sortBy = in_array($request->input('sort_by'), $sortable) ? $request->input('sort_by') : 'created_at';
        $sortDir = $request->input('sort_dir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $perPage = min(max($request->integer('per_page', 15), 1), 100);
        $earnings = $query->paginate($perPage);

        // Get product names
        $productIds = $earnings->pluck('product_id')->unique()->filter();
        $productNames = DB::table('product_flat')
            ->whereIn('product_id', $productIds)
            ->where('locale', 'vi')
            ->pluck('name', 'product_id');

        $data = $earnings->map(fn ($e) => [
            'id'                   => $e->id,
            'seller'               => $e->seller ? ['id' => $e->seller->id, 'shop_name' => $e->seller->shop_name] : null,
            'order_id'             => $e->order_id,
            'order_increment_id'   => $e->order?->increment_id,
            'product'              => ['id' => $e->product_id, 'name' => $productNames[$e->product_id] ?? null],
            'order_amount'         => (float) $e->order_amount,
            'platform_fee_percent' => (float) $e->platform_fee_percent,
            'platform_fee_amount'  => (float) $e->platform_fee_amount,
            'seller_amount'        => (float) $e->seller_amount,
            'status'               => $e->status,
            'completed_at'         => $e->completed_at?->toIso8601String(),
            'created_at'           => $e->created_at?->toIso8601String(),
        ]);

        return response()->json([
            'status' => 'success',
            'data'   => $data,
            'meta'   => [
                'current_page' => $earnings->currentPage(),
                'last_page'    => $earnings->lastPage(),
                'per_page'     => $earnings->perPage(),
                'total'        => $earnings->total(),
            ],
        ]);
    }

    public function statistics(Request $request): JsonResponse
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $totalRevenue = (float) SourceGameEarning::sum('order_amount');
        $platformEarnings = (float) SourceGameEarning::sum('platform_fee_amount');
        $sellerEarnings = (float) SourceGameEarning::sum('seller_amount');

        $byStatus = SourceGameEarning::selectRaw("status, SUM(order_amount) as total")
            ->groupBy('status')->pluck('total', 'status');

        $thisMonth = SourceGameEarning::where('created_at', '>=', $startOfMonth)
            ->selectRaw("SUM(order_amount) as revenue, SUM(platform_fee_amount) as platform, SUM(seller_amount) as seller, COUNT(*) as orders")
            ->first();

        $lastMonth = SourceGameEarning::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->selectRaw("SUM(order_amount) as revenue, COUNT(*) as orders")
            ->first();

        $topProducts = DB::table('source_game_earnings')
            ->join('product_flat', function ($j) {
                $j->on('source_game_earnings.product_id', '=', 'product_flat.product_id')
                  ->where('product_flat.locale', 'vi');
            })
            ->select('source_game_earnings.product_id as id', 'product_flat.name',
                DB::raw('SUM(source_game_earnings.order_amount) as total_revenue'),
                DB::raw('COUNT(*) as sales_count'))
            ->groupBy('source_game_earnings.product_id', 'product_flat.name')
            ->orderByDesc('total_revenue')
            ->limit(10)->get();

        $topSellers = DB::table('source_game_earnings')
            ->join('source_game_sellers', 'source_game_earnings.seller_id', '=', 'source_game_sellers.id')
            ->select('source_game_earnings.seller_id as id', 'source_game_sellers.shop_name',
                DB::raw('SUM(source_game_earnings.seller_amount) as total_earnings'),
                DB::raw('COUNT(*) as sales_count'))
            ->groupBy('source_game_earnings.seller_id', 'source_game_sellers.shop_name')
            ->orderByDesc('total_earnings')
            ->limit(10)->get();

        $monthlyTrend = DB::table('source_game_earnings')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(order_amount) as revenue, COUNT(*) as orders")
            ->groupBy('month')
            ->orderByDesc('month')
            ->limit(12)->get();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'total_revenue'     => $totalRevenue,
                'platform_earnings' => $platformEarnings,
                'seller_earnings'   => $sellerEarnings,
                'by_status' => [
                    'pending'   => (float) ($byStatus['pending'] ?? 0),
                    'completed' => (float) ($byStatus['completed'] ?? 0),
                    'refunded'  => (float) ($byStatus['refunded'] ?? 0),
                ],
                'this_month' => [
                    'revenue'           => (float) $thisMonth->revenue,
                    'platform_earnings' => (float) $thisMonth->platform,
                    'seller_earnings'   => (float) $thisMonth->seller,
                    'orders'            => (int) $thisMonth->orders,
                ],
                'last_month' => [
                    'revenue' => (float) $lastMonth->revenue,
                    'orders'  => (int) $lastMonth->orders,
                ],
                'top_products'  => $topProducts,
                'top_sellers'   => $topSellers,
                'monthly_trend' => $monthlyTrend,
            ],
        ]);
    }
}
