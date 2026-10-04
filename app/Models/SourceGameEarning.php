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
            $platformFeePercent = (float) config('source-game-revenue.platform_fee_percent', 30.00);
            $platformFeeAmount = $orderAmount * ($platformFeePercent / 100);
            $sellerAmount = $orderAmount - $platformFeeAmount;

            // Idempotent + atomic: khóa theo order_item_id để tránh tạo earning trùng
            // (unique constraint sge_order_item_id_unique bảo vệ ở tầng DB).
            \Illuminate\Support\Facades\DB::transaction(function () use (
                $order, $item, $product, $seller,
                $orderAmount, $platformFeePercent, $platformFeeAmount, $sellerAmount
            ) {
                $earning = self::firstOrCreate(
                    ['order_item_id' => $item->id],
                    [
                        'seller_id'            => $seller->id,
                        'order_id'             => $order->id,
                        'product_id'           => $product->id,
                        'order_amount'         => $orderAmount,
                        'platform_fee_percent' => $platformFeePercent,
                        'platform_fee_amount'  => $platformFeeAmount,
                        'seller_amount'        => $sellerAmount,
                        'status'               => 'completed',
                        'completed_at'         => now(),
                    ]
                );

                // Chỉ cập nhật thống kê seller khi earning THỰC SỰ vừa được tạo
                if ($earning->wasRecentlyCreated) {
                    $seller->increment('total_sales');
                    $seller->increment('total_revenue', $sellerAmount);
                }
            });
        }
    }
}
