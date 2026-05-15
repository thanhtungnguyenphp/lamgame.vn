<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('license_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('max_projects')->default(1);
            $table->boolean('allows_resale')->default(false);
            $table->boolean('allows_modification')->default(true);
            $table->timestamps();
        });

        Schema::create('product_licenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreignId('license_type_id')->constrained('license_types');
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['product_id', 'license_type_id']);
        });

        Schema::create('license_keys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->foreignId('license_type_id')->constrained('license_types');
            $table->string('key', 64)->unique();
            $table->unsignedBigInteger('customer_id');
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedBigInteger('transferred_to')->nullable();
            $table->timestamps();
            $table->index(['customer_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('license_keys');
        Schema::dropIfExists('product_licenses');
        Schema::dropIfExists('license_types');
    }
};
