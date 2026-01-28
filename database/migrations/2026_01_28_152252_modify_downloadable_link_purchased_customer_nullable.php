<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('downloadable_link_purchased', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
        });

        Schema::table('downloadable_link_purchased', function (Blueprint $table) {
            $table->integer('customer_id')->unsigned()->nullable()->change();
            $table->string('customer_email')->nullable()->after('customer_id');
        });
    }

    public function down()
    {
        Schema::table('downloadable_link_purchased', function (Blueprint $table) {
            $table->dropColumn('customer_email');
            $table->integer('customer_id')->unsigned()->nullable(false)->change();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }
};
