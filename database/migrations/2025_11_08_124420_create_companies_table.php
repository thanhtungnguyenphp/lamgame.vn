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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->integer('employee_count')->nullable();
            $table->year('founded_year')->nullable();
            $table->string('industry')->nullable();
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('created_by_admin_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Note: Foreign key will be added in separate migration to avoid circular dependency
            $table->index('status');
            $table->index('created_by_admin_id');
            $table->index('email');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
