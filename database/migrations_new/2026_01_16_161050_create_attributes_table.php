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
        Schema::create('attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('admin_name');
            $table->string('type');
            $table->string('swatch_type')->nullable();
            $table->string('validation')->nullable();
            $table->string('regex')->nullable();
            $table->integer('position')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_unique')->default(false);
            $table->boolean('is_filterable')->default(false);
            $table->boolean('is_comparable')->default(false);
            $table->boolean('is_configurable')->default(false);
            $table->boolean('is_user_defined')->default(true);
            $table->boolean('is_visible_on_front')->default(false);
            $table->boolean('value_per_locale')->default(false);
            $table->boolean('value_per_channel')->default(false);
            $table->integer('default_value')->nullable();
            $table->boolean('enable_wysiwyg')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
