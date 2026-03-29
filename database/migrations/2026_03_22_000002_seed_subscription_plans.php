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
                    'ai_concept'      => 3,
                    'ai_generate'     => 0,
                    'ai_debug'        => 0,
                    'ai_test'         => 0,
                    'ai_asset'        => 0,
                    'ai_code_review'  => 0,
                    'ai_model'        => 'gpt-4o-mini',
                    'export_project'  => false,
                    'chat_history'    => 7,       // days
                    'priority_queue'  => false,
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
                    'ai_concept'      => 100,
                    'ai_generate'     => 50,
                    'ai_debug'        => 30,
                    'ai_test'         => 20,
                    'ai_asset'        => 0,
                    'ai_code_review'  => 10,
                    'ai_model'        => 'gpt-4o',
                    'export_project'  => true,
                    'chat_history'    => 30,
                    'priority_queue'  => true,
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
                    'ai_concept'      => -1,     // -1 = unlimited
                    'ai_generate'     => -1,
                    'ai_debug'        => -1,
                    'ai_test'         => -1,
                    'ai_asset'        => 100,
                    'ai_code_review'  => -1,
                    'ai_model'        => 'gpt-4o,claude',
                    'export_project'  => true,
                    'chat_history'    => -1,
                    'priority_queue'  => true,
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
