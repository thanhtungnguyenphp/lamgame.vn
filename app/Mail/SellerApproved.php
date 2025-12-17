<?php

namespace App\Mail;

use App\Models\SourceGameSeller;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellerApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;

    public function __construct(SourceGameSeller $seller)
    {
        $this->seller = $seller;
    }

    public function build()
    {
        return $this->subject('🎉 Tài khoản Seller đã được kích hoạt - Làm Game')
                    ->view('emails.seller-approved');
    }
}
