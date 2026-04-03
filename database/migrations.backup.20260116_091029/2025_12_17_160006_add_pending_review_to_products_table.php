<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('pending_review')->default(false)->after('seller_id');
            $table->text('rejection_reason')->nullable()->after('pending_review');
            $table->index('pending_review');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['pending_review']);
            $table->dropColumn(['pending_review', 'rejection_reason']);
        });
    }
};
