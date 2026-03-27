<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SourceGameEarning extends Model
{
    protected $fillable = [
        'seller_id',
        'order_id',
        'order_item_id',
        'product_id',
        'order_amount',
        'platform_fee_percent',
        'platform_fee_amount',
        'seller_amount',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'platform_fee_percent' => 'decimal:2',
        'platform_fee_amount' => 'decimal:2',
        'seller_amount' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(SourceGameSeller::class, 'seller_id');
    }

    public function order()
    {
        return $this->belongsTo(\Webkul\Sales\Models\Order::class);
    }

    public function product()
    {
        return $this->belongsTo(\Webkul\Product\Models\Product::class);
    }

    public static function createFromOrder($order)
    {
        foreach ($order->items as $item) {
            $product = $item->product;
            
            if (!$product || $product->type !== 'downloadable') {
                continue;
            }

            $seller = SourceGameSeller::find($product->seller_id);
            
            if (!$seller) {
                continue;
            }

            $orderAmount = $item->total;
            $platformFeePercent = 30.00; // Default 30%
            $platformFeeAmount = $orderAmount * ($platformFeePercent / 100);
            $sellerAmount = $orderAmount - $platformFeeAmount;

            self::create([
                'seller_id' => $seller->id,
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'product_id' => $product->id,
                'order_amount' => $orderAmount,
                'platform_fee_percent' => $platformFeePercent,
                'platform_fee_amount' => $platformFeeAmount,
                'seller_amount' => $sellerAmount,
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Update seller stats
            $seller->increment('total_sales');
            $seller->increment('total_revenue', $sellerAmount);
        }
    }
}
