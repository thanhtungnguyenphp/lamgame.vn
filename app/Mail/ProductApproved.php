<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $product, public $seller) {}

    public function build()
    {
        return $this->subject('✅ Sản phẩm đã được duyệt - Làm Game')
                    ->view('emails.product-approved');
    }
}
