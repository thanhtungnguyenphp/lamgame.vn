<?php

namespace App\Listeners;

use App\Models\SourceGameEarning;
use Webkul\Sales\Models\Order;

class CreateSellerEarningOnOrderComplete
{
    public function handle($order)
    {
        if ($order->status !== Order::STATUS_COMPLETED) {
            return;
        }

        // Skip if earnings already created for this order
        if (SourceGameEarning::where('order_id', $order->id)->exists()) {
            return;
        }

        SourceGameEarning::createFromOrder($order);
    }
}
