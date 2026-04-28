<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $product, public $seller, public string $reason) {}

    public function build()
    {
        return $this->subject('Thông báo về sản phẩm - Làm Game')
                    ->view('emails.product-rejected');
    }
}
