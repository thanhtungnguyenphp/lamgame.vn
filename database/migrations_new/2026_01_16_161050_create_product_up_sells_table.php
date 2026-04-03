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
        Schema::create('product_up_sells', function (Blueprint $table) {
            $table->unsignedInteger('parent_id');
            $table->unsignedInteger('child_id')->index('product_up_sells_child_id_foreign');

            $table->unique(['parent_id', 'child_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_up_sells');
    }
};
