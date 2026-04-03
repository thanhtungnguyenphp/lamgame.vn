<?php

return [
    'sales' => [
        'payment_methods' => [
            'lemonsqueezy' => [
                'title'       => 'Lemon Squeezy',
                'description' => 'Thanh toán quốc tế qua Visa, Mastercard, PayPal, Apple Pay',
                'info'        => 'Lemon Squeezy — cổng thanh toán quốc tế cho sản phẩm số. Phí: 5% + $0.50/giao dịch. Hỗ trợ 16+ phương thức thanh toán.',

                'sort'    => 5,
                'active'  => true,
                'sandbox' => true,

                'api_key'            => '',
                'store_id'           => '',
                'signing_secret'     => '',
                'default_variant_id' => '',
                'usd_rate'           => 25000,
            ],
        ],
    ],
];
