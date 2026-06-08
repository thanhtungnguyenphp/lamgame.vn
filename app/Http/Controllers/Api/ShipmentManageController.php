<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\Shipment;
use Webkul\Sales\Repositories\ShipmentRepository;

class ShipmentManageController extends Controller
{
    public function __construct(protected ShipmentRepository $shipmentRepository) {}

    public function list(Request $request): JsonResponse
    {
        $query = Shipment::with(['order']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('track_number', 'like', "%{$search}%")
                  ->orWhereHas('order', fn ($oq) => $oq->where('increment_id', 'like', "%{$search}%"));
            });
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $query->orderBy($request->input('sort_by', 'created_at'), $request->input('sort_dir', 'desc'));
        $shipments = $query->paginate(min($request->integer('per_page', 15), 100));

        return response()->json([
            'status' => 'success',
            'data' => $shipments->map(fn ($s) => [
                'id' => $s->id,
                'order_id' => $s->order_id,
                'order_increment_id' => $s->order?->increment_id,
                'total_qty' => $s->total_qty,
                'carrier_title' => $s->carrier_title,
                'track_number' => $s->track_number,
                'customer_name' => trim($s->order?->customer_first_name . ' ' . $s->order?->customer_last_name),
                'created_at' => $s->created_at?->toIso8601String(),
            ]),
            'meta' => [
                'current_page' => $shipments->currentPage(),
                'last_page' => $shipments->lastPage(),
                'per_page' => $shipments->perPage(),
                'total' => $shipments->total(),
            ],
        ]);
    }

    public function detail(int $id): JsonResponse
    {
        $shipment = Shipment::with(['order', 'items'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $shipment->id,
                'order_id' => $shipment->order_id,
                'total_qty' => $shipment->total_qty,
                'carrier_title' => $shipment->carrier_title,
                'track_number' => $shipment->track_number,
                'items' => $shipment->items->map(fn ($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'sku' => $item->sku,
                    'qty' => $item->qty,
                ]),
                'order' => [
                    'id' => $shipment->order->id,
                    'increment_id' => $shipment->order->increment_id,
                    'status' => $shipment->order->status,
                    'customer_name' => trim($shipment->order->customer_first_name . ' ' . $shipment->order->customer_last_name),
                    'shipping_address' => $shipment->order->shipping_address?->toArray(),
                ],
                'created_at' => $shipment->created_at?->toIso8601String(),
            ],
        ]);
    }

    public function store(Request $request, int $orderId): JsonResponse
    {
        $order = Order::findOrFail($orderId);

        if (!$order->canShip()) {
            return response()->json(['status' => 'error', 'message' => 'Order cannot be shipped'], 422);
        }

        $data = $request->validate([
            'carrier_title' => 'sometimes|string|max:255',
            'track_number' => 'sometimes|string|max:255',
            'items' => 'sometimes|array',
            'items.*' => 'integer|min:0',
            'source' => 'sometimes|integer',
        ]);

        $shipmentData = ['shipment' => ['carrier_title' => $data['carrier_title'] ?? '', 'track_number' => $data['track_number'] ?? '', 'source' => $data['source'] ?? 1, 'items' => []]];

        if (empty($data['items'])) {
            foreach ($order->items as $item) {
                if ($item->qty_to_ship > 0) {
                    $shipmentData['shipment']['items'][$item->id] = [$data['source'] ?? 1 => $item->qty_to_ship];
                }
            }
        } else {
            foreach ($data['items'] as $itemId => $qty) {
                $shipmentData['shipment']['items'][$itemId] = [$data['source'] ?? 1 => $qty];
            }
        }

        $shipment = $this->shipmentRepository->create(array_merge($shipmentData, ['order_id' => $orderId]));

        return response()->json([
            'status' => 'success',
            'message' => 'Shipment created',
            'data' => ['id' => $shipment->id, 'track_number' => $shipment->track_number],
        ], 201);
    }
}
