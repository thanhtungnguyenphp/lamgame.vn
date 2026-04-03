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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('menu_id')->index('menu_items_menu_id_foreign');
            $table->unsignedBigInteger('parent_id')->nullable()->index('menu_items_parent_id_foreign');
            $table->string('title');
            $table->string('url');
            $table->integer('sort_order')->default(0);
            $table->string('target')->default('_self');
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
