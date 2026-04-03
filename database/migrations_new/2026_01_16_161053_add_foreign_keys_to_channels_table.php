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
        Schema::table('channels', function (Blueprint $table) {
            $table->foreign(['base_currency_id'])->references(['id'])->on('currencies')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['default_locale_id'])->references(['id'])->on('locales')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['root_category_id'])->references(['id'])->on('categories')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropForeign('channels_base_currency_id_foreign');
            $table->dropForeign('channels_default_locale_id_foreign');
            $table->dropForeign('channels_root_category_id_foreign');
        });
    }
};
