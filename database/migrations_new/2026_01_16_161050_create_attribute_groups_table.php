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
        Schema::create('attribute_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->unsignedInteger('attribute_family_id');
            $table->string('name');
            $table->integer('column')->default(1);
            $table->integer('position');
            $table->boolean('is_user_defined')->default(true);

            $table->unique(['attribute_family_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_groups');
    }
};
