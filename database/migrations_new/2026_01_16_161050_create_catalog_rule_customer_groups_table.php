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
        Schema::create('catalog_rule_customer_groups', function (Blueprint $table) {
            $table->unsignedInteger('catalog_rule_id');
            $table->unsignedInteger('customer_group_id')->index('catalog_rule_customer_groups_customer_group_id_foreign');

            $table->primary(['catalog_rule_id', 'customer_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_rule_customer_groups');
    }
};
