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
        Schema::create('cart_rule_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale');
            $table->text('label')->nullable();
            $table->unsignedInteger('cart_rule_id');

            $table->unique(['cart_rule_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_rule_translations');
    }
};
