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
        Schema::create('source_game_sellers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id')->unique();
            
            // Shop info
            $table->string('shop_name');
            $table->string('shop_slug')->unique();
            $table->text('shop_description')->nullable();
            $table->string('shop_logo')->nullable();
            $table->string('shop_banner')->nullable();
            
            // Contact
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->string('website')->nullable();
            
            // Business
            $table->enum('business_type', ['individual', 'company'])->default('individual');
            $table->string('tax_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_holder')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'active', 'suspended', 'banned'])->default('pending');
            $table->boolean('verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            
            // Stats
            $table->integer('total_products')->default(0);
            $table->integer('total_sales')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            
            $table->timestamps();
            
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->index('status');
            $table->index('shop_slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('source_game_sellers');
    }
};
