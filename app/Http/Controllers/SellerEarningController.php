<?php

namespace App\Http\Controllers;

use App\Models\SourceGameEarning;
use App\Models\SourceGameWithdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerEarningController extends Controller
{
    public function index()
    {
        $seller = Auth::guard('customer')->user()->seller;

        if (!$seller || !$seller->isActive()) {
            return redirect()->route('seller.pending');
        }

        $earnings = SourceGameEarning::where('seller_id', $seller->id)
            ->with(['order', 'product'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_earnings' => SourceGameEarning::where('seller_id', $seller->id)
                ->where('status', 'completed')
                ->sum('seller_amount'),
            'total_withdrawn' => SourceGameWithdrawal::where('seller_id', $seller->id)
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_earnings' => SourceGameEarning::where('seller_id', $seller->id)
                ->where('status', 'pending')
                ->sum('seller_amount'),
        ];

        $stats['available_balance'] = $stats['total_earnings'] - $stats['total_withdrawn'];

        return view('seller.earnings.index', compact('seller', 'earnings', 'stats'));
    }
}

class SellerWithdrawalController extends Controller
{
    public function index()
    {
        $seller = Auth::guard('customer')->user()->seller;

        if (!$seller || !$seller->isActive()) {
            return redirect()->route('seller.pending');
        }

        $withdrawals = SourceGameWithdrawal::where('seller_id', $seller->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $availableBalance = $this->getAvailableBalance($seller);

        return view('seller.withdrawals.index', compact('seller', 'withdrawals', 'availableBalance'));
    }

    public function create()
    {
        $seller = Auth::guard('customer')->user()->seller;

        if (!$seller || !$seller->isActive()) {
            return redirect()->route('seller.pending');
        }

        $availableBalance = $this->getAvailableBalance($seller);

        if ($availableBalance < 100000) {
            return redirect()->route('seller.withdrawals.index')
                ->with('error', 'Số dư tối thiểu để rút tiền là 100,000đ');
        }

        return view('seller.withdrawals.create', compact('seller', 'availableBalance'));
    }

    public function store(Request $request)
    {
        $seller = Auth::guard('customer')->user()->seller;

        if (!$seller || !$seller->isActive()) {
            return redirect()->route('seller.pending');
        }

        $availableBalance = $this->getAvailableBalance($seller);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:100000|max:' . $availableBalance,
            'note' => 'nullable|string|max:500',
        ]);

        SourceGameWithdrawal::create([
            'seller_id' => $seller->id,
            'amount' => $validated['amount'],
            'status' => 'pending',
            'bank_name' => $seller->bank_name,
            'bank_account' => $seller->bank_account,
            'bank_holder' => $seller->bank_holder,
            'note' => $validated['note'],
        ]);

        return redirect()->route('seller.withdrawals.index')
            ->with('success', 'Yêu cầu rút tiền đã được gửi. Chúng tôi sẽ xử lý trong 3-5 ngày làm việc.');
    }

    private function getAvailableBalance($seller)
    {
        $totalEarnings = SourceGameEarning::where('seller_id', $seller->id)
            ->where('status', 'completed')
            ->sum('seller_amount');

        $totalWithdrawn = SourceGameWithdrawal::where('seller_id', $seller->id)
            ->where('status', 'completed')
            ->sum('amount');

        $pendingWithdrawals = SourceGameWithdrawal::where('seller_id', $seller->id)
            ->whereIn('status', ['pending', 'processing'])
            ->sum('amount');

        return $totalEarnings - $totalWithdrawn - $pendingWithdrawals;
    }
}
