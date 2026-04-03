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
        Schema::create('attribute_group_mappings', function (Blueprint $table) {
            $table->unsignedInteger('attribute_id');
            $table->unsignedInteger('attribute_group_id')->index('attribute_group_mappings_attribute_group_id_foreign');
            $table->integer('position')->nullable();

            $table->primary(['attribute_id', 'attribute_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_group_mappings');
    }
};
