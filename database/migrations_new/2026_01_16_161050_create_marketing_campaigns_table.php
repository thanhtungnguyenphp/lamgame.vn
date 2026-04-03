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
        Schema::create('marketing_campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('subject');
            $table->boolean('status')->default(false);
            $table->string('type');
            $table->string('mail_to');
            $table->string('spooling')->nullable();
            $table->unsignedInteger('channel_id')->nullable()->index('marketing_campaigns_channel_id_foreign');
            $table->unsignedInteger('customer_group_id')->nullable()->index('marketing_campaigns_customer_group_id_foreign');
            $table->unsignedInteger('marketing_template_id')->nullable()->index('marketing_campaigns_marketing_template_id_foreign');
            $table->unsignedInteger('marketing_event_id')->nullable()->index('marketing_campaigns_marketing_event_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_campaigns');
    }
};
