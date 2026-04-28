<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SourceGameEarning;
use App\Models\SourceGameWithdrawal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalManageController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $query = SourceGameWithdrawal::with('seller:id,shop_name,contact_email');

        if ($sellerId = $request->input('seller_id')) {
            $query->where('seller_id', $sellerId);
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

        $sortable = ['created_at', 'amount'];
        $sortBy = in_array($request->input('sort_by'), $sortable) ? $request->input('sort_by') : 'created_at';
        $sortDir = $request->input('sort_dir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $perPage = min(max($request->integer('per_page', 15), 1), 100);
        $items = $query->paginate($perPage);

        $data = $items->map(fn ($w) => [
            'id'             => $w->id,
            'seller'         => $w->seller ? ['id' => $w->seller->id, 'shop_name' => $w->seller->shop_name] : null,
            'amount'         => (float) $w->amount,
            'status'         => $w->status,
            'bank_name'      => $w->bank_name,
            'bank_holder'    => $w->bank_holder,
            'transaction_id' => $w->transaction_id,
            'created_at'     => $w->created_at?->toIso8601String(),
            'processed_at'   => $w->processed_at?->toIso8601String(),
        ]);

        return response()->json([
            'status' => 'success',
            'data'   => $data,
            'meta'   => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ],
        ]);
    }

    public function detail(int $id): JsonResponse
    {
        $w = SourceGameWithdrawal::with('seller:id,shop_name,contact_email')->find($id);
        if (!$w) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy yêu cầu rút tiền.'], 404);
        }

        // Seller available balance
        $balance = $this->getSellerBalance($w->seller_id);

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'     => $w->id,
                'seller' => $w->seller ? [
                    'id'                => $w->seller->id,
                    'shop_name'         => $w->seller->shop_name,
                    'contact_email'     => $w->seller->contact_email,
                    'available_balance' => $balance,
                ] : null,
                'amount'         => (float) $w->amount,
                'status'         => $w->status,
                'bank_info'      => [
                    'bank_name'    => $w->bank_name,
                    'bank_account' => $w->bank_account,
                    'bank_holder'  => $w->bank_holder,
                ],
                'note'           => $w->note,
                'admin_note'     => $w->admin_note,
                'transaction_id' => $w->transaction_id,
                'processed_at'   => $w->processed_at?->toIso8601String(),
                'created_at'     => $w->created_at?->toIso8601String(),
            ],
        ]);
    }

    public function approve(Request $request, int $id): JsonResponse
    {
        $w = SourceGameWithdrawal::find($id);
        if (!$w) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy.'], 404);
        }
        if ($w->status !== 'pending') {
            return response()->json(['status' => 'error', 'message' => 'Chỉ có thể duyệt yêu cầu đang pending.'], 422);
        }

        $balance = $this->getSellerBalance($w->seller_id);
        if ($balance < $w->amount) {
            return response()->json(['status' => 'error', 'message' => "Số dư không đủ. Available: {$balance}"], 422);
        }

        $w->update(['status' => 'processing']);

        return response()->json(['status' => 'success', 'message' => 'Đã duyệt, chuyển sang processing.']);
    }

    public function complete(Request $request, int $id): JsonResponse
    {
        $w = SourceGameWithdrawal::find($id);
        if (!$w) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy.'], 404);
        }
        if ($w->status !== 'processing') {
            return response()->json(['status' => 'error', 'message' => 'Chỉ có thể complete yêu cầu đang processing.'], 422);
        }

        $request->validate([
            'transaction_id' => 'required|string|max:255',
            'admin_note'     => 'nullable|string|max:1000',
        ]);

        $w->update([
            'status'         => 'completed',
            'transaction_id' => $request->input('transaction_id'),
            'admin_note'     => $request->input('admin_note'),
            'processed_at'   => now(),
            'processed_by'   => $request->auth_admin?->id,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Đã hoàn thành rút tiền.']);
    }

    public function reject(Request $request, int $id): JsonResponse
    {
        $w = SourceGameWithdrawal::find($id);
        if (!$w) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy.'], 404);
        }
        if (!in_array($w->status, ['pending', 'processing'])) {
            return response()->json(['status' => 'error', 'message' => 'Không thể từ chối yêu cầu này.'], 422);
        }

        $request->validate(['admin_note' => 'required|string|max:1000']);

        $w->update([
            'status'       => 'rejected',
            'admin_note'   => $request->input('admin_note'),
            'processed_at' => now(),
            'processed_by' => $request->auth_admin?->id,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Đã từ chối yêu cầu rút tiền.']);
    }

    private function getSellerBalance(int $sellerId): float
    {
        $earned = (float) SourceGameEarning::where('seller_id', $sellerId)->where('status', 'completed')->sum('seller_amount');
        $withdrawn = (float) SourceGameWithdrawal::where('seller_id', $sellerId)->whereIn('status', ['completed', 'processing'])->sum('amount');

        return $earned - $withdrawn;
    }
}
