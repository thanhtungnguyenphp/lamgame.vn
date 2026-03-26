<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 30)->unique();           // free, pro, business
            $table->string('name', 100);                     // Free, Pro, Business
            $table->decimal('price', 8, 2)->default(0);      // 0, 9, 29
            $table->string('currency', 3)->default('USD');
            $table->string('billing_interval', 20)->default('monthly'); // monthly, yearly
            $table->string('paypal_plan_id')->nullable();    // PayPal Plan ID
            $table->json('features');                        // quota/limits JSON
            $table->boolean('is_active')->default(true);
            $table->tinyInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');
            $table->string('paypal_subscription_id')->nullable();
            $table->enum('status', ['active', 'cancelled', 'expired', 'pending'])->default('pending');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('paypal_subscription_id');
        });

        Schema::create('subscription_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_id');
            $table->string('paypal_transaction_id')->nullable();
            $table->decimal('amount', 8, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['completed', 'pending', 'failed', 'refunded'])->default('pending');
            $table->json('paypal_data')->nullable();
            $table->timestamps();

            $table->index('subscription_id');
        });

        Schema::create('subscription_usages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('feature', 50);                   // ai_concept, post_job, apply_job, ticket_register
            $table->unsignedInteger('used')->default(0);
            $table->string('period', 7);                     // 2026-03 (year-month)
            $table->timestamps();

            $table->unique(['user_id', 'feature', 'period']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_usages');
        Schema::dropIfExists('subscription_transactions');
        Schema::dropIfExists('user_subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
