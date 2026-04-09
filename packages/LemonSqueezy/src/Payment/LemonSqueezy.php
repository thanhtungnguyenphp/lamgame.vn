<?php

namespace LemonSqueezy\Payment;

use Webkul\Payment\Payment\Payment;

class LemonSqueezy extends Payment
{
    protected $code = 'lemonsqueezy';

    public function getRedirectUrl()
    {
        // No server-side redirect — checkout handled via JS overlay (Lemon.js)
    }

    public function getImage()
    {
        return asset('images/payment/lemonsqueezy.svg');
    }
}
