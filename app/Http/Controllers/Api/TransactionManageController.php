<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webkul\Sales\Models\OrderTransaction;

class TransactionManageController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $query = OrderTransaction::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $query->orderBy($request->input('sort_by', 'created_at'), $request->input('sort_dir', 'desc'));
        $transactions = $query->paginate(min($request->integer('per_page', 15), 100));

        return response()->json([
            'status' => 'success',
            'data' => $transactions->map(fn ($t) => [
                'id' => $t->id,
                'transaction_id' => $t->transaction_id,
                'order_id' => $t->order_id,
                'invoice_id' => $t->invoice_id,
                'status' => $t->status,
                'type' => $t->type,
                'payment_method' => $t->payment_method,
                'amount' => (float) $t->amount,
                'created_at' => $t->created_at?->toIso8601String(),
            ]),
            'meta' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
            ],
        ]);
    }

    public function detail(int $id): JsonResponse
    {
        $t = OrderTransaction::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $t->id,
                'transaction_id' => $t->transaction_id,
                'order_id' => $t->order_id,
                'invoice_id' => $t->invoice_id,
                'status' => $t->status,
                'type' => $t->type,
                'payment_method' => $t->payment_method,
                'amount' => (float) $t->amount,
                'data' => $t->data,
                'created_at' => $t->created_at?->toIso8601String(),
            ],
        ]);
    }
}
