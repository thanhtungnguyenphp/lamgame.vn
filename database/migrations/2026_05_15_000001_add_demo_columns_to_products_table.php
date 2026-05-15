<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('demo_url')->nullable()->after('url_key');
            $table->string('demo_file_path')->nullable()->after('demo_url');
            $table->boolean('has_demo')->default(false)->after('demo_file_path');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['demo_url', 'demo_file_path', 'has_demo']);
        });
    }
};
