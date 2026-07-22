<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add job context to existing conversations
        Schema::table('forum_conversations', function (Blueprint $table) {
            $table->unsignedBigInteger('job_application_id')->nullable()->after('participant_2');
            $table->string('context_type', 20)->default('forum')->after('job_application_id'); // 'forum' or 'job'
        });
    }

    public function down(): void
    {
        Schema::table('forum_conversations', function (Blueprint $table) {
            $table->dropColumn(['job_application_id', 'context_type']);
        });
    }
};
