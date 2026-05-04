<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hire_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 255);
            $table->string('phone', 20)->nullable();
            $table->string('company', 255)->nullable();
            $table->enum('project_type', ['game', 'web', 'app', 'ai', 'other'])->default('game');
            $table->string('budget_range', 50)->nullable();
            $table->text('description');
            $table->enum('status', ['new', 'contacted', 'quoted', 'closed'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hire_requests');
    }
};
