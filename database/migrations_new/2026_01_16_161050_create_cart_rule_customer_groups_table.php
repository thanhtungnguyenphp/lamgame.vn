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
        Schema::create('cart_rule_customer_groups', function (Blueprint $table) {
            $table->unsignedInteger('cart_rule_id');
            $table->unsignedInteger('customer_group_id')->index('cart_rule_customer_groups_customer_group_id_foreign');

            $table->primary(['cart_rule_id', 'customer_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_rule_customer_groups');
    }
};
