<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('subscription_plans')->insert([
            [
                'slug'             => 'free',
                'name'             => 'Free',
                'price'            => 0,
                'currency'         => 'USD',
                'billing_interval' => 'monthly',
                'paypal_plan_id'   => null,
                'features'         => json_encode([
                    'ai_concept'        => 3,
                    'ai_generate'       => 0,
                    'ai_debug'          => 0,
                    'ai_test'           => 0,
                    'export_game'       => false,
                    'ticket_register'   => 3,
                    'statistics'        => false,
                    'apply_job'         => 3,
                    'post_job'          => 0,
                    'featured_job'      => 0,
                    'freelancer_contact' => false,
                    'source_discount'   => 0,
                    'sell_commission'   => 0,
                ]),
                'is_active'   => true,
                'sort_order'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'slug'             => 'pro',
                'name'             => 'Pro',
                'price'            => 9.00,
                'currency'         => 'USD',
                'billing_interval' => 'monthly',
                'paypal_plan_id'   => null, // Set sau khi tạo trên PayPal
                'features'         => json_encode([
                    'ai_concept'        => 50,
                    'ai_generate'       => -1, // -1 = unlimited
                    'ai_debug'          => -1,
                    'ai_test'           => 10,
                    'export_game'       => true,
                    'ticket_register'   => 20,
                    'statistics'        => true,
                    'apply_job'         => -1,
                    'post_job'          => 2,
                    'featured_job'      => 0,
                    'freelancer_contact' => false,
                    'source_discount'   => 10,
                    'sell_commission'   => 15,
                ]),
                'is_active'   => true,
                'sort_order'  => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'slug'             => 'business',
                'name'             => 'Business',
                'price'            => 29.00,
                'currency'         => 'USD',
                'billing_interval' => 'monthly',
                'paypal_plan_id'   => null,
                'features'         => json_encode([
                    'ai_concept'        => -1,
                    'ai_generate'       => -1,
                    'ai_debug'          => -1,
                    'ai_test'           => -1,
                    'export_game'       => true,
                    'ticket_register'   => -1,
                    'statistics'        => true,
                    'apply_job'         => -1,
                    'post_job'          => 10,
                    'featured_job'      => 2,
                    'freelancer_contact' => true,
                    'source_discount'   => 20,
                    'sell_commission'   => 10,
                ]),
                'is_active'   => true,
                'sort_order'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('subscription_plans')->whereIn('slug', ['free', 'pro', 'business'])->delete();
    }
};
