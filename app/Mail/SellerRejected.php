<?php

namespace App\Mail;

use App\Models\SourceGameSeller;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellerRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;
    public $reason;

    public function __construct(SourceGameSeller $seller, $reason)
    {
        $this->seller = $seller;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Thông báo về đơn đăng ký Seller - Làm Game')
                    ->view('emails.seller-rejected');
    }
}
