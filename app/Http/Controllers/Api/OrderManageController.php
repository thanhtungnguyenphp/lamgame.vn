<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webkul\Sales\Models\Order;

class OrderManageController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $query = Order::with(['payment', 'items']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('increment_id', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_first_name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($method = $request->input('payment_method')) {
            $query->whereHas('payment', fn ($q) => $q->where('method', $method));
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        if ($customerId = $request->input('customer_id')) {
            $query->where('customer_id', $customerId);
        }

        // Sort
        $sortable = ['created_at', 'grand_total', 'increment_id'];
        $sortBy = in_array($request->input('sort_by'), $sortable) ? $request->input('sort_by') : 'created_at';
        $sortDir = $request->input('sort_dir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $perPage = min(max($request->integer('per_page', 15), 1), 100);
        $orders = $query->paginate($perPage);

        $data = $orders->map(function ($order) {
            return [
                'id'            => $order->id,
                'increment_id'  => $order->increment_id,
                'status'        => $order->status,
                'customer_name' => trim($order->customer_first_name . ' ' . $order->customer_last_name),
                'customer_email' => $order->customer_email,
                'is_guest'      => (bool) $order->is_guest,
                'items_count'   => $order->total_item_count,
                'grand_total'   => (float) $order->grand_total,
                'currency'      => $order->order_currency_code,
                'payment_method' => $order->payment?->method,
                'created_at'    => $order->created_at?->toIso8601String(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $data,
            'meta'   => [
                'current_page' => $orders->currentPage(),
                'last_page'    => $orders->lastPage(),
                'per_page'     => $orders->perPage(),
                'total'        => $orders->total(),
            ],
        ]);
    }

    public function detail(int $id): JsonResponse
    {
        $order = Order::with(['items.product', 'payment', 'addresses', 'comments', 'invoices', 'transactions'])
            ->find($id);

        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy đơn hàng.'], 404);
        }

        // Earnings for this order
        $earnings = DB::table('source_game_earnings')
            ->where('order_id', $id)
            ->join('source_game_sellers', 'source_game_earnings.seller_id', '=', 'source_game_sellers.id')
            ->select('source_game_sellers.shop_name', 'source_game_earnings.order_amount',
                'source_game_earnings.platform_fee_amount', 'source_game_earnings.seller_amount',
                'source_game_earnings.status')
            ->get();

        $billing = $order->addresses->firstWhere('address_type', 'billing');

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'             => $order->id,
                'increment_id'   => $order->increment_id,
                'status'         => $order->status,
                'is_guest'       => (bool) $order->is_guest,
                'customer'       => [
                    'id'    => $order->customer_id,
                    'name'  => trim($order->customer_first_name . ' ' . $order->customer_last_name),
                    'email' => $order->customer_email,
                ],
                'items' => $order->items->map(fn ($item) => [
                    'id'          => $item->id,
                    'product_id'  => $item->product_id,
                    'name'        => $item->name,
                    'sku'         => $item->sku,
                    'type'        => $item->type,
                    'qty_ordered' => (int) $item->qty_ordered,
                    'price'       => (float) $item->price,
                    'total'       => (float) $item->total,
                ]),
                'payment' => $order->payment ? [
                    'method'       => $order->payment->method,
                    'method_title' => $order->payment->method_title,
                ] : null,
                'billing_address' => $billing ? [
                    'name'    => trim(($billing->first_name ?? '') . ' ' . ($billing->last_name ?? '')),
                    'email'   => $billing->email,
                    'phone'   => $billing->phone,
                    'city'    => $billing->city,
                    'country' => $billing->country,
                ] : null,
                'sub_total'       => (float) $order->sub_total,
                'discount_amount' => (float) $order->discount_amount,
                'tax_amount'      => (float) $order->tax_amount,
                'grand_total'     => (float) $order->grand_total,
                'currency'        => $order->order_currency_code,
                'coupon_code'     => $order->coupon_code,
                'comments' => $order->comments->map(fn ($c) => [
                    'id'                => $c->id,
                    'comment'           => $c->comment,
                    'customer_notified' => (bool) $c->customer_notified,
                    'created_at'        => $c->created_at?->toIso8601String(),
                ]),
                'invoices' => $order->invoices->map(fn ($inv) => [
                    'id'          => $inv->id,
                    'state'       => $inv->state,
                    'grand_total' => (float) $inv->grand_total,
                ]),
                'earnings'   => $earnings,
                'created_at' => $order->created_at?->toIso8601String(),
                'updated_at' => $order->updated_at?->toIso8601String(),
            ],
        ]);
    }

    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy đơn hàng.'], 404);
        }

        $request->validate(['status' => 'required|in:pending,processing,completed,canceled,closed']);

        $newStatus = $request->input('status');
        $current = $order->status;

        // Transition validation
        $allowed = match ($current) {
            'pending'    => ['processing', 'canceled'],
            'processing' => ['completed', 'canceled'],
            'completed'  => ['closed'],
            default      => [],
        };

        if (!in_array($newStatus, $allowed)) {
            return response()->json([
                'status'  => 'error',
                'message' => "Không thể chuyển từ '{$current}' sang '{$newStatus}'.",
            ], 422);
        }

        $order->update(['status' => $newStatus]);

        // Auto-create earnings when completed
        if ($newStatus === 'completed') {
            \App\Models\SourceGameEarning::createFromOrder($order->load('items.product'));
        }

        // Refund earnings when canceled
        if ($newStatus === 'canceled') {
            DB::table('source_game_earnings')
                ->where('order_id', $id)
                ->where('status', 'completed')
                ->update(['status' => 'refunded']);
        }

        return response()->json([
            'status'  => 'success',
            'message' => "Đã chuyển trạng thái đơn hàng sang '{$newStatus}'.",
        ]);
    }

    public function comment(Request $request, int $id): JsonResponse
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy đơn hàng.'], 404);
        }

        $request->validate([
            'comment'           => 'required|string|max:1000',
            'customer_notified' => 'nullable|boolean',
        ]);

        $comment = $order->comments()->create([
            'comment'           => $request->input('comment'),
            'customer_notified' => $request->boolean('customer_notified', false),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Đã thêm ghi chú.',
            'data'    => [
                'id'                => $comment->id,
                'comment'           => $comment->comment,
                'customer_notified' => (bool) $comment->customer_notified,
                'created_at'        => $comment->created_at?->toIso8601String(),
            ],
        ]);
    }

    public function statistics(Request $request): JsonResponse
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $byStatus = DB::table('orders')
            ->selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status');

        $total = $byStatus->sum();

        $revenueTotal = (float) DB::table('orders')->where('status', 'completed')->sum('grand_total');
        $revenueMonth = (float) DB::table('orders')->where('status', 'completed')
            ->where('created_at', '>=', $startOfMonth)->sum('grand_total');
        $revenueLast = (float) DB::table('orders')->where('status', 'completed')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('grand_total');
        $revenueToday = (float) DB::table('orders')->where('status', 'completed')
            ->whereDate('created_at', today())->sum('grand_total');

        $avgOrderValue = $total > 0 ? round($revenueTotal / max((int) ($byStatus['completed'] ?? 1), 1), 0) : 0;

        $byPayment = DB::table('orders')
            ->join('order_payment', 'orders.id', '=', 'order_payment.order_id')
            ->select('order_payment.method', DB::raw('COUNT(*) as count'), DB::raw('SUM(orders.grand_total) as total'))
            ->groupBy('order_payment.method')
            ->get();

        // Recent 7 days
        $recent7 = DB::table('orders')
            ->where('created_at', '>=', $now->copy()->subDays(6)->startOfDay())
            ->selectRaw("DATE(created_at) as date, COUNT(*) as orders, SUM(grand_total) as revenue")
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'total'     => $total,
                'by_status' => [
                    'pending'    => (int) ($byStatus['pending'] ?? 0),
                    'processing' => (int) ($byStatus['processing'] ?? 0),
                    'completed'  => (int) ($byStatus['completed'] ?? 0),
                    'canceled'   => (int) ($byStatus['canceled'] ?? 0),
                    'closed'     => (int) ($byStatus['closed'] ?? 0),
                    'fraud'      => (int) ($byStatus['fraud'] ?? 0),
                ],
                'revenue' => [
                    'total'      => $revenueTotal,
                    'this_month' => $revenueMonth,
                    'last_month' => $revenueLast,
                    'today'      => $revenueToday,
                ],
                'avg_order_value'   => $avgOrderValue,
                'by_payment_method' => $byPayment,
                'recent_7_days'     => $recent7,
            ],
        ]);
    }
}
