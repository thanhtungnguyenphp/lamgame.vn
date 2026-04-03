<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('increment_id')->nullable();
            $table->string('state')->nullable();
            $table->boolean('email_sent')->default(false);
            $table->integer('total_qty')->nullable();
            $table->string('base_currency_code')->nullable();
            $table->string('channel_currency_code')->nullable();
            $table->string('order_currency_code')->nullable();
            $table->decimal('sub_total', 12, 4)->nullable()->default(0);
            $table->decimal('base_sub_total', 12, 4)->nullable()->default(0);
            $table->decimal('grand_total', 12, 4)->nullable()->default(0);
            $table->decimal('base_grand_total', 12, 4)->nullable()->default(0);
            $table->decimal('shipping_amount', 12, 4)->nullable()->default(0);
            $table->decimal('base_shipping_amount', 12, 4)->nullable()->default(0);
            $table->decimal('tax_amount', 12, 4)->nullable()->default(0);
            $table->decimal('base_tax_amount', 12, 4)->nullable()->default(0);
            $table->decimal('discount_amount', 12, 4)->nullable()->default(0);
            $table->decimal('base_discount_amount', 12, 4)->nullable()->default(0);
            $table->decimal('shipping_tax_amount', 12, 4)->default(0);
            $table->decimal('base_shipping_tax_amount', 12, 4)->default(0);
            $table->decimal('sub_total_incl_tax', 12, 4)->default(0);
            $table->decimal('base_sub_total_incl_tax', 12, 4)->default(0);
            $table->decimal('shipping_amount_incl_tax', 12, 4)->default(0);
            $table->decimal('base_shipping_amount_incl_tax', 12, 4)->default(0);
            $table->unsignedInteger('order_id')->nullable()->index('invoices_order_id_foreign');
            $table->string('transaction_id')->nullable();
            $table->integer('reminders')->default(0);
            $table->timestamp('next_reminder_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
