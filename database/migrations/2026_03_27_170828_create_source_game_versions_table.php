<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('source_game_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id');
            $table->string('version', 50);
            $table->text('changelog')->nullable();
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->unsignedInteger('downloads')->default(0);
            $table->unsignedInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->index('product_id');
            $table->unique(['product_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('source_game_versions');
    }
};
