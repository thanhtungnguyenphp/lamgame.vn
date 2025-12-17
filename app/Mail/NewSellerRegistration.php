<?php

namespace App\Mail;

use App\Models\SourceGameSeller;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSellerRegistration extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;

    public function __construct(SourceGameSeller $seller)
    {
        $this->seller = $seller;
    }

    public function build()
    {
        return $this->subject('🔔 Có Seller mới đăng ký - Làm Game')
                    ->view('emails.new-seller-registration');
    }
}
