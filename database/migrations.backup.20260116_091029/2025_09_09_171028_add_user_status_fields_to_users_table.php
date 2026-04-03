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
        Schema::table('customers', function (Blueprint $table) {
            $table->enum('forum_role', ['user', 'moderator'])->default('user')->after('is_suspended');
            $table->text('bio')->nullable()->after('forum_role');
            $table->string('avatar_url')->nullable()->after('bio');
            $table->integer('reputation')->default(0)->after('avatar_url');
            $table->timestamp('banned_until')->nullable()->after('reputation');
            $table->text('ban_reason')->nullable()->after('banned_until');
            $table->unsignedInteger('banned_by')->nullable()->after('ban_reason');
            $table->foreign('banned_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['banned_by']);
            $table->dropColumn([
                'forum_role', 'bio', 'avatar_url', 'reputation',
                'banned_until', 'ban_reason', 'banned_by'
            ]);
        });
    }
};
