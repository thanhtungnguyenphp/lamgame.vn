<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('m7_matches', function (Blueprint $table) {
            $table->id();
            $table->string('round');           // swiss-round-1, quarterfinal, semifinal, final
            $table->string('team_a');
            $table->string('team_b');
            $table->string('winner')->nullable();
            $table->dateTime('match_at');
            $table->tinyInteger('status')->default(0); // 0=upcoming, 1=live, 2=finished
            $table->timestamps();
        });

        Schema::create('m7_predictions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->nullable();   // guest fallback
            $table->unsignedBigInteger('match_id')->nullable();
            $table->string('type');             // match, champion, mvp
            $table->string('pick');             // team name or player name
            $table->boolean('correct')->nullable();
            $table->integer('points')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['session_id', 'type']);
            $table->unique(['user_id', 'match_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m7_predictions');
        Schema::dropIfExists('m7_matches');
    }
};
