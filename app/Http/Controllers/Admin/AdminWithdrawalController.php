<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SourceGameWithdrawal;
use Illuminate\Http\Request;

class AdminWithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = SourceGameWithdrawal::with('seller')
            ->orderByRaw("FIELD(status, 'pending', 'processing', 'completed', 'rejected')")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'pending'   => SourceGameWithdrawal::where('status', 'pending')->sum('amount'),
            'completed' => SourceGameWithdrawal::where('status', 'completed')->sum('amount'),
        ];

        return view('admin.withdrawals.index', compact('withdrawals', 'stats'));
    }

    public function approve(Request $request, $id)
    {
        $withdrawal = SourceGameWithdrawal::findOrFail($id);

        $withdrawal->update([
            'status'              => 'processing',
            'processed_by'        => auth()->guard('admin')->id(),
            'admin_note'          => $request->input('admin_note'),
        ]);

        return redirect()->route('admin.withdrawals.index')
            ->with('success', "Withdrawal #{$id} đang xử lý.");
    }

    public function complete(Request $request, $id)
    {
        $withdrawal = SourceGameWithdrawal::findOrFail($id);

        $withdrawal->update([
            'status'                => 'completed',
            'processed_at'          => now(),
            'processed_by'          => auth()->guard('admin')->id(),
            'transaction_id'        => $request->input('transaction_reference'),
            'admin_note'            => $request->input('admin_note'),
        ]);

        return redirect()->route('admin.withdrawals.index')
            ->with('success', "Withdrawal #{$id} hoàn thành.");
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['admin_note' => 'required|string']);

        $withdrawal = SourceGameWithdrawal::findOrFail($id);

        $withdrawal->update([
            'status'       => 'rejected',
            'processed_at' => now(),
            'processed_by' => auth()->guard('admin')->id(),
            'admin_note'   => $request->input('admin_note'),
        ]);

        return redirect()->route('admin.withdrawals.index')
            ->with('success', "Withdrawal #{$id} đã từ chối.");
    }
}
