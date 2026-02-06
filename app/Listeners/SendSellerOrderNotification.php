<?php

namespace App\Listeners;

use App\Mail\SellerNewOrderNotification;
use App\Models\SourceGameSeller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendSellerOrderNotification
{
    public function handle($order)
    {
        // Group order items by seller
        $sellerItems = [];
        
        foreach ($order->items as $item) {
            $sellerId = DB::table('products')->where('id', $item->product_id)->value('seller_id');
            
            if ($sellerId) {
                if (!isset($sellerItems[$sellerId])) {
                    $sellerItems[$sellerId] = collect();
                }
                $sellerItems[$sellerId]->push($item);
            }
        }
        
        // Send email to each seller
        foreach ($sellerItems as $sellerId => $items) {
            $seller = SourceGameSeller::find($sellerId);
            
            if ($seller && $seller->contact_email) {
                try {
                    Mail::to($seller->contact_email)
                        ->queue(new SellerNewOrderNotification($order, $seller, $items));
                } catch (\Exception $e) {
                    \Log::error('Failed to send seller order notification', [
                        'seller_id' => $sellerId,
                        'order_id' => $order->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
    }
}
