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
        Schema::table('marketing_campaigns', function (Blueprint $table) {
            $table->foreign(['channel_id'])->references(['id'])->on('channels')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['customer_group_id'])->references(['id'])->on('customer_groups')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['marketing_event_id'])->references(['id'])->on('marketing_events')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['marketing_template_id'])->references(['id'])->on('marketing_templates')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_campaigns', function (Blueprint $table) {
            $table->dropForeign('marketing_campaigns_channel_id_foreign');
            $table->dropForeign('marketing_campaigns_customer_group_id_foreign');
            $table->dropForeign('marketing_campaigns_marketing_event_id_foreign');
            $table->dropForeign('marketing_campaigns_marketing_template_id_foreign');
        });
    }
};
