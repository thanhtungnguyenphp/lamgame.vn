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
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender', 50)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('password')->nullable();
            $table->string('api_token', 80)->nullable()->unique();
            $table->unsignedInteger('customer_group_id')->nullable()->index('customers_customer_group_id_foreign');
            $table->unsignedInteger('channel_id')->nullable()->index('customers_channel_id_foreign');
            $table->boolean('subscribed_to_news_letter')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->unsignedTinyInteger('is_suspended')->default(0);
            $table->enum('forum_role', ['user', 'moderator'])->default('user');
            $table->text('bio')->nullable();
            $table->string('avatar_url')->nullable();
            $table->integer('reputation')->default(0);
            $table->timestamp('banned_until')->nullable();
            $table->text('ban_reason')->nullable();
            $table->unsignedInteger('banned_by')->nullable()->index('customers_banned_by_foreign');
            $table->string('token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
