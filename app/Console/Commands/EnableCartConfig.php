<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EnableCartConfig extends Command
{
    protected $signature = 'cart:enable';
    protected $description = 'Enable cart and buy now button configuration';

    public function handle()
    {
        $configs = [
            ['code' => 'sales.checkout.shopping_cart.cart_page', 'value' => '1'],
            ['code' => 'catalog.products.storefront.buy_now_button_display', 'value' => '1'],
        ];

        foreach ($configs as $config) {
            DB::table('core_config')->updateOrInsert(
                ['code' => $config['code'], 'channel_code' => 'default'],
                ['value' => $config['value']]
            );
            $this->info("✅ Set {$config['code']} = {$config['value']}");
        }

        $this->info('🛒 Cart configuration enabled!');
    }
}
