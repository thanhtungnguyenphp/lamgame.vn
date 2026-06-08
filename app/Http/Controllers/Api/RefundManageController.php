<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\Refund;
use Webkul\Sales\Repositories\RefundRepository;

class RefundManageController extends Controller
{
    public function __construct(protected RefundRepository $refundRepository) {}

    public function list(Request $request): JsonResponse
    {
        $query = Refund::with(['order']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('order', fn ($oq) => $oq->where('increment_id', 'like', "%{$search}%"));
            });
        }

        if ($state = $request->input('state')) {
            $query->where('state', $state);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $query->orderBy($request->input('sort_by', 'created_at'), $request->input('sort_dir', 'desc'));
        $refunds = $query->paginate(min($request->integer('per_page', 15), 100));

        return response()->json([
            'status' => 'success',
            'data' => $refunds->map(fn ($r) => [
                'id' => $r->id,
                'order_id' => $r->order_id,
                'order_increment_id' => $r->order?->increment_id,
                'state' => $r->state,
                'adjustment_refund' => (float) $r->adjustment_refund,
                'adjustment_fee' => (float) $r->adjustment_fee,
                'grand_total' => (float) $r->grand_total,
                'currency' => $r->order_currency_code,
                'created_at' => $r->created_at?->toIso8601String(),
            ]),
            'meta' => [
                'current_page' => $refunds->currentPage(),
                'last_page' => $refunds->lastPage(),
                'per_page' => $refunds->perPage(),
                'total' => $refunds->total(),
            ],
        ]);
    }

    public function detail(int $id): JsonResponse
    {
        $refund = Refund::with(['order', 'items'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $refund->id,
                'order_id' => $refund->order_id,
                'state' => $refund->state,
                'sub_total' => (float) $refund->sub_total,
                'shipping_amount' => (float) $refund->shipping_amount,
                'tax_amount' => (float) $refund->tax_amount,
                'discount_amount' => (float) $refund->discount_amount,
                'adjustment_refund' => (float) $refund->adjustment_refund,
                'adjustment_fee' => (float) $refund->adjustment_fee,
                'grand_total' => (float) $refund->grand_total,
                'items' => $refund->items->map(fn ($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'sku' => $item->sku,
                    'qty' => $item->qty,
                    'price' => (float) $item->price,
                    'total' => (float) $item->total,
                ]),
                'order' => [
                    'id' => $refund->order->id,
                    'increment_id' => $refund->order->increment_id,
                    'customer_name' => trim($refund->order->customer_first_name . ' ' . $refund->order->customer_last_name),
                ],
                'created_at' => $refund->created_at?->toIso8601String(),
            ],
        ]);
    }

    public function store(Request $request, int $orderId): JsonResponse
    {
        $order = Order::findOrFail($orderId);

        if (!$order->canRefund()) {
            return response()->json(['status' => 'error', 'message' => 'Order cannot be refunded'], 422);
        }

        $data = $request->validate([
            'items' => 'sometimes|array',
            'items.*' => 'integer|min:0',
            'adjustment_refund' => 'sometimes|numeric|min:0',
            'adjustment_fee' => 'sometimes|numeric|min:0',
            'shipping' => 'sometimes|numeric|min:0',
        ]);

        if (empty($data['items'])) {
            $data['refund']['items'] = [];
            foreach ($order->items as $item) {
                if ($item->qty_to_refund > 0) {
                    $data['refund']['items'][$item->id] = ['qty' => $item->qty_to_refund];
                }
            }
        } else {
            $data['refund']['items'] = [];
            foreach ($data['items'] as $itemId => $qty) {
                $data['refund']['items'][$itemId] = ['qty' => $qty];
            }
        }

        $data['refund']['shipping'] = $data['shipping'] ?? 0;
        $data['refund']['adjustment_refund'] = $data['adjustment_refund'] ?? 0;
        $data['refund']['adjustment_fee'] = $data['adjustment_fee'] ?? 0;

        $refund = $this->refundRepository->create(array_merge($data, ['order_id' => $orderId]));

        return response()->json([
            'status' => 'success',
            'message' => 'Refund created',
            'data' => ['id' => $refund->id, 'grand_total' => (float) $refund->grand_total],
        ], 201);
    }

    public function statistics(): JsonResponse
    {
        $stats = Refund::selectRaw("
            COUNT(*) as total,
            SUM(grand_total) as total_refunded
        ")->first();

        return response()->json(['status' => 'success', 'data' => $stats]);
    }
}
