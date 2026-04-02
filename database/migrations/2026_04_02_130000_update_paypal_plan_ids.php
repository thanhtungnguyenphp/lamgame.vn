<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('subscription_plans')->where('slug', 'pro')
            ->update(['paypal_plan_id' => 'P-06S19361AU003084UNHHA4XY']);

        DB::table('subscription_plans')->where('slug', 'business')
            ->update(['paypal_plan_id' => 'P-6GY50545CA5254507NHHA42I']);
    }

    public function down(): void
    {
        DB::table('subscription_plans')
            ->whereIn('slug', ['pro', 'business'])
            ->update(['paypal_plan_id' => null]);
    }
};
