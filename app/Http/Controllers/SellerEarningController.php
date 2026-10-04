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

        // Dùng công thức thống nhất (đã trừ cả withdrawal đang chờ)
        $stats['available_balance'] = $seller->availableBalance();

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

        $availableBalance = $seller->availableBalance();

        return view('seller.withdrawals.index', compact('seller', 'withdrawals', 'availableBalance'));
    }

    public function create()
    {
        $seller = Auth::guard('customer')->user()->seller;

        if (!$seller || !$seller->isActive()) {
            return redirect()->route('seller.pending');
        }

        $availableBalance = $seller->availableBalance();

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

        // Validate sơ bộ (chặn giá trị âm/dưới mức tối thiểu). Kiểm tra số dư
        // CHÍNH XÁC được thực hiện lại trong transaction + lock bên dưới để
        // tránh race condition (DATA-02).
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100000',
            'note'   => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($seller, $validated) {
                // Khóa các bản ghi earning/withdrawal của seller để tính số dư nhất quán
                SourceGameEarning::where('seller_id', $seller->id)->lockForUpdate()->get();
                SourceGameWithdrawal::where('seller_id', $seller->id)->lockForUpdate()->get();

                $available = $seller->availableBalance();

                if ($validated['amount'] > $available) {
                    throw new \RuntimeException('Số tiền rút vượt quá số dư khả dụng (' . number_format($available) . 'đ).');
                }

                SourceGameWithdrawal::create([
                    'seller_id'    => $seller->id,
                    'amount'       => $validated['amount'],
                    'status'       => 'pending',
                    'bank_name'    => $seller->bank_name,
                    'bank_account' => $seller->bank_account,
                    'bank_holder'  => $seller->bank_holder,
                    'note'         => $validated['note'] ?? null,
                ]);
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('seller.withdrawals.create')
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()->route('seller.withdrawals.index')
            ->with('success', 'Yêu cầu rút tiền đã được gửi. Chúng tôi sẽ xử lý trong 3-5 ngày làm việc.');
    }
}
