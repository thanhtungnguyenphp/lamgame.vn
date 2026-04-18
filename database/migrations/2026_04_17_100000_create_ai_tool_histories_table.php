<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_tool_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->string('tool_type', 30)->comment('concept|codegen|debug|test|review|asset');
            $table->string('model_used', 50);
            $table->text('prompt');
            $table->longText('response')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedInteger('tokens_input')->default(0);
            $table->unsignedInteger('tokens_output')->default(0);
            $table->decimal('cost_usd', 10, 6)->default(0);
            $table->unsignedInteger('duration_ms')->default(0);
            $table->enum('status', ['pending', 'streaming', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'tool_type']);
            $table->index(['customer_id', 'created_at']);
            $table->index('status');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_tool_histories');
    }
};
