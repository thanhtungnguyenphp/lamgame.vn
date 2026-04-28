<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\SourceGameEarning;
use App\Models\SourceGameSeller;
use App\Models\SourceGameWithdrawal;
use App\Models\UserSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Order;

class DashboardManageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $today = $now->copy()->startOfDay();

        // Products stats via product_flat (locale=vi)
        $productStats = DB::table('product_flat')
            ->where('locale', 'vi')
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as published
            ")
            ->first();

        $pendingReview = DB::table('products')->where('pending_review', true)->count();

        // Orders stats
        $orderStats = DB::table('orders')
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing,
                SUM(CASE WHEN status = 'canceled' THEN 1 ELSE 0 END) as canceled
            ")
            ->first();

        // Revenue stats
        $revenueTotal = SourceGameEarning::sum('order_amount');
        $revenueMonth = SourceGameEarning::where('created_at', '>=', $startOfMonth)->sum('order_amount');
        $revenueToday = SourceGameEarning::where('created_at', '>=', $today)->sum('order_amount');
        $platformEarnings = SourceGameEarning::sum('platform_fee_amount');
        $sellerEarnings = SourceGameEarning::sum('seller_amount');

        // Sellers stats
        $sellerStats = SourceGameSeller::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'suspended' THEN 1 ELSE 0 END) as suspended
            ")
            ->first();

        // Customers stats
        $customersTotal = Customer::count();
        $customersMonth = Customer::where('created_at', '>=', $startOfMonth)->count();
        $customersWithOrders = DB::table('orders')->distinct('customer_id')->whereNotNull('customer_id')->count('customer_id');

        // Withdrawals pending
        $withdrawalsPending = SourceGameWithdrawal::where('status', 'pending')
            ->selectRaw('COUNT(*) as count, COALESCE(SUM(amount), 0) as amount')
            ->first();
        $withdrawalsCompletedMonth = SourceGameWithdrawal::where('status', 'completed')
            ->where('processed_at', '>=', $startOfMonth)
            ->sum('amount');

        // Jobs stats
        $jobsTotal = JobPosting::count();
        $jobsActive = JobPosting::where('status', 'active')->count();

        // Subscriptions stats
        $subsActive = UserSubscription::active()->count();
        $mrr = DB::table('user_subscriptions')
            ->join('subscription_plans', 'user_subscriptions.plan_id', '=', 'subscription_plans.id')
            ->where('user_subscriptions.status', 'active')
            ->sum('subscription_plans.price');

        return response()->json([
            'status' => 'success',
            'data' => [
                'products' => [
                    'total'          => (int) $productStats->total,
                    'published'      => (int) $productStats->published,
                    'pending_review' => $pendingReview,
                    'draft'          => (int) $productStats->total - (int) $productStats->published - $pendingReview,
                ],
                'orders' => [
                    'total'      => (int) $orderStats->total,
                    'completed'  => (int) $orderStats->completed,
                    'pending'    => (int) $orderStats->pending,
                    'processing' => (int) $orderStats->processing,
                    'canceled'   => (int) $orderStats->canceled,
                ],
                'revenue' => [
                    'total'             => (float) $revenueTotal,
                    'this_month'        => (float) $revenueMonth,
                    'today'             => (float) $revenueToday,
                    'platform_earnings' => (float) $platformEarnings,
                    'seller_earnings'   => (float) $sellerEarnings,
                ],
                'sellers' => [
                    'total'     => (int) $sellerStats->total,
                    'active'    => (int) $sellerStats->active,
                    'pending'   => (int) $sellerStats->pending,
                    'suspended' => (int) $sellerStats->suspended,
                ],
                'customers' => [
                    'total'       => $customersTotal,
                    'this_month'  => $customersMonth,
                    'with_orders' => $customersWithOrders,
                ],
                'withdrawals' => [
                    'pending_count'          => (int) $withdrawalsPending->count,
                    'pending_amount'         => (float) $withdrawalsPending->amount,
                    'completed_this_month'   => (float) $withdrawalsCompletedMonth,
                ],
                'jobs' => [
                    'total'  => $jobsTotal,
                    'active' => $jobsActive,
                ],
                'subscriptions' => [
                    'active' => $subsActive,
                    'mrr'    => (float) $mrr,
                ],
            ],
        ]);
    }
}
