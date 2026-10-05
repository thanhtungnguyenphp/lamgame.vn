<?php

namespace App\Listeners;

use App\Models\SourceGameEarning;
use App\Models\SourceGameSeller;
use Illuminate\Support\Facades\DB;
use Webkul\Sales\Models\Order;

/**
 * Đảo earning của seller khi đơn hàng bị hủy hoặc hoàn tiền (DATA-06).
 *
 * Khi order chuyển sang 'canceled' hoặc 'closed' (refunded), các earning
 * đang 'completed' của order đó được đánh dấu 'refunded' và trừ lại thống kê
 * total_revenue/total_sales của seller. Idempotent: earning đã refunded thì bỏ qua.
 */
class ReverseEarningOnOrderCancel
{
    public function handle($order)
    {
        if (! in_array($order->status, [Order::STATUS_CANCELED, Order::STATUS_CLOSED], true)) {
            return;
        }

        $earnings = SourceGameEarning::where('order_id', $order->id)
            ->where('status', 'completed')
            ->get();

        if ($earnings->isEmpty()) {
            return;
        }

        foreach ($earnings as $earning) {
            DB::transaction(function () use ($earning) {
                // Khóa bản ghi để tránh double-reverse khi event bắn đồng thời
                $fresh = SourceGameEarning::whereKey($earning->id)
                    ->lockForUpdate()
                    ->first();

                if (! $fresh || $fresh->status !== 'completed') {
                    return;
                }

                $fresh->update(['status' => 'refunded']);

                $seller = SourceGameSeller::find($fresh->seller_id);
                if ($seller) {
                    // Trừ lại doanh thu; không để âm
                    $seller->decrement('total_revenue', min($fresh->seller_amount, $seller->total_revenue));
                    if ($seller->total_sales > 0) {
                        $seller->decrement('total_sales');
                    }
                }
            });
        }
    }
}
