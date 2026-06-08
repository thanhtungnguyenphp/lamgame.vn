<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;

class InvoiceManageController extends Controller
{
    public function __construct(
        protected InvoiceRepository $invoiceRepository,
        protected OrderRepository $orderRepository
    ) {}

    public function list(Request $request): JsonResponse
    {
        $query = Invoice::with(['order']);

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
        $invoices = $query->paginate(min($request->integer('per_page', 15), 100));

        return response()->json([
            'status' => 'success',
            'data' => $invoices->map(fn ($inv) => [
                'id' => $inv->id,
                'order_id' => $inv->order_id,
                'order_increment_id' => $inv->order?->increment_id,
                'state' => $inv->state,
                'grand_total' => (float) $inv->grand_total,
                'base_grand_total' => (float) $inv->base_grand_total,
                'currency' => $inv->order_currency_code,
                'items_count' => $inv->total_qty,
                'created_at' => $inv->created_at?->toIso8601String(),
            ]),
            'meta' => [
                'current_page' => $invoices->currentPage(),
                'last_page' => $invoices->lastPage(),
                'per_page' => $invoices->perPage(),
                'total' => $invoices->total(),
            ],
        ]);
    }

    public function detail(int $id): JsonResponse
    {
        $invoice = Invoice::with(['order', 'items'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $invoice->id,
                'order_id' => $invoice->order_id,
                'state' => $invoice->state,
                'sub_total' => (float) $invoice->sub_total,
                'shipping_amount' => (float) $invoice->shipping_amount,
                'tax_amount' => (float) $invoice->tax_amount,
                'discount_amount' => (float) $invoice->discount_amount,
                'grand_total' => (float) $invoice->grand_total,
                'currency' => $invoice->order_currency_code,
                'items' => $invoice->items->map(fn ($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'sku' => $item->sku,
                    'qty' => $item->qty,
                    'price' => (float) $item->price,
                    'total' => (float) $item->total,
                    'tax_amount' => (float) $item->tax_amount,
                ]),
                'order' => [
                    'id' => $invoice->order->id,
                    'increment_id' => $invoice->order->increment_id,
                    'status' => $invoice->order->status,
                    'customer_name' => trim($invoice->order->customer_first_name . ' ' . $invoice->order->customer_last_name),
                    'customer_email' => $invoice->order->customer_email,
                ],
                'created_at' => $invoice->created_at?->toIso8601String(),
            ],
        ]);
    }

    public function store(Request $request, int $orderId): JsonResponse
    {
        $order = Order::findOrFail($orderId);

        if (!$order->canInvoice()) {
            return response()->json(['status' => 'error', 'message' => 'Order cannot be invoiced'], 422);
        }

        $data = $request->validate([
            'items' => 'sometimes|array',
            'items.*' => 'integer|min:0',
        ]);

        // Default: invoice all items
        if (empty($data['items'])) {
            $data['invoice']['items'] = [];
            foreach ($order->items as $item) {
                if ($item->qty_to_invoice > 0) {
                    $data['invoice']['items'][$item->id] = ['qty' => $item->qty_to_invoice];
                }
            }
        } else {
            $data['invoice']['items'] = [];
            foreach ($data['items'] as $itemId => $qty) {
                $data['invoice']['items'][$itemId] = ['qty' => $qty];
            }
        }

        $invoice = $this->invoiceRepository->create(array_merge($data, ['order_id' => $orderId]));

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice created',
            'data' => ['id' => $invoice->id, 'state' => $invoice->state, 'grand_total' => (float) $invoice->grand_total],
        ], 201);
    }

    public function statistics(): JsonResponse
    {
        $stats = Invoice::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN state = 'paid' THEN 1 ELSE 0 END) as paid,
            SUM(CASE WHEN state = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(grand_total) as total_amount,
            SUM(CASE WHEN state = 'pending' THEN grand_total ELSE 0 END) as pending_amount
        ")->first();

        return response()->json(['status' => 'success', 'data' => $stats]);
    }
}
