<?php

namespace LemonSqueezy\Payment;

use Webkul\Payment\Payment\Payment;

class LemonSqueezy extends Payment
{
    protected $code = 'lemonsqueezy';

    public function getRedirectUrl()
    {
        return route('lemonsqueezy.checkout.create');
    }

    public function getImage()
    {
        return bagisto_asset('images/lemonsqueezy.svg', 'shop');
    }
}
