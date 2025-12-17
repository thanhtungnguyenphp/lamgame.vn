<?php

namespace App\Listeners;

use Webkul\Product\Models\Product;

class PreserveSellerIdOnProductUpdate
{
    public function handle($event)
    {
        // Get product ID from event
        $productId = is_object($event) ? $event : $event;
        
        if (!$productId) {
            return;
        }

        // Get original product with seller_id
        $originalProduct = Product::find($productId);
        
        if (!$originalProduct || !$originalProduct->seller_id) {
            return;
        }

        // Preserve seller_id in request data
        request()->merge([
            'seller_id' => $originalProduct->seller_id
        ]);
    }
}
