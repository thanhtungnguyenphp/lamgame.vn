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
        Schema::create('shipments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status')->nullable();
            $table->integer('total_qty')->nullable();
            $table->integer('total_weight')->nullable();
            $table->string('carrier_code')->nullable();
            $table->string('carrier_title')->nullable();
            $table->text('track_number')->nullable();
            $table->boolean('email_sent')->default(false);
            $table->unsignedInteger('customer_id')->nullable();
            $table->string('customer_type')->nullable();
            $table->unsignedInteger('order_id')->index('shipments_order_id_foreign');
            $table->unsignedInteger('order_address_id')->nullable();
            $table->unsignedInteger('inventory_source_id')->nullable()->index('shipments_inventory_source_id_foreign');
            $table->string('inventory_source_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
