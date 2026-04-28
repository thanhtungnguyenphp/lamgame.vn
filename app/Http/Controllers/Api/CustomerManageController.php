<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SourceGameSeller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webkul\Customer\Models\Customer;

class CustomerManageController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $query = Customer::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('is_verified')) {
            $query->where('is_verified', $request->boolean('is_verified'));
        }
        if ($request->has('is_suspended')) {
            $query->where('is_suspended', $request->boolean('is_suspended'));
        }
        if ($request->boolean('has_orders')) {
            $query->whereHas('orders');
        }
        if ($request->boolean('has_seller')) {
            $query->whereIn('id', SourceGameSeller::pluck('customer_id'));
        }

        $sortable = ['created_at', 'first_name', 'email'];
        $sortBy = in_array($request->input('sort_by'), $sortable) ? $request->input('sort_by') : 'created_at';
        $sortDir = $request->input('sort_dir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $perPage = min(max($request->integer('per_page', 15), 1), 100);
        $customers = $query->paginate($perPage);

        $data = $customers->map(fn ($c) => [
            'id'           => $c->id,
            'first_name'   => $c->first_name,
            'last_name'    => $c->last_name,
            'email'        => $c->email,
            'phone'        => $c->phone,
            'is_verified'  => (bool) $c->is_verified,
            'is_suspended' => (bool) $c->is_suspended,
            'created_at'   => $c->created_at?->toIso8601String(),
        ]);

        return response()->json([
            'status' => 'success',
            'data'   => $data,
            'meta'   => [
                'current_page' => $customers->currentPage(),
                'last_page'    => $customers->lastPage(),
                'per_page'     => $customers->perPage(),
                'total'        => $customers->total(),
            ],
        ]);
    }

    public function detail(int $id): JsonResponse
    {
        $c = Customer::find($id);
        if (!$c) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy khách hàng.'], 404);
        }

        $seller = SourceGameSeller::where('customer_id', $id)->first();

        $ordersSummary = DB::table('orders')
            ->where('customer_id', $id)
            ->selectRaw("COUNT(*) as total_orders, COALESCE(SUM(grand_total), 0) as total_spent, MAX(created_at) as last_order_at")
            ->first();

        $recentOrders = DB::table('orders')
            ->where('customer_id', $id)
            ->select('id', 'increment_id', 'status', 'grand_total', 'created_at')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $subscription = DB::table('user_subscriptions')
            ->join('subscription_plans', 'user_subscriptions.plan_id', '=', 'subscription_plans.id')
            ->where('user_subscriptions.user_id', $id)
            ->where('user_subscriptions.status', 'active')
            ->select('subscription_plans.name as plan', 'user_subscriptions.status', 'user_subscriptions.ends_at as expires_at')
            ->first();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'           => $c->id,
                'first_name'   => $c->first_name,
                'last_name'    => $c->last_name,
                'email'        => $c->email,
                'phone'        => $c->phone,
                'gender'       => $c->gender,
                'is_verified'  => (bool) $c->is_verified,
                'is_suspended' => (bool) $c->is_suspended,
                'seller'       => $seller ? [
                    'id'        => $seller->id,
                    'shop_name' => $seller->shop_name,
                    'status'    => $seller->status,
                ] : null,
                'orders_summary' => [
                    'total_orders' => (int) $ordersSummary->total_orders,
                    'total_spent'  => (float) $ordersSummary->total_spent,
                    'last_order_at' => $ordersSummary->last_order_at,
                ],
                'subscription'  => $subscription,
                'recent_orders' => $recentOrders,
                'created_at'    => $c->created_at?->toIso8601String(),
            ],
        ]);
    }

    public function statistics(Request $request): JsonResponse
    {
        $total = Customer::count();
        $verified = Customer::where('is_verified', true)->count();
        $suspended = Customer::where('is_suspended', true)->count();
        $withOrders = DB::table('orders')->distinct('customer_id')->whereNotNull('customer_id')->count('customer_id');
        $sellers = SourceGameSeller::count();
        $newThisMonth = Customer::where('created_at', '>=', now()->startOfMonth())->count();
        $newLastMonth = Customer::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();

        $topSpenders = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->whereNotNull('orders.customer_id')
            ->select('customers.id', DB::raw("CONCAT(customers.first_name, ' ', customers.last_name) as name"),
                DB::raw('SUM(orders.grand_total) as total_spent'), DB::raw('COUNT(*) as order_count'))
            ->groupBy('customers.id', 'customers.first_name', 'customers.last_name')
            ->orderByDesc('total_spent')
            ->limit(10)->get();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'total'          => $total,
                'verified'       => $verified,
                'suspended'      => $suspended,
                'with_orders'    => $withOrders,
                'sellers'        => $sellers,
                'new_this_month' => $newThisMonth,
                'new_last_month' => $newLastMonth,
                'top_spenders'   => $topSpenders,
            ],
        ]);
    }

    public function suspend(Request $request, int $id): JsonResponse
    {
        $c = Customer::find($id);
        if (!$c) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy khách hàng.'], 404);
        }
        if ($c->is_suspended) {
            return response()->json(['status' => 'error', 'message' => 'Khách hàng đã bị khóa.'], 422);
        }

        $request->validate(['reason' => 'required|string|max:1000']);

        $c->update(['is_suspended' => true, 'notes' => $request->input('reason')]);

        // Cascade: suspend seller if exists
        SourceGameSeller::where('customer_id', $id)->where('status', 'active')->update(['status' => 'suspended']);

        return response()->json(['status' => 'success', 'message' => 'Đã tạm khóa khách hàng.']);
    }

    public function activate(Request $request, int $id): JsonResponse
    {
        $c = Customer::find($id);
        if (!$c) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy khách hàng.'], 404);
        }
        if (!$c->is_suspended) {
            return response()->json(['status' => 'error', 'message' => 'Khách hàng không bị khóa.'], 422);
        }

        $c->update(['is_suspended' => false]);

        // Cascade: activate seller if suspended
        SourceGameSeller::where('customer_id', $id)->where('status', 'suspended')->update(['status' => 'active']);

        return response()->json(['status' => 'success', 'message' => 'Đã kích hoạt lại khách hàng.']);
    }
}
