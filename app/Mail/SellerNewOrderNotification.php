<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Webkul\Sales\Models\Order;

class SellerNewOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public $seller,
        public $sellerItems
    ) {}

    public function build()
    {
        $sellerTotal = $this->sellerItems->sum(fn($item) => $item->total);
        
        return $this->subject('🎉 Đơn hàng mới #' . $this->order->increment_id . ' - LamGame.vn')
            ->view('emails.orders.seller-notification')
            ->with([
                'order' => $this->order,
                'seller' => $this->seller,
                'sellerItems' => $this->sellerItems,
                'sellerTotal' => $sellerTotal,
            ]);
    }
}
