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
        Schema::create('visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('method')->nullable();
            $table->mediumText('request')->nullable();
            $table->mediumText('url')->nullable();
            $table->mediumText('referer')->nullable();
            $table->text('languages')->nullable();
            $table->text('useragent')->nullable();
            $table->text('headers')->nullable();
            $table->text('device')->nullable();
            $table->text('platform')->nullable();
            $table->text('browser')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('visitable_type')->nullable();
            $table->unsignedBigInteger('visitable_id')->nullable();
            $table->string('visitor_type')->nullable();
            $table->unsignedBigInteger('visitor_id')->nullable();
            $table->unsignedInteger('channel_id')->nullable()->index('visits_channel_id_foreign');
            $table->timestamps();

            $table->index(['visitable_type', 'visitable_id']);
            $table->index(['visitor_type', 'visitor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
