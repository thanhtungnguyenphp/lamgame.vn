<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_api_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('admin_id');
            $table->string('action', 20);       // publish, update, delete
            $table->string('slug', 500);
            $table->unsignedBigInteger('blog_id')->nullable();
            $table->string('ip', 45)->nullable();
            $table->json('changes')->nullable(); // updated fields for update action
            $table->timestamp('created_at')->useCurrent();

            $table->index(['admin_id', 'created_at']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_api_logs');
    }
};
